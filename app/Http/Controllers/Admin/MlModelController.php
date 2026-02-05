<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrainModelRequest;
use App\Models\EvaluationResult;
use App\Models\MlDataset;
use App\Models\MlModel;
use App\Services\MLService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Exception;

class MlModelController extends Controller
{
    protected MLService $mlService;

    public function __construct(MLService $mlService)
    {
        $this->mlService = $mlService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $models = MlModel::with(['user', 'latestEvaluation'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $activeModel = MlModel::where('is_active', true)->first();

        return view('admin.ml-models.index', compact('models', 'activeModel'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $kernels = MlModel::KERNELS;
        $datasetStats = $this->mlService->getDatasetStats();

        return view('admin.ml-models.create', compact('kernels', 'datasetStats'));
    }

    /**
     * Store a newly created resource in storage (Train Model).
     */
    public function store(TrainModelRequest $request): RedirectResponse
    {
        try {
            // Check if we have enough data
            $datasetCount = MlDataset::count();
            if ($datasetCount < 10) {
                return back()->with('error', 'Minimal 10 data training diperlukan untuk melatih model.');
            }

            // Train model via PHP SVM
            $result = $this->mlService->trainModel($request->validated());

            if (!$result['success']) {
                return back()
                    ->withInput()
                    ->with('error', 'Gagal melatih model: ' . ($result['error'] ?? 'Unknown error'));
            }

            // Save model to database
            $model = MlModel::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'kernel' => $result['parameters']['kernel'],
                'c_parameter' => $result['parameters']['C'],
                'gamma_parameter' => $result['parameters']['gamma'],
                'degree' => $result['parameters']['degree'],
                'model_path' => $result['model_path'],
                'scaler_path' => null,
                'training_samples' => $result['dataset_info']['training_samples'],
                'testing_samples' => $result['dataset_info']['testing_samples'],
                'test_size' => $request->test_size ?? 0.2,
                'accuracy' => $result['evaluation']['accuracy'],
                'precision_score' => $result['evaluation']['precision'],
                'recall' => $result['evaluation']['recall'],
                'f1_score' => $result['evaluation']['f1_score'],
                'training_date' => now(),
                'is_active' => false,
                'notes' => $request->notes,
            ]);

            // Save evaluation results
            EvaluationResult::create([
                'model_id' => $model->id,
                'test_size' => $request->test_size ?? 0.2,
                'random_state' => $request->random_state ?? 42,
                'accuracy' => $result['evaluation']['accuracy'],
                'precision_score' => $result['evaluation']['precision'],
                'recall' => $result['evaluation']['recall'],
                'f1_score' => $result['evaluation']['f1_score'],
                'classification_report' => $result['evaluation']['class_metrics'] ?? null,
                'confusion_matrix' => $result['evaluation']['confusion_matrix'] ?? null,
                'cross_validation_scores' => $result['cross_validation']['cv_scores'] ?? null,
                'cv_mean' => $result['cross_validation']['cv_mean'] ?? null,
                'cv_std' => $result['cross_validation']['cv_std'] ?? null,
            ]);

            return redirect()
                ->route('admin.ml-models.show', $model)
                ->with('success', 'Model SVM berhasil dilatih! Akurasi: ' . number_format($result['evaluation']['accuracy'] * 100, 2) . '%');

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal melatih model: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MlModel $mlModel): View
    {
        $model = $mlModel;
        $model->load(['user', 'evaluationResults', 'predictions.student']);

        $evaluation = $model->latestEvaluation;

        return view('admin.ml-models.show', compact('model', 'evaluation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MlModel $mlModel): View
    {
        return view('admin.ml-models.edit', compact('mlModel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MlModel $mlModel): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $mlModel->update($request->only(['name', 'notes']));

        return redirect()
            ->route('admin.ml-models.show', $mlModel)
            ->with('success', 'Model berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MlModel $mlModel): RedirectResponse
    {
        // Delete model file
        if ($mlModel->model_path && Storage::exists($mlModel->model_path)) {
            Storage::delete($mlModel->model_path);
        }
        if ($mlModel->scaler_path && Storage::exists($mlModel->scaler_path)) {
            Storage::delete($mlModel->scaler_path);
        }

        $mlModel->delete();

        return redirect()
            ->route('admin.ml-models.index')
            ->with('success', 'Model berhasil dihapus.');
    }

    /**
     * Set model as active for predictions.
     */
    public function setActive(MlModel $mlModel): RedirectResponse
    {
        // Deactivate all other models
        MlModel::where('id', '!=', $mlModel->id)->update(['is_active' => false]);

        // Activate this model
        $mlModel->update(['is_active' => true]);

        return back()->with('success', 'Model berhasil diaktifkan untuk prediksi.');
    }

    /**
     * Retrain the model with same parameters.
     */
    public function retrain(MlModel $mlModel): RedirectResponse
    {
        try {
            $result = $this->mlService->trainModel([
                'kernel' => $mlModel->kernel,
                'c_parameter' => $mlModel->c_parameter,
                'gamma_parameter' => $mlModel->gamma_parameter,
                'degree' => $mlModel->degree,
                'test_size' => $mlModel->test_size,
            ]);

            // Update model
            $mlModel->update([
                'model_path' => $result['model_path'],
                'scaler_path' => $result['scaler_path'] ?? null,
                'training_samples' => $result['training_samples'],
                'testing_samples' => $result['testing_samples'],
                'accuracy' => $result['accuracy'],
                'precision_score' => $result['precision'],
                'recall' => $result['recall'],
                'f1_score' => $result['f1_score'],
                'training_date' => now(),
                'version' => $this->incrementVersion($mlModel->version),
            ]);

            // Save new evaluation results
            EvaluationResult::create([
                'model_id' => $mlModel->id,
                'test_size' => $mlModel->test_size,
                'accuracy' => $result['accuracy'],
                'precision_score' => $result['precision'],
                'recall' => $result['recall'],
                'f1_score' => $result['f1_score'],
                'classification_report' => $result['classification_report'] ?? null,
                'confusion_matrix' => $result['confusion_matrix'] ?? null,
            ]);

            return back()->with('success', 'Model berhasil dilatih ulang! Akurasi baru: ' . number_format($result['accuracy'] * 100, 2) . '%');

        } catch (Exception $e) {
            return back()->with('error', 'Gagal melatih ulang model: ' . $e->getMessage());
        }
    }

    /**
     * Compare models performance.
     */
    public function compare(Request $request): View
    {
        $modelIds = $request->input('models', []);
        $models = MlModel::with('latestEvaluation')
            ->whereIn('id', $modelIds)
            ->get();

        return view('admin.ml-models.compare', compact('models'));
    }

    /**
     * Check ML API health.
     */
    public function checkHealth(): JsonResponse
    {
        $healthy = $this->mlService->checkHealth();

        return response()->json([
            'status' => $healthy ? 'healthy' : 'unhealthy',
            'message' => $healthy ? 'ML API is running' : 'ML API is not available',
        ]);
    }

    /**
     * Increment version string.
     */
    protected function incrementVersion(string $version): string
    {
        $parts = explode('.', $version);
        $minor = (int) ($parts[1] ?? 0);
        return $parts[0] . '.' . ($minor + 1);
    }
}
