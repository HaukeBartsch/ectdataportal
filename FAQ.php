<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Data Portal Frequently Asked Questions</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="img/favicon.png">
    <link href="/js/google-code-prettify/prettify.css" rel="stylesheet">
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
          <a class="brand" href="#">Data Portal</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="/index.php">Home</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container" style="margin-bottom: 20px;">

      <h1>Data Portal Frequently Asked Questions</h1>
      <p>The following is a loose list of questions and answers that might help users which are new to the portal.</p>

      <ul>
        <li>
          <p class="muted" href="WhatIsDataportal">What is the Data Portal?</p>
          <p class="text-info">A web-based data analysis tool with advanced visualization feature.</p>
        </li>
        <li>
	  <p class="muted" href="WhatIsR">What is R?</p>
          <p class="text-info">Software for statistical analysis widely used and freely available.</p>
        </li>
        <li>
	  <p class="muted" href="HowToWebGL">How to enable WebGL?</p>
          <p class="text-info">WebGL is a browser based new technology that allows us to use OpenGL inside the browser window. Data Portal data such as p-value maps are calculated and transmitted to the client machine which renderes these colormap as interactive graphics on your machine. This technology needs to be enabled on some browsers such as Safari. In general a newer browser on a machine with a dedicated graphics chip is better able to display this type of content.</p>
        </li>
        <li>
	  <p class="muted" href="WhatIsHTML5">What is HTML5?</p>
          <p class="text-info">HTML5 is a collection of tools that help to improve the user experience inside a web-browser. A goal of the initiative is to make user interfaces inside a browser platform as reactive as stand-along applications.</p>
        </li>
        <li>
	  <p class="muted" href="HowProvideData">How do I provide data?</p>
          <p class="text-info">The data administration tool is supposed to allow for a convenient import of user defined project data. Curation of data should be done on the user end but new versions of data can be ingested by the portal. The basic file format used is comma-separated value files (.csv). After uploading a new spreadsheet a version control system (git) stores a version of the data.</p>
        </li>
        <li>
	  <p class="muted" href="HowToStartWorking">How do I start analyzing data?</p>
          <p class="text-info">The DataExploration application is currently the best interface to visually inspect waste amounts of data. Formulate a simple model using a variable of interest and plot it over age. The resulting scatter plot will show the data and a model of the data as a straight line. Instead of using a straight line to model the data a smooth version of the age curve can be used if instead of "Age", "s(Age)" is used to specify the predictor variable.</p>
        </li>
        <li>
	  <p class="muted" href="RestrictAnalysis">How do I restrict my analysis to a sub-set of the data available?</p>
          <p class="text-info">The Data Exploration page of the portal provides the Expert Mode text field. The text available in this field is in R-syntax and is executed right after the data is loaded. Any code that limits the data entered in the Expert Mode field will restrict the data used during statistical analysis.</p>
          <p class="text-info">Example: A sub-range of age values can be specified as <code>data <- data[data$Age > 10 & data$Age < 15,]</code> which restricts the analysis to scans that have an "Age" column value greater than 10 and smaller than 15. This will only work for columns that have numerical values.
          </p>
          <p class="text-info">Example: A sub-set of categorical (text) values can be specified as
<pre class="prettyprint linenums lang-js" style="font-size:9pt; border: 0px;">
  data <- data[data$Group == "Control" & data$Group == "X",]
  data$Group <- factor(data$Group)
</pre>
          </p>
          <p class="text-info">
            The second line is required to recompute the levels for the variable before it can be used in a model.
          </p>
        </li>
        <li>
	  <p class="muted" href="Smoothness">How to set the smoothness of model curves?</p>
          <p class="text-info">The model fitting code uses a conservative smoothness setting that results in stiff model curves. The smoothness can be adjusted by the user using the Expert Mode. Add the following two lines to get the default smoothness values:
<pre class="prettyprint linenums lang-js" style="font-size:9pt; border: 0px;">
  params.k.main = 4
  params.k.inter = 3
</pre></p>
          <p class="text-info">The k.main parameter specifies the stiffness of the model curves for the main effect. The interaction effect is controlled by the k.inter variable. Higher values (whole numbers) of the parameter will result in model curves that are less stiff.</p>
        </li>
        <li>
	  <p class="muted" href="quantiles">How to create curves for continuous variables?</p>
          <p class="text-info">The "Interaction by" field creates different curves for variables that are categorical (like Gender). Create a categorical variable from the measure of interest (like vocab scores). Add a (yet) non-existing variable name into the "Interaction by" field, for example VOCAB_TERTILE. Open the "Expert Mode" text field and add the following line that defines the new variable VOCAB_TERTILE using tertiles of the vocabulary score:
<pre class="prettyprint linenums lang-js" style="font-size:9pt; border: 0px;">
  data$VOCAB_TERTILE <- cut(data$TBX_VOCAB_THETA,quantile(data$TBX_VOCAB_THETA,probs=seq(0,1,0.33),na.rm=TRUE))
</pre>
          </p>
          <p class="text-info">The quantile function is used to create the three ranges based on the continuous variable TBX_VOCAB_THETA. The cut function uses those ranges to create the new factorial variable.</p>
        </li>
        <li>
           <p class="muted" href="sobel">How to apply a Sobel test?</p>
           <p class="text-info"><a href="http://www.inside-r.org/packages/cran/multilevel/docs/sobel">The documentation of the multilevel sobel test</a> states:<br/>
<it>"The function provides an estimate of the magnitude of the indirect effect, Sobel's first-order estimate of the standard error associated with the indirect effect, and the corresponding z-value. The estimates are based upon three models as detailed on page 84 of MacKinnon, Lockwood, Hoffman, West and Sheets (2002)."</it><br/>
Add the following R-code to the expert mode text field of the Data Exploration page:
<pre class="prettyprint linenums lang-js" style="font-size:9pt; border: 0px;">
  library(multilevel)
  cat("## model summary\n")
  s=sobel(pred=data$Age,med=data$MRI_cort_area.ctx.total,out=data$MRI_cort_area.ctx.rh.entorhinal)
  cat("N:", s$N, "\n")
  cat("SE:", s$SE, "\n")
  cat("Indirect.Effect:", s$Indirect.Effect, "\n")
  cat("z.value:", s$z.value, "\n")
</pre>
          </p>
          <p class="text-info">In order for this to work the multilevel package needs to be added to R on the server. Notify your system administrator to get additional R libraries to be installed.</p>
        </li>
        <li>
           <p class="muted" href="sobel">How to encode dichotomous (two-valued) predictors?</p>
           <p class="text-info">A predictor that has two (text) values like "ValueA", "ValueB" is better encoded in a generalized linear model as a numerical column. The summary field will list main and interaction effect for the variable instead of only listing "ValueA" related statistics. The data does not have to be re-uploaded it is sufficient to define a new variable in Expert Mode. Here an example:
<pre class="prettyprint linenums lang-js" style="font-size:9pt; border: 0px;">
  data$MyNewVariable = as.numeric(data$dichotomousVariable == "ValueA")
</pre>
           </p>
        </li>
        <li>
           <p class="muted" href="interactionByCategoricalVariable">Equivalent recoding of categorical variables</p>
           <p class="text-info">As an excercise we can re-code a categorical variable like the different alleles of a particular SNP as new data columns.
<pre class="prettyprint linenums lang-js" style="font-size:9pt; border: 0px;">
  N=dim(data)[1]
  data$CC=0
  data$TC=0
  data$TT=0
  for(i in 1:N){
     if(data$rs6994992_T[i]=="CC") data$CC[i]=1
     if(data$rs6994992_T[i]=="TC") data$TC[i]=1
     if(data$rs6994992_T[i]=="TT") data$TT[i]=1
  }
  formula.full=MRI_subcort_vol.LatVentricles ~ s(Age, bs = "ts", k=3)+
                s(Age, by = TC, bs = "ts", k = 3)+s(Age, by = TT, bs = "ts",k=3)+
                TC+TT + FDH_Highest_Education + 
                FDH_3_Household_Income + DeviceSerialNumber + GAF_africa + 
                GAF_amerind + GAF_eastAsia + GAF_oceania + GAF_centralAsia + 
                MRI_subcort_vol.IntracranialVolume + Gender
  model.full = gam(formula=formula.full, data=data)
  summary(model.full2)
</pre>
          This is equivalent to a model which just uses the SNP variable itself:
<pre class="prettyprint linenums lang-js" style="font-size:9pt; border: 0px;">
  formula.full2=MRI_subcort_vol.LatVentricles ~ s(Age, bs = "ts", k = 3)+
               s(Age, by = rs6994992_T,bs="ts",k=3)+rs6994992_T + FDH_Highest_Education + 
               FDH_3_Household_Income + DeviceSerialNumber + GAF_africa + 
               GAF_amerind + GAF_eastAsia + GAF_oceania + GAF_centralAsia + 
               MRI_subcort_vol.IntracranialVolume + Gender
  model.full2 = gam(formula=formula.full2, data=data)
  summary(model.full2)    
</pre>
           </p>
        </li>
        <li>
           <p class="muted" href="adjYAxis">Y-Axis label reports "(adj.)" values in Data Exploration</p>
           <p class="text-info">
             If covariates or interaction terms are present the scatter plot values are shifted vertically to correct for the influence of those variables. Instead of the actual measures values 'adjusted' (adj.) values are displayed.
           </p>
        </li>
        <li>
           <p class="muted" href="vertexstats">How to get the list of subjects that have been included into the vertex based model?</p>
           <p class="text-info">
	     The summary statistics field in the Surface Viewer application contains a minimum of information about the data that is included in a particular analysis. This subset includes all subjects for which the model variables are available and for which surface data exists. Add the following line</p>
<pre class="prettyprint linenums lang-js" style="font-size:9pt; border: 0px;">
  vertexShowSubjects = 1
</pre>
           <p class="text-info">  to the Expert Mode field to get a list of participating subject ID's added to the summary text field of the Surface Viewer.</p>
           </p>
        </li>
        <li>
           <p class="muted" href="curvemodelparameters">If a covariate is in the model, for which level or value of that variable is the curve displayed?</p>
           <p class="text-info">
	     If for example Gender is in a model of cortical area over age a single curve is displayed. But Gender has two levels (M/F). In order to create a single curve all covariates are fixed to a specific value. By default the first level of a categorical variable is choosen, or the mean if the variable is continous. This behavior can be overwritten by specifying a list of variables and their values:</p>
<pre class="prettyprint linenums lang-js" style="font-size:9pt; border: 0px;">
  default.values = list(Gender="F")
</pre>
           </p>
        </li>
        <li>
           <p class="muted" href="categoaspredictor">How can I visualize the change in brain shape based on a diagnosis?</p>
           <p class="text-info">
	     Let say you want to see how a diagnosis of schizophrenia changes the shape of the brain. You would have to
	     add the diagnosis as a predictor variable. This is not possible if the diagnosis is encoded as a categortical variable ("schozophrenia" or "control" as values in the "Diag" column). Instead create a new measurement using the Expert Mode. First change the Predictor entry to a new variable name "DiagNum" (numerical diagnosis). Next open the Expert Mode and add: </p>
<pre class="prettyprint linenums lang-js" style="font-size:9pt; border: 0px;">
  data <- data[data$Diag=="schizophrenia" | data$Diag=="control",]
  data <- droplevels(data)

  data$DiagNum = as.numeric(data$Diag=="schizophrenia")
</pre>
	   <p class="text-info">This will remove all subjects that don't have schizophrenia or that do not belong to the control group. The 'droplevels' command is needed to recompute the levels for the the remaining rows of the data table. The new entry DiagNum encodes schizophrenia as value "1" and controls as "0". Using "Compute Surface Model" you can display the "Predicted values" and "adjust geometry" to display the change is brain shape by changing the value of the "Predictor" slider.
	   </p>
        </li>        

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="js/bootstrap-transition.js"></script>
    <script src="js/bootstrap-alert.js"></script>
    <script src="js/bootstrap-modal.js"></script>
    <script src="js/bootstrap-dropdown.js"></script>
    <script src="js/bootstrap-scrollspy.js"></script>
    <script src="js/bootstrap-tab.js"></script>
    <script src="js/bootstrap-tooltip.js"></script>
    <script src="js/bootstrap-popover.js"></script>
    <script src="js/bootstrap-button.js"></script>
    <script src="js/bootstrap-collapse.js"></script>
    <script src="js/bootstrap-carousel.js"></script>
    <script src="js/bootstrap-typeahead.js"></script>
    <script src="js/google-code-prettify/prettify.js"></script>
    <script type="text/javascript">
       jQuery(document).ready(function() {
          prettyPrint();
       });
    </script>

  </body>
</html>
