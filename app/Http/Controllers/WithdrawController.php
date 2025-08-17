<?php

namespace App\Http\Controllers;

use App\Services\WithdrawService\WithdrawService;

class WithdrawController extends Controller
{
    public function withdraw(WithdrawService $withdrawService)
    {
        $res = $withdrawService->withdraw(1, 100);

        if (empty($res)) {
            throw new \Exception("Unexpected return");
        }
        return ["ok" => true];
    }
}
