<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryDeleteRequest;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;

class CategoryController extends Controller
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
     * @param CategoryStoreRequest $request
     * @return void
     */
    public function store(CategoryStoreRequest $request)
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
     * @param CategoryUpdateRequest $request
     * @param  int                  $id
     * @return void
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CategoryDeleteRequest $request
     * @param  int                  $id
     * @return void
     */
    public function destroy(CategoryDeleteRequest $request, $id)
    {
        //
    }
}
