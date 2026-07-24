<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluasiPelayanansistemAkademikRequest extends FormRequest
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
            'user_id' => 'sometimes|exists:users,id',
            'q1'  => 'required|integer|min:1|max:4',
            'q2'  => 'required|integer|min:1|max:4',
            'q3'  => 'required|integer|min:1|max:4',
            'q4'  => 'required|integer|min:1|max:4',
            'q5'  => 'required|integer|min:1|max:4',
            'q6'  => 'required|integer|min:1|max:4',
            'q7'  => 'required|integer|min:1|max:4',
            'q8'  => 'required|integer|min:1|max:4',
            'q9'  => 'required|integer|min:1|max:4',
            'q10' => 'required|integer|min:1|max:4',
            'q11' => 'required|integer|min:1|max:4',
            'q12' => 'required|integer|min:1|max:4',
            'q13' => 'required|integer|min:1|max:4',
            'saran_dan_masukan' => 'nullable|string',
        ];
    }
}
