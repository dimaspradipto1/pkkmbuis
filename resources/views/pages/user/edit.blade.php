@extends('dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Edit User</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Data Users</a></li>
                <li class="breadcrumb-item active">Edit User</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form Edit User</h5>

                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Nama Lengkap</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $user->name) }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="id_pendaftar" class="col-sm-2 col-form-label">ID Pendaftar</label>
                                <div class="col-sm-10">
                                    <input type="text" name="id_pendaftar" class="form-control" id="id_pendaftar" value="{{ old('id_pendaftar', $user->id_pendaftar) }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nim" class="col-sm-2 col-form-label">NIM</label>
                                <div class="col-sm-10">
                                    <input type="text" name="nim" class="form-control" id="nim" value="{{ old('nim', $user->nim) }}" placeholder="Opsional / jika sudah ada">
                                    <small class="text-muted" style="font-size: 0.75rem;">Nomor Induk Mahasiswa (opsional, dapat digunakan untuk login).</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" name="email" class="form-control" id="email" value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="no_wa" class="col-sm-2 col-form-label">No. WhatsApp</label>
                                <div class="col-sm-10">
                                    <input type="text" name="no_wa" class="form-control" id="no_wa" value="{{ old('no_wa', $user->no_wa) }}" placeholder="Contoh: 6281234567890">
                                    <small class="text-muted" style="font-size: 0.75rem;">Gunakan format 628... tanpa spasi atau tanda hubung.</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="role" class="col-sm-2 col-form-label">Role</label>
                                <div class="col-sm-10">
                                    <select name="role" class="form-select" id="role" required>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="stafbaak" {{ old('role', $user->role) == 'stafbaak' ? 'selected' : '' }}>Staf BAAK</option>
                                        <option value="pimpinan" {{ old('role', $user->role) == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                                        <option value="kakakpendamping" {{ old('role', $user->role) == 'kakakpendamping' ? 'selected' : '' }}>Kakak Pendamping</option>
                                        <option value="dosenpendamping" {{ old('role', $user->role) == 'dosenpendamping' ? 'selected' : '' }}>Dosen Pendamping</option>
                                        <option value="timevaluasi" {{ old('role', $user->role) == 'timevaluasi' ? 'selected' : '' }}>Tim Evaluasi</option>
                                        <option value="panitia" {{ old('role', $user->role) == 'panitia' ? 'selected' : '' }}>Panitia</option>
                                        <option value="mahasiswa" {{ old('role', $user->role) == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                    </select>
                                </div>
                            </div>

                            @php
                                $jabatanList = [
                                    'Pengarah',
                                    'Ketua Pelaksana',
                                    'Wakil Ketua I',
                                    'Wakil Ketua II',
                                    'Wakil Ketua III',
                                    'Wakil Ketua IV',
                                    'Sekretaris',
                                    'Wakil Sekretaris I',
                                    'Wakil Sekretaris II',
                                    'Wakil Sekretaris III',
                                    'Bendahara',
                                    'Wakil Bendahara I',
                                    'Wakil Bendahara II',
                                    'Wakil Bendahara III',
                                    'Wakil Bendahara IV',
                                    'Koordinator Seksi Kesekretariatan',
                                    'Anggota Seksi Kesekretariatan',
                                    'Koordinator Seksi Acara',
                                    'Anggota Seksi Acara',
                                    'Koordinator Seksi Kelompok',
                                    'Anggota Seksi Kelompok',
                                    'Koordinator Seksi Konsumsi',
                                    'Anggota Seksi Konsumsi',
                                    'Koordinator Seksi Perlengkapan',
                                    'Anggota Seksi Perlengkapan',
                                    'Koordinator Seksi Desain, Dokumentasi dan Publikasi',
                                    'Anggota Seksi Desain, Dokumentasi dan Publikasi',
                                    'Koordinator Seksi LPTI',
                                    'Anggota Seksi LPTI',
                                    'Koordinator Seksi P3K',
                                    'Anggota Seksi P3K',
                                    'Koordinator Seksi Perencanaan dan Evaluasi Kegiatan',
                                    'Anggota Seksi Perencanaan dan Evaluasi Kegiatan',
                                ];
                            @endphp

                            <div class="row mb-3" id="jabatan-panitia-group" style="display: none;">
                                <label for="jabatan_panitia" class="col-sm-2 col-form-label">Jabatan Panitia</label>
                                <div class="col-sm-10">
                                    <select name="jabatan_panitia" class="form-select" id="jabatan_panitia">
                                        <option value=""></option>
                                        @foreach ($jabatanList as $jabatan)
                                            <option value="{{ $jabatan }}" {{ old('jabatan_panitia', $user->jabatan_panitia) == $jabatan ? 'selected' : '' }}>{{ $jabatan }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted" style="font-size: 0.75rem;">Opsional / boleh dikosongkan. Ketik untuk mencari jabatan.</small>
                                </div>
                            </div>

                            <div class="row mb-3" id="fakultas-group">
                                <label for="fakultas" class="col-sm-2 col-form-label">Fakultas</label>
                                <div class="col-sm-10">
                                    <select name="fakultas" class="form-select" id="fakultas">
                                        <option value="" selected>-- Pilih Fakultas --</option>
                                        <option value="FAKULTAS EKONOMI DAN BISNIS (FEB)" {{ old('fakultas', $user->fakultas) == 'FAKULTAS EKONOMI DAN BISNIS (FEB)' ? 'selected' : '' }}>FAKULTAS EKONOMI DAN BISNIS (FEB)</option>
                                        <option value="FAKULTAS SAINS DAN TEKNOLOGI (FST)" {{ old('fakultas', $user->fakultas) == 'FAKULTAS SAINS DAN TEKNOLOGI (FST)' ? 'selected' : '' }}>FAKULTAS SAINS DAN TEKNOLOGI (FST)</option>
                                        <option value="FAKULTAS ILMU KESEHATAN (FIKes)" {{ old('fakultas', $user->fakultas) == 'FAKULTAS ILMU KESEHATAN (FIKes)' ? 'selected' : '' }}>FAKULTAS ILMU KESEHATAN (FIKes)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3" id="prodi-group">
                                <label for="program_studi" class="col-sm-2 col-form-label">Program Studi</label>
                                <div class="col-sm-10">
                                    <select name="program_studi" class="form-select" id="program_studi">
                                        <option value="" selected>-- Pilih Program Studi --</option>
                                        <optgroup label="FAKULTAS EKONOMI DAN BISNIS (FEB)">
                                            <option value="S2 MAGISTER MANAJEMEN" {{ old('program_studi', $user->program_studi) == 'S2 MAGISTER MANAJEMEN' ? 'selected' : '' }}>S2 MAGISTER MANAJEMEN</option>
                                            <option value="S1 AKUNTANSI" {{ old('program_studi', $user->program_studi) == 'S1 AKUNTANSI' ? 'selected' : '' }}>S1 AKUNTANSI</option>
                                            <option value="S1 MANAJEMEN" {{ old('program_studi', $user->program_studi) == 'S1 MANAJEMEN' ? 'selected' : '' }}>S1 MANAJEMEN</option>
                                        </optgroup>
                                        <optgroup label="FAKULTAS SAINS DAN TEKNOLOGI (FST)">
                                            <option value="S1 TEKNIK INDUSTRI" {{ old('program_studi', $user->program_studi) == 'S1 TEKNIK INDUSTRI' ? 'selected' : '' }}>S1 TEKNIK INDUSTRI</option>
                                            <option value="S1 TEKNIK INFORMATIKA" {{ old('program_studi', $user->program_studi) == 'S1 TEKNIK INFORMATIKA' ? 'selected' : '' }}>S1 TEKNIK INFORMATIKA</option>
                                            <option value="S1 TEKNIK LOGISTIK" {{ old('program_studi', $user->program_studi) == 'S1 TEKNIK LOGISTIK' ? 'selected' : '' }}>S1 TEKNIK LOGISTIK</option>
                                            <option value="S1 SISTEM INFORMASI" {{ old('program_studi', $user->program_studi) == 'S1 SISTEM INFORMASI' ? 'selected' : '' }}>S1 SISTEM INFORMASI</option>
                                            <option value="S1 TEKNIK PERKAPALAN" {{ old('program_studi', $user->program_studi) == 'S1 TEKNIK PERKAPALAN' ? 'selected' : '' }}>S1 TEKNIK PERKAPALAN</option>
                                        </optgroup>
                                        <optgroup label="FAKULTAS ILMU KESEHATAN (FIKes)">
                                            <option value="S1 KESEHATAN DAN KESELAMATAN KERJA" {{ old('program_studi', $user->program_studi) == 'S1 KESEHATAN DAN KESELAMATAN KERJA' ? 'selected' : '' }}>S1 KESEHATAN DAN KESELAMATAN KERJA</option>
                                            <option value="S1 KESEHATAN LINGKUNGAN" {{ old('program_studi', $user->program_studi) == 'S1 KESEHATAN LINGKUNGAN' ? 'selected' : '' }}>S1 KESEHATAN LINGKUNGAN</option>
                                            <option value="S2 KESEHATAN MASYARAKAT" {{ old('program_studi', $user->program_studi) == 'S2 KESEHATAN MASYARAKAT' ? 'selected' : '' }}>S2 KESEHATAN MASYARAKAT</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Aktif</label>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-warning text-white">Update User</button>
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <script>
        const jabatanPanitiaOptions = [
            { id: '', text: '-- Pilih Jabatan Panitia (Opsional) --' },
            { id: 'Pengarah', text: 'Pengarah' },
            { id: 'Ketua Pelaksana', text: 'Ketua Pelaksana' },
            { id: 'Wakil Ketua I', text: 'Wakil Ketua I' },
            { id: 'Wakil Ketua II', text: 'Wakil Ketua II' },
            { id: 'Wakil Ketua III', text: 'Wakil Ketua III' },
            { id: 'Wakil Ketua IV', text: 'Wakil Ketua IV' },
            { id: 'Sekretaris', text: 'Sekretaris' },
            { id: 'Wakil Sekretaris I', text: 'Wakil Sekretaris I' },
            { id: 'Wakil Sekretaris II', text: 'Wakil Sekretaris II' },
            { id: 'Wakil Sekretaris III', text: 'Wakil Sekretaris III' },
            { id: 'Bendahara', text: 'Bendahara' },
            { id: 'Wakil Bendahara I', text: 'Wakil Bendahara I' },
            { id: 'Wakil Bendahara II', text: 'Wakil Bendahara II' },
            { id: 'Wakil Bendahara III', text: 'Wakil Bendahara III' },
            { id: 'Wakil Bendahara IV', text: 'Wakil Bendahara IV' },
            { id: 'Koordinator Seksi Kesekretariatan', text: 'Koordinator Seksi Kesekretariatan' },
            { id: 'Anggota Seksi Kesekretariatan', text: 'Anggota Seksi Kesekretariatan' },
            { id: 'Koordinator Seksi Acara', text: 'Koordinator Seksi Acara' },
            { id: 'Anggota Seksi Acara', text: 'Anggota Seksi Acara' },
            { id: 'Koordinator Seksi Kelompok', text: 'Koordinator Seksi Kelompok' },
            { id: 'Anggota Seksi Kelompok', text: 'Anggota Seksi Kelompok' },
            { id: 'Koordinator Seksi Konsumsi', text: 'Koordinator Seksi Konsumsi' },
            { id: 'Anggota Seksi Konsumsi', text: 'Anggota Seksi Konsumsi' },
            { id: 'Koordinator Seksi Perlengkapan', text: 'Koordinator Seksi Perlengkapan' },
            { id: 'Anggota Seksi Perlengkapan', text: 'Anggota Seksi Perlengkapan' },
            { id: 'Koordinator Seksi Desain, Dokumentasi dan Publikasi', text: 'Koordinator Seksi Desain, Dokumentasi dan Publikasi' },
            { id: 'Anggota Seksi Desain, Dokumentasi dan Publikasi', text: 'Anggota Seksi Desain, Dokumentasi dan Publikasi' },
            { id: 'Koordinator Seksi LPTI', text: 'Koordinator Seksi LPTI' },
            { id: 'Anggota Seksi LPTI', text: 'Anggota Seksi LPTI' },
            { id: 'Koordinator Seksi P3K', text: 'Koordinator Seksi P3K' },
            { id: 'Anggota Seksi P3K', text: 'Anggota Seksi P3K' },
            { id: 'Koordinator Seksi Perencanaan dan Evaluasi Kegiatan', text: 'Koordinator Seksi Perencanaan dan Evaluasi Kegiatan' },
            { id: 'Anggota Seksi Perencanaan dan Evaluasi Kegiatan', text: 'Anggota Seksi Perencanaan dan Evaluasi Kegiatan' }
        ];

        function updatePanitiaUI() {
            const roleEl = document.getElementById('role');
            if (!roleEl) return;
            const isPanitia = (roleEl.value === 'panitia');

            const jG = document.getElementById('jabatan-panitia-group');
            const fG = document.getElementById('fakultas-group');
            const pG = document.getElementById('prodi-group');

            if (jG) jG.style.display = isPanitia ? 'flex' : 'none';
            if (fG) fG.style.display = isPanitia ? 'none' : 'flex';
            if (pG) pG.style.display = isPanitia ? 'none' : 'flex';

            if (isPanitia && window.jQuery && typeof window.jQuery.fn.select2 !== 'undefined') {
                const $j = window.jQuery('#jabatan_panitia');
                if ($j.length) {
                    const currentVal = $j.val() || "{{ old('jabatan_panitia', $user->jabatan_panitia) }}";
                    if ($j.data('select2')) {
                        $j.select2('destroy');
                    }
                    $j.empty();
                    jabatanPanitiaOptions.forEach(function(item) {
                        const isSelected = (item.id === currentVal && currentVal !== '');
                        $j.append(new Option(item.text, item.id, false, isSelected));
                    });
                    $j.select2({
                        theme: 'bootstrap-5',
                        placeholder: '-- Cari & Pilih Jabatan Panitia (Opsional) --',
                        allowClear: true,
                        width: '100%'
                    });
                    if (currentVal) {
                        $j.val(currentVal).trigger('change.select2');
                    }
                    $j.next('.select2-container').css('width', '100%');
                }
            }
        }

        document.addEventListener('change', function(e) {
            if (e.target && e.target.id === 'role') {
                updatePanitiaUI();
            }
        });

        document.addEventListener('DOMContentLoaded', updatePanitiaUI);
        window.addEventListener('load', updatePanitiaUI);
    </script>
@endsection
