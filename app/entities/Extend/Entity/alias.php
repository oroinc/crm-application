<?php

$aliases = \Symfony\Component\Yaml\Yaml::parse(file_get_contents(__DIR__ . '/alias.yml'));

if (is_array($aliases)) {
    foreach ($aliases as $className => $alias) {
        if (class_exists($className)) {
            class_alias($className, $alias);
        }
    }
}
