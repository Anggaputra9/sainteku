<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

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

    // Konfigurasi tabel
    protected $table = 'mst_user';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // Karena tabel tidak punya created_at/updated_at

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
    ];

    // Kolom yang disembunyikan saat serialisasi
    protected $hidden = [
        'password', 
        'remember_token', // SEBAIKNYA INI JUGA DISEMBUNYIKAN
    ];

    // Casting tipe data
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean', // TAMBAHKAN INI
            'last_login_at' => 'datetime', // TAMBAHKAN INI
        ];
    }

    // TAMBAHKAN METHOD INI UNTUK FITUR RESET PASSWORD
    public function sendPasswordResetNotification($token)
    {
        // Ini akan dipanggil oleh Laravel saat reset password
        // Tapi kita tidak pakai karena pakai custom implementation
        // Bisa dikosongkan saja
    }

    /**
     * Cek apakah user memiliki permission tertentu pada suatu modul
     * Contoh penggunaan: Auth::user()->hasPermission(10, 'C')
     */
    public function hasPermission($moduleId, $permissionCode)
    {
        // Kalau Super Admin (misal code 'ADM'), langsung losin aja semua
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
}
