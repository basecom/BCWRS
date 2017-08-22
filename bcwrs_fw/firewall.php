<?php

/**
 * Basecom Cyber Warfare Response Suite: Firewall
 * Main file
 */

require dirname(__FILE__) . "/geoip/geoip.inc";
require dirname(__FILE__) . "/lib.firewall.php";


$bcwrs_client_info = array();

$bcwrs_client_info['block'] = false;
$bcwrs_client_info['block_cause'] = '';
$bcwrs_client_info['reference_code'] = uniqid('fw');
$bcwrs_client_info['request_time'] = $_SERVER['REQUEST_TIME'];
$bcwrs_client_info['remote_addr'] = bcwrs_fw_find_ip();
$bcwrs_client_info['request_uri'] = $_SERVER['REQUEST_URI'];
$bcwrs_client_info['user_agent'] = getenv('HTTP_USER_AGENT');
list($bcwrs_client_info['country_code'], $bcwrs_client_info['country_name']) = bcwrs_fw_geolookup($bcwrs_client_info['remote_addr']);

require dirname(__FILE__) . "/ruleset.firewall.php";

if(true === $bcwrs_client_info['block'])
{
    $timezone = ini_get('date.timezone');
    if(true === empty($timezone))
    {
        date_default_timezone_set('Europe/Berlin');
    }

    /*$fh = fopen('report.csv', 'a');
    if(true === is_resource($fh))
    {
        fputcsv($fh, $bcwrs_client_info, ',', '"');
        fclose($fh);
    }*/

    header('Content-Type: text/plain;charset=utf-8');
    header('HTTP/1.0 403 Blocked by BCWRS Firewall');

    printf("*** Basecom Cyber Warfare Response Suite: Firewall ***\n");
    printf("    Incident time %s\n", date('Y-m-d H:i:s', $bcwrs_client_info['request_time']));
    printf("    Incident remote address %s\n", $bcwrs_client_info['remote_addr']);
    printf("    Incident request uri %s\n", $bcwrs_client_info['request_uri']);
    printf("    Incident reference code %s \n\n", $bcwrs_client_info['reference_code']);
    printf("    You have been blocked from accessing this uri.\n");
    printf("    Reason: %s\n", $bcwrs_client_info['block_cause']);

    echo PHP_EOL;
    exit();
}