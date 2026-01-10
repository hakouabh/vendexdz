<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order_type extends Model
{
     protected $fillable = [
        'oid',
        'type_id',
    ];
}
