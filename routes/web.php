<?php

use App\Http\Controllers\BarangController;
use Illuminate\Support\Facades\Route;

Route::controller(BarangController::class)->name('barang.')->group(function () {
    // list barang
    Route::get('/barang', 'index')->name('index');
    // form create
    Route::get('/barang/create', 'create')->name('create');
    // menerima request untuk create barang (store barang ke database)
    Route::post('/barang', 'store')->name('store');
    // show detail barang
    Route::get('/barang/{id}', 'show')->name('show');
    // destroy/delete barang
    Route::get('/barang/{id}/destroy', 'destroy')->name('destroy');
    // form edit barang
    Route::get('/barang/{id}/edit', 'edit')->name('edit');
    // menerima request untuk update barang
    Route::post('/barang/{id}', 'update')->name('update');
});

/*Route::get('/', function() {
    return 'Hello World';
});

Route::get('/{nama}', function(string $nama) {
    // return "Hello {$nama}";
    return view('hello', ['nama' => $nama]);
});*/

/*Route::get('/', function () {
    return view('welcome');
});*/
