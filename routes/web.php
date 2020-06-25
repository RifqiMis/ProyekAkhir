<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::resource('/', 'PresensiController');
Route::get('get', 'PresensiController@get')->name('get');
Route::get('/presensi-pegawai', 'PresensiController@pegawai')->name('presensi-pegawai');
Route::get('/presensi-pekerjaan', 'PresensiController@pekerjaan')->name('presensi-pekerjaan');

Auth::routes();
Route::get('/home', 'BerandaController@index')->name('home');

Route::match(["GET", "POST"], "/register", function(){
    return redirect("/login");
})->name("register");

Route::group(['middleware'=>['auth','checkRole:super admin']],function(){
    Route::resource('user', 'UserController');
});


Route::group(['middleware'=>['auth','checkRole:admin,manajer,hrd,super admin,warehouse']],function(){
    Route::resource('home', 'BerandaController');

    // Ajax
    Route::get('pegawai-terlambat', 'RiwayatPresensiController@terlambat')->name('pegawai-terlambat');
    Route::get('warehouse', 'RiwayatPresensiController@warehouse')->name('warehouse');
});

Route::group(['middleware'=>['auth','checkRole:admin,manajer,super admin']],function(){
    Route::resource('jabatan', 'JabatanController');
    Route::resource('jam-kerja', 'JamKerjaController');
    Route::resource('kelompok_pegawai', 'KelompokPegawaiController');
    Route::resource('pegawai', 'PegawaiController');
    Route::resource('pekerjaan', 'PekerjaanController');
    Route::resource('pekerjaan-meta', 'PekerjaanMetaController');
    Route::resource('proyek', 'ProyekController');
    Route::resource('presensi-proyek', 'PresensiProyekController');
    Route::any('presensi-proyek/laporan', 'PresensiProyekController@laporan');
    Route::get('presensi-proyek-ongoing', 'PresensiProyekController@ongoing')->name('presensi-proyek-ongoing');
    Route::resource('riwayat-pekerjaan', 'RiwayatPekerjaanController');

    // Ajax
    Route::any('laporan-harian', 'PresensiProyekController@laporan')->name('laporan-harian');
    Route::any('laporan-harian-pegawai', 'PresensiProyekController@laporanPegawai')->name('laporan-harian-pegawai');
    Route::get('pegawai-absen', 'RiwayatPresensiController@absen')->name('pegawai-absen');
    Route::get('riwayat-pegawai', 'BerandaController@pegawai')->name('riwayat-pegawai');
    Route::any('pegawai-cari', 'PegawaiController@cari');
    Route::get('pekerjaan/{id_proyek}/metaKerja', 'PekerjaanController@getMetaKerja');
    Route::get('pekerjaan/{id_proyek}/{id_pekerjaan}/metaPresen', 'PekerjaanController@getMetaPresen');
    Route::get('akumulasi-presensi', 'RiwayatPresensiController@akumulasi')->name('akumulasi-presensi');
    Route::get('akumulasi-pegawai', 'RiwayatPresensiController@akumulasiPegawai')->name('akumulasi-pegawai');
    Route::get('pengerjaan', 'RiwayatPresensiController@pengerjaanHariIni')->name('pengerjaan');
    Route::get('beranda/pengerjaan', 'BerandaController@pengerjaanHariIni');
    Route::get('pekerjaan/{id_pekerjaan}/meta', 'PekerjaanController@getMeta');
    Route::get('proyek-total', 'ProyekController@total')->name('proyek-total');

});



