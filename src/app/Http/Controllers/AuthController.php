<?php

namespace App\Http\Controllers;

use App\Mail\LoginSecurityCodeMail;
use App\Models\LoginPin;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\User;
use App\Notifications\LoginSecurityCodeNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
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
                'errors' => $validator->messages()
            ];
        }

        $new_pin_saved = false;
        while ($new_pin_saved === false) {

            $new_pin_saved = true;
        }

        $pin = generateRandomPin();
        $login_pin = new LoginPin();

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
            'status_code' => 1,
            'message' => 'login pin sent to your email'
        ];

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
                'errors' => $validator->messages()
            ];
        }

        $fromYesterday = date('Y-m-d', strtotime("-1 day"));

        $pin = strtoupper($_request->input('pin'));
        $login_pin = LoginPin::whereDate('created_at', '>=', $fromYesterday)->wherePin($pin)->first();

        if (!$login_pin) {
            return ['status_code' => 0,
                'message' => 'login pin expired you too slow lmao. login with pin within 1 day'
            ];
        }

        // get user
        $email = Crypt::decrypt($login_pin->token);
        $user = User::whereEmail($email)->first();
        if (!$user) {
            return ['status_code' => 0,
                'message' => 'login expired'
            ];
        }

        // get users organizations
        $user_organizations = OrganizationUser::all()->where('user_id', $user->id);
        $user_organizations_ids = [];

        // create user org array
        foreach ($user_organizations as $user_organization) {
            $user_organizations_ids[] = $user_organization->organization_id;
        }

//        if ($login_pin) {
        $token = $login_pin->token;
        // $login_pin->delete();
        return [
            'x-apikey' => $token,
            'status_code' => 1,
            'message' => 'access token',
            'user' => $user
//                'organization_ids' => $user_organizations_ids
        ];
//        }

//        return [
//            'status_code' => 0,
//            'message' => 'this pin is expired or used lol'
//        ];
    }

    public function verifyToken($_token): array
    {
        $email = Crypt::decrypt($_token);

        if ($email) {
            $user = User::where('email', $email)->first();

            if (!$user) {
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
                'message' => $user->status == 1 ? 'user activate' : 'user not activate',
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
        $user = User::whereEmail($email)->first();
        if (!$user) {
            return ['status_code' => 0,
                'message' => 'login expired'
            ];
        }

//        $user_orgs = OrganizationUser::all()->where('user_id', $user->id);
//        $user_org_ids = [];
//        foreach ($user_orgs as $user_org) {
//            $user_org_ids[] = $user_org->organization_id;
//        }
        $user_orgs = $this->getUserOrgsByUserId($user->id);
//        if ($user_orgs->count() == 0) {
//
//            return ['status_code' => 0,
//                'message' => 'you are lonely, no orgs yet :) '
//            ];
//        }


//        $user_orgs = Organization::findMany($user_org_ids);


//        return array_merge(
//            ['status_code' => 1,
//                'message' => 'users from this org'
//            ],
//            $user_orgs->toArray(),
//        );

        $user = array_merge(
            ['status_code' => 1,
                'message' => 'logged in user'
            ],
            $user->toArray(),
            [
                'orgs' =>
                    $user_orgs->toArray()
            ]
        );
        return $user;
    }

    public function getUserOrgsByUserId(int $_user_id)
    {
        $user_orgs = OrganizationUser::all()->where('user_id', $_user_id);
        $user_org_ids = [];
        foreach ($user_orgs as $user_org) {
            $user_org_ids[] = $user_org->organization_id;
        }
        $user_orgs = Organization::findMany($user_org_ids);
        return $user_orgs;
    }

    public function updateProfile(Request $_request)
    {
        // get user
        $email = Crypt::decrypt($_request->header('x-apikey'));
        $user = User::whereEmail($email)->first();
        if (!$user) {
            return ['status_code' => 0,
                'message' => 'login expired'
            ];
        }


        if ($_request->input('name')) {
            $user->name = $_request->input('name');
        }

        if ($_request->input('default_org')) {
            // validate user organization
            $no_access = User::validateUserOrganization($user->id, $_request->input('default_org'));
            if ($no_access) {
                return $no_access;
            }
            $user->default_org = $_request->input('default_org');
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
