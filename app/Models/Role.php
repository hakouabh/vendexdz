<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $primaryKey = 'rid';
    protected $fillable = [
        'rid',
        'name',
    ];

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'user_role',
            'rid',           
            'uid'            
        );
    }
}
