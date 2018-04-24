<?php
include '../config.php';
include '../functions.php';
$row = 1;
$found = 0;
# Get list of systems, This after the delete statement above, so it updates the page on post.
$files = glob('../'.$botsDir.'*.{json}', GLOB_BRACE);
?>

<?php
   foreach($files as $file) {
     $fileContent = file_get_contents($file);
     $jsonDecode = json_decode($fileContent, true);
     $serial = $jsonDecode['serial'];
     if ($jsonDecode['errorMsg'] != ''){
       $action = "Clean Stop";
       $actionLink = '<a id="action" data-toggle="modal" data-toggle="tooltip" title="ERROR: Click to see." href="#error'.$serial.'" class="btn blink btn-danger btn-icon" data-dismiss="modal"><i id="'.$jsonDecode['serial'].'" style="padding:4px;" class="fa fa-exclamation"></i></a>';
     } else if ($jsonDecode['chargeEnabled'] == 0 && $jsonDecode['ping'] >= time() - 60){
       $actionLink = '<a id="action" data-toggle="tooltip" title="Clean Stop" class="btn btn-outline-danger" href="allBots.php?serial='.$serial.'&action=Clean Stop"><i id="'.$jsonDecode['serial'].'" class="fa fa-stop"></i></a>';
     } else if ($jsonDecode['chargeEnabled'] == 1 && $jsonDecode['ping'] >= time() - 60){
       $actionLink = '<a id="action" data-toggle="tooltip" title="Clean" class="btn btn-outline-success" href="allBots.php?serial='.$serial.'&action=Clean"><i id="'.$jsonDecode['serial'].'" class="fa fa-play"></i></a>';
     } else {
       $actionLink = '<a id="action" data-toggle="tooltip" title="Clean" class="disabled btn btn-outline-secondary" href="allBots.php?serial='.$serial.'&action=Clean"><i id="'.$jsonDecode['serial'].'" class="fa fa-play"></i></a>';
     }
     if ($jsonDecode['ping'] <= time() - 60) {
       $batteryIcon = "disabled progress-bar bg-secondary";
       $jsonDecode['battery'] = 0;
     } else {
       # Draw bettery Icon
       if (! isset($jsonDecode['battery'])) {
	 $jsonDecode['battery'] = 0;
       } 
       $battery = $jsonDecode['battery'];
       # Use charge icon if we're charging. Otherwise show battery.
       if ($jsonDecode['chargeActive'] == 1) {
	       if ($battery <= 100 && $battery >= 75) {
        	 $batteryIcon = "progress-bar-striped progress-bar-animated bg-success";
	       } else if ($battery >= 30 && $battery <=74) {
        	 $batteryIcon = "progress-bar-striped progress-bar-animated bg-warning";
	       } else if ($battery <=29){
        	 $batteryIcon = "progress-bar-striped progress-bar-animated bg-danger";
       	       }

       } else {
	       if ($battery <= 100 && $battery >= 75) {
		 $batteryIcon = "progress-bar bg-success";
	       } else if ($battery >= 30 && $battery <=74) {
		 $batteryIcon = "progress-bar bg-warning";
	       } else if ($battery <= 29){
		 $batteryIcon = "progress-bar bg-danger";
	       }
       }
     }
     # DRAW TABLE
     $batteryC = $jsonDecode['batteryTemp'];
     $batteryF = ($batteryC * 9/5) + 32;
     echo '<tr>';
     echo '<td data-th="Actions">'.$actionLink.'</td>';
     echo '<td data-th="Model">'.$jsonDecode['model'].'</td>';
     echo '<td data-th="Desc">'.$jsonDecode['description'].'</td>';
     echo '<td data-th="FW">'.$jsonDecode['firmware'].'</td>';
     echo '<td data-th="Battery">
		<div class="border border-dark progress position-relative" style="height:40px;">
 		  <div class="'.$batteryIcon.'" role="progressbar" style="width:'.$jsonDecode['battery'].'%" aria-valuenow="'.$jsonDecode['battery'].'" aria-valuemin="0" aria-valuemax="100"></div>
		    <batLarge class="justify-content-center d-flex position-absolute w-100">'.$jsonDecode['battery'].'%</batLarge>
		</div>
	   </td>';
     echo '<td data-th="Options">
	<a data-toggle="tooltip" title="EDIT BOTVAC" class="btn btn-secondary btn-icon" href="add.php?serial='.$jsonDecode['serial'].'"><i class="fa fa-edit"></i></a>
	<a data-toggle="modal" data-toggle="tooltip" title="DELETE BOTVAC" href="#delete'.$row.'" class="btn btn-secondary btn-icon" data-dismiss="modal"><i class="fa fa-trash-alt"></i></a>
	<a data-toggle="modal" data-toggle="tooltip" title="RAW BOT INFO" href="#json'.$jsonDecode['serial'].'" class="btn btn-secondary btn-icon" data-dismiss="modal"><i class="fa fa-code"></i></a>';
     # ERROR MESSAGE MODAL IF IT EXISTS
     if ($jsonDecode['errorMsg'] != '') { 
     echo '<div id="error'.$jsonDecode['serial'].'" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
             <div class="modal-dialog">
               <div class="modal-content">
		<div class="modal-header">
		 <h5 class="modal-title">Error: '.$jsonDecode['description'].' '.$jsonDecode['model'].'</h5>
		 <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
		</div>
                   <div class="modal-body">
                     <p class="lead">'.$jsonDecode['errorMsg'].'</p>
                   </div>
                   <div class="modal-footer">
                     <button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> Ok</button>
                   </div>
               </div>
             </div>
           </div>';
     }
     # JSON MODAL
     echo '<div id="json'.$jsonDecode['serial'].'" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
             <div class="modal-dialog modal-lg">
               <div class="modal-content">
		<div class="modal-header">
		 <h5 class="modal-title">Raw Info: '.$jsonDecode['description'].' '.$jsonDecode['model'].'</h5>
		 <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
		</div>
                   <div class="modal-body">
 			<div class="form-group">
			<textarea class="form-control" rows="16">'.prettyPrint($fileContent).'</textarea>
			</div>
		   </div>
                   <div class="modal-footer">
                     <button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> Ok</button>
                   </div>
               </div>
             </div>
           </div>';
     # DELETE MODAL
     echo '<div id="delete'.$row.'" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
             <div class="modal-dialog">
               <div class="modal-content">
		<div class="modal-header">
		 <h5 class="modal-title">Delete: '.$jsonDecode['description'].' '.$jsonDecode['model'].'</h5>
		 <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
		</div>
                 <form action="allBots.php" method="get">
                   <div class="modal-body">
                     <p class="lead">Are you sure you want to remove this BotVac\'s configuration?</p>
                   </div>
                   <div class="modal-footer">
                     <button type="input" name="serial" value="'.$jsonDecode['serial'].'" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> Yes, Remove It</button>
                     <input type="hidden" name="delete" value="true">
                     <button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> Cancel</button>
                   </div>
                 </form>
               </div>
             </div>
           </div>';
     $row++;
   }
     echo '</td></tr>';
?>
<?php

?>
