<?php

namespace App\Services\Team;

use Exception;

class TeamNotFoundException extends Exception
{
    protected $message = 'команда не найдена';
}
