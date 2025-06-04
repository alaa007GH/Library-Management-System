<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowedBookController;


Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);
Route::post('logout',[UserController::class,'logout'])
  ->middleware('auth:sanctum');


Route::get('Category/get',[CategoryController::class,'get']);
Route::post('Category/create',[CategoryController::class,'create']);
Route::post('Category/update/{id}',[CategoryController::class,'update']);
Route::post('Category/delete/{id}',[CategoryController::class,'delete']);

Route::get('Book/get',[BookController::class,'get']);
Route::post('Book/create',[BookController::class,'create']);
Route::post('Book/update/{id}',[BookController::class,'update']);
Route::post('Book/delete/{id}',[BookController::class,'delete']);

Route::get('BorrowedBook/get',[BorrowedBookController::class,'get']);
Route::post('BorrowedBook/create',[BorrowedBookController::class,'create']);
Route::post('BorrowedBook/update/{id}',[BorrowedBookController::class,'update']);
Route::post('BorrowedBook/delete/{id}',[BorrowedBookController::class,'delete']);
