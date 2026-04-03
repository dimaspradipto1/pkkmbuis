@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Edit Soal Pretest Hari I</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('soalpretestpertama.index') }}">Soal Pretest Hari I</a></li>
                <li class="breadcrumb-item active">Edit</li>
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
                        <h5 class="card-title">Form Edit Soal Pretest</h5>

                        <form action="{{ route('soalpretestpertama.update', $soalPretestPertama->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label for="soal" class="col-sm-2 col-form-label">Soal</label>
                                <div class="col-sm-10">
                                    <textarea name="soal" id="soal" class="form-control" rows="3" required>{{ old('soal', $soalPretestPertama->soal) }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="pilihan_a" class="col-sm-2 col-form-label">Pilihan A</label>
                                <div class="col-sm-10">
                                    <textarea name="pilihan_a" id="pilihan_a" class="form-control" rows="2" required>{{ old('pilihan_a', $soalPretestPertama->pilihan_a) }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="pilihan_b" class="col-sm-2 col-form-label">Pilihan B</label>
                                <div class="col-sm-10">
                                    <textarea name="pilihan_b" id="pilihan_b" class="form-control" rows="2" required>{{ old('pilihan_b', $soalPretestPertama->pilihan_b) }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="pilihan_c" class="col-sm-2 col-form-label">Pilihan C</label>
                                <div class="col-sm-10">
                                    <textarea name="pilihan_c" id="pilihan_c" class="form-control" rows="2" required>{{ old('pilihan_c', $soalPretestPertama->pilihan_c) }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="pilihan_d" class="col-sm-2 col-form-label">Pilihan D</label>
                                <div class="col-sm-10">
                                    <textarea name="pilihan_d" id="pilihan_d" class="form-control" rows="2" required>{{ old('pilihan_d', $soalPretestPertama->pilihan_d) }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="jawaban" class="col-sm-2 col-form-label">Jawaban Benar</label>
                                <div class="col-sm-10">
                                    <select name="jawaban" id="jawaban" class="form-select" required>
                                        <option value="A" {{ old('jawaban', $soalPretestPertama->jawaban) == 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ old('jawaban', $soalPretestPertama->jawaban) == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="C" {{ old('jawaban', $soalPretestPertama->jawaban) == 'C' ? 'selected' : '' }}>C</option>
                                        <option value="D" {{ old('jawaban', $soalPretestPertama->jawaban) == 'D' ? 'selected' : '' }}>D</option>
                                    </select>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-warning text-white">Update Soal</button>
                                <a href="{{ route('soalpretestpertama.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
