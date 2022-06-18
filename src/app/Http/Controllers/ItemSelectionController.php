<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemSelection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class ItemSelectionController extends Controller
{

    public function index(Request $_request)
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

        // validate user organization
        $no_access = User::validateUserOrganizationRole((int)$user->id, ((int)$_request->input('organization_id')), ['staff']);
        if ($no_access) {
            return $no_access;
        }

        //dd( $_request->input('organization_id')  );
        $selected_items = ItemSelection::all()->where('user_id', $user->id)->where('organization_id', $_request->input('organization_id'));
//            dd($selected_items);
        if ($selected_items->count() == 0) {
            return [
                'status_code' => 1,
                'message' => 'items selected by user id: ' . $user->id . ', org id: ' . $_request->input('organization_id'),
                'selections' => [],
            ];
        }

        $selection_dates = [];
        $selection_days = [];
        foreach ($selected_items as $selected_item) {
            $date = date_create($selected_item->scheduled_at);
            // echo date_format($date, 'Y-m-d H:i:s');
            $selection_dates = $this->getItemsByIds(json_decode($selected_item->items_ids), $selected_item->scheduled_at);
            foreach ($selection_dates as $selection_date){
                $selection_days[] = $selection_date;
            }
        }

       // $selections['selections'] = $selection_dates;

        $items =
            ['status_code' => 1,
                'message' => 'items selected by user id: ' . $user->id . ', org id: ' . $_request->input('organization_id'),
                'data' => $selection_days,
            ];
        return $items;

    }

    public function getItemsByIds(array $_item_ids, $_date)
    {
        $items = Item::findMany($_item_ids);
        foreach ($items as $item) {
            $date = date_create($_date);
            $item['scheduled_at'] =  date_format($date, 'Y-M-d');
        }
        return $items;
    }

    public function store(Request $_request)
    {

        $validator = Validator::make($_request->all(), [
            'organization_id' => 'required|exists:organizations,id',
            'item_id' => 'required|exists:items,id',
            'scheduled_at' => 'required|date_format:Y-m-d',
            'add_or_remove' => 'required|in:add,remove',
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

        // validate staff permission in organization
        $no_access = User::validateUserOrganizationRole((int)$user->id, ((int)$_request->input('organization_id')), ['org_admin', 'chef', 'staff']);
        if ($no_access) {
            return $no_access;
        }

        // find an existing item selection for this day
        $item_selection = ItemSelection:: whereUserId($user->id)->whereOrganization_id($_request->input('organization_id'))->whereScheduled_at($_request->input('scheduled_at'))->first();
        $item_selection_ids = [];

        // item selection exist for this day
        if ($item_selection) {

            $item_selection_ids = json_decode($item_selection->items_ids);
        } else {
            // add new item selection for this day
            $item_selection = new ItemSelection();
            $item_selection->schedule_id = $user->id;
            $item_selection->user_id = $user->id;
            $item_selection->organization_id = $_request->input('organization_id');

        }

        // add role
        if ($_request->input('add_or_remove') == 'add') {

            array_push($item_selection_ids, (int)$_request->input('item_id'));

            // remove duplicate items
            $item_selection_ids = array_unique($item_selection_ids);

            $item_selection->item_id = 4; // to be deleted
            $item_selection->scheduled_at = $_request->input('scheduled_at'); // to be deleted
            $item_selection->items_ids = json_encode($item_selection_ids);
            $item_selection->save();
            return [
                'status_code' => 1,
                'message' => 'added item: ' . $_request->input('item_id') . ' on ' . $_request->input('scheduled_at') . ' for this user:' . $user->id . ' lol'
            ];
        }

        // remove role
        if ($_request->input('add_or_remove') == 'remove' && $item_selection_ids) {
            // add new role
            $item_selection_ids = array_diff($item_selection_ids, [$_request->input('item_id')]);

            // remove duplicate roles
            $item_selection->items_ids = json_encode($item_selection_ids);
            $item_selection->save();
            return [
                'status_code' => 1,
                'message' => 'removed item:' . $_request->input('item_id') . ' on ' . $_request->input('scheduled_at') . ' item selection for this user:' . $user->id . ' lol'
            ];
        }


        return [
            'status_code' => 0,
            'message' => 'sum thin bad bruv'
        ];

    }

    public function show(ItemSelection $itemSelection)
    {
        //
    }


}
