<?php
  //
  // This service returns the children of a node.
  //
  // (Hauke, 12/2012)

  // Ontology/hierarchy.php?entry="root"

  date_default_timezone_set('America/Los_Angeles');

   session_start(); /// initialize session

   include("../../code/php/AC.php");
   $user_name = check_logged(); /// function checks if visitor is logged.

   if (isset($_SESSION['project_name']))
      $project_name = $_SESSION['project_name'];
   else
      $project_name = "Project01";

   //echo('<script type="text/javascript"> user_name = "'.$user_name.'"; project_name = "'.$project_name.'"; </script>');

  if (!empty($_GET['entry'])) {
    $entry = $_GET['entry'];
  } else {
    $entry = "";
  }
  if (!empty($_GET['_v'])) {
    $version = $_GET['_v'];
  } else {
    $version = "";
  }

function json_encode_string($in_str) {
  mb_internal_encoding("UTF-8"); // make this work by doing yum install php-mbstring
  $convmap = array(0x80, 0xFFFF, 0, 0xFFFF);
  $str = "";
  for($i=mb_strlen($in_str)-1; $i>=0; $i--)
    {
      $mb_char = mb_substr($in_str, $i, 1);
      if(mb_ereg("&#(\\d+);", mb_encode_numericentity($mb_char, $convmap, "UTF-8"), $match))
	{
	  $str = sprintf("\\u%04x", $match[1]) . $str;
	}
      else
	{
	  $str = $mb_char . $str;
	}
    }
  return $str;
}

function php_json_encode($arr) {
  $json_str = "";
  if(is_array($arr))
    {
      $pure_array = true;
      $array_length = count($arr);
      for($i=0;$i<$array_length;$i++)
	{
	  if(! isset($arr[$i]))
	    {
	      $pure_array = false;
	      break;
	    }
	}
      if($pure_array)
	{
	  $json_str ="[";
	  $temp = array();
	  for($i=0;$i<$array_length;$i++)       
	    {
	      $temp[] = sprintf("%s", php_json_encode($arr[$i]));
	    }
	  $json_str .= implode(",",$temp);
	  $json_str .="]";
	}
      else
	{
	  $json_str ="{";
	  $temp = array();
	  foreach($arr as $key => $value)
	    {
	      $temp[] = sprintf("\"%s\":%s", $key, php_json_encode($value));
	    }
	  $json_str .= implode(",",$temp);
	  $json_str .="}";
	}
    }
  else
    {
      if(is_string($arr))
	{
	  $json_str = "\"". json_encode_string($arr) . "\"";
	}
      else if(is_numeric($arr))
	{
	  $json_str = $arr;
	}
      else
	{
	  $json_str = "\"". json_encode_string($arr) . "\"";
	}
    }
  return $json_str;
}


  $dictionaries = array(
	0 => "../../data/".$project_name."/data_uncorrected".$version."/".$project_name."_datadictionary01.csv",
	1 => "../../data/".$project_name."/data_uncorrected".$version."/".$project_name."_datadictionary02.csv"
  ); 
  $rules_files = array(
	0 => "../../data/".$project_name."/data_uncorrected".$version."/".$project_name."_datadictionary_rules.csv"
  );

  $d = array();
  $row = 1;
  foreach (array_keys($dictionaries) as $u) {
    if (($handle = fopen($dictionaries[$u], "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
        $num = count($data);
        $row++;
        if ($num == 2) {  
  	       $d[$data[0]] = array( "0" => $data[1], "1" => "" ); // add short description
        } else {
           $d[$data[0]] = array( "0" => $data[1], "1" => $data[1] ); // add short description
        }
      }
      fclose($handle);
    }
  }

  $rules = array();
  $row = 1;
  foreach (array_keys($rules_files) as $u) {
    if (($handle = fopen($rules_files[$u], "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
        $num = count($data);
        $row++;
        if ($num >= 3) {
    	    $rules[$data[0]] = array( "0" => $data[1], "1" => $data[2] );
        }
      }
      fclose($handle);
    }
  }

  if ( $entry == "" ) {
    $ret = array();
    foreach (array_keys($d) as $u) {
      if ($d[$u][1] != "")
        $ret[$u] = htmlentities($d[$u][1]);
      else
        $ret[$u] = htmlentities($d[$u][0]);
    }

    echo(php_json_encode( $ret) ) ;
    return;
  } else if ( $entry != "display" && $entry != "" ) {
      // now look through each field in rules and d to see what entries fit, return them as list
    $result = array( "name" => $entry, "children" => array() );
    foreach ($d as $key => $value) {
     if (strlen($rules[$entry][0]) > 0 && preg_match($rules[$entry][0], $key)) {
       array_push($result['children'], array( "name"=> $key,
         "leaf" => "1", "key" => $key, "description" => $value[1] ) );
     }
   }
      foreach ($rules as $key => $value) { // try once to look into the rules as well
       if (strlen($rules[$entry][0]) > 0 && preg_match($rules[$entry][0], $key)) {
         array_push($result['children'], array( "name"=> $value[1],
           "leaf" => "0", "key" => $key, "description" => $value[1] ) );
       }
     }
     echo (php_json_encode( $result ));
     return false;
   } else if ( $entry == "display" ) {
    // write out the HTML portion of the page
    echo <<< EOT
<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Ontology</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>

    <script type="text/javascript" src="/js/d3/d3.v2.min.js"></script>
    <script type="text/javascript" src="/js/d3/d3.layout.js"></script>
    <script type="text/javascript" src="/js/togetherjs-min.js"></script>
    <script src="/js/ace/src-min-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/css/bootstrap-responsive.css" rel="stylesheet">
  </head>
  <style>
    body {
      overflow: scroll;
      margin: 0;
      font-size: 14px;
      font-family: "Helvetica Neue", Helvetica;
    }
#chart, #header, #footer {
  position: absolute;
  top: 0px;
}
#header, #footer {
 z-index: 1;
 display: block;
 font-size: 36px;
 font-weight: 300;
 text-shadow: 0 1px 0 #fff;
}
#header.inverted, #footer.inverted {
 color: #fff;
 text-shadow: 0 1px 4px #000;
}
#header {
 top: 80px;
 left: 140px;
 width: 900px;
}
#footer {
  margin-top: 60px;
  left: 10px;
  text-align: right;
}
rect {
 fill: none;
  pointer-events: all;
}
pre {
  font-size: 18px;
}
line {
 stroke: #000;
  stroke-width: 1.5px;
}
.string, .regexp {
 color: #f39;
}
.keyword {
 color: #00c;
}
.comment {
 color: #777;
  font-style: oblique;
}
.number {
 color: #369;
}
.class, .special {
 color: #1181B8;
}
a:link, a:visited {
 color: #000;
  text-decoration: none;
}
a:hover {
 color: #666;
}
.hint {
 position: absolute;
 right: 0;
 margin-top: 10px;
 width: 1280px;
 font-size: 12px;
 color: #999;
}

.link {
 float: right;
 margin: 10px;
 font-size: 12px;
 color: #999;
}
    .node circle {
      cursor: pointer;
      fill: #fff;
      stroke: steelblue;
      stroke-width: 1.5px;
    }
    .node text {
      font-size: 11px;
    }
    path.link {
      fill: none;
      stroke: #ccc;
      stroke-width: 1.5px;
    }

#body {
  margin-top: 50px;
}
    </style>
    <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Data Portal Ontology Viewer</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="/index.php">Home</a></li>
              <li><a target="data_dictionary" href="/applications/Ontology/translate.php?query=display" title="List the entries of the data dictionary as a continous table.">Data Dictionary</a></li>
	      <li><a href="#" id="open-edit-window" title="Show the rules used to define the hierarchy displayed. These rules are used to group entries from the data dictionary.">Edit</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
      <div id="body">
       <div id="footer">
        <span id="project_name">PING</span> Ontology
        <div class="hint">click or option-click to expand or collapse</div>
       </div>
       <!-- <div class="link btn"><a target="data_dictionary" href="/applications/Ontology/translate.php?query=display">data dictionary</a></div> -->
      </div>
    </div>
    <div id="edit-window" style="display: none;" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
             <h4 class="modal-title">Edit annotation rules</h4>
          </div>
          <div class="modal-body">
	    <div id="editor" style="height: 300px;">Trying to load data for this project...<br/></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="overwriteRules();">Save changes (overwrites old file)</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <script src="/js/bootstrap-transition.js"></script>
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
    <script src="/js/bootstrap-typeahead.js"></script>
    <script type="text/javascript">
      var editor = null;
      jQuery(document).ready(function() {
         // open modal if user wants to edit the model
	 jQuery("#open-edit-window").click(function() {
	    // fill in text for ace
            jQuery.ajax({
              url: '/data/'+"$project_name"+'/data_uncorrected/'+"$project_name"+'_datadictionary_rules.csv', 
	      data: {},
	      success: function(data) {
	        if (editor == null) {
                  editor = ace.edit("editor");
	        }
                //jQuery('#editor').html(data);
	        editor.setValue(data);
                editor.setTheme("ace/theme/monokai");
                editor.getSession().setMode("ace/mode/plain_text");
              },
	      error: function() {
	        if (editor == null) {
                  editor = ace.edit("editor");
	        }
	        editor.setValue('root,/(H_Suum|H_all)/,\"$project_name\"' +
                                'H_Suum,/(mean|total)/i,\"summary measures\"' +
                                'H_all,/.*/,\"all\"');
                editor.setTheme("ace/theme/monokai");
                editor.getSession().setMode("ace/mode/plain_text");                
              },
	      cache: false
            });

            jQuery('#edit-window').modal('show');
         });
      });

      function overwriteRules() {
         jQuery.get('saveRules.php', { "project": "$project_name", "text": editor.getValue() }, function(data) {
	   // ignore the output, its visible in firebug, that should be enough for debugging
         });
	 jQuery('#edit-window').modal('hide');
      }

    </script>

 <script type="text/javascript">
        // update the project name
	document.getElementById('project_name').innerHTML = "$project_name";
	var m = [20, 120, 20, 120],
	w = 1280 - m[1] - m[3],
	h = 1100 - m[0] - m[2],
	i = 0,
	root;
    var tree = d3.layout.tree()
      .size([h, w]);
    var diagonal = d3.svg.diagonal()
      .projection(function(d) { return [d.y, d.x]; });
    var vis = d3.select("#body").append("svg:svg")
      .attr("width", w + m[1] + m[3])
      .attr("height", h + m[0] + m[2])
      .append("svg:g")
      .attr("transform", "translate(" + m[3] + "," + m[0] + ")");
    d3.json("/applications/Ontology/hierarchy.php?_v=$version&entry=root", function(json) {
	root = json;
	root.x0 = h / 2;
	root.y0 = 0;
	function toggleAll(d) {
		if (d.children) {
		  d.children.forEach(toggleAll);
		  toggle(d);
		}
	}
	// Initialize the display to show a few nodes.
	//  root.forEach(toggleAll);
	//toggle(root.children[1]);
	//toggle(root.children[1].children[2]);
	//toggle(root.children[9]);
	//toggle(root.children[9].children[0]);
	update(root);
    });
    function update(source) {
      var duration = d3.event && d3.event.altKey ? 5000 : 500;
      // Compute the new tree layout.
      var nodes = tree.nodes(root).reverse();
      // Normalize for fixed-depth.
      nodes.forEach(function(d) { d.y = d.depth * 180; });
      // Update the nodesâ€¦
      var node = vis.selectAll("g.node")
	.data(nodes, function(d) { return d.id || (d.id = ++i); });
      // Enter any new nodes at the parent's previous position.
      var nodeEnter = node.enter().append("svg:g")
	.attr("class", "node")
	.attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
        .attr("title", function(d) { return d.description; })
	.on("click", function(d) { toggle(d); update(d); })
        .on("mouseover", function(d) { report(d); });
      nodeEnter.append("svg:circle")
	.attr("r", 1e-6)
	.style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });
      nodeEnter.append("svg:title")
        .text(function(d) { return d.description; });
      nodeEnter.append("svg:text")
	.attr("x", function(d) { return d.children || d._children ? -10 : 10; })
	.attr("dy", ".35em")
	.attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
	.text(function(d) { return d.name; })
	.style("fill-opacity", 1e-6);
      // Transition nodes to their new position.
      var nodeUpdate = node.transition()
	.duration(duration)
	.attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });
      nodeUpdate.select("circle")
	.attr("r", 4.5)
	.style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });
      nodeUpdate.select("text")
	.style("fill-opacity", 1);
      // Transition exiting nodes to the parent's new position.
      var nodeExit = node.exit().transition()
	.duration(duration)
	.attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
	.remove();
      nodeExit.select("circle")
	.attr("r", 1e-6);
      nodeExit.select("text")
	.style("fill-opacity", 1e-6);
      // Update the linksâ€¦
      var link = vis.selectAll("path.link")
	.data(tree.links(nodes), function(d) { return d.target.id; });
      // Enter any new links at the parent's previous position.
      link.enter().insert("svg:path", "g")
	.attr("class", "link")
	.attr("d", function(d) {
		var o = {x: source.x0, y: source.y0};
		return diagonal({source: o, target: o});
	      })
	.transition()
	.duration(duration)
	.attr("d", diagonal);
      // Transition links to their new position.
      link.transition()
	.duration(duration)
	.attr("d", diagonal);
      // Transition exiting nodes to the parent's new position.
      link.exit().transition()
	.duration(duration)
	.attr("d", function(d) {
		var o = {x: source.x, y: source.y};
		return diagonal({source: o, target: o});
	      })
	.remove();
      // Stash the old positions for transition.
      nodes.forEach(function(d) {
		      d.x0 = d.x;
		      d.y0 = d.y;
		    });
    }
    // Toggle children.
    function toggle(d) {
      if (d.children) { // hide them
	d._children = d.children;
	d.children = null;
      } else { // ask for children and display
	if (typeof d._children === "undefined") {
          d3.json("/applications/Ontology/hierarchy.php?_v=$version&entry="+d.key, function(json) {
	      d.children = json.children;
	      function toggleAll(d) {
		if (d.children) {
		  d.children.forEach(toggleAll);
		  toggle(d);
		}
	      }
	      // Initialize the display to show a few nodes.
	      //  root.forEach(toggleAll);
	      //toggle(root.children[1]);
	      //toggle(root.children[1].children[2]);
	      //toggle(root.children[9]);
	      //toggle(root.children[9].children[0]);
	      update(root);
	  });
	}
	d.children = d._children;
	d._children = null;
      }
    }
    function report(d) {
      // find out what this stands for
      if (d.leaf == 1) {
	// print out d.description
      }
    }
</script>
</body>
</html> 
EOT;
    
    return;
  } else {
    echo "error: Unknown query string. Only \"entry\" is supported currently.";
  }
?>
