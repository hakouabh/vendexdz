<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDone extends Model
{
     protected $fillable = [
        'oid',
        'tsid'   
    ];
             
}
