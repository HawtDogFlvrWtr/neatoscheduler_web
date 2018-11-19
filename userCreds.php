<?php
include 'header.php';
#iini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);
#error_reporting(E_ALL);
$queryArray = array();

# Force regular users to their profile page
if ($_SESSION['admin'] != 1 && $_GET['username'] != $_SESSION['login_user_'.$sessionID]) {
  header("Location: userCreds.php?username=".$_SESSION['login_user_'.$sessionID]);  
}
# Generate form information if username provided
# input information from form submit
if (count($_POST) > 0 && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['cpassword']) && isset($_POST['opassword'])) {
  if ( $_POST['password'] === $_POST['cpassword'] ) {
    unset($_POST['cpassword']);
    # Checking saved password vs user submitted old password
    $checkUser = checkUser($_POST['opassword'], $_POST['username'], $usersDir);
    if ($checkUser == 0) {
      $username = $_POST['username'];
      $_POST['password'] = generateHash(16, $_POST['password']);
      unset($_POST['opassword']);
      $jsonConfs = stripslashes(json_encode($_POST));
      if(file_put_contents($usersDir.$username.".json", $jsonConfs)) {
        msgBox("Credentials for ".$_POST['username']." saved.", "success");
      } else {
        msgBox("Credentials for ".$_POST['username']." not saved. Are you trying to be naughty?", "danger");
      }
      $_POST = array();
    # Wrong old password
    } else if ($checkUser == 1) {
        msgBox("Your old password for ".$_POST['username']." was not correct. Your new password hasn't been saved.", "danger");
    # User doesn't exist
    } else if ($checkUser == 2) {
        msgBox("The user ".$_POST['username']." doesn't exist. Are you trying to be naughty?", "danger");
    }
  } else {
        msgBox("Your new passwords didn't match. Please try again.", "danger");
  }
  header("Location: userCreds.php");
  die();
} else if (count($_POST) > 0 && isset($_POST['username']) && isset($_POST['password'])) {
  if ( $_POST['password'] === $_POST['cpassword'] ) {
    unset($_POST['cpassword']);
    $username = $_POST['username'];
    $_POST['password'] = generateHash(16, $_POST['password']);
    $jsonConfs = stripslashes(json_encode($_POST));
    if(file_put_contents($usersDir.$username.".json", $jsonConfs)) {
      msgBox("Credentials for ".$_POST['username']." were saved successfully.", "success");
    } else {
      msgBox("Credentials for ".$_POST['username']." were not saved. Please check folder permissions and try again.", "danger");
    }
    $_POST = array();
  } else {
    msgBox("Your new password doesn't match the confirmation password. Please try again.", "danger");
  }
  header("Location: userCreds.php");
  die();
}

# Get user polulated information
if (isset($_GET['username'])){
  $username = $_GET['username'];
  if ($_SESSION['admin'] == 1 || $username == $_SESSION['login_user_'.$sessionID]){
    if (file_exists($usersDir.$username.".json")) {
      if ( isset($_GET['delete'])) {
        if (unlink($usersDir.$username.".json")) {
          msgBox("All credentials for ".$username." were deleted.", "success");
        } else {
          msgBox("Credentials for ".$username." weren't deleted. Please try again.", "danger");
        }
      } else {
        $jsonInfo = file_get_contents($usersDir.$username.'.json');
        $queryArray = json_decode($jsonInfo, true);
      }
    } else {
      $queryArray['username'] = $_GET['username'];
      msgBox($_GET['username']." has no configuration information. You can add it below.", "danger");
    }
  }
}
# Get all Credentials after they've been placed in the file. This is the for sidebar list
$allCreds = glob($usersDir.'*.{json}', GLOB_BRACE);
?>
<div class="container">
  <div class="row">
  <?php if ($_SESSION['admin'] == 1) { ?>
  <div class="container-header border rounded-left bg-light col-md-10">
  <?php } else { ?>
  <div class="container-header border rounded bg-light col-md-12">
  <?php } ?>
    <h1>User Credentials</h1>
    <p>This page allows you to generate NEW credentials per user. To set specific credentials for a user, type the username and password that you would like. Please note, that the passwords are stored in a SHA512 format, and cannot be reversed. Because of this, we provide no way of viewing the current passwords.</p>
        <?php 
         if (isset($queryArray['username'])) {
           $urlGet = "?username=".$queryArray['username'];
         } else {
           $urlGet = "";
         }
        ?>
    <form method="post" action="userCreds.php<?php echo $urlGet;?>"> 
    <?php if (isset($_GET['username']) && $_SESSION['admin'] != 1) {?>
    <div class="form-row">
      <div class="form-group col-md-12">
        <label>Old Password<input aria-describedby="opasswordHelp" placeholder="Password" class="form-control" type="password" id="opassword" name="opassword" value=""></label>
        <small id="opasswordHelp" class="form-text text-muted">Please type your old password, to make changes to your account. If you don't know your password, You will have to delete your account and recreate it.</small>
      </div>
    <?php } else { ?>
      <?php if ($queryArray['admin'] == 1) {
        $checked = "checked='checked'";
      } else {
        $checked = "";
      }
      ?>
    <div class="form-check-row">
      <div class="form-check col-md-12">
        <input aria-describedby="adminHelp" class="form-check-input" type="checkbox" id="admin" name="admin" value="1" <?php echo $checked;?>>
        <label class="form-label" id="adminHelp">Site Administrator</label>
        <small id="adminHelp" class="form-text text-muted">Select this if you want to make this user an administrator.</small>
      </div>
    <?php }?>
    </div>
    <div class="form-row">
      <div class="form-group col-md-2">
        <?php 
         if (isset($queryArray['username'])) {
           $readonly = "readonly";
         } else {
           $readonly = "";
         }
        ?>
        <label>Username<input <?php echo $readonly; ?> placeholder="Username" class="form-control" type="text" id="username" name="username" value="<?php if (isset($queryArray['username'])) { echo $queryArray['username']; }?>"></label>
      </div>
      <div class="form-group col-md-2">
        <label>New Password<input placeholder="Password" class="form-control" type="password" id="password" name="password" value=""></label>
      </div>
      <div class="form-group col-md-2">
        <label>Confirm<input placeholder="Password" class="form-control" type="password" id="cpassword" name="cpassword" value=""></label>
      </div>
    </div>
    <input class="btn btn-success" type="submit" name="" value="Submit">
    <?php if (isset($queryArray['username']) && $_SESSION['admin'] == 1 && $_SESSION['login_user_'.$sessionID] != $queryArray['username']) { 
      echo '<a data-toggle="tooltip" title="Delete User" class="btn btn-danger btn-icon" href="userCreds.php?delete&username='.$queryArray['username'].'"><i ></i>Delete</a>';
    }?>
    </form>
  </div>
  <div class="col-md-0"> 
  </div>
  <?php if ($_SESSION['admin'] == 1) { ?>
  <div class="border rounded-right border-left-0 col-md-2">
    <h5 class="current-set-margin" >Currently Set</h5>
    <?php
      if (count($allCreds) != 0) {
    ?>
      <ul>
    <?php
      foreach($allCreds as $cred) {
        echo '<li><a href="userCreds.php?username='.basename($cred, '.json').'">'.basename($cred, '.json').'</a></li>';
      }
    ?>
      </ul>
    <?php
      } else {
        echo '<p class="text-left">There are no users</p>';
      }
    ?>
  </div>
  <?php } ?>
  </div>
</div>
<?php include 'footer.php';?>

