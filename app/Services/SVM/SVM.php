<?php

namespace App\Services\SVM;

use Exception;
use InvalidArgumentException;

/**
 * Support Vector Machine (SVM) Classifier
 *
 * Implementasi SVM menggunakan algoritma SMO (Sequential Minimal Optimization)
 * untuk klasifikasi multi-class menggunakan strategi One-vs-Rest (OvR).
 *
 * SVM adalah algoritma supervised learning yang mencari hyperplane optimal
 * untuk memisahkan data dari class yang berbeda dengan margin maksimal.
 *
 * @author Sistem Prediksi Prestasi Akademik
 * @version 1.0
 */
class SVM
{
    // Konstanta untuk konvergensi
    private const EPSILON = 1e-6;
    private const TAU = 1e-12;

    /**
     * Tipe kernel yang digunakan
     */
    private string $kernelType;

    /**
     * Parameter C (regularization parameter)
     * Mengontrol trade-off antara margin width dan classification error
     * C besar = margin kecil, error kecil (mungkin overfit)
     * C kecil = margin besar, toleran error (mungkin underfit)
     */
    private float $C;

    /**
     * Parameter gamma untuk kernel RBF, polynomial, sigmoid
     */
    private float $gamma;

    /**
     * Derajat polynomial untuk kernel polynomial
     */
    private int $degree;

    /**
     * Coefficient untuk kernel polynomial dan sigmoid
     */
    private float $coef0;

    /**
     * Toleransi untuk stopping criterion
     */
    private float $tolerance;

    /**
     * Maksimum iterasi training
     */
    private int $maxIterations;

    /**
     * Binary classifiers untuk multi-class (One-vs-Rest)
     */
    private array $classifiers = [];

    /**
     * Daftar label class
     */
    private array $classes = [];

    /**
     * Training data
     */
    private array $samples = [];
    private array $labels = [];

    /**
     * Statistik training
     */
    private array $trainingStats = [];

    /**
     * Constructor
     *
     * @param string $kernel Tipe kernel: 'linear', 'rbf', 'poly', 'sigmoid'
     * @param float $C Parameter regularization (default: 1.0)
     * @param float $gamma Parameter gamma (default: 'auto' = 1/n_features)
     * @param int $degree Derajat untuk polynomial kernel
     * @param float $tolerance Toleransi untuk konvergensi
     * @param int $maxIterations Maksimum iterasi
     */
    public function __construct(
        string $kernel = 'rbf',
        float $C = 1.0,
        float $gamma = 0.0, // 0 = auto
        int $degree = 3,
        float $tolerance = 0.001,
        int $maxIterations = 1000
    ) {
        $this->kernelType = strtolower($kernel);
        $this->C = $C;
        $this->gamma = $gamma;
        $this->degree = $degree;
        $this->coef0 = 1.0;
        $this->tolerance = $tolerance;
        $this->maxIterations = $maxIterations;

        $this->validateParameters();
    }

    /**
     * Validasi parameter
     */
    private function validateParameters(): void
    {
        $validKernels = ['linear', 'rbf', 'poly', 'polynomial', 'sigmoid'];

        if (!in_array($this->kernelType, $validKernels)) {
            throw new InvalidArgumentException(
                "Kernel tidak valid. Gunakan: " . implode(', ', $validKernels)
            );
        }

        if ($this->C <= 0) {
            throw new InvalidArgumentException("Parameter C harus positif");
        }

        if ($this->degree < 1) {
            throw new InvalidArgumentException("Degree harus >= 1");
        }
    }

    /**
     * Training model SVM
     *
     * @param array $samples Array 2D berisi feature vectors
     * @param array $labels Array 1D berisi label class
     * @return self
     */
    public function train(array $samples, array $labels): self
    {
        if (empty($samples) || empty($labels)) {
            throw new InvalidArgumentException("Data training tidak boleh kosong");
        }

        if (count($samples) !== count($labels)) {
            throw new InvalidArgumentException("Jumlah samples dan labels harus sama");
        }

        $this->samples = $samples;
        $this->labels = $labels;
        $this->classes = array_values(array_unique($labels));
        sort($this->classes);

        // Auto gamma jika belum di-set
        if ($this->gamma <= 0) {
            $nFeatures = count($samples[0]);
            $this->gamma = 1.0 / $nFeatures;
        }

        // Training menggunakan One-vs-Rest untuk multi-class
        $startTime = microtime(true);
        $this->trainOneVsRest();
        $trainingTime = microtime(true) - $startTime;

        $this->trainingStats = [
            'n_samples' => count($samples),
            'n_features' => count($samples[0]),
            'n_classes' => count($this->classes),
            'classes' => $this->classes,
            'training_time' => round($trainingTime, 4),
            'kernel' => $this->kernelType,
            'C' => $this->C,
            'gamma' => $this->gamma,
        ];

        return $this;
    }

    /**
     * Training One-vs-Rest untuk multi-class classification
     */
    private function trainOneVsRest(): void
    {
        $this->classifiers = [];

        foreach ($this->classes as $class) {
            // Buat binary labels: 1 untuk class saat ini, -1 untuk lainnya
            $binaryLabels = array_map(
                fn($label) => $label === $class ? 1 : -1,
                $this->labels
            );

            // Train binary classifier
            $classifier = $this->trainBinary($this->samples, $binaryLabels);
            $this->classifiers[$class] = $classifier;
        }
    }

    /**
     * Training binary SVM menggunakan SMO (Sequential Minimal Optimization)
     *
     * SMO memecah QP problem menjadi sub-problem kecil yang bisa diselesaikan secara analitis
     *
     * @param array $samples Feature vectors
     * @param array $labels Binary labels (+1 atau -1)
     * @return array Classifier parameters (alpha, b, support_vectors, support_labels)
     */
    private function trainBinary(array $samples, array $labels): array
    {
        $n = count($samples);

        // Inisialisasi alpha (Lagrange multipliers)
        $alpha = array_fill(0, $n, 0.0);

        // Bias term
        $b = 0.0;

        // Precompute kernel matrix untuk efisiensi
        $kernelCache = $this->computeKernelMatrix($samples);

        // Error cache
        $E = array_fill(0, $n, 0.0);
        for ($i = 0; $i < $n; $i++) {
            $E[$i] = $this->computeOutput($i, $samples, $labels, $alpha, $b, $kernelCache) - $labels[$i];
        }

        // SMO main loop
        $numChanged = 0;
        $examineAll = true;
        $iteration = 0;

        while (($numChanged > 0 || $examineAll) && $iteration < $this->maxIterations) {
            $numChanged = 0;

            if ($examineAll) {
                // Examine semua training examples
                for ($i = 0; $i < $n; $i++) {
                    $numChanged += $this->examineExample($i, $samples, $labels, $alpha, $b, $E, $kernelCache);
                }
            } else {
                // Examine hanya non-bound examples (0 < alpha < C)
                for ($i = 0; $i < $n; $i++) {
                    if ($alpha[$i] > self::EPSILON && $alpha[$i] < $this->C - self::EPSILON) {
                        $numChanged += $this->examineExample($i, $samples, $labels, $alpha, $b, $E, $kernelCache);
                    }
                }
            }

            if ($examineAll) {
                $examineAll = false;
            } elseif ($numChanged == 0) {
                $examineAll = true;
            }

            $iteration++;
        }

        // Extract support vectors
        $supportVectors = [];
        $supportLabels = [];
        $supportAlphas = [];

        for ($i = 0; $i < $n; $i++) {
            if ($alpha[$i] > self::EPSILON) {
                $supportVectors[] = $samples[$i];
                $supportLabels[] = $labels[$i];
                $supportAlphas[] = $alpha[$i];
            }
        }

        return [
            'alphas' => $supportAlphas,
            'support_vectors' => $supportVectors,
            'support_labels' => $supportLabels,
            'b' => $b,
            'n_support' => count($supportVectors),
        ];
    }

    /**
     * Examine satu training example untuk optimisasi
     */
    private function examineExample(
        int $i2,
        array &$samples,
        array &$labels,
        array &$alpha,
        float &$b,
        array &$E,
        array &$kernelCache
    ): int {
        $y2 = $labels[$i2];
        $alpha2 = $alpha[$i2];
        $E2 = $E[$i2];
        $r2 = $E2 * $y2;

        // Check KKT conditions
        if (($r2 < -$this->tolerance && $alpha2 < $this->C) ||
            ($r2 > $this->tolerance && $alpha2 > 0)) {

            // Pilih i1 menggunakan second choice heuristic
            $i1 = $this->selectSecondExample($i2, $E);

            if ($i1 >= 0 && $this->takeStep($i1, $i2, $samples, $labels, $alpha, $b, $E, $kernelCache)) {
                return 1;
            }

            // Loop over non-bound examples
            $n = count($samples);
            $start = rand(0, $n - 1);
            for ($k = 0; $k < $n; $k++) {
                $i1 = ($start + $k) % $n;
                if ($alpha[$i1] > self::EPSILON && $alpha[$i1] < $this->C - self::EPSILON) {
                    if ($this->takeStep($i1, $i2, $samples, $labels, $alpha, $b, $E, $kernelCache)) {
                        return 1;
                    }
                }
            }

            // Loop over semua examples
            $start = rand(0, $n - 1);
            for ($k = 0; $k < $n; $k++) {
                $i1 = ($start + $k) % $n;
                if ($this->takeStep($i1, $i2, $samples, $labels, $alpha, $b, $E, $kernelCache)) {
                    return 1;
                }
            }
        }

        return 0;
    }

    /**
     * Pilih contoh kedua untuk optimisasi (second choice heuristic)
     */
    private function selectSecondExample(int $i2, array $E): int
    {
        $i1 = -1;
        $maxDiff = 0;

        foreach ($E as $i => $error) {
            if ($i !== $i2) {
                $diff = abs($E[$i2] - $error);
                if ($diff > $maxDiff) {
                    $maxDiff = $diff;
                    $i1 = $i;
                }
            }
        }

        return $i1;
    }

    /**
     * Take optimization step untuk pasangan alpha
     */
    private function takeStep(
        int $i1,
        int $i2,
        array &$samples,
        array &$labels,
        array &$alpha,
        float &$b,
        array &$E,
        array &$kernelCache
    ): bool {
        if ($i1 === $i2) {
            return false;
        }

        $alpha1 = $alpha[$i1];
        $alpha2 = $alpha[$i2];
        $y1 = $labels[$i1];
        $y2 = $labels[$i2];
        $E1 = $E[$i1];
        $E2 = $E[$i2];
        $s = $y1 * $y2;

        // Compute L dan H
        if ($y1 !== $y2) {
            $L = max(0, $alpha2 - $alpha1);
            $H = min($this->C, $this->C + $alpha2 - $alpha1);
        } else {
            $L = max(0, $alpha1 + $alpha2 - $this->C);
            $H = min($this->C, $alpha1 + $alpha2);
        }

        if (abs($L - $H) < self::TAU) {
            return false;
        }

        // Compute eta
        $k11 = $kernelCache[$i1][$i1];
        $k12 = $kernelCache[$i1][$i2];
        $k22 = $kernelCache[$i2][$i2];
        $eta = $k11 + $k22 - 2 * $k12;

        if ($eta > 0) {
            $a2 = $alpha2 + $y2 * ($E1 - $E2) / $eta;
            $a2 = max($L, min($H, $a2)); // Clip
        } else {
            // Rare case: evaluate objective function at endpoints
            return false;
        }

        if (abs($a2 - $alpha2) < self::EPSILON * ($a2 + $alpha2 + self::EPSILON)) {
            return false;
        }

        $a1 = $alpha1 + $s * ($alpha2 - $a2);

        // Update bias
        $b1 = $E1 + $y1 * ($a1 - $alpha1) * $k11 + $y2 * ($a2 - $alpha2) * $k12 + $b;
        $b2 = $E2 + $y1 * ($a1 - $alpha1) * $k12 + $y2 * ($a2 - $alpha2) * $k22 + $b;

        if ($a1 > self::EPSILON && $a1 < $this->C - self::EPSILON) {
            $b = $b1;
        } elseif ($a2 > self::EPSILON && $a2 < $this->C - self::EPSILON) {
            $b = $b2;
        } else {
            $b = ($b1 + $b2) / 2;
        }

        // Update alphas
        $alpha[$i1] = $a1;
        $alpha[$i2] = $a2;

        // Update error cache
        $n = count($samples);
        for ($i = 0; $i < $n; $i++) {
            $E[$i] = $this->computeOutput($i, $samples, $labels, $alpha, $b, $kernelCache) - $labels[$i];
        }

        return true;
    }

    /**
     * Compute output untuk satu sample
     */
    private function computeOutput(
        int $i,
        array $samples,
        array $labels,
        array $alpha,
        float $b,
        array $kernelCache
    ): float {
        $sum = 0.0;
        $n = count($samples);

        for ($j = 0; $j < $n; $j++) {
            if ($alpha[$j] > self::EPSILON) {
                $sum += $alpha[$j] * $labels[$j] * $kernelCache[$j][$i];
            }
        }

        return $sum - $b;
    }

    /**
     * Compute kernel matrix
     */
    private function computeKernelMatrix(array $samples): array
    {
        $n = count($samples);
        $matrix = [];

        for ($i = 0; $i < $n; $i++) {
            $matrix[$i] = [];
            for ($j = 0; $j < $n; $j++) {
                if ($j < $i) {
                    $matrix[$i][$j] = $matrix[$j][$i]; // Symmetric
                } else {
                    $matrix[$i][$j] = $this->kernel($samples[$i], $samples[$j]);
                }
            }
        }

        return $matrix;
    }

    /**
     * Hitung kernel antara dua vector
     */
    private function kernel(array $x, array $y): float
    {
        switch ($this->kernelType) {
            case 'linear':
                return Kernel::linear($x, $y);
            case 'poly':
            case 'polynomial':
                return Kernel::polynomial($x, $y, $this->gamma, $this->coef0, $this->degree);
            case 'sigmoid':
                return Kernel::sigmoid($x, $y, $this->gamma, $this->coef0);
            case 'rbf':
            default:
                return Kernel::rbf($x, $y, $this->gamma);
        }
    }

    /**
     * Prediksi class untuk samples
     *
     * @param array $samples Array 2D feature vectors atau 1D untuk single sample
     * @return array|string Predicted labels
     */
    public function predict(array $samples): array|string
    {
        if (empty($this->classifiers)) {
            throw new Exception("Model belum di-training. Panggil train() terlebih dahulu.");
        }

        // Single sample
        if (!is_array($samples[0])) {
            return $this->predictSingle($samples);
        }

        // Multiple samples
        return array_map(fn($sample) => $this->predictSingle($sample), $samples);
    }

    /**
     * Prediksi untuk single sample
     */
    private function predictSingle(array $sample): string
    {
        $scores = [];

        foreach ($this->classifiers as $class => $classifier) {
            $score = $this->decisionFunction($sample, $classifier);
            $scores[$class] = $score;
        }

        // Return class dengan score tertinggi
        arsort($scores);
        return (string) array_key_first($scores);
    }

    /**
     * Decision function untuk binary classifier
     */
    private function decisionFunction(array $sample, array $classifier): float
    {
        $sum = 0.0;

        foreach ($classifier['support_vectors'] as $i => $sv) {
            $sum += $classifier['alphas'][$i]
                  * $classifier['support_labels'][$i]
                  * $this->kernel($sv, $sample);
        }

        return $sum - $classifier['b'];
    }

    /**
     * Prediksi dengan probability scores
     *
     * @param array $sample Single sample
     * @return array ['prediction' => label, 'probabilities' => [...], 'confidence' => float]
     */
    public function predictWithProbability(array $sample): array
    {
        if (empty($this->classifiers)) {
            throw new Exception("Model belum di-training. Panggil train() terlebih dahulu.");
        }

        $scores = [];
        foreach ($this->classifiers as $class => $classifier) {
            $scores[$class] = $this->decisionFunction($sample, $classifier);
        }

        // Convert decision values to probabilities using softmax
        $probabilities = $this->softmax($scores);

        // Get prediction
        arsort($probabilities);
        $prediction = array_key_first($probabilities);
        $confidence = $probabilities[$prediction];

        return [
            'prediction' => (string) $prediction,
            'probabilities' => $probabilities,
            'confidence' => round($confidence * 100, 2),
        ];
    }

    /**
     * Softmax function untuk konversi ke probability
     */
    private function softmax(array $scores): array
    {
        // Numerical stability: subtract max
        $maxScore = max($scores);
        $expScores = array_map(fn($s) => exp($s - $maxScore), $scores);
        $sumExp = array_sum($expScores);

        return array_map(fn($e) => $e / $sumExp, $expScores);
    }

    /**
     * Evaluasi model dengan test data
     *
     * @param array $testSamples Test feature vectors
     * @param array $testLabels True labels
     * @return array Evaluation metrics
     */
    public function evaluate(array $testSamples, array $testLabels): array
    {
        $predictions = $this->predict($testSamples);
        $n = count($testLabels);

        // Confusion matrix
        $confusionMatrix = [];
        foreach ($this->classes as $actual) {
            foreach ($this->classes as $predicted) {
                $confusionMatrix[$actual][$predicted] = 0;
            }
        }

        $correct = 0;
        for ($i = 0; $i < $n; $i++) {
            $confusionMatrix[$testLabels[$i]][$predictions[$i]]++;
            if ($predictions[$i] === $testLabels[$i]) {
                $correct++;
            }
        }

        $accuracy = $correct / $n;

        // Per-class metrics
        $metrics = [];
        foreach ($this->classes as $class) {
            $tp = $confusionMatrix[$class][$class];
            $fp = array_sum(array_column($confusionMatrix, $class)) - $tp;
            $fn = array_sum($confusionMatrix[$class]) - $tp;

            $precision = ($tp + $fp) > 0 ? $tp / ($tp + $fp) : 0;
            $recall = ($tp + $fn) > 0 ? $tp / ($tp + $fn) : 0;
            $f1 = ($precision + $recall) > 0 ? 2 * $precision * $recall / ($precision + $recall) : 0;

            $metrics[$class] = [
                'precision' => round($precision, 4),
                'recall' => round($recall, 4),
                'f1_score' => round($f1, 4),
                'support' => $tp + $fn,
            ];
        }

        // Macro averages
        $macroPrecision = array_sum(array_column($metrics, 'precision')) / count($this->classes);
        $macroRecall = array_sum(array_column($metrics, 'recall')) / count($this->classes);
        $macroF1 = array_sum(array_column($metrics, 'f1_score')) / count($this->classes);

        return [
            'accuracy' => round($accuracy, 4),
            'precision' => round($macroPrecision, 4),
            'recall' => round($macroRecall, 4),
            'f1_score' => round($macroF1, 4),
            'confusion_matrix' => $confusionMatrix,
            'class_metrics' => $metrics,
            'predictions' => $predictions,
        ];
    }

    /**
     * Cross-validation
     *
     * @param array $samples All samples
     * @param array $labels All labels
     * @param int $k Number of folds
     * @return array CV results
     */
    public function crossValidate(array $samples, array $labels, int $k = 5): array
    {
        $n = count($samples);
        $foldSize = intval(ceil($n / $k));

        // Shuffle indices
        $indices = range(0, $n - 1);
        shuffle($indices);

        $scores = [];

        for ($fold = 0; $fold < $k; $fold++) {
            $testStart = $fold * $foldSize;
            $testEnd = min($testStart + $foldSize, $n);

            $trainSamples = [];
            $trainLabels = [];
            $testSamples = [];
            $testLabels = [];

            for ($i = 0; $i < $n; $i++) {
                $idx = $indices[$i];
                if ($i >= $testStart && $i < $testEnd) {
                    $testSamples[] = $samples[$idx];
                    $testLabels[] = $labels[$idx];
                } else {
                    $trainSamples[] = $samples[$idx];
                    $trainLabels[] = $labels[$idx];
                }
            }

            // Train dan evaluate
            $cv = new self($this->kernelType, $this->C, $this->gamma, $this->degree, $this->tolerance, $this->maxIterations);
            $cv->train($trainSamples, $trainLabels);
            $evaluation = $cv->evaluate($testSamples, $testLabels);
            $scores[] = $evaluation['accuracy'];
        }

        return [
            'scores' => $scores,
            'mean' => round(array_sum($scores) / count($scores), 4),
            'std' => round($this->standardDeviation($scores), 4),
        ];
    }

    /**
     * Hitung standard deviation
     */
    private function standardDeviation(array $values): float
    {
        $n = count($values);
        if ($n < 2) return 0;

        $mean = array_sum($values) / $n;
        $variance = array_sum(array_map(fn($x) => pow($x - $mean, 2), $values)) / ($n - 1);

        return sqrt($variance);
    }

    /**
     * Get training statistics
     */
    public function getStats(): array
    {
        return $this->trainingStats;
    }

    /**
     * Get classes
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * Get total support vectors
     */
    public function getSupportVectorCount(): int
    {
        $count = 0;
        foreach ($this->classifiers as $classifier) {
            $count += $classifier['n_support'];
        }
        return $count;
    }

    /**
     * Serialize model untuk disimpan
     */
    public function serialize(): string
    {
        return serialize([
            'kernel' => $this->kernelType,
            'C' => $this->C,
            'gamma' => $this->gamma,
            'degree' => $this->degree,
            'coef0' => $this->coef0,
            'classes' => $this->classes,
            'classifiers' => $this->classifiers,
            'stats' => $this->trainingStats,
        ]);
    }

    /**
     * Unserialize model
     */
    public static function unserialize(string $data): self
    {
        $params = unserialize($data);

        $svm = new self(
            $params['kernel'],
            $params['C'],
            $params['gamma'],
            $params['degree']
        );

        $svm->classes = $params['classes'];
        $svm->classifiers = $params['classifiers'];
        $svm->trainingStats = $params['stats'];

        return $svm;
    }
}
