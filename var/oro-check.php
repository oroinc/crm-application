<?php

require_once dirname(__FILE__) . '/OroRequirements.php';
require_once dirname(__FILE__) . '/../vendor/autoload.php';

$oroRequirements = new OroRequirements();

foreach ($oroRequirements->getRequirements() as $requirement) {
    /** @var Requirement $requirement */
    if (!$requirement->isFulfilled()) {
        echo $requirement->getTestMessage() . "\n";
    }
}
