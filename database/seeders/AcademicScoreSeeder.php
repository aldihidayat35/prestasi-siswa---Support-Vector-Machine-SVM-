<?php

namespace Database\Seeders;

use App\Models\AcademicScore;
use App\Models\LearningActivity;
use App\Models\Student;
use Illuminate\Database\Seeder;

class AcademicScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::with('latestLearningActivity')->get();
        $semester = '2024-1';

        foreach ($students as $student) {
            $activity = $student->latestLearningActivity;

            if (!$activity) {
                continue;
            }

            // Calculate expected score based on learning activities
            // This simulates realistic correlation between activities and scores
            $score = $this->calculateScore($activity);
            $category = AcademicScore::getCategoryFromScore($score);

            AcademicScore::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'semester' => $semester,
                ],
                [
                    'score' => $score,
                    'category' => $category,
                    'notes' => null,
                ]
            );
        }
    }

    /**
     * Calculate academic score based on learning activities
     * This creates a realistic correlation for ML training
     */
    private function calculateScore(LearningActivity $activity): float
    {
        // Weighted average of learning activity features
        // These weights simulate real-world correlation
        $weights = [
            'attendance_rate' => 0.25,        // 25% weight
            'study_duration' => 0.20,         // 20% weight (normalized to 0-100 scale)
            'task_frequency' => 0.15,         // 15% weight (normalized to 0-100 scale)
            'discussion_participation' => 0.15, // 15% weight
            'media_usage' => 0.10,            // 10% weight
            'discipline_score' => 0.15,       // 15% weight
        ];

        // Normalize values
        $normalized = [
            'attendance_rate' => $activity->attendance_rate,
            'study_duration' => min(($activity->study_duration / 8) * 100, 100), // Assuming 8 hours is max
            'task_frequency' => min(($activity->task_frequency / 10) * 100, 100), // Assuming 10 is max
            'discussion_participation' => $activity->discussion_participation,
            'media_usage' => $activity->media_usage,
            'discipline_score' => $activity->discipline_score,
        ];

        // Calculate weighted score
        $score = 0;
        foreach ($weights as $feature => $weight) {
            $score += $normalized[$feature] * $weight;
        }

        // Add some randomness (+/- 5 points) to make it more realistic
        $score += fake()->randomFloat(2, -5, 5);

        // Clamp between 0 and 100
        return max(0, min(100, $score));
    }
}
