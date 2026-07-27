@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Tambah Observasi Acara 2</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('observasiacara2.index') }}">Observasi Acara 2</a></li>
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
                        <h5 class="card-title">Form Tambah Observasi Acara 2</h5>

                        <form action="{{ route('observasiacara2.store') }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <label for="waktu_runddown" class="col-sm-2 col-form-label">Waktu Rundown</label>
                                <div class="col-sm-10">
                                    <input type="text" name="waktu_runddown" id="waktu_runddown" class="form-control" placeholder="Contoh: 08.00 - 08.30" value="{{ old('waktu_runddown') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="waktu_realisasi" class="col-sm-2 col-form-label">Waktu Realisasi</label>
                                <div class="col-sm-10">
                                    <input type="text" name="waktu_realisasi" id="waktu_realisasi" class="form-control" placeholder="Contoh: 08.15 - 08.40" value="{{ old('waktu_realisasi') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="kegiatan" class="col-sm-2 col-form-label">Kegiatan <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <textarea name="kegiatan" id="kegiatan" class="form-control" rows="3" required>{{ old('kegiatan') }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Aspek Observasi</label>
                                <div class="col-sm-10">
                                    <div id="aspek-observasi-wrapper">
                                        @forelse (old('aspek_observasi', ['']) as $aspek)
                                            <div class="input-group mb-2 repeater-row">
                                                <input type="text" name="aspek_observasi[]" class="form-control" placeholder="Aspek yang diobservasi" value="{{ $aspek }}">
                                                <button type="button" class="btn btn-outline-danger btn-remove-row" title="Hapus"><i class="bi bi-dash-circle"></i></button>
                                            </div>
                                        @empty
                                            <div class="input-group mb-2 repeater-row">
                                                <input type="text" name="aspek_observasi[]" class="form-control" placeholder="Aspek yang diobservasi">
                                                <button type="button" class="btn btn-outline-danger btn-remove-row" title="Hapus"><i class="bi bi-dash-circle"></i></button>
                                            </div>
                                        @endforelse
                                    </div>
                                    <button type="button" id="btn-add-aspek" class="btn btn-outline-primary btn-sm mt-1">
                                        <i class="bi bi-plus-circle me-1"></i> Tambah Aspek
                                    </button>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Skala (1 - 5)</label>
                                <div class="col-sm-10 d-flex align-items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div class="form-check form-check-inline me-4">
                                            <input class="form-check-input" type="radio" name="skala" id="skala_{{ $i }}" value="{{ $i }}" {{ old('skala') == $i ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="skala_{{ $i }}">{{ $i }}</label>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="catatan" class="col-sm-2 col-form-label">Catatan</label>
                                <div class="col-sm-10">
                                    <textarea name="catatan" id="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Link Dokumen</label>
                                <div class="col-sm-10">
                                    <div id="link-dokumen-wrapper">
                                        @forelse (old('link_dokumen', ['']) as $link)
                                            <div class="input-group mb-2 repeater-row">
                                                <input type="url" name="link_dokumen[]" class="form-control" placeholder="https://..." value="{{ $link }}">
                                                <button type="button" class="btn btn-outline-danger btn-remove-row" title="Hapus"><i class="bi bi-dash-circle"></i></button>
                                            </div>
                                        @empty
                                            <div class="input-group mb-2 repeater-row">
                                                <input type="url" name="link_dokumen[]" class="form-control" placeholder="https://...">
                                                <button type="button" class="btn btn-outline-danger btn-remove-row" title="Hapus"><i class="bi bi-dash-circle"></i></button>
                                            </div>
                                        @endforelse
                                    </div>
                                    <button type="button" id="btn-add-link" class="btn btn-outline-primary btn-sm mt-1">
                                        <i class="bi bi-plus-circle me-1"></i> Tambah Link
                                    </button>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary">Simpan Observasi</button>
                                <a href="{{ route('observasiacara2.index') }}" class="btn btn-secondary">Batal</a>
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
        document.addEventListener('DOMContentLoaded', function () {
            function initRepeater(wrapperId, addBtnId, inputName, placeholder, inputType) {
                const wrapper = document.getElementById(wrapperId);
                const btnAdd = document.getElementById(addBtnId);

                btnAdd.addEventListener('click', function () {
                    const row = document.createElement('div');
                    row.className = 'input-group mb-2 repeater-row';
                    row.innerHTML = `
                        <input type="${inputType}" name="${inputName}" class="form-control" placeholder="${placeholder}">
                        <button type="button" class="btn btn-outline-danger btn-remove-row" title="Hapus"><i class="bi bi-dash-circle"></i></button>
                    `;
                    wrapper.appendChild(row);
                });

                wrapper.addEventListener('click', function (e) {
                    const btn = e.target.closest('.btn-remove-row');
                    if (!btn) return;

                    const rows = wrapper.querySelectorAll('.repeater-row');
                    if (rows.length > 1) {
                        btn.closest('.repeater-row').remove();
                    } else {
                        btn.closest('.repeater-row').querySelector('input').value = '';
                    }
                });
            }

            initRepeater('aspek-observasi-wrapper', 'btn-add-aspek', 'aspek_observasi[]', 'Aspek yang diobservasi', 'text');
            initRepeater('link-dokumen-wrapper', 'btn-add-link', 'link_dokumen[]', 'https://...', 'url');
        });
    </script>
@endpush
