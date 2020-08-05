<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessMember extends Model
{
    protected $fillable =[
        'business_id',
        'user_id',
        'is_suspended',
        'is_joined',
        'role'
    ];

    function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    function business() {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }
}
