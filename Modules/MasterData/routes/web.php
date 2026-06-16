<?php

use Illuminate\Support\Facades\Route;
use Modules\MasterData\Http\Controllers\MasterDataController;
use Modules\MasterData\Http\Controllers\RoleController;
use Modules\MasterData\Http\Controllers\AdminController;
use Modules\MasterData\Http\Controllers\CurriculaController;
use Modules\MasterData\Http\Controllers\CategoriesController;
use Modules\MasterData\app\Http\Controllers\InfrastructureController;
use Modules\MasterData\app\Http\Controllers\CourseController;
use Modules\MasterData\Http\Controllers\PeriodController;
use Modules\MasterData\Http\Controllers\CpmkController;
use Modules\MasterData\Http\Controllers\CplController;
use Modules\MasterData\Http\Controllers\CplCpmkMappingController;

// =========================================================================
// 🛡️ GEMBOK BRUTAL: Panggil class middleware lengkap langsung di sini
// =========================================================================
Route::middleware([
    'web',
    'auth',
    \App\Http\Middleware\RoleMiddleware::class . ':ADM|Administrator'
])->prefix('masterdata')->name('masterdata.')->group(function () {

    Route::get('/test-users-debug', [AdminController::class, 'index'])->withoutMiddleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':ADM|Administrator']);
    Route::get('/', [MasterDataController::class, 'index'])->name('index');
    Route::resource('masterdatas', MasterDataController::class);

    Route::get('units/api/data', [\Modules\MasterData\Http\Controllers\UnitController::class, 'getUnitsData'])->name('units.api.data');

    // CPL per program studi
    Route::get('units/{unit}/cpl/api/data', [CplController::class, 'getCplData'])->name('units.cpl.api.data');
    Route::post('units/{unit}/cpl', [CplController::class, 'store'])->name('units.cpl.store');
    Route::post('units/{unit}/cpl/bulk-delete', [CplController::class, 'bulkDestroy'])->name('units.cpl.bulk.destroy');
    Route::put('units/{unit}/cpl/{cpl}', [CplController::class, 'update'])->name('units.cpl.update');
    Route::delete('units/{unit}/cpl/{cpl}', [CplController::class, 'destroy'])->name('units.cpl.destroy');

    Route::resource('units', \Modules\MasterData\Http\Controllers\UnitController::class)->except(['create', 'edit']);

    // Role management
    Route::get('/roles/api/data', [RoleController::class, 'getRolesData'])->name('roles.api.data');
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
        Route::get('users/api/data', [AdminController::class, 'getUsersData'])->name('admin.users.api.data');
        Route::get('users', [AdminController::class, 'index'])->name('admin.users.index');
        Route::post('users/{id}/role', [AdminController::class, 'assignRole'])->name('admin.users.assign');

        // User CRUD
        Route::get('users/create', [AdminController::class, 'create'])->name('admin.users.create');
        Route::post('users', [AdminController::class, 'store'])->name('admin.users.store');
        Route::post('users/bulk', [AdminController::class, 'bulkStore'])->name('admin.users.bulk.store');
        Route::get('users/bulk/template', [AdminController::class, 'downloadBulkTemplate'])->name('admin.users.bulk.template');
        Route::get('users/{id}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
        Route::put('users/{id}', [AdminController::class, 'update'])->name('admin.users.update');
        Route::delete('users/{id}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
    });

    // Infrastructure management
    Route::get('infrastructures/api/data', [InfrastructureController::class, 'getInfrastructuresData'])->name('infrastructures.api.data');
    Route::resource('infrastructures', InfrastructureController::class);

    // BENAR: API ditaruh di ATAS Route::resource
    Route::get('courses/api/prodis', [CourseController::class, 'getProdi'])->name('courses.api.prodis');
    Route::get('courses/api/data', [CourseController::class, 'getCoursesData'])->name('courses.api.data');
    Route::post('courses/bulk', [CourseController::class, 'bulkStore'])->name('courses.bulk.store');
    Route::get('courses/bulk/template', [CourseController::class, 'downloadBulkTemplate'])->name('courses.bulk.template');

    // CPMK per mata kuliah
    Route::get('courses/{course}/cpmk/api/data', [CpmkController::class, 'getCpmkData'])->name('courses.cpmk.api.data');
    Route::post('courses/{course}/cpmk', [CpmkController::class, 'store'])->name('courses.cpmk.store');
    Route::put('courses/{course}/cpmk/{cpmk}', [CpmkController::class, 'update'])->name('courses.cpmk.update');
    Route::delete('courses/{course}/cpmk/{cpmk}', [CpmkController::class, 'destroy'])->name('courses.cpmk.destroy');

    // Pemetaan CPL ↔ CPMK per mata kuliah
    Route::get('courses/{course}/mapping/api/data', [CplCpmkMappingController::class, 'getMappingData'])->name('courses.mapping.api.data');
    Route::put('courses/{course}/mapping', [CplCpmkMappingController::class, 'sync'])->name('courses.mapping.sync');

    // Route Resource ditaruh di BAWAH API
    Route::resource('courses', CourseController::class);

    // Tahun akademik / periode
    Route::get('periods/api/data', [PeriodController::class, 'getPeriodsData'])->name('periods.api.data');
    Route::resource('periods', PeriodController::class)->except(['create', 'edit', 'show']);
});
