<?php

namespace App\Http\Controllers;

use App\Models\LoginPin;
use App\Http\Requests\StoreLoginPinRequest;
use App\Http\Requests\UpdateLoginPinRequest;

class LoginPinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreLoginPinRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLoginPinRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LoginPin  $loginPin
     * @return \Illuminate\Http\Response
     */
    public function show(LoginPin $loginPin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LoginPin  $loginPin
     * @return \Illuminate\Http\Response
     */
    public function edit(LoginPin $loginPin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLoginPinRequest  $request
     * @param  \App\Models\LoginPin  $loginPin
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLoginPinRequest $request, LoginPin $loginPin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LoginPin  $loginPin
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoginPin $loginPin)
    {
        //
    }
}
