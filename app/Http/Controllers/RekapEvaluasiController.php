<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiPelayananKemahasiswaanPusatPrestasi;
use App\Models\EvaluasiPelayanansistemAkademik;
use App\Models\EvaluasiPelayanansistemAdministrasiKeuangan;
use App\Models\EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara;
use App\Models\EvaluasiSistemPendidikanTinggidiIndonesia;
use App\Models\EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri;
use App\Models\EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan;
use App\Models\Perpustakaan;
use App\Models\EvaluasiIkaUis;
use App\Models\EvaluasiMotivasiGubernurKepulauanRiau;
use App\Models\EvaluasiFikes;
use App\Models\EvaluasiFst;
use App\Models\EvaluasiFeb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekapEvaluasiController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'mahasiswa') {
            abort(403, 'Akses khusus administrator.');
        }

        // List of 13 Modules (M1 to M13)
        $modules = [
            ['code' => 'M1',  'name' => 'Pelayanan Kemahasiswaan Pusat Prestasi', 'model' => EvaluasiPelayananKemahasiswaanPusatPrestasi::class],
            ['code' => 'M2',  'name' => 'Pelayanan Sistem Akademik', 'model' => EvaluasiPelayanansistemAkademik::class],
            ['code' => 'M3',  'name' => 'Pelayanan Administrasi Keuangan', 'model' => EvaluasiPelayanansistemAdministrasiKeuangan::class],
            ['code' => 'M4',  'name' => 'Bela Negara', 'model' => EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegara::class],
            ['code' => 'M5',  'name' => 'Sistem Pendidikan Tinggi di Indonesia', 'model' => EvaluasiSistemPendidikanTinggidiIndonesia::class],
            ['code' => 'M6',  'name' => 'Pendidikan Tinggi Era Digital', 'model' => EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustri::class],
            ['code' => 'M7',  'name' => 'K3L', 'model' => EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkungan::class],
            ['code' => 'M8',  'name' => 'Perpustakaan', 'model' => Perpustakaan::class],
            ['code' => 'M9',  'name' => 'IKA UIS', 'model' => EvaluasiIkaUis::class],
            ['code' => 'M10', 'name' => 'Motivasi Gubernur Kepri', 'model' => EvaluasiMotivasiGubernurKepulauanRiau::class],
            ['code' => 'M11', 'name' => 'FIKes', 'model' => EvaluasiFikes::class],
            ['code' => 'M12', 'name' => 'FST', 'model' => EvaluasiFst::class],
            ['code' => 'M13', 'name' => 'FEB', 'model' => EvaluasiFeb::class],
        ];

        // 5 Item Questions for Table 1 (Pemateri & Materi)
        $questionsTable1 = [
            1 => ['indikator' => 'Pemateri', 'item' => 'Pemateri menguasai materi dan menyampaikannya dengan jelas serta mudah dipahami.'],
            2 => ['indikator' => 'Pemateri', 'item' => 'Pemateri mampu berinteraksi, memberikan kesempatan berpartisipasi, serta menjawab pertanyaan peserta dengan baik.'],
            3 => ['indikator' => 'Pemateri', 'item' => 'Pemateri menggunakan metode penyampaian yang menarik dan mampu mengelola waktu kegiatan dengan baik.'],
            4 => ['indikator' => 'Materi',   'item' => 'Materi yang disampaikan informatif, bermanfaat, dan sesuai dengan kebutuhan peserta.'],
            5 => ['indikator' => 'Materi',   'item' => 'Materi yang disampaikan relevan dengan kegiatan PKKMB serta mendukung peningkatan wawasan dan kualitas SDM mahasiswa baru UIS.'],
        ];

        // Calculate Table 1: TCR per Question per Module
        $tcrTable1 = []; // [q_num => [module_code => tcr_value]]
        $modAvgTcr = []; // [module_code => avg_tcr]
        $modAvgKat = []; // [module_code => kategori]
        $modTotalResponses = []; // [module_code => count]

        foreach ($modules as $mod) {
            $modelClass = $mod['model'];
            $code = $mod['code'];
            $count = $modelClass::count();
            $modTotalResponses[$code] = $count;

            $sumTcrMod = 0;
            for ($q = 1; $q <= 5; $q++) {
                $avgSkor = $count > 0 ? (float) $modelClass::avg('q' . $q) : 0;
                $tcr = $avgSkor > 0 ? round(($avgSkor / 4) * 100, 2) : 0;
                $tcrTable1[$q][$code] = $tcr;
                $sumTcrMod += $tcr;
            }
            $avgTcrMod = round($sumTcrMod / 5, 2);
            $modAvgTcr[$code] = $avgTcrMod;
            $modAvgKat[$code] = $this->getKategori($avgTcrMod);
        }

        // Calculate Overall Averages for Table 1
        $overallTcrTable1 = count($modAvgTcr) > 0 ? round(array_sum($modAvgTcr) / count($modAvgTcr), 2) : 0;
        $overallKatTable1 = $this->getKategori($overallTcrTable1);

        // 9 Questions for Table 2 (Fasilitas & Penyelenggara, Sarana & Prasarana)
        $questionsTable2 = [
            1 => ['indikator' => 'Fasilitas Penyelenggara', 'field' => 'q14', 'item' => 'Efektifitas waktu dan Jadwal pelaksanaan (tanggal dan durasi)'],
            2 => ['indikator' => 'Fasilitas Penyelenggara', 'field' => 'q15', 'item' => 'Pelayanan panitia dengan peserta'],
            3 => ['indikator' => 'Fasilitas Penyelenggara', 'field' => 'q16', 'item' => 'Kejelasan informasi yang diberikan panitia'],
            4 => ['indikator' => 'Fasilitas Penyelenggara', 'field' => 'q17', 'item' => 'Kedisiplinan Kegiatan PKKMB'],
            5 => ['indikator' => 'Fasilitas Penyelenggara', 'field' => 'q18', 'item' => 'Keramahan Panitia dalam memberikan dan merespon pertanyaan'],
            6 => ['indikator' => 'Sarana dan Prasarana',   'field' => 'q19', 'item' => 'Ketersediaan dan kesiapan sarana prasarana kegiatan pendukung kegiatan kuliah umum'],
            7 => ['indikator' => 'Sarana dan Prasarana',   'field' => 'q20', 'item' => 'Kondisi Lokasi tempat PKKMB (Kondusif dan Nyaman)'],
            8 => ['indikator' => 'Sarana dan Prasarana',   'field' => 'q21', 'item' => 'Kualitas Sarana prasarana pendukung (Toilet, Taman, Tempat duduk, dan Mushola dll)'],
            9 => ['indikator' => 'Sarana dan Prasarana',   'field' => 'q22', 'item' => 'Kualitas Sound/suara dan tampilan layar infocus'],
        ];

        $fakultasModels = [
            'FIKes' => EvaluasiFikes::class,
            'FST'   => EvaluasiFst::class,
            'FEB'   => EvaluasiFeb::class,
        ];

        $tcrTable2 = [];
        $fakAvgTcr = ['FIKes' => 0, 'FST' => 0, 'FEB' => 0];
        $fakSumTcr = ['FIKes' => 0, 'FST' => 0, 'FEB' => 0];

        foreach ($questionsTable2 as $qId => $qInfo) {
            $field = $qInfo['field'];
            $rowSum = 0;
            $rowFakCount = 0;

            foreach ($fakultasModels as $fakName => $fakModel) {
                $count = $fakModel::count();
                $avgSkor = $count > 0 ? (float) $fakModel::avg($field) : 0;
                $tcr = $avgSkor > 0 ? round(($avgSkor / 4) * 100, 2) : 0;

                $tcrTable2[$qId][$fakName] = $tcr;
                $fakSumTcr[$fakName] += $tcr;

                if ($tcr > 0) {
                    $rowSum += $tcr;
                    $rowFakCount++;
                }
            }
            $tcrTable2[$qId]['rerata'] = $rowFakCount > 0 ? round($rowSum / $rowFakCount, 2) : 0;
        }

        foreach ($fakultasModels as $fakName => $fakModel) {
            $fakAvgTcr[$fakName] = round($fakSumTcr[$fakName] / count($questionsTable2), 2);
        }

        $overallTcrTable2 = round(array_sum($fakAvgTcr) / count($fakAvgTcr), 2);
        $overallKatTable2 = $this->getKategori($overallTcrTable2);

        $fakKat = [
            'FIKes' => $this->getKategori($fakAvgTcr['FIKes']),
            'FST'   => $this->getKategori($fakAvgTcr['FST']),
            'FEB'   => $this->getKategori($fakAvgTcr['FEB']),
        ];

        // Legend / Reference Table
        $legendTable = [
            ['persepsi' => 1, 'ni' => '1,00 - 2,5996',   'nik' => '25,00 - 64,99',  'mutu' => 'D', 'kinerja' => 'TIDAK BAIK'],
            ['persepsi' => 2, 'ni' => '2,60 - 3,064',    'nik' => '65,00 - 76,60',  'mutu' => 'C', 'kinerja' => 'KURANG BAIK'],
            ['persepsi' => 3, 'ni' => '3,0664 - 3,2532', 'nik' => '76,61 - 88,30',  'mutu' => 'B', 'kinerja' => 'BAIK'],
            ['persepsi' => 4, 'ni' => '3,26 - 4,00',     'nik' => '88,31 - 100,00', 'mutu' => 'A', 'kinerja' => 'SANGAT BAIK'],
        ];

        return view('pages.rekapevaluasi.index', compact(
            'modules',
            'questionsTable1',
            'tcrTable1',
            'modAvgTcr',
            'modAvgKat',
            'modTotalResponses',
            'overallTcrTable1',
            'overallKatTable1',
            'questionsTable2',
            'tcrTable2',
            'fakAvgTcr',
            'fakKat',
            'overallTcrTable2',
            'overallKatTable2',
            'legendTable'
        ));
    }

    private function getKategori($tcr)
    {
        if ($tcr >= 88.31) {
            return 'Sangat Baik';
        } elseif ($tcr >= 76.61) {
            return 'Baik';
        } elseif ($tcr >= 65.00) {
            return 'Kurang Baik';
        } else {
            return 'Tidak Baik';
        }
    }
}
