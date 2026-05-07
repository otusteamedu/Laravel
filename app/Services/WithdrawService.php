<?php

namespace App\Services;

use Log;

interface WithdrawService
{
    public function withdraw($user, $amount);
}
