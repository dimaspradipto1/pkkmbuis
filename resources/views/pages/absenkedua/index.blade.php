@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Data Absensi Hari Kedua</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Absensi Hari Kedua</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <!-- Attendance Stat Cards -->
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

            <!-- Sudah Absensi Datang -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 mb-0" style="border-left: 5px solid #10b981 !important; transition: all 0.2s ease;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 text-white shadow-sm" style="width: 48px; height: 48px; min-width: 48px; background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="bi bi-box-arrow-in-right fs-5"></i>
                        </div>
                        <div>
                            <div class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px;">SUDAH ABSEN DATANG</div>
                            <div class="fs-4 fw-bold text-success lh-1 mt-1">{{ number_format($sudahDatang ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sudah Absensi Pulang -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 mb-0" style="border-left: 5px solid #6366f1 !important; transition: all 0.2s ease;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 text-white shadow-sm" style="width: 48px; height: 48px; min-width: 48px; background: linear-gradient(135deg, #6366f1, #4338ca);">
                            <i class="bi bi-box-arrow-right fs-5"></i>
                        </div>
                        <div>
                            <div class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px;">SUDAH ABSEN PULANG</div>
                            <div class="fs-4 fw-bold lh-1 mt-1" style="color: #4f46e5 !important;">{{ number_format($sudahPulang ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Belum Absensi -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 mb-0" style="border-left: 5px solid #ef4444 !important; transition: all 0.2s ease;">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 text-white shadow-sm" style="width: 48px; height: 48px; min-width: 48px; background: linear-gradient(135deg, #ef4444, #dc2626);">
                            <i class="bi bi-person-x-fill fs-5"></i>
                        </div>
                        <div>
                            <div class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px;">BELUM ABSENSI</div>
                            <div class="fs-4 fw-bold text-danger lh-1 mt-1">{{ number_format($belumAbsen ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        @php
                            $sPagi = \App\Models\AbsenSetting::where('session_code', 'ABSEN_2_PAGI')->first();
                            $sSore = \App\Models\AbsenSetting::where('session_code', 'ABSEN_2_SORE')->first();
                            $isVisible = ($sPagi->is_visible ?? true) || ($sSore->is_visible ?? true);
                            $isActive = ($sPagi ? $sPagi->checkIsActive() : false) || ($sSore ? $sSore->checkIsActive() : false);
                        @endphp
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">List Absensi Hari II</h5>
                            <div class="d-flex gap-2">
                                @if (Auth::user()->role != 'mahasiswa')
                                    @if ($isVisible)
                                        @if ($isActive)
                                            <button type="button" class="btn btn-outline-dark fw-bold" onclick="showAttendanceQR(2)">
                                                <i class="bi bi-qr-code me-1"></i> QR Absensi
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-secondary disabled" disabled>
                                                <i class="bi bi-clock me-1"></i> QR Non-aktif (Lewat 30m)
                                            </button>
                                        @endif
                                    @endif
                                    <button type="button" class="btn btn-danger text-white" id="btn-bulk-delete" style="display:none;"><i class="bi bi-trash me-1"></i> Hapus Terpilih</button>
                                    <a href="{{ route('absenkedua.export') }}" class="btn btn-success text-white">
                                        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                                    </a>
                                    <a href="{{ route('absenkedua.create') }}" class="btn btn-primary text-white">
                                        <i class="bi bi-plus-circle me-1"></i> Tambah
                                    </a>
                                @else
                                    @if ($isVisible && $isActive)
                                        <a href="{{ route('absenkedua-scan.index') }}" class="btn btn-success">
                                            <i class="bi bi-qr-code-scan me-1"></i> Scan Absensi
                                        </a>
                                    @else
                                        <button class="btn btn-secondary disabled" disabled>
                                            <i class="bi bi-dash-circle me-1"></i> Absensi Non-aktif
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>

                @include('partials.absen-attachments', ['category' => 'absenkedua'])
                @include('partials.absen-notes', ['category' => 'absenkedua'])

            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('change', '#select-all', function() {
                $('.record-checkbox').prop('checked', this.checked);
                toggleBulkDelete();
            });

            $(document).on('change', '.record-checkbox', function() {
                toggleBulkDelete();
            });

            function toggleBulkDelete() {
                const count = $('.record-checkbox:checked').length;
                if (count > 0) {
                    $('#btn-bulk-delete').show();
                } else {
                    $('#btn-bulk-delete').hide();
                }
            }

            $('#btn-bulk-delete').click(function() {
                const checked = $('.record-checkbox:checked');
                const count = checked.length;
                if (count === 0) return;

                const doDelete = function() {
                    const form = $('<form>', {
                        action: "{{ route('absenkedua.bulk-delete') }}",
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
                        text: "Akan menghapus " + count + " data absensi terpilih!",
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
                } else if (confirm('Apakah anda yakin ingin menghapus ' + count + ' data absensi terpilih?')) {
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
