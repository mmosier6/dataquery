var j = jQuery.noConflict();

$j(document).ready( function() {
	$j( "#tabs" ).tabs();
});

$j(function () {
  $j('select').change(function () {
     var cacheBust = (new Date()).valueOf();
     map = $j("#maps").val();
     $j('#state').html("<img src='./states/" + map + "_swody1.png?" + cacheBust + "' height='600px' width='800px'></img>");
  	
  });
});

