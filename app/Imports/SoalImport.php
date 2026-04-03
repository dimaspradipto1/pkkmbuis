<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class SoalImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    protected $modelClass;

    public function __construct($modelClass)
    {
        $this->modelClass = $modelClass;
    }

    public function model(array $row)
    {
        // Expected headers: soal, pilihan_a, pilihan_b, pilihan_c, pilihan_d, jawaban
        return new $this->modelClass([
            'soal'      => $row['soal'],
            'pilihan_a' => $row['pilihan_a'],
            'pilihan_b' => $row['pilihan_b'],
            'pilihan_c' => $row['pilihan_c'],
            'pilihan_d' => $row['pilihan_d'],
            'jawaban'   => strtoupper($row['jawaban']),
        ]);
    }
}
