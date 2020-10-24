//global variables
var finalJSON;
var finalCSV;
var month_count=Array();
var year_count=Array();
var month_abbrev = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
var year_list = [2000,2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018,2019,2020];
var today = new Date();
var current_year = today.getFullYear();
var total = {};
total['type'] = {};
total['time'] = {};
total['time']['month'] = {};
total['time']['year'] = {};
total['time']['consecutive'] = {};
total['time']['drought'] = {};
var totalTitle = Array();
var magTitle;
var timesort;
var mapJSON;
var mapJSONfinal;
var totalType = Array();
var prevDay = new Date();
var nextDay = new Date();
var consDays = 1;
var consDaysEnd;

jQuery.getJSON( "/bentley/dev/page/data/topojson/output.json", function( json ) {
	mapJSON = json;
 });

function displayFilteredData(page){
	for (i=0; i<mapJSON.features.length; i++) {
				mapJSON.features[i].properties.ALLWATCH = 0;
				mapJSON.features[i].properties.TOR = 0;
				mapJSON.features[i].properties.SVR = 0;
				mapJSON.features[i].properties.PDSTOR = 0;
				mapJSON.features[i].properties.PDSSVR = 0;
	}
	
//clear finalJSON every time the script is run
finalJSON = "";
finalCSV = [];
//table formaatting if data type is watch
if (page.dataType === "watch") {
	consDays=1;
	timesort = "dt"; //set timesort equal to the format from the json file
	total['time']['consecutive']['count'] = [];
	total['time']['consecutive']['endDate'] = [];
	
	//function used down below to change the order of the data based on the date
	function compare(a,b){
		const watchA = a["sel_issue_dt"];
		const watchB = b["sel_issue_dt"];
		
		var comparison = 0;
		if(watchA > watchB){
			comparison = 1;
		}else if(watchA < watchB){
			comparison = -1;
		}
		
		return comparison;
	}

	totalTitle = ["All Watches","Tornado","Severe","PDS Tornado","PDS Severe"];

	totalType = ["total","TOR","SVR","PDS TOR","PDS SVR"];
	
	//after filter is run, use compare funtion to sort the data
	page.data['filtered-sorted'] = page.data['filtered'].slice().sort(compare);	

	//establish the total array with multiple dimensions to be used below.
	total['type'] = [0,0,0,0,0];
	totalType.forEach(function(d) {
		total['time']['month'][d] = [];
		total['time']['year'][d] = [];
	});

		total['time']['consecutive']['count'] = [];
		total['time']['consecutive']['endDate'] = [];
		total['time']['drought']['count'] = [];
		total['time']['drought']['endDate'] = [];

	//create variables used to store data 
	var results;
	var results_final = {};
	results_final['data'] = [];

	//build the entire javascript for input into the table here
	page.data['filtered-sorted'].forEach(function(d,n){
		var fulldt = d["sel_issue_dt"];
		results = {};
		results['dt'] = d['sel_issue_dt'];
		results['dt_min'] = fulldt.slice(4,12); //this is the date with only month, day, hour, for sorting that does not include the year (first/last)
		results['dt_dis'] =  "" + fulldt.slice(4,6) +"/"+ fulldt.slice(6,8) +"/"+ fulldt.slice(0,4) +" "+ fulldt.slice(8,12) + ""; //this is the date display
		results['type'] = d['type'];
		results['num'] = "<a href = 'https://www.spc.noaa.gov/products/watch/"+fulldt.slice(0,4)+"/ww"+d['watch_num']+".html' target='_blank'>"+d['watch_num']+"</a>";
		results['st'] = d['ST'].toString();
		results['cwa'] = d['CWA'].toString();
		results['threats'] = d['threats'].toString();
		results['areas'] = d['areas'].toString();
		results['summary'] = d['summary'].toString();
		results_final["data"].push(results);

		//this is how totals are calculated. Add one each time you iterate through.
		total['type'][0]=total['type'][0]+1;
		if (d['type'] === "TOR") {
			total['type'][1]=total['type'][1]+1;
		} else if (d['type'] === "SVR") {
			total['type'][2]=total['type'][2]+1;
		} else if (d['type'] === "PDS TOR") {
			total['type'][3]=total['type'][3]+1;
		} else if (d['type'] === "PDS SVR") {
			total['type'][4]=total['type'][4]+1;
		}

		//build GeoJSON file
		for (i=0; i<mapJSON.features.length; i++) {
			if (d['FIPS'].includes(mapJSON.features[i].properties.FIPS)) {
				mapJSON.features[i].properties.ALLWATCH = mapJSON.features[i].properties.ALLWATCH + 1;
				if (d['type'] === "TOR") {
					mapJSON.features[i].properties.TOR = mapJSON.features[i].properties.TOR+ 1;
				}else if (d['type'] === "SVR") {
					mapJSON.features[i].properties.SVR = mapJSON.features[i].properties.SVR+ 1;
				}else if (d['type'] === "PDS TOR") {
					mapJSON.features[i].properties.PDSTOR = mapJSON.features[i].properties.PDSTOR+ 1;
				}else if (d['type'] === "PDS SVR") {
					mapJSON.features[i].properties.PDSSVR = mapJSON.features[i].properties.PDSSVR+ 1;
				}

			}
		}

		//this function counts the number of consecutive days of each
		nextDay.setTime(prevDay.getTime());
		nextDay.setDate(prevDay.getDate()+1);

		var nextCompare = "" + ("0" + (nextDay.getMonth() + 1)).slice(-2) + "" + ("0" + (nextDay.getDate())).slice(-2) + "";
		var prevCompare = "" + ("0" + (prevDay.getMonth() + 1)).slice(-2) + "" + ("0" + (prevDay.getDate())).slice(-2) + "";
		var dateCompare = results['dt_min'].slice(0,4);
		console.log(dateCompare)
		console.log(nextCompare)
		if (dateCompare === nextCompare) {
			consDays = consDays +1;
		} else if (dateCompare === prevCompare) {
		} else if (consDays >1){
			total['time']['consecutive']['count'].push(consDays);
			total['time']['consecutive']['endDate'].push(prevDay);
			consDays = 1;
		} else {
			consDays = 1;
		}


		prevDay = new Date(fulldt.slice(0,4),fulldt.slice(4,6)-1,fulldt.slice(6,8));

		//this function counts the total number for each month
		month = Number(fulldt.slice(4,6));
		for (i=1; i<13; i++) {
			if (typeof total['time']['month']['total'][i] === 'undefined') {
				total['time']['month']['total'][i] = 0;
				total['time']['month']['TOR'][i]=0;
				total['time']['month']['SVR'][i]=0;
				total['time']['month']['PDS TOR'][i]=0;
				total['time']['month']['PDS SVR'][i]=0;
			}
			if (i=== month) {
				total['time']['month']['total'][i] = total['time']['month']['total'][i] + 1;
				if (d['type'] === "TOR") {
					total['time']['month']['TOR'][i] = total['time']['month']['TOR'][i] + 1;
				} else if (d['type'] === "SVR") {
					total['time']['month']['SVR'][i]= total['time']['month']['SVR'][i] + 1;
				} else if (d['type'] === "PDS TOR") {
					total['time']['month']['PDS TOR'][i] = total['time']['month']['PDS TOR'][i] + 1;
				} else if (d['type'] === "PDS SVR") {
					total['time']['month']['PDS SVR'][i] = total['time']['month']['PDS SVR'][i] + 1;
				}
			}
		}
		//this function counts the total number for each year
		year = Number(fulldt.slice(0,4));
		for (i=2000; i<2021; i++) {
			if (typeof total['time']['year']['total'][i] === 'undefined') {
				total['time']['year']['total'][i] = 0;
				total['time']['year']['TOR'][i]=0;
				total['time']['year']['SVR'][i]=0;
				total['time']['year']['PDS TOR'][i]=0;
				total['time']['year']['PDS SVR'][i]=0;
			}
			if (i=== year) {
				total['time']['year']['total'][i] = total['time']['year']['total'][i] + 1;
				if (d['type'] === "TOR") {
					total['time']['year']['TOR'][i] = total['time']['year']['TOR'][i] + 1;
				} else if (d['type'] === "SVR") {
					total['time']['year']['SVR'][i] = total['time']['year']['SVR'][i] + 1;
				} else if (d['type'] === "PDS TOR") {
					total['time']['year']['PDS TOR'][i] = total['time']['year']['PDS TOR'][i] + 1;
				} else if (d['type'] === "PDS SVR") {
					total['time']['year']['PDS SVR'][i] = total['time']['year']['PDS SVR'][i] + 1;
				}
			}
	}

	console.log(total)
	
		//create the finalJSON variable in the correct format
		finalJSON = finalJSON + ',{"watch_num":"'+d["watch_num"]+'","ST":["'+d["ST"]+'"],"FIPS":["'+d["FIPS"]+'"],"issue_dt":"'+d["sel_issue_dt"]+'","CWA":["'+d["CWA"]+'"],"type":["'+d["type"]+'"],"pds":["'+d["pds"]+'"],"expire_dt":["'+d["sel_expire_dt"]+'"],"threats":["'+d["threats"]+'"],"summary":["'+d["summary"]+'"],"areas":["'+d["areas"]+'"]}';

	d["ST"] = d["ST"].toString().replace(/,/g, '/');
	d["FIPS"] = d["FIPS"].toString().replace(/,/g, '/');
	d["CWA"] = d["CWA"].toString().replace(/,/g, '/');
	d["threats"] = d["threats"].toString().replace(/,/g, '...');
	d["summary"] = d["summary"].toString().replace(/,/g, '...');
	d["areas"] = d["areas"].toString().replace(/,/g, '...');
		finalCSV[total["type"][0]] = [d["watch_num"],d["ST"],d["FIPS"],d["sel_issue_dt"],d["CWA"],d["type"],d["pds"],d["sel_expire_dt"],d["threats"],d["summary"],d["areas"]]
	}); //closing brackets for page.data['filtered-sorted'].forEach function

var total1;

var totals_final = {};
	totals_final['data'] = [];

	for (i=0; i<total['type'].length; i++) {
		total1 = {};
		total1.title = totalTitle[i];
		total1.totals = total['type'][i];
		totals_final["data"].push(total1);
	}

	//add totals to the JSON file
	finalJSON = finalJSON + ',{"Totals":{';

	//iterate through months to add months
	for (i=0; i<12; i++) {
		finalJSON = finalJSON + '"'+month_abbrev[i]+'":'+total['time']['month'][i]+',';
	}
	//iterate through the years to add years
	for (i=2000; i<(current_year+1); i++) {
		if (i===current_year) {
		finalJSON = finalJSON + '"'+year_list[i-2000]+'":'+total['time']['year'][i]+'}}';
		} else {
		finalJSON = finalJSON + '"'+year_list[i-2000]+'":'+total['time']['year'][i]+',';		
		}
	}
	mapJSONfinal = JSON.stringify(mapJSON);
//return the html of the details section when you expand a row
function format ( c ) {
    return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr>'+
            '<td>Threats:</td>'+
            '<td>'+c['threats']+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Areas:</td>'+
            '<td>'+c['areas']+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Summary:</td>'+
            '<td>'+c['summary']+'</td>'+
        '</tr>'+
    '</table>';
}

//end of watch section. Start of report section
} else if (page.dataType === "report") {
	consDays=1;
	timesort = "DT";
	total['time']['consecutive']['count'] = [];
	total['time']['consecutive']['endDate'] = [];
	//function used down below to change the order of the data based on the date
	function compare(a,b){
		const ReportA = a.DT;
		const ReportB = b.DT;
		
		var comparison = 0;
		if(ReportA > ReportB){
			comparison = 1;
		}else if(ReportA < ReportB){
			comparison = -1;
		}
		
		return comparison;
	}
	//after filter is run, use compare funtion to sort the data
	page.data['filtered-sorted'] = page.data['filtered'].slice().sort(compare);		

	//set total array equal to zero to start
	total['type'] = [0,0,0,0,0,0];
	total['time']['month']['total'] = [];
	total['time']['month']['T'] = [];
	total['time']['month']['A'] = [];
	total['time']['month']['G'] = [];
	total['time']['month']['W'] = [];
	total['time']['month']['ALLW'] = [];
	total['time']['year']['total'] = [];
	total['time']['year']['T'] = [];
	total['time']['year']['A'] = [];
	total['time']['year']['G'] = [];
	total['time']['year']['W'] = [];
	total['time']['year']['ALLW'] = [];
	total['mag'] = [0,0,0,0,0,0,0];

	//ranges that will be displayed in totals table
	var torRange = ["Total Tornadoes","EF0","EF1","EF2","EF3","EF4","EF5",""];
	var hailRange = ['Total Hail Reports','<1"','1"-1.99"','2"-2.99"','3"-3.99"','4"-4.99"','5+"',""];
	var windRange = ["Total Wind Gust Reports","58-65 mph","66-74 mph","75-85 mph","85-95 mph","95-105 mph","105+ mph",""];
	var windDRange = ["Total Wind Damage Reports","58-65 mph","66-74 mph","75-85 mph","85-95 mph","95-105 mph","105+ mph",""];
	var windAllRange = ["Total Wind Reports","Damage","58-65 mph","66-74 mph","75-85 mph","85-95 mph","95-105 mph","105+ mph"];
	var windAllRangeSD = ["Total Wind Reports","<58 mph","58-65 mph","66-74 mph","75-85 mph","85-95 mph","95-105 mph","105+ mph"];
	var allRange = ["Total Reports","Tornado","Hail","Wind Gust","Wind Damage","All Wind"];

	//populate the totalTitle Array for display in totals table based on what the report type is
	if (page.reportType === "T") {
	totalTitle = torRange;
	magTitle = "Rating";		
	document.getElementById("chartType3").innerHTML = '<span class="ui-button-text">By Magnitude</span>';
	} else if (page.reportType === "A") {
	totalTitle = hailRange;
	magTitle = "Hail Size";
	document.getElementById("chartType3").innerHTML = '<span class="ui-button-text">By Magnitude</span>';
	} else if (page.reportType === "G") {
	totalTitle = windRange;
	magTitle = "Wind Speed";
	document.getElementById("chartType3").innerHTML = '<span class="ui-button-text">By Magnitude</span>';
	} else if (page.reportType === "W") {
	totalTitle = windDRange;
	magTitle = "Wind Speed";
	document.getElementById("chartType3").innerHTML = '<span class="ui-button-text">By Magnitude</span>';
	} else if (page.reportType === "ALL") {
	totalTitle = allRange;
	magTitle = "Type";
	document.getElementById("chartType3").innerHTML = '<span class="ui-button-text">By Type</span>';
	} else if (page.reportType === "ALLW") {
		if (page.reportSource === "stormData") {
		totalTitle = windAllRangeSD;			
		} else {
		totalTitle = windAllRange;
		}
	magTitle = "Wind Speed";
	document.getElementById("chartType3").innerHTML = '<span class="ui-button-text">By Type</span>';
	total['mag'] = [0,0,0,0,0,0,0,0];
	}

	var results = {};
	var results_final = {};
	results_final['data'] = [];
	page.data['filtered-sorted'].forEach(function(d,n){

		results = {};
		var fulldt = d["DT"];
		results['DT'] = d['DT'];
		results['DT_min'] = fulldt.slice(4,12);
		results['DT_dis'] =  "" + fulldt.slice(4,6) +"/"+ fulldt.slice(6,8) +"/"+ fulldt.slice(0,4) +" "+ fulldt.slice(8,12) + "";
		if (page.reportType === "ALL") {
		results['MAGNITUDE'] = d['TYPE'];
			if (d['TYPE'] === "T") {
				results['MAGNITUDE_dis'] = "Tornado";
			} else if (d['TYPE'] === "A") {
				results['MAGNITUDE_dis'] = "Hail";
			} else if (d['TYPE'] === "G") {
				results['MAGNITUDE_dis'] = "Wind Gust";
			} else if (d['TYPE'] === "W") {
				results['MAGNITUDE_dis'] = "Wind Damage";
			}
		} else if (d['MAGNITUDE'] === "UNK" && d['TYPE'] === "W") {
		results['MAGNITUDE'] = -999;
		results['MAGNITUDE_dis'] = "Wind Damage";
		} else if (d['MAGNITUDE'].length === 2) {
		results['MAGNITUDE'] = "0" + d['MAGNITUDE'];	
		results['MAGNITUDE_dis'] = d['MAGNITUDE'];		
		} else if (d['MAGNITUDE'] === "EFU") {
		results['MAGNITUDE'] = -1;		
		results['MAGNITUDE_dis'] = d['MAGNITUDE'];	
		} else if (page.reportType === "T" && page.reportSource === "stormData") {
		var magString = d['MAGNITUDE']
		results['MAGNITUDE'] = magString.slice(2,3);		
		results['MAGNITUDE_dis'] = d['MAGNITUDE'];	
		} else {
		results['MAGNITUDE'] =  d['MAGNITUDE'];	
		results['MAGNITUDE_dis'] = d['MAGNITUDE'];			
		}
		results['LOCATION'] = d['LOCATION'];
		results['COUNTY'] = d['COUNTY'].toString();
		results['ST'] = d['ST'].toString();
		results['CWA'] = d['CWA'].toString();
		results['INJURY'] = d['INJURY'].toString();
		results['FATALITIES'] = d['FATALITIES'].toString();
		results_final["data"].push(results);

		//this is how totals are calculated. Add one each time you iterate through.
			total['type'][0] = total['type'][0] +1;
			total['mag'][0] = total['mag'][0] +1;

		//add up the total number at each threshold by iterating through.
		//start with tornado
	if (page.reportType === "T") {
		if (d['MAGNITUDE']==="EF0" || d['MAGNITUDE']==="F0") {
			total['mag'][1]=total['mag'][1]+1;
		}	else if (d['MAGNITUDE']==="EF1" || d['MAGNITUDE']==="F1") {
			total['mag'][2]=total['mag'][2]+1;
		}	else if (d['MAGNITUDE']==="EF2" || d['MAGNITUDE']==="F2") {
			total['mag'][3]=total['mag'][3]+1;
		}	else if (d['MAGNITUDE']==="EF3" || d['MAGNITUDE']==="F3") {
			total['mag'][4]=total['mag'][4]+1;
		}	else if (d['MAGNITUDE']==="EF4" || d['MAGNITUDE']==="F4") {
			total['mag'][5]=total['mag'][5]+1;
		}	else if (d['MAGNITUDE']==="EF5" || d['MAGNITUDE']==="F5") {
			total['mag'][6]=total['mag'][6]+1;
		}
		//now add up wind gusts at each threshold
	} else if (page.reportType === "G") {
		if (d['MAGNITUDE']>57 & d['MAGNITUDE']<65) {
			total['mag'][1]=total['mag'][1]+1;
		}	else if (d['MAGNITUDE']>64 & d['MAGNITUDE']<75) {
			total['mag'][2]=total['mag'][2]+1;
		}	else if (d['MAGNITUDE']>74 & d['MAGNITUDE']<85) {
			total['mag'][3]=total['mag'][3]+1;
		}	else if (d['MAGNITUDE']>84 & d['MAGNITUDE']<95) {
			total['mag'][4]=total['mag'][4]+1;
		}	else if (d['MAGNITUDE']>94 & d['MAGNITUDE']<105) {
			total['mag'][5]=total['mag'][5]+1;
		}	else if (d['MAGNITUDE']>104 & d['MAGNITUDE']<200) {
			total['mag'][6]=total['mag'][6]+1;
		}
		//now add up hail reports at each threshold
	} else if (page.reportType === "A") {
		if (d['MAGNITUDE']>0 & d['MAGNITUDE']<100) {
			total['mag'][1]=total['mag'][1]+1;
		}	else if (d['MAGNITUDE']>99 & d['MAGNITUDE']<200) {
			total['mag'][2]=total['mag'][2]+1;
		}	else if (d['MAGNITUDE']>199 & d['MAGNITUDE']<300) {
			total['mag'][3]=total['mag'][3]+1;
		}	else if (d['MAGNITUDE']>299 & d['MAGNITUDE']<400) {
			total['mag'][4]=total['mag'][4]+1;
		}	else if (d['MAGNITUDE']>399 & d['MAGNITUDE']<500) {
			total['mag'][5]=total['mag'][5]+1;
		}	else if (d['MAGNITUDE']>499 & d['MAGNITUDE']<1000) {
			total['mag'][6]=total['mag'][6]+1;
		}
	} else if (page.reportType === "ALLW") {
		if (d['MAGNITUDE']>57 & d['MAGNITUDE']<65) {
			total['mag'][2]=total['mag'][2]+1;
		}	else if (d['MAGNITUDE']>64 & d['MAGNITUDE']<75) {
			total['mag'][3]=total['mag'][3]+1;
		}	else if (d['MAGNITUDE']>74 & d['MAGNITUDE']<85) {
			total['mag'][4]=total['mag'][4]+1;
		}	else if (d['MAGNITUDE']>84 & d['MAGNITUDE']<95) {
			total['mag'][5]=total['mag'][5]+1;
		}	else if (d['MAGNITUDE']>94 & d['MAGNITUDE']<105) {
			total['mag'][6]=total['mag'][6]+1;
		}	else if (d['MAGNITUDE']>104 & d['MAGNITUDE']<200) {
			total['mag'][7]=total['mag'][7]+1;
		} else {
			total['mag'][1]=total['mag'][1]+1;
		}
	}
	if (d['TYPE'] === "T") {	
			total['type'][1]=total['type'][1]+1;		
	} 	else if (d['TYPE'] === "A") {
			total['type'][2]=total['type'][2]+1;
	} 	else if (d['TYPE'] === "G") {
			total['type'][3]=total['type'][3]+1;
			total['type'][5]=total['type'][5]+1;
	}  	else if (d['TYPE'] === "W") {
			total['type'][4]=total['type'][4]+1;
			total['type'][5]=total['type'][5]+1;
	} 

		//this function counts the total number for each month
		var month = Number(fulldt.slice(4,6));
		for (i=1; i<13; i++) {
			if (typeof total['time']['month']['total'][i] === 'undefined') {
				total['time']['month']['total'][i] = 0;
				total['time']['month']['T'][i]=0;
				total['time']['month']['A'][i]=0;
				total['time']['month']['W'][i]=0;
				total['time']['month']['ALLW'][i]=0;
				total['time']['month']['G'][i]=0;
			}
			if (i=== month) {
				total['time']['month']['total'][i] = total['time']['month']['total'][i] + 1;
				if (d['TYPE'] === "T") {
					total['time']['month']['T'][i] = total['time']['month']['T'][i] + 1;
				} else if (d['TYPE'] === "A") {
					total['time']['month']['A'][i]= total['time']['month']['A'][i] + 1;
				} else if (d['TYPE'] === "W") {
					total['time']['month']['W'][i] = total['time']['month']['W'][i] + 1;
					total['time']['month']['ALLW'][i] = total['time']['month']['ALLW'][i] + 1;
				} else if (d['TYPE'] === "G") {
					total['time']['month']['G'][i] = total['time']['month']['G'][i] + 1;
					total['time']['month']['ALLW'][i] = total['time']['month']['ALLW'][i] + 1;
				}
			}
		}	

		//this function counts the total number for each year
		year = Number(fulldt.slice(0,4));
		for (i=2000; i<2021; i++) {
			if (typeof total['time']['year']['total'][i] === 'undefined') {
				total['time']['year']['total'][i] = 0;
				total['time']['year']['T'][i]=0;
				total['time']['year']['A'][i]=0;
				total['time']['year']['W'][i]=0;
				total['time']['year']['ALLW'][i]=0;
				total['time']['year']['G'][i]=0;
			}
			if (i=== year) {
				total['time']['year']['total'][i] = total['time']['year']['total'][i] + 1;
				if (d['TYPE'] === "T") {
					total['time']['year']['T'][i] = total['time']['year']['T'][i] + 1;
				} else if (d['TYPE'] === "A") {
					total['time']['year']['A'][i]= total['time']['year']['A'][i] + 1;
				} else if (d['TYPE'] === "W") {
					total['time']['year']['W'][i] = total['time']['year']['W'][i] + 1;
					total['time']['year']['ALLW'][i] = total['time']['year']['ALLW'][i] + 1;
				} else if (d['TYPE'] === "G") {
					total['time']['year']['G'][i] = total['time']['year']['G'][i] + 1;
					total['time']['year']['ALLW'][i] = total['time']['year']['ALLW'][i] + 1;
				}
			}
		}

//this function counts the number of consecutive days of each
		nextDay.setTime(prevDay.getTime());
		nextDay.setDate(prevDay.getDate()+1);

		var nextCompare = "" + ("0" + (nextDay.getMonth() + 1)).slice(-2) + "" + ("0" + (nextDay.getDate())).slice(-2) + "";
		var prevCompare = "" + ("0" + (prevDay.getMonth() + 1)).slice(-2) + "" + ("0" + (prevDay.getDate())).slice(-2) + "";
		var dateCompare = results['DT_min'].slice(0,4);
		console.log(dateCompare)
		console.log(nextCompare)
		if (dateCompare === nextCompare) {
			consDays = consDays +1;
		} else if (dateCompare === prevCompare) {
		} else if (consDays >1){
			total['time']['consecutive']['count'].push(consDays);
			total['time']['consecutive']['endDate'].push(prevDay);
			consDays = 1;
		} else {
			consDays = 1;
		}


		prevDay = new Date(fulldt.slice(0,4),fulldt.slice(4,6)-1,fulldt.slice(6,8));

		//create the finalJSON variable in the correct format
		finalJSON = finalJSON + ',{"TYPE":"'+d["TYPE"]+'","ST":["'+d["ST"]+'"],"FIPS":["'+d["FIPS"]+'"],"DATE":"'+d["DT"]+'","CWA":["'+d["CWA"]+'"],"LOCATION":"'+d["LOCATION"]+'","MAGNITUDE":"'+d["MAGNITUDE"]+'","INJURIES":"'+d["INJURY"]+'","FATALITIES":"'+d["FATALITIES"]+'","COUNTY":"'+d["COUNTY"]+'"}';
	
		d["ST"] = d["ST"].toString().replace(/,/g, '/');
		d["FIPS"] = d["FIPS"].toString().replace(/,/g, '/');
		d["CWA"] = d["CWA"].toString().replace(/,/g, '/');
		d["LOCATION"] = d["LOCATION"].toString().replace(/,/g, '...');
		finalCSV[total["type"][0]] = [d["TYPE"],d["ST"],d["FIPS"],d["DT"],d["CWA"],d["LOCATION"],d["MAGNITUDE"],d["INJURY"],d["FATALITIES"],d["COUNTY"]];

	});	//closing brackets for page.data['filtered-sorted'].forEach function

var total1;

var totals_final = {};
	totals_final['data'] = [];

	if (page.reportType != "ALL") {	
	for (i=0; i<total['mag'].length; i++) {
		total1 = {};
		total1.title = totalTitle[i];	
		total1.totals = total['mag'][i];
		totals_final["data"].push(total1);
	}
} else {
		for (i=0; i<total['type'].length; i++) {
		total1 = {};
		total1.title = totalTitle[i];	
		total1.totals = total['type'][i];
		totals_final["data"].push(total1);
	}
}

	//add totals to the JSON file
	finalJSON = finalJSON + ',{"Totals":{';
	//iterate through months to add months
	for (i=0; i<12; i++) {
		finalJSON = finalJSON + '"'+month_abbrev[i]+'":{"All":'+total['time']['month']['total'][i+1]+',"T":'+total['time']['month']['T'][i+1]+',"A":'+total['time']['month']['A'][i+1]+',"W":'+total['time']['month']['W'][i+1]+',"G":'+total['time']['month']['G'][i+1]+',"ALLW":'+total['time']['month']['ALLW'][i+1]+'},';
	}
	//iterate through the years to add years
	for (i=2000; i<(current_year+1); i++) {
		if (i===current_year) {
		finalJSON = finalJSON + '"'+year_list[i-2000]+'":{"All":'+total['time']['year']['total'][i]+',"T":'+total['time']['year']['T'][i]+',"A":'+total['time']['year']['A'][i]+',"W":'+total['time']['year']['W'][i]+',"G":'+total['time']['year']['G'][i]+',"ALLW":'+total['time']['year']['ALLW'][i]+'}}}';
		} else {
		finalJSON = finalJSON + '"'+year_list[i-2000]+'":{"All":'+total['time']['year']['total'][i]+',"T":'+total['time']['year']['T'][i]+',"A":'+total['time']['year']['A'][i]+',"W":'+total['time']['year']['W'][i]+',"G":'+total['time']['year']['G'][i]+',"ALLW":'+total['time']['year']['ALLW'][i]+'},';		
		}
	}

//return the html of the details section when you expand a row
function format ( c ) {
    return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr>'+
            '<td>No Additional Data Available at this time</td>'+
        '</tr>'+
    '</table>';
}

} //end of reports else section

	//clear out data-table
	jQuery("#data-table").empty();
	//clear out total-table
	jQuery("#total-table").empty();
	//clear out consecutive-table
	jQuery("#consecutive-table").empty();
	//add html to total-table
	jQuery("#total-table").html('<table id="total_table" class="display"></table><br>');
	//put variable tot into tot-table
	jQuery("#consecutive-table").html('<table id="consecutive_table" class="display"></table><br>');

if (page.dataType === "watch") {
	jQuery("#data-table").html('<table id="watch_table" class="display" width="100%"></table>');
	//turn results table into DataTable
var table = function() {
	jQuery('#watch_table').DataTable({
		data: results_final['data'],
		columns: [

			{	data: ""
			},
			{	data: {
					_: "dt_dis",
					sort: timesort
				},
				title: "Date/Time (CST)"
			},
			{	data: "type",
				title: "Watch Type"
			},
			{	data: "num",
				title: "Watch Number"
			},
			{	data: "st",
				title: "States in Watch"
			},
			{	data: "cwa",
				title: "CWAs in Watch"
			}
		],
		"lengthMenu": [[10,25, 50, 100, -1], [10, 25, 50, 100, "All"]], //different types of lengths available (-1 equals all)
		"pageLength": 50, //default page length
		"columnDefs": [		
			{	className:"dt-center", targets: "_all"
			},
			{ type: 'natural', targets: 1 },
			{	"targets": 0,
				"orderable": false,
				"data": null,
      			"defaultContent": '<i class = "fa fa-plus-square details-control" orderable = "false" title = "Click to see additional'+page.dataType+' text"></i>' 
      		},
			{	"targets": [4, 5],
				"orderable": false
      		}
		]		
	}); //end of variable table
}
table();

var tableTotal = jQuery('#total_table').DataTable({
		data: totals_final['data'],
		columns: [
			{	data: "title",
				title: "Watch Type"
			},
			{	data: "totals",
				title: "Totals"
			}
		],
        "paging":   false,
        "info": false,
  		"searching": false,
        "order": [[ 1, "desc" ]],
        "columnDefs": [
			{	"className":"dt-center", targets: "_all" }
		]
});

jQuery("#firstlast").click(function() {
        	if (jQuery(this).is(":checked")) {
				timesort = "dt_min"
			} else {
				timesort = "dt"
			}
			jQuery('#watch_table').DataTable().destroy();
			table();
		});

} else if (page.dataType === "report") {
	jQuery("#data-table").html('<table id="report_table" class="display" width="100%"></table>');
	//turn results table into DataTable
var table = function() { 
	jQuery('#report_table').DataTable({
		data: results_final['data'],
		columns: [
			{	data: ""
			},
			{	data: {
					_: "DT_dis",
					sort: timesort
				},
				title: "Date/Time (CST)"
			},
			{	data: {
					_: "MAGNITUDE_dis",
					sort: "MAGNITUDE"
				},
				title: magTitle
			},
			{	data: "LOCATION",
				title: "Location"
			},
			{	data: "COUNTY",
				title: "County"
			},
			{	data: "ST",
				title: "State"
			},
			{	data: "CWA",
				title: "CWA"
			},
			{	data: "INJURY",
				title: "Injuries"
			},
			{	data: "FATALITIES",
				title: "Fatalities"
			}
		],
		"lengthMenu": [[10,25, 50, 100, -1], [10, 25, 50, 100, "All"]], //different types of lengths available (-1 equals all)
		"pageLength": 50, //default page length
		"columnDefs": [		
			{	"className":"dt-center", targets: "_all"
			},	
			{	"targets": 0,
				"orderable": false,
				"data": null,
      			"defaultContent": '<i class = "fa fa-plus-square details-control" orderable = "false" title = "Click to see additional'+page.dataType+' text"></i>' 
      		},			
      		{	"targets": [3, 4, 5, 6],
				"orderable": false
      		}
		]		
	}); //end of variable table
}
table();
    // Add event listener for opening and closing details
    jQuery('i.details-control').on('click', function () {
        var tr = jQuery(this).closest('tr');
        var row = table.row(tr);
 
        if ( row.child.isShown() ) {
            jQuery(this).addClass("fa-plus-square");
			jQuery(this).removeClass("fa-minus-square");
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            jQuery(this).removeClass("fa-plus-square");
			jQuery(this).addClass("fa-minus-square");
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );

var tableTotal = jQuery('#total_table').DataTable({
		data: totals_final['data'],
		columns: [
			{	data: "title",
				title: "Watch Type"
			},
			{	data: "totals",
				title: "Totals"
			}
		],
        "paging":   false,
        "info": false,
  		"searching": false,
        "order": [[ 1, "desc" ]],
        "columnDefs": [
			{	"className":"dt-center", targets: "_all" }
		]
});

jQuery("#firstlast").click(function() {
        	if (jQuery(this).is(":checked")) {
				timesort = "DT_min"
			} else {
				timesort = "DT"
			}
			jQuery('#report_table').DataTable().destroy();
			table();
		});
}
var consecutive_final = {};
	consecutive_final['data'] = [];

		for (i=0; i<total['time']['consecutive']['count'].length; i++) {
		total1 = {};
		total1.cons_days = total['time']['consecutive']['count'][i];	
		total1.end = total['time']['consecutive']['endDate'][i];
		consecutive_final['data'].push(total1);
		}
		console.log(consecutive_final)
var tableConsecutive = jQuery('#consecutive_table').DataTable({
		data: consecutive_final['data'],
		columns: [
			{	data: "cons_days",
				title: "Consecutive Days"
			},
			{	data: "end",
				title: "End Date"
			}
		],
  		"searching": false,
        "order": [[ 0, "desc" ]],
		"pageLength": 10, //default page length
        "columnDefs": [
			{	"className":"dt-center", targets: "_all" },
			{	"width":"150px", targets: 0 }
		]
});
}
//this function makes the JSON file which can be downloaded by the user
function makeJSON(page) {
			finalJSON = finalJSON.slice(1);
			finalJSON = "["+finalJSON+"]";
			var JSONfile = document.createElement('a');
			JSONfile.href = 'data:attachment/JSON,' + encodeURI(finalJSON);
			JSONfile.target = '_blank';
			if (page.dataType === "watch") {
			JSONfile.download = ''+page.watchType+ '_' +page.dataType+ '_' +page.filters['date'][0]+ '_'+page.filters['date'][1]+ '.json';
			} else if (page.dataType === "report") {
			JSONfile.download = ''+page.reportType+ '_' +page.dataType+ '_' +page.filters['date'][0]+ '_'+page.filters['date'][1]+ '.json';				
			}
			JSONfile.click();
		}

function makeCSV(page) {
	console.log(finalCSV)
		if (page.dataType === "watch") {
			var csv = 'watch_num,ST,FIPS,issue_dt,CWA,type,pds,expire_dt,threats,summary,areas\n';
		} else if (page.dataType === "report") {	
			var csv = 'TYPE,ST,FIPS,DATE,CWA,LOCATION,MAGNITUDE,INJURIES,FATALITIES,COUNTY\n';		
		}
    		finalCSV.forEach(function(row) {
            csv += row.join(',');
            csv += "\n";
        	});
			var CSVfile = document.createElement('a');		
			CSVfile.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
			CSVfile.target = '_blank';
			if (page.dataType === "watch") {
			CSVfile.download = ''+page.watchType+ '_' +page.dataType+ '_' +page.filters['date'][0]+ '_'+page.filters['date'][1]+ '.csv';
			} else if (page.dataType === "report") {
			CSVfile.download = ''+page.reportType+ '_' +page.dataType+ '_' +page.filters['date'][0]+ '_'+page.filters['date'][1]+ '.csv';				
			}
			CSVfile.click();
		}

