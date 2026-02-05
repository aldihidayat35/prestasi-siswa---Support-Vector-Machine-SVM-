<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrainModelRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'kernel' => ['required', 'in:linear,poly,rbf,sigmoid'],
            'c_parameter' => ['required', 'numeric', 'min:0.0001', 'max:1000'],
            'gamma_parameter' => ['nullable', 'string'], // 'scale', 'auto', or numeric
            'degree' => ['nullable', 'integer', 'min:1', 'max:10'],
            'test_size' => ['required', 'numeric', 'min:0.1', 'max:0.5'],
            'random_state' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Nama Model',
            'kernel' => 'Jenis Kernel SVM',
            'c_parameter' => 'Parameter C (Regularization)',
            'gamma_parameter' => 'Parameter Gamma',
            'degree' => 'Degree (untuk Polynomial)',
            'test_size' => 'Ukuran Data Testing',
            'random_state' => 'Random State',
        ];
    }
}
