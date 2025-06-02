<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PurchaseController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('test/get',[TestController::class,'get']);
Route::get('product/get',[ProductController::class,'get']);
Route::post('product/create',[ProductController::class,'create']);
Route::post('test/create',[TestController::class,'create']);
Route::post('product/update/{id}',[ProductController::class,'update']);
Route::post('product/delete/{id}',[ProductController::class,'delete']);
Route::put('test/update/{id}',[TestController::class,'update']);
Route::delete('test/delete/{id}',[TestController::class,'delete']);
Route::get('book/get',[BookController::class,'get']);
Route::post('book/create',[BookController::class,'create']);
Route::post('book/update/{id}',[BookController::class,'update']);
Route::post('book/delete/{id}',[BookController::class,'delete']);


Route::post('purchase/create',[PurchaseController::class,'create']);
