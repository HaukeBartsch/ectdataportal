<?php

  date_default_timezone_set('America/Los_Angeles');
  session_start();

  include("../../code/php/AC.php");
  $user_name = check_logged(); /// function checks if visitor is logged.

  // We want to prevent people from downloading too many snp's due to 
  // de-anonymization issues. So we store the number of downloaded SNPS.
  $num = getUserVariable( $user_name, "SNPNumDownloaded" );
  if ($num == FALSE)
     $num = 0;
  $maxNumDownloaded = 5000;// 539867.0/10.0; // 10 percent allowed

  if (isset($_GET['project_name'])) {
    $project_name = $_GET['project_name'];
  } else {
    echo ("Error: no session variable for project_name found");
    $project_name = "PING";
  }

  if (!isset($_GET['_v']))
    $version       = "";
  else
    $version       = $_GET['_v'];

  if (!isset($_GET['snps'])) {
    return; // nothing to do
  }

  $snps = urldecode($_GET['snps']);
  $numnew = count(split("\n",$snps)); // there is one more newline at the end
  if ( ($num + $numnew) > $maxNumDownloaded) {
    echo "Error: maximum number of downloadable SNPS reached for this account $maxNumDownloaded";
    return;  
  }
  setUserVariable( $user_name, "SNPNumDownloaded", $numnew + $num );
  if (getUserVariable( $user_name, "SNPNumDownloaded" ) != $numnew + $num) {
    echo "Error: could not store the number of SNPs downloaded, please contact your system administrator...";
    return;
  }

  $snpsHeader = "SubjID,".implode(",",array_filter(split("\n",$snps)));

  function tempdir($dir=false,$prefix='php') {
    $tempfile=tempnam(sys_get_temp_dir(),'');
    if (file_exists($tempfile)) { unlink($tempfile); }
    mkdir($tempfile);
    if (is_dir($tempfile)) { return $tempfile; }
  }

  // execute something like this: /path/to/plink/executable/plink --bfile PING_660_final --extract snps.txt --recode --tab --out snps
  // after writing out the snps to disk into snps.txt
  // create temporary directory for this
  $d = tempdir();
  file_put_contents($d."/snps.txt", $snps);

  $out = array();
  exec ('/usr/bin/nice -10 /usr/local/bin/plink --noweb --bfile ../../data/'.$project_name.'/data_uncorrected/SNPs/'.$project_name.'_660_final --extract '.$d.'/snps.txt --recode --tab --out '.$d.'/snps.csv 2>&1', $out);
  //echo ($out); // for now
  exec ('tr -d \' \' < '.$d.'/snps.csv.ped | cut -f2,7- | tr \'\t\' \',\' > '.$d.'/snps.csv');
  // tr -d ' ' < snps.csv.ped | cut -f2,7-

  // add the header line
  $out = array();
  exec('cat '.$d.'/snps.csv.map | cut -d\'	\' -f2', $out);
  $nn = "SubjID,".implode(",",array_filter($out));
  file_put_contents($d."/snps.csv", $nn."\n".file_get_contents($d."/snps.csv"));

  // create count columns for all snps using minor allele
  $out = array();
  exec ('/usr/bin/R --no-restore --no-save -q -f addCountForMinorAllele.R --args fntable=\"'.$d.'/snps.csv\" 2>&1', $out);
  #foreach ($out as &$u) {
  #  echo $u;
  #}
  # add the number of already downloaded SNPs to the table 
  file_put_contents($d."/snps.csv", "downloaded: ".($numnew + $num)." of ".floor($maxNumDownloaded)."\n".file_get_contents($d."/snps.csv"));

  echo(file_get_contents($d."/snps.csv"));

  return;

?>
