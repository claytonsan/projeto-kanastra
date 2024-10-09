<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProcessaCsvController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/process-csv', [ProcessaCsvController::class, 'processCsv']);