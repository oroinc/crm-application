#!/bin/sh
ENV="prod"
if [ $1 ]
then
    ENV="$1"
fi
php app/console oro:entity-extend:clear --env $ENV
php app/console doctrine:schema:create --env $ENV
php app/console doctrine:fixture:load --no-debug --no-interaction --env $ENV
php app/console oro:entity-config:init --env $ENV
php app/console oro:entity-extend:init --env $ENV
php app/console oro:entity-extend:update-config --env $ENV
php app/console doctrine:schema:update --env $ENV --force
php app/console oro:acl:load --env $ENV
php app/console oro:navigation:init --env $ENV
php app/console oro:search:create-index --env $ENV
php app/console assets:install web --env $ENV
php app/console assetic:dump --env $ENV
php app/console oro:assetic:dump --env $ENV
php app/console oro:translation:dump --env $ENV