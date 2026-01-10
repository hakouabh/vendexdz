<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcceptStepStatu extends Model
{
    protected $primaryKey = 'asid'; 
    public $incrementing = false;  
    public $timestamps = false;

     protected $fillable = [
        'asid',
        'name',
        'icon',
        'color'
    ];
}
