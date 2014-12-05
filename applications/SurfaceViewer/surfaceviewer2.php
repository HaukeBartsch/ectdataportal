<!doctype html>
<html lang="en">
    <head>
    <title>Brain Surface Viewer</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <style>
             body {
		 font-family: Monospace;
		 background-color: #000;
		 color: #fff;
		 margin: 0px;
		 overflow: hidden;
	     }
             #info {
		 color: #fff;
		 position: absolute;
		 top: 10px;
		 width: 100%;
		 text-align: center;
		 z-index: 100;
		 display:block;
	     }
             #info a, .button { 
		 color: #f00; 
		 font-weight: bold; 
		 text-decoration: underline; 
		 cursor: pointer 
             }
             #message-text {
    		 position: absolute;
		 text-align: left;
		 z-index: 100;
		 display: block;
                 margin-left: 25px;
		 margin-top: 100px;
                 font-size: 10pt;
                 border: 0;
             }
             #controlIcon {
    		 position: absolute;
		 text-align: left;
		 z-index: 100;
		 display: block;
                 margin-left: 25px;
		 margin-top: 15px;
             }
             .ui-dialog { 
    	         opacity: 0.7;
                 filter: alpha (opacity=70);
             }
             #colormapDisplay {
    		 position: absolute;
		 z-index: 100;
		 display: block;
		 vertical-align: bottom;
                 margin-left: 55px;
		 background-color: white;
//border-color: rgba(0, 0, 0, 0.8);
//box-shadow: 0 1px 1px rgba(0,0,0, 0.075) inset, 0 0 8px rgba(255, 255, 255, 0.6);
		 outline: 0 none;
             }
             #dialog option {
		 text-align: left;
             }
             .hemi-buttons-active {
                background-color: #FFAA22;
             }
</style>
   <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/themes/vader/jquery-ui.css" rel="stylesheet" type="text/css"/>
   <link href="js/jquery.minicolors.css" rel="stylesheet" type="text/css"/>    </head>
    <body>
	<div id="info" style="font-family:'Times New Roman',Georgia,Serif; font-size:33pt;">
   	  Average Cortical Surface
	</div>
        <canvas id="colormapDisplay" width="200" height="60" style="display: none;"></canvas>
        <div id="controlIcon" onclick="jQuery('#dialog').dialog( 'open' ); jQuery('#dialog').dialog('option', 'width', 480); jQuery('#dialog').dialog('option', 'height', 140);" style="font-size: 30pt;">C</div>
        <div id="message-text" style="font-size: 12pt;">loading ...</div>
        <div id="dialog" title="Controls" style="display: none; width: 650px;">
           <div>
              <span style="margin: 2px;">Display: </span>
              <span id="rate-of-change-box" style="margin-bottom: 2px; display: none; float: right;">
                    <input type="checkbox" id="rate-of-change"/><label for="rate-of-change">rate of change</label>
              </span>
              <select id="modality" class="ui-button"> <!-- add to the name -lh.bin.json -->
		    <option fname="unknown" index="1" title="Some measure">Predicted values</option>
		    <option fname="unknown" index="0" title="Some other measure main effect">Main effect</option>
		    <option fname="unknown" index="1" title="Some other measure grouping effect">Grouping effect</option>
		    <option fname="unknown" index="2" title="Some other measure interaction effect">Interaction effect</option>
              </select>
           </div>
           <div id="controls" style="margin-bottom: 5px;">
             <button onclick="toggleRHMesh();" id="toggle-right-hemisphere">Hide Right</button>
	     <button onclick="toggleLHMesh();" id="toggle-left-hemisphere">Hide Left</button>
             <button onclick="showHemisphere('lh','lh','#ll-button');" id="ll-button" class="hemi-buttons" title="left lateral view"><small>LL</small></button>
             <button onclick="showHemisphere('rh','rh','#rl-button');" id="rl-button" class="hemi-buttons" title="right lateral view"><small>RL</small></button>
             <button onclick="showHemisphere('lh','rh','#lm-button');" id="lm-button" class="hemi-buttons" title="left medial view"><small>LM</small></button>
             <button onclick="showHemisphere('rh','lh','#rm-button');" id="rm-button" class="hemi-buttons" title="right medial view"><small>RM</small></button>
	     <span style="float: right; display: none;" id="controls-groups-block">group: <span id="controls-groups" style="display: none;"></span> </span>
	   </div>
        <div>
        <div id="colormapDialog" title="Colormap Editor" style="display: none; width: 650px; height: 300px;">
           <!-- draw the colormap and the linear function -->
           <canvas id="colormapDialogDraw" style="height: auto; width: 100%;"></canvas>
           <!-- show the values in the interface -->
	     <div>&nbsp;</div>
	   <div>
	     <input type="text" id="cm-min-value" value="-5" style="width: 20%; text-align: right;display: inline-block;"></input>
             <input type="text" id="cm-min-mid-value" value="0" style="width: 20%;text-align: right;display: inline-block;"></input>
             <input type="text" id="cm-max-value" value="5" style="width: 20%;text-align: right;display: inline-block;float: right;"></input>
             <input type="text" id="cm-max-mid-value" value="0" style="width: 20%;text-align: right;display: inline-block;float: right;margin-right: 5px;"></input>
             <input type="hidden" id="cm-min-value-opa" value="1"></input>
	     <input type="hidden" id="cm-min-mid-value-opa" value="0.5"></input>
	     <input type="hidden" id="cm-max-value-opa" value="1"></input>
	     <input type="hidden" id="cm-max-mid-value-opa" value="0.5"></input>
           </div>
        </div>
        <!--<input type="text" id="amount" style="float: right; border:0; color:#f6931f; font-weight:bold; margin-bottom: 5px;" /> -->
       <div style="margin-top: 10px; height: 15px;"><div style="font-size: 1.1em; float: left;">Color:</div><div style="float: right; margin-top: -5px;">bg:<input id="background-color-picker" class="minicolors" type="text" value="#000000"></div></div>
        <br/>
        <div>
          <div id="Color" style="display: inline-block; width: 60%;"></div>
          <input type="text" id="color-min-value" style="float: left; width: 15%; border: 0; margin-right: 10px; color: #f6931f; font-weight: normal;" />
          <input type="text" id="color-max-value" style="float: right; width: 15%; border: 0; color: #f6931f; font-weight: normal;" />
        </div>
        <div id="Options">
           <input type="checkbox" id="options-fdr"/><label for="options-fdr" title="Warning: this is a work in progress... -log10() of false discovery rate threshold for level of .05."><small>Apply FDR<.05 treshold (<span class="fdr-value"></span>)</small></label>
        </div>
      </div>
      <br/>
      <div style="margin-top: 15px;">
        <div id="inflate" style="float: right; margin-bottom: 15px; width: 80%"></div>
        Inflate:
      </div>
      <br/>
      <div style="display: block; width: 100%;">
        <span>Predictor (<span class="edit" id="age-value"></span>):</span>
          <span style="float: right;"><input id="geometry-enabled" type="checkbox"></input><label for="geometry-enabled">adjust geometry</label></span>
          <span id="age" class="ui-slider ui-btn-down ui-btn-corner-all" role="application" style="display: inline-block; width: 80%;"></span>
          <button id="animate-predictor" label="play" style="float: right; margin-top:-4px;"/>play</button>
      </div>
    </div>

 
	<!-- <script src="js/Three.js"></script> -->
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


<?php
	if (empty($_GET["cookie"])) {
	    $cookie = "";
	} else {
	    $cookie = $_GET["cookie"];
        }
        if (empty($_GET["patient"])) {
	    $patient = "";
        } else {
	    $patient = $_GET["patient"];
        }
        if (empty($_GET["visit"])) {
	    $visit = "";
        } else {
	    $visit = $_GET["visit"];
        }
        if (empty($_GET["request"])) {
	    $request = 0;
        } else {
	    $request = $_GET["request"];
        }
        echo "<script type=\"text/javascript\">\n";
        echo "  cookie  = \"".$cookie."\";\n";
        echo "  patient = \"".$patient."\";\n";
        echo "  visit   = \"".$visit."\";\n";
        echo "  request = \"".$request."\";\n";
        echo "</script>\n";
?>
    
    <style type="text/css">
      #renderArea canvas {
         background-color: #000000; 
      }
    </style>

    <script type="text/javascript">

      var project_name = parent.project_name;
      var user_name    = parent.user_name.replace(/\./g,'_');
      var composer;
      var minC = 0.01;
      var maxC = 0.08;
      var autoWindowLevel = true;
      var mini, maxi;
      var leftValues;
      var rightValues;
      var geomRValues;
      var geomLValues;
      var colormap;
      var lh_Labels, rh_Labels;
      var tweenAnimatePredictor1, tweenAnimatePredictor2;
      var isAnimatePredictor1, isAnimatePredictor2;
      var previous_color_max_value;
      var previous_color_min_value;
      var transformations = [];
      parent.$('#permalink').fadeOut('fast');
      

      jQuery(document).ready(function() {

         var background_color = readCookie('MMILSurfaceViewerBackgroundColor');
         if (background_color == null)
	   background_color = "#000000";
	 jQuery('.minicolors').minicolors({ 
   	            control: 'hue',
	            defaultValue: background_color,
		    change: function(hex, opacity) {
			    jQuery('#renderArea canvas').css('background-color', hex);
                            var minVal = jQuery('#color-min-value').val();
 	                    var maxVal = jQuery('#color-max-value').val();
	                    drawColormap(colormap, minVal, maxVal);
			    createCookie('MMILSurfaceViewerBackgroundColor', hex, 7);
		  }
	 });

         jQuery('#geometry-enabled').change(function() {
	    var showGeom = jQuery('#geometry-enabled').attr('checked');
	    if (typeof showGeom == "undefined") {
	    	showGeom = false;
	    } else {
	    	showGeom = true;
	    }
	    // in case we get some transformation information apply it to the geometry
	    if (showGeom == true) {
  	      if (typeof transformations !== "undefined") {
		for( var i = 0; i < transformations.length; i++) {
	           var axis = new THREE.Vector3(transformations[i].x,transformations[i].y,transformations[i].z);
	           rotateAroundWorldAxis(mesh_rh, axis, transformations[i].rad);
	           rotateAroundWorldAxis(mesh_lh, axis, transformations[i].rad);
		}
	      }
	    } else {
	      // undo the transformation
		for( var i = transformations.length-1; i > -1; i--) {
	           var axis = new THREE.Vector3(transformations[i].x,transformations[i].y,transformations[i].z);
	           rotateAroundWorldAxis(mesh_rh, axis, -transformations[i].rad);
	           rotateAroundWorldAxis(mesh_lh, axis, -transformations[i].rad);
		}
	    }
            var time = jQuery('#age').slider( 'option', 'value' );
	    updateGeometry( time/100.0 );	    
	 });

	 jQuery('.edit').editable(function(value, settings) {
	      var minAge = leftValues.range[0];
	      var maxAge = leftValues.range[1];
	      var v2 = (value-minAge)/(maxAge-minAge)*100;
              if (v2 < 0)
		v2 = 0;
	      if (v2 > 99)
		v2 = 99;
 	      jQuery('#age').slider('option', 'value', v2);

	      var showGeom = jQuery('#geometry-enabled').attr('checked');
	      if (typeof showGeom == "undefined")
		showGeom = false;
	      else
		showGeom = true;

	      if (showGeom) {
		updateGeometry( v2/100.0 );
	      }
              minVal = jQuery('#color-min-value').val();
	      maxVal = jQuery('#color-max-value').val();
	      updateColormap( minVal, maxVal );

              return value;
	    }, {
	      style: 'display: inline; width: 100px;',
	      tooltip: 'set value'
	 });

	 var fdr = readCookie('MMILSurfaceViewerOptionFDR');
	 if (typeof fdr != "undefined") {
	   if ( fdr == "true") {
    	     jQuery('#options-fdr').attr('checked','checked');
	   } else {
    	     jQuery('#options-fdr').removeAttr('checked');
	   }
	 }
	 var x = readCookie('MMILSurfaceViewerWLMin');
	 if (x != undefined) {
	   minC = parseFloat(x);
	   autoWindowLevel = false;
	 }
	 var x = readCookie('MMILSurfaceViewerWLMax');
	 if (x != undefined) {
	   maxC = parseFloat(x);
	   autoWindowLevel = false;
	 }
	 var x = readCookie('MMILSurfaceViewerOptionROC');
	 if (x != undefined) {
	   if ( fdr == "true") {
    	     jQuery('#rate-of-change').attr('checked','checked');
	   } else {
    	     jQuery('#rate-of-change').removeAttr('checked');
	   }
	 }

	 jQuery('#colormapDisplay').dblclick(function() {
	       if (jQuery('#colormapDialog').dialog('isOpen')) {
		 jQuery('#colormapDialog').dialog('close');
	       } else {
		 jQuery('#colormapDialog').dialog('open');
                 minVal = jQuery( '#color-min-value' ).val();
                 maxVal = jQuery( '#color-max-value' ).val();
		 
                 setTimeout(function() { drawExpertColormap(colormap, minVal, maxVal); }, 100);
	       }
	 });

         jQuery('#modality').change(function() {
	      var selectIndex = jQuery(this)[0].selectedIndex;
              switchMeasure( selectIndex );
	 });

	 jQuery('#animate-predictor').click(function() {
              if (isAnimatePredictor1 || isAnimatePredictor2) {
	         tweenAnimatePredictor1.stop();
	         tweenAnimatePredictor2.stop();
		 isAnimatePredictor1 = false;
		 isAnimatePredictor2 = false;
  	         jQuery('#animate-predictor').text("play");
	         return;
	      }

	      jQuery('#animate-predictor').text("stop");
	      var start1 = { time : jQuery('#age').slider('option','value') };
	      var end1   = { time : 99 };
	      var start2 = { time : 99 };
	      var end2   = { time : 0 };
	      tweenAnimatePredictor1 = new TWEEN.Tween(start1).to(end1, (end1.time-start1.time)/99.0*10000);
	      tweenAnimatePredictor1.onUpdate(function() {
       	          isAnimatePredictor1 = true;
	          jQuery('#age').slider('option', 'value', start1.time);
		  minVal = jQuery('#color-min-value').val();
		  maxVal = jQuery('#color-max-value').val();
		  updateColormap( minVal, maxVal );
		  var showGeom = jQuery('#geometry-enabled').attr('checked');
		  if (typeof showGeom == "undefined")
		    showGeom = false;
		  else
		    showGeom = true;
                  var time = jQuery('#age').slider( 'option', 'value' );
                  if (showGeom) {
		    updateGeometry( time/100.0 );
		  }

	      }).onComplete(function() {
		  isAnimatePredictor1 = false;
	      });
	      tweenAnimatePredictor2 = new TWEEN.Tween(start2).to(end2, 1000);
	      tweenAnimatePredictor2.onUpdate(function() {
       	          isAnimatePredictor2 = true;
	          jQuery('#age').slider('option', 'value', start2.time);
		  minVal = jQuery('#color-min-value').val();
		  maxVal = jQuery('#color-max-value').val();
		  updateColormap( minVal, maxVal );
		  var showGeom = jQuery('#geometry-enabled').attr('checked');
		  if (typeof showGeom == "undefined")
		    showGeom = false;
		  else
		    showGeom = true;
                  var time = jQuery('#age').slider( 'option', 'value' );
                  if (showGeom) {
		    updateGeometry( time/100.0 );
		  }

	      }).onComplete(function() {
		  jQuery('#animate-predictor').text('play');
		  isAnimatePredictor2 = false;
	      });

	      tweenAnimatePredictor1.chain(tweenAnimatePredictor2);
	      //tweenAnimatePredictor2.chain(tweenAnimatePredictor1);
	      tweenAnimatePredictor1.start();
	 });

	 jQuery('#dialog').dialog( { autoOpen: false });
	 jQuery('#colormapDialog').dialog( { autoOpen: false });
         jQuery('#colormapDialog').dialog( {
	   resize: function(event, ui) {
              var minVal = jQuery('#color-min-value').val();
	      var maxVal = jQuery('#color-max-value').val();
	      drawColormap(colormap, minVal, maxVal);
	      return false;
	   }
	 });

	 function getMouseLocation(event){
	   var totalOffsetX = 0;
	   var totalOffsetY = 0;
	   var canvasX = 0;
	   var canvasY = 0;
	   var currentElement = event.target;

	   do {
	     totalOffsetX += currentElement.offsetLeft - currentElement.scrollLeft;
	     totalOffsetY += currentElement.offsetTop - currentElement.scrollTop;
	   } while(currentElement = currentElement.offsetParent)

	   canvasX = event.pageX - totalOffsetX;
	   canvasY = event.pageY - totalOffsetY;

	   // invert lox and loy to get the correct locations!!!                                                                                                      
	   return [ canvasX, canvasY ];
	 }

	 var startMouseLocation = []; // should contain start mouse location, start value and what value to change
         var cmMouseIsDown = false;
	 jQuery('#colormapDialog canvas').mousedown(function(e){
	      // what element do we move right now?
	      cmMouseIsDown = true;
	      startMouseLocation    = getMouseLocation(e);
              var m                 = pos2value( startMouseLocation[0], startMouseLocation[1] );
	      var min_value         = parseFloat(jQuery('#cm-min-value').val());
	      var max_value         = parseFloat(jQuery('#cm-max-value').val());
	      var min_mid_value_opa = parseFloat(jQuery('#cm-min-mid-value-opa').val());
	      var max_value_opa     = parseFloat(jQuery('#cm-max-value-opa').val());
	      var max_mid_value     = parseFloat(jQuery('#cm-max-mid-value').val());
	      var max_mid_value_opa = parseFloat(jQuery('#cm-max-mid-value-opa').val());
	      var min_value_opa     = parseFloat(jQuery('#cm-min-value-opa').val());
	      var min_mid_value     = parseFloat(jQuery('#cm-min-mid-value').val());
              var below_min_value   = min_value - (max_value-min_value)/4.0/2.0;
              var above_max_value   = max_value + (max_value-min_value)/4.0/2.0;
              var above_min_value   = min_value + (min_mid_value-min_value)/2.0;
	      var below_max_value   = max_mid_value + (max_value - max_mid_value)/2.0;
              var middle_value      = min_mid_value + (max_mid_value-min_mid_value)/2.0;
	      var w                 = jQuery('#colormapDialog').width();
	      var h                 = jQuery('#colormapDialog').height()/2;
	      var handles           = [ 
				       [below_min_value, (min_value_opa)], 
				       [min_value, (min_value_opa)],
				       [above_min_value, (min_value_opa+(min_mid_value_opa-min_value_opa)/2)],
				       [min_mid_value, (min_mid_value_opa)],
				       [min_mid_value + (max_mid_value - min_mid_value)/2.0, (min_mid_value_opa + (max_mid_value_opa - min_mid_value_opa)/2.0)],
				       [max_mid_value, (max_mid_value_opa)],
				       [below_max_value, (max_mid_value_opa+(max_value_opa - max_mid_value_opa)/2.0)],
				       [max_value, (max_value_opa)],
				       [above_max_value, (max_value_opa)]
				      ];
              var dist = [];
	      for ( var i = 0; i < handles.length; i++) {
		dist[i] = Math.abs( Math.sqrt((handles[i][0]-m[0])*(handles[i][0]-m[0]) + (handles[i][1]-m[1])*(handles[i][1]-m[1])) ); 
	      }
	      // who wins?
	      var minidx = 0; 
              for (var i = 1; i < dist.length; i++) {
		if (dist[i] < dist[minidx]) {
		  minidx = i;
		}
	      }
              startMouseLocation[2] = minidx;
              cmStartValue = -1; // indicate that we start here
	      return false;
	 });
	 jQuery('#colormapDialog canvas').mousemove(function(e){
	      if (!cmMouseIsDown)
	      	return;
	      var currentMouseLocation = getMouseLocation(e);
	      var cML = pos2value(currentMouseLocation[0], currentMouseLocation[1]);
	      var sML = pos2value(startMouseLocation[0], startMouseLocation[1]);
              // now which was the winner?
              var amount = [cML[0] - sML[0],
                            cML[1] - sML[1] ];
              
	      switch (startMouseLocation[2]) {
	      case 0:
		if (cmStartValue == -1) {
		  cmStartValue = [ parseFloat(jQuery('#cm-min-value').val()), parseFloat(jQuery('#cm-min-value-opa').val()) ];
		}
		// jQuery('#cm-min-value').val( cmStartValue[0] + amount[0]);
		jQuery('#cm-min-value-opa').val( cmStartValue[1] + amount[1]);
		break;
	      case 1:
		if (cmStartValue == -1) {
		  cmStartValue = [ parseFloat(jQuery('#cm-min-value').val()), parseFloat(jQuery('#cm-min-value-opa').val()) ];
		}
		jQuery('#cm-min-value').val( cmStartValue[0] + amount[0]);
		jQuery('#cm-min-value-opa').val( cmStartValue[1] + amount[1]);		
		break;
	      case 2:
		if (cmStartValue == -1) {
		  cmStartValue = [ parseFloat(jQuery('#cm-min-value').val()), parseFloat(jQuery('#cm-min-mid-value').val()) ];
		}
		jQuery('#cm-min-value').val( cmStartValue[0] + amount[0]);
		jQuery('#cm-min-mid-value').val( cmStartValue[1] + amount[0]);		
		break;
	      case 3:
		if (cmStartValue == -1) {
		  cmStartValue = [ parseFloat(jQuery('#cm-min-mid-value').val()), parseFloat(jQuery('#cm-min-mid-value-opa').val()) ];
		}
		jQuery('#cm-min-mid-value').val( cmStartValue[0] + amount[0]);
		jQuery('#cm-min-mid-value-opa').val( cmStartValue[1] + amount[1]);		
		break;
	      case 4:
		if (cmStartValue == -1) {
		  cmStartValue = [ parseFloat(jQuery('#cm-min-mid-value-opa').val()), parseFloat(jQuery('#cm-max-mid-value-opa').val()) ];
		}
		jQuery('#cm-min-mid-value-opa').val( cmStartValue[0] + amount[1]);
		jQuery('#cm-max-mid-value-opa').val( cmStartValue[1] + amount[1]);		
		break;
	      case 5:
		if (cmStartValue == -1) {
		  cmStartValue = [ parseFloat(jQuery('#cm-max-mid-value').val()), parseFloat(jQuery('#cm-max-mid-value-opa').val()) ];
		}
		jQuery('#cm-max-mid-value').val( cmStartValue[0] + amount[0]);
		jQuery('#cm-max-mid-value-opa').val( cmStartValue[1] + amount[1]);		
		break;
	      case 6:
		if (cmStartValue == -1) {
		  cmStartValue = [ parseFloat(jQuery('#cm-max-mid-value').val()), parseFloat(jQuery('#cm-max-value').val()) ];
		}
		jQuery('#cm-max-mid-value').val( cmStartValue[0] + amount[0]);
		jQuery('#cm-max-value').val( cmStartValue[1] + amount[0]);		
		break;
	      case 7:
		if (cmStartValue == -1) {
		  cmStartValue = [ parseFloat(jQuery('#cm-max-value').val()), parseFloat(jQuery('#cm-max-value-opa').val()) ];
		}
		jQuery('#cm-max-value').val( cmStartValue[0] + amount[0]);
		jQuery('#cm-max-value-opa').val( cmStartValue[1] + amount[1]);		
		break;
	      case 8:
		if (cmStartValue == -1) {
		  cmStartValue = [ parseFloat(jQuery('#cm-max-value-opa').val()), parseFloat(jQuery('#cm-max-value-opa').val()) ];
		}
		jQuery('#cm-max-value-opa').val( cmStartValue[0] + amount[1]);
		break;
	      default:
		console.log('warning: value unknown');
	      }
              var minVal = jQuery('#color-min-value').val();
  	      var maxVal = jQuery('#color-max-value').val();
	      // drawColormap(colormap, minVal, maxVal);
              updateColormap(minVal, maxVal);
	      return false;
	 });
	 jQuery('#colormapDialog canvas').mouseup(function(e){ 
              cmMouseIsDown = false;
	      return false;
	 });
	  
	 jQuery('#renderArea').mousemove(function(e) {
             if ( Math.sqrt(e.pageX*e.pageX + e.pageY*e.pageY) < 300) {
		 jQuery('#controlIcon').fadeIn('slow');
             } else {
	         jQuery('#controlIcon').fadeOut('slow');
	     }
	 });

	 setTimeout(function() { jQuery('#controlIcon').fadeOut('slow'); }, 2000);
	 setTimeout(function() { jQuery('#message-text').fadeOut('slow'); }, 1000);

	 jQuery('#options-fdr').change(function () {
	      // colors changed, redraw now
	      var minVal = jQuery('#color-min-value').val();
	      var maxVal = jQuery('#color-max-value').val();
	      updateColormap( minVal, maxVal );
	 });

	 jQuery('#rate-of-change').change(function() {
  	      // update the window level again
	      minC = leftValues.windowLevel[0]
	      maxC = leftValues.windowLevel[1]
	      var rateOfChange = jQuery('#rate-of-change').attr('checked');
	      if (typeof rateOfChange != "undefined") {
		minC = leftValues.windowLevel2[0]
		maxC = leftValues.windowLevel2[1]
	      }
              rescaleExpertColormap(minC, maxC);
              mini = minC-(maxC-minC)/2;
              maxi = maxC+(maxC-minC)/2;
	      
	      // redraw the vertex map
	      //var minVal = jQuery('#color-min-value').val();
	      //var maxVal = jQuery('#color-max-value').val();
	      updateColormap( minC, maxC );
	 });

	 jQuery('#inflate').slider({
	      min: 0,
	      max: 1000,
              value: 0,
	      animate: true,
              slide: function (event, ui) {
		  var x = ((ui.value-0)/1000.0 + 0) * (1-0);
		  updateGeometry( x );
              }
         });
	 jQuery('#age').slider({
	      min: 0,
	      max: 99,
              value: 0,
	      animate: true,
              slide: function (event, ui) {
		if (leftValues.type == "geometry") {
                  var time = jQuery('#age').slider( 'option', 'value' );
		  updateGeometry( time );

		  var minAge = leftValues.range[0];
		  var maxAge = leftValues.range[1];
                  var age = minAge + time/(100-1) * (maxAge-minAge);
                  jQuery('#age-value').text(age.toFixed(2));
                } else {
		  var showGeom = jQuery('#geometry-enabled').attr('checked');
		  if (typeof showGeom == "undefined")
		    showGeom = false;
		  else
		    showGeom = true;
		  
		  minVal = jQuery('#color-min-value').val();
		  maxVal = jQuery('#color-max-value').val();
		  updateColormap( minVal, maxVal );
                  var time = jQuery('#age').slider( 'option', 'value' );
                  if (showGeom) {
		    // disable left hemisphere for now
                    if (left_is_on)
  		      toggleLHMesh();
		    updateGeometry( time/100.0 );
		  }
		  // map to 
		  var minAge = leftValues.range[0];
		  var maxAge = leftValues.range[1];
                  var age = minAge + time/(100-1) * (maxAge-minAge);
                  jQuery('#age-value').text(age.toFixed(2));
		}
              }
         });
         jQuery('#Color').slider({    
	      range: true,
              min: 0,
              max: 1000,
              values: [ 250, 750 ],
              slide: function( event, ui ) {
	          // make this symmetric by finding out which entry was changed
	          var idx = 0;
                  if (ui.value == ui.values[0])
		    idx = 1;
		  // now change the other entry as well (symmetric around 500)
		  ui.values[idx] = 500 + (500-ui.values[(idx+1)%2]);

                  var minVal = mini + ((ui.values[0]-250)/500.0) * (maxi - mini);
                  var maxVal = mini + ((ui.values[1]-250)/500.0) * (maxi - mini);
                  rescaleExpertColormap(minVal, maxVal);
                  jQuery( '#color-min-value' ).val(minVal.toFixed(5));
                  jQuery( '#color-max-value' ).val(maxVal.toFixed(5));
                  updateColormap(minVal, maxVal);
              }
         });
         jQuery('#color-max-value').focus(function() {
	    previous_color_max_value = jQuery('#color-max-value').val();
	    previous_color_min_value = jQuery('#color-min-value').val();
	 });
         jQuery('#color-min-value').focus(function() {
	    previous_color_max_value = jQuery('#color-max-value').val();
	    previous_color_min_value = jQuery('#color-min-value').val();
	 });
	 jQuery('#color-min-value').change(function() {
	      var v  = jQuery('#color-min-value').val();
	      var v2 = jQuery('#color-max-value').val();
	      var v3 = (v  - mini)/(maxi-mini)*500.0 + 250;
  	      var v4 = (v2 - mini)/(maxi-mini)*500.0 + 250;
              rescaleExpertColormap(parseFloat(v), parseFloat(v2), previous_color_min_value, previous_color_max_value);
              jQuery('#Color').slider( "values", 0, v3 );	      
              jQuery('#Color').slider( "values", 1, v4 );
	      updateColormap(v, v2);
	 });
	 jQuery('#color-max-value').change(function() {
	      var v = jQuery('#color-min-value').val();
	      var v2 = jQuery('#color-max-value').val();
	      var v3 = (v - mini)/(maxi-mini)*500.0 + 250;
  	      var v4 = (v2 - mini)/(maxi-mini)*500.0 + 250;
              rescaleExpertColormap(parseFloat(v), parseFloat(v2), previous_color_min_value, previous_color_max_value);
              jQuery('#Color').slider( "values", 0, v3 );	      
              jQuery('#Color').slider( "values", 1, v4 );
	      updateColormap(v, v2); 
	 });
         jQuery('#Color').mousedown(function(event) {
              event.stopPropagation();
         });
         jQuery('#inflate').mousedown(function(event) {
              event.stopPropagation();
         });
         jQuery('#age').mousedown(function(event) {
              event.stopPropagation();
         });
	 jQuery('.ui-dialog').mousedown(function(event) {
	      event.stopPropagation();
         });
	 jQuery('#cm-min-value').change(function() {
	      var v  = jQuery('#cm-min-value').val();
	      jQuery('#color-min-value').val(v);
	      var v2 = jQuery('#color-max-value').val();
	      var v3 = (v  - mini)/(maxi-mini)*500.0 + 250;
  	      var v4 = (v2 - mini)/(maxi-mini)*500.0 + 250;
              jQuery('#Color').slider( "values", 0, v3 );	      
              jQuery('#Color').slider( "values", 1, v4 );
	      updateColormap(v, v2);
	 });
	 jQuery('#cm-max-value').change(function() {
	      var v  = jQuery('#color-min-value').val();
	      var v2 = jQuery('#cm-max-value').val();
	      jQuery('#color-max-value').val(v2);
	      var v3 = (v  - mini)/(maxi-mini)*500.0 + 250;
  	      var v4 = (v2 - mini)/(maxi-mini)*500.0 + 250;
              jQuery('#Color').slider( "values", 0, v3 );	      
              jQuery('#Color').slider( "values", 1, v4 );
	      updateColormap(v, v2);
	 });
	 jQuery('#cm-max-mid-value').change(function() {
	      var v  = jQuery('#color-min-value').val();
	      var v2 = jQuery('#color-max-value').val();
	      var v3 = (v  - mini)/(maxi-mini)*500.0 + 250;
  	      var v4 = (v2 - mini)/(maxi-mini)*500.0 + 250;
              jQuery('#Color').slider( "values", 0, v3 );	      
              jQuery('#Color').slider( "values", 1, v4 );
	      updateColormap(v, v2);
	 });
	 jQuery('#cm-min-mid-value').change(function() {
	      var v  = jQuery('#color-min-value').val();
	      var v2 = jQuery('#color-max-value').val();
	      var v3 = (v  - mini)/(maxi-mini)*500.0 + 250;
  	      var v4 = (v2 - mini)/(maxi-mini)*500.0 + 250;
              jQuery('#Color').slider( "values", 0, v3 );	      
              jQuery('#Color').slider( "values", 1, v4 );
	      updateColormap(v, v2);
	 });

	 jQuery(document).keyup(function(e) {
	     var keycode = (e.keyCode ? e.keyCode : e.which);
             if (keycode == 65) { //72 'H' 76 'L", 82 'R', 65 'A', 80 'P', 70 'F'
	         jQuery('.hemi-buttons').each(function(key, value) {
		   jQuery(value).removeClass('hemi-buttons-active');
		 });
		 tweenTo( new THREE.Vector3(0,0,0), new THREE.Vector3(0,300,0), new THREE.Vector3(0,0,1), true );
	     } else if (keycode == 72) {
	         jQuery('.hemi-buttons').each(function(key, value) {
		   jQuery(value).removeClass('hemi-buttons-active');
		 });
		 tweenTo( new THREE.Vector3(0,0,0), new THREE.Vector3(0,0,300), new THREE.Vector3(0,1,0), true );
	     } else if (keycode == 70) {
	         jQuery('.hemi-buttons').each(function(key, value) {
		   jQuery(value).removeClass('hemi-buttons-active');
		 });
		 tweenTo( new THREE.Vector3(0,0,0), new THREE.Vector3(0,0,-300), new THREE.Vector3(0,1,0), true );
	     } else if (keycode == 80) {
	         jQuery('.hemi-buttons').each(function(key, value) {
		   jQuery(value).removeClass('hemi-buttons-active');
		 });
		 tweenTo( new THREE.Vector3(0,0,0), new THREE.Vector3(0,-300,0), new THREE.Vector3(0,0,1), true );
	     } else if (keycode == 82) {
	         jQuery('.hemi-buttons').each(function(key, value) {
		   jQuery(value).removeClass('hemi-buttons-active');
		 });
		 tweenTo( new THREE.Vector3(0,0,0), new THREE.Vector3(300,0,0), new THREE.Vector3(0,0,1), true );
	     } else if (keycode == 76) {
	         jQuery('.hemi-buttons').each(function(key, value) {
		   jQuery(value).removeClass('hemi-buttons-active');
		 });
		 tweenTo( new THREE.Vector3(0,0,0), new THREE.Vector3(-300,0,0), new THREE.Vector3(0,0,-1), true );
	     } else if (keycode == 67) {
	         jQuery('.hemi-buttons').each(function(key, value) {
		   jQuery(value).removeClass('hemi-buttons-active');
		 });
	         // toggle the dialog
	         if (jQuery('#dialog').dialog('isOpen')) {
	           jQuery('.hemi-buttons').each(function(key, value) {
		     jQuery(value).removeClass('hemi-buttons-active');
		   });
    	           jQuery('#dialog').dialog('close');
	         } else {
    	           jQuery('#dialog').dialog('open');
	  	   jQuery('#dialog').dialog('option', 'width', 480);
		   jQuery('#dialog').dialog('option', 'height', 140);
	         }
	     } else if (keycode == 77) { // colormap dialog 'm'
	       if (jQuery('#colormapDialog').dialog('isOpen')) {
		 jQuery('#colormapDialog').dialog('close');
	       } else {
		 jQuery('#colormapDialog').dialog('open');
                 minVal = jQuery( '#color-min-value' ).val();
                 maxVal = jQuery( '#color-max-value' ).val();
		 
                 setTimeout(function() { drawExpertColormap(colormap, minVal, maxVal); }, 100);
	       }
	       e.preventDefault();
	       return false; // handels all things related to this event
	     }
	 });

 	 // key for screenshot is 'p'
  	 THREEx.Screenshot.bindKey(renderer, keyopts);
	 //alert('define screenshot shortcut');

	 /*setTimeout( function() {
	 // POSTPROCESSING
	  
	 renderer.autoClear = false;
	  
	  var renderModel = new THREE.RenderPass( scene, camera );
	  var effectBloom = new THREE.BloomPass( 0.25 );
	  var effectFilm = new THREE.FilmPass( 0.5, 0.15, 1280, false );
	  var effectFXAA = new THREE.ShaderPass( THREE.ShaderExtras[ "fxaa" ] );
	  
	  effectFXAA.uniforms[ 'resolution' ].value.set( 1 / window.innerWidth, 1 / window.innerHeight );
	  
	  effectFilm.renderToScreen = true;
	  
	  composer = new THREE.EffectComposer( renderer );
	  
	  composer.addPass( renderModel );
	  composer.addPass( effectFXAA );
	  composer.addPass( effectBloom );
	  composer.addPass( effectFilm );
	  }); */ 
	  
      });

      if ( ! Detector.webgl ) {
        Detector.addGetWebGLMessage();
      }

      var THREEx= THREEx || {};

      /**
       * Update renderer and camera when the window is resized
       * 
       * @param {Object} renderer the renderer to update
       * @param {Object} Camera the camera to update
       */
      THREEx.WindowResize= function(renderer, camera){
	var callback= function(){
	  // notify the renderer of the size change
	  renderer.setSize( window.innerWidth, window.innerHeight );
	  // update the camera
	  camera.aspect= window.innerWidth / window.innerHeight;
	  camera.updateProjectionMatrix();
	  //console.log('resize event...');
	}
	// bind the resize event
	window.addEventListener('resize', callback, false);
	// return .stop() the function to stop watching window resize
	return {
	  /**
	   * Stop watching window resize
	   */
	stop: function(){
	    window.removeEventListener('resize', callback);
	  }
	};
      }

      var loader=new THREE.VTKLoader();
      var container;
      var camera, controls, scene, renderer;
      var mesh_lh, mesh_rh, geometry_lh, geometry_rh, material;
      var geom_lh_inflated, geom_rh_inflated;
      var surfmask_lh, surfmask_rh;
      var dirLight;
      var colormapFire;
      var colormapBGR;
      var clock = new THREE.Clock();
      init();
      animate();

      var keyopts = {}; 
      keyopts.charCode = 's'.charCodeAt(0);
      var mouseMoveFlag = 0;

      function init() {

	  // INFLATED VERSIONS OF GEOMETRY
          loader.load ("data/left_hemisphere_inflated.vtk", function(geom) {
	      geom_lh_inflated = geom;
	  });
	  loader.load ("data/right_hemisphere_inflated.vtk", function(geom) {
	      geom_rh_inflated = geom;
	  });
          jQuery.getJSON( "data/ico5_lh_surfmask.json", function(a) {
	      surfmask_lh = a;
	  });
          jQuery.getJSON( "data/ico5_rh_surfmask.json", function(a) {
	      surfmask_rh = a;
	  });

	  scene = new THREE.Scene();
	  scene.fog = new THREE.FogExp2( 0x000000, 0.001 );
	  camera = new THREE.PerspectiveCamera( 35, window.innerWidth / window.innerHeight, 1, 10000 );
	  camera.position.x = 300;
	  camera.position.y = 0;
	  camera.position.z = 0;
	  camera.up = new THREE.Vector3(0,0,1);
	  scene.add( camera );

	  controls = new THREE.TrackballControls( camera );
	  
	  controls.rotateSpeed = 5.0;
	  controls.zoomSpeed = 5;
	  controls.panSpeed = 2;
	  
	  controls.noZoom = false;
	  controls.noPan = false;
	  
	  controls.staticMoving = true;
	  controls.dynamicDampingFactor = 0.6;
	  
	  // light

	  dirLight = new THREE.DirectionalLight( 0xffffff );
	  dirLight.position.set( 200, 200, 1000 ).normalize();
	  dirLight.castShadow = true;
	  dirLight.shadowCameraLeft = -150; // should be set larger for lower quality further away shadows
	  dirLight.shadowCameraRight = 150;
	  dirLight.shadowCameraBottom = -150;
	  dirLight.shadowCameraTop = 150;
	  //dirLight.shadowCameraFar = 2000;
	  //dirLight.shadowCameraVisible = true;
          //dirLight.shadowBias = 0.001;
	  camera.add( dirLight );
	  camera.add( dirLight.target );

	  var parameters = { color: 0xffffff, vertexColors: THREE.VertexColors };
	  var materials =  new THREE.MeshPhongMaterial( parameters );
	  //var materials =  new THREE.MeshLambertMaterial( { color: 0xffffff, vertexColors: THREE.VertexColors } );//,
              //new THREE.MeshBasicMaterial( { color: 0x000000, shading: THREE.FlatShading, wireframe: true, transparent: true } )
          //];

          // download geometry
	  loader.load ("data/left_hemisphere.vtk", function(geom) {
			 geometry_lh = geom;
			 geometry_lh.dynamic = true;
			 geometry_lh.computeFaceNormals();
			 geometry_lh.computeVertexNormals();
			 lh_orig = THREE.GeometryUtils.clone(geometry_lh);
			 // we could change values here, but we will wait for user interaction
			 mesh_lh = new THREE.Mesh(geometry_lh, materials );
			 mesh_lh.doubleSided=true;
			 mesh_lh.position.setZ(-10);
			 mesh_lh.position.setY(20);
			 for (var i=0; i < mesh_lh.geometry.faces.length; i++) {
			   f = mesh_lh.geometry.faces[i];
			   idx1 = mesh_lh.geometry.faces[i]['a'];
			   idx2 = mesh_lh.geometry.faces[i]['b'];
			   idx3 = mesh_lh.geometry.faces[i]['c'];
			   // scale linear in the colormap
			   //mesh_lh.geometry.faces[i].vertexColors[ 0 ] = 
			   //  getColorForValue(colormap, minC, maxC, a.values[ idx1 ]);
			   //mesh_lh.geometry.faces[i].vertexColors[ 1 ] = 
			   //  getColorForValue(colormap, minC, maxC, a.values[ idx2 ]);
			   //mesh_lh.geometry.faces[i].vertexColors[ 2 ] = 
			   //  getColorForValue(colormap, minC, maxC, a.values[ idx3 ]);
			 }
			 mesh_lh.castShadow = true;
			 mesh_lh.receiveShadow = true;
			 
			 scene.add( mesh_lh );
			 drawColormap(colormap, minC, maxC);
			 animate();
	  });
          loader.load ("data/right_hemisphere.vtk", function(geom) {
              geometry_rh = geom;
              geometry_rh.dynamic = true;
              geometry_rh.computeFaceNormals();
              geometry_rh.computeVertexNormals();
	      rh_orig = THREE.GeometryUtils.clone(geometry_rh);
              // we could change values here, but we will wait for user interaction
	      mesh_rh = new THREE.Mesh(geometry_rh, materials );
	      mesh_rh.doubleSided=true;
	      mesh_rh.position.setZ(-10);
	      mesh_rh.position.setY(20);
	      for (var i=0; i < mesh_rh.geometry.faces.length; i++) {
		f = mesh_rh.geometry.faces[i];
		idx1 = mesh_rh.geometry.faces[i]['a'];
		idx2 = mesh_rh.geometry.faces[i]['b'];
		idx3 = mesh_rh.geometry.faces[i]['c'];
		// scale linear in the colormap
		//mesh_rh.geometry.faces[i].vertexColors[ 0 ] = 
		//  getColorForValue(colormap, minC, maxC, a.values[ idx1 ]);
		//mesh_rh.geometry.faces[i].vertexColors[ 1 ] = 
		//  getColorForValue(colormap, minC, maxC, a.values[ idx2 ]);
		//mesh_rh.geometry.faces[i].vertexColors[ 2 ] = 
		//  getColorForValue(colormap, minC, maxC, a.values[ idx3 ]);
              }
	      mesh_rh.castShadow = true;
              mesh_rh.receiveShadow = true;
	      
	      scene.add( mesh_rh );
	      drawColormap(colormap, minC, maxC);
	      animate(); // remove because it could save time?? (if left hemisphere is loaded already)

              var attrArray = new Array( "icoarea", "thick", "volume" );
              var attrArrayTitle = new Array( "Surface area expansion factor vs. atlas", 
					      "Cortical thickness <small>[mm]</small>", 
					      "Cortical volume expansion factor" );
              jQuery('#modality,option').attr('fname', attrArray[request]);
	      jQuery('#modality,option').attr('title', attrArrayTitle[request]);
	      setTimeout("switchMeasure(1);", 500);
          });
	  
	  renderer = new THREE.WebGLRenderer( { antialias: true, preserveDrawingBuffer: true } );
	  // renderer.setClearColorHex( 0x000000, 1 );
          renderer.setClearColor( { clearAlpha: 1 });
	  renderer.setSize( window.innerWidth, window.innerHeight );
          renderer.shadowMapEnabled = true;
	  renderer.shadowMapSoft = true;
	  renderer.shadowCameraNear = 3;
	  renderer.shadowCameraFar = camera.far;
	  renderer.shadowCameraFov = 50;
	  
	  renderer.shadowMapBias     = 0.0039;
	  renderer.shadowMapDarkness = 0.3;
	  renderer.shadowMapWidth    = 2048;
	  renderer.shadowMapHeight   = 2048;
	  
	  THREEx.WindowResize(renderer, camera);

	  //renderer.gammaInput = true;
	  //renderer.gammaOutput = true;
	  //renderer.physicallyBasedShading = true;
	  
  	  container = document.createElement( 'div' );
          container.setAttribute('id', 'renderArea');
	  document.body.appendChild( container );
	  container.appendChild( renderer.domElement );

	  jQuery('#renderArea canvas').attr('contentEditable', 'true');
	  jQuery('#renderArea canvas').attr('tabindex', '0');
	  jQuery('#renderArea canvas').focus();
	  
	  jQuery('#renderArea canvas').mousedown(function(e){
						   mouseMoveFlag = 0;
						 });
	  jQuery('#renderArea canvas').mousemove(function(e){
						   mouseMoveFlag = 1;
						 });
	  jQuery('#renderArea canvas').mouseup(function(e){ 
						 if(mouseMoveFlag === 0){
						   onMouseDown(e);
						 } else {
						   // ignore
						 }
					       });
	  
	  // jQuery('#renderArea canvas').click(function(e) { onMouseDown(e);} );

	  jQuery.ajax({
	    url: 'data/colormap.json',
		dataType: 'json',
		success: function(data) {
		  colormapBGR = data.values;
	      }
	  });
	  /*jQuery.ajax({
	    url: 'data/colormap2.json',
		dataType: 'json',
		success: function(data) {
		  colormapFire = data.values;
	      },
		error: function(msg, err) {
		alert('could not load fire colormap '+err);
	      }
	      }); */
      }

// Rotate an object around an arbitrary axis in object space
var rotObjectMatrix;
function rotateAroundObjectAxis(object, axis, radians) {
  rotObjectMatrix = new THREE.Matrix4();
  rotObjectMatrix.makeRotationAxis(axis.normalize(), radians);
  object.matrix.multiplySelf(rotObjectMatrix);      // post-multiply
  object.rotation.getRotationFromMatrix(object.matrix, object.scale);
}

var rotWorldMatrix;
// Rotate an object around an arbitrary axis in world space       
function rotateAroundWorldAxis(object, axis, radians) {
  rotWorldMatrix = new THREE.Matrix4();
  rotWorldMatrix.makeRotationAxis(axis.normalize(), radians);
  rotWorldMatrix.multiplySelf(object.matrix);        // pre-multiply
  object.matrix = rotWorldMatrix;

  // new code for Three.js v50+
  object.rotation.setEulerFromRotationMatrix(object.matrix);

  // old code for Three.js v49:
  //object.rotation.getRotationFromMatrix(object.matrix, object.scale);
}


function onMouseDown( event ) {
  var vector = new THREE.Vector3 ((event.clientX / window.innerWidth) * 2 - 1, -(event.clientY / window.innerHeight)*2+1, 0.5);
  var projector = new THREE.Projector();
  projector.unprojectVector(vector, camera);
  var ray = new THREE.Ray(camera.position, vector.subSelf(camera.position).normalize());
  var intersects_lh = null;
  var intersects_rh = null;
  if (left_is_on)
    intersects_lh = ray.intersectObject(mesh_lh); // we need to use geometry_lh here to pick from the right surface
  if (right_is_on) 
    intersects_rh = ray.intersectObject(mesh_rh);
  var uselh = false;
  var userh = false;;
  if (intersects_lh != null && intersects_lh.length > 0)
    uselh = true;
  if (intersects_rh != null && intersects_rh.length > 0)
    userh = true;
  if (uselh && userh) {
    if (intersects_lh[0].distance < intersects_rh[0].distance)
      userh = false;
    else
      uselh = false;
  }

  if (uselh) {
    // load the label data
    jQuery.ajax({
      url: 'data/lh_label_per_vertex.json',
	  dataType: 'json',
	  success: function(data) {
	  // set as global variable
	  lh_Labels = data;
	  // we have the points in intersects.face.a/b/c and intersects.point
	  // we have to find which point is closest and lookup its label
	  // (this is done for the left hemisphere, we have to do it as well for the right and compare the results)

	  //what is the closest point?
	  cp = intersects_lh[0].face.a;
	  var d0 = (geometry_lh.vertices[intersects_lh[0].face.a].x - intersects_lh[0].point.x) * 
	    (geometry_lh.vertices[intersects_lh[0].face.a].x - intersects_lh[0].point.x) +
	    (geometry_lh.vertices[intersects_lh[0].face.a].y - intersects_lh[0].point.y) * 
	    (geometry_lh.vertices[intersects_lh[0].face.a].y - intersects_lh[0].point.y) +
	    (geometry_lh.vertices[intersects_lh[0].face.a].z - intersects_lh[0].point.z) * 
	    (geometry_lh.vertices[intersects_lh[0].face.a].z - intersects_lh[0].point.z);
	  var d1 = (geometry_lh.vertices[intersects_lh[0].face.b].x - intersects_lh[0].point.x) * 
	    (geometry_lh.vertices[intersects_lh[0].face.b].x - intersects_lh[0].point.x) +
	    (geometry_lh.vertices[intersects_lh[0].face.b].y - intersects_lh[0].point.y) * 
	    (geometry_lh.vertices[intersects_lh[0].face.b].y - intersects_lh[0].point.y) +
	    (geometry_lh.vertices[intersects_lh[0].face.b].z - intersects_lh[0].point.z) * 
	    (geometry_lh.vertices[intersects_lh[0].face.b].z - intersects_lh[0].point.z);
	  var d2 = (geometry_lh.vertices[intersects_lh[0].face.c].x - intersects_lh[0].point.x) * 
	    (geometry_lh.vertices[intersects_lh[0].face.c].x - intersects_lh[0].point.x) +
	    (geometry_lh.vertices[intersects_lh[0].face.c].y - intersects_lh[0].point.y) * 
	    (geometry_lh.vertices[intersects_lh[0].face.c].y - intersects_lh[0].point.y) +
	    (geometry_lh.vertices[intersects_lh[0].face.c].z - intersects_lh[0].point.z) * 
	    (geometry_lh.vertices[intersects_lh[0].face.c].z - intersects_lh[0].point.z);
	  if (d1 < d0 && d1 < d2)
	    cp = intersects_lh[0].face.b;
	  if (d2 < d0 && d2 < d1)
	    cp = intersects_lh[0].face.c;
	  
	  var material = "unknown";
	  for(var i = 0; i < data.names.length; i++) {
	    if (data.names[i][0] == data.values[cp]) {
	       material = data.names[data.values[cp]][1];
	       break;
	    }
	  }
	  //var minVal = jQuery('#Color').slider( 'values', 0 );
	  //minVal = minC + ((minVal-250)/500.0) * (maxC-minC);
	  //var maxVal = jQuery('#Color').slider( 'values', 1 );
	  //maxVal = minC + ((maxVal-250)/500.0) * (maxC-minC);

          minVal = jQuery( '#color-min-value' ).val();
          maxVal = jQuery( '#color-max-value' ).val();
	  updateColormap( minVal, maxVal, data.values[cp] );
	  // jQuery('#message-text').html('found vertex: ' + intersects_lh[0].face.a + '<br/>data value:' + data.values[intersects_lh[0].face.a] + '<br/> material:' + material).fadeIn('fast');
	  jQuery('#message-text').html( material + ' [l]' ).fadeIn('fast');
	  setTimeout(function() { jQuery('#message-text').fadeOut('fast'); }, 3000);
	  setTimeout(function() { updateColormap(minVal, maxVal); }, 2000);
	},
	  error: function(msg, err) {
	  alert('could not load label file');
	}
     });
  }
  if (userh) {
    jQuery.ajax({
      url: 'data/rh_label_per_vertex.json',
	  dataType: 'json',
	  success: function(data) {
	  // set as global variable
	  rh_Labels = data;
	  // we have the points in intersects.face.a/b/c and intersects.point
	  // we have to find which point is closest and lookup its label
	  // (this is done for the left hemisphere, we have to do it as well for the right and compare the results)

	  //what is the closest point?
	  cp = intersects_rh[0].face.a;
	  var d0 = (geometry_rh.vertices[intersects_rh[0].face.a].x - intersects_rh[0].point.x) * 
	    (geometry_rh.vertices[intersects_rh[0].face.a].x - intersects_rh[0].point.x) +
	    (geometry_rh.vertices[intersects_rh[0].face.a].y - intersects_rh[0].point.y) * 
	    (geometry_rh.vertices[intersects_rh[0].face.a].y - intersects_rh[0].point.y) +
	    (geometry_rh.vertices[intersects_rh[0].face.a].z - intersects_rh[0].point.z) * 
	    (geometry_rh.vertices[intersects_rh[0].face.a].z - intersects_rh[0].point.z);
	  var d1 = (geometry_rh.vertices[intersects_rh[0].face.b].x - intersects_rh[0].point.x) * 
	    (geometry_rh.vertices[intersects_rh[0].face.b].x - intersects_rh[0].point.x) +
	    (geometry_rh.vertices[intersects_rh[0].face.b].y - intersects_rh[0].point.y) * 
	    (geometry_rh.vertices[intersects_rh[0].face.b].y - intersects_rh[0].point.y) +
	    (geometry_rh.vertices[intersects_rh[0].face.b].z - intersects_rh[0].point.z) * 
	    (geometry_rh.vertices[intersects_rh[0].face.b].z - intersects_rh[0].point.z);
	  var d2 = (geometry_rh.vertices[intersects_rh[0].face.c].x - intersects_rh[0].point.x) * 
	    (geometry_rh.vertices[intersects_rh[0].face.c].x - intersects_rh[0].point.x) +
	    (geometry_rh.vertices[intersects_rh[0].face.c].y - intersects_rh[0].point.y) * 
	    (geometry_rh.vertices[intersects_rh[0].face.c].y - intersects_rh[0].point.y) +
	    (geometry_rh.vertices[intersects_rh[0].face.c].z - intersects_rh[0].point.z) * 
	    (geometry_rh.vertices[intersects_rh[0].face.c].z - intersects_rh[0].point.z);
	  if (d1 < d0 && d1 < d2)
	    cp = intersects_rh[0].face.b;
	  if (d2 < d0 && d2 < d1)
	    cp = intersects_rh[0].face.c;
	  
	  var material = "unknown";
	  for(var i = 0; i < data.names.length; i++) {
	    if (data.names[i][0] == data.values[cp]) {
	       material = data.names[data.values[cp]][1];
	       break;
	    }
	  }
          minVal = jQuery( '#color-min-value' ).val();
          maxVal = jQuery( '#color-max-value' ).val();
	  updateColormap( minVal, maxVal, data.values[cp] );
	  //console.log('found vertex: ' + intersects_rh[0].face.a + ' data value:' + data.values[intersects_rh[0].face.a] + '<br/> material:' + material);
	  //jQuery('#message-text').html('found vertex: ' + intersects_rh[0].face.a + '<br/>data value:' + data.values[intersects_rh[0].face.a] + '<br/> material:' + material).fadeIn('fast');
	  jQuery('#message-text').html( material + ' [r]' ).fadeIn('fast');
	  setTimeout(function() { jQuery('#message-text').fadeOut('fast'); }, 3000);
	  setTimeout(function() { updateColormap(minVal, maxVal); }, 2000);
	},
	  error: function(msg, err) {
	  alert('could not load label file');
	}
      });
  }
}

function rescaleExpertColormap(minC, maxC, oldMin, oldMax) {
  var min_value     = parseFloat(jQuery('#cm-min-value').val());
  var min_mid_value = parseFloat(jQuery('#cm-min-mid-value').val());
  var max_value     = parseFloat(jQuery('#cm-max-value').val());
  var max_mid_value = parseFloat(jQuery('#cm-max-mid-value').val());

  if (jQuery( '#color-min-value' ).val() === "" || 
      jQuery( '#color-max-value' ).val() === "" )
    return;
  var oldrange = [ parseFloat(jQuery( '#color-min-value' ).val()),
		   parseFloat(jQuery( '#color-max-value' ).val()) ];
  if (typeof oldMin !== "undefined") {
    oldrange = [ parseFloat(oldMin), parseFloat(oldMax) ];
  }

  // adjust the value of the color function to adjust for a change in min/max value to minC, maxC
  jQuery('#cm-min-value').val( minC + (min_value - oldrange[0])/(oldrange[1]-oldrange[0])*(maxC-minC) );
  jQuery('#cm-min-mid-value').val( minC + (min_mid_value - oldrange[0])/(oldrange[1]-oldrange[0])*(maxC-minC) );
  jQuery('#cm-max-value').val( minC + (max_value - oldrange[0])/(oldrange[1]-oldrange[0])*(maxC-minC) );
  jQuery('#cm-max-mid-value').val( minC + (max_mid_value - oldrange[0])/(oldrange[1]-oldrange[0])*(maxC-minC) );
}

function switchMeasure(selectIndex) {
  createCookie('MMILSurfaceViewerMea', selectIndex, 7); // remember the last measure
  // make sure the correct drop-down is active
  jQuery('#modality').get(0).selectedIndex = selectIndex;

  var entry = jQuery('#modality').children("option").eq(selectIndex);
  if (selectIndex > 0) { // read in a different dataset (zscores/tscores)
    jQuery('#rate-of-change-box').fadeOut();
    jQuery('#animate-predictor').fadeOut();
    colormap = colormapBGR; // we should switch to -3...3
    var t = jQuery(entry).attr('title');
    var f = jQuery(entry).attr('fname');
    var idx = jQuery(entry).attr('index'); // the index into the loaded measure file (0|1|2)
    // disable age and use always index entry
    jQuery('#age').slider( 'option', 'value', idx );
    jQuery('#age').slider( 'option', 'disabled', true);
    jQuery('#message-text').html( 'loading...' ).fadeIn('fast');
    jQuery.getJSON( "/applications/DataExploration/user_data/vertexdata_main_effect_and_interaction_"
		    +project_name+"_"+user_name+"_"+cookie+"_"+f+"-lh.json", function(a) {
        jQuery('#controls-groups-block').fadeOut();
        if (typeof a.entries == "undefined") {
	  leftValues = a;
	} else {
	  leftValues = a.entries[0];
	}
	// calculate the color slider values as min/max
	var curveIdx = jQuery('#age').slider('option', 'value');
        if (curveIdx > a.values.length-1) {
	  jQuery('#message-text').html( 'Warning: no data available...' ).fadeIn('fast');
	  setTimeout(function() { jQuery('#message-text').fadeOut('fast'); }, 4000);
	  return;
	}
        jQuery('#info').html(t);
	
	minC = leftValues.windowLevel[0];
	maxC = leftValues.windowLevel[1];
        rescaleExpertColormap(minC, maxC);
        mini = minC-(maxC-minC)/2;
        maxi = maxC+(maxC-minC)/2;
	updateColormap(minC, maxC);

	jQuery('#message-text').html( '' ).fadeOut('fast');
    }).error(function(jqXHR, textStatus, errorThrown) {
	console.log("error " + textStatus);
	console.log("incoming Text " + jqXHR.responseText);
	jQuery('#message-text').html( 'Warning: could not load main effects [lh]...' ).fadeIn('fast');
	setTimeout(function() { jQuery('#message-text').fadeOut('fast'); }, 4000);
    });
    jQuery.getJSON( "/applications/DataExploration/user_data/vertexdata_main_effect_and_interaction_"+project_name+"_"+user_name+"_"+cookie+"_"+f+"-rh.json", function(a) {
	if (typeof a.entries == "undefined") {
	  rightValues = a;
	} else {
	  rightValues = a.entries[0]; 
	}
	minC = rightValues.windowLevel[0]
	maxC = rightValues.windowLevel[1]
        rescaleExpertColormap(minC, maxC);
        mini = minC-(maxC-minC)/2;
        maxi = maxC+(maxC-minC)/2;
	updateColormap(minC, maxC);
	jQuery('#message-text').html( '' ).fadeOut('fast');
    }).error(function() {
	jQuery('#message-text').html( 'Warning: could not load main effects [rh]...' ).fadeIn('fast');
	setTimeout(function() { jQuery('#message-text').fadeOut('fast'); }, 4000);
    });
  } else {
    // here we get time varying data with different group levels
    // add here that we can change the geometry as well

    jQuery('#rate-of-change-box').fadeIn();
    jQuery('#animate-predictor').fadeIn();
    jQuery('#age').slider( 'option', 'disabled', false);
    colormap = colormapBGR; // colormapFire; // directed measures (thickness or area)
    var t = jQuery(entry).attr('title');
    var f = jQuery(entry).attr('fname');
    var idx = jQuery(entry).attr('index');
    jQuery('#message-text').html( 'loading...' ).fadeIn('fast');

    jQuery.getJSON( "/applications/DataExploration/user_data/vertexdata_"+project_name+"_"+user_name+"_"+cookie+"_geometry-lh.json", function(a) {
	      if ( typeof a.entries == "undefined" ) {
	      	geomLValues = a;
	      } else {
	      	geomLValues = a.entries[0]; // over time now!!
	      }
    });
    jQuery.getJSON( "/applications/DataExploration/user_data/vertexdata_"+project_name+"_"+user_name+"_"+cookie+"_geometry-rh.json", function(a) {
	      if ( typeof a.entries == "undefined" ) {
	      	geomRValues = a;
	      } else {
	      	geomRValues = a.entries[0]; // over time now!!
	      }
	      transformations = a.transformations;
    });
    jQuery.getJSON( "/applications/DataExploration/user_data/vertexdata_"+project_name+"_"+user_name+"_"+cookie+"_"+f+"-lh.json", function(a) {
	      // list the groups that are available
	      jQuery('#controls-groups-block').fadeIn();
	      jQuery('#controls-groups').fadeIn();
	      jQuery('#controls-groups').children().remove();
              if (a.entries.length == 1) {
		jQuery('#controls-groups').append('<span>[' + a.entries[0].group + ']</span>');
	      } else {
	        jQuery('#controls-groups').append('<select type="dropdown" id="controls-groups-dd"></select>');
	        jQuery.each(a.entries, function(val, text) {
	      	  jQuery('#controls-groups-dd').append(
	      	     jQuery('<option></option>').val(this.group).html(this.group)
	      	  );
	        });
	      }

	      if ( typeof a.entries == "undefined" ) {
	      	leftValues = a;
	      } else {
	      	leftValues = a.entries[0]; // over time now!!
	      }
              jQuery('#info').html(t);
	      // calculate the color slider values as min/max
	      minC = leftValues.windowLevel[0]
 	      maxC = leftValues.windowLevel[1]
  	      var rateOfChange = jQuery('#rate-of-change').attr('checked');
	      if (typeof rateOfChange != "undefined") {
	         minC = leftValues.windowLevel2[0]
	         maxC = leftValues.windowLevel2[1]
	      }
              rescaleExpertColormap(minC, maxC);
              mini = minC-(maxC-minC)/2;
              maxi = maxC+(maxC-minC)/2;
	      updateColormap(minC, maxC);
	      jQuery('#message-text').html('').fadeOut('fast');
    }).error(function() {
	jQuery('#message-text').html( 'Warning: could not load corrected values [lh]...' ).fadeIn('fast');
	setTimeout(function() { jQuery('#message-text').fadeOut('fast'); }, 4000);       
    });
    jQuery.getJSON( "/applications/DataExploration/user_data/vertexdata_"+project_name+"_"+user_name+"_"+cookie+"_"+f+"-rh.json", function(a) {
      if (typeof a.entries == "undefined") {
	rightValues = a;
      } else {
	rightValues = a.entries[0];
      }
      minC = rightValues.windowLevel[0]
      maxC = rightValues.windowLevel[1]
      var rateOfChange = jQuery('#rate-of-change').attr('checked');
      if (typeof rateOfChange != "undefined") {
           minC = rightValues.windowLevel2[0]
           maxC = rightValues.windowLevel2[1]
      }
      rescaleExpertColormap(minC, maxC);
      mini = minC-(maxC-minC)/2;
      maxi = maxC+(maxC-minC)/2;
      updateColormap(minC, maxC);
      jQuery('#message-text').html('').fadeOut('fast');
    }).error(function() {
	jQuery('#message-text').html( 'Warning: could not load corrected values [rh]...' ).fadeIn('fast');
	setTimeout(function() { jQuery('#message-text').fadeOut('fast'); }, 4000);       
    });
  }
}

//
function animate() {
    requestAnimationFrame( animate );
    TWEEN.update();
    render();
}
function render() {
    var delta = clock.getDelta(),
    time = clock.getElapsedTime() * 10;

    controls.update( delta );
    if ( composer ) {
	renderer.clear();
	composer.render( 0.05 );
    } else {
	renderer.render( scene, camera );
    }
}

function getColorForValue(colormap, minC, maxC, value, fdr) {
  if (typeof fdr != "undefined" && (Math.abs(value) < fdr || fdr == -1)) {
      color = new THREE.Color( 0xffffff );
      color.setRGB( .5, .5, .5 ); // neutral gray
      return color;
  }
  color = new THREE.Color( 0xffffff );
  colorEntry = (value-minC)/(maxC-minC)*(colormap.length/3);
  if (colorEntry < 0)
      colorEntry = 0;
  if (colorEntry > colormap.length/3-1)
      colorEntry = colormap.length/3-1;
  colorEntry = colorEntry.toFixed();
  color.setRGB( colormap[ colorEntry * 3 + 0 ], 
  		colormap[ colorEntry * 3 + 1 ],
  		colormap[ colorEntry * 3 + 2 ] );
  return color;
};
var left_is_on = true; // grrrr
function toggleLHMesh( ) {
    if (left_is_on) {
	scene.remove( mesh_lh );
	left_is_on = false;
	jQuery('#toggle-left-hemisphere').text('Show Left');
    } else {
	scene.add( mesh_lh );
	left_is_on = true;
	jQuery('#toggle-left-hemisphere').text('Hide Left');
    }
}
var right_is_on = true; // grrrr
function toggleRHMesh( ) {
    if (right_is_on) {
	scene.remove( mesh_rh );
	right_is_on = false;
	jQuery('#toggle-right-hemisphere').text('Show Right');
    } else {
	scene.add( mesh_rh );
	right_is_on = true;
	jQuery('#toggle-right-hemisphere').text('Hide Right');
    }
}
function showHemisphere(showThis, fromHere, id) {
  if (showThis == 'lh') {
    if (!left_is_on)
      toggleLHMesh();
    if (right_is_on)
      toggleRHMesh();
  } else {
    if (left_is_on)
      toggleLHMesh();
    if (!right_is_on)
      toggleRHMesh();
  }
  if (fromHere == 'lh') {
    tweenTo( new THREE.Vector3(0,0,0), new THREE.Vector3(-300,0,0), new THREE.Vector3(0,0,-1), true );
  } else {
    tweenTo( new THREE.Vector3(0,0,0), new THREE.Vector3(300,0,0), new THREE.Vector3(0,0,1), true );
  }
  // make the id stand out a little bit
  jQuery('.hemi-buttons').each(function(key, value) {
     jQuery(value).removeClass('hemi-buttons-active');
  });
  jQuery(id).addClass('hemi-buttons-active');
}
function updateGeometry( x ) {
  var showGeom = jQuery('#geometry-enabled').attr('checked');
  if (typeof showGeom == "undefined")
    showGeom = false;
  else
    showGeom = true;

  if (geomLValues != undefined && showGeom) {
    var y = Math.floor(x*100);
    var l = geomLValues.values[y].length/3;
    for (var i=0; i < geometry_lh.vertices.length; i++) {
      geometry_lh.vertices[i].x = geomLValues.values[y][0*l+i];
      geometry_lh.vertices[i].y = geomLValues.values[y][1*l+i];
      geometry_lh.vertices[i].z = geomLValues.values[y][2*l+i];
    }
  } else {
    // min and max slider values are in mini and maxi
    for (var i=0; i < geometry_lh.vertices.length; i++) {
        geometry_lh.vertices[i].x = lh_orig.vertices[i].x + x*(geom_lh_inflated.vertices[i].x - lh_orig.vertices[i].x);
        geometry_lh.vertices[i].y = lh_orig.vertices[i].y + x*(geom_lh_inflated.vertices[i].y - lh_orig.vertices[i].y);
        geometry_lh.vertices[i].z = lh_orig.vertices[i].z + x*(geom_lh_inflated.vertices[i].z - lh_orig.vertices[i].z);
    }
  }
  mesh_lh.geometry.verticesNeedUpdate = true;
  mesh_lh.geometry.computeFaceNormals();
  mesh_lh.geometry.computeVertexNormals();
  mesh_lh.geometry.normalsNeedUpdate = true;
  
  if (geomRValues != undefined && showGeom) {
    var y = Math.floor(x*100);
    var r = geomRValues.values[y].length/3;
    for (var i=0; i < geometry_rh.vertices.length; i++) {
      geometry_rh.vertices[i].x = geomRValues.values[y][0*r+i];
      geometry_rh.vertices[i].y = geomRValues.values[y][1*r+i];
      geometry_rh.vertices[i].z = geomRValues.values[y][2*r+i];
    }
  } else {
    for (var i=0; i < geometry_rh.vertices.length; i++) {
        geometry_rh.vertices[i].x = rh_orig.vertices[i].x + x*(geom_rh_inflated.vertices[i].x - rh_orig.vertices[i].x);
        geometry_rh.vertices[i].y = rh_orig.vertices[i].y + x*(geom_rh_inflated.vertices[i].y - rh_orig.vertices[i].y);
        geometry_rh.vertices[i].z = rh_orig.vertices[i].z + x*(geom_rh_inflated.vertices[i].z - rh_orig.vertices[i].z);
    }
  }
  mesh_rh.geometry.verticesNeedUpdate = true;
  mesh_rh.geometry.computeFaceNormals();
  mesh_rh.geometry.computeVertexNormals();
  mesh_rh.geometry.normalsNeedUpdate = true;
}

defaultColor   = new THREE.Color( 0x222222 );
highlightColor = new THREE.Color( 0x22FF22 );
// redraw the surface with the current colormap, if supplied also highlight one of the materials
// based on the values in lh_Labels/rh_Labels
function updateColormap( minV, maxV, highlightMaterial) {
  if (leftValues == undefined || rightValues == undefined)
    return; // do nothing

  // store the current window level in a cookie, after reloading the page we will use the old setting
  createCookie('MMILSurfaceViewerWLMin', minV, 7);
  createCookie('MMILSurfaceViewerWLMax', maxV, 7);

  var val = jQuery('#options-fdr').attr('checked');
  if (typeof val == "undefined")
    createCookie('MMILSurfaceViewerOptionFDR', false, 7);
  else
    createCookie('MMILSurfaceViewerOptionFDR', true, 7);

  var rateOfChange = jQuery('#rate-of-change').attr('checked');
  if (typeof rateOfChange == "undefined") {
    createCookie('MMILSurfaceViewerOptionROC', false, 7);
    rateOfChange = false;
  } else {
    createCookie('MMILSurfaceViewerOptionROC', true, 7);
    rateOfChange = true;
  }

  // sync slider and text windows
  jQuery( '#color-min-value' ).val(parseFloat(minV).toFixed(5));
  jQuery( '#color-max-value' ).val(parseFloat(maxV).toFixed(5));
  var v3 = (minV  - mini)/(maxi-mini)*500.0 + 250;
  var v4 = (maxV  - mini)/(maxi-mini)*500.0 + 250;
  jQuery('#Color').slider( "values", 0, v3 );	      
  jQuery('#Color').slider( "values", 1, v4 );

  var m1 = jQuery('#Color').slider( 'option', 'min');
  var m2 = jQuery('#Color').slider( 'option', 'max');
  var v1 = jQuery('#Color').slider( 'values', 0);
  var v2 = jQuery('#Color').slider( 'values', 1);
  var time = Math.floor(jQuery('#age').slider( 'option', 'value' ));
  if (isNaN(time))
    time = 0;

  if (!geometry_lh)
  	return;

  // var cmap = colormapFire;
  // colormap = cmap;
  cmap = colormap;

  var range = [parseFloat(minV), parseFloat(maxV)];
  var numColors = Math.floor(colormap.length/3);
  var ar = [];
  for (var i = 0; i < colormap.length/3; i++) {
    var dataVal = range[0] + (i/numColors)*(range[1]-range[0]);
    ar.push( dataVal );
  }
  var colors = getExpertColormap(colormap, ar, minV, maxV);
  var cmap2 = [];
  for (var i = 0; i < colormap.length/3; i++) {
    cmap2.push( colors[i][0]/255.0, colors[i][1]/255.0, colors[i][2]/255.0 );
  }

  // scale by FDR?
  var fdrt = false;
  if (jQuery('#options-fdr').attr('checked')) {
    fdrt = true;
  }
  if (typeof leftValues.FDR_Threshold != "undefined") {
    var val = leftValues.FDR_Threshold[ time ];
    if (val == -1) { // threshold is infinite
  	jQuery('.fdr-value').text("infinite");	
    } else {
  	jQuery('.fdr-value').text(val.toFixed(3));
    }
  } else {
    fdrt = false; // does not exist for some maps
    jQuery('.fdr-value').text("undefined");
  }

  var vals = leftValues.values[time];
  if (rateOfChange && typeof leftValues.values2 != "undefined")
    vals = leftValues.values2[time];
  for (var i=0; i < geometry_lh.faces.length; i++) {
  	f = geometry_lh.faces[i];
  	idx1 = geometry_lh.faces[i]['a'];
  	idx2 = geometry_lh.faces[i]['b'];
  	idx3 = geometry_lh.faces[i]['c'];

  	if (typeof highlightMaterial !== 'undefined' &&
  	    typeof lh_Labels !== 'undefined' &&
  	    (lh_Labels.values[idx1] == highlightMaterial ||
  	     lh_Labels.values[idx2] == highlightMaterial ||
  	     lh_Labels.values[idx3] == highlightMaterial )) {
  	  if (lh_Labels.values[idx1] == highlightMaterial)
            geometry_lh.faces[i].vertexColors[ 0 ] = highlightColor;
  	  if (lh_Labels.values[idx2] == highlightMaterial)
            geometry_lh.faces[i].vertexColors[ 1 ] = highlightColor;
  	  if (lh_Labels.values[idx3] == highlightMaterial)
            geometry_lh.faces[i].vertexColors[ 2 ] = highlightColor;
  	  continue;
  	}

  	// scale linear in the colormap
  	if ( time > -1
  	     && surfmask_lh.values[idx1] == 1  
  	     && surfmask_lh.values[idx2] == 1
  	     && surfmask_lh.values[idx3] == 1) {
  	  if ( fdrt == true ) {
            geometry_lh.faces[i].vertexColors[ 0 ] = getColorForValue(cmap2, minV, maxV, vals[ idx1 ], leftValues.FDR_Threshold[ time ]);
            geometry_lh.faces[i].vertexColors[ 1 ] = getColorForValue(cmap2, minV, maxV, vals[ idx2 ], leftValues.FDR_Threshold[ time ]);
            geometry_lh.faces[i].vertexColors[ 2 ] = getColorForValue(cmap2, minV, maxV, vals[ idx3 ], leftValues.FDR_Threshold[ time ]);
  	  } else {
            geometry_lh.faces[i].vertexColors[ 0 ] = getColorForValue(cmap2, minV, maxV, vals[ idx1 ]);
            geometry_lh.faces[i].vertexColors[ 1 ] = getColorForValue(cmap2, minV, maxV, vals[ idx2 ]);
            geometry_lh.faces[i].vertexColors[ 2 ] = getColorForValue(cmap2, minV, maxV, vals[ idx3 ]);
  	  }
  	} else {
          geometry_lh.faces[i].vertexColors[ 0 ] = defaultColor;
          geometry_lh.faces[i].vertexColors[ 1 ] = defaultColor;
          geometry_lh.faces[i].vertexColors[ 2 ] = defaultColor;
  	}
  }
  mesh_lh.geometry.colorsNeedUpdate = true;
  var vals = rightValues.values[time];
  if (rateOfChange && typeof rightValues.values2 != "undefined")
    vals = rightValues.values2[time];
  for (var i=0; i < geometry_rh.faces.length; i++) {
  	f = geometry_rh.faces[i];
  	idx1 = geometry_rh.faces[i]['a'];
  	idx2 = geometry_rh.faces[i]['b'];
  	idx3 = geometry_rh.faces[i]['c'];

  	if (typeof highlightMaterial !== 'undefined' &&
  	    typeof rh_Labels !== 'undefined' &&
  	    (rh_Labels.values[idx1] == highlightMaterial ||
  	     rh_Labels.values[idx2] == highlightMaterial ||
  	     rh_Labels.values[idx3] == highlightMaterial )) {
  	  if (rh_Labels.values[idx1] == highlightMaterial)
            geometry_rh.faces[i].vertexColors[ 0 ] = highlightColor;
  	  if (rh_Labels.values[idx2] == highlightMaterial)
  	    geometry_rh.faces[i].vertexColors[ 1 ] = highlightColor;
  	  if (rh_Labels.values[idx3] == highlightMaterial)	  
            geometry_rh.faces[i].vertexColors[ 2 ] = highlightColor;
  	  continue;
  	}

  	// scale linear in the colormap
  	if ( time > -1 && surfmask_rh.values[idx1] == 1  && surfmask_rh.values[idx2] == 1 && surfmask_rh.values[idx3] == 1) {
  	  if ( fdrt == true ) {
            geometry_rh.faces[i].vertexColors[ 0 ] = getColorForValue(cmap2, minV, maxV, vals[ idx1 ], rightValues.FDR_Threshold[ time ]);
            geometry_rh.faces[i].vertexColors[ 1 ] = getColorForValue(cmap2, minV, maxV, vals[ idx2 ], rightValues.FDR_Threshold[ time ]);
            geometry_rh.faces[i].vertexColors[ 2 ] = getColorForValue(cmap2, minV, maxV, vals[ idx3 ], rightValues.FDR_Threshold[ time ]);
  	  } else {
            geometry_rh.faces[i].vertexColors[ 0 ] = getColorForValue(cmap2, minV, maxV, vals[ idx1 ]);
            geometry_rh.faces[i].vertexColors[ 1 ] = getColorForValue(cmap2, minV, maxV, vals[ idx2 ]);
            geometry_rh.faces[i].vertexColors[ 2 ] = getColorForValue(cmap2, minV, maxV, vals[ idx3 ]);
  	  }
  	} else {
          geometry_rh.faces[i].vertexColors[ 0 ] = defaultColor; 
          geometry_rh.faces[i].vertexColors[ 1 ] = defaultColor;
          geometry_rh.faces[i].vertexColors[ 2 ] = defaultColor;
  	}
  }
  mesh_rh.geometry.colorsNeedUpdate = true;
  drawColormap(cmap, minV, maxV);
}

// Returns an array of arrays that contain color values 
// for each data value in ar. Colors are based on a symmetric input
// colormap cm and an opacity function defined in the user
// interface.
function getExpertColormap(cm, ar, minV, maxV) {

  var colorB = [155, 155, 155, 0.2]; // alpha blend with this background color

  // get all the values
  var min_value     = parseFloat(jQuery('#cm-min-value').val());
  var min_value_opa = parseFloat(jQuery('#cm-min-value-opa').val());
  var min_mid_value = parseFloat(jQuery('#cm-min-mid-value').val());
  var min_mid_value_opa = parseFloat(jQuery('#cm-min-mid-value-opa').val());
  var max_value     = parseFloat(jQuery('#cm-max-value').val());
  var max_value_opa = parseFloat(jQuery('#cm-max-value-opa').val());
  var max_mid_value = parseFloat(jQuery('#cm-max-mid-value').val());
  var max_mid_value_opa = parseFloat(jQuery('#cm-max-mid-value-opa').val());

  if (max_value < min_value) {
    var tmp = min_value;
    min_value = max_value;
    max_value = tmp;
  }
  if (max_mid_value < min_mid_value) {
    var tmp = min_mid_value;
    min_mid_value = max_mid_value;
    max_mid_value = tmp;
  }
  if (max_mid_value > max_value) {
    var tmp = max_value;
    max_value = max_mid_value;
    max_mid_value = tmp;
  }
  if (min_mid_value < min_value) {
    var tmp = min_value;
    min_value = min_mid_value;
    min_mid_value = tmp;
  }
  min_value_opa = Math.min(1,Math.max(0,min_value_opa));
  max_value_opa = Math.min(1,Math.max(0,max_value_opa));
  min_mid_value_opa = Math.min(1,Math.max(0,min_mid_value_opa));
  max_mid_value_opa = Math.min(1,Math.max(0,max_mid_value_opa));

  // put value back into the interface
  jQuery('#cm-min-value').val(min_value);
  jQuery('#cm-min-mid-value').val(min_mid_value);
  jQuery('#cm-max-value').val(max_value);
  jQuery('#cm-max-mid-value').val(max_mid_value);
  jQuery('#cm-min-value-opa').val(min_value_opa);
  jQuery('#cm-min-mid-value-opa').val(min_mid_value_opa);
  jQuery('#cm-max-value-opa').val(max_value_opa);
  jQuery('#cm-max-mid-value-opa').val(max_mid_value_opa);

  // range of the colormap
  var range = [parseFloat(minV), parseFloat(maxV)];

  var colors = [];
  var numColors = cm.length/3;
  var midColor = Math.floor(numColors/2);
  for ( var i = 0; i < ar.length; i++ ) {
    var dataVal = ar[i];
    if (dataVal < min_value) {
      var alpha = min_value_opa;
      colors.push( [ Math.round(255*cm[3*0+0]), Math.round(255*cm[3*0+1]), Math.round(255*cm[3*0+2]), alpha ] );
    } else if (dataVal < min_mid_value) {
      var alpha = min_value_opa + (dataVal-min_value)/(min_mid_value-min_value) * (min_mid_value_opa-min_value_opa);
      var centry = Math.round((dataVal-min_value)/(min_mid_value-min_value)*(midColor-1));
      colors.push( [ Math.round(255*cm[3*centry+0]), Math.round(255*cm[3*centry+1]), Math.round(255*cm[3*centry+2]), alpha ] );
    } else if (dataVal < max_mid_value) {
      // background color
      var alpha = min_mid_value_opa + (dataVal-min_mid_value)/(max_mid_value-min_mid_value) * (max_mid_value_opa-min_mid_value_opa);
      colors.push( [ Math.round(255*cm[3*midColor+0]), Math.round(255*cm[3*midColor+1]), Math.round(255*cm[3*midColor+2]), alpha ] );
    } else if (dataVal < max_value) {
      var alpha = max_mid_value_opa + (dataVal-max_mid_value)/(max_value-max_mid_value) * (max_value_opa-max_mid_value_opa);
      var centry = midColor + Math.round((dataVal-max_mid_value)/(max_value-max_mid_value)*(midColor-1));
      colors.push( [ Math.round(255*cm[3*centry+0]), Math.round(255*cm[3*centry+1]), Math.round(255*cm[3*centry+2]), alpha ]);
    } else {
      var alpha = max_value_opa;
      colors.push( [ Math.round(255*cm[3*(numColors-1)+0]), Math.round(255*cm[3*(numColors-1)+1]), Math.round(255*cm[3*(numColors-1)+2]), alpha ]);
    }
    // alpha compositing of color in front of background
    colors[i] = [ Math.round(colors[i][0]*colors[i][3] + colorB[3]*colorB[0]*(1.0 - colors[i][3])),
                  Math.round(colors[i][1]*colors[i][3] + colorB[3]*colorB[1]*(1.0 - colors[i][3])),
                  Math.round(colors[i][2]*colors[i][3] + colorB[3]*colorB[2]*(1.0 - colors[i][3])),
		  1 /*(colors[i][3] + colorB[3]*(1-colors[i][3]))*/ ];
  }
  return colors;
}

function drawExpertColormap(cm, minV, maxV) {
  var is_symmetric = true; // assume that the min_value is always max_value etc.

  var w;
  var h;
 
  // we have a background color
  var background = [128, 128, 128, 0.1];
  var backgroundC = 'rgba('+background[0]+','+background[1]+','+background[2]+','+background[3]+')'; // neutral gray
  // draw background first
  var context = document.getElementById('colormapDialogDraw').getContext('2d');
  context.canvas.width  = w = jQuery('#colormapDialog').width()  - 20;
  context.canvas.height = h = jQuery('#colormapDialog').height() - 60;

  context.fillStyle = backgroundC;
  context.clearRect(0,0,w,h);  
  
  var numColors = cm.length/3;
  var midColor = Math.floor(numColors/2);
  context.lineWidth = 2;
  var range = [parseFloat(minV), parseFloat(maxV)];
  var ar = [];
  for (var i = 0; i < w; i++) {
    var dataVal = range[0] + (i/w)*(range[1]-range[0]);
    ar.push( dataVal );
  }
  ar.push( 0 ); // find out where 0 is and indicate it on the colormap
  var colors = getExpertColormap(cm, ar, minV, maxV);

  for ( var i = 0; i < w; i+=2 ) {
    var dataVal = ar[i]; // range[0] + (i/w)*(range[1]-range[0]);

    var color = 'rgba(' + colors[i][0] + ','
      + colors[i][1] + ','
      + colors[i][2] + ','
      + colors[i][3] + ')';

    context.beginPath();
    context.moveTo(i,0);
    context.lineTo(i,h);
    context.strokeStyle = color;
    context.stroke();
  }
  // draw the opacity curve
  context.strokeStyle = 'rgb(255,0,0)';
  context.shadowColor = 'rgb(128,128,128)';
  context.shadowOffsetX = 1;
  context.shadowOffsetY = 1;
  context.shadowBlur = 3;
  var margin = 5;
  v2p = [range[0], range[1], w, h, margin]; // set the values used in value2pos

  var min_value     = parseFloat(jQuery('#cm-min-value').val());
  var min_value_opa = parseFloat(jQuery('#cm-min-value-opa').val());
  var min_mid_value = parseFloat(jQuery('#cm-min-mid-value').val());
  var min_mid_value_opa = parseFloat(jQuery('#cm-min-mid-value-opa').val());
  var max_value     = parseFloat(jQuery('#cm-max-value').val());
  var max_value_opa = parseFloat(jQuery('#cm-max-value-opa').val());
  var max_mid_value = parseFloat(jQuery('#cm-max-mid-value').val());
  var max_mid_value_opa = parseFloat(jQuery('#cm-max-mid-value-opa').val());

  var x = value2pos(min_value,0)[0];
  // var x = (min_value-range[0])/(range[1]-range[0])*w;
  context.moveTo(0, value2pos(0,min_value_opa)[1]);
  context.lineTo(x, value2pos(0,min_value_opa)[1]);
  //context.stroke();
  var x2 = value2pos(min_mid_value,0)[0];
  context.lineTo(x2, value2pos(0,min_mid_value_opa)[1]); 
  //context.stroke();
  var x3 = value2pos(max_mid_value,0)[0]; 
  context.lineTo(x3,value2pos(0,max_mid_value_opa)[1]); 
  //context.stroke();
  var x4 = value2pos(max_value, 0)[0]; 
  context.lineTo(x4, value2pos(0,max_value_opa)[1]); 
  //context.stroke();
  context.lineTo(w, value2pos(0,max_value_opa)[1]); 
  context.stroke();
  context.shadowColor = 'rgba(80,80,80,0)'; // disable shadow

  x = value2pos(0,0)[0];
  context.moveTo(x,0);
  context.lineTo(x,h);
  context.strokeStyle = 'rgb(128,128,128)';
  context.lineWidth = 1;
  context.stroke();
}

// provide a way to convert values and opacities into locations on the colormap editor
var v2p = []; // [range[0], range[1], w, h, margin]
function value2pos( x, y ) {
  return [(x-v2p[0])/(v2p[1]-v2p[0])*v2p[2], v2p[4]+(1-y)*(v2p[3]-v2p[4]*2)];
}
// provide a way to convert mouse locations into values and opacities in the colormap editor
function pos2value( x, y ) {
  return [ v2p[0] + x/v2p[2]*(v2p[1]-v2p[0]), 1-Math.max(0,Math.min(1,(y-v2p[4])/(v2p[3]-(2*v2p[4])))) ];
}

function drawColormap(colormap, minV, maxV) {
  if (colormap == undefined)
    return;

  if (jQuery('#colormapDialog').dialog('isOpen')) {
    drawExpertColormap(colormap, minV, maxV);
  }

  var numColors = colormap.length/3;
  context = document.getElementById('colormapDisplay').getContext('2d');
  //context.fillStyle = "rgba(0,0,0,0.5)";
  var w = jQuery('#colormapDisplay').width();    
  var h = jQuery('#colormapDisplay').height()/2.0;
  jQuery('#colormapDisplay').css('position', 'absolute');
  jQuery('#colormapDisplay').css('top', jQuery('#renderArea').height()-100);

  context.clearRect(0,0,w,h*2);
  var c = jQuery('#background-color-picker').minicolors( 'rgbString' );
  context.strokeStyle = c;
  context.fillStyle = c;

  context.fillRect(0,0,w,h*2);
  context.lineWidth = 1;

  var range = [parseFloat(minV), parseFloat(maxV)];
  var ar = [];
  for (var i = 0; i < w; i++) {
    var dataVal = range[0] + (i/w)*(range[1]-range[0]);
    ar.push( dataVal );
  }
  var colors = getExpertColormap(colormap, ar, minV, maxV);

  for ( var i = 0; i < w; i++ ) {
      context.beginPath();
      context.moveTo(i,0);
      context.lineTo(i,h);
      context.strokeStyle = 'rgba('
	+ colors[i][0] + ','
	+ colors[i][1] + ',' 
	+ colors[i][2] + ','
	+ colors[i][3] + ')';
      context.stroke();
  }
  var oldfill = context.fillStyle;
  context.fillStyle = 'rgba(255,255,255,0.5)';
  context.font="12px Arial";
  context.fillText(parseFloat(minV).toFixed(3), 0, 48);
  context.fillText(parseFloat(maxV).toFixed(3), w-30 , 48);
  context.fillStyle = oldfill;
  jQuery('#colormapDisplay').fadeIn(1000);
}

function lerp(s,e,t) {
    var res = s;
    res.x=s.x+(e.x-s.x)*t;
    res.y=s.y+(e.y-s.y)*t;
    res.z=s.z+(e.z-s.z)*t;
    return res;
}

function onCameraAnimUpdate () {
    var currentPos = lerp(startPos, endPos, param.t);
    var currentLookAt = lerp(startLookAt, endLookAt, param.t);
    var currentUp = lerp(startUp, endUp, param.t);
    camera.position.set(currentPos.x, currentPos.y, currentPos.z);
    camera.lookAt(currentLookAt);
    camera.up = currentUp;
    //console.log(camera.up);
}

var param, startPos, endPos, startLookAt, endLookAt, startUp, endUp;
// the lookAt location, the end position, the end up direction and if we want to jump there
function tweenTo(center, endposition, endup, animate) {
    var aabbCenter = center;

    // Compute new camera direction and position
    var dir = new THREE.Vector3(0.0, 0.0, 1.0);
    if (typeof camera === 'undefined')
        dir = camera.matrix.getColumnZ();

    var newPos = new THREE.Vector3();
    newPos.add(aabbCenter, dir);
    newPos = endposition;

    // Update camera
    if (!animate)
    {
        camera.position.set(newPos.x, newPos.y, newPos.z);
        camera.lookAt(aabbCenter);                     
        //createControlsForCamera();
        controls.target = aabbCenter;
	camera.up = endUp;
	//	console.log('no animation');
	//console.log(camera.up);
    } else {
        startPos = camera.position.clone();
        startLookAt = controls.target.clone();
	startUp = camera.up.clone();
        endPos = newPos;
	endUp = endup;
        endLookAt = aabbCenter;

        param = {t: 0};
        anim = new TWEEN.Tween(param).to({t: 1.0}, 4000 )
	    .easing( TWEEN.Easing.Sinusoidal.InOut )
	    .onUpdate( onCameraAnimUpdate );
	anim.start();
    }
}

function createCookie(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
  }
  else var expires = "";
  document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}
</script>
</body>
</html>
