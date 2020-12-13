<?php

namespace App\Service;

use App\Models\Post;
use App\Models\Business;
use Carbon\Carbon;
use Illuminate\Support\Arr;

/**
 * Class GetPostsService
 *
 * @package \App\Service
 */
class GetPostsService
{
    function getRequests($data) {
        $type = Arr::get($data, 'type');
        $value = Arr::get($data, 'value');
        $business = Arr::get($data, 'business');
        $isBusiness = Arr::get($data, 'is_business');
        $filter = Arr::get($data ,'filter');
        $search = Arr::get($data ,'search');
        $parent = Arr::get($data ,'parent');

        if(Post::whereDate('expires_in','<',Carbon::now())->where('is_auto',1)->get()->count() != 0){ 
            $posts = Post::whereDate('expires_in','<',Carbon::now())->where('is_auto',1)->get();
            foreach($posts as $post){                       
                $business = Business::find($post->business_id);    
                if($business->post_limit == 0) $business->post_limit = 0;
                else $business->post_limit = $business->post_limit -1 ;
                $business->save();
                $post->created_at = Carbon::now();
                $post->expires_in = Carbon::now()->addDays(30);         
                $post->save();   
            }
        }
        $posts = Post::where('is_image', 1)->where('is_draft', 0);
        // $posts = Post::where('is_image', 1)->where('is_draft', 0);

        if($type == 'requests'){
            $posts = $posts->where('is_request', 1);

            if($filter == 'archived'){
                $posts = $posts->whereDate('expires_in', '<', Carbon::now());
            }
            else{
                $posts = $posts->whereDate('expires_in', '>=', Carbon::now());
            }

        }
        else if($type == 'exchanges'){
            $posts = $posts->where('is_request', 0);

            if($filter == 'redeemed') {
                $posts = $posts->whereHas('receivedCoupon', function($w) use($search){
                    $w->where('is_redeemed', 1);
                });

            }
            else{
                $posts = $posts->whereHas('receivedCoupon', function($w) use($search){
                    $w->where('is_redeemed', 0);
                });
            }
        }

        if($isBusiness){
            $posts = $posts->whereHas('business', function ($b) use($business){
                $b->where('subdomain', $business);
            });
        }

        if($search){
            $posts = $posts->whereHas('receivedCoupon', function($w) use($search){
                $w->where('coupon', 'LIKE', '%'. $search .'%');
            });
        }

        if($parent) {
            $posts = $posts->where('parent_post', $parent);
        }

        if($business){
            $posts = $posts->orderBy('is_request', 'desc');
        }
        else{
            $posts = $posts->orderBy('created_at', 'desc');
        }

        $posts = $posts->paginate();

        return $posts;
    }
}
