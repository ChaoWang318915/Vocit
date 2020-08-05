<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Coupon extends Model
{
    protected $fillable = [
        'coupon',
        'post_id',
        'user_id',
        'business_id',
        'exchange_id',
        'is_redeemed',
        'qr_code'
    ];

    function setCouponAttribute($coupon) {
        $this->attributes['coupon'] = $coupon;

        $path = 'coupons/'. $coupon .'.png';

        $base64_image = getQrCode($coupon);

        Storage::disk('s3')->put($path, base64_decode($base64_image));
        $this->attributes['qr_code'] = Storage::disk('s3')->url($path);
    }

    function post() {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    function user() {
        return $this->belongsTo(User::class, 'user_id' ,'id');
    }

    function business() {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    function exchange() {
        return $this->belongsTo(Post::class ,'exchange_id', 'id');
    }
}
