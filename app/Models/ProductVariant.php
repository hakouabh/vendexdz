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
    protected $appends = ['label'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getLabelAttribute(){
       return collect([$this->var_1, $this->var_2, $this->var_3])
        ->filter()
        ->implode(' ');
    } 
}
