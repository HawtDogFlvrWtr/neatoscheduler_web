<?php 
include 'header.php';
date_default_timezone_set($timezone);
$row = 1;

# Get list of users, This after the delete statement above, so it updates the page on post.
$files = glob($actionsDir.'*.{json}', GLOB_BRACE);
?>
<div class="container-margin container border rounded bg-light">
<h1>Action Logs</h1>
<p>These are the current run logs for your devices.</p>
<?php 
if (count($files) > 0) {
?>
<table class="table rwd-table table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">BotVac</th>
      <th scope="col">Action</th>
      <th scope="col">Type</th>
      <th scope="col">Date/Time</th>
    </tr>
  </thead>
  <tbody>
<?php
   foreach(array_reverse($files) as $file) {
     $jsonDecode = json_decode(file_get_contents($file), true);
     $id = end(explode("/", explode(".", $file)[0]));
     $botInfo = json_decode(file_get_contents($botsDir.$jsonDecode['serial'].".json"),true);
     $time = $jsonDecode['time'];
     if (isset($jsonDecode['complete'])) {
	$color = 'green';
     } else if (isset($jsonDecode['failed'])) {
	$color = 'red';
     } else {
	$color = 'black';
     }
     $realDate = date("Y-m-d H:i:s", $time);
#	var_dump($realDate);
     echo '<tr style="color:'.$color.'">';
     echo '<td data-th="Info">'.$botInfo['description'].' - '.$botInfo['model'].'</td>';
     echo '<td data-th="Date">'.$jsonDecode['action'].'</td>';
     if ($jsonDecode['scheduled'] == 1) {
	     echo '<td data-th="Desc">Scheduled</td>';
     } else {
	     echo '<td data-th="Desc">Manual</td>';
     }
     echo '<td data-th="Date">'.$realDate.'</td>';
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
