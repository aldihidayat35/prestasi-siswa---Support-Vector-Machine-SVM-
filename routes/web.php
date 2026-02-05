<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\LearningActivityController as AdminLearningActivityController;
use App\Http\Controllers\Admin\AcademicScoreController;
use App\Http\Controllers\Admin\DatasetController;
use App\Http\Controllers\Admin\MlModelController;
use App\Http\Controllers\Admin\MlPredictionController as AdminPredictionController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\LearningActivityController as GuruLearningActivityController;
use App\Http\Controllers\Guru\PredictionController as GuruPredictionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
});

Route::post('logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard Redirect (based on role)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', UserController::class);

    // Student Management
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('students/export', [StudentController::class, 'export'])->name('students.export');
    Route::resource('students', StudentController::class);

    // Learning Activities
    Route::resource('learning-activities', AdminLearningActivityController::class);

    // Academic Scores
    Route::resource('academic-scores', AcademicScoreController::class);

    // Dataset Management
    Route::get('datasets', [DatasetController::class, 'index'])->name('datasets.index');
    Route::post('datasets/generate', [DatasetController::class, 'generate'])->name('datasets.generate');
    Route::post('datasets/split', [DatasetController::class, 'split'])->name('datasets.split');
    Route::delete('datasets/clear', [DatasetController::class, 'clear'])->name('datasets.clear');

    // ML Models
    Route::get('svm-explanation', function () {
        return view('admin.svm-explanation');
    })->name('svm-explanation');

    // Tutorial
    Route::get('tutorial', function () {
        return view('admin.tutorial.index');
    })->name('tutorial');

    Route::post('ml-models/{mlModel}/set-active', [MlModelController::class, 'setActive'])->name('ml-models.set-active');
    Route::post('ml-models/{mlModel}/retrain', [MlModelController::class, 'retrain'])->name('ml-models.retrain');
    Route::get('ml-models/compare', [MlModelController::class, 'compare'])->name('ml-models.compare');
    Route::get('ml-models/health', [MlModelController::class, 'checkHealth'])->name('ml-models.health');
    Route::resource('ml-models', MlModelController::class);

    // Predictions
    Route::get('predictions/batch', [AdminPredictionController::class, 'batchForm'])->name('predictions.batch');
    Route::post('predictions/batch', [AdminPredictionController::class, 'batchPredict'])->name('predictions.batch.store');
    Route::get('predictions/export', [AdminPredictionController::class, 'export'])->name('predictions.export');
    Route::resource('predictions', AdminPredictionController::class);
});

/*
|--------------------------------------------------------------------------
| Guru Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'guru'])->prefix('guru')->name('guru.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');

    // Learning Activities (input data aktivitas belajar)
    Route::resource('learning-activities', GuruLearningActivityController::class);

    // Predictions
    Route::get('predictions', [GuruPredictionController::class, 'index'])->name('predictions.index');
    Route::get('predictions/create', [GuruPredictionController::class, 'create'])->name('predictions.create');
    Route::post('predictions', [GuruPredictionController::class, 'store'])->name('predictions.store');
    Route::get('predictions/{prediction}', [GuruPredictionController::class, 'show'])->name('predictions.show');
});

/*
|--------------------------------------------------------------------------
| API Routes for AJAX
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('students/search', function (\Illuminate\Http\Request $request) {
        $query = $request->get('q');
        return \App\Models\Student::where('name', 'like', "%{$query}%")
            ->orWhere('nis', 'like', "%{$query}%")
            ->where('is_active', true)
            ->limit(10)
            ->get(['id', 'nis', 'name', 'class']);
    })->name('api.students.search');

    Route::get('students/{student}/activities', function (\App\Models\Student $student) {
        return $student->learningActivities()->latest()->limit(5)->get();
    })->name('api.students.activities');

    Route::get('students/{student}/latest-activity', function (\App\Models\Student $student) {
        $activity = $student->learningActivities()->latest()->first();
        if ($activity) {
            return response()->json([
                'success' => true,
                'activity' => $activity
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'No activity found'
        ]);
    })->name('api.students.latest-activity');
});
