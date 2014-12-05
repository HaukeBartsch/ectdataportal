<?php
/*----------------------------------------------------------------------------------------------------------------------*/
// URL Parameters to populate variables
/*----------------------------------------------------------------------------------------------------------------------*/
if (isset($_SERVER['QUERY_STRING'])) { // populate variables with supplied URL parameters

  parse_str($_SERVER['QUERY_STRING']);

  if (isset($searchSubjID)) { // default
      $_SESSION['searchSubjID'] = $searchSubjID;
      echo "<script type=\"text/javascript\"> var searchSubjID = ".$searchSubjID.";</script>";
    }

    if (isset($searchVisitID)) {
      $_SESSION['searchVisitID'] = $searchVisitID;
      echo "<script type=\"text/javascript\"> var searchVisitID = ".$searchVisitID.";</script>";
    }

    if (isset($searchSex)) {
      $_SESSION['searchSex'] = $searchSex;
      echo "<script type=\"text/javascript\"> var searchSex = ".$searchSex.";</script>";
    }

    if (isset($searchAge)) {
      $_SESSION['searchAge'] = $searchAge;
      echo "<script type=\"text/javascript\"> var searchAge = ".$searchAge.";</script>";  
    }

    if (isset($searchSite)) {
      $_SESSION['searchSite'] = $searchSite;
      echo "<script type=\"text/javascript\"> var searchSite = ".$searchSite.";</script>";
    }

    if (isset($searchMaturation)) {
      $_SESSION['searchMaturation'] = $searchMaturation;
      echo "<script type=\"text/javascript\"> var searchMaturation = ".$searchMaturation.";</script>";
    }

    if (isset($searchGroup)) {
      $_SESSION['searchGroup'] = $searchGroup;
      echo "<script type=\"text/javascript\"> var searchGroup = ".$searchGroup.";</script>";
    }

    if (isset($_GET['_v'])) {
       $version = $_GET['_v'];
    } else {
       $version = "";
    }
}
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>

<?php
// copy values into JavaScript variables, access values using window.<variable name>
echo '<script type="text/javascript">';
echo '   /* each session defines the following list of variables */';
echo '   var validLogin   = "'.$validLogin.'";';
echo '   var user_name    = "'.$user_name.'";';
echo '   var user_site    = "'.$user_site.'";';
echo '   var project_name = "'.$project_name.'";';
echo '   var project_short_description = "'.$project_short_description.'";';
echo '   var version = "'.$version.'";';
echo '</script>';
?>

<script type="text/javascript" src="/js/picnet.table.filter.min.js"></script>
<script type="text/javascript" src="/js/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="/js/pixastic/pixastic.core.js"></script>
<script type="text/javascript" src="/js/pixastic/pixastic.jquery.js"></script>
<script type="text/javascript" src="/js/pixastic/actions/brightness.js"></script>
<script type="text/javascript" src="/js/mpr.js"></script>
<script type="text/javascript" src="/applications/QC/qc.js"></script>

<script type="text/javascript">
  var project_name_store = project_name;
  var rawDataCsv = project_name + "_MRI_DTI_Complete.csv";
  var stat_header_line = [];

  // report back the index of the text a in the list of all available measures
  function getColumnIndexPerText(a) {
      for (var i = 0; i < stat_header_line.length; i++)
	  if (stat_header_line[i] == a )
	      return i;
      return -1;
  }

 // $("TABLE1").changed(function() { alert('hi'); });

  // call AgeChart with the patients currently listed in the table
  function viewInAgeChart() {
      var visitids = [];
      var count = 0;
      $('#TABLE1 tr').each(function() {
        var fil = this.getAttribute("filtermatch");
	var visitid = $(this).find("td:first").html();
	if (count > 1) {
	    //visitids.push(visitid);
            if (fil == undefined) {
	      visitids.push(visitid);
	    }
	}
	count++;
      });
      window.open('/AgeChart?patients='+visitids,'','');
  }

  function pad(num, size) {
    var s = "000000000" + num;
    return s.substr(s.length-size);
  }

  $(document).ready(function() {
         jQuery('.project_name').text(project_name);
         jQuery('#rowcount').text('wait while loading data...');
    // read in the data table and create all table entries
         $.get('../../data/' + window.project_name_store + '/data_uncorrected' + version + '/' + rawDataCsv, null, function(tsv) {
	  var lines = [],
	      listen = false,
	      date;

	  try {
	      // split the data into lines and parse them
       	      var include_list = ['Site', 'SubjID', 'Sex', 'Age', 'Group', 'VisitID', 'Maturation_index'];
	      tsv = tsv.split(/\r?\n/g);

  	      var tbody = document.getElementById("table1-tbody-id");
	      $.each(tsv, function(i, line) {
		if (line == '' || line.charAt(0) == '#') {
		    listen = false;
	            return false;
		}
		// extract the header line from the first comment line
		if ( listen == false ) {
		    line = line.replace('#','');
		    line = line.split(/,/);
		    stat_header_line = [];
		    for (var i = 0; i < line.length; i++) {
			var name = line[i];
			name = name.replace('"','');
			name = name.replace('"','');
			stat_header_line.push(name);
		    }
		    
		    // all the headers should be added to the search field
		    var dt = document.getElementById("TABLE1");
		    if (dt) {
			var thead = document.getElementById("table1-thead-id");

 		        var headerrow = document.createElement("tr");
			
			var hackGroup = 0; // only add the second group field
			// create a list of all the headers
			for(var pos = 0; pos < stat_header_line.length; pos++) {
			    var head = stat_header_line[pos];
			    var validEntry = false;
			    for (var t = 0; t < include_list.length; t++) {
				if (include_list[t] == head)
				    validEntry = true;
			    }
			    if (validEntry == false)
				continue;
			    if ( head == "Group" && hackGroup == 0 ) {
				hackGroup = 1;
				continue;
			    }
			    
			    // the item that we want to add to the tree
  			    var group = document.createElement("th");
			    var text = document.createTextNode(head);
			    group.appendChild(text);
		            headerrow.appendChild(group);
			}
			// add image for derived data
			var group = document.createElement("th");
                        group.setAttribute('filter', 'false');
			var text = document.createTextNode('T1');
			group.appendChild(text);
                        headerrow.appendChild(group);

			var group = document.createElement("th");
                        group.setAttribute('filter', 'false');
			var text = document.createTextNode('FA');
			group.appendChild(text);
                        headerrow.appendChild(group);

			// add entries for derived data (ImageViewer)
			var group = document.createElement("th");
                        group.setAttribute('filter', 'false');
			var text = document.createTextNode('Image Viewer');
			group.appendChild(text);
                        headerrow.appendChild(group);

			// add entries for derived data (SurfaceViewer)
			var group = document.createElement("th");
                        group.setAttribute('filter', 'false');
			var text = document.createTextNode('Surface Viewer');
			group.appendChild(text);
                        headerrow.appendChild(group);

			// add entry for Area PDF
/*			var group = document.createElement("th");
                        group.setAttribute('filter', 'false');
			var text = document.createTextNode('Area Z-Score');
			group.appendChild(text);
			headerrow.appendChild(group);

			// add entry for Thickness PDF
			var group = document.createElement("th");
                        group.setAttribute('filter', 'false');
			var text = document.createTextNode('Thickness Z-Score');
			group.appendChild(text);
			headerrow.appendChild(group); */

            thead.appendChild(headerrow);
		    }
		}
		
		if (listen == true) {
		    line = line.split(/,/);
		    
		    var vals = [];
		    for (var i = 0; i < line.length; i++) {
			var val = line[i];
			val = val.replace('"','');
			val = val.replace('"','');
		        vals.push(val);
		    }
		    
		    // add this line to the data table
		    var dt = document.getElementById("TABLE1");
                    if (dt) {
			var headerrow = document.createElement("tr");
					
			var SubjID_index    = getColumnIndexPerText('SubjID');
			var VisitID_index   = getColumnIndexPerText('VisitID');
			var StudyDate_index = getColumnIndexPerText('StudyDate');
			var VisitID         = "";
			var StudyDate       = "";
			var hackGroup = 0;
			// create a list of all the headers
			for(var pos = 0; pos < stat_header_line.length; pos++) {
			    var head = stat_header_line[pos];
			    if (pos == VisitID_index) {
				    VisitID = vals[pos];
			    }
			    if (pos == StudyDate_index) {
				    StudyDate = vals[pos];
			    }
			    if (pos == SubjID_index)
			    	SubjID = vals[pos];

			    var validEntry = false;
			    for (var t = 0; t < include_list.length; t++) {
				if (include_list[t] == head)
				    validEntry = true;
			    }
			    if (validEntry == false)
				    continue;

			    if ( head == "Group" || head == "group" && hackGroup == 0 ) {
				    hackGroup = 1;
				    continue;
			    }
			    
			    // the item that we want to add to the tree
			    var group = document.createElement("td");
			    var text = document.createTextNode(vals[pos]);
			    if (pos == VisitID_index) {
				    $(text).addClass("visitid");
			    }
			    group.appendChild(text);
			    headerrow.appendChild(group);
			}

			var group = document.createElement("td");
			var lin = document.createElement("canvas");
                        lin.setAttribute('id', 'canvas-'+SubjID+VisitID);
                        lin.setAttribute('contentEditable', 'true');
                        lin.setAttribute('width', '64');
                        lin.setAttribute('height', '64');
			// lin.setAttribute('href', '//mmil-dataportal.ucsd.edu/ImageViewer/?patient=' + VisitID + '&visit=' + StudyDate);
			group.appendChild(lin);
			headerrow.appendChild(group);
                        mpr1 = new mpr(0);
                        mpr1.setCacheSize( 0 ); // speed up display by caching 1 image only
                        mpr1.bindWindow('#canvas-'+SubjID+VisitID,[0,0,1]);
			if (StudyDate.indexOf("/") > -1) {
  			  studyDateNormalized = pad(StudyDate.split("/")[2],4) + pad(StudyDate.split("/")[0],2) + pad(StudyDate.split("/")[1],2);
                        } else {
                          studyDateNormalized = StudyDate.trim();
                        }
                        mpr1.setDataPath('/data/'+project_name+'/' + project_name + '_webcache/' + VisitID.replace('_','').replace('_','') + '/' + studyDateNormalized + '/JPEG/MPR');
                        mpr1.infoOverlayEnabled = false;
                        mpr1.crosshairEnabled = false;

			var group = document.createElement("td");
			var lin = document.createElement("canvas");
                        lin.setAttribute('id', 'canvas2-'+SubjID+VisitID);
                        lin.setAttribute('contentEditable', 'true');
                        lin.setAttribute('width', '64');
                        lin.setAttribute('height', '64');
			group.appendChild(lin);
			headerrow.appendChild(group);
                        mpr2 = new mpr(0);
                        mpr2.setCacheSize( 0 ); // speed up display by caching 1 image only
                        mpr2.bindWindow('#canvas2-'+SubjID+VisitID,[0,0,1]);
                        mpr2.setDataPath('/data/'+project_name+'/' + project_name + '_webcache/' + VisitID.replace('_','').replace('_','') + '/' + studyDateNormalized + '/JPEG/FA');
                        mpr2.infoOverlayEnabled = false;
                        mpr2.crosshairEnabled = false;

			var group = document.createElement("td");
			var lin = document.createElement("a");
			lin.setAttribute('href', '/applications/ImageViewerMPR/?patient=' + SubjID + '&visit=' + VisitID + '&project=' + project_name);
            var text = document.createTextNode('Images');
			lin.appendChild(text);
			group.appendChild(lin);
			headerrow.appendChild(group);

			var group = document.createElement("td");
			var lin = document.createElement("a");
			lin.setAttribute('href', '/applications/SurfaceViewerSS/?project_name='+ project_name +'&subjid=' + SubjID + '&visitid=' + VisitID);
            var text = document.createTextNode('Surfaces');
			lin.appendChild(text);
			group.appendChild(lin);
			headerrow.appendChild(group);

/*			var group = document.createElement("td");
			var lin = document.createElement("a");
			lin.setAttribute('href', '/data/Project01/ExampleData/SurfaceArea/' + VisitID.replace('_','').replace('_','') + '/SurfaceArea.pdf');
                        var text = document.createTextNode('pdf');
			lin.appendChild(text);
			group.appendChild(lin);
			headerrow.appendChild(group);

			var group = document.createElement("td");
			var lin = document.createElement("a");
			lin.setAttribute('href', '/data/Project01/ExampleData/SurfaceThickness/' + VisitID.replace('_','').replace('_','') + '/SurfaceThickness.pdf');
                        var text = document.createTextNode('pdf');
			lin.appendChild(text);
			group.appendChild(lin);
			headerrow.appendChild(group); */

			tbody.appendChild(headerrow);
		    }
		}
	        listen = true; // every other line is to be parsed
	  });
          var dt = document.getElementById("TABLE1");

	  var tab = $('#TABLE1');	
	  var options = {
		additionalFilterTriggers: [$('#quickfind')],
                filteredRows: function(filterStates) { setRowCountOnGrid(); }
	  };
          function setRowCountOnGrid() {
              var rowcount = tab.find('tbody tr:not(:hidden)').length;
              jQuery('#rowcount').text(rowcount);
          }
	  tab.tableFilter(options);

	  // todo: trigger is not working, entries are filled into the fields but search is not startet
	  if (window.searchSubjID)
  	    $('#TABLE1_filter_0').val(window.searchSubjID).trigger('keyup');
	  if (window.searchVisitID)
  	    $('#TABLE1_filter_1').val(window.searchVisitID).trigger('keyup');
	  if (window.searchSex)
  	    $('#TABLE1_filter_2').val(window.searchSex).trigger('keyup');
	  if (window.searchAge)
	    $('#TABLE1_filter_3').val(window.searchAge).trigger('keyup');
	  if (window.searchSite)
 	    $('#TABLE1_filter_4').val(window.searchSite).trigger('keyup');
	  /*if (window.searchMaturation)
	    $('#TABLE1_filter_5').val(window.searchMaturation).trigger('keyup');
	  if (window.searchGroup)
	    $('#TABLE1_filter_6').val(window.searchGroup).trigger('keyup'); */

	  } catch (e) { alert(e.message) }
          jQuery('#rowcount').text('loading done.');
  }).fail(function() {
     alert('Error: could not load a basic document for this project. Check with system administrator if the project is setup correctly.');
     jQuery('#rowcount').text('loading failed.');
  });
});

</script>
