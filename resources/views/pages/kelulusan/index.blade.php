@extends('dashboard.template')

@section('content')
    <style>
        .kpi-card {
            transition: all 0.25s ease-in-out;
        }
        .kpi-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important;
        }
        .checklist-item {
            padding: 10px 16px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: background 0.2s ease;
        }
        .checklist-item:hover {
            background: #f1f5f9;
        }
        /* Button & Badge Contrast Rules */
        .bg-success, .badge.bg-success, .btn-success, .badge-success {
            color: #ffffff !important;
        }
        .badge.bg-success *, .btn-success * {
            color: #ffffff !important;
        }
        #kelulusan-table th {
            white-space: nowrap !important;
            vertical-align: middle !important;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        #kelulusan-table td {
            vertical-align: middle !important;
        }
        #kelulusan-table .btn {
            white-space: nowrap !important;
        }
    </style>

    <div class="pagetitle">
        <h1>Hasil Kelulusan & Sertifikat Mahasiswa</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Rekapitulasi & Laporan</li>
                <li class="breadcrumb-item active">Hasil Kelulusan</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        {{-- Panduan Card --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert border-0 bg-dark text-white rounded-4 p-3 p-md-4 shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle p-2 bg-warning bg-opacity-20 text-warning d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px;">
                            <i class="bi bi-info-circle-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-warning mb-1">Informasi Kelulusan & Penerbitan Sertifikat</h6>
                            <p class="mb-0 opacity-90 small" style="line-height: 1.6;">
                                Halaman ini menampilkan rekapitulasi kelulusan & sertifikat seluruh mahasiswa. Secara sistem, kelulusan ditentukan berdasarkan nilai akumulasi terbobot (Ambang batas: <strong>65.0</strong>) dan penuntasan seluruh komponen penilaian.
                                Tombol <strong>Unduh PNG</strong> pada tabel dapat digunakan untuk mengunduh berkas sertifikat resmi beresolusi tinggi langsung.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KPI Summary Stats --}}
        <div class="row g-3 mb-4">
            {{-- Total Mahasiswa --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 kpi-card" style="background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%); border-left: 4px solid #0284c7 !important;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted extra-small fw-bold text-uppercase tracking-wider">Total Mahasiswa</span>
                            <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($stats['totalMahasiswa']) }}</h3>
                            <span class="badge bg-primary text-white extra-small mt-1" style="background: #0284c7 !important;">
                                <i class="bi bi-person-check me-1 text-white"></i> Terdaftar Aktif
                            </span>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px; background: linear-gradient(135deg, #0284c7, #0369a1); color: #fff;">
                            <i class="bi bi-people-fill fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Lulus --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 kpi-card" style="background: linear-gradient(135deg, #ffffff 0%, #ecfdf5 100%); border-left: 4px solid #10b981 !important;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted extra-small fw-bold text-uppercase tracking-wider">Mahasiswa Lulus</span>
                            <h3 class="fw-bold text-success mb-0 mt-1">{{ number_format($stats['totalLulus']) }}</h3>
                            <span class="badge bg-success text-white extra-small mt-1 shadow-sm" style="background: #10b981 !important;">
                                <i class="bi bi-trophy-fill me-1 text-white"></i> Memenuhi Syarat
                            </span>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px; background: linear-gradient(135deg, #10b981, #059669); color: #fff;">
                            <i class="bi bi-patch-check-fill fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Tidak Lulus / Belum Lengkap --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 kpi-card" style="background: linear-gradient(135deg, #ffffff 0%, #fff1f2 100%); border-left: 4px solid #ef4444 !important;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted extra-small fw-bold text-uppercase tracking-wider">Tidak Lulus / Belum Lengkap</span>
                            <h3 class="fw-bold text-danger mb-0 mt-1">{{ number_format($stats['totalTidakLulus'] + $stats['totalBelumLengkap']) }}</h3>
                            <span class="text-muted extra-small mt-1 d-block" style="font-size: 0.72rem;">
                                <b>{{ $stats['totalTidakLulus'] }}</b> Tidak Lulus &bull; <b>{{ $stats['totalBelumLengkap'] }}</b> Belum Lengkap
                            </span>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px; background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff;">
                            <i class="bi bi-x-circle-fill fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tingkat Kelulusan --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 kpi-card" style="background: linear-gradient(135deg, #ffffff 0%, #faf5ff 100%); border-left: 4px solid #8b5cf6 !important;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="w-100 pe-2">
                            <span class="text-muted extra-small fw-bold text-uppercase tracking-wider">Tingkat Kelulusan</span>
                            <div class="d-flex align-items-baseline gap-2 mt-1">
                                <h3 class="fw-bold text-dark mb-0">{{ $stats['passRate'] }}%</h3>
                                <span class="badge bg-purple bg-opacity-10 text-primary extra-small fw-semibold py-1">
                                    {{ $stats['countSertifikatIssued'] }} Sertifikat
                                </span>
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $stats['passRate'] }}%;" aria-valuenow="{{ $stats['passRate'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 48px; height: 48px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: #fff;">
                            <i class="bi bi-award-fill fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Checklist Kelengkapan Requirements Widget --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                                    <i class="bi bi-list-check fs-6"></i>
                                </span>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">CHECKLIST KELENGKAPAN PERSYARATAN KELULUSAN</h6>
                                    <span class="text-muted extra-small">Ringkasan penuntasan 6 komponen syarat kelulusan seluruh mahasiswa</span>
                                </div>
                            </div>
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill extra-small">
                                <i class="bi bi-shield-lock-fill text-warning me-1"></i> Standar Audit Sistem PKKMB
                            </span>
                        </div>

                        <div class="row g-3">
                            {{-- 1. Absensi --}}
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="checklist-item d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-calendar-check text-primary fs-5"></i>
                                        <div>
                                            <div class="fw-bold text-dark small">Absensi Kehadiran</div>
                                            <span class="text-muted extra-small">Target: 6 Sesi</span>
                                        </div>
                                    </div>
                                    <span class="badge {{ $stats['countCompleteAbsensi'] >= $stats['totalMahasiswa'] && $stats['totalMahasiswa'] > 0 ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill px-3 py-2 fw-bold">
                                        {{ $stats['countCompleteAbsensi'] }}/{{ $stats['totalMahasiswa'] }} Selesai
                                    </span>
                                </div>
                            </div>

                            {{-- 2. Kedisiplinan --}}
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="checklist-item d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-shield-check text-success fs-5"></i>
                                        <div>
                                            <div class="fw-bold text-dark small">Penilaian Kedisiplinan</div>
                                            <span class="text-muted extra-small">Target: 3 Hari</span>
                                        </div>
                                    </div>
                                    <span class="badge {{ $stats['countCompleteKedisiplinan'] >= $stats['totalMahasiswa'] && $stats['totalMahasiswa'] > 0 ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill px-3 py-2 fw-bold">
                                        {{ $stats['countCompleteKedisiplinan'] }}/{{ $stats['totalMahasiswa'] }} Selesai
                                    </span>
                                </div>
                            </div>

                            {{-- 3. Pre-Test --}}
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="checklist-item d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-pencil-square text-info fs-5"></i>
                                        <div>
                                            <div class="fw-bold text-dark small">Ujian Pre-Test</div>
                                            <span class="text-muted extra-small">Target: {{ $stats['totalActivePre'] }} Tes Modul</span>
                                        </div>
                                    </div>
                                    <span class="badge {{ $stats['countCompletePretest'] >= $stats['totalMahasiswa'] && $stats['totalMahasiswa'] > 0 ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill px-3 py-2 fw-bold">
                                        {{ $stats['countCompletePretest'] }}/{{ $stats['totalMahasiswa'] }} Selesai
                                    </span>
                                </div>
                            </div>

                            {{-- 4. Post-Test --}}
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="checklist-item d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-file-earmark-check text-purple fs-5" style="color: #8b5cf6;"></i>
                                        <div>
                                            <div class="fw-bold text-dark small">Ujian Post-Test</div>
                                            <span class="text-muted extra-small">Target: {{ $stats['totalActivePost'] }} Tes Modul</span>
                                        </div>
                                    </div>
                                    <span class="badge {{ $stats['countCompletePosttest'] >= $stats['totalMahasiswa'] && $stats['totalMahasiswa'] > 0 ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill px-3 py-2 fw-bold">
                                        {{ $stats['countCompletePosttest'] }}/{{ $stats['totalMahasiswa'] }} Selesai
                                    </span>
                                </div>
                            </div>

                            {{-- 5. Tugas Kelompok --}}
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="checklist-item d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-journal-text text-warning fs-5"></i>
                                        <div>
                                            <div class="fw-bold text-dark small">Tugas Kelompok</div>
                                            <span class="text-muted extra-small">Target: 1 Tugas</span>
                                        </div>
                                    </div>
                                    <span class="badge {{ $stats['countCompleteTugas'] >= $stats['totalMahasiswa'] && $stats['totalMahasiswa'] > 0 ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill px-3 py-2 fw-bold">
                                        {{ $stats['countCompleteTugas'] }}/{{ $stats['totalMahasiswa'] }} Selesai
                                    </span>
                                </div>
                            </div>

                            {{-- 6. Evaluasi Materi --}}
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="checklist-item d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-clipboard2-check text-danger fs-5"></i>
                                        <div>
                                            <div class="fw-bold text-dark small">Evaluasi Penyampaian Materi</div>
                                            <span class="text-muted extra-small">Target: {{ $stats['requiredEvaluasiTotal'] }} Evaluasi</span>
                                        </div>
                                    </div>
                                    <span class="badge {{ $stats['countCompleteEvaluasi'] >= $stats['totalMahasiswa'] && $stats['totalMahasiswa'] > 0 ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill px-3 py-2 fw-bold">
                                        {{ $stats['countCompleteEvaluasi'] }}/{{ $stats['totalMahasiswa'] }} Selesai
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Table Card --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                            <div>
                                <h5 class="card-title mb-0 fs-5">Daftar Hasil Kelulusan & Sertifikat Mahasiswa</h5>
                                <span class="text-muted extra-small">Tabel status kelulusan, nomor registrasi sertifikat, dan unduh sertifikat PNG</span>
                            </div>

                            @if (in_array(Auth::user()->role, ['admin', 'stafbaak']))
                                <div class="d-flex gap-2">
                                    <form action="{{ route('kelulusan.bulkToggle') }}" method="POST" class="d-inline"
                                          data-confirm-title="Konfirmasi Buka Semua Sertifikat"
                                          data-confirm-btn="Ya, Buka Semua!"
                                          data-confirm-color="#198754"
                                          data-confirm-icon="question"
                                          onsubmit="return confirm('Yakin ingin MENAMPILKAN / MEMBUKA sertifikat & hasil kelulusan untuk SELURUH mahasiswa?')">
                                        @csrf
                                        <input type="hidden" name="action" value="enable_all">
                                        <button type="submit" class="btn btn-success btn-sm rounded-pill shadow-sm px-3 fw-bold">
                                            <i class="bi bi-eye-fill me-1"></i> Buka Semua Sertifikat
                                        </button>
                                    </form>
                                    <form action="{{ route('kelulusan.bulkToggle') }}" method="POST" class="d-inline"
                                          data-confirm-title="Konfirmasi Tutup Semua Sertifikat"
                                          data-confirm-btn="Ya, Tutup Semua!"
                                          data-confirm-color="#dc3545"
                                          data-confirm-icon="warning"
                                          onsubmit="return confirm('Yakin ingin MENYEMBUNYIKAN / MENUTUP sertifikat & hasil kelulusan untuk SELURUH mahasiswa?')">
                                        @csrf
                                        <input type="hidden" name="action" value="disable_all">
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill shadow-sm px-3 fw-bold">
                                            <i class="bi bi-eye-slash-fill me-1"></i> Tutup Semua Sertifikat
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        {{-- Filter Bar --}}
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 p-3 bg-light rounded-4 border">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <span class="fw-bold text-dark extra-small text-uppercase"><i class="bi bi-funnel-fill text-primary me-1"></i> Filter Status:</span>
                                <input type="hidden" id="statusFilterSelect" value="">
                                <div class="btn-group btn-group-sm rounded-pill overflow-hidden border" role="group" id="statusFilterBtnGroup">
                                    <button type="button" class="btn btn-primary active status-filter-btn px-3 fw-semibold" data-status="">
                                        Semua ({{ $stats['totalMahasiswa'] }})
                                    </button>
                                    <button type="button" class="btn btn-outline-success status-filter-btn px-3 fw-semibold" data-status="lulus">
                                        <i class="bi bi-check-circle-fill me-1"></i> Lulus ({{ $stats['totalLulus'] }})
                                    </button>
                                    <button type="button" class="btn btn-outline-danger status-filter-btn px-3 fw-semibold" data-status="tidak_lulus">
                                        <i class="bi bi-x-circle-fill me-1"></i> Tidak Lulus ({{ $stats['totalTidakLulus'] }})
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary status-filter-btn px-3 fw-semibold" data-status="belum_lengkap">
                                        <i class="bi bi-clock-history me-1"></i> Belum Lengkap ({{ $stats['totalBelumLengkap'] }})
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold" onclick="resetStatusFilter()">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                            </button>
                        </div>

                        <div class="table-responsive">
                            {!! $dataTable->table(['class' => 'table table-hover align-middle table-striped-columns w-100', 'id' => 'kelulusan-table']) !!}
                        </div>

                        {{-- Legend matching Image 3 --}}
                        <div class="d-flex align-items-center flex-wrap gap-2 mt-3 pt-3 border-top">
                            <span class="fw-bold text-secondary extra-small text-uppercase">KETERANGAN:</span>
                            <span class="badge px-3 py-2 rounded-pill text-white fw-bold shadow-sm" style="background: #15803d !important;">
                                Hijau: Sertifikat Resmi Siap Diunduh
                            </span>
                            <span class="badge bg-secondary bg-opacity-75 px-3 py-2 rounded-pill text-white fw-semibold" style="background: #64748b !important;">
                                Abu-abu: Belum Diterbitkan
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Offscreen Hidden Certificate Generator Canvas for HTML2Canvas Downloads --}}
    <div id="hiddenCertContainer" style="position: fixed; left: -9999px; top: 0; width: 1000px; height: 707px; overflow: hidden; z-index: -1;">
        @php
            $defaultSetting = \App\Models\SertifikatSetting::current();
        @endphp
        @include('partials.sertifikat-card', [
            'canvasId' => 'adminOffscreenCertCanvas',
            'nomorUrut' => '0000',
            'kodeSurat' => $defaultSetting->kode_surat ?? 'UIS.PKKMB/SF/VII/2026',
            'namaMahasiswa' => 'Nama Mahasiswa',
            'npm' => '00000000',
            'prodi' => 'PROGRAM STUDI',
            'fakultas' => 'FAKULTAS',
            'statusLulus' => true,
            'namaKegiatan' => $defaultSetting->nama_kegiatan ?? 'Pengenalan Kehidupan Kampus Mahasiswa Baru (PKKMB)',
            'lokasi' => $defaultSetting->lokasi ?? 'Batam',
            'tanggal' => $defaultSetting->tanggal_pelaksanaan ?? date('d F Y'),
            'namaMengetahui' => $defaultSetting->nama_mengetahui ?? 'Rektor',
            'jabatanMengetahui' => $defaultSetting->jabatan_mengetahui ?? 'Rektor Universitas Ibnu Sina',
            'nipMengetahui' => $defaultSetting->nip_mengetahui ?? '',
            'namaKetuaPanitia' => $defaultSetting->nama_ketua_panitia ?? 'Ketua Panitia',
            'jabatanKetuaPanitia' => $defaultSetting->jabatan_ketua_panitia ?? 'Ketua Panitia PKKMB',
            'nupKetuaPanitia' => $defaultSetting->nup_ketua_panitia ?? '',
            'logoDikti' => $defaultSetting->logo_dikti ? asset('storage/' . $defaultSetting->logo_dikti) : null,
            'logoBelmawa' => $defaultSetting->logo_belmawa ? asset('storage/' . $defaultSetting->logo_belmawa) : null,
            'logoPkkmb' => $defaultSetting->logo_pkkmb ? asset('storage/' . $defaultSetting->logo_pkkmb) : asset('assets/img/logopkkmb.png'),
            'logoKampus' => $defaultSetting->logo_kampus ? asset('storage/' . $defaultSetting->logo_kampus) : asset('assets/img/logo_ibsi.png'),
            'logoLima' => $defaultSetting->logo_lima ? asset('storage/' . $defaultSetting->logo_lima) : null,
            'verifikasiUrl' => '',
        ])
    </div>
@endsection

@push('scripts')
    @if(app()->environment('production'))
        {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
    @else
        {!! $dataTable->scripts() !!}
    @endif

    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        $(document).on('click', '.status-filter-btn', function() {
            $('.status-filter-btn').removeClass('active btn-primary btn-success btn-danger btn-secondary text-white');
            $('.status-filter-btn').each(function() {
                const s = $(this).data('status');
                if (s === 'lulus') $(this).addClass('btn-outline-success');
                else if (s === 'tidak_lulus') $(this).addClass('btn-outline-danger');
                else if (s === 'belum_lengkap') $(this).addClass('btn-outline-secondary');
                else $(this).addClass('btn-outline-primary');
            });

            $(this).addClass('active text-white');
            const selectedStatus = $(this).data('status');
            if (selectedStatus === 'lulus') $(this).removeClass('btn-outline-success').addClass('btn-success');
            else if (selectedStatus === 'tidak_lulus') $(this).removeClass('btn-outline-danger').addClass('btn-danger');
            else if (selectedStatus === 'belum_lengkap') $(this).removeClass('btn-outline-secondary').addClass('btn-secondary');
            else $(this).removeClass('btn-outline-primary').addClass('btn-primary');

            $('#statusFilterSelect').val(selectedStatus);
            if (window.LaravelDataTables && window.LaravelDataTables['kelulusan-table']) {
                window.LaravelDataTables['kelulusan-table'].ajax.reload();
            }
        });

        function resetStatusFilter() {
            $('.status-filter-btn[data-status=""]').click();
        }

        function downloadMahasiswaSertifikat(userId, btn) {
            const originalLabel = btn ? btn.innerHTML : null;
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1 text-white"></span> Mengunduh...';
            }

            fetch("{{ url('kelulusan/sertifikat-data') }}/" + userId)
                .then(response => {
                    if (!response.ok) throw new Error('Gagal mengambil data sertifikat');
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        alert('Data sertifikat tidak valid');
                        return;
                    }

                    const canvas = document.getElementById('adminOffscreenCertCanvas');
                    if (!canvas) return;

                    // Update canvas fields
                    const nomorEl = canvas.querySelector('.sertifikat-nomor');
                    if (nomorEl) nomorEl.innerHTML = 'NO : ' + data.nomorUrut + '/<span data-field="kode_surat">' + data.kodeSurat + '</span>';

                    const nameEl = canvas.querySelector('.sertifikat-namebox');
                    if (nameEl) nameEl.textContent = data.namaMahasiswa;

                    const npmEl = canvas.querySelector('.sertifikat-npm');
                    if (npmEl) npmEl.textContent = 'NIM.' + data.npm;

                    const prodiEl = canvas.querySelector('.sertifikat-prodi');
                    if (prodiEl) prodiEl.textContent = 'PROGRAM STUDI ' + (data.prodi || '-').toUpperCase();

                    const fakEl = canvas.querySelector('.sertifikat-fakultas');
                    if (fakEl) fakEl.textContent = (data.fakultas || '-').toUpperCase() + ' DINYATAKAN';

                    const statusEl = canvas.querySelector('.sertifikat-status');
                    if (statusEl) {
                        statusEl.className = 'sertifikat-status ' + (data.statusLulus ? 'is-lulus' : 'is-tidak-lulus');
                        statusEl.textContent = data.statusLulus ? 'LULUS' : 'TIDAK LULUS';
                    }

                    // Update QR Code
                    const qrEl = document.getElementById('sertifikatQr-adminOffscreenCertCanvas');
                    if (qrEl && data.verifikasiUrl) {
                        qrEl.innerHTML = '';
                        new QRCode(qrEl, {
                            text: data.verifikasiUrl,
                            width: 300,
                            height: 300,
                            correctLevel: QRCode.CorrectLevel.H
                        });
                    }

                    // Reset transform before capture
                    const origTransform = canvas.style.transform;
                    canvas.style.transform = 'none';

                    setTimeout(function() {
                        html2canvas(canvas, {
                            scale: 4,
                            useCORS: true,
                            backgroundColor: '#ffffff',
                            width: 1000,
                            height: 707
                        }).then(function(renderedCanvas) {
                            const cleanName = data.namaMahasiswa.replace(/[^a-zA-Z0-9_-]/g, '_');
                            const link = document.createElement('a');
                            link.download = 'Sertifikat-Kelulusan-' + cleanName + '.png';
                            link.href = renderedCanvas.toDataURL('image/png');
                            link.click();
                        }).catch(function(err) {
                            console.error('HTML2Canvas Error:', err);
                            alert('Gagal mengekspor sertifikat PNG.');
                        }).finally(function() {
                            canvas.style.transform = origTransform;
                            if (btn) {
                                btn.disabled = false;
                                btn.innerHTML = originalLabel;
                            }
                        });
                    }, 250);
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    alert('Terjadi kesalahan saat memproses data sertifikat.');
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = originalLabel;
                    }
                });
        }
    </script>
@endpush
