var showTotal = 1;
var showCons = 0;
//function used to hide the the results table and corresponding divs
function hideResults() {
	jQuery("#data-table").empty();
	jQuery("#total-table").empty();
	jQuery("#consecutive-table").empty();
	jQuery("#chart").empty();
	jQuery("#view-type").hide();
	jQuery("#chart-type").hide();
	jQuery(".download").hide();
	jQuery("#firstlastcheckbox").hide();
	jQuery("#map").hide();
	jQuery("#total_toggle").hide();
	jQuery("#consecutive_toggle").hide();
	jQuery("#filter-opt-list").hide();
}

//show or hide the correct magnitude filters based on what is selected
function showHideMagFilters(page) {
		if ((page.reportType === "T" || page.reportType === "W") && page.reportSource === "LSR") {
			jQuery("#tor-report-filters").hide();
			jQuery("#wind-report-filters").hide();
			jQuery("#hail-report-filters").hide();
		} else if (page.reportType === "ALL") {
			jQuery("#tor-report-filters").hide();
			jQuery("#wind-report-filters").hide();
			jQuery("#hail-report-filters").hide();
		} else if (page.reportType === "A") {
			jQuery("#hail-report-filters").show();
			jQuery("#tor-report-filters").hide();
			jQuery("#wind-report-filters").hide();
		} else if (page.reportType === "G" || page.reportType === "ALLW") {
			jQuery("#hail-report-filters").hide();
			jQuery("#tor-report-filters").hide();
			jQuery("#wind-report-filters").show();
		} else if (page.reportType === "T") {
			jQuery("#hail-report-filters").hide();
			jQuery("#tor-report-filters").show();
			jQuery("#wind-report-filters").hide();			
		}
}

function buildPage(page){
	//Load default values for button select
	page.chartType = "month";
	page.reportSource = "LSR";
	page.reportType = "ALL";
	page.watchType = "TOR";
	page.viewType = "table";

	jQuery("#map").hide();
	jQuery("#downloadJSON").hide();
	jQuery("#downloadCSV").hide();
	jQuery("#total_toggle").html('<i class = "fa fa-minus-square details-control">Hide Totals Table</i>');
	jQuery("#consecutive_toggle").html('<i class = "fa fa-plus-square details-control">Show Consecutive Table</i>');
	jQuery("#total_toggle").hide();
	jQuery("#consecutive_toggle").hide();
	jQuery("#consecutive-table").hide();

	//drawMapElements();

	//Data type buttonset on change function
	jQuery("#data-type-buttonset").buttonset().change(function(){
			//Clear out previous search data
			hideResults();

			//set value of checked radio button to v
		jQuery(this).find("input:radio").each(function(){
			if(jQuery(this).prop("checked")){
				v = jQuery(this).attr("value");
			}
		});

		//set page.dataType equal to the value of the checked radio button
		page.dataType = v;

		//hide or show several divs based on the value of the checked radio button
		if(v === 'watch'){
			jQuery("#all-report-filters").hide();
			jQuery("#report-source").hide();
			jQuery("#report-type").hide();
			jQuery("#all-watch-filters").show();
			jQuery("#watch-type").show();
			jQuery("#view-type-3").button("enable");
			document.getElementById("chartType3").innerHTML = '<span class="ui-button-text">By Type</span>';
		}
		if(v === 'report'){
			jQuery("#all-watch-filters").hide();
			jQuery("#watch-type").hide();
			jQuery("#all-report-filters").show();
			jQuery("#report-source").show();
			jQuery("#report-type").show();
			jQuery("#view-type-3").button("disable");
			document.getElementById("chartType3").innerHTML = '<span class="ui-button-text">By Magnitude</span>';
		}
			jQuery("#time-type").show();
			jQuery("#date-pickers").show();

	}); //End of data-type-buttonset on change function

	//Function when report-type-buttonset is changed
	jQuery("#report-type-buttonset").buttonset().change(function(){
			//Clear out previous search data
			hideResults();

		//set value of checked radio button to v
		jQuery(this).find("input:radio").each(function(){
			if(jQuery(this).prop("checked")){
				v = jQuery(this).attr("value");
			}
		});

		//set page.reportType equal to the value of the checked radio button
		page.reportType = v;

		showHideMagFilters(page);

	});//end of report-type-buttonset on change function

	//set page.viewType equal to whatever is checked on view-type-buttonset. Also hide and show different divs based on what is selected
	jQuery("#view-type-buttonset").buttonset().change(function(){
		jQuery(this).find("input:radio").each(function(){
			if(jQuery(this).prop("checked")){
				v = jQuery(this).attr("value");
			}
		});
		page.viewType = v;

	if (page.viewType === "table") {
		jQuery(".download").show(); //show download button		
		jQuery("#data-table").show(); //show data table
		jQuery("#total-table").show();//show total table
		jQuery("#firstlastcheckbox").show(); //show firstlast checkbox
		jQuery("#chart-type").hide();//hide chart type selector
		jQuery("#chart").hide();//hide chart
		jQuery("#map").hide();
	} else if (page.viewType ==="chart") {
		jQuery("#data-table").hide(); //hide data table
		jQuery("#total-table").hide();//hide total table
		jQuery("#firstlastcheckbox").hide(); //hide firstlast checkbox
		jQuery(".download").hide();//hide download button
		jQuery("#chart-type").show();//show chart type selector
		jQuery("#chart").show(); //show chart
		jQuery("#map").hide();
	} else if (page.viewType ==="map") {
		jQuery("#data-table").hide(); //hide data table
		jQuery("#total-table").hide();//hide total table
		jQuery("#firstlastcheckbox").hide(); //hide firstlast checkbox
		jQuery(".download").hide();//hide download button
		jQuery("#chart-type").hide();//show chart type selector
		jQuery("#chart").hide(); //show chart
		jQuery("#map").show();
		if (page.watchType === "TOR") {
		selectTornado();
		redrawMap();
		} else if (page.watchType === "SVR") {
		selectSevere();
		redrawMap();
		} else {	
		selectAll();
		redrawMap();
		}
	}
	});//end of on change function for view-type-buttonset

	//Function when watch-type-buttonset is changed
	jQuery("#watch-type-buttonset").buttonset().change(function(){
			//Clear out previous search data
			hideResults();
			
		jQuery(this).find("input:radio").each(function(){
			if(jQuery(this).prop("checked")){
				v = jQuery(this).attr("value");
			}
		});
		page.watchType = v;
	});//end of watch-type-buttonset on change function

	//Function when time-type-buttonset is changed
	jQuery("#time-type-buttonset").buttonset().change(function(){
			//Clear out previous search data
			hideResults();
			
		jQuery(this).find("input:radio").each(function(){
			if(jQuery(this).prop("checked")){
				v = jQuery(this).attr("value");
			}
			jQuery("#date-pickers").hide();
			jQuery("#month_dropdown").hide();
			jQuery("#season_dropdown").hide();
			jQuery("#date_dropdown").hide();
			if (v === "range") {
			jQuery("#date-pickers").show();
			} else if (v === "month") {
			jQuery("#month_dropdown").show();
			} else if (v === "season") {
			jQuery("#season_dropdown").show();
			} else if (v === "date_range") {
			jQuery("#date_dropdown").show();
			}
		});
	});//end of time-type-buttonset on change function

	//Function when report-source-buttonset is changed
	jQuery("#report-source-buttonset").buttonset().change(function(){
			//Clear out previous search data
			hideResults();

		jQuery("#report-type").show(); //show report type buttons
		jQuery(this).find("input:radio").each(function(){
			if(jQuery(this).prop("checked")){
				v = jQuery(this).attr("value");
			}
		});
		page.reportSource = v;
		//disable G and W if stormData is selected since all wind is combined.
		if (v === "stormData") {
			jQuery("#report-type-5").button("disable");
			jQuery("#report-type-6").button("disable");
		} else {
			jQuery("#report-type-5").button("enable");
			jQuery("#report-type-6").button("enable");			
		}
		showHideMagFilters(page); //function to show/hide the correct magnitude filters based on what report type/source is selected
	});//end of report-source-buttonset on change function

	//Function when chart-type-buttonset is changed
	jQuery("#chart-type-buttonset").buttonset().change(function(){
		jQuery(this).find("input:radio").each(function(){
			if(jQuery(this).prop("checked")){
				v = jQuery(this).attr("value");
			}
		});
		page.chartType = v;
		makeChart(page);//redraw chart anytime this button is changed.
	});//end of chart-type-buttonset on change function

	jQuery("#downloadJSON").click(function() {
		makeJSON(page);
	});
	jQuery("#downloadCSV").click(function() {
		makeCSV(page);
	});

	//Create dialog box when browser is unsupported
	jQuery("#error-dialog").dialog({
		autoOpen: false,
		resizable: false,
		height:300,
		width: 400,
		modal: true,
		buttons:{
			"Close": function(){
				jQuery(this).dialog("close");
			}
		}
	});

	//Create Filters for Dates/States/CWAs/FIPS
	createCalendars(page);
	filterStates(page);
	filterCWAs(page);
	filterFIPSandZIP(page);

	//Start long function that is activated when Generate Button is pressed
	jQuery("#go-btn").button().on('click', function(){

	jQuery("#firstlast").prop("checked",false);
	jQuery("#view-type").show(); //show view type selector
	jQuery("#total_toggle").show();
	jQuery("#consecutive_toggle").show();
	jQuery("#filter-opt-list").show();
	if (jQuery("#view-type-2").prop("checked")) {
	jQuery("#chart-type").show(); //show view type selector
	} else if (jQuery("#view-type-3").prop("checked")) {
	jQuery("#map").show();
	}
	jQuery("#filter-opt-list").show(); //show results of filters

	if (page.dataType === "report" && page.reportType === "T" && page.reportSource === "LSR" || page.dataType === "report" && page.reportType === "W" && page.reportSource === "LSR" || page.dataType === "watch" && page.watchType != "ALL") {
		jQuery("#chart-type-3").button("disable");
		jQuery("#chart-type-4").button("disable");		
	} else {
		jQuery("#chart-type-3").button("enable");
		jQuery("#chart-type-4").button("enable");
		}

		var urlStr = '';
		if (page.dataType ==="watch"){
			if(typeof(page.data) === 'undefined'){
				urlStr ="/bentley/dev/page/data/collections/combined_watch_collections_2017-2020.json";
			}else if(typeof(page.data['watch'])){
				urlStr ="/bentley/dev/page/data/collections/combined_watch_collections_2017-2020.json";
			}else{
				urlStr = "";
			}
		}else if(page.dataType ==="report"){
			console.log(page.reportSource)
			if (page.reportSource ==="LSR") {
				if(typeof(page.data) === 'undefined'){
					urlStr ="/bentley/dev/page/data/collections/combined_report_collections_2017-2019-packed.json";
				}else if(typeof(page.data['report'])){
					urlStr ="/bentley/dev/page/data/collections/combined_report_collections_2017-2019-packed.json";
				}else{
					urlStr = "";
				}
			} else if (page.reportSource ==="stormData") {
				if(typeof(page.data) === 'undefined'){
					urlStr ="/bentley/dev/page/data/collections/combined_stormdata_collections_2017-2019-packed.json";
				}else if(typeof(page.data['stormData'])){
					urlStr ="/bentley/dev/page/data/collections/combined_stormdata_collections_2017-2019-packed.json";
				}else{
					urlStr = "";
				}
			}
		}//end of else if for page.dataType ==="report"

		//Run AJAX call to pull the data as long as urlStr is valid
		if(urlStr !== ''){
			//Get watch data
			jQuery.ajax({
				dataType: "json",
				url: urlStr
			}).done(function(data){
				//store data in page.data
				page.data = new Object();
				if(page.dataType === 'watch'){
					page.data['watch'] = data;
				}else if(page.dataType === 'report'){
					page.data['report'] = jsonh.unpack(data[0]);
				}
				//Once data is assembled run createFilteredData and getFilteredData functions
				createFilteredData(page);
				getFilteredData(page);
			});//end of AJAX call
		}//end of if function containing AJAX call              

		//Allow download button to be visible if the view type is table
		if (page.viewType === "table") {
			jQuery(".download").show();
			jQuery("#firstlastcheckbox").show();
		}

		if (page.watchType === "TOR") {
		selectTornado();
		redrawMap();
		} else if (page.watchType === "SVR") {
		selectSevere();
		redrawMap();
		} else {	
		selectAll();
		redrawMap();
		}
	}); //end of on click function for go-btn

	jQuery("#total_toggle").on('click', function(){
		if (showTotal === 1) {
		jQuery("#total-table").hide();
		jQuery("#total_toggle").html('<i class = "fa fa-plus-square details-control">Show Totals Table</i>');
		showTotal = 0;
		} else {
		jQuery("#total-table").show();	
		jQuery("#total_toggle").html('<i class = "fa fa-minus-square details-control">Hide Totals Table</i>');
		showTotal = 1;		
		}
	});

	jQuery("#consecutive_toggle").on('click', function(){
		if (showCons === 1) {
		jQuery("#consecutive-table").hide();
		jQuery("#consecutive_toggle").html('<i class = "fa fa-plus-square details-control">Show Consecutive Table</i>');
		showCons = 0;
		} else {
		jQuery("#consecutive-table").show();
		jQuery("#consecutive_toggle").html('<i class = "fa fa-minus-square details-control">Hide Consecutive Table</i>');	
		showCons = 1;		
		}
	});

	//Show dialog box when go-button is clicked
  jQuery( function() {
      dialog = jQuery( "#dialog" ).dialog({
        autoOpen: false,
        closeOnEscape: false,
        resizable: false,
      }),
      downloadButton = jQuery( "#go-btn" )
        .button()
        .on( "click", function() {
          dialog.dialog( "open" );
        });
  } );
} //end of buildPage function
