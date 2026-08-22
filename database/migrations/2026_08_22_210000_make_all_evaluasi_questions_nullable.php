<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'evaluasi_pengenalan_wawasan_ibnu_sinas',
            'evaluasi_pelayanan_kemahasiswaan_pusat_prestasis',
            'evaluasi_pelayanansistem_akademiks',
            'evaluasi_pelayanansistem_administrasi_keuangans',
            'evaluasi_kehidupan_berbangsa_bela_negaras',
            'evaluasi_sistem_pendidikan_tinggi_indonesias',
            'evaluasi_era_digital_revolusi_industris',
            'evaluasi_pengenalan_k3ls',
            'evaluasi_perpustakaans',
            'evaluasi_ika_uis',
            'evaluasi_kewirausahaans',
            'evaluasi_pencarian_bakat_mahasiswas',
            'evaluasi_motivasi_wali_kota_batams',
            'evaluasi_motivasi_gubernur_kepulauan_riaus',
            'evaluasi_febs',
            'evaluasi_fsts',
            'evaluasi_fikes',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    for ($i = 1; $i <= 30; $i++) {
                        $col = "q{$i}";
                        if (Schema::hasColumn($tableName, $col)) {
                            $table->unsignedTinyInteger($col)->nullable()->change();
                        }
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
