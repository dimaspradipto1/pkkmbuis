@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Detail Kelompok: {{ $kelompok->nama_kelompok }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kelompok.index') }}">Manajemen Kelompok</a></li>
                <li class="breadcrumb-item active">Detail Kelompok</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <!-- Information Card -->
            <div class="col-lg-12 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="fw-bold text-primary mb-1">{{ $kelompok->nama_kelompok }}</h4>
                                <p class="text-muted mb-0">
                                    <i class="bi bi-person-badge me-1 text-secondary"></i> Pendamping:
                                    <span class="fw-bold text-dark">{{ $kelompok->pendamping->name ?? 'Belum Ditentukan' }}</span>
                                    @if($kelompok->pendamping)
                                        <span class="badge bg-light text-secondary border ms-1">{{ strtoupper($kelompok->pendamping->role) }}</span>
                                    @endif
                                </p>
                                @if($kelompok->keterangan)
                                    <small class="text-muted d-block mt-1"><i class="bi bi-info-circle me-1"></i> {{ $kelompok->keterangan }}</small>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('kelompok.index') }}" class="btn btn-outline-secondary me-2">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                                @if(Auth::user()->role != 'mahasiswa')
                                    <button class="btn btn-outline-success me-2" data-bs-toggle="modal" data-bs-target="#importMemberModal">
                                        <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
                                    </button>
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                                        <i class="bi bi-person-plus-fill me-1"></i> Tambah Anggota
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Members List -->
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="card-title p-0 mb-4 fw-bold">Daftar Anggota ({{ $kelompok->anggota->count() }} Mahasiswa)</h5>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">NO</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>ID Pendaftar</th>
                                        <th>Fakultas</th>
                                        <th>Program Studi</th>
                                        <th>Email</th>
                                        @if(Auth::user()->role != 'mahasiswa')
                                            <th class="text-center" style="width: 120px;">Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kelompok->anggota as $index => $mhs)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="fw-bold text-dark">{{ $mhs->name }}</td>
                                            <td><span class="badge bg-light text-dark border">{{ $mhs->id_pendaftar }}</span></td>
                                            <td><small>{{ $mhs->fakultas ?? '-' }}</small></td>
                                            <td><small>{{ $mhs->program_studi ?? '-' }}</small></td>
                                            <td><small class="text-muted">{{ $mhs->email }}</small></td>
                                            @if(Auth::user()->role != 'mahasiswa')
                                                <td class="text-center">
                                                     <form action="{{ route('kelompok.remove-member', [$kelompok->slug, $mhs->id]) }}" method="POST" onsubmit="return confirm('Keluarkan mahasiswa ini dari kelompok?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Keluarkan dari kelompok">
                                                            <i class="bi bi-person-dash me-1"></i> Keluarkan
                                                        </button>
                                                    </form>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ Auth::user()->role != 'mahasiswa' ? 7 : 6 }}" class="text-center py-4 text-muted">
                                                Belum ada mahasiswa yang dimasukkan ke dalam kelompok ini.
                                            </td>
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

    <!-- Modal Tambah Anggota -->
    @if(Auth::user()->role != 'mahasiswa')
        <div class="modal fade" id="addMemberModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i> Tambah Anggota ke {{ $kelompok->nama_kelompok }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('kelompok.add-member', $kelompok->slug) }}" method="POST">
                        @csrf
                        <div class="modal-body p-4">
                            <p class="small text-muted mb-2">Pilih mahasiswa (yang belum memiliki kelompok) untuk dimasukkan ke kelompok ini:</p>
                            @if($unassignedStudents->count() > 0)
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                                        <input type="text" id="searchUnassignedInput" class="form-control" placeholder="Cari nama, ID pendaftar, atau prodi...">
                                    </div>
                                </div>
                                <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                                    <table class="table table-sm table-hover align-middle" id="unassignedStudentsTable">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th style="width: 40px;" class="text-center">
                                                    <input class="form-check-input" type="checkbox" id="selectAllUnassigned" title="Pilih Semua">
                                                </th>
                                                <th>Nama</th>
                                                <th>ID Pendaftar</th>
                                                <th>Program Studi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($unassignedStudents as $s)
                                                <tr>
                                                    <td class="text-center">
                                                        <input class="form-check-input student-checkbox" type="checkbox" name="user_ids[]" value="{{ $s->id }}" id="mhs_{{ $s->id }}">
                                                    </td>
                                                    <td>
                                                        <label for="mhs_{{ $s->id }}" class="fw-bold mb-0 text-dark pointer" style="cursor: pointer;">
                                                            {{ $s->name }}
                                                        </label>
                                                    </td>
                                                    <td><small class="text-muted">{{ $s->id_pendaftar }}</small></td>
                                                    <td><small class="text-muted">{{ $s->program_studi ?? '-' }}</small></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle me-1"></i> Semua mahasiswa saat ini sudah memiliki kelompok.
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            @if($unassignedStudents->count() > 0)
                                <button type="submit" class="btn btn-primary px-4">Masukkan ke Kelompok</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Import Excel Anggota Kelompok -->
    @if(Auth::user()->role != 'mahasiswa')
        <div class="modal fade" id="importMemberModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="bi bi-file-earmark-excel me-1"></i> Import Anggota {{ $kelompok->nama_kelompok }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('kelompok.import-member', $kelompok->slug) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="alert alert-info py-2 small mb-3">
                                Format header Excel: <strong>id_pendaftar</strong>, <strong>name</strong>, <strong>fakultas</strong>, <strong>program_studi</strong>, <strong>email</strong>.
                                <br>
                                <a href="{{ route('kelompok.template-member', $kelompok->slug) }}" class="fw-bold"><i class="bi bi-cloud-download me-1"></i> Download Template Excel</a>
                            </div>
                            <div class="mb-3">
                                <label for="file_member" class="form-label fw-bold small">Pilih File Excel (.xlsx, .xls, .csv)</label>
                                <input type="file" name="file" class="form-control" id="file_member" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success px-4">Import Anggota</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchUnassignedInput');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const query = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#unassignedStudentsTable tbody tr');
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }

            const selectAllCheckbox = document.getElementById('selectAllUnassigned');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const visibleCheckboxes = document.querySelectorAll('#unassignedStudentsTable tbody tr:not([style*="display: none"]) .student-checkbox');
                    visibleCheckboxes.forEach(cb => cb.checked = this.checked);
                });
            }
        });
    </script>
@endpush
