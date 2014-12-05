<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Filter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <!-- <link href="/css/bootstrap.css" rel="stylesheet"> -->
    <!-- <link href="/css/bootstrap-responsive.css" rel="stylesheet"> -->
    <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/themes/vader/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/js/highslide/highslide.css" />

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

    <link href="css/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
  </head>
  <body>

    <!-- http://mmil-dataportal.ucsd.edu:3000/applications/SurfaceViewerSS/index.php?subjid=Y0181,Y0368&visitid=Y0181,Y0368 -->


<nav class="navbar navbar-default navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Filter <span class="project_name"></span></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="/index.php">Home</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<!--    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="navbar-brand" href="#">Filter <span class="project_name"></span></a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="/index.php">Home</a></li>
            </ul>
          </div>--> <!--/.nav-collapse -->
        <!-- </div>
      </div>
    </div> -->

 <?php 
   session_start();

   include("../../code/php/AC.php");
   $user_name = check_logged(); /// function checks if visitor is logged.

   if (isset($_SESSION['project_name']))
      $project_name = $_SESSION['project_name'];

   echo('<script type="text/javascript"> user_name = "'.$user_name.'"; project_name = "'.$project_name.'"; </script>');

 ?>


 <div class="container-fluid">
 
     <!-- <div id="existingFilters" class="row-fluid">
       <button class="btn btn-primary">+</button>
     </div>
     <div id="createFilter" class="row-fluid">

     </div> -->
     <div class="row-fluid">
       <div id="start" class="col-xs-12"></div>
     </div>
 </div>

<!-- Loading animation container -->
<div class="loading" style="display: none;">
    <!-- We make this div spin -->
    <div class="spinner">
        <!-- Mask of the quarter of circle -->
        <div class="mask">
            <!-- Inner masked circle -->
            <div class="maskedCircle"></div>
        </div>
    </div>
</div>
<div id="place-for-popups"></div>
<!-- <div class="highslide-html-content" id="my-content" style="width: 300px">
  <div class="highslide-body">
     <div style="float: right; width: 100px">
         <div class="sliceCanvas" width="100px"></div>
     </div>
     <span id="bla"></span>
     <a href="#" onclick="return hs.close(this);">
       Close
     </a>
  </div>
</div> -->

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript" src="/js/highslide/highslide-full.min.js"></script>
<script type="text/javascript" src="/js/highslide/highslide.config.js" charset="utf-8"></script>
<script src="js/jquery.csv-0.71.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<!-- <script src="js/bootstrap.min.js"></script> -->
<script src="js/bootstrap-select.min.js"></script>
<script src="js/peg.0.8.0.js"></script>
<script src="js/all.js"></script>

</body>
</html>
