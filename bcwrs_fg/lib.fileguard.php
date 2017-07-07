<?php

function bcwrs_scan(&$db, &$report, $directory, $config)
{
	$dirlist = scandir($directory);
	foreach($dirlist as $diritem)
	{
		$continue = false;
		
		if($diritem == '.' || $diritem == '..') $continue = true;
		
		foreach($config['watch_exclusions'] as $exclusion)
		{
			if($diritem == $exclusion) $continue = true;
		}
		
		if($continue) continue;

		$current_path = $directory . DIRECTORY_SEPARATOR . $diritem;

		if(true === is_dir($current_path))
		{
			bcwrs_scan($db, $report, $current_path, $config);
		}
		else
		{
			$current_ext = pathinfo($current_path, PATHINFO_EXTENSION);
			if(false === in_array($current_ext, $config['watch_extensions'])) continue;

			$current_key = sha1($current_path);
			$current_hash = sha1_file($current_path);
			$current_size = filesize($current_path);
			if(false === empty($db[$current_key]))
			{
				if($db[$current_key]['hash'] != $current_hash)
				{
					$report[] = array('type' => 'modified', 'path' => $current_path, 'filesize_now' => $current_size, 'filesize_prev' => $db[$current_key]['size']);
				}
			}
			else 
			{
				$report[] = array('type' => 'new', 'path' => $current_path, 'filesize_now' => $current_size, 'filesize_prev' => 0);
			}
				
			$db[$current_key] = array('path' => $current_path, 'hash' => $current_hash, 'hashtime' => time(), 'size' => $current_size);
		}
	}
}

function bcwrs_clean(&$db, &$report, $config)
{
	foreach($db as $key => &$dbitem)
	{
		if(false === file_exists($dbitem['path']))
		{
			$report[] = array('type' => 'deleted', 'path' => $dbitem['path'], 'filesize_now' => 0, 'filesize_prev' => $dbitem['size']);
			unset($db[$key]);
		}
	}
}

function bcwrs_database_load($file)
{
	if(false === file_exists($file))
	{
		file_put_contents($file, serialize(array()));
	}

	$content = file_get_contents($file);
	$db = unserialize($content);
	

	return $db;
}

function bcwrs_database_save($file, $db)
{
	$content = serialize($db);
	file_put_contents($file, $content);

	return $db;
}