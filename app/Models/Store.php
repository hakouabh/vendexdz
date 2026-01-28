<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name',
        'created_by',
    ];

    protected $appends = ['short_name'];

    public function getShortNameAttribute()
    {
        return strtoupper(substr($this->name, 0, 2));
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function managers(){
        return $this->hasManyThrough(
            User::class,
            UserStore::class,
            'store_id', // Foreign key on StoreManager table...
            'id', // Foreign key on User table...
            'id', // Local key on Store table...
            'user_id' // Local key on StoreManager table...
        )->whereHas('roles', function ($q) {
            $q->where('roles.rid', 3);
        });
    }

    public function agents(){
        return $this->hasManyThrough(
            User::class,
            UserStore::class,
            'store_id', // Foreign key on StoreManager table...
            'id', // Foreign key on User table...
            'id', // Local key on Store table...
            'user_id' // Local key on StoreManager table...
        )->whereHas('roles', function ($q) {
            $q->where('roles.rid', 4);
        });
    }

    public function shops(){
        return $this->hasManyThrough(
            User::class,
            UserStore::class,
            'store_id', // Foreign key on StoreManager table...
            'id', // Foreign key on User table...
            'id', // Local key on Store table...
            'user_id' // Local key on StoreManager table...
        )->whereHas('roles', function ($q) {
            $q->where('roles.rid', 5);
        });
    }
}
