<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\MlModel;
use App\Models\MlPrediction;
use App\Models\Student;
use App\Services\MLService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PredictionController extends Controller
{
    protected MLService $mlService;

    public function __construct(MLService $mlService)
    {
        $this->mlService = $mlService;
    }

    /**
     * Display a listing of predictions.
     */
    public function index(Request $request): View
    {
        $query = MlPrediction::with(['student', 'mlModel', 'predictedBy']);

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by class
        if ($request->filled('class')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class', $request->class);
            });
        }

        // Filter by result
        if ($request->filled('result')) {
            $query->where('predicted_label', $request->result);
        }

        $predictions = $query->latest()->paginate(15);

        // Stats
        $stats = [
            'total' => MlPrediction::count(),
            'tinggi' => MlPrediction::where('predicted_label', 'Tinggi')->count(),
            'sedang' => MlPrediction::where('predicted_label', 'Sedang')->count(),
            'rendah' => MlPrediction::where('predicted_label', 'Rendah')->count(),
        ];

        return view('guru.predictions.index', compact('predictions', 'stats'));
    }

    /**
     * Show the form for creating a new prediction.
     */
    public function create(): View
    {
        $students = Student::where('is_active', true)
            ->orderBy('class')
            ->orderBy('name')
            ->get();

        $activeModel = MlModel::where('is_active', true)->first();

        return view('guru.predictions.create', compact('students', 'activeModel'));
    }

    /**
     * Store a newly created prediction.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'attendance_rate' => 'required|numeric|min:0|max:100',
            'study_duration' => 'required|numeric|min:0|max:24',
            'task_frequency' => 'required|integer|min:0',
            'discussion_participation' => 'required|numeric|min:0|max:100',
            'media_usage' => 'required|numeric|min:0|max:100',
            'discipline_score' => 'required|numeric|min:0|max:100',
        ]);

        // Get active model
        $activeModel = MlModel::where('is_active', true)->first();
        if (!$activeModel) {
            return back()->with('error', 'Tidak ada model aktif. Hubungi admin.');
        }

        // Prepare features
        $features = [
            'attendance_rate' => $validated['attendance_rate'],
            'study_duration' => $validated['study_duration'],
            'task_frequency' => $validated['task_frequency'],
            'discussion_participation' => $validated['discussion_participation'],
            'media_usage' => $validated['media_usage'],
            'discipline_score' => $validated['discipline_score'],
        ];

        try {
            // Call ML service
            $result = $this->mlService->predict($features);

            if (!$result['success']) {
                return back()->with('error', 'Gagal melakukan prediksi: ' . ($result['message'] ?? 'Unknown error'));
            }

            // Generate recommendation
            $recommendation = $this->generateRecommendation($result['prediction'], $features);

            // Save prediction
            $prediction = MlPrediction::create([
                'student_id' => $validated['student_id'],
                'model_id' => $activeModel->id,
                'input_features' => $features,
                'predicted_label' => $result['prediction'],
                'confidence_score' => $result['confidence'] ?? 0.8,
                'recommendation' => $recommendation,
                'predicted_by' => Auth::id(),
            ]);

            return redirect()
                ->route('guru.predictions.show', $prediction)
                ->with('success', 'Prediksi berhasil! Hasil: ' . $result['prediction']);

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified prediction.
     */
    public function show(MlPrediction $prediction): View
    {
        $prediction->load(['student', 'mlModel', 'predictedBy']);

        return view('guru.predictions.show', compact('prediction'));
    }

    /**
     * Generate recommendation based on prediction result.
     */
    private function generateRecommendation(string $label, array $features): string
    {
        $recommendations = [];

        if ($label === 'Rendah') {
            if ($features['attendance_rate'] < 80) {
                $recommendations[] = 'Tingkatkan kehadiran di kelas';
            }
            if ($features['study_duration'] < 3) {
                $recommendations[] = 'Tambah durasi belajar mandiri';
            }
            if ($features['discipline_score'] < 70) {
                $recommendations[] = 'Perbaiki kedisiplinan';
            }
            if ($features['discussion_participation'] < 60) {
                $recommendations[] = 'Lebih aktif dalam diskusi kelas';
            }
        } elseif ($label === 'Sedang') {
            if ($features['attendance_rate'] < 90) {
                $recommendations[] = 'Pertahankan dan tingkatkan kehadiran';
            }
            if ($features['task_frequency'] < 20) {
                $recommendations[] = 'Tingkatkan frekuensi pengerjaan tugas';
            }
            $recommendations[] = 'Terus pertahankan usaha belajar';
        } else {
            $recommendations[] = 'Pertahankan prestasi yang baik';
            $recommendations[] = 'Dapat membantu teman yang membutuhkan';
        }

        return implode('. ', $recommendations) . '.';
    }
}
