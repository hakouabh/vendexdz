<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'full_name',
        'phone_number_1',
        'phone_number_2',
        'wilaya',
        'town',
        'address'
    ];
    public function willaya()
    {
         return $this->hasOne(willaya::class, 'wid', 'wilaya');
    }
  
}
