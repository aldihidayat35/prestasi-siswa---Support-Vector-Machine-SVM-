<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationResult extends Model
{
    protected $fillable = [
        'model_id',
        'test_size',
        'random_state',
        'accuracy',
        'precision_score',
        'recall',
        'f1_score',
        'classification_report',
        'confusion_matrix',
        'cross_validation_scores',
        'cv_scores',
        'cv_mean',
        'cv_std',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'test_size' => 'decimal:2',
            'random_state' => 'integer',
            'accuracy' => 'decimal:4',
            'precision_score' => 'decimal:4',
            'recall' => 'decimal:4',
            'f1_score' => 'decimal:4',
            'classification_report' => 'array',
            'confusion_matrix' => 'array',
            'cross_validation_scores' => 'array',
            'cv_scores' => 'array',
            'cv_mean' => 'decimal:4',
            'cv_std' => 'decimal:4',
        ];
    }

    /**
     * Get cv_scores accessor (alias)
     */
    public function getCvScoresAttribute($value)
    {
        if ($value) {
            return is_array($value) ? $value : json_decode($value, true);
        }
        return $this->cross_validation_scores;
    }

    /**
     * Get the ML model
     */
    public function mlModel(): BelongsTo
    {
        return $this->belongsTo(MlModel::class, 'model_id');
    }

    /**
     * Get accuracy as percentage
     */
    public function getAccuracyPercentageAttribute(): string
    {
        return number_format($this->accuracy * 100, 2) . '%';
    }

    /**
     * Get precision as percentage
     */
    public function getPrecisionPercentageAttribute(): string
    {
        return number_format($this->precision_score * 100, 2) . '%';
    }

    /**
     * Get recall as percentage
     */
    public function getRecallPercentageAttribute(): string
    {
        return number_format($this->recall * 100, 2) . '%';
    }

    /**
     * Get F1-score as percentage
     */
    public function getF1PercentageAttribute(): string
    {
        return number_format($this->f1_score * 100, 2) . '%';
    }
}
