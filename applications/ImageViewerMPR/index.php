<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Data Portal Image Viewer</title>
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
    <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/start/jquery-ui.css" rel="stylesheet" type="text/css"/>


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
<?php
  echo "<script type='text/javascript'>";
  if (isset($_GET["project"])) {
    $project_id = $_GET['project'];
    echo "  project_id = \"".$_GET["project"]."\";";
  } else {
    $project_id = "PING";
    echo "  var project_id = \"PING\";";
  }
  if (isset($_GET["patient"])) {
     $patient = $_GET['patient'];
     echo "  var patient = \"".$_GET["patient"]."\";\n";
  } else {
     $patient = 'P004900001';
     echo "  var patient = 'P004900001';\n";
  }
  if (isset($_GET["visit"])) {
     $visit = $_GET['visit'];
     echo "  var visit = \"".$_GET["visit"]."\";\n";
  } else {
     $visit = '20100312';
     echo "  var visit = 20100312;\n";
  }
  echo "</script>";
?>

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
          <a class="brand" href="#">Data Portal Image Viewer</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="/index.php">Home</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

 <?php 
   session_start(); /// initialize session

   include("../../code/php/AC.php");
   $user_name = check_logged(); /// function checks if visitor is logged.

   echo('<script type="text/javascript"> user_name = "'.$user_name.'"; project_name = "'.$project_id.'"; </script>');

   if (!empty($_GET["_v"])) {
      $version = $_GET["_v"];
   } else {
      $version = "";
   }

   // lets check what modalities are present at that location
   $loc = '../../data/' . $project_id . '/' . $project_id . '_webcache/' . $patient . '/' . $visit . '/JPEG/*';
   $dirs = glob($loc,GLOB_ONLYDIR);
   echo('<script type="text/javascript"> modalities = [');
   $count = 0;
   foreach ($dirs as $d) {
     if ($count > 0)
       echo (',');
     $mod = end(explode('/',$d));
     echo ('"'.$mod.'"');
     $count = $count + 1;
   }
   echo ('];</script>');

?>

    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/js/jquery.mousewheel.min.js"></script>
    <script type="text/javascript" src="/js/pixastic/pixastic.core.js"></script>	
    <script type="text/javascript" src="/js/pixastic/pixastic.jquery.js"></script>
    <script type="text/javascript" src="/js/pixastic/actions/brightness.js"></script>
    <script type="text/javascript" src="/js/mpr.js"></script>

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

    <script type="text/javascript">
      // this re-defines the "$" operator, use jQuery() instead as $() is used by prototype
      jQuery.noConflict();
    </script>

    <div class="container">

      <p align=center>
         <?php include 'imageviewermpr.php'; ?>
      </p>

    </div> <!-- /container -->

  </body>
</html>
