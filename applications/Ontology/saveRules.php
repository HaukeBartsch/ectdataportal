<?php
  session_start(); /// initialize session
  include("../../code/php/AC.php");
  $user_name = check_logged(); /// function checks if visitor is logged.
  if (!$user_name) {
     echo (json_encode ( array( "message" => "no user name" ) ) );
     return; // nothing
  }
  if (!check_role( "admin" )) {
     return;
  }

  if (isset($_GET['text']))
    $text = $_GET['text'];
  else
    $text = "";
  if (isset($_GET['project']))
    $project = $_GET['project'];
  else {
    return; // cannot do anything without a proper project name
  }
  
  if ($text != "") {
     // save the text as a new rules file (todo: guard this with git)
     $filename = "../../data/".$project."/data_uncorrected";
     if (!is_dir($filename)) {
        echo "Error: ".$filename." does not exist";
	return;
     }
     $filename = $filename."/".$project."_datadictionary_rules.csv";
     if (!file_exists($filename)) {
        // try to save for the first time
	file_put_contents( $filename, $text );
	echo "File has been created";
     } else {
	file_put_contents( $filename, $text );
	echo "File has been overwritten.";
     }
  }
 ?>
 