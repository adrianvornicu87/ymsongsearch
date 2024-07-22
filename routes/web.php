<?php

use App\Http\Controllers\SongController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/song',[SongController::class,'create']);
Route::get('/scan',[SongController::class,'scan']);
Route::get('/search/{searchString}',[SongController::class,'search']);
Route::get('/play/{indexPath}',[SongController::class,'play']);