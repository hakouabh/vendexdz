<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class town extends Model
{
    protected $fillable = [
        'wid',
        'name',
        'code_postal',
    ];
    
}
