=======================

### General

  * Pull changes from repository
```bash
git pull
git checkout <VERSION TO UPGRADE>
```
  * Upgrade composer dependency
```bash
php composer.phar install --prefer-dist
```
  * Disable APC, OpCache, other code accelerators
  * Remove old caches and assets
```bash
rm -rf var/cache/*
rm -rf public/js/*
rm -rf public/css/*
```
  * Upgrade platform
```bash
php bin/console oro:platform:update --env=prod

  