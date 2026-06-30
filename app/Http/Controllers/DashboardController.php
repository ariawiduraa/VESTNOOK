<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lahan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Lahan::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nama_lahan', 'like', "%{$search}%")
                  ->orWhere('hasil_cluster', 'like', "%{$search}%")
                  ->orWhere('rekomendasi_pupuk', 'like', "%{$search}%");
            });
        }

        $riwayat = $query->latest()->get();
        
        return view('dashboard', compact('riwayat'));
    }
}
