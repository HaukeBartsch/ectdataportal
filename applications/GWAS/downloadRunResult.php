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

  if (isset($_GET['id'])) {
    $id = $_GET['id'];
  } else {
    echo "{ \"message\": \"unknown id\" } ";
    return;
  }

  function getRun( $id ) {
     global $project_name;

     $output = null;
     $rc = 0;
     // get the first thousend snps
     exec ( "/usr/bin/ssh gwasproc@pip31.ucsd.edu 'sort -g -k 3 pingGWAS/runs/".$project_name."/".$id."/PING_660_final_run.ps | head -200'", $output, $rc );
     if ( count($output) == 0 ) {
        return "";
     }
     return implode("\n",$output);
  }

  // this is a remote file
  $content = getRun( $id );
  echo $content;

?>
