<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicScore;
use App\Models\Student;
use Illuminate\Http\Request;

class AcademicScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AcademicScore::with('student');

        // Search by student name or NISN
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

        // Filter by semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Filter by academic year
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        $scores = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.academic-scores.index', compact('scores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::orderBy('name')->get();
        return view('admin.academic-scores.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'semester' => ['required', 'integer', 'min:1', 'max:6'],
            'academic_year' => ['required', 'string', 'max:10'],
            'math_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'indonesian_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'english_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'physics_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'chemistry_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'biology_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'average_score' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        AcademicScore::create($validated);

        return redirect()
            ->route('admin.academic-scores.index')
            ->with('success', 'Nilai akademik berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicScore $academicScore)
    {
        $academicScore->load('student');
        return view('admin.academic-scores.show', compact('academicScore'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicScore $academicScore)
    {
        $academicScore->load('student');
        $students = Student::orderBy('name')->get();
        return view('admin.academic-scores.edit', compact('academicScore', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AcademicScore $academicScore)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'semester' => ['required', 'integer', 'min:1', 'max:6'],
            'academic_year' => ['required', 'string', 'max:10'],
            'math_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'indonesian_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'english_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'physics_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'chemistry_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'biology_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'average_score' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $academicScore->update($validated);

        return redirect()
            ->route('admin.academic-scores.index')
            ->with('success', 'Nilai akademik berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicScore $academicScore)
    {
        $academicScore->delete();

        return redirect()
            ->route('admin.academic-scores.index')
            ->with('success', 'Nilai akademik berhasil dihapus.');
    }
}
