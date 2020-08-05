<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'business_id',
        'amount',
        'package',
        'customer_id',
        'billing_interval',
        'trial_days',
        'payment_method',
        'transaction_id',
        'is_refunded',
        'is_success',
        'is_subscription'
    ];
    function business(){
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }
}
