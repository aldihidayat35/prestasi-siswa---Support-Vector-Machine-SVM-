<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MlPrediction extends Model
{
    protected $fillable = [
        'student_id',
        'model_id',
        'ml_model_id',
        'predicted_by',
        'input_features',
        'predicted_label',
        'confidence_score',
        'probability_scores',
        'actual_label',
        'is_correct',
        'recommendation',
    ];

    protected function casts(): array
    {
        return [
            'input_features' => 'array',
            'probability_scores' => 'array',
            'confidence_score' => 'decimal:4',
            'is_correct' => 'boolean',
        ];
    }

    /**
     * Get the student
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the model used for prediction
     */
    public function mlModel(): BelongsTo
    {
        return $this->belongsTo(MlModel::class, 'model_id');
    }

    /**
     * Get the user who made the prediction
     */
    public function predictor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'predicted_by');
    }

    /**
     * Get predicted label badge color
     */
    public function getPredictedLabelColorAttribute(): string
    {
        return match($this->predicted_label) {
            'Rendah' => 'danger',
            'Sedang' => 'warning',
            'Tinggi' => 'success',
            default => 'secondary',
        };
    }

    /**
     * Get confidence as percentage
     */
    public function getConfidencePercentageAttribute(): string
    {
        return number_format($this->confidence_score * 100, 2) . '%';
    }

    /**
     * Get confidence accessor (alias for confidence_score)
     */
    public function getConfidenceAttribute(): float
    {
        return $this->confidence_score ?? 0;
    }

    /**
     * Get the user who made the prediction (alias for predictor)
     */
    public function predictedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'predicted_by');
    }

    /**
     * Generate recommendation based on prediction
     */
    public static function generateRecommendation(string $predictedLabel, array $features): string
    {
        $recommendations = [];

        if ($predictedLabel === 'Rendah') {
            $recommendations[] = 'Siswa memerlukan perhatian khusus dan bimbingan intensif.';

            if ($features['attendance_rate'] < 80) {
                $recommendations[] = 'Tingkatkan kehadiran siswa di kelas.';
            }
            if ($features['study_duration'] < 2) {
                $recommendations[] = 'Dorong siswa untuk menambah durasi belajar harian.';
            }
            if ($features['task_frequency'] < 3) {
                $recommendations[] = 'Motivasi siswa untuk lebih aktif mengerjakan tugas.';
            }
        } elseif ($predictedLabel === 'Sedang') {
            $recommendations[] = 'Siswa menunjukkan perkembangan yang cukup baik.';
            $recommendations[] = 'Berikan dorongan untuk meningkatkan performa akademik.';
        } else {
            $recommendations[] = 'Siswa menunjukkan prestasi akademik yang sangat baik.';
            $recommendations[] = 'Pertahankan dan tingkatkan pencapaian ini.';
        }

        return implode(' ', $recommendations);
    }
}
