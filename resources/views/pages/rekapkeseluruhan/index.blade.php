@extends('dashboard.template')

@section('content')
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <div>
            <h1>Rekapitulasi Keseluruhan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Rekapitulasi</li>
                    <li class="breadcrumb-item active">Master Report</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('rekapkeseluruhan.export') }}" class="btn btn-success text-white d-flex align-items-center gap-2 px-4 py-2 shadow-sm rounded-pill">
            <i class="bi bi-file-earmark-spreadsheet-fill fs-5"></i>
            <span class="text-uppercase fw-bold ls-1">Export Laporan Lengkap (.xlsx)</span>
        </a>
    </div>

    <section class="section">
        <!-- Summary Cards: Total Mahasiswa per Komponen -->
        <div class="row g-3 mb-4">
            <!-- Card 1: Total Mahasiswa -->
            <div class="col-12 col-sm-6 col-md-4 col-xl-2">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted extra-small fw-bold text-uppercase tracking-wider">Total Mahasiswa</span>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(2, 132, 199, 0.1); color: #0284c7;">
                            <i class="bi bi-people-fill fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark my-1">{{ number_format($stats['totalMahasiswa']) }}</h3>
                    <div class="text-muted extra-small mt-1">
                        <span class="text-secondary">Mahasiswa Terdaftar</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Test (10%) -->
            <div class="col-12 col-sm-6 col-md-4 col-xl-2">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted extra-small fw-bold text-uppercase tracking-wider">Selesai Test (10%)</span>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(79, 70, 229, 0.1); color: #4f46e5;">
                            <i class="bi bi-file-earmark-text-fill fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark my-1">{{ number_format($stats['countTest']) }}</h3>
                    <div class="d-flex align-items-center justify-content-between extra-small mt-1">
                        <span class="text-muted">{{ $stats['pctTest'] }}% partisipasi</span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Tugas (10%) -->
            <div class="col-12 col-sm-6 col-md-4 col-xl-2">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted extra-small fw-bold text-uppercase tracking-wider">Kumpul Tugas (10%)</span>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(147, 51, 234, 0.1); color: #9333ea;">
                            <i class="bi bi-folder-check fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark my-1">{{ number_format($stats['countTugas']) }}</h3>
                    <div class="d-flex align-items-center justify-content-between extra-small mt-1">
                        <span class="text-muted">{{ $stats['pctTugas'] }}% submit tugas</span>
                    </div>
                </div>
            </div>

            <!-- Card 4: Kehadiran (50%) -->
            <div class="col-12 col-sm-6 col-md-4 col-xl-2">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted extra-small fw-bold text-uppercase tracking-wider">Hadir Absen (50%)</span>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(5, 150, 105, 0.1); color: #059669;">
                            <i class="bi bi-person-check-fill fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark my-1">{{ number_format($stats['countAbsensi']) }}</h3>
                    <div class="d-flex align-items-center justify-content-between extra-small mt-1">
                        <span class="text-muted">{{ $stats['pctAbsensi'] }}% kehadiran</span>
                    </div>
                </div>
            </div>

            <!-- Card 5: Kedisiplinan (30%) -->
            <div class="col-12 col-sm-6 col-md-4 col-xl-2">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted extra-small fw-bold text-uppercase tracking-wider">Disiplin (30%)</span>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(217, 119, 6, 0.1); color: #d97706;">
                            <i class="bi bi-shield-check fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark my-1">{{ number_format($stats['countDisiplin']) }}</h3>
                    <div class="d-flex align-items-center justify-content-between extra-small mt-1">
                        <span class="text-muted">{{ $stats['pctDisiplin'] }}% tercatat</span>
                    </div>
                </div>
            </div>

            <!-- Card 6: Kelulusan -->
            <div class="col-12 col-sm-6 col-md-4 col-xl-2">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted extra-small fw-bold text-uppercase tracking-wider">Status Kelulusan</span>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(22, 163, 74, 0.1); color: #16a34a;">
                            <i class="bi bi-patch-check-fill fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success my-1">{{ number_format($stats['passedCount']) }}</h3>
                    <div class="d-flex align-items-center justify-content-between extra-small mt-1">
                        <span class="text-success fw-semibold">{{ $stats['passRate'] }}% Lulus</span>
                        <span class="text-muted">({{ $stats['notPassedCount'] }} Belum)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unified Info Legend -->
        <div class="row">
            <div class="col-12">
                <div class="alert border-0 bg-dark text-white rounded-4 p-4 mb-4 d-flex align-items-start gap-3 shadow-lg" style="border-left: 6px solid var(--uis-green) !important; background: #0f172a !important;">
                    <div class="bg-success-subtle rounded-circle p-2">
                        <i class="bi bi-info-circle-fill fs-4 text-emerald"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Panduan Atribut Akademik & Pembobotan</h6>
                        <p class="mb-0 opacity-75 small">
                            <strong>A:</strong> Absensi (Datang/Pulang) | <strong>D:</strong> Kedisiplinan Atribut | <strong>M:</strong> Grade Pretest/Posttest | <strong>M5:</strong> Tugas Kelompok. 
                            <br>
                            <span class="text-warning fw-bold">Bobot Akhir: Test/Tugas (20%) + Absensi (50%) + Disiplin (30%).</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Table (Hidden but kept for Excel Export) -->
        <div class="row d-none">
            <div class="col-lg-12">
                <div class="card shadow-lg border-0" style="border-radius: 20px; background: rgba(255,255,255,1);">
                    <div class="card-body px-4 py-4">
                        <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                            <i class="bi bi-journal-text text-emerald"></i>
                            Detail Aktivitas & Nilai Pre/Post Test
                        </h5>
                        <div id="detailed-table-container" class="table-responsive">
                            {!! $dtDetailed->table(['width' => '100%', 'class' => 'table table-hover align-middle table-rekap']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Final Recap Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-lg border-0" style="border-radius: 20px; background: rgba(255,255,255,1);">
                    <div class="card-body px-4 py-4">
                        <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                            <i class="bi bi-award text-emerald"></i>
                            Rekapitulasi Nilai Akhir Terbobot
                        </h5>
                        <div id="final-table-container" class="table-responsive">
                            {!! $dtFinal->table(['width' => '100%', 'class' => 'table table-hover align-middle table-rekap']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
@endsection

@push('scripts')
    {!! $dtDetailed->scripts() !!}
    {!! $dtFinal->scripts() !!}
    <script>
        $(document).on('draw.dt', '#rekapkeseluruhan-table', function() {
            // Synchronize the Final Recap table whenever the Detailed table is redrawn
            const finalTable = $('#rekapnilaiakhir-table').DataTable();
            if (finalTable) {
                finalTable.ajax.reload(null, false); // Reload without resetting pagination
            }
        });
    </script>
@endpush

<style>
    :root {
        --uis-green: #00A551;
        --emerald-dark: #087C39;
        --uis-yellow: #FFF742;
    }

    .btn-synergy-master {
        background: linear-gradient(45deg, var(--emerald-dark), var(--uis-green));
        color: #fff;
        border: none;
        padding: 0.8rem 2rem;
        border-radius: 14px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 20px -5px rgba(0, 165, 81, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.1);
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }

    .btn-synergy-master:hover {
        transform: translateY(-4px) scale(1.03);
        box-shadow: 0 20px 30px -5px rgba(0, 165, 81, 0.6);
        color: #fff;
        background: linear-gradient(45deg, var(--uis-green), var(--emerald-dark));
    }

    .ls-1 { letter-spacing: 1px; }

    .table-rekap thead th {
        background: #f8fafc !important;
        color: #334155 !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 0.65rem !important;
        padding: 1.2rem 0.75rem !important;
        border-bottom: 2px solid #e2e8f0 !important;
        text-align: center !important;
    }

    .table-rekap tbody td {
        padding: 0.8rem 0.5rem !important;
        font-size: 0.8rem !important;
        font-weight: 500;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9 !important;
        text-align: center !important;
    }

    .text-emerald { color: var(--uis-green) !important; }
    
    .card {
        transition: all 0.3s ease;
        border-radius: 20px !important;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
    }
</style>
