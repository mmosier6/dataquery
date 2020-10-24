//Error handling functions.
function errorDialog(error_type){
	if(error_type == 'date'){	
		jQuery("#error-dialog-text").empty();
		jQuery("#error-dialog-text").html("Error in the date format. The correct date format is <b>YYYYMMDDD</b>.");		
		jQuery("#error-dialog").dialog("open");
	}	
}
