<?php

namespace App\Http\Controllers;

use App\Models\OrganizationUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class OrganizationUserController extends Controller
{
    //
    public function org_users(Request $_request)
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
        $user = User::whereEmail($email)->firstOrFail();

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
}
