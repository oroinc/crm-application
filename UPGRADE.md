The upgrade instructions are available at [OroCRM website](https://oroinc.com/orocrm/doc/current/install-upgrade/upgrade).

This file includes only the most important items that should be addressed before attempting to upgrade or during the upgrade of a vanilla Oro application.

Please also refer to [CHANGELOG.md](CHANGELOG.md) for a list of significant changes in the code that may affect the upgrade of some customizations.

## 4.2.0

The minimum required PHP version is 7.4.14.

### Routing

The regular expressions in `fos_js_routing.routes_to_expose` configuration parameter (see `config/config.yml`) have changed.

### Directory structure and filesystem changes

The `var/attachment` and `var/import_export` directories are no longer used for storing files and have been removed from the default directory structure.

All files from these directories must be moved to the new locations:
- from `var/attachment/protected_mediacache` to `var/data/protected_mediacache`;
- from `var/attachment` to `var/data/attachments`;
- from `var/import_export` to `var/data/importexport`.

Files for the standard import should be placed into `var/data/import_files` instead of `var/import_export/files`.

The `public/uploads` directory has been removed.

## 4.1.0

The minimum required PHP version is 7.3.13.

Upgrade PHP before running `composer install` or `composer update`, otherwise composer may download wrong versions of the application packages.

## 3.1.0

The minimum required PHP version is 7.1.26.

Upgrade PHP before running `composer install` or `composer update`, otherwise composer may download wrong versions of the application packages.
