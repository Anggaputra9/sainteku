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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'trx_user_role',
            'user_id',
            'role_id'
        );
    }

    public function achievements()
    {
        return $this->hasMany(
            \Modules\ManajemenAchievement\Models\Achievement::class,
            'user_id',
            'id'
        );
    }

    public function dosenAchievements()
    {
        return $this->hasMany(
            \Modules\ManajemenAchievement\Models\DosenAchievement::class,
            'user_id',
            'id'
        );
    }

    public function courses()
    {
        return $this->hasMany(\App\Models\MstCourse::class, 'unit_id');
    }

    public function unitUtama()
    {
        return $this->belongsTo(\App\Models\MstUnit::class, 'unit_id', 'id');
    }

    public function unitTambahan()
    {
        return $this->belongsToMany(
            \App\Models\MstUnit::class,
            'mst_user_unit',           
            'user_id',                 
            'unit_id'                  
        );
    }

    public function hasPermission($moduleId, $permissionCode)
    {
        if ($this->roles()->where('role_code', 'ADM')->exists()) {
            return true;
        }

        $roleIds = $this->roles->pluck('id')->toArray();

        return \Illuminate\Support\Facades\DB::table('trx_role_permission as rp')
            ->join('ref_permission as p', 'rp.permission_id', '=', 'p.id')
            ->whereIn('rp.role_id', $roleIds)
            ->where('rp.modul_id', $moduleId)
            ->where('p.permission_code', $permissionCode)
            ->where('rp.allowed', 1)
            ->exists();
    }

    public function sendPasswordResetNotification($token)
    {
    }
}