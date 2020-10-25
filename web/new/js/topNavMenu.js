 //The following var assigned so jQuery plays nice with prototype.js
var $j = jQuery.noConflict();
$j(document).ready(function() {
				function megaHoverOver(){
					$j(this).find(".sub").css('opacity', '1').show();
			
				//Calculate width of all ul's
				(function($) { 
					jQuery.fn.calcSubWidth = function() {						
						rowWidth = 0;
						//Calculate row
						$j(this).find("ul").each(function() {					
							rowWidth += $j(this).width(); 
						});	
					};
				})(jQuery); 
		
				if ( $j(this).find(".row").length > 0 ) { 
					//If row exists...
					var biggestRow = 0;	
					//Calculate each row
					$j(this).find(".row").each(function() {							   
						$j(this).calcSubWidth();
						//Find biggest row
						if(rowWidth > biggestRow) {
							biggestRow = rowWidth;
						}
					});
			
					//Set width
					$j(this).find(".sub").css({'width' :biggestRow});
					$j(this).find(".row:last").css({'margin':'0'});
				} 
				else { 
					//If row does not exist...
					$j(this).calcSubWidth();
			
					//Set Width
					$j(this).find(".sub").css({'width' : rowWidth});
				}
			}
	
			function megaHoverOut(){ 			
				$j(this).find(".sub").css('opacity','0').hide();				
			}
			
		
		function closePreviewNotice () {
			$j("#preview-notice").slideToggle();
			return false;
		}
		

		var config = {    
		 	sensitivity: 1, // number = sensitivity threshold (must be 1 or higher)    
		 	interval: 100, // number = milliseconds for onMouseOver polling interval    
		 	over: megaHoverOver, // function = onMouseOver callback (REQUIRED)    
		 	timeout: 100, // number = milliseconds delay before onMouseOut    
		 	out: megaHoverOut // function = onMouseOut callback (REQUIRED)    
		};
                
		$j("ul#topnav li .sub").css({'opacity':'0'});
		$j("ul#topnav li").hoverIntent(config);
		$j("ul#subMenuNav li").hoverIntent(config);
		closePreviewNotice();
		$j("#preview-notice-close").click(closePreviewNotice);


		});
