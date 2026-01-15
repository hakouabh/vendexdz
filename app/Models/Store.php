<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name',
        'created_by',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
