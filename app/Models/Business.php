<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Business extends Model
{
    protected $fillable = [
        'name',
        'subdomain',
        'email',
        'phone',
        'state',
        'zip',
        'address',
        'logo',
        'ein',
        'contact_person',
        'contact_email',
        'contact_phone',
        'banner',
        'is_registered',
        'is_suspended',
        'post_limit'
    ];

    protected $appends = [
        'is_subscribed',
        'requests_count',
        'exchanges_count',
        'requests_per_month',
        'members_count',
        'subscription',
        'payments_count'
    ];

    function setNameAttribute($name){
        $this->attributes['name'] = ucfirst($name);
        $subdomain = Str::slug($name);
        $count = Business::where('subdomain', $subdomain)->count();
        if($count > 0){
            $this->attributes['subdomain'] = $subdomain. rand(00, 99);
        }
        else
        {
            $this->attributes['subdomain'] = $subdomain;
        }

    }

    function members() {
       return $this->hasMany(BusinessMember::class, 'business_id', 'id');
    }

    function getAdminAttribute() {
        return $this->members->where('role', 'admin')->first();
    }

    function posts() {
        return $this->hasMany(Post::class, 'business_id', '');
    }

    function getIsSubscribedAttribute() {
        return $this->getSubscriptionAttribute() ? true : false;
    }

    function getRequestsCountAttribute() {
        return $this->posts()->where('is_request', 1)->where('is_draft', 0)->where('is_image', 1)->count();
    }

    function getExchangesCountAttribute() {
        return Post::where('business_id', $this->id)->where('is_request', 0)->where('is_draft', 0)->where('is_image', 1)->count();
    }

    function getRequestsPerMonthAttribute() {
        return Post::where('business_id', $this->id)->where('is_request', 1)->where('is_image', 1)->where('is_draft', 0)->whereMonth(
            'created_at', '=', Carbon::now()->month
        )->count();
    }

    function getMembersCountAttribute() {
        return $this->members->count();
    }

    function payments() {
        return $this->hasMany(Payment::class, 'business_id', 'id');
    }

    function getSubscriptionAttribute() {
        return $this->payments()->where('is_subscription', '=',1)->first();
    }

    function getPaymentsCountAttribute() {
        return $this->payments->count();
    }
}
