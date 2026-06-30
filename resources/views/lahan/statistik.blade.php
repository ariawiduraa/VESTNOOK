<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Statistik – {{ $lahan->nama_lahan }}</title>
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
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8f9fb; color: #202124; margin: 0; }

        /* ── Navbar ── */
        .navbar {
            position: sticky; top: 0; z-index: 50;
            height: 56px; background: #fff;
            border-bottom: 1px solid #e8eaed;
            display: flex; align-items: center;
            padding: 0 24px; gap: 16px;
        }
        .navbar-brand { font-weight: 700; font-size: 17px; color: #4285f4; text-decoration: none; }
        .navbar-sep { color: #dadce0; font-size: 18px; }
        .navbar-title { font-size: 14px; font-weight: 500; color: #5f6368; }
        .navbar-right { margin-left: auto; display: flex; gap: 8px; }
        .nav-link {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px; border-radius: 20px;
            font-size: 13px; font-weight: 500; text-decoration: none;
            color: #5f6368; transition: background .15s;
        }
        .nav-link:hover { background: #f1f3f4; }
        .nav-link-blue { background: #4285f4; color: #fff; }
        .nav-link-blue:hover { background: #3367d6; color: #fff; }

        /* ── Layout ── */
        .page { max-width: 1000px; margin: 0 auto; padding: 32px 20px 64px; }

        /* ── Page Header ── */
        .page-eyebrow { font-size: 11px; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: #9aa0a6; margin-bottom: 6px; }
        .page-title { font-size: 28px; font-weight: 700; color: #202124; margin: 0 0 4px; }
        .page-subtitle { font-size: 14px; color: #5f6368; margin: 0 0 32px; }

        /* ── Score Cards ── */
        .score-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 32px; }
        @media(max-width:640px){ .score-grid { grid-template-columns: 1fr; } }
        .score-card {
            background: #fff; border-radius: 12px;
            border: 1px solid #e8eaed;
            padding: 20px 24px;
        }
        .score-card-label { font-size: 11px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: #9aa0a6; margin-bottom: 10px; }
        .score-value { font-size: 38px; font-weight: 700; line-height: 1; }
        .score-value.green  { color: #1e8e3e; }
        .score-value.yellow { color: #f9ab00; }
        .score-value.red    { color: #d93025; }
        .score-value.blue   { color: #4285f4; }
        .score-desc { font-size: 12px; color: #5f6368; margin-top: 6px; }

        /* ── Section ── */
        .section { background: #fff; border-radius: 12px; border: 1px solid #e8eaed; margin-bottom: 24px; overflow: hidden; }
        .section-header { padding: 18px 24px 16px; border-bottom: 1px solid #f1f3f4; }
        .section-title { font-size: 15px; font-weight: 600; color: #202124; margin: 0; }
        .section-desc { font-size: 13px; color: #5f6368; margin: 4px 0 0; }
        .section-body { padding: 20px 24px; }

        /* ── Bar Chart per Parameter ── */
        .param-list { display: flex; flex-direction: column; gap: 20px; }
        .param-row { display: grid; grid-template-columns: 160px 1fr 56px; align-items: center; gap: 12px; }
        @media(max-width:560px){ .param-row { grid-template-columns: 1fr; gap: 4px; } }
        .param-label { font-size: 13px; font-weight: 500; color: #3c4043; }
        .param-value-label { font-size: 11px; color: #9aa0a6; margin-top: 1px; }
        .bar-track { height: 10px; background: #f1f3f4; border-radius: 99px; overflow: hidden; position: relative; }
        .bar-fill { height: 100%; border-radius: 99px; transition: width .8s cubic-bezier(.4,0,.2,1); }
        .bar-fill.green  { background: #1e8e3e; }
        .bar-fill.yellow { background: #f9ab00; }
        .bar-fill.red    { background: #d93025; }
        .param-pct { font-size: 13px; font-weight: 600; text-align: right; }
        .param-pct.green  { color: #1e8e3e; }
        .param-pct.yellow { color: #f9ab00; }
        .param-pct.red    { color: #d93025; }
        .status-badge {
            display: inline-block; font-size: 10px; font-weight: 600;
            padding: 2px 7px; border-radius: 4px; margin-top: 3px;
            letter-spacing: .04em; text-transform: uppercase;
        }
        .status-badge.green  { background: #e6f4ea; color: #1e8e3e; }
        .status-badge.yellow { background: #fef7e0; color: #b36800; }
        .status-badge.red    { background: #fce8e6; color: #c5221f; }

        /* ── Donut-style summary chart ── */
        .donut-wrap { display: flex; align-items: center; gap: 24px; padding: 8px 0; }
        .donut-svg { flex-shrink: 0; }
        .donut-legend { flex: 1; }
        .legend-item { display: flex; align-items: center; gap: 8px; font-size: 13px; margin-bottom: 8px; }
        .legend-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
        .legend-label { color: #5f6368; }
        .legend-count { font-weight: 600; color: #202124; margin-left: auto; }

        /* ── Alternatives ── */
        .alt-grid { display: flex; flex-direction: column; gap: 10px; }
        .alt-row { display: flex; align-items: center; gap: 12px; }
        .alt-rank { width: 24px; height: 24px; border-radius: 6px; background: #f1f3f4; font-size: 11px; font-weight: 700; color: #5f6368; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .alt-name { font-size: 13px; font-weight: 600; color: #202124; min-width: 120px; }
        .alt-bar-track { flex: 1; height: 8px; background: #f1f3f4; border-radius: 99px; overflow: hidden; }
        .alt-bar-fill { height: 100%; background: #dadce0; border-radius: 99px; transition: width .8s; }
        .alt-pct { font-size: 12px; font-weight: 600; color: #5f6368; min-width: 40px; text-align: right; }

        /* ── Insight Gemini ── */
        .insight-box { border: 1px solid #e8eaed; border-radius: 10px; overflow: hidden; }
        .insight-box-header { background: #4285f4; padding: 12px 20px; display: flex; align-items: center; gap: 10px; }
        .insight-box-header-title { font-size: 13px; font-weight: 700; color: #fff; letter-spacing: .04em; text-transform: uppercase; }
        .insight-box-body { padding: 20px; }
        .insight-paragraph { font-size: 15px; line-height: 1.85; color: #3c4043; margin-bottom: 18px; }
        .insight-paragraph:last-child { margin-bottom: 0; }
        .skeleton-line {
            height: 15px; border-radius: 8px; margin-bottom: 11px;
            background: linear-gradient(90deg, #f0f0f0 25%, #e4e4e4 50%, #f0f0f0 75%);
            background-size: 200% 100%; animation: shimmer 1.5s infinite;
        }
        @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
        .cursor { display: inline-block; width: 2px; height: 1em; background: #4285f4; margin-left: 2px; vertical-align: text-bottom; animation: blink .7s step-end infinite; }
        @keyframes blink { 50%{opacity:0} }
        .fade-in { animation: fadeIn .4s ease forwards; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:none} }

        /* ── Radar Chart Canvas ── */
        .chart-wrap { display: flex; justify-content: center; padding: 8px 0; }

        /* ── Dark Mode Overrides ── */
        html.dark body { background: #090d16; color: #f1f5f9; }
        html.dark .navbar { background: #111827; border-bottom-color: #1e293b; }
        html.dark .navbar-title { color: #9ca3af; }
        html.dark .nav-link { color: #9ca3af; }
        html.dark .nav-link:hover { background: #1e293b; }
        html.dark .page-title { color: #fff; }
        html.dark .page-subtitle { color: #9ca3af; }
        html.dark .score-card { background: #111827; border-color: #1e293b; }
        html.dark .score-card-label { color: #6b7280; }
        html.dark .score-desc { color: #9ca3af; }
        html.dark .section { background: #111827; border-color: #1e293b; }
        html.dark .section-header { border-bottom-color: #1e293b; }
        html.dark .section-title { color: #fff; }
        html.dark .section-desc { color: #9ca3af; }
        html.dark .param-label { color: #cbd5e1; }
        html.dark .bar-track { background: #1e293b; }
        html.dark .legend-label { color: #9ca3af; }
        html.dark .legend-count { color: #fff; }
        html.dark .alt-rank { background: #1e293b; color: #9ca3af; }
        html.dark .alt-name { color: #fff; }
        html.dark .alt-bar-track { background: #1e293b; }
        html.dark .alt-bar-fill { background: #334155; }
        html.dark .alt-pct { color: #9ca3af; }
        html.dark .insight-box { border-color: #1e293b; }
        html.dark .insight-paragraph { color: #cbd5e1; }
        html.dark .skeleton-line { background: linear-gradient(90deg, #1e293b 25%, #334155 50%, #1e293b 75%); }
        html.dark .status-badge.green { background: rgba(30, 142, 62, 0.15); color: #81c995; }
        html.dark .status-badge.yellow { background: rgba(249, 171, 0, 0.15); color: #fdd663; }
        html.dark .status-badge.red { background: rgba(217, 48, 37, 0.15); color: #f28b82; }

    </style>
</head>
<body>

<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="navbar-brand">VESTNOOK</a>
    <span class="navbar-sep">/</span>
    <span class="navbar-title">{{ $lahan->nama_lahan }}</span>
    <div class="navbar-right">
        <a href="{{ route('lahan.show', $lahan) }}" class="nav-link">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span data-en="Analysis Results" data-id="Hasil Analisis">Hasil Analisis</span>
        </a>
        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
    </div>
</nav>

@php
    $stats    = $lahan->statistik_data ?? [];
    $params   = $stats['param_stats']      ?? [];
    $avgFit   = $stats['avg_fit']           ?? 0;
    $conf     = $stats['confidence_score']  ?? 0;
    $alts     = $stats['top_alternatives']  ?? [];

    // Hitung distribusi status
    $countBaik   = collect($params)->where('status','baik')->count();
    $countSedang = collect($params)->where('status','sedang')->count();
    $countRendah = collect($params)->where('status','rendah')->count();
    $total       = count($params);

    function colorClass($pct) {
        if ($pct >= 75) return 'green';
        if ($pct >= 40) return 'yellow';
        return 'red';
    }
@endphp

<div class="page">

    {{-- Page Header --}}
    <div class="page-eyebrow" data-en="Detailed Statistics" data-id="Statistik Detail">Statistik Detail</div>
    <h1 class="page-title">{{ $lahan->nama_lahan }}</h1>
    <p class="page-subtitle">
        <span data-en="Fertilizer recommendation:" data-id="Rekomendasi pupuk:">Rekomendasi pupuk:</span> <strong>{{ strtoupper($lahan->rekomendasi_pupuk) }}</strong>
        &nbsp;&middot;&nbsp; <span data-en="Zone:" data-id="Zona:">Zona:</span> {{ $lahan->hasil_cluster }}
    </p>

    {{-- Score Cards --}}
    <div class="score-grid">
        {{-- Confidence Model --}}
        @php $cls = colorClass($conf); @endphp
        <div class="score-card">
            <div class="score-card-label" data-en="AI Model Confidence" data-id="Keyakinan Model AI">Keyakinan Model AI</div>
            <div class="score-value {{ $cls }}">{{ $conf }}%</div>
            <div class="score-desc">
                <span data-en="Algorithm confidence level for fertilizer recommendation" data-id="Tingkat kepercayaan algoritma terhadap rekomendasi pupuk">Tingkat kepercayaan algoritma terhadap rekomendasi pupuk</span>
                <strong>{{ strtoupper($lahan->rekomendasi_pupuk) }}</strong>.
            </div>
        </div>

        {{-- Avg Fit --}}
        @php $clsAvg = colorClass($avgFit); @endphp
        <div class="score-card">
            <div class="score-card-label" data-en="Average Land Suitability" data-id="Rata-rata Kesesuaian Lahan">Rata-rata Kesesuaian Lahan</div>
            <div class="score-value {{ $clsAvg }}">{{ $avgFit }}%</div>
            <div class="score-desc">
                <span data-en="Average compatibility of all parameters to the ideal range for the recommended fertilizer." data-id="Rata-rata kecocokan seluruh parameter lahan terhadap rentang ideal untuk pupuk yang direkomendasikan.">Rata-rata kecocokan seluruh parameter lahan terhadap rentang ideal untuk pupuk yang direkomendasikan.</span>
            </div>
        </div>

        {{-- Distribusi --}}
        <div class="score-card">
            <div class="score-card-label" data-en="Parameter Distribution" data-id="Distribusi Parameter">Distribusi Parameter</div>
            <div style="display:flex;gap:14px;margin-top:4px;">
                <div style="text-align:center;">
                    <div style="font-size:26px;font-weight:700;color:#1e8e3e;">{{ $countBaik }}</div>
                    <div style="font-size:11px;color:#9aa0a6;text-transform:uppercase;letter-spacing:.06em;" data-en="Optimal" data-id="Baik">Baik</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:26px;font-weight:700;color:#f9ab00;">{{ $countSedang }}</div>
                    <div style="font-size:11px;color:#9aa0a6;text-transform:uppercase;letter-spacing:.06em;" data-en="Moderate" data-id="Sedang">Sedang</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:26px;font-weight:700;color:#d93025;">{{ $countRendah }}</div>
                    <div style="font-size:11px;color:#9aa0a6;text-transform:uppercase;letter-spacing:.06em;" data-en="Critical" data-id="Rendah">Rendah</div>
                </div>
            </div>
            <div class="score-desc" style="margin-top:8px;">
                <span data-en="out of" data-id="dari">dari</span> {{ $total }} <span data-en="parameters analyzed." data-id="parameter yang dianalisis.">parameter yang dianalisis.</span>
            </div>
        </div>
    </div>

    {{-- Bar Chart Parameter --}}
    <div class="section">
        <div class="section-header">
            <div class="section-title" data-en="Soil Parameter Suitability" data-id="Kenesuaian Parameter Lahan">Kesesuaian Parameter Lahan</div>
            <div class="section-desc">
                <span data-en="Percentage shows how compatible the entered value is to the ideal range for" data-id="Persentase menunjukkan seberapa cocok nilai yang Anda masukkan dengan rentang ideal untuk rekomendasi pupuk">Persentase menunjukkan seberapa cocok nilai yang Anda masukkan dengan rentang ideal untuk rekomendasi pupuk</span>
                <strong>{{ strtoupper($lahan->rekomendasi_pupuk) }}</strong>.
                <span data-en="A value of 100% means the parameter is precisely inside the optimal range." data-id="Nilai 100% berarti parameter berada tepat di dalam zona optimal.">Nilai 100% berarti parameter berada tepat di dalam zona optimal.</span>
            </div>
        </div>
        <div class="section-body">
            <div class="param-list" id="param-list">
                @forelse($params as $p)
                    @php $cl = colorClass($p['fit_pct']); @endphp
                    <div class="param-row">
                        <div>
                            <div class="param-label">{{ $p['label'] }}</div>
                            <div class="param-value-label">
                                <span data-en="Value:" data-id="Nilai:">Nilai:</span> <strong>{{ $p['value'] }}{{ $p['unit'] ? ' '.$p['unit'] : '' }}</strong>
                                &nbsp;&middot;&nbsp;
                                <span data-en="Ideal:" data-id="Ideal:">Ideal:</span> {{ $p['ideal_min'] }}–{{ $p['ideal_max'] }}{{ $p['unit'] ? ' '.$p['unit'] : '' }}
                            </div>
                            <span class="status-badge {{ $cl }}" data-en="{{ $p['status'] == 'baik' ? 'Optimal' : ($p['status'] == 'sedang' ? 'Moderate' : 'Critical') }}" data-id="{{ ucfirst($p['status']) }}">{{ ucfirst($p['status']) }}</span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill {{ $cl }}"
                                 style="width:0%"
                                 data-target="{{ $p['fit_pct'] }}"></div>
                        </div>
                        <div class="param-pct {{ $cl }}">{{ $p['fit_pct'] }}%</div>
                    </div>
                @empty
                    <div style="text-align:center;color:#9aa0a6;padding:24px 0;font-size:14px;">
                        <span data-en="Parameter statistics not available. Re-run analysis to get this data." data-id="Statistik parameter belum tersedia. Lakukan analisis ulang untuk mendapatkan data ini.">Statistik parameter belum tersedia. Lakukan analisis ulang untuk mendapatkan data ini.</span>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Radar Chart --}}
    @if(count($params) > 0)
    <div class="section">
        <div class="section-header">
            <div class="section-title">Grafik Radar Kesesuaian</div>
            <div class="section-desc">Gambaran visual keseimbangan semua parameter lahan secara bersamaan.</div>
        </div>
        <div class="section-body chart-wrap">
            <canvas id="radar-chart" width="400" height="400" style="max-width:400px;"></canvas>
        </div>
    </div>
    @endif

    {{-- Alternative Fertilizers --}}
    @if(count($alts) > 0)
    <div class="section">
        <div class="section-header">
            <div class="section-title">Alternatif Pupuk Lainnya</div>
            <div class="section-desc">Pupuk berikut juga dipertimbangkan oleh model AI, namun dengan tingkat keyakinan lebih rendah.</div>
        </div>
        <div class="section-body">
            <div style="margin-bottom:12px;font-size:12px;font-weight:600;color:#9aa0a6;letter-spacing:.06em;text-transform:uppercase;">
                Rekomendasi Utama
            </div>
            <div class="alt-row" style="margin-bottom:16px;">
                <div class="alt-rank" style="background:#e8f0fe;color:#4285f4;">1</div>
                <div class="alt-name" style="color:#4285f4;">{{ strtoupper($lahan->rekomendasi_pupuk) }}</div>
                <div class="alt-bar-track">
                    <div class="alt-bar-fill" style="background:#4285f4;width:{{ min($conf,100) }}%"></div>
                </div>
                <div class="alt-pct" style="color:#4285f4;">{{ $conf }}%</div>
            </div>
            <div style="margin-bottom:12px;font-size:12px;font-weight:600;color:#9aa0a6;letter-spacing:.06em;text-transform:uppercase;">
                Alternatif
            </div>
            <div class="alt-grid">
                @foreach($alts as $i => $alt)
                <div class="alt-row">
                    <div class="alt-rank">{{ $i + 2 }}</div>
                    <div class="alt-name">{{ strtoupper($alt['pupuk']) }}</div>
                    <div class="alt-bar-track">
                        <div class="alt-bar-fill" style="width:{{ min($alt['confidence'],100) }}%"></div>
                    </div>
                    <div class="alt-pct">{{ $alt['confidence'] }}%</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Insight AI Vestnook Statistik --}}
    <div class="section">
        <div class="section-header">
            <div class="section-title">Rekomendasi Tindak Lanjut dari AI</div>
            <div class="section-desc">
                AI Vestnook membaca seluruh data parameter dan statistik kesesuaian lahan
                untuk memberikan saran tindak lanjut yang spesifik dan terukur.
            </div>
        </div>
        <div class="section-body">
            <div class="insight-box">
                <div class="insight-box-header">
                    <svg width="14" height="14" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span class="insight-box-header-title">Analisis Mendalam AI Vestnook</span>
                </div>
                <div class="insight-box-body" id="insight-stat-section">

                    @if($lahan->insight_statistik)
                        <div class="fade-in" id="insight-stat-output">
                            @foreach(array_filter(explode("\n\n", trim($lahan->insight_statistik))) as $para)
                                <p class="insight-paragraph">{{ trim($para) }}</p>
                            @endforeach
                        </div>
                    @else
                        <div id="insight-stat-cta">
                            <p style="font-size:14px;color:#5f6368;margin:0 0 16px;line-height:1.7;">
                                Minta AI Vestnook menganalisis semua data parameter lahan dan statistik kesesuaian
                                untuk memberikan panduan tindak lanjut yang konkret dan terukur.
                            </p>
                            <button id="btn-insight-stat" onclick="loadInsightStat()"
                                style="display:inline-flex;align-items:center;gap:8px;padding:10px 22px;background:#4285f4;color:#fff;border:none;border-radius:24px;font-size:13px;font-weight:600;cursor:pointer;transition:background .15s;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Minta Analisis Mendalam
                            </button>
                        </div>
                        <div id="insight-stat-loading" class="hidden">
                            <div class="skeleton-line" style="width:100%"></div>
                            <div class="skeleton-line" style="width:92%"></div>
                            <div class="skeleton-line" style="width:80%"></div>
                            <div class="skeleton-line" style="width:100%;margin-top:14px"></div>
                            <div class="skeleton-line" style="width:88%"></div>
                            <div class="skeleton-line" style="width:75%"></div>
                            <div class="skeleton-line" style="width:95%;margin-top:14px"></div>
                            <div class="skeleton-line" style="width:70%"></div>
                        </div>
                        <div id="insight-stat-output" class="hidden"></div>
                    @endif

                </div>
            </div>
        </div>
    </div>

</div>{{-- /page --}}

{{-- Chart.js for Radar --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Animate bar fills on load ──
window.addEventListener('load', () => {
    document.querySelectorAll('.bar-fill[data-target]').forEach(bar => {
        setTimeout(() => {
            bar.style.width = bar.dataset.target + '%';
        }, 100);
    });
});

// ── Radar Chart ──
@if(count($params) > 0)
(function() {
    const labels = @json(collect($params)->pluck('label')->values());
    const values = @json(collect($params)->pluck('fit_pct')->values());

    const ctx = document.getElementById('radar-chart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'radar',
        data: {
            labels,
            datasets: [{
                label: 'Kesesuaian (%)',
                data: values,
                backgroundColor: 'rgba(66,133,244,0.12)',
                borderColor:     '#4285f4',
                borderWidth:     2,
                pointBackgroundColor: values.map(v =>
                    v >= 75 ? '#1e8e3e' : v >= 40 ? '#f9ab00' : '#d93025'
                ),
                pointRadius:    5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.raw}% kesesuaian`
                    }
                }
            },
            scales: {
                r: {
                    min: 0, max: 100,
                    ticks: {
                        stepSize: 25,
                        font: { size: 10 },
                        color: '#9aa0a6',
                        backdropColor: 'transparent',
                        callback: v => v + '%'
                    },
                    grid:     { color: '#f1f3f4' },
                    angleLines:{ color: '#e8eaed' },
                    pointLabels: {
                        font: { size: 11, family: 'Inter' },
                        color: '#5f6368'
                    }
                }
            }
        }
    });
})();
@endif

// ── Insight Statistik AJAX ──
const INSIGHT_STAT_URL = "{{ route('lahan.insight.statistik', $lahan->id) }}";

async function loadInsightStat() {
    document.getElementById('insight-stat-cta').classList.add('hidden');
    document.getElementById('insight-stat-loading').classList.remove('hidden');

    try {
        const res  = await fetch(INSIGHT_STAT_URL);
        const data = await res.json();

        document.getElementById('insight-stat-loading').classList.add('hidden');

        if (data.error) {
            showStatError(data.error);
            return;
        }

        const output = document.getElementById('insight-stat-output');
        output.classList.remove('hidden');
        output.classList.add('fade-in');
        await typewriterParagraphs(output, data.insight);

    } catch (e) {
        document.getElementById('insight-stat-loading').classList.add('hidden');
        showStatError('Terjadi kesalahan koneksi. Silakan coba lagi.');
    }
}

function showStatError(msg) {
    const cta = document.getElementById('insight-stat-cta');
    cta.classList.remove('hidden');
    const btn = document.getElementById('btn-insight-stat');
    if (btn) { btn.disabled = false; btn.textContent = 'Coba Lagi'; }
    const err = document.createElement('p');
    err.style.cssText = 'font-size:13px;color:#d93025;margin-top:10px;';
    err.textContent = msg;
    cta.appendChild(err);
}

async function typewriterParagraphs(container, fullText) {
    const paragraphs = fullText.split(/\n\n+/).map(p => p.trim()).filter(Boolean);
    for (const para of paragraphs) {
        const p = document.createElement('p');
        p.className = 'insight-paragraph';
        container.appendChild(p);
        const cursor = document.createElement('span');
        cursor.className = 'cursor';
        p.appendChild(cursor);
        await typeChar(p, cursor, para);
        cursor.remove();
        await new Promise(r => setTimeout(r, 100));
    }
}

function typeChar(el, cursor, text) {
    return new Promise(resolve => {
        let i = 0;
        const speed = 10;
        function tick() {
            if (i < text.length) {
                el.insertBefore(document.createTextNode(text[i]), cursor);
                i++;
                setTimeout(tick, speed);
            } else { resolve(); }
        }
        tick();
    });
}
    // Initial settings
    document.addEventListener('DOMContentLoaded', () => {
        const lang = localStorage.getItem('lang') || 'id';
        document.documentElement.setAttribute('lang', lang);
        
        // Apply text translation
        document.querySelectorAll('[data-en]').forEach(el => {
            el.textContent = lang === 'en' ? el.getAttribute('data-en') : el.getAttribute('data-id');
        });
    });
</script>
</body>
</html>
