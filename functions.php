<?php
function convertDates($date){
     $daysoweek = array();
     # Made dates work out
     if ($date === '1,1,1,1,1,1,1') {
       $date = "Every Day";
     } else if ($date === '1,0,0,0,0,0,1') {
       $date = "Weekends";
     } else if ($date === '0,1,1,1,1,1,0') {
       $date = "Weekdays";
     } else {
       $days = explode(',',$date);
       if ($days[0] == 1) { array_push($daysoweek, 'Sun'); }
       if ($days[1] == 1) { array_push($daysoweek, 'Mon'); }
       if ($days[2] == 1) { array_push($daysoweek, 'Tue'); }
       if ($days[3] == 1) { array_push($daysoweek, 'Wed'); }
       if ($days[4] == 1) { array_push($daysoweek, 'Thu'); }
       if ($days[5] == 1) { array_push($daysoweek, 'Fri'); }
       if ($days[6] == 1) { array_push($daysoweek, 'Sat'); }
       $date = implode(' ',$daysoweek);
     }
     return $date;
}

function getAdditionals() {
  $gets = $_GET;
  return $gets;
}
function printHelp() {
  $helpInfo = "You're missing some information";
  return $helpInfo;
}
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function generateHash($saltLength = 16, $passString, $customSalt = '') {
  if ($customSalt == '') {
    $posCharacters = 'adcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789./';
    $string = '';
    $max = strlen($posCharacters) - 1;
    for ($i = 0; $i < $saltLength; $i++) {
      $string .= $posCharacters[mt_rand(0, $max)];
    }
  } else {
    $string = $customSalt;
  }
  return crypt($passString, '$6$'.$string); 
}

function msgBox($message, $type) {
  $msgBox = "<div class='alert alert-".$type." alert-dismissible fade show' role='alert'>
               $message
               <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                 <span aria-hidden='true'>&times;</span>
               </button>
             </div>";
  $_SESSION['msgBox'] = $msgBox;
  
}

function checkUser($currentPassString, $userName, $usersDir) {
  if (file_exists($usersDir.$userName.'.json')) {
    $userCreds = file_get_contents($usersDir.$userName.'.json');
    $decodeUser = json_decode($userCreds, true);
    $splitPasswd = explode('$', $decodeUser['password']);
    $verifyKey = generateHash(strlen($splitPasswd[2]), $currentPassString, $splitPasswd[2]);
    # FOR DEBUGGING!
    #echo "CurrentKey: ".$decodeUser['password']."\n";
    #echo "NewKey:     ".$verifyKey."\n";
    if (hash_equals($decodeUser['password'], $verifyKey)) {
       $output = "0";
       if ($decodeUser['admin'] == 1) {
         $output = "3";
       }
    } else {
       $output = "1";
    }
  } else {
    $output = "2";
  }
  return $output;
}

if (!function_exists('hash_equals')) {
  function hash_equals($str1, $str2) {
    if(strlen($str1) != strlen($str2)) {
      return false;
    } else {
      $res = $str1 ^ $str2;
      $ret = 0;
      for($i = strlen($res) - 1; $i >= 0; $i--) {
        $ret |= ord($res[$i]);
      }
      return !$ret;
    }
  }
}


# Removed pretty print from json_encode to keep php a lower version. rewrote it here.
function prettyPrint( $json ) { 
  $result = '';
  $level = 0;
  $in_quotes = false;
  $in_escape = false;
  $ends_line_level = NULL;
  $json_length = strlen( $json );

  for( $i = 0; $i < $json_length; $i++ ) {
    $char = $json[$i];
    $new_line_level = NULL;
    $post = "";
    if( $ends_line_level !== NULL ) {
      $new_line_level = $ends_line_level;
      $ends_line_level = NULL;
    }
    if ( $in_escape ) {
      $in_escape = false;
    } else if( $char === '"' ) {
      $in_quotes = !$in_quotes;
    } else if( ! $in_quotes ) {
      switch( $char ) {
        case '}': case ']':
          $level--;
          $ends_line_level = NULL;
          $new_line_level = $level;
          break;

        case '{': case '[':
          $level++;
        case ',':
          $ends_line_level = $level;
          break;

        case ':': $post = " "; break; case " ": case "\t": case "\n": case "\r":
          $char = "";
          $ends_line_level = $new_line_level;
          $new_line_level = NULL;
          break;
      }
    } else if ( $char === '\\' ) {
      $in_escape = true;
    }
    if( $new_line_level !== NULL ) {
      $result .= "\n".str_repeat( "\t", $new_line_level );
    }
    $result .= $char.$post;
  }

  return $result;
}

?>
