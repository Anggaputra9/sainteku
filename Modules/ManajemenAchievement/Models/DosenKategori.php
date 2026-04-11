<?php

namespace Modules\ManajemenAchievement\Models;

use Illuminate\Database\Eloquent\Model;

class DosenKategori extends Model
{
    protected $table = 'dosen_kategori';
    protected $fillable = ['nama', 'slug', 'deskripsi', 'is_active'];

    public function achievements()
    {
        return $this->hasMany(DosenAchievement::class, 'kategori_id');
    }
}
