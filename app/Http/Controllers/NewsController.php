<?php

namespace App\Http\Controllers;

use App\Models\ScrapedNews;
use Illuminate\View\View;

class NewsController extends Controller
{
    /**
     * Tampilkan landing page dengan berita terbaru
     */
    public function index(): View
    {
        $latestNews = ScrapedNews::latestNews(6)->get();

        return view('landing', compact('latestNews'));
    }
}