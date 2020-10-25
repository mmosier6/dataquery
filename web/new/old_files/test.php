<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="DC.title" content="NOAA/NWS Storm Prediction Center" />
    <meta name="DC.language" scheme="DCTERMS.RFC1766" content="EN-US" />
    <meta name="language" content="EN-US" />
    <title>NOAA/NWS Storm Prediction Center</title>

    <!-- Sitewide css and scripts -->
    <link rel="stylesheet" href="/new/css/SPCmain.css" />


    <!--Conflict between prototype.js and jquery, so using jQuery.noConflict() -->
    <script src="//ajax.googleapis.com/ajax/libs/prototype/1.7.1.0/prototype.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="/new/js/SPCindex.js"></script>
    <script type="text/javascript" src="/misc/usno_gmttime.js"></script>
    <script type="text/javascript" src="/misc/lastMod.js"></script>
    <script type="text/javascript" src="/new/js/ProdTab.js"></script>
    <script type="text/javascript" src="/new/js/jquery.hoverIntent.minified.js"></script>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>

    <script type="text/javascript">
function MM_jumpMenu(targ,selObj,restore)
{
        //v3.0
        eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
        if (restore) selObj.selectedIndex=0;
}
</script>


<script type="text/javascript" src="/new/js/topNavMenu.js"></script>

</head>

<body>
<div id="SPCWrapper">
   <div id="SPCMain">
      <div class="SPCMain2">
         <!-- ##### SPC TOP MENU ##### -->
         <?php include("/local/spcwebsite/new/MainMenu/topMenu.html.testing"); ?>

         <!-- ##### WEATHER HEADLINES (if warranted) ##### -->
         <?php include("./SPCWeatherHeadlines/headlines.html.testing"); ?>

         <div class="LeftCol" > <!-- Left-side 585px wide column -->

            <!-- ##### SPC QUICK VIEW ( IMAGE DISPLAY ) ##### -->
            <?php include("/local/spcwebsite/new/SPCQuickView/viewer.html.testing"); ?>

            <!-- ##### SPC OUTLOOK MATRIX ##### -->
            <?php include("/local/spcwebsite/new/Matrix/mx.php"); ?>
            <br />


         <div> <!-- LeftCol -->
      </div> <!-- SPCMain2 -->
   </div> <!-- SPCMain -->

   <!-- ##### SPC PRODUCT ALERTS ##### -->
   <div class="RightCol">
      <div class="content_container_prods">
            <?php include("/local/spcwebsite/new/SPCProducts/validProducts.html"); ?>
        </div>
   </div>

<div class="feature_container">
    <!-- ##### 6-panel ##### -->

<?php
//first open valid products file to determine operational mode of mainpage

$prodFile = file("/local/spcwebsite/new/SPCProducts/validProducts.txt");

///Hazard Levels///
//0 = No Thunder //
//1 = No Severe  //
//2 = See Text   //
//3 = Slight     //
//4 = Moderate   //
//5 = High       //
///////////////////

foreach($prodFile as $line) { 
	$tline = trim($line);
	$data = explode("|",$tline);
        $prodType = $data[0];
	$otlkCat = $data[3];
	if ($prodType == "OTLK" ) {
		if ($otlkCat == "High") { $hazard = 5; }	
	        elseif ($otlkCat == "Moderate") { $hazard = 4; }
		elseif ($otlkCat == "Slight") { $hazard = 3; }
		elseif ($otlkCat == "See Text") { $hazard = 2; }
		elseif ($otlkCat == "No Severe") { $hazard = 1; }
		elseif ($otlkCat == "No Thunder") { $hazard = 0; }
	}
	$strCheck = substr($prodType,0,2);
        if ($strCheck == "WW") {
                $ww = substr($prodType,0,2);
                $wwNum = substr($prodType,2,4);
        if ($wwNum != "none") { $validWW = 1; }
	else { $validWW = 0; }
	} 
}

if (($hazard > "3") || ($validWW == 1)) {
	include("/local/spcwebsite/new/6pnl_active.php");
} else { include("/local/spcwebsite/new/9pnl_quiet.php");
}

?>

</div> <!-- SPCMain -->




   <!-- ##### FOOTER ACROSS BOTTOM OF PAGE ##### -->
   <div class="footer_container">
      <div id="footer">
        <?php include("/local/spcwebsite/new/footer/footer1.html"); ?>

        Page last modified:
        <!--#config timefmt="%B %d, %Y"-->
        <!--#config errmsg=""-->
        <?php include("/local/spcwebsite/new/footer/footer2.html"); ?>
</div>
   </div>

<div class="clear"> </div> <!-- Sets background to white -->

 </div>
<script type="text/javascript">
   var $j = jQuery.noConflict();
   show_tab("TABoverview");
   $j("#query-field").blur();
</script>
</body>
</html>

