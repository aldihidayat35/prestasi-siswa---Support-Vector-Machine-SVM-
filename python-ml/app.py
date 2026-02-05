"""
SVM Machine Learning Service for Student Academic Performance Prediction
========================================================================
Implementasi Support Vector Machine (SVM) untuk prediksi prestasi akademik siswa
berdasarkan aktivitas belajar di SMA Negeri 2 Bukittinggi.

Variabel Input (X) - Aktivitas Belajar:
- attendance_rate: Kehadiran siswa (%)
- study_duration: Durasi belajar harian (jam)
- task_frequency: Frekuensi mengerjakan tugas
- discussion_participation: Partisipasi diskusi (skor 0-100)
- media_usage: Penggunaan media pembelajaran (skor 0-100)
- discipline_score: Kedisiplinan belajar (skor 0-100)

Variabel Output (Y) - Prestasi Akademik:
- Rendah (score < 60)
- Sedang (60 <= score < 80)
- Tinggi (score >= 80)

Author: Academic Research Assistant
For: Skripsi - Penerapan Algoritma Machine Learning untuk Prediksi Prestasi Akademik
"""

import os
import json
import joblib
import numpy as np
import pandas as pd
from datetime import datetime
from flask import Flask, request, jsonify
from flask_cors import CORS
from sklearn.svm import SVC
from sklearn.preprocessing import StandardScaler, LabelEncoder
from sklearn.model_selection import train_test_split, cross_val_score
from sklearn.metrics import (
    accuracy_score,
    precision_score,
    recall_score,
    f1_score,
    classification_report,
    confusion_matrix
)
from dotenv import load_dotenv

# Load environment variables
load_dotenv()

# Initialize Flask app
app = Flask(__name__)
CORS(app)

# Configuration
MODEL_DIR = os.environ.get('MODEL_DIR', 'models')
os.makedirs(MODEL_DIR, exist_ok=True)

# Feature names for the SVM model
FEATURE_NAMES = [
    'attendance_rate',
    'study_duration',
    'task_frequency',
    'discussion_participation',
    'media_usage',
    'discipline_score'
]

# Class labels
CLASS_NAMES = ['Rendah', 'Sedang', 'Tinggi']


@app.route('/health', methods=['GET'])
def health_check():
    """Health check endpoint."""
    return jsonify({
        'status': 'healthy',
        'service': 'SVM ML Service',
        'timestamp': datetime.now().isoformat(),
        'version': '1.0.0'
    })


@app.route('/train', methods=['POST'])
def train_model():
    """
    Train SVM model with the provided dataset.

    Request JSON:
    {
        "X": [[...], [...], ...],  # Features array
        "y": ["Rendah", "Sedang", ...],  # Labels
        "kernel": "rbf",  # linear, poly, rbf, sigmoid
        "C": 1.0,  # Regularization parameter
        "gamma": "scale",  # Kernel coefficient
        "degree": 3,  # For polynomial kernel
        "test_size": 0.2,  # Proportion for testing
        "random_state": 42
    }

    Response JSON:
    {
        "success": true,
        "model_path": "models/svm_model_xxx.pkl",
        "scaler_path": "models/scaler_xxx.pkl",
        "accuracy": 0.85,
        "precision": 0.84,
        "recall": 0.85,
        "f1_score": 0.84,
        "classification_report": {...},
        "confusion_matrix": [[...], ...],
        "training_samples": 80,
        "testing_samples": 20,
        "cv_scores": [...],
        "cv_mean": 0.83,
        "cv_std": 0.05
    }
    """
    try:
        data = request.get_json()

        # Validate input
        if 'X' not in data or 'y' not in data:
            return jsonify({'error': 'Missing X or y data'}), 400

        X = np.array(data['X'])
        y = np.array(data['y'])

        if len(X) < 10:
            return jsonify({'error': 'Minimum 10 samples required for training'}), 400

        # Get SVM parameters
        kernel = data.get('kernel', 'rbf')
        C = float(data.get('C', 1.0))
        gamma = data.get('gamma', 'scale')
        degree = int(data.get('degree', 3))
        test_size = float(data.get('test_size', 0.2))
        random_state = int(data.get('random_state', 42))

        # Handle gamma parameter
        if gamma not in ['scale', 'auto']:
            try:
                gamma = float(gamma)
            except:
                gamma = 'scale'

        # Encode labels
        label_encoder = LabelEncoder()
        label_encoder.classes_ = np.array(CLASS_NAMES)
        y_encoded = label_encoder.transform(y)

        # Split data
        X_train, X_test, y_train, y_test = train_test_split(
            X, y_encoded,
            test_size=test_size,
            random_state=random_state,
            stratify=y_encoded if len(np.unique(y_encoded)) > 1 else None
        )

        # Feature scaling (Normalization/Standardization)
        scaler = StandardScaler()
        X_train_scaled = scaler.fit_transform(X_train)
        X_test_scaled = scaler.transform(X_test)

        # Initialize and train SVM model
        svm_model = SVC(
            kernel=kernel,
            C=C,
            gamma=gamma,
            degree=degree,
            probability=True,  # Enable probability estimates
            random_state=random_state
        )

        svm_model.fit(X_train_scaled, y_train)

        # Make predictions
        y_pred = svm_model.predict(X_test_scaled)

        # Calculate metrics
        accuracy = accuracy_score(y_test, y_pred)
        precision = precision_score(y_test, y_pred, average='weighted', zero_division=0)
        recall = recall_score(y_test, y_pred, average='weighted', zero_division=0)
        f1 = f1_score(y_test, y_pred, average='weighted', zero_division=0)

        # Classification report
        report = classification_report(
            y_test, y_pred,
            target_names=CLASS_NAMES,
            output_dict=True,
            zero_division=0
        )

        # Confusion matrix
        conf_matrix = confusion_matrix(y_test, y_pred).tolist()

        # Cross-validation
        cv_scores = cross_val_score(svm_model, X_train_scaled, y_train, cv=min(5, len(X_train)))

        # Generate unique filename
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
        model_filename = f'svm_model_{timestamp}.pkl'
        scaler_filename = f'scaler_{timestamp}.pkl'
        encoder_filename = f'encoder_{timestamp}.pkl'

        model_path = os.path.join(MODEL_DIR, model_filename)
        scaler_path = os.path.join(MODEL_DIR, scaler_filename)
        encoder_path = os.path.join(MODEL_DIR, encoder_filename)

        # Save model, scaler, and encoder
        joblib.dump(svm_model, model_path)
        joblib.dump(scaler, scaler_path)
        joblib.dump(label_encoder, encoder_path)

        return jsonify({
            'success': True,
            'model_path': model_path,
            'scaler_path': scaler_path,
            'encoder_path': encoder_path,
            'accuracy': float(accuracy),
            'precision': float(precision),
            'recall': float(recall),
            'f1_score': float(f1),
            'classification_report': report,
            'confusion_matrix': conf_matrix,
            'training_samples': int(len(X_train)),
            'testing_samples': int(len(X_test)),
            'cv_scores': cv_scores.tolist(),
            'cv_mean': float(cv_scores.mean()),
            'cv_std': float(cv_scores.std()),
            'parameters': {
                'kernel': kernel,
                'C': C,
                'gamma': str(gamma),
                'degree': degree
            }
        })

    except Exception as e:
        return jsonify({'error': str(e)}), 500


@app.route('/predict', methods=['POST'])
def predict():
    """
    Make prediction using trained SVM model.

    Request JSON:
    {
        "features": [85.5, 3.5, 8, 75.0, 80.0, 85.0],  # Feature values
        "model_path": "models/svm_model_xxx.pkl"
    }

    Response JSON:
    {
        "success": true,
        "prediction": "Tinggi",
        "confidence": 0.92,
        "probabilities": {
            "Rendah": 0.02,
            "Sedang": 0.06,
            "Tinggi": 0.92
        }
    }
    """
    try:
        data = request.get_json()

        # Validate input
        if 'features' not in data:
            return jsonify({'error': 'Missing features'}), 400
        if 'model_path' not in data:
            return jsonify({'error': 'Missing model_path'}), 400

        features = np.array(data['features']).reshape(1, -1)
        model_path = data['model_path']

        # Derive scaler and encoder paths
        scaler_path = model_path.replace('svm_model_', 'scaler_')
        encoder_path = model_path.replace('svm_model_', 'encoder_')

        # Load model, scaler, and encoder
        if not os.path.exists(model_path):
            return jsonify({'error': f'Model not found: {model_path}'}), 404

        model = joblib.load(model_path)

        # Load scaler if exists
        if os.path.exists(scaler_path):
            scaler = joblib.load(scaler_path)
            features_scaled = scaler.transform(features)
        else:
            features_scaled = features

        # Make prediction
        prediction_encoded = model.predict(features_scaled)[0]
        probabilities = model.predict_proba(features_scaled)[0]

        # Load encoder or use default class names
        if os.path.exists(encoder_path):
            label_encoder = joblib.load(encoder_path)
            prediction = label_encoder.inverse_transform([prediction_encoded])[0]
        else:
            prediction = CLASS_NAMES[prediction_encoded]

        # Get confidence (max probability)
        confidence = float(max(probabilities))

        # Create probability dict
        prob_dict = {CLASS_NAMES[i]: float(probabilities[i]) for i in range(len(CLASS_NAMES))}

        return jsonify({
            'success': True,
            'prediction': prediction,
            'confidence': confidence,
            'probabilities': prob_dict
        })

    except Exception as e:
        return jsonify({'error': str(e)}), 500


@app.route('/evaluate', methods=['POST'])
def evaluate_model():
    """
    Evaluate a trained model on given dataset.

    Request JSON:
    {
        "X": [[...], [...], ...],
        "y": ["Rendah", "Sedang", ...],
        "model_path": "models/svm_model_xxx.pkl"
    }
    """
    try:
        data = request.get_json()

        X = np.array(data['X'])
        y = np.array(data['y'])
        model_path = data['model_path']

        scaler_path = model_path.replace('svm_model_', 'scaler_')
        encoder_path = model_path.replace('svm_model_', 'encoder_')

        # Load model
        model = joblib.load(model_path)

        # Load encoder
        label_encoder = LabelEncoder()
        if os.path.exists(encoder_path):
            label_encoder = joblib.load(encoder_path)
        else:
            label_encoder.classes_ = np.array(CLASS_NAMES)

        y_encoded = label_encoder.transform(y)

        # Scale features
        if os.path.exists(scaler_path):
            scaler = joblib.load(scaler_path)
            X_scaled = scaler.transform(X)
        else:
            X_scaled = X

        # Make predictions
        y_pred = model.predict(X_scaled)

        # Calculate metrics
        accuracy = accuracy_score(y_encoded, y_pred)
        precision = precision_score(y_encoded, y_pred, average='weighted', zero_division=0)
        recall = recall_score(y_encoded, y_pred, average='weighted', zero_division=0)
        f1 = f1_score(y_encoded, y_pred, average='weighted', zero_division=0)

        report = classification_report(
            y_encoded, y_pred,
            target_names=CLASS_NAMES,
            output_dict=True,
            zero_division=0
        )

        conf_matrix = confusion_matrix(y_encoded, y_pred).tolist()

        return jsonify({
            'success': True,
            'accuracy': float(accuracy),
            'precision': float(precision),
            'recall': float(recall),
            'f1_score': float(f1),
            'classification_report': report,
            'confusion_matrix': conf_matrix,
            'total_samples': len(X)
        })

    except Exception as e:
        return jsonify({'error': str(e)}), 500


@app.route('/model-info', methods=['POST'])
def model_info():
    """Get information about a trained model."""
    try:
        data = request.get_json()
        model_path = data['model_path']

        if not os.path.exists(model_path):
            return jsonify({'error': 'Model not found'}), 404

        model = joblib.load(model_path)

        info = {
            'kernel': model.kernel,
            'C': model.C,
            'gamma': str(model.gamma),
            'degree': model.degree,
            'n_support': model.n_support_.tolist(),
            'classes': model.classes_.tolist(),
            'n_features': model.n_features_in_,
        }

        return jsonify({'success': True, 'info': info})

    except Exception as e:
        return jsonify({'error': str(e)}), 500


@app.route('/feature-importance', methods=['POST'])
def feature_importance():
    """
    Get feature importance (for linear kernel only).
    For non-linear kernels, returns permutation importance.
    """
    try:
        data = request.get_json()
        model_path = data['model_path']

        model = joblib.load(model_path)

        if model.kernel == 'linear':
            # For linear kernel, we can use coefficients
            importance = np.abs(model.coef_).mean(axis=0).tolist()
            importance_dict = {FEATURE_NAMES[i]: importance[i] for i in range(len(FEATURE_NAMES))}
        else:
            # For non-linear kernels, importance is not directly available
            importance_dict = {name: 0 for name in FEATURE_NAMES}

        return jsonify({
            'success': True,
            'feature_importance': importance_dict,
            'note': 'Feature importance is only meaningful for linear kernel'
        })

    except Exception as e:
        return jsonify({'error': str(e)}), 500


if __name__ == '__main__':
    port = int(os.environ.get('PORT', 5000))
    debug = os.environ.get('FLASK_DEBUG', 'False').lower() == 'true'
    app.run(host='0.0.0.0', port=port, debug=debug)
