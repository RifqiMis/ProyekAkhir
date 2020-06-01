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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::match(["GET", "POST"], "/register", function(){
    return redirect("/login");
})->name("register");

// Route::get('pegawai', 'PegawaiController@index')->middleware('auth');

Route::group(['middleware'=>['auth','checkRole:admin,manajer']],function(){
    Route::resource('jabatan', 'JabatanController');
    Route::resource('pekerjaan', 'PekerjaanController');
    Route::resource('pekerjaan-meta', 'PekerjaanMetaController');
    Route::resource('proyek', 'ProyekController');
    Route::resource('pegawai', 'PegawaiController');
    Route::resource('jam-kerja', 'JamKerjaController');
    Route::resource('kelompok_pegawai', 'KelompokPegawaiController');
    Route::resource('riwayat-pekerjaan', 'RiwayatPekerjaanController');
    // Route::get('pegawai', 'PegawaiController@index');
    Route::get('pekerjaan/{id_pekerjaan}/meta', 'PekerjaanController@getMeta');

});


// route test QrCode
// Route::get('qrcode', function () {
//     return QrCode::size(300)->generate('A basic example of QR code!');
// });


