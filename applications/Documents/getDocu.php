<?php
  date_default_timezone_set('America/Los_Angeles');
  // increase this scripts memory limit to allow larger spreadsheets to be processed
  // ini_set("memory_limit","512M");

  // check permissions first
  session_start(); /// initialize session
  include("../../code/php/AC.php");
  $user_name = check_logged(); /// function checks if visitor is logged.
  if (!$user_name)
    return; // do nothing

  if (!empty($_GET['query'])) { //$_GET is superglobal, com is one of the keys inside that array
    $query = $_GET['query'];
  } else {
    $query = "query";
  }

  if (!empty($_GET['project_name'])) {
    $g_project = $_GET['project_name'];
  } else {
    echo "Error: no project defined...";
    return;
  }
  $g_project = str_replace('"', '', $g_project);
  // if no project is defined we cannot do anything...

  if (!empty($_GET['value'])) { //$_GET lets you pass variables in via URL
    $value = $_GET['value'];
  } else {
    $value = "";
  }
  $value = str_replace('"', '', $value);

  if (!empty($_GET['_v'])) {
    $version = $_GET['_v'];
  } else {
    $version = "";
  }

  // root to documentation tree
  $_file = $_SERVER['DOCUMENT_ROOT']."/data/".$g_project."/data_uncorrected".$version."/documentation/";

  function readDirectory( $_file ) {
    $d = array();
    if ($handle = opendir( $_file )) {
      /* This is the correct way to loop over the directory. */
      while (false !== ($entry = readdir($handle))) {
	if ($entry == "." || $entry == ".." || !is_dir($_file."/".$entry) ) {
	  continue;
	}
	$d[] = $entry;
      }
      closedir($handle);
    }

    return $d;
  }

  // return JSON
  // populate accordion headers
  if ( strcmp($query, "sections") == 0) {
    // https://mmil-dataportal.ucsd.edu/code/Documentation/getDocu.php?project_name=PING&query=sections&_v=_v0.1
    $d = readDirectory( $_file );
    echo json_encode( $d );
    return;

  //populate top buttons
  } else if (strcmp($query, "masterlist") == 0) {
    $fn = $_file.'/masterlist.json';
    $data = json_decode(file_get_contents($fn),true);
    foreach ($data['values'] as &$value ) {
      if (!isset($value['date'])) {
        $filename = $value['filename'];
        $filename = str_replace('$VERSION', $version, $filename);
        $filename = str_replace('$PROJECT_NAME', $g_project, $filename);
        $filename = str_replace('$USER_NAME', $user_name, $filename);
	// make path absolute
        $filename = $_SERVER['DOCUMENT_ROOT']."/data/".$g_project."/data_uncorrected".$version."/documentation/".$filename;
	//echo (' date is not set here:' . $filename. ' date is : ');
        //if (!is_file($filename)) {
	//  echo ("could not read file: ".$filename.' directory is: '.getcwd());
	//}
//echo ("TRY TO READ: ".$filename);
        if (is_readable($filename)) {
  	  // read the date from the file itself
	  $value['date'] = date("D, m/d/Y", filemtime($filename));
	} else {
	  $value['date'] = "File not found, run DataExploration";
	}
      }
    }
    echo json_encode($data);
    return;
  //populate accordion buttons
  } else if (strcmp($value, "") == 0) {
    // https://mmil-dataportal.ucsd.edu/code/Documentation/getDocu.php?project_name=PING&query=ApprovedDataRequests&_v=_v0.1
    // read sub-directory and show contents of .json files
    $file = $_file.$query."/".$query.".json"; //Path to your *.txt file 

    $data = json_decode(file_get_contents($file),true);
    $output = $data;
    $output['values'] =	array();
    foreach ($data['values'] as $value ) {
      if ( isset($value['active']) ) {
        if (strcasecmp($value['active'],"Yes") == 0) {
          $output['values'][] = $value;
        }
      }
    }
    echo json_encode($output);
    return;
  } else if(strcmp($value, "") != 0) {
    // https://mmil-dataportal.ucsd.edu/code/Documentation/getDocu.php?project_name=PING&query=ApprovedDataRequests&value="ASVZ_PINGDataAnalysisProposal_062811.pdf"&_v=_v0.1
    if ( file_exists ( $_file.$query."/".$value ) ) {
      echo $_file.$query."/".$value." exists!";
    } else {
      echo $_file.$query."/".$value." does NOT exist.";
    }

//
  } else {
    echo "Error: unknown arguments, use _v, project_name, query, and value";
  }

?>
