<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\PrivateShare;
use Illuminate\Http\Request;

class PrivateShareController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PrivateShare::class);
    }

    public function index()
    {
        // @TODO should there be an overview?
    }

    public function create()
    {
        // @TODO where are share links created from?
    }

    public function store(Request $request)
    {
    }

    public function show(PrivateShare $privateShare)
    {
    }

    public function edit(PrivateShare $privateShare)
    {
    }

    public function update(Request $request, PrivateShare $privateShare)
    {
    }

    public function destroy(PrivateShare $privateShare)
    {
    }
}
