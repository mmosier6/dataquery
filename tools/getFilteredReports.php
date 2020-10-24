<?php
	date_default_timezone_set("Etc/UTC");

	require './json_hpack.php';
	require './vendor/autoload.php';

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

	$srcDir = "../src";

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

	//Make sure all the options are good and clean
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
		$date = create_date($d);
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
							}
						}
					}
				}
				$ts = explode(",", $rtype);
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
		if($which == 'date'){
			print "<br>";
			print "Date/Time: $date<br>";
		}elseif($which == 'range'){
			print "<br>";
			print "Start Date/Time: $start_date<br>";
			print "End Date/Time: $end_date<br>";
		}
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

	$datadir = "../../data";

	//Determine file(s) needed
	if($dtype == 'reports'){
		$fa = $datadir."/collections/report_collection";
		$ca = $datadir."/collections/combined_report_collections";
	}elseif($dtype == 'watches'){
		$fa = $datadir."/collections/watch_collection";
		$ca = $datadir."/collections/combined_watch_collections";
	}elseif($dtype == 'outlooks'){
		$fa = $datadir."/collections/outlook_collection";
		$ca = $datadir."/collections/combined_outlook_collections";
	}

	$data_files = array();
	if($range){
		#mktime(hour, minute, second, month, day, year)
		list($yr, $mo, $dy, $hr, $mi, $sc) = parse_date($start_date);
		$start_dt = mktime($hr, $mi, $sc, $mo, $dy, $yr);
		list($yr, $mo, $dy, $hr, $mi, $sc) = parse_date($end_date);
		$end_dt = mktime($hr, $mi, $sc, $mo, $dy, $yr);
		//echo "Start date year is " . date("Y", $start_dt)."<br>";
		//echo "End date year is " . date("Y", $end_dt)."<br>";
		if(date("Y", $start_dt) === date("Y", $end_dt)){
			//$p_df = $fa."_".date("Y", $start_dt)."-packed.json";
			//if(file_exists($p_df)){
			//	array_push($data_files, $p_df);
			//}
			if(empty($data_files)){
				$df = $fa."_".date("Y", $start_dt).".json";
				if(file_exists($df)){
					array_push($data_files, $df);
				}
			}
		}else{
			#Combined file
			$fs = glob($ca."*.json");
			foreach ($fs as $f){
				if(strpos($df, 'packed') !== false){
					continue;
				}
				$sfy = substr($f, -21, 4);
				$efy = substr($f, -16, 4);
				print "$sfy <= ".date("Y", $start_dt)." <= $efy<br>";
				print "$sfy <= ".date("Y", $end_dt)." <= $efy<br>";
				if(($sfy <= date("Y", $start_dt)) && (date("Y", $start_dt) <= $efy)){
					if(($sfy <= date("Y", $end_dt)) && (date("Y", $end_dt) <= $efy)){
						array_push($data_files, $f);
						break;
					}
				}
			}

			if(empty($data_files)){
				#Individual files
				$ydiff = date("Y", $end_dt) - date("Y", $start_dt);
				$dfs = array();
				for($i = 0; $i < $ydiff+1; $i++){
					$ny = date("Y", strtotime(date("Y-m-d", $start_dt)."+".(365*$i)." days"));
					$df = $fa."_".$ny.".json";
					$pdf= $ca."_".$ny."-packed.json";
					if(file_exists($pdf)){
						array_push($dfs, $pdf);
					}elseif(file_exists($df)){
						array_push($dfs, $df);
					}else{
						print "No data files found for $ny<br>";
					}
				}
				$data_files = $dfs;
			}
		}
	}else{
		list($yr, $mo, $dy, $hr, $mi, $sc) = parse_date($date);
		$dt = mktime($hr, $mi, $sc, $mo, $dy, $yr);
		echo "End date year is " . date("Y", $dt);
	}


	$reports = array();
	foreach($data_files as $df){
		if($v){ print "Searching...$df\n";}
		$jsonStream = \JsonMachine\JsonMachine::fromFile($df);

		foreach ($jsonStream as $name => $data) {
			if($range){
				#Report date/time
				list($yr, $mo, $dy, $hr, $mi, $sc) = parse_date($data['DT']);
				$rdt = mktime($hr, $mi, $sc, $mo, $dy, $yr);
				//echo "Report date/time is " . date("Y/m/d H:i:s", $rdt)."\n";
				#Filter start date/time
				list($yr, $mo, $dy, $hr, $mi, $sc) = parse_date($start_date);
				$start_dt = mktime($hr, $mi, $sc, $mo, $dy, $yr);
				#Filter end date/time
				list($yr, $mo, $dy, $hr, $mi, $sc) = parse_date($end_date);
				$end_dt = mktime($hr, $mi, $sc, $mo, $dy, $yr);
				if(($rdt >= $start_dt) && ($rdt <= $end_dt)){
					array_push($reports, $data);
				}else{
					continue;
				}
			}else{
				#Report date/time
				list($yr, $mo, $dy, $hr, $mi, $sc) = parse_date($data['DT']);
				$rdt = mktime($hr, $mi, $sc, $mo, $dy, $yr);
				//echo "Report date/time is " . date("Y/m/d H:i:s", $rdt)."\n";
				#Filter date/time
				list($yr, $mo, $dy, $hr, $mi, $sc) = parse_date($date);
				$dt = mktime($hr, $mi, $sc, $mo, $dy, $yr);
			}
				 // 1st iteration: $name === "apple" and $data === ["color" => "red"]
		    // 2nd iteration: $name === "pear" and $data === ["color" => "yellow"]
		}
	}

	print_r($reports);
	exit(1);

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

	function parse_date($d){
		$yr = substr($d, 0, 4);
		$mo = substr($d, 4, 2);
		$dy = substr($d, 6, 2);
		$hr = substr($d, 8, 2);
		$mi = substr($d, 10, 2);
		$sc = substr($d, 12, 2);
		return array($yr, $mo, $dy, $hr, $mi, $s);
	}

	function create_date($d){
		if((strlen($d) == 8) || (strlen($d) == 10) || (strlen($d) == 12)){
			if(strlen($d) == 8){

				$d = $d.'1200';

			}else if(strlen($d) == 10){

				$d = $d.'00';

			}else if(strlen($d) == 12){
				$d = $d;
			}

			return $d;
		}else{
			print "Date format is YYYYMMDD or YYYYMMDDHH or YYYYMMDDHHmm <br>";
			exit();
		}
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
