<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Konfigurasi tabel
    protected $table = 'mst_user';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    // Kolom yang bisa diisi (mass assignable)
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
    ];

    // Kolom yang disembunyikan saat serialisasi
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casting tipe data
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    // ==================================================
    // RELASI
    // ==================================================

    /**
     * Relasi dengan Role (many-to-many)
     */
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'trx_user_role',
            'user_id',
            'role_id'
        );
    }

    /**
     * Relasi dengan Achievement Mahasiswa (dari tabel trx_achievements)
     */
    public function achievements()
    {
        return $this->hasMany(
            \Modules\ManajemenAchievement\Models\Achievement::class,
            'user_id',
            'id'
        );
    }

    /**
     * Relasi dengan Achievement Dosen (dari tabel dosen_achievements)
     */
    public function dosenAchievements()
    {
        return $this->hasMany(
            \Modules\ManajemenAchievement\Models\DosenAchievement::class,
            'user_id',
            'id'
        );
    }

    /**
     * Relasi dengan Course
     */
    public function courses()
    {
        return $this->hasMany(\App\Models\MstCourse::class, 'unit_id');
    }

    // ==================================================
    // PERMISSION
    // ==================================================

    /**
     * Cek apakah user memiliki permission tertentu pada suatu modul
     * Contoh penggunaan: Auth::user()->hasPermission(10, 'C')
     */
    public function hasPermission($moduleId, $permissionCode)
    {
        // Kalau Super Admin (role_code 'ADM'), langsung return true
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

    // ==================================================
    // METHOD LAIN
    // ==================================================

    /**
     * Method untuk reset password (override dari Authenticatable)
     */
    public function sendPasswordResetNotification($token)
    {
        // Ini akan dipanggil oleh Laravel saat reset password
        // Tapi kita tidak pakai karena pakai custom implementation
        // Bisa dikosongkan saja
    }
}
