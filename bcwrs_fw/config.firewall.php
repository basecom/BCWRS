<?php

$bcwrs_config = array(
    'geoblock' => array(
        'enable' => true,
        'country_whitelist' => array('DE', 'AT', 'CH'),
        'block_unknown' => true,
        'block_request_uri' => array(
            '/admin/*',
            '/user/*',
            '/wp-login.*',
            '/wp-admin/*',
            '/wp-content/plugins/*'
        )
    ),
    'inputblock' => array(
        'enable' => true
    )
);
