<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    /**
     * Check if user is logged in and has admin status
     */
    private function checkAdmin()
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action. Hanya Admin yang dapat mengakses halaman ini.');
        }
    }

    /**
     * Display admin overview
     */
    public function index(Request $request)
    {
        $this->checkAdmin();

        $search = $request->input('search');

        // Calculate statistics
        $totalUsers = User::count();
        $totalLands = Lahan::count();
        $totalAdmins = User::where('is_admin', true)->count();
        $avgLands = $totalUsers > 0 ? round($totalLands / $totalUsers, 1) : 0;

        // Fetch users with search and pagination
        $usersQuery = User::withCount('lahans')->latest();

        if ($search) {
            $usersQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $usersQuery->paginate(10)->withQueryString();

        // Recent analysis activity
        $recentLands = Lahan::with('user')->latest()->take(10)->get();

        // 1. Chart Data: Distribusi rekomendasi pupuk
        $fertilizers = Lahan::select('rekomendasi_pupuk', DB::raw('count(*) as total'))
            ->whereNotNull('rekomendasi_pupuk')
            ->groupBy('rekomendasi_pupuk')
            ->pluck('total', 'rekomendasi_pupuk')
            ->toArray();

        // 2. Chart Data: Pertumbuhan analisis bulanan (6 bulan terakhir)
        $monthlyGrowth = Lahan::select(
                DB::raw("strftime('%Y-%m', created_at) as month"), 
                DB::raw('count(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->take(6)
            ->pluck('total', 'month')
            ->toArray();

        // 3. Health & Dataset Monitor
        $dbStatus = true;
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $dbStatus = false;
        }

        $mlEngineStatus = File::exists(base_path('ml_engine/app.py'));
        $diskFreeSpace = round(disk_free_space(base_path()) / (1024 * 1024 * 1024), 2) . ' GB';

        return view('admin', compact(
            'totalUsers', 
            'totalLands', 
            'totalAdmins', 
            'avgLands', 
            'users', 
            'recentLands',
            'fertilizers',
            'monthlyGrowth',
            'dbStatus',
            'mlEngineStatus',
            'diskFreeSpace'
        ));
    }

    /**
     * Return chart data filtered by period (AJAX)
     */
    public function chartData(Request $request)
    {
        $this->checkAdmin();
        $period = $request->input('period', 'bulanan');

        switch ($period) {
            case 'harian':
                // 30 hari terakhir
                $data = Lahan::select(
                        DB::raw("strftime('%Y-%m-%d', created_at) as label"),
                        DB::raw('count(*) as total')
                    )
                    ->where('created_at', '>=', now()->subDays(30))
                    ->groupBy('label')
                    ->orderBy('label')
                    ->pluck('total', 'label')
                    ->toArray();
                break;

            case 'mingguan':
                // 12 minggu terakhir
                $data = Lahan::select(
                        DB::raw("strftime('%Y-W%W', created_at) as label"),
                        DB::raw('count(*) as total')
                    )
                    ->where('created_at', '>=', now()->subWeeks(12))
                    ->groupBy('label')
                    ->orderBy('label')
                    ->pluck('total', 'label')
                    ->toArray();
                break;

            case 'tahunan':
                // Semua tahun
                $data = Lahan::select(
                        DB::raw("strftime('%Y', created_at) as label"),
                        DB::raw('count(*) as total')
                    )
                    ->groupBy('label')
                    ->orderBy('label')
                    ->pluck('total', 'label')
                    ->toArray();
                break;

            default: // bulanan
                $data = Lahan::select(
                        DB::raw("strftime('%Y-%m', created_at) as label"),
                        DB::raw('count(*) as total')
                    )
                    ->where('created_at', '>=', now()->subMonths(12))
                    ->groupBy('label')
                    ->orderBy('label')
                    ->pluck('total', 'label')
                    ->toArray();
                break;
        }

        return response()->json([
            'labels' => array_keys($data),
            'values' => array_values($data),
        ]);
    }

    /**
     * Toggle admin status for a user
     */
    public function toggleAdmin(User $user)
    {
        $this->checkAdmin();

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mencabut hak akses admin Anda sendiri.');
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        $role = $user->is_admin ? 'dijadikan Admin' : 'dihapus dari Admin';
        return back()->with('success', "Peran pengguna {$user->name} berhasil diubah menjadi {$role}.");
    }

    /**
     * Delete user and their associated records
     */
    public function destroyUser(User $user)
    {
        $this->checkAdmin();

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userName = $user->name;

        // Delete associated records manually to ensure integrity
        $user->lahans()->delete();
        $user->delete();

        return back()->with('success', "Pengguna {$userName} dan semua data lahannya berhasil dihapus dari sistem.");
    }
}
