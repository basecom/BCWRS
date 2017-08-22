<?php

/**
 * Basecom Cyber Warfare Response Suite: Firewall
 * Library file / auxiliary functions
 */

if(false === function_exists('fnmatch'))
{
    function fnmatch($pattern, $string)
    {
        return preg_match("#^".strtr(preg_quote($pattern, '#'), array('\*' => '.*', '\?' => '.'))."$#i", $string);
    }
}


function bcwrs_fw_find_ip()
{
    $remote_addr = getenv('REMOTE_ADDR');

    if(true === empty($remote_addr) && false === empty($remote_addr['REMOTE_ADDR']))
    {
        $remote_addr = $_SERVER['REMOTE_ADDR'];
    }

    return $remote_addr;
}

function bcwrs_fw_geolookup($remote_addr)
{
    $gi = geoip_open(dirname(__FILE__) . "/geoip/GeoIP.dat", GEOIP_STANDARD);

    $result = array(
        geoip_country_code_by_addr($gi, $remote_addr),
        geoip_country_name_by_addr($gi, $remote_addr)
    );

    geoip_close($gi);

    return $result;
}

function bcwrs_fw_input_analysis(&$input, $blocklist = array())
{
    $check = false;

    if(true === is_array($input))
    {
        foreach($input as &$item)
        {
            $check = bcwrs_fw_input_analysis($item, $blocklist);
        }
    }
    else
    {
        $check = bcwrs_fw_input_eval($input, $blocklist);
    }

    return $check;
}

function bcwrs_fw_input_eval($input, $blocklist = array())
{
    $result = array();


    foreach($blocklist as $blockitem)
    {
        $result[] = (false === strpos($input, $blockitem));
    }

    foreach($result as $item)
    {
        if(false === $item) return true;
    }

    return false;
}

function bcwrs_fw_rule_uri_country_whitelist($request_uri, $country_whitelist = array(), $block_unknown_clients = true)
{
    global $bcwrs_client_info;

    $isWhitelisted = false;

    if(false === empty($bcwrs_client_info['country_code']))
    {
        $isWhitelisted = in_array($bcwrs_client_info['country_code'], $country_whitelist);
    }
    else
    {
        if(false === $block_unknown_clients)
        {
            $isWhitelisted = true;
        }
    }

    if(false === $isWhitelisted && true === fnmatch($request_uri, $bcwrs_client_info['request_uri']))
    {
        $bcwrs_client_info['block'] = true;
        $bcwrs_client_info['block_cause'] = sprintf('Bad or unknown geo location [%s]', $bcwrs_client_info['country_name']);
    }
}

function bcwrs_fw_rule_uri_deny($request_uri)
{
    global $bcwrs_client_info;

    if(true === fnmatch($request_uri, $bcwrs_client_info['request_uri']))
    {
        $bcwrs_client_info['block'] = true;
        $bcwrs_client_info['block_cause'] = sprintf('Bad request uri [%s]', $bcwrs_client_info['request_uri']);
    }
}

function bcwrs_fw_rule_input_scanner($input, $blocklist = array())
{
    global $bcwrs_client_info;

    if(true === bcwrs_fw_input_analysis($input, $blocklist))
    {
        $bcwrs_client_info['block'] = true;
        $bcwrs_client_info['block_cause'] = sprintf('Bad characters or strings in request input.');
    }
}

function bcwrs_fw_rule_deny_user_agent($user_agent)
{
    global $bcwrs_client_info;

    if(true === fnmatch($user_agent, $bcwrs_client_info['user_agent']))
    {
        $bcwrs_client_info['block'] = true;
        $bcwrs_client_info['block_cause'] = sprintf('Bad user agent [%s]', $bcwrs_client_info['user_agent']);
    }
}
