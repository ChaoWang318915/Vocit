<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Coupon;
use App\Models\Impression;
use App\Models\Integration;
use App\Models\Post;
use App\Models\User;
use App\Notifications\CouponDistributedNotification;
use App\Notifications\CouponNotification;
use App\Service\GetPostsService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as IntImage;
use App\Notifications\FacebookAutoPost;
use Spatie\Image\Image;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends BaseController
{
    protected $service;
    public function __construct(GetPostsService $getPosts)
    {
        $this->service = $getPosts;
    }

    function getPosts(Request $request)
    {
        $request = collect($request->all());
        if ($request->get('is_business')) {
            $request->put('is_business', true);
        }

        $posts = $this->service->getRequests($request);

        return $this->getResponse($posts, 'Posts available');
    }

    
    function getUserPosts()
    {                  
        $posts = Post::where('user_id',auth()->user()->id)->get();
        $result = array();
        foreach($posts as $post){
            $temp['lg_url'] = $post->attachments[0]->lg_url;
            $result[] = $temp;            
        }         
        return $this->getResponse($result, 'Posts available');
    }

    function getPost($postId)
    {
        $post = Post::whereId($postId)->where('is_draft', 0)->first();
        if (!$post) {
            throw new NotFoundHttpException($this->getMessage('not_found', 'Post'));
        }

        return $this->getResponse($post);
    }

    function create(Request $request)
    {

        $request->validate([
            // 'images.*' => 'image|mimes:jpeg,jpg,png,gif',
            'content' => 'string',
            // 'parent_post' => 'numeric',
            // 'is_request' => 'numeric'
        ]);

        if ($request->post_type === 1) $request->validate([
            'images.*' => 'image|mimes:jpeg,jpg,png,gif',
        ]);

        $data['user_id'] = auth()->id();
        $data['is_image'] = 0;
        $data['is_draft'] = 0;
        $data['is_request'] = 1;
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
        // if($post == 'template'){
        //     $data['user_id'] = auth()->id();
        //     $data['is_image'] = 1;
        //     $data['is_draft'] = 1;
        //     $data['is_request'] = 1;
        //     $data['business_id'] = auth()->user()->active_business->id;
        //     $data['request_type'] = $request->request_type;
        //     $post = Post::create($data);

        //     $image = IntImage::make('storage/template.png');
        //     $post->addMedia($image)
        //     ->sanitizingFileName(function ($fileName) {
        //         return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
        //     })
        //     ->toMediaCollection('Posts');

        //     $post->addMediaFromUrl(customMaskImage($post))
        //         ->sanitizingFileName(function ($fileName) {
        //             return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
        //         })
        //         ->toMediaCollection('Posts');

        //     return $this->getResponse($post, 'Your post has been created');

        // }
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
        // return $this->getResponse($post, ['data' => $image]);
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
        //        $request->validate([
        //            'file' => 'image|mimes:jpeg,jpg,png,gif',
        //        ]);
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
        $data['parent_post'] = $request->get('parent_id');
        $data['business_id'] = $request->get('business_id');;
        $data['content'] = $request->get('content');
        if ($hasImages) {
            $data['is_image'] = 1;
        } else {
            $data['is_image'] = 0;
        }

        $data['is_draft'] = 0;
        $data['is_request'] = 0;
        $post = Post::create($data);
        // dd($hasImages);die;
        if ($hasImages) {
            foreach ($images as $image) {
                $post->addMedia($image)
                    ->sanitizingFileName(function ($fileName) {
                        return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                    })
                    ->toMediaCollection('Posts');
            }
        }

        if ($post && $hasImages) {
            $this->createCoupon($data['business_id'], $originPost, $post->id);
            // //facebook autopost making image step
            // $parent_post = Post::find($request->parent_id);
            // //step 1 - getting top bg 
            // $top_bg = IntImage::make('storage/top-bg.jpg');
            // //step 2 - get the width of the parent post image
            // $post_image = IntImage::make($parent_post->lg_url)->width();

        }

        $exchangePost = Post::find($post->id);
        $post = Post::find($originPost);

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
        $post = Post::find($parentPost);
        // $post->notify(new FacebookAutoPost);
        return $this->getResponse($post, 'Successfully Posted');
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

    function deletePost($postId)
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
