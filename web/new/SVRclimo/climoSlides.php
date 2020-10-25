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
		$j('#slides').slides({
			fadeSpeed: 1000,
			crossfade: true,
			effect: 'fade',
			preload: true,
			preloadImage: '/new/SVRclimo/loading.gif',
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
      <div id="slides">
	 <div class="slides_container">
	    <div class="slide">
	       <a href="/misc/climographics/any_severe/<?php print "$file";?>" title="Any Severe for <?php print "$date";?>" target="_blank" alt="Slide 1"><img src="/misc/climographics/any_severe/<?php print "$smfile";?>"></a>
	       <div class="caption" style="bottom:0"><p>Any Severe Probabilities: <?php print "$date";?></p></div>
	    </div>
	    <div class="slide">
	       <a href="/misc/climographics/all_torn/<?php print "$file";?>" target="_blank"><img src="/misc/climographics/all_torn/<?php print "$smfile";?>"></a>
	       <div class="caption" style="bottom:0"><p>Tornado Probabilities: <?php print "$date";?></p></div>
	    </div>
	    <div class="slide">
               <a href="/misc/climographics/all_wind/<?php print "$file";?>" target="_blank"><img src="/misc/climographics/all_wind/<?php print "$smfile";?>"></a>
               <div class="caption" style="bottom:0"><p>Damaging Wind Probabilities: <?php print "$date";?></p></div>
            </div>
            <div class="slide">
               <a href="/misc/climographics/all_hail/<?php print "$file";?>" target="_blank"><img src="/misc/climographics/all_hail/<?php print "$smfile";?>"></a>
               <div class="caption" style="bottom:0"><p>Severe Hail Probabilities: <?php print "$date";?></p></div>
	    </div>
	    <div class="slide">
               <a href="/misc/climographics/sig_torn/<?php print "$file";?>" target="_blank"><img src="/misc/climographics/sig_torn/<?php print "$smfile";?>"></a>
               <div class="caption" style="bottom:0"><p>Significant Tornado Probabilities: <?php print "$date";?></p></div>
            </div>
            <div class="slide">
               <a href="/misc/climographics/sig_wind/<?php print "$file";?>" target="_blank"><img src="/misc/climographics/sig_wind/<?php print "$smfile";?>"></a>
               <div class="caption" style="bottom:0"><p>Significant Wind Probabilities: <?php print "$date";?></p></div>
            </div>
            <div class="slide">
               <a href="/misc/climographics/sig_hail/<?php print "$file";?>" target="_blank"><img src="/misc/climographics/sig_hail/<?php print "$smfile";?>"></a>
               <div class="caption" style="bottom:0"><p>Significant Hail Probabilities: <?php print "$date";?></p></div>
            </div>
         </div>
      </div>
   </div>
</div>
