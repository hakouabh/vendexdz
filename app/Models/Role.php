<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $primaryKey = 'rid';

    public const PENDING = 1;
    public const ADMIN = 2;
    public const MANAGER = 3;
    public const AGENT = 4;
    public const STORE = 5;

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
