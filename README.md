# Laravel Module Installer

The purpose of this package is to allow for easy installation of standalone Modules into the [Laravel Modules](https://github.com/nWidart/laravel-modules) package. This package will ensure that your module is installed into the `Modules/` directory instead of `vendor/`.

You can specify an alternate directory by including a `module-dir` in the extra data in your composer.json file:

    "extra": {
        "module-dir": "Custom"
    }


## Installation

1. Ensure you have the `type` set to `laravel-module` in your module's `composer.json`
2. Ensure your package is named in the convention `<namespace>/<name>-module`, for example `joshbrw/user-module` would install into `Modules/User`
3. Require this package: `composer require joshbrw/laravel-module-installer`
4. Require your bespoke module using Composer. You may want to set the constraint to `dev-master` to ensure you always get the latest version.

## Notes
* When working on a module that is version controlled within an app that is also version controlled, you have to commit and push from inside the Module directory and then `composer update` within the app itself to ensure that the latest version of your module (dependant upon constraint) is specified in your composer.lock file.
