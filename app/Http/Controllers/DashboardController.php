<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Services\userService\getAllUsers\GetAllUsers;
use App\Services\userService\getUser\InputDTO as userDTO;

class DashboardController
{
    public function index(
        Gate $gate,
        GetAllUsers $getAllUsers,
    )
    {
        $usersToShow = $getAllUsers();

        return view('dashboard', ['usersToShow' => $usersToShow]);
    }
}
