<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Notifications\Notifiable;

class Post extends Model implements HasMedia
{
    use HasMediaTrait;
    use Notifiable;
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->watermark(public_path('assets/images/logo.png'))
            ->watermarkOpacity(50)
            ->watermarkPosition(Manipulations::POSITION_BOTTOM_RIGHT)
            ->watermarkHeight(30, Manipulations::UNIT_PIXELS);

        $this->addMediaConversion('lg')
            ->width(1250)
            ->watermark(public_path('assets/images/logo.png'))
            ->watermarkOpacity(50)
            ->watermarkPosition(Manipulations::POSITION_BOTTOM_RIGHT)
            ->watermarkHeight(30, Manipulations::UNIT_PIXELS);
    }

    protected $fillable = [
        'user_id',
        'business_id',
        'content',
        'short_description',
        'is_image', //image or text
        'parent_post',
        'is_request',
        'is_draft',
        'coupon',
        'is_paid',
        'expires_in',
        'request_type',
    ];

    protected $with = [
        'media',
        'user',
        'exchanges',
        'business',
        'receivedCoupon'
    ];

    protected $appends = [
        'attachments',
        'post_time',
        'post_time_small',
        'comments_count',
        'parent_short_description',
        'expiry_time',
        'expire_percentage',
        'expire_color',
        'is_expired',
        'is_displayable',
        'is_liked',
        'shares_count',
        'likes_count',
        'clicks_count',
        'lg_url'
    ];

    function setContentAttribute($content) {
        $this->attributes['content'] = $this->makeClickableLinks($content);
    }

    function setExpiresInAttribute($days) {
        $this->attributes['expires_in'] = Carbon::parse($this->created_at)->addDays(30);
    }

    protected function makeClickableLinks($s) {
        return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $s);
    }

    function getAttachmentsAttribute() {
        $medias = $this->media;
        $medias = $medias->map(function($media){
            $url = $media->getFullUrl();
            $media->thumb_url = $media->getUrl('thumb');
            $media->lg_url = $media->getUrl('lg');
            $media->url = $url;
            return $media;
        });

        return $medias;
    }

    function receivedCoupon() {
        return $this->hasOne(Coupon::class, 'exchange_id', 'id');
    }

    function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    function business() {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    function exchanges() {
        return $this->hasMany(Post::class, 'parent_post', 'id')->orderBy('created_at', 'desc');
    }

    function  getPostTimeAttribute() {
        return Carbon::parse($this->created_at)->format('jS F, Y');
    }

    function  getPostTimeSmallAttribute() {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    function getCommentsCountAttribute(){
        return Post::where('parent_post', $this->id)->count();
    }

    function getParentShortDescriptionAttribute() {
        if(!$this->is_request){
            $post = Post::find($this->parent_post);
            if($post){
                return $post->short_description;
            }
        }

        return null;
    }

    function getExpiryTimeAttribute() {
        if($this->expires_in){
            $date = Carbon::parse($this->expires_in);
            return $date->diffForHumans();
        }

        return null;
    }

    function getExpirePercentageAttribute() {
        if($this->expires_in){
            $start = strtotime(Carbon::parse($this->created_at));
            $end = strtotime(Carbon::parse($this->expires_in));

            $current = strtotime(Carbon::now());
            if(($end - $start) == 0){
                return 0;
            }
            else{
                return intval(round((($current - $start) / ($end - $start)) * 100));
            }
        }

        return 100;
    }

    function getExpireColorAttribute() {
       $percent = $this->getExpirePercentageAttribute();
       if($percent <= 70) {
           return 'green';
       }
       else if($percent > 70 && $percent < 90 ) {
           return 'orange';
       }
       else{
           return 'red';
       }
    }

    function getIsExpiredAttribute() {
        if($this->expires_in){
            $date = Carbon::parse($this->expires_in);
            return Carbon::parse($date)->isPast();
        }

        return true;
    }

    function getIsDisplayableAttribute() {
        if($this->is_request){
            $timeDiff  = Carbon::parse($this->created_at)->diffInMinutes();
            return $timeDiff >= 5 ?? true;
        }

        return true;

    }

    function impressions(){
        return $this->hasMany(Impression::class, 'post_id', 'id');
    }

    function getIsLikedAttribute() {
        if(auth()->check()){
            $userId = auth()->id();
            $impression = $this->impressions()->where('user_id', $userId)->where('action', 'like')->first();
            return $impression ? true : false;
        }

        return false;
    }

    function getLikesCountAttribute() {
        return $this->impressions()->where('action', 'like')->count();
    }

    function getSharesCountAttribute() {
        return $this->impressions()->where('action', 'share')->count();
    }

    function getClicksCountAttribute() {
        return $this->impressions()->where('action', 'click')->count();
    }

    function getLgUrlAttribute() {
        $mediaItems = $this->media;
        return isset($mediaItems[0]) && $mediaItems[0]->hasGeneratedConversion('lg') ? $mediaItems[0]->getUrl('lg') : '';
    }

    function getLgMediaAttribute() {
        $mediaItems = $this->media;
        return isset($mediaItems[0]) && $mediaItems[0]->hasGeneratedConversion('lg') ? $mediaItems[0]->file_name : '';
    }
}

