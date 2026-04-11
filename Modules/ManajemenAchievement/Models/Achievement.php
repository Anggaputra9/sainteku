<?php

namespace Modules\ManajemenAchievement\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Achievement extends Model
{
    protected $table = 'trx_achievements';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'achievement_type_id',
        'achievement_level_id',
        'title',
        'description',
        'achievement_date',
        'publication_type',
        'publisher',
        'url',
        'file_path',
        'file_name',
        'status',
        'rejection_note',
        'approved_by',
        'approved_at',
        'unit_id'
    ];

    protected $casts = [
        'achievement_date' => 'date',
        'approved_at' => 'datetime'
    ];

    /**
     * Relasi ke User (pengaju)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relasi ke User (approver)
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    /**
     * Relasi ke AchievementType (jenis prestasi)
     */
    public function type()
    {
        return $this->belongsTo(AchievementType::class, 'achievement_type_id', 'id');
    }

    /**
     * Relasi ke AchievementLevel (tingkat prestasi)
     */
    public function level()
    {
        return $this->belongsTo(AchievementLevel::class, 'achievement_level_id', 'id');
    }

    /**
     * Scope untuk status pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk status approved
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope untuk status rejected
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
