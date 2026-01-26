<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected static function booted(): void
    {
        static::created(function (User $user) {
            $user->roles()->attach(1);
        });
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'is_active',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,      
            'user_roles',     
            'uid',            
            'rid',            
            'id',             
            'rid'
        );
    }
    public function hasRole($roleId)
    {
    // Check if any role associated with this user matches the given 'rid'
    return $this->roles()->where('roles.rid', $roleId)->exists();
    }

    public function userStore()
    {
        return $this->hasOne(UserStore::class, 'user_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    // public function Store()
    // {
    //     return $this->belongsToMany(
    //         Role::class,      // 1. المودل المرتبط
    //         'user_roles',     // 2. اسم الجدول الوسيط
    //         'uid',            // 3. مفتاح المستخدم في الوسيط (Foreign Key for User)
    //         'rid',            // 4. مفتاح الرتبة في الوسيط (Foreign Key for Role)
    //         'id',             // 5. المفتاح الأصلي للمستخدم (User Primary Key)
    //         'rid'             // 6. المفتاح الأصلي للرتبة (Role Primary Key) <--- هذا هو الأهم!
    //     );
    // }
    //manager
    public function managedStores()
    {
        return $this->belongsToMany(User::class, 'store_managers', 'mid', 'sid');
    }

    public function managers()
    {
        return $this->belongsToMany(User::class, 'store_managers', 'sid', 'mid');
    }

    //agent
    public function AgentStores()
    {
        return $this->belongsToMany(User::class, 'store_agents', 'aid', 'sid');
    }

    public function Agents()
    {
        return $this->belongsToMany(User::class, 'store_agents', 'sid', 'aid');
    }
}
