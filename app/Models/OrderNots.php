<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderNots extends Model
{
    protected $fillable = [
        'oid',
        'uid',
        'text',
    ];
}