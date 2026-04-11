<?php

namespace Modules\ManajementInfrastruktur\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ManajementInfrastrukturController extends Controller
{
    private $moduleId = 6;

    // =========================================================================
    // 1. DASHBOARD
    // =========================================================================
   public function index()
    {
        if (!Auth::user()->hasPermission($this->moduleId, 'R')) {
            abort(403, 'Unauthorized');
        }

        // 1. Ambil data statistik untuk Widget/Card
        $totalBarang = DB::table('mst_inventory')->where('status', 1)->count();
        $totalPending = DB::table('trx_inventory_loans')->where('status', 0)->count();
        $totalDipinjam = DB::table('trx_inventory_loans')->where('status', 1)->count();
        $totalSelesai = DB::table('trx_inventory_loans')->where('status', 3)->count();

        // 2. Ambil 5 aktivitas peminjaman terbaru untuk tabel mini
        $transaksiTerbaru = DB::table('trx_inventory_loans')
            ->join('mst_inventory', 'trx_inventory_loans.inventory_id', '=', 'mst_inventory.id')
            ->join('mst_user', 'trx_inventory_loans.user_id', '=', 'mst_user.id')
            ->select('trx_inventory_loans.*', 'mst_inventory.item_name', 'mst_user.name as user_name')
            ->orderBy('trx_inventory_loans.created_at', 'desc')
            ->limit(5)
            ->get();

        // PASTIKAN BARIS INI PERSIS SEPERTI INI:
        return view('manajementinfrastruktur::index', compact(
            'totalBarang', 
            'totalPending', 
            'totalDipinjam', 
            'totalSelesai', 
            'transaksiTerbaru'
        ));
    }

    // =========================================================================
    // 2. PENGAJUAN PEMINJAMAN (Menu User)
    // =========================================================================
    public function pengajuanIndex(Request $request)
    {
        if (!Auth::user()->hasPermission($this->moduleId, 'C')) {
            abort(403, 'Unauthorized');
        }

        $user = Auth::user();
        
        // 1. Tangkap status filter dari URL (default: 'all')
        $filterStatus = $request->query('status', 'all');

        // 2. Siapkan Query Dasar
        $query = DB::table('trx_inventory_loans')
            ->join('mst_inventory', 'trx_inventory_loans.inventory_id', '=', 'mst_inventory.id')
            ->where('trx_inventory_loans.user_id', $user->id)
            ->select('trx_inventory_loans.*', 'mst_inventory.item_name', 'mst_inventory.photo');

        // 3. Terapkan Filter jika ada
        if ($filterStatus === 'pending') {
            $query->where('trx_inventory_loans.status', 0); // Menunggu
        } elseif ($filterStatus === 'approved') {
            $query->where('trx_inventory_loans.status', 1); // Disetujui
        } elseif ($filterStatus === 'rejected') {
            $query->where('trx_inventory_loans.status', 2); // Ditolak
        } elseif ($filterStatus === 'returned') {
            $query->where('trx_inventory_loans.status', 3); // Dikembalikan
        }

        // 4. Eksekusi query
        $riwayat = $query->orderBy('trx_inventory_loans.created_at', 'desc')->get();

        // Ambil daftar barang yang tersedia
        $inventories = DB::table('mst_inventory')
            ->where('status', 1)
            ->where('stock', '>', 0)
            ->get();

        return view('manajementinfrastruktur::pengajuan.index', compact('riwayat', 'inventories', 'filterStatus'));
    }

    public function pengajuanStore(Request $request)
    {
        if (!Auth::user()->hasPermission($this->moduleId, 'C')) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'inventory_id' => 'required',
            'quantity'     => 'required|integer|min:1',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'purpose'      => 'required|string|max:500',
        ]);

        // Generate Kode Peminjaman (Contoh: TRX-20260315-A1B2)
        $loanCode = 'TRX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        DB::table('trx_inventory_loans')->insert([
            'loan_code'    => $loanCode,
            'user_id'      => Auth::user()->id,
            'inventory_id' => $request->inventory_id,
            'quantity'     => $request->quantity,
            'start_date'   => $request->start_date,
            'end_date'     => $request->end_date,
            'purpose'      => $request->purpose,
            'status'       => 0, // 0 = Menunggu (Pending)
            'created_at'   => now(),
        ]);

        return redirect()->route('manajementinfrastruktur.pengajuan.index')
            ->with('success', 'Pengajuan berhasil dikirim! Silakan tunggu persetujuan Admin.');
    }

    // =========================================================================
    // 3. ACC / PERSETUJUAN PEMINJAMAN (Menu Admin)
    // =========================================================================
    public function persetujuanIndex(Request $request)
    {
        if (!Auth::user()->hasPermission($this->moduleId, 'A')) {
            abort(403, 'Unauthorized');
        }

        // 1. Tangkap status filter dari URL (Default: 'pending' agar admin langsung fokus ke yang butuh ACC)
        $filterStatus = $request->query('status', 'pending');

        // 2. Siapkan Query Dasar
        $query = DB::table('trx_inventory_loans')
            ->join('mst_inventory', 'trx_inventory_loans.inventory_id', '=', 'mst_inventory.id')
            ->join('mst_user', 'trx_inventory_loans.user_id', '=', 'mst_user.id')
            ->select('trx_inventory_loans.*', 'mst_inventory.item_name', 'mst_user.name as user_name');

        // 3. Terapkan Filter
        if ($filterStatus === 'pending') {
            $query->where('trx_inventory_loans.status', 0); // Menunggu
        } elseif ($filterStatus === 'approved') {
            $query->where('trx_inventory_loans.status', 1); // Disetujui
        } elseif ($filterStatus === 'rejected') {
            $query->where('trx_inventory_loans.status', 2); // Ditolak
        } elseif ($filterStatus === 'returned') {
            $query->where('trx_inventory_loans.status', 3); // Dikembalikan
        }

        // 4. Eksekusi query
        $peminjaman = $query->orderBy('trx_inventory_loans.created_at', 'desc')->get();

        return view('manajementinfrastruktur::persetujuan.index', compact('peminjaman', 'filterStatus'));
    }

    public function persetujuanUpdate(Request $request, $id)
    {
        if (!Auth::user()->hasPermission($this->moduleId, 'A')) {
            abort(403, 'Unauthorized');
        }

        // Status: 1=Setuju, 2=Tolak, 3=Selesai/Dikembalikan
        $request->validate([
            'status'     => 'required|in:1,2,3', 
            'admin_note' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction(); // Gunakan transaksi agar data aman

            $loan = DB::table('trx_inventory_loans')->where('id', $id)->first();
            if (!$loan) {
                return back()->with('error', 'Data peminjaman tidak ditemukan!');
            }

            // Aksi 1: Jika disetujui, KURANGI stok barang di Master
            if ($request->status == 1 && $loan->status == 0) {
                DB::table('mst_inventory')->where('id', $loan->inventory_id)->decrement('stock', $loan->quantity);
            }
            
            // Aksi 2: Jika barang dikembalikan (Selesai), TAMBAH/kembalikan stok barang
            if ($request->status == 3 && $loan->status == 1) {
                DB::table('mst_inventory')->where('id', $loan->inventory_id)->increment('stock', $loan->quantity);
            }

            // Update status di tabel transaksi
            DB::table('trx_inventory_loans')->where('id', $id)->update([
                'status'      => $request->status,
                'admin_note'  => $request->admin_note,
                'approved_by' => Auth::user()->id,
                'updated_at'  => now(),
            ]);

            DB::commit();
            return back()->with('success', 'Status peminjaman berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}