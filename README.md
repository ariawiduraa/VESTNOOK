<p align="center">
  <img src="https://img.shields.io/badge/VESTNOOK-v2.0-4CAF50?style=for-the-badge&logo=leaf&logoColor=white" alt="VESTNOOK v2">
  <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/Python-3.8+-3776AB?style=for-the-badge&logo=python&logoColor=white" alt="Python">
  <img src="https://img.shields.io/badge/AI_Powered-Gemini-4285F4?style=for-the-badge&logo=google&logoColor=white" alt="Gemini AI">
</p>

<h1 align="center">🌾 VESTNOOK V2 — Smart Farming Platform</h1>

<p align="center">
  Platform manajemen lahan pertanian berbasis <strong>Machine Learning & AI</strong> yang membantu petani menganalisis kondisi tanah, mendapatkan rekomendasi pupuk yang tepat, dan merencanakan jadwal pemupukan secara otomatis.
</p>

---

## 📋 Daftar Isi

- [Tentang Proyek](#-tentang-proyek)
- [Fitur Utama](#-fitur-utama)
- [Tech Stack](#-tech-stack)
- [Prasyarat Sistem](#-prasyarat-sistem)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Menjalankan Aplikasi](#-menjalankan-aplikasi)
- [Panduan Penggunaan](#-panduan-penggunaan)
- [Struktur Proyek](#-struktur-proyek)
- [Troubleshooting](#-troubleshooting)

---

## 🌿 Tentang Proyek

**VESTNOOK V2** adalah aplikasi web *Smart Farming* yang mengintegrasikan teknologi Machine Learning (K-Means Clustering & Random Forest) dengan kecerdasan buatan Google Gemini untuk memberikan:

- **Analisis tanah** berdasarkan parameter fisik dan kimia
- **Rekomendasi pupuk** yang dipersonalisasi per lahan
- **Kalender pemupukan** 8 minggu yang dihasilkan otomatis oleh AI
- **Perbandingan lahan** dengan analisis mendalam berbasis AI

---

## ✨ Fitur Utama

| Fitur | Deskripsi |
|---|---|
| 🔐 **Autentikasi** | Registrasi, login, edit profil, dan manajemen akun |
| 🗺️ **Dashboard Lahan** | Tampilan ringkasan semua lahan dengan status analisis |
| 🧪 **Analisis Parameter** | Input 13+ parameter tanah (pH, NPK, kelembapan, dll.) |
| 🤖 **ML Engine** | K-Means Clustering + Random Forest untuk zonasi & rekomendasi pupuk |
| 🌐 **Analisis AI Gemini** | Penjelasan kondisi tanah mendalam dari Google Gemini |
| 📅 **Kalender Pemupukan** | Jadwal 8 minggu berbasis AI dengan fitur checklist |
| ⚖️ **Bandingkan Lahan** | Komparasi dua lahan + analisis AI secara berdampingan |
| 🎨 **Dark/Light Mode** | Tema gelap dan terang yang dapat diubah sewaktu-waktu |
| 🌏 **Multi-bahasa** | Antarmuka tersedia dalam Bahasa Indonesia dan Inggris |

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|---|---|
| **Backend** | Laravel 13.x (PHP 8.3+) |
| **Frontend** | Blade Templates + TailwindCSS 4.x + Vite |
| **ML Engine** | Python 3 (scikit-learn, pandas, numpy, joblib) |
| **AI Integration** | Google Gemini API |
| **Database** | SQLite (default) |
| **Build Tool** | Vite 8.x |

---

## ⚙️ Prasyarat Sistem

Pastikan semua perangkat lunak berikut sudah terinstal di sistem Anda sebelum memulai:

| Perangkat Lunak | Versi Minimum | Cek Versi |
|---|---|---|
| **PHP** | 8.3+ | `php -v` |
| **Composer** | 2.x | `composer -V` |
| **Node.js** | 18+ | `node -v` |
| **NPM** | 9+ | `npm -v` |
| **Python** | 3.8 – 3.11 | `python3 --version` |
| **pip** | terbaru | `pip3 --version` |
| **Git** | terbaru | `git --version` |

> **Catatan:** SQLite biasanya sudah tersedia secara bawaan di Linux/macOS. Untuk Windows, pastikan driver SQLite PHP aktif di `php.ini`.

---

## 🚀 Instalasi

Ikuti langkah-langkah berikut secara berurutan untuk menyiapkan proyek.

### Langkah 1 — Clone Repositori

```bash
git clone <URL_REPOSITORI_ANDA> vestnook-v2
cd vestnook-v2
```

Atau, jika Anda sudah memiliki folder proyeknya, masuk ke direktori proyek:

```bash
cd "/path/ke/folder/VESTNOOK V2"
```

---

### Langkah 2 — Instal Dependensi PHP (Backend)

```bash
composer install
```

---

### Langkah 3 — Konfigurasi File `.env`

Salin file environment contoh:

```bash
cp .env.example .env
```

Buka file `.env` yang baru dibuat, kemudian sesuaikan konfigurasi berikut:

```env
APP_NAME="VESTNOOK Smart Farming"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database (SQLite — bawaan, tidak perlu diubah)
DB_CONNECTION=sqlite

# ⬇️ WAJIB DIISI — API Key Google Gemini Anda
GEMINI_API_KEY="MASUKKAN_API_KEY_GEMINI_ANDA_DI_SINI"
```

> **Cara mendapatkan Gemini API Key:**
> 1. Kunjungi [https://aistudio.google.com/app/apikey](https://aistudio.google.com/app/apikey)
> 2. Klik **"Create API Key"**
> 3. Salin key tersebut dan tempelkan ke nilai `GEMINI_API_KEY` di file `.env`

---

### Langkah 4 — Generate Application Key

```bash
php artisan key:generate
```

---

### Langkah 5 — Setup Database & Migrasi

Buat file database SQLite dan jalankan semua migrasi:

```bash
# Buat file database SQLite
touch database/database.sqlite

# Jalankan migrasi tabel
php artisan migrate
```

---

### Langkah 6 — Instal Dependensi Frontend & Build Aset

```bash
npm install
npm run build
```

---

### Langkah 7 — Instal Dependensi Python (ML Engine)

```bash
# Masuk ke folder ML Engine
cd ml_engine

# Instal library Python yang dibutuhkan
pip3 install joblib pandas numpy scikit-learn

# Kembali ke folder utama proyek
cd ..
```

Pastikan file model berikut sudah ada di dalam folder `ml_engine/`:

```
ml_engine/
├── kmeans_smart_farming.joblib        ✅ Model K-Means Clustering
├── scaler_smart_farming.joblib        ✅ Scaler untuk K-Means
├── cluster_label_map.joblib           ✅ Peta label cluster
├── preprocessor_smart_farming_v2.joblib ✅ Preprocessor untuk Random Forest
├── random_forest_model.joblib         ✅ Model Random Forest
└── label_encoder_target_v2.joblib     ✅ Label encoder target
```

> **Jika file model belum ada**, jalankan skrip pelatihan:
> ```bash
> cd ml_engine
> python3 train.py
> cd ..
> ```

---

### ✅ Ringkasan Perintah Instalasi (Semua Sekaligus)

```bash
# 1. Salin .env
cp .env.example .env

# 2. Instal semua dependensi
composer install
npm install

# 3. Generate key & setup database
php artisan key:generate
touch database/database.sqlite
php artisan migrate

# 4. Build aset frontend
npm run build

# 5. Instal dependensi Python
pip3 install joblib pandas numpy scikit-learn
```

---

## ▶️ Menjalankan Aplikasi

Setelah instalasi selesai, jalankan server menggunakan **salah satu** cara berikut:

### Cara 1 — Mode Development (Direkomendasikan)

Buka **dua terminal** secara bersamaan:

**Terminal 1 — Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 — Vite Hot Reload (live preview perubahan CSS/JS):**
```bash
npm run dev
```

---

### Cara 2 — Satu Perintah (Composer Dev)

Jalankan semua service sekaligus (server, queue, log viewer, vite):

```bash
composer run dev
```

---

Setelah server berjalan, buka browser dan akses:

```
http://127.0.0.1:8000
```

---

## 📖 Panduan Penggunaan

### 1. 🏠 Halaman Beranda (Landing Page)

Saat pertama kali membuka aplikasi, Anda akan melihat halaman beranda dengan informasi singkat tentang platform dan tombol **Login** / **Daftar**.

---

### 2. 📝 Registrasi & Login

1. Klik tombol **"Daftar"** di pojok kanan atas.
2. Isi form pendaftaran:
   - Nama Lengkap
   - Email
   - Password
   - Wilayah Tani
   - Tipe Tanaman Utama
3. Setelah berhasil mendaftar, Anda akan otomatis diarahkan ke Dashboard.
4. Untuk login berikutnya, klik **"Masuk"** dan isi email & password.

> **Edit Profil:** Klik ikon avatar di pojok kanan atas → pilih **"Edit Profil"** untuk memperbarui nama, email, foto profil, atau kata sandi.

---

### 3. 📊 Dashboard Lahan

Setelah login, Anda akan masuk ke Dashboard yang menampilkan:

- Daftar seluruh lahan yang telah Anda tambahkan
- Ringkasan hasil zonasi klaster & rekomendasi pupuk tiap lahan
- Aksi cepat: **Lihat Detail**, **Edit**, dan **Hapus** lahan

---

### 4. ➕ Menambah Lahan Baru & Analisis

1. Klik tombol **"Tambah Lahan Baru"** di Dashboard.
2. Isi form data lahan:

   | Parameter | Satuan | Contoh |
   |---|---|---|
   | Nama Lahan | — | Sawah Utama |
   | pH Tanah | — | 6.5 |
   | Kelembapan Tanah | % | 65 |
   | Karbon Organik | % | 2.3 |
   | Konduktivitas Listrik | dS/m | 0.8 |
   | Nitrogen (N) | ppm | 45 |
   | Fosfor (P) | ppm | 30 |
   | Kalium (K) | ppm | 120 |
   | Suhu Rata-rata | °C | 28 |
   | Kelembapan Udara | % | 70 |
   | Curah Hujan | mm/bulan | 180 |
   | Jenis Tanaman | — | Padi |
   | Fase Pertumbuhan | — | Vegetative |

3. Klik **"Simpan Data Lahan"**.
4. Sistem akan **otomatis menjalankan ML Engine** (K-Means + Random Forest) untuk menghasilkan:
   - Zonasi Tanah (Cluster)
   - Jenis Pupuk yang Direkomendasikan

---

### 5. 🔍 Detail Lahan & Kalender Pemupukan

Klik **"Lihat Detail"** pada salah satu lahan untuk melihat:

- **Zonasi & Rekomendasi Pupuk** — hasil prediksi ML beserta *Confidence Score*
- **Analisis AI Gemini** — penjelasan mendalam mengenai kondisi tanah Anda
- **Kalender Pemupukan 8 Minggu:**
  - Jadwal kegiatan pemupukan yang dibuat otomatis oleh AI
  - Centang aktivitas yang sudah selesai — status tersimpan otomatis
  - Kalender akan di-reset otomatis jika data lahan diedit

---

### 6. ⚖️ Bandingkan Lahan

1. Klik menu **"Compare"** di navigasi atas.
2. Pilih **dua lahan** dari dropdown yang tersedia.
3. Sistem menampilkan tabel komparasi nilai N-P-K, pH, kelembapan, dan suhu.
4. Klik **"Analisis dengan AI Gemini"** untuk mendapatkan kesimpulan perbandingan dan rekomendasi taktis dari AI.

---

### 7. 🎨 Pengaturan Tema & Bahasa

Klik ikon avatar → **"Settings"**:

- **Tema:** Beralih antara **Dark Mode** dan **Light Mode**
- **Bahasa:** Pilih **Bahasa Indonesia (ID)** atau **English (EN)**

---

## 📁 Struktur Proyek

```
VESTNOOK V2/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php       # Autentikasi (login/register)
│   │       ├── LahanController.php      # CRUD lahan & integrasi ML
│   │       ├── DashboardController.php  # Halaman dashboard
│   │       ├── ProfileController.php    # Manajemen profil pengguna
│   │       ├── AdminController.php      # Panel admin
│   │       └── AnalisisController.php   # Analisis lahan
│   └── Models/
│       ├── User.php                     # Model pengguna
│       └── Lahan.php                    # Model data lahan
├── database/
│   ├── migrations/                      # Migrasi tabel database
│   └── database.sqlite                  # File database SQLite
├── ml_engine/
│   ├── app.py                           # Script utama ML Engine
│   ├── train.py                         # Script pelatihan model
│   ├── kmeans_smart_farming.joblib      # Model K-Means
│   ├── random_forest_model.joblib       # Model Random Forest
│   └── *.joblib                         # File model lainnya
├── resources/
│   └── views/                           # Blade templates (UI)
├── routes/
│   └── web.php                          # Definisi semua route
├── .env.example                         # Template konfigurasi
├── composer.json                        # Dependensi PHP
├── package.json                         # Dependensi JavaScript
└── vite.config.js                       # Konfigurasi Vite
```

---

## 🔧 Troubleshooting

### ❌ Error: `python3: not found` saat Analisis Lahan

Pastikan Python 3 terinstal di sistem Anda:
```bash
# Cek instalasi Python
python3 --version

# Jika belum ada (Ubuntu/Debian)
sudo apt update && sudo apt install python3 python3-pip
```

---

### ❌ Error: `Class not found` atau `Autoload error`

Regenerasi autoload Composer:
```bash
composer dump-autoload
```

---

### ❌ Error: Halaman menampilkan 500 Internal Server Error

Cek log error Laravel:
```bash
tail -f storage/logs/laravel.log
```

Bersihkan cache aplikasi:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

---

### ❌ Error: Database tidak ditemukan / tabel belum ada

```bash
# Pastikan file SQLite ada
touch database/database.sqlite

# Ulangi migrasi
php artisan migrate:fresh
```

---

### ❌ Aset CSS/JS tidak muncul (halaman tanpa style)

```bash
# Rebuild aset frontend
npm run build
```

Pastikan file `public/build/manifest.json` sudah terbuat setelah perintah di atas.

---

### ❌ Kalender AI tidak muncul / error Gemini

Pastikan `GEMINI_API_KEY` sudah diisi dengan benar di file `.env`:
```bash
# Cek apakah key sudah tersimpan
grep GEMINI_API_KEY .env
```

Setelah mengubah `.env`, bersihkan cache config:
```bash
php artisan config:clear
```

---

## 📄 Lisensi

Proyek ini dikembangkan untuk keperluan akademis — **Tugas Akhir / UAS Pemrograman Web Framework, Semester 4**.

---

<p align="center">
  Dibuat dengan ❤️ menggunakan <strong>Laravel</strong> + <strong>Python ML</strong> + <strong>Google Gemini AI</strong>
</p>
