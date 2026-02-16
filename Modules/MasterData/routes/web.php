<?php

use Illuminate\Support\Facades\Route;
use Modules\MasterData\Http\Controllers\MasterDataController;
use Modules\MasterData\Http\Controllers\RoleController;
use Modules\MasterData\Http\Controllers\AdminController;

Route::middleware(['auth'])->prefix('masterdata')->name('masterdata.')->group(function () {
    Route::get('/', [MasterDataController::class, 'index'])->name('index');
    Route::resource('masterdatas', MasterDataController::class);
    Route::resource('units', \Modules\MasterData\Http\Controllers\UnitController::class);
    // Role management read-only listing for now
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');

    // Admin UI: manage users & roles
    Route::get('admin/users', [AdminController::class, 'index'])->name('admin.users.index')->middleware('role:1');
    Route::post('admin/users/{id}/role', [AdminController::class, 'assignRole'])->name('admin.users.assign')->middleware('role:1');
});