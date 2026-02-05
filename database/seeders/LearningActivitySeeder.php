<?php

namespace Database\Seeders;

use App\Models\LearningActivity;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class LearningActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $guru = User::whereHas('role', fn($q) => $q->where('name', 'guru'))->first();

        $period = '2024-1'; // Semester Ganjil 2024

        foreach ($students as $student) {
            // Generate realistic learning activity data
            $activityData = $this->generateLearningActivity();

            LearningActivity::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'period' => $period,
                ],
                [
                    'recorded_by' => $guru->id,
                    'attendance_rate' => $activityData['attendance_rate'],
                    'study_duration' => $activityData['study_duration'],
                    'task_frequency' => $activityData['task_frequency'],
                    'discussion_participation' => $activityData['discussion_participation'],
                    'media_usage' => $activityData['media_usage'],
                    'discipline_score' => $activityData['discipline_score'],
                    'notes' => null,
                ]
            );
        }
    }

    /**
     * Generate realistic learning activity data
     */
    private function generateLearningActivity(): array
    {
        // Random profile: high performer, medium performer, or low performer
        $profile = fake()->randomElement(['high', 'medium', 'low']);

        switch ($profile) {
            case 'high':
                return [
                    'attendance_rate' => fake()->randomFloat(2, 90, 100),
                    'study_duration' => fake()->randomFloat(2, 4, 8),
                    'task_frequency' => fake()->numberBetween(8, 12),
                    'discussion_participation' => fake()->randomFloat(2, 80, 100),
                    'media_usage' => fake()->randomFloat(2, 75, 100),
                    'discipline_score' => fake()->randomFloat(2, 85, 100),
                ];
            case 'medium':
                return [
                    'attendance_rate' => fake()->randomFloat(2, 70, 90),
                    'study_duration' => fake()->randomFloat(2, 2, 5),
                    'task_frequency' => fake()->numberBetween(4, 8),
                    'discussion_participation' => fake()->randomFloat(2, 50, 80),
                    'media_usage' => fake()->randomFloat(2, 40, 75),
                    'discipline_score' => fake()->randomFloat(2, 60, 85),
                ];
            case 'low':
            default:
                return [
                    'attendance_rate' => fake()->randomFloat(2, 40, 70),
                    'study_duration' => fake()->randomFloat(2, 0.5, 2),
                    'task_frequency' => fake()->numberBetween(0, 4),
                    'discussion_participation' => fake()->randomFloat(2, 10, 50),
                    'media_usage' => fake()->randomFloat(2, 10, 40),
                    'discipline_score' => fake()->randomFloat(2, 30, 60),
                ];
        }
    }
}
