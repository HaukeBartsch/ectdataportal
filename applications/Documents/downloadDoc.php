<?php

date_default_timezone_set('America/Los_Angeles');
// check permissions first
session_start(); /// initialize session
include("../../code/php/AC.php");
$user_name = check_logged(); /// function checks if visitor is logged in
if (!$user_name) {
   echo "User is not logged in";
   return; // do nothing
}
if (isset($_GET["project_name"])) {
  $project_name = $_GET["project_name"];
} else {
  echo "no project name";
  return;
}
if (isset($_GET["file"])) {
  $file = $_GET["file"];
} else {
  echo "no file name";
  return;
}
if (isset($_GET["version"])) {
  $version = $_GET["version"];
} else {
  return;
}
function download_csv_results($results, $name = NULL) {
    if( ! $name) {
        $name = md5(uniqid() . microtime(TRUE) . mt_rand()). '.csv';
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename='. $name);
    header('Pragma: no-cache');
    header("Expires: 0");

    $outstream = fopen("php://output", "w");
    fwrite($outstream, $results);
    fclose($outstream);
}

$fn = "/home/dataportal/www/data/".$project_name."/data_uncorrected".$version."/documentation/".$file;
audit( "Download", "essential data file: ".$fn );
if ( is_readable($fn) ) {
  download_csv_results(file_get_contents($fn), basename($fn));
} else {
  echo "file not found";
  return;
}

?>