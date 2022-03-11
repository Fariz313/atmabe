<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\TransaksiController;

Route::group(['middleware' => ['cors']], function () {
    //http://localhost:8000/api/register
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);
    Route::get('login/check', [UserController::class, 'loginCheck']);

    //ini outlet
    // Route::group(['middleware' => ['jwt.verify:admin']], function () {
    Route::post('outlet', [OutletController::class, 'insert']);
    Route::put('outlet/{id}', [OutletController::class, 'update']);
    Route::delete('outlet/{id}', [OutletController::class, 'delete']);
    Route::get('outlet', [OutletController::class, 'getAll']);
    Route::get('outlet/{id}', [OutletController::class, 'getById']);
    // });
    //Route::post('outlet', [OutletController::class, 'insert']);
    //Route::put('outlet/{id}', [OutletController::class, 'update']);
    //Route::delete('outlet/{id}', [OutletController::class, 'delete']);
    Route::post('user', [UserController::class, 'insert']);
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::delete('user/{id}', [UserController::class, 'delete']);
    Route::get('user', [UserController::class, 'getAll']);
    Route::get('user/{id}', [UserController::class, 'getById']);

    // REPORT
    Route::post('transaksi', [TransaksiController::class, 'insert']);
    Route::put('transaksi/{id}', [TransaksiController::class, 'update']);
    Route::delete('transaksi/{id}', [TransaksiController::class, 'delete']);
    Route::get('transaksi', [TransaksiController::class, 'getAll']);
    Route::get('transaksi/{id}', [TransaksiController::class, 'getById']);

    //ini member
    Route::post('member', [MemberController::class, 'insert']);
    Route::put('member/{id}', [MemberController::class, 'update']);
    Route::delete('member/{id}', [MemberController::class, 'delete']);
    Route::get('member', [MemberController::class, 'getAll']);
    Route::get('member/{id}', [MemberController::class, 'getById']);

    //ini paket
    Route::post('paket', [PaketController::class, 'insert']);
    Route::get('paket', [PaketController::class, 'getAll']);
    Route::get('paket/{id}', [PaketController::class, 'getById']);
    Route::put('paket/{id}', [PaketController::class, 'update']);
    Route::delete('paket/{id}', [PaketController::class, 'delete']);
});
