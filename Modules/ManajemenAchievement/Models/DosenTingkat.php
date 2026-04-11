<?php

namespace Modules\ManajemenAchievement\Models;   // ✅ HARUS INI, tanpa "app"!

use Illuminate\Database\Eloquent\Model;

class DosenTingkat extends Model
{
    protected $table = 'dosen_tingkat';
    protected $fillable = ['nama', 'slug', 'is_active'];

    public function achievements()
    {
        return $this->hasMany(DosenAchievement::class, 'tingkat_id');
    }
}
