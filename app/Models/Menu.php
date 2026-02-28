<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'mst_menu';

    protected $fillable = [
        'menu_name',
        'menu_link',
        'menu_icon',
        'parent_id',
        'order_no',
        'is_active'
    ];

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->where('is_active', 1)
            ->orderBy('order_no');
    }
}