<?php

use App\Http\Controllers\Admin\MasterUserController;
use App\Http\Controllers\Admin\SampleCrudController;
use App\Http\Controllers\Admin\TinyEditorController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\klinik\Dashboard\DashboarddController;
use App\Http\Controllers\klinik\DataMaster\AnggotaPersonilController;
use App\Http\Controllers\klinik\DataMaster\AnggotaSiswaController;
use App\Http\Controllers\Klinik\DataMaster\MasterAnggotaController;
use App\Http\Controllers\Klinik\DataMaster\MasterDokterController;
use App\Http\Controllers\Klinik\DataMaster\MasterObatController;
use App\Http\Controllers\Klinik\DataMaster\PenyesuaianStokObatController;
use App\Http\Controllers\Klinik\Laporan\LaporanController;
use App\Http\Controllers\klinik\Pasien\PasienController;
use App\Http\Controllers\klinik\Pemeriksaan\PemeriksaanController;
use App\Http\Controllers\Klinik\Riwayat\RiwayatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::middleware(['auth'])->group(function () {
   Route::get('beranda', [BerandaController::class, 'index'])->name('beranda.index');
   Route::controller(UserController::class)->group(function () {
      Route::put('user/profile/{user_id}', 'update')->name('user.update');
      Route::get('user/profile', 'profile')->name('user.profile');
      Route::get('user/profile/{username}', 'show')->name('user.show');
      Route::put('user/profile/photo/change', 'changePhoto')->name('user.change.photo');
   });

   Route::post('tiny-editor/upload', [TinyEditorController::class, 'upload'])->name('tiny-editor.upload');
   Route::resource('sample-crud', SampleCrudController::class);


   // app klinik
   Route::get('dashboard', [DashboarddController::class, 'index'])->name('klinik.dashboard.index');
   Route::get('riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
   Route::get('riwayat/user/{user_id}', [RiwayatController::class, 'show'])->name('riwayat.show');

   Route::get('laporan/pemeriksaan', [LaporanController::class, 'pemeriksaan'])->name('laporan.pemeriksaan');
   Route::get('laporan/obat', [LaporanController::class, 'obat'])->name('laporan.obat');


   Route::resource('pasien.pemeriksaan', PemeriksaanController::class);
   Route::resource('pasien', PasienController::class);
   
  
   Route::get('anggota/{user_id}/jenis/{jenis}', [PemeriksaanController::class, 'userDetail'])->name('anggota.detail');
 

   Route::resource('master-data/anggota/siswa', AnggotaSiswaController::class, [
      'as' => 'master-data',
   ]) ->parameters(['anggota' => 'anggota_personil']);

   Route::resource('master-data/anggota/personil', AnggotaPersonilController::class, [
      'as' => 'master-data',
   ]) ->parameters(['anggota' => 'anggota_siswa']);

   Route::resource('master-data/dokter', MasterDokterController::class, [
      'as' => 'master-data',
     
   ]); 
   
  
   Route::get('master-data/penyesuaian/obat', [PenyesuaianStokObatController::class, 'index'])->name('penyesuaian.stok.obat');
   Route::get('master-data/penyesuaian/obat/riwayat', [PenyesuaianStokObatController::class, 'riwayat'])->name('penyesuaian.stok.obat.riwayat');
   Route::resource('master-data/obat', MasterObatController::class, [
      'as' => 'master-data'
   ]);

   Route::resource('master-data/pengguna', MasterUserController::class, [
      'as' => 'master-data'
   ]);
});
