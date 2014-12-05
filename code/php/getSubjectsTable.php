<?php

 date_default_timezone_set('America/Los_Angeles');
 // increase this scripts memory  limit to allow larger spreadsheets to be processed
 ini_set("memory_limit","300M");


 session_start(); /// initialize session
 include("AC.php");
 $user_name = check_logged(); /// function checks if visitor is logged.

 $ar = array();

 if (!$user_name) {
    $ar['text'] = "error: unknown user";
    echo json_encode( $ar );
    return; // do nothing
 }

 if (isset($_GET['project']))
    $project = $_GET['project'];
 else {
    $ar['text'] = "error: unknown project";
    echo json_encode( $ar );
    return; 
 }
  $version = "";

 if (! check_permission( $project )) {
  $ar['text'] = "No permissions for this project";
  echo json_encode ( $ar ) ;
  return; // not allowed to return data for this project
 }

 // now create a valid table and put it in $ar['text']
 $ss = "../../data/".$project."/data_uncorrected/usercache_". $project. "_". $user_name. $version. ".csv";

 // read only two columns in here
 function loadDatabase( $_file ) {
    $row = 1;
    $d = array();

    if (!file_exists($_file)) {
       $ar['text'] = "file does not exist ".$_file;
       echo json_encode ( $ar ) ;
       return $d; // nothing
    }

    if (($handle = fopen($_file, "r")) !== FALSE) {
      // read the header
      $SubjIDidx = FALSE;
      $VisitIDidx = FALSE;
      if (($data = fgetcsv($handle, 100000, ",")) !== FALSE) {
        // header section
        $SubjIDidx = array_search("SubjID", $data);
        $VisitIDidx = array_search("VisitID", $data);
      }
      if ($SubjIDidx === FALSE || $VisitIDidx === FALSE) {
         $ar['text'] = "no SubjID or VisitID found ".$SubjIDidx.", ".$VisitIDidx;
         echo json_encode ( $ar ) ;
         return $d; // nothing        
      }

      while (($data = fgetcsv($handle, 100000, ",")) !== FALSE) {
        $num = count($data);
        $row++;
      
        $vals = array();
        $vals[0] = $data[$SubjIDidx];
        $vals[1] = $data[$VisitIDidx];
        /*for ($c=0; $c < $num; $c++) {  
          $vals[$c] = $data[$c];
        }*/
        $d[$row] = $vals;
      }
      fclose($handle);
    }
    return $d;
 }
 
 $data = loadDatabase($ss);
 if ( count($data) == 0) {
   return;
 }

 $t = "SubjID, VisitID\n";
 foreach($data as $line) {
  $t = $t . $line[0] . ",". $line[1]."\n";
 }
 $ar['text'] = $t;
 echo json_encode( $ar );

?>