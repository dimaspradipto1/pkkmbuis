@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Edit LPJ</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('lpj.index') }}">LPJ</a></li>
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
                        <h5 class="card-title">Form Edit LPJ</h5>

                        <form action="{{ route('lpj.update', $lpj->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label for="lpj" class="col-sm-2 col-form-label">Isi LPJ <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <textarea name="lpj" id="lpj" class="form-control" rows="6" required>{{ old('lpj', $lpj->lpj) }}</textarea>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-warning text-white">Update LPJ</button>
                                <a href="{{ route('lpj.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
