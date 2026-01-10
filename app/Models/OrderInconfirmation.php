<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderInconfirmation extends Model
{
    protected $fillable = [
        'oid',
        'fsid'   
    ];
    public function firstStepStatu()
    {
        return $this->hasOne(firstStepStatu::class, 'fsid', 'fsid');
    }
}
