<?php

namespace My\PackageWithPackages\Services\PackageList\getAllPackages;

use \Exception;

class PackageListException extends Exception
{
    public $message;

    public function __construct()
    {
        parent::__construct();
        $this->message = __('packageWithPackages::packageLang.packageListException');
    }
}
