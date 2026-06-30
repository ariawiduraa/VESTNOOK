# Panduan Instalasi Sistem Smart Farming

Dokumen ini menjelaskan langkah-langkah untuk menyiapkan, menginstal, dan menjalankan aplikasi **Smart Farming** di lingkungan lokal Anda.

---

## Prasyarat Sistem

Pastikan perangkat Anda sudah terinstal perangkat lunak berikut:
1. **PHP** (Minimal versi 8.2)
2. **Composer** (Manajer dependensi PHP)
3. **Node.js & NPM** (Untuk kompilasi aset frontend menggunakan Vite)
4. **Python** (Versi 3.8 - 3.11, diperlukan untuk menjalankan *Machine Learning Engine*)
5. **SQLite** / Driver database SQL lainnya

---

## Langkah-Langkah Instalasi

### 1. Ekstrak atau Clone Repositori
Pastikan Anda berada di direktori utama proyek:
```bash
cd "/Users/KRISNA/Desktop/SMSTER 4/Framework-Surya/UAS"
```

### 2. Instal Dependensi Backend (PHP & Laravel)
Jalankan Composer untuk menginstal semua pustaka PHP yang dibutuhkan proyek:
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file konfigurasi contoh `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` yang baru dibuat dan sesuaikan konfigurasi berikut:
*   **Database:** Proyek ini dikonfigurasi menggunakan SQLite secara bawaan:
    ```env
    DB_CONNECTION=sqlite
    # Hapus atau beri komentar pada DB_HOST, DB_PORT, dll jika memakai SQLite
    ```
*   **Kunci API Gemini:** Tambahkan API Key Gemini Anda di bagian bawah file `.env` agar fitur analisis AI & kalender dapat bekerja:
    ```env
    GEMINI_API_KEY="ISI_DENGAN_API_KEY_GEMINI_ANDA"
    ```

### 4. Generate Application Key
Jalankan perintah berikut untuk mengamankan enkripsi sesi Laravel:
```bash
php artisan key:generate
```

### 5. Setup Database & Migrasi
Buat file database SQLite kosong jika belum ada (biasanya di `database/database.sqlite`), lalu jalankan migrasi untuk membuat tabel:
```bash
# Untuk SQLite, pastikan file database sudah terbuat secara otomatis oleh Laravel
php artisan migrate
```

### 6. Instal Dependensi Frontend (JavaScript & CSS)
Instal dependensi NPM dan build aset frontend menggunakan Vite:
```bash
npm install
npm run build
```

### 7. Konfigurasi Python & Machine Learning Engine
Aplikasi ini menggunakan modul Python untuk memprediksi rekomendasi pupuk (*K-Means Clustering* & *Random Forest*).
*   Masuk ke folder `ml_engine`:
    ```bash
    cd ml_engine
    ```
*   Instal pustaka Python yang dibutuhkan:
    ```bash
    pip install joblib pandas numpy scikit-learn
    ```
*   Pastikan file model berikut sudah diletakkan di dalam folder `ml_engine`:
    *   `kmeans_smart_farming.joblib`
    *   `scaler_smart_farming.joblib`
    *   `cluster_label_map.joblib`
    *   `preprocessor_smart_farming_v2.joblib`
    *   `random_forest_model.joblib`
    *   `label_encoder_target_v2.joblib`

Kembali ke folder utama proyek setelah selesai:
```bash
cd ..
```

---

## Menjalankan Aplikasi

Setelah semua langkah instalasi selesai, Anda dapat menjalankan server lokal dengan dua perintah berikut secara bersamaan (di dua terminal berbeda):

1.  **Terminal 1 (Laravel Server):**
    ```bash
    php artisan serve
    ```
    Aplikasi akan berjalan di `http://127.0.0.1:8000`.

2.  **Terminal 2 (Vite Hot Reload - Opsional untuk development):**
    ```bash
    npm run dev
    ```

Sistem kini siap digunakan!
