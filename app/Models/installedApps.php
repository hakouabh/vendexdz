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
    public function supportedApp()
    {
        return $this->belongsTo(SupportedApps::class, 'app_id', 'app_id');
    }
}
