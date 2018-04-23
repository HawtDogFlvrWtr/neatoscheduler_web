<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$addedPath = '/var/www/neato/neato/';
// Access DB Info
include($addedPath.'config.php');

// Include Functions
include($addedPath.'functions.php');
date_default_timezone_set($timezone);

// Get timezone crap
$currentTime = gmdate('Y-m-d H:i:s');
// get current actions
$actionArray = array();
$files = glob($addedPath.$schedulesDir.'*.{json}', GLOB_BRACE);
$actionCount = 1;
    foreach($files as $file) {
	$jsonInfo = file_get_contents($file);
	$queryArray = json_decode($jsonInfo, true);
	$randomID = generateRandomString(10);
	$timeNow = time();
	$daysoweek = array();
	$days = explode(',',$queryArray['date']);
	$fileName = $addedPath.$actionsDir.$timeNow.'-'.$randomID.'.json';
       	if ($days[0] == 1) { array_push($daysoweek, 'sun'); }
        if ($days[1] == 1) { array_push($daysoweek, 'mon'); }
        if ($days[2] == 1) { array_push($daysoweek, 'tue'); }
        if ($days[3] == 1) { array_push($daysoweek, 'wed'); }
        if ($days[4] == 1) { array_push($daysoweek, 'thu'); }
        if ($days[5] == 1) { array_push($daysoweek, 'fri'); }
        if ($days[6] == 1) { array_push($daysoweek, 'sat'); }
	$currentDay = strtolower(date('D'));
	
	$eventTime = $queryArray['hour'].":".$queryArray['minute'];

	$schedule_date = new DateTime($currentTime, new DateTimeZone('UTC'));
	$schedule_date->setTimeZone(new DateTimeZone($timezone));
	$time = $schedule_date->format('H:i');
	echo $actionCount.") Now: ".$currentDay." @ ".$time." Scheduled: ".join(",", $daysoweek)." @ ".$eventTime."\n";
	
	// Compare and kickoff if need be.
	if (in_array($currentDay, $daysoweek) && $eventTime != $time) {
		echo "(Day is right, but time isn't)\n";
	} else if ($eventTime == $time && in_array($currentDay, $daysoweek)) {
		$txt = "(Performing action '".$queryArray['action']."' at ".$eventTime.".)\n";
                $actionArray['action'] = $queryArray['action'];
                $actionArray['serial'] = $queryArray['serial'];
		$actionArray['time'] = $timeNow;
		$actionArray['scheduled'] = 1;
                $jsonConfs = json_encode($actionArray);
		file_put_contents('/tmp/neatoRun', $txt, FILE_APPEND);
		# Touch file and change permissions before writing to it.
		file_put_contents($fileName, $jsonConfs);
		chown($fileName, 'apache');
		chgrp($fileName, 'apache');
	} else {
		echo "(Wrong day and time.)\n";
	}
	$actionCount++;
    }
?>
