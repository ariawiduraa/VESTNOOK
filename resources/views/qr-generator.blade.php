<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IoT Sensor QR Code Generator – Vestnook</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at 50% 50%, #0f172a 0%, #020617 100%);
        }
        .glow-card {
            background: rgba(30, 41, 59, 0.45);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.35);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(37, 99, 235, 0.5);
        }
        .btn-gradient:active {
            transform: translateY(0);
        }
    </style>
</head>
<body class="min-h-screen text-slate-100 flex items-center justify-center p-4 md:p-8">

    <div class="w-full max-w-4xl glow-card rounded-3xl p-6 md:p-10">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-white mb-2">
                VESTNOOK IoT QR Generator
            </h1>
            <p class="text-sm text-slate-400 max-w-lg mx-auto">
                Alat bantu pengembangan untuk mensimulasikan sensor IoT. Membantu menghasilkan QR Code dengan data acak (dummy) untuk dipindai melalui fitur scan analisis.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            <!-- Left: QR Display -->
            <div class="lg:col-span-5 flex flex-col items-center justify-center bg-slate-900/60 rounded-2xl p-6 border border-slate-800/80">
                <div class="p-4 bg-white rounded-2xl shadow-2xl mb-6">
                    <canvas id="qr-canvas" class="w-48 h-48 md:w-56 md:h-56"></canvas>
                </div>
                <button onclick="regenerateData()" class="btn-gradient w-full py-3.5 px-6 rounded-xl text-white font-semibold text-sm flex items-center justify-center gap-2 mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.656 48.656 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
                    </svg>
                    Acak & Generate Data Baru
                </button>
                
                <!-- Kunci Jawaban AI -->
                <div class="w-full p-4 rounded-xl border border-blue-500/25 bg-blue-950/20 text-center">
                    <div class="text-[10px] text-blue-400 font-bold uppercase tracking-wider mb-1">AI Prediction (Kunci Jawaban)</div>
                    <div class="text-lg font-bold text-white tracking-wide" id="ai-prediction">Mengambil data...</div>
                    <div class="text-[9px] text-slate-400 mt-1">Menggunakan setelan default: Rice, Loamy, dll.</div>
                </div>
            </div>

            <!-- Right: Details & JSON Preview -->
            <div class="lg:col-span-7 flex flex-col justify-between space-y-6">
                <!-- Info Table -->
                <div class="bg-slate-950/40 rounded-2xl border border-slate-800/60 p-5">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-blue-400 mb-4">Nilai Parameter Tanah & Cuaca</h3>
                    <div class="grid grid-cols-2 gap-y-3 gap-x-6 text-sm">
                        <div class="flex justify-between border-b border-slate-800/60 pb-1.5">
                            <span class="text-slate-400">pH Tanah (Soil_pH)</span>
                            <span class="font-bold text-emerald-400" id="val-Soil_pH">-</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-800/60 pb-1.5">
                            <span class="text-slate-400">Moisture Tanah (%)</span>
                            <span class="font-bold text-emerald-400" id="val-Soil_Moisture">-</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-800/60 pb-1.5">
                            <span class="text-slate-400">Karbon Organik</span>
                            <span class="font-bold text-emerald-400" id="val-Organic_Carbon">-</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-800/60 pb-1.5">
                            <span class="text-slate-400">Konduktivitas (EC)</span>
                            <span class="font-bold text-emerald-400" id="val-Electrical_Conductivity">-</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-800/60 pb-1.5">
                            <span class="text-slate-400">Kadar Nitrogen (N)</span>
                            <span class="font-bold text-emerald-400" id="val-Nitrogen_Level">-</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-800/60 pb-1.5">
                            <span class="text-slate-400">Kadar Fosfor (P)</span>
                            <span class="font-bold text-emerald-400" id="val-Phosphorus_Level">-</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-800/60 pb-1.5">
                            <span class="text-slate-400">Kadar Kalium (K)</span>
                            <span class="font-bold text-emerald-400" id="val-Potassium_Level">-</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-800/60 pb-1.5">
                            <span class="text-slate-400">Suhu Udara (&deg;C)</span>
                            <span class="font-bold text-emerald-400" id="val-Temperature">-</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-800/60 pb-1.5 col-span-2">
                            <span class="text-slate-400">Kelembapan Udara (Humidity %)</span>
                            <span class="font-bold text-emerald-400" id="val-Humidity">-</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-800/60 pb-1.5 col-span-2">
                            <span class="text-slate-400">Curah Hujan (Rainfall mm)</span>
                            <span class="font-bold text-emerald-400" id="val-Rainfall">-</span>
                        </div>
                    </div>
                </div>

                <!-- JSON Code Block -->
                <div class="flex-grow flex flex-col bg-slate-950/80 rounded-2xl border border-slate-800 p-5">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Raw Data JSON Payload</span>
                        <button onclick="copyToClipboard()" class="text-xs text-blue-400 hover:text-blue-300 font-semibold flex items-center gap-1 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                            Copy JSON
                        </button>
                    </div>
                    <pre class="text-xs text-emerald-400 font-mono overflow-auto flex-grow max-h-40 whitespace-pre-wrap" id="json-preview"></pre>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-800/60 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <span>&copy; 2026 VESTNOOK AI Smart Farming Engine. All rights reserved.</span>
            <div class="flex gap-4">
                <a href="/analisis" class="text-blue-400 hover:underline">Kembali ke Halaman Analisis</a>
                <span>&bull;</span>
                <a href="/dashboard" class="text-blue-400 hover:underline">Dashboard</a>
            </div>
        </div>
    </div>

    <script>
        var qr = new QRious({
            element: document.getElementById('qr-canvas'),
            size: 250,
            level: 'M'
        });

        function getRandomFloat(min, max, decimals = 1) {
            const str = (Math.random() * (max - min) + min).toFixed(decimals);
            return parseFloat(str);
        }

        function getRandomInt(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        function generateDummyData() {
            return {
                Soil_pH: getRandomFloat(3.5, 9.5, 1),
                Soil_Moisture: getRandomInt(5, 95),
                Organic_Carbon: getRandomFloat(0.1, 5.9, 1),
                Electrical_Conductivity: getRandomFloat(0.1, 5.9, 1),
                Nitrogen_Level: getRandomInt(10, 190),
                Phosphorus_Level: getRandomInt(5, 145),
                Potassium_Level: getRandomInt(20, 480),
                Temperature: getRandomFloat(10.0, 48.0, 1),
                Humidity: getRandomInt(10, 95),
                Rainfall: getRandomInt(20, 480)
            };
        }

        function updateUI(data) {
            // Update table
            Object.keys(data).forEach(function(key) {
                const el = document.getElementById('val-' + key);
                if (el) {
                    let suffix = '';
                    if (key === 'Temperature') suffix = ' °C';
                    else if (key === 'Soil_Moisture' || key === 'Humidity') suffix = ' %';
                    else if (key === 'Rainfall') suffix = ' mm';
                    else if (key === 'Electrical_Conductivity') suffix = ' dS/m';
                    el.textContent = data[key] + suffix;
                }
            });

            // Update JSON string
            const jsonStr = JSON.stringify(data, null, 2);
            document.getElementById('json-preview').textContent = jsonStr;

            // Generate QR Code
            qr.value = JSON.stringify(data);

            // Fetch AI Prediction key (Answer Key)
            const predEl = document.getElementById('ai-prediction');
            predEl.textContent = 'Memproses...';
            predEl.className = 'text-lg font-bold text-slate-400 tracking-wide';

            const queryParams = new URLSearchParams(data).toString();
            fetch('/predict-dummy?' + queryParams)
                .then(response => {
                    if (!response.ok) throw new Error('Prediction request failed');
                    return response.json();
                })
                .then(res => {
                    if (res.status === 'success') {
                        predEl.textContent = res.rekomendasi_pupuk;
                        predEl.className = 'text-lg font-bold text-emerald-400 tracking-wide';
                    } else {
                        predEl.textContent = 'Gagal memprediksi';
                        predEl.className = 'text-sm font-semibold text-rose-400';
                    }
                })
                .catch(err => {
                    console.error(err);
                    predEl.textContent = 'Koneksi error';
                    predEl.className = 'text-sm font-semibold text-rose-400';
                });
        }

        function regenerateData() {
            const data = generateDummyData();
            updateUI(data);
        }

        function copyToClipboard() {
            const jsonText = document.getElementById('json-preview').textContent;
            navigator.clipboard.writeText(jsonText).then(function() {
                alert('Payload JSON berhasil disalin ke clipboard!');
            }).catch(function(err) {
                console.error('Gagal menyalin text: ', err);
            });
        }

        // Generate data on load
        window.onload = function() {
            regenerateData();
        };
    </script>
</body>
</html>
