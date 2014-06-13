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
  * Remove old caches and assets
```bash
rm -rf app/cache/*
rm -rf web/js/*
rm -rf web/css/*
```
  * Upgrade platform
```bash
php app/console oro:platform:update --env=prod

  