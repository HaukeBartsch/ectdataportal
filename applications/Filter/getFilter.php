<?php

// make sure user is logged in
session_start();
include("../../code/php/AC.php");
$user_name = check_logged();
if ($user_name == FALSE) {
  echo "no user name";
  return;
}

$fn = "data/".$user_name.".json";
if (!is_readable($fn)) {
  echo "could not find ".$fn;
  return;
}

$aruser = json_decode( file_get_contents($fn), TRUE);

$fn = "data/public.json";
if (is_readable($fn)) {
  $puuser = json_decode( file_get_contents($fn), TRUE);
  $aruser = array_merge( $aruser, $puuser );
}
echo json_encode( $aruser );

?>