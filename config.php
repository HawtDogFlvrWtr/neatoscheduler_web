<?php
# Add additional facts you want to provide for configuration
$siteName = "Neato Scheduler";
$possibleActions = array('Clean', 'Clean House', 'Clean Spot', 'Clean Stop');
$timezone = "America/New_York";
$addFacts = array
(
  # Name on page, form info, placeholder, default value
  # Ex. array("NAMEONWEBPAGE, "FORMVALUENAME", "PLACEHOLDERNAME", "DEFAULTVALUE");
  #array("Test", "test", "Placeholder", "value"),
  #array("Test2", "test2", "Placeholder2", "value2")
);
# Directory Setup
$botsDir = 'db/botvacs/';
$lidarDir = 'db/lidar/';
$callbackDir = 'db/callback/';
$usersDir = 'db/usercreds/';
$actionsDir = 'db/actions/';
$schedulesDir = 'db/schedules/';

# CHANGE THIS TO ENSURE SESSION DATA IS DIFFERENT THAN ALL OTHER NEATO SITES
$sessionID = 'CHANGEME';
?>
