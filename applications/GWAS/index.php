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
       	background-color: black;
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

    <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/themes/vader/jquery-ui.css" rel="stylesheet" type="text/css"/>

    <link href="css/base.css" rel="stylesheet" type="text/css"/>

  </head>
  <body>

    <!-- http://mmil-dataportal.ucsd.edu:3000/applications/SurfaceViewerSS/index.php?subjid=Y0181,Y0368&visitid=Y0181,Y0368 -->

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Data Portal Genome Wide Associations <span class="project_name"></span></a>
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

   echo('<script type="text/javascript"> user_name = "'.$user_name.'"; project_name = "'.$project_name.'"; </script>');

 ?>


   <div class="container-fluid">
     <div class="row-fluid"><div class="span12">
        <input type="text" id="yvalue" name="yvalue" class="yvalue input-block-level" value="MRI_cort_area.ctx.total" style="width: 100%; margin-top: 2px;" title="Select an independent variable for the statistical model." />
     </div></div>

     <div class="row-fluid"><div class="span12">
        <input type="text" id="covariates" name="covariates" class="covariates input-block-level" value="MRI_cort_area.ctx.total" style="width: 100%; margin-top: 2px;" title="Select a covariate." /> 
     </div></div>
     <div class="row-fluid"><div class="span12">
        <button id="runModel" name="runModel" class="btn btn-primary text-center">Genome Wide Association Study</button>
     </div></div>

     <hr>
     <div class="row-fluid info"><div class="span12">
        <div id="info_runs" style="background-color: gray;"></div>
     </div></div>

     <div class="row-fluid">
         <div id="runs" class="runs span12"> </div>
     </div>
     <div class="row-fluid"><div class="span12"></div></div>
   </div>

   <script src="js/jquery.min.js"></script>
   <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
   <script src="//code.highcharts.com/highcharts.js"></script>
   <script src="//code.highcharts.com/modules/drilldown.js"></script>
   <script src="js/jquery.appear.js"></script>
   <script type="text/javascript">
       // this re-defines the "$" operator, use jQuery() instead as $() is used by prototype
       jQuery.noConflict();
   </script>
   <script src="js/all.js"></script>
 </body>
</html>
