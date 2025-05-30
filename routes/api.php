<?php

use App\Http\Controllers\Api\AuthLinkController;
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

Route::post('/auth/email/link', AuthLinkController::class);
// ->middleware("throttle:1,5");


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
