<?php
//////////////////////////////////////////////////////////////////////
// This script is called by PanelContent/panelContent.php and used  //
// to display a slide show of the severe weather probabilities for  //
// the current day.                                                 //  
// 12/2012 CMM                                                      //
//////////////////////////////////////////////////////////////////////

$YYYY = `date -u +%Y`;
$MM = `date -u +%b`;
$DD = `date -u +%d`;
$date = $DD . " " . $MM;

$julian = `date -u +%j`;

//logic to remove leap year graphic.  Will need to be modified in 2016 (leap year)
if ($julian < 60) {
	$julian2 = $julian;
} elseif ($julian >= 60) {
	$julian2 = $julian +1;
}

//format new number correctly

$numCheck = substr($julian2,0,1);

if (($julian2 < 100) && ($numCheck == 0)) { $number = $julian2; }
elseif (($julian2 < 100) && ($numCheck != 0)) { $number = "0" . $julian2; }
elseif ($julian2 >=100) { $number = $julian2; }

$file = $number . ".png";
$smfile = "sm_" . $number . ".png";

?>

<script src="/new/js/slides.min.jquery.js"></script>
<script>
	var $j = jQuery.noConflict();
	$j(function(){
		$j('#slides3').slides({
			fadeSpeed: 1000,
			crossfade: true,
			effect: 'fade',
			preload: true,
			preloadImage: '/new/FWclimo/loading.gif',
			play: 7000,
			pause: 2500,
			hoverPause: true,
			animationStart: function(current){
				$j('.caption').animate({
					bottom:-35
				},100);
				if (window.console && console.log) {
					// example return of current slide number
					console.log('animationStart on slide: ', current);
				};
			},
			animationComplete: function(current){
				$j('.caption').animate({
					bottom:0
				},200);
				if (window.console && console.log) {
					// example return of current slide number
					console.log('animationComplete on slide: ', current);
				};
			},
			slidesLoaded: function() {
				$j('.caption').animate({
					bottom:0
				},200);
			}
		});
	});
</script>

<div id="spc1">
   <div id="example">
      <div id="slides3">
	 <div class="slides_container">
	    <div class="slide">
	       <a href="/misc/fire_climo/Figs_Climo100ac/<?php print "$file";?>" title="100 acres for <?php print "$date";?>" target="_blank" alt="Slide 1"><img src="/misc/fire_climo/Figs_Climo100ac/<?php print "$smfile";?>"></a>
	       <div class="caption" style="bottom:0"><p>100 Acres Wildfire Probabilities: <?php print "$date";?></p></div>
	    </div>
	    <div class="slide">
	       <a href="/misc/fire_climo/Figs_Climo300ac/<?php print "$file";?>" target="_blank"><img src="/misc/fire_climo/Figs_Climo300ac/<?php print "$smfile";?>"></a>
	       <div class="caption" style="bottom:0"><p>300 Acres Wildfire Probabilities: <?php print "$date";?></p></div>
	    </div>
	    <div class="slide">
               <a href="/misc/fire_climo/Figs_Climo1000ac/<?php print "$file";?>" target="_blank"><img src="/misc/fire_climo/Figs_Climo1000ac/<?php print "$smfile";?>"></a>
               <div class="caption" style="bottom:0"><p>1000 Acres Wildfire Probabilities: <?php print "$date";?></p></div>
            </div>
            <div class="slide">
               <a href="/misc/fire_climo/Figs_Climo5000ac/<?php print "$file";?>" target="_blank"><img src="/misc/fire_climo/Figs_Climo5000ac/<?php print "$smfile";?>"></a>
               <div class="caption" style="bottom:0"><p>5000 Acres Wildfire Probabilities: <?php print "$date";?></p></div>
	    </div>
         </div>
      </div>
   </div>
</div>
