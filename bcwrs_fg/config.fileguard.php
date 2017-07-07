<?php

$config = array(

    // Enable FileGuard. Will exit if disabled.
    'fileguard_enable' => true,

    // Directory to index. Should be the root directory of the aplication you want to protect.
    'fileguard_directory' => '/var/www/vhosts/yoursite/httpdocs',

    // Only the following extensions will be indexed. Other files will be ignored.
    'watch_extensions' => array('php', 'php3', 'php4', 'php5', 'phtml'),

    // These files and directories will be excluded from indexing.
    'watch_exclusions' => array(),

    // Name of your site. Can be the domain or url. Change this to something meaningful or you won't be able to
    // tell from what site the reports come from.
    'report_name' => 'Yoursite',

    // The email address to send reports to. Leave blank to send no reports via email.
    'report_email' => 'support@basecom.de',

    // Path of the index file.
    'database_file' => './index.db'
);