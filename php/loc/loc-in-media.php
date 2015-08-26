<?php
require_once ('../login/StartSession.php');
if (! isset($_SESSION['user_id'])) {
    header("refresh:1;url=../login/login.php");
    exit();
}
?>
<?php

require_once ('../connectvars.php');
$user_id = $_SESSION['user_id'];
$timestamp;
$type;
$dest;
$allowedExts = array(
    "gif",
    "jpeg",
    "jpg",
    "png",
    "wav",
    "ogg"
);
$temp = explode(".", $_FILES["fileSelect"]["name"]);
$extension = end($temp);
if ((($_FILES["fileSelect"]["type"] == "image/gif") || ($_FILES["fileSelect"]["type"] == "image/jpeg") || ($_FILES["fileSelect"]["type"] == "image/jpg") || ($_FILES["fileSelect"]["type"] == "image/pjpeg") || ($_FILES["fileSelect"]["type"] == "image/x-png") || ($_FILES["fileSelect"]["type"] == "image/png") || ($_FILES["fileSelect"]["type"] == "audio/x-wav") || ($_FILES["fileSelect"]["type"] == "video/ogg")) && ($_FILES["fileSelect"]["size"] < 8000000) && in_array($extension, $allowedExts)) {
    if ($_FILES["fileSelect"]["error"] > 0) {
        echo "Return Code: " . $_FILES["fileSelect"]["error"] . "<br>";
    } else {
        $filename = $_FILES["fileSelect"]["name"];
        $timestamp = $temp[0];
        $dir;
        if (strlen($timestamp) > 10) {
            $dir = date("Y", substr($timestamp, 0, 10));
        } else {
            $dir = date("Y", $timestamp);
        }
        if (! file_exists("media/" . $dir)) {
            mkdir("media/" . $dir);
        }
        echo "Upload: " . $_FILES["fileSelect"]["name"] . "<br>";
        $type = $_FILES["fileSelect"]["type"];
        echo "Type: " . $type . "<br>";
        echo "Size: " . ($_FILES["fileSelect"]["size"] / 1024) . " kB<br>";
        // echo "Temp file: " . $_FILES["fileSelect"]["tmp_name"] . "<br>";
        
        $dest = "media/" . $dir . "/" . $filename;
        if (file_exists($dest)) {
            echo $filename . " already exists. ";
        } else {
            move_uploaded_file($_FILES["fileSelect"]["tmp_name"], $dest);
            echo "Stored in: " . $dest . "<br>";
            
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            if (mysqli_connect_errno($dbc)) {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }
            mysqli_set_charset($dbc, "utf8") or die('Could not connect: ' . mysqli_error($dbc));
            
            $timestamp = mysqli_real_escape_string($dbc, $timestamp);
            $time = mysqli_real_escape_string($dbc, $time);
            $text = mysqli_real_escape_string($dbc, $text);
            $geoCode = mysqli_real_escape_string($dbc, $geoCode);
            $query = "insert into locMedia (ID,user_id,locTimestamp,type,link) values (NULL,'$user_id','$timestamp','$type','$dest')";
            $result = mysqli_query($dbc, $query) or die(mysqli_error($dbc));
            $query1 = "UPDATE `locTest` SET `hasMedia`='1' WHERE `locTimestamp`='$timestamp'";
            $result1 = mysqli_query($dbc, $query1) or die(mysqli_error($dbc));
            mysqli_close($dbc);
            $response = $filename . " added to DB";
            echo $response;
        }
    }
} else {
    echo "Invalid file";
}

?>
