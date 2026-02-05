<?php

namespace App\Console\Commands;

use App\Models\MlDataset;
use App\Models\MlModel;
use App\Models\EvaluationResult;
use App\Services\MLService;
use Illuminate\Console\Command;

class TrainSvmModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ml:train
                            {--kernel=rbf : Kernel type (linear, rbf, poly, sigmoid)}
                            {--C=1.0 : Regularization parameter C}
                            {--gamma=0 : Gamma parameter (0=auto)}
                            {--test-size=0.2 : Test set proportion}
                            {--name= : Model name}
                            {--activate : Set as active model after training}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Train an SVM model using the dataset';

    protected MLService $mlService;

    public function __construct(MLService $mlService)
    {
        parent::__construct();
        $this->mlService = $mlService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Training SVM Model...');
        $this->newLine();

        // Check dataset count
        $datasetCount = MlDataset::count();
        $this->info("📊 Dataset: {$datasetCount} samples");

        if ($datasetCount < 10) {
            $this->error('❌ Minimal 10 data training diperlukan!');
            return 1;
        }

        // Get parameters
        $params = [
            'kernel' => $this->option('kernel'),
            'c_parameter' => floatval($this->option('C')),
            'gamma_parameter' => floatval($this->option('gamma')),
            'test_size' => floatval($this->option('test-size')),
        ];

        $this->table(
            ['Parameter', 'Value'],
            [
                ['Kernel', $params['kernel']],
                ['C', $params['c_parameter']],
                ['Gamma', $params['gamma_parameter'] == 0 ? 'auto' : $params['gamma_parameter']],
                ['Test Size', $params['test_size'] * 100 . '%'],
            ]
        );

        $this->newLine();
        $this->info('🔄 Training in progress...');

        // Train model
        $result = $this->mlService->trainModel($params);

        if (!$result['success']) {
            $this->error('❌ Training failed: ' . ($result['error'] ?? 'Unknown error'));
            return 1;
        }

        // Save to database
        $modelName = $this->option('name') ?? 'SVM Model ' . now()->format('Y-m-d H:i');

        $model = MlModel::create([
            'user_id' => 1, // Admin
            'name' => $modelName,
            'kernel' => $result['parameters']['kernel'],
            'c_parameter' => $result['parameters']['C'],
            'gamma_parameter' => $result['parameters']['gamma'],
            'degree' => $result['parameters']['degree'],
            'model_path' => $result['model_path'],
            'scaler_path' => null,
            'training_samples' => $result['dataset_info']['training_samples'],
            'testing_samples' => $result['dataset_info']['testing_samples'],
            'test_size' => $params['test_size'],
            'accuracy' => $result['evaluation']['accuracy'],
            'precision_score' => $result['evaluation']['precision'],
            'recall' => $result['evaluation']['recall'],
            'f1_score' => $result['evaluation']['f1_score'],
            'training_date' => now(),
            'is_active' => false,
            'notes' => 'Trained via CLI',
        ]);

        // Save evaluation
        EvaluationResult::create([
            'model_id' => $model->id,
            'test_size' => $params['test_size'],
            'random_state' => 42,
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

        $this->newLine();
        $this->info('✅ Training completed successfully!');
        $this->newLine();

        // Show results
        $this->table(
            ['Metric', 'Value'],
            [
                ['Model ID', $model->id],
                ['Training Time', round($result['training_time'], 2) . 's'],
                ['Accuracy', number_format($result['evaluation']['accuracy'] * 100, 2) . '%'],
                ['Precision', number_format($result['evaluation']['precision'] * 100, 2) . '%'],
                ['Recall', number_format($result['evaluation']['recall'] * 100, 2) . '%'],
                ['F1-Score', number_format($result['evaluation']['f1_score'] * 100, 2) . '%'],
                ['Support Vectors', $result['support_vectors']],
                ['CV Mean', number_format($result['cross_validation']['cv_mean'] * 100, 2) . '%'],
                ['CV Std', number_format($result['cross_validation']['cv_std'] * 100, 2) . '%'],
            ]
        );

        // Activate if requested
        if ($this->option('activate')) {
            MlModel::where('id', '!=', $model->id)->update(['is_active' => false]);
            $model->update(['is_active' => true]);
            $this->newLine();
            $this->info('🟢 Model activated for predictions!');
        }

        $this->newLine();
        $this->info("📁 Model saved: {$result['model_filename']}");

        return 0;
    }
}
