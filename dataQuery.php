<?php
	date_default_timezone_set("Etc/UTC");

	$docRoot = $_SERVER['DOCUMENT_ROOT'];

	if($docRoot == ""){
		$docRoot = getcwd();
	}
	//$srcDir1 = "/vdevweb2/";
	//$srcDir2 = "/var/www/html/";

	$srcDir = "$docRoot/data/dataquery/src-vdevweb2";
	echo "$srcDir<br>";

	//Get options for search from url -- this is tricky so all inputs that
	//aren't exact will be ignored and the script WILL NOT run
	$longopts = array(
		"dtype:",
		"source:",
		"rtype:",
		"wtype:",
		"v",
		"date:",
		"range",
		"start:",
		"end:",
		"states:",
		"cwas:",
		"fips:",
		"cache"
	);

	$dtype 	= false;
	$source	= false;
	$rtype 	= false;
	$wtype 	= false;
	$v 			= false;
	$date 	= false;
	$range 	= false;
	$start 	= false;
	$end 		= false;
	$cache 	= false;
	$which 	= false;
	$types  = array();
	$states = false;
	$cwas		= false;
	$fips		= false;
	$filters= false;
	$options = getoptreq("", $longopts);
	if(isset($options["v"])){
		$v = true;
	}

	//Check required options - data type and date or date range.
	//Date/Time Options
	if(isset($options["range"])){
		$which = 'range';
		$date = false;
		$range = true;
		if(isset($options["start"])){
			$s = $options["start"];
			$start_date = create_date($s);
		}else{
			handleError(1);
		}
		if(isset($options["end"])){
			$e = $options["end"];
			$end_date = create_date($e);
		}else{
			handleError(1);
		}
	}else if(isset($options["date"])){
		$which = 'date';
		$d = $options["date"];
		$start_date = create_date($d);
		$ndate = date_create_from_format("YmdHi", $start_date);
		date_add($ndate, date_interval_create_from_date_string("1 day"));
		$end_date =  date_format($ndate, "YmdHi");
	}else{
		handleError(2);
	}

	//Data Type options
	if(isset($options["dtype"])){
		$dtype = $options["dtype"];
		if($dtype == 'reports'){
			//Report source options
			if(isset($options["source"])){
				$source = $options["source"];
				if(($source != 'lsr') && ($source != 'stormdata')){
					handleError(4);
				}
			}else{
				handleError(4);
			}
			//Report type options

			if(isset($options["rtype"])){
				$rtype = $options["rtype"];
				##Get valid report types from file
				$a = file_get_contents($srcDir."/reportTypes.csv");
				$lines = explode("\n", $a);
				$num = count($lines);
				$keys = array();
				$valid_report_types = array();
				for($i = 0; $i < $num; $i++){
					$l = trim($lines[$i]);
					if($i == 0){
						$keys = explode(",", $l);
					}else{
						$tmp = explode(",", $l);
						for($ii = 0; $ii < count($tmp)-1; $ii++){
							if($keys[$ii] == 'SHORT'){
								if ($tmp[$ii] == " "){continue;}
								array_push($valid_report_types, trim($tmp[$ii]));
								array_push($valid_report_types, trim($tmp[$ii]));
							}
						}
					}
				}
				$ts = explode(",", $rtype);
				array_push($valid_report_types, 'All');
				array_push($valid_report_types, 'all');
				array_push($valid_report_types, 'AllWind');
				foreach($ts as $t){
					if(in_array($t, $valid_report_types)){
						if(! in_array($t, $types)){
							array_push($types, $t);
						}
					}else{
						handleError(6);
					}
				}
			}else{
				handleError(5);
			}
		}elseif($dtype == 'watches'){
			//Watch type options
			if(isset($options["wtype"])){
				$wtype = $options["wtype"];
				$valid_watch_types = array("TOR", "SVR", "PDSTOR", "PDSSVR", "All");
				$ts = explode(",", $wtype);
				foreach($ts as $t){
					if(in_array($t, $valid_watch_types)){
						if(! in_array($t, $types)){
							array_push($types, $t);
						}
					}else{
						handleError(8);
					}
				}
			}else{
				handleError(7);
			}
		}elseif($dtype == 'outlooks'){
			print "Not set up for outlooks yet<br>";
			exit(1);
		}else{
			handleError(3);
		}
	}else{
		handleError(3);
	}

	if($v){
		print "<br>";
		print "Start Date/Time: $start_date<br>";
		print "End Date/Time: $end_date<br>";
		if($rtype){
			print "Report Type(s): ".join(", ", $types)."<br>";
			print "Report Source: $source<br>";
		}
		if($wtype){
			print "Watch Type(s): ".join(", ", $types)."<br>";
		}
	}

	//Determine other filters
	//State
	if(isset($options["states"])){
		$s = $options["states"];
		if(check_states($s)){
			$states = explode(",", $s);
		}else{
			handleError(9);
		}
	}
	//CWA
	if(isset($options["cwas"])){
		$c = $options["cwas"];
		if(check_cwas($c)){
			$cwas = explode(",", $c);
		}else{
			handleError(10);
		}
	}
	//FIPS
	if(isset($options["fips"])){
		$f = $options["fips"];
		if(check_fips($f)){
			$fips = explode(",", $f);
		}else{
			handleError(11);
		}
	}

	if($v){
		print "--- Filters Set --- <br>";
		if($states){
			print "State(s): ".join(", ", $states);
		}else{
			print "State(s): None";
		}
		print "<br>";
		if($cwas){
			print "CWA(s): ".join(", ", $cwas);
		}else{
			print "CWA(s): None";
		}
		print "<br>";
		if($fips){
			print "FIPS: ".join(", ", $fips);
		}else{
			print "FIPS: None";
		}
		print "<br>";
	}

	$args = array();
	array_push($args, escapeshellarg($start_date));
	array_push($args, escapeshellarg($end_date));
	array_push($args, escapeshellarg(strtoupper('TYPE='.join(",", ($types)))));
	if($states){
		array_push($args, escapeshellarg(strtoupper('ST='.join(",", ($states)))));
	}
	if($cwas){
		array_push($args, escapeshellarg(strtoupper('CWA='.join(",", ($cwas)))));
	}
	if($fips){
		array_push($args, escapeshellarg(strtoupper('FIPS='.join(",", ($fips)))));
	}

	if($rtype){
		//$command = './dataQuery reports '.$arg2.' '.$arg3.' '.$arg4.' CWA=OUN';
		$command = $srcDir .'/dataQuery reports '.join(" ", ($args));
	}else if($wtype){
		$command = $srcDir .'/dataQuery watches '.join(" ", ($args));
	}
	$escaped_command = escapeshellcmd($command);
	if($v){echo $escaped_command."<br>";}
	$json = `$escaped_command`;

	if($v){
		switch(json_last_error()){
			case JSON_ERROR_NONE:
				echo '- No errors parsing JSON<br>';
			break;
		}
	}

	print_r($json);

	if($v){echo "<br>";}

	function create_date($d){
		if((strlen($d) == 8) || (strlen($d) == 10) || (strlen($d) == 12)){
			if(strlen($d) == 8){

				$d = $d.'1200';

			}else if(strlen($d) == 10){

				$d = $d.'00';

			}else if(strlen($d) == 12){
				$d = $d;
			}

			return $d.'00';
		}else{
			print "Date format is YYYYMMDD or YYYYMMDDHH or YYYYMMDDHHmm <br>";
			exit();
		}
	}

	function check_states($s){
		$states = explode(",", $s);
		//print_r($states);
		$tmp = "AK AL AZ AR CA CO CT DE FL GA HI ID IL IN IA KS KY LA ME MD MA MI MN MS MO MT NE NV NH NJ NM NY NC ND OH OK OR PA RI SC SD TN TX UT VT VA WA WV WI WY";
		$tmp2= explode(" ", $tmp);
		//print_r($tmp2);
		foreach ($states as $ss){
			if(in_array($ss, $tmp2)){

			}else{
				return false;
			}
		}
		return true;
	}

	function check_cwas($c){
		$sr = "BMX HUN MOB LZK JAX KEY MLB MFL TAE TBW FFC LCH LIX SHV JAN ABQ OUN TSA SJU MRX MEG OHX AMA EWX BRO CRP FWD EPZ HGX LUB MAF SJT";
		$cr = "BOU PUB GJT ILX LOT IND IWX DMX DVN DDC GLD TOP ICT JKL LMK PAH DTX GRR MQT APX DLH MPX EAX SGF LSX GID LBF OAX BIS FGF ABR UNR FSD GRB ARX MKX CYS RIW";
		$wr = "FGZ PSR TWC EKA LOX STO SGX MTR HNX BOI PIH BYZ GGW TFX MSO LKN VEF REV MFR PDT PQR SLC SEW OTX";
		$er = "CAR GYX LWX BOX PHI ALY BGM BUF OKX MHX RAH ILM ILN CLE CTP PBZ CHS CAE GSP BTV RNK AKQ RLX";

		$sr_cwas = explode(" ", $sr);
		$cr_cwas = explode(" ", $cr);
		$wr_cwas = explode(" ", $wr);
		$er_cwas = explode(" ", $er);
		$all_cwas = array_merge($sr_cwas, $cr_cwas, $wr_cwas, $er_cwas);

		$cwas = explode(",", $c);
		foreach($cwas as $cc){
			if(in_array($cc, $all_cwas)){

			}else{
				return false;
			}
		}
		return true;
		#my @cwas;
		#push @cwas, @sr_cwas, @cr_cwas, @wr_cwas, @er_cwas, "???";
	}

	function check_fips($f){

		return true;

	}




	function handleError($errorNum){
		$valid_report_types = array("A", "T", "W", "G", "All", "AllWind");
		$valid_watch_types = array("TOR", "SVR", "PDSTOR", "PDSSVR", "All");
		print "<br>";
		if($errorNum == 1){print "<br>ERROR: A start and end date must be set if the range option is set<br>";}
		if($errorNum == 2){print "<br>ERROR: 'date' or 'range' (with start and end dates) must be set <br>";}
		if($errorNum == 3){	print "<br>ERROR: 'dtype' (data type) option must be set with either 'reports', 'watches', or 'outlooks'<br>";}
		if($errorNum == 4){	print "<br>ERROR: 'source' (report source) option must be set with either 'lsr' or 'stormdata'<br>";}
		if($errorNum == 5){	print "<br>ERROR: 'rtype' (report type) option must be set.'<br>";}
		if($errorNum == 6){
			print "<br>ERROR: 'rtype' (report type) set to an invalid report type. '<br>";
			print "Valid report types are ".join(", ", $valid_report_types)."<br>";
		}
		if($errorNum == 7){print "<br>ERROR: 'wtype' (watch type) option must be set.'<br>";}
		if($errorNum == 8){
			print "<br>ERROR: 'wtype' (watch type) set to an invalid report type. '<br>";
			print "Valid report types are ".join(", ", $valid_watch_types)."<br>";
		}
		if($errorNum == 9){
			print "<br>ERROR: 'states' (state filter) set to an invalid state. '<br>";
		}
		print "<br><br>";
		exit(1);
	}



  function getoptreq ($options, $longopts){
	   if (PHP_SAPI === 'cli' || empty($_SERVER['REMOTE_ADDR']))  // command line
	   {
	      return getopt($options, $longopts);
	   }
	   else if (isset($_REQUEST))  // web script
	   {
	      $found = array();

	      $shortopts = preg_split('@([a-z0-9][:]{0,2})@i', $options, 0, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
	      $opts = array_merge($shortopts, $longopts);

	      foreach ($opts as $opt)
	      {
		 if (substr($opt, -2) === '::')  // optional
		 {
		    $key = substr($opt, 0, -2);

		    if (isset($_REQUEST[$key]) && !empty($_REQUEST[$key]))
		       $found[$key] = $_REQUEST[$key];
		    else if (isset($_REQUEST[$key]))
		       $found[$key] = false;
		 }
		 else if (substr($opt, -1) === ':')  // required value
		 {
		    $key = substr($opt, 0, -1);

		    if (isset($_REQUEST[$key]) && !empty($_REQUEST[$key]))
		       $found[$key] = $_REQUEST[$key];
		 }
		 else if (ctype_alnum($opt))  // no value
		 {
		    if (isset($_REQUEST[$opt]))
		       $found[$opt] = false;
		 }
	      }

	      return $found;
	   }

	   return false;
	}

?>
