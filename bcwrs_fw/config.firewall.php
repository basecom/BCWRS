<?php

/**
 * Basecom Cyber Warfare Response Suite: Firewall
 * Configuration file
 */

$bcwrs_config = array(

    // Configuration for geo blocking
    'geoblock' => array(

        // Enable blocking by geo location
        'enable' => true,

        // Whitelist these countries
        'country_whitelist' => array('DE', 'AT', 'CH'),

        // Whitelist these ip addresses
        'remote_address_whitelist' => array('195.50.152.102'),

        // Block client if the geo location is unknown
        'block_unknown' => true,

        // Block the client if the request uri contains these strings and geo location is not on whitelist.
        // The php function fnmatch() is used for matching.
        'block_request_uri' => array(
            '/admin/*',
            '/user/*',
            '/wp-login.*',
            '/wp-admin/*',
            '/wp-content/plugins/*'
        )
    ),

    // Block all bad input on $_GET, $_POST, $_COOKIE and $_REQUEST.
    'inputblock' => array(

        // Enable input blocking
        'enable' => true
    )
);
