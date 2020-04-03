<?php
Route::group([
    'namespace' => 'Ombimo\LarawebLokasi\Controllers',
    'middleware' => 'web',
    'prefix' => 'api/lokasi'
], function() {
    //artikel index
    Route::get('provinsi', 'LokasiController@provinsi')->name('lokasi.provinsi');
    Route::get('kota', 'LokasiController@kota')->name('lokasi.kota');
    Route::get('kecamatan', 'LokasiController@kecamatan')->name('lokasi.kecamatan');
    Route::get('kelurahan', 'LokasiController@kelurahan')->name('lokasi.kelurahan');
});
