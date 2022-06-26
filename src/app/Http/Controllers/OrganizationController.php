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

    public function index(Request $_request){
        $validator = Validator::make($_request->all(), [
//            'name' => 'required|max:255',
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
//        dd('df');
        $user = User::whereEmail($email)->first();
        if (!$user) {
            return ['status_code' => 0,
                'message' => 'login expired'
            ];
        }

        // find org
        $orgs = Organization::simplePaginate(10);
//        if (!$orgs) {
//            return ['status_code' => 0,
//                'message' => 'org does not exist'
//            ];
//        }

        $user_orgs = OrganizationUser::all()->where('user_id', $user->id);
//        dd($user_orgs);
        // add if user is part of this org
        foreach ($orgs as $org){
            foreach($user_orgs as $user_org){
                if($org->id == $user_org->user_id){
                        $org['joined'] = true;
                        $org['roles'] =  $user_org->roles;
                }
            }

        }

        $orgs = array_merge(
            ['status_code' => 1,
                'message' => 'all the registered orgs lol'
            ],
            $orgs->toArray()
        );
        return $orgs;


    }

    public function store(Request $_request)
    {
        $validator = Validator::make($_request->all(), [
            'name' => 'required|max:255',
            'description' => 'sometimes|max:255',
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
            return [
                'status_code' => 0,
                'message' => 'login expired'
            ];
        }

        // get org for this user where user is org_admin
        $user_orgs = OrganizationUser::all()->where('user_id', $user->id);
        foreach($user_orgs as $user_org){
            if( in_array( 'org_admin', json_decode(  $user_org->roles) )){
                $org = Organization::whereId($user_org->organization_id)->first();
                return [
                    'status_code' => 0,
                    'message' => 'you already have org id: '. $user_org->id .', name:"'. $org->name  . '", you can only have 1 org lmao'
                ];
            }
        }

        // create org
        $org = new Organization();
        $org->name = $_request->input('name');
        if($_request->input('description')){
            $org->description = $_request->input('description');
        }
        $org->save();

        // create user_org
        $user_org = new OrganizationUser();
        $user_org->organization_id = $org->id;
        $user_org->user_id = $user->id;
        $user_org->roles =  json_encode( ["org_admin", "chef", "staff"]) ;
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
        $user = User::whereEmail($email)->first();
        if (!$user) {
            return ['status_code' => 0,
                'message' => 'login expired'
            ];
        }

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
