<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
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

        $validator = Validator::make($request->all(), [
            'email' => 'required|max:255|unique:users,email',
        ]);

        if ($validator->fails()) {
            return [
                'status_code' => 0,
                'message' => $validator->messages()->first(),
                'errors' => $validator->messages()
            ];
        }
        return true;

    }

    public function show($_id)
    {

        return User::find($_id);
    }

    public function update(UpdateUserRequest $_request, User $_user)
    {
        //
    }

    public function destroy(User $_user)
    {
        $user = User::destroy($_user->id);
        if ($user) {
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
