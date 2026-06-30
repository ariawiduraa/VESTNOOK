import joblib
import pandas as pd
import numpy as np
import warnings
import sys
import json

# Mengabaikan warning sklearn
warnings.filterwarnings('ignore')

# 1. Memuat Model
try:
    scaler_unsupervised     = joblib.load('scaler_smart_farming.joblib')
    model_unsupervised      = joblib.load('kmeans_smart_farming.joblib')
    cluster_labels_raw      = joblib.load('cluster_label_map.joblib')
    preprocessor_supervised = joblib.load('preprocessor_smart_farming_v2.joblib')
    model_supervised        = joblib.load('random_forest_model.joblib')
    le_target               = joblib.load('label_encoder_target_v2.joblib')
except Exception as e:
    print(json.dumps({"status": "error", "message": f"Gagal memuat file model. Detail: {e}"}))
    sys.exit(1)

# Terjemahan bahasa Indonesia sesuai dengan style label sekarang
translation_map = {
    'Cool Dry Zone': 'Lahan Dingin & Kering',
    'Humid Tropical Zone': 'Lahan Tropis Basah',
    'Cool Humid Zone': 'Lahan Pegunungan (Lembap)',
    'Hot Dry Zone': 'Lahan Terik (Kritis)'
}

# Terjemahkan label dari cluster_label_map.joblib secara dinamis
label_map = {}
for cid, label_eng in cluster_labels_raw.items():
    label_map[cid] = translation_map.get(label_eng, label_eng)

# Rentang referensi ideal per parameter (universal/agronomis umum)
PARAM_RANGES = {
    'Soil_pH':                    {'min': 3.0,  'max': 10.0, 'ideal_min': 5.5,  'ideal_max': 7.5,  'label': 'pH Tanah',              'unit': ''},
    'Soil_Moisture':              {'min': 0.0,  'max': 100.0,'ideal_min': 30.0, 'ideal_max': 70.0, 'label': 'Kelembapan Tanah',      'unit': '%'},
    'Organic_Carbon':             {'min': 0.0,  'max': 6.0,  'ideal_min': 0.5,  'ideal_max': 3.0,  'label': 'Karbon Organik',        'unit': '%'},
    'Electrical_Conductivity':    {'min': 0.0,  'max': 6.0,  'ideal_min': 0.1,  'ideal_max': 2.0,  'label': 'Konduktivitas Listrik', 'unit': 'dS/m'},
    'Nitrogen_Level':             {'min': 0.0,  'max': 250.0,'ideal_min': 20.0, 'ideal_max': 140.0,'label': 'Kadar Nitrogen',        'unit': 'ppm'},
    'Phosphorus_Level':           {'min': 0.0,  'max': 250.0,'ideal_min': 15.0, 'ideal_max': 120.0,'label': 'Kadar Fosfor',          'unit': 'ppm'},
    'Potassium_Level':            {'min': 0.0,  'max': 250.0,'ideal_min': 80.0, 'ideal_max': 180.0,'label': 'Kadar Kalium',          'unit': 'ppm'},
    'Temperature':                {'min': 0.0,  'max': 50.0, 'ideal_min': 15.0, 'ideal_max': 38.0, 'label': 'Suhu',                  'unit': 'C'},
    'Humidity':                   {'min': 0.0,  'max': 100.0,'ideal_min': 40.0, 'ideal_max': 90.0, 'label': 'Kelembapan Udara',      'unit': '%'},
    'Rainfall':                   {'min': 0.0,  'max': 600.0,'ideal_min': 50.0, 'ideal_max': 350.0,'label': 'Curah Hujan',           'unit': 'mm'},
    'Fertilizer_Used_Last_Season':{'min': 0.0,  'max': 250.0,'ideal_min': 0.0,  'ideal_max': 150.0,'label': 'Pupuk Musim Lalu',     'unit': 'kg/ha'},
    'Yield_Last_Season':          {'min': 0.0,  'max': 12.0, 'ideal_min': 0.5,  'ideal_max': 8.0,  'label': 'Hasil Panen Lalu',      'unit': 'ton/ha'},
}

NUMERIC_PARAMS = [
    'Soil_pH','Soil_Moisture','Organic_Carbon','Electrical_Conductivity',
    'Nitrogen_Level','Phosphorus_Level','Potassium_Level',
    'Temperature','Humidity','Rainfall',
    'Fertilizer_Used_Last_Season','Yield_Last_Season'
]


def compute_param_fit(value, param_key):
    r = PARAM_RANGES.get(param_key)
    if r is None:
        return 100.0
    ideal_min, ideal_max = r['ideal_min'], r['ideal_max']
    abs_min,   abs_max   = r['min'],       r['max']

    if ideal_min <= value <= ideal_max:
        return 100.0
    if value < ideal_min:
        span = max(ideal_min - abs_min, 1e-9)
        return round(max(0.0, 1.0 - (ideal_min - value) / span) * 100, 1)
    span = max(abs_max - ideal_max, 1e-9)
    return round(max(0.0, 1.0 - (value - ideal_max) / span) * 100, 1)


def status_from_pct(pct):
    if pct >= 75:  return 'baik'
    if pct >= 40:  return 'sedang'
    return 'rendah'


# 2. Terima data dari Laravel
try:
    data = json.loads(sys.argv[1])
except IndexError:
    print(json.dumps({"status": "error", "message": "Tidak ada data JSON yang dikirim ke Python."}))
    sys.exit(1)
except json.JSONDecodeError:
    print(json.dumps({"status": "error", "message": "Format data yang dikirim bukan JSON yang valid."}))
    sys.exit(1)

# 3. Prediksi
try:
    temp = float(data.get('Temperature', 0))
    hum  = float(data.get('Humidity', 0))

    # A. Unsupervised – Zonasi
    df_u = pd.DataFrame([[temp, hum]], columns=['Temperature','Humidity'])
    cluster_id   = int(model_unsupervised.predict(scaler_unsupervised.transform(df_u))[0])
    nama_cluster = label_map.get(cluster_id, "Klaster Tidak Diketahui")

    # B. Supervised – Rekomendasi Pupuk
    df_s = pd.DataFrame([{
        'Soil_Type':                  str(data.get('Soil_Type', '')),
        'Soil_pH':                    float(data.get('Soil_pH', 0)),
        'Soil_Moisture':              float(data.get('Soil_Moisture', 0)),
        'Organic_Carbon':             float(data.get('Organic_Carbon', 0)),
        'Electrical_Conductivity':    float(data.get('Electrical_Conductivity', 0)),
        'Nitrogen_Level':             float(data.get('Nitrogen_Level', 0)),
        'Phosphorus_Level':           float(data.get('Phosphorus_Level', 0)),
        'Potassium_Level':            float(data.get('Potassium_Level', 0)),
        'Temperature':                temp,
        'Humidity':                   hum,
        'Rainfall':                   float(data.get('Rainfall', 0)),
        'Crop_Type':                  str(data.get('Crop_Type', '')),
        'Crop_Growth_Stage':          str(data.get('Crop_Growth_Stage', '')),
        'Season':                     str(data.get('Season', '')),
        'Irrigation_Type':            str(data.get('Irrigation_Type', '')),
        'Previous_Crop':              str(data.get('Previous_Crop', '')),
        'Region':                     str(data.get('Region', '')),
        'Fertilizer_Used_Last_Season':float(data.get('Fertilizer_Used_Last_Season', 0)),
        'Yield_Last_Season':          float(data.get('Yield_Last_Season', 0))
    }])

    processed      = preprocessor_supervised.transform(df_s)
    pred_encoded   = model_supervised.predict(processed)[0]
    rekomendasi    = le_target.inverse_transform([pred_encoded])[0]

    # C. Confidence score via predict_proba
    confidence_score = 0.0
    top_alternatives = []
    try:
        proba   = model_supervised.predict_proba(processed)[0]
        classes = le_target.inverse_transform(range(len(proba)))
        pred_idx = list(classes).index(rekomendasi)
        confidence_score = round(float(proba[pred_idx]) * 100, 1)
        for idx in np.argsort(proba)[::-1]:
            if classes[idx] != rekomendasi and len(top_alternatives) < 3:
                top_alternatives.append({
                    "pupuk":      str(classes[idx]),
                    "confidence": round(float(proba[idx]) * 100, 1)
                })
    except Exception:
        confidence_score = 0.0

    # D. Kesesuaian per parameter
    param_stats = []
    for key in NUMERIC_PARAMS:
        raw = data.get(key)
        if raw is None:
            continue
        try:
            val = float(raw)
        except (ValueError, TypeError):
            continue
        r       = PARAM_RANGES.get(key, {})
        fit_pct = compute_param_fit(val, key)
        param_stats.append({
            "key":       key,
            "label":     r.get('label', key),
            "unit":      r.get('unit', ''),
            "value":     val,
            "ideal_min": r.get('ideal_min', 0),
            "ideal_max": r.get('ideal_max', 100),
            "abs_min":   r.get('min', 0),
            "abs_max":   r.get('max', 100),
            "fit_pct":   fit_pct,
            "status":    status_from_pct(fit_pct)
        })

    avg_fit = round(sum(p['fit_pct'] for p in param_stats) / len(param_stats), 1) if param_stats else 0.0

    print(json.dumps({
        "status":            "success",
        "cluster_id":        cluster_id,
        "nama_cluster":      nama_cluster,
        "rekomendasi_pupuk": rekomendasi,
        "confidence_score":  confidence_score,
        "avg_fit":           avg_fit,
        "top_alternatives":  top_alternatives,
        "param_stats":       param_stats
    }))

except Exception as e:
    print(json.dumps({"status": "error", "message": f"Gagal memproses data atau memprediksi. Detail: {e}"}))
    sys.exit(1)
