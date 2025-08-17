<?php

namespace App\Services\WithdrawService;

interface WithdrawService
{
    public function withdraw($user, $amount);
}
