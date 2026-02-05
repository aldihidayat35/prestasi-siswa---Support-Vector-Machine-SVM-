<?php

namespace App\Services;

use App\Models\MlDataset;
use App\Models\MlModel;
use App\Models\EvaluationResult;
use App\Services\SVM\SVM;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

/**
 * Machine Learning Service
 *
 * Service untuk training, prediksi, dan evaluasi model SVM
 * menggunakan implementasi PHP native (tanpa Python)
 */
class MLService
{
    /**
     * Directory untuk menyimpan model
     */
    protected string $modelDirectory;

    public function __construct()
    {
        $this->modelDirectory = storage_path('app/ml-models');

        if (!file_exists($this->modelDirectory)) {
            mkdir($this->modelDirectory, 0755, true);
        }
    }

    /**
     * Get dataset for training
     *
     * @return array Dataset dengan format X (features) dan y (labels)
     */
    public function prepareDataset(): array
    {
        $datasets = MlDataset::with(['student', 'learningActivity', 'academicScore'])->get();

        $X = [];
        $y = [];

        foreach ($datasets as $dataset) {
            $X[] = $this->normalizeFeatures(array_values($dataset->features));
            $y[] = $dataset->label;
        }

        return [
            'X' => $X,
            'y' => $y,
            'feature_names' => [
                'attendance_rate',
                'study_duration',
                'task_frequency',
                'discussion_participation',
                'media_usage',
                'discipline_score',
            ],
            'class_names' => ['Rendah', 'Sedang', 'Tinggi'],
        ];
    }

    /**
     * Normalize features ke range [0, 1]
     *
     * @param array $features Raw features
     * @return array Normalized features
     */
    protected function normalizeFeatures(array $features): array
    {
        // Feature ranges berdasarkan definisi:
        // attendance_rate: 0-100 (%)
        // study_duration: 0-24 (jam per hari, max realistic ~8)
        // task_frequency: 0-100 (jumlah tugas, max realistic ~50)
        // discussion_participation: 0-100 (%)
        // media_usage: 0-100 (%)
        // discipline_score: 0-100 (%)

        $maxValues = [100, 8, 50, 100, 100, 100];

        return array_map(function ($value, $max) {
            return min(1.0, max(0.0, $value / $max));
        }, $features, $maxValues);
    }

    /**
     * Denormalize features
     */
    protected function denormalizeFeatures(array $normalized): array
    {
        $maxValues = [100, 8, 50, 100, 100, 100];

        return array_map(function ($value, $max) {
            return $value * $max;
        }, $normalized, $maxValues);
    }

    /**
     * Train SVM model
     *
     * @param array $params Training parameters
     * @return array Training results
     */
    public function trainModel(array $params): array
    {
        try {
            $startTime = microtime(true);

            // Prepare dataset
            $dataset = $this->prepareDataset();

            if (count($dataset['X']) < 10) {
                throw new Exception('Dataset terlalu kecil. Minimal 10 sampel diperlukan.');
            }

            // Split dataset
            $testSize = $params['test_size'] ?? 0.2;
            $split = $this->splitDataset($dataset['X'], $dataset['y'], $testSize);

            // Create dan train SVM
            $svm = new SVM(
                kernel: $params['kernel'] ?? 'rbf',
                C: floatval($params['c_parameter'] ?? 1.0),
                gamma: floatval($params['gamma_parameter'] ?? 0), // 0 = auto
                degree: intval($params['degree'] ?? 3),
                tolerance: 0.001,
                maxIterations: 1000
            );

            $svm->train($split['X_train'], $split['y_train']);

            // Evaluate on test set
            $evaluation = $svm->evaluate($split['X_test'], $split['y_test']);

            // Cross-validation
            $cvResults = $svm->crossValidate($dataset['X'], $dataset['y'], 5);

            // Save model
            $modelFilename = 'svm_model_' . date('Ymd_His') . '.model';
            $modelPath = $this->modelDirectory . '/' . $modelFilename;
            file_put_contents($modelPath, $svm->serialize());

            $trainingTime = microtime(true) - $startTime;

            return [
                'success' => true,
                'model_path' => $modelPath,
                'model_filename' => $modelFilename,
                'training_time' => round($trainingTime, 4),
                'parameters' => [
                    'kernel' => $params['kernel'] ?? 'rbf',
                    'C' => floatval($params['c_parameter'] ?? 1.0),
                    'gamma' => $svm->getStats()['gamma'],
                    'degree' => intval($params['degree'] ?? 3),
                ],
                'dataset_info' => [
                    'total_samples' => count($dataset['X']),
                    'training_samples' => count($split['X_train']),
                    'testing_samples' => count($split['X_test']),
                    'n_features' => count($dataset['X'][0]),
                    'classes' => $dataset['class_names'],
                ],
                'evaluation' => [
                    'accuracy' => $evaluation['accuracy'],
                    'precision' => $evaluation['precision'],
                    'recall' => $evaluation['recall'],
                    'f1_score' => $evaluation['f1_score'],
                    'confusion_matrix' => $evaluation['confusion_matrix'],
                    'class_metrics' => $evaluation['class_metrics'],
                ],
                'cross_validation' => [
                    'cv_scores' => $cvResults['scores'],
                    'cv_mean' => $cvResults['mean'],
                    'cv_std' => $cvResults['std'],
                ],
                'support_vectors' => $svm->getSupportVectorCount(),
            ];

        } catch (Exception $e) {
            Log::error('ML Training Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Split dataset menjadi training dan testing
     */
    protected function splitDataset(array $X, array $y, float $testSize): array
    {
        $n = count($X);
        $testCount = intval(ceil($n * $testSize));

        // Shuffle indices
        $indices = range(0, $n - 1);
        shuffle($indices);

        $X_train = [];
        $y_train = [];
        $X_test = [];
        $y_test = [];

        for ($i = 0; $i < $n; $i++) {
            $idx = $indices[$i];
            if ($i < $testCount) {
                $X_test[] = $X[$idx];
                $y_test[] = $y[$idx];
            } else {
                $X_train[] = $X[$idx];
                $y_train[] = $y[$idx];
            }
        }

        return [
            'X_train' => $X_train,
            'y_train' => $y_train,
            'X_test' => $X_test,
            'y_test' => $y_test,
        ];
    }

    /**
     * Make prediction using trained model
     *
     * @param array $features Input features (raw values)
     * @param string|null $modelPath Path to model file (optional, uses active model if null)
     * @return array Prediction result
     */
    public function predict(array $features, ?string $modelPath = null): array
    {
        try {
            // Load model
            if ($modelPath === null) {
                $activeModel = MlModel::where('is_active', true)->first();
                if (!$activeModel) {
                    throw new Exception('Tidak ada model aktif. Silakan training model terlebih dahulu.');
                }
                $modelPath = $activeModel->model_path;
            }

            if (!file_exists($modelPath)) {
                throw new Exception('File model tidak ditemukan: ' . $modelPath);
            }

            $modelData = file_get_contents($modelPath);
            $svm = SVM::unserialize($modelData);

            // Normalize features
            $normalizedFeatures = $this->normalizeFeatures(array_values($features));

            // Predict with probability
            $result = $svm->predictWithProbability($normalizedFeatures);

            return [
                'success' => true,
                'prediction' => $result['prediction'],
                'confidence' => $result['confidence'],
                'probabilities' => $result['probabilities'],
            ];

        } catch (Exception $e) {
            Log::error('ML Prediction Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Evaluate model on test dataset
     *
     * @param string $modelPath Path to model file
     * @return array Evaluation results
     */
    public function evaluateModel(string $modelPath): array
    {
        try {
            if (!file_exists($modelPath)) {
                throw new Exception('File model tidak ditemukan: ' . $modelPath);
            }

            $modelData = file_get_contents($modelPath);
            $svm = SVM::unserialize($modelData);

            $dataset = $this->prepareDataset();
            $evaluation = $svm->evaluate($dataset['X'], $dataset['y']);

            return [
                'success' => true,
                'accuracy' => $evaluation['accuracy'],
                'precision' => $evaluation['precision'],
                'recall' => $evaluation['recall'],
                'f1_score' => $evaluation['f1_score'],
                'confusion_matrix' => $evaluation['confusion_matrix'],
                'class_metrics' => $evaluation['class_metrics'],
            ];

        } catch (Exception $e) {
            Log::error('ML Evaluation Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get model info
     *
     * @param string $modelPath Path to model file
     * @return array Model information
     */
    public function getModelInfo(string $modelPath): array
    {
        try {
            if (!file_exists($modelPath)) {
                throw new Exception('File model tidak ditemukan: ' . $modelPath);
            }

            $modelData = file_get_contents($modelPath);
            $svm = SVM::unserialize($modelData);

            return [
                'success' => true,
                'stats' => $svm->getStats(),
                'classes' => $svm->getClasses(),
                'support_vectors' => $svm->getSupportVectorCount(),
            ];

        } catch (Exception $e) {
            Log::error('ML Model Info Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check if ML service is available
     * Selalu return true karena menggunakan PHP native
     */
    public function checkHealth(): bool
    {
        return true;
    }

    /**
     * Generate dataset statistics
     */
    public function getDatasetStats(): array
    {
        $total = MlDataset::count();
        $training = MlDataset::training()->count();
        $testing = MlDataset::testing()->count();

        $labelDistribution = MlDataset::selectRaw('label, COUNT(*) as count')
            ->groupBy('label')
            ->pluck('count', 'label')
            ->toArray();

        return [
            'total' => $total,
            'training' => $training,
            'testing' => $testing,
            'label_distribution' => $labelDistribution,
        ];
    }

    /**
     * Generate classification report as formatted array
     */
    public function generateClassificationReport(array $evaluation): array
    {
        $report = [];

        foreach ($evaluation['class_metrics'] as $class => $metrics) {
            $report[$class] = [
                'precision' => round($metrics['precision'] * 100, 2),
                'recall' => round($metrics['recall'] * 100, 2),
                'f1_score' => round($metrics['f1_score'] * 100, 2),
                'support' => $metrics['support'],
            ];
        }

        $report['accuracy'] = round($evaluation['accuracy'] * 100, 2);
        $report['macro_avg'] = [
            'precision' => round($evaluation['precision'] * 100, 2),
            'recall' => round($evaluation['recall'] * 100, 2),
            'f1_score' => round($evaluation['f1_score'] * 100, 2),
        ];

        return $report;
    }
}
