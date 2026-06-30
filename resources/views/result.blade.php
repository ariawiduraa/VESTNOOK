<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hasil Analisis – {{ $lahan->nama_lahan }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    

    
    <style>
        body { font-family: 'Inter', sans-serif; background: #fff; color: #202124; }

        /* Typewriter cursor */
        .cursor { display: inline-block; width: 2px; height: 1.1em; background: #4285f4; margin-left: 2px; vertical-align: text-bottom; animation: blink 0.75s step-end infinite; }
        @keyframes blink { 50% { opacity: 0; } }

        /* Skeleton loading blocks (Gemini-style) */
        .skeleton-line {
            height: 16px; border-radius: 8px;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            margin-bottom: 12px;
        }

        @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

        /* Insight text: rendered paragraphs */
        .insight-paragraph { font-size: 17px; line-height: 1.85; color: #3c4043; margin-bottom: 20px; }
        .insight-paragraph:last-child { margin-bottom: 0; }

        /* Fade in */
        .fade-in { animation: fadeIn 0.4s ease forwards; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

        /* Section divider */
        .divider { border: none; border-top: 1px solid #f1f3f4; margin: 32px 0; }
    </style>
    <link rel="icon" type="image/png" href="{{ asset('images/logovestnook.png') }}">
</head>
<body class="antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="w-full px-6 h-16 flex items-center justify-between border-b border-gray-100 bg-white sticky top-0 z-50 shadow-sm transition-colors">
        <a href="/" class="flex items-center gap-2 text-blue-600 font-bold text-lg tracking-tight">VESTNOOK</a>
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Dashboard
        </a>
    </nav>

    <!-- Content -->
    <main class="flex-grow w-full max-w-2xl mx-auto px-6 py-12 md:py-16">

        <!-- Label -->
        <p class="text-xs font-bold tracking-widest text-slate-400 uppercase mb-4">Hasil Analisis</p>

        <!-- Nama Lahan -->
        <h1 class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight leading-tight mb-6">
            {{ $lahan->nama_lahan }}
        </h1>

        <!-- Status Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-50 border border-green-100 text-green-700 text-sm font-semibold mb-8">
            <span class="w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></span>
            Status Lahan: {{ $lahan->hasil_cluster }}
        </div>

        <hr class="divider">

        <!-- Rekomendasi Pupuk -->
        <div class="mb-2">
            <p class="text-xs font-bold tracking-widest text-slate-400 uppercase mb-3">Rekomendasi Pupuk Utama</p>
            <p class="text-5xl md:text-6xl font-black text-blue-600 tracking-tight uppercase">
                {{ $lahan->rekomendasi_pupuk }}
            </p>
        </div>

        <hr class="divider">

        <!-- Insight AI Section -->
        <div id="insight-section">

            @if($lahan->insight_gemini)
                {{-- Sudah ada di DB: tampilkan langsung dengan animasi teks --}}
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-base font-bold text-slate-800">Insight AI Vestnook</h2>
                </div>
                <div id="insight-output" class="fade-in">
                    @foreach(array_filter(explode("\n\n", trim($lahan->insight_gemini))) as $paragraph)
                        <p class="insight-paragraph">{{ trim($paragraph) }}</p>
                    @endforeach
                </div>
            @else
                {{-- Belum ada: tampilkan tombol --}}
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-base font-bold text-slate-800">Insight AI Vestnook</h2>
                </div>

                <div id="insight-cta">
                    <p class="text-sm text-slate-500 mb-5 leading-relaxed">
                        Minta analisis mendalam dari AI Vestnook tentang kondisi lahan ini dan cara perawatan yang tepat.
                    </p>
                    <button
                        id="btn-insight"
                        onclick="loadInsight()"
                        class="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-full transition-all shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Minta Analisis dari AI Vestnook
                    </button>
                </div>

                <!-- Skeleton Loading (hidden by default) -->
                <div id="insight-loading" class="hidden">
                    <div class="skeleton-line w-full"></div>
                    <div class="skeleton-line w-11/12"></div>
                    <div class="skeleton-line w-4/5"></div>
                    <div class="skeleton-line w-full mt-4"></div>
                    <div class="skeleton-line w-9/12"></div>
                    <div class="skeleton-line w-10/12"></div>
                </div>

                <!-- Output area (hidden by default) -->
                <div id="insight-output" class="hidden"></div>
            @endif
        </div>

        <!-- Kalender Pemupukan -->
        <div class="mt-8 no-print">
            <h3 class="text-base font-bold text-slate-800 mb-4">Kalender Pemupukan 8 Minggu</h3>
            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 space-y-4">
                <p class="text-xs text-slate-500">
                    Jadwal pemupukan terintegrasi khusus untuk rekomendasi <strong>{{ strtoupper($lahan->rekomendasi_pupuk) }}</strong>. Centang aktivitas untuk melacak progres perawatan.
                </p>
                <div class="divide-y divide-slate-100" id="schedule-list">
                    <!-- Javascript will populate schedule based on fertilizer -->
                </div>
            </div>
        </div>

        <hr class="divider mt-8">

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-slate-650 bg-slate-100 hover:bg-slate-200 rounded-full transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Dashboard
            </a>
            <a href="{{ route('lahan.statistik', $lahan->id) }}"
                class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-full transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Lihat Statistik Detail
            </a>
        </div>

    </main>

    <script>
        const INSIGHT_URL = "{{ route('lahan.insight', $lahan->id) }}";

        async function loadInsight() {
            // Sembunyikan tombol, tampilkan skeleton
            document.getElementById('insight-cta').classList.add('hidden');
            document.getElementById('insight-loading').classList.remove('hidden');

            try {
                const res  = await fetch(INSIGHT_URL);
                const data = await res.json();

                // Sembunyikan skeleton
                document.getElementById('insight-loading').classList.add('hidden');

                if (data.error) {
                    showError(data.error);
                    return;
                }

                // Tampilkan area output
                const output = document.getElementById('insight-output');
                output.classList.remove('hidden');
                output.classList.add('fade-in');

                // Typewriter per paragraf
                await typewriterParagraphs(output, data.insight);

            } catch (e) {
                document.getElementById('insight-loading').classList.add('hidden');
                showError('Terjadi kesalahan koneksi. Silakan coba lagi.');
            }
        }

        function showError(msg) {
            const cta = document.getElementById('insight-cta');
            cta.classList.remove('hidden');
            document.getElementById('btn-insight').disabled = false;
            document.getElementById('btn-insight').textContent = 'Coba Lagi';

            const err = document.createElement('p');
            err.className = 'text-sm text-red-550 mt-3';
            err.textContent = msg;
            cta.appendChild(err);
        }

        async function typewriterParagraphs(container, fullText) {
            // Split menjadi paragraf berdasarkan baris kosong
            const paragraphs = fullText.split(/\n\n+/).map(p => p.trim()).filter(Boolean);

            for (const para of paragraphs) {
                const p = document.createElement('p');
                p.className = 'insight-paragraph';
                container.appendChild(p);

                // Tambahkan kursor kedip
                const cursor = document.createElement('span');
                cursor.className = 'cursor';
                p.appendChild(cursor);

                // Typewriter per karakter
                await typeChar(p, cursor, para);

                // Hapus kursor, sisipkan baris baru sebelum paragraf berikutnya
                cursor.remove();
                await delay(120); // jeda antar paragraf
            }
        }

        function typeChar(el, cursor, text) {
            return new Promise(resolve => {
                let i = 0;
                const speed = 12; // ms per karakter (lebih cepat = lebih kecil)
                function tick() {
                    if (i < text.length) {
                        // Insert text sebelum kursor
                        el.insertBefore(document.createTextNode(text[i]), cursor);
                        i++;
                        setTimeout(tick, speed);
                    } else {
                        resolve();
                    }
                }
                tick();
            });
        }

        function delay(ms) { return new Promise(r => setTimeout(r, ms)); }        // Fetch Jadwal Pemupukan AI Gemini
        document.addEventListener('DOMContentLoaded', async () => {
            const container = document.getElementById('schedule-list');
            if (!container) return;

            // Render skeleton loading
            container.innerHTML = `
                <div class="space-y-3 py-3" id="schedule-loading">
                    <div class="skeleton-line w-full"></div>
                    <div class="skeleton-line w-11/12"></div>
                    <div class="skeleton-line w-4/5"></div>
                </div>
            `;

            const lahanId = "{{ $lahan->id }}";

            try {
                const res = await fetch(`/lahan/${lahanId}/schedule`);
                const data = await res.json();
                
                if (data.schedule) {
                    const checkedArray = data.checked || [];
                    
                    container.innerHTML = data.schedule.map(t => {
                        const isChecked = checkedArray.includes(parseInt(t.week));
                        const checkedStr = isChecked ? 'checked' : '';
                        return `
                            <div class="py-3 flex items-start gap-3">
                                <input type="checkbox" id="chk_${t.week}" onchange="toggleTask(${t.week}, this.checked)" ${checkedStr}
                                       class="mt-1 w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-350 bg-white">
                                <div>
                                    <label for="chk_${t.week}" class="block text-sm font-semibold text-slate-850 cursor-pointer ${isChecked ? 'line-through opacity-50 text-slate-400' : ''}" id="label_${t.week}">
                                        Minggu ${t.week}: ${t.title}
                                    </label>
                                    <p class="text-xs text-slate-450 mt-1">${t.desc}</p>
                                </div>
                            </div>
                        `;
                    }).join('');
                } else {
                    container.innerHTML = `<p class="text-xs text-slate-400">Gagal memuat jadwal dari AI.</p>`;
                }
            } catch (e) {
                container.innerHTML = `<p class="text-xs text-slate-450">Gagal memuat jadwal pemupukan.</p>`;
            }

            window.toggleTask = async function(week, isChecked) {
                const label = document.getElementById(`label_${week}`);
                if (label) {
                    if (isChecked) {
                        label.classList.add('line-through', 'opacity-50', 'text-slate-400', 'dark:text-slate-550');
                    } else {
                        label.classList.remove('line-through', 'opacity-50', 'text-slate-400', 'dark:text-slate-550');
                    }
                }

                try {
                    await fetch(`/lahan/${lahanId}/schedule-toggle`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ week: week, checked: isChecked })
                    });
                } catch (e) {
                    console.error('Failed to save toggle state', e);
                }
            };
        });
    </script>

</body>
</html>
