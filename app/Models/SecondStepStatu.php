<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecondStepStatu extends Model
{
    protected $primaryKey = 'ssid'; 
    public $incrementing = false;  
    public $timestamps = false;
     protected $fillable = [
        'ssid',
        'name',
        'icon',
        'color'
    ];
    
}
