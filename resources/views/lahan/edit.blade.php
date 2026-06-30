<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Analisis Lahan - VESTNOOK</title>
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
        body { font-family: 'Inter', sans-serif; background-color: #ffffff; color: #202124; transition: background-color 0.25s, color 0.25s; }
        html.dark body { background-color: #090d16; color: #f1f5f9; }
        html.dark .section-title { color: #ffffff; border-bottom-color: #1e293b; }
        html.dark .input-field { background-color: #020617; border-color: #1e293b; color: #ffffff; }
        html.dark .input-field:focus { border-color: #1a73e8; }
        html.dark label { color: #cbd5e1 !important; }
        html.dark .slider-widget { background: #111827; border-color: #1e293b; }
        html.dark .slider-label-text { color: #9ca3af; }
        html.dark .slider-value-num { color: #60a5fa; }
        html.dark .combobox-list { background: #111827; border-color: #1e293b; }
        html.dark .combo-item { color: #cbd5e1; }
        html.dark .combo-item:hover, html.dark .combo-item.highlighted { background: #1e3a8a; color: #60a5fa; }
        html.dark .combo-group-label { background: #0f172a; border-top-color: #1e293b; color: #6b7280; }
        html.dark select option { background: #111827; color: #ffffff; }
        .section-title { font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #f3f4f6; }
        .input-field { width: 100%; padding: 0.625rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; transition: all 0.2s; background-color: #fff; }
        .input-field:focus { outline: none; border-color: #1a73e8; box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.15); }

        /* Slider Widget */
        .slider-widget { background: #f8faff; border: 1px solid #e8eef7; border-radius: 12px; padding: 14px 16px 12px; transition: border-color 0.2s, box-shadow 0.2s; }
        .slider-widget:focus-within { border-color: #1a73e8; box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.12); }
        .slider-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .slider-label-text { font-size: 13px; font-weight: 500; color: #374151; }
        .slider-value-badge { display: flex; align-items: center; gap: 6px; }
        .slider-value-num { font-size: 15px; font-weight: 700; color: #1a73e8; min-width: 42px; text-align: right; }
        .slider-desc-pill { font-size: 11px; font-weight: 600; padding: 2px 9px; border-radius: 20px; letter-spacing: 0.03em; transition: background 0.3s, color 0.3s; white-space: nowrap; }
        input[type="range"].styled-range { -webkit-appearance: none; appearance: none; width: 100%; height: 6px; border-radius: 4px; outline: none; cursor: pointer; transition: background 0.2s; }
        input[type="range"].styled-range::-webkit-slider-thumb { -webkit-appearance: none; appearance: none; width: 20px; height: 20px; border-radius: 50%; background: #fff; border: 2.5px solid #1a73e8; box-shadow: 0 2px 6px rgba(26,115,232,0.3); cursor: pointer; transition: transform 0.15s, box-shadow 0.2s; }
        input[type="range"].styled-range::-webkit-slider-thumb:hover { transform: scale(1.2); box-shadow: 0 3px 10px rgba(26,115,232,0.4); }
        input[type="range"].styled-range::-moz-range-thumb { width: 18px; height: 18px; border-radius: 50%; background: #fff; border: 2.5px solid #1a73e8; cursor: pointer; }
        .slider-ticks { display: flex; justify-content: space-between; margin-top: 5px; padding: 0 2px; }
        .slider-tick-label { font-size: 10px; color: #9ca3af; }

        /* Combobox */
        .combobox-wrap { position: relative; }
        .combobox-list { display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0; background: #fff; border: 1px solid #d1d5db; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); z-index: 100; max-height: 240px; overflow-y: auto; }
        .combobox-list.open { display: block; }
        .combo-item { padding: 9px 14px; font-size: 14px; cursor: pointer; color: #374151; transition: background .1s; }
        .combo-item:hover, .combo-item.highlighted { background: #eff6ff; color: #1d4ed8; }
        .combo-item.hidden { display: none; }
        .combo-group-label { padding: 6px 14px 2px; font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .05em; background: #f9fafb; border-top: 1px solid #f3f4f6; margin-top: 2px; }
        .combo-no-result { padding: 10px 14px; font-size: 13px; color: #9ca3af; }
    </style>
    <link rel="icon" type="image/png" href="{{ asset('images/logovestnook.png') }}">
</head>
<body class="antialiased min-h-screen flex flex-col selection:bg-[#1a73e8] selection:text-white">

    <!-- Top Navbar -->
    <nav class="w-full px-6 py-4 flex items-center gap-4 border-b border-gray-200 bg-white sticky top-0 z-50 shadow-sm">
        <a href="{{ route('dashboard') }}" class="p-2 -ml-2 text-gray-500 hover:bg-gray-100 hover:text-[#1a73e8] rounded-full transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="flex flex-col">
            <span class="font-semibold text-lg tracking-tight leading-tight text-[#1a73e8]">Edit Data Lahan</span>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow w-full max-w-4xl mx-auto px-4 sm:px-6 py-8">
        
        <!-- Form View -->
        <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm">
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-md bg-red-50 border border-red-100 text-red-600 text-sm">
                    {!! nl2br(e($errors->first())) !!}
                </div>
            @endif
            
            @php
                $data = is_array($lahan->data_input) ? $lahan->data_input : json_decode($lahan->data_input, true) ?? [];
                
                $cropLabels = [
                    'Wheat' => 'Padi Gandum (Wheat)',
                    'Maize' => 'Jagung (Maize)',
                    'Rice' => 'Padi (Rice)',
                    'Barley' => 'Jelai (Barley)',
                    'Sorghum' => 'Sorgum (Sorgum)',
                    'Millet' => 'Millet / Juwawut',
                    'Potato' => 'Kentang (Potato)',
                    'Tomato' => 'Tomat (Tomato)',
                    'Onion' => 'Bawang (Onion)',
                    'Garlic' => 'Bawang Putih (Garlic)',
                    'Cabbage' => 'Kubis (Cabbage)',
                    'Carrot' => 'Wortel (Carrot)',
                    'Spinach' => 'Bayam (Spinach)',
                    'Pakchoi' => 'Pokcoy / Pakcoy',
                    'Chili' => 'Cabai (Chili)',
                    'Eggplant' => 'Terong (Eggplant)',
                    'Banana' => 'Pisang (Banana)',
                    'Mango' => 'Mangga (Mango)',
                    'Sugarcane' => 'Tebu (Sugarcane)',
                    'Watermelon' => 'Semangka (Watermelon)',
                    'Melon' => 'Melon',
                    'Cotton' => 'Kapas (Cotton)',
                    'Soybean' => 'Kedelai (Soybean)',
                    'Groundnut' => 'Kacang Tanah (Groundnut)',
                    'Coffee' => 'Kopi (Coffee)',
                    'Cocoa' => 'Kakao (Cocoa)',
                    'Tobacco' => 'Tembakau (Tobacco)',
                    'Rubber' => 'Karet (Rubber)',
                    'Oilpalm' => 'Kelapa Sawit (Oil Palm)',
                    'Coconut' => 'Kelapa (Coconut)'
                ];
                
                $cropTypeVal = old('Crop_Type', $data['Crop_Type'] ?? '');
                $cropTypeDisplay = $cropLabels[$cropTypeVal] ?? $cropTypeVal;
                
                $prevCropVal = old('Previous_Crop', $data['Previous_Crop'] ?? '');
                $prevCropLabels = array_merge(['None' => 'Tidak Ada / Lahan Baru'], $cropLabels);
                $prevCropDisplay = $prevCropLabels[$prevCropVal] ?? $prevCropVal;
            @endphp

            <form method="POST" action="{{ route('lahan.update', $lahan->id) }}" class="space-y-8" onsubmit="return confirmAction(this, event, 'Apakah Anda yakin ingin menyimpan perubahan dan memproses ulang data lahan ini?', 'primary')">
                @csrf
                @method('PUT')
                
                <!-- Identitas -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lahan (Untuk Riwayat)</label>
                    <input type="text" name="nama_lahan" value="{{ old('nama_lahan', $lahan->nama_lahan) }}" required class="input-field bg-gray-50 font-medium text-lg" placeholder="Misal: Lahan Sawah Blok A">
                </div>

                <!-- Section 1: Kelompok Tanah -->
                <div>
                    <h3 class="section-title">Kondisi Tanah & Zat Hara</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="col-span-1 sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Tanah (Soil_Type)</label>
                            <select name="Soil_Type" class="input-field" required>
                                <option value="">Pilih Tipe</option>
                                @foreach(['Clay', 'Sandy', 'Loamy', 'Silt', 'Peaty', 'Chalky'] as $type)
                                    <option value="{{ $type }}" {{ (old('Soil_Type', $data['Soil_Type'] ?? '') == $type) ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- pH Tanah -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">pH Tanah</label>
                            <div class="slider-widget">
                                <div class="slider-header">
                                    <span class="slider-label-text">Keasaman</span>
                                    <div class="slider-value-badge">
                                        <span class="slider-value-num" id="disp-Soil_pH">{{ old('Soil_pH', $data['Soil_pH'] ?? '6.5') }}</span>
                                        <span class="slider-desc-pill" id="pill-Soil_pH" style="background:#d1fae5;color:#065f46">Netral</span>
                                    </div>
                                </div>
                                <input type="range" class="styled-range" id="range-Soil_pH" min="3.5" max="9.5" step="0.1" value="{{ old('Soil_pH', $data['Soil_pH'] ?? '6.5') }}" oninput="updateSlider('Soil_pH',this.value)">
                                <input type="hidden" name="Soil_pH" id="input-Soil_pH" value="{{ old('Soil_pH', $data['Soil_pH'] ?? '6.5') }}" required>
                                <div class="slider-ticks"><span class="slider-tick-label">3.5 Asam</span><span class="slider-tick-label">9.5 Basa</span></div>
                            </div>
                        </div>

                        <!-- Kelembapan Tanah -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kelembapan Tanah (%)</label>
                            <div class="slider-widget">
                                <div class="slider-header">
                                    <span class="slider-label-text">Moisture</span>
                                    <div class="slider-value-badge">
                                        <span class="slider-value-num" id="disp-Soil_Moisture">{{ old('Soil_Moisture', $data['Soil_Moisture'] ?? '30') }}</span>
                                        <span class="slider-desc-pill" id="pill-Soil_Moisture" style="background:#fef08a;color:#92400e">Kering</span>
                                    </div>
                                </div>
                                <input type="range" class="styled-range" id="range-Soil_Moisture" min="0" max="100" step="1" value="{{ old('Soil_Moisture', $data['Soil_Moisture'] ?? '30') }}" oninput="updateSlider('Soil_Moisture',this.value)">
                                <input type="hidden" name="Soil_Moisture" id="input-Soil_Moisture" value="{{ old('Soil_Moisture', $data['Soil_Moisture'] ?? '30') }}" required>
                                <div class="slider-ticks"><span class="slider-tick-label">0% Kering</span><span class="slider-tick-label">100% Jenuh</span></div>
                            </div>
                        </div>

                        <!-- Karbon Organik -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Karbon Organik</label>
                            <div class="slider-widget">
                                <div class="slider-header">
                                    <span class="slider-label-text">Kandungan</span>
                                    <div class="slider-value-badge">
                                        <span class="slider-value-num" id="disp-Organic_Carbon">{{ old('Organic_Carbon', $data['Organic_Carbon'] ?? '1.5') }}</span>
                                        <span class="slider-desc-pill" id="pill-Organic_Carbon" style="background:#fed7aa;color:#c2410c">Rendah</span>
                                    </div>
                                </div>
                                <input type="range" class="styled-range" id="range-Organic_Carbon" min="0" max="6" step="0.1" value="{{ old('Organic_Carbon', $data['Organic_Carbon'] ?? '1.5') }}" oninput="updateSlider('Organic_Carbon',this.value)">
                                <input type="hidden" name="Organic_Carbon" id="input-Organic_Carbon" value="{{ old('Organic_Carbon', $data['Organic_Carbon'] ?? '1.5') }}" required>
                                <div class="slider-ticks"><span class="slider-tick-label">0 Rendah</span><span class="slider-tick-label">6 Tinggi</span></div>
                            </div>
                        </div>

                        <!-- Konduktivitas Elektrik -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Konduktivitas Elektrik</label>
                            <div class="slider-widget">
                                <div class="slider-header">
                                    <span class="slider-label-text">EC (dS/m)</span>
                                    <div class="slider-value-badge">
                                        <span class="slider-value-num" id="disp-Electrical_Conductivity">{{ old('Electrical_Conductivity', $data['Electrical_Conductivity'] ?? '1.0') }}</span>
                                        <span class="slider-desc-pill" id="pill-Electrical_Conductivity" style="background:#d1fae5;color:#065f46">Normal</span>
                                    </div>
                                </div>
                                <input type="range" class="styled-range" id="range-Electrical_Conductivity" min="0" max="6" step="0.1" value="{{ old('Electrical_Conductivity', $data['Electrical_Conductivity'] ?? '1.0') }}" oninput="updateSlider('Electrical_Conductivity',this.value)">
                                <input type="hidden" name="Electrical_Conductivity" id="input-Electrical_Conductivity" value="{{ old('Electrical_Conductivity', $data['Electrical_Conductivity'] ?? '1.0') }}" required>
                                <div class="slider-ticks"><span class="slider-tick-label">0 Rendah</span><span class="slider-tick-label">6 Salin</span></div>
                            </div>
                        </div>

                        <!-- Nitrogen -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nitrogen (N)</label>
                            <div class="slider-widget">
                                <div class="slider-header">
                                    <span class="slider-label-text">Kadar N</span>
                                    <div class="slider-value-badge">
                                        <span class="slider-value-num" id="disp-Nitrogen_Level">{{ old('Nitrogen_Level', $data['Nitrogen_Level'] ?? '50') }}</span>
                                        <span class="slider-desc-pill" id="pill-Nitrogen_Level" style="background:#d1fae5;color:#065f46">Cukup</span>
                                    </div>
                                </div>
                                <input type="range" class="styled-range" id="range-Nitrogen_Level" min="0" max="200" step="1" value="{{ old('Nitrogen_Level', $data['Nitrogen_Level'] ?? '50') }}" oninput="updateSlider('Nitrogen_Level',this.value)">
                                <input type="hidden" name="Nitrogen_Level" id="input-Nitrogen_Level" value="{{ old('Nitrogen_Level', $data['Nitrogen_Level'] ?? '50') }}" required>
                                <div class="slider-ticks"><span class="slider-tick-label">0 Defisit</span><span class="slider-tick-label">200 Berlebih</span></div>
                            </div>
                        </div>

                        <!-- Fosfor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fosfor (P)</label>
                            <div class="slider-widget">
                                <div class="slider-header">
                                    <span class="slider-label-text">Kadar P</span>
                                    <div class="slider-value-badge">
                                        <span class="slider-value-num" id="disp-Phosphorus_Level">{{ old('Phosphorus_Level', $data['Phosphorus_Level'] ?? '30') }}</span>
                                        <span class="slider-desc-pill" id="pill-Phosphorus_Level" style="background:#d1fae5;color:#065f46">Cukup</span>
                                    </div>
                                </div>
                                <input type="range" class="styled-range" id="range-Phosphorus_Level" min="0" max="150" step="1" value="{{ old('Phosphorus_Level', $data['Phosphorus_Level'] ?? '30') }}" oninput="updateSlider('Phosphorus_Level',this.value)">
                                <input type="hidden" name="Phosphorus_Level" id="input-Phosphorus_Level" value="{{ old('Phosphorus_Level', $data['Phosphorus_Level'] ?? '30') }}" required>
                                <div class="slider-ticks"><span class="slider-tick-label">0 Defisit</span><span class="slider-tick-label">150 Berlebih</span></div>
                            </div>
                        </div>

                        <!-- Kalium -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kalium (K)</label>
                            <div class="slider-widget">
                                <div class="slider-header">
                                    <span class="slider-label-text">Kadar K</span>
                                    <div class="slider-value-badge">
                                        <span class="slider-value-num" id="disp-Potassium_Level">{{ old('Potassium_Level', $data['Potassium_Level'] ?? '100') }}</span>
                                        <span class="slider-desc-pill" id="pill-Potassium_Level" style="background:#d1fae5;color:#065f46">Cukup</span>
                                    </div>
                                </div>
                                <input type="range" class="styled-range" id="range-Potassium_Level" min="0" max="500" step="1" value="{{ old('Potassium_Level', $data['Potassium_Level'] ?? '100') }}" oninput="updateSlider('Potassium_Level',this.value)">
                                <input type="hidden" name="Potassium_Level" id="input-Potassium_Level" value="{{ old('Potassium_Level', $data['Potassium_Level'] ?? '100') }}" required>
                                <div class="slider-ticks"><span class="slider-tick-label">0 Defisit</span><span class="slider-tick-label">500 Berlebih</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Kelompok Cuaca & Lokasi -->
                <div>
                    <h3 class="section-title">Cuaca & Lokasi</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Suhu -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Suhu (&#176;C)</label>
                            <div class="slider-widget">
                                <div class="slider-header">
                                    <span class="slider-label-text">Temperatur</span>
                                    <div class="slider-value-badge">
                                        <span class="slider-value-num" id="disp-Temperature">{{ old('Temperature', $data['Temperature'] ?? '25') }}</span>
                                        <span class="slider-desc-pill" id="pill-Temperature" style="background:#fef3c7;color:#92400e">Hangat</span>
                                    </div>
                                </div>
                                <input type="range" class="styled-range" id="range-Temperature" min="5" max="50" step="0.5" value="{{ old('Temperature', $data['Temperature'] ?? '25') }}" oninput="updateSlider('Temperature',this.value)">
                                <input type="hidden" name="Temperature" id="input-Temperature" value="{{ old('Temperature', $data['Temperature'] ?? '25') }}" required>
                                <div class="slider-ticks"><span class="slider-tick-label">5&#176;C Dingin</span><span class="slider-tick-label">50&#176;C Panas</span></div>
                            </div>
                        </div>

                        <!-- Kelembapan Udara -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kelembapan Udara (%)</label>
                            <div class="slider-widget">
                                <div class="slider-header">
                                    <span class="slider-label-text">Humidity</span>
                                    <div class="slider-value-badge">
                                        <span class="slider-value-num" id="disp-Humidity">{{ old('Humidity', $data['Humidity'] ?? '55') }}</span>
                                        <span class="slider-desc-pill" id="pill-Humidity" style="background:#d1fae5;color:#065f46">Sedang</span>
                                    </div>
                                </div>
                                <input type="range" class="styled-range" id="range-Humidity" min="0" max="100" step="1" value="{{ old('Humidity', $data['Humidity'] ?? '55') }}" oninput="updateSlider('Humidity',this.value)">
                                <input type="hidden" name="Humidity" id="input-Humidity" value="{{ old('Humidity', $data['Humidity'] ?? '55') }}" required>
                                <div class="slider-ticks"><span class="slider-tick-label">0% Kering</span><span class="slider-tick-label">100% Lembap</span></div>
                            </div>
                        </div>

                        <!-- Curah Hujan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Curah Hujan (mm)</label>
                            <div class="slider-widget">
                                <div class="slider-header">
                                    <span class="slider-label-text">Rainfall</span>
                                    <div class="slider-value-badge">
                                        <span class="slider-value-num" id="disp-Rainfall">{{ old('Rainfall', $data['Rainfall'] ?? '100') }}</span>
                                        <span class="slider-desc-pill" id="pill-Rainfall" style="background:#d1fae5;color:#065f46">Sedang</span>
                                    </div>
                                </div>
                                <input type="range" class="styled-range" id="range-Rainfall" min="0" max="500" step="1" value="{{ old('Rainfall', $data['Rainfall'] ?? '100') }}" oninput="updateSlider('Rainfall',this.value)">
                                <input type="hidden" name="Rainfall" id="input-Rainfall" value="{{ old('Rainfall', $data['Rainfall'] ?? '100') }}" required>
                                <div class="slider-ticks"><span class="slider-tick-label">0 Kering</span><span class="slider-tick-label">500 Lebat</span></div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Region</label>
                            <select name="Region" class="input-field" required>
                                <option value="">Pilih Region</option>
                                <option value="North" {{ (old('Region', $data['Region'] ?? '') == 'North') ? 'selected' : '' }}>Utara (North)</option>
                                <option value="South" {{ (old('Region', $data['Region'] ?? '') == 'South') ? 'selected' : '' }}>Selatan (South)</option>
                                <option value="East" {{ (old('Region', $data['Region'] ?? '') == 'East') ? 'selected' : '' }}>Timur (East)</option>
                                <option value="West" {{ (old('Region', $data['Region'] ?? '') == 'West') ? 'selected' : '' }}>Barat (West)</option>
                                <option value="Central" {{ (old('Region', $data['Region'] ?? '') == 'Central') ? 'selected' : '' }}>Tengah (Central)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Kelompok Pertanian -->
                <div>
                    <h3 class="section-title">Informasi Tanaman & Panen</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Jenis Tanaman -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Tanaman</label>
                            <p class="text-xs text-gray-400 mb-1">Pilih dari daftar atau ketik nama tanaman Anda</p>
                            <div class="combobox-wrap" id="wrap-Crop_Type">
                                <input type="text" class="input-field combobox-input" placeholder="Cari atau ketik tanaman..." id="display-Crop_Type" value="{{ $cropTypeDisplay }}" autocomplete="off" oninput="filterCombo('Crop_Type')" onfocus="openCombo('Crop_Type')" onblur="closeCombo('Crop_Type')">
                                <input type="hidden" name="Crop_Type" id="val-Crop_Type" value="{{ $cropTypeVal }}" required>
                                <div class="combobox-list" id="list-Crop_Type">
                                    <div class="combo-group-label">Serealia &amp; Biji-Bijian</div>
                                    <div class="combo-item" data-value="Wheat">Padi Gandum (Wheat)</div>
                                    <div class="combo-item" data-value="Maize">Jagung (Maize)</div>
                                    <div class="combo-item" data-value="Rice">Padi (Rice)</div>
                                    <div class="combo-item" data-value="Barley">Jelai (Barley)</div>
                                    <div class="combo-item" data-value="Sorghum">Sorgum (Sorgum)</div>
                                    <div class="combo-item" data-value="Millet">Millet / Juwawut</div>
                                    <div class="combo-group-label">Sayuran &amp; Umbi</div>
                                    <div class="combo-item" data-value="Potato">Kentang (Potato)</div>
                                    <div class="combo-item" data-value="Tomato">Tomat (Tomato)</div>
                                    <div class="combo-item" data-value="Onion">Bawang (Onion)</div>
                                    <div class="combo-item" data-value="Garlic">Bawang Putih (Garlic)</div>
                                    <div class="combo-item" data-value="Cabbage">Kubis (Cabbage)</div>
                                    <div class="combo-item" data-value="Carrot">Wortel (Carrot)</div>
                                    <div class="combo-item" data-value="Spinach">Bayam (Spinach)</div>
                                    <div class="combo-item" data-value="Pakchoi">Pokcoy / Pakcoy</div>
                                    <div class="combo-item" data-value="Chili">Cabai (Chili)</div>
                                    <div class="combo-item" data-value="Eggplant">Terong (Eggplant)</div>
                                    <div class="combo-group-label">Buah-Buahan</div>
                                    <div class="combo-item" data-value="Banana">Pisang (Banana)</div>
                                    <div class="combo-item" data-value="Mango">Mangga (Mango)</div>
                                    <div class="combo-item" data-value="Sugarcane">Tebu (Sugarcane)</div>
                                    <div class="combo-item" data-value="Watermelon">Semangka (Watermelon)</div>
                                    <div class="combo-item" data-value="Melon">Melon</div>
                                    <div class="combo-group-label">Tanaman Industri &amp; Perkebunan</div>
                                    <div class="combo-item" data-value="Cotton">Kapas (Cotton)</div>
                                    <div class="combo-item" data-value="Soybean">Kedelai (Soybean)</div>
                                    <div class="combo-item" data-value="Groundnut">Kacang Tanah (Groundnut)</div>
                                    <div class="combo-item" data-value="Coffee">Kopi (Coffee)</div>
                                    <div class="combo-item" data-value="Cocoa">Kakao (Cocoa)</div>
                                    <div class="combo-item" data-value="Tobacco">Tembakau (Tobacco)</div>
                                    <div class="combo-item" data-value="Rubber">Karet (Rubber)</div>
                                    <div class="combo-item" data-value="Oilpalm">Kelapa Sawit (Oil Palm)</div>
                                    <div class="combo-item" data-value="Coconut">Kelapa (Coconut)</div>
                                </div>
                            </div>
                        </div>

                        <!-- Fase Tumbuh -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fase Tumbuh Saat Ini</label>
                            <p class="text-xs text-gray-400 mb-1">Di tahap mana tanaman Anda sekarang?</p>
                            <select name="Crop_Growth_Stage" class="input-field" required>
                                <option value="">-- Pilih Fase Tumbuh --</option>
                                <optgroup label="Awal Tanam">
                                    <option value="Germination" {{ (old('Crop_Growth_Stage', $data['Crop_Growth_Stage'] ?? '') == 'Germination') ? 'selected' : '' }}>Perkecambahan - baru mulai tumbuh dari benih</option>
                                    <option value="Seedling" {{ (old('Crop_Growth_Stage', $data['Crop_Growth_Stage'] ?? '') == 'Seedling') ? 'selected' : '' }}>Bibit / Persemaian - tanaman masih kecil</option>
                                </optgroup>
                                <optgroup label="Masa Tumbuh">
                                    <option value="Vegetative" {{ (old('Crop_Growth_Stage', $data['Crop_Growth_Stage'] ?? '') == 'Vegetative') ? 'selected' : '' }}>Pertumbuhan Daun &amp; Batang - tanaman aktif berkembang</option>
                                    <option value="Tillering" {{ (old('Crop_Growth_Stage', $data['Crop_Growth_Stage'] ?? '') == 'Tillering') ? 'selected' : '' }}>Anakan - mulai membentuk rumpun (padi/jagung)</option>
                                </optgroup>
                                <optgroup label="Pembungaan &amp; Buah">
                                    <option value="Flowering" {{ (old('Crop_Growth_Stage', $data['Crop_Growth_Stage'] ?? '') == 'Flowering') ? 'selected' : '' }}>Berbunga - muncul bunga</option>
                                    <option value="Fruiting" {{ (old('Crop_Growth_Stage', $data['Crop_Growth_Stage'] ?? '') == 'Fruiting') ? 'selected' : '' }}>Pembentukan Buah - buah mulai terbentuk</option>
                                    <option value="Ripening" {{ (old('Crop_Growth_Stage', $data['Crop_Growth_Stage'] ?? '') == 'Ripening') ? 'selected' : '' }}>Pematangan - buah/biji sedang matang</option>
                                </optgroup>
                                <optgroup label="Akhir Siklus">
                                    <option value="Harvest" {{ (old('Crop_Growth_Stage', $data['Crop_Growth_Stage'] ?? '') == 'Harvest') ? 'selected' : '' }}>Siap Panen - waktu memanen</option>
                                    <option value="Post-Harvest" {{ (old('Crop_Growth_Stage', $data['Crop_Growth_Stage'] ?? '') == 'Post-Harvest') ? 'selected' : '' }}>Pasca Panen - setelah dipanen, lahan istirahat</option>
                                </optgroup>
                            </select>
                        </div>

                        <!-- Musim -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Musim Saat Tanam</label>
                            <p class="text-xs text-gray-400 mb-1">Pilih kondisi musim di daerah Anda saat ini</p>
                            <select name="Season" class="input-field" required>
                                <option value="">-- Pilih Musim --</option>
                                <optgroup label="Musim di Indonesia">
                                    <option value="Kharif" {{ (old('Season', $data['Season'] ?? '') == 'Kharif') ? 'selected' : '' }}>Musim Hujan - curah hujan tinggi (biasanya Apr-Sep)</option>
                                    <option value="Rabi" {{ (old('Season', $data['Season'] ?? '') == 'Rabi') ? 'selected' : '' }}>Musim Kemarau - curah hujan rendah/kering (Okt-Mar)</option>
                                    <option value="Zaid" {{ (old('Season', $data['Season'] ?? '') == 'Zaid') ? 'selected' : '' }}>Musim Pancaroba / Peralihan - antara hujan dan kemarau</option>
                                </optgroup>
                            </select>
                        </div>

                        <!-- Metode Irigasi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cara Pengairan Lahan</label>
                            <p class="text-xs text-gray-400 mb-1">Bagaimana Anda mengairi lahan?</p>
                            <select name="Irrigation_Type" class="input-field" required>
                                <option value="">-- Pilih Cara Pengairan --</option>
                                <optgroup label="Aliran Air">
                                    <option value="Canal" {{ (old('Irrigation_Type', $data['Irrigation_Type'] ?? '') == 'Canal') ? 'selected' : '' }}>Saluran Irigasi / Got - air mengalir dari sungai/saluran</option>
                                    <option value="Flood" {{ (old('Irrigation_Type', $data['Irrigation_Type'] ?? '') == 'Flood') ? 'selected' : '' }}>Leb / Banjiran - lahan digenangi air (sawah)</option>
                                    <option value="Furrow" {{ (old('Irrigation_Type', $data['Irrigation_Type'] ?? '') == 'Furrow') ? 'selected' : '' }}>Alur Parit - air dialirkan melalui parit kecil di antara tanaman</option>
                                </optgroup>
                                <optgroup label="Semprot &amp; Tetes">
                                    <option value="Sprinkler" {{ (old('Irrigation_Type', $data['Irrigation_Type'] ?? '') == 'Sprinkler') ? 'selected' : '' }}>Sprinkler / Penyiram Putar - disemprot dari atas</option>
                                    <option value="Drip" {{ (old('Irrigation_Type', $data['Irrigation_Type'] ?? '') == 'Drip') ? 'selected' : '' }}>Tetes (Drip) - selang tetes langsung ke akar</option>
                                </optgroup>
                                <optgroup label="Tidak Ada Irigasi">
                                    <option value="Rainfed" {{ (old('Irrigation_Type', $data['Irrigation_Type'] ?? '') == 'Rainfed') ? 'selected' : '' }}>Tadah Hujan - hanya mengandalkan curah hujan</option>
                                    <option value="Manual" {{ (old('Irrigation_Type', $data['Irrigation_Type'] ?? '') == 'Manual') ? 'selected' : '' }}>Siram Manual - disiram dengan ember/pompa tangan</option>
                                </optgroup>
                            </select>
                        </div>

                        <!-- Tanaman Sebelumnya -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanaman Sebelumnya di Lahan Ini</label>
                            <p class="text-xs text-gray-400 mb-1">Tanaman apa yang ditanam terakhir di lahan ini?</p>
                            <div class="combobox-wrap" id="wrap-Previous_Crop">
                                <input type="text" class="input-field combobox-input" placeholder="Cari atau ketik tanaman sebelumnya..." id="display-Previous_Crop" value="{{ $prevCropDisplay }}" autocomplete="off" oninput="filterCombo('Previous_Crop')" onfocus="openCombo('Previous_Crop')" onblur="closeCombo('Previous_Crop')">
                                <input type="hidden" name="Previous_Crop" id="val-Previous_Crop" value="{{ $prevCropVal }}" required>
                                <div class="combobox-list" id="list-Previous_Crop">
                                    <div class="combo-item" data-value="None">Tidak Ada / Lahan Baru</div>
                                    <div class="combo-group-label">Serealia</div>
                                    <div class="combo-item" data-value="Wheat">Padi Gandum (Wheat)</div>
                                    <div class="combo-item" data-value="Maize">Jagung (Maize)</div>
                                    <div class="combo-item" data-value="Rice">Padi (Rice)</div>
                                    <div class="combo-item" data-value="Barley">Jelai (Barley)</div>
                                    <div class="combo-item" data-value="Sorghum">Sorgum</div>
                                    <div class="combo-group-label">Sayuran &amp; Umbi</div>
                                    <div class="combo-item" data-value="Potato">Kentang (Potato)</div>
                                    <div class="combo-item" data-value="Tomato">Tomat (Tomato)</div>
                                    <div class="combo-item" data-value="Onion">Bawang (Onion)</div>
                                    <div class="combo-item" data-value="Cabbage">Kubis (Cabbage)</div>
                                    <div class="combo-item" data-value="Carrot">Wortel (Carrot)</div>
                                    <div class="combo-item" data-value="Spinach">Bayam (Spinach)</div>
                                    <div class="combo-item" data-value="Chili">Cabai (Chili)</div>
                                    <div class="combo-group-label">Tanaman Industri</div>
                                    <div class="combo-item" data-value="Cotton">Kapas (Cotton)</div>
                                    <div class="combo-item" data-value="Soybean">Kedelai (Soybean)</div>
                                    <div class="combo-item" data-value="Groundnut">Kacang Tanah</div>
                                    <div class="combo-item" data-value="Sugarcane">Tebu (Sugarcane)</div>
                                    <div class="combo-item" data-value="Tobacco">Tembakau (Tobacco)</div>
                                </div>
                            </div>
                        </div>
                        <!-- Pupuk Sebelumnya -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pupuk Musim Lalu <span class="font-normal text-gray-400">(kg/ha)</span></label>
                            <div class="slider-widget">
                                <div class="slider-header">
                                    <span class="slider-label-text">Jumlah Pupuk</span>
                                    <div class="slider-value-badge">
                                        <span class="slider-value-num" id="disp-Fertilizer_Used_Last_Season">{{ old('Fertilizer_Used_Last_Season', $data['Fertilizer_Used_Last_Season'] ?? '100') }}</span>
                                        <span class="slider-desc-pill" id="pill-Fertilizer_Used_Last_Season" style="background:#d1fae5;color:#065f46">Sedang</span>
                                    </div>
                                </div>
                                <input type="range" class="styled-range" id="range-Fertilizer_Used_Last_Season" min="0" max="600" step="5" value="{{ old('Fertilizer_Used_Last_Season', $data['Fertilizer_Used_Last_Season'] ?? '100') }}" oninput="updateSlider('Fertilizer_Used_Last_Season',this.value)">
                                <input type="hidden" name="Fertilizer_Used_Last_Season" id="input-Fertilizer_Used_Last_Season" value="{{ old('Fertilizer_Used_Last_Season', $data['Fertilizer_Used_Last_Season'] ?? '100') }}" required>
                                <div class="slider-ticks"><span class="slider-tick-label">0 Tidak Ada</span><span class="slider-tick-label">600 kg Banyak</span></div>
                            </div>
                        </div>

                        <!-- Hasil Panen -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hasil Panen Musim Lalu <span class="font-normal text-gray-400">(ton/ha)</span></label>
                            <div class="slider-widget">
                                <div class="slider-header">
                                    <span class="slider-label-text">Produktivitas</span>
                                    <div class="slider-value-badge">
                                        <span class="slider-value-num" id="disp-Yield_Last_Season">{{ old('Yield_Last_Season', $data['Yield_Last_Season'] ?? '3.0') }}</span>
                                        <span class="slider-desc-pill" id="pill-Yield_Last_Season" style="background:#fef3c7;color:#92400e">Rata-rata</span>
                                    </div>
                                </div>
                                <input type="range" class="styled-range" id="range-Yield_Last_Season" min="0" max="15" step="0.1" value="{{ old('Yield_Last_Season', $data['Yield_Last_Season'] ?? '3.0') }}" oninput="updateSlider('Yield_Last_Season',this.value)">
                                <input type="hidden" name="Yield_Last_Season" id="input-Yield_Last_Season" value="{{ old('Yield_Last_Season', $data['Yield_Last_Season'] ?? '3.0') }}" required>
                                <div class="slider-ticks"><span class="slider-tick-label">0 Gagal</span><span class="slider-tick-label">5 Rata-rata</span><span class="slider-tick-label">15 ton Luar Biasa</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-[#1a73e8] hover:bg-blue-700 text-white text-lg font-semibold rounded-lg transition-colors focus:outline-none focus:ring-4 focus:ring-blue-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Simpan Perubahan & Proses Ulang AI
                    </button>
                </div>
            </form>
        </div>
        
    </main>

    <script>
        /* ═══ SLIDER CONFIG ═══
           Setiap field punya array langkah dengan label deskriptif & warna pill */
        const SLIDER_CONFIG = {
            Temperature: {
                steps: [
                    { max:10,  label:'Sangat Dingin', bg:'#dbeafe', color:'#1e40af' },
                    { max:16,  label:'Dingin',        bg:'#bfdbfe', color:'#1d4ed8' },
                    { max:22,  label:'Sejuk',         bg:'#a7f3d0', color:'#065f46' },
                    { max:28,  label:'Hangat',        bg:'#fef3c7', color:'#92400e' },
                    { max:35,  label:'Panas',         bg:'#fed7aa', color:'#c2410c' },
                    { max:50,  label:'Sangat Panas',  bg:'#fecaca', color:'#b91c1c' }
                ]
            },
            Humidity: {
                steps: [
                    { max:20,  label:'Sangat Kering', bg:'#fef9c3', color:'#854d0e' },
                    { max:40,  label:'Kering',         bg:'#fef08a', color:'#92400e' },
                    { max:60,  label:'Sedang',         bg:'#d1fae5', color:'#065f46' },
                    { max:80,  label:'Lembap',         bg:'#bae6fd', color:'#0369a1' },
                    { max:100, label:'Sangat Lembap',  bg:'#93c5fd', color:'#1e3a8a' }
                ]
            },
            Soil_Moisture: {
                steps: [
                    { max:15,  label:'Sangat Kering', bg:'#fef9c3', color:'#854d0e' },
                    { max:30,  label:'Kering',         bg:'#fef08a', color:'#92400e' },
                    { max:55,  label:'Optimal',        bg:'#d1fae5', color:'#065f46' },
                    { max:75,  label:'Basah',          bg:'#bae6fd', color:'#0369a1' },
                    { max:100, label:'Jenuh Air',      bg:'#93c5fd', color:'#1e3a8a' }
                ]
            },
            Rainfall: {
                steps: [
                    { max:50,  label:'Sangat Kering', bg:'#fef9c3', color:'#854d0e' },
                    { max:100, label:'Rendah',         bg:'#fef3c7', color:'#92400e' },
                    { max:200, label:'Sedang',         bg:'#d1fae5', color:'#065f46' },
                    { max:300, label:'Lebat',          bg:'#bae6fd', color:'#0369a1' },
                    { max:500, label:'Sangat Lebat',   bg:'#93c5fd', color:'#1e3a8a' }
                ]
            },
            Soil_pH: {
                steps: [
                    { max:4.5, label:'Sangat Asam', bg:'#fecaca', color:'#b91c1c' },
                    { max:5.5, label:'Asam',         bg:'#fed7aa', color:'#c2410c' },
                    { max:6.5, label:'Agak Asam',   bg:'#fef3c7', color:'#92400e' },
                    { max:7.0, label:'Netral',        bg:'#d1fae5', color:'#065f46' },
                    { max:7.5, label:'Agak Basa',   bg:'#a7f3d0', color:'#047857' },
                    { max:8.5, label:'Basa',          bg:'#bae6fd', color:'#0369a1' },
                    { max:9.5, label:'Sangat Basa', bg:'#c7d2fe', color:'#3730a3' }
                ]
            },
            Organic_Carbon: {
                steps: [
                    { max:1.0, label:'Sangat Rendah', bg:'#fecaca', color:'#b91c1c' },
                    { max:2.0, label:'Rendah',         bg:'#fed7aa', color:'#c2410c' },
                    { max:3.5, label:'Sedang',         bg:'#d1fae5', color:'#065f46' },
                    { max:5.0, label:'Tinggi',         bg:'#a7f3d0', color:'#047857' },
                    { max:6.0, label:'Sangat Tinggi', bg:'#6ee7b7', color:'#065f46' }
                ]
            },
            Electrical_Conductivity: {
                steps: [
                    { max:0.5, label:'Sangat Rendah', bg:'#f3f4f6', color:'#6b7280' },
                    { max:1.5, label:'Normal',         bg:'#d1fae5', color:'#065f46' },
                    { max:3.0, label:'Agak Salin',    bg:'#fef3c7', color:'#92400e' },
                    { max:6.0, label:'Sangat Salin',  bg:'#fecaca', color:'#b91c1c' }
                ]
            },
            Nitrogen_Level: {
                steps: [
                    { max:30,  label:'Defisit',   bg:'#fecaca', color:'#b91c1c' },
                    { max:60,  label:'Rendah',    bg:'#fed7aa', color:'#c2410c' },
                    { max:100, label:'Cukup',     bg:'#d1fae5', color:'#065f46' },
                    { max:150, label:'Tinggi',    bg:'#a7f3d0', color:'#047857' },
                    { max:200, label:'Berlebih',  bg:'#fef9c3', color:'#854d0e' }
                ]
            },
            Phosphorus_Level: {
                steps: [
                    { max:15,  label:'Defisit',  bg:'#fecaca', color:'#b91c1c' },
                    { max:30,  label:'Rendah',   bg:'#fed7aa', color:'#c2410c' },
                    { max:60,  label:'Cukup',    bg:'#d1fae5', color:'#065f46' },
                    { max:100, label:'Tinggi',   bg:'#a7f3d0', color:'#047857' },
                    { max:150, label:'Berlebih', bg:'#fef9c3', color:'#854d0e' }
                ]
            },
            Potassium_Level: {
                steps: [
                    { max:50,  label:'Defisit',  bg:'#fecaca', color:'#b91c1c' },
                    { max:100, label:'Rendah',   bg:'#fed7aa', color:'#c2410c' },
                    { max:200, label:'Cukup',    bg:'#d1fae5', color:'#065f46' },
                    { max:350, label:'Tinggi',   bg:'#a7f3d0', color:'#047857' },
                    { max:500, label:'Berlebih', bg:'#fef9c3', color:'#854d0e' }
                ]
            },
            Fertilizer_Used_Last_Season: {
                steps: [
                    { max:50,  label:'Tidak Ada', bg:'#f3f4f6', color:'#6b7280' },
                    { max:150, label:'Sedikit',   bg:'#fef3c7', color:'#92400e' },
                    { max:300, label:'Sedang',    bg:'#d1fae5', color:'#065f46' },
                    { max:450, label:'Banyak',    bg:'#a7f3d0', color:'#047857' },
                    { max:600, label:'Berlebih',  bg:'#fef9c3', color:'#854d0e' }
                ]
            },
            Yield_Last_Season: {
                steps: [
                    { max:0.5, label:'Gagal Panen', bg:'#fecaca', color:'#b91c1c' },
                    { max:2.0, label:'Rendah',       bg:'#fed7aa', color:'#c2410c' },
                    { max:4.0, label:'Rata-rata',    bg:'#fef3c7', color:'#92400e' },
                    { max:7.0, label:'Baik',         bg:'#d1fae5', color:'#065f46' },
                    { max:10,  label:'Sangat Baik',  bg:'#a7f3d0', color:'#047857' },
                    { max:15,  label:'Luar Biasa',   bg:'#6ee7b7', color:'#065f46' }
                ]
            }
        };

        function getDescriptor(fieldId, val) {
            const cfg = SLIDER_CONFIG[fieldId];
            if (!cfg) return { label: String(val), bg: '#eff6ff', color: '#1d4ed8' };
            const v = parseFloat(val);
            for (const step of cfg.steps) {
                if (v <= step.max) return { label: step.label, bg: step.bg, color: step.color };
            }
            const last = cfg.steps[cfg.steps.length - 1];
            return { label: last.label, bg: last.bg, color: last.color };
        }

        function updateSlider(fieldId, val) {
            // 1. Update hidden form value
            document.getElementById('input-' + fieldId).value = val;

            // 2. Update numeric display
            const v = parseFloat(val);
            document.getElementById('disp-' + fieldId).textContent =
                (v % 1 === 0) ? v.toFixed(0) : v.toFixed(1);

            // 3. Update descriptor pill
            const desc = getDescriptor(fieldId, val);
            const pill = document.getElementById('pill-' + fieldId);
            pill.textContent  = desc.label;
            pill.style.background = desc.bg;
            pill.style.color  = desc.color;

            // 4. Update slider track fill
            const el  = document.getElementById('range-' + fieldId);
            const min = parseFloat(el.min), max = parseFloat(el.max);
            const pct = ((v - min) / (max - min)) * 100;
            el.style.background =
                'linear-gradient(to right,' +
                desc.color + ' 0%,' + desc.color + ' ' + pct + '%,' +
                '#e5e7eb ' + pct + '%,#e5e7eb 100%)';
        }

        // Init all sliders on page load
        document.addEventListener('DOMContentLoaded', () => {
            const allSliders = [
                'Temperature','Humidity','Rainfall',
                'Soil_pH','Soil_Moisture','Organic_Carbon',
                'Electrical_Conductivity','Nitrogen_Level',
                'Phosphorus_Level','Potassium_Level',
                'Fertilizer_Used_Last_Season','Yield_Last_Season'
            ];
            allSliders.forEach(id => {
                const el = document.getElementById('range-' + id);
                if (el) updateSlider(id, el.value);
            });

            // Attach combobox click handlers
            document.querySelectorAll('.combo-item').forEach(item => {
                item.addEventListener('mousedown', e => {
                    e.preventDefault();
                    const list = item.closest('.combobox-list');
                    const id   = list.id.replace('list-', '');
                    selectCombo(id, item.dataset.value, item.textContent.trim());
                });
            });
        });
    </script>

    <script>
        /* ═══ COMBOBOX LOGIC ═══ */
        const comboCloseTimers = {};

        function openCombo(id) {
            clearTimeout(comboCloseTimers[id]);
            document.getElementById('list-' + id).classList.add('open');
        }

        function closeCombo(id) {
            comboCloseTimers[id] = setTimeout(() => {
                document.getElementById('list-' + id).classList.remove('open');
                const display = document.getElementById('display-' + id);
                const hidden  = document.getElementById('val-' + id);
                if (display.value.trim() && !hidden.value) hidden.value = display.value.trim();
            }, 200);
        }

        function filterCombo(id) {
            const q     = document.getElementById('display-' + id).value.toLowerCase();
            const items = document.querySelectorAll('#list-' + id + ' .combo-item');
            let count   = 0;
            items.forEach(item => {
                const match = item.textContent.toLowerCase().includes(q);
                item.classList.toggle('hidden', !match);
                if (match) count++;
            });
            document.querySelectorAll('#list-' + id + ' .combo-group-label').forEach(label => {
                let next = label.nextElementSibling, has = false;
                while (next && !next.classList.contains('combo-group-label')) {
                    if (!next.classList.contains('hidden') && next.classList.contains('combo-item')) has = true;
                    next = next.nextElementSibling;
                }
                label.classList.toggle('hidden', !has);
            });
            const existing = document.querySelector('#list-' + id + ' .combo-no-result');
            if (existing) existing.remove();
            if (count === 0 && q) {
                const msg = document.createElement('div');
                msg.className   = 'combo-no-result';
                msg.textContent = 'Tidak ditemukan. "' + document.getElementById('display-' + id).value + '" akan digunakan langsung.';
                document.getElementById('list-' + id).appendChild(msg);
                document.getElementById('val-' + id).value = document.getElementById('display-' + id).value.trim();
            } else {
                document.getElementById('val-' + id).value = '';
            }
            openCombo(id);
        }

        function selectCombo(id, value, label) {
            document.getElementById('display-' + id).value = label;
            document.getElementById('val-' + id).value     = value;
            document.getElementById('list-' + id).classList.remove('open');
        }
    </script>
</body>
</html>
