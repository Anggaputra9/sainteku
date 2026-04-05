<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstCpmk extends Model
{
    protected $table = 'mst_cpmk';
    
    // Matikan auto-increment karena ID berupa string (contoh: CP-01)
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 
        'name', 
        'is_active'
    ];
}