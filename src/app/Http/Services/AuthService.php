<?php


namespace App\Http\Services;


use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class AuthService{

    public function getUser(){
        $key = request()->header('key');
        $key = Crypt::decrypt($key);
        return User::whereEmail($key)->firstOrFail();
}




}







