<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Overview Measures</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Show distribution of arbitrary measures">
    <meta name="author" content="Hauke Bartsch">

    <!-- Le styles -->
    <link href="/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
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
          <a class="brand" href="#">Data Portal Overview Measures <span class="current-project"></span></a>
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
	<div class="span3" id="search-block">
	  <div class="form-group">
  	    <div id="history">
	      <ul id="historyList">
	      </ul>
	    </div>
 	    <input class="input-block-level ui-autocomplete-input form-control" data-original-title="Search dictionaries for measurement names" id="SearchField" placeholder="search the dictionary" autocomplete="off"></input>
      <center><div id="totals" style="font-size: 80%;"><span id="measures">-</span> measures available</div></center>
      <div id="usage-help" style="margin-top: 20px; margin-bottom: 20px; border: 1px solid #A0A0A0; background-color: #F0F0F0; border-radius: 3px; padding: 5px;"><small>Select two measures to display them together. Search for available measures by typing into the "search the dictionary" field.</small></div>
    </div>
	</div>
	<div id="result-block" class="span9">
	  <div id="ir_container"></div>
	  <div id="display">
	  </div>
	  <!-- space for horizontal list of last couple of boxplots -->
	</div>
      </div>
      
      <!-- <aside id="totals"><span id="measures">-</span> measures available</aside> -->
      
      <div class="footer">
        This visualization uses http://informationandvisualization.de/blog/box-plot.
      </div>

    </div> <!-- /container -->

    
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <!-- <script type="text/javascript" src="/js/HighChart/js/highcharts.js"></script> -->
    <script src="//code.highcharts.com/highcharts.js"></script>
    <!-- <script src="//code.highcharts.com/modules/data.js"></script> -->
    <script src="//code.highcharts.com/modules/drilldown.js"></script>
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
    <script src="js/boxplot.js"></script>
    <script src="js/all.js" type="text/javascript"></script>

  </body>
</html>
