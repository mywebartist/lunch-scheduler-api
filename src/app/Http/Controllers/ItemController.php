<?php

namespace App\Http\Controllers;

use App\Http\Services\AuthService;
use App\Models\Item;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\User;
use App\Models\UserOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;


class ItemController extends Controller
{

    public function index(Request $_request)
    {

        $validator = Validator::make($_request->all(), [
            'organization_id' => 'required|exists:organizations,id',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        // get user
        $email = Crypt::decrypt($_request->header('x-apikey'));
        $user = User::whereEmail($email)->firstOrFail();

//        dd(( (int) $_request->input('organization_id' ) ) );

        // validate user organization
        $no_access = User::validateUserOrganization( (int)$user->id,( (int) $_request->input('organization_id' ) ));
        if ($no_access) {
            return $no_access;
        }
//        dd('g');

        $user_org = UserOrganization::where('user_id', $user->id,)->where('organization_id', $_request->input('organization_id'))->firstOrFail();
//        print_r($user->firstOrFail());
//        dd( $user->id  );
//        $user_count = User::where('email', $_request->input('email'))->count();
//            dd($_request->input('organization_id'));

//        dd($_request->header('x-apikey'));
//        dd($organization_user->organization_id);
        $items = Item::whereOrganizationId($user_org->organization_id)->simplePaginate(5);

        return $items;
//        $items = Item::where
//        dd( $items );
//        return Item::simplePaginate(5);
    }

    public function store(Request $_request)
    {

        $validator = Validator::make($_request->all(), [
            'name' => 'required|max:255',
            'organization_id' => 'required|exists:organizations,id',
            'thumbnail_media_id' => 'exists:medias,id'
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        // get user
        $email = Crypt::decrypt($_request->header('x-apikey'));
        $user = User::whereEmail($email)->firstOrFail();

        // validate chef rights
//        $no_access = User::validateChef($email, $_request->organization_id);
//        if ($no_access) {
//            return $no_access;
//        }

        // validate user role in organization
        $no_access = User::validateUserOrganizationRole( (int)$user->id, ( (int) $_request->input('organization_id' ) ) , ['chef'] );
        if ($no_access) {
            return $no_access;
        }


//        $user = User::whereEmail($email )->firstOrFail();
//        $user_org = UserOrganization::where('user_id', $user->id,)->where('organization_id', $_request->input('organization_id'))->first();
//
//// check user is part of that org
//        if ($user_org->count() < 1) {
//            return [
//                'status_code' => 0,
//                'message' => 'you are not part of this organization lol'
//            ];
//        }
//
//        // check user is chef
//        if ($user_org->role !== 'chef' ) {
//            return [
//                'status_code' => 0,
//                'message' => 'you are not a chef in this organization lol'
//            ];
//        }

//        dd($user_org->get('role'));

        $item = new Item();
        $item->name = $_request->input('name');
        $item->organization_id = $_request->input('organization_id');

        if ($_request->input('description')) {
            $item->description = $_request->input('description');
        }

        if ($_request->input('description')) {
            $item->description = $_request->input('description');
        }

        if ($validator->fails()) {
            return $validator->messages();
        }

        if ($_request->input('thumbnail_media_id')) {
            $item->thumbnail_media_id = $_request->input('thumbnail_media_id');
        }

        $item->save();
        return $item;
    }


    public function show( Request $_request, $_item_id)
    {
//        dd($_item_id);

        // get user org
        $email = Crypt::decrypt($_request->header('x-apikey'));

        $item = Item::whereId($_item_id)->firstOrFail();
//        dd($item);

        // validate staff organization
        $no_access = User::validateStaffOrganization( (int)$user->id,( (int) $_request->input('organization_id' ) ));
        if ($no_access) {
            return $no_access;
        }

        // validate staff rights
//        $no_access = User::validateStaff($email, $item, $_item_id);
//        if ($no_access) {
//            return $no_access;
//        }

        return $item;
    }

    public function update(Request $_request)
    {

        $validator = Validator::make($_request->all(), [
            'id' => 'required|exists:items,id',
//            'name' => 'required|max:255',
            'organization_id' => 'required|exists:organizations,id',
            'thumbnail_media_id' => 'exists:medias,id'
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        // get user org
        $email = Crypt::decrypt($_request->header('x-apikey'));

        // validate chef rights
        $no_access = User::validateChef($email, $_request->organization_id);
        if ($no_access) {
            return $no_access;
        }


        $item = Item::find($_request->input('id'));

//        dd($item->organization_id);
//        dd( (int) $_request->input('organization_id') );

        // check if this item is in that organization
        if( (int) $_request->input('organization_id') !== $item->organization_id ){
            return [
                'status_code' => 0,
                'message' => 'this item is not part of this organization lol'
            ];
        }

//        $item->name = $_request->input('name');
//        $item->organization_id = $_request->input('organization_id');

        if ($_request->input('name')) {
            $item->name = $_request->input('name');
        }

        if ($_request->input('description')) {
            $item->description = $_request->input('description');
        }

//        if ($validator->fails()) {
//            return $validator->messages();
//        }

//        if ($_request->input('thumbnail_media_id')) {
//            $item->thumbnail_media_id = $_request->input('thumbnail_media_id');
//        }

        $item->save();

        return $item;
//        return Item::find($_item->id);
    }

    public function destroy( Request $_request, $item_id )
    {
//            dd($_item);
//        dd($_item );
        $validator = Validator::make($_request->all(), [
//            'id' => 'required|exists:items,id',
//            'name' => 'required|max:255',
            'organization_id' => 'required|exists:organizations,id',
            'thumbnail_media_id' => 'exists:medias,id'
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        // get user org
        $email = Crypt::decrypt($_request->header('x-apikey'));

        // validate chef rights
        $no_access = User::validateChef($email, $_request->organization_id);
        if ($no_access) {
            return $no_access;
        }

        $item = Item::whereId( $item_id)->firstOrFail();

//        dd();
        // check if this item is in that organization
        if( (int) $_request->input('organization_id') !== $item->organization_id ){
            return [
                'status_code' => 0,
                'message' => 'this item is not part of this organization lol'
            ];
        }


        $item = Item::destroy($item_id);
        if ($item) {
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