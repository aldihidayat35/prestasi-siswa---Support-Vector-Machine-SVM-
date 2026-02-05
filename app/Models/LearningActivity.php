<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LearningActivity extends Model
{
    protected $fillable = [
        'student_id',
        'recorded_by',
        'attendance_rate',
        'study_duration',
        'task_frequency',
        'discussion_participation',
        'media_usage',
        'discipline_score',
        'period',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'attendance_rate' => 'decimal:2',
            'study_duration' => 'decimal:2',
            'task_frequency' => 'integer',
            'discussion_participation' => 'decimal:2',
            'media_usage' => 'decimal:2',
            'discipline_score' => 'decimal:2',
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
     * Get the guru who recorded this activity
     */
    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Get the ML dataset for this activity
     */
    public function mlDataset(): HasOne
    {
        return $this->hasOne(MlDataset::class);
    }

    /**
     * Get features as array for ML
     */
    public function getFeaturesArrayAttribute(): array
    {
        return [
            'attendance_rate' => (float) $this->attendance_rate,
            'study_duration' => (float) $this->study_duration,
            'task_frequency' => (int) $this->task_frequency,
            'discussion_participation' => (float) $this->discussion_participation,
            'media_usage' => (float) $this->media_usage,
            'discipline_score' => (float) $this->discipline_score,
        ];
    }
}
