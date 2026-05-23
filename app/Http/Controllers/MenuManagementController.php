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

    public function index(Request $request)
    {
        $this->guardAdmin();

        $menus = Menu::with(['allChildren', 'parent'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where(function ($q) use ($search) {
                    $q->where('menu_name', 'like', "%{$search}%")
                        ->orWhere('menu_link', 'like', "%{$search}%")
                        ->orWhere('menu_icon', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->status === 'active'))
            ->when($request->filled('parent'), function ($query) use ($request) {
                $request->parent === 'root'
                    ? $query->whereNull('parent_id')
                    : $query->where('parent_id', $request->parent);
            })
            ->when($request->filled('module_id'), fn ($query) => $query->where('module_id', $request->module_id))
            ->orderBy('parent_id')
            ->orderBy('order_no')
            ->paginate(10)
            ->withQueryString();
        $parents = Menu::whereNull('parent_id')->orderBy('order_no')->get();
        $modules = DB::table('mst_module')->orderBy('id')->get();

        if ($request->expectsJson()) {
            return response()->json($menus);
        }

        return view('settings.menu.index', compact('menus', 'parents', 'modules'))->with('title', 'Manajemen Menu');
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

