<?php
include 'functions.php';
include 'config.php';

#ini_set('display_errors', 1); 
#ini_set('display_startup_errors', 1); 
#error_reporting(E_ALL);
# input information from form submit
if (count($_POST) > 0 && $_POST['serial']) {
  $jsonConfs = json_encode($_POST);
  $serial = $_POST["serial"];
  $cleanMac = str_replace(":", "", $serial);
  file_put_contents($cleanMac.".json", $jsonConfs);
}

# Get system information
if (isset($_GET["serial"])) {
  # Get Mac Address
  $serial = $_GET["serial"];
  $addProps = getAdditionals();
  $jsonConfs = json_encode($addProps);
  # Open new file if it doesn't exists, remoserialg the colon's from the file name
  if (file_exists($botsDir.$serial.".json") && count($addProps) <= 1){
    $fileContent = file_get_contents($botsDir.$serial.".json", true);
    # convert to array
    $jsonArrayBase = json_decode($fileContent, true);
    header('Content-Type: application/json');
    echo prettyPrint(stripslashes(json_encode($jsonArrayBase)));
  } else {
    echo "This BotVac's configuration doesn't exist.<br>";
    echo printHelp();
  }
# Get specific user information as json
} else if (isset($_GET["username"]) && $_GET['username'] != 'admin') {
  # Get username 
  $username = $_GET["username"];
  # Open new file if it doesn't exists, remoserialg the colon's from the file name
  if (file_exists($usersDir.$username.".json") && count($addProps) <= 1){
    $fileContent = file_get_contents($usersDir.$username.".json", true);
    header('Content-Type: application/json');
    echo prettyPrint(stripslashes($fileContent));
  } else {
    echo "This user doesn't exist.<br>";
    echo printHelp();
  }
# Get all users in one json return
} else if (isset($_GET['allusers'])){
  $userArray = array();
  $files = glob($usersDir.'*.{json}', GLOB_BRACE);
  foreach($files as $file) {
    # Skip the admin user, as it's only for web access
    if ($file !== $usersDir.'admin.json') {
      $jsonDecode = json_decode(file_get_contents($file), true);
      $userArray[] = $jsonDecode;
    }
  }
  header('Content-Type: application/json');
  echo prettyPrint(stripslashes(json_encode($userArray)));
} else {
  echo printHelp();
}
?>
