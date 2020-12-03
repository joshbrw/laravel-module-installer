<?php

namespace Joshbrw\LaravelModuleInstaller\Exceptions;

use Exception;

class LaravelModuleInstallerException extends Exception
{
    protected $message = "Ensure your package's name is in the format <vendor>/<name>-<module>";
}