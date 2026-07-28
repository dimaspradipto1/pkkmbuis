@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Pengaturan Sertifikat Kelulusan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Pengaturan Sertifikat</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="alert border-0 bg-dark text-white rounded-4 p-4 mb-4 shadow-lg" style="background: #0f172a !important;">
                    <h6 class="fw-bold mb-1"><i class="bi bi-info-circle-fill text-warning me-2"></i>Panduan</h6>
                    <p class="mb-0 opacity-75 small">
                        Nomor sertifikat (4 digit di depan, cth. <strong>0001</strong>) diterbitkan otomatis dan berurutan untuk setiap mahasiswa saat status kelulusan mereka tampil di dashboard — tidak bisa diubah manual di sini.
                        Field di bawah ini hanya mengatur teks & logo yang tampil di badan sertifikat (kode surat, nama kegiatan, penanda tangan, dan logo).
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Sertifikat</h5>
                        <form id="sertifikatSettingForm" action="{{ route('sertifikatsetting.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label fw-bold small">Kode Surat (setelah nomor urut)</label>
                                <input type="text" name="kode_surat" class="form-control" value="{{ old('kode_surat', $setting->kode_surat) }}" required>
                                <div class="form-text">Nomor lengkap akan tampil sebagai: <strong>0001/{{ $setting->kode_surat }}</strong></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small">Nama Kegiatan</label>
                                <textarea name="nama_kegiatan" class="form-control" rows="2" required>{{ old('nama_kegiatan', $setting->nama_kegiatan) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">Lokasi Pelaksanaan</label>
                                    <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $setting->lokasi) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">Tanggal Pelaksanaan</label>
                                    <input type="text" name="tanggal_pelaksanaan" class="form-control" value="{{ old('tanggal_pelaksanaan', $setting->tanggal_pelaksanaan) }}" required>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">Nama (Mengetahui)</label>
                                    <input type="text" name="nama_mengetahui" class="form-control" value="{{ old('nama_mengetahui', $setting->nama_mengetahui) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">Jabatan (Mengetahui)</label>
                                    <input type="text" name="jabatan_mengetahui" class="form-control" value="{{ old('jabatan_mengetahui', $setting->jabatan_mengetahui) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">NIP (Mengetahui)</label>
                                    <input type="text" name="nip_mengetahui" class="form-control" value="{{ old('nip_mengetahui', $setting->nip_mengetahui) }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">Nama Ketua Panitia</label>
                                    <input type="text" name="nama_ketua_panitia" class="form-control" value="{{ old('nama_ketua_panitia', $setting->nama_ketua_panitia) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">Jabatan Ketua Panitia (baris ke-2)</label>
                                    <input type="text" name="jabatan_ketua_panitia" class="form-control" value="{{ old('jabatan_ketua_panitia', $setting->jabatan_ketua_panitia) }}" required>
                                    <div class="form-text">Label "Ketua Panitia," sudah tetap; isi di sini hanya baris kedua, cth. "PKKMB Tahun 2026-2027".</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">NUP Ketua Panitia</label>
                                    <input type="text" name="nup_ketua_panitia" class="form-control" value="{{ old('nup_ketua_panitia', $setting->nup_ketua_panitia) }}">
                                </div>
                            </div>

                            <hr>

                            <h6 class="fw-bold small text-uppercase text-muted mb-3">Logo Header Sertifikat</h6>
                            <div class="row">
                                @foreach ([
                                    'logo_dikti' => ['label' => 'Logo DIKTI Saintek Berdampak', 'default' => null],
                                    'logo_belmawa' => ['label' => 'Logo Belmawa', 'default' => null],
                                    'logo_pkkmb' => ['label' => 'Logo PKKMB', 'default' => asset('assets/img/logopkkmb.png')],
                                    'logo_kampus' => ['label' => 'Logo Kampus (UIS)', 'default' => asset('assets/img/logo_ibsi.png')],
                                    'logo_lima' => ['label' => 'Logo Tambahan', 'default' => null],
                                ] as $field => $meta)
                                    <div class="col-md-3 col-6 mb-3 text-center">
                                        <div class="border rounded-3 p-2 mb-2 d-flex align-items-center justify-content-center bg-light" style="height: 90px;">
                                            @php $currentSrc = $setting->$field ? asset('storage/' . $setting->$field) : $meta['default']; @endphp
                                            @if ($currentSrc)
                                                <img src="{{ $currentSrc }}" alt="{{ $meta['label'] }}" style="max-height: 100%; max-width: 100%;">
                                            @else
                                                <span class="text-muted extra-small">Belum ada logo</span>
                                            @endif
                                        </div>
                                        <label class="form-label small mb-1">{{ $meta['label'] }}</label>
                                        <input type="file" name="{{ $field }}" class="form-control form-control-sm" accept="image/*">
                                    </div>
                                @endforeach
                            </div>
                            <p class="extra-small text-muted">Logo DIKTI Saintek Berdampak & Belmawa belum tersedia di sistem — unggah file logo resminya di sini agar tampil pada sertifikat.</p>

                            <button type="submit" class="btn btn-success rounded px-4 mt-2">
                                <i class="bi bi-save me-1"></i> Simpan Pengaturan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Nomor Urut Sertifikat</h5>
                        <p class="small text-muted">Nomor terakhir yang sudah diterbitkan ke mahasiswa:</p>
                        <div class="display-6 fw-bold text-primary">{{ str_pad($setting->nomor_urut_terakhir, 4, '0', STR_PAD_LEFT) }}</div>
                        <p class="extra-small text-muted mb-0">Nomor berikutnya (<strong>{{ str_pad($setting->nomor_urut_terakhir + 1, 4, '0', STR_PAD_LEFT) }}</strong>) akan otomatis diberikan ke mahasiswa berikutnya yang status kelulusannya tampil.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Preview Sertifikat</h5>
                        <p class="extra-small text-muted">Contoh tampilan dengan data mahasiswa dummy. Perubahan pada form di sebelah kiri langsung terlihat di sini sebelum disimpan.</p>
                        @include('partials.sertifikat-card', [
                            'canvasId' => 'sertifikatPreviewCanvas',
                            'nomorUrut' => str_pad($setting->nomor_urut_terakhir + 1, 4, '0', STR_PAD_LEFT),
                            'kodeSurat' => $setting->kode_surat,
                            'namaMahasiswa' => 'Nama Lengkap Mahasiswa',
                            'npm' => '000000000',
                            'prodi' => 'PROGRAM STUDI CONTOH',
                            'fakultas' => 'FAKULTAS CONTOH',
                            'statusLulus' => true,
                            'namaKegiatan' => $setting->nama_kegiatan,
                            'lokasi' => $setting->lokasi,
                            'tanggal' => $setting->tanggal_pelaksanaan,
                            'namaMengetahui' => $setting->nama_mengetahui,
                            'jabatanMengetahui' => $setting->jabatan_mengetahui,
                            'nipMengetahui' => $setting->nip_mengetahui,
                            'namaKetuaPanitia' => $setting->nama_ketua_panitia,
                            'jabatanKetuaPanitia' => $setting->jabatan_ketua_panitia,
                            'nupKetuaPanitia' => $setting->nup_ketua_panitia,
                            'logoDikti' => $setting->logo_dikti ? asset('storage/' . $setting->logo_dikti) : null,
                            'logoBelmawa' => $setting->logo_belmawa ? asset('storage/' . $setting->logo_belmawa) : null,
                            'logoPkkmb' => $setting->logo_pkkmb ? asset('storage/' . $setting->logo_pkkmb) : asset('assets/img/logopkkmb.png'),
                            'logoKampus' => $setting->logo_kampus ? asset('storage/' . $setting->logo_kampus) : asset('assets/img/logo_ibsi.png'),
                            'logoLima' => $setting->logo_lima ? asset('storage/' . $setting->logo_lima) : null,
                            'verifikasiUrl' => url('/verifikasi-sertifikat/0?signature=preview-only-not-a-real-link'),
                        ])
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById('sertifikatSettingForm');
            var preview = document.getElementById('sertifikatPreviewCanvas');
            if (!form || !preview) return;

            var textFields = ['kode_surat', 'nama_kegiatan', 'lokasi', 'tanggal_pelaksanaan', 'nama_mengetahui', 'jabatan_mengetahui', 'nip_mengetahui', 'nama_ketua_panitia', 'jabatan_ketua_panitia', 'nup_ketua_panitia'];
            textFields.forEach(function (field) {
                var input = form.querySelector('[name="' + field + '"]');
                var target = preview.querySelector('[data-field="' + field + '"]');
                if (!input || !target) return;
                input.addEventListener('input', function () {
                    target.textContent = input.value;
                });
            });

            var logoFields = ['logo_dikti', 'logo_belmawa', 'logo_pkkmb', 'logo_kampus', 'logo_lima'];
            logoFields.forEach(function (field) {
                var input = form.querySelector('[name="' + field + '"]');
                var img = preview.querySelector('[data-logo-field="' + field + '"]');
                if (!input || !img) return;
                input.addEventListener('change', function () {
                    var file = input.files && input.files[0];
                    if (!file) return;
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        img.src = e.target.result;
                        img.style.display = '';
                    };
                    reader.readAsDataURL(file);
                });
            });
        });
    </script>
@endpush
