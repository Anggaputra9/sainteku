<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'mst_role';
    protected $primaryKey = 'id';
    public $incrementing = true;      // ✅ karena auto_increment
    protected $keyType = 'int';        // ✅ karena integer
    public $timestamps = false;

    protected $fillable = [
        'id',
        'role_code',
        'role_name',
        'is_active',
        'created_at'
    ];

    /**
     * Relasi ke User (many-to-many)
     */
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'trx_user_role',
            'role_id',
            'user_id'
        );
    }
}
