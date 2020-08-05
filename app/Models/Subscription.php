<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'post_id',
        'business_id',
        'user_id',
        'payment_method',
        'amount',
        'customer_id',
        'plan_id',
        'next_billing_date',
        'is_recurring'
    ];
}
