<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportedApps extends Model
{
    protected $fillable = [
        'app_id',
        'name',
        'icon',
    ];
}
