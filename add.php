<?php
include 'header.php';
#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);
#error_reporting(E_ALL);
$queryArray = array();
$currentUser = $_SESSION['login_user_'.$sessionID];
# Generate form information if serial provided
# input information from form submit
if (count($_POST) > 0 && $_POST['serial']) {
  $_POST['owner'] = $currentUser;
  $jsonConfs = json_encode($_POST);
  if (! file_exists($botsDir.$_POST['serial'].".json", $jsonConfs)) {
    if(file_put_contents($botsDir.$_POST['serial'].".json", $jsonConfs)) {
      msgBox("BotVac information saved.", "success");
    } else {
      msgBox("BotVac information NOT saved. Are you trying to be naughty?", "danger");
    }
    header("Location: allBots.php");
    die();
  } else {
    msgBox("This device already exists.", "danger");
    header("Location: allBots.php");
    die();
  }
}
if (isset($_GET['serial'])){
  if (file_exists($botsDir.$_GET['serial'].".json")) {
    if ( isset($_GET['delete'])) {
      if (unlink($botssDir.$_GET['serial'].".json")) {
        msgBox("This BotVac configuration was deleted.", "success");
      } else {
        msgBox("This BotVac configuration was NOT deleted. Please try again.", "danger");
      }
    } else {
      $jsonInfo = file_get_contents($botsDir.$_GET['serial'].".json");
      $queryArray = json_decode($jsonInfo, true);
    }
  } else {
    $queryArray['serial'] = $_GET['serial'];
     msgBox("This BotVac doesn't have configuration information. You can add it below.", "danger");
  }
}
?>
<?php 
if ($_SESSION['msgBox'] != "") {
  echo '<div class="red-text container">';
  echo $_SESSION['msgBox'];
  echo '</div>';
  $_SESSION['msgBox'] = "";
}

?>
<div class="container container-header border rounded bg-light">
<h1>Insert or Update</h1>
<p>This page allows you to add new BotVac's, or if you've searched your serial with the search button above, or clicked edit from the <a href="allBots.php">All BotVacs</a> page, you will be able to modify it's information.</p>
<form method="post" action="add.php?<?php if (isset($queryArray['serial'])) { echo 'serial='.$queryArray['serial']; }?>"> 
  <div class="form-row">
    <div class="form-group col-md-2">
      <?php
       if (isset($queryArray['serial'])) {
         $readonly = "readonly";
       } else {
         $readonly = "";
       }
      ?>
    <label>Serial<input <?php echo $readonly; ?> class="form-control" type="text" id="serial" name="serial" value="<?php if (isset($queryArray['serial'])) { echo $queryArray['serial']; }?>"></label>
    </div>
    <div class="form-group col-md-2">
      <label>Model<input  class="form-control" type="text" id="model" name="model" value="<?php if (isset($queryArray['model'])) { echo $queryArray['model']; }?>"></label>
    </div>
    <div class="form-group col-md-2">
      <label>Description<input class="form-control" type="text" id="description" name="description" value="<?php if (isset($queryArray['description'])) { echo $queryArray['description']; }?>"></label>
    </div>
    <?php
      # Adding additional facts that exist in the config.php
      $factCount = count($addFacts);
      for ($row = 0; $row < $factCount; $row++) {
        # Use the previously saved value if it exists
        if (isset($queryArray[$addFacts[$row][1]])) { 
          $factValue = $queryArray[$addFacts[$row][1]]; 
        # User the default value if one exists and isn't empty
        } else if (!isset($queryArray[$addFacts[$row][1]]) && $addFacts[$row][3] != ''){
          $factValue = $addFacts[$row][3];
        } else {
          $factValue = '';
        }
        echo '<div class="form-group col-md-2">';
        echo '  <label>'.$addFacts[$row][0].'<input placeholder="'.$addFacts[$row][2].'" class="form-control" type="text" name="'.$addFacts[$row][1].'" value="'.$factValue.'"></label>';
        echo '</div>';
      }
    ?>
  </div>
  <input class="btn btn-success" type="submit" name="" value="Submit">
</form> 
</div>
<?php include 'footer.php';?>
