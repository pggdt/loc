<?php
 require_once('../login/StartSession.php');
 if (!isset($_SESSION['user_id'])) {
 	header("refresh:1;url=../login/login.php");
  	exit; 
 }
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8" />

<title>html5地理位置</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script stype="text/javascript" src="js/loc.js"></script>
<script
src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDfYwZAVajNuT6_mcTYwY6_Jxdsg3q-3tI&sensor=false">
</script>
</head>

<body style="padding:0px 10px;width:320px;height:500px;">

	<dl>
	<dt>Latitude</dt><dd id="latitude"></dd>
	<dt>Longitude</dt><dd id="longitude"></dd>
	<dt>Accuracy</dt><dd id="accuracy"></dd>
	<dt>Altitude</dt><dd id="altitude"></dd>
	<dt>Altitude Accuracy</dt><dd id="altitudeAccuracy"></dd>
	<dt>Heading</dt><dd id="heading"></dd>
	<dt>Speed</dt><dd id="speed"></dd>
	<dt>Timestamp</dt><dd id="timestamp"></dd>
	<dt>Time</dt><dd id="time"></dd>
	<dt id="codeSrc" onclick="changeCodeSrc()">noCode</dt><dd id="geocode"></dd>
	</dl>
<div id="load" style="visibility:hidden"></div>	
<p id="location"></p>
<input type="button" value="写入数据库" onclick="send()">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="获取当前位置" onclick="getLocation(showPosition)"><br />
Text:<br /><textarea rows="10" cols="30" id="text" name="text"></textarea><br />
	
<a href="loc-outi.php" target="_blank">Records</a>&nbsp;&nbsp;&nbsp;&nbsp;
<a id="mapLink" href="#" target="_blank"></a>&nbsp;&nbsp;&nbsp;&nbsp;
<a id="sateLink" href="#" target="_blank"></a>

<script type="text/javascript">
	var geocoder;
	var x=document.getElementById("location");
	var parseTimestamp = function(timestamp) {
	var d = new Date(timestamp);
	var day = d.getDate();
	var month = d.getMonth() + 1;
	var year = d.getFullYear();
	return year + "年" + month +"月"+ day +"日 "+ d.toLocaleTimeString();
	};
	function getLocation(func1)
	  {
	  if (navigator.geolocation)
		{
		navigator.geolocation.getCurrentPosition(func1,showError);
		document.getElementById('load').style.visibility='visible';
		}
	  else{x.innerHTML="Geolocation is not supported by this browser.";}
	  }
	
	function showPosition(position){
	
	  document.getElementById('load').style.visibility='hidden';
	  document.getElementById("latitude").innerHTML=position.coords.latitude;
	  document.getElementById("longitude").innerHTML=position.coords.longitude;
	  document.getElementById("accuracy").innerHTML=position.coords.accuracy;
	  document.getElementById("altitude").innerHTML=position.coords.altitude;
	  document.getElementById("altitudeAccuracy").innerHTML=position.coords.altitudeAccuracy;
	  document.getElementById("heading").innerHTML=position.coords.heading;
	  document.getElementById("speed").innerHTML=position.coords.speed;
	  document.getElementById("timestamp").innerHTML=position.timestamp.valueOf();
	  document.getElementById("time").innerHTML=parseTimestamp(position.timestamp);
	      if(position.coords.heading!=null){
	      document.getElementById('mapLink').href="map.php?a="+position.coords.latitude+"&n="+position.coords.longitude+"&r="+position.coords.accuracy+"&t=ROADMAP&h="+position.coords.heading+"&s="+position.coords.speed;
	      document.getElementById('sateLink').href="map.php?a="+position.coords.latitude+"&n="+position.coords.longitude+"&r="+position.coords.accuracy+"&t=HYBRID&h="+position.coords.heading+"&s="+position.coords.speed;
	      } else {
	      document.getElementById('mapLink').href="map.php?a="+position.coords.latitude+"&n="+position.coords.longitude+"&r="+position.coords.accuracy+"&t=ROADMAP";
	      document.getElementById('sateLink').href="map.php?a="+position.coords.latitude+"&n="+position.coords.longitude+"&r="+position.coords.accuracy+"&t=HYBRID";
	      }
	  document.getElementById('mapLink').innerHTML="Map";
	  document.getElementById('sateLink').innerHTML="Sate";
	  if("ggCode"==document.getElementById("codeSrc").innerHTML){
		  var latlng= new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
		  geocoder= new google.maps.Geocoder();
		  geocoder.geocode({'latLng': latlng}, function(results, status) {
			  if (status == google.maps.GeocoderStatus.OK) {
				if (results[1]) {
				  document.getElementById("geocode").innerHTML=results[1].formatted_address;
				}
			  } else {
			  	document.getElementById("geocode").innerHTML="Geocoder failed due to: " + status;
			  }
			});
		  } else {
		    document.getElementById("geocode").innerHTML=""
		  }
	  }
	function showError(error){
	  switch(error.code) 
		{
		case error.PERMISSION_DENIED:
		  x.innerHTML="User denied the request for Geolocation."
		  break;
		case error.POSITION_UNAVAILABLE:
		  x.innerHTML="Location information is unavailable."
		  break;
		case error.TIMEOUT:
		  x.innerHTML="The request to get user location timed out."
		  break;
		case error.UNKNOWN_ERROR:
		  x.innerHTML="An unknown error occurred."
		  break;
		}
	  }
	  
	function changeCodeSrc(){
	  switch(document.getElementById("codeSrc").innerHTML){
	    case "noCode":
	    	document.getElementById("codeSrc").innerHTML="ggCode";
	    	break;
	    case "ggCode":
	    	document.getElementById("codeSrc").innerHTML="noCode";
	    	break;
	  }
	}
	
</script>
<?php include_once("../analyticstracking.php") ?></body>
</html>
