<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class OrganizationUserController extends Controller
{

    private array $user_allowed_roles = ['staff', 'chef', 'org_admin'];

    public function get_user_orgs(Request $_request)
    {

        // get user
        $email = Crypt::decrypt($_request->header('x-apikey'));
        $user = User::whereEmail($email)->first();
        if (!$user) {
            return ['status_code' => 0,
                'message' => 'login expired'
            ];
        }

        $user_orgs = OrganizationUser::all()->where('user_id', $user->id);
        $user_org_ids = [];
        foreach ($user_orgs as $user_org) {
            $user_org_ids[] = $user_org->organization_id;
        }
        if (!$user_org_ids) {

            return ['status_code' => 0,
                'message' => 'you are lonely, no orgs yet :) '
            ];
        }


        $user_orgs = Organization::findMany($user_org_ids);
        return array_merge(
            ['status_code' => 1,
                'message' => 'users from this org'
            ],
            $user_orgs->toArray(),
        );

    }

    public function user_join_org(Request $_request)
    {
        $validator = Validator::make($_request->all(), [
            'organization_id' => 'required|exists:organizations,id',
//            'email' => 'required|exists:users,email',
        ]);

        if ($validator->fails()) {
            return [
                'status_code' => 0,
                'message' => $validator->messages()->first(),
                'errors' => $validator->messages()
            ];
        }

        // get user
        $email = Crypt::decrypt($_request->header('x-apikey'));
        $user = User::whereEmail($email)->first();
        if (!$user) {
            return ['status_code' => 0,
                'message' => 'login expired'
            ];
        }

        // check if user already in that org
        $org_user = OrganizationUser::whereOrganization_id($_request->input('organization_id'))->whereUser_id($user->id)->first();
//        dd($org_user);
        if (!$org_user) {
            $org_user = new OrganizationUser();
            $org_user->organization_id = $_request->input('organization_id');
            $org_user->user_id = $user->id;
            $org_user->roles = json_encode(["staff"]);
            $org_user->save();
            $org_user = array_merge(
                ['status_code' => 1,
                    'message' => 'user added to org: ' . $_request->input('organization_id')
                ],
                $org_user->toArray());
            return $org_user;
        }

        if ($org_user->status == 0) {
            return ['status_code' => 0,
                'message' => 'org id: '.$_request->input('organization_id') . ', your request is already pending they lazy lmao.'
            ];
        }

        return ['status_code' => 0,
            'message' => 'org id: '.$_request->input('organization_id') .  'lmao you are already in this'
        ];


    }

    public function add_org_user(Request $_request)
    {
        $validator = Validator::make($_request->all(), [
            'organization_id' => 'required|exists:organizations,id',
            'email' => 'required|exists:users,email',
        ]);

        if ($validator->fails()) {
            return [
                'status_code' => 0,
                'message' => $validator->messages()->first(),
                'errors' => $validator->messages()
            ];
        }

        // get user
        $email = Crypt::decrypt($_request->header('x-apikey'));
        $user = User::whereEmail($email)->first();
        if (!$user) {
            return ['status_code' => 0,
                'message' => 'login expired'
            ];
        }

        // validate chef/org_admin permission in organization
        $no_access = User::validateUserOrganizationRole((int)$user->id, ((int)$_request->input('organization_id')), ['chef', 'org_admin']);
        if ($no_access) {
            return $no_access;
        }


        // find user with email
        $user_target = User::whereEmail($_request->input('email'))->first();
//        if (!$user_target) {
//            return ['status_code' => 0,
//                'message' => 'there is no user with this email: ' .$_request->input('email'). 'what are you doing, in system lmao'
//            ];
//        }
        $org_user_exist =  OrganizationUser::whereUser_id($user_target->id)->whereOrganization_id($_request->input('organization_id'))->first();

        if($org_user_exist){
            return [
                'status_code' => 0,
                'message' => 'user id: '.$user_target->id.', email '. $_request->input('email') .' already in org: ' . $_request->input('organization_id')
            ];
        }

        $org_user = new OrganizationUser();
        $org_user->organization_id = $_request->input('organization_id');
        $org_user->user_id = $user_target->id;
        $org_user->roles = json_encode(["staff"]);
        $org_user->save();

        $org_user = array_merge(
            [
                'status_code' => 1,
                'message' => 'user id: '.$user_target->id. ', email '. $_request->input('email') . ' added to org: ' . $_request->input('organization_id')
            ],
            $org_user->toArray());
        return $org_user;
    }

    //
    public function get_org_users(Request $_request)
    {
        $validator = Validator::make($_request->all(), [
            'organization_id' => 'required|exists:organizations,id',
        ]);

        if ($validator->fails()) {
            return [
                'status_code' => 0,
                'message' => $validator->messages()->first(),
                'errors' => $validator->messages()
            ];
        }

        // get user
        $email = Crypt::decrypt($_request->header('x-apikey'));
        $user = User::whereEmail($email)->first();
        if (!$user) {
            return ['status_code' => 0,
                'message' => 'login expired'
            ];
        }

        // validate chef/org_admin permission in organization
        $no_access = User::validateUserOrganizationRole((int)$user->id, ((int)$_request->input('organization_id')), ['chef', 'org_admin']);
        if ($no_access) {
            return $no_access;
        }

        if ($_request->input('status')) {
            $org_users = OrganizationUser::all()->where('organization_id', $_request->input('organization_id'))->where('status', $_request->input('status'));
            $org_users_ids = [];
            foreach ($org_users as $org_user) {
                $org_users_ids[] = $org_user->user_id;
            }
        } else {
            $org_users = OrganizationUser::all()->where('organization_id', $_request->input('organization_id'));
            $org_users_ids = [];
            foreach ($org_users as $org_user) {
                $org_users_ids[] = $org_user->user_id;
            }
        }

        $users = User::findMany($org_users_ids);

        $users = array_merge(
            ['status_code' => 1,
                'message' => 'users from this org'
            ],
            $users->toArray());
        return $users;
    }


    public function admit_org_user(Request $_request)
    {
        $validator = Validator::make($_request->all(), [
            'user_id' => 'required|exists:users,id',
            'organization_id' => 'required|exists:organizations,id',
//            'role' => 'required',
//            'add_or_remove' => 'required|in:add,remove',
        ]);
        if ($validator->fails()) {
            return [
                'status_code' => 0,
                'message' => $validator->messages()->first(),
                'errors' => $validator->messages()
            ];
        }

        // get user
        $email = Crypt::decrypt($_request->header('x-apikey'));
        $user = User::whereEmail($email)->first();
        if (!$user) {
            return ['status_code' => 0,
                'message' => 'login expired'
            ];
        }

        // validate org_admin permission in organization
        $no_access = User::validateUserOrganizationRole((int)$user->id, ($_request->input('organization_id')), ['org_admin']);
        if ($no_access) {
            return $no_access;
        }

        // select user to admin
        $user_target = OrganizationUser::whereUser_id($_request->input('user_id'))->whereOrganization_id($_request->input('organization_id'))->whereStatus(0)->first();
//        dd($user_target);
        if (!$user_target) {
            return ['status_code' => 0,
                'message' => 'this user has no pending request to join lmao user id: ' . $_request->input('user_id')
            ];
        }

        $user_target->status = 1;
        $user_target->save();
        return [
            'status_code' => 1,
            'message' => 'this user id: ' . $_request->input('user_id') . ' has been admited to org id: ' . $_request->input('organization_id')
        ];


    }

    public function org_user_add_remove_role(Request $_request)
    {

        $validator = Validator::make($_request->all(), [
            'email' => 'required|exists:users,email',
            'organization_id' => 'required|exists:organizations,id',
            'role' => 'required',
            'add_or_remove' => 'required|in:add,remove',
        ]);

        if ($validator->fails()) {
            return [
                'status_code' => 0,
                'message' => $validator->messages()->first(),
                'errors' => $validator->messages()
            ];
        }

        // check if new role is valid
        if (!$this->isUserRoleValid($_request->input('role'))) {
            return ['status_code' => 0,
                'message' => 'this role does exist in user role list: ' . implode(', ', $this->user_allowed_roles)
            ];
        }

        // get user
        $email = Crypt::decrypt($_request->header('x-apikey'));
        $user = User::whereEmail($email)->first();
        if (!$user) {
            return ['status_code' => 0,
                'message' => 'login expired'
            ];
        }

        // validate org_admin permission in organization
        $no_access = User::validateUserOrganizationRole((int)$user->id, ($_request->input('organization_id')), ['org_admin']);
        if ($no_access) {
            return $no_access;
        }

        // get target user to change
        $user_target = User::whereEmail($_request->email)->first();

        // validate target user organization
        $no_access = User::validateUserOrganization((int)$user_target->id, $_request->input('organization_id'));
        if ($no_access) {
            return $no_access;
        }

        // target org_user
        $org_user = OrganizationUser::whereUser_id((int)$user_target->id)->whereOrganization_id($_request->input('organization_id'))->first();
        $org_user_roles = json_decode($org_user->roles);


        // org_admin cant remove org_admin role of others
        if ($_request->input('role') == 'org_admin' && $_request->input('add_or_remove') == 'remove') {
            if ($user->email !== $user_target->email) {
                return ['status_code' => 0,
                    'message' => 'you cant remove org_admin from other account, they can leave themself if they like'
                ];
            }

        }

        // add role
        if ($_request->input('add_or_remove') == 'add') {

            // add new role
            array_push($org_user_roles, $_request->input('role'));
            // remove duplicate roles
            $org_user_roles = array_unique($org_user_roles);

            $org_user->roles = json_encode($org_user_roles);
            $org_user->save();
            return [
                'status_code' => 1,
                'message' => 'added ' . $_request->input('role') . ' role for this user lol'
            ];
        }

        // remove role
        if ($_request->input('add_or_remove') == 'remove') {
            // add new role
            $org_user_roles = array_diff($org_user_roles, [$_request->input('role')]);

            // remove duplicate roles
            $org_user->roles = json_encode($org_user_roles);
            $org_user->save();
            return [
                'status_code' => 1,
                'message' => 'removed ' . $_request->input('role') . ' role for this user lol'
            ];
        }

        return [
            'status_code' => 0,
            'message' => 'something fishy'
        ];
    }

    public function isUserRoleValid(string $_role)
    {
        foreach ($this->user_allowed_roles as $role) {
            if ($role == $_role) {
                return true;
            }
        }
        return false;
    }

}
