@extends('dashboard.template')

@section('content')
<style>
    .rekap-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 16px rgba(0,0,0,0.04);
        padding: 24px;
        margin-bottom: 24px;
    }
    .chart-container-box {
        position: relative;
        height: 380px;
        width: 100%;
    }
    .table-rekap-obs {
        font-size: 0.84rem;
    }
    .table-rekap-obs th {
        background-color: #012970 !important;
        color: #ffffff !important;
        text-align: center;
        vertical-align: middle;
        padding: 10px 8px;
        font-weight: 600;
        border: 1px solid #001f54 !important;
    }
    .table-rekap-obs td {
        vertical-align: middle;
        padding: 8px 10px;
        border: 1px solid #e2e8f0;
    }
    @media print {
        .sidebar, .header, .pagetitle button, .pagetitle a, .pagetitle .dropdown, .no-print {
            display: none !important;
        }
        .main {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .rekap-card, .card {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
        }
    }
</style>

<div class="pagetitle">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h1 class="h4 fw-bold text-dark mb-1">Rekapitulasi Observasi Acara PKKMB</h1>
            <nav>
                <ol class="breadcrumb mb-0 extra-small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Rekapitulasi</li>
                    <li class="breadcrumb-item active">Observasi Acara</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap no-print">
            {{-- Dropdown Export All / Per Observasi --}}
            <div class="dropdown">
                <button class="btn btn-outline-success btn-sm rounded-pill px-3 py-2 fw-bold dropdown-toggle shadow-sm d-inline-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-file-earmark-excel-fill"></i> Export Excel
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 extra-small p-2">
                    <li><h6 class="dropdown-header text-uppercase fw-bold text-dark">Master & Per Sesi</h6></li>
                    <li>
                        <button class="dropdown-item py-2 rounded-3 fw-semibold text-success" onclick="exportObservasiToExcel('masterObservasiTable', 'Rekapitulasi_Master_Observasi_PKKMB')">
                            <i class="bi bi-table me-2"></i> Master Rekapitulasi (Semua Sesi)
                        </button>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    @foreach ($events as $ev)
                        <li>
                            <button class="dropdown-item py-2 rounded-3" onclick="exportObservasiToExcel('table-detail-{{ $ev['id'] }}', 'Observasi_Acara_{{ str_replace(' ', '_', $ev['badge']) }}')">
                                <i class="bi bi-file-earmark-arrow-down me-2 text-primary"></i> Export {{ $ev['title'] }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<section class="section">
    {{-- Top Metric / KPI Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted extra-small fw-bold text-uppercase tracking-wider">Total Kegiatan</span>
                        <h3 class="fw-extrabold text-dark my-1">{{ $totalSemuaKegiatan }}</h3>
                        <span class="extra-small text-muted">5 Sesi PKKMB</span>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(14, 165, 233, 0.1); color: #0284c7;">
                        <i class="bi bi-calendar2-check-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted extra-small fw-bold text-uppercase tracking-wider">Rata-Rata Skala</span>
                        <h3 class="fw-extrabold text-dark my-1">{{ $overallAvgSkala }} <span class="fs-6 text-muted fw-normal">/ 5.0</span></h3>
                        <span class="extra-small text-muted">Skala Penilaian Acara</span>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(245, 158, 11, 0.1); color: #d97706;">
                        <i class="bi bi-star-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted extra-small fw-bold text-uppercase tracking-wider">Tingkat Capaian (TCR)</span>
                        <h3 class="fw-extrabold text-success my-1">{{ $overallTcr }}%</h3>
                        @php
                            $overallKategoriBg = match($overallKategori) {
                                'Sangat Baik' => 'background-color: #00A551;',
                                'Baik' => 'background-color: #0d6efd;',
                                'Cukup' => 'background-color: #f59e0b;',
                                'Kurang' => 'background-color: #dc3545;',
                                default => 'background-color: #475569;',
                            };
                        @endphp
                        <span class="badge text-white fw-bold px-2 py-1 extra-small" style="{{ $overallKategoriBg }}">{{ $overallKategori }}</span>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(0, 165, 81, 0.1); color: #00A551;">
                        <i class="bi bi-graph-up-arrow fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted extra-small fw-bold text-uppercase tracking-wider">Lampiran Dokumentasi</span>
                        <h3 class="fw-extrabold text-dark my-1">{{ $totalDokumen }}</h3>
                        <span class="extra-small text-muted">Foto & Video Bukti</span>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                        <i class="bi bi-camera-reels-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Visual Charts Row (Like Rekap Evaluasi TCR) --}}
    <div class="row g-4 mb-4">
        {{-- Chart 1: Capaian TCR (%) 3D Column Chart --}}
        <div class="col-12 col-lg-6">
            <div class="rekap-card h-100" id="chartTcrCard">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-bar-chart-line-fill text-primary"></i>
                        Grafik 1: Capaian TCR (%) Per Sesi Observasi
                    </h5>
                    <button type="button" onclick="downloadHighchartPNG(chartTcr3D, 'Grafik_TCR_Observasi_Acara')" class="btn btn-outline-primary btn-sm rounded-pill px-3 no-print">
                        <i class="bi bi-download me-1"></i> Unduh PNG
                    </button>
                </div>
                <div id="chartTcrContainer" class="chart-container-box"></div>
            </div>
        </div>

        {{-- Chart 2: Rata-Rata Skala Penilaian (1-5) Bar Chart --}}
        <div class="col-12 col-lg-6">
            <div class="rekap-card h-100" id="chartSkorCard">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-stars text-warning"></i>
                        Grafik 2: Rata-Rata Skala Penilaian Acara (1 - 5.0)
                    </h5>
                    <button type="button" onclick="downloadHighchartPNG(chartSkor3D, 'Grafik_Skala_Observasi_Acara')" class="btn btn-outline-primary btn-sm rounded-pill px-3 no-print">
                        <i class="bi bi-download me-1"></i> Unduh PNG
                    </button>
                </div>
                <div id="chartSkorContainer" class="chart-container-box"></div>
            </div>
        </div>
    </div>

    {{-- Main Content: Master Summary Table --}}
    <div class="rekap-card" id="masterRekapCard">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-table text-success"></i>
                    Tabel 1: Master Rekapitulasi Pelaksanaan & Observasi Acara
                </h5>
                <p class="text-muted extra-small mb-0">Ringkasan hasil evaluasi dan penilaian pelaksanaan seluruh sesi PKKMB Universitas Ibnu Sina.</p>
            </div>
            <div class="d-flex align-items-center gap-2 no-print">
                <button type="button" onclick="exportObservasiToExcel('masterObservasiTable', 'Master_Rekapitulasi_Observasi_Acara')" class="btn btn-outline-success btn-sm rounded-pill px-3">
                    <i class="bi bi-file-earmark-excel me-1"></i> Excel
                </button>
                <button type="button" onclick="downloadTableAsPNG('masterRekapCard', 'Tabel_Master_Rekapitulasi_Observasi')" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                    <i class="bi bi-download me-1"></i> Download PNG
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-rekap-obs align-middle mb-0" id="masterObservasiTable">
                <thead>
                    <tr>
                        <th style="width: 45px;">NO</th>
                        <th>SESI / KATEGORI ACARA</th>
                        <th style="width: 140px;">JUMLAH AGENDA</th>
                        <th style="width: 160px;">RATA-RATA SKOR (1-5)</th>
                        <th style="width: 160px;">CAPAIAN TCR (%)</th>
                        <th style="width: 150px;">PREDIKAT</th>
                        <th style="width: 140px;">DOKUMENTASI</th>
                        <th class="no-print" style="width: 110px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($events as $ev)
                        @php
                            $data = $rekapData[$ev['id']];
                            $evBadgeBg = match($ev['id']) {
                                'h1' => 'background-color: #0d6efd;',
                                'h2' => 'background-color: #0284c7;',
                                'feb' => 'background-color: #F5A524;',
                                'fst' => 'background-color: #9F1521;',
                                'fikes' => 'background-color: #823ca2;',
                                default => 'background-color: #475569;',
                            };
                            $kategoriBg = match($data['kategori']) {
                                'Sangat Baik' => 'background-color: #00A551;',
                                'Baik' => 'background-color: #0d6efd;',
                                'Cukup' => 'background-color: #f59e0b;',
                                'Kurang' => 'background-color: #dc3545;',
                                default => 'background-color: #475569;',
                            };
                        @endphp
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $no++ }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge text-white fw-bold px-2 py-1 extra-small" style="{{ $evBadgeBg }}">
                                        {{ $ev['badge'] }}
                                    </span>
                                    <div>
                                        <span class="fw-bold text-dark d-block">{{ $ev['title'] }}</span>
                                        <span class="text-muted extra-small">{{ $ev['subtitle'] }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center fw-bold">{{ $data['count'] }} Kegiatan</td>
                            <td class="text-center">
                                <span class="fw-bold text-dark fs-6">{{ $data['avgSkala'] }}</span>
                                <span class="text-muted extra-small">/ 5.0</span>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex flex-column align-items-center" style="min-width: 100px;">
                                    <span class="fw-bold text-dark mb-1">{{ $data['tcr'] }}%</span>
                                    <div class="progress w-100" style="height: 6px;">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $data['tcr'] }}%; {{ $data['tcr'] >= 80 ? 'background-color: #00A551;' : ($data['tcr'] >= 60 ? 'background-color: #0d6efd;' : 'background-color: #f59e0b;') }}"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge text-white px-3 py-1 extra-small rounded-pill" style="{{ $kategoriBg }}">{{ $data['kategori'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-2 py-1 extra-small">
                                    <i class="bi bi-paperclip me-1"></i> {{ $data['docCount'] }} Link
                                </span>
                            </td>
                            <td class="text-center no-print">
                                <a href="{{ $ev['manageUrl'] }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 extra-small fw-bold shadow-none">
                                    <i class="bi bi-pencil-square me-1"></i> Kelola
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-light fw-bold">
                    <tr style="background-color: #f1f5f9;">
                        <td colspan="2" class="ps-3 py-2 text-uppercase">Rerata Keseluruhan Observasi Acara</td>
                        <td class="text-center">{{ $totalSemuaKegiatan }} Kegiatan</td>
                        <td class="text-center fs-6 text-primary">{{ $overallAvgSkala }} / 5.0</td>
                        <td class="text-center fs-6 text-success">{{ $overallTcr }}%</td>
                        <td class="text-center">
                            <span class="badge text-white px-3 py-1 rounded-pill" style="{{ $overallKategoriBg }}">{{ $overallKategori }}</span>
                        </td>
                        <td class="text-center">{{ $totalDokumen }} Link</td>
                        <td class="text-center text-muted extra-small no-print">Otomatis</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Detail Rincian Observasi Per Kategori (Tabs) --}}
    <div class="rekap-card" id="detailTabsCard">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-card-checklist text-primary"></i>
                    Detail Rincian Observasi Per Kategori
                </h5>
                <p class="text-muted extra-small mb-0">Rincian ketepatan waktu rundown, realisasi, aspek penilaian, dan catatan temuan.</p>
            </div>

            {{-- Tabs Navigation --}}
            <ul class="nav nav-pills gap-1 bg-light p-1 rounded-pill no-print" id="observasiDetailTabs" role="tablist">
                @foreach ($events as $index => $ev)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill btn-sm extra-small px-3 py-2 fw-bold {{ $index === 0 ? 'active' : '' }}"
                            id="tab-btn-{{ $ev['id'] }}" data-bs-toggle="tab" data-bs-target="#pane-{{ $ev['id'] }}" type="button" role="tab">
                            {{ $ev['badge'] }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Tabs Content --}}
        <div class="tab-content pt-2" id="observasiDetailTabContent">
            @foreach ($events as $index => $ev)
                @php
                    $data = $rekapData[$ev['id']];
                    $tabTitleBg = match($ev['id']) {
                        'h1' => 'background-color: #0d6efd;',
                        'h2' => 'background-color: #0284c7;',
                        'feb' => 'background-color: #F5A524;',
                        'fst' => 'background-color: #9F1521;',
                        'fikes' => 'background-color: #823ca2;',
                        default => 'background-color: #475569;',
                    };
                @endphp
                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="pane-{{ $ev['id'] }}" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge text-white px-3 py-2 rounded-pill fs-6" style="{{ $tabTitleBg }}">{{ $ev['title'] }}</span>
                            <span class="text-muted extra-small">({{ $data['count'] }} Kegiatan Diobservasi)</span>
                        </div>

                        {{-- Per-Sesi Export Actions & Search --}}
                        <div class="d-flex align-items-center gap-2 flex-wrap no-print">
                            <button type="button" onclick="exportObservasiToExcel('table-detail-{{ $ev['id'] }}', 'Observasi_Acara_{{ str_replace(' ', '_', $ev['badge']) }}')" class="btn btn-outline-success btn-sm rounded-pill px-3">
                                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                            </button>
                            <button type="button" onclick="downloadTableAsPNG('pane-{{ $ev['id'] }}', 'Tabel_Observasi_{{ str_replace(' ', '_', $ev['badge']) }}')" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                <i class="bi bi-download me-1"></i> Download PNG
                            </button>
                            <div class="position-relative">
                                <input type="text" class="form-control form-control-sm rounded-pill px-3 ps-4 shadow-none border-light obs-table-search"
                                    placeholder="Cari kegiatan / catatan..." style="background: #f8fafc; min-width: 200px; font-size: 0.8rem;">
                                <i class="bi bi-search position-absolute top-50 translate-middle-y ms-2 text-muted opacity-50" style="left: 0;"></i>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-rekap-obs align-middle mb-0 obs-detail-table" id="table-detail-{{ $ev['id'] }}">
                            <thead>
                                <tr>
                                    <th style="width: 45px;">NO</th>
                                    <th style="width: 120px;">RUNDOWN</th>
                                    <th style="width: 120px;">REALISASI</th>
                                    <th>NAMA KEGIATAN</th>
                                    @for ($a = 1; $a <= $data['maxAspek']; $a++)
                                        <th>ASPEK OBSERVASI {{ $a }}</th>
                                    @endfor
                                    <th style="width: 95px;">SKALA</th>
                                    <th>CATATAN / TEMUAN</th>
                                    <th style="width: 120px;">DOKUMEN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data['items'] as $itemIndex => $item)
                                    <tr class="extra-small">
                                        <td class="text-center text-muted fw-bold">{{ $itemIndex + 1 }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2 py-1">
                                                <i class="bi bi-clock me-1 text-muted"></i> {{ $item->waktu_runddown ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-primary border border-primary border-opacity-25 px-2 py-1">
                                                <i class="bi bi-clock-history me-1"></i> {{ $item->waktu_realisasi ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="fw-bold text-dark search-target">
                                            {{ $item->kegiatan }}
                                        </td>
                                        @for ($a = 0; $a < $data['maxAspek']; $a++)
                                            <td class="search-target">
                                                @if (isset($item->aspek_list[$a]))
                                                    <span class="text-dark">{{ $item->aspek_list[$a] }}</span>
                                                @else
                                                    <span class="text-muted opacity-50">-</span>
                                                @endif
                                            </td>
                                        @endfor
                                        <td class="text-center">
                                            @php
                                                $skalaBg = match((int) $item->skala) {
                                                    5 => 'background-color: #00A551;',
                                                    4 => 'background-color: #0d6efd;',
                                                    3 => 'background-color: #f59e0b;',
                                                    2 => 'background-color: #dc3545;',
                                                    default => 'background-color: #475569;',
                                                };
                                            @endphp
                                            <span class="badge text-white px-2 py-1 fs-6 fw-bold" style="{{ $skalaBg }}">
                                                {{ $item->skala ?? '-' }} <i class="bi bi-star-fill small"></i>
                                            </span>
                                        </td>
                                        <td class="search-target">
                                            @if (!empty($item->catatan))
                                                <div class="text-secondary extra-small" style="max-width: 240px; line-height: 1.4;">
                                                    {{ $item->catatan }}
                                                </div>
                                            @else
                                                <span class="text-muted opacity-50">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if (!empty($item->link_dokumen))
                                                @php
                                                    $docLinks = array_values(array_filter(array_map('trim', explode("\n", $item->link_dokumen))));
                                                @endphp
                                                <div class="d-flex flex-wrap justify-content-center gap-1">
                                                    @foreach ($docLinks as $linkIdx => $docLink)
                                                        <a href="{{ $docLink }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-2 py-0 extra-small" title="{{ $docLink }}">
                                                            <i class="bi bi-link-45deg"></i> Link {{ $linkIdx + 1 }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted opacity-50">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="empty-row">
                                        <td colspan="{{ 6 + $data['maxAspek'] }}" class="text-center py-4 text-muted small">Belum ada data observasi untuk sesi ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Legend Reference Card --}}
    <div class="rekap-card mb-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-info-circle-fill text-primary fs-5"></i>
            <h6 class="fw-bold text-dark mb-0">Pedoman Skala & Tingkat Capaian Responden (TCR)</h6>
        </div>
        <div class="row g-3">
            @foreach ($legendSkala as $leg)
                @php
                    $legBg = match((int) $leg['skala']) {
                        5 => 'background-color: #00A551;',
                        4 => 'background-color: #0d6efd;',
                        3 => 'background-color: #f59e0b;',
                        2 => 'background-color: #dc3545;',
                        default => 'background-color: #475569;',
                    };
                @endphp
                <div class="col-12 col-md-6 col-xl-2-4" style="flex: 0 0 20%; max-width: 20%;">
                    <div class="p-3 bg-light rounded-4 border h-100">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge text-white px-2 py-1 fw-bold" style="{{ $legBg }}">Skala {{ $leg['skala'] }}</span>
                            <span class="fw-bold text-muted extra-small">Nilai Mutu {{ $leg['mutu'] }}</span>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">{{ $leg['predikat'] }}</h6>
                        <div class="text-primary extra-small fw-semibold mb-1">{{ $leg['rentang'] }}</div>
                        <p class="text-muted extra-small mb-0" style="line-height: 1.3;">{{ $leg['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('scripts')
    {{-- Highcharts & html2canvas & SheetJS Libraries --}}
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Setup Search on each Detail Tab
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.obs-table-search').forEach(input => {
                input.addEventListener('input', function() {
                    const filter = this.value.toLowerCase().trim();
                    const pane = this.closest('.tab-pane');
                    if (!pane) return;

                    const rows = pane.querySelectorAll('tbody tr:not(.empty-row)');
                    let visibleCount = 0;
                    rows.forEach(row => {
                        const searchCells = row.querySelectorAll('.search-target');
                        let match = false;
                        searchCells.forEach(cell => {
                            if (cell.textContent.toLowerCase().includes(filter)) {
                                match = true;
                            }
                        });

                        if (match || filter === '') {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    const emptyRow = pane.querySelector('tbody tr.empty-row');
                    if (emptyRow) {
                        emptyRow.style.display = (visibleCount === 0) ? '' : 'none';
                    }
                });
            });
        });

        // 1. Export Specific Table to Excel
        function exportObservasiToExcel(tableId, filename) {
            const table = document.getElementById(tableId);
            if (!table || typeof XLSX === 'undefined') {
                Swal.fire('Error', 'Tabel atau pustaka export Excel tidak ditemukan.', 'error');
                return;
            }

            // Clone table and remove action column for clean export
            const cloneTable = table.cloneNode(true);
            cloneTable.querySelectorAll('.no-print').forEach(el => el.remove());

            // Convert link anchors to actual clean URL text
            cloneTable.querySelectorAll('td a').forEach(a => {
                if (a.href) {
                    a.replaceWith(document.createTextNode(a.href + ' '));
                }
            });

            const wb = XLSX.utils.table_to_book(cloneTable, { sheet: "Observasi Acara", raw: false });
            XLSX.writeFile(wb, `${filename}.xlsx`);
        }

        // 2. Download Table Container as PNG via html2canvas
        function downloadTableAsPNG(elementId, filename) {
            const targetElement = document.getElementById(elementId);
            if (!targetElement) return;

            Swal.fire({
                title: 'Menyiapkan Gambar PNG...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            const noPrintElems = targetElement.querySelectorAll('.no-print');
            noPrintElems.forEach(el => el.style.display = 'none');

            html2canvas(targetElement, {
                scale: 2,
                backgroundColor: '#ffffff',
                useCORS: true,
                logging: false,
            }).then(canvas => {
                noPrintElems.forEach(el => el.style.display = '');
                Swal.close();

                const link = document.createElement('a');
                link.download = `${filename}.png`;
                link.href = canvas.toDataURL('image/png');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }).catch(err => {
                noPrintElems.forEach(el => el.style.display = '');
                Swal.close();
                console.error('Download PNG failed:', err);
                Swal.fire('Error', 'Gagal membuat gambar PNG.', 'error');
            });
        }

        // 3. Download Highcharts as PNG
        function downloadHighchartPNG(chartObj, filename) {
            if (!chartObj) return;
            chartObj.exportChartLocal({
                type: 'image/png',
                filename: filename
            });
        }

        // --- Highcharts Initialization ---
        const eventCategories = [
            @foreach($events as $ev)
                '{{ $ev['badge'] }}',
            @endforeach
        ];

        const tcrValues = [
            @foreach($events as $ev)
                {{ $rekapData[$ev['id']]['tcr'] ?? 0 }},
            @endforeach
        ];

        const skalaValues = [
            @foreach($events as $ev)
                {{ $rekapData[$ev['id']]['avgSkala'] ?? 0 }},
            @endforeach
        ];

        const tcrColors = ['#0d6efd', '#0284c7', '#F5A524', '#9F1521', '#823ca2'];

        // Chart 1: Capaian TCR (%) 3D Column Chart
        const chartTcr3D = Highcharts.chart('chartTcrContainer', {
            chart: {
                type: 'column',
                options3d: {
                    enabled: true,
                    alpha: 0,
                    beta: 0,
                    depth: 30,
                    viewDistance: 25
                },
                backgroundColor: '#ffffff'
            },
            title: {
                text: 'Capaian TCR (%) Observasi Acara',
                style: { fontSize: '16px', fontWeight: 'bold', fontFamily: 'Poppins, sans-serif', color: '#0f172a' }
            },
            subtitle: {
                text: 'Rata-Rata Capaian Keseluruhan: {{ $overallTcr }}% ({{ $overallKategori }})'
            },
            xAxis: {
                categories: eventCategories,
                labels: {
                    style: { fontSize: '12px', fontWeight: '700', color: '#334155' }
                }
            },
            yAxis: {
                title: { text: 'Tingkat Capaian Responden (%)' },
                max: 105,
                labels: { format: '{value:.1f}%' },
                plotLines: [{
                    value: {{ $overallTcr }},
                    color: '#00A551',
                    width: 2,
                    dashStyle: 'ShortDash',
                    zIndex: 5,
                    label: {
                        text: 'Rerata: {{ $overallTcr }}%',
                        align: 'right',
                        style: { color: '#00A551', fontWeight: 'bold', fontSize: '11px' }
                    }
                }]
            },
            tooltip: {
                pointFormat: 'TCR: <b>{point.y:.2f}%</b>'
            },
            plotOptions: {
                column: {
                    depth: 30,
                    colorByPoint: true,
                    colors: tcrColors,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:.2f}%',
                        style: { fontSize: '12px', fontWeight: 'bold', color: '#0f172a', textOutline: '2px #ffffff' }
                    }
                }
            },
            series: [{
                name: 'TCR (%)',
                data: tcrValues,
                showInLegend: false
            }],
            exporting: { enabled: false },
            credits: { enabled: false }
        });

        // Chart 2: Rata-Rata Skala Penilaian (1-5) 3D Bar Chart
        const chartSkor3D = Highcharts.chart('chartSkorContainer', {
            chart: {
                type: 'bar',
                options3d: {
                    enabled: true,
                    alpha: 0,
                    beta: 0,
                    depth: 25,
                    viewDistance: 25
                },
                backgroundColor: '#ffffff'
            },
            title: {
                text: 'Rata-Rata Skala Mutu (Skala 1 - 5.0)',
                style: { fontSize: '16px', fontWeight: 'bold', fontFamily: 'Poppins, sans-serif', color: '#0f172a' }
            },
            subtitle: {
                text: 'Rata-Rata Skala Keseluruhan: {{ $overallAvgSkala }} / 5.0'
            },
            xAxis: {
                categories: eventCategories,
                labels: {
                    style: { fontSize: '12px', fontWeight: '700', color: '#334155' }
                }
            },
            yAxis: {
                title: { text: 'Skor Rata-Rata' },
                max: 5.2,
                labels: { format: '{value:.2f}' }
            },
            tooltip: {
                pointFormat: 'Skor: <b>{point.y:.2f} / 5.0</b>'
            },
            plotOptions: {
                bar: {
                    depth: 25,
                    colorByPoint: true,
                    colors: tcrColors,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:.2f}',
                        style: { fontSize: '11px', fontWeight: 'bold', color: '#0f172a', textOutline: '2px #ffffff' }
                    }
                }
            },
            series: [{
                name: 'Skor Skala (1-5)',
                data: skalaValues,
                showInLegend: false
            }],
            exporting: { enabled: false },
            credits: { enabled: false }
        });
    </script>
@endpush
