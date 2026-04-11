<?php

use Illuminate\Support\Facades\Route;
use Modules\MasterData\Http\Controllers\MasterDataController;
use Modules\MasterData\Http\Controllers\RoleController;
use Modules\MasterData\Http\Controllers\AdminController;
use Modules\MasterData\Http\Controllers\CurriculaController;
use Modules\MasterData\Http\Controllers\CategoriesController;
use Modules\MasterData\app\Http\Controllers\InfrastructureController;

// =========================================================================
// 🛡️ GEMBOK BRUTAL: Panggil class middleware lengkap langsung di sini
// =========================================================================
Route::middleware([
    'web',
    'auth',
    \App\Http\Middleware\RoleMiddleware::class . ':ADM|Administrator'
])->prefix('masterdata')->name('masterdata.')->group(function () {

    Route::get('/', [MasterDataController::class, 'index'])->name('index');
    Route::resource('masterdatas', MasterDataController::class);

    Route::resource('units', \Modules\MasterData\Http\Controllers\UnitController::class)->except(['create', 'edit']);

    // Role management read-only listing for now
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::post('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');

    // Curricula management
    Route::get('curricula', [CurriculaController::class, 'index'])->name('curricula.index');

    // Categories management
    Route::get('categories', [CategoriesController::class, 'index'])->name('categories.index');

    // Admin UI: manage users 
    // (Middleware role:1 dihapus karena grup luar udah khusus Admin)
    Route::prefix('admin')->group(function () {
        Route::get('users', [AdminController::class, 'index'])->name('admin.users.index');
        Route::post('users/{id}/role', [AdminController::class, 'assignRole'])->name('admin.users.assign');

        // User CRUD
        Route::get('users/create', [AdminController::class, 'create'])->name('admin.users.create');
        Route::post('users', [AdminController::class, 'store'])->name('admin.users.store');
        Route::get('users/{id}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
        Route::put('users/{id}', [AdminController::class, 'update'])->name('admin.users.update');
        Route::delete('users/{id}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
    });

    // Infrastructure management
    Route::resource('infrastructures', InfrastructureController::class);
});