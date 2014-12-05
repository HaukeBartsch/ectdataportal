<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Overview</title>
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
    <link href="custom_styles.css" rel="stylesheet">
    <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/start/jquery-ui.css" rel="stylesheet" type="text/css"/>

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

 <?php 
   session_start(); /// initialize session

   include("../../code/php/AC.php");
   $user_name = check_logged(); /// function checks if visitor is logged.

   if (isset($_SESSION['project_name']))
      $project_name = $_SESSION['project_name'];
   else {
      // take the first project
      $projs = json_decode(file_get_contents('/code/php/getProjectInfo.php'),TRUE);
      if ($projs)
         $project_name = $projs[0]['name'];
      else
         $project_name = "Project01";
   }

   echo('<script type="text/javascript"> user_name = "'.$user_name.'"; project_name = "'.$project_name.'"; </script>');

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
          <a class="brand" href="#">Data Portal Overview <span class="current-project"></span></a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="/index.php">Home</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="current-project-description"></div>
      </div>
      <ul id="sets" class="nav nav-pills">
      </ul>
      <div id="charts">
	<div id="cortthick-chart" class="chart">
	  <div class="title" id="title1-text">Thickness</div>
	</div>
	<div id="area-chart" class="chart">
	  <div class="title" id="title2-text">Area</div>
	</div>
	<div id="volume-chart" class="chart">
	  <div class="title" id="title3-text">Volume</div>
	</div>
	<div id="age-chart" class="chart">
	  <div class="title" id="title4-text">Age</div>
	</div>
      </div>
      
      <aside id="totals"><span id="active">-</span> of <span id="total">-</span> scans selected.</aside>
      <br/><br/>
      <div id="lists">
	<div id="scan-list" class="list"></div>
      </div>
      
      <div class="footer">
        This visualization uses the crossfilter (http://square.github.io/crossfilter/) javascript library.
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
    <script src="js/d3.v3.min.js"></script>
    <script src="js/crossfilter.min.js" type="text/javascript"></script>
    <script src="sets.js" type="text/javascript"></script>
    <script src="custom_scripts.js" type="text/javascript"></script>

  </body>
</html>
