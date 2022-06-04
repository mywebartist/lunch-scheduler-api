<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::resource('users', UserController::class );
//Route::post('register',[\App\Http\Controllers\AuthController::class,'register']);

// items
Route::resource('items', \App\Http\Controllers\ItemController::class)->except(['create', 'edit'])->middleware(['logged_in']);

// items selection
Route::resource('items-selection', \App\Http\Controllers\ItemSelectionController::class)->except(['create', 'edit']);

//media
Route::resource('medias', \App\Http\Controllers\MediaController::class)->only(['store', 'index']);

// organization
Route::resource('org', \App\Http\Controllers\OrganizationController::class)->except(['create', 'edit']);

// schedule
Route::resource('schedule', \App\Http\Controllers\ScheduleController::class)->except(['create', 'edit']);

// user
Route::resource('users', UserController::class )->except(['create', 'edit', 'store']);
Route::post('login',[\App\Http\Controllers\AuthController::class,'login']);
Route::get('profile',[\App\Http\Controllers\AuthController::class,'getProfile'])->middleware('logged_in');
Route::put('profile',[\App\Http\Controllers\AuthController::class,'updateProfile']);
Route::get('verify/token/{token}',[\App\Http\Controllers\AuthController::class,'verifyToken'])->name('verify_token');






// User
//Route::get('users', [UserController::class, 'index']);
//Route::get('users/{id}', [UserController::class, 'show']);
//Route::post('users', [UserController::class, 'store']);
//Route::put('users/{id}', [UserController::class, 'update']);
//Route::delete('users/{id}', [UserController::class, 'destroy']);


