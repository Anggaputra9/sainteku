<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    // WAJIB DISESUAIKAN: Tulis nama tabel unit kamu di database yang bener apa
    protected $table = 'mst_unit'; 

    // Biarkan Mass Assignment aman
    protected $guarded = ['id'];

    /**
     * Relasi kebalikannya: 1 Unit punya Banyak Course (Mata Kuliah)
     */
    public function courses()
    {
        return $this->hasMany(\App\Models\MstCourse::class, 'unit_id');
    }
}