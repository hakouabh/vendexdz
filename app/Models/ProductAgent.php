<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAgent extends Model
{
    protected $fillable = [
        'aid',
        'sku',
        'portion',
        'is_active',
        'daily_received',  
        'last_assigned_date'
    ];
}
