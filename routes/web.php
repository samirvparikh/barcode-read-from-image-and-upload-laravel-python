<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Artisan;

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


Route::get('/', function () {
    return view('upload');
});


Route::get('/barcode-upload', [FileUploadController::class, 'showForm'])->name('barcode.form');
Route::post('/barcode-read', [FileUploadController::class, 'readBarcode'])->name('barcode.read');

Route::get('/run-barcode-processing', function () {
    Artisan::call('barcode:process-images');
    return response()->json(['status' => 'success', 'message' => 'Barcode processing executed.']);
});
