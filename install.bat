echo OFF
set ENV=prod
if "%1" NEQ "" (
    set ENV=%1
)

php app/console oro:entity-extend:clear --env %ENV% || goto :error
php app/console doctrine:schema:drop --force --full-database --env %ENV% || goto :error
php app/console doctrine:schema:create --env %ENV% || goto :error
php app/console doctrine:fixture:load --no-debug --no-interaction --env %ENV% || goto :error
php app/console doctrine:fixtures:load --fixtures=vendor/oro/platform/src/Oro/Bundle/TestFrameworkBundle/Fixtures/ --append --no-debug --no-interaction --env  %ENV% || goto :error
php app/console oro:navigation:init --env %ENV% || goto :error
php app/console oro:entity-config:init --env %ENV% || goto :error
php app/console oro:entity-extend:init --env %ENV% || goto :error
php app/console oro:entity-extend:update-config --env %ENV% || goto :error
php app/console doctrine:schema:update --env %ENV% --force || goto :error
php app/console oro:search:create-index --env %ENV% || goto :error
php app/console assets:install web --env %ENV% || goto :error
php app/console assetic:dump --env %ENV% || goto :error
php app/console oro:assetic:dump --env %ENV% || goto :error
php app/console oro:translation:dump --env %ENV% || goto :error
goto :EOF

:error
echo Failed with error #%ERRORLEVEL%.
exit /b %ERRORLEVEL%
