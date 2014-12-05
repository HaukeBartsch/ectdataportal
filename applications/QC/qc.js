
  function addQCNotes( patientID, visitID, notes, user ) {
    $.ajax({url: '/applications/QC/getQCStatus.php?project_name=' + window.project_name + 
		'&patientid=' + patientID +
		'&visitid=' + visitID +	
		'&com=setnotes' + 
                '&user=' + user +
		'&value=' + notes,
	    success: function(data) {
	          ; // we don't expect something coming  back
	    },
            error: function() {
	       $('#loading').html('Server error...').fadeOut(10000);
	    }
	   });
        return;
  }

  function addQCBadSeries( patientID, visitID, notes, user ) {
    $.ajax({url: '/applications/QC/getQCStatus.php?project_name=' + window.project_name + 
		'&patientid=' + patientID +
		'&visitid=' + visitID +	
		'&com=setbadseries' + 
                '&user=\"' + user + "\"" +
		'&value=' + notes,
	       succsess: function(data) {
	    ; // we don't expect something coming  back
	     },
	       error: function() {
                  $('#loading').html('Server error...');
	     }
	});
        return;
  }

  // instead of checking locally this should be done on the server (php)
  function addQCEntry( patientID, visitID, code, user ) { // add a time stamp
    $.ajax({url: '/applications/QC/getQCStatus.php?project_name=' + window.project_name + 
		'&patientid=' + patientID +
		'&visitid=' + visitID +	
		'&com=setcode' + 
  	        '&value=\"' + code + "\"" +
                '&user=\"' + user + "\"",
	   success: function(data) {
	    ; // we don't expect something coming  back
	   },
	   error: function() {
              $('#loading').html('Server error...');
	     }
	   });
        return;
  } 

  function getQCCode( patientID, visitID ) {
    // needs to be defined in the global scope (no 'var') to make this work 
    code = { "code": 0, "notes": "", "time": "" };
      $.ajax({
  	  dataType: 'json',
	  url: '/applications/QC/getQCStatus.php?project_name=' + window.project_name + 
		     '&patientid=' + patientID +
		     '&visitid=' + visitID +	
		     '&com=query',
	  success: function(data) {
	             $.each(data, function(key, value) {
		        code[key] = value;
		     });
	  },
	  error: function() {
             $('#loading').html('Server error...');
	  },
	  async: false
      });
      return code;
  }

  function getQCAll() {
      values = new Array();
      $.ajax({
  	  dataType: 'json',
	  url: '/applications/QC/getQCStatus.php?project_name=' + window.project_name + 
		     '&com="queryall"' + '&user="' + window.user_name + '"',
	  success: function(data) {
		values = data;
	  },
	  error: function() {
             $('#loading').html('Server error...');
	  },
	  async: false
      });
      return values;
  }

function getStringForCode( code ) {
  if (typeof code == "string") {
    code = parseFloat(code);
  }
    
   switch(code)
	{
	case -1: 
	return "bad";
	break;
	case 0:
	return "unknown";
	break;
	case 1:
        return "good";
	break;
	default:
	return "???";
   }
}

