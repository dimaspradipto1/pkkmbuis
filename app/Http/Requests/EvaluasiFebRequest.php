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
        return [
            'user_id'                  => 'sometimes|exists:users,id',

            // Penilaian Evaluasi Pemateri
            'q1'                       => 'required|integer|min:1|max:4',
            'q2'                       => 'required|integer|min:1|max:4',
            'q3'                       => 'required|integer|min:1|max:4',
            'q4'                       => 'required|integer|min:1|max:4',
            'q5'                       => 'required|integer|min:1|max:4',
            'q6'                       => 'required|integer|min:1|max:4',
            'q7'                       => 'required|integer|min:1|max:4',
            'q8'                       => 'required|integer|min:1|max:4',

            // Penilaian Evaluasi Isi Materi
            'q9'                       => 'required|integer|min:1|max:4',
            'q10'                      => 'required|integer|min:1|max:4',
            'q11'                      => 'required|integer|min:1|max:4',
            'q12'                      => 'required|integer|min:1|max:4',
            'q13'                      => 'required|integer|min:1|max:4',

            // Saran dan Masukan
            'saran_dekan'              => 'nullable|string',
            'saran_wakil_dekan_1'      => 'nullable|string',
            'saran_wakil_dekan_2'      => 'nullable|string',
            'saran_upmi'               => 'nullable|string',
            'saran_uppm'               => 'nullable|string',
            'saran_prodi_akuntansi'    => 'nullable|string',
            'saran_prodi_s1_manajemen' => 'nullable|string',
            'saran_prodi_s2_manajemen' => 'nullable|string',
            'saran_hima_feb'           => 'nullable|string',

            // Fasilitas dan Penyelenggara
            'q14'                      => 'required|integer|min:1|max:4',
            'q15'                      => 'required|integer|min:1|max:4',
            'q16'                      => 'required|integer|min:1|max:4',
            'q17'                      => 'required|integer|min:1|max:4',
            'q18'                      => 'required|integer|min:1|max:4',

            // Sarana dan Prasarana
            'q19'                      => 'required|integer|min:1|max:4',
            'q20'                      => 'required|integer|min:1|max:4',
            'q21'                      => 'required|integer|min:1|max:4',
            'q22'                      => 'required|integer|min:1|max:4',

            // Saran dan Masukan untuk Panitia
            'saran_panitia'            => 'nullable|string',
        ];
    }
}
