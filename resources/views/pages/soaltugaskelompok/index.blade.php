@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Tugas Kelompok (Modul 5)</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Tugas Kelompok</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">List Link Tugas Kelompok</h5>
                            @if (Auth::user()->role != 'mahasiswa')
                                <a href="{{ route('soaltugaskelompok.create') }}" class="btn btn-primary text-white">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Link Tugas
                                </a>
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
@endsection

@push('scripts')
    @if (app()->environment('production'))
        {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
    @else
        {!! $dataTable->scripts() !!}
    @endif
    <script>
        $(document).ready(function() {
            // Use event delegation for dynamically loaded inputs
            $(document).on('change', '.input-nilai-tugas', function() {
                var input = $(this);
                var url = input.data('url');
                var nilai = input.val();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        nilai: nilai
                    },
                    success: function(response) {
                        if(response.success) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    },
                    error: function(xhr) {
                        var msg = 'Gagal menyimpan nilai.';
                        if(xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: msg,
                            showConfirmButton: false,
                            timer: 5000
                        });
                    }
                });
            });
        });
    </script>
@endpush
