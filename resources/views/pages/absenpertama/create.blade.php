@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Tambah Absensi Hari Pertama</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('absenpertama.index') }}">Absensi Pertama</a></li>
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
                        <h5 class="card-title">Form Tambah Absensi</h5>

                        <form action="{{ route('absenpertama.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="row mb-3">
                                <label for="user_id" class="col-sm-3 col-form-label text-nowrap">Nama Pengguna <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select name="user_id" id="user_id" class="form-select">
                                        <option value=""></option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->id_pendaftar }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-nowrap">Hadir Datang</label>
                                <div class="col-sm-9 d-flex align-items-center">
                                    <div class="form-check form-check-inline me-4">
                                        <input class="form-check-input" type="radio" name="hadir_pagi" id="pagi_hadir" value="Hadir" {{ old('hadir_pagi') == 'Hadir' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="pagi_hadir">Hadir</label>
                                    </div>
                                    <div class="form-check form-check-inline me-4">
                                        <input class="form-check-input" type="radio" name="hadir_pagi" id="pagi_izin" value="Izin" {{ old('hadir_pagi') == 'Izin' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="pagi_izin">Izin</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="hadir_pagi" id="pagi_tidak" value="Tidak Hadir" {{ old('hadir_pagi') == 'Tidak Hadir' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="pagi_tidak">Tidak Hadir</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3" id="container_hadir_sore">
                                <label class="col-sm-3 col-form-label text-nowrap">Hadir Pulang</label>
                                <div class="col-sm-9 d-flex align-items-center">
                                    <div class="form-check form-check-inline me-4">
                                        <input class="form-check-input" type="radio" name="hadir_sore" id="sore_hadir" value="Hadir" {{ old('hadir_sore') == 'Hadir' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="sore_hadir">Hadir</label>
                                    </div>
                                    <div class="form-check form-check-inline me-4">
                                        <input class="form-check-input" type="radio" name="hadir_sore" id="sore_izin" value="Izin" {{ old('hadir_sore') == 'Izin' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="sore_izin">Izin</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="hadir_sore" id="sore_tidak" value="Tidak Hadir" {{ old('hadir_sore') == 'Tidak Hadir' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="sore_tidak">Tidak Hadir</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3" id="container_catatan" style="display: none;">
                                <label class="col-sm-3 col-form-label text-nowrap">Catatan & Bukti <span class="text-muted small">(Opsional)</span></label>
                                <div class="col-sm-9">
                                    <div class="mb-3">
                                        <label for="catatan_datang" class="form-label fw-semibold text-secondary small">Catatan Waktu Datang (Opsional)</label>
                                        <textarea name="catatan_datang" id="catatan_datang" class="form-control" rows="2" placeholder="Masukkan catatan/keterangan saat Hadir Datang (misal: Datang Terlambat)...">{{ old('catatan_datang') }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="catatan_pulang" class="form-label fw-semibold text-secondary small">Catatan Waktu Pulang (Opsional)</label>
                                        <textarea name="catatan_pulang" id="catatan_pulang" class="form-control" rows="2" placeholder="Masukkan catatan/keterangan saat Hadir Pulang (misal: Izin Pulang Awal)...">{{ old('catatan_pulang') }}</textarea>
                                    </div>
                                    <label for="bukti_izin" class="form-label fw-semibold text-secondary small">Upload Bukti (PNG, JPG, JPEG, WEBP)</label>
                                    <input type="file" name="bukti_izin" id="bukti_izin" class="form-control" accept="image/png,image/jpeg,image/jpg,image/webp">
                                    <div id="preview_container" class="mt-3" style="display: none;">
                                        <small class="text-muted fw-semibold d-block mb-1">Preview Bukti:</small>
                                        <img id="image_preview" src="" class="img-thumbnail rounded shadow-sm" style="max-height: 220px; object-fit: contain;">
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary">Simpan Absensi</button>
                                <a href="{{ route('absenpertama.index') }}" class="btn btn-secondary">Batal</a>
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

            $('#user_id').on('change', function() {
                var userId = $(this).val();
                $('#badge_pagi_status').remove();
                $('#badge_sore_status').remove();
                $('input[name="hadir_pagi"]').prop('disabled', false);
                $('input[name="hadir_sore"]').prop('disabled', false);

                if (!userId) return;

                $.get("{{ url('absenpertama/check-status') }}/" + userId, function(res) {
                    if (res.is_complete) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Peringatan',
                                text: 'Pengguna ini sudah memiliki data absensi pertama lengkap (Datang & Pulang)!',
                                confirmButtonColor: '#3085d6'
                            });
                        } else {
                            alert('Pengguna ini sudah memiliki data absensi pertama lengkap (Datang & Pulang)!');
                        }
                        $('#user_id').val('').trigger('change');
                        return;
                    }

                    if (res.hadir_pagi && res.hadir_pagi !== 'Belum Absen') {
                        $('input[name="hadir_pagi"][value="' + res.hadir_pagi + '"]').prop('checked', true).data('was-checked', true);
                        $('input[name="hadir_pagi"]').prop('disabled', true);
                        $('label[for="pagi_hadir"]').closest('.row').find('.col-form-label').append('<span id="badge_pagi_status" class="badge bg-success ms-2 small">Sudah Absen (' + res.hadir_pagi + ')</span>');
                    }

                    if (res.hadir_sore && res.hadir_sore !== 'Belum Absen') {
                        $('input[name="hadir_sore"][value="' + res.hadir_sore + '"]').prop('checked', true).data('was-checked', true);
                        $('input[name="hadir_sore"]').prop('disabled', true);
                        $('label[for="sore_hadir"]').closest('.row').find('.col-form-label').append('<span id="badge_sore_status" class="badge bg-success ms-2 small">Sudah Absen (' + res.hadir_sore + ')</span>');
                    }

                    checkIzin();
                });
            });

            function checkIzin() {
                var valPagi = $('input[name="hadir_pagi"]:checked').val();
                var valSore = $('input[name="hadir_sore"]:checked').val();

                var isSelectedPagi = (valPagi === 'Hadir' || valPagi === 'Izin' || valPagi === 'Tidak Hadir');
                var isSelectedSore = (valSore === 'Hadir' || valSore === 'Izin' || valSore === 'Tidak Hadir');

                if (isSelectedPagi || isSelectedSore) {
                    $('#container_catatan').slideDown(200);
                } else {
                    $('#container_catatan').slideUp(200);
                }
            }

            checkIzin();

            $('input[type="radio"]:checked').each(function() {
                $(this).data('was-checked', true);
            });

            $('input[type="radio"]').on('click', function() {
                var $radio = $(this);
                var name = $radio.attr('name');

                if ($radio.prop('disabled')) return;

                if ($radio.data('was-checked') === true) {
                    $radio.prop('checked', false);
                    $radio.data('was-checked', false);
                } else {
                    $('input[name="' + name + '"]').data('was-checked', false);
                    $radio.data('was-checked', true);
                }

                setTimeout(checkIzin, 50);
            });

            $('#bukti_izin').on('change', function(e) {
                var file = e.target.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(evt) {
                        $('#image_preview').attr('src', evt.target.result);
                        $('#preview_container').slideDown(200);
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#preview_container').slideUp(200);
                    $('#image_preview').attr('src', '');
                }
            });

            $('form').on('submit', function(e) {
                $('input[name="hadir_pagi"], input[name="hadir_sore"]').prop('disabled', false);
                var userId = $('#user_id').val();
                if (!userId) {
                    e.preventDefault();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'Silakan pilih Nama Pengguna terlebih dahulu!',
                            confirmButtonColor: '#3085d6'
                        });
                    } else {
                        alert('Silakan pilih Nama Pengguna terlebih dahulu!');
                    }
                    return false;
                }
            });
        });
    </script>
@endpush
