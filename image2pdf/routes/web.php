<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ConversionController;

Route::get('/home', function () {
    return view('home');
});

Route::post('upload-image', [ImageController::class, 'uploadImage'])->name('uploadImage');
Route::post('convert-to-pdf', [ConversionController::class, 'convertToPdf'])->name('convertToPdf');

Route::get('/home-{sizes?}', function ($sizes = null) {
    $defaultSizes = ['10.5x6.3', '7.2x6.3', '0.0x0.0', '0x0'];
    $sizeArray = [];
    if ($sizes) {
        $paramArray = explode('-', $sizes);

        if (!empty($paramArray)) {
            $validSizes = preg_grep('/^\d+(\.\d+)?x\d+(\.\d+)?$/', $paramArray);

            if (!empty($validSizes)) {
                foreach ($validSizes as $size) {
 
                    if (!in_array($size, $defaultSizes)) {
                        $sizeArray[] = $size;
                    }
                }
            }
        }
    }

    return view('home', ['sizes' => $sizeArray]);
})->where('sizes', '.*');







