<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NimWhitelist extends Model
{
    protected $fillable = [
        'nim',
        'name',
        'is_used',
    ];
}
