<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserPostsCollection;
use App\Models\Business;
use App\Models\Coupon;
use App\Models\Impression;
use App\Models\Integration;
use App\Models\Post;
use App\Models\User;
use App\Notifications\CouponDistributedNotification;
use App\Notifications\CouponNotification;
use App\Notifications\FacebookAutoPost;
use App\Service\GetPostsService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as IntImage;
use Spatie\Image\Image;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;

class PostController extends BaseController
{
    protected $service;
    public function __construct(GetPostsService $getPosts)
    {
        $this->service = $getPosts;
    }

    function getPosts(Request $request)
    {
        
        DB::table('posts')->whereNull('business_id')->delete();
        $request = collect($request->all());
        if ($request->get('is_business')) {
            $request->put('is_business', true);
        }
         
        $posts = $this->service->getRequests($request);

        return $this->getResponse($posts, 'Posts available');
    }

    
    function getUserPosts()
    {                  
        return new UserPostsCollection(auth()->user()->posts()->paginate());
    }

    function getPost($postId)
    {
        DB::table('posts')->whereNull('business_id')->delete();
        $post = Post::whereId($postId)->where('is_draft', 0)->first();
        if (!$post) {
            throw new NotFoundHttpException($this->getMessage('not_found', 'Post'));
        }        
         
        return $this->getResponse($post);
    }

    function create(Request $request)
    {

        $request->validate([
            'content' => 'string',
        ]);

        if ($request->post_type === 1) $request->validate([
            'images.*' => 'image|mimes:jpeg,jpg,png,gif',
        ]);

        $data['user_id'] = auth()->id();
        $data['is_image'] = 0;
        $data['is_draft'] = 0;
        $data['is_request'] = 1;
        $data['is_auto'] = $request->post['is_auto'];
        $data['business_id'] = auth()->user()->active_business->id;
        $data['content'] = $request->post['content'];
        $data['request_type'] = $request->post['request_type'];
        $data['short_description'] = $request->post['short_description'];
        $data['coupon'] = $request->post['coupon'];
        $data['is_image'] = 1;

        $hasImages = $request->hasFile('images');
        $images = $request->file('images');

        if (!$hasImages) {
            $images = (string) IntImage::make(url('storage/template.png'))->encode('data-url');
        }

        $post = Post::create($data);

        if (!$post) {
            throw new BadRequestHttpException($this->getMessage('common_error'));
        }

        if ($post->request_type == 2) {
            $post->addMediaFromBase64($images)
                ->sanitizingFileName(function ($fileName = 'tempfile.png') {
                    return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                })
                ->toMediaCollection('Posts');

            $post->addMediaFromUrl(customMaskImage($post))
                ->sanitizingFileName(function ($fileName) {
                    return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                })
                ->toMediaCollection('Posts');
        } else {
            $post->addMedia($images)
                ->sanitizingFileName(function ($fileName) {
                    return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                })
                ->toMediaCollection('Posts');
        }

        $integrations = Integration::where('business_id', $post->business_id)->where('app_name', 'zapier')->get();
        if (is_countable($integrations) && count($integrations) > 0) {
            foreach ($integrations as $integration) {
                $hookId = $integration->key;
                if ($hookId) {
                    $this->dispatchIntegrationPayload($post, $hookId);
                }
            }
        }      
        return $this->getResponse($request, 'Your post has been created');
    }

    function updatePost(Request $request, $postId)
    {
        $post = Post::find($postId);
        if (!$post) {

            throw new NotFoundHttpException('Post not found');
        }

        $data = $request->get('post');
        $data['user_id'] = auth()->id();
        $data['is_draft'] =  0;
        if (!$data['coupon']) {
            $data['coupon'] = Str::random(4);
        }

        $post->fill($data);
        $isSaved = $post->save();

        if ($post->request_type == 2) {
            $post->addMediaFromUrl(customMaskImage($post))
                ->sanitizingFileName(function ($fileName) {
                    return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                })
                ->toMediaCollection('Posts');
        }

        if (!$isSaved) {
            throw new BadRequestHttpException('Something went wrong try again later');
        }

        $isEditing = Arr::get($data, 'is_editing');
        if (!$isEditing) {
            $business = auth()->user()->active_business;
            $business->post_limit = $business->post_limit - 1;
            $business->save();
        }

        return $this->getResponse($post, 'Your post has been saved');
    }

    function addCard(Request $request, Post $post)
    {

        if ($request->has('overlay_image')) {
            $fileName = Str::random() . '-ad-overlay-' . time();
            $toPath = "temp/{$fileName}";

            customOverlay($post, uploadBase64($request->overlay_image, $toPath));
        }
        return $this->getResponse($post, 'Your post has been updated');
    }

    function createDraftImagePost(Request $request)
    {
        $hasImage = $request->hasFile('file');
        $image = $request->file('file');

        $postId = $request->get('post_id');

        if ($postId) {
            $post = Post::find($postId);
        } else {
            $data['user_id'] = auth()->id();
            $data['is_image'] = 1;
            $data['is_draft'] = 1;
            $data['is_request'] = 1;
            $data['business_id'] = auth()->user()->active_business->id;
            $data['request_type'] = $request->request_type;
            $post = Post::create($data);
        }

        if ($hasImage) {
            if ($postId) {
                $post->clearMediaCollection('Posts');
            }

            $post->addMedia($image)
                ->sanitizingFileName(function ($fileName) {
                    return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                })
                ->toMediaCollection('Posts');
        }

        return $this->getResponse($post, 'Draft post has been created');
    }

    function exchangePost(Request $request)
    {
        
        $hasImages = $request->hasFile('images');
        $images = $request->file('images');
        $originPost = $request->get('origin_post');

        $parentPost = $request->get('parent_id');
        $data['user_id'] = auth()->id();
        $data['parent_post'] = $parentPost;        
        $data['content'] = $request->get('content');
        if ($hasImages) {
            $data['is_image'] = 1;
        } else {
            $data['is_image'] = 0;
        }
        $parent_post = Post::find($parentPost);
        $data['is_draft'] = 0;
        $data['is_request'] = 0;
        $data['is_auto'] =  1;
        $data['business_id'] = $request->business_id;
        // $data['business_id'] = auth()->user()->active_business->id;
        // $data['short_description'] = $parent_post->parent_short_description;        
        $post = Post::create($data);
        if ($hasImages) {     
            
            foreach ($images as $image) {
                $post->addMedia($image)
                    ->sanitizingFileName(function ($fileName) {
                        return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                    })
                    ->toMediaCollection('Posts');
            }        
            $post->save();

            $fileName = $post->media()->first()->file_name;             
           
            /**** 
                Start to Make New image with business title and logo
            *****/
            //$fileName = $images[0]->getClientOriginalName();
            $s3file = IntImage::make($post->attachments[0]->lg_url);                      
            //step 1 - getting top bg 
            $top_bg = IntImage::make('storage/top-bg.jpg');   
            $top_mask = IntImage::make('storage/mask.png');    
            //step 2 - get the width of the parent post image and resize the top image
            $width = $s3file->width();            
            $top_bg->resize($width,150);
            //step 3 - insert logo ,business name
            $logo_image = IntImage::make($parent_post->business->logo)->resize(150,150)->mask($top_mask);
            $business_name = $parent_post->business->name;
            $top_bg->insert($logo_image,'left',60,3);        
            $fonts = ['anydore', 'gladifilthefte', 'momentus', 'roboto-regular'];
            $top_bg->text($business_name, 250, 90, function ($font) use ($fonts) {     
                $index = rand(0, 3);
                $font->file(public_path("fonts/{$fonts[0]}.ttf"));
                $font->size(30);
            });           
            $merge_image = IntImage::canvas($width,$s3file->height()+150);
            $merge_image->insert($top_bg,'top',0, 0);
            $merge_image->insert($s3file,'top',0,150);
            $merge_image->save('storage/facebook/'.$fileName);//->encode('data-url');
            // End of new image maker

            /**** 
                Save new made image on S3 storage
            *****/
            $contents = Storage::disk('public')->get('facebook/'.$fileName);            
            $path = 'facebook/' . time() . '/' . $fileName;
            Storage::disk('s3')->put($path, $contents);
            $url = Storage::disk('s3')->url($path);
            // // End of saving
           
            $post->facebook_url = $url;
            $post->save();

        }else {
            $post->business_id = $request->get('business_id');
            $post->facebook_url = $post->lg_url;
            $post->save();
            $this->createIntegration($post->id, $originPost);
            $post = Post::find($parentPost);
        }

        if ($hasImages) {
            return response()->json([
                'post' => $post,
                'fb_image' => $post->facebook_url,
            ]);
        }else {
            return $this->getResponse($post, 'Successfully Posted');
        }        
    }

    function completeExchange(Request $request) {
         
        $postId = $request->get('postId');
        $originPost = $request->get('origin_post');
        $parentPost = $request->get('parent_id');
        $businessId = $request->get('business_id');
        $hasImages = $request->hasFile('images');
        $images = $request->file('images');                

        $post = Post::find($postId);
        $post->business_id = $businessId;
        // if ($hasImages) {
        //     foreach ($images as $image) {
        //         $post->addMedia($image)
        //             ->sanitizingFileName(function ($fileName) {
        //                 return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
        //             })
        //             ->toMediaCollection('Posts');
        //     }
        // }
        // $post->facebook_url = '';
        $post->save();
        
        $this->createCoupon($businessId, $postId, $postId);
        $this->createIntegration($postId, $originPost);

        $post = Post::find($parentPost);
        
        return $this->getResponse($post, 'Successfully Posted');
    }

    protected function createIntegration($exchangeId, $originId) {
        $exchangePost = Post::find($exchangeId);
        $post = Post::find($originId);

        $integrations = Integration::where('business_id', $post->business_id)->where('app_name', 'zapier')->get();
        $statusCodes = '';
        if (is_countable($integrations) && count($integrations) > 0) {
            foreach ($integrations as $integration) {
                $hookId = $integration->key;
                if ($hookId) {
                    $statusCodes = $this->dispatchIntegrationPayload($exchangePost, $hookId);
                }
            }
        }
    }

    protected function createCoupon($businessId, $originPost, $exchangeId)
    {
        $post = Post::find($originPost);
        $business = Business::find($businessId);
        $existingCouponCount = Coupon::where('user_id', auth()->id())->where('post_id', $post->id)->count();

        if ($existingCouponCount > 0) {
            return false;
        }

        $coupon = $post->coupon ? ($post->coupon . $this->getCouponCode(4)) : $this->getCouponCode(8);
        $data = [
            'user_id' => auth()->id(),
            'coupon' => $coupon,
            'business_id' => $business->id,
            'exchange_id' => $exchangeId,
            'post_id' => $post->id
        ];

        $coupon = Coupon::create($data);
        $user = new User();
        $user->email = auth()->user()->email;
        $user->notify(new CouponNotification(auth()->user()->first_name, $business, $coupon, $post));
        $this->sendCouponNotificationToAdmin($businessId, $post->id, $coupon, $exchangeId);
    }

    protected function getCouponCode($size)
    {
        $string = strtoupper(substr(md5(time() . rand(10000, 99999)), 0, $size));
        return $string;
    }

    function coupons(Request $request)
    {
        $search = $request->get('search');
        $business = auth()->user()->active_business;

        $postId = $request->get('post');

        $coupons = Coupon::with(['business', 'user', 'exchange', 'post'])->whereHas('business', function ($b) use ($business) {
            $b->where('subdomain', $business->subdomain);
        });

        if ($postId) {
            $coupons = $coupons->where('post_id', $postId);
        }

        if ($search) {
            $coupons = $coupons->where('coupon', 'LIKE', ('%' . $search . '%'));
        }

        $coupons = $coupons->get();

        return $this->getResponse($coupons);
    }

    function redeemCoupon($couponId)
    {
        $coupon = Coupon::find($couponId);
        if (!$coupon) {
            throw new NotFoundHttpException('Coupon not found');
        }

        $coupon->is_redeemed = 1;
        $coupon->save();

        $businessId = $coupon->business_id;
        $coupons = Coupon::with(['business', 'user', 'exchange'])->where('business_id', '=', $businessId)->where('is_redeemed', 0)->get();

        return $this->getResponse($coupons, 'Coupon has been redeemed');
    }

    function sendCouponNotificationToAdmin($businessId, $postId, $coupon, $exchangeId)
    {
        $business = Business::with(['members.user'])->whereId($businessId)->first();
        $members = $business->members->pluck('user')->unique();
        foreach ($members as $member) {
            $user = new User();
            $user->email = $member->email;
            $user->notify(new CouponDistributedNotification($coupon->coupon, $postId, $coupon->user->name, $exchangeId));
        }
    }

    function deletePost(Request $request,$postId)
    {
         
        $post = Post::find($postId);
        if (!$post) {
            throw new NotFoundHttpException('Post not found');
        }

        if ($post->delete()) {
            Post::where('parent_post', $postId)->delete();
            Coupon::where('post_id', $postId)->delete();
        }

        return $this->getResponse($post, 'Post has been deleted');
    }

    function addImpression(Request $request)
    {
        $userId = auth()->check() ? auth()->id() : '';
        $action = $request->get('action');
        $postId = $request->get('post_id');

        $post = Post::find($postId);
        if (!$post) {
            throw new BadRequestHttpException('Post not found');
        }
        $likeExists = false;
        if ($userId) {
            $likeExists = Impression::where('user_id', $userId)
                ->where('action', 'like')
                ->where('post_id', $postId)
                ->exists();
        }


        $impression = '';
        if ($action == 'like') {
            if ($likeExists) {
                $impression = Impression::where('user_id', $userId)
                    ->where('action', 'like')
                    ->where('post_id', $postId)
                    ->delete();
            } else {
                $impression = Impression::create([
                    'post_id' => $postId,
                    'user_id' => $userId,
                    'action' => $action
                ]);
            }
        } else {
            $impression = Impression::create([
                'post_id' => $postId,
                'user_id' => $userId,
                'action' => $action
            ]);
        }

        if (!$impression) {
            throw new BadRequestHttpException('Something went wrong, try again later');
        }

        return $this->getResponse($impression, 'Impression added');
    }

    function sharePost($postId)
    {
    }

    private function dispatchIntegrationPayload($post, $hookUrl)
    {
        $post_json = json_encode($post);
        $ch = @curl_init();
        @curl_setopt($ch, CURLOPT_POST, true);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
        @curl_setopt($ch, CURLOPT_URL, $hookUrl);
        @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = @curl_exec($ch);
        $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errors = curl_error($ch);
        @curl_close($ch);
    }
}
