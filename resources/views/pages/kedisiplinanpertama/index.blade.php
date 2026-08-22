@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Data Kedisiplinan Hari I</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Kedisiplinan Hari I</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <!-- Stat Cards -->
        <div class="row g-3 mb-4">
            <!-- Total Mahasiswa -->
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 mb-0" style="border-left: 5px solid #3b82f6 !important; transition: all 0.2s ease;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 text-white shadow-sm" style="width: 48px; height: 48px; min-width: 48px; background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                            <i class="bi bi-people-fill fs-5"></i>
                        </div>
                        <div>
                            <div class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px;">TOTAL MAHASISWA</div>
                            <div class="fs-4 fw-bold text-dark lh-1 mt-1">{{ number_format($totalMahasiswa ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sudah Ada Data -->
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 mb-0" style="border-left: 5px solid #10b981 !important; transition: all 0.2s ease;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 text-white shadow-sm" style="width: 48px; height: 48px; min-width: 48px; background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="bi bi-clipboard-check-fill fs-5"></i>
                        </div>
                        <div>
                            <div class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px;">SUDAH ADA DATA</div>
                            <div class="fs-4 fw-bold text-success lh-1 mt-1">{{ number_format($sudahAdaData ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Belum Ada Data -->
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 mb-0" style="border-left: 5px solid #ef4444 !important; transition: all 0.2s ease;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 text-white shadow-sm" style="width: 48px; height: 48px; min-width: 48px; background: linear-gradient(135deg, #ef4444, #dc2626);">
                            <i class="bi bi-clipboard-x-fill fs-5"></i>
                        </div>
                        <div>
                            <div class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px;">BELUM ADA DATA</div>
                            <div class="fs-4 fw-bold text-danger lh-1 mt-1">{{ number_format($belumAdaData ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">List Kedisiplinan Hari I</h5>
                            @if (Auth::user()->role != 'mahasiswa')
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-warning text-white" id="btn-bulk-edit" style="display:none;"><i class="bi bi-pencil-square me-1"></i> Edit Terpilih</button>
                                    <button type="button" class="btn btn-danger text-white" id="btn-bulk-delete" style="display:none;"><i class="bi bi-trash me-1"></i> Hapus Terpilih</button>
                                    <a href="{{ route('kedisiplinanpertama.export') }}" class="btn btn-success text-white">
                                        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                                    </a>
                                    <a href="{{ route('kedisiplinanpertama.create') }}" class="btn btn-primary text-white"><i
                                            class="bi bi-plus-circle me-1"></i> Tambah Penilaian</a>
                                </div>
                            @endif
                        </div>
                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>

                @include('partials.kedisiplinan-attachments', ['category' => 'kedisiplinanpertama'])
                @include('partials.kedisiplinan-notes', ['category' => 'kedisiplinanpertama'])

            </div>
        </div>
    </section>

    <!-- Modal Bulk Edit -->
    <div class="modal fade" id="bulkEditModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('kedisiplinanpertama.bulk-update') }}" method="POST" id="bulk-edit-form">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Massal Kedisiplinan (<span id="selected-count">0</span> Data)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="selected-ids-container"></div>
                        
                        <div class="mb-3">
                            <label class="form-label">Kelengkapan Atribut</label>
                            <select name="kelengkapan_atribut" class="form-select">
                                <option value="">Tetap (Tidak diubah)</option>
                                <option value="Lengkap">Lengkap</option>
                                <option value="Cukup Lengkap">Cukup Lengkap</option>
                                <option value="Tidak Lengkap">Tidak Lengkap</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ketepatan Waktu</label>
                            <select name="ketepatan_waktu" class="form-select">
                                <option value="">Tetap (Tidak diubah)</option>
                                <option value="Tepat Waktu">Tepat Waktu</option>
                                <option value="Terlambat">Terlambat</option>
                                <option value="Sangat Terlambat">Sangat Terlambat</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Perilaku</label>
                            <select name="perilaku" class="form-select">
                                <option value="">Tetap (Tidak diubah)</option>
                                <option value="Sangat Baik">Sangat Baik</option>
                                <option value="Baik">Baik</option>
                                <option value="Cukup">Cukup</option>
                                <option value="Buruk">Buruk</option>
                            </select>
                        </div>
                        
                        <p class="text-muted small">*Data yang dikosongkan tidak akan memperbarui nilai yang sudah ada.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Semua</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const table = $('#kedisiplinanpertama-table');
            
            // Handle Select All
            $(document).on('change', '#select-all', function() {
                $('.record-checkbox').prop('checked', this.checked);
                toggleBulkButton();
            });

            $(document).on('change', '.record-checkbox', function() {
                toggleBulkButton();
            });

            function toggleBulkButton() {
                const checkedCount = $('.record-checkbox:checked').length;
                if (checkedCount > 0) {
                    $('#btn-bulk-edit').show().find('span').text(checkedCount);
                    $('#btn-bulk-delete').show();
                    $('#selected-count').text(checkedCount);
                } else {
                    $('#btn-bulk-edit').hide();
                    $('#btn-bulk-delete').hide();
                }
            }

            $('#btn-bulk-edit').click(function() {
                const container = $('#selected-ids-container');
                container.empty();
                $('.record-checkbox:checked').each(function() {
                    container.append(`<input type="hidden" name="ids[]" value="${$(this).val()}">`);
                });
                $('#bulkEditModal').modal('show');
            });

            $('#btn-bulk-delete').click(function() {
                const checked = $('.record-checkbox:checked');
                const count = checked.length;
                if (count === 0) return;

                const doDelete = function() {
                    const form = $('<form>', {
                        action: "{{ route('kedisiplinanpertama.bulk-delete') }}",
                        method: 'POST'
                    });
                    form.append('@csrf');
                    checked.each(function() {
                        form.append($('<input>', {
                            type: 'hidden',
                            name: 'ids[]',
                            value: $(this).val()
                        }));
                    });
                    $('body').append(form);
                    form.submit();
                };

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Apakah anda yakin?',
                        text: "Akan menghapus " + count + " data kedisiplinan terpilih!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            doDelete();
                        }
                    });
                } else if (confirm('Apakah anda yakin ingin menghapus ' + count + ' data kedisiplinan terpilih?')) {
                    doDelete();
                }
            });
        });
    </script>
     @if(app()->environment('production'))
        {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
    @else
        {!! $dataTable->scripts() !!}
    @endif
@endpush
