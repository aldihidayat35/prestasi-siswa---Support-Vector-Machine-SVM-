<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\LearningActivity;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LearningActivityController extends Controller
{
    /**
     * Display a listing of learning activities.
     */
    public function index(Request $request): View
    {
        $query = LearningActivity::with(['student', 'recordedBy']);

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Filter by class
        if ($request->filled('class')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class', $request->class);
            });
        }

        // Filter by period
        if ($request->filled('period')) {
            $query->where('period', $request->period);
        }

        // Filter by mine only
        if ($request->filled('mine') && $request->mine == '1') {
            $query->where('recorded_by', Auth::id());
        }

        $activities = $query->latest()->paginate(15);

        return view('guru.learning-activities.index', compact('activities'));
    }

    /**
     * Show the form for creating a new activity.
     */
    public function create(): View
    {
        $students = Student::where('is_active', true)
            ->orderBy('class')
            ->orderBy('name')
            ->get();

        $todayCount = LearningActivity::where('recorded_by', Auth::id())
            ->whereDate('created_at', today())
            ->count();

        return view('guru.learning-activities.create', compact('students', 'todayCount'));
    }

    /**
     * Store a newly created activity.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'period' => 'required|string|max:10',
            'attendance_rate' => 'required|numeric|min:0|max:100',
            'study_duration' => 'required|numeric|min:0|max:24',
            'task_frequency' => 'required|integer|min:0',
            'discussion_participation' => 'required|numeric|min:0|max:100',
            'media_usage' => 'required|numeric|min:0|max:100',
            'discipline_score' => 'required|numeric|min:0|max:100',
        ]);

        $validated['recorded_by'] = Auth::id();

        $activity = LearningActivity::create($validated);

        // If user clicked "Save & Predict"
        if ($request->has('predict')) {
            return redirect()
                ->route('guru.predictions.create', ['student_id' => $activity->student_id])
                ->with('success', 'Data aktivitas berhasil disimpan. Silakan lakukan prediksi.');
        }

        return redirect()
            ->route('guru.learning-activities.index')
            ->with('success', 'Data aktivitas belajar berhasil disimpan.');
    }

    /**
     * Display the specified activity.
     */
    public function show(LearningActivity $learningActivity): View
    {
        $learningActivity->load(['student', 'recordedBy']);

        return view('guru.learning-activities.show', compact('learningActivity'));
    }

    /**
     * Show the form for editing the activity.
     */
    public function edit(LearningActivity $learningActivity): View|RedirectResponse
    {
        // Only allow editing own records
        if ($learningActivity->recorded_by !== Auth::id()) {
            return redirect()
                ->route('guru.learning-activities.index')
                ->with('error', 'Anda hanya dapat mengedit data yang Anda input.');
        }

        $students = Student::where('is_active', true)
            ->orderBy('class')
            ->orderBy('name')
            ->get();

        return view('guru.learning-activities.edit', compact('learningActivity', 'students'));
    }

    /**
     * Update the specified activity.
     */
    public function update(Request $request, LearningActivity $learningActivity): RedirectResponse
    {
        // Only allow updating own records
        if ($learningActivity->recorded_by !== Auth::id()) {
            return redirect()
                ->route('guru.learning-activities.index')
                ->with('error', 'Anda hanya dapat mengedit data yang Anda input.');
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'period' => 'required|string|max:10',
            'attendance_rate' => 'required|numeric|min:0|max:100',
            'study_duration' => 'required|numeric|min:0|max:24',
            'task_frequency' => 'required|integer|min:0',
            'discussion_participation' => 'required|numeric|min:0|max:100',
            'media_usage' => 'required|numeric|min:0|max:100',
            'discipline_score' => 'required|numeric|min:0|max:100',
        ]);

        $learningActivity->update($validated);

        return redirect()
            ->route('guru.learning-activities.index')
            ->with('success', 'Data aktivitas berhasil diperbarui.');
    }

    /**
     * Remove the specified activity.
     */
    public function destroy(LearningActivity $learningActivity): RedirectResponse
    {
        // Only allow deleting own records
        if ($learningActivity->recorded_by !== Auth::id()) {
            return redirect()
                ->route('guru.learning-activities.index')
                ->with('error', 'Anda hanya dapat menghapus data yang Anda input.');
        }

        $learningActivity->delete();

        return redirect()
            ->route('guru.learning-activities.index')
            ->with('success', 'Data aktivitas berhasil dihapus.');
    }
}
