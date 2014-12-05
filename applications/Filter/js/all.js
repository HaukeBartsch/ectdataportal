 var filters = null;
 var allMeasures = null;
 var idxSubjID = 0;
 var idxVisitID = 1;
 var idxStudyDate = -1;
 var header = [];

 function getAllFilters() {
   jQuery.getJSON('getFilter.php', function(data) {
     filters = data;
     // replace function string with real function
     for (var i = 0; i < filters.length; i++) {
       /* for (var j = 0; j < filters[i]["rules"].length; j++) {
         try {
           filters[i]["rules"][j] = JSON.parse(JSON.stringify(filters[i]["rules"][j]), function(key, value) {
             if (value && (typeof value === 'string') && value.indexOf("function") === 0) {
               // we can only pass a function as string in JSON ==> doing a real function
               eval("var jsFunc = " + value);
               return jsFunc;
             }
             return value;
           });
         } catch (e) {
           console.log("Error parsing(" + e + "): " + filters[i]["rules"][j]["func"]);
         }
       } */
     }

     // add the null filter first
     jQuery(".existingFilters").append('<option>Predefined filters</option>');
     for (var i = 0; i < filters.length; i++) {
        var optGrp = document.createElement('optgroup');
	jQuery(optGrp).attr('label', filters[i]["name"]);
        jQuery('.existingFilters').append(optGrp);
        for (var j = 0; j < filters[i]["rules"].length; j++) {
           jQuery(optGrp).append('<option>' + filters[i]["rules"][j]["name"] + '</option>');
        }
     }
     jQuery('.selectpickerS').selectpicker({
        style: ''
     });
     jQuery('.selectpickerS').change(function() {
        for (var i = 0; i < filters.length; i++) {
          for (var j = 0; j < filters[i]["rules"].length; j++) {
            if (jQuery(this).val() == filters[i]["rules"][j]["name"]) {
	       jQuery('.inputmeasures').val(filters[i]["rules"][j]["func"]);
	       changeSearch();
            }
          }
        }
     });
   });
 }

 function highlight(where, what) {
   // get that measure for each variable
   var idWhat = -1;
   for (var i = 0; i < header.length; i++) {
     if (what == header[i]) {
       idWhat = i;
       break;
     }
   }
   if (idWhat == -1) {
     console.log("Error: could not find " + what + " variable in the header");
     return;
   }
   var measure = new Object();
   var valueAr = jQuery.map(allMeasures, function(v, i) {
     return v[idWhat];
   });
   for (var i = 0; i < allMeasures.length; i++) {
     if (!measure.hasOwnProperty(allMeasures[i][idxSubjID]))
       measure[allMeasures[i][idxSubjID]] = new Object();
     measure[allMeasures[i][idxSubjID]][allMeasures[i][idxVisitID]] = allMeasures[i][idWhat];
   }
   // come up with a color code for this measure, sort and use colormap
   valueAr.sort();
   valueAr = jQuery.grep(valueAr, function(v, k) {
     return jQuery.inArray(v, valueAr) === k;
   });

   jQuery(where + " .data").each(function(dat) {
     var sid = jQuery(this).attr('SubjID');
     var vid = jQuery(this).attr('VisitID');
     var v = valueAr.indexOf(measure[sid][vid]) / (valueAr.length - 1);
     var col = parseInt(8 * v); // goes from 0 to valueAr.length
     // console.log("highlight: "+dat + " " + sid + " " + vid + " " + measure[sid][vid] + " val: .q" + col + "-9");
     //jQuery(this).children().remove();

     if (parseFloat(measure[sid][vid]) == measure[sid][vid]) {
       jQuery('<div class="spot ' + "q" + col + "-9" + '" title="' + what + ' = ' + parseFloat(measure[sid][vid]).toFixed(2) + '"></div>').appendTo(this); // .hide().appendTo(this).fadeIn(1500);
     } else {
       jQuery('<div class="spot ' + "q" + col + "-9" + '" title="' + what + ' = ' + measure[sid][vid] + '"></div>').appendTo(this);
     }
     //jQuery(this).append('<div class="spot ' + "q" + col + "-9" + '" title="' + what + ' = ' + measure[sid][vid] + '"></div>');
   });

 }

 // create one level of the filter
 // below - where one block should be
 function createBlock(below) {
   // organization: #below div .dataHere

   // add two rows, one for the data the other for the filter
   var d01 = document.createElement("div");
   jQuery(d01).addClass("row-fluid").css('margin-top', '10px');
   var d02 = document.createElement("div");
   jQuery(d02).addClass("row-fluid").css('margin-top', '10px');
   var d1 = document.createElement("div");
   var d2 = document.createElement("div");
   jQuery(d1).addClass("col-xs-9");
   jQuery(d2).addClass("col-xs-12");
   jQuery(d2).addClass("dataHere");
   jQuery(below).append("<div class=\"sectionTitle\" id=\"dataHereTitle\">All data points available in " + project_name + "</div>");
   jQuery(d02).append(d2);
   jQuery(below).append(d02);
   jQuery(below).append(d01);
   // now add a div for the data

   var existingFilters = document.createElement("div");
   jQuery(existingFilters).addClass('existingFilterDiv');
   jQuery(existingFilters).addClass('col-xs-3');
   var sel = document.createElement("select");
   jQuery(sel).addClass('selectpickerS');
   jQuery(sel).addClass('existingFilters');
   jQuery(sel).attr('data-live-search', 'true');
   jQuery(sel).attr('data-size', '10');
   jQuery(existingFilters).append(sel);
   jQuery(d01).append(existingFilters);

   jQuery(d01).append(d1);

   var d21 = document.createElement("div");
   //jQuery(d21).addClass("span12");
   jQuery(d21).addClass("select");
   jQuery(d1).append(d21);
   jQuery(d21).append('<div class="input-group"><input class="inputmeasures form-control" type="text" placeholder="select a predefined filter or enter your own"/><span class="input-group-addon btn" id="saveNewFilter">&nbsp;Save Filter</span></div>');
   jQuery(d21).append('<div id="info"></div>')
   jQuery('#saveNewFilter').click(function() {
       var z = jQuery('.inputmeasures').val();
       if(z == ""){
         return; // nothing to do
       }
       alert('not implemented yet, would have to store this as a filter');  
   });
 }

 function changeSearch() {
   jQuery('.loading').show();
   // do a partial load of only the variables of interest (overwrite allMeasures)
   var requiredVariables = [ "Gender", "SubjID", "VisitID", "StudyDate" ];

   // get the variables from the searchTerm
   var search = jQuery('.inputmeasures').val();
   var searchTerms = [];
   try {
     searchTerms = search.match(/[\"$]*[A-Za-z0-9_\.]+[\"\ ]*?/g).map(function(v){ return v.replace(/[\"\$]/g,''); });
   } catch(e) {};
   // create unique list of variables
   searchTerms = searchTerms.sort();
   for (var i = 1; i < searchTerms.length; i++) {
    if (searchTerms[i] == searchTerms[i-1]) {
      searchTerms.splice(i,1);
      i--;
    }
   }

   var languageKeywords = [ "has", "not", "and", "or", "visit", "numVisits" ];
   for (var i = 0; i < searchTerms.length; i++) {
     var idx = languageKeywords.indexOf(searchTerms[i]);
     if ( idx !== -1 || searchTerms[i] == +searchTerms[i] ) {
       searchTerms.splice(i, 1);
       i--; // check same position again because we removed one entry
     }
   }

   searchTermsAll = searchTerms.slice(0);
   searchTermsAll.push.apply(searchTermsAll, requiredVariables);
   // searchTermsAll = jQuery.unique(searchTermsAll);
   jQuery.ajax({
	dataType: "json",
	url: 'getRestrictedSet.php',
	type: 'POST',
	data: { "variables": JSON.stringify(searchTermsAll),
	        "project_name": project_name },
	success: function(data) {
	  // get the header
   	  header = data.shift();
   	  jQuery('.inputmeasures').autocomplete({
             source: header
          });
          for (var i = 0; i < header.length; i++) {
            if (header[i] == "SubjID")
              idxSubjID = i;
            if (header[i] == "VisitID")
              idxVisitID = i;
            if (header[i] == "StudyDate")
              idxStudyDate = i;
          }

          allMeasures = data;
          parse();

          jQuery('.spot').remove();
          try {
             createTermInfo(searchTerms);
          } catch(e) {};
          // create the output for this stage
          // check if output exists?
          if (jQuery('#start div .dataHere').parent().next().next().children(':first').find('.yes').length == 0) {
            var newDParent = jQuery('#start div .dataHere').parent().parent();
             var newDiv = document.createElement("div");
            jQuery(newDiv).addClass("row-fluid").css('margin-top', '-10px');
            if (jQuery('.yes').length == 0) {
              var yes = document.createElement("div");
              jQuery(yes).addClass("col-xs-6");
              jQuery(yes).addClass("yes");
              //createBlock(yes);
              // fill this block
          
              jQuery(newDiv).append(yes);
              var no = document.createElement("div");
              jQuery(no).addClass("col-xs-6");
              jQuery(no).addClass("no");
              //createBlock(no);
              // fill this block
          
              jQuery(newDiv).append(no);
              jQuery(newDParent).append("<div class=\"row-fluid\"><div class=\"sectionTitle col-xs-12\">Result of the current restriction</div></div>");
              jQuery(newDParent).append(newDiv);
            }
          }
          jQuery('.loading').hide();

        }
   });

 }

 function getAllData() {
   jQuery('.loading').css('display', '');
   jQuery.get('/data/' + project_name + '/data_uncorrected/usercache_' + project_name + '_' + user_name + '.csv' , function(data) {
     data = jQuery.csv.toArrays(data);
     // get the header
     header = data.shift();
     jQuery('.inputmeasures').autocomplete({
       source: header
     });
     for (var i = 0; i < header.length; i++) {
       if (header[i] == "SubjID")
         idxSubjID = i;
       if (header[i] == "VisitID")
         idxVisitID = i;
       if (header[i] == "StudyDate")
         idxStudyDate = i;
     }
     allMeasures = data;
     // initial display of data
     displayData(data, '#start div .dataHere');

     jQuery('.inputmeasures').change(function() {
       changeSearch();
     });
     jQuery('.loading').css('display', 'none');
   }).error(function() {
     jQuery('.loading').css('display', 'none');
     alert('Error, failed to download user data, create your cache in the Data Exploration application and come back...');
   });
 }

 // array of array in data
 function displayData(data, where) {
   jQuery(where).children().remove();
   str = '<div class="datas">';
   for (var i = 0; i < data.length; i++) {
     str = str + '<div class="data" SubjID="' + data[i][idxSubjID] + 
                 '" VisitID="' + data[i][idxVisitID] + 
                 '" StudyDate="' + data[i][idxStudyDate] + 
                 '" title="SubjID: ' + data[i][idxSubjID] + ', VisitID: ' + data[i][idxVisitID] + ', StudyDate: ' + data[i][idxStudyDate] + '">' + "" + '</div>';
   }
   str = str + '</div>';
   jQuery(where).append(str);
   jQuery(where).on('click', '.data', function(event) {
      showInfoWindow(event, this);
   });
 }

 function showInfoWindow(event, t) {
  var subjid = jQuery(t).attr('SubjID');
  var visitid = jQuery(t).attr('VisitID');
  var studydate = jQuery(t).attr('StudyDate');
  var title = jQuery(t).find('.spot').attr('Title');

  // create a div that we want to display inside a popup
  var popup = document.createElement('div');
  // create a unique ID for each div we create as a popup
  var numRand = Math.floor(Math.random() * 1000);
  popup.setAttribute('id', 'popup' + subjid + visitid);
  popup.className = 'highslide-html-content';

  var header = document.createElement('div');
  header.className = 'highslide-header';
  popup.appendChild(header);
  var headerList = document.createElement('ul');
  header.appendChild(headerList);
  var entry = document.createElement('li');
  headerList.appendChild(entry);
  entry.className = 'highslide-close';
  var closeLink = document.createElement('a');
  entry.appendChild(closeLink);
  closeLink.setAttribute('href', '#');
  closeLink.setAttribute('title', '{hs.lang.closeTitle}');
  closeLink.setAttribute('onclick', 'return hs.close(this)');
  var closeLinkSpan = document.createElement('span');
  closeLink.appendChild(closeLinkSpan);
  closeLinkSpan.innerHTML = '{hs.lang.closeText}';

  var popupBody = document.createElement('div');
  popupBody.className = 'highslide-body';
  popupBody.setAttribute('margin-top', '30px');
  popup.appendChild(popupBody);
  var popupBodyDiv = document.createElement('div');
  //popupBodyDiv.setAttribute('style', 'float: right; width: 110px; margin: 4px;');
  popupBody.appendChild(popupBodyDiv);
  var can = document.createElement('div');
  popupBodyDiv.appendChild(can);
  can.setAttribute('id', 'sliceCanvas' + subjid + visitid);
  jQuery(can).append("<br/><span>SubjID:" + subjid + "</span><br/>");
  jQuery(can).append("<span>VisitID:" + visitid + "</span><br/>");
  jQuery(can).append("<span>StudyDate:" + studydate + "</span><br/>");
  jQuery(can).append("<span>" + title + "</span><br/>");
  document.getElementById('place-for-popups').appendChild(popup);
  var te = document.createElement('div');
  te.setAttribute('id', 'text' + subjid + visitid);
  var txtNode = document.createTextNode("");

  te.appendChild(txtNode);
  popupBody.appendChild(te);
  var footer = document.createElement('div');
  footer.className = 'highslide-footer';
  popup.appendChild(footer);
  var span = document.createElement('span');
  span.className = 'highslide-resize';
  span.setAttribute('title', '{hs.lang.resizeTitle}');
  footer.appendChild(span);

  hs.htmlExpand(null, {
    pageOrigin: {
      x: event.pageX,
      y: event.pageY
    },
    contentId: 'popup' + subjid + visitid,
    headingText: 'Subject info', // + ' (' + gender + ')',
    width: 310,
    height: 190
  });
 }

 function createTermInfo( searchTerms ) {
   // look through allMeasures
   var infoStr = "";
   jQuery('#info').html(infoStr);
   searchTerms.forEach( function(t) {

     var idWhat = -1;
     for (var i = 0; i < header.length; i++) {
       if (t == header[i]) {
         idWhat = i;
         break;
       }
     }
     if (idWhat == -1) {
       infoStr = infoStr + "<div class=\"info\"><span>"+t+" is unknown</span></div>";      
       return;
     }

     var min = +allMeasures[0][idWhat];
     var max = min;
     if (isNaN(min)) {
        min = allMeasures[0][idWhat];
        max = "";
        // search for the next entry
        for (var i = 1; i < allMeasures.length; i++) {
          if (allMeasures[i][idWhat] !== "" && allMeasures[i][idWhat] !== min) {
            max = allMeasures[i][idWhat]; // just show the first two as examples 
            break;
          }
        }
     } else {
       for (var i = 0; i < allMeasures.length; i++) {
         if (!isNaN(allMeasures[i][idWhat])) {
           if (min > allMeasures[i][idWhat])
             min = +allMeasures[i][idWhat];
           if (max < allMeasures[i][idWhat])
             max = +allMeasures[i][idWhat];
         }
       }
       min = parseFloat(min).toFixed(2);
       max = parseFloat(max).toFixed(2);
     }
     infoStr = infoStr + "<div class=\"info\"><span>" + t + "</span> <span> " + min.toString() + "</span>...<span>" + max.toString() + "</span></div>";
   });
   jQuery('#info').html(infoStr);
 }

 function parse() {
   jQuery('.dataHere').hide();
   jQuery('#dataHereTitle').hide();
   // delete the dataHere block
   jQuery('.dataHere').children().remove();
   // try peg library
   // we will apply the rules to each data entry and generate an output array
   var searchTerm = jQuery('.inputmeasures').val();
   jQuery.get('js/grammar.txt?_=346', function(data) {
     var parser;
     try {
       parser = PEG.buildParser(data);
     } catch(e) {
       alert('Parser is invalid: ' + e);
     }
     var yes = [];
     var no = [];
     try {
       bla = 0;
       parser.parse(searchTerm);
     } catch(e) {
       alert(e.message);
     }
     for (var i = 0; i < allMeasures.length; i++) {
       bla = i;
       if (parser.parse(searchTerm))
          yes.push(allMeasures[i]);
       else
          no.push(allMeasures[i]);
     }
     console.log('number of yes/no: ' + yes.length + " " + no.length);
     displayData(yes, ".yes");
     displayData(no, ".no");

     // add Yea and Nay fields
     var yea = jQuery(document.createElement("div")).addClass("Yea");
     var nay = jQuery(document.createElement("div")).addClass("Nay");
     jQuery('.yes').append(yea);
     jQuery('.no').append(nay)
     //pile(yea, nay);

     var SubjIDIDX = header.indexOf("SubjID");
     var VisitIDIDX = header.indexOf("VisitID");
     if (SubjIDIDX == -1) {
         alert("Error: could not find a SubjID entry");
     }

     var yesSubjects = [];
     for(var i = 0; i < yes.length; i++) {
         if (VisitIDIDX > -1) {
           yesSubjects.push([ yes[i][SubjIDIDX], yes[i][VisitIDIDX] ]); // append the SubjID and the VisitID
         } else {
           yesSubjects.push( yes[i][SubjIDIDX] ); 
         }
     }
     yesSubjects = jQuery.unique(yesSubjects);
     var numYesSubjects = jQuery.unique(yesSubjects.map(function(e) { if (e.length == 2) return e[0]; else return e; })).length;

     var noSubjects = new Array();
     for(var i = 0; i < no.length; i++) {
         if (VisitIDIDX > -1) {
           noSubjects.push([ no[i][SubjIDIDX], no[i][VisitIDIDX] ]);
 	   } else {
           noSubjects.push( no[i][SubjIDIDX] );
         }
     }
     noSubjects = jQuery.unique(noSubjects); // we can have the same subject with multiple VisitIDs, but together they should be unique
     var numNoSubjects = jQuery.unique(noSubjects.map(function(e) { if (e.length == 2) return e[0]; else return e; })).length;

     // we should get a key for this selection (either Yea or Nay)
     var uniqueIDY = ("0000" + (Math.random()*Math.pow(36,4) << 0).toString(36)).slice(-4);
     var uniqueIDN = ("0000" + (Math.random()*Math.pow(36,4) << 0).toString(36)).slice(-4);

     jQuery('.Yea').html("Yea: " + yes.length + "<br/><small title=\"Use this key to reference the set of subjects for which the filter is true.\">key: #" + uniqueIDY + "</small>").attr('title', yes.length + ' sessions for which the filter "' + uniqueIDY + '" is true (#subjects: '+ numYesSubjects+')');
     jQuery('.Yea').draggable();
     jQuery('.Nay').html("Nay: " + no.length + "<br/><small title=\"Use this key to reference the set of subjects for which the filter is false.\">key: #" + uniqueIDN + "</small>").attr('title', no.length + ' sessions for which the filter "' + uniqueIDN + '" is false (#subjects: '+ numNoSubjects+')');
     jQuery('.Nay').draggable();

     // store this as subset
     jQuery.ajax({
         type: "POST",
         url: 'saveAsSubset.php',
         data: {
	    project_name: project_name,
            key: uniqueIDY,
            set: yesSubjects,
	    code: jQuery('.inputmeasures').val().replace(/\s/g,''),
            which: 'yes'
         }
     }).done(function(msg) {
         // alert(msg);
     }).error(function(msg) {
	       alert('error');
     });

     jQuery.ajax({
         type: "POST",
         url: 'saveAsSubset.php',
         data: {
            project_name: project_name,
            key: uniqueIDN,
            set: noSubjects,
	       code: jQuery('.inputmeasures').val().replace(/\s/g,''),
            which: 'no'
         }
     }).done(function(msg) {
         // alert(msg);
     }).error(function(msg) {
	       alert('hi');
     });

     var search = jQuery('.inputmeasures').val();
     var variables = [];
     try {
       variables = search.match(/[\"\$]*[A-Za-z0-9_\.]+[\"\ ]*?/g).map(function(v){ return v.replace(/[\"\$]/g,''); });
     } catch(e) {};

     // create unique list of variables
     variables = variables.sort();
     for (var i = 1; i < variables.length; i++) {
      if (variables[i] == variables[i-1]) {
        variables.splice(i,1);
        i--;
      }
     }

     var languageKeywords = [ "has", "not", "and", "or", "visit", "numVisits" ];
     for (var i = 0; i < variables.length; i++) {
       var idx = languageKeywords.indexOf(variables[i]);
       if ( idx !== -1 || variables[i] == +variables[i]) {
         variables.splice(i, 1);
         i--; // check same position again because we removed an entry
       }
     }

     if (variables.length == 0) {
        variables.push("SubjID");
     }
     variables.forEach( function(v) { 
         highlight('.yes', v);
     });
     variables.forEach( function(v) { 
         highlight('.no', v);
     });

   });
 }

 jQuery(document).ready(function() {
   jQuery('.project_name').text(project_name);
   createBlock('#start');
   getAllFilters();
   jQuery('#dataHereTitle').hide();
   jQuery('.inputmeasures').change(function() {
      changeSearch();
   });

   setTimeout(function() { getAllData() }, 0);
 });







