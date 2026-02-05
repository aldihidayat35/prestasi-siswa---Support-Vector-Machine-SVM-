<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MlModel extends Model
{
    protected $table = 'ml_models';

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'version',
        'kernel',
        'c_parameter',
        'c_param',
        'gamma_parameter',
        'gamma',
        'degree',
        'model_path',
        'scaler_path',
        'training_samples',
        'testing_samples',
        'test_size',
        'accuracy',
        'precision_score',
        'precision',
        'recall',
        'f1_score',
        'training_date',
        'trained_at',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'c_parameter' => 'decimal:4',
            'c_param' => 'decimal:4',
            'gamma_parameter' => 'decimal:4',
            'degree' => 'integer',
            'training_samples' => 'integer',
            'testing_samples' => 'integer',
            'test_size' => 'decimal:2',
            'accuracy' => 'decimal:4',
            'precision_score' => 'decimal:4',
            'precision' => 'decimal:4',
            'recall' => 'decimal:4',
            'f1_score' => 'decimal:4',
            'training_date' => 'datetime',
            'trained_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * SVM Kernel options
     */
    public const KERNELS = [
        'linear' => 'Linear',
        'poly' => 'Polynomial',
        'rbf' => 'RBF (Radial Basis Function)',
        'sigmoid' => 'Sigmoid',
    ];

    /**
     * Get c_param accessor
     */
    public function getCParamAttribute($value)
    {
        return $value ?? $this->c_parameter;
    }

    /**
     * Get gamma accessor
     */
    public function getGammaAttribute($value)
    {
        return $value ?? $this->gamma_parameter ?? 'scale';
    }

    /**
     * Get precision accessor
     */
    public function getPrecisionAttribute($value)
    {
        return $value ?? $this->precision_score;
    }

    /**
     * Get trained_at accessor
     */
    public function getTrainedAtAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value) : $this->training_date;
    }

    /**
     * Get the user who trained this model
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get evaluation results for this model
     */
    public function evaluationResults(): HasMany
    {
        return $this->hasMany(EvaluationResult::class, 'model_id');
    }

    /**
     * Get predictions using this model
     */
    public function predictions(): HasMany
    {
        return $this->hasMany(MlPrediction::class, 'model_id');
    }

    /**
     * Get latest evaluation result
     */
    public function latestEvaluation()
    {
        return $this->hasOne(EvaluationResult::class, 'model_id')->latestOfMany();
    }

    /**
     * Scope for active model
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get accuracy as percentage
     */
    public function getAccuracyPercentageAttribute(): string
    {
        return number_format($this->accuracy * 100, 2) . '%';
    }
}
