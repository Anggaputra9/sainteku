<?php

namespace Modules\MasterData\Entities;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'mst_unit';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'unit_name',
        'unit_parent',
        'unit_type_id',
        'is_active',
        'created_at',
    ];
}
