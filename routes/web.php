<?php

use App\Http\Controllers\AbsenAttachmentController;
use App\Http\Controllers\AbsenNoteController;
use App\Http\Controllers\AbsenKeduaController;
use App\Http\Controllers\AbsenKeduaScanController;
use App\Http\Controllers\AbsenKetigaController;
use App\Http\Controllers\AbsenKetigaScanController;
use App\Http\Controllers\AbsenPertamaController;
use App\Http\Controllers\AbsenScanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HasilTestController;
use App\Http\Controllers\KedisiplinanAttachmentController;
use App\Http\Controllers\KedisiplinanNoteController;
use App\Http\Controllers\KedisiplinanKeduaController;
use App\Http\Controllers\KedisiplinanKetigaController;
use App\Http\Controllers\KedisiplinanPertamaController;
use App\Http\Controllers\MateriModulController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\LpjAttachmentController;
use App\Http\Controllers\LpjController;
use App\Http\Controllers\ModulPostTestController;
use App\Http\Controllers\ObservasiAcaraController;
use App\Http\Controllers\ObservasiAcara2Controller;
use App\Http\Controllers\ObservasiAcaraFebController;
use App\Http\Controllers\ObservasiAcaraFstController;
use App\Http\Controllers\ObservasiAcaraFikesController;
use App\Http\Controllers\SoalPostTestKeduaController;
use App\Http\Controllers\SoalPostTestKeempatController;
use App\Http\Controllers\SoalPostTestKetigaController;
use App\Http\Controllers\SoalPostTestPertamaController;
use App\Http\Controllers\SoalPretestKeduaController;
use App\Http\Controllers\SoalPretestKeempatController;
use App\Http\Controllers\SoalPretestKetigaController;
use App\Http\Controllers\SoalPretestPertamaController;
use App\Http\Controllers\SoalTugasKelompokController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\KelompokNoteController;
use App\Http\Controllers\RekapKeseluruhanController;
use App\Http\Controllers\RekapEvaluasiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EvaluasiPengenalanWawasanIbnuSinaController;
use App\Http\Controllers\EvaluasiPelayananKemahasiswaanPusatPrestasiController;
use App\Http\Controllers\EvaluasiPelayanansistemAkademikController;
use App\Http\Controllers\EvaluasiPelayanansistemAdministrasiKeuanganController;
use App\Http\Controllers\EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegaraController;
use App\Http\Controllers\EvaluasiSistemPendidikanTinggidiIndonesiaController;
use App\Http\Controllers\EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustriController;
use App\Http\Controllers\EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkunganController;
use App\Http\Controllers\PerpustakaanController;
use App\Http\Controllers\EvaluasiIkaUisController;
use App\Http\Controllers\EvaluasiKewirausahaanController;
use App\Http\Controllers\EvaluasiPencarianBakatMahasiswaController;
use App\Http\Controllers\EvaluasiMotivasiWaliKotaBatamController;
use App\Http\Controllers\EvaluasiMotivasiGubernurKepulauanRiauController;
use App\Http\Controllers\EvaluasiFikesController;
use App\Http\Controllers\EvaluasiFstController;
use App\Http\Controllers\EvaluasiFebController;
use Illuminate\Support\Facades\Route;



Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'login')->name('login');
    Route::post('/', 'authenticate')->name('login.post');
    Route::get('/logout', 'logout')->name('logout');
});

// Public, signed-URL-only verification page (scanned via QR on the certificate). No login required.
Route::get('verifikasi-sertifikat/{user}', [\App\Http\Controllers\SertifikatVerifikasiController::class, 'show'])
    ->middleware('signed')
    ->name('sertifikat.verifikasi');

Route::middleware(['auth', 'checkrole'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('rekap-keseluruhan', [RekapKeseluruhanController::class, 'index'])->name('rekapkeseluruhan.index');
    Route::get('rekap-evaluasi', [RekapEvaluasiController::class, 'index'])->name('rekapevaluasi.index');
    Route::get('rekap-observasi', [\App\Http\Controllers\RekapObservasiController::class, 'index'])->name('rekapobservasi.index');
    Route::get('kelulusan', [\App\Http\Controllers\KelulusanController::class, 'index'])->name('kelulusan.index');
    Route::get('kelulusan/sertifikat-data/{id}', [\App\Http\Controllers\KelulusanController::class, 'getSertifikatData'])->name('kelulusan.sertifikatData');
    Route::post('kelulusan/{id}/toggle', [\App\Http\Controllers\KelulusanController::class, 'toggle'])->name('kelulusan.toggle');
    Route::post('kelulusan/bulk-toggle', [\App\Http\Controllers\KelulusanController::class, 'bulkToggle'])->name('kelulusan.bulkToggle');
    Route::get('hasiltest/export', [HasilTestController::class, 'export'])->name('hasiltest.export');
    Route::post('hasiltest/bulk-reset', [HasilTestController::class, 'bulkReset'])->name('hasiltest.bulkReset');
    Route::post('hasiltest/bulk-reset-modul', [HasilTestController::class, 'bulkResetModul'])->name('hasiltest.bulkResetModul');
    Route::delete('hasiltest/{id}/reset-single', [HasilTestController::class, 'resetSingle'])->name('hasiltest.resetSingle');
    Route::get('hasiltest/{type}/{modul}/export', [HasilTestController::class, 'exportModul'])->name('hasiltest.exportModul');
    Route::get('hasiltest/{type}/{modul}', [HasilTestController::class, 'showModul'])->name('hasiltest.modul');
    Route::post('hasiltest/user-reset/{user}', [HasilTestController::class, 'resetByUser'])->name('hasiltest.resetByUser');
    Route::resource('hasiltest', HasilTestController::class);

    Route::get('users/template', [UsersController::class, 'downloadTemplate'])->name('users.template');
    Route::post('users/import', [UsersController::class, 'import'])->name('users.import');
    Route::post('users/bulk-destroy', [UsersController::class, 'bulkDestroy'])->name('users.bulkDestroy');
    Route::resource('users', UsersController::class);

    Route::get('users/{user}/password', [UsersController::class, 'updatePassword'])->name('users.updatePassword');
    Route::post('users/{user}/password', [UsersController::class, 'updatePasswordPost'])->name('users.updatePasswordPost');
    Route::get('absenpertama/check-status/{user_id}', [AbsenPertamaController::class, 'checkStatus'])->name('absenpertama.checkStatus');
    Route::get('absenkedua/check-status/{user_id}', [AbsenKeduaController::class, 'checkStatus'])->name('absenkedua.checkStatus');
    Route::get('absenketiga/check-status/{user_id}', [AbsenKetigaController::class, 'checkStatus'])->name('absenketiga.checkStatus');
    Route::post('absenpertama/bulk-delete', [AbsenPertamaController::class, 'bulkDelete'])->name('absenpertama.bulk-delete');
    Route::post('absenkedua/bulk-delete', [AbsenKeduaController::class, 'bulkDelete'])->name('absenkedua.bulk-delete');
    Route::post('absenketiga/bulk-delete', [AbsenKetigaController::class, 'bulkDelete'])->name('absenketiga.bulk-delete');
    Route::get('absenpertama/export', [AbsenPertamaController::class, 'export'])->name('absenpertama.export');
    Route::get('absenkedua/export', [AbsenKeduaController::class, 'export'])->name('absenkedua.export');
    Route::get('absenketiga/export', [AbsenKetigaController::class, 'export'])->name('absenketiga.export');
    Route::resource('absenpertama', AbsenPertamaController::class);
    Route::resource('absenkedua', AbsenKeduaController::class);
    Route::resource('absenketiga', AbsenKetigaController::class);
    Route::post('absen-attachments', [AbsenAttachmentController::class, 'store'])->name('absen-attachments.store');
    Route::delete('absen-attachments/{id}', [AbsenAttachmentController::class, 'destroy'])->name('absen-attachments.destroy');
    Route::post('absen-notes', [AbsenNoteController::class, 'store'])->name('absen-notes.store');
    Route::delete('absen-notes/{id}', [AbsenNoteController::class, 'destroy'])->name('absen-notes.destroy');
    Route::get('absen-scan', [AbsenScanController::class, 'index'])->name('absen-scan.index');
    Route::get('absen-scan/get-token/{session}', [AbsenScanController::class, 'getDynamicToken'])->name('absen-scan.get-token');
    Route::post('absen-scan/update-setting', [AbsenScanController::class, 'updateSetting'])->name('absen-scan.update-setting');
    Route::post('absen-scan', [AbsenScanController::class, 'process'])->name('absen-scan.process');
    Route::get('absenkedua-scan', [AbsenKeduaScanController::class, 'index'])->name('absenkedua-scan.index');
    Route::post('absenkedua-scan', [AbsenKeduaScanController::class, 'process'])->name('absenkedua-scan.process');
    Route::get('absenketiga-scan', [AbsenKetigaScanController::class, 'index'])->name('absenketiga-scan.index');
    Route::post('absenketiga-scan', [AbsenKetigaScanController::class, 'process'])->name('absenketiga-scan.process');

    Route::get('kedisiplinanpertama/export', [KedisiplinanPertamaController::class, 'export'])->name('kedisiplinanpertama.export');
    Route::get('kedisiplinankedua/export', [KedisiplinanKeduaController::class, 'export'])->name('kedisiplinankedua.export');
    Route::get('kedisiplinanketiga/export', [KedisiplinanKetigaController::class, 'export'])->name('kedisiplinanketiga.export');
    Route::post('kedisiplinanpertama/bulk-update', [KedisiplinanPertamaController::class, 'bulkUpdate'])->name('kedisiplinanpertama.bulk-update');
    Route::post('kedisiplinanpertama/bulk-delete', [KedisiplinanPertamaController::class, 'bulkDelete'])->name('kedisiplinanpertama.bulk-delete');
    Route::resource('kedisiplinanpertama', KedisiplinanPertamaController::class);

    Route::post('kedisiplinankedua/bulk-update', [KedisiplinanKeduaController::class, 'bulkUpdate'])->name('kedisiplinankedua.bulk-update');
    Route::post('kedisiplinankedua/bulk-delete', [KedisiplinanKeduaController::class, 'bulkDelete'])->name('kedisiplinankedua.bulk-delete');
    Route::resource('kedisiplinankedua', KedisiplinanKeduaController::class);

    Route::post('kedisiplinanketiga/bulk-update', [KedisiplinanKetigaController::class, 'bulkUpdate'])->name('kedisiplinanketiga.bulk-update');
    Route::post('kedisiplinanketiga/bulk-delete', [KedisiplinanKetigaController::class, 'bulkDelete'])->name('kedisiplinanketiga.bulk-delete');
    Route::resource('kedisiplinanketiga', KedisiplinanKetigaController::class);

    Route::post('kedisiplinan-attachments', [KedisiplinanAttachmentController::class, 'store'])->name('kedisiplinan-attachments.store');
    Route::delete('kedisiplinan-attachments/{id}', [KedisiplinanAttachmentController::class, 'destroy'])->name('kedisiplinan-attachments.destroy');
    Route::post('kedisiplinan-notes', [KedisiplinanNoteController::class, 'store'])->name('kedisiplinan-notes.store');
    Route::delete('kedisiplinan-notes/{id}', [KedisiplinanNoteController::class, 'destroy'])->name('kedisiplinan-notes.destroy');

    Route::get('soalpretestpertama/template', [SoalPretestPertamaController::class, 'downloadTemplate'])->name('soalpretestpertama.template');
    Route::post('soalpretestpertama/import', [SoalPretestPertamaController::class, 'import'])->name('soalpretestpertama.import');
    Route::get('soalpretestpertama/export', [SoalPretestPertamaController::class, 'export'])->name('soalpretestpertama.export');
    Route::resource('soalpretestpertama', SoalPretestPertamaController::class);

    Route::get('soalpretestkedua/template', [SoalPretestKeduaController::class, 'downloadTemplate'])->name('soalpretestkedua.template');
    Route::post('soalpretestkedua/import', [SoalPretestKeduaController::class, 'import'])->name('soalpretestkedua.import');
    Route::get('soalpretestkedua/export', [SoalPretestKeduaController::class, 'export'])->name('soalpretestkedua.export');
    Route::resource('soalpretestkedua', SoalPretestKeduaController::class);

    Route::get('soalpretestketiga/template', [SoalPretestKetigaController::class, 'downloadTemplate'])->name('soalpretestketiga.template');
    Route::post('soalpretestketiga/import', [SoalPretestKetigaController::class, 'import'])->name('soalpretestketiga.import');
    Route::get('soalpretestketiga/export', [SoalPretestKetigaController::class, 'export'])->name('soalpretestketiga.export');
    Route::resource('soalpretestketiga', SoalPretestKetigaController::class);

    Route::get('soalpretestkeempat/template', [SoalPretestKeempatController::class, 'downloadTemplate'])->name('soalpretestkeempat.template');
    Route::post('soalpretestkeempat/import', [SoalPretestKeempatController::class, 'import'])->name('soalpretestkeempat.import');
    Route::get('soalpretestkeempat/export', [SoalPretestKeempatController::class, 'export'])->name('soalpretestkeempat.export');
    Route::resource('soalpretestkeempat', SoalPretestKeempatController::class);

    Route::get('soalposttestpertama/template', [SoalPostTestPertamaController::class, 'downloadTemplate'])->name('soalposttestpertama.template');
    Route::post('soalposttestpertama/import', [SoalPostTestPertamaController::class, 'import'])->name('soalposttestpertama.import');
    Route::get('soalposttestpertama/export', [SoalPostTestPertamaController::class, 'export'])->name('soalposttestpertama.export');
    Route::resource('soalposttestpertama', SoalPostTestPertamaController::class);

    Route::get('soalposttestkedua/template', [SoalPostTestKeduaController::class, 'downloadTemplate'])->name('soalposttestkedua.template');
    Route::post('soalposttestkedua/import', [SoalPostTestKeduaController::class, 'import'])->name('soalposttestkedua.import');
    Route::get('soalposttestkedua/export', [SoalPostTestKeduaController::class, 'export'])->name('soalposttestkedua.export');
    Route::resource('soalposttestkedua', SoalPostTestKeduaController::class);

    Route::get('soalposttestketiga/template', [SoalPostTestKetigaController::class, 'downloadTemplate'])->name('soalposttestketiga.template');
    Route::post('soalposttestketiga/import', [SoalPostTestKetigaController::class, 'import'])->name('soalposttestketiga.import');
    Route::get('soalposttestketiga/export', [SoalPostTestKetigaController::class, 'export'])->name('soalposttestketiga.export');
    Route::resource('soalposttestketiga', SoalPostTestKetigaController::class);

    Route::get('soalposttestkeempat/template', [SoalPostTestKeempatController::class, 'downloadTemplate'])->name('soalposttestkeempat.template');
    Route::post('soalposttestkeempat/import', [SoalPostTestKeempatController::class, 'import'])->name('soalposttestkeempat.import');
    Route::get('soalposttestkeempat/export', [SoalPostTestKeempatController::class, 'export'])->name('soalposttestkeempat.export');
    Route::resource('soalposttestkeempat', SoalPostTestKeempatController::class);
    Route::post('soaltugaskelompok/{id}/update-nilai', [SoalTugasKelompokController::class, 'updateNilai'])->name('soaltugaskelompok.update-nilai');
    Route::resource('soaltugaskelompok', SoalTugasKelompokController::class);
    Route::post('modulposttest/reset', [ModulPostTestController::class, 'reset'])->name('modulposttest.reset');
    Route::post('modulposttest/upload-tugas', [ModulPostTestController::class, 'uploadTugasKelompok'])->name('modulposttest.upload-tugas');
    Route::post('modulposttest/toggle-active/{modul}', [ModulPostTestController::class, 'toggleActive'])->name('modulposttest.toggle-active');
    Route::post('modulposttest/toggle-pretest/{modul}', [ModulPostTestController::class, 'togglePretestActive'])->name('modulposttest.toggle-pretest');
    Route::post('modulposttest/toggle-modul/{modul}', [ModulPostTestController::class, 'toggleModulActive'])->name('modulposttest.toggle-modul');
    Route::post('modulposttest/toggle-all-modul', [ModulPostTestController::class, 'toggleAllModul'])->name('modulposttest.toggle-all-modul');
    Route::post('modulposttest/toggle-all-pretest', [ModulPostTestController::class, 'toggleAllPretest'])->name('modulposttest.toggle-all-pretest');
    Route::post('modulposttest/toggle-all-posttest', [ModulPostTestController::class, 'toggleAllPosttest'])->name('modulposttest.toggle-all-posttest');
    Route::resource('modulposttest', ModulPostTestController::class);
    Route::resource('dokumen', DokumenController::class);
    Route::get('materimodul/{id}/download/{modul}', [MateriModulController::class, 'download'])->name('materimodul.download');
    Route::get('materimodul/{id}/view/{modul}', [MateriModulController::class, 'viewFile'])->name('materimodul.view');
    Route::resource('materimodul', MateriModulController::class);

    Route::resource('observasiacara', ObservasiAcaraController::class);

    Route::resource('observasiacara2', ObservasiAcara2Controller::class);

    Route::resource('observasiacarafeb', ObservasiAcaraFebController::class);

    Route::resource('observasiacarafst', ObservasiAcaraFstController::class);

    Route::resource('observasiacarafikes', ObservasiAcaraFikesController::class);

    Route::resource('lpj', LpjController::class);
    Route::post('lpj-attachments', [LpjAttachmentController::class, 'store'])->name('lpj-attachments.store');
    Route::get('lpj-attachments/{id}/edit', [LpjAttachmentController::class, 'edit'])->name('lpj-attachments.edit');
    Route::put('lpj-attachments/{id}', [LpjAttachmentController::class, 'update'])->name('lpj-attachments.update');
    Route::delete('lpj-attachments/{id}', [LpjAttachmentController::class, 'destroy'])->name('lpj-attachments.destroy');

    Route::get('kelompok/template', [KelompokController::class, 'downloadTemplate'])->name('kelompok.template');
    Route::post('kelompok/import', [KelompokController::class, 'import'])->name('kelompok.import');
    Route::get('kelompok/{slug}/template-member', [KelompokController::class, 'downloadMemberTemplate'])->name('kelompok.template-member');
    Route::post('kelompok/{slug}/import-member', [KelompokController::class, 'importMembers'])->name('kelompok.import-member');
    Route::post('kelompok/{id}/add-member', [KelompokController::class, 'addMember'])->name('kelompok.add-member');
    Route::delete('kelompok/{kelompokId}/remove-member/{userId}', [KelompokController::class, 'removeMember'])->name('kelompok.remove-member');
    Route::post('kelompok/{slug}/notes', [KelompokNoteController::class, 'store'])->name('kelompok.notes.store');
    Route::delete('kelompok/{slug}/notes/{id}', [KelompokNoteController::class, 'destroy'])->name('kelompok.notes.destroy');
    Route::resource('kelompok', KelompokController::class);

    Route::post('evaluasipengenalanwawasanibnusina/bulk-delete', [EvaluasiPengenalanWawasanIbnuSinaController::class, 'bulkDelete'])->name('evaluasipengenalanwawasanibnusina.bulk-delete');
    Route::resource('evaluasipengenalanwawasanibnusina', EvaluasiPengenalanWawasanIbnuSinaController::class)->parameters([
        'evaluasipengenalanwawasanibnusina' => 'evaluasi'
    ]);

    Route::post('evaluasipelayanankemahasiswaanpusatprestasi/bulk-delete', [EvaluasiPelayananKemahasiswaanPusatPrestasiController::class, 'bulkDelete'])->name('evaluasipelayanankemahasiswaanpusatprestasi.bulk-delete');
    Route::resource('evaluasipelayanankemahasiswaanpusatprestasi', EvaluasiPelayananKemahasiswaanPusatPrestasiController::class)->parameters([
        'evaluasipelayanankemahasiswaanpusatprestasi' => 'evaluasi'
    ]);

    Route::post('evaluasipelayanansistemakademik/bulk-delete', [EvaluasiPelayanansistemAkademikController::class, 'bulkDelete'])->name('evaluasipelayanansistemakademik.bulk-delete');
    Route::resource('evaluasipelayanansistemakademik', EvaluasiPelayanansistemAkademikController::class)->parameters([
        'evaluasipelayanansistemakademik' => 'evaluasi'
    ]);

    Route::post('evaluasipelayanansistemadministrasikeuangan/bulk-delete', [EvaluasiPelayanansistemAdministrasiKeuanganController::class, 'bulkDelete'])->name('evaluasipelayanansistemadministrasikeuangan.bulk-delete');
    Route::resource('evaluasipelayanansistemadministrasikeuangan', EvaluasiPelayanansistemAdministrasiKeuanganController::class)->parameters([
        'evaluasipelayanansistemadministrasikeuangan' => 'evaluasi'
    ]);

    Route::post('evaluasikehidupanberbangsabelanegara/bulk-delete', [EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegaraController::class, 'bulkDelete'])->name('evaluasikehidupanberbangsabelanegara.bulk-delete');
    Route::resource('evaluasikehidupanberbangsabelanegara', EvaluasiKehidupanBerbangsaBernegaradanPembinaanKesadaranBelaNegaraController::class)->parameters([
        'evaluasikehidupanberbangsabelanegara' => 'evaluasi'
    ]);

    Route::post('evaluasisistempendidikantinggidiindonesia/bulk-delete', [EvaluasiSistemPendidikanTinggidiIndonesiaController::class, 'bulkDelete'])->name('evaluasisistempendidikantinggidiindonesia.bulk-delete');
    Route::resource('evaluasisistempendidikantinggidiindonesia', EvaluasiSistemPendidikanTinggidiIndonesiaController::class)->parameters([
        'evaluasisistempendidikantinggidiindonesia' => 'evaluasi'
    ]);

    Route::post('evaluasipendidikantinggieradigital/bulk-delete', [EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustriController::class, 'bulkDelete'])->name('evaluasipendidikantinggieradigital.bulk-delete');
    Route::resource('evaluasipendidikantinggieradigital', EvbvaluasiPendidikanTinggidiEraDigitaldanRevolusiIndustriController::class)->parameters([
        'evaluasipendidikantinggieradigital' => 'evaluasi'
    ]);

    Route::post('evaluasipengenalank3l/bulk-delete', [EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkunganController::class, 'bulkDelete'])->name('evaluasipengenalank3l.bulk-delete');
    Route::resource('evaluasipengenalank3l', EvaluasiPengenalanKeselamatanKesehatanKerjadanLingkunganController::class)->parameters([
        'evaluasipengenalank3l' => 'evaluasi'
    ]);

    Route::post('evaluasiperpustakaan/bulk-delete', [PerpustakaanController::class, 'bulkDelete'])->name('evaluasiperpustakaan.bulk-delete');
    Route::resource('evaluasiperpustakaan', PerpustakaanController::class)->parameters([
        'evaluasiperpustakaan' => 'evaluasi'
    ]);

    Route::post('evaluasiikauis/bulk-delete', [EvaluasiIkaUisController::class, 'bulkDelete'])->name('evaluasiikauis.bulk-delete');
    Route::resource('evaluasiikauis', EvaluasiIkaUisController::class)->parameters([
        'evaluasiikauis' => 'evaluasi'
    ]);

    Route::post('evaluasikewirausahaan/bulk-delete', [EvaluasiKewirausahaanController::class, 'bulkDelete'])->name('evaluasikewirausahaan.bulk-delete');
    Route::resource('evaluasikewirausahaan', EvaluasiKewirausahaanController::class)->parameters([
        'evaluasikewirausahaan' => 'evaluasi'
    ]);

    Route::post('evaluasipencarianbakatmahasiswa/bulk-delete', [EvaluasiPencarianBakatMahasiswaController::class, 'bulkDelete'])->name('evaluasipencarianbakatmahasiswa.bulk-delete');
    Route::resource('evaluasipencarianbakatmahasiswa', EvaluasiPencarianBakatMahasiswaController::class)->parameters([
        'evaluasipencarianbakatmahasiswa' => 'evaluasi'
    ]);

    Route::post('evaluasimotivasiwalikotabatam/bulk-delete', [EvaluasiMotivasiWaliKotaBatamController::class, 'bulkDelete'])->name('evaluasimotivasiwalikotabatam.bulk-delete');
    Route::resource('evaluasimotivasiwalikotabatam', EvaluasiMotivasiWaliKotaBatamController::class)->parameters([
        'evaluasimotivasiwalikotabatam' => 'evaluasi'
    ]);

    Route::post('evaluasimotivasigubernurkepulauanriau/bulk-delete', [EvaluasiMotivasiGubernurKepulauanRiauController::class, 'bulkDelete'])->name('evaluasimotivasigubernurkepulauanriau.bulk-delete');
    Route::resource('evaluasimotivasigubernurkepulauanriau', EvaluasiMotivasiGubernurKepulauanRiauController::class)->parameters([
        'evaluasimotivasigubernurkepulauanriau' => 'evaluasi'
    ]);

    Route::post('evaluasifikes/bulk-delete', [EvaluasiFikesController::class, 'bulkDelete'])->name('evaluasifikes.bulk-delete');
    Route::resource('evaluasifikes', EvaluasiFikesController::class)->parameters([
        'evaluasifikes' => 'evaluasi'
    ]);

    Route::post('evaluasifst/bulk-delete', [EvaluasiFstController::class, 'bulkDelete'])->name('evaluasifst.bulk-delete');
    Route::resource('evaluasifst', EvaluasiFstController::class)->parameters([
        'evaluasifst' => 'evaluasi'
    ]);

    Route::post('evaluasifeb/bulk-delete', [EvaluasiFebController::class, 'bulkDelete'])->name('evaluasifeb.bulk-delete');
    Route::resource('evaluasifeb', EvaluasiFebController::class)->parameters([
        'evaluasifeb' => 'evaluasi'
    ]);

    Route::get('evaluasi-export/{id}', [\App\Http\Controllers\EvaluasiExportController::class, 'export'])->name('evaluasi.export');

    Route::get('evaluasimenu', [\App\Http\Controllers\EvaluasiMenuController::class, 'index'])->name('evaluasimenu.index');
    Route::post('evaluasimenu/bulk-toggle', [\App\Http\Controllers\EvaluasiMenuController::class, 'bulkToggle'])->name('evaluasimenu.bulkToggle');
    Route::post('evaluasimenu/{id}/toggle', [\App\Http\Controllers\EvaluasiMenuController::class, 'toggle'])->name('evaluasimenu.toggle');
    Route::get('evaluasimenu/{id}/questions', [\App\Http\Controllers\EvaluasiMenuController::class, 'questions'])->name('evaluasimenu.questions');
    Route::post('evaluasimenu/{id}/questions', [\App\Http\Controllers\EvaluasiMenuController::class, 'updateQuestions'])->name('evaluasimenu.updateQuestions');
    Route::post('evaluasimenu/{id}/reset-questions', [\App\Http\Controllers\EvaluasiMenuController::class, 'resetQuestions'])->name('evaluasimenu.resetQuestions');

    Route::get('pengaturan-sertifikat', [\App\Http\Controllers\SertifikatSettingController::class, 'index'])->name('sertifikatsetting.index');
    Route::put('pengaturan-sertifikat', [\App\Http\Controllers\SertifikatSettingController::class, 'update'])->name('sertifikatsetting.update');

    Route::get('chatbot-faq', [\App\Http\Controllers\ChatbotFaqController::class, 'index'])->name('chatbot-faq.index');
    Route::post('chatbot-faq', [\App\Http\Controllers\ChatbotFaqController::class, 'store'])->name('chatbot-faq.store');
    Route::put('chatbot-faq/{id}', [\App\Http\Controllers\ChatbotFaqController::class, 'update'])->name('chatbot-faq.update');
    Route::delete('chatbot-faq/{id}', [\App\Http\Controllers\ChatbotFaqController::class, 'destroy'])->name('chatbot-faq.destroy');
    Route::post('chatbot-faq/{id}/toggle', [\App\Http\Controllers\ChatbotFaqController::class, 'toggle'])->name('chatbot-faq.toggle');
    Route::post('chatbot-faq/update-wa', [\App\Http\Controllers\ChatbotFaqController::class, 'updateWaGroup'])->name('chatbot-faq.update-wa');

    Route::get('chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('chat/messages/{userId}', [\App\Http\Controllers\ChatController::class, 'fetchMessages'])->name('chat.fetch');
    Route::post('chat/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('chat/unread-total', [\App\Http\Controllers\ChatController::class, 'getUnreadTotal'])->name('chat.unread-total');
    Route::get('chat/unread-details', [\App\Http\Controllers\ChatController::class, 'getUnreadDetails'])->name('chat.unread-details');
});





