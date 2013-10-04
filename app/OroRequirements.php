<?php

require_once __DIR__ . '/SymfonyRequirements.php';

/**
 * This class specifies all requirements and optional recommendations that are necessary to run the Oro Application.
 */
class OroRequirements extends SymfonyRequirements
{
    public function __construct()
    {
        parent::__construct();

        $gdVersion   = defined('GD_VERSION') ? (float) GD_VERSION : null;
        $curlVersion = function_exists('curl_version') ? curl_version() : null;

        $this->addRequirement(
            null !== $gdVersion && $gdVersion >= 2.0,
            'GD extension must be at least 2.0',
            'Install and enable the <strong>JSON</strong> extension.'
        );

        $this->addRequirement(
            function_exists('mcrypt_encrypt'),
            'mcrypt_encrypt() should be available',
            'Install and enable the <strong>Mcrypt</strong> extension.'
        );

        $this->addRecommendation(
            class_exists('SoapClient'),
            'SOAP extension should be installed (API calls)',
            'Install and enable the <strong>SOAP</strong> extension.'
        );

        $this->addRecommendation(
            null !== $curlVersion && (float) $curlVersion['version'] >= 7.0,
            'cURL extension must be at least 7.0',
            'Install and enable the <strong>cURL</strong> extension.'
        );

        // Windows specific checks
        if (defined('PHP_WINDOWS_VERSION_BUILD')) {
            $this->addRecommendation(
                function_exists('finfo_open'),
                'finfo_open() should be available',
                'Install and enable the <strong>Fileinfo</strong> extension.'
            );

            $this->addRecommendation(
                class_exists('COM'),
                'COM extension should be installed',
                'Install and enable the <strong>COM</strong> extension.'
            );
        }

        $baseDir = realpath(__DIR__ . '/..');
        $mem     = $this->getBytes(ini_get('memory_limit'));

        $this->addPhpIniRequirement(
            'memory_limit',
            function ($cfgValue) use ($mem) {
                return $mem >= 256 * 1024 * 1024 || -1 == $mem;
            },
            false,
            'memory_limit should be at least 256M',
            'Set the "<strong>memory_limit</strong>" setting in php.ini<a href="#phpini">*</a> to at least "256M".'
        );

        $this->addRequirement(
            is_writable($baseDir . '/web/uploads'),
            'web/uploads/ directory must be writable',
            'Change the permissions of the "<strong>web/uploads/</strong>" directory so that the web server can write into it.'
        );

        $this->addRequirement(
            is_writable($baseDir . '/web/bundles'),
            'web/bundles/ directory must be writable',
            'Change the permissions of the "<strong>web/bundles/</strong>" directory so that the web server can write into it.'
        );
    }

    /**
     * Get the list of mandatory requirements (all requirements excluding PhpIniRequirement)
     *
     * @return array
     */
    public function getMandatoryRequirements()
    {
        return array_filter($this->getRequirements(), function ($requirement) {
            return !($requirement instanceof PhpIniRequirement);
        });
    }

    /**
     * Get the list of PHP ini requirements
     *
     * @return array
     */
    public function getPhpIniRequirements()
    {
        return array_filter($this->getRequirements(), function ($requirement) {
            return $requirement instanceof PhpIniRequirement;
        });
    }

    /**
     * @param  string $val
     * @return int
     */
    protected function getBytes($val)
    {
        if (empty($val)) {
            return 0;
        }

        preg_match('/([\-0-9]+)[\s]*([a-z]*)$/i', trim($val), $matches);

        if (isset($matches[1])) {
            $val = (int) $matches[1];
        }

        switch (strtolower($matches[2])) {
            case 'g':
            case 'gb':
                $val *= 1024;
                // no break
            case 'm':
            case 'mb':
                $val *= 1024;
                // no break
            case 'k':
            case 'kb':
                $val *= 1024;
                // no break
        }

        return (float) $val;
    }
}
