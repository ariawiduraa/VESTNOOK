import joblib
import pandas as pd
import numpy as np
import warnings
import sys

# Suppress unpickling and feature name warnings for clean CLI output
warnings.filterwarnings('ignore', category=UserWarning)

# Color definitions for terminal output
BLUE = "\033[94m"
GREEN = "\033[92m"
YELLOW = "\033[93m"
RED = "\033[91m"
BOLD = "\033[1m"
RESET = "\033[0m"

# 1. Load Model & Scaler
try:
    kmeans = joblib.load('kmeans_smart_farming.joblib')
    scaler = joblib.load('scaler_smart_farming.joblib')
except FileNotFoundError as e:
    print(f"{RED}[ERROR] File model atau scaler tidak ditemukan!{RESET}")
    print("Pastikan file 'kmeans_smart_farming.joblib' dan 'scaler_smart_farming.joblib' berada di folder yang sama.")
    sys.exit(1)

# 2. Label Map from the last cell of Unsupervised.ipynb
label_map = {
    0: {
        "label": "Lahan Dingin & Kering",
        "range_suhu": (10.0, 23.0),
        "range_kelembapan": (30.0, 55.0)
    },
    1: {
        "label": "Lahan Tropis Basah",
        "range_suhu": (23.1, 40.0),
        "range_kelembapan": (60.1, 90.0)
    },
    2: {
        "label": "Lahan Pegunungan (Lembap)",
        "range_suhu": (10.0, 23.0),
        "range_kelembapan": (60.1, 90.0)
    },
    3: {
        "label": "Lahan Terik (Kritis)",
        "range_suhu": (23.1, 40.0),
        "range_kelembapan": (30.0, 60.0)
    }
}

def dapatkan_label_lahan(suhu, kelembapan):
    # Predict using scaler & K-Means (with feature names to avoid sklearn warnings)
    df_input = pd.DataFrame([[suhu, kelembapan]], columns=['Temperature', 'Humidity'])
    data_scaled = scaler.transform(df_input)
    klaster_id = int(kmeans.predict(data_scaled)[0])
    
    info = label_map.get(klaster_id)
    return {
        "klaster_id": klaster_id,
        "label_lahan": info["label"],
        "rentang_suhu": info["range_suhu"],
        "rentang_kelembapan": info["range_kelembapan"]
    }

def run_automated_test():
    print(f"\n{BOLD}{BLUE}====================================================================={RESET}")
    print(f"{BOLD}{BLUE}          MEMULAI UJI KONSISTENSI BATAS KLASTER (BATCH TEST)         {RESET}")
    print(f"{BOLD}{BLUE}====================================================================={RESET}")
    print("Menguji apakah data acak dalam rentang 'label_map' secara konsisten")
    print("diprediksi masuk ke klaster yang sesuai oleh model K-Means.")
    print("---------------------------------------------------------------------")

    np.random.seed(42)
    n_samples = 1000
    all_consistent = True

    for cid, info in label_map.items():
        suhu_min, suhu_max = info['range_suhu']
        hum_min, hum_max = info['range_kelembapan']
        
        # Generate random values strictly within the defined rectangle boundaries
        suhus = np.random.uniform(suhu_min, suhu_max, n_samples)
        hums = np.random.uniform(hum_min, hum_max, n_samples)
        
        df_samples = pd.DataFrame({
            'Temperature': suhus,
            'Humidity': hums
        })
        
        scaled = scaler.transform(df_samples)
        preds = kmeans.predict(scaled)
        
        matches = (preds == cid).sum()
        consistency = (matches / n_samples) * 100
        
        status_color = GREEN if consistency == 100 else YELLOW
        print(f"[*] Cluster {cid} - {BOLD}{info['label']}{RESET}:")
        print(f"    - Rentang Suhu       : {suhu_min} s/d {suhu_max} °C")
        print(f"    - Rentang Kelembapan : {hum_min} s/d {hum_max} %")
        print(f"    - Konsistensi Prediksi: {status_color}{consistency:.2f}% ({matches}/{n_samples} data cocok){RESET}")
        
        if consistency < 100:
            all_consistent = False
            # Show a few sample mismatches to explain why
            mismatch_idx = np.where(preds != cid)[0]
            print(f"    - {YELLOW}Catatan Mismatch (Contoh batas yang terlewat):{RESET}")
            for idx in mismatch_idx[:3]:
                mismatched_cluster = preds[idx]
                print(f"      > Input: Suhu {suhus[idx]:.2f}°C, Kelembapan {hums[idx]:.2f}% -> Terdeteksi: Cluster {mismatched_cluster} ({label_map[mismatched_cluster]['label']})")
        print("-" * 69)

    print(f"\n{BOLD}ANALISIS HASIL & PENJELASAN KONSISTENSI:{RESET}")
    if all_consistent:
        print(f"{GREEN}✓ Semua klaster 100% konsisten antara model K-Means dan aturan manual.{RESET}")
    else:
        print(f"{YELLOW}! Ditemukan beberapa ketidakkonsistenan batas (terutama pada Cluster 1 dan 3).{RESET}")
        print("  Hal ini wajar karena:")
        print("  1. K-Means mengelompokkan data berdasarkan Jarak Euclidean di ruang ter-skala (StandardScaler),")
        print("     sehingga batas antar klaster berbentuk diagonal/melengkung (Voronoi cell), bukan kotak sempurna.")
        print("  2. Range 'label_map' di notebook hanyalah rentang kotak (rectangular thresholds) penyederhanaan.")
        print("  3. Nilai suhu/kelembapan yang sangat dekat dengan batas (misal: suhu 23.5°C di dekat batas 23.0°C)")
        print("     mungkin lebih dekat secara jarak Euclidean ke centroid Cluster lain, sehingga berganti klaster.")

def run_interactive_mode():
    print(f"\n{BOLD}{GREEN}====================================================================={RESET}")
    print(f"{BOLD}{GREEN}             MODE INTERAKTIF: DETEKSI KLASTER LAHAN                  {RESET}")
    print(f"{BOLD}{GREEN}====================================================================={RESET}")
    print("Masukkan nilai suhu dan kelembapan untuk mengecek prediksi klaster.")
    print("Ketik 'keluar' atau 'exit' untuk kembali ke menu utama.\n")
    
    while True:
        try:
            suhu_str = input(f"{BOLD}Masukkan Suhu (°C)       : {RESET}").strip()
            if suhu_str.lower() in ['keluar', 'exit', 'q']:
                break
            suhu = float(suhu_str)
            
            hum_str = input(f"{BOLD}Masukkan Kelembapan (%)  : {RESET}").strip()
            if hum_str.lower() in ['keluar', 'exit', 'q']:
                break
            kelembapan = float(hum_str)
            
            hasil = dapatkan_label_lahan(suhu, kelembapan)
            
            print(f"\n{BOLD}---> HASIL DETEKSI KLASTER LAHAN <---{RESET}")
            print(f"Cluster ID         : {BOLD}{hasil['klaster_id']}{RESET}")
            print(f"Kategori Lahan     : {BOLD}{BLUE}{hasil['label_lahan']}{RESET}")
            print(f"Aturan Range Suhu  : {hasil['rentang_suhu']} °C")
            print(f"Aturan Range Hum   : {hasil['rentang_kelembapan']} %")
            
            # Check if input actually matches the range rules in label_map
            suhu_min, suhu_max = hasil['rentang_suhu']
            hum_min, hum_max = hasil['rentang_kelembapan']
            in_suhu = suhu_min <= suhu <= suhu_max
            in_hum = hum_min <= kelembapan <= hum_max
            
            if in_suhu and in_hum:
                print(f"Status Konsistensi : {GREEN}✓ KONSISTEN{RESET} (Input sesuai dengan karakteristik klaster)")
            else:
                print(f"Status Konsistensi : {YELLOW}! METRIC OVERLAP{RESET} (Diprediksi Cluster {hasil['klaster_id']} secara jarak, tetapi secara manual diluar range)")
                if not in_suhu:
                    print(f"  * Alasan: Suhu {suhu}°C di luar rentang aturan {suhu_min} - {suhu_max}°C")
                if not in_hum:
                    print(f"  * Alasan: Kelembapan {kelembapan}% di luar rentang aturan {hum_min} - {hum_max}%")
            print("-" * 45 + "\n")
            
        except ValueError:
            print(f"{RED}[ERROR] Masukan tidak valid! Silakan masukkan angka.{RESET}\n")

if __name__ == "__main__":
    while True:
        print(f"\n{BOLD}SISTEM PENGUJI KONSISTENSI K-MEANS SMART FARMING{RESET}")
        print("1. Jalankan Uji Konsistensi Batas Klaster (Batch Test 1000 data/klaster)")
        print("2. Mode Deteksi Interaktif (Manual Input)")
        print("3. Keluar")
        pilihan = input("Pilihan Anda (1/2/3): ").strip()
        
        if pilihan == '1':
            run_automated_test()
        elif pilihan == '2':
            run_interactive_mode()
        elif pilihan == '3':
            print("Keluar dari program. Terima kasih!")
            break
        else:
            print(f"{RED}Pilihan tidak valid! Silakan pilih 1, 2, atau 3.{RESET}")
