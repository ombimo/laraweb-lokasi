<?php

use Illuminate\Support\Facades\Route;
use Ombimo\LarawebLokasi\Controllers\DusunController;
use Ombimo\LarawebLokasi\Controllers\KecamatanController;
use Ombimo\LarawebLokasi\Controllers\KelurahanController;
use Ombimo\LarawebLokasi\Controllers\KotaController;
use Ombimo\LarawebLokasi\Controllers\ProvinsiController;

Route::group([
    'prefix' => 'api/v1/lokasi',
], function () {

    Route::get('provinsi', [ProvinsiController::class, 'get']);
    Route::get('provinsi/{id}', [ProvinsiController::class, 'get']);

    Route::get('kota', [KotaController::class, 'get']);
    Route::get('kota/{id}', [KotaController::class, 'get']);

    Route::get('kecamatan', [KecamatanController::class, 'get']);
    Route::get('kecamatan/{id}', [KecamatanController::class, 'get']);

    Route::get('kelurahan', [KelurahanController::class, 'get']);
    Route::get('kelurahan/{id}', [KelurahanController::class, 'get']);

    Route::get('dusun', [DusunController::class, 'get']);
    Route::get('dusun/{id}', [DusunController::class, 'get']);
});
