<?php
include 'config.php';
include 'functions.php';

# Start session across pages
session_start();
# redirect if not logged in, or if logging out.
if (!isset($_SESSION['login_user']) OR isset($_GET['logout'])) {
  unset($_SESSION['login_user']);
  header("Location: login.php");
  die();
}

$currentPage = basename($_SERVER['PHP_SELF'],'.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $siteName;?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href='css/fontawesome-all.min.css'>
    <link rel="stylesheet" type="text/css" href='css/custom.css'>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  </head>
<body>
<div class="container container-header">
<nav class="navbar rounded navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php"><?php echo $siteName;?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item <?php if ($currentPage == 'add') { echo 'active'; } ?>">
        <a class="nav-margin btn btn-dark btn-sm" href="add.php"><i class="fa fa-plus"></i> Add BotVac <?php if ($currentPage == 'add') { echo '<span class="sr-only">(current)</span>'; } ?></a>
      </li>
      <li class="nav-item <?php if ($currentPage == 'allBots') { echo 'active'; } ?>">
        <a class="nav-margin btn btn-dark btn-sm" href="allBots.php"><i class="fa fa-desktop"></i> All BotsVac's <?php if ($currentPage == 'allSystems') { echo '<span class="sr-only">(current)</span>'; }?></a>
      </li>
      <li class="nav-item <?php if ($currentPage == 'allSchedules') { echo 'active'; } ?>">
        <a class="nav-margin btn btn-dark btn-sm" href="allSchedules.php"><i class="far fa-calendar-alt"></i> Schedules <?php if ($currentPage == 'allSchedules') { echo '<span class="sr-only">(current)</span>'; }?></a>
      </li>
      <li class="nav-item <?php if ($currentPage == 'logs') { echo 'active'; } ?>">
        <a class="nav-margin btn btn-dark btn-sm" href="logs.php"><i class="far fa-file-alt"></i> Action Logs <?php if ($currentPage == 'logs') { echo '<span class="sr-only">(current)</span>'; }?></a>
      </li>
      <?php if ($_SESSION['admin'] == 1) { ?>
      <li class="nav-item <?php if ($currentPage == 'userCreds') { echo 'active'; } ?>">
        <a class="nav-margin btn btn-dark btn-sm" href="userCreds.php"><i class="fa fa-user-plus"></i> Users <?php if ($currentPage == 'userCreds') { echo '<span class="sr-only">(current)</span>'; }?></a>
      </li>
      <?php } else { ?>
      <li class="nav-item <?php if ($currentPage == 'userCreds') { echo 'active'; } ?>">
        <a class="nav-margin btn btn-dark btn-sm" href="userCreds.php"><i class="fa fa-user"></i> My Account <?php if ($currentPage == 'userCreds') { echo '<span class="sr-only">(current)</span>'; }?></a>
      </li>
      <?php } ?>
 
    </ul>
    <ul class="navbar-nav">
      <li class="nav-item active" >
        <a class="nav-margin btn btn-danger  btn-sm" href="index.php?logout"><i class="fa fa-sign-out-alt"></i> <?php echo strtoupper($_SESSION['login_user']);?>, Log Out</a>
      </li>
    </ul>
  </div>
</nav>
</div>
<!--<div class="container container-header">
</div>-->

<?php
if (isset($_SESSION['msgBox']) && $_SESSION['msgBox'] != "") {
  echo '<div class="container">';
  echo $_SESSION['msgBox'];
  echo '</div>';
  unset($_SESSION['msgBox']);
}
?>
