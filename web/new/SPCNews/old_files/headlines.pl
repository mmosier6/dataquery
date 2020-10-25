#!/usr/bin/perl

########################################################################
# Added WW and MD links check to ensure the links to WWs and MDs are valid.
# Jay Liang, Mon Jul  7 21:46:18 UTC 2008
# Added pwocheck to take care of day1 SLGT when overnight PWOs are issued.
# Jay Liang, Wed Dec  2 20:41:06 UTC 2009
# Changing tomorrow and Day 3 to the actual date to reduce confusion.
# Jay Liang, Tue Oct 26 16:11:29 UTC 2010
# Modified to handle Day2 and Day3 dates better by checking the valid date with the current date in local timezone CST6CDT.
# Jay Liang, Thu Feb 24 13:25:51 CST 2011
# Added PWOMB check and removes the link after 1530Z.  Jay Liang, Tue Mar 15 16:00:28 UTC 2011
# Disable PWOMB check so PWOMB will be available for $pwomb_valid_minutes
# Jay Liang, Mon Apr 25 20:08:32 UTC 2011
########################################################################

$lockfile = "/tmp/.headlines.lock";

if (-e $lockfile) {
  print "$lockfile exists, $0 will not run ...\n";
  exit;
}

open(LOCKFILE, ">$lockfile");

########################################################################
####initialize variables################################################
########################################################################

####Date variables######################################################
$cur_julian = `date -u +%j`;
$year = `date -u +%Y`;
$lastyear = `date -u -d "1 year ago" +%Y`;
$cur_day = `date -u +%d`;
$cur_time = `date -u +%H%M`;
$day = `date -u +%Y%m%d`;
$time = `date -u +%Y%m%d%H%M`;
#$ftpprog = "/web/spcwebgen/bin/wwwftp.exp";
$headlines = 'topnews_above_graphic.html';
#$remove_pwomb = 1530;
$pwomb_valid_minutes = 300;

unlink($headlines);
open(HEADLINES, ">$headlines") || die "Big problem, can't create $headlines\n";

chomp ($cur_julian);
chomp ($year);
chomp ($lastyear);
chomp ($cur_day);
chomp ($cur_time);
chomp ($day);
chomp ($time);

########################################################################
####Parse Day 1 Outlook#################################################
########################################################################

### Dump "dat" directory into an array. Then reverse it to get
### the most recent file first.

$otlkdir = '/NAWIPS/archive/OUTLOOK/' . $year . '/' . "dat";
$otlkdir_lastyear = '/NAWIPS/archive/OUTLOOK/' . $lastyear . '/' . "dat";

#$HHMM = `/bin/date -u +"%H%M"`;
#chomp($HHMM);
$day1file = `/bin/find $otlkdir_lastyear $otlkdir -maxdepth 1 -type f -name "outlook_DAY1_??????Z.dat.*" -mtime -1 | tail -1`;
#if ($HHMM lt 1200) {
#  print "Checking 1Z Day1\n";
#  $day1file1z = `/bin/find $otlkdir_lastyear $otlkdir -maxdepth 1 -type f -name "outlook_DAY1_??0100Z.dat.*" -mtime -1 | tail -1`;
#  chomp($day1file1z);
#  print "day1file1z = $day1file1z\n";
#  if (-e $day1file1z) {
#    $day1file = $day1file1z;
#  }
#}
chomp ($day1file);
print "day1file = $day1file\n";

### Open and parse latest day 1 file.
open (IN, "< $day1file");
open (IN1, "< $day1file");
@day1date = <IN>;
@day1type = <IN1>;
close (IN);
close (IN1);
splice (@day1date,0,2);
chomp ($day1date[0]);
splice (@day1type,0,3);
chomp ($day1type[0]);
print "day1date = $day1date[0]\n";
print "day1type = $day1type[0]\n";
$day1Start = substr($day1date[0], 0, 2);
chomp($day1Start);
print "day1Start = $day1Start\n";
$todayDate = `export TZ=CST6CDT; /bin/date +"%d"`;
chomp($todayDate);
$tomorrowDate = `export TZ=CST6CDT; /bin/date -d "1 day" +"%d"`;
chomp($tomorrowDate);
print "todayDate = $todayDate, tomorrowDate = $tomorrowDate\n";
$otlk1date = `export TZ=CST6CDT; /bin/date +"%A (%F %Z)"`;
chomp($otlk1date);
print "otlk1date = $otlk1date\n";

if ($day1type[0] eq "HIGH RISK") {
  $otlk1type = "5";
} elsif ($day1type[0] eq "MODERATE RISK") {
  $otlk1type = "4";
} elsif ($day1type[0] eq "SLIGHT RISK") {
  $otlk1type = "3";
} elsif ($day1type[0] eq "GENERAL THUNDERSTORMS") {
  $otlk1type = "2";
} elsif ($day1type[0] eq "") {
  $otlk1type = "1";
} else {
  print "Problem found\n";
}

########################################################################
####Parse Day 2 Outlook#################################################
########################################################################

$day2file = `/bin/find $otlkdir_lastyear $otlkdir -type f -mtime -1 | grep /outlook_DAY2_ | tail -1`;
chomp ($day2file);
print "day2file = $day2file\n";

### Open and parse latest day 2 file.
open (IN, "< $day2file");
open (IN1, "< $day2file");
@day2date = <IN>;
@day2type = <IN1>;
close (IN);
close (IN1);
splice (@day2date,0,2);
chomp ($day2date[0]);
splice (@day2type,0,3);
chomp ($day2type[0]);
print "day2date = $day2date[0]\n";
$day2Start = substr($day2date[0], 0, 2);
chomp($day2Start);
print "day2Start = $day2Start\n";
print "todayDate = $todayDate, tomorrowDate = $tomorrowDate\n";
if ($day2Start == $tomorrowDate) {
  $otlk2date = `export TZ=CST6CDT; /bin/date -d "1 day" +"%A (%F %Z)"`;
} elsif ($day2Start == $todayDate) {
  $otlk2date = `export TZ=CST6CDT; /bin/date +"%A (%F %Z)"`;
}

if ($day2type[0] eq "HIGH RISK") {
  $otlk2type = "5";
} elsif ($day2type[0] eq "MODERATE RISK") {
  $otlk2type = "4";
} elsif ($day2type[0] eq "SLIGHT RISK") {
  $otlk2type = "3";
} elsif ($day2type[0] eq "GENERAL THUNDERSTORMS") {
  $otlk2type = "2";
} elsif ($day2type[0] eq "") {
  $otlk2type = "1";
} else {
  print "Problem found\n";
}

########################################################################
####Parse Day 3 Outlook#################################################
########################################################################

$day3file = `/bin/find $otlkdir_lastyear $otlkdir -type f -mtime -1 | grep /outlook_DAY3_ | tail -1`;
chomp ($day3file);
print "day3file = $day3file\n";

### Open and parse latest day 3 file.
open (IN, "< $day3file");
open (IN1, "< $day3file");
@day3date = <IN>;
@day3type = <IN1>;
close (IN);
close (IN1);
splice (@day3date,0,2);
chomp ($day3date[0]);
splice (@day3type,0,3);
chomp ($day3type[0]);
print "day3date = $day3date[0]\n";
$day3Start = substr($day3date[0], 0, 2);
chomp($day3Start);
print "day3Start = $day3Start\n";
$Day3Date = `export TZ=CST6CDT; /bin/date -d "2 day" +"%d"`;
print "todayDate = $todayDate, tomorrowDate = $tomorrowDate, Day3Date = $Day3Date\n";
if ($day3Start == $Day3Date) {
  $otlk3date = `export TZ=CST6CDT; /bin/date -d "2 day" +"%A (%F %Z)"`;
} elsif ($day3Start == $tomorrowDate) {
  $otlk3date = `export TZ=CST6CDT; /bin/date -d "1 day" +"%A (%F %Z)"`;
}

if ($day3type[0] eq "HIGH RISK") {
  $otlk3type = "5";
} elsif ($day3type[0] eq "MODERATE RISK") {
  $otlk3type = "4";
} elsif ($day3type[0] eq "SLIGHT RISK") {
  $otlk3type = "3";
} elsif ($day3type[0] eq "GENERAL THUNDERSTORMS") {
  $otlk3type = "2";
} elsif ($day3type[0] eq "") {
  $otlk3type = "1";
} else {
  print "Problem found\n";
}

########################################################################
####Parse Day 1 Fire Weather############################################
########################################################################

### Dump "dat" directory into an array. Then reverse it to get
### the most recent file first.

$firedir = '/NAWIPS/archive/FIREWX/' . $year . '/' . "dat";
$firedir_lastyear = '/NAWIPS/archive/FIREWX/' . $lastyear . '/' . "dat";

#$HHMM = `/bin/date -u +"%H%M"`;
#chomp($HHMM);
$fire1file = `/bin/find $firedir_lastyear $firedir -maxdepth 1 -type f -name "firewx_DAY1_??????Z.dat.*" -mtime -1 | tail -1`;
#if ($HHMM lt 1200) {
#  print "Checking 1Z Day1\n";
#  $day1file1z = `/bin/find $otlkdir_lastyear $otlkdir -maxdepth 1 -type f -name "outlook_DAY1_??0100Z.dat.*" -mtime -1 | tail -1`;
#  chomp($day1file1z);
#  print "day1file1z = $day1file1z\n";
#  if (-e $day1file1z) {
#    $day1file = $day1file1z;
#  }
#}
chomp ($fire1file);
print "fire1file = $fire1file\n";

### Open and parse latest day 1 file.
open (IN, "< $fire1file");
open (IN1, "< $fire1file");
@fire1date = <IN>;
@fire1type = <IN1>;
close (IN);
close (IN1);
splice (@fire1date,0,2);
chomp ($fire1date[0]);
splice (@fire1type,0,3);
chomp ($fire1type[0]);
print "fire1date = $fire1date[0]\n";
print "fire1type = $fire1type[0]\n";
$fire1Start = substr($fire1date[0], 0, 2);
chomp($fire1Start);
print "fire1Start = $fire1Start\n";
$todayDate = `export TZ=CST6CDT; /bin/date +"%d"`;
chomp($todayDate);
$tomorrowDate = `export TZ=CST6CDT; /bin/date -d "1 day" +"%d"`;
chomp($tomorrowDate);
print "todayDate = $todayDate, tomorrowDate = $tomorrowDate\n";
$fire1date = `export TZ=CST6CDT; /bin/date +"%A (%F %Z)"`;
chomp($fire1date);
print "fire1date = $fire1date\n";

if ($fire1type[0] eq "EXTREMELY CRITICAL AREA FIRE WEATHER") {
  $fire1type = "4";
} elsif ($fire1type[0] eq "CRITICAL AREA FIRE WEATHER") {
  $fire1type = "3";
} elsif ($fire1type[0] eq "DRY TSTM AREA FIRE WEATHER") {
  $fire1type = "2";
} elsif ($fire1type[0] eq "") {
  $fire1type = "1";
} else {
  print "Problem found\n";
}


########################################################################
####Parse Day 2 Fire WX#################################################
########################################################################

$fire2file = `/bin/find $firedir_lastyear $firedir -maxdepth 1 -type f -name "firewx_DAY2_??????Z.dat.*" -mtime -1 | tail -1`;
chomp ($fire2file);
print "fire2file = $fire2file\n";

### Open and parse latest day 2 file.
open (IN, "< $fire2file");
open (IN1, "< $fire2file");
@fire2date = <IN>;
@fire2type = <IN2>;
close (IN);
close (IN2);
splice (@fire2date,0,2);
chomp ($fire2date[0]);
splice (@fire2type,0,3);
chomp ($fire2type[0]);
print "fire2date = $fire2date[0]\n";
$fire2Start = substr($fire2date[0], 0, 2);
chomp($fire2Start);
print "fire2Start = $fire2Start\n";
print "todayDate = $todayDate, tomorrowDate = $tomorrowDate\n";
if ($fire2Start == $tomorrowDate) {
  $fire2date = `export TZ=CST6CDT; /bin/date -d "1 day" +"%A (%F %Z)"`;
} elsif ($fire2Start == $todayDate) {
  $fire2date = `export TZ=CST6CDT; /bin/date +"%A (%F %Z)"`;
}

if ($fire2type[0] eq "EXTREMELY CRITICAL AREA FIRE WEATHER") {
  $fire2type = "4";
} elsif ($fire2type[0] eq "CRITICAL AREA FIRE WEATHER") {
  $fire2type = "3";
} elsif ($fire2type[0] eq "DRY TSTM AREA FIRE WEATHER") {
  $fire2type = "2";
} elsif ($fire2type[0] eq "") {
  $fire2type = "1";
} else {
  print "Problem found\n";
} 

#######################################################################
####Parse MCD##########################################################
#######################################################################

###Define MCD log file.
$logfile = '/NAWIPS/archive/MCD/' . $year . '/log/mcd' . $year . '.log';

# Below checks to see if a MD has been issued in a new year.  If not,
# then use lastyear's MD log.
if (! -e $logfile) {
  $logfile = '/NAWIPS/archive/MCD/' . $lastyear . '/log/mcd' . $lastyear . '.log';
}

###Dump log file into array. Reverse array, so most recent file is first.
open (IN, "<$logfile") or die "Couldn't open log file!";
@mcdlist = <IN>;
close (IN);

@mcdlist = reverse @mcdlist;

@mcd =();

###Push most recent 20 MCDs into new array to find valid products.
for ($i = 0; $i < 20; $i++) {
    chomp($i);
### Check to remove duplicate MD numbers
### Jay Liang, Fri Jan 20 22:03:51 UTC 2006
    if ($i < 19) {
      if ($mcdlist[$i] != $mcdlist[$i+1]) {
        push(@mcd, $mcdlist[$i]);
      }
    }
}

@validmcd = ();

###For each MCD, parse and check validity.
foreach $i (@mcd) {
        chomp($i);
        @value = split(/,/,$i);
        $mdnum = $value[0];
	$issday = $value[1];
	$begin = $value[2];
        $end = $value[3];
     
	###Assign specific time variable to see if each MCD is valid
        $issdate = substr($issday,4,2).substr($issday,0,2).substr($issday,2,2);
	$iss_julian = `date -d $issdate +%j`;
	$begin_day = substr($begin,0,2);
	$begin_time = substr($begin,2,4);
        $end_day = substr($end,0,2);
        $end_time = substr($end,2,4);
	
	chomp ($issdate);
	chomp ($iss_julian);
	chomp ($begin_day);
	chomp ($begin_time);
	chomp ($end_day);
	chomp ($end_time);
     
	#The following checks are done to make sure that MCD expiration julian day is correct.
	  	
	   #MCDs where change of month occurs within product valid time
	   if ( $cur_julian >= $iss_julian && $end_day < $begin_day ) {
	      $mcd_julian = $iss_julian + "1";
	      $mcd_julian = "0" . $mcd_julian;
	      chomp ($mcd_julian);
	   }
	   #MCDs where valid product time crosses UTC day change
	   elsif ( $end_day > $begin_day ) {
	      $mcd_julian = $iss_julian + "1";
	      $mcd_julian = "0" . $mcd_julian;
	      chomp ($mcd_julian);
	   }
	   #MCDS where valid product time is same UTC day
	   else {
	      $mcd_julian = $iss_julian;
	   }
	
	#Once correct MCD expiration julian day has been correctly defined, then true checks can now
	#be done to see if each porduct is valid.
	
	$valid=0;
	
	if ( $cur_julian == $mcd_julian && $end_time > $cur_time) {
	$valid = 1;
	}
	elsif ($mcd_julian > $cur_julian) {
	$valid = 1;
	}
	
	chomp ($valid);
	
	      #Push all valid MCDs into an array for printing in headline
              if ($valid == "1") { push(@validmcd, $mdnum); }
           
        }


#Count elements in array to alert script that MCDs are valid.

@validmcd = reverse(@validmcd);
$mcdarray = scalar(@validmcd);

if ($mcdarray != "0") {
   $mcdcheck = 1;
   }
else {
   $mcdcheck = 0;
   }


#######################################################################
####Parse Weather Watches##############################################
#######################################################################

#
# Modified to take advantage of /web/spcwebgen/watch_overview/watchdata.txt
# file which contains all valid watches.
# Jay Liang, Wed Apr 19 22:04:22 UTC 2006
#

@validww = ();
$wwcheck = 0;

@validww = `/bin/sort -u -k 1,1 /tmp/.watchdata.txt | /bin/awk '{if (\$1 < 9000) printf "%4.4d\\n", \$1}'`;

$wwarray = scalar(@validww);

if ($wwarray != "0") {
   $wwcheck = 1;
} 

#######################################################################
####Parse PWO##########################################################
#######################################################################

#JL# @pwofiles = glob("/NAWIPS/archive/PWO/$year/txt/pwo$day*.txt");
#
# Modified to use find instead of glob to list PWOs less than 480 minutes old
# Jay Liang, Mon Apr  3 15:20:19 UTC 2006
# Added code to check PWO valid time
# Jay Liang, Thu Aug 23 17:44:32 UTC 2007
#
#@pwofiles = `/bin/find /NAWIPS/archive/PWO/$year/txt -maxdepth 1 -type f -mmin -960`;

$pwocheck = 0;

$pwofiles = `/bin/find /NAWIPS/archive/PWO/$year/txt -maxdepth 1 -type f -mtime -1 -name 'pwo*.txt' | /bin/sort | /usr/bin/tail -1`;
chomp($pwofiles);
print "pwofiles = $pwofiles\n";

if ( ! $pwofiles eq "" ) {
  $pwovalidtime=`head -3 $pwofiles | tail -1 | awk 'BEGIN {RS="-"}{print \$1}' | tail -2 | head -1`;
  chomp($pwovalidtime);
  print "pwovalidtime = $pwovalidtime\n";

  $vdate = substr($pwovalidtime,0,2);
  chomp($vdate);
  print "vdate = $vdate\n";

  $vtime = substr($pwovalidtime,2,4);
  chomp($vtime);
  print "vtime = $vtime\n";

  $cdate = `date -u +%d`;
  chomp($cdate);
  print "cdate = $cdate\n";

  $ctime = `date -u +%H%M`;
  chomp($ctime);
  print "ctime = $ctime\n";

  $ndate = `date -u -d "tomorrow" +%d`;
  chomp($ndate);
  print "ndate = $ndate\n";

  if ($vdate == $ndate) {
    $pwocheck = 1;
    print "PWO valid till tomorrow\n";
  }
  elsif (($vdate == $cdate) && ($vtime >= $ctime)) {
    $pwocheck = 1;
    print "PWO valid till $vtime\n";
  }
  else {
    print "PWO not valid\n";
  }
}
print "pwocheck = $pwocheck\n";


#######################################################################
#######################################################################
# checking if pwomb is available, makes the link.  Removes the link after $remove_pwomb
# setting $remove_pwomb 
#######################################################################
#######################################################################

$pwombcheck = 0;
#$hhmm = `/bin/date -u +"%H%M"`;
#if (($pwocheck > 0) && ($hhmm < $remove_pwomb)) {
if ($pwocheck > 0) {
  print "There is a valid PWO and the current time is prior to $remove_pwomb UTC. Check if a link to PWO MP4 needs to be made.\n";
  $pwombfile = `/bin/find /web/spcweb/briefings -maxdepth 1 -type f -mmin -$pwomb_valid_minutes -name 'pwo*.mp4' | /bin/sort | /usr/bin/tail -1`;
  chomp($pwombfile);
  print "pwombfile = $pwombfile\n";
  if ( ! $pwombfile eq "" ) {
    print "Found $pwombfile, see if it has made it to the spcwebsite.\n";
    @wget_results = qx{ /usr/bin/wget -q -O /dev/null http://spcwebsite.spc.noaa.gov/products/outlook/pwo.mp4 };
    $wget_status = $?;
    if ($wget_status == 0) {
      print "$pwombfile has made it to the spcwebsite. Put up a link in the top banner.\n";
      $pwombcheck = 1;
    }
  }
}

#######################################################################
#######################################################################
#### Begin Headline Coding ############################################
#######################################################################
#######################################################################

$alert = 0;

print HEADLINES <<"PrintTag";

<div id="spcNow">

PrintTag

if ($otlk1type == "5") {
$alert = 1;
print HEADLINES <<"PrintTag";

<ul id="spcNow-risk-List">
<li><div id="spcNow-risk">High Risk for severe storms today, $otlk1date ...</div>
The Storm Prediction Center is forecasting a major severe weather outbreak today and/or tonight.  See Details below.</li>
PrintTag

} elsif ($otlk1type == "4") {
$alert = 1;
print HEADLINES <<"PrintTag";

<ul id="spcNow-risk-List">
<li><div id="spcNow-risk">Moderate Risk for severe storms today, $otlk1date ...</div>
<a href="http://www.spc.noaa.gov/products/outlook/day1otlk.html">Day 1 Convective Outlook</a></li> 
PrintTag

} elsif ($otlk2type == "5" && $otlk1type < "4" ) {
$alert = 1;
print HEADLINES <<"PrintTag";

<ul id="spcNow-risk-List">
<li><div id="spcNow-risk">High Risk for severe storms tomorrow, $otlk2date ...</div>
<a href="http://www.spc.noaa.gov/products/outlook/day2otlk.html">Day 2 Convective Outlook</a></li>
PrintTag

} elsif ($otlk2type == "4" && $otlk1type < "4" ) {
$alert = 1;
print HEADLINES <<"PrintTag";

<ul id="spcNow-risk-List">
<li><div id="spcNow-risk">Moderate Risk for severe storms tomorrow, $otlk2date ...</div>
<a href="http://www.spc.noaa.gov/products/outlook/day2otlk.html">Day 2 Convective Outlook</a></li>
PrintTag

} elsif ($otlk3type == "4" && $otlk1type < "4" && $otlk2type < "4") {
$alert = 1;
print HEADLINES <<"PrintTag";

<ul id="spcNow-risk-List">
<li><div id="spcNow-risk">Moderate Risk for severe storms for $otlk3date ...</div>
<a href="http://www.spc.noaa.gov/products/outlook/day3otlk.html">Day 3 Convective Outlook</a></li>
PrintTag

} elsif ($fire1type == "4" && $otlk1type < "4" && $otlk2type < "4" && $otlk3type < "4") {
$alert = 1;
print HEADLINES <<"PrintTag";

<ul id="spcNow-risk-List">
<li><div id="spcNow-risk">Extremely critical fire weather conditions today, $fire1date ...</div>
<a href="http://www.spc.noaa.gov/products/fire_wx/fwdy1.html">Day 1 Fire Weather Outlook</a></li>
PrintTag

} elsif ($fire2type == "4" && $otlk1type < "4" && $otlk2type < "4" && $otlk3type < "4" && $fire1type < "4") {
$alert = 1;
print HEADLINES <<"PrintTag";

<ul id="spcNow-risk-List">
<li><div id="spcNow-risk">Extremely critical fire weather conditions forecast tomorrow, $fire2date ...</div>
<a href="http://www.spc.noaa.gov/products/fire_wx/fwdy2.html">Day 2 Fire Weather Outlook</a></li>
PrintTag

} 

#
# Added pwocheck to take care of day1 SLGT when overnight PWOs are issued.
# Jay Liang, Wed Dec  2 20:41:06 UTC 2009
#

if ($pwocheck == "1") {
$alert = 1;  
print HEADLINES <<"PrintTag";

<ul id="spcNow-risk-List">
<li><div id="spcNow-risk"><a href="/products/outlook/pwo.html">Latest Public Severe Weather Outlook</a></div><br />View <a href="http://www.nws.noaa.gov/cgi-bin/nwsexit.pl?url=http://www.youtube.com/watch?v=x3V3HZBs1Y4" target="YouTube Clip">What is a Watch?</a> clip.</li>
PrintTag
}

if ($pwombcheck == "1") {
$alert = 1;  
print HEADLINES <<"PrintTag";

<ul id="spcNow-risk-List">
<li><div id="spcNow-risk"><a href="/products/outlook/pwo.mp4">Experimental Multimedia Briefing in MP4 Format</a>.</div></li>
PrintTag
}

if ($wwcheck == "1") {
$alert = 1;

  my $wwcount = scalar(@validww);
  my $wwnum = 0;

  if ($wwcount < 2) {

print HEADLINES << "PrintTag";
  
<ul id="spcNowList">
 <li><div id="spcNow-misc">Active Weather Watch:</div>
PrintTag

  } else {
 
print HEADLINES << "PrintTag";
<ul id="spcNowList">
<li><div id="spcNow-misc">Active Weather Watches:</div>
PrintTag
  }

  foreach $i (@validww) {
    chomp($i);
    $in = $i * 1;
    print "watch number: $in \n";
    @wget_results = qx{ /usr/bin/wget -q -O /dev/null http://spcwebsite.spc.noaa.gov/products/watch/ww$i.html };
    $wget_status = $?;
    if ($wget_status == 0) {
      print HEADLINES "<a href='/products/watch/ww$i.html'>$in</a>";
    } else {
      print HEADLINES "working on $in";
    }
    $wwnum++;
    if ($wwnum < $wwcount) {
      print HEADLINES " ... ";
    }
  }
  print HEADLINES "</li>";
}

if ($mcdcheck == "1") {
$alert = 1;

  my $mcdcount = scalar(@validmcd);
  my $mcdnum = 0;

  if ($mcdcount < 2) {

print HEADLINES << "PrintTag";
<ul id="spcNowList">
<li><div id="spcNow-misc">Active Mesoscale Discussion:</div>
PrintTag

  } else {
    print HEADLINES << "PrintTag";

<ul id="spcNowList">
<li><div id="spcNow-misc">Active Mesoscale Discussions:</div>
PrintTag
  }
  foreach $i (@validmcd) {
    chomp($i);
    $in = $i * 1;
    print "MD number: $in \n";
    @wget_results = qx{ /usr/bin/wget -q -O /dev/null http://spcwebsite.spc.noaa.gov/products/md/md$i.html };
    $wget_status = $?;
    if ($wget_status == 0) {
      print HEADLINES "<a href='/products/md/md$i.html'>$in</a>";
    } else {
      print HEADLINES "working on $in";
    }
    $mcdnum++;
    if ($mcdnum < $mcdcount) {
      print HEADLINES " ... ";
    }
  }
  print HEADLINES "</li>";
}


print HEADLINES "</ul></div>";

if ($alert == "0") { 

print HEADLINES "<div id='spcNow-misc'>No Current Headlines</div>"; 

}


print HEADLINES <<"PrintTag";

Last updated:&nbsp; 

<!--#config errmsg=""-->
<!--#config timefmt="%F %T %Z"-->
<!--#flastmod virtual="./topnews_above_graphic.html"-->
PrintTag


close(HEADLINES);

#system("$ftpprog $headlines public_html2/products/$headlines");

unlink("$lockfile");
