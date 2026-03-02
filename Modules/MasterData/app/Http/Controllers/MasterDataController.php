<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Masterdata\app\models\User; // Pastikan namespace Model User benar
use Modules\MasterData\app\Models\Unit; // Pastikan namespace Model Unit benar
use Modules\MasterData\app\Models\Role; // Sesuaikan dengan nama model Role kamu
// use Modules\MasterData\Models\Curriculum; // Aktifkan jika model Kurikulum sudah ada

class MasterDataController extends Controller
{
    public function index()
    {
        // Mengambil total data secara real-time dari database
        $totalUsers = User::count();
        $totalRoles = Role::count();
        $totalUnits = Unit::count();
        
        // Contoh untuk Kurikulum (Pastikan modelnya sudah dibuat/di-import)
        $totalCurricula = 0; // Ganti dengan Curriculum::count() jika sudah ada

        return view('masterdata::index', compact(
            'totalUsers', 
            'totalRoles', 
            'totalUnits', 
            'totalCurricula'
        ));
    }
}