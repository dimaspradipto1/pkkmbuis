@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1 class="h4 fw-bold text-dark mb-1">Rekapitulasi Observasi Acara PKKMB</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Rekapitulasi</li>
                        <li class="breadcrumb-item active">Observasi Acara</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold d-inline-flex align-items-center gap-1 shadow-sm" onclick="exportObservasiToExcel()">
                    <i class="bi bi-file-earmark-excel-fill"></i> Export Excel
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold d-inline-flex align-items-center gap-1 shadow-sm" onclick="window.print()">
                    <i class="bi bi-printer-fill"></i> Cetak / Print
                </button>
            </div>
        </div>
    </div>

    <section class="section mt-3">
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

        {{-- Main Content: Master Summary Table --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" id="masterRekapCard">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Tabel 1: Master Rekapitulasi Pelaksanaan & Observasi Acara</h5>
                        <p class="text-muted extra-small mb-0">Ringkasan hasil evaluasi dan penilaian pelaksanaan seluruh sesi PKKMB Universitas Ibnu Sina.</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="masterObservasiTable">
                        <thead>
                            <tr class="text-uppercase extra-small fw-bold" style="background: #f8fafc; color: #012970;">
                                <th class="ps-3 py-3" style="width: 50px;">NO</th>
                                <th>SESI / KATEGORI ACARA</th>
                                <th class="text-center">JUMLAH AGENDA</th>
                                <th class="text-center">RATA-RATA SKOR (1-5)</th>
                                <th class="text-center">CAPAIAN TCR (%)</th>
                                <th class="text-center">PREDIKAT</th>
                                <th class="text-center">DOKUMENTASI</th>
                                <th class="text-end pe-3">AKSI KELOLA</th>
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
                                        'feb' => 'background-color: #d97706;',
                                        'fst' => 'background-color: #00A551;',
                                        'fikes' => 'background-color: #dc2626;',
                                        default => 'background-color: #475569;',
                                    };
                                @endphp
                                <tr class="border-bottom border-light">
                                    <td class="ps-3 fw-bold text-muted">{{ $no++ }}</td>
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
                                        <div class="d-inline-flex flex-column align-items-center" style="min-width: 90px;">
                                            <span class="fw-bold text-dark mb-1">{{ $data['tcr'] }}%</span>
                                            <div class="progress w-100" style="height: 5px;">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $data['tcr'] }}%; {{ $data['tcr'] >= 80 ? 'background-color: #00A551;' : ($data['tcr'] >= 60 ? 'background-color: #0d6efd;' : 'background-color: #f59e0b;') }}"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $kategoriBg = match($data['kategori']) {
                                                'Sangat Baik' => 'background-color: #00A551;',
                                                'Baik' => 'background-color: #0d6efd;',
                                                'Cukup' => 'background-color: #f59e0b;',
                                                'Kurang' => 'background-color: #dc3545;',
                                                default => 'background-color: #475569;',
                                            };
                                        @endphp
                                        <span class="badge text-white px-3 py-1 extra-small rounded-pill" style="{{ $kategoriBg }}">{{ $data['kategori'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-2 py-1 extra-small">
                                            <i class="bi bi-paperclip me-1"></i> {{ $data['docCount'] }} Link
                                        </span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="{{ $ev['manageUrl'] }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 extra-small fw-bold shadow-none">
                                            <i class="bi bi-pencil-square me-1"></i> Kelola
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light fw-bold">
                            <tr class="border-top border-2">
                                <td colspan="2" class="ps-3 py-3 text-uppercase">Rerata Keseluruhan Observasi Acara</td>
                                <td class="text-center">{{ $totalSemuaKegiatan }} Kegiatan</td>
                                <td class="text-center fs-6 text-primary">{{ $overallAvgSkala }} / 5.0</td>
                                <td class="text-center fs-6 text-success">{{ $overallTcr }}%</td>
                                <td class="text-center">
                                    <span class="badge text-white px-3 py-1 rounded-pill" style="{{ $overallKategoriBg }}">{{ $overallKategori }}</span>
                                </td>
                                <td class="text-center">{{ $totalDokumen }} Link</td>
                                <td class="text-end pe-3 text-muted extra-small">Terakumulasi Otomatis</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Detail Per-Sesi Hub (Tabs) --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Detail Rincian Observasi Per Kategori</h5>
                        <p class="text-muted extra-small mb-0">Rincian ketepatan waktu rundown, realisasi, aspek penilaian, dan catatan temuan.</p>
                    </div>

                    {{-- Tabs Navigation --}}
                    <ul class="nav nav-pills gap-1 bg-light p-1 rounded-pill" id="observasiDetailTabs" role="tablist">
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
                                'feb' => 'background-color: #d97706;',
                                'fst' => 'background-color: #00A551;',
                                'fikes' => 'background-color: #dc2626;',
                                default => 'background-color: #475569;',
                            };
                        @endphp
                        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="pane-{{ $ev['id'] }}" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge text-white px-3 py-2 rounded-pill fs-6" style="{{ $tabTitleBg }}">{{ $ev['title'] }}</span>
                                    <span class="text-muted extra-small">({{ $data['count'] }} Kegiatan Diobservasi)</span>
                                </div>
                                <div class="position-relative">
                                    <input type="text" class="form-control form-control-sm rounded-pill px-3 ps-4 shadow-none border-light obs-table-search"
                                        placeholder="Cari kegiatan / catatan..." style="background: #f8fafc; min-width: 220px; font-size: 0.8rem;">
                                    <i class="bi bi-search position-absolute top-50 translate-middle-y ms-2 text-muted opacity-50" style="left: 0;"></i>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0 obs-detail-table">
                                    <thead>
                                        <tr class="text-uppercase extra-small fw-bold" style="background: #f8fafc; color: #012970;">
                                            <th class="ps-3 py-2" style="width: 45px;">NO</th>
                                            <th style="width: 140px;">RUNDOWN</th>
                                            <th style="width: 140px;">REALISASI</th>
                                            <th>NAMA KEGIATAN</th>
                                            <th>ASPEK OBSERVASI</th>
                                            <th class="text-center" style="width: 90px;">SKALA</th>
                                            <th>CATATAN / TEMUAN</th>
                                            <th class="text-center pe-3" style="width: 120px;">DOKUMEN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data['items'] as $itemIndex => $item)
                                            <tr class="extra-small border-bottom border-light">
                                                <td class="ps-3 py-2 text-muted fw-bold">{{ $itemIndex + 1 }}</td>
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
                                                <td class="search-target">
                                                    @if (!empty($item->aspek_observasi))
                                                        <div class="text-muted extra-small" style="max-width: 260px; line-height: 1.4;">
                                                            {!! nl2br(e($item->aspek_observasi)) !!}
                                                        </div>
                                                    @else
                                                        <span class="text-muted opacity-50">-</span>
                                                    @endif
                                                </td>
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
                                                        <div class="text-secondary extra-small" style="max-width: 260px; line-height: 1.4;">
                                                            {{ $item->catatan }}
                                                        </div>
                                                    @else
                                                        <span class="text-muted opacity-50">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center pe-3">
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
                                                <td colspan="8" class="text-center py-4 text-muted small">Belum ada data observasi untuk sesi ini.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Legend Reference Card --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 pb-2">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-info-circle-fill me-2 text-primary"></i> Pedoman Skala & Tingkat Capaian Responden (TCR)</h6>
            </div>
            <div class="card-body px-4 pb-4">
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
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Setup live search on each detail tab
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

        function exportObservasiToExcel() {
            const table = document.getElementById('masterObservasiTable');
            if (!table || typeof XLSX === 'undefined') {
                alert('Pustaka Excel belum siap. Silakan coba kembali.');
                return;
            }

            const wb = XLSX.utils.table_to_book(table, { sheet: "Master Rekap Observasi" });
            XLSX.writeFile(wb, "Rekapitulasi_Observasi_Acara_PKKMB_UIS.xlsx");
        }
    </script>
@endpush
