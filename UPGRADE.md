UPGRADE to 1.0.1 from 1.0.0
=======================

### General

  * Pull changes from repository
```bash
git pull
git checkout 1.0.1
```
  * Upgrade composer dependency
```bash
php composer.phar install --prefer-dist
```
  * Remove old caches and assets
```bash
rm -rf app/cache/*
rm -rf web/js/*
rm -rf web/css/*
```
  * Upgrade platform
```bash
php app/console oro:platform:update --env=prod

UPGRADE to 1.0.0 from 1.0.0-RC3
=======================

### General

  * Pull changes from repository
```bash
git pull
```
  * Upgrade composer dependency
```bash
php composer.phar update --prefer-dist
```
  * Remove old caches and assets
```bash
rm -rf app/cache/*
rm -rf web/js/*
rm -rf web/css/*
```
  * Upgrade platform
```bash
php app/console oro:platform:update --env=prod
```

UPGRADE to 1.0.0-RC2 from 1.0.0-RC1
=======================

### General

  * Pull changes from repository
```bash
git pull
```
  * Upgrade composer dependency
```bash
php composer.phar update --prefer-dist
```
  * Remove old caches
```bash
rm -rf app/cache/*
```
  * Fix extended entities configuration by executing below queries in mysql console.

delete FROM oro_entity_config_value where code = 'schema' and field_id is not null;
delete FROM oro_entity_config_value where field_id in (select id FROM oro_entity_config_field where field_name like 'field_%');
delete FROM oro_entity_config_field where field_name like 'field_%';
delete FROM oro_entity_config_value where code = 'set_options' and value = 'Array';

  * Update extend entities configuration
```bash
php app/console oro:entity-extend:update-config --env=prod
php app/console oro:entity-extend:dump --env=prod
```
  * Upgrade platform
```bash
php app/console oro:platform:update --env=prod
```
  * Load new fixtures
```bash
php app/console oro:installer:fixtures:load --env=prod
```
  * Load new workflows definitions
```bash
php app/console oro:workflow:definitions:load --env=prod
```

UPGRADE to any 1.0.0-alpha and beta version
=======================

### General

  * Upgrade to 1.0.0-alpha or beta is not supported and full reinstall with drop database, clear cache folders is required
  