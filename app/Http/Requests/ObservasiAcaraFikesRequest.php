<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ObservasiAcaraFikesRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'waktu_runddown' => 'nullable|string|max:255',
            'waktu_realisasi' => 'nullable|string|max:255',
            'kegiatan' => 'required|string',
            'aspek_observasi' => 'nullable|array',
            'aspek_observasi.*' => 'nullable|string',
            'skala' => 'nullable|integer|min:1|max:5',
            'catatan' => 'nullable|string',
            'link_dokumen' => 'nullable|array',
            'link_dokumen.*' => 'nullable|string|max:2048',
        ];
    }
}
