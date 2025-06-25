<?php

namespace My\PackageWithPackages\Http\Controllers;

use My\PackageWithPackages\Services\PackageList\getAllPackages\GetAllPackages;
use My\PackageWithPackages\Services\PackageList\getAllPackages\InputDTO;
use My\PackageWithPackages\Services\PackageList\getAllPackages\PackageListException;

class StartPackageController
{
    public function packageWork(
        GetAllPackages $getAllPackages,
    )
    {
        try {
            $packages = $getAllPackages(new InputDTO());
        } catch (PackageListException $e) {
            $packages = [];
        }

        return view('packageWithPackages::mainPackageView', ['packages' => $packages->packages]);
    }
}
