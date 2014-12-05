<?php

  # get the variables from the parent page, or use pre-defined values
  if (!isset($_POST['_v']))
    $version       = "";
  else
    $version       = $_POST['_v'];
  if (!isset($_POST['cookie']))
    $cookie       = 42;
  else
    $cookie       = $_POST['cookie'];
  if (!isset($_POST['user_name']))
    $user_name  = "hauke";
  else
    $user_name    = $_POST['user_name'];
  if (!isset($_POST['project_name']))
    $project_name = 'PING';
  else
    $project_name = $_POST['project_name'];
  if (!isset($_POST['returnFile']))
    $returnFile = 'false';
  else
    $returnFile = $_POST['returnFile'];
  if (!isset($_POST['a']))
    return; // don't do anything if we don't get told what is happening
  else
    $a = $_POST['a'];
  if (!isset($_POST['b']))
    return; // don't do anything if we don't get told what is happening
  else
    $b = $_POST['b'];


  // The R code itself
  // Important: php variables are used as is, for example $expert
  //            is replaced with its text representation by php. 
  //            R variables can have the "$"-sign as well, in that case
  //            one needs to quote it with "\$" in the text.
  //
  // Input directory structure:
  //   /opt/dataportal/data/PING/data_uncorrected/PING_MRI_DTI_Complete_QCed.csv
  //   /opt/dataportal/data/PING/data_uncorrected/PING_Behavior.csv  <- super spreadsheet
  //   /opt/dataportal/code/StatisticsGAM2/user_code/userdata_PING_hauke.csv  <- user defined spreadsheet
  // Output directory structure (sub-directories for the project/user/cookie are created therein)
  //   /opt/dataportal/code/StatisticsGAM2/
  //
  // Requirements for output:
  //  R stdout should contain all information displayed in the "Statistics Summary" section of the webpage. 
  //

$out = array();
exec ('/usr/bin/R --no-restore --no-save -q -f StatsScript_Scatter.R --args project_name=\"'.$project_name.'\" user_name=\"'.$user_name.'\" version=\"'.$version.'\" a=\"'.$a.'\" b=\"'.$b.'\"  2>&1', $out);

$stop = 0;
foreach ($out as &$u) {
   if ( $u == "## model summary") {
     $stop = 1;
   }
   if ( $stop == 1 ) {
     if ( strpos($u, '>') !== 0 && strpos($u, '+ ') !== 0)
       echo $u.'<br>';
   }
}
if ( $stop == 0 ) {
   echo 'Error returned by R for project '.$project_name.'.<br>See the end of the summary statistics for the relevant error message.';
   foreach ($out as &$u) {
      echo $u.'<br>';
   }
}
file_put_contents('/tmp/bla', $out);
// if not in debug mode, remove the temporary R-command file again
// unlink($file);

?>
