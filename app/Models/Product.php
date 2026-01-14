<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sid',
        'name',
        'nickname',
        'url',
        'sku',
        'cid',
        'price',
        
    ];

    public static $rules = [
        'name' => 'required|string|max:255',
        'sku' => 'required|numeric',
        'price' => 'required|numeric|min:0',
        'variants.*.quantity' => 'required|integer|min:0',
        'variants.*.discount' => 'required|integer|min:0',
        'variants.*.sku' => 'nullable|string',
    ];

    public function variants() {
          return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }
    public function agents()
    {
        return $this->belongsToMany(User::class, 'product_agents', 'sku', 'aid', 'sku', 'id')
                ->withPivot('portion', 'is_active');
    }

    public function agentAssignments()
    {
        return $this->hasMany(ProductAgent::class, 'sku', 'sku');
    }
}
