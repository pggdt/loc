<?php 
 require_once('../login/StartSession.php');
 if (!isset($_SESSION['user_id'])) {
 	header("refresh:1;url=../login/login.php");
  	exit; 
 }
?> 
<?php
require_once('../connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (mysqli_connect_errno($dbc))
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
mysqli_set_charset($dbc, "utf8") or die('Could not connect: ' . mysqli_error($dbc));
//$con=mysql_connect("mysql.freeidc.me","u210397065_loc","147147");
//if(!$con){die('Could not connect: '.mysql_error());
//	}
//mysql_query("set names utf8");
//mysql_select_db("u210397065_loc",$con);
$user_id=$_SESSION['user_id'];
$result = mysqli_query($dbc,"SELECT * FROM locTest WHERE user_id='$user_id' ORDER BY id DESC LIMIT 10");
//$sql="INSERT INTO `u210397065_loc`.`locTest` (`ID`, `latitude`, `longitude`, `accuracy`, `altitude`, `altitudeAccuracy`, `heading`, `speed`, `locTimestamp`, `locTime`, `text`, `insertTime`) VALUES (NULL,'$latitude','$longitude','$accuracy','$altitude','$altitudeAccuracy','$heading','$speed','$timestamp','$time','$text',UTC_TIMESTAMP())";
//if (!mysql_query($sql,$con)){
//  die('Error: ' . mysql_error());
//  }

echo "<table border='1'>
<tr>
<th>latitude</th>
<th>longitude</th>
<th>accuracy</th>
<th>altitude</th>
<th>altitudeAccuracy</th>
<th>heading</th>
<th>speed</th>
<th>locTime</th>
<th>text</th>
</tr>";

while($row = mysqli_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['latitude'] . "</td>";
  echo "<td>" . $row['longitude'] . "</td>";
  echo "<td>" . $row['accuracy'] . "</td>";
  echo "<td>" . $row['altitude'] . "</td>";
  echo "<td>" . $row['altitudeAccuracy'] . "</td>";
  echo "<td>" . $row['heading'] . "</td>";
  echo "<td>" . $row['speed'] . "</td>";
  echo "<td>" . $row['locTime'] . "</td>";
  echo "<td>" . $row['text'] . "</td>";
  echo "</tr>";
  }
echo "</table>";

mysqli_close($dbc);
?>