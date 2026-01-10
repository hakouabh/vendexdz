<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order_logs extends Model
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
    public function statusNew()
    {
        return $this->belongsTo(firstStepStatu::class, 'statu_new', 'fsid');
    }
    public function statusOld()
    {
        return $this->belongsTo(firstStepStatu::class, 'statu_old', 'fsid');
    }
}
