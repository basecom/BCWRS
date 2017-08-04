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
    $gi = geoip_open("geoip/GeoIP.dat", GEOIP_STANDARD);

    $result = array(
        geoip_country_code_by_addr($gi, $remote_addr),
        geoip_country_name_by_addr($gi, $remote_addr)
    );

    geoip_close($gi);

    return $result;
}

function bcwrs_fw_input_analysis(&$input)
{
    $check = false;

    if(true === is_array($input))
    {
        foreach($input as &$item)
        {
            $check = bcwrs_fw_input_analysis($item);
        }
    }
    else
    {
        $check = bcwrs_fw_input_eval($input);

    }

    return $check;
}

function bcwrs_fw_input_eval($input)
{
    $result = array(
        (false === strpos($input, chr(0))),
        (false === strpos($input, '"'))
        (false === strpos($input, "'")),
        (false === strpos($input, "´")),
        (false === strpos($input, "`")),
        (false === strpos($input, "\b")),
        (false === strpos($input, "\n")),
        (false === strpos($input, "\r")),
        (false === strpos($input, "\t")),
        (false === strpos($input, "\Z")),
        (false === strpos($input, "\\"))
    );

    foreach($result as $item)
    {
        if(false === $item) return true;
    }

    return false;
}
