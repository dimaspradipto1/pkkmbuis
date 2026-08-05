@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Tambah Penilaian Kedisiplinan Hari I</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kedisiplinanpertama.index') }}">Kedisiplinan Hari I</a></li>
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
                        <h5 class="card-title">Form Penilaian Kedisiplinan</h5>

                        <form action="{{ route('kedisiplinanpertama.store') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label for="user_id" class="col-sm-2 col-form-label">Nama Pengguna</label>
                                <div class="col-sm-10">
                                    <select name="user_id" id="user_id" class="form-select" required>
                                        <option value=""></option>
                                        @foreach($users as $user)
                                            @php
                                                $kp = $user->kedisiplinanPertama;
                                                $hasData = $kp && (
                                                    ($kp->kelengkapan_atribut && $kp->kelengkapan_atribut !== '-') ||
                                                    ($kp->ketepatan_waktu && $kp->ketepatan_waktu !== '-') ||
                                                    ($kp->perilaku && $kp->perilaku !== '-')
                                                );
                                            @endphp
                                            <option value="{{ $user->id }}" data-has-data="{{ $hasData ? '1' : '0' }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->id_pendaftar }}) {{ $hasData ? '✓' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="already-exists-alert" class="alert alert-warning py-2 px-3 mt-2" style="display: none; border-radius: 8px;">
                                        <i class="fa-solid fa-triangle-exclamation me-1"></i> <strong>Perhatian:</strong> Pengguna ini sudah memiliki data kedisiplinan Hari I. Menyimpan form ini akan <strong>memperbarui (update)</strong> data tersebut.
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Kelengkapan Atribut</label>
                                <div class="col-sm-10 d-flex align-items-center">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="kelengkapan_atribut" id="atribut_lengkap" value="Lengkap" {{ old('kelengkapan_atribut') == 'Lengkap' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="atribut_lengkap">Lengkap</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="kelengkapan_atribut" id="atribut_tidak_lengkap" value="Tidak Lengkap" {{ old('kelengkapan_atribut') == 'Tidak Lengkap' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="atribut_tidak_lengkap">Tidak Lengkap</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Ketepatan Waktu</label>
                                <div class="col-sm-10 d-flex align-items-center">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="ketepatan_waktu" id="waktu_tepat" value="Tepat Waktu" {{ old('ketepatan_waktu') == 'Tepat Waktu' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="waktu_tepat">Tepat Waktu</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="ketepatan_waktu" id="waktu_terlambat" value="Terlambat" {{ old('ketepatan_waktu') == 'Terlambat' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="waktu_terlambat">Terlambat</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Perilaku</label>
                                <div class="col-sm-10 d-flex align-items-center">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="perilaku" id="perilaku_baik" value="Baik" {{ old('perilaku') == 'Baik' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perilaku_baik">Baik</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="perilaku" id="perilaku_tidak_baik" value="Tidak Baik" {{ old('perilaku') == 'Tidak Baik' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perilaku_tidak_baik">Tidak Baik</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="catatan" class="col-sm-2 col-form-label">Catatan</label>
                                <div class="col-sm-10">
                                    <textarea name="catatan" id="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
                                </div>
                            </div>

                        <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary">Simpan Penilaian</button>
                                <a href="{{ route('kedisiplinanpertama.index') }}" class="btn btn-secondary">Batal</a>
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
            function formatUserOption (opt) {
                if (!opt.id) return opt.text;
                var $element = $(opt.element);
                var hasData = $element.data('has-data') == '1';
                if (hasData) {
                    return $('<span>' + opt.text.replace(' ✓', '') + ' <span class="badge bg-success ms-1" title="Sudah Ada Data"><i class="fa-solid fa-circle-check"></i></span></span>');
                }
                return $('<span>' + opt.text + ' <span class="badge bg-secondary ms-1" title="Belum Ada Data"><i class="fa-solid fa-circle-xmark"></i></span></span>');
            }

            $('#user_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'Pilih Pengguna...',
                allowClear: true,
                templateResult: formatUserOption,
                templateSelection: formatUserOption
            });

            function checkSelectedUser() {
                var selectedOpt = $('#user_id').find('option:selected');
                if (selectedOpt.length && selectedOpt.data('has-data') == '1') {
                    $('#already-exists-alert').slideDown();
                } else {
                    $('#already-exists-alert').slideUp();
                }
            }

            $('#user_id').on('change', checkSelectedUser);
            checkSelectedUser();

            $('input[type="radio"]:checked').each(function() {
                $(this).data('was-checked', true);
            });

            $('input[type="radio"]').on('click', function() {
                var $radio = $(this);
                var name = $radio.attr('name');

                if ($radio.data('was-checked') === true) {
                    $radio.prop('checked', false);
                    $radio.data('was-checked', false);
                } else {
                    $('input[name="' + name + '"]').data('was-checked', false);
                    $radio.data('was-checked', true);
                }
            });
        });
    </script>
@endpush
