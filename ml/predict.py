import sys
import numpy as np
from sklearn.preprocessing import LabelEncoder
from xgboost import XGBRanker

# REQUEST TYPE FROM PHP
request_type = sys.argv[1]

# LABELS
labels = [
    'Clearance',
    'Emergency',
    'Medical',
    'Senior'
]

# ENCODER
encoder = LabelEncoder()

encoder.fit(labels)

# ENCODE INPUT
encoded = encoder.transform([request_type])[0]

# LOAD MODEL
model = XGBRanker()

model.load_model("ml/barangay_rank_model.json")

# PREDICT
data = np.array([[encoded]])

prediction = model.predict(data)

print(float(prediction[0]))