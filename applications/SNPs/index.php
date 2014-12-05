<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>SNP browser</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/sausage.css" rel="stylesheet">
    <link href="css/sausage.reset.css" rel="stylesheet">
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
      $projs = json_decode(file_get_contents('../../code/php/getProjectInfo.php'),TRUE);
      if ($projs)
         $project_name = $projs[0]['name'];
      else
         $project_name = "Project01";
   }
   echo('<script type="text/javascript"> user_name = "'.$user_name.'"; project_name = "'.$project_name.'"; </script>');

   if (isset($_SESSION['version']))
     $version = $_SESSION['version'];
   else
     $version = "";
   echo('<script type="text/javascript"> version = "'.$version.'"; </script>');

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
          <a class="brand" href="#">Data Portal SNP Browser <span class="current-project"></span></a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="/index.php">Home</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container" id="content">
      <div class="row small" style="margin-bottom:10px;">
         <span id="num-current">-</span> of <span id="num-total">-</span><sup title="Illumina Human660W-Quad BeadChip">*</sup> single nucleotide polymorphisms match the search
      </div>
      <div class="pull-right" style="position: absolute; right: 20px; top: 60px;">
         <textarea id="list-of-snps"></textarea><br/>
         <button class="btn btn-primary" id="download-now" onclick="downloadSNPs();" data-loading-text="download..." title="This page allows you to download a maximum of 5,000 single nucleotide polymorphism vectors for the current study.">download</button>
         <button class="btn" onclick="jQuery('#list-of-snps').val('');jQuery('.add-to-download').removeClass('btn-primary');">clear</button>
      </div>
      <div class="row">
        <div class="snp-browser">
           <input id="gene-name" type="text" placeholder="Gene name (SHH)" title="Use regular expressions for searches."/><br/>
           <input id="snp-name" type="text" placeholder="SNP name"/>
           <input id="chromosome" type="text" placeholder="chromosome number" title="1-22,X=23,Y=24,exact search=^3$."/>
           <div class="input-append" style="display: inline-block;line-height: 20px; margin-bottom: 10px;vertical-align:middle;">
              <input class="span2" id="basepair" type="text" placeholder="basepair location" title="Either a number, or a range (two numbers separated by '-').">
              <div class="btn-group">
                  <button class="btn dropdown-toggle" data-toggle="dropdown">
                     range
                     <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                     <li><a href="#" onclick="switchRange(1);">exact</a></li>
                     <li><a href="#" onclick="switchRange(10);">10</a></li>
                     <li><a href="#" onclick="switchRange(100);">100</a></li>
                     <li><a href="#" onclick="switchRange(1000);">1,000</a></li>
                     <li><a href="#" onclick="switchRange(10000);">10,000</a></li>
                     <li><a href="#" onclick="switchRange(100000);">100,000</a></li>
                     <li><a href="#" onclick="switchRange(1000000);">1,000,000</a></li>
                  </ul>
              </div>
           </div>
        </div>
      </div>

      <div class="page-set"></div>      

      <div class="footer">

      </div>

    </div> <!-- /container -->

    <div id="showResults" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="showResultsLabel" aria-hidden="true">
       <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
           <h3 id="showResultsLabel">Download SNPs</h3>
       </div>
       <div class="modal-body">
           <p>Copy and save this table into a text file with the extension .csv ...</p>
           <textarea id="result-text-area" rows="6" style="width: 94%;">wait for it...</textarea>
           <p><span title="Download is limited for each user to a maximum number of SNPs for each project. You will not be able to download more SNP data if the download limit is reached." id="result-text-area-info" style="float: right;"></span></p>
       </div>
       <div class="modal-footer">
          <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
       </div>
    </div>

    
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
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
  <!--   <script src="js/jquery.auto.pagination.min.js"></script>
    <script src="js/jquery.scrollUp.min.js"></script> -->
 
    <script src="js/jquery.sausage.min.js" type="text/javascript"></script>
    <script src="custom_scripts.js" type="text/javascript"></script>

  </body>
</html>
