<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
     protected $fillable = [
        'oid',
        'aid',
        'statu_old',
        'statu_new',
        'text',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'aid', 'id');
    }
    public function firstStepStatusNew()
    {
        return $this->belongsTo(FirstStepStatu::class, 'statu_new', 'fsid');
    }

    public function secondStepStatusNew()
    {
        return $this->belongsTo(SecondStepStatu::class, 'statu_new', 'ssid');
    }

    public function firstStepStatusOld()
    {
        return $this->belongsTo(FirstStepStatu::class, 'statu_old', 'fsid');
    }

    public function secondStepStatusOld()
    {
        return $this->belongsTo(SecondStepStatu::class, 'statu_old', 'ssid');
    }

    public function getStatusNewAttribute()
    {
        return $this->step == 1
            ? $this->firstStepStatusNew
            : $this->secondStepStatusNew;
    }

    public function getStatusOldAttribute()
    {
        return $this->step == 1
            ? $this->firstStepStatusOld
            : $this->secondStepStatusOld;
    }
}
