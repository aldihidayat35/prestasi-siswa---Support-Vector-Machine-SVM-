# Python ML Service for SVM Prediction

## Setup

1. Create virtual environment:
```bash
cd python-ml
python -m venv venv
```

2. Activate virtual environment:
- Windows: `venv\Scripts\activate`
- Linux/Mac: `source venv/bin/activate`

3. Install dependencies:
```bash
pip install -r requirements.txt
```

4. Copy environment file:
```bash
cp .env.example .env
```

5. Run the service:
```bash
python app.py
```

The service will run on `http://localhost:5000`

## API Endpoints

### Health Check
- **GET** `/health`
- Returns service status

### Train Model
- **POST** `/train`
- Train SVM model with dataset

### Predict
- **POST** `/predict`
- Make prediction using trained model

### Evaluate
- **POST** `/evaluate`
- Evaluate model on dataset

### Model Info
- **POST** `/model-info`
- Get information about trained model

## Production Deployment

Use Gunicorn:
```bash
gunicorn -w 4 -b 0.0.0.0:5000 app:app
```
