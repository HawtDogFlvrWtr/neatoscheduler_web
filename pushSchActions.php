<?php
include 'config.php';
	$getDeviceInfo = glob($botsDir.'*.{json}', GLOB_BRACE);
	$selectActions = ['Clean', 'Clean House', 'Clean Spot'];
?>
	<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	</head>
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
	<form enctype="multipart/form-data" action="allSchedules.php" method="post">
	<div class="modal-body">
	  <div class="form-group">
	  <label style="font-weight:bold;" for="device">Device:
	  <select class="form-control" name="device" id="device">
		<?php
			foreach ($getDeviceInfo as $deviceFile) {
				$deviceInfo = json_decode(file_get_contents($deviceFile), true);
				echo '<option value="'.$deviceInfo['serial'].'">'.$deviceInfo['model'].'-'.$deviceInfo['description'].'</option>';
			}
		?>
	  </select>
	  </label>
	  </div>
	  <div class="form-group">
	<label style="font-weight:bold;" for="action">Action:
	<select class="form-control" name="action" id="action">
	  <?php 
		foreach ($selectActions as $actions){
			echo '<option value="'.$actions.'">'.$actions.'</option>';
		}
	  ?>
	</select>
   	</div>
	  <div class="form-group">
	<label style="font-weight:bold;">Run Once:
	<input type="checkbox" name="runonce" id="runonce"></br>
	</label>
	  <div class="form-group">
	<label style="font-weight:bold;" for="hour">Time:
	<select class="form-control" name="hour" id="hour">
	<?php
		$hours = 0;
		while ($hours < 24) {
			$hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
			echo '<option value="'.$hours.'">'.$hours.'</option>';
			$hours++;
		}
	?>
	</select>
	</label>
	<label for="minute">
	<select class="form-control" name="minute" id="minute">
	<?php
		$minutes = 0;
		while ($minutes < 61) {
			$minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);
			echo '<option value="'.$minutes.'">'.$minutes.'</option>';
			$minutes++;
		}
	?>
	</select>
	</label>
	</div>
	  <div class="form-group">
	<label style="font-weight:bold;">Day(s): </label>
	<label style="padding-left:10px;" for="sunday">Sun</label>
	<input type="checkbox" name="sunday" id="sunday">
	<label style="padding-left:10px;" for="monday">Mon</label>
	<input type="checkbox" name="monday" id="monday">
	<label style="padding-left:10px;" for="tuesday">Tue</label>
	<input type="checkbox" name="tuesday" id="tuesday">
	<label style="padding-left:10px;" for="wednesday">Wed</label>
	<input type="checkbox" name="wednesday" id="wednesday">
	<label style="padding-left:10px;" for="thursday">Thu</label>
	<input type="checkbox" name="thursday" id="thursday">
	<label style="padding-left:10px;" for="friday">Fri</label>
	<input type="checkbox" name="friday" id="friday">
	<label style="padding-left:10px;" for="saturday">Sat</label>
	<input type="checkbox" name="saturday" id="saturday"></br>
	<label style="padding-top:20px; font-weight:bold;">Note: </label>
	<input type="text" name="note" value="" size=10 class="form-control"/>
	</div>
	</div>
	<div class="modal-footer">
  	  <button type="input" name="submit" value="addschaction" class="btn btn-success btn-icon"><i class="fa fa-calendar"></i> Add Schedule </button>
	  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
	</form>
	</div>
        </div><!-- /.modal-content -->
	</html>
