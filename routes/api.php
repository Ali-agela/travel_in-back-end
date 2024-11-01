<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\ResevationsController;
use App\Http\Controllers\Uploader;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResortController;


Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'logIn']);
Route::post('dash-board/register', [adminController::class, 'register']);
Route::post('dash-board/login', [adminController::class, 'logIn']);
Route::get('admins', [adminController::class, 'index']);
Route::get('resorts', [ResortController::class, 'getTwoResortsFromEachAdmin']);

Route::get('resorts/{id}', [ResortController::class, 'getResorts']);
Route::get('reservations/{id}', [ResortController::class, 'reservations']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [UserController::class, 'logout']);
    //Route::post('resorts/reserve', [UserController::class, 'reserveResort']);
    Route::get('resort/reserve', [UserController::class, 'reservation']);
    Route::delete('reservations/{id}', [UserController::class, 'deleteReservation']);
    Route::put('reservations/{id}', [UserController::class, 'updateReservation']);
    Route::post('resorts/reserve', [ResevationsController::class, 'store']);
    Route::get('resort/favorite', [UserController::class, 'favorites']);
    Route::post('resort/favorite', [UserController::class, 'addToFavorites']);
    Route::delete('resort/favorite', [UserController::class, 'removeFromFavorites']);
    Route::post('upload/user', [Uploader::class, 'uploadForUser']);
    Route::get('user', [UserController::class, 'user']);
    Route::put('user', [UserController::class, 'updateUser']);
    Route::get('user/reservations/{id}', [ResevationsController::class, 'showUserReservation']);
});

Route::middleware('auth:admin')->group(function () {
    Route::post('dash-board/logout', [adminController::class, 'logout']);
    Route::get('dash-board/resorts', [ResortController::class, 'index']);
    Route::post('dash-board/resorts', [ResortController::class, 'store']);
    Route::get('dash-board/resorts/{id}', [ResortController::class, 'show']);
    Route::put('dash-board/resorts/{id}', [ResortController::class, 'update']);
    Route::delete('dash-board/resorts/{id}', [ResortController::class, 'destroy']);
    Route::put('dash-board/resorts/{id}', [ResortController::class, 'updateResort']);
    Route::get('dash-board/resorts/{id}', [adminController::class, 'showResortWithReservations']);
    Route::put('dash-board/reservations/{id}', [adminController::class, 'updateReservation']);
    Route::post('dash-board/upload/admin', [Uploader::class, 'uploadForAdmin']);
    Route::post('dash-board/upload/resort/{id}', [Uploader::class, 'uploadForResort']);
    Route::post('dash-board/resorts/spasifications', [ResortController::class, 'addSpasification']);

});
