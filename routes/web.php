<?php

use App\Http\Controllers\AbsenKeduaController;
use App\Http\Controllers\AbsenKeduaScanController;
use App\Http\Controllers\AbsenKetigaController;
use App\Http\Controllers\AbsenKetigaScanController;
use App\Http\Controllers\AbsenPertamaController;
use App\Http\Controllers\AbsenScanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HasilTestController;
use App\Http\Controllers\KedisiplinanKeduaController;
use App\Http\Controllers\KedisiplinanKetigaController;
use App\Http\Controllers\KedisiplinanPertamaController;
use App\Http\Controllers\MateriModulController;
use App\Http\Controllers\ModulPostTestController;
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
use App\Http\Controllers\RekapKeseluruhanController;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'login')->name('login');
    Route::post('/', 'authenticate')->name('login.post');
    Route::get('/logout', 'logout')->name('logout');
});

Route::middleware(['auth', 'checkrole'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('rekap-keseluruhan', [RekapKeseluruhanController::class, 'index'])->name('rekapkeseluruhan.index');
    Route::get('hasiltest/export', [HasilTestController::class, 'export'])->name('hasiltest.export');
    Route::post('hasiltest/bulk-reset', [HasilTestController::class, 'bulkReset'])->name('hasiltest.bulkReset');
    Route::post('hasiltest/user-reset/{user}', [HasilTestController::class, 'resetByUser'])->name('hasiltest.resetByUser');
    Route::resource('hasiltest', HasilTestController::class);

    Route::get('users/template', [UsersController::class, 'downloadTemplate'])->name('users.template');
    Route::post('users/import', [UsersController::class, 'import'])->name('users.import');
    Route::resource('users', UsersController::class);

    Route::get('users/{user}/password', [UsersController::class, 'updatePassword'])->name('users.updatePassword');
    Route::post('users/{user}/password', [UsersController::class, 'updatePasswordPost'])->name('users.updatePasswordPost');
    Route::resource('absenpertama', AbsenPertamaController::class);
    Route::resource('absenkedua', AbsenKeduaController::class);
    Route::resource('absenketiga', AbsenKetigaController::class);
    Route::get('absen-scan', [AbsenScanController::class, 'index'])->name('absen-scan.index');
    Route::post('absen-scan', [AbsenScanController::class, 'process'])->name('absen-scan.process');
    Route::get('absenkedua-scan', [AbsenKeduaScanController::class, 'index'])->name('absenkedua-scan.index');
    Route::post('absenkedua-scan', [AbsenKeduaScanController::class, 'process'])->name('absenkedua-scan.process');
    Route::get('absenketiga-scan', [AbsenKetigaScanController::class, 'index'])->name('absenketiga-scan.index');
    Route::post('absenketiga-scan', [AbsenKetigaScanController::class, 'process'])->name('absenketiga-scan.process');

    Route::post('kedisiplinanpertama/bulk-update', [KedisiplinanPertamaController::class, 'bulkUpdate'])->name('kedisiplinanpertama.bulk-update');
    Route::resource('kedisiplinanpertama', KedisiplinanPertamaController::class);

    Route::post('kedisiplinankedua/bulk-update', [KedisiplinanKeduaController::class, 'bulkUpdate'])->name('kedisiplinankedua.bulk-update');
    Route::resource('kedisiplinankedua', KedisiplinanKeduaController::class);

    Route::post('kedisiplinanketiga/bulk-update', [KedisiplinanKetigaController::class, 'bulkUpdate'])->name('kedisiplinanketiga.bulk-update');
    Route::resource('kedisiplinanketiga', KedisiplinanKetigaController::class);

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
    Route::resource('soaltugaskelompok', SoalTugasKelompokController::class);
    Route::post('modulposttest/reset', [ModulPostTestController::class, 'reset'])->name('modulposttest.reset');
    Route::post('modulposttest/upload-tugas', [ModulPostTestController::class, 'uploadTugasKelompok'])->name('modulposttest.upload-tugas');
    Route::resource('modulposttest', ModulPostTestController::class);
    Route::resource('materimodul', MateriModulController::class);
});
