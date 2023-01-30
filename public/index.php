<?php

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new AppKernel($context['ORO_ENV'], (bool) $context['ORO_DEBUG']);
};
