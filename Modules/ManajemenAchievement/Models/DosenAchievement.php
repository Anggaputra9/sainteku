<?php

namespace Modules\ManajemenAchievement\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DosenAchievement extends Model
{
    protected $table = 'dosen_achievements';
    protected $fillable = [
        'user_id',
        'kategori_id',
        'tingkat_id',
        'judul',
        'deskripsi',
        'tanggal',
        'penyelenggara',
        'url',
        'jenis_publikasi',
        'nama_jurnal',
        'volume',
        'nomor',
        'halaman',
        'issn',
        'nomor_pendaftaran',
        'status_hki',
        'isbn',
        'penerbit',
        'jumlah_halaman',
        'file_path',
        'file_name',
        'status',
        'catatan_penolakan',
        'approved_by',
        'approved_at',
        'unit_id'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'approved_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function kategori()
    {
        return $this->belongsTo(DosenKategori::class, 'kategori_id');
    }

    public function tingkat()
    {
        return $this->belongsTo(DosenTingkat::class, 'tingkat_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }
}
