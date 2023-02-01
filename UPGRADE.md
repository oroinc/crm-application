The upgrade instructions are available at [Oro documentation website](https://doc.oroinc.com/master/backend/setup/upgrade-to-new-version/).

This file includes only the most important items that should be addressed before attempting to upgrade or during the upgrade of a vanilla Oro application.

Please also refer to [CHANGELOG.md](CHANGELOG.md) for a list of significant changes in the code that may affect the upgrade of some customizations.

### 5.1.0 RC

Added `.env-app` files support and removed most of the parameters from the config/parameters.yml in favor of environment variables with DSNs. For more details, see [the migration guide](https://doc.oroinc.com/master/backend/setup/dev-environment/env-vars/).

* The supported PHP version is 8.2
* The supported PostgreSQL version is 15
* The supported NodeJS version is 18
* The supported Redis version is 7
* The supported RabbitMQ version is 3.11
* The supported PHP MongoDB extension version is 1.15
* The supported MongoDB version is 6.0

## 5.0.0

The `oro.email.update_visibilities_for_organization` MQ process can take a long time when updating from the old versions
if the system has many email addresses (in User, Customer user, Lead, Contact, RFP request, Mailbox entities).
During performance tests with 1M of email addresses, this process  took  approximately 10 minutes.

It is recommended to add these MQ topics to the `oro.index` queue:

- `oro.email.recalculate_email_visibility`
- `oro.email.update_visibilities`
- `oro.email.update_visibilities_for_organization`
- `oro.email.update_email_visibilities_for_organization`
- `oro.email.update_email_visibilities_for_organization_chunk`

## 5.0.0-rc

The supported NodeJS version is 16.0

## 5.0.0-alpha.2

The minimum required PHP version is 8.0.0.

## 4.2.1

- The link at the calendar events search items was changed,
  please reindex calendar event items with command
  `php bin/console oro:search:reindex --class="Oro\Bundle\CalendarBundle\Entity\CalendarEvent"`

## 4.2.0

The minimum required PHP version is 7.4.14.

### Routing

The regular expressions in `fos_js_routing.routes_to_expose` configuration parameter (see `config/config.yml`) have changed.

### Directory structure and filesystem changes

The `var/attachment` and `var/import_export` directories are no longer used for storing files and have been removed from the default directory structure.

All files from these directories must be moved to the new locations:
- from `var/attachment/protected_mediacache` to `var/data/protected_mediacache`;
- from `var/attachment` to `var/data/attachments`;
- from `var/import_export` to `var/data/importexport`;
- from `var/import_export/files` to `var/data/import_files`.

The `public/uploads` directory has been removed.

The console command `oro:gaufrette:migrate-filestorages` will help to migrate the files to new structure.

## 4.1.0

- The minimum required PHP version is 7.3.13.
- The feature toggle for WEB API was implemented. After upgrade, the API feature will be disabled.
  To enable it please follow the documentation [Enabling an API Feature](https://doc.oroinc.com/api/enabling-api-feature/).
- Upgrade PHP before running `composer install` or `composer update`, otherwise composer may download wrong versions of the application packages.

## 3.1.0

The minimum required PHP version is 7.1.26.

Upgrade PHP before running `composer install` or `composer update`, otherwise composer may download wrong versions of the application packages.

## 2.6.0

* Changed minimum required php version to 7.1

## 2.2.0

* Search index fields `description`, `resolution` and `message` for `CaseEntity` now contain no more than **255** characters each.
    * Please, run re-indexation for this entity using command:
        ```bash
          php bin/console oro:search:reindex OroCaseBundle:CaseEntity --env=prod
        ```

## 2.1.0

* Changed minimum required php version to 7.0
* Updated dependency to [fxpio/composer-asset-plugin](https://github.com/fxpio/composer-asset-plugin) composer plugin to version 1.3.
* Composer updated to version 1.4.

```
    composer self-update
    composer global require "fxp/composer-asset-plugin"
```

## 2.0.0

### app/config/config.yml
- removed `be_simple_soap` section.
- removed `authentication_listener_class` parameter from `escape_wsse_authentication` section

## 1.9.0

### app/config/config.yml

- Removed `doctrine` section. From now this declaration is located in `OroPlatformBundle` bundle. Remove declaration of DBAL connections and ORM entity managers from your `app/config/config.yml` to be sure that an application will work properly.
- Removed unused `report_source` and `report_target` DBAL connections.
- Added `config` DBAL connection and ORM entity manager. They can be used as a gateway for different kind of configuration data to improve performance of the default ORM entity manager. For example `OroEntityConfigBundle` uses them for entity configuration data.

## 1.8.0

### app/config/config.yml
- Changed definition of `framework` / `templating` section. New definition is:
``` yaml
framework:
    templating:
        engines: ['twig', 'php']
        assets_version: %assets_version%
        assets_version_format: %%s?version=%%s
```
- Removed `twig` / `globals` / `ws` section. From now this declaration is located in `OroSyncBundle` bundle.
- Removed `clank` section. From now this declaration is located in `OroSyncBundle` bundle.
- Removed `doctrine.dbal.default.wrapped_connection` service.
- Removed `session.handler.pdo` service. From now a declaration of this service is located in `OroPlatformBundle`. Remove this service from your `app/config/config.yml` to be sure that PDO session will work properly. If you need to override this service, you can keep it in your `app/config/config.yml`, but make sure that a default database connection is not used here. You can use `doctrine.dbal.session_connection.wrapped` service if sessions are stored in a main database.

### app/Resources
- Removed `app/Resources/DoctrineBundle/views/Collector/db.html.twig`.
- Removed `app/Resources/SecurityBundle/views/Collector/security.html.twig`.
- Removed `app/Resources/SwiftmailerBundle/views/Collector/swiftmailer.html.twig`.
- Removed `app/Resources/WebProfilerBundle/views/Collector/config.html.twig`.
- Removed `app/Resources/WebProfilerBundle/views/Collector/logger.html.twig`.
- Removed `app/Resources/WebProfilerBundle/views/Collector/memory.html.twig`.
- Removed `app/Resources/WebProfilerBundle/views/Collector/request.html.twig`.
- Removed `app/Resources/WebProfilerBundle/views/Collector/time.html.twig`.
