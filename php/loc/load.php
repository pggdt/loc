<?php
require_once ('../login/StartSession.php');
if (! isset($_SESSION['user_id'])) {
    header("refresh:1;url=../login/login.php");
    exit();
}
?>
<?php
// Connect to the database
require_once ('../connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
mysqli_set_charset($dbc, "utf8") or die('MySql Error1');

// Calculate pagination information
$cur_page = isset($_POST['page']) ? $_POST['page'] : 1;
$results_per_page = 10; // number of results per page
$skip = (($cur_page - 1) * $results_per_page);
$msg = "";
$previous_btn = true;
$next_btn = true;
$first_btn = true;
$last_btn = true;
$user_id = $_SESSION['user_id'];

// Query to get the total results
$query = "SELECT * FROM locTest WHERE user_id='$user_id' ORDER BY locTimestamp DESC";
$result = mysqli_query($dbc, $query) or die('MySql Error');
$total = mysqli_num_rows($result);
$num_pages = ceil($total / $results_per_page);

$msg .= "<table class='location_data' style='border-spacing: 1px;'>
<tr>
<th>Tag</th>
<th>Detail</th>
</tr>";
$query = $query . " LIMIT $skip, $results_per_page";
$result = mysqli_query($dbc, $query);
// sleep(3);
while ($row = mysqli_fetch_array($result)) {
    $map_url = 'https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/map.php?a=' . $row['latitude'] . '&n=' . $row['longitude'] . '&r=' . $row['accuracy'] . '&t=HYBRID';
    $map_urli = 'https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/map.php?a=' . $row['latitude'] . '&n=' . $row['longitude'] . '&r=' . $row['accuracy'] . '&t=HYBRID&h=' . $row['heading'] . '&s=' . $row['speed'];
    $msg .= "<tr>";
    $msg .= "<td style='background-color:#FFBF5F;font-weight:bold;text-align:center;'>" . $row['text'] . "<br/>" . $row['geoCode'] . "<br/>";
    if ($row['hasMedia'] == "1") {
        $timeStamp = $row['locTimestamp'];
        $queryMedia = "SELECT * FROM locMedia WHERE locTimestamp='$timeStamp'";
        $resultMedia = mysqli_query($dbc, $queryMedia);
        while ($rowMedia = mysqli_fetch_array($resultMedia)) {
            $mediaLink = 'https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/' . $rowMedia['link'];
            $msg .= "<a href='" . $mediaLink . "' target='_blank'>" . $rowMedia['type'] . "</a>";
        }
    }
    $msg .= "</td><td bgcolor='pink'><ul><li>latitude:" . $row['latitude'] . "</li>";
    $msg .= "<li>longitude:" . $row['longitude'] . "</li>";
    $msg .= "<li><a href='" . $map_url . "' target='_blank'>accuracy</a>:" . $row['accuracy'] . "</li>";
    $msg .= "<li>altitude:" . $row['altitude'] . "</li>";
    $msg .= "<li>altitudeAccuracy:" . $row['altitudeAccuracy'] . "</li>";
    $msg .= "<li><a href='" . $map_urli . "' target='_blank'>heading</a>:" . $row['heading'] . "</li>";
    $msg .= "<li>speed:" . $row['speed'] . "</li>";
    $msg .= "<li>locTime:" . $row['locTime'] . "</li>";
    $msg .= "<li>insertUTCTime:" . $row['insertTime'] . "</li></ul></td>";
    $msg .= "</tr>";
}
$msg .= "</table>";

// Generate navigational page links if we have more than one page
if ($num_pages > 1) {
    // Calculating the starting and endign values for the loop
    if ($cur_page >= 7) {
        $start_loop = $cur_page - 3;
        if ($num_pages > $cur_page + 3)
            $end_loop = $cur_page + 3;
        else 
            if ($cur_page <= $num_pages && $cur_page > $num_pages - 6) {
                $start_loop = $num_pages - 6;
                $end_loop = $num_pages;
            } else {
                $end_loop = $num_pages;
            }
    } else {
        $start_loop = 1;
        if ($num_pages > 7)
            $end_loop = 7;
        else
            $end_loop = $num_pages;
    }
    
    $msg .= "<div class='pagination'><ul>";
    
    // FOR ENABLING THE FIRST BUTTON
    if ($first_btn && $cur_page > 1) {
        $msg .= "<li p='1' class='active'>First</li>";
    } else 
        if ($first_btn) {
            $msg .= "<li p='1' class='inactive'>First</li>";
        }
    
    // FOR ENABLING THE PREVIOUS BUTTON
    if ($previous_btn && $cur_page > 1) {
        $pre = $cur_page - 1;
        $msg .= "<li p='$pre' class='active'>Previous</li>";
    } else 
        if ($previous_btn) {
            $msg .= "<li class='inactive'>Previous</li>";
        }
    for ($i = $start_loop; $i <= $end_loop; $i ++) {
        
        if ($cur_page == $i)
            $msg .= "<li p='$i' style='color:#fff;background-color:#006699;' class='active'>{$i}</li>";
        else
            $msg .= "<li p='$i' class='active'>{$i}</li>";
    }
    
    // TO ENABLE THE NEXT BUTTON
    if ($next_btn && $cur_page < $num_pages) {
        $nex = $cur_page + 1;
        $msg .= "<li p='$nex' class='active'>Next</li>";
    } else 
        if ($next_btn) {
            $msg .= "<li class='inactive'>Next</li>";
        }
    
    // TO ENABLE THE END BUTTON
    if ($last_btn && $cur_page < $num_pages) {
        $msg .= "<li p='$num_pages' class='active'>Last</li>";
    } else 
        if ($last_btn) {
            $msg .= "<li p='$num_pages' class='inactive'>Last</li>";
        }
}

$goto = "<input type='text' class='goto' size='2' style='margin-top:-1px;margin-left:60px;'/><input type='button' id='go_btn' class='go_button' value='Go'/>";
$total_string = "<span class='total' a='$num_pages'>Page <b>" . $cur_page . "</b> of <b>$num_pages</b></span>";

$msg .= $goto . $total_string;
echo $msg;
mysqli_close($dbc);
?>
