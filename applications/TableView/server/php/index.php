<?php
/*
 * jQuery File Upload Plugin PHP Example
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');
$error_messages = "";

$urlHolder = NULL;


// This option will be used while uploading the file.
// The path will come as POST request.
if( isset($_POST['fuPath']) ) {
    // perform validations to make sure the path is as you intend it to be
    $urlHolder = filter_var($_POST['fuPath'], FILTER_SANITIZE_URL);
}
// This option will be used when deleting a file.
// The file details will come from GET request so have to be careful
else if( isset($_GET['fuPath']) ){
    // perform validations to make sure the path is as you intend it to be
    $urlHolder = filter_var($_GET['fuPath'], FILTER_SANITIZE_URL);
}
else{
    exit;
}
$options = array(
                'upload_dir'=>  $_SERVER['DOCUMENT_ROOT'] .'/' .$urlHolder,
                'image_versions' => array(),            // This option will disable creating thumbnail images and will not create that extra folder.
                                                        // However, due to this, the images preview will not be displayed after upload
            );

if($urlHolder){
    $upload_handler = new UploadHandler($options , true , $error_msg);
    //echo("ERROR MESSAGE FROM UPLOADHANDLER: " . $error_msg);
}

