@extends('dashboard.template')

@section('content')
<style>
    .rekap-card { background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03); padding: 24px; margin-bottom: 24px; }
    .table-rekap { font-size: 0.82rem; }
    .table-rekap th { background-color: #012970 !important; color: #ffffff !important; text-align: center; vertical-align: middle; padding: 10px 8px; font-weight: 600; border: 1px solid #001f54 !important; }
    .table-rekap td { vertical-align: middle; padding: 8px 10px; border: 1px solid #cbd5e1; }
    .table-rekap .bg-header-sub { background-color: #f1f5f9; font-weight: 700; color: #1e293b; }
    .table-rekap .bg-summary { background-color: #e2e8f0; font-weight: 700; color: #0f172a; }
    .table-rekap .bg-kat { background-color: #f8fafc; font-weight: 700; }
    .tcr-badge { font-weight: 700; padding: 4px 8px; border-radius: 6px; font-size: 0.78rem; display: inline-block; }
    .tcr-sangat-baik { background-color: #dcfce7; color: #15803d; border: 1px solid #86efac; }
    .tcr-baik { background-color: #dbeafe; color: #1d4ed8; border: 1px solid #93c5fd; }
    .tcr-kurang-baik { background-color: #fef9c3; color: #a16207; border: 1px solid #fde047; }
    .tcr-tidak-baik { background-color: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }
    .legend-box { background-color: #fff7ed; border: 1px solid #fdba74; border-radius: 10px; padding: 16px; margin-bottom: 24px; }
    .legend-title { font-weight: 700; color: #c2410c; margin-bottom: 12px; font-size: 0.95rem; }
    .chart-container-box { position: relative; height: 380px; width: 100%; }
    @media print {
        .sidebar, .header, .pagetitle button, .pagetitle a, .no-print { display: none !important; }
        .main { margin-left: 0 !important; padding: 0 !important; }
        .rekap-card { box-shadow: none !important; border: 1px solid #ccc !important; }
    }
</style>

<div class="pagetitle d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
        <h1 class="h4 fw-bold text-dark mb-1">Rekapitulasi Evaluasi Penyampaian Materi & Pelaksanaan PKKMB UIS</h1>
        <nav>
            <ol class="breadcrumb mb-0 extra-small">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Rekapitulasi</li>
                <li class="breadcrumb-item active">Evaluasi</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2 no-print">
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-printer me-1"></i> Cetak PDF / Print
        </button>
    </div>
</div>

{{-- Reference Legend Table --}}
<div class="legend-box shadow-sm">
    <div class="d-flex align-items-center gap-2 legend-title">
        <i class="bi bi-info-circle-fill fs-5"></i>
        Acuan Nilai Interval & Kategori Mutu Pelayanan (TCR)
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-bordered text-center mb-0 bg-white" style="font-size: 0.82rem;">
            <thead>
                <tr style="background-color: #ffedd5;">
                    <th style="color: #9a3412;">NILAI PERSEPSI</th>
                    <th style="color: #9a3412;">NILAI INTERVAL (NI)</th>
                    <th style="color: #9a3412;">NILAI INTERVAL KONVERSI (NIK / TCR %)</th>
                    <th style="color: #9a3412;">MUTU PELAYANAN (X)</th>
                    <th style="color: #9a3412;">KINERJA UNIT PELAYANAN (Y)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($legendTable as $leg)
                    <tr>
                        <td class="fw-bold">{{ $leg['persepsi'] }}</td>
                        <td>{{ $leg['ni'] }}</td>
                        <td>{{ $leg['nik'] }}%</td>
                        <td class="fw-bold">{{ $leg['mutu'] }}</td>
                        <td>
                            <span class="tcr-badge {{ strtolower(str_replace(' ', '-', $leg['kinerja'])) == 'sangat-baik' ? 'tcr-sangat-baik' : (strtolower(str_replace(' ', '-', $leg['kinerja'])) == 'baik' ? 'tcr-baik' : (strtolower(str_replace(' ', '-', $leg['kinerja'])) == 'kurang-baik' ? 'tcr-kurang-baik' : 'tcr-tidak-baik')) }}">
                                {{ $leg['kinerja'] }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- TABEL 1: Rekapitulasi Pemateri (M1 s.d. M17) --}}
<div class="rekap-card" id="tabel1Card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-table text-primary"></i>
            Tabel 1: Rekapitulasi Tingkat Capaian Responden (TCR %) Pemateri & Isi Materi (M1 s.d. M17)
        </h5>
        <button onclick="downloadTableAsPNG('tabel1Card', 'Tabel_1_Rekapitulasi_Pemateri_M1_M17')" class="btn btn-outline-primary btn-sm rounded-pill px-3 no-print">
            <i class="bi bi-download me-1"></i> Download PNG
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-rekap align-middle mb-0">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 35px;">No</th>
                    <th rowspan="2" style="width: 90px;">Indikator</th>
                    <th rowspan="2" style="min-width: 260px;">Item Pertanyaan</th>
                    <th colspan="17">TCR (%) Module Pemateri (M1 s.d. M17)</th>
                </tr>
                <tr>
                    @foreach($modules as $m)
                        <th title="{{ $m['name'] }}" style="min-width: 55px;">{{ $m['code'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php $currentIndikator = ''; @endphp
                @foreach($questionsTable1 as $qNum => $qInfo)
                    <tr>
                        <td class="text-center fw-bold">{{ $qNum }}</td>
                        @if($currentIndikator != $qInfo['indikator'])
                            @php
                                $currentIndikator = $qInfo['indikator'];
                                $rowspan = $qNum <= 8 ? 8 : 5;
                            @endphp
                            <td rowspan="{{ $rowspan }}" class="fw-bold bg-header-sub text-center align-middle">
                                {{ $currentIndikator }}
                            </td>
                        @endif
                        <td>{{ $qInfo['item'] }}</td>
                        @foreach($modules as $m)
                            @php $val = $tcrTable1[$qNum][$m['code']] ?? 0; @endphp
                            <td class="text-center {{ $val == 0 ? 'text-muted' : 'fw-semibold' }}">
                                {{ $val > 0 ? number_format($val, 2, ',', '.') : '-' }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach

                {{-- Row Rata-Rata per Module --}}
                <tr class="bg-summary">
                    <td colspan="3" class="text-end fw-bold">Rata-Rata TCR (%)</td>
                    @foreach($modules as $m)
                        @php $val = $modAvgTcr[$m['code']] ?? 0; @endphp
                        <td class="text-center text-primary fw-bold">
                            {{ $val > 0 ? number_format($val, 2, ',', '.') : '-' }}
                        </td>
                    @endforeach
                </tr>

                {{-- Row Kategori per Module --}}
                <tr class="bg-kat">
                    <td colspan="3" class="text-end fw-bold">Kategori / Mutu Pelayanan</td>
                    @foreach($modules as $m)
                        @php
                            $kat = $modAvgKat[$m['code']] ?? '-';
                            $badgeClass = $kat == 'Sangat Baik' ? 'tcr-sangat-baik' : ($kat == 'Baik' ? 'tcr-baik' : ($kat == 'Kurang Baik' ? 'tcr-kurang-baik' : 'tcr-tidak-baik'));
                        @endphp
                        <td class="text-center p-1">
                            <span class="tcr-badge {{ $badgeClass }}">{{ $kat }}</span>
                        </td>
                    @endforeach
                </tr>

                {{-- Overall Summary Row Table 1 --}}
                <tr style="background-color: #e0f2fe; font-size: 0.9rem;">
                    <td colspan="3" class="text-end fw-bold text-dark">Rata-Rata TCR Keseluruhan Pemateri:</td>
                    <td colspan="17" class="fw-bold text-dark text-start ps-3">
                        <span class="fs-6 text-primary me-3">{{ number_format($overallTcrTable1, 2, ',', '.') }}%</span>
                        Kategori: <span class="tcr-badge tcr-sangat-baik ms-1 fs-6">{{ $overallKatTable1 }}</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- TABEL 2: Rekapitulasi Pelaksanaan PKKMB UIS (Fasilitas & Sarana Prasarana per Fakultas) --}}
<div class="rekap-card" id="tabel2Card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-building-check text-success"></i>
            Tabel 2: Rekapitulasi Pelaksanaan & Sarana Prasarana PKKMB per Fakultas (FIKes, FST, FEB)
        </h5>
        <button onclick="downloadTableAsPNG('tabel2Card', 'Tabel_2_Rekapitulasi_Fasilitas_PKKMB')" class="btn btn-outline-success btn-sm rounded-pill px-3 no-print">
            <i class="bi bi-download me-1"></i> Download PNG
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-rekap align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 35px;">No</th>
                    <th style="width: 150px;">Indikator</th>
                    <th style="min-width: 320px;">Item Pertanyaan</th>
                    <th style="width: 100px;">FIKes (%)</th>
                    <th style="width: 100px;">FST (%)</th>
                    <th style="width: 100px;">FEB (%)</th>
                    <th style="width: 130px; background-color: #0369a1 !important;">Rerata / Fakultas (%)</th>
                </tr>
            </thead>
            <tbody>
                @php $currInd2 = ''; @endphp
                @foreach($questionsTable2 as $qId => $qInfo)
                    <tr>
                        <td class="text-center fw-bold">{{ $qId }}</td>
                        @if($currInd2 != $qInfo['indikator'])
                            @php
                                $currInd2 = $qInfo['indikator'];
                                $rowspan2 = $qId <= 5 ? 5 : 4;
                            @endphp
                            <td rowspan="{{ $rowspan2 }}" class="fw-bold bg-header-sub text-center align-middle">
                                {{ $currInd2 }}
                            </td>
                        @endif
                        <td>{{ $qInfo['item'] }}</td>
                        <td class="text-center fw-semibold">{{ number_format($tcrTable2[$qId]['FIKes'] ?? 0, 2, ',', '.') }}</td>
                        <td class="text-center fw-semibold">{{ number_format($tcrTable2[$qId]['FST'] ?? 0, 2, ',', '.') }}</td>
                        <td class="text-center fw-semibold">{{ number_format($tcrTable2[$qId]['FEB'] ?? 0, 2, ',', '.') }}</td>
                        <td class="text-center fw-bold bg-light text-primary">{{ number_format($tcrTable2[$qId]['rerata'] ?? 0, 2, ',', '.') }}</td>
                    </tr>
                @endforeach

                {{-- Row Rata-Rata --}}
                <tr class="bg-summary">
                    <td colspan="3" class="text-end fw-bold">Rata-Rata TCR (%)</td>
                    <td class="text-center text-primary">{{ number_format($fakAvgTcr['FIKes'], 2, ',', '.') }}</td>
                    <td class="text-center text-primary">{{ number_format($fakAvgTcr['FST'], 2, ',', '.') }}</td>
                    <td class="text-center text-primary">{{ number_format($fakAvgTcr['FEB'], 2, ',', '.') }}</td>
                    <td class="text-center text-dark fs-6">{{ number_format($overallTcrTable2, 2, ',', '.') }}</td>
                </tr>

                {{-- Row Kategori --}}
                <tr class="bg-kat">
                    <td colspan="3" class="text-end fw-bold">Kategori / Mutu Pelayanan</td>
                    <td class="text-center p-1"><span class="tcr-badge tcr-sangat-baik">{{ $fakKat['FIKes'] }}</span></td>
                    <td class="text-center p-1"><span class="tcr-badge tcr-sangat-baik">{{ $fakKat['FST'] }}</span></td>
                    <td class="text-center p-1"><span class="tcr-badge tcr-sangat-baik">{{ $fakKat['FEB'] }}</span></td>
                    <td class="text-center p-1"><span class="tcr-badge tcr-sangat-baik fs-6">{{ $overallKatTable2 }}</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- GRAFIK 3D SECTION --}}
<div class="row">
    {{-- Chart 1: Grafik 3D Pelaksanaan PKKMB UIS --}}
    <div class="col-lg-12">
        <div class="rekap-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-box-seam-fill text-primary"></i>
                    Grafik 3D Pelaksanaan & Sarana Prasarana PKKMB UIS
                </h5>
                <button onclick="download3DChart(chartPelaksanaan3D, 'Grafik_3D_Pelaksanaan_PKKMB_UIS')" class="btn btn-outline-primary btn-sm rounded-pill px-3 no-print">
                    <i class="bi bi-download me-1"></i> Download PNG (3D)
                </button>
            </div>
            <div id="chartPelaksanaanContainer" style="min-height: 420px; width: 100%;"></div>
        </div>
    </div>

    {{-- Chart 2: Grafik 3D TCR Pemateri (M1 s.d. M17) --}}
    <div class="col-lg-12">
        <div class="rekap-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-box-seam-fill text-success"></i>
                    Grafik 3D Evaluasi TCR Pemateri (M1 s.d. M17)
                </h5>
                <button onclick="download3DChart(chartPemateri3D, 'Grafik_3D_TCR_Pemateri_M1_M17')" class="btn btn-outline-success btn-sm rounded-pill px-3 no-print">
                    <i class="bi bi-download me-1"></i> Download PNG (3D)
                </button>
            </div>
            <div id="chartPemateriContainer" style="min-height: 460px; width: 100%;"></div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- html2canvas for Table PNG Export --}}
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

{{-- Highcharts 3D & Exporting CDN Scripts --}}
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/offline-exporting.js"></script>

<script>
    // Download Table HTML Card as PNG Image (Full Untruncated Table)
    function downloadTableAsPNG(elementId, filename) {
        const cardEl = document.getElementById(elementId);
        if (!cardEl) return;

        const responsiveDiv = cardEl.querySelector('.table-responsive');
        const tableEl = cardEl.querySelector('table');

        // Store original inline style states
        const origOverflow = responsiveDiv ? responsiveDiv.style.overflow : '';
        const origWidth = responsiveDiv ? responsiveDiv.style.width : '';
        const origMaxW = responsiveDiv ? responsiveDiv.style.maxWidth : '';

        // Temporarily expand responsive wrapper to capture full scrollable width (M1..M17)
        if (responsiveDiv && tableEl) {
            responsiveDiv.style.overflow = 'visible';
            responsiveDiv.style.width = 'max-content';
            responsiveDiv.style.maxWidth = 'none';
        }

        const noPrintElems = cardEl.querySelectorAll('.no-print');
        noPrintElems.forEach(el => el.style.display = 'none');

        Swal.fire({
            title: 'Mengekspor Gambar PNG...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Determine full target width including hidden overflow
        const fullWidth = Math.max(cardEl.scrollWidth, tableEl ? tableEl.scrollWidth + 30 : 1300);

        html2canvas(cardEl, {
            scale: 2, // High resolution crisp image
            backgroundColor: '#ffffff',
            useCORS: true,
            windowWidth: fullWidth,
            width: fullWidth
        }).then(canvas => {
            // Restore original responsive styles
            if (responsiveDiv) {
                responsiveDiv.style.overflow = origOverflow;
                responsiveDiv.style.width = origWidth;
                responsiveDiv.style.maxWidth = origMaxW;
            }
            noPrintElems.forEach(el => el.style.display = '');
            Swal.close();

            const link = document.createElement('a');
            link.download = filename + '.png';
            link.href = canvas.toDataURL('image/png');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }).catch(err => {
            if (responsiveDiv) {
                responsiveDiv.style.overflow = origOverflow;
                responsiveDiv.style.width = origWidth;
                responsiveDiv.style.maxWidth = origMaxW;
            }
            noPrintElems.forEach(el => el.style.display = '');
            Swal.close();
            console.error('Export PNG failed:', err);
        });
    }

    // Data for Chart 1 (Pelaksanaan & Sarana Prasarana)
    const labelsChart1 = [
        'Efektifitas waktu',
        'Pelayanan panitia',
        'Kejelasan informasi',
        'Kedisiplinan Kegiatan',
        'Keramahan Panitia',
        'Ketersediaan sarana',
        'Kondisi Lokasi',
        'Kualitas Sarana',
        'Kualitas Sound & Screen'
    ];

    const dataChart1 = [
        @foreach($questionsTable2 as $qId => $qInfo)
            {{ $tcrTable2[$qId]['rerata'] ?? 0 }},
        @endforeach
    ];

    const minValChart1 = Math.min(...dataChart1.filter(v => v > 0));
    const seriesData1 = dataChart1.map(val => ({
        y: val,
        color: val === minValChart1 ? '#dc2626' : '#3b82f6'
    }));

    // Init 3D Chart 1 (Flat 2D Gridlines + 3D Bar Columns)
    const chartPelaksanaan3D = Highcharts.chart('chartPelaksanaanContainer', {
        chart: {
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 0,  // Gridlines remain flat 2D!
                beta: 0,   // Gridlines remain flat 2D!
                depth: 35, // 3D depth for bars only
                viewDistance: 25
            },
            backgroundColor: '#ffffff'
        },
        title: {
            text: 'Pelaksanaan PKKMB UIS',
            style: { fontSize: '18px', fontWeight: 'bold', fontFamily: 'Poppins, sans-serif', color: '#0f172a' }
        },
        subtitle: {
            text: 'Visualisasi Tingkat Capaian Responden (TCR %)'
        },
        xAxis: {
            categories: labelsChart1,
            labels: {
                skew3d: false, // Flat text labels!
                style: { fontSize: '11px', fontWeight: '600', color: '#334155' }
            }
        },
        yAxis: {
            title: { text: 'Rerata TCR (%)' },
            max: 105,
            labels: {
                format: '{value:.2f}'
            }
        },
        tooltip: {
            pointFormat: 'TCR: <b>{point.y:.2f}%</b>'
        },
        plotOptions: {
            column: {
                depth: 35,
                colorByPoint: false,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.2f}',
                    style: { fontSize: '12px', fontWeight: 'bold', color: '#0f172a', textOutline: '2px #ffffff' }
                }
            }
        },
        series: [{
            name: 'Rerata TCR (%)',
            data: seriesData1,
            showInLegend: false
        }],
        exporting: {
            enabled: false
        },
        credits: { enabled: false }
    });

    // Data for Chart 2 (TCR Pemateri M1 s.d. M17)
    const labelsChart2 = [
        @foreach($modules as $m)
            '{{ $m['code'] }}',
        @endforeach
    ];

    const dataChart2 = [
        @foreach($modules as $m)
            {{ $modAvgTcr[$m['code']] ?? 0 }},
        @endforeach
    ];

    const minValChart2 = Math.min(...dataChart2.filter(v => v > 0));
    const seriesData2 = dataChart2.map(val => ({
        y: val,
        color: val === minValChart2 ? '#dc2626' : '#3b82f6'
    }));

    // Init 3D Chart 2 (Horizontal 3D Bar Chart with Flat Gridlines)
    const chartPemateri3D = Highcharts.chart('chartPemateriContainer', {
        chart: {
            type: 'bar', // Horizontal bar chart
            options3d: {
                enabled: true,
                alpha: 0,  // Gridlines remain flat 2D!
                beta: 0,   // Gridlines remain flat 2D!
                depth: 25, // 3D depth for bars
                viewDistance: 25
            },
            backgroundColor: '#ffffff'
        },
        title: {
            text: 'Rata-Rata TCR Pemateri (M1 s.d. M17)',
            style: { fontSize: '18px', fontWeight: 'bold', fontFamily: 'Poppins, sans-serif', color: '#0f172a' }
        },
        subtitle: {
            text: 'Evaluasi Pemateri M1 s.d. M17'
        },
        xAxis: {
            categories: labelsChart2,
            labels: {
                skew3d: false, // Flat text labels!
                style: { fontSize: '11px', fontWeight: 'bold', color: '#334155' }
            }
        },
        yAxis: {
            title: { text: 'Rata-Rata TCR (%)' },
            max: 105,
            labels: {
                format: '{value:.2f}'
            }
        },
        tooltip: {
            pointFormat: 'Rata-Rata TCR: <b>{point.y:.2f}%</b>'
        },
        plotOptions: {
            bar: {
                depth: 25,
                colorByPoint: false,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.2f}',
                    style: { fontSize: '11px', fontWeight: 'bold', color: '#0f172a', textOutline: '2px #ffffff' }
                }
            }
        },
        series: [{
            name: 'Rata-Rata TCR (%)',
            data: seriesData2,
            showInLegend: false
        }],
        exporting: {
            enabled: false
        },
        credits: { enabled: false }
    });

    // Download 3D Chart as PNG Image
    function download3DChart(chartObj, filename) {
        chartObj.exportChartLocal({
            type: 'image/png',
            filename: filename
        });
    }
</script>
@endpush
