<?php 
session_start () ;
 if ($_SESSION['login']!=42){ 
	 header ("Location:login.php") ; 
 exit () ; 
 } 
?> 
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="zh-CN">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>历史位置</title>
</head>
<body> 
<?php
  // Connect to the database
  require_once('connectvars.php');
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  mysqli_set_charset($dbc, "utf8"); 

  // This function builds navigational page links based on the current page and the number of pages
  function generate_page_links( $cur_page, $num_pages) {
    $page_links = '';

    // If this page is not the first page, generate the "previous" link
    if ($cur_page > 1) {
      $page_links .= '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . ($cur_page - 1) . '"><-</a> ';
    }
    else {
      $page_links .= '<- ';
    }

    // Loop through the pages generating the page number links
    for ($i = 1; $i <= $num_pages; $i++) {
      if ($cur_page == $i) {
        $page_links .= ' ' . $i;
      }
      else {
        $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?page=' . $i . '"> ' . $i . '</a>';
      }
    }

    // If this page is not the last page, generate the "next" link
    if ($cur_page < $num_pages) {
      $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?page=' . ($cur_page + 1) . '">-></a>';
    }
    else {
      $page_links .= ' ->';
    }

    return $page_links;
  }

  // Calculate pagination information
  $cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
  $results_per_page = 15;  // number of results per page
  $skip = (($cur_page - 1) * $results_per_page);
  
  // Query to get the total results 
  $query = "SELECT * FROM locTest";
  $result = mysqli_query($dbc, $query);
  $total = mysqli_num_rows($result);
  $num_pages = ceil($total / $results_per_page);
 
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
  $query =  $query . " LIMIT $skip, $results_per_page";
  $result = mysqli_query($dbc, $query); 
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
 
  // Generate navigational page links if we have more than one page
  if ($num_pages > 1) {
    echo generate_page_links($cur_page, $num_pages);
  } 
mysqli_close($dbc);
?>
</body></html>
