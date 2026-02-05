<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicScore;
use App\Models\LearningActivity;
use App\Models\MlDataset;
use App\Models\MlModel;
use App\Models\MlPrediction;
use App\Models\Student;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function index(): View
    {
        // Statistics
        $stats = [
            'total_students' => Student::count(),
            'active_students' => Student::where('is_active', true)->count(),
            'total_activities' => LearningActivity::count(),
            'total_scores' => AcademicScore::count(),
            'total_datasets' => MlDataset::count(),
            'total_models' => MlModel::count(),
            'active_model' => MlModel::active()->first(),
            'total_predictions' => MlPrediction::count(),
            'total_users' => User::count(),
        ];

        // Category distribution
        $categoryDistribution = AcademicScore::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        // Latest predictions
        $latestPredictions = MlPrediction::with(['student', 'mlModel'])
            ->latest()
            ->limit(5)
            ->get();

        // Model performance (if active model exists)
        $modelPerformance = null;
        if ($stats['active_model']) {
            $modelPerformance = [
                'accuracy' => $stats['active_model']->accuracy,
                'precision' => $stats['active_model']->precision_score,
                'recall' => $stats['active_model']->recall,
                'f1_score' => $stats['active_model']->f1_score,
            ];
        }

        // Recent activities
        $recentActivities = LearningActivity::with(['student', 'recorder'])
            ->latest()
            ->limit(5)
            ->get();

        // Prediction distribution
        $predictionDistribution = MlPrediction::selectRaw('predicted_label, COUNT(*) as count')
            ->groupBy('predicted_label')
            ->pluck('count', 'predicted_label')
            ->toArray();

        return view('admin.dashboard', compact(
            'stats',
            'categoryDistribution',
            'latestPredictions',
            'modelPerformance',
            'recentActivities',
            'predictionDistribution'
        ));
    }
}
