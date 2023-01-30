<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

(new Dotenv('ORO_ENV', 'ORO_DEBUG'))
    ->setProdEnvs(['prod', 'behat_test'])
    ->bootEnv(dirname(__DIR__).'/.env-app', 'prod', ['test']);
