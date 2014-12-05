//
// This module can store information about acknowledgements of legal documents
// as either cookies or as user defined variables (in user database).
//

// configuration
//legal_storage = 'cookie'; // store values in browser cookie
legal_storage = 'userdb'; // store values in user database

function checkLegal() {
  if (legal_storage == 'cookie') {
     // check if the cookie name exists already
     if (readCookie("dataportal-legal"))
       return;
     // if not we need to display the legal document and set the cookie
     jQuery('#legal').modal({
		       keyboard: false,
		       backdrop: 'static'
		       });
     jQuery('#legal').modal('show');  
   
     // createCookie("dataportal-legal", "agreed", 356);
     return;
  } else if (legal_storage == 'userdb') {
     jQuery.getJSON('/code/php/getUser.php?action=getValue&value=legalok', function(data) {
		      
	// if we get a value back we are ok
        if (data != null && data['message'] != "error")
	  return;
        // otherwise
        jQuery('#legal').modal({
		       keyboard: false,
		       backdrop: 'static'
		       });
        jQuery('#legal').modal('show');
     });
     return;
  }    
}

function createCookie(name, value, days) { 
      if (days) {
	        var date = new Date();
	        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
	        var expires = "; expires=" + date.toGMTString();
	    } else 
		var expires = "";
      document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
  }

function readCookie(name) {
  
      var nameEQ = escape(name) + "=";
      var ca = document.cookie.split(';');
      for (var i = 0; i < ca.length; i++) {	
	        var c = ca[i];
	        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
	        if (c.indexOf(nameEQ) == 0) return unescape(c.substring(nameEQ.length, c.length));
	    }
      return null;
}

function eraseCookie(name) {  
      createCookie(name, "", -1);
}

jQuery(document).ready(function() {
  
		    
  jQuery('#legal').on('show', function() { // fill the legal block with text
    // disable the buttons initially, user needs to scroll to end of legal text
    jQuery('#legal-agree').addClass('disabled');
    jQuery('#legal-disagree').hide(); // prevent people from disagreeing again if they agreed once                                                      
		   
    jQuery('#legal-text').load('/legal.html');
    jQuery('#legal-text').bind('scroll', function() { // this does not count the borders
        if (jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight) {
	  jQuery('#legal-agree').removeClass('disabled');
        }
    });

  });

  jQuery('#legal-disagree').click(function() {
	// ignore if button is disabled
	if (jQuery(this).hasClass('disabled'))
	  return;
	if (legal_storage == 'cookie')
           eraseCookie("dataportal-legal");
	if (legal_storage == 'userdb') {
	   var now = new Date();
	   var date = (now.getMonth()+1) + '/' + (now.getDate()) + '/' + now.getFullYear();
	   jQuery.ajax({
		       url: '/code/php/getUser.php?action=setValue&value=legalok&value2=rm', 
		       type: 'PUT',
	 	       success: function(response) {
		          // do nothing
		       }
		      });
	}
        jQuery('#legal').modal('hide');
			       
	//var opened = window.open("", "_self");
	//opened.document.write("<html><head><title>Not allowed</title></head><body>Access to this page is not allowed.</body></html>");
	logout();
  });
  jQuery('#legal-agree').click(function() {
	if (jQuery(this).hasClass('disabled'))
	  return;
	if (legal_storage == 'cookie')
           createCookie("dataportal-legal", "agreed", 356);
	if (legal_storage == 'userdb') {
	   var now = new Date();
	   var date = (now.getMonth()+1) + '/' + (now.getDate()) + '/' + now.getFullYear();
	   jQuery.getJSON('/code/php/getUser.php?action=setValue&value=legalok&value2='+date, function(data) {
		// stores the date of the agree	    
	   });
	}
        jQuery('#legal').modal('hide'); // close the dialog again
  });

});
