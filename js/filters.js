var startDate;
var endDate;
function filterStates(page){
	//var astr = '';
	//astr = astr + "<div>";
	//astr = astr + "<select data-placeholder = 'Select State(s)...' class = '"+type+"-states-select' multiple style = 'width:350px;'>";

	var bstr = '';
	bstr = bstr + "<option value = 'AL'>Alabama (AL)</option>";
	//bstr = bstr + "<option value = 'AK'>Alaska (AK)</option>";
	bstr = bstr + "<option value = 'AZ'>Arizona (AZ)</option>";
	bstr = bstr + "<option value = 'AR'>Arkansas (AR)</option>";
	bstr = bstr + "<option value = 'CA'>California (CA)</option>";
	bstr = bstr + "<option value = 'CO'>Colorado (CO)</option>";
	bstr = bstr + "<option value = 'CT'>Connecticut (CT)</option>";
	bstr = bstr + "<option value = 'DE'>Delaware (DE)</option>";
	bstr = bstr + "<option value = 'FL'>Florida (FL)</option>";
	bstr = bstr + "<option value = 'GA'>Georgia (GA)</option>";
	//bstr = bstr + "<option value = 'HI'>Hawaii (HI)</option>";
	bstr = bstr + "<option value = 'ID'>Idaho (ID)</option>";
	bstr = bstr + "<option value = 'IL'>Illinois (IL)</option>";
	bstr = bstr + "<option value = 'IN'>Indiana (IN)</option>";
	bstr = bstr + "<option value = 'IA'>Iowa (IA)</option>";
	bstr = bstr + "<option value = 'KS'>Kansas (KS)</option>";
	bstr = bstr + "<option value = 'KY'>Kentucky (KY)</option>";
	bstr = bstr + "<option value = 'LA'>Louisiana (LA)</option>";
	bstr = bstr + "<option value = 'ME'>Maine (ME)</option>";
	bstr = bstr + "<option value = 'MD'>Maryland (MD)</option>";
	bstr = bstr + "<option value = 'MA'>Massachusetts (MA)</option>";
	bstr = bstr + "<option value = 'MI'>Michigan (MI)</option>";
	bstr = bstr + "<option value = 'MN'>Minnesota (MN)</option>";
	bstr = bstr + "<option value = 'MS'>Mississippi (MS)</option>";
	bstr = bstr + "<option value = 'MO'>Missouri (MO)</option>";
	bstr = bstr + "<option value = 'MT'>Montana (MT)</option>";
	bstr = bstr + "<option value = 'NE'>Nebraska (NE)</option>";
	bstr = bstr + "<option value = 'NV'>Nevada (NV)</option>";
	bstr = bstr + "<option value = 'NH'>New Hampshire (NH)</option>";
	bstr = bstr + "<option value = 'NJ'>New Jersey (NJ)</option>";
	bstr = bstr + "<option value = 'NM'>New Mexico (NM)</option>";
	bstr = bstr + "<option value = 'NY'>New York (NY)</option>";
	bstr = bstr + "<option value = 'NC'>North Carolina (NC)</option>";
	bstr = bstr + "<option value = 'ND'>North Dakota (ND)</option>";
	bstr = bstr + "<option value = 'OH'>Ohio (OH)</option>";
	bstr = bstr + "<option value = 'OK'>Oklahoma (OK)</option>";
	bstr = bstr + "<option value = 'OR'>Oregon (OR)</option>";
	bstr = bstr + "<option value = 'PA'>Pennsylvania (PA)</option>";
	bstr = bstr + "<option value = 'RI'>Rhode Island (RI)</option>";
	bstr = bstr + "<option value = 'SC'>South Carolina (SC)</option>";
	bstr = bstr + "<option value = 'SD'>South Dakota (SD)</option>";
	bstr = bstr + "<option value = 'TN'>Tennessee (TN)</option>";
	bstr = bstr + "<option value = 'TX'>Texas (TX)</option>";
	bstr = bstr + "<option value = 'UT'>Utah (UT)</option>";
	bstr = bstr + "<option value = 'VT'>Vermont (VT)</option>";
	bstr = bstr + "<option value = 'VA'>Virginia (VA)</option>";
	bstr = bstr + "<option value = 'WA'>Washington (WA)</option>";
	bstr = bstr + "<option value = 'WV'>West Virginia (WV)</option>";
	bstr = bstr + "<option value = 'WI'>Wisconsin (WI)</option>";
	bstr = bstr + "<option value = 'WY'>Wyoming (WY)</option>";

	var typeKeys = new Array("watch", "report", "outlook");

	typeKeys.forEach(function(type, n){
		var astr = '';
		astr = astr + "<div>";
		astr = astr + "<select data-placeholder = 'Select State(s)...' class = '"+type+"-states-select' multiple style = 'width:350px;'>";
		var str = astr + bstr
		str = str + "</select>"
		str = str + "</div>";

		jQuery("#" + type + "-state-filter").append(str);
		jQuery("." + type +"-states-select").chosen({
			width: "350px",
			search_contains: true
		}).change(function(){
			getFilters(page);
		});
	});
}

function filterCWAs(page){
	var bstr = '';
	bstr = bstr +"<option value = 'ABR'>Aberdeen, SD (ABR)</option>";
	bstr = bstr +"<option value = 'ALY'>Albany, NY (ALY)</option>";
	bstr = bstr +"<option value = 'ABQ'>Albuquerque, NM (ABQ)</option>";
	bstr = bstr +"<option value = 'AMA'>Amarillo, TX (AMA)</option>";
	//bstr = bstr +"<option value = 'AFC'>Anchorage, AK (AFC)</option>";
	//bstr = bstr +"<option value = 'ANC'>Anchorage, AK (ANC)</option>";
	bstr = bstr +"<option value = 'FFC'>Atlanta, GA (FFC)</option>";
	bstr = bstr +"<option value = 'EWX'>Austin/San Antonio, TX (EWX)</option>";
	bstr = bstr +"<option value = 'BYZ'>Billings, MT (BYZ)</option>";
	bstr = bstr +"<option value = 'BGM'>Binghamton, NY (BGM)</option>";
	bstr = bstr +"<option value = 'BMX'>Birmingham, AL (BMX)</option>";
	bstr = bstr +"<option value = 'BIS'>Bismarck, ND (BIS)</option>";
	bstr = bstr +"<option value = 'RNK'>Blacksburg, VA (RNK)</option>";
	bstr = bstr +"<option value = 'BOI'>Boise, ID	(BOI)</option>";
	bstr = bstr +"<option value = 'BOX'>Boston, MA (BOX)</option>";
	bstr = bstr +"<option value = 'BRO'>Brownsville, TX (BRO)</option>";
	bstr = bstr +"<option value = 'BUF'>Buffalo, NY (BUF)</option>";
	bstr = bstr +"<option value = 'BTV'>Burlington, VT (BTV)</option>";
	bstr = bstr +"<option value = 'CAR'>Caribou, ME (CAR)</option>";
	bstr = bstr +"<option value = 'CHS'>Charleston, SC (CHS)</option>";
	bstr = bstr +"<option value = 'RLX'>Charleston, WV (RLX)</option>";
	bstr = bstr +"<option value = 'CYS'>Cheyenne, WY (CYS)</option>";
	bstr = bstr +"<option value = 'LOT'>Chicago, IL (LOT)</option>";
	bstr = bstr +"<option value = 'CLE'>Cleveland, OH (CLE)</option>";
	bstr = bstr +"<option value = 'CAE'>Columbia, SC (CAE)</option>";
	bstr = bstr +"<option value = 'CRP'>Corpus Christi, TX (CRP)</option>";
	bstr = bstr +"<option value = 'FWD'>Dallas/Fort Worth, TX (FWD)</option>";
	bstr = bstr +"<option value = 'BOU'>Denver/Boulder, CO (BOU)</option>";
	bstr = bstr +"<option value = 'DMX'>Des Moines, IA (DMX)</option>";
	bstr = bstr +"<option value = 'DTX'>Detroit, MI (DTX)</option>";
	bstr = bstr +"<option value = 'DDC'>Dodge City, KS (DDC)</option>";
	bstr = bstr +"<option value = 'DLH'>Duluth, MN (DLH)</option>";
	bstr = bstr +"<option value = 'EPZ'>El Paso, TX (EPZ)</option>";
	bstr = bstr +"<option value = 'LKN'>Elko, NV (LKN)</option>";
	bstr = bstr +"<option value = 'EKA'>Eureka, CA (EKA)</option>";
	//bstr = bstr +"<option value = 'AFG'>Fairbanks, AK (AFG)</option>";
	bstr = bstr +"<option value = 'FGZ'>Flagstaff, AZ (FGZ)</option>";
	bstr = bstr +"<option value = 'APX'>Gaylord, MI (APX)</option>";
	bstr = bstr +"<option value = 'GGW'>Glasgow, MT (GGW)</option>";
	bstr = bstr +"<option value = 'GLD'>Goodland, KS (GLD)</option>";
	bstr = bstr +"<option value = 'FGF'>Grand Forks, ND (FGF)</option>";
	bstr = bstr +"<option value = 'GJT'>Grand Junction, CO (GJT)</option>";
	bstr = bstr +"<option value = 'GRR'>Grand Rapids, MI (GRR)</option>";
	bstr = bstr +"<option value = 'GYX'>Gray, ME (GYX)</option>";
	bstr = bstr +"<option value = 'TFX'>Great Falls, MT (TFX)</option>";
	bstr = bstr +"<option value = 'GRB'>Green Bay, WI (GRB)</option>";
	bstr = bstr +"<option value = 'GSP'>Greer, SC	(GSP)</option>";
	//bstr = bstr +"<option value = 'GUM'>Guam, GU (GUM)</option>";
	bstr = bstr +"<option value = 'HNX'>Hanford, CA (HNX)</option>";
	bstr = bstr +"<option value = 'GID'>Hastings, NE (GID)</option>";
	//bstr = bstr +"<option value = 'HFO'>Honolulu, HI (HFO)</option>";
	bstr = bstr +"<option value = 'HGX'>Houston, TX (HGX)</option>";
	bstr = bstr +"<option value = 'HUN'>Huntsville, AL (HUN)</option>";
	bstr = bstr +"<option value = 'IND'>Indianapolis, IN (IND)</option>";
	bstr = bstr +"<option value = 'JAN'>Jackson, MS (JAN)</option>";
	bstr = bstr +"<option value = 'JKL'>Jackson, KY (JKL)</option>";
	bstr = bstr +"<option value = 'JAX'>Jacksonville, FL (JAX)</option>";
	//bstr = bstr +"<option value = 'AJK'>Juneau, AK (AJK)</option>";
	bstr = bstr +"<option value = 'EAX'>Kansas City, MO (EAX)</option>";
	bstr = bstr +"<option value = 'KEY'>Key West, FL (KEY)</option>";
	bstr = bstr +"<option value = 'ARX'>La Crosse, WI (ARX)</option>";
	bstr = bstr +"<option value = 'LCH'>Lake Charles, LA (LCH)</option>";
	bstr = bstr +"<option value = 'VEF'>Las Vegas, NV (VEF)</option>";
	bstr = bstr +"<option value = 'ILX'>Lincoln, IL (ILX)</option>";
	bstr = bstr +"<option value = 'LZK'>Little Rock, AR (LZK)</option>";
	bstr = bstr +"<option value = 'LOX'>Los Angeles, CA (LOX)</option>";
	bstr = bstr +"<option value = 'LMK'>Louisville, KY (LMK)</option>";
	bstr = bstr +"<option value = 'LUB'>Lubbock, TX (LUB)</option>";
	bstr = bstr +"<option value = 'MQT'>Marquette, MI (MQT)</option>";
	bstr = bstr +"<option value = 'MFR'>Medford, OR (MFR)</option>";
	bstr = bstr +"<option value = 'MLB'>Melbourne, FL (MLB)</option>";
	bstr = bstr +"<option value = 'MEG'>Memphis, TN (MEG)</option>";
	bstr = bstr +"<option value = 'MFL'>Miami, FL (MFL)</option>";
	bstr = bstr +"<option value = 'MAF'>Midland/Odessa, TX (MAF)</option>";
	bstr = bstr +"<option value = 'MKX'>Milwaukee, WI (MKX)</option>";
	bstr = bstr +"<option value = 'MSO'>Missoula, MT (MSO)</option>";
	bstr = bstr +"<option value = 'MOB'>Mobile, AL (MOB)</option>";
	bstr = bstr +"<option value = 'MHX'>Morehead City, NC (MHX)</option>";
	bstr = bstr +"<option value = 'MRX'>Morristown, TN (MRX)</option>";
	bstr = bstr +"<option value = 'PHI'>Mount Holly, NJ (PHI)</option>";
	bstr = bstr +"<option value = 'OHX'>Nashville, TN (OHX)</option>";
	bstr = bstr +"<option value = 'LIX'>New Orleans, LA (LIX)</option>";
	bstr = bstr +"<option value = 'OKX'>New York City, NY (OKX)</option>";
	bstr = bstr +"<option value = 'OUN'>Norman, OK (OUN)</option>";
	bstr = bstr +"<option value = 'LBF'>North Platte, NE (LBF)</option>";
	bstr = bstr +"<option value = 'IWX'>Nrn. Indiana, IN (IWX)</option>";
	bstr = bstr +"<option value = 'OAX'>Omaha, NE (OAX)</option>";
	bstr = bstr +"<option value = 'PAH'>Paducah, KY (PAH)</option>";
	bstr = bstr +"<option value = 'PDT'>Pendleton, OR (PDT)</option>";
	bstr = bstr +"<option value = 'PSR'>Phoenix, AZ (PSR)</option>";
	bstr = bstr +"<option value = 'PBZ'>Pittsburgh, PA (PBZ)</option>";
	bstr = bstr +"<option value = 'PIH'>Pocatello, ID (PIH)</option>";
	bstr = bstr +"<option value = 'PQR'>Portland, OR (PQR)</option>";
	bstr = bstr +"<option value = 'PUB'>Pueblo, CO (PUB)</option>";
	bstr = bstr +"<option value = 'DVN'>Quad Cities, IA (DVN)</option>";
	bstr = bstr +"<option value = 'RAH'>Raleigh, NC (RAH)</option>";
	bstr = bstr +"<option value = 'UNR'>Rapid City, SD (UNR)</option>";
	bstr = bstr +"<option value = 'REV'>Reno, NV (REV)</option>";
	bstr = bstr +"<option value = 'RIW'>Riverton, WY (RIW)</option>";
	bstr = bstr +"<option value = 'STO'>Sacramento, CA (STO)</option>";
	bstr = bstr +"<option value = 'SLC'>Salt Lake City, UT (SLC)</option>";
	bstr = bstr +"<option value = 'SJT'>San Angelo, TX (SJT)</option>";
	bstr = bstr +"<option value = 'SGX'>San Diego, CA (SGX)</option>";
	bstr = bstr +"<option value = 'MTR'>San Francisco, CA (MTR)</option>";
	//bstr = bstr +"<option value = 'SJU'>San Juan, PR (SJU)</option>";
	bstr = bstr +"<option value = 'SEW'>Seattle, WA (SEW)</option>";
	bstr = bstr +"<option value = 'SHV'>Shreveport, LA (SHV)</option>";
	bstr = bstr +"<option value = 'FSD'>Sioux Falls, SD (FSD)</option>";
	bstr = bstr +"<option value = 'OTX'>Spokane, WA (OTX)</option>";
	bstr = bstr +"<option value = 'SGF'>Springfield, MO (SGF)</option>";
	bstr = bstr +"<option value = 'LSX'>St. Louis, MO (LSX)</option>";
	bstr = bstr +"<option value = 'CTP'>State College, PA (CTP)</option>";
	bstr = bstr +"<option value = 'LWX'>Sterling, VA (LWX)</option>";
	bstr = bstr +"<option value = 'TAE'>Tallahassee, FL (TAE)</option>";
	bstr = bstr +"<option value = 'TBW'>Tampa Bay Area, FL (TBW)</option>";
	bstr = bstr +"<option value = 'TOP'>Topeka, KS (TOP)</option>";
	bstr = bstr +"<option value = 'TWC'>Tucson, AZ (TWC)</option>";
	bstr = bstr +"<option value = 'TSA'>Tulsa, OK (TSA)</option>";
	bstr = bstr +"<option value = 'MPX'>Twin Cities, MN (MPX)</option>";
	bstr = bstr +"<option value = 'AKQ'>Wakefield, VA (AKQ)</option>";
	bstr = bstr +"<option value = 'ICT'>Wichita, KS (ICT)</option>";
	bstr = bstr +"<option value = 'ILM'>Wilmington, NC (ILM)</option>";
	bstr = bstr +"<option value = 'ILN'>Wilmington, OH (ILN)</option>";

	var typeKeys = new Array("watch", "report", "outlook");
	typeKeys.forEach(function(type, n){
		var astr = '';
		astr = astr + "<div>";
		astr = astr + "<select name = '"+type+"-cwa-select' data-placeholder = 'Select CWA(s)...' class = '"+type+"-cwa-select' multiple style = 'width:350px;'>";

		var str = astr + bstr;
		str = str + "</select>"
		str = str + "</div>";

		jQuery("#" + type + "-cwa-filter").append(str);
		jQuery("." + type +"-cwa-select").chosen({
			width: "350px",
			search_contains: true
		}).change(function(e, params){
			getFilters(page);
		});
	});
}

function filterFIPSandZIP(page){
	page.ZIPS = new Array();
	page.FIPS = new Array();
	jQuery.ajax({
		dataType: "json",
		url: "/bentley/dev/page/dataquery/src/zip2fips.json"
	}).done(function(data){
		for(var p in data){
			if(data.hasOwnProperty(p)){
				if(! page.ZIPS.includes(p)){page.ZIPS.push(p);}
				if(! page.FIPS.includes(data[p])){page.FIPS.push(data[p]);}
			}
		}
		var bstr = '';
		//bstr = bstr + "<div style = 'width:150px;'>";
		//bstr = bstr + "<select name = 'all-fips-select' data-placeholder = 'Select FIPS Code(s)...' class = 'all-fips-select' multiple style = 'width:150px; float: left;'>";
		page.FIPS.forEach(function(v,i){
			bstr = bstr + "<option value = '" + v + "'> " + v + "</option>";
		});
		bstr = bstr + "</select>"
		//bstr = bstr + "</div>";

		var typeKeys = new Array("watch", "report", "outlook");
		typeKeys.forEach(function(type, n){
			var astr = '';
			astr = astr + "<div>";
			astr = astr + "<select name = '" + type + "-fips-select' data-placeholder = 'Select FIPS Code(s)...' class = '" + type + "-fips-select' multiple style = 'width:150px; float: left;'>";
			var str = astr + bstr;
			str = str + "</select>";
			str = str + "</div>";

			jQuery("#" + type + "-fips-filter").append(str);

			jQuery("." + type + "-fips-select").chosen({
				width: "270px",
				search_contains: true
			}).change(function(e, params){
				getFilters(page);
			});
		});
	});
}


function listFilters(page){
	console.log("listFilters");
	console.log(page.filters);
	filters = page.filters;
	var filterKeys = new Array("date", "state", "cwa", "fips", "type");
	filterKeys.forEach(function(d,n){
		console.log(d);
		var k =  d.toLowerCase();
		var txt = '';
		var str = 'None';
		if(d === "date"){
			txt = "Date Range: ";
		}else if(d === 'state'){
			txt = "State Filter(s): ";
		}else if(d === 'cwa'){
			txt ="CWA Filter(s): ";
		}else if(d === 'fips'){
			txt = "FIPS Filter(s): ";
		}else if(d === 'type'){
			txt = "Report/Watch Type Filter(s): ";
		}
		if((filters[d]) && (d != 'date') && (d != 'type')){
			str = filters[d].join(",");
		}else if((filters[d]) && (d === 'date')){
			var sm = moment(filters['date'][0], "YYYYMMDD");
			var em = moment(filters['date'][1], "YYYYMMDD");
			str = sm.format("MM/DD/YYYY") + " to " + em.format("MM/DD/YYYY");
		}else if ((filters[d]) && (d === 'type')){
			console.log(filters[d])
			if (filters[d].includes("A") == true) {
				str = "HAIL";
			}else if (filters[d].includes("G") == true) {
				str = "WIND GUST";
			}else if (filters[d].includes("W") == true) {
				str = "WIND DAMAGE";
			}else if (filters[d].includes("ALLW") == true) {
				str = "ALL WIND";
			}else if (filters[d].includes("T") == true) {
				str = "TORNADO";
			}else {
				str = filters[d].join(",");
			}
		}
		jQuery("#" + k + "-filter-list").text(txt + str);
		/*
		if(filters[d]){
			var str = filters[d].join(",");
			if(d === "Date"){

			}else if(d === 'State'){
				jQuery("#state-filter-list").text("State Filter(s): " + str);
			}else if(d === 'CWA'){
				jQuery("#cwa-filter-list").text("CWA Filter(s): " + str);
			}else if(d === 'FIPS'){
				jQuery("#fips-filter-list").text("FIPS Filter(s): " + str);
			}
		}else{
			if(d === "Date"){

			}else if(d === 'State'){
				jQuery("#state-filter-list").text("State Filter(s): none");
			}else if(d === 'CWA'){
				jQuery("#cwa-filter-list").text("CWA Filter(s): none");
			}else if(d === 'FIPS'){
				jQuery("#fips-filter-list").text("FIPS Filter(s): none");
			}
		}
		*/
	});
}

function getFilters(page){
	console.log("getFilters");
	var filters = new Object();
	startDate = jQuery("#startCalInput").val();
	endDate = jQuery("#endCalInput").val();
	var tmpArray = new Array();
	if(checkDate(startDate)){
		tmpArray.push(startDate);
	}else{
		errorDialog('date');
		tmpArray.push(false);
	}

	if(checkDate(endDate)){
		tmpArray.push(endDate);
	}else{
		errorDialog('date');
		tmpArray.push(false);
	}
	filters['date'] = tmpArray;

	if((filters['date'][0] !== false) && (filters['date'][1] !== false)){
		var type = page.dataType;
		filters['state'] 	= (jQuery("." + type + "-states-select").chosen().val());
		filters['cwa'] 		= (jQuery("." + type + "-cwa-select").val());
		filters['fips'] 	= (jQuery("." + type + "-fips-select").val());
	if (page.dataType === "report") {
		if (jQuery("#report-type-1").prop("checked") === true) {
		filters.type     = [""];
		}else if (jQuery("#report-type-2").prop("checked") === true) {
		filters.type     = [jQuery("#report-type-2").val()];
		} else if (jQuery("#report-type-3").prop("checked") === true) {
		filters.type     = [jQuery("#report-type-3").val()];
		} else if (jQuery("#report-type-4").prop("checked") === true) {
		filters.type     = ["G","W"];
		} else if (jQuery("#report-type-5").prop("checked") === true) {
		filters.type     = [jQuery("#report-type-5").val()];
		} else if (jQuery("#report-type-6").prop("checked") === true) {
		filters.type     = [jQuery("#report-type-6").val()];
		}
	}else if (page.dataType === "watch") {
		if (jQuery("#watch-type-1").prop("checked") === true) {
		filters.type   	 = [""];
		} else if (jQuery("#watch-type-2").prop("checked") === true) {
		filters.type     = [jQuery("#watch-type-2").val()];
		} else if (jQuery("#watch-type-3").prop("checked") === true) {
		filters.type     = [jQuery("#watch-type-3").val()];
		} else if (jQuery("#watch-type-4").prop("checked") === true) {
		filters.type     = [""];
		filters['pds']  = ["1"];
		}
	}
		page.filters = filters;
		return filters;
	}else{
		return false;
	}
}

function createFilteredData(page){
	jQuery('data-table').empty();
	jQuery('data-table').html('<iframe id="loading_gif" src="https://giphy.com/embed/3o7TKtnuHOHHUjR38Y" width="200" height="200" frameBorder="0" class="giphy-embed" allowFullScreen></iframe>');

	console.log("\t\tRunning createFilteredData");
	//***************************************
	//			Crossfilter stuff								*
	//***************************************
	if(typeof page.crossfilters === "undefined"){
		page.crossfilters = {};
	}

	page.crossfilters['all'] 		= crossfilter(page.data[page.dataType]);
	page.crossfilters['cwa'] 		= page.crossfilters['all'].dimension(function(d){ return d.CWA});
	page.crossfilters['state'] 	= page.crossfilters['all'].dimension(function(d){ return d.ST});
	page.crossfilters['fips'] 	= page.crossfilters['all'].dimension(function(d){ return d.FIPS});
	if (page.dataType ==="watch") {
	page.crossfilters['date'] 	= page.crossfilters['all'].dimension(function(d){ return moment.utc(d['sel_issue_dt'], "YYYYMMDDHHmmss")});
	page.crossfilters['type']   = page.crossfilters['all'].dimension(function(d) { return d.type});
	page.crossfilters['pds'] 	= page.crossfilters['all'].dimension(function(d){ return d.pds});
	}else if (page.dataType ==="report") {
	page.crossfilters['date'] 	= page.crossfilters['all'].dimension(function(d){ return moment.utc(d['DT'], "YYYYMMDDHHmmss")});
	page.crossfilters['type'] 	= page.crossfilters['all'].dimension(function(d){ return d["TYPE"]});
}

	/*
	page.crossfilters[type+'-hour'] 	= page.crossfilters[type+'-all'].dimension(
		function(d){
			var a 	= moment.utc(d.Date);
			var tmp = a.format("YYYYMMDDHHmm");
			return tmp;
		}
	);
	/*
	page.crossfilters[type+'-type']			= page.crossfilters[type+'-all'].dimension(function(d){ return d.TypeLong});
	page.crossfilters[type+'-mag'] 			= page.crossfilters[type+'-all'].dimension(function(d){ return d.Mag});
	page.crossfilters[type+'-typeMag']	= page.crossfilters[type+'-all'].dimension(function(d){ return d.TypeLong + ';' + d.Mag + ";" + d["E/M/U"]});
	page.crossfilters[type+'-state'] 		= page.crossfilters[type+'-all'].dimension(function(d){ return d.St});
	page.crossfilters[type+'-cwa'] 			= page.crossfilters[type+'-all'].dimension(function(d){ return d.CWA});
	page.crossfilters[type+'-county']		= page.crossfilters[type+'-all'].dimension(function(d){ return d.County});
	page.crossfilters[type+'-lat'] 			= page.crossfilters[type+'-all'].dimension(function(d){ return d.Lat});
	page.crossfilters[type+'-lon'] 			= page.crossfilters[type+'-all'].dimension(function(d){ return d.Lon});
	page.crossfilters[type+'-fips'] 		= page.crossfilters[type+'-all'].dimension(function(d){ return d.FIPS});
	*/
}


function getFilteredData(page){

	console.log("getFilteredData");
	clearFilteredData(page);
	page.filters = getFilters(page);
	listFilters(page);
	console.log(page.filters)
	//Date
	if(page.filters['date']){
		var sdm = moment.utc(page.filters['date'][0]+'0000', "YYYYMMDDHHmmss");
		var edm = moment.utc(page.filters['date'][1]+'0000', "YYYYMMDDHHmmss");
		page.crossfilters['date'].filter(function(d){
			if((d >= sdm) && (d <= edm)){return d;}
		});
	}
	//CWA
	if(page.filters['cwa']){
		//OR filtering
		page.crossfilters['cwa'].filter(function(d){
			match = 0;
			page.filters['cwa'].forEach(function(dd,nn){
				if(d.includes(dd)){match = 1;}
			});
			if(match === 1){return d;}
		});
		//AND filtering
		//page.filters['cwa'].forEach(function(dd,nn){
		//	page.crossfilters['cwa'].filter(function(d){
		//		if(d.includes(dd)){return d;}
		//	});
		//});

	}
	//State
	if(page.filters['state']){
		//OR filtering
		page.crossfilters['state'].filter(function(d){
			match = 0;
			page.filters['state'].forEach(function(dd,nn){
				if(d.includes(dd)){match = 1;}
			});
			if(match === 1){return d;}
		});
		//AND filtering
		//page.filters['state'].forEach(function(dd,nn){
		//	page.crossfilters['state'].filter(function(d){
		//		if(d.includes(dd)){return d;}
		//	});
		//});
	}
	//FIPS
	if(page.filters['fips']){
		page.crossfilters['fips'].filter(function(d){
			match = 0;
			page.filters['fips'].forEach(function(dd,nn){
				if(d.includes(dd)){match = 1;}
			});
			if(match === 1){return d;}
		});
		//AND filtering
		//page.filters['fips'].forEach(function(dd,nn){
		//	page.crossfilters['fips'].filter(function(d){
		//		if(d.includes(dd)){return d;}
		//	});
		//});
	}
	//PDS
	if(page.filters['pds']){
		page.filters['pds'].forEach(function(dd,nn){
			page.crossfilters['pds'].filter(function(d){
				if(d.includes(dd)){return d;}
				});
			});
		}
	//Type
	if(page.filters['type']){
		//OR filtering
		page.crossfilters['type'].filter(function(d){
			match = 0;
			page.filters['type'].forEach(function(dd,nn){
				if(d.includes(dd)){match = 1;}
			});
			if(match === 1){return d;}
		});
		//AND Filtering
		//page.filters['type'].forEach(function(dd,nn){
		//	page.crossfilters['type'].filter(function(d){
		//		if(d.includes(dd)){return d;}
		//		});
		//	});
		}

	var d = page.crossfilters['fips'].top(Infinity);
	console.log(d)
	page.data['filtered'] = d;
	displayFilteredData(page);
	makeChart(page);
	jQuery( "#dialog" ).dialog( "close" );
}

function clearFilteredData(page){
	page.crossfilters['date'].filter(null);
	page.crossfilters['cwa'].filter(null);
	page.crossfilters['state'].filter(null);
	page.crossfilters['fips'].filter(null);
	page.crossfilters['type'].filter(null);
	//page.crossfilters['pds'].filter(null);
}
