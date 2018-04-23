<?php 
include 'header.php';
#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);
#error_reporting(E_ALL);
$row = 1;

# Add scheduled event
if (isset($_POST['submit']) && $_POST['submit'] == "addschaction" && isset($_POST['device']) && isset($_POST['action']) && isset($_POST['hour']) && isset($_POST['minute']) && isset($_POST['note'])){
	$actionArray = array();
        $vid = $_POST['device'];
        $action = $_POST['action'];
        $hour = $_POST['hour'];
        $minute = $_POST['minute'];
        if (isset($_POST['note'])){
                $note = $_POST['note'];
        } else {
                $note = "";
        }
        if (isset($_POST['runonce']) && $_POST['runonce'] == "on"){
                $runonce = 1;
        } else {
                $runonce = 0;
        }
        $dateArray = array();
        if (isset($_POST['sunday']) && $_POST['sunday'] == "on"){
                array_push($dateArray, 1);
        } else {
                array_push($dateArray, 0);
        }
        if (isset($_POST['monday']) && $_POST['monday'] == "on"){
                array_push($dateArray, 1);
        } else {
                array_push($dateArray, 0);
        }
        if (isset($_POST['tuesday']) && $_POST['tuesday'] == "on"){
                array_push($dateArray, 1);
        } else {
                array_push($dateArray, 0);
        }
        if (isset($_POST['wednesday']) && $_POST['wednesday'] == "on"){
                array_push($dateArray, 1);
        } else {
                array_push($dateArray, 0);
        }
        if (isset($_POST['thursday']) && $_POST['thursday'] == "on"){
                array_push($dateArray, 1);
        } else {
                array_push($dateArray, 0);
        }
        if (isset($_POST['friday']) && $_POST['friday'] == "on"){
                array_push($dateArray, 1);
        } else {
                array_push($dateArray, 0);
        }
        if (isset($_POST['saturday']) && $_POST['saturday'] == "on"){
                array_push($dateArray, 1);
        } else {
                array_push($dateArray, 0);
        }
        $dateArray = implode(',',$dateArray);
                $getDeviceActions = ['Clean', 'Clean House', 'Clean Spot'];
                if (in_array($action, $getDeviceActions) === false) {
                        msgBox("This isn't a valid action type for this device. Are you trying to be sneaky?", "<i class='fa fa-check-square'></i>", "danger");
                } else {
			$randomID = generateRandomString(10);
      			$actionArray['action'] = $action;
			$actionArray['serial'] = $vid;
			$actionArray['description'] = $note;
			$actionArray['date'] = $dateArray;
			$actionArray['hour'] = $hour;
			$actionArray['minute'] = $minute;
		        $jsonConfs = json_encode($actionArray);
		        if(file_put_contents($schedulesDir.$randomID.".json", $jsonConfs)) {
			        msgBox("Botvac has been scheduled to ".$_GET['action'], "success");
		        } else {
			        msgBox("Botvac schedule NOT saved. Please check permissions.", "danger");
			}
                        header( 'Location: allSchedules.php' );
                }
}


# Delete Record
if (isset($_GET['id'])){
  if (file_exists($schedulesDir.$_GET['id'].".json")) {
    if ( isset($_GET['delete'])) {
      if (unlink($schedulesDir.$_GET['id'].".json")) {
        msgBox("Scheduled action was deleted successfully.", "success");
      } else {
        msgBox("Scheduled action wasn't deleted. There was an issue deleting the information, or the schedule no longer exists. Please try again.", "danger");
      }
    }
  }
}
# Get list of users, This after the delete statement above, so it updates the page on post.
$files = glob($schedulesDir.'*.{json}', GLOB_BRACE|GLOB_NOSORT);
if ($_SESSION['msgBox'] != "") {
  echo '<div class="red-text container">';
  echo $_SESSION['msgBox'];
  echo '</div>';
  $_SESSION['msgBox'] = "";
}
?>
<div class="container-margin container border rounded bg-light">
<h1>Scheduled Events</h1>
	<a style="font-style:italic;color:black;float:right;margin-top:-40px;" href="pushSchActionsNew.php" data-toggle="modal" title="Schedule A New Action" data-target="#actionmodal"><i class="text-primary fa fa-2x fa-calendar-plus"></i></a>
<p>These are the current schedules that you have configuration information for. You can edit or delete any record.</p>
<?php 
if (count($files) > 0) {
?>
<table class="table rwd-table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">BotVac Information</th>
      <th scope="col">Time</th>
      <th scope="col">Days</th>
      <th scope="col">Description</th>
      <th scope="col">Options</th>
    </tr>
  </thead>
  <tbody>
<?php
   foreach($files as $file) {
     $jsonDecode = json_decode(file_get_contents($file), true);
     $id = end(explode("/", explode(".", $file)[0]));
     $jsonDecode['date'] = convertDates($jsonDecode['date']);
     $botInfo = json_decode(file_get_contents($botsDir.$jsonDecode['serial'].".json"),true);
   
     echo '<tr>';
     echo '<td data-th="Info">'.$botInfo['description'].' - '.$botInfo['model'].'</td>';
     echo '<td data-th="Time">'.$jsonDecode['hour'].':'.$jsonDecode['minute'].'</td>';
     echo '<td data-th="Days">'.$jsonDecode['date'].'</td>';
     echo '<td data-th="Desc">'.$jsonDecode['description'].'</td>';
     echo '<td data-th="Options">
	<a data-toggle="modal" href="#delete'.$row.'" class="btn btn-danger btn-icon" data-dismiss="modal"><i class="fa fa-trash-alt"></i></a>
     ';
     echo '</td>';
     echo '</tr>';
     echo '<div id="delete'.$row.'" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
             <div class="modal-dialog">
               <div class="modal-content">
                 <form action="allSchedules.php" method="get">
                   <div class="modal-body">
                     <p class="lead">Are you sure you want to remove this scheduled task?</p>
                   </div>
                   <div class="modal-footer">
                     <button type="input" name="id" value="'.$id.'" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> Yes, Remove It</button>
                     <input type="hidden" name="delete">
                     <button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> Cancel</button>
                   </div>
                 </form>
               </div>
             </div>
           </div>';
     $row++;
   }
?>
  </tbody>
</table>
<?php
} else {
  echo '<h3><em>There are currently no scheduled events to display</em></h3>';
}
?>
<div id="actionmodal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<?php include( 'pushSchActions.php'); ?>
</div>
</div>
<?php
include 'footer.php';
?>
