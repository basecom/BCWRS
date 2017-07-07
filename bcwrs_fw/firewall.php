<?php

require "./config.firewall.php";
require "geoip/geoip.inc";
require "./lib.firewall.php";


$bcwrs_client_info = array();

$bcwrs_client_info['block'] = false;
$bcwrs_client_info['block_cause'] = '';
$bcwrs_client_info['reference_code'] = uniqid('fw');
$bcwrs_client_info['request_time'] = $_SERVER['REQUEST_TIME'];
$bcwrs_client_info['remote_addr'] = bcwrs_ids_find_ip('123.111.133.153');
$bcwrs_client_info['request_uri'] = /*$_SERVER['REQUEST_URI']*/ '/wp-admin/admin.php';
list($bcwrs_client_info['country_code'], $bcwrs_client_info['country_name']) = bcwrs_ids_geolookup($bcwrs_client_info['remote_addr']);



if(false === empty($bcwrs_config['geoblock']['enable']))
{
    if(false === empty($bcwrs_client_info['country']['code']))
    {
        if(false === in_array($bcwrs_client_info['country']['code'], $bcwrs_config['geoblock']['country_whitelist']))
        {
            foreach($bcwrs_config['geoblock']['block_request_uri'] as $request_uri)
            {
                if(true === fnmatch($request_uri, $bcwrs_client_info['request_uri']))
                {
                    $bcwrs_client_info['block'] = true;
                    $bcwrs_client_info['block_cause'] = sprintf('Bad geo location: %s', $bcwrs_client_info['country']['name']);
                }
            }
        }
    }
    else
    {
        $bcwrs_client_info['block'] = $bcwrs_config['geoblock']['block_unknown'];
        $bcwrs_client_info['block_cause'] = 'Unknown geo location';
    }
}


if(true === $bcwrs_client_info['block'])
{
    $timezone = ini_get('date.timezone');
    if(true === empty($timezone))
    {
        date_default_timezone_set('Europe/Berlin');
    }

    $fh = fopen('report.csv', 'a');
    if(true === is_resource($fh))
    {
        fputcsv($fh, $bcwrs_client_info, ',', '""');
        fclose($fh);
    }

    header('Content-Type: text/plain;charset=utf-8');
    header('HTTP/1.0 403 Blocked by BCWRS Firewall');

    printf("*** Basecom Cyber Warfare Response Suite: Firewall ***\n");
    printf("    Incident time %s\n", date('Y-m-d H:i:s', $bcwrs_client_info['request_time']));
    printf("    Incident reference code %s \n\n", $bcwrs_client_info['reference_code']);
    printf("    You have been blocked from accessing this uri.\n");
    printf("    Reason: %s\n", $bcwrs_client_info['block_cause']);

    echo PHP_EOL;
    exit();
}