<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class fees extends Model
{
    protected $fillable = [
        'sid',
        'app_id',
        'wid',
        'pid',
        'o_s_p',
        'c_s_p',
        'o_d_p',
        'c_d_p',
    ];
    public function willaya() {
        return $this->belongsTo(willaya::class, 'wid', 'wid');
    }
    public function app()
    {
    // Links app_id in fees table to app_id in installedApps table
        return $this->belongsTo(installedApps::class, 'app_id', 'app_id');
    }
    public function product()
    {
        // pid in fees table maps to sku in products table
        return $this->belongsTo(Product::class, 'pid', 'sku');
    }
}
