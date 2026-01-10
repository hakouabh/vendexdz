<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    protected $fillable = [
        'cid',
        'qmin',
        'qmax',
        'qb',
        'cid',
        'usell',
        'csell',
        'ab_o',
        'msg',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class, 'cid', 'cid');
    }
      
}
