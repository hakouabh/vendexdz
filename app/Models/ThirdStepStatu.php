<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThirdStepStatu extends Model
{
    protected $primaryKey = 'tsid'; 
    public $incrementing = false;  
    public $timestamps = false;
    protected $fillable = [
        'tsid',
        'name',
        'icon',
        'color'
    ];
}
