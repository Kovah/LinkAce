<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagDeleteRequest;
use App\Http\Requests\TagStoreRequest;
use App\Http\Requests\TagUpdateRequest;
use Illuminate\Http\Request;

class TagController extends Controller
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
     * @param TagStoreRequest $request
     * @return void
     */
    public function store(TagStoreRequest $request)
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
     * @param TagUpdateRequest $request
     * @param  int             $id
     * @return void
     */
    public function update(TagUpdateRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param TagDeleteRequest $request
     * @param  int             $id
     * @return void
     */
    public function destroy(TagDeleteRequest $request, $id)
    {
        //
    }
}
