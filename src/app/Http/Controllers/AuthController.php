<?php

namespace App\Http\Controllers;

use App\Mail\LoginSecurityCodeMail;
use App\Models\LoginPin;
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

        $new_pin_saved = false;
        while ($new_pin_saved === false) {

            $new_pin_saved = true;
        }

        $pin = generateRandomPin();

        $login_pin = new LoginPin();

        // check if user exist with email
//        $user_count = User::whereEmail( $_request->input('email'))->count();

        // create login code
        $token = Crypt::encrypt($_request->input('email'));

        $user = User::whereEmail($_request->input('email'))->first();

        // register user
        if (!$user) {
            // register user
            $user = new User();
            $user->email = $_request->input('email');
            $user->save();
        }

        //create login pin
        $login_pin->user_id = $user->id;
        $login_pin->token = $token;
        $login_pin->pin = $pin;
        $login_pin->save();

        // send login email
        try {
            Notification::send($user, new LoginSecurityCodeNotification($pin));
        } catch (\Exception $e) {
            $response = [
                'status_code' => 0,
                'message' => ' notification system is down'
            ];
            return response()->json($response, 413);
        }
        return [
            'user' => $user,
            'status_code' => 1,
            'message' => 'login pin sent to your email'
        ];

        // save login pin
//        $login_pin->user_id = $user->get('id');
//        $login_pin->token = ' tok';
//        $login_pin->pin = 'sdf pin';

        // send verify email
//        Notification::send($user, new VerifyEmailNotification($login_code));

//        return [
//            'user' => $user,
//            'status_code' => 1,
//            'message' => 'verify link sent to your email'
//        ];
    }

    public function login_pin(Request $_request)
    {
        // validate email is provide
        $validator = Validator::make($_request->all(), [
            'pin' => 'required|max:4',
        ]);

        if ($validator->fails()) {
            return [
                'status_code' => 0,
                'message' => $validator->messages()->first(),
            ];
        }

        //       $s =  \DateTime::now();
        //        dd();
        //        $login_pin = LoginPin::wherePin($_request->input('pin'))->first();
        $fromYesterday = date('Y-m-d', strtotime("-1 day"));

        $pin = strtoupper($_request->input('pin'));
        $login_pin = LoginPin::whereDate('created_at', '>=', $fromYesterday)->wherePin($pin)->first();
        // dd($login_pin);
        if ($login_pin) {
            $token = $login_pin->token;
            $login_pin->delete();
            return [
                'x-apikey' => $token,
                'status_code' => 1,
                'message' => 'access token'
            ];
        }

        return [
            'status_code' => 0,
            'message' => 'this pin is expired or used lol'
        ];
    }

    public function verifyToken($_token): array
    {
        $email = Crypt::decrypt($_token);

        if ($email) {
            $user = User::where('email', $email)->first();

            if(!$user){
                return [
                    'status_code' => 0,
                    'message' => 'invalid user'
                ];
            }

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

    public function getProfile(Request $_request)
    {
        // get user
        $email = Crypt::decrypt($_request->header('x-apikey'));
        $user = User::whereEmail($email)->firstOrFail();
        $user = array_merge(
            ['status_code' => 1,
                'message' => 'logged in user'
            ],
            $user->toArray()
            );
        return $user;
    }

    public function updateProfile(Request $_request)
    {
        // get user
        $email = Crypt::decrypt($_request->header('x-apikey'));
        $user = User::whereEmail($email)->firstOrFail();

        if ($_request->input('name')) {
            $user->name = $_request->input('name');
        }

        $user->save();

        $user = array_merge(
            ['status_code' => 1,
                'message' => 'logged in user'
            ],
            $user->toArray());
        return $user;
    }
}
