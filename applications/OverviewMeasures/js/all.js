version = "";
var columnNames;
var stats;
var chart;

jQuery(document).ready(function() {
  jQuery('.current-project').text(project_name);
  readColumnNames();
  readStats();
  readDefaultValues();
  resizeSearch();

  jQuery(window).resize(function() {
    resizeSearch();
  });

  jQuery('#history ul').on("click", "a", function() {
    jQuery('#SearchField').val(jQuery(this).text());
    // do we have to show the boxplot? Only if it does not exist yet
    var historyOn = true;
    jQuery('#display').children('div').each(function(idx, value) {
      if (jQuery(value).attr('searchterm') == jQuery('#SearchField').val()) {
        historyOn = false;
      }
    });
    search(historyOn);
  });

  jQuery('#display').on("click", "div", function() {
    jQuery('#SearchField').val(jQuery(this).attr('searchterm'));
    search(false);
  });

  jQuery('#SearchField').keyup(function(event) {
    if (event.keyCode == '13') {
      search(true);
    }
    return false;
  });
});

function stripSses(val) {
  if (val.substr(0, 2) == "s(")
    val = val.substring(2, val.length - 1);
  return val;
}
var stat_header_line = [];

function getColumnIndexPerText(a) {
  var b = a.toLowerCase();
  for (var i = 0; i < stat_header_line.length; i++)
    if (stat_header_line[i].toLowerCase() == b)
      return i;
  return -1;
}

// add them to the interface as first values (historyList)
function readDefaultValues() {
  jQuery.getJSON('/code/php/getProjectInfo.php', function(data) {
    // we need to identify the current project
    for (var i = 0; i < data.length; i++) {
      if (data[i]['name'] == project_name) {
        if (typeof data[i]['applications'] != 'undefined' && typeof data[i]['applications']['DataExploration'] != 'undefined' && typeof data[i]['applications']['DataExploration']['command'] != 'undefined') {
          // lets add this one command = data[i]['applications']['DataExploration']['command'];
          jQuery('#historyList').append("<li><a href=\"\#\">" + stripSses(data[i]['applications']['DataExploration']['command']) +
            "</a></li>");
        }
        if (typeof data[i]['applications'] != 'undefined' && typeof data[i]['applications']['DataExploration'] != 'undefined' && typeof data[i]['applications']['DataExploration']['yvalue'] != 'undefined') {
          // yvalue = data[i]['applications']['DataExploration']['yvalue'];
          jQuery('#historyList').append("<li><a href=\"\#\">" + stripSses(data[i]['applications']['DataExploration']['yvalue']) +
            "</a></li>");
        }
        if (typeof data[i]['applications'] != 'undefined' && typeof data[i]['applications']['DataExploration'] != 'undefined' && typeof data[i]['applications']['DataExploration']['functionOf'] != 'undefined') {
          // functionOf = data[i]['applications']['DataExploration']['functionOf'];
          jQuery('#historyList').append("<li><a href=\"\#\">" + stripSses(data[i]['applications']['DataExploration']['functionOf']) +
            "</a></li>");

        }
        if (typeof data[i]['applications'] != 'undefined' && typeof data[i]['applications']['DataExploration'] != 'undefined' && typeof data[i]['applications']['DataExploration']['interaction'] != 'undefined') {
          // interaction = data[i]['applications']['DataExploration']['interaction'];
          jQuery('#historyList').append("<li><a href=\"\#\">" + stripSses(data[i]['applications']['DataExploration']['interaction']) +
            "</a></li>");
        }
      }
    }
  });
}

function resizeSearch() {
  var h1 = jQuery(window).height();
  //console.log('height is: ' + h1);
  // remove height of top bar
  if (h1 > 100) {
    //console.log('height of history is:' + jQuery('#history').height() );
    h2 = h1 / 2 - jQuery('#history').height() - 100;
  } else {
    h2 = 0;
    h1 = 0;
  }
  //console.log('now set height to : ' + h2/2);
  jQuery('#search-block').slideDown('slow').animate({
    marginTop: h2
  });
  //jQuery('#result-block').css('margin-top', h1/2 - 100 ); // space for scatter plot
  //jQuery('#search-block').css('margin-bottom', h2);
  jQuery('#ir_container').css('height', h1 / 2);
}

function closeD(id) {
  jQuery('#display #' + id).toggle("slide", {
    direction: 'right'
  });
  return false;
}
var lastSearchItem = "";

function search(addToHistory) {
  if (lastSearchItem.length > 0 && lastSearchItem == jQuery('#SearchField').val()) {
    return; // nothing to do
  }

  createScatterPlot(lastSearchItem, jQuery('#SearchField').val());
  lastSearchItem = jQuery('#SearchField').val();
  if (!addToHistory)
    return;

  var id = 'boxplot' + count++;
  jQuery('#display div').removeClass('glowing-border');
  jQuery('#display').prepend("<div id=\"" + id + "\" class=\"boxplot-container glowing-border\" width=\"120\" height=\"500\"><button type=\"button\" class=\"close\" onclick=\"closeD('" + id + "'); return false;\">&times;</button>" + "</div>");
  jQuery('#' + id).attr('searchterm', jQuery('#SearchField').val());
  if (parseOneStat(jQuery('#SearchField').val(), id) == 0) {
    // failed
    jQuery('#display').children().first().remove();
    jQuery('#SearchField').addClass('error');
    return;
  }
  jQuery('#SearchField').removeClass('error');
  jQuery('#' + id).attr('title', jQuery('#SearchField').val());

  // add this entry now to the history
  var entry = jQuery("<li><a href=\"\#\">" + jQuery('#SearchField').val() + "</a></li>")
    .hide()
    .css('opacity', 0.0);
  entry.appendTo("#historyList").slideDown('slow').animate({
    opacity: 1.0
  });
  resizeSearch(); // move text field back into middle 
}
var count = 0;
// column names are added to the search field for autocomplete
function readColumnNames() {
  jQuery.getJSON('/applications/DataExploration/user_code/usercache_' + project_name + '_' + user_name + version + '_colnames.json', function(data) {
    columnNames = data;
    jQuery('#measures').text(columnNames.length);
    jQuery('#SearchField').autocomplete({
      source: data,
      change: function(event, ui) {
        search(true);
      }
    });
  });
}

var version = '';

function createScatterPlot(a, b) {
  var dataString = '_v=' + version + '&user_name=' + user_name + '&project_name=' + project_name + '&a=' + a + '&b=' + b;
  jQuery.ajax({
    type: "POST",
    url: "executeR.php",
    data: dataString,
    success: function(summary) {
      reloadData(a, b);
    },
    error: function(ob, errStr) {
      console.log(errStr);
    }
  });
  return false;
}

function createCorrelationList() {
  var dataString = '_v=' + version + '&user_name=' + user_name + '&project_name=' + project_name;
  jQuery.ajax({
    type: "POST",
    url: "executeRCorrelations.php",
    data: dataString,
    success: function(summary) {
       // get the data file
       var rawDataCsv = "curves/" + user_name + "_" + project_name + "_curves/" + user_name + "_" + project_name + "_Correlations.json";
       jQuery.getJSON(rawDataCsv, function(data) {
         var rawDataCsv = "curves/" + user_name + "_" + project_name + "_curves/" + user_name + "_" + project_name + "_CorrelationsNames.json";
         jQuery.getJSON(rawDataCsv, function(data2) {
           // add to interface
           interface = "<ul style=\"height: 200px; overflow-y: scroll;\">";
	   for (var i = 0; i < data.ID.length; i++) {
             interface = interface + "<li style=\"display: block;\"><a onclick=\"reloadData('"+ data2[data.ID[i]] + "','" + data2[data.ID2[i]] +"');\">"+ data2[data.ID[i]] + " - " + data2[data.ID2[i]] + " [" + data.cor[i] +"]</a></li>";
	   }
           interface = interface + "</ul>";
           jQuery('#search-block').append(interface);
         });
       });
    },
    error: function(ob, errStr) {
      console.log(errStr);
    }
  });
  return false;    
}

function CSVToArray(strData, strDelimiter) {
  // Check to see if the delimiter is defined. If not,
  // then default to comma.
  strDelimiter = (strDelimiter || ",");

  // Create a regular expression to parse the CSV values.               
  var objPattern = new RegExp(
    (
      // Delimiters.                                                 
      "(\\" + strDelimiter + "|\\r?\\n|\\r|^)" +

      // Quoted fields. 
      "(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +

      // Standard fields.
      "([^\"\\" + strDelimiter + "\\r\\n]*))"
    ),
    "gi"
  );

  var arrData = [
    []
  ];
  var arrMatches = null;

  // Keep looping over the regular expression matches
  // until we can no longer find a match.
  while (arrMatches = objPattern.exec(strData)) {
    // Get the delimiter that was found.
    var strMatchedDelimiter = arrMatches[1];

    // Check to see if the given delimiter has a length
    // (is not the start of string) and if it matches
    // field delimiter. If id does not, then we know
    // that this delimiter is a row delimiter.
    if (
      strMatchedDelimiter.length &&
      (strMatchedDelimiter != strDelimiter)
    ) {

      // Since we have reached a new row of data,
      // add an empty row to our data array.
      arrData.push([]);
    }

    // Now that we have our delimiter out of the way,
    // let's check to see which kind of value we
    // captured (quoted or unquoted).
    if (arrMatches[2]) {

      // We found a quoted value. When we capture
      // this value, unescape any double quotes.
      var strMatchedValue = arrMatches[2].replace(
        new RegExp("\"\"", "g"),
        "\""
      );
    } else {
      // We found a non-quoted value.
      var strMatchedValue = arrMatches[3];
    }
    // Now that we have our value string, let's add
    // it to the data array.
    arrData[arrData.length - 1].push(strMatchedValue);
  }

  // Return the parsed data.
  return (arrData);
}

var plotdata = [];

function reloadData(a, b) {
  var rawDataCsv = "curves/" + user_name + "_" + project_name + "_curves/" + user_name + "_" + project_name + "_Scatter.tsv";
  stat_header_line = [];

  jQuery.get(rawDataCsv, {
    '_': jQuery.now()
  }, function(tsv) {
    var lines = [],
      listen = false,
      plotdata = [],
      categories = []; // store categorical data

    try {
      // split the data return into lines and parse them
      tsv = tsv.split(/\n/g);
      jQuery.each(tsv, function(i, line) {
        listen = true;
        if (line == '' || line.charAt(0) == '#' || stat_header_line.length == 0) {
          listen = false;
        }
        // extract the header line from the first comment line
        if (listen == false && stat_header_line.length == 0) {
          line = CSVToArray(line);
          line = line[0];
          stat_header_line = [];
          for (var i = 0; i < line.length; i++) {
            var name = line[i];
            name = name.replace('"', '');
            name = name.replace('"', '');
            stat_header_line.push(name);
          }

          // what column in the data belongs to our keys?
          gender = getColumnIndexPerText("Sex");
          if (gender == -1) {
            gender = getColumnIndexPerText("Gender");
          }
          subjectID = getColumnIndexPerText("SubjID");
          visitID = getColumnIndexPerText("VisitID");
          studyDate = getColumnIndexPerText("StudyDate");
          age = getColumnIndexPerText("Age");
          site = getColumnIndexPerText("Site");
          xaxis = getColumnIndexPerText(a);
          yaxis = getColumnIndexPerText(b);
        } else if (listen == true) { // header line
          line = CSVToArray(line);
          line = line[0];

          var vals = [];
          for (var i = 0; i < line.length; i++) {
            var val = line[i];
            if (typeof val == "undefined") {// a column can be missing - empty string
		vals.push("");
                continue;
	    }
            val = val.replace('"', '');
            val = val.replace('"', '');
            val = val.replace(/\s/g, "");

            if (i == 0) {
              if (window.patients && window.patients != "") {
                if (i == subjectID) {
                  var found = 0;
                  // find the current subjectID in the array of patients
                  for (var pat in patients) {
                    if (patients[pat] == val) {
                      found = 1;
                      break;
                    }
                  }
                  if (found == 0)
                    continue;
                }
              }
            }

            if (i != gender &&
              i != subjectID &&
              i != visitID &&
              i != studyDate &&
              i != site
            ) {
              if (!isNaN(val) && isFinite(val))
                 vals.push(parseFloat(val));
              else {
                vals.push(val);
              }
            } else
              vals.push(val);
          }
          plotdata.push(vals);
        }
      });
      // now plot plotdata
      var siteIndex = getColumnIndexPerText("Site");
      var ageIndex = getColumnIndexPerText("Age");
      var subjIndex = getColumnIndexPerText("SubjID");
      var visitIndex = getColumnIndexPerText("VisitID");
      var xaxisIndex = getColumnIndexPerText(a);
      var yaxisIndex = getColumnIndexPerText(b);
      var siteConf = [];
      var markerSize = 4;

      siteConf.push({
        name: site,
        id: site,
        marker: {
          radius: markerSize,
          lineColor: 'rgba(10,10,10,1)',
          lineWidth: 1,
          fillColor: 'rgba(51,102,255,.5)'
        },
        showInLegend: 0
      });
      var options = {
        chart: {
          renderTo: 'ir_container',
          defaultSeriesType: 'scatter',
          zoomType: 'xy',
	  backgroundColor: 'rgba(0,0,0,0)',
	  events: {
	    redraw: function(event) {
		addQuantilesToScatter();
	    }
	  }
        },
        title: {
          text: project_name + ' data'
        },
        subtitle: {
          text: ''
        },
        xAxis: {
          title: {
            enabled: true,
            text: a
          },
          tickWidth: 1,
          gridLineWidth: 0,
          labels: {
            align: 'left',
            x: 3,
            y: -3
          }
        },
        yAxis: [{
          title: {
            text: b
          },
	  lineWidth: 1,
	  tickWidth: 1,
          gridLineWidth: 0
        }],
        legend: {
          align: 'right',
          verticalAlign: 'top',
          y: 45,
          floating: true,
          borderWidth: 1
        },
        tooltip: {
          formatter: function() {
            var series = chart.get(this.series.name);
            var index = 0
            for (u = 0; u < series.data.length; u++) {
              if (series.data[u].x == this.x && series.data[u].y == this.y) {
                index = u;
                break;
              }
            }

            var names = '';
            var site = '';
            var siteIndex = getColumnIndexPerText("Site");
            var subjectIndex = getColumnIndexPerText("SubjID");
            var genderIndex = getColumnIndexPerText("Sex");
            if (genderIndex == -1)
              genderIndex = getColumnIndexPerText("Gender");
            names = plotdata[index][subjectIndex];
            site = plotdata[index][siteIndex];
            visit = plotdata[index][visitIndex];
            gender = plotdata[index][genderIndex];

            return '<h3>' + names + '/' + visit + ' (' + gender + ((typeof site == 'undefined') ? '' : (', ' + site)) + '):</h3><br/>x=' + this.x.toFixed(2) + ', y=' + this.y.toFixed(2);
          }
        },
        plotOptions: {
          scatter: {
            point: {
              events: {
                touched: function() {
                  jQuery(document).trigger('click', this);
                },
                click: function() {

                }
              }
            },

            marker: {
              radius: markerSize,
              states: {
                hover: {
                  enabled: true,
                  lineColor: 'rgb(200,20,20)'
                }
              }
            },
            states: {
              hover: {
                marker: {
                  enabled: false
                }
              }
            }
          }
        },
        series: siteConf
      }

      // find out if one of the two columns contains a categorical variable
      category = [];
      category.push(new Object);
      category.push(new Object);
      for (i = 0; i < plotdata.length; i++) {
        if (isNaN(plotdata[i][xaxisIndex]*1)) { // multiply with 1 works only with numbers
            // this is a string
            if (category[0][plotdata[i][xaxisIndex]] == undefined)
              category[0][plotdata[i][xaxisIndex]] = 1;
            else
              category[0][plotdata[i][xaxisIndex]]++;
        }
        if (isNaN(plotdata[i][yaxisIndex]*1)) { // multiply with 1 works only with numbers
            // this is a string
            if (category[1][plotdata[i][yaxisIndex]] == undefined)
              category[1][plotdata[i][yaxisIndex]] = 1;
            else
              category[1][plotdata[i][yaxisIndex]]++;
        }
      }
      var countCategories = 0;
      var aCategories = [];
      for (var v in category[0]) {
        countCategories++;
        aCategories.push(v);
      }
      var aIsCategory = countCategories>1?true:false;
      var bCategories = [];
      countCategories = 0;
      for (var v in category[1]) {
        countCategories++;
        bCategories.push(v);
      }
      var bIsCategory = countCategories>1?true:false;

      // to plot one categorical against one other categorical variable we need to use
      // something like: http://www.highcharts.com/demo/column-drilldown
      if (aIsCategory && bIsCategory) {
        //console.log('Error: cannot display two categorical variables yet...');
        //options.subtitle.text = 'cannot display two categorical variables together';

        var catx = {}, caty = {};
        var brandsData = [],
            drilldownSeries = [];
 
        columns = [];
        columns[0] = []; // xAxis
        columns[1] = []; // yAxis
        columns[2] = []; // how many in that pair
        var d = new Object;
        for (i = 0; i < plotdata.length; i++) {
          var key = plotdata[i][xaxisIndex] + ',' + plotdata[i][yaxisIndex];
          if (!d[key])
            d[key] = 1;
          else
            d[key]++;
        }
        for (var i in d) {
          var acat = i.split(',')[0];
          var bcat = i.split(',')[1];
          var ccat = d[i];
          columns[0].push(acat);
          columns[1].push(bcat);
          columns[2].push(ccat);
          if (!catx[acat]) {
            catx[acat] = ccat;
          } else {
            catx[acat] += ccat;
          }
          if (bcat !== null) {
            if (!caty[acat]) {
              caty[acat] = [];
            }
            caty[acat].push([bcat, ccat]);
          }
        }

        $.each(catx, function(name, y) {
          brandsData.push({
            name: name,
            y: y,
            drilldown: caty[name] ? name : null
          });
        });
        $.each(caty, function(key, value) {
          drilldownSeries.push({
            name: key,
            id: key,
            data: value
          });
        });

        $('#ir_container').highcharts({
          chart: {
	    type: 'column',
          },
          title: {
            text: project_name + ' data'
          },
          subtitle: {
            text: 'Click the columns to view second category.'
          },
          xAxis: {
            type: 'category'
          },
          yAxis: {
            title: {
              text: b
            }
          },
          legend: {
            enabled: false
          },
          plotOptions: {
            series: {
              borderWidth: 0,
              dataLabels: {
                enabled: true,
                format: '{point.y:.0f}'
              }
            }
          },
          tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b><br/>'
          },

          series: [{
            name: 'Category A',
            colorByPoint: true,
            data: brandsData
          }],
          drilldown: {
            series: drilldownSeries
	  }
        });

        //chart = new Highcharts.Chart(options);
        return;
      }

      dataSite = [];
      var countA = 0;
      var countB = 0;
      var countTotal = 0;
      if (!aIsCategory && !bIsCategory) {
        for (i = 0; i < plotdata.length; i++) {
          if (plotdata[i][xaxisIndex] != undefined && !isNaN(plotdata[i][xaxisIndex]))
            countA++;
          if (plotdata[i][yaxisIndex] != undefined && !isNaN(plotdata[i][yaxisIndex]))
            countB++;
          if (!isNaN(plotdata[i][xaxisIndex]) && !isNaN(plotdata[i][yaxisIndex]))
            countTotal++;
          else
            continue;
          dataSite.push([plotdata[i][xaxisIndex], plotdata[i][yaxisIndex]]);
        }
      } else { // one of the variables is categorical
        if (aIsCategory){
          options.xAxis.categories = aCategories;
          for (i = 0; i < plotdata.length; i++) {
             dataSite.push( [ aCategories.indexOf(plotdata[i][xaxisIndex]), plotdata[i][yaxisIndex] ] );
             if (plotdata[i][yaxisIndex] != undefined && !isNaN(plotdata[i][yaxisIndex])){
               countB++;
               countTotal++;
             }
             countA++;
          }
        } else {
          options.yAxis.categories = bCategories;
          for (i = 0; i < plotdata.length; i++) {
             dataSite.push( [ plotdata[i][xaxisIndex], bCategories.indexOf(plotdata[i][yaxisIndex]) ] );
             if (plotdata[i][xaxisIndex] != undefined && !isNaN(plotdata[i][xaxisIndex])) {
               countA++;
               countTotal++;
             }
             countB++;
          }
        }
      }
      options.subtitle.text = 'N = ' + countTotal + ' subjects with both measures (Nx:'+countA+',Ny:'+countB+')';
      options.series[0].data = dataSite;
      chart = new Highcharts.Chart(options);
      jQuery('#usage-help').fadeOut();
      jQuery('#ir_container').attr('a', a);
      jQuery('#ir_container').attr('b', b);

      addQuantilesToScatter();

    } catch (e) {
      alert(e.message)
    }
  });

}

// add a visual representation of the quantiles to the plot
function addQuantilesToScatter() {
    var a = jQuery('#ir_container').attr('a');
    var b = jQuery('#ir_container').attr('b');
    var divA = null, divB = null;
    jQuery('#display').children('div').each(function(idx, value) {
	if (jQuery(value).attr('searchterm') == a) {
	  divA = value;
	}
	if (jQuery(value).attr('searchterm') == b) {
	  divB = value;
	}
    });
    if (divA != null) {
	var mi = jQuery(divA).attr('mi');
	var q1 = jQuery(divA).attr('q1');
	var me = jQuery(divA).attr('me');
	var q3 = jQuery(divA).attr('q3');
	var ma = jQuery(divA).attr('ma');
	
	if (mi && chart) {
	    var radius = 6;
	    jQuery('#q1').remove();
	    jQuery('#q3').remove();
	    jQuery('#median').remove();
	    jQuery('#xbox').remove();
	    var px1 = Math.round(chart.xAxis[0].toPixels(q1)-radius/2-2);
	    var py1 = Math.round(chart.yAxis[0].toPixels(chart.yAxis[0].min)-radius/2-2);
	    var px2 = Math.round(chart.xAxis[0].toPixels(q3)-radius/2-2);
	    var py2 = Math.round(chart.yAxis[0].toPixels(chart.yAxis[0].min)-radius/2-2);
	    var px3 = Math.round(chart.xAxis[0].toPixels(me)-radius/2-2);
	    var py3 = Math.round(chart.yAxis[0].toPixels(chart.yAxis[0].min)-radius/2-2);
	    jQuery('.highcharts-container').append('<div id="q1" style="border-radius: '+radius+
						   'px; width: '+radius+
						   'px; height: '+radius+
						   'px; border: 3px solid rgba(250,150,100,0.9); background-color: rgba(250,100,100,0.2); position: absolute; left: '+
						   px1 +'px; top: '+
						   py1 +
						   'px;z-index: 99;" title="1st quantile: '+q1+'"></div>');
	    jQuery('.highcharts-container').append('<div id="q3" style="border-radius: '+
						   radius+'px; width: '+
						   radius+'px; height: '+
						   radius+'px; border: 3px solid rgba(250,150,100,0.9); background-color: rgba(250,100,100,0.2); position: absolute; left: '+
						   px2 +'px; top: '+
						   py2 +
						   'px;z-index: 99;" title="3rd quantile: '+q3+'"></div>');
	    jQuery('.highcharts-container').append('<div id="median" style="border-radius: '+
						   radius+'px; width: '+
						   radius+'px; height: '+
						   radius+'px; border: 3px solid rgba(250,150,100,0.9); background-color: rgba(250,100,100,0.2); position: absolute; left: '+
						   px3 +'px; top: '+
						   py3 +
						   'px;z-index: 99;" title="median: '+me+'"></div>');
	    jQuery('.highcharts-container').append('<div id="xbox" style="background-color: rgba(220,220,220,.6); width:'+(px2-px1+1)+'px; height:'+
						   (py1 - chart.yAxis[0].toPixels(chart.yAxis[0].max) + radius/2+2)+
						   'px; left: ' + (px1+radius/2+2) + 'px; top: ' + chart.yAxis[0].toPixels(chart.yAxis[0].max) + 'px; ' + 
						   'position: absolute; z-index: -1;"></div>');
	}
    }
    if (divB != null) {
	var mi = jQuery(divB).attr('mi');
	var q1 = jQuery(divB).attr('q1');
	var me = jQuery(divB).attr('me');
	var q3 = jQuery(divB).attr('q3');
	var ma = jQuery(divB).attr('ma');
	
	if (mi && chart) {
	    var radius = 6;
	    jQuery('#q1Y').remove();
	    jQuery('#q3Y').remove();
	    jQuery('#medianY').remove();
	    jQuery('#ybox').remove();
	    var px1 = Math.round(chart.xAxis[0].toPixels(chart.xAxis[0].min)-radius);
	    var py1 = Math.round(chart.yAxis[0].toPixels(q1)-radius/2);
	    var px2 = Math.round(chart.xAxis[0].toPixels(chart.xAxis[0].min)-radius);
	    var py2 = Math.round(chart.yAxis[0].toPixels(q3)-radius/2);
	    var px3 = Math.round(chart.xAxis[0].toPixels(chart.xAxis[0].min)-radius);
	    var py3 = Math.round(chart.yAxis[0].toPixels(me)-radius/2);
	    jQuery('.highcharts-container').append('<div id="q1Y" style="border-radius: '+radius+
						   'px; width: '+radius+
						   'px; height: '+radius+
						   'px; border: 3px solid rgba(250,150,100,0.9); background-color: rgba(250,100,100,0.2); position: absolute; left: '+
						   px1  +'px; top: '+
						   py1 +
						   'px;z-index: 99;" title="1st quantile: '+q1+'"></div>');
	    jQuery('.highcharts-container').append('<div id="q3Y" style="border-radius: '+
						   radius+'px; width: '+
						   radius+'px; height: '+
						   radius+'px; border: 3px solid rgba(250,150,100,0.9); background-color: rgba(250,100,100,0.2); position: absolute; left: '+
						   px2  +'px; top: '+
						   py2 +
						   'px;z-index: 99;" title="3rd quantile: '+q3+'"></div>');
	    jQuery('.highcharts-container').append('<div id="medianY" style="border-radius: '+
						   radius+'px; width: '+
						   radius+'px; height: '+
						   radius+'px; border: 3px solid rgba(250,150,100,0.9); background-color: rgba(250,100,100,0.2); position: absolute; left: '+
						   px3  +'px; top: '+
						   py3 +
						   'px;z-index: 99;" title="median: '+me+'"></div>');
	    jQuery('.highcharts-container').append('<div id="ybox" style="background-color: rgba(220,220,220,.6); width:'+
						   (chart.xAxis[0].toPixels(chart.xAxis[0].max)-chart.xAxis[0].toPixels(chart.xAxis[0].min))+'px; height: '+
						   (py1 - py2 + 2)+
 		                                   'px; left: ' + 
		                                   (chart.xAxis[0].toPixels(chart.xAxis[0].min)+radius/2-4) + 'px; top: ' + 
		                                   (py2+radius/2+1) + 'px; ' + 
		                                   'position: absolute; z-index: -1;"></div>');
	}
    }
}

// statistics are added to stats array
function readStats() {
  jQuery.getJSON('/applications/DataExploration/user_code/usercache_' + project_name + '_' + user_name + version + '_summary.json', function(data) {
    stats = data;
  });
}

// value found
function parseOneStat(s, d) {

  i = -1;
  // find the entry in the list of known keys
  for (var ii = 0; ii < columnNames.length; ii++) {
    if (columnNames[ii] == s)
      i = ii;
  }
  if (i == -1) {
    console.log('error: key not found');
    return 0;
  }

  data2 = stats;

  var isLogical = jQuery.trim(data2[7 * i + 0].split(":")[1]) == "logical";
  if (isLogical) {
    jQuery('#' + d).append("logical variable</br>" + data2[7 * i + 1]);
    return 1;
  }
  var mi = parseFloat(jQuery.trim(data2[7 * i + 0].split(":")[1]));
  var q1 = parseFloat(jQuery.trim(data2[7 * i + 1].split(":")[1]));
  var me = parseFloat(jQuery.trim(data2[7 * i + 2].split(":")[1])); // median
  var mean = parseFloat(jQuery.trim(data2[7 * i + 3].split(":")[1])); // mean
  var q3 = parseFloat(jQuery.trim(data2[7 * i + 4].split(":")[1]));
  var ma = parseFloat(jQuery.trim(data2[7 * i + 5].split(":")[1]));

  jQuery('#' + d).attr('mi', mi);
  jQuery('#' + d).attr('q1', q1);
  jQuery('#' + d).attr('me', me);
  jQuery('#' + d).attr('q3', q3);
  jQuery('#' + d).attr('ma', ma);

  var yesno = jQuery.trim(data2[7 * i + 0].split(":")[0]);
  if (yesno === "YES" || yesno === "No") {
    jQuery('#' + d).append(data2[7 * i + 0] + "</br>" + data2[7 * i + 1] + "</br>" + data2[7 * i + 2]);
    return 1;
  }
  if (jQuery.trim(data2[7 * i + 6].split(":")[0]) === "NA's") {
    /*jQuery('#'+d).append("<div style='float: right; font-size:0.8em;'>" 
         + data2[7*i+0] + "</br>" 
         + data2[7*i+1] + "</br>" + data2[7*i+2] + "</br>" 
         + data2[7*i+3] + "</br>" + data2[7*i+4] + "</br>" 
         + data2[7*i+5] + "</br>" + data2[7*i+6] + "</div>" ); */
    jQuery('#' + d).append("<div style='position: absolute; top: 138px; font-size:0.8em;'>" + data2[7 * i + 6] + "</div>");
  }
  if (jQuery.trim(data2[7 * i + 0].split(":")[0]) === "Min.") {
    setBPValues(mi, q1, me, q3, ma, [], [], mi, ma);
    createBoxPlot([], 120, d);
  } else {
    jQuery('#' + d).append("<div style='float: right; font-size:0.8em;'>" + data2[7 * i + 0] + "</br>" + data2[7 * i + 1] + "</br>" + data2[7 * i + 2] + "</br>" + data2[7 * i + 3] + "</br>" + data2[7 * i + 4] + "</br>" + data2[7 * i + 5] + "</br>" + data2[7 * i + 6] + "</div>");
  }
  return 1;
}