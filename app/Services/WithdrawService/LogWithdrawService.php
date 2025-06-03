<?php

namespace App\Services\WithdrawService;

use Illuminate\Support\Facades\Log;

class LogWithdrawService implements WithdrawService
{
    public function withdraw($user, $amount)
    {
        Log::info('Withdraw', [$user, $amount]);

        return $amount;
    }
}
