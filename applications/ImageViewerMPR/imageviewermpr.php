<!-- START CONTENT AREA: EDIT BETWEEN THIS COMMENT AND END COMMENT -->

<script type="text/javascript">
    project_name = project_id; // "PING";
    patient_id   = patient; // VisitID.split('_',1);
    VisitID = patient;
    StudyDate = visit;


  jQuery(parent.window).resize(function() { // adjust the size of the div elements according to the size of the window
	var tpar = jQuery('#container');
	var t = jQuery('#window0');
	var t2 = jQuery('#window1');
	var t3 = jQuery('#window2');
	var p = 0.667;
	document.getElementById('window0').height = tpar.width()*p;
	document.getElementById('window0').width = tpar.width()*p;
	document.getElementById('window1').height = tpar.width()*(1-p);
	document.getElementById('window1').width = tpar.width()*(1-p);
	document.getElementById('window2').height = tpar.width()*(1-p);
	document.getElementById('window2').width = tpar.width()*(1-p);
	mpr1.update();
	mpr2.update();
	mpr3.update();
	jQuery('#control').css('left', tpar.width()*p-150);
      });

  jQuery(document).ready(function() {
      //jQuery('#scans').autocomplete({ source : scans });

      // add to modalityData
      for (i = 0; i < modalities.length; i++) {
        jQuery('#modalityData').append('<option value="' + modalities[i] + '">' + modalities[i] + '</option>');

        jQuery('#inner-wrap').append('<canvas id="window'+(3+i)+'" contenteditable="true" width="128px" height="128px" orientation="0,1,0" style="cursor:crosshair;" modality="' + modalities[i] + '"></canvas>');
      }

      // first three are always the same
      mpr1 = new mpr(0);
      mpr1.setVoxelSize([1,1,1]);
      mpr1.bindWindow("#window0", [0,0,1]); // bind windows first
      mpr1.bindWindow("#window1", [0,1,0]);
      mpr1.bindWindow("#window2", [1,0,0]);
      mpr1.setFlipSliceDirections( [false, false, true] );
      mpr1.setCacheSize( 5 ); // speed up display by caching 1 image only
      mpr1.setPatientInformation( patient_id, StudyDate, "MPR", "none" );
      mpr1.setDataPath('/data/'+project_name+'/' + project_name 
		       + '_webcache/' + VisitID.replace('_','').replace('_','') + '/' + StudyDate + '/JPEG/MPR');

      mpr2 = new mpr(1);
      mpr2.setVoxelSize([1,1,1]);
      mpr2.setPatientInformation( patient_id, StudyDate, "MPR", "none" );
      mpr2.setFlipSliceDirections( [false, false, true] );
      mpr2.setDataPath('/data/'+project_name+'/' + project_name 
		       + '_webcache/' + VisitID.replace('_','').replace('_','') + '/' + StudyDate + '/JPEG/MPR');

      mpr3 = new mpr(2);
      mpr3.setVoxelSize([1,1,1]);
      mpr3.setFlipSliceDirections( [false, false, true] );
      mpr3.setPatientInformation( patient_id, StudyDate, "MPR", "none" );
      mpr3.setDataPath('/data/'+project_name+'/' + project_name 
		       + '_webcache/' + VisitID.replace('_','').replace('_','') + '/' + StudyDate + '/JPEG/MPR');

      // next entries depend on number of detected modalities
      for (i =0; i < modalities.length; i++) {
        mpr4 = new mpr(3+i);
        mpr4.bindWindow("#window"+(3+i), [0,0,1]);
        mpr4.setVoxelSize([1,1,1]);
        mpr4.setFlipSliceDirections( [false, false, true] );
        mpr4.setPatientInformation( patient_id, StudyDate, modalities[i], "none" );
        mpr4.crosshair( false );
        mpr4.infoOverlayEnabled = false;
        mpr4.setDataPath('/data/'+project_name+'/' + project_name 
		       + '_webcache/' + VisitID.replace('_','').replace('_','') + '/' + StudyDate + '/JPEG/' + modalities[i]);
      }

      jQuery('#modalityData').change(function() { 
          var value = jQuery('#modalityData').val();
          mpr1.setDataPath('/data/'+project_name+'/' + project_name 
			   + '_webcache/' + VisitID.replace('_','').replace('_','') + '/' + StudyDate + '/JPEG/'+value);
    	    mpr1.patientinformation.modality = value;
          mpr2.setDataPath('/data/'+project_name+'/' + project_name 
			   + '_webcache/' + VisitID.replace('_','').replace('_','') + '/' + StudyDate + '/JPEG/'+value);
          mpr3.setDataPath('/'+project_name+'/' + project_name 
			   + '_webcache/' + VisitID.replace('_','').replace('_','') + '/' + StudyDate + '/JPEG/'+value);
          mpr1.resetImage();
          mpr2.resetImage();
          mpr3.resetImage();
      });
      jQuery('.modalityOverlay').change(function() { 
          var value = jQuery('.modalityOverlay').val();
          if ( value == "none" ) {
            mpr1.overlayDataIsSet = false;
            mpr2.overlayDataIsSet = false;
            mpr3.overlayDataIsSet = false;
          } else {
            mpr1.resetOverlay();
            mpr2.resetOverlay();
            mpr3.resetOverlay();
            mpr1.setOverlayDataPath('/data/'+project_name+'/' + project_name 
				    + '_webcache/' + VisitID.replace('_','').replace('_','') + '/' + StudyDate + '/JPEG/'+value);
            mpr2.setOverlayDataPath('/data/'+project_name+'/' + project_name 
				    + '_webcache/' + VisitID.replace('_','').replace('_','') + '/' + StudyDate + '/JPEG/'+value);
            mpr3.setOverlayDataPath('/data/'+project_name+'/' + project_name 
				    + '_webcache/' + VisitID.replace('_','').replace('_','') + '/' + StudyDate + '/JPEG/'+value);
         }
      });
      jQuery('#inner-wrap canvas').click(function() { 
         var modality = jQuery(this).attr('modality');
	    // set this by adjusting the entry of the drop-down
	    jQuery('#modalityData').val(modality);
	    // we listen to the change event
	    jQuery('#modalityData').change();
    });

    jQuery(parent.window).resize();
  });

</script>


<div id="page" class="siteContent">

    <!-- add a search panel -->
<!--    <div class="ui-widget"> <label for="scans">Scans: </label> <input id="scans"/> </div>
    <div class="spacer"></div> -->
    <!-- <div class="search_panel" id="search_panel">
            <input type="text" name="search_term" id="search_term" class="search_term" autocomplete="off" value="" style="margin-left: 2px;" />
            <input type="button" class="pSearch" id="search_button" value="Search" onclick="doSearch();"/>
    </div> -->
    <div id="control" style="position: absolute; z-index: 1;">
    <select id="modalityData">
      <!--   <option value="MPR">MPR</option>
        <option value="RGB">RGB</option>
	      <option value="fsegRGB">fsegRGB</option>
	      <option value="fsegMPR">fsegMPR</option>
     	  <option value="ASEG">ASEG</option>
	      <option value="faMPR">faMPR</option>
	      <option value="ADC">ADC</option>
	      <option value="FA">FA</option> -->
    </select>
    </div>
 <!--    Overlay: <select class="modalityOverlay">
        <option value="none">none</option>
        <option value="MPR">MPR</option>
        <option value="RGB">RGB</option>
	<option value="fsegRGB">fsegRGB</option>
	<option value="fsegMPR">fsegMPR</option>
	<option value="ASEG">ASEG</option>
	<option value="faMPR">faMPR</option>
	<option value="ADC">ADC</option>
	<option value="FA">FA</option>
    </select> -->

    <div class="spacer"></div>

    <div id="container" class="block" style="width=100%;">
       <div style="width=60%; position: relative; float: left;">
           <canvas id="window0" contenteditable="true" width="440px" height="400px" orientation="0,0,1" style="cursor:crosshair;"></canvas>
       </div>
       <div style="width=35%">
           <canvas id="window1" contenteditable="true" width="220px" height="200px" orientation="0,1,0" style="cursor:crosshair;"></canvas><br>
           <canvas id="window2" contenteditable="true" width="220px" height="200px" orientation="1,0,0" style="cursor:crosshair;"></canvas>
       </div>
    </div>
    <div id="wrapper">
      <div id="slide-wrap">
        <div id="inner-wrap">
   <!--       <canvas id="window3" contenteditable="true" width="128px" height="128px" orientation="0,1,0" style="cursor:crosshair;" modality="MPR"></canvas>
          <canvas id="window4" contenteditable="true" width="128px" height="128px" orientation="0,1,0" style="cursor:crosshair;" modality="RGB"></canvas>
          <canvas id="window5" contenteditable="true" width="128px" height="128px" orientation="0,1,0" style="cursor:crosshair;" modality="fsegRGB"></canvas>
          <canvas id="window6" contenteditable="true" width="128px" height="128px" orientation="0,1,0" style="cursor:crosshair;" modality="fsegMPR"></canvas>
          <canvas id="window7" contenteditable="true" width="128px" height="128px" orientation="0,1,0" style="cursor:crosshair;" modality="ASEG"></canvas>
          <canvas id="window8" contenteditable="true" width="128px" height="128px" orientation="0,1,0" style="cursor:crosshair;" modality="faMPR"></canvas>
          <canvas id="window9" contenteditable="true" width="128px" height="128px" orientation="0,1,0" style="cursor:crosshair;" modality="ADC"></canvas>
          <canvas id="window10" contenteditable="true" width="128px" height="128px" orientation="0,1,0" style="cursor:crosshair;" modality="FA"></canvas> -->
        </div>
      </div>
    </div>
</div> <!-- end page -->


