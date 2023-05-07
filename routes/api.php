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

// API Company

Route::prefix('company')->middleware('auth:sanctum')->name('company.')->group(function(){
    Route::post('',[CompanyController::class, 'create'])->name('create');
    Route::get('', [CompanyController::class, 'fetch'])->name('fetch');
    // Route::put('{id}',[CompanyController::class, 'update'])->name('update');    // Karena ngk bsia update gambar dan teks bersamaan
    Route::post('update/{id}',[CompanyController::class, 'update'])->name('update');  

});


// ----------------------- O R -----------------------------------
// Route::group([
//     'prefix' =>'company',
//     'middleware' =>'auth:sanctum',
// ], function (){
//     Route::post('',[CompanyController::class, 'create'])->name('create');
//     Route::get('', [CompanyController::class, 'all'])->name('fetch');
//     Route::put('',[CompanyController::class, 'update'])->name('update');
// });

// API Auth
Route::name('auth.')->group(function(){
    Route::post('register',[UserController::class, 'register'])->name('register');
    Route::post('login',[UserController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function(){
        Route::get('user',[UserController::class, 'fetch'] )->middleware('auth:sanctum')->name('fetch');
        Route::post('logout',[UserController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
    });
    
});

