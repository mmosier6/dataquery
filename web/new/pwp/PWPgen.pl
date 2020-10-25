#!/usr/bin/perl 

#Set Date Variables
$yr = `date -u +%Y`;
chomp($yr);
$cacheBust = `date -u +%s`;
chomp($cacheBust);

######################################
### Images
######################################
open FILE, "images/imgOut.txt";
@imgs = <FILE>;
close FILE;

$tstm = $imgs[0];
$mrgl = $imgs[1];
$slgt = $imgs[2];
$enh = $imgs[3];
$mdt = $imgs[4];
$high = $imgs[5];

#Start at 1 to account for the overview graphic
$img = 1;

foreach (@imgs) {
    if ($_ =~/True/) { 
       $img++; 
    }
}

print "img: $img\n";

#####################################
### PWO
#####################################
$pwocheck = 0;

$pwodir = '/nfsops/ops_users/archive/PWO/' . $yr . '/' . "txt";
chomp($pwodir);
$pwo_file = `ls -1t '$pwodir'/pwo*.txt | head -1`;
chomp($pwo_file);
$pwo_iss = substr($pwo_file,42,12); 

$pwofiles = `/usr/bin/find /nfsops/ops_users/archive/PWO/$yr/txt -maxdepth 1 -type f -mtime -1 -name 'pwo*.txt' | /bin/sort | /usr/bin/tail -1`;
chomp($pwofiles);

if ( ! $pwofiles eq "" ) {
  $pwovalidtime=`head -3 $pwofiles | tail -1 | awk 'BEGIN {RS="-"}{print \$1}' | tail -2 | head -1`;
  chomp($pwovalidtime);
  #print "pwovalidtime = $pwovalidtime\n";

  $vdate = substr($pwovalidtime,0,2);
  chomp($vdate);
  #print "vdate = $vdate\n";

  $vtime = substr($pwovalidtime,2,4);
  chomp($vtime);
  #print "vtime = $vtime\n";

  $cdate = `date -u +%d`;
  chomp($cdate);
  #print "cdate = $cdate\n";

  $ctime = `date -u +%H%M`;
  chomp($ctime);
  #print "ctime = $ctime\n";

  $ndate = `date -u -d "tomorrow" +%d`;
  chomp($ndate);
  #print "ndate = $ndate\n";

  if ($vdate == $ndate) {
    $pwocheck = 1;
    #print "PWO valid till tomorrow\n";
  }
  elsif (($vdate == $cdate) && ($vtime >= $ctime)) {
    $pwocheck = 1;
    #print "PWO valid till $vtime\n";
  }
  else {
    #print "PWO not valid\n";
  }
}



#If there is a valid PWO, parse it.

if ($pwocheck == 1) {

   #parse PWO
   @locations = ();
   @hazards = ();
   @summary = ();

   open PWO, '<', $pwofile or die $!;

   while(<PWO>) {
	chomp($_);
	if (/\* LOCATIONS.../ .. /^\s*$/) {
		push(@locations,$_);
	}
	if (/\* HAZARDS.../ .. /^\s*$/) {
                push(@hazards,$_);
        }
	if (/\* SUMMARY.../ .. /^\s*$/) {
                push(@summary,$_);
        }

   }

   #get rid of the target text
   $shift = shift(@locations);
   $shift = shift(@hazards);
   $shift = shift(@summary);
}

#################################################################
#### MP4
##################################################################
$pwombcheck = 0;

$mp4dir = '/nfsops/ops_users/archive/PWO/' . $yr . '/' . "mp4";
chomp($mp4dir);
$mp4_file = `ls -1t '$mp4dir'/pwo*.mp4 | head -1`;
chomp($mp4_file);
$mp4_iss = substr($mp4_file,43,12);

if ($pwocheck > 0) {
  ## See if an mp4 has been issued
  $pwombfile = `/usr/bin/find /nfsops/ops_users/archive/PWO/$yr/mp4 -maxdepth 1 -type f -mmin -$pwomb_valid_minutes -name 'pwo*.mp4' | /bin/sort | /usr/bin/tail -1`;
  chomp($pwombfile);
  $pwombfile = '/nfsops/ops_users/archive/PWO/2014/mp4/pwo_201410131953.mp4';
  chomp($pwombfile);
  print "pwombfile = $pwombfile\n";
  if ( ! $pwombfile eq "" ) {
      #print "Found $pwombfile, see if it has made it to the spcwebsite.\n";
      @wget_results = qx{ /usr/bin/wget -q -O /dev/null http://spcwebsite.spc.noaa.gov/products/outlook/pwo.mp4 };
      $wget_status = $?;
      if ($wget_status == 0) {
         #print "$pwombfile has made it to the spcwebsite.\n";
         $pwombcheck = 1;
      }
  }
}

#######################################
### Outlook Summary
#######################################

$D1sumFile = `/usr/bin/find /NAWIPS/archive/OUTLOOK/$yr/dsc -maxdepth 1 -type f -mtime -1 -name 'outlook*DAY1*.sum.txt' | /bin/sort -t. -k2 | /usr/bin/tail -1`;
chomp($D1sumFile);

####################################
### Create the HTML
####################################
unlink("./pwp.html");
$pwpPage = "./pwp.html";
open (OUT, ">$pwpPage");

print OUT "<link rel='stylesheet' href='./css/pwp.css' />\n";
print OUT "<link rel='stylesheet' href='./css/jquery-ui.css' />\n";
print OUT "<script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js'></script>\n";
print OUT "<script src='./js/jquery-ui.js'></script>\n";
print OUT "<script type='text/javascript' src='./js/pwp.js'></script>\n";
print OUT "<div id='pwoContainer'>\n";
print OUT "<div id='pwoImageDiv'>\n";
if ($pwombcheck == 1) {
  print OUT "<table id='headlineTable'><tr>\n";
  print OUT "<td id='headlinePWO'>Public Weather Page</td>\n";
  print OUT "<td id='headlinePWOimage'><a href='products/outlook/pwo.mp4'><img src='./imgs/mp4.png' width='40px' height='40px' align='right'></img></a></td>\n";
  print OUT "<td id='headlineMP4'><a href='products/outlook/pwo.mp4'> MP4 Available</a></td>\n";
  print OUT "</tr></table>\n";
} else { 
print OUT "<div id='headlineNormal'>Public Weather Page<br></div>\n";
}
print OUT "<div id='tabs'>\n";
print OUT "<ul>\n";

#build the tabs based on the category
@categories = ("Overview", "Thunderstorms", "Marginal Risk", "Slight Risk", "Enhanced Risk", "Moderate Risk", "High Risk");
@images = ("./images/US_swody1.png","./images/TSTM_swody1.png","./images/MRGL_swody1.png", "./images/SLGT_swody1.png", "./images/ENH_swody1.png", "./images/MDT_swody1.png", "./images/HIGH_swody1.png");

for ($i = 0; $i < $img; $i++) {
	$tabNum = $i + 1;
	$tabNum = "#tabs-" . $tabNum;
        print OUT "<li><a href='$tabNum'>$categories[$i]</a></li>\n";
}
$tabNum = $i + 1;
$tabNum = "#tabs-" . $tabNum;
print OUT "<li><a href='#state'>States</a></li>\n";
print OUT "<li id='dropDown'><select id='maps'>\n";
print OUT "<option value='AL'>Alabama</option>\n";
print OUT "<option value='AZ'>Arizona</option>\n";
print OUT "<option value='AR'>Arkansas</option>\n";
print OUT "<option value='CA'>California</option>\n";
print OUT "<option value='CO'>Colorado</option>\n";
print OUT "<option value='CT'>Connecticut</option>\n";
print OUT "<option value='DE'>Delaware</option>\n";
print OUT "<option value='FL'>Florida</option>\n";
print OUT "<option value='GA'>Georgia</option>\n";
print OUT "<option value='ID'>Idaho</option>\n";
print OUT "<option value='IL'>Illinois</option>\n";
print OUT "<option value='IN'>Indiana</option>\n";
print OUT "<option value='IA'>Iowa</option>\n";
print OUT "<option value='KS'>Kansas</option>\n";
print OUT "<option value='KY'>Kentucky</option>\n";
print OUT "<option value='LA'>Louisiana</option>\n";
print OUT "<option value='ME'>Maine</option>\n";
print OUT "<option value='MD'>Maryland</option>\n";
print OUT "<option value='MA'>Massachusetts</option>\n";
print OUT "<option value='MI'>Michigan</option>\n";
print OUT "<option value='MN'>Minnesota</option>\n";
print OUT "<option value='MS'>Mississippi</option>\n";
print OUT "<option value='MO'>Missouri</option>\n";
print OUT "<option value='MT'>Montana</option>\n";
print OUT "<option value='NE'>Nebraska</option>\n";
print OUT "<option value='NV'>Nevada</option>\n";
print OUT "<option value='NH'>New Hampshire</option>\n";
print OUT "<option value='NJ'>New Jersey</option>\n";
print OUT "<option value='NM'>New Mexico</option>\n";
print OUT "<option value='NY'>New York</option>\n";
print OUT "<option value='NC'>North Carolina</option>\n";
print OUT "<option value='ND'>North Dakota</option>\n";
print OUT "<option value='OH'>Ohio</option>\n";
print OUT "<option value='OK'>Oklahoma</option>\n";
print OUT "<option value='OR'>Oregon</option>\n";
print OUT "<option value='PA'>Pennsylvania</option>\n";
print OUT "<option value='RI'>Rhode Island</option>\n";
print OUT "<option value='SC'>South Carolina</option>\n";
print OUT "<option value='SD'>South Dakota</option>\n";
print OUT "<option value='TN'>Tennessee</option>\n";
print OUT "<option value='TX'>Texas</option>\n";
print OUT "<option value='UT'>Utah</option>\n";
print OUT "<option value='VT'>Vermont</option>\n";
print OUT "<option value='VA'>Virginia</option>\n";
print OUT "<option value='WA'>Washington</option>\n";
print OUT "<option value='WV'>West Virginia</option>\n";
print OUT "<option value='WI'>Wisconsin</option>\n";
print OUT "<option value='WY'>Wyoming</option>\n";
print OUT "</select></li>\n";
print OUT "</ul>\n";
for ($i = 0; $i < $img; $i++) {
        $tabNum = $i + 1;
        $tabNum = "tabs-" . $tabNum;
        print OUT "<div id='$tabNum'><img src='$images[$i]?" . $cacheBust . "' height='600px' width='800px'></img></div>\n";
}
$tabNum = $i + 1;
$tabNum = "tabs-" . $tabNum;
print OUT "<div id='state'><img src='./states/AL_swody1.png?" . $cacheBust . "' height='600px' width='800px'></img></div>\n";
print OUT "</div>\n";#close tabs
#print OUT "</div>\n";#close pwoImageDiv
print OUT "<div id='popTable'>\n";
#open population table data
$popTable = "./ImpactTable.html";
open SI, '<', $popTable or die $!;
while(<SI>) {
  print OUT "$_\n";
}
print OUT "</div>\n";#close popTable
if ($pwocheck == 1) { #Valid PWO
  print OUT "<div id='PWOdiv'>\n";
  print OUT "<p id='SUMtitle'>Weather Summary:</p>\n";
  print OUT "<p id='PWOtext'>\n";
  foreach (@summary) {
	print OUT "$_\n";
  }
  print OUT "</p><br>\n";

  print OUT "<p id='SUMtitle'>States Affected:</p>\n";
  print OUT "<p id='PWOtext'>\n";
  foreach (@locations) {
        print OUT "$_<br>\n";
  }
  print OUT "</p>\n";

  print OUT "<p id='SUMtitle'>Primary Severe Weather Hazards:</p>\n";
  print OUT "<p id='PWOtext'>\n";
  foreach (@hazards) {
        print OUT "$_<br>\n";
  }
  print OUT "</p>\n";
  print OUT "</div>\n";#close PWOdiv 

} else { #No PWO
  print OUT "<div id='SUMdiv'>\n";
  print OUT "<p id='SUMtitle'>Weather Summary:</p>\n";
  print OUT "<p id='PWOtext'>\n";
  open FILE, '<', $D1sumFile or die $!;

  while(<FILE>) {
    print OUT "$_\n";
  }
  print OUT "</p>\n";
  close(FILE);
  print OUT "</div>\n";#close SUMdiv
}

print OUT "<div id='PStable'>\n";
print OUT "<div id='PSthumb'>What is My Risk?<br><a href='/new/images/Outlook-category-descriptions.png' target='_blank'><img src='/new/images/Outlook-category-descriptions-sm.png' height='174' width='248'></img></a></div>\n";
print OUT "<div id='PSthumb'>What is a Watch?<br><a href='http://www.nws.noaa.gov/cgi-bin/nwsexit.pl?url=http://www.youtube.com/watch?v=x3V3HZBs1Y4' title='What is a Watch?' target='YouTube Clip'><img src='/new/images/WatchWarn.png' height='174' width='260'/></a></div>\n";
print OUT "<div id='PSthumb'>How is a Forecast Made?<a href='http://www.nws.noaa.gov/cgi-bin/nwsexit.pl?url=http://youtu.be/PHMlpv5vJ_Y'  title='How is a Forecast Made?' target='YouTube Clip'><img src='/new/images/ForecastMade.jpg' height='174' width='260'/></a></div>\n";
print OUT "</div>\n";

$date = `date`;
chomp($date);	
print OUT "<div id='dateDiv'>Page Updated: $date</div>\n";
print OUT "</div>\n";#close pwoContainer

close(OUT);

#Let's move some files
#`/users/spcbeta/wwwftp/wwwftp.exp /local/devweb/public_html2/mead/web/PWO/pwo3.html public_html2/new_backup/PWO/pwo3.html`;
#`/users/spcbeta/wwwftp/wwwftp.exp /local/devweb/public_html2/mead/web/PWO/imgs/*.png public_html2/new_backup/PWO/imgs/`;

exit
