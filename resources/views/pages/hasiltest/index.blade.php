@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Hasil Pretest dan Postes Mahasiswa</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Rekapitulasi</li>
                <li class="breadcrumb-item active">Hasil Pretest dan Postes Mahasiswa</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card overflow-hidden shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="card-title p-0 m-0" style="font-size: 1.25rem;">Daftar Hasil Ujian Mahasiswa</h5>
                                <p class="text-muted extra-small mt-1 mb-0">Pemantauan progres akademik melalui skor Pre-test & Post-test per modul</p>
                            </div>
                            <div class="bulk-actions d-flex gap-2">
                                <a href="{{ route('hasiltest.export') }}" class="btn btn-success btn-sm shadow-sm rounded-pill px-3 py-1 fw-bold extra-small text-white">
                                    <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                                </a>
                                <form action="{{ route('hasiltest.bulkReset') }}" method="POST" id="bulk-reset-form" class="d-inline">
                                    @csrf
                                    <div id="selected-ids-container"></div>
                                    <button type="submit" class="btn btn-danger btn-sm shadow-sm rounded-pill px-4 py-1 fw-bold extra-small" id="btn-bulk-reset" style="display:none; transition: all 0.3s ease;">
                                        <i class="bi bi-trash-fill me-1"></i> Reset Terpilih (<span id="selected-count">0</span>)
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            {!! $dataTable->table(['width' => '100%', 'class' => 'table table-hover align-middle border-0 mb-0']) !!}
                        </div>

                        <div class="mt-4 pt-3 border-top border-light">
                            <h6 class="text-uppercase extra-small fw-bold text-muted mb-3" style="letter-spacing: 1px;">Keterangan Inisial:</h6>
                            <div class="row g-3">
                                <div class="col-md-3 col-6">
                                    <div class="d-flex flex-column gap-2">
                                        <div class="extra-small"><span class="fw-bold text-primary mr-2">H/A :</span> Hadir / Alpha</div>
                                        <div class="extra-small"><span class="fw-bold text-primary mr-2">L/TL :</span> Lengkap / Tidak Lengkap</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex flex-column gap-2">
                                        <div class="extra-small"><span class="fw-bold text-primary mr-2">TW/TR :</span> Tepat Waktu / Terlambat</div>
                                        <div class="extra-small"><span class="fw-bold text-primary mr-2">ST :</span> Sangat Terlambat</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex flex-column gap-2">
                                        <div class="extra-small"><span class="fw-bold text-primary mr-2">B/C :</span> Baik / Cukup</div>
                                        <div class="extra-small"><span class="fw-bold text-primary mr-2">BR :</span> Buruk</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex flex-column gap-2">
                                        <div class="extra-small"><span class="fw-bold text-primary mr-2">M1-M4 :</span> Modul 1 s/d Modul 4</div>
                                        <div class="extra-small"><span class="fw-bold text-primary mr-2">Y/- :</span> Tugas Disubmit / Belum</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function() {
            const tableId = '#hasiltest-table';
            const bulkBtn = $('#btn-bulk-reset');
            const bulkCount = $('#selected-count');
            const bulkForm = $('#bulk-reset-form');
            const container = $('#selected-ids-container');

            function toggleBulkButton() {
                const checked = $('.record-checkbox:checked');
                const count = checked.length;
                if (count > 0) {
                    bulkCount.text(count);
                    bulkBtn.fadeIn(200);
                } else {
                    bulkBtn.fadeOut(200);
                }
            }

            // Detailed event delegation for Master Selection
            $(document).on('click', '#select-all', function() {
                const isChecked = $(this).prop('checked');
                $('.record-checkbox').prop('checked', isChecked);
                toggleBulkButton();
            });

            // Individual Selection synchronization
            $(document).on('change', '.record-checkbox', function() {
                const total = $('.record-checkbox').length;
                const checked = $('.record-checkbox:checked').length;
                $('#select-all').prop('checked', (total > 0 && total === checked));
                toggleBulkButton();
            });

            // Persistence guard: Clear selections on table navigation/redraw
            $(document).on('draw.dt', tableId, function() {
                $('#select-all').prop('checked', false);
                toggleBulkButton();
            });

            // Form submission logic
            bulkForm.on('submit', function(e) {
                const checked = $('.record-checkbox:checked');
                if (checked.length === 0) {
                    e.preventDefault();
                    return false;
                }
                
                container.empty();
                checked.each(function() {
                    container.append(`<input type="hidden" name="ids[]" value="${$(this).val()}">`);
                });
                return true;
            });
        });
    </script>
@endpush
