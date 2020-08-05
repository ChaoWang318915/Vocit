<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Impression extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'action'
    ];
}
