<?php

 $dir = glob('/home/dataportal/www/applications/DataExploration/curves/*_curves');
 $ch = array();
 foreach ($dir as $d) {
    $vals = split ("_", basename($d));
    $blob = array( "name" => $vals[0], "project" => $vals[1], "user" => $vals[0], "children" => array() );
    $ch2 = glob($d."/*.txt");
    // parse all files for variables of interest, count up the number of times they are used
    $variables = array();
    foreach ($ch2 as $d2) {
      $txt = file_get_contents($d2);
      $lines = explode( "\n", $txt);
      foreach ($lines as $line) {
         if (substr(trim($line), 0, 1) == "#")
	   continue;
         $tmp = explode("=", $line);
	 if (count($tmp) == 2) {
	    $entries = strtok(trim($tmp[1], " \"\t"), "+:");
	    while ($entries !== false) {
	       $e = trim($entries);
	       if (!array_key_exists($e, $variables)) {
	          $variables[$e] = 1;
               } else {
	          $variables[$e] = $variables[$e]+1;
               }
	       $entries = strtok("+:");
	    }
         } else {
	    // a line could also contain a new definition, we would have to look for data$something and add that
	    if (count($tmp) < 2 || strlen($tmp[1]) == 0)
	       continue;
	    $entries = explode( "data$", trim($tmp[1]));
	    if (count($entries) < 2)
	      continue;
	    $entries = array_shift($entries); // don't use the first element
            foreach ($entries as $entry) {
	       //$e = 
	       // don't take the first one
	       $e = explode(" ", $entry);
	       if (count($e) == 0)
	         continue;
	       $e = $e[0];
	       if (!array_key_exists($e, $variables)) {
	          $variables[$e] = 1;
               } else {
	          $variables[$e] = $variables[$e]+1;
               }
            }
         }
      }
    }

    foreach ($variables as $key => $v) {
      array_push( $blob["children"], array( "name" => $key, "size" => $v ) );
    }
    $blob['size'] = count($ch2);
    $ch[] = $blob;
 } 

 // return data exploration state as json
 $ret = array( "users" => array("bar", array( "name" => "users", "children" => $ch  ) ), "info" => "utilization of measures" );
 
 echo( json_encode( $ret ) );

?>
