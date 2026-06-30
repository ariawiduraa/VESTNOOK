<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel – VESTNOOK</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    

    
    <style>
        body { font-family: 'Inter', sans-serif; transition: background-color 0.25s, color 0.25s; }
    </style>
    <link rel="icon" type="image/png" href="{{ asset('images/logovestnook.png') }}">
</head>
<body class="antialiased min-h-screen bg-slate-50 text-slate-800">

    <!-- Navbar -->
    <nav class="w-full h-16 px-6 flex items-center justify-between bg-white border-b border-slate-200/80 sticky top-0 z-50 shadow-sm">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logovestnook.png') }}" alt="VESTNOOK" class="w-7 h-7 object-contain">
            <a href="/" class="font-bold text-lg text-red-655 tracking-tight flex items-center gap-1.5">
                VESTNOOK <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-600 uppercase tracking-wider">Admin</span>
            </a>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-full transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Dashboard User
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-semibold transition-colors px-4 py-2 hover:bg-red-50 rounded-full">Logout</button>
            </form>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <!-- Flash alerts -->
        @if(session('success'))
            <div class="px-4 py-3.5 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm font-semibold flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="px-4 py-3.5 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-semibold flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Welcome Banner -->
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Selamat Datang, Admin</h1>
            <p class="text-sm text-slate-500 mt-1">Gunakan panel analitik ini untuk mengelola pengguna, memantau infrastruktur model, dan memantau kesehatan server.</p>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-semibold text-slate-450 uppercase tracking-wider">Total Pengguna</span>
                    <div class="p-2 rounded-lg bg-blue-50 text-blue-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-900">{{ $totalUsers }}</div>
                <p class="text-xs text-slate-400 mt-2">Terdaftar di database</p>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-semibold text-slate-450 uppercase tracking-wider">Total Analisis Lahan</span>
                    <div class="p-2 rounded-lg bg-green-50 text-green-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-900">{{ $totalLands }}</div>
                <p class="text-xs text-slate-400 mt-2">Hasil pengujian petani</p>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-semibold text-slate-450 uppercase tracking-wider">Rasio Lahan / User</span>
                    <div class="p-2 rounded-lg bg-amber-50 text-amber-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-900">{{ $avgLands }}</div>
                <p class="text-xs text-slate-400 mt-2">Rata-rata lahan per pengguna</p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Sebaran Rekomendasi Pupuk AI</h3>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="fertilizerChart"></canvas>
                </div>
            </div>
            
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4 gap-2 flex-wrap">
                    <h3 class="text-sm font-bold text-slate-800">Tren Aktivitas Analisis Lahan</h3>
                    <div class="flex items-center gap-1 bg-slate-100 rounded-xl p-1">
                        <button onclick="setFilter('harian')"   id="btn-harian"   class="filter-btn px-3 py-1 text-xs font-semibold rounded-lg transition-all text-slate-500">Harian</button>
                        <button onclick="setFilter('mingguan')" id="btn-mingguan" class="filter-btn px-3 py-1 text-xs font-semibold rounded-lg transition-all text-slate-500">Mingguan</button>
                        <button onclick="setFilter('bulanan')"  id="btn-bulanan"  class="filter-btn px-3 py-1 text-xs font-semibold rounded-lg transition-all text-slate-500 bg-white shadow text-blue-600">Bulanan</button>
                        <button onclick="setFilter('tahunan')"  id="btn-tahunan"  class="filter-btn px-3 py-1 text-xs font-semibold rounded-lg transition-all text-slate-500">Tahunan</button>
                    </div>
                </div>
                <div class="h-64 flex items-center justify-center relative">
                    <canvas id="growthChart"></canvas>
                    <div id="chart-loading" class="hidden absolute inset-0 flex items-center justify-center bg-white/80 rounded-xl">
                        <svg class="animate-spin w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Health Monitor -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-800">Server & Health Monitor</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                <div class="flex justify-between items-center py-3 border-b md:border-b-0 md:border-r border-slate-100 pr-0 md:pr-6">
                    <span class="text-slate-450">Koneksi Database</span>
                    @if($dbStatus)
                        <span class="px-2 py-0.5 text-xs font-semibold rounded bg-green-50 text-green-700">ONLINE (SQLite)</span>
                    @else
                        <span class="px-2 py-0.5 text-xs font-semibold rounded bg-red-50 text-red-700">OFFLINE</span>
                    @endif
                </div>
                <div class="flex justify-between items-center py-3 border-b md:border-b-0 md:border-r border-slate-100 px-0 md:px-6">
                    <span class="text-slate-450">Python ML Module</span>
                    @if($mlEngineStatus)
                        <span class="px-2 py-0.5 text-xs font-semibold rounded bg-green-50 text-green-700">TERHUBUNG (app.py)</span>
                    @else
                        <span class="px-2 py-0.5 text-xs font-semibold rounded bg-red-50 text-red-700">TERPUTUS</span>
                    @endif
                </div>
                <div class="flex justify-between items-center py-3 pl-0 md:pl-6">
                    <span class="text-slate-450">Sisa Disk Server</span>
                    <span class="font-bold">{{ $diskFreeSpace }}</span>
                </div>
            </div>
        </div>

        <!-- Users Table Card -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Manajemen Pengguna</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar seluruh pengguna aktif dan hak akses perannya.</p>
                </div>
                
                <!-- Search -->
                <form method="GET" action="{{ route('admin.index') }}" class="relative w-full sm:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." 
                           class="w-full pl-9 pr-4 py-2 border border-slate-200 text-slate-900 rounded-xl text-xs focus:outline-none focus:border-blue-600 transition-colors">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-bold text-slate-450 uppercase border-b border-slate-100 tracking-wider">
                            <th class="py-4 px-6">Nama Pengguna</th>
                            <th class="py-4 px-6">Email</th>
                            <th class="py-4 px-6">Total Lahan</th>
                            <th class="py-4 px-6">Peran</th>
                            <th class="py-4 px-6 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @foreach($users as $u)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 font-semibold text-slate-900">{{ $u->name }}</td>
                            <td class="py-4 px-6 text-slate-450">{{ $u->email }}</td>
                            <td class="py-4 px-6 font-semibold">{{ $u->lahans_count }} Lahan</td>
                            <td class="py-4 px-6">
                                @if($u->is_admin)
                                    <span class="px-2 py-0.5 text-[9px] font-bold bg-red-50 text-red-600 rounded-full tracking-wide">ADMIN</span>
                                @else
                                    <span class="px-2 py-0.5 text-[9px] font-bold bg-slate-100 text-slate-600 rounded-full tracking-wide">USER</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.users.toggle', $u->id) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 text-[10px] font-bold border border-slate-200 hover:bg-slate-50 rounded-lg transition-all text-slate-700">
                                            {{ $u->is_admin ? 'Jadikan User' : 'Jadikan Admin' }}
                                        </button>
                                    </form>
                                    @if($u->id !== Auth::id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}"
                                              onsubmit="return confirmAction(this, event, 'Apakah Anda yakin ingin menghapus akun pengguna ini beserta seluruh data lahannya?', 'danger')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 text-[10px] font-bold bg-red-50 hover:bg-red-100 text-red-500 rounded-lg transition-all">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>

    </main>

    <script>
        // Data pupuk untuk Chart
        const fertData = @json($fertilizers);
        const fertLabels = Object.keys(fertData);
        const fertValues = Object.values(fertData);

        // Data pertumbuhan untuk Chart
        const growthData = @json($monthlyGrowth);
        const growthLabels = Object.keys(growthData);
        const growthValues = Object.values(growthData);

        // Render Chart Rekomendasi Pupuk
        new Chart(document.getElementById('fertilizerChart'), {
            type: 'doughnut',
            data: {
                labels: fertLabels.length ? fertLabels : ['Belum Ada Data'],
                datasets: [{
                    data: fertValues.length ? fertValues : [1],
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#cbd5e1'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#4b5563', font: { size: 11 } }
                    }
                }
            }
        });

        // Render Chart Tren Analisis (initial)
        let growthChart = new Chart(document.getElementById('growthChart'), {
            type: 'line',
            data: {
                labels: growthLabels.length ? growthLabels : ['Belum Ada Data'],
                datasets: [{
                    label: 'Analisis Lahan',
                    data: growthValues.length ? growthValues : [0],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.08)',
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#3b82f6',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#4b5563', maxTicksLimit: 8 } },
                    y: { ticks: { precision: 0, color: '#4b5563' }, beginAtZero: true }
                }
            }
        });

        /* ── Filter logic ── */
        let activeFilter = 'bulanan';
        const CHART_URL  = '{{ route("admin.chart.data") }}';

        function setFilter(period) {
            if (period === activeFilter) return;
            activeFilter = period;

            // Update button styles
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'shadow', 'text-blue-600');
                btn.classList.add('text-slate-500');
            });
            const active = document.getElementById('btn-' + period);
            active.classList.add('bg-white', 'shadow', 'text-blue-600');
            active.classList.remove('text-slate-500');

            // Show loading
            document.getElementById('chart-loading').classList.remove('hidden');

            fetch(CHART_URL + '?period=' + period, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                growthChart.data.labels   = data.labels.length ? data.labels : ['Belum Ada Data'];
                growthChart.data.datasets[0].data = data.values.length ? data.values : [0];
                growthChart.update();
            })
            .catch(err => console.error('Chart error:', err))
            .finally(() => document.getElementById('chart-loading').classList.add('hidden'));
        }
    </script>
</body>
</html>
