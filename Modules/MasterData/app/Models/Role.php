<?php

namespace Modules\MasterData\app\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'mst_role';
    public $timestamps = false; // Karena di tabel mst_role tidak ada updated_at
    protected $guarded = [];
}