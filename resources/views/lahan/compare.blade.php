<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title data-en="Compare Lands – VESTNOOK" data-id="Bandingkan Lahan – VESTNOOK">Bandingkan Lahan – VESTNOOK</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    
    <style>
        body { font-family: 'Inter', sans-serif; transition: background-color 0.25s, color 0.25s; }

        /* Skeleton loading */
        .skeleton-line {
            height: 16px; border-radius: 8px;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            margin-bottom: 12px;
        }
        html.dark .skeleton-line {
            background: linear-gradient(90deg, #1e293b 25%, #334155 50%, #1e293b 75%);
            background-size: 200% 100%;
        }
        @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

        .cursor { display: inline-block; width: 2px; height: 1.1em; background: #2563eb; margin-left: 2px; vertical-align: text-bottom; animation: blink 0.75s step-end infinite; }
        @keyframes blink { 50% { opacity: 0; } }
        .fade-in { animation: fadeIn 0.4s ease forwards; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
    </style>
    <link rel="icon" type="image/png" href="{{ asset('images/logovestnook.png') }}">
</head>
<body class="antialiased min-h-screen bg-slate-50 text-slate-800">

    <!-- Navbar -->
    <nav class="w-full h-16 px-6 flex items-center justify-between bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logovestnook.png') }}" alt="VESTNOOK" style="width:28px;height:28px;object-fit:contain;">
            <a href="/" class="font-bold text-lg text-blue-600 tracking-tight">VESTNOOK</a>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors" data-en="Dashboard" data-id="Dashboard">Dashboard</a>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 py-10 space-y-8">
        
        <!-- Back Navigation Button -->
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span data-en="Back to Dashboard" data-id="Kembali ke Dashboard">Kembali ke Dashboard</span>
        </a>

        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight" data-en="Compare Land Analysis" data-id="Bandingkan Hasil Lahan">Bandingkan Hasil Lahan</h1>
            <p class="text-sm text-slate-500 mt-1" data-en="Compare parameter metrics and recommendations between two fields." data-id="Bandingkan metrik parameter dan kecocokan pupuk di antara dua lahan Anda.">Bandingkan metrik parameter dan kecocokan pupuk di antara dua lahan Anda.</p>
        </div>

        <!-- Selector Card -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <form method="GET" action="{{ route('lahan.compare') }}" class="space-y-4">
                <label class="block text-sm font-bold text-slate-700" data-en="Select up to 4 fields to compare" data-id="Pilih hingga 4 lahan untuk dibandingkan">Pilih hingga 4 lahan untuk dibandingkan</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($lahans as $l)
                        <label class="flex items-start gap-3 p-3 border rounded-xl cursor-pointer hover:bg-slate-50 transition-colors {{ $selectedLahans->contains('id', $l->id) ? 'border-blue-500 bg-blue-50/50' : 'border-slate-200' }}">
                            <input type="checkbox" name="lahan_ids[]" value="{{ $l->id }}" class="mt-0.5 w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500" {{ $selectedLahans->contains('id', $l->id) ? 'checked' : '' }} onchange="checkLimit(this)">
                            <div class="flex-1 min-w-0">
                                <span class="block text-sm font-semibold text-slate-800 truncate">{{ $l->nama_lahan }}</span>
                                <span class="block text-xs text-slate-500 mt-0.5">{{ $l->data_input['Crop_Type'] ?? '-' }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition-all shadow-sm focus:outline-none flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7M5 12h14"/></svg>
                        <span data-en="Compare Selected" data-id="Bandingkan Pilihan">Bandingkan Pilihan</span>
                    </button>
                </div>
            </form>
        </div>
        
        <script>
            function checkLimit(cb) {
                const checkedCount = document.querySelectorAll('input[name="lahan_ids[]"]:checked').length;
                if (checkedCount > 4) {
                    cb.checked = false;
                    alert('Maksimal 4 lahan dapat dibandingkan sekaligus.');
                }
            }
        </script>

        @if($selectedLahans->count() >= 2)
            <!-- Comparison Results Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ min($selectedLahans->count(), 4) }} gap-4">
                @foreach($selectedLahans as $index => $lahan)
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-base font-bold text-slate-900 truncate pr-2" title="{{ $lahan->nama_lahan }}">{{ $lahan->nama_lahan }}</h3>
                            <span class="px-2 py-1 text-[10px] font-bold bg-blue-50 text-blue-600 rounded-full shrink-0">Lahan {{ $index + 1 }}</span>
                        </div>
                        <div class="space-y-1.5 text-xs">
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-400">Tanaman</span>
                                <span class="font-semibold truncate max-w-[100px] text-right" title="{{ $lahan->data_input['Crop_Type'] ?? '-' }}">{{ $lahan->data_input['Crop_Type'] ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-400">Tanah</span>
                                <span class="font-semibold truncate max-w-[100px] text-right" title="{{ $lahan->data_input['Soil_Type'] ?? '-' }}">{{ $lahan->data_input['Soil_Type'] ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-400">Rekomendasi</span>
                                <span class="font-bold text-blue-600 uppercase">{{ $lahan->rekomendasi_pupuk ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Parameters Side-by-Side Table -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase border-b border-slate-100">
                            <th class="py-4 px-6">Parameter Metrik</th>
                            @foreach($selectedLahans as $lahan)
                                <th class="py-4 px-6 text-center">{{ $lahan->nama_lahan }}</th>
                            @endforeach
                            <th class="py-4 px-6 text-center">Status / Rentang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                        @php
                            $metrics = [
                                'Nitrogen_Level' => ['label' => 'Nitrogen (N)', 'unit' => ' mg/kg'],
                                'Phosphorus_Level' => ['label' => 'Phosphorus (P)', 'unit' => ' mg/kg'],
                                'Potassium_Level' => ['label' => 'Potassium (K)', 'unit' => ' mg/kg'],
                                'Soil_pH' => ['label' => 'pH Tanah', 'unit' => ''],
                                'Soil_Moisture' => ['label' => 'Kelembaban Tanah', 'unit' => ' %'],
                                'Temperature' => ['label' => 'Temperatur', 'unit' => ' °C'],
                                'Humidity' => ['label' => 'Kelembaban Udara', 'unit' => ' %'],
                                'Rainfall' => ['label' => 'Curah Hujan', 'unit' => ' mm'],
                            ];
                        @endphp
                        @foreach($metrics as $key => $m)
                            @php
                                $values = [];
                                foreach($selectedLahans as $index => $l) {
                                    $values[] = [ 'val' => $l->data_input[$key] ?? 0, 'name' => "Lahan " . ($index+1) ];
                                }
                                $collection = collect($values);
                                $max = $collection->max('val');
                                $min = $collection->min('val');
                                $maxItem = $collection->firstWhere('val', $max);
                                $minItem = $collection->firstWhere('val', $min);
                                $diff = $max - $min;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 font-semibold">{{ $m['label'] }}</td>
                                @foreach($values as $v)
                                    <td class="py-4 px-6 text-center font-bold {{ $v['val'] == $max && $diff > 0 ? 'text-blue-600' : 'text-slate-900' }}">{{ $v['val'] }}{{ $m['unit'] }}</td>
                                @endforeach
                                <td class="py-4 px-6 text-center text-xs">
                                    @if($diff > 0)
                                        <span class="text-slate-500">Selisih Max: <b class="text-slate-700">{{ round($diff, 2) }}{{ $m['unit'] }}</b><br>Tertinggi: {{ $maxItem['name'] }}</span>
                                    @else
                                        <span class="text-slate-400 font-semibold">Semua Sama</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- AI Comparative Insight -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-slate-855">Analisis Perbandingan AI VESTNOOK</h3>
                
                <div id="compare-insight-cta">
                    <p class="text-sm text-slate-500 mb-4">
                        Dapatkan analisis kecocokan dan perbandingan tanah secara mendalam dari AI Gemini untuk kedua lahan ini.
                    </p>
                    <button onclick="loadCompareInsight()" id="btn-compare-insight"
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl transition-all shadow-sm focus:outline-none flex items-center gap-1.5" style="cursor:pointer;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Analisis dengan AI Gemini
                    </button>
                </div>

                <!-- Skeleton Loading -->
                <div id="compare-insight-loading" class="hidden space-y-3">
                    <div class="skeleton-line w-full"></div>
                    <div class="skeleton-line w-11/12"></div>
                    <div class="skeleton-line w-4/5"></div>
                </div>

                <!-- Output -->
                <div id="compare-insight-output" class="hidden text-sm text-slate-700 leading-relaxed"></div>
            </div>
        @else
            <!-- Placeholder -->
            <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900" data-en="Select Fields to Compare" data-id="Pilih Lahan untuk Dibandingkan">Pilih Lahan untuk Dibandingkan</h3>
                <p class="text-sm text-slate-500 mt-1" data-en="Select two different fields from the dropdowns above to analyze side-by-side differences." data-id="Pilih dua lahan berbeda dari pilihan di atas untuk memulai analisis perbandingan nutrisi.">Pilih dua lahan berbeda dari pilihan di atas untuk memulai analisis perbandingan nutrisi.</p>
            </div>
        @endif

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const lang = localStorage.getItem('lang') || 'id';
            document.documentElement.setAttribute('lang', lang);
            
            document.querySelectorAll('[data-en]').forEach(el => {
                el.textContent = lang === 'en' ? el.getAttribute('data-en') : el.getAttribute('data-id');
            });
        });

        async function loadCompareInsight() {
            const cta = document.getElementById('compare-insight-cta');
            const loading = document.getElementById('compare-insight-loading');
            const output = document.getElementById('compare-insight-output');

            cta.classList.add('hidden');
            loading.classList.remove('hidden');

            const checked = Array.from(document.querySelectorAll('input[name="lahan_ids[]"]:checked')).map(cb => cb.value);
            const params = new URLSearchParams();
            checked.forEach(id => params.append('lahan_ids[]', id));

            try {
                const res = await fetch(`/lahan/compare-insight?${params.toString()}`);
                const data = await res.json();
                
                loading.classList.add('hidden');

                if (data.error) {
                    showError(data.error);
                    return;
                }

                output.classList.remove('hidden');
                output.classList.add('fade-in');

                await renderInsightParagraphs(output, data.insight);
            } catch (e) {
                loading.classList.add('hidden');
                showError('Koneksi terputus. Silakan coba lagi.');
            }
        }

        function showError(msg) {
            const cta = document.getElementById('compare-insight-cta');
            cta.classList.remove('hidden');
            const err = document.createElement('p');
            err.className = 'text-xs text-rose-500 mt-2';
            err.textContent = msg;
            cta.appendChild(err);
        }

        async function renderInsightParagraphs(container, fullText) {
            // Render seluruh teks sekaligus per paragraf, dengan animasi fade-in tiap paragraf.
            // Ini memastikan tidak ada teks yang terpotong di tengah jalan.
            const paragraphs = fullText.split(/\n+/).map(p => p.trim()).filter(Boolean);

            for (let i = 0; i < paragraphs.length; i++) {
                const p = document.createElement('p');
                p.textContent = paragraphs[i];
                p.className = 'mb-4 leading-7';
                p.style.opacity = '0';
                p.style.transform = 'translateY(8px)';
                p.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
                container.appendChild(p);

                // Trigger animasi dengan delay kecil antar paragraf
                await new Promise(r => setTimeout(r, 80));
                p.style.opacity = '1';
                p.style.transform = 'translateY(0)';
            }
        }
    </script>
</body>
</html>
