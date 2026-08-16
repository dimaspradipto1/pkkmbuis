<?php

namespace App\Http\Controllers;

use App\Models\ObservasiAcara;
use App\Models\ObservasiAcara2;
use App\Models\ObservasiAcaraFeb;
use App\Models\ObservasiAcaraFst;
use App\Models\ObservasiAcaraFikes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekapObservasiController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'mahasiswa') {
            abort(403, 'Akses khusus administrator & tim evaluasi.');
        }

        $events = [
            [
                'id'        => 'h1',
                'title'     => 'Observasi Acara Hari 1',
                'subtitle'  => 'PKKMB Universitas - Hari Pertama',
                'badge'     => 'Hari 1',
                'color'     => 'primary',
                'model'     => ObservasiAcara::class,
                'manageUrl' => route('observasiacara.index'),
                'addUrl'    => route('observasiacara.create'),
            ],
            [
                'id'        => 'h2',
                'title'     => 'Observasi Acara Hari 2',
                'subtitle'  => 'PKKMB Universitas - Hari Kedua',
                'badge'     => 'Hari 2',
                'color'     => 'info',
                'model'     => ObservasiAcara2::class,
                'manageUrl' => route('observasiacara2.index'),
                'addUrl'    => route('observasiacara2.create'),
            ],
            [
                'id'        => 'feb',
                'title'     => 'Observasi Acara FEB',
                'subtitle'  => 'Fakultas Ekonomi & Bisnis',
                'badge'     => 'FEB',
                'color'     => 'warning',
                'model'     => ObservasiAcaraFeb::class,
                'manageUrl' => route('observasiacarafeb.index'),
                'addUrl'    => route('observasiacarafeb.create'),
            ],
            [
                'id'        => 'fst',
                'title'     => 'Observasi Acara FST',
                'subtitle'  => 'Fakultas Sains & Teknologi',
                'badge'     => 'FST',
                'color'     => 'success',
                'model'     => ObservasiAcaraFst::class,
                'manageUrl' => route('observasiacarafst.index'),
                'addUrl'    => route('observasiacarafst.create'),
            ],
            [
                'id'        => 'fikes',
                'title'     => 'Observasi Acara FIKes',
                'subtitle'  => 'Fakultas Ilmu Kesehatan',
                'badge'     => 'FIKes',
                'color'     => 'danger',
                'model'     => ObservasiAcaraFikes::class,
                'manageUrl' => route('observasiacarafikes.index'),
                'addUrl'    => route('observasiacarafikes.create'),
            ],
        ];

        $rekapData = [];
        $totalSemuaKegiatan = 0;
        $sumSkorSemua = 0;
        $totalItemsWithSkor = 0;
        $totalDokumen = 0;

        foreach ($events as $ev) {
            $modelClass = $ev['model'];
            $items = $modelClass::orderBy('id', 'asc')->get();
            $count = $items->count();
            $totalSemuaKegiatan += $count;

            $skalaSum = $items->sum('skala');
            $avgSkala = $count > 0 ? round($skalaSum / $count, 2) : 0;
            $tcr = $avgSkala > 0 ? round(($avgSkala / 5) * 100, 2) : 0;

            if ($count > 0) {
                $sumSkorSemua += $skalaSum;
                $totalItemsWithSkor += $count;
            }

            // Count documentation links
            $docCount = 0;
            foreach ($items as $item) {
                if (!empty($item->link_dokumen)) {
                    $links = array_filter(array_map('trim', explode("\n", $item->link_dokumen)));
                    $docCount += count($links);
                }
            }
            $totalDokumen += $docCount;

            $rekapData[$ev['id']] = [
                'config'    => $ev,
                'items'     => $items,
                'count'     => $count,
                'avgSkala'  => $avgSkala,
                'tcr'       => $tcr,
                'kategori'  => $this->getKategoriObservasi($tcr),
                'docCount'  => $docCount,
            ];
        }

        $overallAvgSkala = $totalItemsWithSkor > 0 ? round($sumSkorSemua / $totalItemsWithSkor, 2) : 0;
        $overallTcr = $overallAvgSkala > 0 ? round(($overallAvgSkala / 5) * 100, 2) : 0;
        $overallKategori = $this->getKategoriObservasi($overallTcr);

        $legendSkala = [
            ['skala' => 5, 'rentang' => '80.01% - 100%', 'mutu' => 'A', 'predikat' => 'Sangat Baik', 'badge' => 'bg-success', 'desc' => 'Pelaksanaan sangat lancar, tertib, dan tepat waktu.'],
            ['skala' => 4, 'rentang' => '60.01% - 80.00%', 'mutu' => 'B', 'predikat' => 'Baik', 'badge' => 'bg-primary', 'desc' => 'Pelaksanaan berjalan dengan baik sesuai rencana kerja.'],
            ['skala' => 3, 'rentang' => '40.01% - 60.00%', 'mutu' => 'C', 'predikat' => 'Cukup', 'badge' => 'bg-warning text-dark', 'desc' => 'Terdapat beberapa catatan waktu/aspek yang perlu dievaluasi.'],
            ['skala' => 2, 'rentang' => '20.01% - 40.00%', 'mutu' => 'D', 'predikat' => 'Kurang', 'badge' => 'bg-danger', 'desc' => 'Banyak ketidaksesuaian rundown dan catatan penting.'],
            ['skala' => 1, 'rentang' => '0.00% - 20.00%', 'mutu' => 'E', 'predikat' => 'Sangat Kurang', 'badge' => 'bg-dark', 'desc' => 'Kendala signifikan dalam pelaksanaan acara.'],
        ];

        return view('pages.rekapobservasi.index', compact(
            'events',
            'rekapData',
            'totalSemuaKegiatan',
            'overallAvgSkala',
            'overallTcr',
            'overallKategori',
            'totalDokumen',
            'legendSkala'
        ));
    }

    private function getKategoriObservasi(float $tcr): string
    {
        if ($tcr >= 80.01) {
            return 'Sangat Baik';
        } elseif ($tcr >= 60.01) {
            return 'Baik';
        } elseif ($tcr >= 40.01) {
            return 'Cukup';
        } elseif ($tcr >= 20.01) {
            return 'Kurang';
        } else {
            return 'Sangat Kurang';
        }
    }
}
