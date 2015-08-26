<?php
header('Content-type: text/json');
header("Cache-Control: no-cache, must-revalidate");

$lat = $_POST["lat"];
$lng = $_POST["lng"];
$cvt = $_POST["cvt"];
$url = "";
if ($cvt) {
    $url = "http://api.zdoz.net/transgps.aspx?lat=" . $lat . "&lng=" . $lng;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    $content = curl_exec($ch);
    curl_close($ch);
    $obj = json_decode($content);
    $lat = $obj->Lat;
    $lng = $obj->Lng;
}

if (! empty($_POST['lang'])) {
    $lang = $_POST["lang"];
    $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $lat . "," . $lng . "&sensor=true&language=" . $lang;
} else {
    $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $lat . "," . $lng . "&sensor=true";
}
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
$content = curl_exec($ch);
curl_close($ch);

echo $content;
?>	