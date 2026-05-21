<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MenuManagementController extends Controller
{
    private function guardAdmin(): void
    {
        abort_unless(Auth::user()?->roles()->where('role_code', 'ADM')->exists(), 403);
    }

    public function index()
    {
        $this->guardAdmin();
        $menus = Menu::with(['allChildren', 'parent'])->orderBy('parent_id')->orderBy('order_no')->get();
        $parents = Menu::whereNull('parent_id')->orderBy('order_no')->get();
        $modules = DB::table('mst_module')->orderBy('id')->get();

        return view('menu-management.index', compact('menus', 'parents', 'modules'))->with('title', 'Manajemen Menu');
    }

    public function store(Request $request)
    {
        $this->guardAdmin();
        Menu::create($this->validated($request));
        return back()->with('success', 'Menu berhasil ditambahkan.');
    }

    public function update(Request $request, Menu $menu)
    {
        $this->guardAdmin();
        $menu->update($this->validated($request, $menu->id));
        return back()->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        $this->guardAdmin();
        abort_if($menu->allChildren()->exists(), 422, 'Hapus submenu dulu.');
        $menu->delete();
        return back()->with('success', 'Menu berhasil dihapus.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'menu_name' => 'required|string|max:50',
            'menu_link' => 'nullable|string|max:100',
            'menu_icon' => 'nullable|string|max:100',
            'parent_id' => 'nullable|integer|exists:mst_menu,id',
            'module_id' => 'nullable|integer|exists:mst_module,id',
            'order_no' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($id && (int) ($data['parent_id'] ?? 0) === $id) {
            abort(422, 'Parent tidak boleh menu sendiri.');
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['order_no'] = $data['order_no'] ?? 0;
        return $data;
    }
}
