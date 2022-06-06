<?php

namespace App\Http\Controllers;

use App\Models\ItemSelection;
use App\Http\Requests\StoreItemSelectionRequest;
use App\Http\Requests\UpdateItemSelectionRequest;
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
            return $validator->messages();
        }

        // get user
        $email = Crypt::decrypt($_request->header('x-apikey'));
        $user = User::whereEmail($email)->firstOrFail();


        // validate user organization
        $no_access = User::validateUserOrganization((int)$user->id, ((int)$_request->input('organization_id')));
        if ($no_access) {
            return $no_access;
        }


        $items_selected =  ItemSelection::simplePaginate(5) ;
        $items = array_merge(
            ['status_code' => 1,
                'message' => 'items selected'
            ],
            $items_selected->toArray()
            );
        return $items;


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreItemSelectionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreItemSelectionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemSelection  $itemSelection
     * @return \Illuminate\Http\Response
     */
    public function show(ItemSelection $itemSelection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemSelection  $itemSelection
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemSelection $itemSelection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateItemSelectionRequest  $request
     * @param  \App\Models\ItemSelection  $itemSelection
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateItemSelectionRequest $request, ItemSelection $itemSelection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemSelection  $itemSelection
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemSelection $itemSelection)
    {
        //
    }
}
