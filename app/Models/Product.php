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
    public function variants() {
          return $this->hasMany(ProductVariant::class, 'sku', 'sku');
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
