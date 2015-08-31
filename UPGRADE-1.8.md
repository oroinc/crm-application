UPGRADE FROM any to 1.8
=======================

## app/config/config.yml
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

## app/Resources
- Removed `app/Resources/DoctrineBundle/views/Collector/db.html.twig`.
- Removed `app/Resources/SecurityBundle/views/Collector/security.html.twig`.
- Removed `app/Resources/SwiftmailerBundle/views/Collector/swiftmailer.html.twig`.
- Removed `app/Resources/WebProfilerBundle/views/Collector/config.html.twig`.
- Removed `app/Resources/WebProfilerBundle/views/Collector/logger.html.twig`.
- Removed `app/Resources/WebProfilerBundle/views/Collector/memory.html.twig`.
- Removed `app/Resources/WebProfilerBundle/views/Collector/request.html.twig`.
- Removed `app/Resources/WebProfilerBundle/views/Collector/time.html.twig`.
