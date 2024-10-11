<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProcessaCsvController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/process-csv', [ProcessaCsvController::class, 'processCsv']);
Route::post('/process-csv', [ProcessaCsvController::class, 'processCsv']);
Route::get('/process-csv-local', [ProcessaCsvController::class, 'processCsvLocal']);
Route::post('/process-csv-local', [ProcessaCsvController::class, 'processCsvLocal']);