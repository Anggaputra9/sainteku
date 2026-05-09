<?php

namespace Modules\ManajemenAchievement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManajemenAchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('manajemenachievement::index')->with('title', 'Daftar Prestasi Saya');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('manajemenachievement::create')->with('title', 'Tambah Prestasi Baru');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('manajemenachievement::show')->with('title', 'Detail Prestasi');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('manajemenachievement::edit')->with('title', 'Edit Prestasi');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}

    
}
