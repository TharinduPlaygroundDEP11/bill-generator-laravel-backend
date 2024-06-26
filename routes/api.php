<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MeterReadingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group(['middleware' => 'cors'], function () {

//     Route::post('/save', [MeterReadingController::class, 'save']);
//     Route::get('/get/{number}', [CustomerController::class, 'get']);

// });

Route::middleware(['cors'])->group(function () {
    Route::controller(CustomerController::class)->group(function() {
        Route::get('/get/{number}', 'get');
    });
    Route::controller(MeterReadingController::class)->group(function() {
        Route::get('/save', 'save');
    });
});
