<?php

use App\Http\Controllers\Dokters\DokterController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/register-dokter', function () {
    return view('auth.register-x');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', 'App\Http\Controllers\HomeController@index')->name('dashboard');

Route::get('/addDoctorOrNurse', 'App\Http\Controllers\Pasiens\PasienController@addDocNur');

Route::get('/owncheck', 'App\Http\Controllers\Pasiens\PasienController@ownCheck');

Route::get('/result', 'App\Http\Controllers\Pasiens\PasienController@result');

Route::post('/upload_signal', 'App\Http\Controllers\Pasiens\PasienController@uploadSignal');
Route::get('/postScanning', 'App\Http\Controllers\Pasiens\PasienController@postScanning');

Route::post('/run', 'App\Http\Controllers\MachineLearning\MLController@runml');

Route::post('/graph', 'App\Http\Controllers\MachineLearning\MLController@graph');

Route::get('/datails/{id}', 'App\Http\Controllers\Pasiens\PasienController@result')->name('pasien.pasien.result');

Route::get('/classification/{id}', 'App\Http\Controllers\Dokters\DokterController@classification')->name('dokter.dokter.classification');

Route::post('/uploadSignal/{user_id}', 'App\Http\Controllers\Dokters\DokterController@uploadsignal');

Route::get('/result/{id}', 'App\Http\Controllers\Dokters\DokterController@result')->name('dokter.dokter.result');

Route::post('/resultOffline/{id}', 'App\Http\Controllers\MachineLearning\MLController@runIndocnur')->name('dokter.dokter.resultOffline');
Route::get('/resultOffline/{id}', 'App\Http\Controllers\MachineLearning\MLController@runIndocnur')->name('dokter.dokter.resultOffline');

Route::get('/patientProfile/{id}', 'App\Http\Controllers\Dokters\DokterController@patientProfile')->name('dokter.dokter.pasien');

Route::get('/patientDetails/{id}', 'App\Http\Controllers\Dokters\DokterController@detailPatient')->name('dokter.dokter.details');

Route::get('/onlineCheck', 'App\Http\Controllers\Dokters\DokterController@onlineCheck')->name('dokter.onlineCheck');
Route::get('/mqtt', 'App\Http\Controllers\Pasiens\PasienController@mqtt')->name('pasien.mqtt');
Route::get('/mqtt_sub', 'App\Http\Controllers\Pasiens\PasienController@mqtt_sub')->name('pasien.mqtt_sub');

Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => 'role:pasien', 'prefix' => 'pasien', 'as' => 'pasien.'], function () {
        Route::resource('pasiens', App\Http\Controllers\Pasiens\PasienController::class);
    });
    Route::group(['middleware' => 'role:dokter', 'prefix' => 'dokter', 'as' => 'dokter.'], function () {
        Route::resource('dokters', App\Http\Controllers\Dokters\DokterController::class);
    });
});
