<?php

namespace App\Services;

use Log;

class WithdrawServiceImpl implements WithdrawService
{
    public function withdraw($user, $amount)
    {
        Log::info("withdraw", compact("user", "amount"));

        return $amount;
    }
}
