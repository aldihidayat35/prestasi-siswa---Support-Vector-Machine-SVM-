<?php

namespace Database\Seeders;

use App\Models\AcademicScore;
use App\Models\LearningActivity;
use App\Models\MlDataset;
use App\Models\Student;
use Illuminate\Database\Seeder;

class MlDatasetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all students with learning activities and academic scores
        $students = Student::with(['latestLearningActivity', 'latestAcademicScore'])->get();

        $trainingCount = 0;
        $testingCount = 0;

        foreach ($students as $student) {
            $activity = $student->latestLearningActivity;
            $score = $student->latestAcademicScore;

            if (!$activity || !$score) {
                continue;
            }

            // 80% training, 20% testing
            $isTraining = fake()->boolean(80);

            if ($isTraining) {
                $trainingCount++;
            } else {
                $testingCount++;
            }

            MlDataset::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'learning_activity_id' => $activity->id,
                    'academic_score_id' => $score->id,
                ],
                [
                    'features' => [
                        'attendance_rate' => (float) $activity->attendance_rate,
                        'study_duration' => (float) $activity->study_duration,
                        'task_frequency' => (int) $activity->task_frequency,
                        'discussion_participation' => (float) $activity->discussion_participation,
                        'media_usage' => (float) $activity->media_usage,
                        'discipline_score' => (float) $activity->discipline_score,
                    ],
                    'label' => $score->category,
                    'is_training' => $isTraining,
                ]
            );
        }

        $this->command->info("Created ML Dataset: {$trainingCount} training, {$testingCount} testing samples");
    }
}
