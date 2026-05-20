import pandas as pd
from sklearn.preprocessing import LabelEncoder
from xgboost import XGBRanker

# LOAD DATASET
data = pd.read_csv("dataset.csv")

# ENCODE TEXT
encoder = LabelEncoder()

data['request_type_encoded'] = encoder.fit_transform(
    data['request_type']
)

# FEATURES
X = data[['request_type_encoded']]

# TARGET
y = data['priority']

# GROUP
group = [len(data)]

# MODEL
model = XGBRanker(
    objective='rank:pairwise',
    learning_rate=0.1,
    max_depth=4,
    n_estimators=100
)

# TRAIN
model.fit(X, y, group=group)

# SAVE MODEL
model.save_model("barangay_rank_model.json")

print("Model Trained Successfully")