<?php

namespace App\Http\Controllers;

use App\Models\ItemSelection;
use App\Http\Requests\StoreItemSelectionRequest;
use App\Http\Requests\UpdateItemSelectionRequest;

class ItemSelectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ItemSelection::simplePaginate(5);
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
