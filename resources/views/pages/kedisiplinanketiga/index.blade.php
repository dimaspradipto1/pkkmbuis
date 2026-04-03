@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Data Kedisiplinan Hari III</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Kedisiplinan Hari III</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">List Kedisiplinan Hari III</h5>
                            @if (Auth::user()->role != 'mahasiswa')
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-warning text-white" id="btn-bulk-edit" style="display:none;"><i class="bi bi-pencil-square me-1"></i> Edit Terpilih</button>
                                    <a href="{{ route('kedisiplinanketiga.create') }}" class="btn btn-primary text-white"><i
                                            class="bi bi-plus-circle me-1"></i> Tambah Penilaian</a>
                                </div>
                            @endif
                        </div>
                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Modal Bulk Edit -->
    <div class="modal fade" id="bulkEditModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('kedisiplinanketiga.bulk-update') }}" method="POST" id="bulk-edit-form">
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
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function() {
            const table = $('#kedisiplinanketiga-table');
            
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
                    $('#selected-count').text(checkedCount);
                } else {
                    $('#btn-bulk-edit').hide();
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
        });
    </script>
@endpush
