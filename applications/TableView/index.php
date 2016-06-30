<!DOCTYPE html>


<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Table Viewer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/css/bootstrap.css" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.js"></script>

    <script type="text/javascript">
           // this re-defines the "$" operator, use jQuery() instead as $() is used by prototype
           jQuery.noConflict();
        </script>
    <link rel="stylesheet" href="css/jquery.fileupload.css">
    <style>
       .progress-bar-success { 
            
            height: 18px; 
            background: green; 
            width: 0%;
            top: 50%;
        }
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="/css/bootstrap-responsive.css" rel="stylesheet">
    <link rel="stylesheet" href="css/jquery.fileupload.css">
    <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/start/jquery-ui.css" rel="stylesheet" type="text/css"/>
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.fileupload.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.fileupload-jquery-ui.js"></script>
    <script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.iframe-transport.js"></script>

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
?>


    <?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/applications/TableView/custom_scripts.inc') ?>
    <?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/applications/TableView/custom_styles.inc') ?>

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
          <a class="brand" href="#">Data Portal Table View <span class="project_name"></span></a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="/index.php">Home</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

      <H1>Table View</H1>

      <div align="right">Current selection <span id="rowcount"></span>.
      </div>

      <table id='TABLE1'><thead id='table1-thead-id'></thead><tbody id='table1-tbody-id'></tbody></table>
      Quick Find: <input type="text" id="quickfind"/><br>

    </div> 
<div class="modal fade" tabindex="-1" role="dialog" id="file-upload-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Data Upload</h4>
      </div>
      <div class="modal-body">
        <span class="btn btn-success fileinput-button">
          <i class="glyphicon glyphicon-plus"></i>
          <span>Select files...</span>
          <!-- The file input field used as target for the file upload widget -->
          <input id="fileupload" type="file" name="files[]" multiple>
        </span> 
        <br>

        <br>
      
      <div id="progress" class="progress">
        <div class="progress-bar progress-bar-success"></div>
    </div>
      </div>
      <div id="files" class="files" style="height: 100px; overflow-y: scroll;"></div>
      <br>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div> <!-- /.modal-header-->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

    
    <!-- /container -->
    
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> -->
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
<!--     <script src="js/jquery.lazyload.min.js" type="text/javascript"></script> -->

  </body>
</html>
