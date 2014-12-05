<?php

/*----------------------------------------------------------------------------------------------------------------------*/
// URL Parameters to populate variables
/*----------------------------------------------------------------------------------------------------------------------*/
if (isset($_SERVER['QUERY_STRING'])) { // populate variables with supplied URL parameters

  parse_str($_SERVER['QUERY_STRING']);

    if (isset($searchSubjID)) { // default
      $_SESSION['searchQC'] = $searchQC;
      echo "<script type=\"text/javascript\"> var searchQC = ".$searchQC.";</script>";
    }

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

    if (isset($searchGroup)) {
      $_SESSION['searchGroup'] = $searchGroup;
      echo "<script type=\"text/javascript\"> var searchGroup = ".$searchGroup.";</script>";
    }
    $allowEdit = 1;
    $allowView = 1;

    echo "<script type=\"text/javascript\"> var allowEdit = ".$allowEdit.";</script>";
    echo "<script type=\"text/javascript\"> var allowView = ".$allowView.";</script>";

    if ( isset ( $_SESSION['project_name'] ) )
      $project_name = $_SESSION['project_name'];
    else
      $project_name = "Project01";
    //if ( isset ( $_SESSION['user_name'] ) )
    //  $user_name = $_SESSION['user_name'];
    //if ( isset ( $_SESSION['user_site'] ) )
    //  $user_site = $_SESSION['user_site'];
    //if ( isset ( $_SESSION['project_short_description'] ) )
    //  $project_short_description = $_SESSION['project_short_description'];
}
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/picnet.table.filter.min.js"></script>
<script type="text/javascript" src="qc.js"></script>

<?php
// copy values into JavaScript variables, access values using window.<variable name>
echo '<script type="text/javascript">
  /* each session defines the following list of variables */
  var user_name    = "'.$user_name.'";
  var project_name = "'.$project_name.'";
</script>';
?>

<script type="text/javascript">
  // Insert custom Javascript here...
  var project_name_store = project_name;
  var rawDataCsv = project_name + "_MRI_DTI_Complete.csv";
  var qcDataCsv  = project_name + "_QC.tsv";
  var stat_header_line = [];
  // the list contains arrays of "PatientID", "VisitID", "QC-code"
  var QCList = new Array();

  // report back the index of the text a in the list of all available measures
  function getColumnIndexPerText(a) {
      for (var i = 0; i < stat_header_line.length; i++)
          if (stat_header_line[i] == a )
              return i;
      return -1;
  }

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

  function getQCAsCsv() {
     data = getQCAll();
     if (data === undefined)
	return;
     var date = new Date();
     var htm = "<html><head><title>QC at " + date + "</title></head><body><pre>";
     htm += "# project: " + window.project_name + ", " + date + "\n";
     htm += "# 'QCSTAT' is either bad == -1, unknown == 0, or good == 1\n";
     htm += "# 'user' is the login name of the user that last changed an entry in the table\n";
     htm += "PatientID,VisitID,QCSTAT,BadSeries,Notes,TimeStamp,User\n";
     for (var i = 0; i < data.length; i++) {
	var dat = data[i];
	var blub = dat["badseries"];
	htm += "\"" + dat["patientid"] + "\",\"" 
	    + dat["visitid"] + "\","
	    + dat["code"] + ",\""
	    + dat["badseries"] + "\",\""
	    + dat["notes"] + "\",\""
	    + dat["time"] + "\",\""
            + dat["user"] + "\"\n";
     }

     var doc = window.open('','Quality Control');
     doc.document.write(htm + "</pre></body></html>");
     doc.document.close();
  }

  $.expr[':'].focus = function( elem ) {
    return elem === document.activeElement && ( elem.type || elem.href );
  };

  var timeout;
  function update() {
	data = getQCAll();
	var $allCodes = $('span#QCCODE');
	$allCodes.each(function(index) {
	   patientid = $(this).attr('patientid');
	   visitid = $(this).attr('visitid');
           elem = $(this);
           replaced = false;
	   $.each(data, function(index, val) {
              if (val["patientid"] == patientid && val["visitid"] == visitid) {
		 elem.attr('code', val["code"]);
		 elem.attr('class', getStringForCode( val["code"] ));
		 elem.attr('current-code', val["code"]);
		 elem.val(getStringForCode( val["code"] ));
		 elem.html(getStringForCode( val["code"] ));
                 replaced = true;
              }
	   });
           if (!replaced) { // clear the entry as we don't know anything about this
	     elem.attr('code', 0);
             elem.attr('class', getStringForCode( 0 ));
	     elem.attr('current-code', 0);
	     elem.val(getStringForCode( 0 ));
             elem.html(getStringForCode( 0 ));
	   }
	});
	var $allCodes = $('input.notesfield');
	$allCodes.each(function(index) {
	   patientid = $(this).attr('patientid');
	   visitid = $(this).attr('visitid');
           elem = $(this);
           replaced = false;
	   $.each(data, function(index, val) {
 	       // we only want to update the field if the user is not currently changing it himself
               if (val["patientid"] == patientid && val["visitid"] == visitid &&
		   !$(elem).is(":focus")) {
		   $(elem).val(val["notes"]);
                   replaced = true;
               }
           });   
           if (!replaced) { // clear the entry as we don't know anything about this
	     //$(elem).val("");
	   }
        });
	var $allCodes = $('input.badseriesfield');
	$allCodes.each(function(index) {
	   patientid = $(this).attr('patientid');
	   visitid = $(this).attr('visitid');
           elem = $(this);
           replaced = false;
	   $.each(data, function(index, val) {
	      // we only want to update the field if the user is not currently changing it himself
              if (val["patientid"] == patientid && val["visitid"] == visitid &&
	          !$(elem).is(":focus")) {
		 $(elem).val(val["badseries"]);
                 replaced = true;
              }
           });
           if (!replaced) { // clear the entry as we don't know anything about this
	     // $(elem).val("");
	   }
        });
     timeout = setTimeout( update , 10000 );
  }

  $(document).ready(function() {
    $('#loading').html('Loading data...');

    $('.project_name').text(project_name);
  
    str = "editing and viewing data is forbidden for "+user_name;  
    if (allowEdit) {
      if (allowView) {
         $('#permissions').html('[ '+user_name + ': r/w ]');
      } 
    } else {
      if (allowView) {
         $('#permissions').html('[ '+user_name+': r');
      }
    }

    $.ajax({url: '/data/' + window.project_name_store + '/data_uncorrected/' + rawDataCsv,
          success: function(tsv) {
          var lines = [],
              listen = false,
              date;

          try {
              // split the data into lines and parse them
              var include_list = ['Site', 'SubjID', 'Sex', 'Age', 'Group', 'VisitID'];
              tsv = tsv.split(/\r?\n/g);

              var tbody = document.getElementById("table1-tbody-id");
              $.each(tsv, function(i, line) {
                if (line == '') {
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

                        var group = document.createElement("th");
                        // group.setAttribute('filter', 'true');
			// group.setAttribute('filter-type', 'ddl');
                        var text = document.createTextNode('QC status');
                        group.appendChild(text);
                        headerrow.appendChild(group);

                        var group = document.createElement("th");
                        var text = document.createTextNode('Bad series');
                        group.setAttribute('filter', 'false');
                        group.appendChild(text);
                        headerrow.appendChild(group);

                        var group = document.createElement("th");
                        var text = document.createTextNode('Notes');
                        group.setAttribute('filter', 'false');
                        group.appendChild(text);
                        headerrow.appendChild(group);

                        // add entries for derived data (ImageViewer)
                        var group = document.createElement("th");
                        group.setAttribute('filter', 'false');
                        var text = document.createTextNode('ImageViewer');
                        group.appendChild(text);
                        headerrow.appendChild(group);

                        
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

                      var group = document.createElement("td");
                      var lin = document.createElement("span"); // add QC checkboxes
                      group.appendChild(lin);
                      headerrow.appendChild(group);

                      var group = document.createElement("td");
                      var lin4 = document.createElement("input"); // notes section
                      lin4.setAttribute('type', 'text');
                      lin4.setAttribute('class', 'badseriesfield');
                      group.appendChild(lin4);
                      headerrow.appendChild(group);

                      var group = document.createElement("td");
                      var lin3 = document.createElement("input"); // notes section
                      lin3.setAttribute('type', 'text');
                      lin3.setAttribute('class', 'notesfield');
                      group.appendChild(lin3);
                      headerrow.appendChild(group);

                      var group = document.createElement("td");
                      var lin2 = document.createElement("a");
                      var text = document.createTextNode('ImageViewer');
                      lin2.appendChild(text);
                      group.appendChild(lin2);
                      headerrow.appendChild(group);
                        var VisitID_index   = getColumnIndexPerText('VisitID');
                        var StudyDate_index = getColumnIndexPerText('StudyDate');
                        var PatientID_index = getColumnIndexPerText('SubjID');
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
                            if (pos == PatientID_index) {
                                PatientID = vals[pos];
                            }

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
                            var group = document.createElement("td");
                            var text = document.createTextNode(vals[pos]);
                            if (pos == VisitID_index) {
                                $(text).addClass("visitid");
                            }
                            group.appendChild(text);
                            headerrow.appendChild(group);
                        }
                        lin2.setAttribute('href', '/applications/ImageViewerMPR/?patient=' + VisitID.replace('_','').replace('_','') + '&visit=' + StudyDate);
                        lin2.setAttribute('target','ImageViewer');
			var code = 0; //getQCCode( PatientID, VisitID );
			lin.setAttribute('id', 'QCCODE');
			lin.setAttribute('current-code', code);
			lin.setAttribute('patientID', PatientID);
			lin.setAttribute('visitID', VisitID);
			lin3.setAttribute('value', "");
			lin3.setAttribute('patientID', PatientID);
			lin3.setAttribute('visitID', VisitID);
			lin4.setAttribute('value', "");
			lin4.setAttribute('patientID', PatientID);
			lin4.setAttribute('visitID', VisitID);
			lin.value = getStringForCode( code );
			lin.appendChild( document.createTextNode( getStringForCode( code ) ));
			lin.setAttribute('class', getStringForCode( code ));

                        tbody.appendChild(headerrow);
                    }                        
                }
	        listen = true;
	  });	      
	  // update for all QCCODE entries and for all notes the entry by calling getQCAll()
	  timeout = setTimeout( update , 100);

          var dt = document.getElementById("TABLE1");

          var tab = $('#TABLE1');
          var options = {
                additionalFilterTriggers: [$('#quickfind')]
//		matchingRow: function(state, tr, textTokens) {
//  		  if (!state || state.id != 'filter_0') { 
//		     return true; 
//		  }
//		  return state.value != true || tr.children('td:eq(2)').text() == 'unknown';
//		}
          };
          tab.tableFilter(options);

          // todo: trigger is not working, entries are filled into the fields but search is not startet
          if (window.searchQC)
            $('#TABLE1_filter_0').val(window.searchQC).trigger('keyup');
          if (window.searchSubjID)
            $('#TABLE1_filter_1').val(window.searchSubjID).trigger('keyup');
          if (window.searchVisitID)
            $('#TABLE1_filter_2').val(window.searchVisitID).trigger('keyup');
          if (window.searchSex)
            $('#TABLE1_filter_3').val(window.searchSex).trigger('keyup');
          if (window.searchAge)
            $('#TABLE1_filter_4').val(window.searchAge).trigger('keyup');
          if (window.searchSite)
            $('#TABLE1_filter_5').val(window.searchSite).trigger('keyup');
          if (window.searchGroup)
            $('#TABLE1_filter_6').val(window.searchGroup).trigger('keyup');

	  $('span#QCCODE').click(function(event) {
		var code = $(this).attr('current-code');
		if (typeof(code) == 'string' && code.length == 0)
		   code = -1;
		if (typeof(code) == 'string')
		   code = parseInt(code);
		if (code != -1 && code != 0 && code != 1)
		   code = -1; // (+1) equals we don't know 
	        var patientID = $(this).attr('patientID');
		var visitID = $(this).attr('visitID');
		// toggle to the next code
		var nextCode = (((parseInt(code)+1) + 1) % 3) - 1;
		addQCEntry( patientID, visitID, nextCode, window.user_name );
		$(this).attr('current-code', nextCode);
		$(this).html( getStringForCode( nextCode ));
		$(this).attr('class', getStringForCode( nextCode ));
          });
	  $('input.notesfield').keypress(function(event) {
	      if (event.keyCode == 13) {
	         var patientID = $(this).attr('patientID');
	         var visitID   = $(this).attr('visitID');
	         var notes     = $(this).val();

	         addQCNotes( patientID, visitID, notes, window.user_name );
              }
          });
	  $('input.badseriesfield').keypress(function(event) {
	      if (event.keyCode == 13) {
	         var patientID = $(this).attr('patientID');
	         var visitID   = $(this).attr('visitID');
	         var notes     = $(this).val();

	         addQCBadSeries( patientID, visitID, notes, window.user_name );
              }
          });
	  $('input.badseriesfield').focusout(function() { // leave focus 
	      var patientID = $(this).attr('patientID');
	      var visitID   = $(this).attr('visitID');
	      var notes     = $(this).val();
              addQCBadSeries( patientID, visitID, notes, window.user_name );
          });

          } catch (e) { alert("in QC:" + e.message) }
          $('#loading').html('Loading done...').fadeOut(3000);
	     },
	     error: function() {
	         $('#loading').html('Loading data, done...');
	     }} // success end
	   ); // ajax end

});

</script>  
