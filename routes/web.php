<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',[UsersController::class,'login'])->name('login');
Route::post('/login/validate',[UsersController::class,'validateLogin'])->name('validate');

Route::get('/create',[UsersController::class,'create'])->name('create');
Route::get('/register',[UsersController::class,'register'])->name('register');
Route::post('/user/store',[UsersController::class,'store'])->name('store');
Route::get('user/verify/{token}',[UsersController::class,'verifyEmail'])->name('verfiy');