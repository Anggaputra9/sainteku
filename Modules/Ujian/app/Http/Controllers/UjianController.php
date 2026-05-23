<?php

namespace Modules\Ujian\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Entry-point modul Ujian.
 * Admin/Dosen → diarahkan ke daftar Ruang Ujian (RoomController).
 * Mahasiswa   → diarahkan ke halaman join via kode ruang.
 */
class UjianController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $isDosen = $user->roles()->whereIn('role_code', ['ADM', 'DSN', 'KPD'])->exists();

        return $isDosen
            ? redirect()->route('ujian.rooms.index')
            : redirect()->route('ujian.attempt.join');
    }
}
