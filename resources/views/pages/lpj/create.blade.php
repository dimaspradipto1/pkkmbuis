@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Tambah LPJ</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('lpj.index') }}">LPJ</a></li>
                <li class="breadcrumb-item active">Tambah</li>
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
                        <h5 class="card-title">Form Tambah LPJ</h5>

                        <div class="alert alert-info border-0 py-2 small mb-3 rounded-3">
                            <i class="bi bi-info-circle me-1"></i> Gunakan template resmi berikut sebagai acuan penulisan LPJ.
                            <br>
                            <a href="https://drive.google.com/drive/folders/1gyaNcZyuDuB_E9vHeXVjTCJgTwtiIRYU?usp=sharing" target="_blank" rel="noopener" class="fw-bold text-primary">
                                <i class="bi bi-file-earmark-text me-1"></i> Download Template LPJ
                            </a>
                        </div>

                        <form action="{{ route('lpj-attachments.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-3">
                                <label for="user_id" class="col-sm-2 col-form-label">Diunggah Oleh</label>
                                <div class="col-sm-10">
                                    <select name="user_id" id="user_id" class="form-select">
                                        <option value=""></option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ strtoupper($user->role) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text extra-small text-muted mt-1">Kosongkan untuk menggunakan akun Anda saat ini.</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="link" class="col-sm-2 col-form-label">Link Eksternal(optional)</label>
                                <div class="col-sm-10">
                                    <input type="text" name="link" id="link" class="form-control" placeholder="https://..." value="{{ old('link') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="file" class="col-sm-2 col-form-label">Upload File (PDF / Word)</label>
                                <div class="col-sm-10">
                                    <input type="file" name="file" id="file" class="form-control" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                    <div class="form-text extra-small text-muted mt-1">Format: PDF atau Word (.doc, .docx). Tanpa batasan ukuran. Boleh isi link, upload file, atau keduanya sekaligus.</div>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary">Simpan LPJ</button>
                                <a href="{{ route('lpj.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>

                    </div>
                </div>

                @if (Auth::user()->role != 'admin')
                    @include('partials.lpj-attachments')
                @endif

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
