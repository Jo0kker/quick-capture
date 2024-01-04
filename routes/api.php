<?php

use App\Http\Controllers\LinkedinController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

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


Route::group(['middleware' => 'auth:sanctum'], static function () {
    Route::post('/openapi/linkedin', [LinkedinController::class, 'getDataFromOpenApi']);

    // route to store capture_linkedin
    Route::post('/captureLinkedin', [LinkedinController::class, 'store']);
});
