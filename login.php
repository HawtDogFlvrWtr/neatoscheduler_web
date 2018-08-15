<?php
include 'config.php';
include 'functions.php';
#var_dump($_POST);
if (isset($_SESSION['login_user_'.$sessionID])) {
  header("Location: index.php");
  die();
}
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $checkUser = checkUser($password, $username, $usersDir);
  if ($checkUser == 0) {
    session_start();
    $_SESSION['login_user_'.$sessionID] = $username;
    $_SESSION['sessionID'] = $sessionID;
    $_SESSION['time'] = time();
    $_SESSION['admin'] = 0;
    header("Location: index.php");
    die();
  } else if ($checkUser == 3) {
    session_start();
    $_SESSION['login_user_'.$sessionID] = $username;
    $_SESSION['sessionID'] = $sessionID;
    $_SESSION['time'] = time();
    $_SESSION['admin'] = 1;
    header("Location: index.php");
    die();
  } else if ($checkUser == 1) {
    $msgBox = msgBox("Username or password incorrect.", "danger");
  }
}
?>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $siteName;?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymo
us">
    <link rel="stylesheet" type="text/css" href='css/fontawesome-all.min.css'>
    <link rel="stylesheet" type="text/css" href='css/login.css'>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  </head>
<body>
<?php
if ($msgBox != "") {
  echo '<div class="msg-box col-centered col-md-6 text-center">';
  echo $msgBox;
  echo '</div>';
}
?>
  <div class="loginRow col-md-12">
     <div class="border col-centered rounded bg-light col-sm-4 text-center">
        <h2 class="headerText"><?php echo $siteName;?></h2>
        <form method="post" action="login.php" >
              <div class="form-row">
                <div class="form-group col-md-12">
                  <input name="username" type="text" id="username" class="form-control" placeholder="username" />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                  <input name="password" type="password" id="password" class="form-control" placeholder="password" />
                </div>
              </div>
              <div class="wrapper">
                <span class="group-btn">     
                  <button type="submit" class="btn btn-primary btn-md">login <i class="fa fa-sign-in-alt"></i></button>
                </span>
              </div>
          </form>
        </div>
    </div>
</body>
