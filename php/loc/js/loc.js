var xmlHttp

function send(){
xmlHttp=GetXmlHttpObject()
if (xmlHttp==null){
  alert ("Browser does not support HTTP Request");
  return;
  }
var latitude = document.getElementById("latitude").innerHTML;
var longitude = document.getElementById("longitude").innerHTML; 
var accuracy = document.getElementById("accuracy").innerHTML; 
var altitude = document.getElementById("altitude").innerHTML; 
var altitudeAccuracy = document.getElementById("altitudeAccuracy").innerHTML; 
var heading = document.getElementById("heading").innerHTML; 
var speed = document.getElementById("speed").innerHTML; 
var timestamp = document.getElementById("timestamp").innerHTML;
var locTime = document.getElementById("time").innerHTML;
if(timestamp===""||locTime===""){
  timestamp=new Date().getTime();
  locTime=parseTimestamp(timestamp);
}
var geoCode = document.getElementById("geocode").innerHTML;  
var locText = document.getElementById("text").value; 
var url="loc-in.php";
var postStr="latitude="+latitude+"&longitude="+longitude+"&accuracy="+accuracy+"&altitude="+altitude+"&altitudeAccuracy="+altitudeAccuracy+"&heading="+heading+"&speed="+speed+"&timestamp="+timestamp+"&time="+locTime+"&text="+locText+"&geoCode="+geoCode;
xmlHttp.open("POST",url,true);
xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlHttp.send(postStr);
document.getElementById('load').style.visibility='visible';
xmlHttp.onreadystatechange=function(){
	if (xmlHttp.readyState==4 || xmlHttp.status==200){
		document.getElementById('load').style.visibility='hidden';
		document.getElementById("location").innerHTML=xmlHttp.responseText;
		}
	}
} 

function GetXmlHttpObject(){
	var xmlHttp=null;
	try{
	 xmlHttp=new XMLHttpRequest();
	 }
	catch (e){
		 try{
		  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		  }
		 catch (e){
		  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
	 }
	return xmlHttp;
}