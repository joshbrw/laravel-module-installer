<?php

namespace Joshbrw\LaravelModuleInstaller;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class LaravelModuleInstaller extends LibraryInstaller
{
    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        $name = $package->getPrettyName();
        $split = explode("/", $name);

        if (count($split) !== 2) {
            throw new \Exception("Ensure your package's name is in the format <vendor>/<name>-<module>");
        }

        $nameToUse = $split[1];
        $splitNameToUse = explode("-", $nameToUse);

        if (count($splitNameToUse) < 2) {
            throw new \Exception("Ensure your package's name is in the format <vendor>/<name>-<module>");
        }

        return 'Modules/' . ucfirst($splitNameToUse[0]);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return 'laravel-module' === $packageType;
    }
}
