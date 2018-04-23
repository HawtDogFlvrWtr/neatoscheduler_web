<?php

include('../config.php');
include('../includes/functions.php');
ini_set('display_errors', 'On');
error_reporting(E_ALL);


if (isset($_GET['serial'])){
  if (file_exists("../".$lidarDir.$_GET['serial'].".json")){
	$stamp = imagecreatefrompng('../images/neato.png');
	$im = imagecreatetruecolor(200, 200);
	$white  = imagecolorallocate($im,255,255,255);
	$red = imagecolorallocate($im, 255, 0, 0);
	$blue = imagecolorallocate($im, 0, 0, 255);
	$gray = imagecolorallocate($im, 128,128,128);
	$black = imagecolorallocate($im, 0,0,0);
	$aqua = imagecolorallocate($im, 0,255,255);
	$green = imagecolorallocate($im, 0,153,0);
	$dotColor = $black;
	$lidarData = json_decode(file_get_contents("../".$lidarDir.$_GET['serial'].".json"), true);
	$lidarData = array_reverse(explode(",", $lidarData['lidar'])); // Flip array to pull correctly.
	// FIND LARGEST VALUE IN THE ARRAY
	$findMax = max($lidarData);
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
		$bottomText = 'Scaled: '.$divide.'%';
	}
	foreach($lidarData as &$r) {
		$distance = $r / $divide;
		$x = round($distance * cos($degrees * M_PI / 180));
		$y = round($distance * sin($degrees * M_PI / 180));
		imagefill($im, 0, 0, $white);
	        imagesetpixel($im, $x + $offsetX, $y + $offsetY, $dotColor);
	        imagesetpixel($im, $x + $offsetX-1, $y + $offsetY, $dotColor);
	        imagesetpixel($im, $x + $offsetX, $y + $offsetY-1, $dotColor);
		$degrees++;	
	}
	#imageflip($im, IMG_FLIP_HORIZONTAL);
	imagestring($im, 2, 0, 189, $bottomText, $black);
	imagecopy($im, $stamp, 95, 95, 0, 0, 10, 10);
	header("Content-Type: image/png");
	imagepng($im);
	imagedestroy($im);
  } else {
	echo "This device doesn't exist";
  }
}
?>
