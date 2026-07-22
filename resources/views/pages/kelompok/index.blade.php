@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Manajemen Kelompok</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Manajemen Kelompok</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title p-0 m-0 fw-bold text-primary">Daftar Kelompok PKKMB</h5>
                            @if (in_array(Auth::user()->role, ['admin', 'stafbaak', 'pimpinan']))
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-success rounded px-3" data-bs-toggle="modal" data-bs-target="#importExcelModal">
                                        <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
                                    </button>
                                    <a href="{{ route('kelompok.create') }}" class="btn btn-primary rounded px-3">
                                        <i class="bi bi-plus-circle me-1"></i> Buat Kelompok Baru
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">NO</th>
                                        <th>Nama Kelompok</th>
                                        <th>Pendamping / Kakak Leting</th>
                                        <th class="text-center">Jumlah Anggota</th>
                                        <th>Keterangan</th>
                                        <th class="text-center" style="width: 180px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kelompoks as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="fw-bold text-dark">{{ $item->nama_kelompok }}</td>
                                            <td>
                                                @if($item->pendamping)
                                                    <span class="badge bg-light text-primary border border-primary border-opacity-25 px-2 py-1">
                                                        <i class="bi bi-person-badge me-1"></i>{{ $item->pendamping->name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted small">Belum Ditentukan</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info text-dark px-3 py-1 rounded-pill">
                                                    {{ $item->anggota_count }} Mahasiswa
                                                </span>
                                            </td>
                                            <td><small class="text-muted">{{ $item->keterangan ?? '-' }}</small></td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center gap-1">
                                                    <a href="{{ route('kelompok.show', $item->slug) }}" class="btn btn-sm btn-info text-white px-3 rounded d-inline-flex align-items-center gap-1" title="Lihat & Kelola Anggota">
                                                        <i class="bi bi-people"></i> <span>Anggota</span>
                                                    </a>
                                                    @if(in_array(Auth::user()->role, ['admin', 'stafbaak', 'pimpinan']))
                                                        <a href="{{ route('kelompok.edit', $item->slug) }}" class="btn btn-sm btn-warning text-white px-3 rounded d-inline-flex align-items-center justify-content-center" title="Edit">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <form action="{{ route('kelompok.destroy', $item->slug) }}" method="POST" class="d-inline-block m-0 p-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelompok ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger px-3 rounded d-inline-flex align-items-center justify-content-center" title="Hapus">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">Belum ada kelompok yang dibuat.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Import Excel Kelompok -->
    @if(in_array(Auth::user()->role, ['admin', 'stafbaak', 'pimpinan']))
        <div class="modal fade" id="importExcelModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="bi bi-file-earmark-excel me-1"></i> Import Kelompok & Anggota Excel</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('kelompok.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="alert alert-info py-2 small mb-3">
                                Format header: <strong>id_pendaftar</strong>, <strong>nama_kelompok</strong>, <strong>name</strong>, <strong>fakultas</strong>, <strong>program_studi</strong>, <strong>email</strong>.
                                <br>
                                <a href="{{ route('kelompok.template') }}" class="fw-bold"><i class="bi bi-cloud-download me-1"></i> Download Template Excel</a>
                            </div>
                            <div class="mb-3">
                                <label for="file" class="form-label fw-bold small">Pilih File Excel (.xlsx, .xls, .csv)</label>
                                <input type="file" name="file" class="form-control" id="file" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success px-4">Import Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
