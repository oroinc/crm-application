UPGRADE FROM 1.8 to 1.9
=======================

## app/config/config.yml

- Removed `doctrine` section. From now this declaration is located in `OroPlatformBundle` bundle. Remove declaration of DBAL connections and ORM entity managers from your `app/config/config.yml` to be sure that an application will work properly.
- Removed unused `report_source` and `report_target` DBAL connections.
- Added `config` DBAL connection and ORM entity manager. They can be used as a gateway for different kind of configuration data to improve performance of the default ORM entity manager. For example `OroEntityConfigBundle` uses them for entity configuration data.
- Removed support PHP version below 5.5.9
