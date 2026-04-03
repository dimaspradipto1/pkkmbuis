<?php

namespace App\Imports;

use App\Models\HasilTest;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class HasilTestImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $user = User::where('nomor_registrasi', $row['nomor_registrasi'])->first();

            if ($user) {
                // Update or create scores for each module
                $modules = [
                    'm1' => ['pre' => $row['m1_pre'] ?? null, 'post' => $row['m1_post'] ?? null],
                    'm2' => ['pre' => $row['m2_pre'] ?? null, 'post' => $row['m2_post'] ?? null],
                    'm3' => ['pre' => $row['m3_pre'] ?? null, 'post' => $row['m3_post'] ?? null],
                    'm4' => ['pre' => $row['m4_pre'] ?? null, 'post' => $row['m4_post'] ?? null],
                ];

                foreach ($modules as $modKey => $types) {
                    $modNumber = str_replace('m', '', $modKey);
                    
                    foreach ($types as $typeKey => $score) {
                        if ($score !== null) {
                            HasilTest::updateOrCreate(
                                [
                                    'user_id' => $user->id,
                                    'modul'   => $modNumber,
                                    'type'    => ($typeKey == 'pre' ? 'pretest' : 'posttest'),
                                ],
                                [
                                    'skor'    => $score,
                                ]
                            );
                        }
                    }
                }
            }
        }
    }
}
