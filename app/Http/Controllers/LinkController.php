<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinkDeleteRequest;
use App\Http\Requests\LinkStoreRequest;
use App\Http\Requests\LinkUpdateRequest;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LinkStoreRequest $request
     * @return void
     */
    public function store(LinkStoreRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return void
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LinkUpdateRequest $request
     * @param  int              $id
     * @return void
     */
    public function update(LinkUpdateRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param LinkDeleteRequest $request
     * @param  int              $id
     * @return void
     */
    public function destroy(LinkDeleteRequest $request, $id)
    {
        //
    }
}
