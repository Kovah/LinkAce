<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::withTrashed()->orderBy('name')->paginate();
        return view('admin.user-management.index', ['users' => $users]);
    }

    public function inviteUser()
    {
        //
    }

    public function acceptInvitation()
    {
        //
    }

    public function deleteUser()
    {
        //
    }

    public function restoreUser()
    {
        //
    }
}
