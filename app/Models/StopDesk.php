<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StopDesk extends Model
{
    protected $fillable = [
        '3PLid',
        'code_postal',
        'has_stop_desk',
    ];
                
}
