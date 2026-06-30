<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalisisController extends Controller
{
    public function index()
    {
        return view('analisis');
    }

    public function store(Request $request)
    {
        // Dummy logic for processing data
        return redirect()->route('dashboard')->with('success', 'Analisis lahan berhasil diproses.');
    }
}
