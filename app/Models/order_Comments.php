<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order_Comments extends Model
{
    protected $fillable = [
        'oid',
        'uid',
        'text',
        
    ];
    
}
