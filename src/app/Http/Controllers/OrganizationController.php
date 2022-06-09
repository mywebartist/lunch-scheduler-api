<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;


class OrganizationController extends Controller
{

    public function store(Request $_request)
    {
        $validator = Validator::make($_request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        // get user
        $email = Crypt::decrypt($_request->header('x-apikey'));
        $user = User::whereEmail($email)->firstOrFail();

        // create org
        $org = new Organization();
        $org->name = $_request->input('name');
        $org->save();

        // create user_org
        $user_org = new OrganizationUser();
        $user_org->organization_id = $org->id;
        $user_org->user_id = $user->id;
        $user_org->save();

        return ['status_code' => 1,
            'message' => 'org entered'
        ];
    }


    public function show(Request $_request, $_org_id)
    {
        //
        $validator = Validator::make($_request->all(), [

        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        // get user
        $email = Crypt::decrypt($_request->header('x-apikey'));
        $user = User::whereEmail($email)->firstOrFail();

        // find org
        $org = Organization::whereId(4)->firstOrFail();
        if (!$org) {
            return ['status_code' => 0,
                'message' => 'org does not exist'
            ];
        }

        $org = array_merge(
            ['status_code' => 1,
                'message' => 'org found'
            ],
            $org->toArray()
        );
        return $org;

    }


    public function update(Request $_request, $_org_id)
    {
        // get user
        $email = Crypt::decrypt($_request->header('x-apikey'));
        $user = User::whereEmail($email)->firstOrFail();


        // find item
        $org = Organization::find($_org_id);
        if (!$org) {
            return ['status_code' => 0,
                'message' => 'org does not exist'
            ];
        }

        // validate chef permission in organization
        $no_access = User::validateUserOrganizationRole((int)$user->id, ((int)$org->id), ['chef', 'org_admin']);
        if ($no_access) {
            return $no_access;
        }

        if ($_request->input('name')) {
            $org->name = $_request->input('name');
        }

        if ($_request->input('description')) {
            $org->description = $_request->input('description');
        }

        if ($_request->input('website')) {
            $org->website = $_request->input('website');
        }
        $org->save();

        return ['status_code' => 1,
            'message' => 'org updated'
        ];
    }


}
