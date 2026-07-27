@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Edit Lampiran LPJ</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('lpj.index') }}">LPJ</a></li>
                <li class="breadcrumb-item active">Edit Lampiran</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form Edit Lampiran LPJ</h5>

                        <form action="{{ route('lpj-attachments.update', $attachment->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label for="user_id" class="col-sm-2 col-form-label">Diunggah Oleh</label>
                                <div class="col-sm-10">
                                    <select name="user_id" id="user_id" class="form-select">
                                        <option value=""></option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id', $attachment->user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ strtoupper($user->role) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="link" class="col-sm-2 col-form-label">Link Eksternal</label>
                                <div class="col-sm-10">
                                    <input type="text" name="link" id="link" class="form-control" placeholder="https://..." value="{{ old('link', $attachment->link) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="file" class="col-sm-2 col-form-label">Upload File (PDF / Word)</label>
                                <div class="col-sm-10">
                                    @if ($attachment->file)
                                        <div class="alert alert-light border py-2 small mb-2 rounded-3 d-flex align-items-center gap-2">
                                            <i class="bi bi-file-earmark-text text-primary"></i>
                                            <span class="text-truncate">File saat ini: <strong>{{ basename($attachment->file) }}</strong></span>
                                            <a href="{{ asset('storage/' . $attachment->file) }}" target="_blank" class="ms-auto btn btn-sm btn-outline-primary">Lihat</a>
                                        </div>
                                    @endif
                                    <input type="file" name="file" id="file" class="form-control" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                    <div class="form-text extra-small text-muted mt-1">Format: PDF atau Word (.doc, .docx). Tanpa batasan ukuran. Kosongkan jika tidak ingin mengganti file. Boleh isi link, upload file, atau keduanya sekaligus.</div>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-warning text-white">Update Lampiran</button>
                                <a href="{{ route('lpj.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#user_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'Cari / Pilih Pengguna...',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush
