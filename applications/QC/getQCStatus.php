<?php
  //
  // Get/Set the QC status of each entry
  //
  // (Hauke, 02/2013)


  date_default_timezone_set('America/Los_Angeles');
  // increase this scripts memory	limit to allow larger spreadsheets to be processed
  ini_set("memory_limit","512M");


  //phpinfo();
  // check permissions first
  global $user;
  if ( isset($_SERVER["PHP_AUTH_USER"])) {
    $user_name = $_SERVER["PHP_AUTH_USER"];
  }
  $allowEdit = true;

  if (!empty($_GET['patientid'])) {
    $patientid = $_GET['patientid'];
  } else {
    $patientid = "";
  }
  $patientid = str_replace('"', '', $patientid);

  if (!empty($_GET['com'])) {
    $command = $_GET['com'];
  } else {
    $command = "query";
  }
  $command = str_replace('"', '', $command);

  if (!empty($_GET['user'])) {
    $g_user = $_GET['user'];
  } else {
    if ($command != "query") {
      echo "Error: no user defined...";
      return;
    }
    $g_user = "query";
  }
  $g_user = str_replace('"', '', $g_user);

  if (!empty($_GET['visitid'])) {
    $visitid = $_GET['visitid'];
  } else {
    $visitid = "";
  }
  $visitid = str_replace('"', '', $visitid);

  if (!empty($_GET['project_name'])) {
    $g_project = $_GET['project_name'];
  } else {
    $g_project = "Project01";
  }
  $g_project = str_replace('"', '', $g_project);
  // if no project is defined we cannot do anything...

  if (!empty($_GET['value'])) {
    $value = $_GET['value'];
  } else {
    $value = "";
  }
  $value = str_replace('"', '', $value);

  $qc_file = "../../data/".$g_project."/".$g_project."_QC.tsv";
  $non_corrected_file = "../../data/".$g_project."/data_uncorrected/".$g_project."_MRI_DTI_Complete.csv";
  // the next file needs to be created from the file above and has the bad QC-ed entries removed
  $non_corrected_qced_file = "../../data/".$g_project."/data_uncorrected/".$g_project."_MRI_DTI_Complete_QCed.csv";

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
      while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
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
 
  function saveDatabase( $data, $filename ) {
    global $allowEdit;
    global $non_corrected_file;
    global $non_corrected_qced_file;
    if (!$allowEdit) {
      syslog( LOG_INFO, "Editing not allowed for current user");
      echo "Error: editing database is not allowed for the current user";
      return;   
    }

    $fp = fopen($filename, 'w');
    if ($fp == FALSE)
       return;
    foreach ($data as $d) {
       fputcsv($fp, $d);
    }
    fclose($fp);
    syslog( LOG_INFO, "in saveDatabase, before correction");

    // now create a new master table with bad QC entries removed
    $orig = loadDatabase( $non_corrected_file );
    $orig_qced = array();
    $row = 0;
    foreach ($orig as $key1 => $o) {    // filter out the bad entries from QC
      $patid = $o[0];
      $visid = $o[1];
      $found = false; // bad qc-ed
      foreach ($data as $key2 => $d) {
	if ( $patid == $d[0] && $visid == $d[1] && $d[2] == -1 ) {
	  $found = true;
          break;
	}
      }
      if ( $found == false ) {
        $orig_qced[$row] = $o;
        $row = $row + 1;
      } else {
        syslog( LOG_INFO, " QC: filtered out ".$o[0]." ".$o[1]."." );
      }
    }
    $fp = fopen($non_corrected_qced_file, 'w');
    if ($fp == FALSE)
       return;
    foreach ($orig_qced as $d) {
       fputcsv($fp, $d);
    }
    fclose($fp);
    syslog( LOG_INFO, "exported new qc-ed version of spreadsheet to ".$non_corrected_qced_file);    
  }

  $d = loadDatabase( $qc_file );

  // return JSON
  if ( strcmp($command, "query") == 0) {
    if ($patientid == "" || $visitid == "" ) {
      return;
    }

    foreach ($d as $line) {
      if ( $line[0] == $patientid && $line[1] == $visitid ) {
	echo "{ \"patientid\": \"".$patientid."\", \"visitid\": \"".$visitid."\", \"code\": \"".$line[2]."\", \"notes\": \"".$line[4]."\", \"time\": \"".$line[3]."\", \"badseries\": \"".$line[5]."\", \"user:\": \"".$line[6]."\" }";
	return;
      }
    }
    echo "{}";
    return;
  } elseif (strcmp($command, "queryall") == 0) {
    $str = "";
    foreach ($d as $line) {
      if (strlen($str) > 0) {
        $str = $str.",";
      }
      $str = $str."{ \"patientid\": \"".$line[0]."\", \"visitid\": \"".$line[1]."\", \"code\": \"".$line[2]."\", \"notes\": \"".$line[4]."\", \"time\": \"".$line[3]."\", \"badseries\": \"".$line[5]."\", \"user\": \"".$line[6]."\" }";
    }
    echo "[ ".$str." ]";
 // return;
  } elseif (strcmp($command, "setnotes") == 0) {
 syslog ( LOG_INFO, " SETNOTES"); 
    if ($patientid == "" || $visitid == "" ) {
      return;
    }
    foreach ($d as $key => $line) {
      if ( $line[0] == $patientid && $line[1] == $visitid ) {
	// found the patient, change notes
	$line[4] = $value;
        $line[3] = date("D M j G:i:s T Y"); // time stamp
	$line[6] = $g_user;
	$d[$key] = $line;
	saveDatabase( $d, $qc_file );
	return;
      }
    }
    // create the entry if it did not exist already
    $d[] = array( "patientid" => $patientid, "visitid" => $visitid,
	          "code" => 0, "time" => date("D M j G:i:s T Y" ),
                  "notes" => $value, "badseries" => "", "user_g" => $g_user );
    saveDatabase( $d, $qc_file );
 syslog ( LOG_INFO, " SETNOTES END"); 
    return;
  } elseif (strcmp($command, "setbadseries") == 0) {
 syslog ( LOG_INFO, " SETBADSERIES"); 
    if ($patientid == "" || $visitid == "" ) {
      return;
    }
    foreach ($d as $key => $line) {
      if ( $line[0] == $patientid && $line[1] == $visitid ) {
	// found the patient, change notes
	$line[5] = $value;
        $line[3] = date("D M j G:i:s T Y"); // time stamp
	$line[6] = $g_user;
	$d[$key] = $line;
	saveDatabase( $d, $qc_file );
 syslog ( LOG_INFO, " SETBADSERIES found entry to change"); 
	return;
      }
    }
    // create the entry if it did not exist already
    $d[] = array( "patientid" => $patientid, "visitid" => $visitid,
	          "code" => 0, "time" => date("D M j G:i:s T Y" ),
                  "notes" => "", "badseries" => $value, "user_g" => $g_user );
    saveDatabase( $d, $qc_file );
 syslog ( LOG_INFO, " SETBADSERIES END"); 
    return;
  } elseif (strcmp($command, "setcode") == 0) {
 syslog ( LOG_INFO, " SETCODE"); 
    if ($patientid == "" || $visitid == "" ) {
      syslog( LOG_INFO, " SETCODE empty patientid or visitid field");
      return;
    }
    foreach ($d as $key => $line) {
      if ( $line[0] == $patientid && $line[1] == $visitid ) {
	// found the patient, change code
        syslog( LOG_INFO, "SETCODE found patient id, setting code");
	$line[2] = $value;
        $line[3] = date("D M j G:i:s T Y"); // time stamp
	$line[6] = $g_user;
	$d[$key] = $line;
	saveDatabase( $d, $qc_file );
	return;
      }
    }
    syslog( LOG_INFO, "create new entry ".$patientid." ".$visitid." ".$value." ".$g_user);
    // create the entry if it did not exist already
    $d[] = array( "patientid" => $patientid, "visitid" => $visitid,
	          "code" => $value, "time" => date("D M j G:i:s T Y"),
		  "notes" => "", "badseries" => "", "user_g" => $g_user);
    syslog( LOG_INFO, "now save to new database");
    saveDatabase( $d, $qc_file );
 syslog ( LOG_INFO, " SETCODE END"); 
    return;
  } else {
 syslog ( LOG_INFO, " ELSE"); 
    echo "error: Unknown query string \"".$command."\". Only \"query\" and \"setnotes\" and \"setcode\", \"queryall\", \"setbadseries\" are supported currently.";
  }

?>
