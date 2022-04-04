<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AmbassadorController;
use App\Http\Controllers\Api\LinkController;
use App\Http\Controllers\Api\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

function common_routes($scope){
    Route::post('/register', [AuthController::class,'register'])->name('api.register');
    Route::post('/login', [AuthController::class,'login'])->name('api.login');

    Route::middleware(['auth:sanctum',$scope])->group(function () {
        Route::get('/user', [AuthController::class,'user'])->name('api.user');
        Route::post('/logout', [AuthController::class,'logout'])->name('api.logout');
        Route::post('/users/update-info', [AuthController::class,'updateInfo'])->name('api.update_info');
        Route::post('/users/update-password', [AuthController::class,'updatePassword'])->name('api.update_password');
    });

}

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('App\Http\Controllers\Api')->prefix('admin')->group(function(){

    common_routes('scope.admin');

    Route::middleware(['auth:sanctum','scope.admin'])->group(function () {
        Route::get('/ambassadors', [AmbassadorController::class,'index'])->name('api.ambassadors');
        Route::get('/users/{id}/links', [LinkController::class,'index'])->name('api.users.links');
        Route::get('/orders', [OrderController::class,'index'])->name('api.orders');

        Route::apiResource('/products', ProductController::class);

    });

});


Route::namespace('App\Http\Controllers\Api')->prefix('admin')->group(function(){

    common_routes('scope.ambassador');

});
// Route::get('/login', [App\Http\Controllers\Api\AuthController::class,'register'])->name('login');

