<?php

use App\Http\Controllers\downloadRecordings;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('/viewRecords', [downloadRecordings::class, 'index'])->name('viewRecords');
Route::get('/questionRecordings', [DownloadRecordings::class, 'questionRecordings'])->name('questionRecordings');
Route::get('/escuchar/{archivo}', [DownloadRecordings::class, 'escucharGrabacion'])->name('escuchar');
Route::get('/descargar/{archivo}', [DownloadRecordings::class, 'descargarGrabacion'])->name('descargar');

