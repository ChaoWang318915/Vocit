<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    protected $fillable = [
        'business_id',
        'name',
        'app_name',
        'key'
    ];
}
