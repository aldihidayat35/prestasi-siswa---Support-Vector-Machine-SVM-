<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LearningActivity;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class LearningActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LearningActivity::with(['student', 'recorder']);

        // Search by student
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // Filter by class
        if ($request->filled('class')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('class', $request->class);
            });
        }

        // Filter by recorder
        if ($request->filled('recorded_by')) {
            $query->where('recorded_by', $request->recorded_by);
        }

        // Filter by period
        if ($request->filled('period')) {
            $query->where('period', $request->period);
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(15);
        $teachers = User::whereHas('role', fn($q) => $q->where('name', 'guru'))->orderBy('name')->get();

        return view('admin.learning-activities.index', compact('activities', 'teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::orderBy('name')->get();
        $teachers = User::whereHas('role', fn($q) => $q->where('name', 'guru'))->orderBy('name')->get();
        return view('admin.learning-activities.create', compact('students', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'period' => ['required', 'string', 'max:50'],
            'attendance_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'study_duration' => ['required', 'numeric', 'min:0', 'max:24'],
            'task_frequency' => ['required', 'integer', 'min:0', 'max:100'],
            'discussion_participation' => ['required', 'numeric', 'min:0', 'max:100'],
            'media_usage' => ['required', 'numeric', 'min:0', 'max:100'],
            'discipline_score' => ['required', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        // Set recorded_by to current authenticated user
        $validated['recorded_by'] = auth()->id();

        LearningActivity::create($validated);

        return redirect()
            ->route('admin.learning-activities.index')
            ->with('success', 'Aktivitas belajar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LearningActivity $learningActivity)
    {
        $learningActivity->load(['student', 'recorder']);
        return view('admin.learning-activities.show', compact('learningActivity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LearningActivity $learningActivity)
    {
        $learningActivity->load(['student', 'recorder']);
        $students = Student::orderBy('name')->get();
        $teachers = User::whereHas('role', fn($q) => $q->where('name', 'guru'))->orderBy('name')->get();
        return view('admin.learning-activities.edit', compact('learningActivity', 'students', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LearningActivity $learningActivity)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'period' => ['required', 'string', 'max:50'],
            'attendance_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'study_duration' => ['required', 'numeric', 'min:0', 'max:24'],
            'task_frequency' => ['required', 'integer', 'min:0', 'max:100'],
            'discussion_participation' => ['required', 'numeric', 'min:0', 'max:100'],
            'media_usage' => ['required', 'numeric', 'min:0', 'max:100'],
            'discipline_score' => ['required', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $learningActivity->update($validated);

        return redirect()
            ->route('admin.learning-activities.index')
            ->with('success', 'Aktivitas belajar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LearningActivity $learningActivity)
    {
        $learningActivity->delete();

        return redirect()
            ->route('admin.learning-activities.index')
            ->with('success', 'Aktivitas belajar berhasil dihapus.');
    }
}
