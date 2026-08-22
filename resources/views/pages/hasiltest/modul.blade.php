@extends('dashboard.template')

@section('content')
    <div class="pagetitle d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h1 class="h4 fw-bold text-dark mb-1">
                Hasil {{ ucfirst($type) }} - Modul {{ $modul }}
            </h1>
            <nav>
                <ol class="breadcrumb mb-0 extra-small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Hasil Test</li>
                    <li class="breadcrumb-item active">{{ ucfirst($type) }} Modul {{ $modul }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('hasiltest.exportModul', ['type' => $type, 'modul' => $modul]) }}" class="btn btn-success btn-sm shadow-sm rounded-pill px-3 py-2">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
            @if (in_array(Auth::user()->role, ['admin', 'stafbaak']))
                <button type="button" class="btn btn-danger btn-sm shadow-sm rounded-pill px-3 py-2 fw-bold" id="btn-bulk-reset-trigger" style="display:none;">
                    <i class="fa-solid fa-rotate-left me-1"></i> Reset Terpilih (<span id="selected-count">0</span>)
                </button>
            @endif
        </div>
    </div>

    <section class="section">
        <!-- Module Test Stat Cards -->
        <div class="row g-3 mb-4">
            <!-- Total Mahasiswa -->
            <div class="col-12 col-sm-6 col-xl-3">
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

            <!-- Sudah Mengerjakan -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 mb-0" style="border-left: 5px solid #10b981 !important; transition: all 0.2s ease;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 text-white shadow-sm" style="width: 48px; height: 48px; min-width: 48px; background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                        </div>
                        <div>
                            <div class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px;">SUDAH MENGERJAKAN</div>
                            <div class="fs-4 fw-bold text-success lh-1 mt-1">{{ number_format($sudahMengerjakan ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Belum Mengerjakan -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 mb-0" style="border-left: 5px solid #ef4444 !important; transition: all 0.2s ease;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 text-white shadow-sm" style="width: 48px; height: 48px; min-width: 48px; background: linear-gradient(135deg, #ef4444, #dc2626);">
                            <i class="bi bi-x-circle-fill fs-5"></i>
                        </div>
                        <div>
                            <div class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px;">BELUM MENGERJAKAN</div>
                            <div class="fs-4 fw-bold text-danger lh-1 mt-1">{{ number_format($belumMengerjakan ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rata-rata Nilai -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 mb-0" style="border-left: 5px solid #f59e0b !important; transition: all 0.2s ease;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 text-white shadow-sm" style="width: 48px; height: 48px; min-width: 48px; background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <i class="bi bi-award-fill fs-5"></i>
                        </div>
                        <div>
                            <div class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px;">RATA-RATA NILAI</div>
                            <div class="fs-4 fw-bold text-dark lh-1 mt-1">{{ $rataRata ?? 0 }}</div>
                            <div class="extra-small text-muted mt-1">Tuntas: <span class="text-success fw-bold">{{ $tuntasCount ?? 0 }}</span> | <span class="text-danger fw-bold">{{ $tidakTuntasCount ?? 0 }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hidden Form for Bulk Reset --}}
        <form action="{{ route('hasiltest.bulkResetModul') }}" method="POST" id="bulk-reset-form" class="d-none">
            @csrf
            <div id="bulk-ids-container"></div>
        </form>

        {{-- Main DataTable Card --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-3">
                        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2 fs-6">
                            <i class="bi bi-card-checklist text-primary"></i>
                            Daftar Mahasiswa & Hasil {{ ucfirst($type) }} Modul {{ $modul }}
                        </h6>
                        <div class="table-responsive">
                            {{ $dataTable->table(['class' => 'table table-hover align-middle border-0 mb-0', 'width' => '100%']) }}
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

    <script>
        $(document).ready(function() {
            const bulkBtn = $('#btn-bulk-reset-trigger');
            const bulkCount = $('#selected-count');
            const bulkForm = $('#bulk-reset-form');
            const container = $('#bulk-ids-container');

            function toggleBulkButton() {
                const count = $('.record-checkbox:checked').length;
                if (count > 0) {
                    bulkCount.text(count);
                    bulkBtn.fadeIn(200);
                } else {
                    bulkBtn.fadeOut(200);
                }
            }

            // Select all checkbox
            $(document).on('click', '#select-all', function() {
                const isChecked = $(this).prop('checked');
                $('.record-checkbox').prop('checked', isChecked);
                toggleBulkButton();
            });

            // Individual checkbox change
            $(document).on('change', '.record-checkbox', function() {
                const total = $('.record-checkbox').length;
                const checked = $('.record-checkbox:checked').length;
                $('#select-all').prop('checked', (total > 0 && total === checked));
                toggleBulkButton();
            });

            // Clear selection on table redraw
            $(document).on('draw.dt', function() {
                $('#select-all').prop('checked', false);
                toggleBulkButton();
            });

            // Trigger Bulk Reset Button click
            bulkBtn.on('click', function(e) {
                e.preventDefault();
                const checked = $('.record-checkbox:checked');
                const count = checked.length;

                if (count === 0) return;

                const doBulkReset = function() {
                    container.empty();
                    checked.each(function() {
                        container.append(`<input type="hidden" name="ids[]" value="${$(this).val()}">`);
                    });
                    bulkForm.submit();
                };

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Reset Nilai Terpilih?',
                        text: `Akan mereset nilai ${count} mahasiswa terpilih. Mahasiswa terkait akan dapat mengerjakan ulang test ini.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Reset Nilai!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            doBulkReset();
                        }
                    });
                } else if (confirm(`Apakah Anda yakin ingin mereset nilai ${count} mahasiswa terpilih?`)) {
                    doBulkReset();
                }
            });
        });
    </script>
@endpush
