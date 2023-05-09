<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\TeamController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\ResponsibilityController;

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

// API Team
Route::prefix('team')->middleware('auth:sanctum')->name('team.')->group(function(){
    Route::post('',[TeamController::class, 'create'])->name('create');
    Route::get('', [TeamController::class, 'fetch'])->name('fetch');
    Route::post('update/{id}',[TeamController::class, 'update'])->name('update');  
    Route::delete('{id}', [TeamController::class, 'destroy'])->name('delete');
});

// API Role
Route::prefix('role')->middleware('auth:sanctum')->name('role.')->group(function(){
    Route::post('',[RoleController::class, 'create'])->name('create');
    Route::get('', [RoleController::class, 'fetch'])->name('fetch');
    Route::post('update/{id}',[RoleController::class, 'update'])->name('update');  
    Route::delete('{id}', [RoleController::class, 'destroy'])->name('delete');
});

// API Responsibility
Route::prefix('responsibility')->middleware('auth:sanctum')->name('responsibility.')->group(function(){
    Route::post('',[ResponsibilityController::class, 'create'])->name('create');
    Route::get('', [ResponsibilityController::class, 'fetch'])->name('fetch');
    Route::post('update/{id}',[ResponsibilityController::class, 'update'])->name('update');  
    Route::delete('{id}', [ResponsibilityController::class, 'destroy'])->name('delete');
});

// API Employee
Route::prefix('employee')->middleware('auth:sanctum')->name('employee.')->group(function(){
    Route::post('',[EmployeeController::class, 'create'])->name('create');
    Route::get('', [EmployeeController::class, 'fetch'])->name('fetch');
    Route::post('update/{id}',[EmployeeController::class, 'update'])->name('update');  
    Route::delete('{id}', [EmployeeController::class, 'destroy'])->name('delete');
});

// API Auth
Route::name('auth.')->group(function(){
    Route::post('register',[UserController::class, 'register'])->name('register');
    Route::post('login',[UserController::class, 'login'])->name('login');
    Route::middleware('auth:sanctum')->group(function(){
        Route::get('user',[UserController::class, 'fetch'] )->middleware('auth:sanctum')->name('fetch');
        Route::post('logout',[UserController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
    });
    
});

// Note:
// ----------------------- O R -----------------------------------
// Route::group([
//     'prefix' =>'company',
//     'middleware' =>'auth:sanctum',
// ], function (){
//     Route::post('',[CompanyController::class, 'create'])->name('create');
//     Route::get('', [CompanyController::class, 'all'])->name('fetch');
//     Route::put('',[CompanyController::class, 'update'])->name('update');
// });