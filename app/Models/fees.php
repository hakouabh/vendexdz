<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class fees extends Model
{
    protected $fillable = [
        'sid',
        'app_id',
        'wid',
        'product_id',
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
        return $this->belongsTo(installedApps::class, 'app_id', 'app_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
