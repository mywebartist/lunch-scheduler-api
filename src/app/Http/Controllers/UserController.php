<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        return User::simplePaginate(5);
    }

    public function store(Request $request)
    {
//      $validated =  $request->validate([
//            'email' => 'required'
//        ]);
//        $user = new User();
//        $user->profile_media_id = $request->input(3);
//        $user->name = 'k';
//        $user->email = 'abc@hot.com';
//        $user->role = 'staff';
//        $user->status = 0;
//        return request()->all();

        $validator = Validator::make($request->all(),[
            'email' => 'required|max:255|unique:users,email',
//            'name' => 'required|max:255',
//            'role' => 'required|in:staff'
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
        return true;

    }

    public function show($_id)
    {

        return User::find($_id);
    }

//    public function show(User $user)
//    {
//
//        return $user;
////        return $id;
//    }

    public function update(UpdateUserRequest $_request, User $_user)
    {
        //
    }

    public function destroy(User $_user)
    {
        $user =  User::destroy($_user->id);
        if($user){
            return [
                'status_code' => 1,
                'message' => 'deleted'
            ];
        }

        return [
            'status_code' => 0,
            'message' => 'not found or not deleted'
        ];
    }
}
