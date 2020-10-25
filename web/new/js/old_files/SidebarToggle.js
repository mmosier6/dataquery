$('#toggle').toggle(function(){
	$('#content').animate({width:"90%"});
	$('#right-sidebar').animate({
  	width: "0px",
  	backgroundColor: "#000000"
	},400);
},function(){
	$('#content').animate({width:"50%"});		
  	$('#right-sidebar').animate({
  	width: "200px",
  	backgroundColor: "#ffffff"
	},400);
})	
