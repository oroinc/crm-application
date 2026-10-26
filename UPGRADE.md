The upgrade instructions are available at [Oro documentation website](https://doc.oroinc.com/master/backend/setup/upgrade-to-new-version/).

This file includes only the most important items that should be addressed before attempting to upgrade or during the upgrade of a vanilla Oro application.

Please also refer to [CHANGELOG.md](CHANGELOG.md) for a list of significant changes in the code that may affect the upgrade of some customizations.

### 7.1.0

#### EmailBundle - Available in Template Entity Configuration

- The new `email.available_in_template` entity config setting controls whether an entity can be selected when creating email templates. By default, all entities are unavailable. To make an entity available for email templates, enable this setting in the `#[Config]` attribute before the upgrade (if you can change the entity's source code), or after the upgrade using the Entity Management UI or the `\Oro\Bundle\EntityConfigBundle\Migration\UpdateEntityConfigEntityValueQuery` migration query.
- The same `email.available_in_template` setting also exists at the entity field level and controls which fields are exposed as variables in email templates, and now it defaults to `false`. Enable it in the `#[ConfigField]` attribute, via the Entity Management UI, or using the `\Oro\Bundle\EmailBundle\Migration\SetEmailAvailableInTemplateQuery` migration query.
- Use the following commands to verify:
  - `./bin/console oro:debug:email:template:entities` — lists entity classes available when creating email templates.
  - `./bin/console oro:debug:email:variables` — lists template variables allowed for a given entity.
  - `./bin/console oro:email:template:security-policy-check` — validates that an email template passes the security policy checks.
- Use the following commands to enable "email.available_in_template" setting:
  - `./bin/console oro:platform:post-upgrade-tasks --task=enable_available_in_template_for_entities_having_templates` — enables the `email.available_in_template` entity config setting for all entities that already have email templates.
  - `./bin/console oro:platform:post-upgrade-tasks --task=enable_available_in_template_for_fields_in_templates` — enables the `email.available_in_template` entity field config setting for all entity fields that are used in email templates.
- The confirmation token of a user is no longer an email template variable. The sending code passes it as the template parameter `confirmationToken`.
- An email template render resolves only the records the current user is allowed to view: an attribute of a related record the current user cannot view renders empty.

#### ImportExportBundle - Import/Export Result Permissions

- During the upgrade, the `View` permission on the `Import/Export result` entity is set to the `User` access level for every role, except `Administrator`, that has a wider level, for example, `ROLE_USER` and `ROLE_MANAGER`. The roles with the `User` level and the roles without the `View` permission are not changed.
- After the upgrade, users of these roles can open only the export files and the error logs of the import and export jobs they ran. If a role needs access to the results of other users, set the `View` permission on `Import/Export result` to the required level on the role edit page.
- Access to an export file or an error log also requires the `View` permission, at any access level, on the entity the import or export was run for, if this entity is protected by ACL.

#### AttachmentBundle - File and Attachment API Content Validation and Safe File Serving

- The File and Attachment REST APIs validate a file against the upload restrictions of the field the file is added to, in every request that creates or updates a file, including a file included into a request of another resource. The uploaded `content` must have an allowed MIME type and must not exceed the maximum file size. An external file created from `externalUrl` must have an allowed MIME type. The field must store files externally for an `externalUrl` and must not store files externally for `content` (`is_stored_externally`, the `Oro\Bundle\AttachmentBundle\Validator\Constraints\FileFieldCompatibility` constraint). A file of an attachment is validated against the attachment configuration of its target entity. A file without a parent entity is validated against the system-wide upload settings (`oro_attachment.upload_file_mime_types`, `oro_attachment.upload_image_mime_types` and `oro_attachment.maxsize`). A request that does not meet these restrictions is rejected with an HTTP 400 response. Integrations must send files that meet these restrictions, or change the configuration of the field or of the entity attachments.
- The `mimeType` field of the File REST API is now read-only. A value passed on input is ignored, and the stored MIME type is detected from the file content, so `mimeType` can be removed from create and update request payloads.
- Files served through the file download controller (`{prefix}/attachment/get/{id}/{filename}`) are now opened in the browser only when their MIME type is in the new `oro_attachment.inline_mime_types` list. By default, it contains `image/gif`, `image/jpeg`, `image/png`, `image/webp`, `application/pdf`, `text/plain`, and `text/csv`. A file of any other type, including `text/html` and `image/svg+xml`, keeps its media type in the `Content-Type` header and is served with the `Content-Disposition: attachment` and `Content-Security-Policy: sandbox` headers, so a browser downloads it instead of showing it. A file whose stored type is empty or is not a valid media type is served as `application/octet-stream`. The `download` action keeps the `application/force-download` content type and gets the same two headers.
- The stored MIME type is compared with the list as a media type: in lower case, without parameters, and the parameters of the stored type are not passed to the `Content-Type` header; the framework can still add `charset` to a `text/*` type.
- The `oro_attachment.inline_mime_types` option can be changed in the application configuration. The option rejects the well-known types that a browser runs as a page or a script, for example `text/html`, `image/svg+xml` and other `+xml` types, and the JavaScript types. This check is a safety net, not a complete list: add only the types that a browser opens without running anything. The value you set replaces the default list instead of extending it, so list every type that must be opened in the browser, including the default ones.
- A new file included into a request can be used in one field only. If the file has a `parent`, it must be the entity and the field the file is added to. Otherwise, the request is rejected with an HTTP 400 response.
- If you extend or decorate `Oro\Bundle\AttachmentBundle\Api\Form\Type\FileEntityType` or `Oro\Bundle\AttachmentBundle\Api\Form\Type\MultiFileEntityType`, pass the new `Oro\Bundle\AttachmentBundle\Api\Form\FileValidationTargetRegistrar` argument (service `oro_attachment.api.form.file_validation_target_registrar`) as the last constructor argument.

### 6.1.8, 6.0.9, 7.0.1

Strict deserialization rules were applied to workflow data. Every class referenced in serialized objects, object graphs, or arrays must belong to a trusted namespace prefix or be explicitly allowed. If a disallowed class is detected, the application throws a `RuntimeException` and aborts deserialization by default.

When a violation occurs, the following critical log entry is written to the `oro_workflow` channel regardless of whether blocking is enabled:

```
CRITICAL: Failed to unserialize workflow attribute value {"exception":"[...] Data passed to unserialize contains not allowed classes: <fully\qualified\class\name>"} []
```

Three environment variables control the behavior:

- `ORO_UNSERIALIZE_BLOCK` (boolean, default: `true`) — set to `false` to log violations as critical errors instead of throwing an exception. Use this temporarily to audit existing workflow data before enforcing the new rules.
- `ORO_UNSERIALIZE_TRUSTED_ORGS` (comma-separated, optional) — additional namespace prefixes whose classes are permitted during deserialization (e.g., `Acme\\`).
- `ORO_UNSERIALIZE_NOT_ALLOWED_CLASSES` (comma-separated, optional) — additional fully qualified class names to block from deserialization regardless of namespace.

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
