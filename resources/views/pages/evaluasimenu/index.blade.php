@extends('dashboard.template')

@section('content')
<div class="pagetitle">
    <h1>Pengaturan Status Aktif Menu Evaluasi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Pengaturan Menu Evaluasi</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                            <div>
                                <h5 class="card-title mb-0 pb-0">Daftar Sub-Menu Evaluasi Penyampaian Materi</h5>
                                <p class="text-muted small mb-0">Kelola status (is_active) untuk setiap menu. Menu yang aktif (is_active = true) akan dapat diakses dan tampil untuk mahasiswa.</p>
                            </div>
                            <div class="d-flex gap-2">
                                <form action="{{ route('evaluasimenu.bulkToggle') }}" method="POST" class="d-inline"
                                      data-confirm-title="Konfirmasi Buka Semua Evaluasi"
                                      data-confirm-btn="Ya, Buka Semua!"
                                      data-confirm-color="#198754"
                                      data-confirm-icon="question"
                                      onsubmit="return confirm('Yakin ingin MEMBUKA / MENGAKTIFKAN SELURUH menu evaluasi untuk mahasiswa?')">
                                    @csrf
                                    <input type="hidden" name="action" value="enable_all">
                                    <button type="submit" class="btn btn-success btn-sm rounded shadow-sm px-3 fw-bold">
                                        <i class="bi bi-eye-fill me-1"></i> Buka Semua Evaluasi
                                    </button>
                                </form>
                                <form action="{{ route('evaluasimenu.bulkToggle') }}" method="POST" class="d-inline"
                                      data-confirm-title="Konfirmasi Tutup Semua Evaluasi"
                                      data-confirm-btn="Ya, Tutup Semua!"
                                      data-confirm-color="#dc3545"
                                      data-confirm-icon="warning"
                                      onsubmit="return confirm('Yakin ingin MENUTUP / MENONAKTIFKAN SELURUH menu evaluasi untuk mahasiswa?')">
                                    @csrf
                                    <input type="hidden" name="action" value="disable_all">
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded shadow-sm px-3 fw-bold">
                                        <i class="bi bi-eye-slash-fill me-1"></i> Tutup Semua Evaluasi
                                    </button>
                                </form>
                            </div>
                        </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;" class="text-center">No</th>
                                    <th>Nama Sub-Menu Evaluasi</th>
                                    <th style="width: 160px;" class="text-center">Status</th>
                                    <th style="width: 240px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($menus as $menu)
                                    <tr>
                                        <td class="text-center font-monospace fw-bold">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                <span class="{{ $menu->is_active ? 'fw-bold text-primary' : 'text-secondary' }}">
                                                    {{ $loop->iteration }}. {{ $menu->clean_nama }}
                                                </span>
                                                @if($menu->isFacultyMenu())
                                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-2.5 py-1" style="font-size: 0.75rem;">
                                                        <i class="bi bi-mortarboard-fill me-1"></i> Khusus {{ $menu->faculty_code }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border rounded-pill px-2.5 py-1" style="font-size: 0.75rem;">
                                                        <i class="bi bi-globe2 me-1"></i> Evaluasi Umum
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($menu->is_active)
                                                <span class="badge bg-success px-3 py-2"><i class="bi bi-check-circle me-1"></i> Aktif</span>
                                            @else
                                                <span class="badge bg-secondary px-3 py-2"><i class="bi bi-x-circle me-1"></i> Non-Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('evaluasimenu.questions', $menu->id) }}" class="btn btn-sm btn-primary px-3 rounded shadow-sm">
                                                    <i class="bi bi-pencil-square me-1"></i> Kelola Soal
                                                </a>
                                                <form action="{{ route('evaluasimenu.toggle', $menu->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $menu->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" title="Toggle Status">
                                                        @if($menu->is_active)
                                                            <i class="bi bi-toggle-on"></i>
                                                        @else
                                                            <i class="bi bi-toggle-off"></i>
                                                        @endif
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
