<?php 
include 'header.php';
#echo '<meta http-equiv="refresh" content="5">';
#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);
#error_reporting(E_ALL);
$row = 1;
$found = 0;
# Perform Action
if (isset($_GET['action']) && isset($_GET['serial'])) {
  $actionArray = array();
  if (in_array($_GET['action'], $possibleActions)) {
    $files = glob($actionsDir.'*.{json}', GLOB_BRACE);
    foreach($files as $file) {
      $jsonInfo = file_get_contents($file);
      $queryArray = json_decode($jsonInfo, true);
      if ($queryArray['serial'] = $_GET['serial'] && !isset($queryArray['complete']) && $queryArray['action'] === $_GET['action']) {
        $found = 1;
        msgBox("This action (".$_GET['action'].") is already being performed on this botvac", "danger");
        break;
      }
    }
    if ($found != 1) {
      $randomID = generateRandomString(10);
      $time = time();
      $actionArray['action'] = $_GET['action'];
      $actionArray['serial'] = $_GET['serial'];
      $actionArray['time'] = $time;
      $actionArray['scheduled'] = 0;
      $jsonConfs = json_encode($actionArray);
      if(file_put_contents($actionsDir.$time."-".$randomID.".json", $jsonConfs)) {
        msgBox("Botvac has been told to ".$_GET['action'], "success");
      } else {
        msgBox("Botvac action NOT saved. Please check db folder permissions.", "danger");
      }
    }
  } else {
    msgBox("This is an invalid action. Are you being naughty?", "danger");
  }
  header("Location: allBots.php");
  die();
}
# Delete Record
if (isset($_GET['serial'])){
  if (file_exists($botsDir.$_GET['serial'].".json")) {
    if ( isset($_GET['delete'])) {
      if (unlink($botsDir.$_GET['serial'].".json")) {
        msgBox("This BotVac's (".$_GET['serial'].") configuration was deleted.", "success");
      } else {
        msgBox("This BotVac's (".$_GET['serial'].") configuration wasn't deleted. Please try again.", "danger");
      }
    }
  }
  header("Location: allBots.php");
  die();
}
# Get list of systems, This after the delete statement above, so it updates the page on post.
$lidarList = glob($lidarDir.'*.{json}', GLOB_BRACE);
$files = glob($botsDir.'*.{json}', GLOB_BRACE);
?>
<div class="container-margin container border rounded bg-light">
<div class="row">
<div class="col-lg">
<h1>All BotVacs</h1>
<p>These are the current BotVac's that you have configuration information for. You can edit or delete any record.</p>
<?php 
if (count($files) > 0) {
?>
<script type="text/javascript">
  // Refresh the table if no modals are open on the page
  function pullSchActions(){
    if (!$('[id^=delete]').hasClass('show') && !$('[id^=error]').hasClass('show') && !$('[id^=json]').hasClass('show')){
      $.get("api/botList.php", function(data) {
        $(".botsTable").html(data);
      });
    }
                                     
    setTimeout(function(){
      pullSchActions();
    }, 5000);
  }
  $(document).ready(function(){
    pullSchActions();
  });
</script>
<table class="table rwd-table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Actions</th>
      <th scope="col">Model</th>
      <th scope="col">Description</th>
      <th scope="col">Firmware</th>
      <th scope="col">Battery</th>
      <th scope="col">Options</th>
    </tr>
  </thead>
  <tbody class="botsTable">
  </tbody>
</table>
<?php
} else {
  echo '<h3><em>There are currently no BotVacs to display</em></h3>';
}
?>
</div>
</div>
</div>
<div class="container" style="margin-top:20px;">
	<?php foreach($lidarList as $lidar) { 
		$serial = end(explode("/", $lidar));
		$jsonInfo = file_get_contents($botsDir.$serial);
	        $queryArray = json_decode($jsonInfo, true);
	?>
	
	<div class="fix-spacing container-margin container col-md-3 float-left border rounded" >
		<div class="card-body">
		     <h5 class="card-title"><?php echo $queryArray['description']?></h5>
         	                       <script language="JavaScript">
					      function pullMap<?php echo $queryArray['serial']?>(){
						 // Only refresh maps that have changes.
						 if ($("i#<?php echo $queryArray['serial']?>").hasClass("fa-stop")) {
                        		           $("#map<?php echo $queryArray['serial']?>").attr("src", "api/drawMap.php?serial=<?php echo $queryArray['serial']?>&"+new Date().getTime());
						 }
                	                       setTimeout(function(){
						 pullMap<?php echo $queryArray['serial']?>();
                                               },2000);
					      }
					      $(document).ready(function(){
    						pullMap<?php echo $queryArray['serial']?>();
					      });
                                       </script>
	                               <div>
        	                               <a href="largeMap.php?serial=<?php echo $queryArray['serial']?>" onclick="window.open(this.href, 'mywin','left=20,top=20,width=700,height=700,toolbar=1,resizable=0'); return false;" ><img class="border rounded img-fluid" id="map<?php echo $queryArray['serial']?>" width=202 height=202 src="api/drawMap.php?serial=<?php echo $queryArray['serial']?>"/></a>
                                       </div>
		</div>
	</div>
	<?php } ?>
</div>
<script>
    setInterval(function() { 
      $('.blink').fadeIn(1000).fadeOut(1000);
    }, 1000);
</script>
<?php
include 'footer.php';
?>
