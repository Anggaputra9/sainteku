<?php

use Illuminate\Support\Facades\Route;
use Modules\DocumentRepository\Http\Controllers\DocumentRepositoryController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('documentrepositories', DocumentRepositoryController::class)->names('documentrepository');
});
