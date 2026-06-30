import pandas as pd
from sklearn.preprocessing import LabelEncoder, StandardScaler, OneHotEncoder
from sklearn.compose import ColumnTransformer
from sklearn.model_selection import train_test_split
from sklearn.cluster import KMeans
from sklearn.neural_network import MLPClassifier
import joblib

print("Mulai proses training model di laptop lokal...")

# 1. Load Data
df = pd.read_csv('fertilizer_recommendation.csv')
df_clean = df.drop_duplicates()

# 2. Encoding Target
le_target = LabelEncoder()
df_clean['Target_Encoded'] = le_target.fit_transform(df_clean['Recommended_Fertilizer'])

X_data = df_clean.drop(columns=['Recommended_Fertilizer', 'Target_Encoded'])
y_data = df_clean['Target_Encoded']

# 3. Preprocessing
numerical_cols = [
    'Soil_pH', 'Soil_Moisture', 'Organic_Carbon', 'Electrical_Conductivity', 
    'Nitrogen_Level', 'Phosphorus_Level', 'Potassium_Level', 'Temperature', 
    'Humidity', 'Rainfall', 'Fertilizer_Used_Last_Season', 'Yield_Last_Season'
]
categorical_cols = [
    'Soil_Type', 'Crop_Type', 'Crop_Growth_Stage', 
    'Season', 'Irrigation_Type', 'Previous_Crop', 'Region'
]

preprocessor = ColumnTransformer(
    transformers=[
        ('num', StandardScaler(), numerical_cols),
        ('cat', OneHotEncoder(handle_unknown='ignore', sparse_output=False), categorical_cols)
    ]
)
X_processed = preprocessor.fit_transform(X_data)

# 4. Unsupervised Learning (K-Means)
print("Training K-Means (Unsupervised)...")
kmeans_final = KMeans(n_clusters=2, random_state=42, n_init=10)
kmeans_final.fit(X_processed)

# 5. Split Data
X_train, X_test, y_train, y_test = train_test_split(
    X_processed, y_data, test_size=0.2, stratify=y_data, random_state=42
)

# 6. Supervised Learning (MLP)
print("Training MLP Neural Network (Supervised)...")
mlp_model = MLPClassifier(
    hidden_layer_sizes=(128, 64, 32), 
    activation='relu',
    solver='adam',
    learning_rate_init=0.001,
    max_iter=500,
    tol=0.0,              
    n_iter_no_change=500, 
    random_state=42
)
mlp_model.fit(X_train, y_train)

# 7. Simpan Model (.joblib)
print("Menyimpan model ke file .joblib...")
joblib.dump(preprocessor, 'preprocessor_smart_farming_v2.joblib')
joblib.dump(kmeans_final, 'model_unsupervised_kmeans_v2.joblib')
joblib.dump(mlp_model, 'model_supervised_fertilizer_v2.joblib')
joblib.dump(le_target, 'label_encoder_target_v2.joblib')

print("SUKSES! File .joblib baru telah dibuat dan siap digunakan oleh app.py.")
