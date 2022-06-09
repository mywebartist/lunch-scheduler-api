<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\OrganizationUser;
use App\Models\User;
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
        $user = User::whereEmail($email)->first();
        if (!$user) {
            return ['status_code' => 0,
                'message' => 'login expired'
            ];
        }

        // validate user organization
        $no_access = User::validateUserOrganizationRole((int)$user->id, ((int)$_request->input('organization_id')), ['staff']);
        if ($no_access) {
            return $no_access;
        }

        $user_org = OrganizationUser::where('user_id', $user->id,)->where('organization_id', $_request->input('organization_id'))->firstOrFail();
        $items = Item::whereOrganizationId($user_org->organization_id)->simplePaginate(5);

        // return $items;
        $items = array_merge(
            ['status_code' => 1,
                'message' => 'items from this org'
            ],
            $items->toArray());
        return $items;
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
        $user = User::whereEmail($email)->first();
        if (!$user) {
            return ['status_code' => 0,
                'message' => 'login expired'
            ];
        }

        // validate chef permission in organization
        $no_access = User::validateUserOrganizationRole((int)$user->id, ((int)$_request->input('organization_id')), ['chef']);
        if ($no_access) {
            return $no_access;
        }

        // check existing item with same name
        $item_existing = Item::whereName($_request->input('name'))->whereOrganization_id($_request->input('organization_id'))->first();

        if ($item_existing) {
            return ['status_code' => 0,
                'message' => 'item with this name already exist in your org, open your eyes lol'
            ];
        }

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


    public function show(Request $_request, $_item_id)
    {
        // get user
        $email = Crypt::decrypt($_request->header('x-apikey'));
        $user = User::whereEmail($email)->first();
        if (!$user) {
            return ['status_code' => 0,
                'message' => 'login expired'
            ];
        }

        // get item
        $item = Item::whereId($_item_id)->firstOrFail();

        // validate user organization
        $no_access = User::validateUserOrganizationRole((int)$user->id, ((int)$item->organization_id), ['staff']);
        if ($no_access) {
            return $no_access;
        }

        $item = array_merge(
            ['status_code' => 1,
                'message' => 'item found'
            ],
            $item->toArray()
        );
        return $item;
    }

    public function update(Request $_request, $_item_id)
    {

        $validator = Validator::make($_request->all(), [
            'thumbnail_media_id' => 'exists:medias,id'
        ]);


        if ($validator->fails()) {
            return $validator->messages();
        }

        // find item
        $item = Item::find($_item_id);
        if (!$item) {
            return ['status_code' => 0,
                'message' => 'item does not exist'
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

        // validate chef permission in organization
        $no_access = User::validateUserOrganizationRole((int)$user->id, ((int)$item->organization_id), ['chef']);
        if ($no_access) {
            return $no_access;
        }

        // check existing item with same name
        $item_existing = Item::whereName($_request->input('name'))->whereOrganization_id($_request->input('organization_id'))->first();

        if ($item_existing) {
            return ['status_code' => 0,
                'message' => 'item with this name already exist in your org, open your eyes lol'
            ];
        }

        if ($_request->input('name')) {
            $item->name = $_request->input('name');
        }

        if ($_request->input('description')) {
            $item->description = $_request->input('description');
        }

        $item->save();
        $item = array_merge(
            ['status_code' => 1,
                'message' => 'item updated'
            ],
            $item->toArray()
        );
        return $item;
    }

    public function destroy(Request $_request, $_item_id)
    {

        $validator = Validator::make($_request->all(), [
            'thumbnail_media_id' => 'exists:medias,id'
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        // find item
        $item = Item::find($_item_id);
        if (!$item) {
            return ['status_code' => 0,
                'message' => 'item does not exist'
            ];
        }

        // get user org
        $email = Crypt::decrypt($_request->header('x-apikey'));
        $user = User::whereEmail($email)->first();
        if (!$user) {
            return ['status_code' => 0,
                'message' => 'login expired'
            ];
        }

        // validate chef permission in organization
        $no_access = User::validateUserOrganizationRole((int)$user->id, ((int)$item->organization_id), ['chef']);
        if ($no_access) {
            return $no_access;
        }

        $item = Item::destroy($_item_id);
        if ($item) {
            return [
                'status_code' => 1,
                'message' => 'deleted'
            ];
        }

        return [
            'status_code' => 0,
            'message' => 'item not deleted'
        ];
    }
}
