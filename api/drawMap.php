<?php

include('../config.php');
include('../functions.php');
ini_set('display_errors', 'On');
error_reporting(E_ALL);


if (isset($_GET['serial'])){
  if (file_exists("../".$lidarDir.$_GET['serial'].".json")){
	$lidarData = json_decode(file_get_contents("../".$lidarDir.$_GET['serial'].".json"), true);
	$lidarData = array_reverse(explode(",", $lidarData['lidar'])); // Flip array to pull correctly.
	// FIND LARGEST VALUE IN THE ARRAY
	$findMax = max($lidarData);
	$maxLocation = array_keys($lidarData, $findMax);
	if (file_exists("../".$lidarDir.$_GET['serial'].".last")) {
		$lastInitial = file_get_contents("../".$lidarDir.$_GET['serial'].".last");
		$lastInitial = explode(":", $lastInitial);
		$lastMax = $lastInitial[0];
		$lastLocation = $lastInitial[1];
		file_put_contents("../".$lidarDir.$_GET['serial'].".last", $findMax.":".$maxLocation[0]);
		$here = "top";
	} else {
		$here = "bottom";
		$lastMax = $findMax;
		$lastLocation = $maxLocation[0];
		file_put_contents("../".$lidarDir.$_GET['serial'].".last", $lastMax.":".$lastLocation);
	}
	if (count($maxLocation) > 1) {
		foreach($maxLocation as &$location) {
			if ($location >= $lastLocation - 10 || $location <= $lastLocation + 10) {
				$maxLocation = $location;
				break;
			}
		}
		$here = "top";
	} else {
		$here = "bottom";
		$maxLocation = $maxLocation[0];
	}
	$newDirection = $maxLocation - $lastLocation;
	$stamp = imagecreatefrompng('../images/neato.png');
	$im = imagecreatetruecolor(200, 200);
	$white  = imagecolorallocate($im,255,255,255);
	$blue = imagecolorallocate($im, 0, 51, 102);
	$blue1 = imagecolorallocate($im, 0, 76, 153);
	$blue2 = imagecolorallocate($im, 0, 102, 204);
	$purple = imagecolorallocate($im, 102, 102, 204);
	$purple1 = imagecolorallocate($im, 127, 0, 255);
	$purple2 = imagecolorallocate($im, 76, 0, 153);
	$pink = imagecolorallocate($im, 204, 0, 204);
	$pink1 = imagecolorallocate($im, 255, 0, 255);
	$pink2 = imagecolorallocate($im, 255, 0, 127);
	$gray = imagecolorallocate($im, 248,249,250);
	$black = imagecolorallocate($im, 0,0,0);
	$dotColor = $black;
	$divide = "";
	$divCount = 1;
	while ($divide == "") {
		if ($findMax / $divCount < 800) {
			$divide = $divCount;
			break;
		} else {
			$divCount++;
		}
	}
	$offsetX = 100;
	$offsetY = 100;
	$circleRadius = 70;
	$degrees = 0;
	if ($findMax == 0) {
		$bottomText = 'No data. (Device was reset)';
	} else {
		$bottomText = 'Scaled: '.$divide.'% ML:'.$maxLocation.' ND: '.$newDirection.' '.$here;
	}
	foreach($lidarData as &$r) {
		$distance = $r / $divide;
#		var_dump($distance);
		$x = round($distance * cos($degrees * M_PI / 180));
		$y = round($distance * sin($degrees * M_PI / 180));
		#echo "$r <br>";
		if ($r >= 2000 ) {
			$dotColor = $blue;
		} else if ($r <= 1999 && $r >= 1749 ) {
			$dotColor = $blue1;
		} else if ($r <= 1748 && $r >= 1498) {
			$dotColor = $blue2;
		} else if ($r <= 1497 && $r >= 1247) {
			$dotColor = $purple2;
		} else if ($r <= 1246 && $r >= 996) {
			$dotColor = $purple1;
		} else if ($r <= 995 && $r >= 745) {
			$dotColor = $purple;
		} else if ($r <= 744 && $r >= 494) {
			$dotColor = $pink;
		} else if ($r <= 493 && $r >= 243) {
			$dotColor = $pink1;
		} else {
			$dotColor = $pink2;
		}
		imagefill($im, 0, 0, $gray);
	        imagesetpixel($im, $x + $offsetX, $y + $offsetY, $dotColor);
	        imagesetpixel($im, $x + $offsetX-1, $y + $offsetY, $dotColor);
	        imagesetpixel($im, $x + $offsetX, $y + $offsetY-1, $dotColor);
	        #imagesetpixel($im, $x + $offsetX+1, $y + $offsetY, $dotColor);
	        #imagesetpixel($im, $x + $offsetX, $y + $offsetY+1, $dotColor);
		#imagefilter($im, IMG_FILTER_PIXELATE, 2);
		$degrees++;	
	}
	#imageflip($im, IMG_FLIP_HORIZONTAL);
	imagecopy($im, $stamp, 95, 95, 0, 0, 10, 10);
	$newImage = $im;
	#$newImage = imagerotate($im, $newDirection, $gray);
	imagestring($newImage, 2, 2, 187, $bottomText, $black);
	header("Content-Type: image/png");
	imagepng($newImage);
	imagedestroy($im);
  } else {
	echo "This device doesn't exist";
  }
}
?>
