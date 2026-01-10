<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
     protected $fillable = [
        'oid',
        'tracking',
        'cid',
        'sid',
        'aid',
        'app_id',
        'custom_id',
        'from',
        
    ];
    public function getRouteKeyName()
    {
        return 'oid';
    }
    public function items()
    {
        return $this->hasMany(OrderItems::class, 'oid', 'oid');
    }
    public function Store()
    {
        return $this->hasMany(User::class, 'id', 'oid');
    }
    public function Notes()
    {
        
        return $this->hasMany(OrderNots::class, 'oid', 'oid')->latest();
    }
    public function chats()
    {
        return $this->hasMany(order_Comments::class, 'oid', 'oid');
    }
    public function histories()
    {
        return $this->hasMany(order_logs::class, 'oid', 'oid')->latest();
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'cid', 'id');
    }
    public function details()
    {
        return $this->hasOne(OrderDetails::class, 'oid', 'oid');
    }
    public function Inconfirmation()
    {
        return $this->hasOne(OrderInconfirmation::class, 'oid', 'oid');
    }
    public function Postpend()
    {
        return $this->hasOne(OrderInconfirmation::class, 'oid', 'oid');
    }
    public function Waiting()
    {
        return $this->hasOne(OrderWaiting::class, 'oid', 'oid');
    }
    public function Indelivery()
    {
        return $this->hasOne(OrderIndelivery::class, 'oid', 'oid');
    }

    public function Timer()
    {
        return $this->hasOne(Timer::class, 'oid', 'oid');
    }
    public function logs()
    {
         return $this->hasMany(order_logs::class, 'oid', 'oid')->latest();
    }
     public function Type()
    {
         return $this->hasOne(order_type::class, 'oid', 'oid')->latest();
    }
}
