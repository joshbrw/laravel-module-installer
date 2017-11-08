<?php

namespace Joshbrw\LaravelModuleInstaller;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class LaravelModuleInstaller extends LibraryInstaller
{
    const DEFAULT_ROOT = "Modules";

    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        return $this->getBasePath() . '/' . $this->getModuleName($package);
    }

    protected function getBasePath()
    {
        if (! $this->composer || ! $this->composer->getPackage())
            return self::DEFAULT_ROOT;

        $extra = $this->composer->getPackage()->getExtra();
        if (! $extra || empty($extra['module-dir']))
            return self::DEFAULT_ROOT;

        return $extra['module-dir'];
    }

    protected function getModuleName(PackageInterface $package)
    {
        $name = $package->getPrettyName();
        $split = explode("/", $name);

        if (count($split) !== 2)
            throw new \Exception($this->usage());

        $splitNameToUse = explode("-", $split[1]);

        if (count($splitNameToUse) < 2)
            throw new \Exception($this->usage());

        if (array_pop($splitNameToUse) !== 'module')
            throw new \Exception($this->usage());

        $final = '';
        foreach($splitNameToUse as $part)
            $final .= ucfirst($part);

        return $final;
    }

    protected function usage()
    {
        return "Ensure your package's name is in the format <vendor>/<name>-<module>";
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return 'laravel-module' === $packageType;
    }
}
