<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluasi_menus', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor')->unique();
            $table->string('nama');
            $table->string('route_name')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // Insert initial 17 evaluasi submenus
        $items = [
            ['nomor' => 1, 'nama' => '1. Pengenalan wawasan sejarah Ibnu Sina', 'route_name' => 'evaluasipengenalanwawasanibnusina.index', 'is_active' => true],
            ['nomor' => 2, 'nama' => '2. Pelayanan Kemahasiswaan Pusat Prestasi', 'route_name' => null, 'is_active' => false],
            ['nomor' => 3, 'nama' => '3. Pelayanan sistem Akademik', 'route_name' => null, 'is_active' => false],
            ['nomor' => 4, 'nama' => '4. Pelayanan sistem administrasi keuangan', 'route_name' => null, 'is_active' => false],
            ['nomor' => 5, 'nama' => '5. Kehidupan Berbangsa, Bernegara dan pembinaan kesadaran bela negara', 'route_name' => null, 'is_active' => false],
            ['nomor' => 6, 'nama' => '6. Sistem Pendidikan Tinggi di Indonesia', 'route_name' => null, 'is_active' => false],
            ['nomor' => 7, 'nama' => '7. Pendidikan Tinggi di Era Digital dan Revolusi Industri', 'route_name' => null, 'is_active' => false],
            ['nomor' => 8, 'nama' => '8. Pengenalan Keselamatan,Kesehatan Kerja dan Lingkungan ( K3L)', 'route_name' => null, 'is_active' => false],
            ['nomor' => 9, 'nama' => '9. Perpustakaan', 'route_name' => null, 'is_active' => false],
            ['nomor' => 10, 'nama' => '10. IKA UIS', 'route_name' => null, 'is_active' => false],
            ['nomor' => 11, 'nama' => '11. Kewirausahaan', 'route_name' => null, 'is_active' => false],
            ['nomor' => 12, 'nama' => '12. Pencarian Bakat Mahasiswa UIS (Survei minat UKM) dan Sosialiasi BEI', 'route_name' => null, 'is_active' => false],
            ['nomor' => 13, 'nama' => '13. Motivasi Wali Kota Batam', 'route_name' => null, 'is_active' => false],
            ['nomor' => 14, 'nama' => '14. Motivasi Gubernur Kepulauan Riau', 'route_name' => null, 'is_active' => false],
            ['nomor' => 15, 'nama' => '15. (FIKes) (FAKULTAS ILMU KESEHATAN)', 'route_name' => null, 'is_active' => false],
            ['nomor' => 16, 'nama' => '16. (FST) (FAKULTAS SAINS & TEKNOLOGI)', 'route_name' => null, 'is_active' => false],
            ['nomor' => 17, 'nama' => '17. (FEB) (FAKULTAS EKONOMI DAN BISNIS)', 'route_name' => null, 'is_active' => false],
        ];

        $now = now();
        foreach ($items as $item) {
            DB::table('evaluasi_menus')->insert([
                'nomor' => $item['nomor'],
                'nama' => $item['nama'],
                'route_name' => $item['route_name'],
                'is_active' => $item['is_active'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_menus');
    }
};
