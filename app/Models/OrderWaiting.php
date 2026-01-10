<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderWaiting extends Model
{
     protected $fillable = [
        'oid',
        'asid'   
    ];
    public function AcceptStepStatu()
    {
        return $this->hasOne(AcceptStepStatu::class, 'asid', 'asid');
    }
}
