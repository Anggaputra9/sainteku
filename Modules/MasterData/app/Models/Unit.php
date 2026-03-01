<?php

namespace Modules\MasterData\app\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'mst_unit'; // Menyesuaikan nama tabel kamu
    protected $keyType = 'string'; // Karena ID nya varchar ('0001')
    public $incrementing = false;
    protected $guarded = [];
}