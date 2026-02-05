<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MlDataset extends Model
{
    protected $fillable = [
        'student_id',
        'learning_activity_id',
        'academic_score_id',
        'features',
        'label',
        'is_training',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'is_training' => 'boolean',
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
     * Get the learning activity
     */
    public function learningActivity(): BelongsTo
    {
        return $this->belongsTo(LearningActivity::class);
    }

    /**
     * Get the academic score
     */
    public function academicScore(): BelongsTo
    {
        return $this->belongsTo(AcademicScore::class);
    }

    /**
     * Get training data scope
     */
    public function scopeTraining($query)
    {
        return $query->where('is_training', true);
    }

    /**
     * Get testing data scope
     */
    public function scopeTesting($query)
    {
        return $query->where('is_training', false);
    }
}
