<?php
if (isset($_GET['serial'])) {
?>
	<script src="js/jquery.js" type="text/javascript"></script>
	<script language="JavaScript">
		setInterval(function(){
			$("#map<?php echo $_GET['serial']?>").attr("src", "api/drawMap.php?serial=<?php echo $_GET['serial']?>&"+new Date().getTime());
		},1000);
	</script>
	<div style="width:600px; height:600px; margin: 0 auto;">
		<img id="map<?php echo $_GET['serial']?>" width=600 height=600 src="api/drawMap.php?serial=<?php echo $_GET['serial']?>"/>
	</div>
<?php
} else {
	echo "You\'re missing the serial number";
}
?>
