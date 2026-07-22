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
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar Sub-Menu Evaluasi Penyampaian Materi</h5>
                    <p class="text-muted small">Kelola status (is_active) untuk setiap menu. Menu yang aktif (is_active = true) akan dapat diakses dan tampil untuk mahasiswa.</p>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;" class="text-center">No</th>
                                    <th>Nama Sub-Menu Evaluasi</th>
                                    <th style="width: 160px;" class="text-center">Status (is_active)</th>
                                    <th style="width: 160px;" class="text-center">Aksi Toggle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($menus as $menu)
                                    <tr>
                                        <td class="text-center font-monospace fw-bold">{{ $menu->nomor }}</td>
                                        <td>
                                            <span class="{{ $menu->is_active ? 'fw-bold text-primary' : 'text-secondary' }}">
                                                {{ $menu->nama }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($menu->is_active)
                                                <span class="badge bg-success px-3 py-2"><i class="bi bi-check-circle me-1"></i> Aktif</span>
                                            @else
                                                <span class="badge bg-secondary px-3 py-2"><i class="bi bi-x-circle me-1"></i> Non-Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('evaluasimenu.toggle', $menu->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $menu->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                    @if($menu->is_active)
                                                        <i class="bi bi-toggle-on me-1"></i> Nonaktifkan
                                                    @else
                                                        <i class="bi bi-toggle-off me-1"></i> Aktifkan
                                                    @endif
                                                </button>
                                            </form>
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
