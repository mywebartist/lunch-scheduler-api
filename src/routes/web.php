<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {

    return 'Lunch server is running! See API docs.';
});


//Route::get('/web/users', function () {

//    request()->validate([
//        'email' => 'required'
//    ]);

//    return 1;
//});


