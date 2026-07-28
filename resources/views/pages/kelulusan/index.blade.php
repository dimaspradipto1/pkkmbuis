@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Status Kelulusan Mahasiswa</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Status Kelulusan</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="alert border-0 bg-dark text-white rounded-4 p-4 mb-4 shadow-lg" style="background: #0f172a !important;">
                    <h6 class="fw-bold mb-1"><i class="bi bi-info-circle-fill text-warning me-2"></i>Panduan</h6>
                    <p class="mb-0 opacity-75 small">
                        Tabel ini menampilkan rekapitulasi kelengkapan & status kelulusan seluruh mahasiswa, identik dengan kartu "Status Kelulusan" yang mereka lihat di dashboard. Secara normal, nilai akhir & predikat (Lulus/Tidak Lulus) baru tampil ke mahasiswa setelah <strong>seluruh</strong> komponen (absensi, kedisiplinan, pretest, posttest, tugas, evaluasi) lengkap.
                        Kolom <strong>Paksa Tampilkan Hasil</strong> adalah pengecualian manual per mahasiswa: jika diaktifkan, nilai & predikat kelulusan akan tetap tampil di dashboard mahasiswa tersebut <strong>meskipun ada komponen yang belum lengkap</strong>.
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Status Kelulusan</h5>
                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @if(app()->environment('production'))
        {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
    @else
        {!! $dataTable->scripts() !!}
    @endif
@endpush
