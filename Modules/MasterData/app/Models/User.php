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
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}