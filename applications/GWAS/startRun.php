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
  if (isset($_GET['com'])) {
    $yvalue = $_GET['com'];
  }
  if (isset($_GET['covariates'])) {
    $covariates = $_GET['covariates'];
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
  function setRemoteFile( $fn, $content ) {
     $output = null;
     $rc = 0;
     exec ( "/usr/bin/ssh gwasproc@pip31.ucsd.edu '/bin/echo \"".addslashes($content)."\" > pingGWAS/runs/".$fn."'", $output, $rc );
  }
  function startComputation( $id, $yvalue, $covariates ) {
     global $project_name;
     global $user_name;

     // setup a new project
     $output = null;
     $rc = 0;
     exec ( "/usr/bin/ssh gwasproc@pip31.ucsd.edu '/bin/mkdir -p \"pingGWAS/runs/".$project_name."/".$id."\"'", $output, $rc );
     // create spreadsheet with measurement variable and covariates
     $RCode = <<<EOT

yvalue="$yvalue"
covariates="$covariates"

EOT;
     file_put_contents("data/".$id.".model", $RCode);


     // create the input data
     exec ('/usr/bin/R --no-restore --no-save -q -f createSpreadSheet.R --args project_name=\"'.$project_name.'\" user_name=\"'.$user_name.'\" id=\"'.$id.'\" 2>&1', $output);

     // copy data
     exec ( "/usr/bin/ssh gwasproc@pip31.ucsd.edu '/bin/mkdir -p \"pingGWAS/runs/".$project_name."/".$id."\"'", $output, $rc );

     $pheno_file = $id."_pheno_EMMAX.csv";
     $covar_file = $id."_pheno_EMMAX_covariates.csv";
     $keep_file  = $id."_plink_Complete_Subjects.txt";
     exec ( "/usr/bin/scp data/".$pheno_file." gwasproc@pip31.ucsd.edu:pingGWAS/runs/".$project_name."/".$id, $output, $rc );
     exec ( "/usr/bin/scp data/".$covar_file." gwasproc@pip31.ucsd.edu:pingGWAS/runs/".$project_name."/".$id, $output, $rc );
     exec ( "/usr/bin/scp data/".$keep_file."  gwasproc@pip31.ucsd.edu:pingGWAS/runs/".$project_name."/".$id, $output, $rc );

     // use the keep file and re-calculate the SNP file without missing values
     exec ( "/usr/bin/ssh gwasproc@pip31.ucsd.edu 'cd pingGWAS; /usr/local/bin/plink --noweb --tfile PING_660_final_tped --output-missing-genotype 0 --transpose --recode12 --keep ~/pingGWAS/runs/".$project_name."/".$id."/".$id."_plink_Complete_Subjects.txt --out ~/pingGWAS/runs/".$project_name."/".$id."/PING_660_final_tped_keep'");
     // exec ( "/usr/bin/ssh gwasproc@pip31.ucsd.edu 'cd pingGWAS/runs/".$project_name."/".$id."/; /usr/pubsw/packages/emmax/emmax-kin -v -h -s -d 10 PING_660_final_tped_keep'");
     exec ( "/usr/bin/ssh gwasproc@pip31.ucsd.edu 'cd pingGWAS/runs/".$project_name."/".$id."/; /usr/pubsw/packages/emmax/emmax-kin -v -h -d 10 PING_660_final_tped_keep'");
    
     // /usr/pubsw/packages/emmax/emmax -v -d 10 -t PING_660_final_tped -p ~/pingGWAS/runs/".$project_name."/".$id."/".$pheno_file." -k PING_660_final_tped.hBN.kinf -c ~/pingGWAS/runs/".$project_name."/".$id."/".$covar_file." -o ~/pingGWAS/runs/".$project_name."/".$id."/result'", $output, $rc );

     // start emmax in the background
     // file_put_contents('/tmp/logfile', "/usr/bin/ssh gwasproc@pip31.ucsd.edu '/usr/pubsw/packages/emmax/emmax -v -d 10 -t /home-local/gwasproc/pingGWAS/PING_660_final_tped -p pingGWAS/runs/".$project_name."/".$id."/".$pheno_file." -k /home-local/gwasproc/pingGWAS/PING_660_final_tped.hBN.kinf -c pingGWAS/runs/".$project_name."/".$id."/".$covar_file." -o pingGWAS/runs/".$project_name."/".$id."/result'");

     exec ( "/usr/bin/ssh gwasproc@pip31.ucsd.edu 'cd pingGWAS/runs/".$project_name."/".$id."/; /usr/pubsw/packages/emmax/emmax -v -d 10 -t PING_660_final_tped_keep -p ~/pingGWAS/runs/".$project_name."/".$id."/".$pheno_file." -k PING_660_final_tped_keep.hBN.kinf -c ~/pingGWAS/runs/".$project_name."/".$id."/".$covar_file." -o ~/pingGWAS/runs/".$project_name."/".$id."/PING_660_final_run'", $output, $rc );

     // if this worked we can copy back our results (or use downloadRunResult instead)
     //exec ( "/usr/bin/scp gwasproc@pip31.ucsd.edu:pingGWAS/runs/".$project_name."/".$id."/result.log data/".$id."_result.log", $output, $rc );
     //exec ( "/usr/bin/scp gwasproc@pip31.ucsd.edu:pingGWAS/runs/".$project_name."/".$id."/result.ps data/".$id."_result.ps", $output, $rc );
     exec ( "/usr/bin/scp gwasproc@pip31.ucsd.edu:pingGWAS/runs/".$project_name."/".$id."/result.reml data/".$id."_result.reml", $output, $rc );
     
  }

  // add to list of runs
  $fn = 'listOfRuns_'.$project_name.'.json';
  $content = json_decode( getRemoteFile( $fn ), true);

  $id = time();
  startComputation( $id, $yvalue, $covariates );  

  array_push( &$content['runs'], array( "id" => $id, "yvalue" => $yvalue, "covariates" => $covariates ) );

  // save again
  setRemoteFile($fn, json_encode($content) );

  // return the id of this run
  echo "{ \"id\": ".$id." }";
?>
