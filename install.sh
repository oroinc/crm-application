#!/bin/sh
php app/console doctrine:database:create
php app/console doctrine:schema:create
php app/console oro:search:create-index
php app/console doctrine:fixture:load --no-debug --no-interaction
php app/console oro:acl:load
php app/console oro:navigation:init
php app/console assets:install web
php app/console assetic:dump
php app/console oro:assetic:dump
