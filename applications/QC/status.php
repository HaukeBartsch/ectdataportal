<?php
  //
  // Get/Set the QC status of each entry
  //
  // (Hauke, 12/2011)

  // http://mmil-dataportal.ucsd.edu/QC/status.php?Project="PING"&PatientID="P0007"&VisitID="P000700001"&com="query"
  // http://mmil-dataportal.ucsd.edu/QC/status.php?Project="PING"&PatientID="P0007"&VisitID="P000700001"&com="setnotes"&value="notesNotes"
  // http://mmil-dataportal.ucsd.edu/QC/status.php?Project="PING"&PatientID="P0007"&VisitID="P000700001"&com="setbadseries"&value="3,2,4,5"
  // http://mmil-dataportal.ucsd.edu/QC/status.php?Project="PING"&PatientID="P0007"&VisitID="P000700001"&com="setcode"&value="-1"
  // http://mmil-dataportal.ucsd.edu/QC/status.php?Project="PING"&PatientID="P0007"&VisitID="P000700001"&com="queryall"

  if (!empty($_GET['patientid'])) {
    $patientid = $_GET['patientid'];
  } else {
    $patientid = "";
  }
  $patientid = str_replace('"', '', $patientid);

  if (!empty($_GET['user'])) {
    $user = $_GET['user'];
  } else {
    echo "Error: no user defined...";
    return;
  }
  $user = str_replace('"', '', $user);

  if (!empty($_GET['visitid'])) {
    $visitid = $_GET['visitid'];
  } else {
    $visitid = "";
  }
  $visitid = str_replace('"', '', $visitid);

  if (!empty($_GET['Project'])) {
    $project = $_GET['Project'];
  } else {
    echo "Error: no project defined...";
    return;
  }
  $project = str_replace('"', '', $project);
  // if no project is defined we cannot do anything...

  if (!empty($_GET['com'])) {
    $command = $_GET['com'];
  } else {
    $command = "query";
  }
  $command = str_replace('"', '', $command);

  if (!empty($_GET['value'])) {
    $value = $_GET['value'];
  } else {
    $value = "";
  }
  $value = str_replace('"', '', $value);

  $qc_file = "../../data/".$project."/".$project."_QC.tsv";


  function loadDatabase( $qc_file ) {
    $row = 1;
    $d = array();
    // read the projects QC table
    if (($handle = fopen($qc_file, "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
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
    $fp = fopen($filename, 'w');
    if ($fp == FALSE)
       return;
    foreach ($data as $d) {
       fputcsv($fp, $d);
    }
    fclose($fp);
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
    return;
  } elseif (strcmp($command, "setnotes") == 0) {
    if ($patientid == "" || $visitid == "" ) {
      return;
    }
    foreach ($d as $key => $line) {
      if ( $line[0] == $patientid && $line[1] == $visitid ) {
	// found the patient, change notes
	$line[4] = $value;
        $line[3] = date("D M j G:i:s T Y"); // time stamp
	$line[6] = $user;
	$d[$key] = $line;
	saveDatabase( $d, $qc_file );
	return;
      }
    }
    // create the entry if it did not exist already
    $d[] = array( "patientid" => $patientid, "visitid" => $visitid,
	          "code" => 0, "time" => date("D M j G:i:s T Y" ),
                  "notes" => $value, "badseries" => "", "user" => $user );
    saveDatabase( $d, $qc_file );
    return;
  } elseif (strcmp($command, "setbadseries") == 0) {
    if ($patientid == "" || $visitid == "" ) {
      return;
    }
    foreach ($d as $key => $line) {
      if ( $line[0] == $patientid && $line[1] == $visitid ) {
	// found the patient, change notes
	$line[5] = $value;
        $line[3] = date("D M j G:i:s T Y"); // time stamp
	$line[6] = $user;
	$d[$key] = $line;
	saveDatabase( $d, $qc_file );
	return;
      }
    }
    // create the entry if it did not exist already
    $d[] = array( "patientid" => $patientid, "visitid" => $visitid,
	          "code" => 0, "time" => date("D M j G:i:s T Y" ),
                  "notes" => "", "badseries" => $value, "user" => $user );
    saveDatabase( $d, $qc_file );
    return;
  } elseif (strcmp($command, "setcode") == 0) {
    if ($patientid == "" || $visitid == "" ) {
      return;
    }
    foreach ($d as $key => $line) {
      if ( $line[0] == $patientid && $line[1] == $visitid ) {
	// found the patient, change code
	$line[2] = $value;
        $line[3] = date("D M j G:i:s T Y"); // time stamp
	$line[6] = $user;
	$d[$key] = $line;
	saveDatabase( $d, $qc_file );
	return;
      }
    }
    // create the entry if it did not exist already
    $d[] = array( "patientid" => $patientid, "visitid" => $visitid,
	          "code" => $value, "time" => date("D M j G:i:s T Y" ),
		  "notes" => "", "badseries" => "", "user" => user);
    saveDatabase( $d, $qc_file );
    return;
  } else {
    echo "error: Unknown query string \"".$command."\". Only \"query\" and \"setnotes\" and \"setcode\", \"queryall\", \"setbadseries\" are supported currently.";
  }

?>
