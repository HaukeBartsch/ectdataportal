<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Data Exploration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/img/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/img/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/img/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="/img/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="/img/favicon.png">

    <link href="css/all.css" rel="stylesheet" type="text/css" />
    <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/themes/vader/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <link href="js/jquery.minicolors.css" rel="stylesheet" type="text/css"/>    

  </head>
  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Data Portal Surface Viewer</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="/index.php">Home</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

 <?php 
   session_start();

   include("../../code/php/AC.php");
   $user_name = check_logged(); /// function checks if visitor is logged.

   if (isset($_SESSION['project_name']))
      $project_name = $_SESSION['project_name'];
   else { // not correct if Project01 does not exist!
      $_table = "../../data/table.json";

      $projects = json_decode( file_get_contents($_table), true);
      $allowedProjects = array();
      foreach( $projects as $project ) {
         if ($user_name == "admin" || check_permission( $project['name'] )) {
           $allowedProjects[] = $project;
         }
      }
      $project_name = $allowedProjects[0]['name'];
   }

   if (isset($_GET['cookie'])) {
      $cookie = $_GET['cookie'];
   } else {
      $cookie = "";
   }
   if (isset($_GET['request'])) {
      $request = $_GET['request'];
   } else {
      $request = 0;
   }
   if (isset($_GET['stats'])) {
      $stats = $_GET['stats'];
   } else {
      $stats = "";
   }

   echo('<script type="text/javascript"> user_name = "'.$user_name.'"; project_name = "'.$project_name.'"; cookie = "'.$cookie.'"; request = '.($request+0).'; stats = '.json_encode($stats).';</script>');

   ?>


    <div class="container">

      <div class="row-fluid">
        <div id="info" style="font-size:33pt;">
         Average Cortical Surface
        </div>
      </div>
      <div class="row-fluid">
        <div class="span12">
          <p align=center>
           <!-- <iframe scrolling="no" width="960px" style="border:solid 0px #ccc;height:756px;" 
                   src='surfaceviewer.php?<?php echo("cookie=".$cookie."&request=".$request); ?>'>
           </iframe> -->
           <?php include 'surfaceviewer.php' ; ?>
           <div id="renderArea"></div>
          </p>
        </div>
      </div>
      <div class="row-fluid">
        <div class="span12">
           <div id="summary" style="overflow:scroll;overflow-x:auto;width:98%;height:300px;padding:5px;"></div>
        </div>
      </div>
      <div class="row-fluid">
        <div class="span12"><hr></div>
      </div>


    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="/js/bootstrap-transition.js"></script>
    <script src="/js/bootstrap-alert.js"></script>
    <script src="/js/bootstrap-modal.js"></script>
    <script src="/js/bootstrap-dropdown.js"></script>
    <script src="/js/bootstrap-scrollspy.js"></script>
    <script src="/js/bootstrap-tab.js"></script>
    <script src="/js/bootstrap-tooltip.js"></script>
    <script src="/js/bootstrap-popover.js"></script>
    <script src="/js/bootstrap-button.js"></script>
    <script src="/js/bootstrap-collapse.js"></script>
    <script src="/js/bootstrap-carousel.js"></script>
    <script src="/js/bootstrap-typeahead.js"></script>

  <script src="js/three.min.js"></script>
        <script src="js/ShaderExtras.js"></script>
    
        <script src="js/postprocessing/EffectComposer.js"></script>
  <script src="js/postprocessing/RenderPass.js"></script>
  <script src="js/postprocessing/ShaderPass.js"></script>
  <script src="js/postprocessing/MaskPass.js"></script>
  <script src="js/postprocessing/BloomPass.js"></script>
  <script src="js/postprocessing/FilmPass.js"></script>

  <script src="js/loaders/VTKLoader.js"></script>
  <script src="js/Detector.js"></script>
  <script src="js/Tween.js"></script>
  <script src="js/Threex.screenshot.js"></script>
  <!-- <script type="text/javascript" src="http://www.google.com/jsapi"></script> -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
        <!-- <script src='//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js' type='text/javascript'></script> -->
        <!-- <script type="text/javascript"> 
           //google.load("jqueryui", "1.8"); 
        </script> -->
  <script type="text/javascript" src="js/jquery.jeditable.js"></script>
  <script type="text/javascript" src="js/jquery.minicolors.js"></script>  

<?php
  if (empty($_GET["cookie"])) {
      $cookie = "";
  } else {
      $cookie = $_GET["cookie"];
        }
  if (empty($_GET["request"])) {
      $request = 0;
  } else {
      $request = $_GET["request"];
        }
        echo "<script type=\"text/javascript\">\n";
        echo "  cookie   = \"".$cookie."\";\n";
        echo "  request  = \"".$request."\";\n";
        echo "</script>\n";
?>
    
    <script type="text/javascript" src="js/all.js"></script>

  </body>
</html>
