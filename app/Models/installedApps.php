<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class installedApps extends Model
{
    protected $fillable = [
        'sid',
        'app_id',
        'key',
        'token',
        'is_active',
    ];

}
