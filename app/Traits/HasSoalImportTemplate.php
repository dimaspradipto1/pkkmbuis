<?php

namespace App\Traits;

use App\Imports\SoalImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

trait HasSoalImportTemplate
{
    /**
     * Import soal from excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ], [
            'file.required' => 'File harus diupload.',
            'file.mimes' => 'Format file harus xlsx, xls, atau csv.',
        ]);

        try {
            Excel::import(new SoalImport($this->modelClass), $request->file('file'));
            Alert::success('Berhasil!', 'Soal berhasil diimport.')->toToast()->autoClose(4000);
        } catch (\Exception $e) {
            Alert::error('Gagal!', 'Terjadi kesalahan saat mengimport data: ' . $e->getMessage())->toToast()->autoClose(4000);
        }

        return redirect()->back();
    }

    /**
     * Download template soal excel.
     */
    public function downloadTemplate()
    {
        $headers = ['soal', 'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'jawaban'];
        $data = [
            ['Ibu kota Indonesia adalah?', 'Jakarta', 'Bandung', 'Surabaya', 'Medan', 'A'],
        ];

        return Excel::download(new class($headers, $data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $headers;
            private $data;
            public function __construct($headers, $data) { $this->headers = $headers; $this->data = $data; }
            public function headings(): array { return $this->headers; }
            public function array(): array { return $this->data; }
        }, 'template_soal.xlsx');
    }

    /**
     * Export soal to excel.
     */
    public function export()
    {
        $model = new $this->modelClass;
        $filename = str_replace(['App\\Models\\', 'Soal'], '', $this->modelClass) . '_Export_' . date('YmdHis') . '.xlsx';
        
        return Excel::download(new class($this->modelClass) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $modelClass;
            public function __construct($modelClass) { $this->modelClass = $modelClass; }
            public function collection() { 
                return ($this->modelClass)::all(['soal', 'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'jawaban']); 
            }
            public function headings(): array { 
                return ['soal', 'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'jawaban']; 
            }
        }, $filename);
    }
}
