# Panduan Penggunaan Sistem Smart Farming

Dokumen ini menjelaskan alur kerja dan cara menggunakan berbagai fitur utama di dalam platform **Smart Farming** untuk mengelola lahan pertanian Anda.

---

## 1. Halaman Beranda (Landing Page)
Saat pertama kali membuka aplikasi, Anda akan disambut oleh halaman beranda. 
*   **Navigasi Utama:** Terdapat opsi untuk masuk (*Login*) atau mendaftar (*Register*).
*   **Informasi Singkat:** Penjelasan mengenai fitur platform (Analisis Tanah, Rekomendasi Pupuk berbasis AI, dan Kalender Pemupukan).

---

## 2. Pendaftaran & Autentikasi Akun
Sebelum mengelola lahan, Anda wajib memiliki akun.
*   **Registrasi:** Klik tombol "Daftar", isi nama lengkap, email, password, wilayah tani, dan tipe tanaman utama Anda.
*   **Login:** Masuk menggunakan email dan password yang telah didaftarkan.
*   **Edit Profil:** Anda dapat memperbarui informasi nama, email, foto profil, dan kata sandi dengan mengklik ikon avatar profil Anda lalu memilih opsi **Edit Profil**.

---

## 3. Dashboard Lahan
Setelah berhasil masuk, Anda akan diarahkan ke Dashboard. Di sini Anda bisa memantau seluruh lahan Anda:
*   **Tambah Lahan Baru:** Klik tombol **"Tambah Lahan Baru"** di sudut kanan atas.
*   **Daftar Lahan:** Menampilkan ringkasan informasi nama lahan, hasil zonasi klaster, dan rekomendasi pupuk utama.
*   **Aksi Cepat:** Terdapat opsi cepat untuk melihat detail, mengedit parameter tanah, atau menghapus data lahan.

---

## 4. Input & Analisis Parameter Lahan
Saat menambahkan atau mengedit lahan, Anda akan diminta mengisi data agronomis tanah secara spesifik:
*   **Parameter Fisik & Kimia:** pH Tanah, Kelembapan Tanah (%), Karbon Organik (%), Konduktivitas Listrik (dS/m), serta Kadar Nitrogen, Fosfor, dan Kalium (ppm).
*   **Kondisi Lingkungan:** Suhu rata-rata, Kelembapan udara, Curah Hujan bulanan, dan Wilayah Lahan.
*   **Informasi Tanaman:** Jenis tanaman saat ini (misal: Padi, Jagung, Cabai, Kentang) dan Fase pertumbuhan tanaman (Germination/Vegetative/Flowering/Harvest).

Setelah menekan tombol **"Simpan Data Lahan"**, sistem akan memproses data tersebut menggunakan *Machine Learning Engine* (Python K-Means & Random Forest) untuk menentukan zonasi tanah dan menghasilkan jenis pupuk yang paling cocok.

---

## 5. Fitur Detail Lahan & Kalender AI

Setelah analisis selesai, masuk ke menu **"Lihat Detail"** lahan Anda untuk melihat:
*   **Zonasi Tanah & Rekomendasi Pupuk:** Hasil rekomendasi utama (Urea, NPK, DAP, dll) lengkap dengan tingkat akurasi prediksi (*Confidence Score*).
*   **Analisis AI Gemini:** Penjelasan teks komprehensif mengenai kondisi tanah Anda dan saran pengolahannya yang ditulis langsung oleh AI.
*   **Kalender Pemupukan 8 Minggu (Berbasis AI):**
    *   Sistem akan secara otomatis membuat jadwal pemupukan dinamis 8 minggu menggunakan API Gemini, disesuaikan secara khusus dengan jenis tanah, pH, dan jenis pupuk rekomendasi Anda.
    *   **Penyimpanan Checklist:** Anda dapat mencentang aktivitas pemupukan mingguan yang telah diselesaikan. Status centang ini disimpan secara otomatis ke database (tidak hilang saat halaman dimuat ulang).
    *   **Pembaruan Otomatis:** Jika Anda melakukan "Edit Lahan" dan mengubah datanya, kalender pemupukan dan status checklist akan otomatis di-reset dan di-generate ulang menyesuaikan kondisi tanah terbaru Anda.

---

## 6. Fitur Bandingkan Lahan (Compare Lands)
Fitur ini digunakan untuk membandingkan dua lahan pertanian Anda secara berdampingan:
*   Akses menu **"Compare"** dari bilah navigasi atas.
*   Pilih dua lahan berbeda dari menu pilihan dropdown yang disediakan.
*   Sistem akan menampilkan tabel komparasi nilai parameter N-P-K, pH, kelembapan, suhu, dan memvisualisasikan selisih angkanya (+ atau -).
*   Klik tombol **"Analisis dengan AI Gemini"** untuk mendapatkan kesimpulan perbandingan mendalam beserta langkah taktis pengelolaan yang direkomendasikan AI untuk kedua lahan tersebut secara terintegrasi.

---

## 7. Pengaturan Tema & Bahasa (Settings)
Anda dapat menyesuaikan kenyamanan tampilan aplikasi melalui menu Settings di dalam dropdown Profil:
*   **Dark Mode & Light Mode:** Beralih antara tema gelap dan tema terang. Tema ini akan langsung diterapkan secara konsisten di seluruh halaman aplikasi, termasuk halaman grafik statistik dan halaman detail analisis.
*   **Bahasa (Language):** Beralih bahasa antara Bahasa Indonesia (ID) dan Bahasa Inggris (EN) secara dinamis.
