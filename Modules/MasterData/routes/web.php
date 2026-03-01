<?php

use Illuminate\Support\Facades\Route;
use Modules\MasterData\Http\Controllers\MasterDataController;
use Modules\MasterData\Http\Controllers\RoleController;
use Modules\MasterData\Http\Controllers\AdminController;
use Modules\MasterData\Http\Controllers\CurriculaController;
use Modules\MasterData\Http\Controllers\CategoriesController;

Route::middleware(['auth'])->prefix('masterdata')->name('masterdata.')->group(function () {
    Route::get('/', [MasterDataController::class, 'index'])->name('index');
    Route::resource('masterdatas', MasterDataController::class);
    Route::resource('units', \Modules\MasterData\Http\Controllers\UnitController::class);

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

    // Admin UI: manage users & roles
    Route::middleware('role:1')->group(function () {
        Route::get('admin/users', [AdminController::class, 'index'])->name('admin.users.index');
        Route::post('admin/users/{id}/role', [AdminController::class, 'assignRole'])->name('admin.users.assign');

        // User CRUD
        Route::get('admin/users/create', [AdminController::class, 'create'])->name('admin.users.create');
        Route::post('admin/users', [AdminController::class, 'store'])->name('admin.users.store');
        Route::get('admin/users/{id}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
        Route::put('admin/users/{id}', [AdminController::class, 'update'])->name('admin.users.update');
        Route::delete('admin/users/{id}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
    });
});
