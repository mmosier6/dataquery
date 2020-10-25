/*=====================================================================
jQuery.noConflict() is utilized due to a conflict between
prototype.js and the jquery library
=====================================================================*/

/*-----------------------------------
Function for the tabbed overview
-----------------------------------*/
function show_tab(nam) {
	var $j = jQuery.noConflict();
	$j("#TABoverview").hide();
	$j("#TABoverviewT").removeClass('Tyellow');
	$j("#TABday1").hide();
	$j("#TABday1T").removeClass('Tyellow');
	$j("#TABwatch").hide();
	$j("#TABwatchT").removeClass('Tyellow');
	$j("#TABmcd").hide();
	$j("#TABmcdT").removeClass('Tyellow');
	$j("#TABreport").hide();
	$j("#TABreportT").removeClass('Tyellow');
	$j("#TABmeso").hide();
	$j("#TABmesoT").removeClass('Tyellow');
	$j("#TABfirewx").hide();
	$j("#TABfirewxT").removeClass('Tyellow');
	$j("#TABwwa1").hide();
	$j("#TABwwa1T").removeClass('Tyellow');
	$j("#"+nam).show();
	$j("#"+nam+"T").addClass('Tyellow');
	}

/*-------------------------------------------------------------------------------
Function to update the tabbed overview graphics every 60 sec.  This function also
appends a date tag on the images so there are no caching issues.
-------------------------------------------------------------------------------*/
function updateImgs() {
        var $j = jQuery.noConflict();
        var d = new Date();
        $j("#activity").attr("src", "/products/activity_loop.gif?"+d.getTime());
        $j("#dy1otlk").attr("src", "/products/outlook/day1otlk-overview.gif?"+d.getTime());
        $j("#curww").attr("src", "/products/watch/validww.png?"+d.getTime());
        $j("#curmcd").attr("src", "/products/md/validmd.png?"+d.getTime());
        $j("#reports").attr("src", "/climo/reports/today.gif?"+d.getTime());
        $j("#meso").attr("src", "/exper/mesoanalysis/sectorloop2.gif?"+d.getTime());
        $j("#fire").attr("src", "/products/fire_wx/day1fireotlk-overview.png?"+d.getTime());
        $j("#wwa").attr("src", "http://forecast.weather.gov/wwamap/png/US.png?"+d.getTime());
}

var ImageUpdate = setInterval("updateImgs()", 60000);

/*---------------------------------------------------------------------------------------------------
 Function to update the content in the tabbed product section every 60 sec.  This function also 
appends a date tag on the images so there are no caching issues. Use +d.getTime()) to prevent
browser caching of images and static html pages.
---------------------------------------------------------------------------------------------------*/ 
function updateProducts() {
	var $j = jQuery.noConflict();
	$j.ajax({
		url: "/new/SPCProducts/validProducts.php?content=0",
		success: function(data) {
	 		var $j = jQuery.noConflict();
			var d = new Date();
			$j("#allProd").html(data);
			$j("#SWODY1img").attr("src", "/products/outlook/day1otlk_sm.gif?"+d.getTime());
                        $j("#SWODY2img").attr("src", "/products/outlook/day2otlk_sm.gif?"+d.getTime());
                        $j("#SWODY3img").attr("src", "/products/outlook/day3otlk_sm.gif?"+d.getTime());
                        $j("#SWODY48img").attr("src", "/products/exper/day4-8/day48prob_small.gif?"+d.getTime());
			$j("#ENHimg").attr("src", "/products/exper/enhtstm/enh_small.gif?"+d.getTime());
			$j("#FIRE1img").attr("src", "/products/fire_wx/day1fireotlk_sm.png?"+d.getTime());
			$j("#FIRE2img").attr("src", "/products/fire_wx/day2fireotlk_sm.png?"+d.getTime());
			$j("#FIRE38img").attr("src", "/products/exper/fire_wx/day3-8fireotlk_sm.png?"+d.getTime());
			}
		});

	$j.ajax({
                url: "/new/SPCProducts/validProducts.php?content=1",
                success: function(data) {
                        var $j = jQuery.noConflict();
                        $j("#allWW").html(data);
                        }
                });

	$j.ajax({
                url: "/new/SPCProducts/validProducts.php?content=2",
                success: function(data) {
                        var $j = jQuery.noConflict();
                        $j("#allMD").html(data);
                        }
                });

	$j.ajax({
                url: "/new/SPCProducts/validProducts.php?content=3",
                success: function(data) {
                        var $j = jQuery.noConflict();
			var d = new Date();
                        $j("#allOTLK").html(data);
			$j("#SWODY1img").attr("src", "/products/outlook/day1otlk_sm.gif?"+d.getTime());
			$j("#SWODY2img").attr("src", "/products/outlook/day2otlk_sm.gif?"+d.getTime());
			$j("#SWODY3img").attr("src", "/products/outlook/day3otlk_sm.gif?"+d.getTime());
			$j("#SWODY48img").attr("src", "/products/exper/day4-8/day48prob_small.gif?"+d.getTime());
			$j("#ENHimg").attr("src", "/products/exper/enhtstm/enh_small.gif?"+d.getTime());
                        }
                });

	$j.ajax({
                url: "/new/SPCProducts/validProducts.php?content=4",
                success: function(data) {
                        var $j = jQuery.noConflict();
			var d = new Date();
                        $j("#allFIRE").html(data);
			$j("#FIRE1img").attr("src", "/products/fire_wx/day1fireotlk_sm.png?"+d.getTime());
                        $j("#FIRE2img").attr("src", "/products/fire_wx/day2fireotlk_sm.png?"+d.getTime());
                        $j("#FIRE38img").attr("src", "/products/exper/fire_wx/day3-8fireotlk_sm.png?"+d.getTime());
                        }
                });

	$j.ajax({
                url: "/new/SPCProducts/validProductsCSS.php",
                success: function(data) {
			//alert(data);
                        var $j = jQuery.noConflict();
                        $j("#prodCSS").html(data);
                        }
                });
	
	$j.ajax({
                url: "/new/Matrix/mx.php",
                success: function(data) {
                        var $j = jQuery.noConflict();
                        $j("#matrixContainer").html(data);
                        }
                });

	$j.ajax({
                url: "/new/SPCWeatherHeadlines/headlines-zz.html",
                success: function(data) {
                        var $j = jQuery.noConflict();
                        $j("#headlineContainer").html(data);
                        }
                });

        }

/*-----------------------------------------------------------
Function to update valid products
------------------------------------------------------------*/
var SPCInterval = setInterval("updateProducts()", 60000);
var $j = jQuery.noConflict();
$j(document).ready( function() {
        updateProducts();
        });

/*--------------------------------------------------------------------
Function to update the content panels below the overview graphics and
product alerts.  This function updates every 5 minutes.
--------------------------------------------------------------------*/
setInterval(function(){
	var $j = jQuery.noConflict();
	$j('#contentCheck').load('/new/PanelContent/panelContent.php');
}, 300000);

/*--------------------------------------------------------------------
Pulsing button
--------------------------------------------------------------------*/
function pulse(elem, duration, easing, props_to, props_from, until) {
   elem.animate( props_to, duration, easing,
   function() {
      if ( until () == false )
      {
         pulse(elem, duration, easing, props_from, props_to, until);
      }
   });
}

