<?php
syslog(LOG_INFO, 'upload started');

// check permissions first
session_start(); /// initialize session
include("../../code/php/AC.php");
$user_name = check_logged(); /// function checks if visitor is logged.
if (!$user_name) {
  syslog(LOG_INFO, 'unknown user!');
  return; // nothin
}

syslog(LOG_INFO, 'user name is ok');

$allowed_filetypes = array('csv'); // These will be the types of file that will pass the validation.
$max_filesize = trim($_POST['MAX_FILE_SIZE']); // Maximum filesize in BYTES
$project  = trim($_POST['project']);
if ($project == "" ) {
  die('no project specified');
  syslog(LOG_INFO, 'no project specified');
}

$upload_path = './user_data/'; // The place the files will be uploaded to
if (!is_dir($upload_path))
  mkdir($upload_path);

$filename = $_FILES['userfile']['name']; // Get the name of the file (including file extension).
$info = pathinfo($filename);
syslog(LOG_INFO, "try to find file ".$filename);
$ext = $info['extension'];

// Check if the filetype is allowed, if not DIE and inform the user.
if(!in_array($ext,$allowed_filetypes)) {
  die('The file type you attempted to upload is not allowed ('.$ext.'). Only .csv files are accepted.');
  syslog(LOG_INFO, "upload.php: file type is not allowed");
}
// Now check the filesize, if it is too large then DIE and inform the user.
if(filesize($_FILES['userfile']['tmp_name']) > $max_filesize) {
  die('The file you attempted to upload is too large. '.filesize($_FILES['userfile']['tmp_name']).' only '.$max_filesize.'bytes are allowed');
  syslog(LOG_INFO, 'file too large');
}
 
// Check if we can upload to the specified path, if not DIE and inform the user.
if(!is_writable($upload_path)) {
  die('You cannot upload to the specified directory, please CHMOD it to 777.');
  syslog(LOG_INFO, 'cannot write to directory '.$upload_path); 
}
// use spaces to separate the different entities in the file name
$endpoint = $upload_path . 'userdata_' . $project . '_' . $user_name. '.csv';
// Upload the file to your specified path.
if(move_uploaded_file($_FILES['userfile']['tmp_name'],$endpoint))
  echo 'Your file upload was successful, view the file <a href="' . $endpoint . '" title="Your File">here</a>'; // It worked.
else
  echo 'There was an error during the file upload.  Please try again.'; // It failed :(.

// convert line endings from mac to linux, remove empty lines after conversion
exec("/usr/bin/tr '
' '\n' < \"".$endpoint."\" | /bin/sed '/^$/d' > /tmp/test.txt; mv /tmp/test.txt ".$endpoint);

?>
