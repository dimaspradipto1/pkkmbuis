<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluasiFebRequest extends FormRequest
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
        $questions = \App\Models\EvaluasiFeb::questions();
        $rules = [];
        foreach ($questions as $key => $text) {
            $rules[$key] = 'required|integer|in:1,2,3,4';
        }
        $rules['saran_inputs'] = 'nullable|array';
        $rules['saran_inputs.*'] = 'nullable|string';
        $rules['saran_panitia'] = 'nullable|string';
        $rules['saran_dan_masukan'] = 'nullable|string';
        return $rules;
    }
}
