<?php

namespace App\Http\Controllers;

use App\Mail\LoginSecurityCodeMail;
use App\Models\User;
use App\Notifications\LoginSecurityCodeNotification;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use Notifiable;

    public function login(Request $_request)
    {

        // validate email is provide
        $validator = Validator::make($_request->all(), [
            'email' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return [
                'status_code' => 0,
                'message' => $validator->messages()->first(),
            ];
        }

        // check if user exist with email
        $user_count = User::where('email', $_request->input('email'))->count();

        // create login code
        $login_code = Crypt::encrypt($_request->input('email'));
        // user is registered
        if ($user_count > 0) {
            $user = User::where('email', $_request->input('email'))->first();

            // send login email
            try {
                Notification::send($user, new LoginSecurityCodeNotification($login_code));
            } catch (\Exception $e) {
                $response = [
                    'status_code' => '0',
                    'message' => ' notification system is down'
                ];
                return response()->json($response, 413);
            }

            return [
                'user' => $user,
                'status_code' => 1,
                'message' => 'login link sent to your email'
            ];
        }

        // register user
        $user = new User();
        $user->email = $_request->input('email');
        $user->save();

        // send verify email
        Notification::send($user, new VerifyEmailNotification($login_code));

        return [
            'user' => $user,
            'status_code' => 1,
            'message' => 'verify link sent to your email'
        ];
    }

    public function verifyToken($_token): array
    {
        $email = Crypt::decrypt($_token);

        if ($email) {
            $user = User::where('email', $email)->first();

            // activate user
            if ($user->status == 0) {
                $user->status = 1;
                $user->save();
            }

            return [
                'user' => $user,
                'status_code' => 1,
                'message' => 'login valid'
            ];
        }

        return [
            'status_code' => 0,
            'message' => 'invalid login'
        ];

    }

    public function getProfile()
    {


    }

    public function updateProfile()
    {

    }
}
