function createCalendars(page){
	//Calendar stuff for the climo page
	var defd = moment().toDate();
	var mind = new Date(2010, 1, 2);
	var maxd = moment().toDate();
	
	jQuery("#startCal").on("click", function(){
		jQuery("#start-dp").datepicker("destroy");		
		jQuery("#start-dp").datepicker({
			changeMonth: true,
			changeYear: true,
			minDate: mind,
			maxDate: maxd,
			defaultDate: defd,
			showButtonPanel: true,
			beforeShow: function(input){
				setTimeout(function(){
					var buttonPane = jQuery(input)
						.datepicker("widget")
						.find(".ui-datepicker-current");
					if(jQuery(buttonPane).hasClass("ui-priority-secondary")){
						jQuery(buttonPane).addClass("ui-priority-primary");
						jQuery(buttonPane).removeClass("ui-priority-secondary");	
					}else{

					}
					jQuery(buttonPane).click(function(){
						jQuery("#start-dp").datepicker("setDate", new Date());	
					})
				}, 1);				
			},
			onChangeMonthYear: function(input){
				setTimeout(function(){
					var buttonPane = jQuery(input)
						.datepicker("widget")
						.find(".ui-datepicker-current");
					if(jQuery(buttonPane).hasClass("ui-priority-secondary")){
						jQuery(buttonPane).addClass("ui-priority-primary");
						jQuery(buttonPane).removeClass("ui-priority-secondary");	
					}else{

					}
					jQuery(buttonPane).click(function(){
						jQuery("#start-dp").datepicker("setDate", new Date());	
					});
				}, 1);																
			},
			onClose: function(newDate){
				var d = moment( newDate, 'MM/DD/YYYY').format('YYYYMMDD');
				jQuery("#startCalInput").val(d);			
			}
		});				
		jQuery("#start-dp").datepicker("show");
	});

	jQuery("#endCal").on("click", function(){
		jQuery("#end-dp").datepicker("destroy");
		jQuery("#end-dp").datepicker({
			changeMonth: true,
			changeYear: true,
			minDate: mind,
			maxDate: maxd,
			defaultDate: defd,
			showButtonPanel: true,
			beforeShow: function(input){
				setTimeout(function(){
					var buttonPane = jQuery(input)
						.datepicker("widget")
						.find(".ui-datepicker-current");
					if(jQuery(buttonPane).hasClass("ui-priority-secondary")){
						jQuery(buttonPane).addClass("ui-priority-primary");
						jQuery(buttonPane).removeClass("ui-priority-secondary");	
					}else{

					}
					jQuery(buttonPane).click(function(){
						jQuery("#end-dp").datepicker("setDate", new Date());	
					})

					//code for today button to close when it is clicked
					jQuery.datepicker._gotoToday = function(id) { 
    				jQuery(id).datepicker('setDate', new Date()).datepicker('hide').blur(); 
					};
					
				}, 1);				
			},
			onChangeMonthYear: function(input){
				setTimeout(function(){
					var buttonPane = jQuery(input)
						.datepicker("widget")
						.find(".ui-datepicker-current");
					if(jQuery(buttonPane).hasClass("ui-priority-secondary")){
						jQuery(buttonPane).addClass("ui-priority-primary");
						jQuery(buttonPane).removeClass("ui-priority-secondary");	
					}else{

					}
					
					//code for today button to close when it is clicked
					jQuery.datepicker._gotoToday = function(id) { 
    				jQuery(id).datepicker('setDate', new Date()).datepicker('hide').blur(); 
					};

				}, 1);																
			},
			onClose: function(newDate){
				var d = moment( newDate, 'MM/DD/YYYY').format('YYYYMMDD');
				jQuery("#endCalInput").val(d);				
			}
		});
		jQuery("#end-dp").datepicker("show");				
	});
	jQuery(".ui-datepicker-today").click(function(){
		jQuery("#end-dp").datepicker("destroy");
	});
}

function checkDate(d){
	if(d.length !== 8){
		return false;
	}else{
		var m = moment(d, "YYYYMMDD");
		if(moment(d, "YYYYMMDD").format("YYYYMMDD") === d){
			return d;
		}else{
			return false;
		}
	}		
}
