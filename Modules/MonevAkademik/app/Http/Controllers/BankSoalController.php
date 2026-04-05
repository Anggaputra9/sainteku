<?php

namespace Modules\MonevAkademik\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\MonevAkademik\App\Models\Question;

class BankSoalController extends Controller
{
    // Halaman utama Bank Soal (nanti kita buat UI-nya menyusul)
    public function index()
    {
        return view('monevakademik::banksoal.index');
    }

    // Endpoint API untuk ditarik ke dalam Modal Form Pengajuan
    public function getApiQuestions($course_id)
    {
        $questions = Question::with('cpmk')
            ->where('course_id', $course_id)
            // INI KUNCI FILTERNYA: Hanya ambil soal yang punya pengajuan berstatus APPROVED
            ->whereHas('examQuestions.proposal', function ($query) {
                $query->where('status', 'APPROVED');
            })
            ->latest()
            ->get();

        return response()->json($questions);
    }
}