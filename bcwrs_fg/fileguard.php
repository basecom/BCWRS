<?php 

/* ====================================================================
Basecom Cyber Warfare Response Suite 
FileGuard
Version 0.3
Written by Daniel Kollorz <kollorz@basecom.de>
==================================================================== */


chdir(dirname(__FILE__));
require dirname(__FILE__) . '/config.fileguard.php';
require dirname(__FILE__) . '/lib.fileguard.php';


if(true === empty($config['fileguard_enable']))
{
	print "FileGuard disabled by configuration.\n";
	exit();
}

ob_start();

$report = array();
$profiler_start = microtime(true);

$db = bcwrs_database_load($config['database_file']);
bcwrs_scan($db, $report, $config['fileguard_directory'], $config);
bcwrs_clean($db, $report, $config);
bcwrs_database_save($config['database_file'], $db);

$profiler_stop = microtime(true);


unset($db);

$profiler_diff = round(($profiler_stop-$profiler_start) / 1024, 2);
$memory_usage = round(memory_get_peak_usage() / 1024);

printf("*** Basecom Cyber Warfare Response Suite: FileGuard ***\n");
printf("    Report %s at %s\n", $config['report_name'], date('Y-m-d H:i:s', time()));
if(false === empty($report))
{
	printf("\n--> Reporting new files:\n");
	foreach($report as $r)
	{
		if($r['type'] == 'new') printf("%s\n", $r['path']);
	}
	
	printf("\n--> Reporting modified files:\n");
	foreach($report as $r)
	{
		if($r['type'] == 'modified') printf("%s\n", $r['path']);
	}
	
	printf("\n--> Reporting deleted files:\n");
	foreach($report as $r)
	{
		if($r['type'] == 'deleted') printf("%s\n", $r['path']);
	}
}
else
{
	printf("\n    Nothing to report.\n");
}

echo PHP_EOL;

printf("    Total time needed: %s ms\n", $profiler_diff);
printf("    Total memory usage: %s kBytes\n", $memory_usage);

echo PHP_EOL;


$ob = ob_get_contents();
file_put_contents(sprintf("./reports/report_%s.txt", date('Ymd_His', time())), $ob);

if(false === empty($report))
{
    if(false === empty($config['report_email']))
    {
        @mail($config['report_email'], sprintf('[FileGuard] Report for %s', $config['report_name']), $ob);
    }
}

ob_end_flush();
