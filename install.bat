echo OFF
set ENV=prod
if "%1" NEQ "" (
    set ENV=%1
)
php app/console doctrine:schema:create --env %ENV% || goto :error
php app/console oro:search:create-index --env %ENV% || goto :error
php app/console doctrine:fixture:load --no-debug --no-interaction --env %ENV% || goto :error
php app/console oro:acl:load --env %ENV% || goto :error
php app/console oro:navigation:init --env %ENV% || goto :error
php app/console oro:entity-config:update --env %ENV% || goto :error
php app/console assets:install web --env %ENV% || goto :error
php app/console assetic:dump --env %ENV% || goto :error
php app/console oro:assetic:dump || goto :error
goto :EOF

:error
echo Failed with error #%ERRORLEVEL%.
exit /b %ERRORLEVEL%
