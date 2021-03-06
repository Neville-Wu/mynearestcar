<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserFavouriteController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CarparkController;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::apiResource('carparks', CarparkController::class, [
    'as' => 'api',
    'except' => 'show',
]);

Route::group(['prefix' => 'carparks'], function() {
    Route::post('nearest', [CarparkController::class, 'nearest'])
        ->name('api.carparks.nearest');
    Route::any('filter', [CarparkController::class, 'filter'])
        ->name('api.carparks.filter');
    Route::get('/{carpark}/vehicles', [CarparkController::class, 'vehicles'])
        ->name('api.carparks.vehicles');
});

Route::group(['prefix' => 'order'], function() { // @todo auth api
    Route::post('create', [OrderController::class, 'create'])
        ->name('api.order.create');
    Route::post('{order}/payment', [OrderController::class, 'payment'])
        ->name('api.order.payment');
});

Route::group(['prefix' => 'favourite'], function() { // @todo auth api
    Route::get('{user}', [UserFavouriteController::class, 'index'])
        ->name('api.favourite.vehicle.index');
    Route::post('{user}/vehicle/{vehicle}', [UserFavouriteController::class, 'store'])
        ->name('api.favourite.vehicle.store');
});
