<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AcademicScore extends Model
{
    protected $fillable = [
        'student_id',
        'semester',
        'score',
        'category',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
        ];
    }

    /**
     * Categories for academic performance
     */
    public const CATEGORIES = [
        'Rendah' => 'Rendah',
        'Sedang' => 'Sedang',
        'Tinggi' => 'Tinggi',
    ];

    /**
     * Get category based on score
     */
    public static function getCategoryFromScore(float $score): string
    {
        if ($score < 60) {
            return 'Rendah';
        } elseif ($score < 80) {
            return 'Sedang';
        } else {
            return 'Tinggi';
        }
    }

    /**
     * Get the student
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the ML dataset for this score
     */
    public function mlDataset(): HasOne
    {
        return $this->hasOne(MlDataset::class);
    }

    /**
     * Get category badge color
     */
    public function getCategoryColorAttribute(): string
    {
        return match($this->category) {
            'Rendah' => 'danger',
            'Sedang' => 'warning',
            'Tinggi' => 'success',
            default => 'secondary',
        };
    }
}
