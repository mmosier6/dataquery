<!--

// JavaScript Document
var xmlHttp;
var startResponse;
var serverTime;
var refresher;
var lag;
var DST;

function GetXmlHttpObject()
{
  var xmlHttp=null;
  
  if (window.XMLHttpRequest) { 
    xmlHttp = new XMLHttpRequest();
  } else if (window.ActiveXObject) {
    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  return xmlHttp;
}


function stateChanged() 
{ 
  if (xmlHttp.readyState==4){ 
    lag = (new Date().getTime() - startResponse)/2;
    serverTime = parseFloat(xmlHttp.responseXML.documentElement.firstChild.firstChild.nodeValue);
    DST = xmlHttp.responseXML.documentElement.firstChild.attributes[0].value;
    serverTime = serverTime + 1000 + lag;
    setTimeout("incTime()", (2000-serverTime%1000));
  }
}

function showTime()
{
  document.getElementById("USNOtime").innerHTML="Loading...";
  xmlHttp=GetXmlHttpObject();
  if (xmlHttp==null){
    document.getElementById("USNOtime").innerHTML="Sorry, browser incapatible.";
    return;
  } 
  refresher = 0;
  startResponse = new Date().getTime();
  var url="/local/spcwebsite/cgi-bin-spc/utcsec.pl?n="+ startResponse;
  xmlHttp.onreadystatechange=stateChanged;
  xmlHttp.open("GET",url,true);
  xmlHttp.send(null);
} 

function incTime()
{
  refresher++;
  serverTime=Math.floor(serverTime-(serverTime%1000)+1000);
  var d = new Date();
  d.setTime(serverTime);
  var hh = d.getUTCHours();
  if (hh < 10) hh = "0" + hh;
  var mm = d.getUTCMinutes();
  if (mm < 10) mm = "0" + mm;
  var ss = d.getUTCSeconds();
  if (ss < 10) ss = "0" + ss;
  
  var tempString = d.toUTCString();
// JL  document.getElementById("USNOtime").innerHTML=tempString.substring(17, tempString.lastIndexOf(" "));
  document.getElementById("USNOtime").innerHTML=hh + ":" + mm + ":" + ss;
  
  if (refresher > 180){
    showTime();
  }else{
    setTimeout("incTime()", 1000);
  }
}

window.onload = showTime;

//-->
