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
    Route::get('pegawai', 'PegawaiController@index');
});
