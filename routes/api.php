<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowedBookController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserBookController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NotificationController;



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

Route::get( 'Purchase/get',[PurchaseController::class,'get']);
Route::post('Purchase/create',[PurchaseController::class,'create']);
Route::post('Purchase/update/{id}',[PurchaseController::class,'update']);
Route::post('Purchase/delete/{id}',[PurchaseController::class,'delete']);

Route::get( 'UserBook/get',[UserBookController::class,'get']);
Route::post('UserBook/create',[UserBookController::class,'create']);
Route::post('UserBook/update/{id}',[UserBookController::class,'update']);
Route::post('UserBook/delete/{id}',[UserBookController::class,'delete']);

Route::get( 'Point/get',[PointController::class,'get']);
Route::post('Point/create',[PointController::class,'create']);
Route::post('Point/update/{id}',[PointController::class,'update']);
Route::post('Point/delete/{id}',[PointController::class,'delete']);

Route::get( 'Review/get',[ReviewController::class,'get']);
Route::post('Review/create',[ReviewController::class,'create']);
Route::post('Review/update/{id}',[ReviewController::class,'update']);
Route::post('Review/delete/{id}',[ReviewController::class,'delete']);

    Route::get('notification/get', [NotificationController::class, 'getNotifications']);
    Route::post('notification/get', [NotificationController::class, 'deleteNotification']);

