<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\models\User; // Pastikan namespace Model User benar
use App\Models\Unit; // Pastikan namespace Model Unit benar
use App\Models\Role; // Sesuaikan dengan nama model Role kamu
use App\Models\Period;
// use Modules\MasterData\Models\Curriculum; // Aktifkan jika model Kurikulum sudah ada

class MasterDataController extends Controller
{

    public static function middleware(): array
    {
        return [
            // Panggil alias 'role' lu di sini
            new Middleware('role:ADM|Administrator'),
        ];
    }

    public function index()
    {
        // Mengambil total data secara real-time dari database
        $totalUsers = User::count();
        $totalRoles = Role::count();
        $totalUnits = Unit::count();

        $totalPeriods = Period::count();

        return view('masterdata::index', compact(
            'totalUsers',
            'totalRoles',
            'totalUnits',
            'totalPeriods'
        ))->with('title', 'Dashboard Master Data');
    }
}