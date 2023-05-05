<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CompanyController;

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

Route::get('user',[UserController::class, 'fetch'] )->middleware('auth:sanctum');

Route::post('login',[UserController::class, 'login']);
Route::post('logout',[UserController::class, 'logout'])->middleware('auth:sanctum');
Route::post('register',[UserController::class, 'register']);

Route::get('/company', [CompanyController::class, 'all']);