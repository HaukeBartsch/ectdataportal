<?php
  session_start(); /// initialize session
  include("../../code/php/AC.php");
  $user_name = check_logged(); /// function checks if visitor is logged in
  // echo('<script type="text/javascript"> user_name = "'.$user_name.'"; </script>'."\n");

  if (isset($_GET['project_name'])) {
    $project_name = $_GET['project_name'];
  } else {
    echo "{ \"message\": \"unknown project\" } ";
    return;
  }

  function getRemoteFile( $fn ) {
     $output = null;
     $rc = 0;
     exec ( "/usr/bin/ssh gwasproc@pip31.ucsd.edu 'cat pingGWAS/runs/".$fn."'", $output, $rc );
     if ( count($output) == 0 ) {
        // no file found, try to create empty file
        exec ( "/usr/bin/ssh gwasproc@pip31.ucsd.edu '/bin/echo \"{ \\\"runs\\\": [ ] }\" > pingGWAS/runs/".$fn."'", $output, $rc );
        return "{ \"runs\": [ ] }";
     }
     return implode("\n",$output);
  }


  // this is a remote file
  $fn = 'listOfRuns_'.$project_name.'.json';
  $content = getRemoteFile( $fn );
  echo $content;

?>
