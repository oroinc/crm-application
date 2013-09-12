<?php

$aliasPath = __DIR__ . '/alias.yml';
if (file_exists($aliasPath)) {
    $aliases = \Symfony\Component\Yaml\Yaml::parse(file_get_contents(__DIR__ . '/alias.yml', FILE_USE_INCLUDE_PATH));

    if (is_array($aliases)) {
        foreach ($aliases as $className => $alias) {
            if (class_exists($className)) {
                class_alias($className, $alias);
            }
        }
    }
}
