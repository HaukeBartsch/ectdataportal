jQuery('#help1').click(function() {
  jQuery('#yvalue').val("CortArea.Total");
  jQuery('#functionOf').val("s(Age)");
  jQuery('#command').val("");
  updateExpertField();
  jQuery('#submit').click();
});
jQuery('#help2').click(function() {
  jQuery('#yvalue').val("CortArea.Total");
  jQuery('#functionOf').val("s(Age)");
  jQuery('#command').val("Sex");
  updateExpertField();
  jQuery('#submit').click();
});
jQuery('#help3').click(function() {
  jQuery('#yvalue').val("CortArea.Total.lh");
  jQuery('#functionOf').val("s(TBX_flanker_score)");
  jQuery('#command').val("Sex+s(Age)");
  updateExpertField();
  jQuery('#submit').click();
});
jQuery('#help4').click(function() {
  jQuery('#yvalue').val("TBX_attention_score");
  jQuery('#functionOf').val("s(TBX_flanker_score)");
  jQuery('#command').val("Sex+s(Age)");
  jQuery('#covariates_scanner').addClass('active');
  jQuery('#covariates_SES').addClass('active');
  jQuery('#covariates_GAF').addClass('active');
  updateExpertField();
  jQuery('#submit').click();
});
jQuery('#help5').click(function() {
  jQuery('#yvalue').val("TBX_dccs_score");
  jQuery('#hidden_r').val("TBX_dccs_score_r");
  jQuery('#functionOf').val("s(Age)");
  jQuery('#command').val("");
  jQuery('#interaction').val("Sex");
  jQuery('#covariates_scanner').removeClass('active');
  jQuery('#covariates_SES').addClass('active');
  jQuery('#covariates_GAF').addClass('active');
  updateExpertField();
  jQuery('#submit').click();
});
jQuery('#help6').click(function() {
  jQuery('#yvalue').val("TBX_flanker_score");
  jQuery('#hidden_r').val("TBX_flanker_score_r");
  jQuery('#functionOf').val("s(Age)");
  jQuery('#command').val("");
  jQuery('#interaction').val("Sex");
  jQuery('#covariates_scanner').removeClass('active');
  jQuery('#covariates_SES').addClass('active');
  jQuery('#covariates_GAF').addClass('active');
  updateExpertField();
  jQuery('#submit').click();
});
jQuery('#help7').click(function() {
  jQuery('#yvalue').val("TBX_reading_score");
  jQuery('#hidden_r').val("TBX_reading_score_r");
  jQuery('#functionOf').val("s(Age)");
  jQuery('#command').val("");
  jQuery('#interaction').val("Sex");
  jQuery('#covariates_scanner').removeClass('active');
  jQuery('#covariates_SES').addClass('active');
  jQuery('#covariates_GAF').addClass('active');
  updateExpertField();
  jQuery('#submit').click();
});
jQuery('#help8').click(function() {
  jQuery('#yvalue').val("TBX_ibam_scr");
  jQuery('#hidden_r').val("TBX_ibam_score_r");
  jQuery('#functionOf').val("s(Age)");
  jQuery('#command').val("");
  jQuery('#interaction').val("Sex");
  jQuery('#covariates_scanner').removeClass('active');
  jQuery('#covariates_SES').addClass('active');
  jQuery('#covariates_GAF').addClass('active');
  updateExpertField();
  jQuery('#submit').click();
});
jQuery('#help9').click(function() {
  jQuery('#yvalue').val("TBX_VOCAB_THETA");
  jQuery('#hidden_r').val("TBX_vocab_base");
  jQuery('#functionOf').val("s(CortArea.Total)");
  jQuery('#command').val("Sex+Age:Sex+Age+I(Age^2)");
  jQuery('#interaction').val("");
  jQuery('#covariates_scanner').removeClass('active');
  jQuery('#covariates_SES').removeClass('active');
  jQuery('#covariates_GAF').removeClass('active');
  updateExpertField();
  jQuery('#submit').click();
});
jQuery('#help10').click(function() {
  jQuery('#yvalue').val("TBX_VOCAB_THETA");
  jQuery('#hidden_r').val("TBX_vocab_he");
  jQuery('#functionOf').val("s(CortArea.Total)");
  jQuery('#command').val("Sex+Age:Sex+Age+I(Age^2)");
  jQuery('#interaction').val("");
  jQuery('#covariates_scanner').removeClass('active');
  jQuery('#covariates_SES').addClass('active');
  jQuery('#covariates_GAF').removeClass('active');
  updateExpertField();
  jQuery('#submit').click();
});
jQuery('#help11').click(function() {
  jQuery('#yvalue').val("CortArea.Total");
  jQuery('#hidden_r').val("surfarea_base");
  jQuery('#functionOf').val("s(Age)");
  jQuery('#command').val("");
  jQuery('#interaction').val("Sex");
  jQuery('#covariates_scanner').removeClass('active');
  jQuery('#covariates_SES').removeClass('active');
  jQuery('#covariates_GAF').removeClass('active');
  updateExpertField();
  jQuery('#submit').click();
});
jQuery('#help12').click(function() {
  jQuery('#yvalue').val("CortArea.Total");
  jQuery('#hidden_r').val("surfarea_he");
  jQuery('#functionOf').val("s(Age)");
  jQuery('#command').val("");
  jQuery('#interaction').val("Sex");
  jQuery('#covariates_scanner').removeClass('active');
  jQuery('#covariates_SES').addClass('active');
  jQuery('#covariates_GAF').removeClass('active');
  updateExpertField();
  jQuery('#submit').click();
});
jQuery('#help13').click(function() {
  jQuery('#yvalue').val("TBX_VOCAB_THETA");
  jQuery('#hidden_r').val("surfarea_he2");
  jQuery('#functionOf').val("s(CortArea.Total)");
  jQuery('#command').val("Age:CortArea.Total + Age");
  jQuery('#interaction').val("Sex");
  jQuery('#covariates_scanner').removeClass('active');
  jQuery('#covariates_SES').addClass('active');
  jQuery('#covariates_GAF').removeClass('active');
  updateExpertField();
  jQuery('#submit').click();
});
jQuery('#help14').click(function() {
  jQuery('#yvalue').val("HLR");
  jQuery('#hidden_r').val("HippocampusAsym_r");
  jQuery('#functionOf').val("s(Age)");
  jQuery('#command').val("");
  jQuery('#interaction').val("Sex");
  jQuery('#covariates_scanner').addClass('active');
  jQuery('#covariates_SES').addClass('active');
  jQuery('#covariates_GAF').removeClass('active');
  updateExpertField();
  jQuery('#submit').click();
});
jQuery('#help15').click(function() {
  jQuery('#yvalue').val("CortArea.Total");
  jQuery('#hidden_r').val("genetics_r");
  jQuery('#functionOf').val("s(Age)");
  jQuery('#command').val("");
  jQuery('#interaction').val("rs6551665_Genotype");
  jQuery('#covariates_scanner').addClass('active');
  jQuery('#covariates_SES').addClass('active');
  jQuery('#covariates_GAF').removeClass('active');
  updateExpertField();
  jQuery('#submit').click();
});


// report back the index of the text a in the list of all available measures

function getColumnIndexPerText(a) {
  var b = a.toLowerCase();
  for (var i = 0; i < stat_header_line.length; i++)
    if (stat_header_line[i].toLowerCase() == b)
      return i;
  return -1;
}

var confidenceInterval = null;
var user_name = parent.user_name;
user_name = user_name.replace(/\./g, "_");
user_name = user_name.replace(/\ /g, "_");
var project_name = parent.project_name;
var column = 0;
var stat_header_line = [];
var Male = [];
var Female = [];
var dataSites = new Object(); // instead of Male and Female
var longSites = new Object(); // get all that are longitudinal
var current_curves = [];
var mapMaleToAllMale = [];
var mapFemaleToAllFemale = [];
var mapAllSites = new Object();
var AllSites = new Object();
var AllMale = [];
var AllFemale = [];
var analysis_names = []; // values used by the ontology field to help select entries
var curves_header = []; // keep the column names for all the produced curve columns
var markerSize = 5;

function updateExpertField() {
  var depmeasure = jQuery('#yvalue').val();
  depmeasure = depmeasure.replace(/-/g, '.');
  var covariates = jQuery('#command').val();
  covariates = covariates.replace(/-/g, '.');
  var indepmeasure = jQuery('#functionOf').val();
  indepmeasure = indepmeasure.replace(/-/g, '.');
  var interaction = jQuery('#interaction').val();
  interaction = interaction.replace(/-/g, '.');
  var dev = jQuery('#covariates_scanner').hasClass('active');
  var ses = jQuery('#covariates_SES').hasClass('active');
  var gaf = jQuery('#covariates_GAF').hasClass('active');
  var systemCovariates = "";
  var devString = "";
  var sesString = "";
  var gafString = "";
  if (dev) {
    if (systemCovariates.length > 0)
      systemCovariates = systemCovariates + " + ";
    systemCovariates = systemCovariates + "DeviceSerialNumber";
    devString = "DeviceSerialNumber";
  }
  if (gaf) {
    if (systemCovariates.length > 0)
      systemCovariates = systemCovariates + " + ";
    systemCovariates = systemCovariates + "GAF_africa + GAF_amerind + GAF_eastAsia + GAF_oceania + GAF_centralAsia";
    gafString = "GAF_africa + GAF_amerind + GAF_eastAsia + GAF_oceania + GAF_centralAsia";
  }
  if (ses) {
    if (systemCovariates.length > 0)
      systemCovariates = systemCovariates + " + ";
    systemCovariates = systemCovariates + "FDH_Highest_Education + FDH_3_Household_Income";
    sesString = "FDH_Highest_Education + FDH_3_Household_Income";
  }

  // # data$sex_num=as.numeric(data$Sex=="F")\n				\
  // # data$age_sex=as.numeric((data$Age-mean(data$Age,na.omit=TRUE))*data$sex_num)\n\
  t = '\
dependent.measure     = "dep_field"\n\
covariates.usr        = "cov_field"\n\
covariates.ses        = "cov_ses"\n\
covariates.dev        = "cov_dev"\n\
covariates.gaf        = "cov_gaf"\n\
independent.variable  = "ind_field"\n\
smoothing.interaction = "int_field"\n\
\n\
# data <- data[data$Age > 10,]\n\
# data <- droplevels(data)\n\
# data$HLR=as.numeric((data$SubCort.Left.Hippocampus-data$SubCort.Right.Hippocampus)/(data$SubCort.Left.Hippocampus+data$SubCort.Right.Hippocampus))\n';

  t = t.split("dep_field").join(depmeasure);
  t = t.split("cov_field").join(covariates);
  t = t.split("ind_field").join(indepmeasure);
  t = t.split("cov_system").join(systemCovariates);
  t = t.split("int_field").join(interaction);
  t = t.split("cov_ses").join(sesString);
  t = t.split("cov_dev").join(devString);
  t = t.split("cov_gaf").join(gafString);
  jQuery('#expert_text').val(t);

  var dependentMeasure = jQuery('#yvalue').val();
  var surfacestatsflag = false;
  if (dependentMeasure.substring(0, 8) == "CortArea" ||
    dependentMeasure.substring(0, 9) == "CortThick" ||
    dependentMeasure.substring(0, 3) == "T1w")
    surfacestatsflag = true;
  /*if (surfacestatsflag) {
    jQuery('#vertexStats').fadeIn('slow').show();
  } else {
    jQuery('#vertexStats').fadeOut('slow');
    } */
}

// check if the string contains a valid R syntax field
// for now just verify that all entries are present in the tables
// and that they are separated by '+'
// The function will return an array, first value will be
// true/false, second value with additional information.

function isValidEntry(string) {

  // return true if the string is empty (might not work for some measures)
  if (string.length == 0) {
    return [true, ""];
  }

  var found = false;
  var values = string.split('+');
  for (var i = 0; i < values.length; i++) {
    var parts = values[i].split(':');
    for (var j = 0; j < parts.length; j++) {
      var val = parts[j];
      val = val.replace(/\ /g, '');
      if (val.substr(0, 2) == "s(")
        val = val.substring(2, val.length - 1);
      if (val.substr(0, 2) == "I(")
        val = val.substring(2, val.length - 1);
      // add test for ^x
      var end = val.match(/\^\d+$/);
      if (end && end.length > 0) {
        val = val.substring(0, val.length - end.length - 1);
      }
      found = false;
      jQuery.each(analysis_names, function(index, value) {
        if (value == val) {
          found = true;
        }
      });
      if (found == false)
        return [found, val];
    }
  }
  return [found, val]; // yes,no found
}

function yvalueChanged() {
  setTimeout(function() {
    updateExpertField();
    var ret = isValidEntry(jQuery('#yvalue').val());
    if (ret[0] != true) {
      jQuery('#yvalue').css('background-color', '#FF9999');
      jQuery('#yvalue').effect("highlight", {}, 1000);
      jQuery('#yvalue').attr('title', 'Error: value "' + ret[1] + '" is unknown.');
    } else {
      jQuery('#yvalue').css('background-color', 'white');
      jQuery('#yvalue').attr('title', 'valid entry');
    }
  }, 500);
}

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function updateURLParameter(url, param, paramVal) {
  var TheAnchor = null;
  var newAdditionalURL = "";
  var tempArray = url.split("?");
  var baseURL = tempArray[0];
  var additionalURL = tempArray[1];
  var temp = "";

  if (additionalURL) {
    var tmpAnchor = additionalURL.split("#");
    var TheParams = tmpAnchor[0];
    TheAnchor = tmpAnchor[1];
    if (TheAnchor)
      additionalURL = TheParams;

    tempArray = additionalURL.split("&");

    for (i = 0; i < tempArray.length; i++) {
      if (tempArray[i].split('=')[0] != param) {
        newAdditionalURL += temp + tempArray[i];
        temp = "&";
      }
    }
  } else {
    var tmpAnchor = baseURL.split("#");
    var TheParams = tmpAnchor[0];
    TheAnchor = tmpAnchor[1];

    if (TheParams)
      baseURL = TheParams;
  }

  if (TheAnchor)
    paramVal += "#" + TheAnchor;

  var rows_txt = temp + "" + param + "=" + paramVal;
  return baseURL + "?" + newAdditionalURL + rows_txt;
}

function setDefaultValues() {

  // if we have some defaults from the URL use those
  if (command != "")
    jQuery('#command').val(decodeURIComponent(command));
  if (yvalue != "")
    jQuery('#yvalue').val(yvalue);
  if (functionOf != "")
    jQuery('#functionOf').val(functionOf);
  if (interaction != "")
    jQuery('#interaction').val(interaction);
  if (expert_mode_text != "") {
    jQuery('#expert_text').val(expert_mode_text);
    // we need to make the expert field active and disable the other fields = press the Expert Mode button
    setTimeout(function() {
      jQuery('#open_expert_mode').click();
    }, 500);
  } else {
    updateExpertField();
  }
  if (typeof interaction !== 'undefined')
    jQuery('#interaction').val(interaction);
  if (covDev != "") {
    if (covDev == "true")
      jQuery('#covariates_scanner').addClass('active');
    else
      jQuery('#covariates_scanner').removeClass('active');
  }
  if (covSES != "") {
    if (covSES == "true")
      jQuery('#covariates_SES').addClass('active');
    else
      jQuery('#covariates_SES').removeClass('active');
  }
  if (covGAF != "") {
    if (covGAF == "true")
      jQuery('#covariates_GAF').addClass('active');
    else
      jQuery('#covariates_GAF').removeClass('active');
  }

}
jQuery(document).ready(function() {

  // check if project_name is defined, if not produce error message
  console.log("project name is:" + project_name);

  //jQuery('#covariates_system').buttonset();

  // lets read the default values for the text fields and fill them in
  jQuery.getJSON('/code/php/getProjectInfo.php', function(data) {
    // we need to identify the current project
    for (var i = 0; i < data.length; i++) {
      if (data[i]['name'] == project_name) {
        if (command == "" && typeof data[i]['applications'] != 'undefined' && typeof data[i]['applications']['DataExploration'] != 'undefined' && typeof data[i]['applications']['DataExploration']['command'] != 'undefined') {
          command = data[i]['applications']['DataExploration']['command'];
        }
        if (yvalue == "" && typeof data[i]['applications'] != 'undefined' && typeof data[i]['applications']['DataExploration'] != 'undefined' && typeof data[i]['applications']['DataExploration']['yvalue'] != 'undefined') {
          yvalue = data[i]['applications']['DataExploration']['yvalue'];
        }
        if (functionOf == "" && typeof data[i]['applications'] != 'undefined' && typeof data[i]['applications']['DataExploration'] != 'undefined' && typeof data[i]['applications']['DataExploration']['functionOf'] != 'undefined') {
          functionOf = data[i]['applications']['DataExploration']['functionOf'];
        }
        if (interaction == "" && typeof data[i]['applications'] != 'undefined' && typeof data[i]['applications']['DataExploration'] != 'undefined' && typeof data[i]['applications']['DataExploration']['interaction'] != 'undefined') {
          interaction = data[i]['applications']['DataExploration']['interaction'];
        }
        if (expert_mode_text == "" && typeof data[i]['applications'] != 'undefined' && typeof data[i]['applications']['DataExploration'] != 'undefined' && typeof data[i]['applications']['DataExploration']['expert_text'] != 'undefined') {
          expert_mode_text = data[i]['applications']['DataExploration']['expert_text'];
        }
        if (covDev == "" && typeof data[i]['applications'] != 'undefined' && typeof data[i]['applications']['DataExploration'] != 'undefined' && typeof data[i]['applications']['DataExploration']['covDev'] != 'undefined') {
          covDev = data[i]['applications']['DataExploration']['covDev'];
        }
        if (covSES == "" && typeof data[i]['applications'] != 'undefined' && typeof data[i]['applications']['DataExploration'] != 'undefined' && typeof data[i]['applications']['DataExploration']['covSES'] != 'undefined') {
          covSES = data[i]['applications']['DataExploration']['covSES'];
        }
        if (covGAF == "" && typeof data[i]['applications'] != 'undefined' && typeof data[i]['applications']['DataExploration'] != 'undefined' && typeof data[i]['applications']['DataExploration']['covGAF'] != 'undefined') {
          covGAF = data[i]['applications']['DataExploration']['covGAF'];
        }
        break;
      }
    }
    // we waited for the project info but now we can set the values in the interface
    setDefaultValues();
  });
  jQuery('#surface-model').click(function() {
    window.open('/applications/DataExploration/img/3DLS8.pdf');
    return false;
  });

  jQuery('#word-cloud').click(function() {
    jQuery.ajax({
      dataType: "JSON",
      url: '/applications/Ontology/translate.php?_v=' + version,
      success: function(data) {
        var wc = new Object();
        jQuery.each(data, function(key, value) {
          var e = key.split(/[_\.-]+/);
          jQuery.each(e, function(key, value) {
            if (value == "lh" || value == "rh" || value == "DT" || value == "L" || value == "L" || value == "Left" || value == "Right" || value == "FDH" || value == "ctx" || value == "DT" || isNumber(value) || value == "of" || value == "By" || value == "or")
            ;
            else {
              if (wc[value]) {
                wc[value] = wc[value] + 1;
              } else {
                wc[value] = 1;
              }
            }
          });
        });
        jQuery('#word-cloud-content').children().remove();
        jQuery.each(wc, function(key, value) {
          jQuery('#word-cloud-content').append('<span data-weight="' + (Math.log(value) + 1) + '">' + key + '</span>');
        });
        jQuery('#word-cloud-content').awesomeCloud({
          "size": {
            "grid": 8,
            "normalize": false,
            "printMultiplier": 3,
            "sort": "random",
            "rotationRatio": 0.35,
            "color": "random-light"
          },
          "shape": "square",
          "font": "'Times New Roman', Times, serif"
        });
        jQuery('#word-cloud-content').dialog({
          width: 520,
          title: "Dictionary of Measurements",
          position: {
            my: 'top',
            at: 'top',
            of: jQuery('#expert_text')
          }
        });
      } //,
      //       error: function() { alert('could not get ontology data...'); }
    });
    return false;
  });

  /* jQuery('#Onto').tooltip({
    events: {
      input: 'mouseover,mouseout'
    },
    delay: 1000,
    bodyHandler: function() {
      var v = jQuery('#Onto').val();
      v = v.replace(/\./g, "-");
      jQuery.get('/applications/Ontology/translate.php?_v=' + version + '&column=' + v + '&query=long', function(data) {
        jQuery('#Onto').attr('title', data);
      });
      return '-';
    }
  }); */
  jQuery('#Onto').tooltip();
  jQuery('#Onto').on( 'show.bs.tooltip', function() {
    var v = jQuery('#Onto').val();
    if (v == "")
      return;
    v = v.replace(/\./g, "-");
    jQuery.get('/applications/Ontology/translate.php?_v=' + version + '&column=' + v + '&query=long', function(data) {
      jQuery('#Onto').attr( "title", data );
      jQuery('#Onto').css( 'display', '');
    });
    return true;
  });
  jQuery('#Onto').on( 'hidden.bs.tooltip', function() { jQuery('#Onto').css('display','');  });
			 
  jQuery("#accordion").accordion( { autoHeight: false } );
  //jQuery("#accordion").accordion("activate", 1);
  jQuery("#open_expert_mode").click(function() {
    if (jQuery('#expert').is(':hidden')) {
      jQuery('#expert-mode-button-text').html('Close Expert Mode');
      jQuery('#expert').slideDown('slow');
      jQuery('#yvalue').attr('disabled', 'disabled');
      jQuery('#functionOf').attr('disabled', 'disabled');
      jQuery('#command').attr('disabled', 'disabled');
      jQuery('#interaction').attr('disabled', 'disabled');
      jQuery('#covariates_scanner').button({
        disabled: true
      });
      jQuery('#covariates_SES').button({
        disabled: true
      });
      jQuery('#covariates_GAF').button({
        disabled: true
      });
    } else {
      jQuery('#expert-mode-button-text').html('Open Expert Mode');
      jQuery('#expert').slideUp('slow');
      jQuery('#yvalue').removeAttr('disabled');
      jQuery('#functionOf').removeAttr('disabled');
      jQuery('#command').removeAttr('disabled');
      jQuery('#interaction').removeAttr('disabled');
      jQuery('#covariates_scanner').button({
        disabled: false
      });
      jQuery('#covariates_SES').button({
        disabled: false
      });
      jQuery('#covariates_GAF').button({
        disabled: false
      });
    }
    return false;
  });

  jQuery('#yvalue').change(yvalueChanged);

  jQuery('#command').change(function() {
    setTimeout(function() {
      updateExpertField();
      var ret = isValidEntry(jQuery('#command').val());
      if (ret[0] != true) {
        jQuery('#command').css('background-color', '#FF9999');
        jQuery('#command').effect("highlight", {}, 3000);
        jQuery('#command').attr('title', 'Error: value "' + ret[1] + '" is unknown.');
      } else {
        jQuery('#command').css('background-color', 'white');
        jQuery('#command').attr('title', 'valid entry');
      }
    }, 500);
  });
  jQuery('#functionOf').change(function() {
    setTimeout(function() {
      updateExpertField();
      var ret = isValidEntry(jQuery('#functionOf').val());
      if (ret[0] != true) {
        jQuery('#functionOf').css('background-color', '#FF9999');
        jQuery('#functionOf').effect("highlight", {}, 3000);
        jQuery('#functionOf').attr('title', 'Error: value "' + ret[1] + '" is unknown.');
      } else {
        jQuery('#functionOf').css('background-color', 'white');
        jQuery('#functionOf').attr('title', 'valid entry');
      }
    }, 500);
  });
  jQuery('#interaction').change(function() {
    setTimeout(function() {
      updateExpertField();
      var ret = isValidEntry(jQuery('#interaction').val());
      if (ret[0] != true) {
        jQuery('#interaction').css('background-color', '#FF9999');
        jQuery('#interaction').effect("highlight", {}, 3000);
        jQuery('#interaction').attr('title', 'Error: value "' + ret[1] + '" is unknown.');
      } else {
        jQuery('#interaction').css('background-color', 'white');
        jQuery('#interaction').attr('title', 'valid entry');
      }
    }, 500);
  });
  jQuery('#covariates_scanner').change(function() {
    updateExpertField();
  });
  jQuery('#covariates_SES').change(function() {
    updateExpertField();
  });
  jQuery('#covariates_GAF').change(function() {
    updateExpertField();
  });


  loadAnalysisNames(); // fill in the array analysis_names which is used by the ontology field
  jQuery('#user_name_field').val(user_name);
  jQuery('#project_name_field').val(project_name);
  jQuery('#upload_project_name').val(project_name);

  jQuery('#executeR #submit').click(function() {
    if (project_name == "Data Exploration") {
      alert("Error detected. Re-select your project from the Projects menu to containue.");
    }

    // update the expert field again to make sure it uses the currently displayed settings
    // do this only if we don't have the expert mode on
    if (jQuery('#expert').is(':hidden')) {
      updateExpertField();
    }
    document.body.className = 'wait';
    jQuery('#ir_container').empty();
    jQuery('#StatSummary').fadeOut();
    jQuery('#sum').fadeOut();
    jQuery('#ir_container').addClass('wait');
    jQuery('#executeR #submit').attr("disabled", "disabled");
    jQuery('#summary').html('');
    // set the values from before to 0, read them in again now
    Male = [];
    Female = [];
    curves = [];
    var yvalue = jQuery('#yvalue').get();
    command = jQuery('#command').val();
    var functionOf = jQuery('#functionOf').get();
    var interaction = jQuery('#interaction').get();
    // always use only the expert text (codes for the training wheels part)
    var expert = jQuery('#expert_text').val();
    var txt = encodeURIComponent(expert);
    // we will use a random number and add it to the files produced by the system,
    // this way we keep a record of queries and we make sure that the files cannot be cached by the system
    var cookie = Math.round(Math.random() * 1000);
    var dataString = '_v=' + version + '&cookie=' + cookie + '&user_name=' + user_name + '&project_name=' + project_name + '&' + jQuery('#command').serialize() + '&' + jQuery('#yvalue').serialize() + '&' + jQuery('#functionOf').serialize() + '&' + jQuery('#interaction').serialize() + '&expert=' + txt;
    jQuery.ajax({
      type: "POST",
      url: "executeR.php",
      data: dataString,
      success: function(summary) {
        var rsq = "";
        var n = "";
        var dev = "";
        var aic = "";
        var pval = "";
        var full = summary.match(/<br>full\s+([^\s]+)\s+([^\s]+)\s+([^\s]+)\s+([^\s]+)\s+([^\s]+)\s+([^\s]+)\s+([^\s]+)<br>/);
        if (full != null && full.length != 6) {
          var rsq = full[4];
          var n = full[1];
          var dev = full[3];
          var aic = full[5];
          var pval = full[7];
        }
        var bla = jQuery('#hidden_r').val();
        jQuery('#hidden_r').val("");
        if (rsq != "") {
          jQuery('#' + bla).html('<span style="position: relative; float: right;">R<sup>2</sup>: ' + parseFloat(rsq).toFixed(4) + '</span>');
        }
        var error = summary.match(/(Error in[^<]+)/);
        if (error == null) {
          error = "unknown";
        }
        if (rsq != "" && aic != "") {
          jQuery('#sum').hide().html('<b>Figure 1</b>: The coefficient of determination R<sup>2</sup> (adjusted) is ' + rsq + ' (n = ' + n + '). The AIC score of the full model is ' + aic + '.').fadeIn(3000);
          jQuery('#StatSummary').hide().html('<div style="margin-left: 5px; margin-top: 5px; border-radius: 6px 6px 6px 6px; -webkit-border-radius: 6px; -moz-border-radius: 6px; margin-right: 5px;"><span title="larger is better"><a target="wikipedia" href="http://en.wikipedia.org/wiki/Coefficient_of_determination#Interpretation" style="color: white;text-decoration: none;">R<sup>2</sup> (adj.)</a>: ' + rsq + '</span><br><span title="smaller is better"><a target="wikipedia" href="http://en.wikipedia.org/wiki/Akaike_information_criterion" style="color: white; text-decoration: none">AIC:</a> ' + aic + '</span><br/><span>n: ' + n + '</span></div>').fadeIn(3000);
        } else {
          jQuery('#sum').html('Computation failed with "' + error[0] + '". See \"Statistical summary\" to find out more.');
        }
        jQuery('#sum').fadeIn("slow");
        jQuery('#summary').val(jQuery.trim(summary).replace(/\<br\>/g, "\n"));
        jQuery('#executeR #submit').removeAttr("disabled");
        reloadData(cookie);
        document.body.className = 'default';
        properEscaped = encodeURIComponent(command);
        properEscaped2 = encodeURIComponent(jQuery('#expert_text').val());

        // add .edu/permalink/PING/StatisticsGAM/...
        // NOTE: PROJECT missing from permalink path. Added it. tcooper 2012-03-22
        // NOTE: StatisticsGAM is NOT an authorized application. Replaced with StatisticalModeling.
        // NOTE: Also added sym link in /opt/dataportal for StatisticalModeling -> code/StatisticsGAM which
        //       exists on staging but not on production.
        // NOTE: BOTH of these should use app_defaults from the parent.
        // NOTE: mmil module permalink code does not seem to be able to deal with the 'command' code.
        /*parent.mmil_dataportal_updatePermalink('//mmil-dataportal.ucsd.edu/permalink/' + project_name + '/DataExploration/index.php?' + "command=" + properEscaped + "&" + jQuery('#yvalue').serialize() + "&" + jQuery('#functionOf').serialize() 
                   //+ "&expert_text=" + properEscaped2 + "&" 
                   + "&" + jQuery('#interaction').serialize()
                   + "&Dev=" + jQuery('#covariates_scanner').is(':checked')
                   + "&SES=" + jQuery('#covariates_SES').is(':checked')
                   + "&GAF=" + jQuery('#covariates_GAF').is(':checked')); */
        jQuery('#ir_container').removeClass('wait');

      },
      error: function(ob, errStr) {
        jQuery('#summary').html(errStr);
        jQuery('#executeR #submit').removeAttr("disabled");
        document.body.className = 'default';
        jQuery('#ir_container').removeClass('wait');
      }
    });
    document.body.style.cursor = "default";

    return false;
  });
  jQuery('#upload_extra_data').click(function() {
    upload_extra_data();
  });
  jQuery('#download_data_model').click(function() {
    download_data_model();
  });
  //jQuery('#sum').each(function() { PIE.attach(this); });
  //jQuery('.ui-button').each( function() { PIE.attach(this); });

  TogetherJS.reinitialize();
});

function computeGlobalVertexStats() {
  // add what type is requested
  var selection = jQuery('#vertex-model-selection').get(0).selectedIndex;

  var yvalue = jQuery('#yvalue').get();
  command = jQuery('#command').val();
  var functionOf = jQuery('#functionOf').get();
  var interaction = jQuery('#interaction').get();
  var expert = jQuery('#expert_text').val();
  var txt = encodeURIComponent(expert);
  // we will use a random number and add it to the files produced by the system,
  // this way we keep a record of queries and we make sure that the files cannot be cached by the system
  var cookie = Math.round(Math.random() * 1000);
  jQuery('#place-for-popups').html('Computing...<br/><div id="progressbar"></div><br>After computation this page will try to open a new window. Make sure this popup is not blocked.');
  jQuery('#place-for-popups-dialog').dialog({
    modal: true,
    position: {
      my: 'top',
      at: 'top',
      of: jQuery('#ir_container')
    }
  });
  jQuery('#progressbar').progressbar({
    value: 60
  });
  var dataString = '_v=' + version + '&cookie=' + cookie + '&user_name=' + user_name + '&project_name=' + project_name + '&' + jQuery('#command').serialize() + '&' + jQuery('#yvalue').serialize() + '&' + jQuery('#functionOf').serialize() + '&vertexwise=1' + '&request=' + selection + '&' + jQuery('#interaction').serialize() + '&expert=' + txt;
  jQuery.ajax({
    type: "POST",
    url: "executeR.php",
    data: dataString,
    success: function(summary) {
      // jQuery('#place-for-popups').html(summary);
      // we can have an error in the computation is summary starts with "Error"
      if (summary.indexOf("Error") == 0 || summary.indexOf("Error in") > 0) {
        jQuery('#place-for-popups').html('Error returned by computational backend:<br>' + summary);
      } else {
        jQuery('#place-for-popups-dialog').dialog('close');
        // this way we will get asked for popup permissions
        window.open('/applications/SurfaceViewer/?' +
          'cookie=' + cookie + '&request=' + selection + '&stats=' + encodeURIComponent(summary), 'SurfaceViewer', 'menubar=yes,toolbar=yes');
      }
    },
    error: function(summary) {
      //jQuery('#place-for-popups').html(summary);
      jQuery('#place-for-popups-dialog').dialog('close');
    }
  });
}

function computeVertexStats(patient, studyDate) {
  var yvalue = jQuery('#yvalue').get();
  command = jQuery('#command').val();
  var functionOf = jQuery('#functionOf').get();
  var interaction = jQuery('#interaction').get();
  var expert = jQuery('#expert_text').val();
  var txt = encodeURIComponent(expert);
  // we will use a random number and add it to the files produced by the system,
  // this way we keep a record of queries and we make sure that the files cannot be cached by the system
  var cookie = Math.round(Math.random() * 1000);
  jQuery('#place-for-popups').html('Computing...<br/><div id="progressbar"></div><br>After computation this page will try to open a new page.');
  jQuery('#place-for-popups-dialog').dialog({
    modal: true,
    position: {
      my: 'top',
      at: 'top',
      of: jQuery('#expert_text')
    },
    width: 500
  });
  jQuery('#progressbar').progressbar({
    value: 60
  });
  var dataString = 'cookie=' + cookie + '&user_name=' + user_name + '&project_name=' + project_name + '&' + jQuery('#command').serialize() + '&' + jQuery('#yvalue').serialize() + '&' + jQuery('#functionOf').serialize() + '&vertexwise=1' + '&' + jQuery('#interaction').serialize() + '&expert=' + txt;
  jQuery.ajax({
    type: "POST",
    url: "executeR.php",
    data: dataString,
    success: function(summary) {
      jQuery('#place-for-popups-dialog').dialog('close');
      //alert('error in executing vertex model');
      jQuery('#place-for-popups').html(summary);

      // this way we will get asked for popup permissions
      window.open('/applications/SurfaceViewer/?patient=' +
        patientID + '&visit=' + studyDate + '&cookie=' + cookie, 'SurfaceViewer', 'menubar=yes,toolbar=yes');
    },
    error: function(summary) {
      //alert('error in executing vertex model');
      //jQuery('#place-for-popups').html(summary);
      jQuery('#place-for-popups-dialog').dialog('close');
    }
  });
}

function download_data_model() {
  // create the r-code
  var yvalue = jQuery('#yvalue').get();
  command = jQuery('#command').val();
  var functionOf = jQuery('#functionOf').get();
  var interaction = jQuery('#interaction').get();
  // always use only the expert text (codes for the training wheels part)
  var expert = jQuery('#expert_text').val();
  var txt = encodeURIComponent(expert);
  // we will use a random number and add it to the files produced by the system,
  // this way we keep a record of queries and we make sure that the files cannot be cached by the system
  var cookie = Math.round(Math.random() * 1000);
  var dataString = 'cookie=' + cookie + '&user_name=' + user_name + '&project_name=' + project_name + '&' + jQuery('#command').serialize() + '&' + jQuery('#yvalue').serialize() + '&' + jQuery('#functionOf').serialize() + '&' + jQuery('#interaction').serialize() + '&expert=' + txt + '&returnFile=true';
  jQuery.ajax({
    type: "POST",
    url: "executeR.php",
    data: dataString,
    success: function(summary) {
      // using 'returnFile=true' we return the rcode instead of the result
      jQuery('#download-data-as-package').dialog({
        position: {
          my: 'top',
          at: 'top',
          of: jQuery('#ir_container')
        }
      });
      jQuery('#download-data-as-package-link').attr('href', summary);
      jQuery('#download-data-as-package-link').fadeIn('slow');
      //jQuery('#download-data-as-package').dialog('option', 'width', 600);
    },
    error: function(ob, errStr) {
      // using 'returnFile=true' we return the rcode instead of the result
      jQuery('#download-data-as-package-link').text(summary);
    }
  });
  // open dialog already
  jQuery('#download-data-as-package').dialog({
    position: {
      my: 'top',
      at: 'top',
      of: jQuery('#ir_container')
    }
  });
  jQuery('#download-data-as-package').dialog('option', 'width', 600);
  jQuery('#download-data-as-package-link').hide();

}

function show_extra_data() {
  jQuery.get('user_data/userdata_' + project_name + '_' + user_name + '.csv', {
    cache: false
  }, function(t) {
    jQuery('#text-area-user-data').val(t);
    jQuery('#show-user-data').dialog({
      position: {
        my: 'top',
        at: 'top',
        of: jQuery('#ir_container')
      }
    });
  }).error(function() {
    jQuery('#text-area-user-data').val("No extra user data exists. Upload a table with a valid \"SubjID\" column.");
    jQuery('#show-user-data').dialog({
      position: {
        my: 'top',
        at: 'top',
        of: jQuery('#ir_container')
      }
    });
    jQuery.getJSON('/code/php/getSubjectsTable.php?project=' + project_name, function(data) {
      if (typeof data != "undefined" && typeof data.text != "undefined")
        jQuery('#text-area-user-data').val(data.text);
    })
  });
}

function delete_extra_data() {
  data = {
    'project_name': project_name,
    'user_name': user_name
  };
  jQuery.post('delete_extra_data.php', data, function() {});
}

function upload_extra_data() {
  // create a dialog
  jQuery('#upload').dialog({
    position: {
      my: 'top',
      at: 'top',
      of: jQuery('#expert_text')
    },
    width: 500			
  });
  return false;
}

var dataMRIRead = false;
var dataBehavior = false;

// fill in the array analysis_names

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
  jQuery('#Onto').autocomplete({
    source: analysis_names
  });
  jQuery('#Onto').bind('keyup', function(event) {
      var searchText = jQuery(this).val();
      if (searchText.charAt(0) == '@') {
         search(searchText);
      }
  });
  jQuery('#command').bind("keydown", function(event) {
    if (event.keyCode == jQuery.ui.keyCode.TAB &&
      jQuery(this).data("autocomplete").menu.active) {
      event.preventDefault();
    }
  }).autocomplete({
    minLength: 0,
    source: function(request, response) {
      response(jQuery.ui.autocomplete.filter(analysis_names, extractLast(request.term)));
    },
    focus: function() {
      return false;
    },
    select: function(event, ui) {
      var terms = split(this.value);
      terms.pop();
      terms.push(ui.item.value);
      terms.push("");
      this.value = terms.join("+");
      return false;
    }
  });
  jQuery('#yvalue').autocomplete({
    source: analysis_names,
    select: function() {
      yvalueChanged();
    }
  });
  jQuery('#interaction').autocomplete({
    source: analysis_names
  });
  jQuery('#functionOf').autocomplete({
    source: analysis_names
  });

  // all the headers should be added to the search field
  var con = document.getElementById("ontology_container");
  if (con) {
    // create a list of all the headers
    var exclude_list = ['Site', 'Age', 'PING_ID', 'VisitID', 'VisitNumber', 'Gender', 'Age_In_Years_At_NPExam',
      'TBX_Toolbox_Note', 'TBX_IBAM_set', 'TBX_dccs_pre_cue', 'TBX_dccs_pst_cue', 'TBX_dccs_dom_cue',
      'Sex', 'StudyDate', 'SubjID', 'Manufacturer', 'ManufacturersModelName',
      'DeviceSerialNumber', 'ASEG_QC', 'Group', 'DTI_QC', 'Surface_QC'
    ];
    for (var pos = 0; pos < analysis_names.length; pos++) {
      var head = analysis_names[pos];
      var validEntry = true;
      for (var t = 0; t < exclude_list.length; t++) {
        if (exclude_list[t] == head)
          validEntry = false;
      }
      if (validEntry == false)
        continue;

      // do we have to create a sub-level?
      headSubLevelList = head.split(/-/);
      var headSubLevel = null;
      if (headSubLevelList.length > 0)
        headSubLevel = headSubLevelList[0];
      else
        headSubLevel = "";

      // the item that we want to add to the tree
      var listEntry = document.createElement("li");
      if (headSubLevel != "") {
        // does this sub heading already exist?
        var foundHeading = null;
        var groupFound = document.getElementById(headSubLevel);
        if (groupFound != null) {
          foundHeading = document.getElementById(headSubLevel);
          foundHeading.appendChild(listEntry);
        } else {
          // create new sub-menu for the unknown heading
          var group = document.createElement("ul");

          var list5 = document.createElement("li");
          var item = document.createTextNode("Section: " + headSubLevel);
          list5.appendChild(item);
          var attr = document.createAttribute('class');
          attr.nodeValue = 'gitem';
          group.setAttributeNode(attr);
          attr = document.createAttribute('id');
          attr.nodeValue = headSubLevel;
          group.setAttributeNode(attr);

          group.appendChild(list5);
          attr = document.createAttribute('style');
          attr.nodeValue = 'color: rgb(120, 120, 120);';
          group.setAttributeNode(attr);
          group.appendChild(listEntry);
          con.appendChild(group);
        }
      }

      // create the actual element
      attr = document.createAttribute('class');
      attr.nodeValue = 'titem';
      listEntry.setAttributeNode(attr);
      attr = document.createAttribute('style');
      attr.nodeValue = 'color: rgb(120, 120, 120);';
      listEntry.setAttributeNode(attr);
      var nobr = document.createElement('nobr');
      var div3 = document.createElement("div");
      attr = document.createAttribute('id');
      attr.nodeValue = pos;
      div3.setAttributeNode(attr);
      attr = document.createAttribute('class');
      attr.nodeValue = "tnc";
      div3.setAttributeNode(attr);
      var item = document.createTextNode(head);
      div3.appendChild(item);
      nobr.appendChild(div3);
      listEntry.appendChild(nobr);
    }
  }
  // the search init function needs to read the content of the ontology div field again
  //init();
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

var chart;

// set the Male, Female, curves variables with values from the resulting files

function reloadData(cookie) {
  // reset the values
  AllFemale = [];
  AllMale = [];
  AllSites = new Object(); // an associative array of site names with points as array values
  mapMaleToAllMale = [];
  mapFemaleToAllFemale = [];
  mapAllSites = new Object(); // an associative array of site names

  stat_header_line = [];

  // what is the last model of the current user?
  var rawDataCsv = "curves/" + user_name + "_" + project_name + "_curves/" + user_name + "_" + project_name + "_Corrected" + cookie + ".tsv";

  jQuery.ajaxSetup({
    cache: false // Disable caching of AJAX responses
  });

  // load the data
  jQuery.get(rawDataCsv, {
    cache: false
  }, function(tsv) {
    var lines = [],
      listen = false,
      date;

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
          //line = line.split(/,/);
          line = CSVToArray(line);
          line = line[0];
          stat_header_line = [];
          for (var i = 0; i < line.length; i++) {
            var name = line[i];
            name = name.replace('"', '');
            name = name.replace('"', '');
            stat_header_line.push(name);
          }
          // its not the last entry, find the real entry here
          // column = stat_header_line.length-1;

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
          manufacturer = getColumnIndexPerText("ManufacturersModelName");
          plotgroup = getColumnIndexPerText("PlotGroup");
          genderVar = jQuery('select#grouping_variable option:selected').val();
          columnToMap = getColumnIndexPerText(genderVar);
          if (columnToMap == -1 && genderVar == "Gender") {
            // could not find Gender, try to use Sex instead
            columnToMap = getColumnIndexPerText("Sex");
          }
        } else if (listen == true) { // header line
          // line = line.split(/,/);
          line = CSVToArray(line);
          line = line[0];

          var vals = [];
          for (var i = 0; i < line.length; i++) {
            var val = line[i];
            if (typeof val == "undefined") // a column can be missing
              continue;
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
              i != site &&
              i != manufacturer &&
              i != plotgroup &&
              i != columnToMap
            ) {
              vals.push(parseFloat(val));
            } else
              vals.push(val);
          }
          // map the sites instead of male/female
          if (!AllSites[vals[columnToMap]]) {
            // warning columnToMap is -1 if there is only a single function, which creates undefined here
            AllSites[vals[columnToMap]] = [];
          }
          AllSites[vals[columnToMap]].push(vals);

          if (!mapAllSites[vals[columnToMap]]) {
            mapAllSites[vals[columnToMap]] = [];
          }
          mapAllSites[vals[columnToMap]].push(AllSites[vals[columnToMap]].length - 1);

          // the one below we need only for male female distinction, not for sites
          if (vals[gender] == 'M') {
            mapMaleToAllMale.push(AllMale.length);
            AllMale.push(vals);
          } else if (vals[gender] == 'F') {
            mapFemaleToAllFemale.push(AllFemale.length);
            AllFemale.push(vals);
          } else {
            console.log('Warning: gender/sex column entry is neither M nor F... graph will be empty');
          }
        }
      });
    } catch (e) {
      alert(e.message)
    }

    mapAllSites = new Object();
    mapMaleToAllMale = [];
    mapFemaleToAllFemale = [];

    var siteIndex = getColumnIndexPerText("Site");
    var ageIndex = getColumnIndexPerText("Age");
    var subjIndex = getColumnIndexPerText("SubjID");

    // create a good version of the yvalue to lookup its column in the table
    functionOf = jQuery('#functionOf').val();
    // functionOf can be smooth, remove the s() part here
    if (functionOf.substr(0, 2) == "s(") {
      functionOf = functionOf.substring(2, functionOf.length - 1);
    }
    yvalue = jQuery('#yvalue').val();
    functionOfString = functionOf;
    functionOfString = functionOfString.replace(/-/g, '.');
    xlabelIndex = getColumnIndexPerText(functionOfString);
    // create a good version of the yvalue to lookup its column in the table
    yvalueString = yvalue;
    yvalueString = yvalueString.replace(/-/g, '.');
    ylabelIndex = getColumnIndexPerText(yvalueString);

    current_y_axis = ylabelIndex;
    dataSite = new Object();
    longSites = new Object();
    for (site in AllSites) {
      for (i = 0; i < AllSites[site].length; i++) {
        if (!isNaN(AllSites[site][i][ylabelIndex]) && !isNaN(AllSites[site][i][xlabelIndex])) {
          if (!dataSite[site])
            dataSite[site] = [];
          dataSite[site].push([AllSites[site][i][xlabelIndex], AllSites[site][i][ylabelIndex]]);
          if (!longSites[AllSites[site][i][subjIndex]])
            longSites[AllSites[site][i][subjIndex]] = [];
          longSites[AllSites[site][i][subjIndex]].push([AllSites[site][i][xlabelIndex], AllSites[site][i][ylabelIndex]]);
          if (!mapAllSites[site])
            mapAllSites[site] = [];
          mapAllSites[site].push(i);
        }
      }
    }
    // filter out all subjects with only a single scan
    var nums = 0;
    for (sub in longSites) {
      if (longSites[sub].length < 2) {
        delete longSites[sub];
      } else {
        nums++;
      }
    }
    console.log('found longitudinal data: ' + nums);

    // below is only required for male/female but not for sites
    for (i = 0; i < AllMale.length; i++) {
      if (!isNaN(AllMale[i][ylabelIndex]) && !isNaN(AllMale[i][xlabelIndex])) {
        Male.push([AllMale[i][xlabelIndex], AllMale[i][ylabelIndex]]);
        mapMaleToAllMale.push(i);
      }
    }
    for (i = 0; i < AllFemale.length; i++) {
      if (!isNaN(AllFemale[i][ylabelIndex]) && !isNaN(AllFemale[i][xlabelIndex])) {
        Female.push([AllFemale[i][xlabelIndex], AllFemale[i][ylabelIndex]]);
        mapFemaleToAllFemale.push(i);
      }
    }
    drawHighCharts(); // try to draw the chart
  });

  // load the curves now
  column_name = jQuery('#yvalue').val();
  column_name = column_name.replace(/-/g, '.');
  var curvesCsv = "curves/" + user_name + "_" + project_name + "_curves/" + column_name + cookie + ".tsv";
  var VMin = [];
  var VMean = [];
  var VMax = [];

  jQuery.get(curvesCsv, null, function(csv) {
    var lines = [],
      listen = false,
      date;
    var VMin = [],
      VMean = [],
      VMax = [];

    try {
      // split the data return into lines and parse them
      csv = csv.split(/\n/g);
      header = false;
      jQuery.each(csv, function(i, line) {
        if (line == '' || line.charAt(0) == '#') {
          listen = false;
        }

        /*   if (!isNaN(line[0].replace(/\"/g,""))) {
					     age = parseFloat(line[0].replace(/\"/g,""));
					     minVal   = parseFloat(line[1].replace(/\"/g,""));
					     meanVal = parseFloat(line[2].replace(/\"/g,""));
					     maxVal = parseFloat(line[3].replace(/\"/g,""));

  					     if (!isNaN(minVal))
                                               VMin.push( [age, minVal] );
  					     if (!isNaN(meanVal))
					       VMean.push( [age, meanVal] );
  					     if (!isNaN(maxVal))
					       VMax.push( [age, maxVal] );
					       } */


        if (header == false) { // read header first
          curves_header = new Array();
          current_curves = new Array();
          //line = line.split(/,/);
          line = CSVToArray(line);
          line = line[0];
          jQuery.each(line, function(index, value) {
            curves_header[index] = value.replace(/[\" ]/g, "");
            if (index > 0)
              current_curves[index - 1] = new Array();
          });
          header = true;
        } else {
          line = line.split(/,/);
          //line = CSVToArray( line );
          //line = line[0];
          age = parseFloat(line[0].replace(/\"/g, ""));
          line.splice(0, 1); // remove age
          jQuery.each(line, function(index, value) { // we can have a variable number of columns
            value = parseFloat(value.replace(/\"/g, ""));
            if (!isNaN(value) && !isNaN(age))
              current_curves[index].push([age, value]);
          });
        }
      });
    } catch (e) {
      alert(e.message)
    }
    drawHighCharts(); // try to draw the function if everything was loaded
  });
}

function updateQCInformation(id, code) {
  jQuery(id).html(code);
}

function pad(num, size) {
  var s = "000000000" + num;
  return s.substr(s.length - size);
}

function drawHighCharts() {
  // update only if we have all information available
  if ((Male.length > 0 || Female.length > 0) && current_curves.length > 0) {

    var siteConf = [];

    // start with drawing the least important
    var numLongs = 0;
    for (t in longSites) {
      numLongs++;
    }
    if (numLongs > 0) { // we have longitudinal data
      for (t in longSites) {
        siteConf.push({
          type: 'line',
          id: t,
          name: 'nothing',
          data: longSites[t],
          lineColor: 'rgba(10,10,10,0.2)',
          color: 'rgba(10,10,10,0.2)',
          lineWidth: 1,
          marker: {
            enabled: false,
          },
          dashStyle: 'Solid',
          enableMouseTracking: false,
          showInLegend: false
        });
      }
    }

    // this correspondsto the old colors from Highcharts 2.x
    var mycolors = [
       'rgba(#4572A7,.5)',
       'rgba(#AA4643,.5)',
       'rgba(#89A54E,.5)',
       'rgba(#80699B,.5)',
       'rgba(#3D96AE,.5)',
       'rgba(#DB843D,.5)',
       'rgba(#92A8CD,.5)',
       'rgba(#A47D7C,.5)',
       'rgba(#B5CA92,.5)' ];    
    
    // keep track of colors assigned
    var colors = [];
    var co = 0;
    for (site in AllSites) {
      putinlegend = true;
      siteConf.push({
        name: site,
        id: site,
        marker: {
          radius: markerSize,
          fillColor: mycolors[co] // Highcharts.getOptions().colors[co]
        },
        showInLegend: putinlegend
      });
      colors[site] = mycolors[co]; // Highcharts.getOptions().colors[co];
      co++;
    }

    var hasInteraction = (jQuery('#interaction').val().length > 0);
    var c_header = ["Age", "model", "Median", "95%"];
    co = 0;
    for (var j = 0; j < current_curves.length; j++) { // each curve has age,value pairs
      //var nam = !hasInteraction?c_header[j+1]:curves_header[j+1];
      var nam = curves_header[j + 1];
      siteConf.push({
        type: 'line',
        id: 'line' + nam,
        name: nam,
        data: [
          [2.5, 3.25],
          [22, 2.78]
        ],
        lineColor: colors[nam],
        color: colors[nam],
        marker: {
          enabled: false,
        },
        dashStyle: 'Solid',
        enableMouseTracking: false,
        showInLegend: true // !hasInteraction?false:true
      });
      co++;
    }

    // define the options
    var options = {
      chart: {
        //reflow: false,
        renderTo: 'ir_container',
        defaultSeriesType: 'scatter',
        zoomType: 'xy',
        events: {
          /* redraw: function() {
                    if (confidenceInterval != null) {
                        confidenceInterval.destroy();
                    }
                   // draw the yellow area that indicates the confidence intervall around the mean
                   var p = [];
                   i = 0;
                   for (site in AllSites)
                     i++;
                   var one = chart.series[i+0];
                   var two = chart.series[i+2];
                   var hasInteraction = (jQuery('#interaction').val().length > 0);
                   if (false && !hasInteraction && one && two && one.visible == true && two.visible == true) {
                       
                       p.push('M', chart.plotLeft + one.data[0].plotX,
                              chart.plotTop + one.data[0].plotY );
                       p.push('L');
                       for ( var points = 0; points < one.data.length; points++) {
                           p.push( chart.plotLeft + one.data[points].plotX,
                                   chart.plotTop + one.data[points].plotY );
                       }
                       one = chart.series[i+2];
                       for ( var points = one.data.length-1; points >= 0; points--) {
                           p.push( chart.plotLeft + one.data[points].plotX,
                                   chart.plotTop + one.data[points].plotY );
		       }
		       one = chart.series[i+0];
		       p.push( chart.plotLeft + one.data[0].plotX, 
			       chart.plotTop + one.data[0].plotY );
		       
	               confidenceInterval = chart.renderer.path(p).attr({zIndex: 1, 'stroke-width': 0, fill:'LightYellow'}).add();
		   }
		   } */
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
          text: 'Age (years)'
        },
        tickWidth: 0,
        gridLineWidth: 0,
        labels: {
          align: 'left',
          x: 3,
          y: -3
        }
      },

      yAxis: [{
        title: {
          text: stat_header_line[column]
        },
        gridLineWidth: 1
      }],

      legend: {
        align: 'right',
        verticalAlign: 'top',
        y: 45,
        floating: true,
        borderWidth: 1,
        labelFormatter: function() {
          if (this.name == "undefined") {
            return "data";
          }
          return this.name;
        }
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
          // the index is in the array of the current series, we need the index in AllMale/AllFemale
          // to get the values out
          index = mapAllSites[this.series.name][index];

          var names = '';
          var site = '';
          var siteIndex = getColumnIndexPerText("Site");
          var subjectIndex = getColumnIndexPerText("SubjID");
          var genderIndex = getColumnIndexPerText("Sex");
          if (genderIndex == -1)
            genderIndex = getColumnIndexPerText("Gender");
          names = AllSites[this.series.name][index][subjectIndex];
          site = AllSites[this.series.name][index][siteIndex];
          gender = AllSites[this.series.name][index][genderIndex];

          return '' + names + ' (' + gender + ', ' + site + '): ' + this.x.toFixed(2) + ', ' + this.y.toFixed(2);
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
                // find the other columns for this data point
                // series is this.series.name
                var series = chart.get(this.series.name);
                var x = this.x;
                var y = this.y;
                var point = series.data[0];
                var index = 0
                for (u = 0; u < series.data.length; u++) {
                  if (series.data[u].x == x && series.data[u].y == y) {
                    index = u;
                    break;
                  }
                }
                // the index is in the array of the current series, we need the index in AllMale/AllFemal
                // to get the values out
                index = mapAllSites[this.series.name][index];
                /*if (this.series.name == 'Male') {
				index = mapMaleToAllMale[index];
			    } else if (this.series.name == 'Female') {
				index = mapFemaleToAllFemale[index];
			    } else {
				alert('WRONG GENDER');
				} */

                var patientID = 0;
                var visitID = '';
                var site = '';
                var visitID = '';
                var gender = '';
                var studyDate = '';
                var manufacturer = '';

                patientID = AllSites[this.series.name][index][getColumnIndexPerText("SubjID")];
                visitID = AllSites[this.series.name][index][getColumnIndexPerText("VisitID")];
                site = AllSites[this.series.name][index][getColumnIndexPerText("Site")];
                manufacturer = AllSites[this.series.name][index][getColumnIndexPerText("ManufacturersModelName")];
                studyDate = AllSites[this.series.name][index][getColumnIndexPerText("StudyDate")];
		var genderIdx = getColumnIndexPerText("Sex");
                gender = AllSites[this.series.name][index][genderIdx];
                if (genderIdx == -1)
                  gender = AllSites[this.series.name][index][getColumnIndexPerText("Gender")];


                // convert studyDate to a version that has dashes
                var studyDateWithDashes = "";
                if (studyDate) {
		  if (studyDate.indexOf("/") == -1) {
		    if (project_name == "PING") {
		      studyDateWithDashes = studyDate.slice(0,5) + "-" + studyDate.slice(4,6) + "-" + studyDate.slice(5,7);
		    } else {
  		      alert('Warning: studyDate column for this project is not formatted correctly (YYYY/MM/DD). Please contact your systems administrator.');   
		    }
		  }
                  studyDateWithDashes = studyDate.split("/")[2] + "-" + studyDate.split("/")[0] + "-" + studyDate.split("/")[1];
                }

                // create a div that we want to display inside a popup
                var popup = document.createElement('div');
                // create a unique ID for each div we create as a popup
                var numRand = Math.floor(Math.random() * 1000);
                popup.setAttribute('id', 'popup' + patientID + numRand);
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
                popupBodyDiv.setAttribute('style', 'float: right; width: 110px; margin: 4px;');
                popupBody.appendChild(popupBodyDiv);
                var can = document.createElement('canvas');
                popupBodyDiv.appendChild(can);
                can.setAttribute('id', 'sliceCanvas' + patientID + numRand);
                can.setAttribute('width', '100px');
                can.setAttribute('height', '100px');
                document.getElementById('place-for-popups-dialog').appendChild(popup);
                var te = document.createElement('div');
                te.setAttribute('id', 'text' + patientID + numRand);
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

                // query QC for the patient value
                code = {
                  "code": 0,
                  "notes": "",
                  "time": ""
                };
                // NOTE: I suspect this is where project_name get's 'poisoned'. tcooper 2012-03-21

                xval = this.x;
                yval = this.y;
                hs.htmlExpand(null, {
                  pageOrigin: {
                    x: this.pageX,
                    y: this.pageY
                  },
                  contentId: 'popup' + patientID + numRand,
                  headingText: patientID + ' (' + gender + ')',
                  width: 310,
                  height: 190
                });

                // fill in the image from the harddrive
                hs.Expander.prototype.onAfterExpand = function(sender) {
                  mpr1 = new mpr(0);
                  mpr1.bindWindow('#sliceCanvas' + patientID + numRand, [0, 0, 1]);
		  if (typeof visitID == "undefined")
		    visitID = "undefined";
                  visitID2 = visitID.replace("_", "").replace("_", "");
                  if (typeof studyDate != "undefined" && studyDate.indexOf("/") != -1)
                    studyDateNormalized = pad(studyDate.split("/")[2], 4) + pad(studyDate.split("/")[0], 2) + pad(studyDate.split("/")[1], 2);
                  else
                    studyDateNormalized = "unknown";
                  var imageLocation = '/data/' +
                    project_name + '/' + project_name + '_webcache/' +
                    (project_name=="PING"?visitID:patientID) + '/' + studyDateNormalized + '/JPEG/MPR';
                  mpr1.setDataPath(imageLocation);
                  mpr1.infoOverlayEnabled = false;
                  mpr1.crosshairEnabled = false;

                  // display vertex data only if we have a dependent measure that allows vertex wise stats
                  var dependentMeasure = jQuery('#yvalue').val();
                  var surfacestatsflag = false;
                  if (dependentMeasure.substring(0, 8) == "CortArea" ||
                    dependentMeasure.substring(0, 9) == "CortThick" ||
                    dependentMeasure.substring(0, 2) == "T1w")
                    surfacestatsflag = true;
                  var buttonSurfStatsStr = "";
                  surfacestatsflag = false;
                  if (surfacestatsflag) {
                    buttonSurfStatsStr = '<INPUT type="button" value="Review Vertex Data" name="button4"' +
                      'onClick="computeVertexStats(\'' +
                      visitID2 + '\',\'' + studyDate + '\')">';
                  }

                  jQuery('#text' + patientID + numRand).html((site==undefined?'':'site: ' + site + '<br/>') +
                    'visitID: ' + visitID +
                    '<br/>predictor: ' + xval +
                    '<br/>predicted: ' + yval.toFixed(4) + ' [QC:<text id=qc' + patientID + numRand + '>?</text>]' +
                    '<br/>studyDate: ' + studyDateWithDashes + '<br/><br/><div class=\"aux-data\"></div>' +
                    '<button type="button" class="btn btn-small" value="Review Image Data" name="button1" onClick="window.open(\'/applications/ImageViewerMPR/index.php?patient=' + (project_name=="PING"?visitID:patientID) + '&project=' + window.project_name + '&visit=' + studyDateNormalized + '\',\'Images\',\'ImageViewer\')">Review Image Data</button>' +
                    buttonSurfStatsStr);
                };
                // fill in the QC information for this patientID/visitID
                jQuery.ajax({
                  dataType: 'json',
                  url: '/applications/QC/getQCStatus.php?project_name=' + window.project_name + '&patientid=' + patientID + '&visitid=' + visitID + '&com=query',
                  success: function(data) {
                    jQuery.each(data, function(key, value) {
                      code[key] = value;
                    });
                    setTimeout('updateQCInformation( "#qc' + patientID + numRand + '", ' + code.code + ')', 1000);
                  },
                  error: function() {
                    code['notes'] = 'Server error...';
                    setTimeout('updateQCInformation( "#qc' + patientID + numRand + '", ' + '\"server error\"' + ')', 1000);
                    // jQuery('text #qc'+patientID+numRand).html('server error');
                  },
                  async: true
                });
                jQuery.getJSON('/code/php/getAuxData.php?project_name=' + window.project_name + '&patientid=' + patientID + '&visitid=' + visitID + '&com=query',
                  function(data) {
                    if (data != null) {
                      jQuery.each(data, function(key, entry) {
		        var values = entry['values'];
   	                var type = entry['type'];
		        if (values.length > 0) {
                          setTimeout(function() {
                            var entry = jQuery('#text' + patientID + numRand + ' .aux-data');
                            entry.append('<button type="button" class="btn btn-small" target=\"_' + type + '\" value="' + type + '" onClick="window.open(\'' + values[0]['value'] + '\',\'' + type + '\',\''+type+'\')">'+type+'</button><br/>');
                          }, 1000);
			}
                      });
                    }
                  });
              }
            }
          },


          marker: {
            radius: markerSize,
            states: {
              hover: {
                enabled: true,
                lineColor: 'rgb(100,100,100)'
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

    // create the chart
    i = 0;
    for (site in AllSites) {
      options.series[i + numLongs].data = dataSite[site];
      i++;
    }

    for (var j = 0; j < current_curves.length; j++)
      options.series[i + j + numLongs].data = current_curves[j];
    options.animation = true;

    chart = new Highcharts.Chart(options);
    jQuery(".highcharts-legend").draggable();

    // if we have interaction terms, output is normalized
    var dev = jQuery('#covariates_scanner').hasClass('active');
    var ses = jQuery('#covariates_SES').hasClass('active');
    var gaf = jQuery('#covariates_GAF').hasClass('active');
    var hasCommand = jQuery('#command').val() != "";
    var titlePost = "";
    if (hasInteraction || hasCommand || dev || ses || gaf) {
      titlePost = " (adj.)"; 
    }

    chart.yAxis[0].axisTitle.attr({
      text: jQuery('#yvalue').val() + titlePost
    });
    jQuery(chart.yAxis[0].axisTitle.element).text(jQuery('#yvalue').val() + titlePost);

    jQuery(chart.xAxis[0].axisTitle.element).text(jQuery('#functionOf').val());

    // get the long name of the axis
    jQuery.get('/applications/Ontology/translate.php?_v=' + version + '&column=' + jQuery('#yvalue').val() + '&query=short', function(data) {
      var idx = data.indexOf("error");
      if (idx !== "-1") {
        chart.yAxis[0].axisTitle.attr({
          text: data + titlePost
        });
        jQuery(chart.yAxis[0].axisTitle.element).text(data + titlePost);
        chart.options.yAxis[0].title.text = data + titlePost; // this has to be added to make png images show the correct title as well
      }
    });
    var non_smooth = jQuery('#functionOf').val();
    if (non_smooth.substr(0, 2) == "s(") {
      non_smooth = non_smooth.substring(2, non_smooth.length - 1);
    }
    jQuery.get('/applications/Ontology/translate.php?_v=' + version + '&column=' + non_smooth + '&query=short', function(data) {
      var idx = data.indexOf("error");
      if (idx !== "-1") {
        chart.xAxis[0].axisTitle.attr({
          text: data
        });
        jQuery(chart.xAxis[0].axisTitle.element).text(data);
        chart.options.xAxis[0].title.text = data; // this has to be added to make png images show the correct title as well
      } else
        jQuery(chart.xAxis[0].axisTitle.element).text(non_smooth);
    });
    //
    // The clip path which is set by highchart does not work inside an iframe.
    // Only if the clip-path attributes of all svg elements are removed the elements are visible.
    //
    //jQuery('svg').find('g').removeAttr('clip-path');

    // add a csv export module
    jQuery('#export-button').show();
    jQuery('#export-button').click(function() {
      // create a csv file for downloading by the user
      // we want to export all input data used to generate the model
      // and the model and curves of the output of the plot (AllSites)

      data = "# " + jQuery('#yvalue').val() + ' by ' + jQuery('#functionOf').val() + '\n';
      data = data + "# (export of scatter plot and model curves)\n";
      data = data + "# created from mmil-dataportal.ucsd.edu/DataExploration/\n\n";
      // output from the page
      for (site in AllSites) {
        if (site != "undefined")
          data = data + "# " + site + "\n"
        data = data + jQuery('#functionOf').val() + ", " + jQuery('#yvalue').val() + ",\n";
        for (var i = 0; i < dataSite[site].length; i++) {
          data = data + dataSite[site][i][0] + "," + dataSite[site][i][1] + ",\n";
        }
        data = data + "\n\n";
      }
      // add curves
      for (var i = 0; i < current_curves.length; i++) {
        data = data + "# model curves\n";
        for (var j = 0; j < current_curves[i].length; j++) {
          data = data + current_curves[i][j][0] + ", " + current_curves[i][j][1] + ",\n";
        }
        data = data + "\n\n";
      }

      jQuery(this).append('<form id="exportform" action="export_csv.php" method="post" target="_blank"><input type="hidden" id="exportdata" name="exportdata" /><input type="hidden" id="exportdatafilename" name="exportdatafilename" /></form>');
      jQuery("#exportdata").val(data);
      var fn = jQuery('#yvalue').val() + '_by_' + jQuery('#functionOf').val() + '.csv';
      fn = fn.replace("s(", "");
      fn = fn.replace(")", "");
      jQuery("#exportdatafilename").val(fn);
      console.log(fn);
      jQuery("#exportform").submit().remove();
    });
  }
}


//-----------------------------------------------------


var current_search_term = "";
var tree = null;
var tree_positioned = false;
var current_search_index = 0;
var structure_nodes = null;
var selected_index = null;

function inspect(obj) {
  var s = "";
  for (key in obj) {
    s += "\nkey: " + key + ", value: " + obj[key];
  }
  return (s);
}

function doSearch() {
  //$('page').addClassName('wait');

  hideTree();
  // var type = $('search_form').search_type[0].checked ? $('search_form').search_type[0].value : $('search_form').search_type[1].value;
  if (selected_index != null) {
    //$('yvalue').value = structure_nodes[selected_index].innerHTML;
    jQuery('#yvalue').val(structure_nodes[selected_index].innerHTML);
    var textField = structure_nodes[selected_index].innerHTML;
    var foundIt = false;
    for (var i = 0; i < analysis_names.length; i++) {
      if (analysis_names[i] == textField) {
        current_y_axis = i;
        foundIt = true;
        break;
      }
    }
    if (foundIt == false) {
      alert('could not find ' + textField);
    }
    // loadNewYValue(current_y_axis);
  }
  // jQuery('#executeR').submit();
}

function doTypeAhead(evt) {

  if (evt.keyCode == 13) {
    doSearch();
    return;
  }
  // down arrow
  if (evt.keyCode == 40) {
    doTreeNav(evt)
    return;
  }
  // up arrow
  if (evt.keyCode == 38) {
    doTreeNav(evt)
    return;
  }

  // page down arrow
  if (evt.keyCode == 34) {
    doTreeNav(evt)
    return;
  }

  // page up arrow
  if (evt.keyCode == 33) {
    doTreeNav(evt)
    return;
  }

  var sender = (evt.target) ? evt.target : evt.srcElement;

  showTree();
  search(sender.value);
}


function showTree() {

  if (tree === null)
  ; //tree = $('ontology_container');
  return;
  if (tree.hasClassName('hidden')) {
    positionTree(tree);
    tree.removeClassName('hidden');
  }
}

function hideTree() {
  if (tree === null)
  ; //tree = $('ontology_container');

  if (!tree.hasClassName('hidden'))
    tree.addClassName('hidden');
}

function positionTree() {
  if (tree_positioned)
    return;

  var input = $('yvalue');

  tree.clonePosition(input, {
    setHeight: false,
    offsetTop: 25,
    offsetLeft: +2
  });

  tree_positioned = true;
}

function doBlur() {
  hideTree();
}
function search(term) {

  if (term.length == 0) {
    selectNone();
    hideTree();
    return;
  }

  // special case search for a data point
  if (typeof chart != 'undefined' && term.charAt(0) == '@' && term.length > 1) {
    var foundFirst = false;
    for (var s = 0; s < chart.series.length; s++) {
      var series = chart.series[s];
      var point = series.data[0];
      var index = 0
      for (u = 0; u < series.data.length; u++) {
        index = u;
        // the index is in the array of the current series, we need the index in AllMale/AllFemal
        // to get the values out
        sn = series.name;
        if (sn == "nothing")
          sn = 'undefined';

        if (typeof mapAllSites[sn] == 'undefined')
          continue;

        index = mapAllSites[sn][index];

        var patientID = 0;
        //var visitID = '';

        patientID = AllSites[sn][index][getColumnIndexPerText("SubjID")];
        // visitID = AllSites[this.series.name][index][getColumnIndexPerText("VisitID")];

        if (patientID.match(new RegExp(term.substr(1), 'ig')) != null) {
          if (foundFirst == false) {
             series.data[u].select(true, false); // deselect all other points
             foundFirst = true;
          } else
             series.data[u].select(true, true);
        } 
      }
    }
    return; // do nothing else
  } else {
    for (var s = 0; s < chart.series.length; s++) {
      var series = chart.series[s];
      for (u = 0; u < series.data.length; u++) {
        series.data[u].select(false);
      }
    }
  }

  //  var pat = "^"+term;
  var pat = term;
  var reg = new RegExp(pat, 'i');
  reg.compile(pat, "i");

  if (typeof structure_nodes != "undefined" && structure_nodes != null) {
    for (var i = 0; i < structure_nodes_length; i++) {
      if (reg.test(structure_nodes[i].innerHTML)) {
        selectStructure(i);
        break;
      }
    }
  }
}

function moveTo(containerX, element) {
  Position.prepare();
  container_y = Position.cumulativeOffset($(containerX))[1];
  element_y = Position.cumulativeOffset($(element))[1];
  new Effect.Scroll(containerX, {
    x: 0,
    y: (element_y - container_y)
  });
  return false;
}

function selectStructure(index) {
  if (selected_index != null)
    structure_nodes[selected_index].removeClassName('selected');

  structure_nodes[index].addClassName('selected');
  selected_index = index;

  moveTo(tree, structure_nodes[index]);
  //jQuery('#yvalue').val(structure_nodes[selected_index].innerHTML);
}

function selectStructureById(id) {
  for (var i = 0; i < structure_nodes_length; i++) {
    if (structure_nodes[i].id == id) {
      selectStructure(i);
      break;
    }
  }
}

function selectNone() {
  if (selected_index)
    structure_nodes[selected_index].removeClassName('selected');
}

function doTreeClick(evt) {
  //alert("click");
  var sender = (evt.target) ? evt.target : evt.srcElement;
  //alert("click : " + inspect(sender));
  selectStructureById(sender.id);
  doSearch();
}

function doTreeNav(evt) {
  if (evt.keyCode == 40) {
    if (selected_index < structure_nodes_length - 1)
      selectStructure(selected_index + 1);
  } else if (evt.keyCode == 38) {
    if (selected_index > 0)
      selectStructure(selected_index - 1);
  } else if (evt.keyCode == 34) {
    if (selected_index > 0 && selected_index < structure_nodes_length - 1)
      selectStructure(selected_index + 9);
  } else if (evt.keyCode == 33) {
    if (selected_index > 0)
      selectStructure(selected_index - 9);
  }
  jQuery('#yvalue').val(structure_nodes[selected_index].innerHTML);
}

function init() {
  var search_box = $('yvalue');
  //search_box.value = "";
  if (search_box) {
    search_box.observe('keyup', doTypeAhead);
  }
  //search_box.observe('blur', doBlur);

  //tree = $('ontology_container');
  if (tree) {
    tree.observe('click', doTreeClick);
    tree.observe('keyup', doTreeNav);
    tree.observe('blur', doBlur);

    structure_nodes = tree.select('.tnc');
    if (structure_nodes) {
      structure_nodes_length = structure_nodes.length;
    }
  }
}

Effect.Scroll = Class.create();
Object.extend(Object.extend(Effect.Scroll.prototype, Effect.Base.prototype), {
  initialize: function(element) {
    this.element = $(element);
    var options = Object.extend({
      x: 0,
      y: 0,
      mode: 'absolute'
    }, arguments[1] || {});
    this.start(options);
  },
  setup: function() {
    if (this.options.continuous && !this.element._ext) {
      this.element.cleanWhitespace();
      this.element._ext = true;
      this.element.appendChild(this.element.firstChild);
    }

    this.originalLeft = this.element.scrollLeft;
    this.originalTop = this.element.scrollTop;

    if (this.options.mode == 'absolute') {
      this.options.x -= this.originalLeft;
      this.options.y -= this.originalTop;
    } else {

    }
  },
  update: function(position) {
    this.element.scrollLeft = this.options.x * position + this.originalLeft;
    this.element.scrollTop = this.options.y * position + this.originalTop;
  }
});