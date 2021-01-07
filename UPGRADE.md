The upgrade instructions are available at [OroCRM website](https://oroinc.com/orocrm/doc/current/install-upgrade/upgrade).

This file includes only the most important items that should be addressed before attempting to upgrade or during the upgrade of a vanilla Oro application.

Please also refer to [CHANGELOG.md](CHANGELOG.md) for a list of significant changes in the code that may affect the upgrade of some customizations.

## 4.2.0

The minimum required PHP version is 7.4.11.

The File storage component was implement. Directories `var/attchment` and `var/import_export` are no longer used as storage
and has been removed from the git source code.

Files from these directories must be moved to new locations:

 - files from `var/attachment` to `var/data/attachments`;
 - files from `var/attachment/protected_mediacache` to `var/data/protected_mediacache`;
 - files from `var/import_export` to `var/data/importexport`.
 
Files for import should be placed into `var/data/import_files` instead of `var/import_export/files`.

Directory `public/uploads` has been removed.

## 4.1.0

The minimum required PHP version is 7.3.13.

Upgrade PHP before running `composer install` or `composer update`, otherwise composer may download wrong versions of the application packages.

## 3.1.0

The minimum required PHP version is 7.1.26.

Upgrade PHP before running `composer install` or `composer update`, otherwise composer may download wrong versions of the application packages.
