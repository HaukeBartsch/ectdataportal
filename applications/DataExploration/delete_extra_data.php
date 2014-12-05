<?php
  session_start(); /// initialize session
  include("../../code/php/AC.php");
  $user_name = check_logged(); /// function checks if visitor is logged.
  echo ($user_name);
  syslog(LOG_INFO, 'delete user data started');

  // check permissions first
  if ( !$user_name ) {
    syslog(LOG_INFO, "No user defined... (not logged in?)");
    echo "No user defined ... (not logged in?)";
    return; // no permissions
  }
  syslog(LOG_INFO, 'upload.php: permissions ok');

  $project = trim($_POST['project_name']); // Maximum filesize in BYTES
  $u_name = trim($_POST['user_name']);
  if ($u_name != $user_name) {
    // one can only selete the own data
    echo("<html>Error: can only delete own (".$u_name.", ".$user_name.") data</html>");
    return;
  }

  $file='user_data/userdata_'.$project.'_'.$u_name.'.csv';
  echo("<html>deleted file:".$file."</html>");
  unlink($file);
  echo("<html>deleted file:".$file."</html>");
?>
