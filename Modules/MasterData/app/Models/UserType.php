<?php

namespace Modules\MasterData\app\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $table = 'ref_user_type'; // Menyesuaikan nama tabel
    protected $keyType = 'string'; // Karena ID nya varchar ('001')
    public $incrementing = false;
    public $timestamps = false; // Karena di DB tidak ada created_at
    protected $guarded = [];
}