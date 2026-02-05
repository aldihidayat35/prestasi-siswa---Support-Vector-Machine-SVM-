<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLearningActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'attendance_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'study_duration' => ['required', 'numeric', 'min:0', 'max:24'],
            'task_frequency' => ['required', 'integer', 'min:0'],
            'discussion_participation' => ['required', 'numeric', 'min:0', 'max:100'],
            'media_usage' => ['required', 'numeric', 'min:0', 'max:100'],
            'discipline_score' => ['required', 'numeric', 'min:0', 'max:100'],
            'period' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'student_id' => 'Siswa',
            'attendance_rate' => 'Tingkat Kehadiran',
            'study_duration' => 'Durasi Belajar Harian',
            'task_frequency' => 'Frekuensi Mengerjakan Tugas',
            'discussion_participation' => 'Partisipasi Diskusi',
            'media_usage' => 'Penggunaan Media Pembelajaran',
            'discipline_score' => 'Skor Kedisiplinan',
            'period' => 'Periode',
        ];
    }
}
