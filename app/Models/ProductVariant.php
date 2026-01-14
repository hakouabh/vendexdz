<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'var_1',
        'var_2',
        'var_3',
        'discount',
        'quantity'
        
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'sku','sku');
    }
}
