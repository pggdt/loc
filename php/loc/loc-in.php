<?php
 require_once('../login/StartSession.php');
 if (!isset($_SESSION['user_id'])) {
 	header("refresh:1;url=../login/login.php");
  	exit; 
 }
?>
<?php
require_once('../connectvars.php');
$latitude=$_POST["latitude"];$longitude=$_POST["longitude"];
$accuracy=$_POST["accuracy"];$altitude=$_POST["altitude"];
$altitudeAccuracy=$_POST["altitudeAccuracy"];$heading=$_POST["heading"];
$speed=$_POST["speed"];$timestamp=$_POST["timestamp"];
$time=$_POST["time"];$text=$_POST["text"];$geoCode=$_POST["geoCode"];
$user_id=$_SESSION['user_id'];
if(empty($timestamp)&&empty($text)){
	exit();
	}
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (mysqli_connect_errno($dbc))
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
mysqli_set_charset($dbc, "utf8") or die('Could not connect: ' . mysqli_error($dbc));
$latitude=mysqli_real_escape_string($dbc,$latitude);
$longitude=mysqli_real_escape_string($dbc,$longitude);
$accuracy=mysqli_real_escape_string($dbc,$accuracy);
$altitude=mysqli_real_escape_string($dbc,$altitude);
$altitudeAccuracy=mysqli_real_escape_string($dbc,$altitudeAccuracy);
$heading=mysqli_real_escape_string($dbc,$heading);
$speed=mysqli_real_escape_string($dbc,$speed);
$timestamp=mysqli_real_escape_string($dbc,$timestamp);
$time=mysqli_real_escape_string($dbc,$time);
$text=mysqli_real_escape_string($dbc,$text);
$geoCode=mysqli_real_escape_string($dbc,$geoCode);
$query="insert into locTest (ID,user_id,latitude,longitude,accuracy,altitude,altitudeAccuracy,heading,speed,locTimestamp,locTime,text,geoCode,insertTime) values (NULL,'$user_id','$latitude','$longitude','$accuracy','$altitude','$altitudeAccuracy','$heading','$speed','$timestamp','$time','$text','$geoCode',UTC_TIMESTAMP())";
$result = mysqli_query($dbc,$query) or die(mysqli_error($dbc));
mysqli_close($dbc);
$response="Record at ".$time." added.";
echo $response;
?>
