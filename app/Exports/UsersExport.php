<?php

namespace App\Exports;

use App\Services\UsersService;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class UsersExport implements FromView
{
    public function __construct(private UsersService $service) 
    {}

    public function view(): View
    {
        $users = $this->service->getAll();
        return view('exports.users', compact('users'));
    }
}

