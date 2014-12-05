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

<script type = "text/javascript">
if (version == "" && project_name == "PING") { // update this to have a default version of no argument ?_v= is provided in the URL
  version = "";
}

jQuery('.version').each(function() {
  jQuery(this).text('(version: ' + version + ')');
});

//make buttons 
jQuery.getJSON('/applications/Documents/getDocu.php?project_name=' + project_name + '&query=masterlist&_v=' + version, function(data) {
  jQuery('#list-of-most-important-files').children().remove();
  for (var entry = 0; entry < data.values.length; entry++) {
    if (data.values[entry]['valid'] == "0")
      continue;
    var fname = data.values[entry]['filename'];
    fname = fname.replace("$PROJECT_NAME", project_name);
    fname = fname.replace("$USER_NAME", user_name);
    fname = fname.replace("$VERSION", version);

    var str = "<li class='list-entry-li ui-button ui-widget ui-state-default ui-corner-all'><a href='downloadDoc.php?project_name=" + project_name + "&version=" + version + "&file=" + fname + "' target='" + fname + "'><div>";
    str = str + "  <div class='list-entry-li-flow'><h3 class='list-entry-name'>" + data.values[entry]['name'] + "</h3>";
    str = str + "  <div class='list-entry-description'>" + data.values[entry]['description'] + "</div>";
    str = str + "  <span class='list-entry-author-label'>author:</span>&nbsp;<span class='list-entry-author'>" + data.values[entry]['author'] + "</span></br>";
    str = str + "  <span class='list-entry-requested-by-label'>requested by:</span>&nbsp;<span class='list-entry-requested-by'>" + data.values[entry]['requested-by'] + "</span>";
    if (data.values[entry].keywords.length !== 0 && data.values[entry].keywords[0] != "default") {
      str = str + "  <div class='list-entry-keywords'>";
      for (var i = 0; i < data.values[entry].keywords.length; i++) {
        str = str + data.values[entry].keywords[i];
        if (i < data.values[entry].keywords.length - 1)
          str = str + ", ";
      }
      str = str + "<div>";
    }
    str = str + "</div></div>";
    str = str + "  <div class='list-entry-footer'><div class='list-entry-date'>" + data.values[entry]['date'] + "</div>";
    str = str + "  <div class='list-entry-version'>ver:" + data.values[entry]['version'] + "</div></div>";
    str = str + "</a></li>";
    jQuery('#list-of-most-important-files').append(str);
  }

});
// lets call and ask for some json code
jQuery.getJSON('/applications/Documents/getDocu.php?project_name=' + project_name + '&query=sections&_v=' + version, function(data) {
    //alert('hi, got some data.' + 'data is:' + data );
    var str = "<div id='accordion'>";
    for (var i = 0; i < data.length; i++) {
      var sanitizedName = data[i].replace(/\ /g, "-");
      str = str + '<h3 class="header-something" directory="' + data[i] + '">' + data[i] + '</h3><div id="accordion-container-' + sanitizedName + '"></div>';
    }
    str = str + '</div>';
    jQuery('#list-of-really-important-files').append(str);

    jQuery('#accordion').accordion({
      heightStyle: "fill",
      active: false,
      collapsible: true
    });

    // define a function that is called if the user clicks on one of the headers
    // find the headers first... 

    jQuery('.header-something').click(function() {
        // what are we looking for?
        var title = jQuery(this).attr('directory');
        if (title == "Data Use") {
          //alert('Please bear with us as this section is under development. We appreciate your patience.');
          // create table
          // now do the getJSON again and get the content of that directory, fill it into the div (instead of "nothing")
          // we would create a link for every entry, 
          jQuery.getJSON('/applications/Documents/getDocu.php?project_name=' + project_name + '&query=' + title + '&_v=' + version, function(data) {
              // should get information about this one study
              if (data.values.length > 0) {
                var sanitizedTitle = title.replace(/\ /g, "-");
                jQuery('#accordion-container-' + sanitizedTitle).children().remove();
                var str = "<div><table border='1' cellpadding='5'><th>TITLE</th><th>SITE</th><th>AUTHOR</th>";
                for (var entry = 0; entry < data.values.length; entry++) {
                  if (data.values[entry]['valid'] == "0")
                    continue;
                  str = str + "<tr><td><a href='/data/" + project_name + "/data_uncorrected" + version + "/documentation/" + title + "/" + data.values[entry]['filename'] + "'>";
		  str = str + "  " + data.values[entry]['name'] + "</a></td>"; str = str + "  <td>" + data.values[entry].keywords[0] + "</td> "; str = str + "  <td>" + data.values[entry]['author'] + "</td></tr>";
                }
                str = str + "</table></div>";
                jQuery('#accordion-container-' + sanitizedTitle).append(str);
              }
          });
        } else {
            // create buttons

            // now do the getJSON again and get the content of that directory, fill it into the div (instead of "nothing")
            // we would create a link for every entry, 
            jQuery.getJSON('/applications/Documents/getDocu.php?project_name=' + project_name + '&query=' + title + '&_v=' + version, function(data) {
              // should get information about this one study
              if (data.values.length > 0) {
                var sanitizedTitle = title.replace(/\ /g, "-");
                jQuery('#accordion-container-' + sanitizedTitle).children().remove();
                for (var entry = 0; entry < data.values.length; entry++) {
                  if (data.values[entry]['valid'] == "0")
                    continue;
                  var fname = data.values[entry]['filename'];
                  fname = fname.replace("$PROJECT_NAME", project_name);
                  fname = fname.replace("$USER_NAME", user_name);
                  fname = fname.replace("$VERSION", version);

                  var str = "<li class='list-entry-li ui-button ui-widget ui-corner-all'><div><a href='downloadDoc.php?project_name=" + project_name + "&version=" + version + "&file=" + fname + "' target='" + fname + "'>";

                  str = str + "  <div class='list-entry-li-flow'><h3 class='list-entry-name'>" + data.values[entry]['name'] + "</h3>";
                  if (data.values[entry]['description'] != "text")
                    str = str + "  <div class='list-entry-description'>" + data.values[entry]['description'] + "</div><br/>";
                  str = str + "  <span class='list-entry-author-label'>author:</span>&nbsp;<span class='list-entry-author'>" + data.values[entry]['author'] + "</span><br/>";
                  str = str + "  <span class='list-entry-requested-by-label'>requested by:</span>&nbsp;<span class='list-entry-requested-by'>" + data.values[entry]['requested-by'] + "</span>";
                  if (data.values[entry].keywords.length !== 0 && data.values[entry].keywords[0] != "default") {
                    str = str + "  <div class='list-entry-keywords'>";
                    for (var i = 0; i < data.values[entry].keywords.length; i++) {
                      str = str + data.values[entry].keywords[i];
                      if (i < data.values[entry].keywords.length - 1)
                        str = str + ", ";
                    }
                    str = str + "<div>";
                  }
                  str = str + "</div></a></div>";
                  str = str + "  <div class='list-entry-footer'><div class='list-entry-date'>" + data.values[entry]['date'] + "</div>";
                  str = str + "  <div class='list-entry-version'>" + data.values[entry]['version'] + "</div></div>";
                  str = str + "</li>";
                  jQuery('#accordion-container-' + sanitizedTitle).append(str);
                }
              }
            });
 	  }
       });
    }); 
</script>
