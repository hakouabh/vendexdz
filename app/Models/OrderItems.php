<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $fillable = [
        'oid',
        'product_id',
        'vid',   
        'quantity',     
    ];
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'vid', 'id');
    }

}         