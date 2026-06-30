<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title data-en="Dashboard – VESTNOOK" data-id="Dashboard – VESTNOOK">Dashboard – VESTNOOK</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    

    
    <style>
        body { font-family: 'Inter', sans-serif; transition: background-color 0.25s, color 0.25s; }
    </style>
    <link rel="icon" type="image/png" href="{{ asset('images/logovestnook.png') }}">
</head>
<body class="antialiased min-h-screen bg-slate-50 text-slate-800">

    <!-- Navbar -->
    <nav class="w-full h-16 px-6 flex items-center justify-between bg-white border-b border-slate-200/80 sticky top-0 z-50 shadow-sm">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logovestnook.png') }}" alt="VESTNOOK" style="width:28px;height:28px;object-fit:contain;">
            <a href="/" class="font-bold text-lg text-blue-600 tracking-tight">VESTNOOK</a>
        </div>
        <div class="flex items-center gap-3">
            @if(Auth::user()->is_admin)
                <a href="{{ route('admin.index') }}" class="text-sm font-semibold text-white bg-red-500 hover:bg-red-600 px-4 py-2 rounded-full transition-colors">Admin Panel</a>
            @endif
            <a href="{{ route('analisis.index') }}" class="text-sm text-slate-600 hover:text-blue-600 font-medium hidden sm:block" data-en="New Analysis" data-id="Analisis Baru">Analisis Baru</a>
            <span class="text-sm text-slate-400 hidden sm:block">
                <span data-en="Hello, " data-id="Halo, ">Halo, </span>
                <strong class="text-slate-700">{{ Auth::user()->name }}</strong>
            </span>

            {{-- Avatar Dropdown --}}
            <div class="relative" id="avatar-menu-wrap">
                <button type="button" id="avatar-menu-btn" onclick="toggleAvatarMenu()"
                    class="flex items-center focus:outline-none group" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ Auth::user()->avatarUrl() }}" alt="avatar"
                        style="width:36px;height:36px;border-radius:50%;object-fit:cover;"
                        class="border-2 border-slate-200 group-hover:border-blue-400 transition-all shadow-sm">
                </button>

                {{-- Dropdown --}}
                <div id="avatar-dropdown"
                    class="hidden absolute right-0 mt-2 w-52 bg-white border border-slate-200 rounded-2xl shadow-xl py-1.5 z-50"
                    style="top:100%;">

                    {{-- User info --}}
                    <div class="px-4 py-3 border-b border-slate-100">
                        <p class="text-xs font-semibold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-400 truncate mt-0.5">{{ Auth::user()->email }}</p>
                    </div>

                    {{-- Menu items --}}
                    <a href="{{ route('profile.settings') }}"
                        class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Pengaturan Profil
                    </a>

                    <div class="border-t border-slate-100 my-1"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors text-left">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-10">

        <!-- Flash messages -->
        @if(session('success'))
            <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 border border-green-100 text-green-700 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- Header + CTA + Search -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full md:w-auto flex-grow">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight" data-en="Land Analysis History" data-id="Riwayat Analisis Lahan">Riwayat Analisis Lahan</h1>
                    <p class="text-sm text-slate-500 mt-1" data-en="List of all analyzed fields and AI fertilizer recommendations." data-id="Daftar seluruh analisis lahan dan rekomendasi pupuk AI Anda.">Daftar seluruh analisis lahan dan rekomendasi pupuk AI Anda.</p>
                </div>
                
                <!-- Search Form -->
                <form method="GET" action="{{ route('dashboard') }}" class="relative w-full sm:w-64 flex-shrink-0 mt-4 sm:mt-0">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari lahan atau pupuk..." data-en-placeholder="Search fields or fertilizer..." data-id-placeholder="Cari lahan atau pupuk..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 text-slate-900 rounded-full text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-colors">
                    <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    @if(request('search'))
                        <a href="{{ route('dashboard') }}" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </form>
            </div>
            
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <a href="{{ route('lahan.compare') }}" 
               class="px-5 py-3 border border-slate-200 text-slate-700 bg-white hover:bg-slate-55 rounded-xl text-sm font-semibold shadow-sm focus:outline-none transition-all flex items-center justify-center gap-2 flex-grow sm:flex-grow-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                <span data-en="Compare Lands" data-id="Bandingkan Lahan">Bandingkan Lahan</span>
            </a>
            <a href="{{ route('analisis.index') }}" 
               class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm focus:outline-none focus:ring-4 focus:ring-blue-100 transition-all flex items-center justify-center gap-2 flex-grow sm:flex-grow-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                <span data-en="New Analysis" data-id="Analisis Baru">Analisis Baru</span>
            </a>
        </div>
        </div>

        <!-- History Content -->
        @if($riwayat->isEmpty())
            <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900" data-en="No analysis history found" data-id="Belum ada riwayat analisis">Belum ada riwayat analisis</h3>
                <p class="text-sm text-slate-500 mt-1 mb-6" data-en="Create your first field analysis and optimize crop nutrient outputs." data-id="Mulai analisis pertama Anda untuk mendeteksi nutrisi tanah dengan AI.">Mulai analisis pertama Anda untuk mendeteksi nutrisi tanah dengan AI.</p>
                <a href="{{ route('analisis.index') }}" 
                   class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition-all shadow-sm focus:outline-none" data-en="Start Analysis" data-id="Mulai Analisis">
                    Mulai Analisis
                </a>
            </div>
        @else
            <!-- Desktop Table View -->
            <div class="hidden sm:block bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase border-b border-slate-100">
                            <th class="py-4 px-6" data-en="Land Name" data-id="Nama Lahan">Nama Lahan</th>
                            <th class="py-4 px-6" data-en="Cluster Zone" data-id="Zonasi Lahan">Zonasi Lahan</th>
                            <th class="py-4 px-6" data-en="AI Recommendation" data-id="Rekomendasi Pupuk">Rekomendasi Pupuk</th>
                            <th class="py-4 px-6" data-en="Analysis Date" data-id="Tanggal Input">Tanggal Input</th>
                            <th class="py-4 px-6 text-right" data-en="Actions" data-id="Pilihan">Pilihan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                        @foreach($riwayat as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="py-4 px-6 font-semibold text-slate-900">{{ $item->nama_lahan }}</td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600">
                                    {{ $item->hasil_cluster ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-800 uppercase">{{ $item->rekomendasi_pupuk ?? '-' }}</td>
                            <td class="py-4 px-6 text-slate-450">{{ $item->created_at->format('d M Y') }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('lahan.show', $item->id) }}"
                                       class="px-3 py-1.5 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-full transition-colors" data-en="Detail" data-id="Lihat Detail">
                                        Lihat Detail
                                    </a>
                                    <a href="{{ route('lahan.edit', $item->id) }}"
                                       class="px-3 py-1.5 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-full transition-colors" data-en="Edit" data-id="Edit">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('lahan.destroy', $item->id) }}"
                                          onsubmit="return confirmAction(this, event, 'Apakah Anda yakin ingin menghapus data lahan ini?', 'danger')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 text-xs font-semibold text-red-500 bg-red-50 hover:bg-red-100 rounded-full transition-colors" data-en="Delete" data-id="Hapus">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="sm:hidden flex flex-col gap-3">
                @foreach($riwayat as $item)
                <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $item->nama_lahan }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $item->created_at->format('d M Y') }}</p>
                        </div>
                        <span class="text-sm font-bold text-blue-600 uppercase">{{ $item->rekomendasi_pupuk ?? '-' }}</span>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 mb-4">
                        {{ $item->hasil_cluster ?? '-' }}
                    </span>
                    <div class="flex gap-2 pt-3 border-t border-slate-100">
                        <a href="{{ route('lahan.show', $item->id) }}"
                           class="flex-1 text-center py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-full" data-en="Detail" data-id="Detail">Detail</a>
                        <a href="{{ route('lahan.edit', $item->id) }}"
                           class="flex-1 text-center py-2 text-xs font-semibold text-blue-600 bg-blue-50 rounded-full" data-en="Edit" data-id="Edit">Edit</a>
                        <form method="POST" action="{{ route('lahan.destroy', $item->id) }}"
                               onsubmit="return confirmAction(this, event, 'Apakah Anda yakin ingin menghapus data lahan ini?', 'danger')" class="flex-1">
                            @csrf @method('DELETE')
                            <button class="w-full py-2 text-xs font-semibold text-red-500 bg-red-50 rounded-full" data-en="Delete" data-id="Hapus">Hapus</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const lang = localStorage.getItem('lang') || 'id';
            document.documentElement.setAttribute('lang', lang);
            
            // Apply text translation
            document.querySelectorAll('[data-en]').forEach(el => {
                el.textContent = lang === 'en' ? el.getAttribute('data-en') : el.getAttribute('data-id');
            });
            
            // Apply placeholders
            document.querySelectorAll('input[placeholder]').forEach(el => {
                if (el.dataset.enPlaceholder) {
                    el.placeholder = lang === 'en' ? el.dataset.enPlaceholder : el.dataset.idPlaceholder;
                }
            });
        });
    </script>

    <script>
        /* ── Avatar dropdown ── */
        function toggleAvatarMenu() {
            const dd  = document.getElementById('avatar-dropdown');
            const btn = document.getElementById('avatar-menu-btn');
            const open = dd.classList.toggle('hidden');
            btn.setAttribute('aria-expanded', open ? 'false' : 'true');
        }
        document.addEventListener('click', function(e) {
            const wrap = document.getElementById('avatar-menu-wrap');
            if (wrap && !wrap.contains(e.target)) {
                document.getElementById('avatar-dropdown').classList.add('hidden');
                document.getElementById('avatar-menu-btn').setAttribute('aria-expanded','false');
            }
        });
    </script>
</body>
</html>
