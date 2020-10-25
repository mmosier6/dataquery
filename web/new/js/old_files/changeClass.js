if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)){ 
//test for MSIE x.x;
 var ieversion=new Number(RegExp.$1) // capture x.x portion and store as a number
 
 if (ieversion<=6)
 {
  
document.write("<h4 style='color:red'>This site is optimized for use with <a href='http://www.getfirefox.com'>Firefox</a> version 3.6 or higher and <a href='http://www.microsoft.com/windows/internet-explorer/default.aspx'>Internet Explorer</a> version 7 or higher.</h4><p>For an optimal user experience, please utilize a newer version of either of these browsers.</p>")
 }
 
}


//to get rid of rollover focuses


function setLI()//Adds the onClick event to all <li>'s in the document
{
li = document.getElementsByTagName('li');
for(i=0; i<li.length; i++)
	{
		li[i].onclick = function()
		{
		linkFocus(i);
		}
	}
}



function changeClass(id)
{
	document.getElementById(id).style.display='block';
	
}
function changeAgain(id)
{
	document.getElementById(id).style.display='none';
}