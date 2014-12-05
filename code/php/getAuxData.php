<?php
  //
  // Get any auxillary data for a specific patient/visit
  //
  // (Hauke, 08/2013)


  date_default_timezone_set('America/Los_Angeles');
  // increase this scripts memory	limit to allow larger spreadsheets to be processed
  ini_set("memory_limit","128M");

  session_start(); /// initialize session
  include("AC.php");
  $user_name = check_logged(); /// function checks if visitor is logged.
  if (!$user_name)
     return; // nothing

  if (!empty($_GET['patientid'])) {
    $patientid = $_GET['patientid'];
  } else {
    $patientid = "";
  }
  $patientid = str_replace('"', '', $patientid);

  if (!empty($_GET['visitid'])) {
    $visitid = $_GET['visitid'];
  } else {
    $visitid = "";
  }
  $visitid = str_replace('"', '', $visitid);

  if (!empty($_GET['project_name'])) {
    $g_project = $_GET['project_name'];
    $g_project = str_replace('"', '', $g_project);
    if (!check_permission($g_project)) {
      //syslog (LOG_EMERG, 'user '.$user_name.' does not have permission to see project: '.$project_name);
      return;
    }
  } else {
    return;
  }
  // if no project is defined we cannot do anything...

  $aux_files = "../../data/".$g_project."/data_uncorrected/".$g_project."_AUX_*.csv";


  function loadDatabase( $qc_file ) {
    $row = 1;
    $d = array();

    // if file does not exist create an empty version
    if (!file_exists($qc_file)) {
      $handle = fopen($qc_file, 'w'); 
      fclose($handle);
    }

    // read the projects QC table
    if (($handle = fopen($qc_file, "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 100000, ",")) !== FALSE) {
        $num = count($data);
        $row++;
      
        $vals = array();
        for ($c=0; $c < $num; $c++) {  
  	  $vals[$c] = $data[$c];
        }
        $d[$row] = $vals;
      }
      fclose($handle);
    }
    return $d;
  }
  $auxfiles = glob($aux_files);
  $ret = "[ ";
  $auxs = 0;
  foreach ($auxfiles as $f) {
    $fn = basename($f, '.csv');
    $type = explode('_',$fn);
    $type = $type[2];
    // do we have permissions to read this project?
    if ( ! check_permission( $type )) {
       syslog(LOG_EMERG, 'user '.$user_name.' does not have permission to see aux type: '.$type);
       continue;
    }

    $d = loadDatabase( $f );
    if ($auxs > 0)
       $ret = $ret . ",";
    $auxs = $auxs + 1;    

    $ret = $ret . "{ \"type\": \"".$type."\", \"values\": [ ";
    $count = 0;
    foreach ($d as $line) {
       if ($line[0] == $patientid && $line[1] == $visitid) {
         if ($count > 0)
           $ret = $ret . ", ";
         
         $ret = $ret . "{ \"type\": \"" . $line[2] . "\", \"value\": \"" . $line[3] . "\" }";
         $count = $count + 1;
       }
    }
    $ret = $ret." ] }";
  }
  $ret = $ret . "]";
  echo $ret;
?>
