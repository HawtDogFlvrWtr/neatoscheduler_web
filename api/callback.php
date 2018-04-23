<?php
error_reporting(E_ALL);
ini_set('display_errors', 1); 
include('../config.php');

function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_,', '+/='));
}
# Pull api and device information
if (isset($_GET['serial']) and !isset($_POST['lidar'])){
	if (file_exists("../".$botsDir.$_GET['serial'].".json")) {
		$sysInfo = json_decode(file_get_contents("../".$botsDir.$_GET['serial'].".json"), true);
		$newInfo = [];
        	$newInfo['ping'] = time();
		$newInfo['ip'] = $_SERVER['REMOTE_ADDR'];
	        if (isset($_GET['firmware'])) {
			$newInfo['firmware'] = $_GET['firmware'];
	        } else {
			$newInfo['firmware'] = Null;
		}
		if (isset($_GET['battery'])) {
			$batteryCharge = explode(',', base64_url_decode($_GET['battery']));
			$newInfo['battery'] = $batteryCharge[0];
			$newInfo['batteryTemp']  = $batteryCharge[4];
			$newInfo['batteryFailure']  = $batteryCharge[3];
			$newInfo['chargeActive']  = $batteryCharge[1];
			$newInfo['chargeEnabled']  = $batteryCharge[2];
		} else {
			$newInfo['battery'] = Null;
			$newInfo['batteryTemp']  = Null;
			$newInfo['batteryFailure']  = Null;
			$newInfo['chargeActive']  = Null;
			$newInfo['chargeEnabled'] = Null;
		}
		if (isset($_GET['errorMsg'])) {
			$newInfo['errorMsg'] = base64_url_decode($_GET['errorMsg']);
			if ($newInfo['errorMsg'] == "\n") {
				$newInfo['errorMsg'] = Null;
			}
		} else {
			$newInfo['errorMsg'] = Null;
		}
		$newSysInfo = array_merge($sysInfo, $newInfo);
		file_put_contents("../".$botsDir.$_GET['serial'].".json", json_encode($newSysInfo));
		#var_dump($newSysInfo);
	} else {
		echo "This botvac doesn't exist";
	}
} else if (isset($_GET['serial']) && isset($_POST['lidar'])) {
	if (file_exists("../".$botsDir.$_GET['serial'].".json")) {
		file_put_contents("../".$lidarDir.$_GET['serial'].".json", json_encode($_POST));
		# Check if we have actions
		$files = glob("../".$actionsDir.'*.{json}', GLOB_BRACE);
		$serial = $_GET['serial'];
		foreach($files as $file) {
		    $jsonInfo = file_get_contents($file);
		    $queryArray = json_decode($jsonInfo, true);
		    if ($queryArray['serial'] == $serial && !isset($queryArray['complete'])) {
		      $queryArray['complete'] = 1;
		      $newJson = json_encode($queryArray);
      		      file_put_contents($file, $newJson);
		      echo $queryArray['action'];
		      break;
		    }
		}
	} else {
		echo "This boxvac doesn't exist";
	}
} else {
        echo "You're missing some information";
}
?>
