<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PredictRequest;
use App\Models\MlModel;
use App\Models\MlPrediction;
use App\Models\Student;
use App\Services\MLService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Exception;

class MlPredictionController extends Controller
{
    protected MLService $mlService;

    public function __construct(MLService $mlService)
    {
        $this->mlService = $mlService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = MlPrediction::with(['student', 'mlModel', 'predictedBy']);

        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by predicted label
        if ($request->filled('result')) {
            $query->where('predicted_label', $request->result);
        }

        // Filter by model
        if ($request->filled('model_id')) {
            $query->where('model_id', $request->model_id);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $predictions = $query->orderBy('created_at', 'desc')->paginate(15);
        $models = MlModel::orderBy('created_at', 'desc')->get();

        // Statistics
        $stats = [
            'total' => MlPrediction::count(),
            'rendah' => MlPrediction::where('predicted_label', 'Rendah')->count(),
            'sedang' => MlPrediction::where('predicted_label', 'Sedang')->count(),
            'tinggi' => MlPrediction::where('predicted_label', 'Tinggi')->count(),
        ];

        return view('admin.predictions.index', compact('predictions', 'models', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $students = Student::where('is_active', true)->orderBy('name')->get();
        $activeModel = MlModel::active()->first();

        return view('admin.predictions.create', compact('students', 'activeModel'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PredictRequest $request): RedirectResponse
    {
        try {
            $activeModel = MlModel::active()->first();

            if (!$activeModel) {
                return back()->with('error', 'Tidak ada model aktif. Silakan aktifkan model terlebih dahulu.');
            }

            $features = $request->getFeatures();

            // Make prediction via PHP SVM
            $result = $this->mlService->predict($features, $activeModel->model_path);

            if (!$result['success']) {
                return back()->with('error', 'Gagal melakukan prediksi: ' . ($result['error'] ?? 'Unknown error'));
            }

            // Generate recommendation
            $recommendation = MlPrediction::generateRecommendation($result['prediction'], $features);

            // Save prediction
            $prediction = MlPrediction::create([
                'student_id' => $request->student_id,
                'model_id' => $activeModel->id,
                'predicted_by' => Auth::id(),
                'input_features' => $features,
                'predicted_label' => $result['prediction'],
                'confidence_score' => $result['confidence'] ?? null,
                'probability_scores' => $result['probabilities'] ?? null,
                'recommendation' => $recommendation,
            ]);

            return redirect()
                ->route('admin.predictions.show', $prediction)
                ->with('success', 'Prediksi berhasil! Hasil: ' . $result['prediction']);

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal melakukan prediksi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MlPrediction $prediction): View
    {
        $prediction->load(['student', 'mlModel', 'predictedBy']);

        return view('admin.predictions.show', compact('prediction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MlPrediction $prediction): View
    {
        return view('admin.predictions.edit', compact('prediction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MlPrediction $prediction): RedirectResponse
    {
        $request->validate([
            'actual_label' => ['nullable', 'in:Rendah,Sedang,Tinggi'],
            'recommendation' => ['nullable', 'string'],
        ]);

        $isCorrect = null;
        if ($request->filled('actual_label')) {
            $isCorrect = $request->actual_label === $prediction->predicted_label;
        }

        $prediction->update([
            'actual_label' => $request->actual_label,
            'is_correct' => $isCorrect,
            'recommendation' => $request->recommendation,
        ]);

        return redirect()
            ->route('admin.predictions.show', $prediction)
            ->with('success', 'Prediksi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MlPrediction $prediction): RedirectResponse
    {
        $prediction->delete();

        return redirect()
            ->route('admin.predictions.index')
            ->with('success', 'Prediksi berhasil dihapus.');
    }

    /**
     * Show batch prediction form.
     */
    public function batchForm(): View
    {
        $students = Student::with('learningActivities')
            ->where('is_active', true)
            ->orderBy('class')
            ->orderBy('name')
            ->get();

        $activeModel = MlModel::where('is_active', true)->first();

        return view('admin.predictions.batch', compact('students', 'activeModel'));
    }

    /**
     * Batch predict for multiple students.
     */
    public function batchPredict(Request $request): RedirectResponse
    {
        $request->validate([
            'student_ids' => ['required', 'array'],
            'student_ids.*' => ['exists:students,id'],
        ]);

        try {
            $activeModel = MlModel::active()->first();

            if (!$activeModel) {
                return back()->with('error', 'Tidak ada model aktif.');
            }

            $students = Student::with(['learningActivities' => fn($q) => $q->latest()->limit(1)])
                ->whereIn('id', $request->student_ids)
                ->get();

            $count = 0;
            foreach ($students as $student) {
                $latestActivity = $student->learningActivities->first();
                if (!$latestActivity) {
                    continue;
                }

                $features = [
                    'attendance_rate' => $latestActivity->attendance_rate,
                    'study_duration' => $latestActivity->study_duration,
                    'task_frequency' => $latestActivity->task_frequency,
                    'discussion_participation' => $latestActivity->discussion_participation,
                    'media_usage' => $latestActivity->media_usage,
                    'discipline_score' => $latestActivity->discipline_score,
                ];

                $result = $this->mlService->predict($features);

                if (!$result['success']) {
                    continue;
                }

                $recommendation = MlPrediction::generateRecommendation($result['prediction'], $features);

                MlPrediction::create([
                    'student_id' => $student->id,
                    'model_id' => $activeModel->id,
                    'predicted_by' => Auth::id(),
                    'input_features' => $features,
                    'predicted_label' => $result['prediction'],
                    'confidence_score' => $result['confidence'] ?? null,
                    'probability_scores' => $result['probabilities'] ?? null,
                    'recommendation' => $recommendation,
                ]);

                $count++;
            }

            return redirect()
                ->route('admin.predictions.index')
                ->with('success', "Berhasil melakukan prediksi untuk {$count} siswa.");

        } catch (Exception $e) {
            return back()->with('error', 'Gagal melakukan batch prediksi: ' . $e->getMessage());
        }
    }

    /**
     * Export predictions to Excel.
     */
    public function export(Request $request)
    {
        // TODO: Implement export with Laravel Excel

        return redirect()
            ->route('admin.predictions.index')
            ->with('info', 'Fitur export sedang dalam pengembangan.');
    }
}
