<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SasaranKinerjaController;
use App\Http\Controllers\ViewController;
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
    return view('welcome');
});

Route::get('cetak_skp/{id_skp}', [SasaranKinerjaController::class, 'print']);
Route::get('cetak_skp_jpt/{id_skp}', [SasaranKinerjaController::class, 'print_jpt']);

//Route::get('buku-panduan', [ViewController::class, 'bukuPanduan']);
Route::get('buku-panduan', function() {
    return response()->file(public_path('files/E-book Penyusunan SKP.pdf'));
})->name('show-pdf');