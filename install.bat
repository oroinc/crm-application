echo OFF
set ENV=prod
if "%1" NEQ "" (
    set ENV=%1
)
php app/console doctrine:schema:drop --no-debug --force --env %ENV%
php app/console doctrine:schema:create --no-debug --env %ENV%
php app/console oro:search:create-index --no-debug --env %ENV%
php app/console doctrine:fixture:load --no-debug --no-interaction --env %ENV%
php app/console oro:acl:load --no-debug --env %ENV%
php app/console oro:navigation:init --no-debug --env %ENV%
php app/console assets:install web --no-debug --env %ENV%
php app/console assetic:dump --no-debug --env %ENV%
php app/console oro:assetic:dump --no-debug