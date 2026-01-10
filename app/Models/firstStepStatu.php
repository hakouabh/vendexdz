<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class firstStepStatu extends Model
{
    protected $primaryKey = 'fsid'; 
    public $incrementing = false;  
    public $timestamps = false;
    protected $fillable = [
        'fsid',
        'name',
        'icon',
        'color'
    ];
}
