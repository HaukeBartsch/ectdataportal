<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Data Exploration</title>
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

    <script type="text/JavaScript" src="/applications/QC/qc.js"></script>
    <script type="text/JavaScript">
      // prevent a warning message on IE8/9 that will cause following javascript
      // code to not load, page will look ugly but is still functional on IE
      var curvyCornersVerbose = false;
    </script>
    <script type="text/JavaScript" src="/js/curvycorners.js"></script>
    <script type="text/JavaScript" src="/js/togetherjs-min.js"></script>
    <!-- <link rel="stylesheet" href="/css/reset.css" />
    <link rel="stylesheet" href="/css/text.css" />
    <link rel="stylesheet" href="/css/960_12_col.css" />
    <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/start/jquery-ui.css" rel="stylesheet" type="text/css"/> -->

    <link rel="stylesheet" href="all.css">
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
          <a class="brand" href="#">Data Exploration <span class="project_name"></span></a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="/index.php">Home</a></li>
	      <li><a href="#" onclick="TogetherJS(this); return false;" title="Invite another user into your Data Exploration session. The other user needs to have a valid account for your project." data-placement="bottom">Together</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

 <?php 
   session_start(); /// initialize session

   include("../../code/php/AC.php");
   $user_name = check_logged(); /// function checks if visitor is logged.

   

   if (isset($_SESSION['project_name']))
      $project_name = $_SESSION['project_name'];
   else {
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

   echo('<script type="text/javascript"> user_name = "'.$user_name.'"; project_name = "'.$project_name.'"; </script>');

   if (!empty($_GET["_v"])) {
      $version = $_GET["_v"];
   } else {
      $version = "";
   }
   if (!empty($_GET["command"])) {
      $com = $_GET["command"];
   } else {
      $com = "";
   }
   if (!empty($_GET["yvalue"])) {
      $yva = $_GET["yvalue"];
   } else {
      $yva = "";
   }
   if (!empty($_GET["functionOf"])) {
      $fuc = $_GET["functionOf"];
   } else {
      $fuc = "";
   }
   if (!empty($_GET["interaction"])) {
      $int = $_GET["interaction"];
   } else {
      $int = "";
   }
   if (!empty($_GET["expert"])) {
      $exp = $_GET["expert"];
   } else {
      $exp = "";
   }
   if (!empty($_GET["Dev"])) {
      $covDev = $_GET["Dev"];
   } else {
     if ($project_name == "PING")
        $covDev = "true";
      else
        $covDev = "";
   }
   if (!empty($_GET["SES"])) {
      $covSES = $_GET["SES"];
   } else {
     if ($project_name == "PING")
        $covSES = "true";
      else
        $covSES = "";
   }
   if (!empty($_GET["GAF"])) {
      $covGAF = $_GET["GAF"];
   } else {
     if ($project_name == "PING")
        $covGAF = "true";
      else
        $covGAF = "";
   }

  echo '<script type="text/javascript">
      var expert_mode_text    = "'.$exp.'";
      var covDev    = "'.$covDev.'";
      var covSES    = "'.$covSES.'";
      var covGAF    = "'.$covGAF.'";
      var version   = "'.$version.'";
      var command   = "'.$com.'";
      if (command == "") 
        command = "";
      var yvalue    = "'.$yva.'";
      //if (yvalue == "") 
      //   yvalue = "MRI_cort_area.ctx.total";
      var functionOf    = "'.$fuc.'";
      //if (functionOf == "") 
      //   functionOf = "s(Age_At_IMGExam)";
      var interaction    = "'.$int.'";
      // old interaction term: rs6551665_Genotype;
  ';
  echo '</script>';
?>

<?php

  // print out all the permissions
  $permissions = list_permissions_for_user($user_name);
  $p = '<script type="text/javascript"> permissions = [';
  foreach($permissions as $perm) {
    $p = $p."\"".$perm."\",";
  }
  echo ($p."]; </script>\n");

  $admin = false;
  if (check_role( "admin" )) {
     $admin = true;
  }
  echo('<script type="text/javascript"> admin = '.($admin?"true":"false").'; </script>'."\n");

  $can_data_exploration = FALSE;
  if (check_permission( "can-data-exploration" ) ) {
     $can_data_exploration = TRUE;
  }
?>


    <div class="container">

      <p>

<?php if ($can_data_exploration ) : ?>
         <!--  <iframe scrolling="no" width="960px" style="border:solid 0px #ccc;height:1400px;" 
                   src='InteractiveR.php?command=<?php echo(urlencode($com)."&yvalue=".$yva."&functionOf=".$fuc."&interaction=".$int."&expert=".$exp."&Dev=".$covDev."&SES=".$covSES."&GAF=".$covGAF."&_v=".$version); ?>'>
           </iframe> -->


           <div class="row-fluid" id="executeR">
            <form>
              <div class="span9"> <!-- left side -->
                <div class="row-fluid">
                  <div class="span5 offset2">
                    <button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" id="submit" role="button" type="submit" style="width: 100%; height: 50px;" title="Compute the model currently defined and create a scatter plot with model curves below.">Compute Model</button>
                  </div>
                  <div class="span5">
                    <button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" id="vertexStats" onclick="computeGlobalVertexStats(); return false;" style="width: 100%;  height: 50px;" title="Compute the model defined below for each point on the cortical surface. This computation ignores the predicted variable and replace it with either area expansion factor, thickness or volume expansion factor. The Surface Viewer application is called to display the resulting p-value maps." >Compute Surface Model</button>
                  </div>
                </div>
                <div class="row-fluid">
                  <div class="span2">
                    <div style="margin-top: 7px; text-align: right;">Predicted</div>
                  </div>
                  <div class="span5">
                    <input type="hidden" id="hidden_r"/>
                    <input type="text" id="yvalue" name="yvalue" class="yvalue input-block-level" value="MRI_cort_area.ctx.total" style="width: 100%; margin-top: 2px;" title="The dependent variable displayed on the x-axis of the scatter plot. Use any term listed in the ontology." />
                  </div>
                  <div class="span5">
                    <select id="vertex-model-selection" style="font-size: 1em; margin-top:2px; width: 100%;">
                      <option title="Area expansion factor is calculated by mapping individual brain surfaces to an atlas brain.">Area expansion factor</option>
                      <option title="Cortical thickness for each subjects brain">Thickness</option>
                      <option title="Volume expansion factor calculated as the produce of area expansion factor and thickness.">Volume expansion factor</option>
                      <option title="Change in Volume expansion factor over age.">Change in Volume expansion factor</option>
                    </select>                  
                  </div>
                </div>

                <div class="row-fluid">
                  <div class="span2">
                    <div style="margin-top: 7px; text-align:right;">Covariates</div>
                  </div>
                  <div class="span10">
                    <div class="row-fluid">
                      <div class="span8">
                        <input class="input-block-level" type="text" id="command" name="command" value="" style="margin-bottom: 0px;" title="Covariates are separated into user defined covariates and system defined covariates. Enter any term or combination of terms separated by '+'-sign that are defined in the ontology."/>
                      </div>
                      <div class="span4">
                        <div class="btn-group" data-toggle="buttons-checkbox" id="covariates_system">
                          <button type="button" class="btn btn-success btn-small" id="covariates_scanner" title="Device serial number of imaging scanner">Dev</button>
                          <button type="button" class="btn btn-success btn-small" id="covariates_SES" title="Socio-economic factors: household income, highest level of education">SES</button>
                          <button type="button" class="btn btn-success btn-small" id="covariates_GAF" title="Genetic ancestry factors: europe, africa, east asia, central asia, oceania">GAF</button>
                        </div>
                      </div>
                    </div>
<!--                  <div class="btn-group" data-toggle="buttons-checkbox" id="covariates_system" style="font-size: 6pt; width: 20%;">
                        <input id="covariates_scanner" type="checkbox" checked="true" title="DeviceSerialNumber">
                        <label for="covariates_scanner" title="Include DeviceSerialNumber as a scanner variable.">Dev</label>
                        <input id="covariates_SES" type="checkbox" checked="true" title="">
                        <label for="covariates_SES" id="label-SES" title="Include socio-economic status with highest education and the household income.">SES</label>
                        <input id="covariates_GAF" type="checkbox" checked="true">
                        <label for="covariates_GAF" title="Include genetic ancestry factors for Africa, AmerInd, EastAsia, Oceania, and CentralAsia.">GAF</label>
                      </div> -->
                  </div>
                </div>

                <div class="row-fluid">
                  <div class="span2">
                    <div style="margin-top: 7px; text-align:right">Predictor</div>   <!-- Independent Variable -->      
                  </div>
                  <div class="span10">
                      <input type="text" id="functionOf" name="functionOf" value="Age_At_IMGExam" class="input-block-level" style="margin-bottom: 0px;"  title="Select an independent variable for the statistical model. This variable will be displayed on the y-axis of the scatter plot below. Search the ontology for a list of available measures." />
                  </div>
                </div>

                <div class="row-fluid">
                  <div class="span2">
                    <div style="margin-top: 7px; text-align:right">Interaction by</div>
                  </div>
                  <div class="span10">
                    <input type="text" id="interaction" name="interaction" value="" class="input-block-level" style="margin-bottom: 0px;" title="If an interaction term is present it will be added to the model as interaction with predictor and as a main effect."/>
                  </div>
                </div>

                <!-- <div class="clearfix"></div>
                <div style="margin-top: 5px;">
                  <div class="span2">
                    <div style="height:23px; text-align:right">Covariates</div>
                    <div style="height:23px; text-align:right">Predictor Variable</div> 
                    <div style="height:23px; text-align:right">Interaction by</div>
                  </div>
                  <div class="span6 alpha omega">
                    <div style="height: 23px;">
                      <input type="text" id="command" name="command" value=""/>&nbsp;+
                      <div id="covariates_system" style="font-size: 6pt; display: inline;">
                        <input id="covariates_scanner" type="checkbox" checked="true" title="DeviceSerialNumber">
                        <label for="covariates_scanner" title="Include DeviceSerialNumber as a scanner variable.">Dev</label>
                        <input id="covariates_SES" type="checkbox" checked="true" title="">
                        <label for="covariates_SES" id="label-SES" title="Include socio-economic status with highest education and the household income.">SES</label>
                        <input id="covariates_GAF" type="checkbox" checked="true">
                        <label for="covariates_GAF" title="Include genetic ancestry factors for Africa, AmerInd, EastAsia, Oceania, and CentralAsia.">GAF</label>
                      </div>
                    </div>
                    <div style="height: 23px;">
                      <input type="text" id="functionOf" name="functionOf" value="Age_At_IMGExam"/>
                    </div>
                    <div style="height: 23px;">
                      <input type="text" id="interaction" name="interaction" value=""/>
                    </div>
                  </div>
                </div> -->
              </div>
              <!-- <div class="grid_2">&nbsp;</div> -->
              <div class="span3" style="height: 60px; margin-top: 45px;">
                <div class="row-fluid">
                  <div class="span3" style="line-height: 30px;">
                    <span title="Color scatter plot points by:">Color:</span>
                  </div>
                  <div class="span9">
                    <select id="grouping_variable" style="width: 100%;">
                      <option value="PlotGroup">Interaction</option>
                      <option value="Site">Site</option>
                      <option value="Gender">Gender</option>
                      <option value="Group">Group</option>
                    </select>
                  </div>
                </div>
                <div class="row-fluid">
                  <div class="span3" style="line-height: 30px;">
                    Search:
                  </div> 
                  <div class="span9">
                    <input id="Onto" placeholder="lookup name" class="input-block-level" title="Search data dictionaries for measurements. Start a search for a subject-id with an @-character."></input>
                  </div>
                </div>
                <div style="border-radius: 3px; border: 0px solid #AAA; padding: 0px 0px 0px 0px; margin-top: 5px;">
                   Help:
                   <!--   <a href="#" id="word-cloud" title="Open word-cloud"><span class="ui-icon ui-icon-comment" style="border: 1px solid #AAA; border-radius: 3px;"></span>Word-Cloud</a> --> <!-- <button id="word-cloud" title="Word Cloud">Word-Cloud</button> -->
                   <a href="/img/3DLS8.pdf" title="Surface Model" style="float: right;"><span style="position:absolute; margin-left: -20px; margin-top: 2px;" class="ui-icon ui-icon-comment"></span>ROI</a>
                   <a href="/applications/Ontology/hierarchy.php?entry=display" title="Ontology" target="Ontology" style="float: right; width: 90px;"><span class="ui-icon ui-icon-shuffle" style="position: absolute; margin-left: -20px; margin-top: 2px;"></span>Ontology</a>
                </div>
                <button id="open_expert_mode" style="width: 100%; height: 30px; margin-top: 6px;" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="return false;"><span class="ui-button-text-only" id="expert-mode-button-text" style="font-size: 10pt;">Open Expert Mode</span></button>
              </div>

              <div class="clearfix">&nbsp;</div>
              <div class="row-fluid" id="expert" style="display: none;">
                <h3 class="span12 ui-accordion-header ui-helper-reset ui-state-default ui-corner-all">
                  <button id="upload_extra_data" onclick="return false;" style="position: relative; float: right; margin-right: 5px; top: 2px; font-size: 14px;">upload user spreadsheet</button>
                  <button id="download_data_model" onclick="return false;" style="position: relative; float: right; top: 2px; font-size: 14px;">download data and model</button>
                  <!-- <a href="#" tabindex="-1" style="position: relative; left: 20px; top: -5px; font-size: 1em;">Expert Mode</a> -->
                </h3>
                <textarea id="expert_text" class="span12" rows="10" cols="80" style="overflow: scroll; overflow-x: auto;"></textarea>
              </div>
            </form>
  </div>

  <div class="clearfix">&nbsp;</div>
  <!-- <div class="spacer">&nbsp;</div>-->
  <div id="ir_container" class="span11" style="height: 500px; width=860px;">
     <h4>How this works:</h4>
     Start by identifying variables of interest using the ontology. Enter independent and dependent variables into the predicted and predictor text fields. Press "Compute Model" to start the model analysis. Significance values are available in the Statistical Summary section at the bottom of this page.
  </div>
  <div id="export-button" class="ui-state-default ui-corner-all" style="padding:1px 1px 1px 1px;cursor: pointer;position: relative; float: left; display: none; top: 10px; left: -9px;"><span class="ui-icon ui-icon-suitcase" title="Export currently displayed data as .csv."></span></div>

  <div class="clearfix"></div>

  <div class="span12">&nbsp;</div>
  <div class="row-fluid">
    <div class="span11 offset1" id="sum" style=""></div>
  </div>
  <div class="clearfix"></div>
  <!-- <div id="spacer"></div> -->
  <div class="row-fluid">
    <div class="span12" id="StatSummary"></div>

    <div class="clearfix"></div>
    <div id="spacer"></div>
  </div>
  <div class="row-fluid">
    <div class="span12">
      <div id="accordion" autoHeight="false">
        <h3><a href="#">Statistical Summary</a></h3>
        <div style="padding:5px; height: 200px;">
          <textarea id="summary" style="overflow:scroll;overflow-x:auto;width:98%;height:89%;padding:5px;"></textarea>
        </div>
<!--        <h3><a href="#">Examples</a></h3>
        <div>
          All examples assume that the data version 0.0 is active.
          <h5 style="margin-bottom: 5px;">Vocabulary score as dependent variable</h5>
          <span id="help9"><u>A. Independent variables Age, Sex, and Age by Sex</u><span id="TBX_vocab_base"></span></span><br>
          <span id="help10"><u>B. Independent variables Age, Sex, Age by Sex, and SES</u> <span id="TBX_vocab_he"></span></span><br>
          <span id="help13"><u>C. Independent variables CortArea.Total, Sex, Age by Sex, and SES</u><span id="surfarea_he2"></span></span><br><br>
          <h5 style="margin-bottom: 5px;">Total cortical surface as dependent variable</h5>
          <span id="help11"><u>A. Independent variables Age, Sex, and Age by Sex</u><span id="surfarea_base"></span></span><br>
          <span id="help12"><u>B. Independent variables Age, Sex, Age by Sex, and SES</u><span id="surfarea_he"></span></span><br><br>
          <h5 style="margin-bottom: 5px;">Miscellaneous</h5>
          <div id="help5"><u>dccs_score, Base + SES + GAF</u><span id="TBX_dccs_score_r"></span></div>
          <div id="help6"><u>flanker_score, Base + SES + GAF</u><span id="TBX_flanker_score_r"></span></div>
          <div id="help7"><u>reading_score, Base + SES + GAF</u><span id="TBX_reading_score_r"></span></div>
          <div id="help8"><u>ibam_score, Base + SES + GAF</u><span id="TBX_ibam_score_r"></span></div>
          <div id="help14"><u>Hippocampus asymmetry</u><span id="HippcampusAsym_r"></span></div>
          <div id="help15"><u>Genetics (SNP) test</u><span id="genetics_r"></span></div>

        </div> -->
      </div>
    </div>
  </div>
  <div id="spacer"></div>

  <!-- an (initially) invisible drop-down list -->
  <div id="ontology_container" class="ontology_container hidden" style="left: 332px; top: 192px; width: 366px; "> </div>

</div> <!-- container_12 -->

<!-- content for the popup -->
<div id="place-for-popups-dialog" style="height: 200px; overflow-y: scroll; display: none;">
  <div id="place-for-popups" style="height: 200px"></div>
</div>
<div class="highslide-html-content" id="my-content" style="width: 300px">
  <div class="highslide-body">
     <div style="float: right; width: 100px">
         <canvas id="sliceCanvas" width="100px"></canvas>
     </div>
     <span id="bla"></span>
     <a href="#" onclick="return hs.close(this);">
       Close
     </a>
  </div>
</div>

<!-- content for word cloud dialog -->
      <div id="word-cloud-content" class="wordcloud" style="display: none;">
      </div>

<!-- content for data upload -->
      <div id="upload" style="display:none;" title="Table upload" class="small">
        <p>Upload a spreadsheet as a comma separated (csv) table. Specify a "SubjID" column to identify scans and add feature columns with unique column names. The new values will be added to your personal data store and are available in the model definition.</p>
        <p>If a prior table exists it can be seen <a class="btn" href="#" onclick="show_extra_data();">here</a>. If no prior table exists the dialog will create a table that you can extend with new columns and upload.</p> 
        <p>Delete the current table by selecting this <a href="#" class="btn" onclick="delete_extra_data();">link.</a></p>
        <br/>
        <h4>Upload new user specific CSV file:</h4>
        <form title="Table upload" class="well control-group" action="./upload.php" method="post" enctype="multipart/form-data">
                <input type="file" class="span4" placeholder="enter a new patient name" name="userfile">
                <input type="hidden" name="MAX_FILE_SIZE" value="20000000"/>
                <input type="hidden" name="project" id="upload_project_name" class=""/>
                <input type="hidden" name="version" id="upload_version_number" class=""/>
                <button>Upload CSV</button>
        </form>
      </div>
<!-- content for display of uploaded data -->
      <div id="show-user-data" style="display: none;" title="Current extra user data">
        <textarea cols="80" rows="20" style="width: 100%;" id="text-area-user-data">
        </textarea>
      </div>
<!-- content for downloading package -->
      <div id="download-data-as-package" style="display: none;" title="Download data and model">  
        Download <a href="http://www.r-project.org">R</a> source code and merged spreadsheets for the current model using the following link:
        <center><a id="download-data-as-package-link" href='#'>package.zip</a></center>
      </div>
      


<script type="text/javascript" src="/js/prototype.js"></script>
<script type="text/javascript" src="/js/st/effects.js"></script>
<script type="text/javascript" src="/js/st/dragdrop.js"></script>
<script type="text/javascript" src="/js/st/controls.js"></script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script type="text/javascript">
       // this re-defines the "$" operator, use jQuery() instead as $() is used by prototype
       jQuery.noConflict();
    </script>

<!-- <script src="//code.jquery.com/jquery-1.7.2.min.js"></script> -->
<!-- <script src="//code.jquery.com/ui/1.8.22/jquery-ui.min.js"></script> -->
<script type="text/javascript" src="/js/highslide/highslide-full.min.js"></script>
<script type="text/javascript" src="/js/highslide/highslide.config.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="/js/highslide/highslide.css" />
<script type="text/javascript" src="/js/HighChart/js/highcharts.js"></script>
<script type="text/javascript" src="/js/HighChart/js/modules/exporting.js"></script>
<script type="text/javascript" src="/js/jquery.mousewheel.min.js"></script>
<!-- <script type="text/javascript" src="/js/jquery.tooltip.min.js"></script> -->
<script type="text/javascript" src="/js/jquery.awesomeCloud.min.js"></script>
<script src="/js/jquery.ui.touch-punch.min.js"></script>
<script type="text/javascript" src="/js/pixastic/pixastic.core.js"></script>
<script type="text/javascript" src="/js/pixastic/pixastic.jquery.js"></script>
<script type="text/javascript" src="/js/pixastic/actions/brightness.js"></script>
<script type="text/javascript" src="/js/mpr.js"></script>

<?php else : ?>
        The current user does not have permissions to use the Data Exploration application. Please contact your system administrator to get the proper permissions setup.
<?php endif; ?>
      </p>


    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> -->
    <script src="/js/bootstrap.min.js"></script>
<!--     <script src="/js/bootstrap-transition.js"></script>
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
    <script src="/js/bootstrap-typeahead.js"></script> -->

    <script type="text/javascript">
       // logout the current user
       function logout() {
         jQuery.get('/code/php/logout.php', function(data) {
           if (data == "success") {
             // user is logged out, reload this page
             location.reload();
           } else {
             alert('something went terribly wrong during logout: ' + data);
           }
         });
       }

       jQuery(document).ready(function() {
          checkLegal();
          jQuery('.project_name').text(project_name);
       });
    </script>

<?php
   include '../../legal.php';
?>
    <script src="/js/legal.js"></script>

    <script type="text/javascript" src="all.js"></script>

  </body>
</html>
