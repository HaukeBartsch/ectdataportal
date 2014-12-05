// show crossview's overview of area, thickness and volume measures over age

var sh;
var projects = [];

jQuery(document).ready(function() {
  if (typeof dL == 'undefined') {
    alert('error: system not setup correctly (no sets.json found). Contact your system administrator');
  }
  var firstone = true;
  for (var i = 0; i < dL.length; i++) {
    if (typeof dL[i]['projects'] != 'undefined') {
      if ( dL[i]['projects'].indexOf( project_name ) == -1 )
        continue; // don't do this one.. 
    }
    if (firstone) {
      firstone = false;
      currentSet = i;
    }
    str = "";
    if (currentSet == i)
      str = "active";
    jQuery('#sets').append('<li class="'+str+'"><a href="#" data-toggle=\"pill\" onclick="currentSet='+i+';display();">'+dL[i]['entry']+'</a></li>');	       
	       
    //jQuery('#first-section').html(dL[0]['entry']);
    //jQuery('#second-section').html(dL[1]['entry']);
  }

  jQuery.get('../../../data/'+project_name+"/data_uncorrected/usercache_"+project_name+"_"+user_name+".csv", function(data) {
    sh = data;
    display();
  });
  jQuery.getJSON('/code/php/getProjectInfo.php', function(data) {
    projects = data;

    jQuery('.current-project').text(project_name);
    var newProject = 0;
    for (var i = 0; i < projects.length; i++) {
      if (projects[i]['name'] == project_name)
	newProject = i;
    }
		   
    var str = projects[newProject].description;
    if (typeof projects[newProject].numCols != "undefined") {
       str += "<br>measures: "+projects[newProject].numCols;
       str += "<br>sessions: "+projects[newProject].numRows;
       var per = 100*(projects[newProject].numCols*projects[newProject].numRows-projects[newProject].numNAs)
				   /(projects[newProject].numCols*projects[newProject].numRows);
       str += "<br>populated at: "+roundNumber(per,2)+"&#37;";
       str += "<br>male: "+projects[newProject].male+", female: "+projects[newProject].female;
    }
    jQuery('.current-project-description').html(str); 
  });
});

// the next section needs to be included on each system (site dependent)
/*var formatNumber = d3.format(",d"),
    formatArea = d3.format(",f"),
    formatDate = d3.time.format("%B %d, %Y"),
    formatTime = d3.time.format("%I:%M %p"),
    formatAge = d3.format(".2f");

var currentSet = 0;
var dL = [ { 'entry': 'Imaging Measures',
       'projects': [ 'Project01' ],
	     'variables': [ 'Age_At_IMGExam', 'MRI_cort_area.ctx.total', 'MRI_cort_vol.ctx.total', 'MRI_cort_thick.ctx.mean'],
	     'names': ['Age', 'Area', 'Volume', 'Thickness'],
	     'display': [ 
	       function(d) { return formatAge(d.age); },
	       function(d) { return formatArea(Math.floor(0.01*d.area*100)/100) + " cm<sup>2</sup>"; },
	       function(d) { return Math.floor(0.001*d.vol*100)/100 + " cm<sup>3</sup>"; },
	       function(d) { return Math.floor(d.thick*100)/100 + " mm"; }
			]
	   },
           { 'entry': 'Cognition Measures',
       'projects': [ 'Project01' ],
	     'variables': [ 'Age_At_IMGExam', 'TBX_VOCAB_THETA', 'TBX_flanker_score', 'TBX_ibam_scr'],
	     'names': ['Age', 'Vocabulary Score', 'Flanker Score', 'IBAM score'],
	     'display': [ 
	       function(d) { return formatAge(d.age); },
	       function(d) { return d.area; },
	       function(d) { return d.vol; },
	       function(d) { return d.thick; }
			]
	   }	   
	 ]; */

var chart;
var list;
var charts;
var nestByAge;
var scan, all, age, ages, area, areas, vol, vols, thick, thicks;

function display() {
  version = "";
  jQuery('#title1-text').html(dL[currentSet]['names'][3]);
  jQuery.get('/applications/Ontology/translate.php?_v=' + 
	     version + '&column=' + 
	     dL[currentSet]['variables'][3] + '&query=long', function(data) {
     jQuery('#title1-text').attr('title', data);
  });
  jQuery('#title2-text').html(dL[currentSet]['names'][1]); 
  jQuery.get('/applications/Ontology/translate.php?_v=' + 
	     version + '&column=' + 
	     dL[currentSet]['variables'][1] + '&query=long', function(data) {
     jQuery('#title2-text').attr('title', data);
  });
  jQuery('#title3-text').html(dL[currentSet]['names'][2]); 
  jQuery.get('/applications/Ontology/translate.php?_v=' + 
	     version + '&column=' + 
	     dL[currentSet]['variables'][2] + '&query=long', function(data) {
     jQuery('#title3-text').attr('title', data);
  });
  jQuery('#title4-text').html(dL[currentSet]['names'][0]); 
  jQuery.get('/applications/Ontology/translate.php?_v=' + 
	     version + '&column=' + 
	     dL[currentSet]['variables'][0] + '&query=long', function(data) {
     jQuery('#title4-text').attr('title', data);
  });
  d3.selectAll("g").remove();
  d3.selectAll("svg").remove();
  barChart.id = 0;
  d3.selectAll(".age").remove();
  
  scan   = null;
  all = age = ages = area = areas = vol = vols = thick = thicks = null;
  
  //d3.selectAll(".axis").remove();
  //if ( typeof barChart == "function" ) {
  //  barChart = null;
  //}
  
  scans = sh.split("\n");
  scans2 = new Array();
  var isHeader = true;
  var headerLine = new Array();
  // we should add Site as a group variable as well
  // var interest = ['Age_At_IMGExam', 'MRI_cort_area.ctx.total', 'MRI_cort_vol.ctx.total', 'MRI_cort_thick.ctx.mean'];
  // var interest = dL[currentSet]['variables'];
  var ranges = [];
  var idxAge;
  var idxArea;
  var idxVol;
  var idxThick;
  scans.forEach(function(d,i) {
     vals = d.split(",");
     if (isHeader) {
       headerLine = vals;
       jQuery.each(headerLine,function(index, value) {
         headerLine[index] = value.replace(/\"/g,"");
         headerLine[index] = headerLine[index].trim();
         if (headerLine[index] == dL[currentSet]['variables'][0])
            idxAge = index;
         if (headerLine[index] == dL[currentSet]['variables'][1])
            idxArea = index;
         if (headerLine[index] == dL[currentSet]['variables'][2])
            idxVol = index;
         if (headerLine[index] == dL[currentSet]['variables'][3])
            idxThick = index;
       });     
       isHeader = false;
     } else {
       if (isFinite(vals[idxAge]) &&
	  isFinite(vals[idxArea]) &&
	  isFinite(vals[idxVol]) &&
	  isFinite(vals[idxThick])) 
         scans2.push(vals);
     }
  });

  if (scans2.length == 0) {
    alert('Error: Setup incomplete ('+dL[currentSet]['variables'][0]+'-'+idxAge+','+
	 +dL[currentSet]['variables'][1]+'-'+idxArea+','+
	 +dL[currentSet]['variables'][2]+'-'+idxVol+','+
	 +dL[currentSet]['variables'][3]+'-'+idxThick+')');    
  }
  
  // A nest operator, for grouping the session list.
  nestByAge = d3.nest()
      .key(function(d) { return Math.floor(d.age); });

  // A little coercion, since the CSV is untyped.
  scans2.forEach(function(d, i) {
    if (isHeader) {
      headerLine = d;
      jQuery.each(headerLine,function(index, value) {
        headerLine[index] = value.replace(/\"/g,"");
        headerLine[index] = headerLine[index].trim();
        if (headerLine[index] == dL[currentSet]['variables'][0])
           idxAge = index;
        if (headerLine[index] == dL[currentSet]['variables'][1])
           idxArea = index;
        if (headerLine[index] == dL[currentSet]['variables'][2])
           idxVol = index;
        if (headerLine[index] == dL[currentSet]['variables'][3])
           idxThick = index;
      });     
      isHeader = false;
    } else {
      var vals = d;
      d.index = i;
      d.age   = vals[idxAge]*1.0;
      d.area  = vals[idxArea]*1.0;
      d.vol   = vals[idxVol]*1.0;
      d.thick = vals[idxThick]*1.0;
      if (typeof ranges[dL[currentSet]['variables'][0]] == "undefined") {
         ranges[dL[currentSet]['variables'][0]] = [d.age, d.age];	    
         ranges[dL[currentSet]['variables'][1]] = [d.area, d.area];	    
         ranges[dL[currentSet]['variables'][2]] = [d.vol, d.vol];	    
         ranges[dL[currentSet]['variables'][3]] = [d.thick, d.thick];	    
      } else {
	 if (ranges[dL[currentSet]['variables'][0]][0] > d.age)
	   ranges[dL[currentSet]['variables'][0]][0] = d.age;
	 if (ranges[dL[currentSet]['variables'][0]][1] < d.age)
	   ranges[dL[currentSet]['variables'][0]][1] = d.age;

	 if (ranges[dL[currentSet]['variables'][1]][0] > d.area)
	   ranges[dL[currentSet]['variables'][1]][0] = d.area;
	 if (ranges[dL[currentSet]['variables'][1]][1] < d.area)
	   ranges[dL[currentSet]['variables'][1]][1] = d.area;

	 if (ranges[dL[currentSet]['variables'][2]][0] > d.vol)
	   ranges[dL[currentSet]['variables'][2]][0] = d.vol;
	 if (ranges[dL[currentSet]['variables'][2]][1] < d.vol)
	   ranges[dL[currentSet]['variables'][2]][1] = d.vol;

	 if (ranges[dL[currentSet]['variables'][3]][0] > d.thick)
	   ranges[dL[currentSet]['variables'][3]][0] = d.thick;
	 if (ranges[dL[currentSet]['variables'][3]][1] < d.thick)
	   ranges[dL[currentSet]['variables'][3]][1] = d.thick;
      }
    }
  });

  var sB = [
        40/(ranges[dL[currentSet]['variables'][0]][1]-ranges[dL[currentSet]['variables'][0]][0]),
        40/(ranges[dL[currentSet]['variables'][1]][1]-ranges[dL[currentSet]['variables'][1]][0]),
        40/(ranges[dL[currentSet]['variables'][2]][1]-ranges[dL[currentSet]['variables'][2]][0]),
        40/(ranges[dL[currentSet]['variables'][3]][1]-ranges[dL[currentSet]['variables'][3]][0])
  ];
  
  // Create the crossfilter for the relevant dimensions and groups.                                         
  scan = crossfilter(scans2);
  all = scan.groupAll();
  age = scan.dimension(function(d) { return d.age; });
  ages = age.group(function(d) { return Math.floor(d*sB[0])/sB[0]; });
  area = scan.dimension(function(d) { return d.area; });
  areas = area.group(function(d) { return Math.floor(d*sB[1])/sB[1]; });
  vol = scan.dimension(function(d) { return d.vol; });
  vols = vol.group(function(d) { return Math.floor(d*sB[2])/sB[2]; });
  thick = scan.dimension(function(d) { return d.thick; });
  thicks = thick.group(function(d) { return Math.floor(d*sB[3])/sB[3]; });

  charts = [
    barChart()
        .dimension(thick)
        .group(thicks)
        .x(d3.scale.linear()
        .domain([ ranges[dL[currentSet]['variables'][3]][0]-(1.0/sB[3]), 
		  ranges[dL[currentSet]['variables'][3]][1]+(1.0/sB[3]) ])
          .rangeRound([0, 420])),

    barChart()
        .dimension(area)
        .group(areas)
        .x(d3.scale.linear()
           .domain([  ranges[dL[currentSet]['variables'][1]][0]-(1.0/sB[1]), 
		      ranges[dL[currentSet]['variables'][1]][1]+(1.0/sB[1]) ]) //        110000, 230000])                                                   
           .rangeRound([0, 420])),
    
    barChart()
        .dimension(vol)
        .group(vols)
        .x(d3.scale.linear()
           .domain([ ranges[dL[currentSet]['variables'][2]][0]-(1.0/sB[2]), 
		     ranges[dL[currentSet]['variables'][2]][1]+(1.0/sB[2]) ]) // 390000, 710000])                                                           
           .rangeRound([0, 420])),

    barChart()
        .dimension(age)
        .group(ages)
        .x(d3.scale.linear()
           .domain([ ranges[dL[currentSet]['variables'][0]][0]-(1.0/sB[0]), 
		     ranges[dL[currentSet]['variables'][0]][1]+(1.0/sB[0]) ])// 2,22])                                                                      
           .rangeRound([0, 420]))

  ];

  // Given our array of charts, which we assume are in the same order as the
  // .chart elements in the DOM, bind the charts to the DOM and render them.
  // We also listen to the chart's brush events to update the display.
  chart = d3.selectAll(".chart")
      .data(charts)
      .each(function(chart) { chart.on("brush", renderAll).on("brushend", renderAll); });

  // Render the initial lists.
  list = d3.selectAll(".list")
      .data([scanList]);

  // Render the total.
  d3.selectAll("#total")
      .text(formatNumber(scan.size()));

  renderAll();
}

  // Renders the specified chart or list.
  function render(method) {
    d3.select(this).call(method);
  }
    
  // Whenever the brush moves, re-rendering everything.
  function renderAll() {
    chart.each(render);
    list.each(render);
    d3.select("#active").text(formatNumber(all.value()));
  }

  // Like d3.time.format, but faster.
  function parseDate(d) {
    return new Date(2001,
        d.substring(0, 2) - 1,
        d.substring(2, 4),
        d.substring(4, 6),
        d.substring(6, 8));
  }

  window.filter = function(filters) {
    filters.forEach(function(d, i) { charts[i].filter(d); });
    renderAll();
  };

  window.reset = function(i) {
    charts[i].filter(null);
    renderAll();
  };

  function scanList(div) {
    var scansByAge = nestByAge.entries(age.top(20));

    div.each(function() {
      var age = d3.select(this).selectAll(".age")
          .data(scansByAge, function(d) { return d.key; });

      age.enter().append("div")
          .attr("class", "age")
          .append("div")
          .attr("class", "age-value")
          .text(function(d) {
		  var a = Math.floor(d.values[0].age);
		  var endS = " years of age";
		  if (a == 1)
		    endS = " year of age";
		  return a+endS; 
	   });

      age.exit().remove();

      var scan = age.order().selectAll(".scan")
          .data(function(d) { return d.values; }, function(d) { return d.index; });

      var scanEnter = scan.enter().append("div")
          .attr("class", "scan");

      scanEnter.append("div")
          .attr("class", "age-value")
          .html(dL[currentSet]['display'][0]);  // function(d) { return formatAge(d.age); });

      scanEnter.append("div")
          .attr("class", "origin")
          .text(function(d) { return d[0].replace(/\"/g,""); });

      scanEnter.append("div")
          .attr("class", "thick")
          .html(dL[currentSet]['display'][3]); // function(d) { return formatArea(Math.floor(0.01*d.area*100)/100) + " cm<sup>2</sup>"; });

      scanEnter.append("div")
          .attr("class", "thick")
          .html(dL[currentSet]['display'][1]); // function(d) { return Math.floor(d.thick*100)/100 + " mm"; });

      scanEnter.append("div")
          .attr("class", "vol")
          .classed("small", function(d) { return d.vol < 500000; })
          .html(dL[currentSet]['display'][2]); // function(d) { return Math.floor(0.001*d.vol*100)/100 + " cm<sup>3</sup>"; });

      scan.exit().remove();

      scan.order();
    });
  }


  function barChart() {
    if (!barChart.id) barChart.id = 0;

    var margin = {top: 10, right: 10, bottom: 20, left: 10},
        x,
        y = d3.scale.linear().range([100, 0]),
        id = barChart.id++,
        axis = d3.svg.axis().orient("bottom"),
        brush = d3.svg.brush(),
        brushDirty,
        dimension,
        group,
        round;
    
    function chart(div) {
      var width  = 420, // x.range()[1],
          height = y.range()[0];

      var b = group.top(1)[0];
      if (typeof b != "undefined")
        y.domain([0, group.top(1)[0].value]);

      div.each(function() {
        var div = d3.select(this),
            g = div.select("g");

        // Create the skeletal chart.
        if (g.empty()) {
          div.select(".title").append("a")
              .attr("href", "javascript:reset(" + id + ")")
              .attr("class", "reset")
              .text("reset")
              .style("display", "none");

          g = div.append("svg")
              .attr("width", width + margin.left + margin.right)
              .attr("height", height + margin.top + margin.bottom)
            .append("g")
              .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

          g.append("clipPath")
              .attr("id", "clip-" + id)
            .append("rect")
              .attr("width", width)
              .attr("height", height);

          g.selectAll(".bar")
              .data(["background", "foreground"])
            .enter().append("path")
              .attr("class", function(d) { return d + " bar"; })
              .datum(group.all());

          g.selectAll(".foreground.bar")
              .attr("clip-path", "url(#clip-" + id + ")");

          g.append("g")
              .attr("class", "axis")
              .attr("transform", "translate(0," + height + ")")
              .call(axis);

          // Initialize the brush component with pretty resize handles.
          var gBrush = g.append("g").attr("class", "brush").call(brush);
          gBrush.selectAll("rect").attr("height", height);
          gBrush.selectAll(".resize").append("path").attr("d", resizePath);
        }
        // Only redraw the brush if set externally.
        if (brushDirty) {
          brushDirty = false;
          g.selectAll(".brush").call(brush);
          div.select(".title a").style("display", brush.empty() ? "none" : null);
          if (brush.empty()) {
            g.selectAll("#clip-" + id + " rect")
                .attr("x", 0)
                .attr("width", width);
          } else {
            var extent = brush.extent();
            g.selectAll("#clip-" + id + " rect")
                .attr("x", x(extent[0]))
                .attr("width", x(extent[1]) - x(extent[0]));
          }
        }

        g.selectAll(".bar").attr("d", barPath);
      });
      function barPath(groups) {
        var path = [],
            i = -1,
            n = groups.length,
            d;
        while (++i < n) {
          d = groups[i];
          path.push("M", x(d.key), ",", height, "V", y(d.value), "h9V", height);
        }
        return path.join("");
      }

      function resizePath(d) {
        var e = +(d == "e"),
            x = e ? 1 : -1,
            y = height / 3;
        return "M" + (.5 * x) + "," + y
            + "A6,6 0 0 " + e + " " + (6.5 * x) + "," + (y + 6)
            + "V" + (2 * y - 6)
            + "A6,6 0 0 " + e + " " + (.5 * x) + "," + (2 * y)
            + "Z"
            + "M" + (2.5 * x) + "," + (y + 8)
            + "V" + (2 * y - 8)
            + "M" + (4.5 * x) + "," + (y + 8)
            + "V" + (2 * y - 8);
      }
    }
    brush.on("brushstart.chart", function() {
      var div = d3.select(this.parentNode.parentNode.parentNode);
      div.select(".title a").style("display", null);
    });

    brush.on("brush.chart", function() {
      var g = d3.select(this.parentNode),
          extent = brush.extent();
      if (round) g.select(".brush")
          .call(brush.extent(extent = extent.map(round)))
        .selectAll(".resize")
          .style("display", null);
      g.select("#clip-" + id + " rect")
          .attr("x", x(extent[0]))
          .attr("width", x(extent[1]) - x(extent[0]));
      dimension.filterRange(extent);
    });

    brush.on("brushend.chart", function() {
      if (brush.empty()) {
        var div = d3.select(this.parentNode.parentNode.parentNode);
        div.select(".title a").style("display", "none");
        div.select("#clip-" + id + " rect").attr("x", null).attr("width", "100%");
        dimension.filterAll();
      }
    });


    chart.margin = function(_) {
      if (!arguments.length) return margin;
      margin = _;
      return chart;
    };

    chart.x = function(_) {
      if (!arguments.length) return x;
      x = _;
      axis.scale(x);
      brush.x(x);
      return chart;
    };

    chart.y = function(_) {
      if (!arguments.length) return y;
      y = _;
      return chart;
    };

    chart.dimension = function(_) {
      if (!arguments.length) return dimension;
      dimension = _;
      return chart;
    };

    chart.filter = function(_) {
      if (_) {
        brush.extent(_);
        dimension.filterRange(_);
      } else {
        brush.clear();
        dimension.filterAll();
      }
      brushDirty = true;
      return chart;
    };

    chart.group = function(_) {
      if (!arguments.length) return group;
      group = _;
      return chart;
    };

    chart.round = function(_) {
      if (!arguments.length) return round;
      round = _;
      return chart;
    };

  return d3.rebind(chart, brush, "on");
}
//}

function roundNumber(number, digits) {
   var multiple = Math.pow(10, digits);
   var rndedNum = Math.round(number * multiple) / multiple;
   return rndedNum;
}

//});