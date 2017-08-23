<?php

/**
 * Basecom Cyber Warfare Response Suite: Firewall
 * Firewall rules
 */


/** @var $country_whitelist Whitelist array featuring ISO-3166-2 country codes. */
$country_whitelist = array('DE', 'AT', 'CH');

/** @var $block_unknown_clients Block clients that cannot be geo located. */
$block_unknown_clients = true;

/** @var $block_input Scan all input and look for these chars. Block request if found. */
$block_input = array(chr(0), '"', "'", "´", "`","\b","\n","\r", "\t", "\Z", "\\");


/**
 * bcwrs_fw_rule_uri_country_whitelist()
 * Use this function to deny access to URIs if the client is not located in the whitelisted country.
 * Request URIs will be matched using fnmatch()
 */
bcwrs_fw_rule_uri_country_whitelist('/admin/*', $country_whitelist, $block_unknown_clients);
bcwrs_fw_rule_uri_country_whitelist('/user/*', $country_whitelist, $block_unknown_clients);
bcwrs_fw_rule_uri_country_whitelist('/wp-admin/*', $country_whitelist, $block_unknown_clients);
bcwrs_fw_rule_uri_country_whitelist('/wp-login.*', $country_whitelist, $block_unknown_clients);
bcwrs_fw_rule_uri_country_whitelist('/wp-content/plugins/*', $country_whitelist, $block_unknown_clients);

/**
 * bcwrs_fw_rule_upload_country_whitelist()
 * Use this function to deny uploads from clients which are not located within the whitelisted countries.
 */
bcwrs_fw_rule_upload_country_whitelist($country_whitelist, $block_unknown_clients);

/**
 * bcwrs_fw_rule_uri_deny()
 * Use this function to deny access to URIs completely.
 * Request URIs will be matched using fnmatch()
 */
bcwrs_fw_rule_uri_deny('/wp-content/uploads/*');

/**
 * bcwrs_fw_rule_input_scanner()
 * Scan global input arrays for given characters. Block client if given characters are found.
 */
bcwrs_fw_rule_input_scanner($_GET, $block_input);

/**
 * bcwrs_fw_rule_deny_user_agent()
 * Block clients with given user agents.
 */
bcwrs_fw_rule_deny_user_agent('Java/*');
bcwrs_fw_rule_deny_user_agent('Mozilla');
bcwrs_fw_rule_deny_user_agent('*spider*');
bcwrs_fw_rule_deny_user_agent('*compatible ;*');

