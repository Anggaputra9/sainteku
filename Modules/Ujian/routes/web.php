<?php

use Illuminate\Support\Facades\Route;
use Modules\Ujian\Http\Controllers\AttemptController;
use Modules\Ujian\Http\Controllers\ExportController;
use Modules\Ujian\Http\Controllers\RoomController;
use Modules\Ujian\Http\Controllers\UjianController;

/*
|--------------------------------------------------------------------------
| Ujian Module Routes
|--------------------------------------------------------------------------
| Konvensi:
| - Halaman dosen (/ujian/rooms): single page Alpine; semua CRUD dilakukan
|   lewat modal yang memanggil endpoint JSON di controller.
| - Resource binding pakai UUID (lihat ExamRoom::getRouteKeyName()), jadi
|   URL admin pakai uuid bukan auto-increment id (lebih sulit ditebak).
| - Mahasiswa join ujian pakai room_code 6 char yang QR-friendly.
| - Hasil ujian (attempt) juga di-resolve via uuid.
*/
Route::middleware(['auth'])->group(function () {

    Route::get('ujian', [UjianController::class, 'index'])->name('ujian.index');

    /*
    |---------------------------------------------------------------------------
    | Dosen / Admin → Manajemen Ruang Ujian (modal-only SPA)
    |---------------------------------------------------------------------------
    */
    Route::prefix('ujian/rooms')->name('ujian.rooms.')->group(function () {
        Route::get('/',                       [RoomController::class, 'index'])->name('index');
        Route::get('api/data',                [RoomController::class, 'getRoomsData'])->name('api.data');
        Route::post('/',                      [RoomController::class, 'store'])->name('store');

        Route::get('{room:uuid}',             [RoomController::class, 'show'])->name('show');
        Route::put('{room:uuid}',             [RoomController::class, 'update'])->name('update');
        // POST alias — WAF/Cloudflare prod memblokir PUT/DELETE dari fetch JSON
        Route::post('{room:uuid}/update',      [RoomController::class, 'update'])->name('update.post');
        Route::delete('{room:uuid}',          [RoomController::class, 'destroy'])->name('destroy');
        Route::post('{room:uuid}/delete',      [RoomController::class, 'destroy'])->name('destroy.post');
        Route::delete('{room:uuid}/attempts/{attempt:uuid}', [RoomController::class, 'destroyAttempt'])->name('attempts.destroy');
        Route::post('{room:uuid}/attempts/{attempt:uuid}/delete', [RoomController::class, 'destroyAttempt'])->name('attempts.destroy.post');

        Route::post('{room:uuid}/start',      [RoomController::class, 'start'])->name('start');
        Route::post('{room:uuid}/close',      [RoomController::class, 'close'])->name('close');
        Route::post('{room:uuid}/reopen',     [RoomController::class, 'reopen'])->name('reopen');
        Route::post('{room:uuid}/attempts/{attempt:uuid}/reset-violation', [RoomController::class, 'resetViolation'])->name('attempts.reset-violation');
        Route::post('{room:uuid}/attempts/{attempt:uuid}/grade', [RoomController::class, 'gradeAttempt'])->name('attempts.grade');

        // Batch AI Grading
        Route::post('{room:uuid}/grade-all-attempts', [RoomController::class, 'gradeAllAttempts'])->name('grade-all-attempts');
        Route::get('{room:uuid}/grading-progress',    [RoomController::class, 'gradingProgress'])->name('grading-progress');
        Route::post('{room:uuid}/cancel-grading',     [RoomController::class, 'cancelGrading'])->name('cancel-grading');

        // Export PDF
        Route::get('{room:uuid}/export-pdf',  [ExportController::class, 'exportRoomResults'])->name('export-pdf');

        // Polling AJAX untuk live monitoring di modal Detail
        Route::get('{room:uuid}/live-monitor', [RoomController::class, 'liveMonitor'])->name('live-monitor');
    });

    /*
    |---------------------------------------------------------------------------
    | Mahasiswa → Mengerjakan Ujian
    |---------------------------------------------------------------------------
    | Endpoint /ujian/attempt/scan: terima param ?code=ABCDEF dari hasil
    | scan QR room ujian. Controller akan validasi & lanjut ke join.
    */
    Route::prefix('ujian/attempt')->name('ujian.attempt.')->group(function () {
        Route::get('join',                  [AttemptController::class, 'join'])->name('join');
        Route::post('join',                 [AttemptController::class, 'joinSubmit'])->name('join.submit');
        Route::get('scan',                  [AttemptController::class, 'scan'])->name('scan');

        Route::get('{code}/work',           [AttemptController::class, 'work'])->name('work');
        Route::post('{code}/save-answer',   [AttemptController::class, 'saveAnswer'])->name('save-answer');
        Route::post('{code}/event',         [AttemptController::class, 'recordEvent'])->name('event');
        Route::post('{code}/submit',        [AttemptController::class, 'submit'])->name('submit');
        Route::get('{code}/finished',       [AttemptController::class, 'finished'])->name('finished');

        // Hasil ujian per attempt (uuid). Dipakai dosen untuk melihat hasil
        // mahasiswa tertentu, atau mahasiswa yang sudah selesai untuk
        // melihat ringkasan hasilnya — semua via uuid (tidak bisa ditebak).
        Route::get('result/{attempt:uuid}',  [AttemptController::class, 'result'])->name('result');

        // AI Grading routes
        Route::post('answer/{answer}/ai',           [AttemptController::class, 'gradeWithAi'])->name('grade-ai');
        Route::post('{attempt:uuid}/grade-all-ai',  [AttemptController::class, 'gradeAllWithAi'])->name('grade-all-ai');
    });

});
