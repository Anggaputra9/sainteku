<?php

namespace Modules\MasterData\app\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    // Arahkan ke nama tabel yang benar
    protected $table = 'mst_unit';

    // 1. WAJIB: Beri tahu Laravel bahwa ID kita adalah String (U001), bukan angka biasa
    public $incrementing = false;
    protected $keyType = 'string';

    // 2. WAJIB: Tambahkan 'id' ke dalam fillable agar tidak dibuang oleh Laravel
    protected $fillable = [
        'id',           
        'unit_name',
        'unit_parent',
        'unit_type_id',
        'is_active',
    ];
}