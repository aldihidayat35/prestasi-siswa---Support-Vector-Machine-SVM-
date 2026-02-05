<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\LearningActivity;
use App\Models\MlPrediction;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display guru dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Statistics
        $stats = [
            'totalStudents' => Student::where('is_active', true)->count(),
            'totalActivities' => LearningActivity::count(),
            'myActivities' => LearningActivity::where('recorded_by', $user->id)->count(),
            'todayPredictions' => MlPrediction::whereDate('created_at', today())->count(),
            'byClass' => Student::where('is_active', true)
                ->selectRaw('class, COUNT(*) as count')
                ->groupBy('class')
                ->pluck('count', 'class')
                ->toArray(),
        ];

        // My recent activities
        $recentActivities = LearningActivity::with('student')
            ->where('recorded_by', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        // Recent predictions
        $recentPredictions = MlPrediction::with(['student', 'mlModel'])
            ->latest()
            ->limit(5)
            ->get();

        return view('guru.dashboard', compact(
            'stats',
            'recentActivities',
            'recentPredictions'
        ));
    }
}
