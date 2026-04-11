<?php

namespace Modules\ManajemenAchievement\Models;  // ✅ Pastikan ini

use Illuminate\Database\Eloquent\Model;

class AchievementLevel extends Model
{
    protected $table = 'mst_achievement_level';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['description', 'is_active'];

    public function achievements()
    {
        return $this->hasMany(Achievement::class, 'achievement_level_id', 'id');
    }
}
