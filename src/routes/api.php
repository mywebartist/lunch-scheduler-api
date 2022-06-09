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


// login
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('login/pin', [\App\Http\Controllers\AuthController::class, 'login_pin']);
Route::get('verify/token/{token}', [\App\Http\Controllers\AuthController::class, 'verifyToken'])->name('verify_token');

// user
Route::get('profile', [\App\Http\Controllers\AuthController::class, 'getProfile'])->middleware('logged_in');
Route::put('profile', [\App\Http\Controllers\AuthController::class, 'updateProfile'])->middleware(['logged_in']);
//Route::resource('users', UserController::class )->except(['create', 'edit', 'store'])->middleware(['logged_in']);

// items
Route::resource('items', \App\Http\Controllers\ItemController::class)->except(['create', 'edit'])->middleware(['logged_in']);

// organization
Route::get('org/users', [\App\Http\Controllers\OrganizationUserController::class, 'get_org_users'])->middleware('logged_in');
Route::post('org/users', [\App\Http\Controllers\OrganizationUserController::class, 'add_org_user'])->middleware('logged_in');
Route::put('org/user/roles', [\App\Http\Controllers\OrganizationUserController::class, 'org_user_add_remove_role'])->middleware('logged_in');
Route::resource('org', \App\Http\Controllers\OrganizationController::class)->except(['index', 'create', 'edit', 'destroy'])->middleware(['logged_in']);


// items selection
Route::resource('items-selection', \App\Http\Controllers\ItemSelectionController::class)->except(['create', 'edit', 'update' ])->middleware(['logged_in']);

//media
Route::resource('medias', \App\Http\Controllers\MediaController::class)->only(['store', 'index'])->middleware(['logged_in']);

// schedule
Route::resource('schedule', \App\Http\Controllers\ScheduleController::class)->except(['create', 'edit'])->middleware(['logged_in']);






// User
//Route::get('users', [UserController::class, 'index']);
//Route::get('users/{id}', [UserController::class, 'show']);
//Route::post('users', [UserController::class, 'store']);
//Route::put('users/{id}', [UserController::class, 'update']);
//Route::delete('users/{id}', [UserController::class, 'destroy']);


