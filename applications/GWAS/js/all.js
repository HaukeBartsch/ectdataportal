var functionOf = "";
var yvalue = "";
var analysis_names = [];
var version = "";
var masterlist = [];
var snps = [];

function setDefaultValues() {
  if (functionOf != "")
    jQuery('#covariates').val(decodeURIComponent(functionOf));
  if (yvalue != "")
    jQuery('#yvalue').val(yvalue);
}

function loadAnalysisNames() {
  analysis_names = [];
  dataMRIRead = false; // we have to read them and afterwards add the entries to the ontology field
  dataBehaviorRead = false;

  var inputData = "../../data/" + project_name + "/data_uncorrected" + version + "/" + project_name + "_MRI_DTI_Complete.csv";
  jQuery.get(inputData, {
    cache: true
  }, function(tsv) {
    var lines = [],
      listen = false;

    try {
      // split the data return into lines and parse them
      tsv = tsv.split(/\r?\n/);
      jQuery.each(tsv, function(i, line) {
        if (line == '' || line.charAt(0) == '#') {
          listen = false;
        }
        // extract the header line from the first comment line
        if (listen == false) {
          listen = true; // do this only once...
          line = line.split(/,/);
          //line = CSVToArray( line );
          //line = line[0];
          for (var i = 0; i < line.length; i++) {
            var name = line[i];
            name = name.replace(/\"/g, '');
            name = name.replace(/-/g, '.');
            analysis_names.push(name);
          }

          dataMRIRead = true;
          if (dataMRIRead && dataBehaviorRead)
            addToOntology(analysis_names);

        }
        return false; // leave the each loop early, we only need the first line
      });
    } catch (e) {
      alert(e.message)
    }
  });


  var inputData = "../../data/" + project_name + "/data_uncorrected" + version + "/" + project_name + "_Behavior.csv";
  jQuery.get(inputData, {
    cache: true
  }, function(tsv) {
    var lines = [],
      listen = false;

    try {
      // split the data return into lines and parse them
      tsv = tsv.split(/\r?\n/);
      jQuery.each(tsv, function(i, line) {
        if (line == '' || line.charAt(0) == '#' || analysis_names.length == 0) {
          listen = false;
        }
        // extract the header line from the first comment line
        if (listen == false) {
          listen = true; // don't come in here again, only the first line can be the heaer line
          line = line.split(/,/);
          //line = CSVToArray( line );
          //line = line[0];

          for (var i = 0; i < line.length; i++) {
            var name = line[i];
            name = name.replace(/\"/g, '');
            name = name.replace('-', '.');
            analysis_names.push(name);
          }
          dataBehaviorRead = true;
          if (dataMRIRead && dataBehaviorRead)
            addToOntology(analysis_names);
        }
        return false; // leave the each loop early, we only need the first line
      });
    } catch (e) {
      alert(e.message)
    }
  }).error(function() {
    // we could not read the behavior file, but nevertheless we need to add not to ontology
    dataBehaviorRead = true; // this is not correct, but it should work
    if (dataMRIRead)
      addToOntology(analysis_names);
  });
}

function split(val) {
  return val.split(/\+\s*/);
}

function extractLast(term) {
  return split(term).pop();
}

function addToOntology(analysis_names) {
  jQuery('#covariates').autocomplete({
    source: analysis_names,
    select: function() {
      // yvalueChanged();
    }
  });

  jQuery('#yvalue').autocomplete({
    source: analysis_names,
    select: function() {
      // yvalueChanged();
    }
  });
}

var chromLocations = [0];
jQuery(document).ready(function() {

  jQuery.get('/data/' + project_name + '/data_uncorrected' + version + '/SNPs/' + project_name + '_SNPs.txt', function(data) {
    data = data.split(/\n/);
    //jQuery('#num-total').text(formatNumber(data.length));
    var chrom = "0";
    var loc = 0;
    var a = [];
    data.forEach(function(d, i) {
      if (i == 0)
        return;
      var b = d.split(",");
      if (b.length != 5) {
        console.log('wrong entry for: ' + b.join(" "));
        return;
      }
      a = b;
      snps.push(a);

      var currentChrom = a[1];
      if (chrom != currentChrom) {
        chrom = currentChrom;
        loc = parseInt(a[2]);
        chromLocations.push(chromLocations[chromLocations.length - 1] + parseInt(snps[snps.length - 2][2])); // last basepair location is last snp in chromosome before the new one
      }
    });
    //a = data[data.length-1].split(",");
    chromLocations.push(chromLocations[chromLocations.length - 1] + parseInt(a[2]));
    masterlist = snps;
  }).fail(function() {
    alert('Error: The SNP data for this project could not be found.');
  });


  jQuery.getJSON('/code/php/getProjectInfo.php', function(data) {
    // we need to identify the current project
    for (var i = 0; i < data.length; i++) {
      if (data[i]['name'] == project_name) {
        if (functionOf == "" && typeof data[i]['applications'] != 'undefined' &&
          typeof data[i]['applications']['DataExploration'] != 'undefined' &&
          typeof data[i]['applications']['DataExploration']['functionOf'] != 'undefined') {
          functionOf = data[i]['applications']['DataExploration']['functionOf'];
          var tmp = functionOf.split('(');
          if (tmp.length > 1) {
            functionOf = tmp[1].split(')')[0];
          }
        }
        if (yvalue == "" && typeof data[i]['applications'] != 'undefined' &&
          typeof data[i]['applications']['DataExploration'] != 'undefined' &&
          typeof data[i]['applications']['DataExploration']['yvalue'] != 'undefined') {
          yvalue = data[i]['applications']['DataExploration']['yvalue'];
        }
        break;
      }
    }
    // we waited for the project info but now we can set the values in the interface
    setDefaultValues();

    jQuery('#runModel').click(function() {
      jQuery.getJSON('startRun.php?project_name=' + project_name + '&com=' + jQuery('#yvalue').val() + '&covariates=' + jQuery('#covariates').val(), function(data) {
        // started project data.id
        getListOfRuns();
      });
    });
  });

  // allow the user to search for measures
  loadAnalysisNames();

  getListOfRuns();
});

function getListOfRuns() {
  jQuery.getJSON('getListOfRuns.php?project_name=' + project_name, function(data) {
    if (typeof data.message != undefined) {
      jQuery('#runs').children().remove(); // TODO: later make sure that we update only
      jQuery('#info_runs').html('<small style="margin-left: 10px;">' + data.runs.length + ' runs available</small>')
      for (var i = 0; i < data.runs.length; i++) {
        var d = new Date();
        d.setTime(data.runs[i].id * 1000);
        jQuery('#runs').append('<div id="' + data.runs[i].id + '" class="erg"><span class="label label-info">' + d.toString() + '</span>&nbsp;<span class="label label-default">SNP ~ ' + data.runs[i]['yvalue'] + ' + ' + data.runs[i]['covariates'] + '</span>&nbsp;<span class="label label-info">' + data.runs[i].id + '</span></div>');
        modelSpecification( data.runs[i].id );
        downloadRunResult(data.runs[i].id);
      }
    }
  });
}

function modelSpecification(id) {
    // how many levels for the covariates
    // number of variables for which all data points are awailable
    jQuery('#'+id).append('<div class="info" id="'+id+'N"></div>');
    jQuery.getJSON('data/'+id+'_stat.json', function(data) {
       jQuery('#'+id+'N').text("N: " + data.numSubjects + ", List of covariates: " + data.covars);
    });
}

function downloadRunResult(id) {
  jQuery.get('downloadRunResult.php?project_name=' + project_name + '&id=' + id, function(data) {
    var ps = data.split('\n');
    // now we have in column 1 the snp name, in column 3 we have the p-value
    // we should compute the SNP location along x
    if (data.length > 0) {
      jQuery('#' + id).append('<div class="manhatten" id="chart_' + id + '"></div>');
      drawPlot(id, data);
    }
  });
}


function drawPlot(id, data) {
  // add a highchart to this id
  // create x/y array
  var vals = []; // array of points
  var dataLabel = {
    dataLabels: {
      enabled: true,
      x: 35,
      formatter: function() {
        return this.point.name;
      },
      style: {
        color: "white"
      }
    },
    data: [],
    showInLegend: 0
  };
  var info = [];

  var d = data.split("\n");
  var minP;
  for (var i = 0; i < d.length; i++) {
    var line = d[i].split("\t");
    var s = line[0];
    var beta = parseFloat(line[1]);
    var pval = parseFloat(line[2]);
    if (i == 0) {
      minP = pval;
    } else {
      if (minP > pval)
        minP = pval;
    }
    var loc = 0; // genome wide location of this snp

    // this snp belongs to what chrom?
    for (var j = 0; j < snps.length; j++) {
      if (snps[j][0] == s) { // found our chrom
        ch = parseInt(snps[j][1]);
        loc = chromLocations[ch] + parseInt(snps[j][2]);
        info.push(snps[j]);
        break;
      }
    }
    vals.push([loc /*/chromLocations[chromLocation.length-1]*/ , -(Math.log(pval) / Math.log(10))]);
    if (i < 3)
      dataLabel.data.push({
        "x": loc,
        "y": -(Math.log(pval) / Math.log(10)),
        "z": 100,
        "name": snps[j][0]
      });
  }
  // now draw the vals array into #id
  var markerSize = 4;
  var siteConf = [];

  siteConf.push({
    name: 'genetics',
    id: 'genetics',
    marker: {
      radius: markerSize,
      lineColor: 'rgba(10,10,100,1)',
      lineWidth: 1,
      fillColor: 'rgba(251,180,0,.6)'
    },
    shadow: {
      color: '#FF0000',
      offsetX: 0,
      offsetY: 0,
      width: 20
    },
    showInLegend: 0,
    zIndex: 1
  });
  // lets add two lines, one for minimum displayed and one for required (red) at -log10(5e-8)
  siteConf.push({
    type: 'line',
    name: 'floor',
    id: 'floor',
    lineColor: 'rgba(100,100,100,.3)',
    lineWidth: .5,
    marker: {
      enabled: false
    },
    showInLegend: 0,
    zIndex: -1,
    enableMouseTracking: true
  });
  siteConf.push({
    type: 'line',
    name: 'required',
    id: 'required',
    lineColor: 'rgba(255,10,10,1)',
    lineWidth: 0.5,
    marker: {
      enabled: false
    },
    showInLegend: 0,
    zIndex: -1,
    enableMouseTracking: true
  });

  var options = {
    chart: {
      renderTo: 'chart_' + id,
      defaultSeriesType: 'scatter',
      zoomType: 'x',
      backgroundColor: 'rgba(0,0,0,0)'
    },
    title: {
      text: ''
    },
    subtitle: {
      text: ''
    },
    xAxis: {
      title: {
        enabled: true,
        text: 'chromosom'
      },
      tickWidth: 1,
      gridLineWidth: 0,
      labels: {
        align: 'left',
        x: 3,
        y: 14
      }
    },
    yAxis: [{
      title: {
        text: '-log<sub>10</sub> P',
        useHTML: true
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
        if (this.series.name == 'required') {
          return 'By convention significance is measures as being above this line at -log<sub>10</sub> 5e-8.';
        }
        if (this.series.name == 'floor') {
          return 'Only SNPs with a value above -log<sub>10</sub> ' + minP + ' are displayed.';
        }

        var index = 0
        for (u = 0; u < series.data.length; u++) {
          if (series.data[u].x == this.x && series.data[u].y == this.y) {
            index = u;
            break;
          }
        }

        return '' + info[index][0] + ' (chr.: ' + info[index][1] + ')<br/>-log<sub>10</sub>P = ' + this.y.toFixed(2);
      },
      useHTML: true
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
  // add the plot bands that signal the different chromosomes
  options.xAxis.plotBands = [];
  for (var i = 0; i < chromLocations.length - 1; i++) {
    options.xAxis.plotBands.push({
      from: chromLocations[i],
      to: chromLocations[i + 1],
      color: i % 2 == 0 ? 'rgba(255,127,80,.5)' : 'rgba(165,42,42,.5)',
      label: {
        text: (i == 0 || i == chromLocations.length - 2) ? '' : i,
        style: {
          color: '#606060'
        },
        verticalAlign: 'bottom',
        y: -14
      }
    });
  }

  //options.subtitle.text = 'N = ' + countTotal + ' subjects with both measures (Nx:'+countA+',Ny:'+countB+')';
  options.series[0].data = vals;
  options.series[0].data.dataLabels = dataLabel;
  var e = chromLocations[chromLocations.length - 1];
  options.series[1].data = [
    [0, vals[vals.length - 1][1]],
    [e, vals[vals.length - 1][1]]
  ];
  options.series[2].data = [
    [0, -Math.log(5e-8) / Math.log(10)],
    [e, -Math.log(5e-8) / Math.log(10)]
  ];
  options.series[3] = dataLabel;
  chart = new Highcharts.Chart(options);

}