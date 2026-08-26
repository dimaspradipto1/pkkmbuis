@extends('dashboard.template')

@section('content')
    <div class="pagetitle d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h1 class="h4 fw-bold text-dark mb-1">Evaluasi Pelayanan Sistem Administrasi Keuangan</h1>
            <nav>
                <ol class="breadcrumb mb-0 extra-small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Evaluasi</li>
                    <li class="breadcrumb-item active">Pelayanan Sistem Administrasi Keuangan</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @if (Auth::user()->role != 'mahasiswa')
                <button type="button" class="btn btn-danger btn-sm shadow-sm rounded-pill px-3 py-2 text-white" id="btn-bulk-delete" style="display:none;">
                    <i class="bi bi-trash me-1"></i> Hapus Terpilih
                </button>
            @endif
            <a href="{{ route('evaluasi.export', 4) }}" class="btn btn-success btn-sm shadow-sm rounded-pill px-3 py-2">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
            <a href="{{ route('evaluasipelayanansistemadministrasikeuangan.create') }}" class="btn btn-primary btn-sm shadow-sm rounded-pill px-3 py-2">
                <i class="bi bi-plus-lg me-1"></i> Isi Evaluasi
            </a>
        </div>
    </div>

    <section class="section">
        @include('partials.evaluasi-stats')

        {{-- Individual Responses DataTable --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-3">
                        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2 fs-6">
                            <i class="bi bi-list-check text-primary"></i>
                            Daftar Response Evaluasi Mahasiswa
                        </h6>
                        <div class="table-responsive">
                            {{ $dataTable->table() }}
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
                        action: "{{ route('evaluasipelayanansistemadministrasikeuangan.bulk-delete') }}",
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
                        text: "Akan menghapus " + count + " data evaluasi terpilih!",
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
                } else if (confirm('Apakah anda yakin ingin menghapus ' + count + ' data evaluasi terpilih?')) {
                    doDelete();
                }
            });
        });
    </script>
@endpush