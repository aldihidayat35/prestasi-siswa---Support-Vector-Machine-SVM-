<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MlDataset;
use App\Models\LearningActivity;
use App\Models\AcademicScore;
use App\Models\Student;
use Illuminate\Http\Request;

class DatasetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MlDataset::with(['student', 'learningActivity', 'academicScore']);

        // Filter by type (training/testing)
        if ($request->filled('type')) {
            $query->where('is_training', $request->type === 'training');
        }

        // Filter by label
        if ($request->filled('label')) {
            $query->where('label', $request->label);
        }

        // Search by student
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $datasets = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total' => MlDataset::count(),
            'training' => MlDataset::where('is_training', true)->count(),
            'testing' => MlDataset::where('is_training', false)->count(),
            'label_rendah' => MlDataset::where('label', 'Rendah')->count(),
            'label_sedang' => MlDataset::where('label', 'Sedang')->count(),
            'label_tinggi' => MlDataset::where('label', 'Tinggi')->count(),
        ];

        return view('admin.datasets.index', compact('datasets', 'stats'));
    }

    /**
     * Generate dataset from learning activities and academic scores.
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'min_score' => 'nullable|numeric|min:0|max:100',
        ]);

        // Get students with both learning activities and academic scores
        $students = Student::whereHas('learningActivities')
            ->whereHas('academicScores')
            ->get();

        $generated = 0;

        foreach ($students as $student) {
            // Get latest learning activity
            $activity = $student->learningActivities()->latest()->first();

            // Get latest academic score
            $score = $student->academicScores()->latest()->first();

            if (!$activity || !$score) {
                continue;
            }

            // Check if dataset already exists for this combination
            $exists = MlDataset::where('student_id', $student->id)
                ->where('learning_activity_id', $activity->id)
                ->where('academic_score_id', $score->id)
                ->exists();

            if ($exists) {
                continue;
            }

            // Calculate average score
            $avgScore = ($score->math_score + $score->science_score + $score->indonesian_score +
                        $score->english_score + $score->social_score) / 5;

            // Determine label
            if ($avgScore >= 80) {
                $label = 'Tinggi';
            } elseif ($avgScore >= 60) {
                $label = 'Sedang';
            } else {
                $label = 'Rendah';
            }

            // Create dataset entry
            MlDataset::create([
                'student_id' => $student->id,
                'learning_activity_id' => $activity->id,
                'academic_score_id' => $score->id,
                'features' => [
                    'attendance_rate' => $activity->attendance_rate,
                    'study_duration' => $activity->study_duration,
                    'task_frequency' => $activity->task_frequency,
                    'discussion_participation' => $activity->discussion_participation,
                    'media_usage' => $activity->media_usage,
                    'discipline_score' => $activity->discipline_score,
                ],
                'label' => $label,
                'is_training' => true, // Default to training set
            ]);

            $generated++;
        }

        return redirect()
            ->route('admin.datasets.index')
            ->with('success', "Berhasil generate {$generated} dataset baru.");
    }

    /**
     * Split dataset into training and testing sets.
     */
    public function split(Request $request)
    {
        $validated = $request->validate([
            'train_ratio' => 'required|numeric|min:0.5|max:0.9',
        ]);

        $trainRatio = $validated['train_ratio'];

        // Get all datasets
        $datasets = MlDataset::inRandomOrder()->get();
        $total = $datasets->count();

        if ($total < 10) {
            return redirect()
                ->route('admin.datasets.index')
                ->with('error', 'Minimal 10 data diperlukan untuk split dataset.');
        }

        $trainCount = (int) floor($total * $trainRatio);

        // Reset all to training first
        MlDataset::query()->update(['is_training' => true]);

        // Mark some as testing
        $testingIds = $datasets->skip($trainCount)->pluck('id');
        MlDataset::whereIn('id', $testingIds)->update(['is_training' => false]);

        $testCount = $total - $trainCount;

        return redirect()
            ->route('admin.datasets.index')
            ->with('success', "Dataset berhasil di-split: {$trainCount} training, {$testCount} testing.");
    }

    /**
     * Clear all datasets.
     */
    public function clear()
    {
        $deleted = MlDataset::count();
        MlDataset::truncate();

        return redirect()
            ->route('admin.datasets.index')
            ->with('success', "Berhasil menghapus {$deleted} dataset.");
    }
}
