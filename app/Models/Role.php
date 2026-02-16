<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'mst_role';
    public $timestamps = false;
    protected $fillable = ['id', 'role_code', 'role_name', 'is_active', 'created_at'];
}
