<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lahan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;

class LahanController extends Controller
{
    public function store(Request $request)
    {
        $nama_lahan = $request->input('nama_lahan');
        $data_input = $request->except(['_token', 'nama_lahan']);
        $json_input = json_encode($data_input);

        $workingDir = base_path('ml_engine');
        $result = Process::path($workingDir)
            ->env([
                'SystemRoot' => getenv('SystemRoot') ?: 'C:\WINDOWS',
                'PATH'       => getenv('PATH')
            ])
            ->run(['python', 'app.py', $json_input]);

        if ($result->failed()) {
            return back()->withErrors('Gagal menjalankan AI Engine. Pesan: ' . $result->errorOutput());
        }

        $pythonOutput = json_decode($result->output(), true);

        if (!$pythonOutput || !isset($pythonOutput['status']) || $pythonOutput['status'] === 'error') {
            return back()->withErrors('Terjadi kesalahan pada AI: ' . ($pythonOutput['message'] ?? 'Output tidak valid'));
        }

        $lahan = Lahan::create([
            'user_id'          => Auth::id(),
            'nama_lahan'       => $nama_lahan,
            'data_input'       => $data_input,
            'hasil_cluster'    => $pythonOutput['nama_cluster'],
            'rekomendasi_pupuk'=> $pythonOutput['rekomendasi_pupuk'],
            'insight_gemini'   => null,
            'statistik_data'   => [
                'confidence_score' => $pythonOutput['confidence_score']  ?? 0,
                'avg_fit'          => $pythonOutput['avg_fit']            ?? 0,
                'top_alternatives' => $pythonOutput['top_alternatives']   ?? [],
                'param_stats'      => $pythonOutput['param_stats']        ?? [],
            ],
            'insight_statistik'=> null,
        ]);

        return view('result', compact('lahan'));
    }

    /** Halaman detail / hasil analisis */
    public function show(Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::id()) abort(403);
        return view('result', compact('lahan'));
    }

    /** Halaman statistik detail */
    public function statistik(Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::id()) abort(403);
        return view('lahan.statistik', compact('lahan'));
    }

    /** Halaman form edit */
    public function edit(Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::id()) abort(403);
        return view('lahan.edit', compact('lahan'));
    }

    /** Proses update: jalankan ulang Python, reset insight */
    public function update(Request $request, Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::id()) abort(403);

        $nama_lahan = $request->input('nama_lahan');
        $data_input = $request->except(['_token', '_method', 'nama_lahan']);
        $json_input = json_encode($data_input);

        $workingDir = base_path('ml_engine');
        $result = Process::path($workingDir)
            ->env([
                'SystemRoot' => getenv('SystemRoot') ?: 'C:\WINDOWS',
                'PATH'       => getenv('PATH')
            ])
            ->run(['python', 'app.py', $json_input]);

        if ($result->failed()) {
            return back()->withErrors('Gagal menjalankan AI Engine: ' . $result->errorOutput());
        }

        $pythonOutput = json_decode($result->output(), true);

        if (!$pythonOutput || ($pythonOutput['status'] ?? '') === 'error') {
            return back()->withErrors('AI Error: ' . ($pythonOutput['message'] ?? 'Output tidak valid'));
        }

        $lahan->update([
            'nama_lahan'        => $nama_lahan,
            'data_input'        => $data_input,
            'hasil_cluster'     => $pythonOutput['nama_cluster'],
            'rekomendasi_pupuk' => $pythonOutput['rekomendasi_pupuk'],
            'insight_gemini'    => null,
            'statistik_data'    => [
                'confidence_score' => $pythonOutput['confidence_score']  ?? 0,
                'avg_fit'          => $pythonOutput['avg_fit']            ?? 0,
                'top_alternatives' => $pythonOutput['top_alternatives']   ?? [],
                'param_stats'      => $pythonOutput['param_stats']        ?? [],
                // Reset jadwal AI dan status checklist agar di-generate ulang setelah edit
                'ai_schedule'         => null,
                'ai_schedule_checked' => [],
            ],
            'insight_statistik' => null,
        ]);

        return redirect()->route('lahan.show', $lahan)->with('success', 'Lahan berhasil diperbarui.');
    }

    /** Hapus lahan */
    public function destroy(Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::id()) abort(403);
        $lahan->delete();
        return redirect()->route('dashboard')->with('success', 'Data lahan berhasil dihapus.');
    }

    /**
     * AJAX – Insight Gemini ringkas (hasil analisis utama)
     */
    public function insight(Request $request, Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($lahan->insight_gemini) {
            return response()->json(['insight' => $lahan->insight_gemini]);
        }

        $apiKey = env('GEMINI_API_KEY');
        $prompt = "Berikan analisis lahan pertanian singkat dan panduan perawatan yang ramah petani.\n" .
                  "Data lahan:\n" .
                  "- Karakteristik zona lahan: {$lahan->hasil_cluster}\n" .
                  "- Rekomendasi pupuk dari AI: {$lahan->rekomendasi_pupuk}\n\n" .
                  "Panduan penulisan yang WAJIB diikuti:\n" .
                  "1. Gunakan bahasa Indonesia yang sederhana dan hangat.\n" .
                  "2. JANGAN gunakan simbol markdown apapun (tidak ada **, ##, *, ---, atau _).\n" .
                  "3. JANGAN gunakan emoji apapun.\n" .
                  "4. Bagi jawaban menjadi 2 paragraf pendek yang mengalir natural.\n" .
                  "5. Paragraf 1: Penjelasan singkat mengapa pupuk ini cocok untuk zona lahan tersebut.\n" .
                  "6. Paragraf 2: 2 hingga 3 langkah praktis cara aplikasi pupuk yang mudah dipahami.\n" .
                  "7. Akhiri dengan satu kalimat motivasi singkat yang tulus.";

        return $this->callGemini($apiKey, $prompt, $lahan, 'insight_gemini');
    }

    /**
     * AJAX – Insight Gemini detail statistik (membaca semua parameter + persentase)
     */
    public function insightStatistik(Request $request, Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($lahan->insight_statistik) {
            return response()->json(['insight' => $lahan->insight_statistik]);
        }

        $apiKey = env('GEMINI_API_KEY');
        $stats  = $lahan->statistik_data ?? [];
        $params = $stats['param_stats']      ?? [];
        $avgFit = $stats['avg_fit']           ?? 0;
        $conf   = $stats['confidence_score']  ?? 0;
        $pupuk  = $lahan->rekomendasi_pupuk;

        // Susun deskripsi parameter secara tekstual
        $paramLines = '';
        foreach ($params as $p) {
            $paramLines .= "- {$p['label']}: nilai {$p['value']} {$p['unit']} "
                        . "(kesesuaian {$p['fit_pct']}% - status: {$p['status']})\n";
        }

        $prompt = "Kamu adalah konsultan pertanian berpengalaman. Berikan rekomendasi mendalam kepada petani berdasarkan hasil analisis berikut.\n\n" .
                  "Hasil AI:\n" .
                  "- Pupuk yang direkomendasikan: {$pupuk}\n" .
                  "- Tingkat keyakinan model AI: {$conf}%\n" .
                  "- Rata-rata kesesuaian lahan: {$avgFit}%\n" .
                  "- Zona lahan: {$lahan->hasil_cluster}\n\n" .
                  "Detail kesesuaian setiap parameter lahan:\n{$paramLines}\n" .
                  "Panduan penulisan yang WAJIB diikuti:\n" .
                  "1. Gunakan bahasa Indonesia yang jelas dan mudah dipahami petani.\n" .
                  "2. JANGAN gunakan simbol markdown (tidak ada **, ##, *, ---, atau _).\n" .
                  "3. JANGAN gunakan emoji apapun.\n" .
                  "4. Tulis dalam 3 paragraf:\n" .
                  "   Paragraf 1: Evaluasi kondisi lahan secara keseluruhan berdasarkan nilai rata-rata kesesuaian.\n" .
                  "   Paragraf 2: Identifikasi 2-3 parameter dengan kesesuaian paling rendah dan berikan langkah konkret apa yang harus dilakukan petani untuk memperbaikinya (misalnya jika pH rendah, bagaimana cara menaikkannya).\n" .
                  "   Paragraf 3: Panduan spesifik cara penggunaan pupuk {$pupuk} yang tepat untuk kondisi lahan ini, termasuk dosis dan waktu yang disarankan.\n" .
                  "5. Akhiri dengan satu kalimat motivasi tulus.";

        return $this->callGemini($apiKey, $prompt, $lahan, 'insight_statistik');
    }

    /**
     * Helper: Panggil Gemini API, bersihkan output, simpan ke kolom DB, kembalikan JSON.
     */
    private function callGemini(string $apiKey, string $prompt, Lahan $lahan, string $column)
    {
        try {
            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}",
                [
                    'contents' => [[
                        'parts' => [['text' => $prompt]]
                    ]],
                    'generationConfig' => [
                        'temperature'     => 0.7,
                        'maxOutputTokens' => 2048,
                    ],
                ]
            );

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if ($text) {
                    $text = preg_replace('/[\*\_]{1,3}(.+?)[\*\_]{1,3}/', '$1', $text);
                    $text = preg_replace('/^#{1,6}\s+/m', '', $text);
                    $text = preg_replace('/^[-*]\s+/m', '', $text);
                    $text = preg_replace('/---+/', '', $text);
                    $text = trim($text);

                    $lahan->update([$column => $text]);
                    return response()->json(['insight' => $text]);
                }
            }

            return response()->json(['error' => 'AI Vestnook tidak merespons dengan benar.'], 500);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Koneksi ke AI gagal: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Uji prediksi dummy untuk QR Generator.
     */
    public function predictDummy(Request $request)
    {
        $sensorData = $request->all();

        // Standard default parameters
        $defaultInputs = [
            'Soil_Type'                   => 'Loamy',
            'Crop_Type'                   => 'Rice',
            'Crop_Growth_Stage'           => 'Vegetative',
            'Season'                      => 'Kharif',
            'Irrigation_Type'             => 'Canal',
            'Previous_Crop'               => 'None',
            'Region'                      => 'Central',
            'Fertilizer_Used_Last_Season' => 100,
            'Yield_Last_Season'           => 3.0
        ];

        // Merge inputs
        $data_input = array_merge($defaultInputs, $sensorData);
        $json_input = json_encode($data_input);

        $workingDir = base_path('ml_engine');
        $result = Process::path($workingDir)
            ->env([
                'SystemRoot' => getenv('SystemRoot') ?: 'C:\WINDOWS',
                'PATH'       => getenv('PATH')
            ])
            ->run(['python', 'app.py', $json_input]);

        if ($result->failed()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menjalankan AI Engine: ' . $result->errorOutput()
            ], 500);
        }

        $pythonOutput = json_decode($result->output(), true);

        if (!$pythonOutput || !isset($pythonOutput['status']) || $pythonOutput['status'] === 'error') {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada AI: ' . ($pythonOutput['message'] ?? 'Output tidak valid')
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'rekomendasi_pupuk' => $pythonOutput['rekomendasi_pupuk'],
            'nama_cluster' => $pythonOutput['nama_cluster'],
            'confidence_score' => $pythonOutput['confidence_score'] ?? 0
        ]);
    }

    /**
     * Membandingkan dua lahan pengguna secara berdampingan.
     */
    public function compare(Request $request)
    {
        $user = Auth::user();
        $lahans = Lahan::where('user_id', $user->id)->get();
        
        $selectedLahans = collect();
        
        if ($request->has('lahan_ids') && is_array($request->input('lahan_ids'))) {
            // Limit to max 4 to avoid UI breaking
            $ids = array_slice($request->input('lahan_ids'), 0, 4);
            $selectedLahans = Lahan::where('user_id', $user->id)
                                   ->whereIn('id', $ids)
                                   ->get();
        }
        
        return view('lahan.compare', compact('lahans', 'selectedLahans'));
    }

    /**
     * AJAX – Rencana jadwal pemupukan 8 minggu berbasis AI Gemini.
     */
    public function schedule(Request $request, Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $stats = $lahan->statistik_data ?? [];
        
        // Auto-repair: Jika jadwal yang disimpan adalah jadwal fallback generik, abaikan cache
        // agar sistem men-generate ulang jadwal kustom menggunakan Gemini API yang baru
        $hasFallback = isset($stats['ai_schedule']) && 
                       is_array($stats['ai_schedule']) && 
                       count($stats['ai_schedule']) > 0 && 
                       ($stats['ai_schedule'][0]['title'] ?? '') === 'Pemupukan Dasar Awal';

        if (isset($stats['ai_schedule']) && is_array($stats['ai_schedule']) && !$hasFallback) {
            // Jadwal sudah tersimpan di DB — kembalikan beserta state checklist
            return response()->json([
                'schedule' => $stats['ai_schedule'],
                'checked'  => $stats['ai_schedule_checked'] ?? [],
            ]);
        }

        $apiKey = env('GEMINI_API_KEY');
        $crop = $lahan->data_input['Crop_Type'] ?? 'Tanaman';
        $soil = $lahan->data_input['Soil_Type'] ?? 'Tanah';
        $pupuk = $lahan->rekomendasi_pupuk ?? 'Urea';
        $ph = $lahan->data_input['Soil_pH'] ?? 'Netral';
        $moisture = $lahan->data_input['Soil_Moisture'] ?? 'Sedang';

        $prompt = "Berikan rencana jadwal pemupukan 8 minggu terperinci khusus untuk tanaman {$crop} pada tanah {$soil} (pH: {$ph}, kelembaban: {$moisture}%) dengan rekomendasi pupuk utama {$pupuk}.\n" .
                  "Jadwal ini harus logis sesuai kebutuhan nutrisi tanaman.\n" .
                  "Format jawaban harus berupa JSON array valid berisi persis 4 objek dengan key: 'week' (integer), 'title' (string), dan 'desc' (string).\n" .
                  "Contoh output:\n" .
                  "[\n" .
                  "  {\"week\": 1, \"title\": \"Judul Pemupukan Dasar\", \"desc\": \"Deskripsi detail cara pengaplikasian\"},\n" .
                  "  {\"week\": 3, \"title\": \"Judul Pemupukan Tahap 2\", \"desc\": \"Deskripsi detail\"},\n" .
                  "  {\"week\": 6, \"title\": \"Judul Pemupukan Tahap 3\", \"desc\": \"Deskripsi\"},\n" .
                  "  {\"week\": 8, \"title\": \"Judul Evaluasi Akhir\", \"desc\": \"Deskripsi\"}\n" .
                  "]\n" .
                  "Kembalikan HANYA kode JSON array tersebut tanpa pembungkus markdown ```json ... ``` atau teks penjelasan tambahan lainnya.";

        try {
            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}",
                [
                    'contents' => [[
                        'parts' => [['text' => $prompt]]
                    ]],
                    'generationConfig' => [
                        'temperature'     => 0.2,
                        'maxOutputTokens' => 4096,
                        'responseMimeType'=> 'application/json'
                    ],
                ]
            );

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                $text = preg_replace('/```(json)?/', '', $text);
                $text = trim($text);

                $scheduleArray = json_decode($text, true);

                if (is_array($scheduleArray) && count($scheduleArray) > 0) {
                    $stats['ai_schedule']         = $scheduleArray;
                    $stats['ai_schedule_checked'] = []; // Reset checklist saat jadwal baru
                    
                    $lahan->update(['statistik_data' => $stats]);

                    return response()->json([
                        'schedule' => $scheduleArray,
                        'checked'  => [],
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Fallback handled below
        }

        // Fallback default schedule if Gemini fails
        $fallback = [
            ['week' => 1, 'title' => 'Pemupukan Dasar Awal', 'desc' => 'Gunakan pupuk rekomendasi secara merata pada tanah dasar.'],
            ['week' => 3, 'title' => 'Penyiraman Rutin & Penyiangan', 'desc' => 'Jaga kelembaban tanah sesuai parameter ideal dan bersihkan gulma.'],
            ['week' => 6, 'title' => 'Pemupukan Susulan & Pengendalian Hama', 'desc' => 'Berikan pupuk tambahan di sekitar lingkar batang.'],
            ['week' => 8, 'title' => 'Pengecekan Akhir Lahan', 'desc' => 'Pastikan kondisi fisik daun dan buah segar sebelum panen.']
        ];

        // Simpan fallback ke DB agar di reload selanjutnya tidak memanggil API Gemini lagi
        $stats['ai_schedule']         = $fallback;
        $stats['ai_schedule_checked'] = $stats['ai_schedule_checked'] ?? [];
        $lahan->update(['statistik_data' => $stats]);

        return response()->json([
            'schedule' => $fallback,
            'checked'  => $stats['ai_schedule_checked']
        ]);
    }

    /**
     * AJAX – Analisis perbandingan komparatif dua lahan berbasis AI Gemini.
     */
    public function compareInsight(Request $request)
    {
        $user = Auth::user();
        
        if (!$request->has('lahan_ids') || !is_array($request->input('lahan_ids'))) {
            return response()->json(['error' => 'Pilih minimal 2 lahan.'], 400);
        }

        $ids = array_slice($request->input('lahan_ids'), 0, 4);
        $selectedLahans = Lahan::where('user_id', $user->id)->whereIn('id', $ids)->get();

        if ($selectedLahans->count() < 2) {
            return response()->json(['error' => 'Minimal 2 lahan diperlukan untuk perbandingan.'], 400);
        }

        $apiKey = env('GEMINI_API_KEY');
        
        $prompt = "Kamu adalah pakar agronomi digital. Bandingkan lahan-lahan pertanian milik petani berikut dan berikan analisis komparatif detail.\n\n";

        foreach ($selectedLahans as $index => $lahan) {
            $stats = $lahan->data_input;
            $num = $index + 1;
            $prompt .= "Lahan {$num} ({$lahan->nama_lahan}):\n" .
                       "- Tanaman: " . ($stats['Crop_Type'] ?? '-') . "\n" .
                       "- Tipe Tanah: " . ($stats['Soil_Type'] ?? '-') . "\n" .
                       "- Nitrogen: " . ($stats['Nitrogen_Level'] ?? 0) . " mg/kg\n" .
                       "- Phosphorus: " . ($stats['Phosphorus_Level'] ?? 0) . " mg/kg\n" .
                       "- Potassium: " . ($stats['Potassium_Level'] ?? 0) . " mg/kg\n" .
                       "- pH Tanah: " . ($stats['Soil_pH'] ?? 7) . "\n" .
                       "- Kelembaban: " . ($stats['Soil_Moisture'] ?? 0) . " %\n" .
                       "- Rekomendasi Pupuk AI: {$lahan->rekomendasi_pupuk}\n\n";
        }

        $prompt .= "Panduan penulisan yang WAJIB diikuti:\n" .
                   "1. Gunakan bahasa Indonesia yang jelas, edukatif, dan ramah.\n" .
                   "2. JANGAN gunakan simbol markdown (tidak ada **, ##, *, ---, atau _).\n" .
                   "3. JANGAN gunakan emoji apapun.\n" .
                   "4. Tulis dalam 2 paragraf:\n" .
                   "   Paragraf 1: Analisis perbandingan perbedaan nutrisi utama (N-P-K & pH) antara lahan-lahan ini secara objektif.\n" .
                   "   Paragraf 2: Rekomendasi praktis pengelolaan berkelanjutan untuk masing-masing lahan agar hasil panennya maksimal.\n" .
                   "5. Akhiri dengan satu kalimat penyemangat hangat.";

        try {
            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}",
                [
                    'contents' => [[
                        'parts' => [['text' => $prompt]]
                    ]],
                    'generationConfig' => [
                        'temperature'     => 0.7,
                        'maxOutputTokens' => 2048,
                    ],
                ]
            );

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                $text = preg_replace('/[\*\_]{1,3}(.+?)[\*\_]{1,3}/', '$1', $text);
                $text = preg_replace('/^#{1,6}\s+/m', '', $text);
                $text = preg_replace('/^[-*]\s+/m', '', $text);
                $text = preg_replace('/---+/', '', $text);
                $text = trim($text);

                return response()->json(['insight' => $text]);
            }

            return response()->json(['error' => 'Gagal mendapatkan analisis perbandingan.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Koneksi ke AI gagal: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Toggle status checklist pemupukan
     */
    public function scheduleToggle(Request $request, Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $week = $request->input('week');
        $isChecked = $request->input('checked');

        if (!$week) {
            return response()->json(['error' => 'Missing week parameter'], 400);
        }

        $stats = $lahan->statistik_data ?? [];
        $checkedArray = $stats['ai_schedule_checked'] ?? [];

        if ($isChecked === 'true' || $isChecked === true || $isChecked === 1) {
            // Tambahkan ke array jika belum ada
            if (!in_array($week, $checkedArray)) {
                $checkedArray[] = (int) $week;
            }
        } else {
            // Hapus dari array
            $checkedArray = array_filter($checkedArray, function($w) use ($week) {
                return (int)$w !== (int)$week;
            });
            $checkedArray = array_values($checkedArray); // Re-index
        }

        $stats['ai_schedule_checked'] = $checkedArray;
        $lahan->update(['statistik_data' => $stats]);

        return response()->json(['success' => true, 'checked' => $checkedArray]);
    }
}
