@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Data Absensi Kedua</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Absensi Kedua</li>
            </ol>
        </nav>
    </div>

    <section class="section">
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
