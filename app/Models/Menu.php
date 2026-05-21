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
        'module_id',
        'order_no',
        'is_active'
    ];

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->where('is_active', 1)
            ->orderBy('order_no');
    }

    public function allChildren()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order_no');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }
}