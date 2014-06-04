<html>
<head>
<title>Location-Map</title>
<script
src="<?php 
if(isset($_GET['s'])&&isset($_GET['h'])&&$_GET["s"]!=0){
    echo 'http://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyDfYwZAVajNuT6_mcTYwY6_Jxdsg3q-3tI&sensor=false';
} else {
    echo 'http://maps.googleapis.com/maps/api/js?key=AIzaSyDfYwZAVajNuT6_mcTYwY6_Jxdsg3q-3tI&sensor=false';
}?>">
</script>
 
<script>
var myCenter=new google.maps.LatLng(<?php echo $_GET["a"].",".$_GET["n"]; ?>);
function initialize()
{
  var mapProp = {
    center: myCenter,
    zoom:16,
    mapTypeId: google.maps.MapTypeId.<?php echo $_GET["t"]; ?>
  };

  var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
  
  var marker=new google.maps.Marker({
  position:myCenter,
  });
  
  var myCity = new google.maps.Circle({
  center:myCenter,
  radius:<?php echo $_GET["r"]; ?>,
  strokeColor:"#0000FF",
  strokeOpacity:0.5,
  strokeWeight:1,
  fillColor:"#0000FF",
  fillOpacity:0.2
  });
<?php
if(isset($_GET['s'])&&isset($_GET['h'])&&$_GET["s"]!=0){
    $s=$_GET['s']*10;
    $h=$_GET['h']+180;
    echo 'var startLL=new google.maps.geometry.spherical.computeOffset(myCenter, '.$s.', '.$h.');';
    echo 'var myTrip=[startLL,myCenter];';
    echo 'var flightPath=new google.maps.Polyline({
  path:myTrip,
  strokeColor:"#0000FF",
  strokeOpacity:0.8,
  strokeWeight:2
  });';
    echo 'flightPath.setMap(map);';
}
?>
  
  marker.setMap(map);
  myCity.setMap(map);
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>

<body>

<div id="googleMap" style="width:400px;height:300px;"></div>
<br>

<?php include_once("../analyticstracking.php") ?></body>
</body>
</html>
