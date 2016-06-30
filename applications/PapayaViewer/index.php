<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
    <meta charset="utf-8">
    <title>Data Portal Image Viewer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="/css/bootstrap-responsive.css" rel="stylesheet">
     <style type="text/css" media="all">
    body { 
       background-color: #ffffff;
       background: none repeat scroll 0 0 #FFFFFF;
       overflow: scroll;
    }
    .hidden { display: none; }
    input .search_term { 
      margin-left: 2px;
    }
    #page {
      width: 100%;
      height: 900px;
    }
    #control {
      margin-top: 5px;
    }
    #slide-wrap {
      height:147px;
      overflow-x:scroll;
      background:#FFFFFF;
      padding:0 0px; 
    }
    #inner-wrap {
      float:left;
      margin-right:-32767px;/*Be safe with Opera's limited neg margin of -32767px*/
    }
    #wrapper {
      width:100%;
      min-width:600px;
      max-width:1100px;
      margin:10px auto;
      border:0px solid #000;
      background:#CCC;
      padding:0px 0 0;
    }
  </style>
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
        <script type="text/javascript">
           // this re-defines the "$" operator, use jQuery() instead as $() is used by prototype
           jQuery.noConflict();
        </script>
        <link rel="stylesheet" type="text/css" href="css/papaya.css?version=0.8&build=1148"/>
        <script type="text/javascript" src="js/papaya.js?version=0.8&build=1148"></script>
        <title>Papaya Viewer</title>
     	
     <script type='text/javascript'>
     var project="";
     var params =[];
  </script>

 <?php 
   #
   #
   session_start(); /// initialize session
   include("../../code/php/AC.php");
   $user_name = check_logged(); /// function checks if visitor is logged.
   if (isset($_SESSION['project_name'])){
      $project_name = $_SESSION['project_name']; 
   } else {
      $project_name = "Project01";
   }
   echo('<script type="text/javascript"> user_name = "'.$user_name.'"; project_name = "'.$project_name.'"; </script>');

   if (isset($_GET["patient"])) {
       $subject_id = $_GET['patient'];
   }
   else {
     $subject_id='XXX-001';
   }
   if (isset($_GET["visit"])) {
       $visitid = $_GET['visit'];
   }

  $loc = '/data/' . $project_name .'/raw/'.$subject_id.'/'.$visitid.'/' ;
  //print_r($loc);
  $loc2= '../..'.$loc;
  $files = scandir("$loc2");
  $filesWithPath = array(); 
  foreach ($files as &$item) {
    if ($item == "." || $item == "..")
      continue;
    $filesWithPath[] = $loc.$item;
  }
  $files = $filesWithPath;
?>

  <script>
  params['images']=[[
  <?php
    echo '"'.implode('","', $filesWithPath).'"' 

  ?>
  ]];
  </script>

    </head>
    <body style=''>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Data Portal Image Viewer</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="/index.php">Home</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

        <div style="margin-top: 60px;">

          <!--  <select id="fileupload">
              <option>Image 1</option>
              <option>Image 2</option> 
           </select>  --> 
        </div>


   <!--      <script>

             jQuery(document).ready(function() {
                 jQuery('#choices').change(function() {

                    console.log('hi, I am here');
                    alert('hi');
                 });
             });
        </script> -->
     
        <div class="papaya" data-params='params'></div>


    </body>
</html>
