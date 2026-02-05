<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'nis',
        'name',
        'class',
        'gender',
        'birth_date',
        'birth_place',
        'address',
        'parent_name',
        'parent_phone',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get learning activities for this student
     */
    public function learningActivities(): HasMany
    {
        return $this->hasMany(LearningActivity::class);
    }

    /**
     * Get academic scores for this student
     */
    public function academicScores(): HasMany
    {
        return $this->hasMany(AcademicScore::class);
    }

    /**
     * Get ML datasets for this student
     */
    public function mlDatasets(): HasMany
    {
        return $this->hasMany(MlDataset::class);
    }

    /**
     * Get predictions for this student
     */
    public function predictions(): HasMany
    {
        return $this->hasMany(MlPrediction::class);
    }

    /**
     * Get latest learning activity
     */
    public function latestLearningActivity()
    {
        return $this->hasOne(LearningActivity::class)->latestOfMany();
    }

    /**
     * Get latest academic score
     */
    public function latestAcademicScore()
    {
        return $this->hasOne(AcademicScore::class)->latestOfMany();
    }

    /**
     * Get latest prediction
     */
    public function latestPrediction()
    {
        return $this->hasOne(MlPrediction::class)->latestOfMany();
    }

    /**
     * Get gender label
     */
    public function getGenderLabelAttribute(): string
    {
        return $this->gender === 'L' ? 'Laki-laki' : 'Perempuan';
    }
}
