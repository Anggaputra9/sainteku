<?php

namespace Modules\MasterData\app\Models;

use Illuminate\Database\Eloquent\Model;
// Gunakan Authenticatable jika model ini dipakai untuk fitur Login bawaan Laravel
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    // 1. Arahkan ke tabel yang benar
    protected $table = 'mst_user';

    // 2. Setting Primary Key karena formatnya varchar ('u0001')
    protected $keyType = 'string';
    public $incrementing = false;

    // 3. Kolom yang boleh diisi (Mass Assignment)
    protected $guarded = [];

    // 4. Relasi ke Role (Penting karena Controller memanggil User::with('roles'))
    public function roles()
    {
        // belongsToMany(Model Tujuan, Nama Tabel Pivot, Foreign Key di Pivot, Related Key di Pivot)
        return $this->belongsToMany(Role::class, 'trx_user_role', 'user_id', 'role_id');
    }

    // Opsional: Relasi ke Unit
    // ========================================================
    // RELASI UNTUK MULTI-UNIT
    // ========================================================

    /**
     * Relasi ke Unit Utama (Berdasarkan kolom unit_id di tabel mst_user)
     */
    public function unitUtama()
    {
        // Pastikan path Model Unit-nya sesuai dengan yang lu pake di modul
        return $this->belongsTo(\Modules\MasterData\app\Models\Unit::class, 'unit_id', 'id');
    }

    /**
     * Relasi ke Unit Tambahan / Rangkap Jabatan (Berdasarkan tabel pivot mst_user_unit)
     */
    public function unitTambahan()
    {
        return $this->belongsToMany(
            \Modules\MasterData\app\Models\Unit::class, // Model target unit di dalam modul lu
            'mst_user_unit',                            // Nama tabel pivot
            'user_id',                                  // Foreign key user di pivot
            'unit_id'                                   // Foreign key unit di pivot
        );
    }
}