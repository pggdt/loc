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
$query="insert into locTest (ID,user_id,latitude,longitude,accuracy,altitude,altitudeAccuracy,heading,speed,locTimestamp,locTime,text,geoCode,insertTime) values (NULL,'$user_id','$latitude','$longitude','$accuracy','$altitude','$altitudeAccuracy','$heading','$speed','$timestamp','$time','$text','$geoCode',UTC_TIMESTAMP())";
$result = mysqli_query($dbc,$query) or die(mysqli_error($dbc));
mysqli_close($dbc);
$response="Record at ".$time." added.";
echo $response;
?>
