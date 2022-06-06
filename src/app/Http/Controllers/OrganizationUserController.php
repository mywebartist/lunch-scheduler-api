<?php

namespace App\Http\Controllers;

use App\Models\OrganizationUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class OrganizationUserController extends Controller
{

    private $user_allowed_roles = ['staff', 'chef', 'org_admin'];

    //
    public function get_org_users(Request $_request)
    {

        $validator = Validator::make($_request->all(), [
//            'name' => 'required|max:255',
            'organization_id' => 'required|exists:organizations,id',
//            'thumbnail_media_id' => 'exists:medias,id'
        ]);

        if ($validator->fails()) {
            return $validator->messages();
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

        $org_users = OrganizationUser::all()->where('organization_id', $_request->input('organization_id'));
//        dd($org_users);
        $org_users_ids = [];
        foreach ($org_users as $org_user) {
            $org_users_ids[] = $org_user->user_id;
        }


//        dd($org_users_ids);
        $users = User::findMany($org_users_ids);

//        dd( $_request->input('organization_id'));
//        dd($users);
//        return User::whereId( $_request->input('organization_id'))->firstOrFail();

//            return $users;
        $users = array_merge(
            ['status_code' => 1,
                'message' => 'users from this org'
            ],
            $users->toArray());
        return $users;
    }


    public function org_user_add_remove_role(Request $_request)
    {

        $validator = Validator::make($_request->all(), [
            'user_id' => 'required|exists:users,id',
            'organization_id' => 'required|exists:organizations,id',
            'role' => 'required',
            'add_or_remove' => 'required|in:add,remove',
//            'thumbnail_media_id' => 'exists:medias,id'
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        // check if new role is valid
        if (!$this->isUserRoleValid($_request->input('role'))) {
            return ['status_code' => 0,
                'message' => 'this role does exist in user role list: ' . implode(', ', $this->user_allowed_roles)
            ];
        }

        $email = Crypt::decrypt($_request->header('x-apikey'));
        $user = User::whereEmail($email)->first();
        if (!$user) {
            return ['status_code' => 0,
                'message' => 'login expired'
            ];
        }

        // cant edit yourself
        if ($user->id == $_request->input('user_id')) {
            return ['status_code' => 0,
                'message' => 'you cant edit yourself booi'
            ];
        }

        // validate user organization
        $no_access = User::validateUserOrganization($_request->input('user_id'), $_request->input('organization_id'));
        if ($no_access) {
            return $no_access;
        }

        $org_user = OrganizationUser::whereUser_id($_request->input('user_id'))->whereOrganization_id($_request->input('organization_id'))->first();
        $org_user_roles = json_decode($org_user->roles);

        // validate org_admin permission in organization
        $no_access = User::validateUserOrganizationRole((int)$user->id, ($_request->input('organization_id')), ['org_admin']);
        if ($no_access) {
            return $no_access;
        }

//        dd(   $org_user_roles     );
//

//        dd( $org_user_roles );
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
//            $org_user_roles = array_unique($org_user_roles);

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

    public function org_user_remove_role(Request $_request)
    {

        $validator = Validator::make($_request->all(), [
            'user_id' => 'required|exists:users,id',
            'organization_id' => 'required|exists:organizations,id',
//            'thumbnail_media_id' => 'exists:medias,id'
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

    }

}
