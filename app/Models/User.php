<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'mst_user';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'identity_id',
        'user_type',
        'unit_id',
        'signature',
        'is_active',
        'remember_token',
        'last_login_at',
        'phone_number',
        'avatar',
        'bio',
        'address',
        'gender',
        'birth_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'birth_date' => 'date',
        ];
    }

    // Relasi dengan Role
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'trx_user_role',
            'user_id',
            'role_id'
        );
    }

    // Relasi dengan Achievement Mahasiswa
    public function achievements()
    {
        return $this->hasMany(
            \Modules\ManajemenAchievement\Models\Achievement::class,
            'user_id',
            'id'
        );
    }

    // Relasi dengan Achievement Dosen
    public function dosenAchievements()
    {
        return $this->hasMany(
            \Modules\ManajemenAchievement\Models\DosenAchievement::class,
            'user_id',
            'id'
        );
    }

    public function sendPasswordResetNotification($token)
    {
        // Custom implementation
    }
}
