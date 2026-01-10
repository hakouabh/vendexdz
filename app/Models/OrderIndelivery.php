<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderIndelivery extends Model
{
     protected $fillable = [
        'oid',
        'ssid'   
    ];
    public function SecondStepStatu()
    {
        return $this->hasOne(SecondStepStatu::class, 'ssid', 'ssid');
    }
}
