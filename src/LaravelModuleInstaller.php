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
     * Get the fully-qualified install path
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        return $this->getBaseInstallationPath() . '/' . $this->getModuleName($package);
    }
    
    /**
     * Get the base path that the module should be installed into.
     * Defaults to Modules/ and can be overridden in the module's composer.json.
     * @return string
     */
    protected function getBaseInstallationPath()
    {
        if (!$this->composer || !$this->composer->getPackage()) {
            return self::DEFAULT_ROOT;
        }
        
        $extra = $this->composer->getPackage()->getExtra();
        
        if (!$extra || empty($extra['module-dir'])) {
            return self::DEFAULT_ROOT;
        }
        
        return $extra['module-dir'];
    }
    
    /**
     * Get the module name, i.e. "joshbrw/something-module" will be transformed into "Something"
     * @param PackageInterface $package
     * @return string
     * @throws \Exception
     */
    protected function getModuleName(PackageInterface $package)
    {
        $name = $package->getPrettyName();
        $split = explode("/", $name);
        
        if (count($split) !== 2) {
            throw new \Exception($this->usage($name));
        }
        
        $splitNameToUse = explode("-", $split[1]);
        
        if (count($splitNameToUse) < 2) {
            throw new \Exception($this->usage($name));
        }
        
        if (array_pop($splitNameToUse) !== 'module') {
            throw new \Exception($this->usage($name));
        }
        
        return implode('',array_map('ucfirst', $splitNameToUse));
    }
    
    /**
     * Get the usage instructions
     * @return string
     */
    protected function usage($name)
    {
        return "Ensure your package's name ($name) is in the format <vendor>/<name>-<module>";
    }
    
    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return 'laravel-module' === $packageType;
    }
}
