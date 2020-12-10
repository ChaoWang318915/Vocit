<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'avatar',
        'phone',
        'username',
        'password',
        'is_business_account',
        'api_token',
        'is_blocked',
        'sandbox_user',
        'facebook_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'name',
        'profile_pic',
        'member_since',
        'active_business',
        'active_role'
    ];

    protected $with = [
        'businesses'
    ];

    function getNameAttribute() {
        return ($this->first_name . ' ' . $this->last_name);
    }

    function getMemberSinceAttribute() {
        return Carbon::parse($this->created_at)->format('jS F, Y');
    }

    function getProfilePicAttribute() {
        return $this->avatar ? $this->avatar : asset('assets/images/matthew.png');
    }

    function businesses() {
        return $this->hasManyThrough(Business::class,
            BusinessMember::class,
            'user_id',
            'id',
            'id',
            'business_id'
        )->where('is_joined', '=',1);
    }

    function posts() {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    function getActiveBusinessAttribute() {
        if(session()->get('active_business')){
            return Business::find(session()->get('active_business'));
        }

        return $this->businesses()->where('is_joined', 1)->first();

    }

    function getActiveRoleAttribute() {
        if(session()->get('active_business')){
            $member = BusinessMember::where('user_id', $this->id)
                ->where('business_id', session()->get('active_business'))
                ->first();

            if($member){
                return $member->role;
            }
        }

        return null;
    }
}
