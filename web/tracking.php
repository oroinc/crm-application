<?php
/**
 * This is tracking endpoint, which must be as fast as possible.
 * KISS.
 */

$trackingFolder = '../app/logs/tracking';
$settingsFile   = $trackingFolder . DIRECTORY_SEPARATOR . 'settings.ser';
$settings       = [
    'dynamic_tracking_enabled'  => true,
    'dynamic_tracking_endpoint' => '/tracking/data/create',
    'log_rotate_interval'       => 60,
    'piwik_host'                => null,
    'piwik_token_auth'          => null
];

/**
 * Pass request to given URL.
 *
 * @param string $url
 */
function passDataToUrl($url)
{
    // Send visit to new URL
    $handle = curl_init();
    curl_setopt($handle, CURLOPT_URL, modifyUrl($url));
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
    curl_exec($handle);
    curl_close($handle);
}

/**
 * @param string $url
 *
 * @return string
 */
function modifyUrl($url)
{
    if (strpos($url, 'http') !== 0) {
        $schema = 'http';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            $schema .= 's';
        }
        $url = $schema . '://' . $_SERVER['HTTP_HOST'] . $url;
    }
    // Pass request data to new URL
    $delimiter = strpos($url, '?') === false ? '?' : '&';
    $url .= $delimiter . $_SERVER['QUERY_STRING'];

    // Set visit date time
    $url .= '&loggedAt=' . urlencode(getLoggedAt());

    // Set visit IP address
    $url .= '&cip=' . urlencode(getClientIp());

    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        $url .= '&ua=' . urlencode($_SERVER['HTTP_USER_AGENT']);
    }
    if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $url .= '&lang=' . urlencode($_SERVER['HTTP_ACCEPT_LANGUAGE']);
    }

    return $url;
}

function getClientIp()
{
    // Set correct visitor information
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}

function passDataToApplication($url)
{
    $_SERVER['REQUEST_URI'] = modifyUrl($url);

    $_GET['loggedAt'] = getLoggedAt();
    $_GET['cip']      = getClientIp();
    $_GET['ua']       = $_SERVER['HTTP_USER_AGENT'];

    require_once __DIR__ . '/../app/bootstrap.php.cache';
    require_once __DIR__ . '/../app/AppKernel.php';
    $kernel = new AppKernel('prod', false);
    $kernel->loadClassCache();
    $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

    $kernel->handle($request);
}

/**
 * Get log datetime
 *
 * @return string
 */
function getLoggedAt()
{
    $now = new \DateTime('now', new \DateTimeZone('UTC'));

    return $now->format(\DateTime::ISO8601);
}

// Ensure tracking directory exists and read settings
if (is_dir($trackingFolder)) {
    if (is_readable($settingsFile)) {
        $settings = unserialize(file_get_contents($settingsFile));
    }
} else {
    mkdir($trackingFolder);
}

// Track visit
if ($settings['dynamic_tracking_enabled']) {
    // Pass visit to dynamic tracking endpoint
    passDataToApplication($settings['dynamic_tracking_endpoint']);
} else {
    // Calculate interval part
    $rotateInterval = 60;
    $currentPart    = 1;
    if ($settings['log_rotate_interval'] > 0 && $settings['log_rotate_interval'] < 60) {
        $rotateInterval = (int)$settings['log_rotate_interval'];
        $passingMinute  = (int)(date('i')) + 1;
        $currentPart    = ceil($passingMinute / $rotateInterval);
    }

    // Construct file name
    $date     = new \DateTime('now', new \DateTimeZone('UTC'));
    $fileName = $date->format('Ymd-H') . '-' . $rotateInterval . '-' . $currentPart . '.log';

    // Add visit to log to file
    $rawData             = $_GET;
    $rawData['loggedAt'] = getLoggedAt();
    $data                = json_encode($rawData) . PHP_EOL;
    $fh                  = fopen($trackingFolder . DIRECTORY_SEPARATOR . $fileName, 'a');
    if (flock($fh, LOCK_EX)) {
        fwrite($fh, $data);
        fflush($fh);
        flock($fh, LOCK_UN);
    }
    fclose($fh);
}

// Pass tracking request to piwik instance
if ($settings['piwik_host']) {
    $piwikTrackingUrl = $settings['piwik_host'] . '/piwik.php';
    if ($settings['piwik_token_auth']) {
        $piwikTrackingUrl .= '?token_auth=' . urlencode($settings['piwik_token_auth']);
    }

    passDataToUrl($piwikTrackingUrl);
}

//Disable XSS Auditor
header('X-XSS-Protection: 0');
//Send 1x1 blank gif
header('Content-Type: image/gif');
echo base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==');
