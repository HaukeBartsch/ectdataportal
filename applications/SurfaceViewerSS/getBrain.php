<?php

date_default_timezone_set('America/Los_Angeles');
ini_set("memory_limit","1024M");


if (isset($_POST['subjid']))
  $subjid = $_POST['subjid'];

if (isset($_POST['visitid']))
  $visitid = $_POST['visitid'];

if (isset($_POST['project_name']))
  $project_name	= $_POST['project_name'];

if (!isset($project_name)) {
	echo "error: project_name does not exist";
	return;
}

// read the vertex geometry file, first the csv afterwards the .dat                                                                                     
// get the correct vertices and return as json                                                                                                          
$pathlh = '/usr/share/nginx/html/data/'.$project_name.'/data_uncorrected/VertexStatsInput/'.$project_name.'_concat_surfstats_sm2819-geometry-lh';
$pathrh = '/usr/share/nginx/html/data/'.$project_name.'/data_uncorrected/VertexStatsInput/'.$project_name.'_concat_surfstats_sm2819-geometry-rh';

if (!file_exists($pathlh.".csv")) {
    echo "could not open file :".$pathlh.".csv";
    return;
}

if (!file_exists($pathrh.".csv")) {
    echo "could not open file".$pathrh.".csv";
    return;
}

$row = 0;
$header = array();
$subjidIdx = -1;
$visitidIdx = -1;
$found = -1;
//$rows = array();
$file = fopen($pathlh.".csv", "r") or exit("Could not open file: ".$pathlh.".csv");
while (($data = fgetcsv($file, 800, ",")) !== FALSE) {
  $num = count($data);
  if ($row == 0) {
  	// read header
    $header = $data;
    for ($c=0; $c < $num; $c++) {
    	if ($data[$c] == "SubjID")
    		$subjidIdx = $c;
    	if ($data[$c] == "VisitID")
    		$visitidIdx = $c;
    }
  }

  $row++;
  if ( $data[$subjidIdx] == $subjid && ($data[$visitidIdx] == $visitid || $visitidIdx == -1) ) {
  	$found = $row-1;
        break;
  }
}
fclose($file);

if ($found < 0) {
	echo "Could not find this entry: ".$subjid.", ".$visitid;
	return;
}

function readSurface( $file, $found) {

	// now read in $found from .dat and return it
	$fp = fopen($file, "rb");
	if (!$fp) {
		echo "Error reading file: ".$file;
		return;	
	}
	$nsubj  = unpack("i1nsubj", fread($fp, 4));
	$nvisit = unpack("i1nvisits", fread($fp, 4));
	$dummy  = unpack("i1nvisits", fread($fp, 4));
	//echo "number of subjects: ".$nsubj["nsubj"]."\n";
	//echo "number of vertices: ".$nvisit["nvisits"]."\n";

	//fseek($fp, (3*4)+(2562*3*4)*$found); // three (*4) int size followed by x,y,z (*3) entries of 2562 float values
	$numentries = 4*3*$nvisit["nvisits"]*$nsubj["nsubj"];
	$str = fread($fp, $numentries);
	fclose($fp);
	if (strlen($str) != $numentries){
  	 echo "could not read enough float values from file: ". strlen($str);
  	 return;
	}
	echo("read string with length: ".strlen($str). " try to get 3* ". ($nvisit["nvisits"]*$nsubj["nsubj"]). " geometry values ");
	$dat = array_values(unpack("f".($nvisit["nvisits"]*$nsubj["nsubj"]*3), $str));

	//echo "read in that many float values: ".count($dat);
	$dat2 = array(); // array_slice($dat, 0, 7686);
	for ($c = 0; $c < 7686;$c++) {
		$dat2[] = $dat[$c*$nsubj["nsubj"]+$found];
	}
	return $dat2;
}

$lh = readSurface($pathlh.".dat", $found);
$rh = readSurface($pathrh.".dat", $found);

//echo("number of entries found: ".count($dat)."\n");
echo (json_encode( array( "lh" => $lh, "rh" => $rh ) ) ); // x array first, followed by y array followed by z array

?>


















