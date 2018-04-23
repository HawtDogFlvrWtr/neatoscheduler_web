<?php
error_reporting(E_ALL);
ini_set('display_errors', 1); 
include('../config.php');
	
	$schedule_date = new DateTime(null, new DateTimeZone('UTC'));
        $schedule_date->setTimeZone(new DateTimeZone($timezone));
        $time = $schedule_date->format('N, G, i, s');
	echo $time;
?>
