<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_photo_year = "SELECT year_photo FROM gallery_team_photos WHERE gallery_team_photos.`current` ='Yes' GROUP BY year_photo ORDER BY gallery_team_photos.year_photo DESC";
$photo_year = mysql_query($query_photo_year, $connvbsa) or die(mysql_error());
$row_photo_year = mysql_fetch_assoc($photo_year);
$totalRows_photo_year = mysql_num_rows($photo_year);

$currentPage = $_SERVER["PHP_SELF"];

$queryString_FPitems = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_FPitems") == false && 
        stristr($param, "totalRows_FPitems") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_FPitems = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_FPitems = sprintf("&totalRows_FPitems=%d%s", $totalRows_FPitems, $queryString_FPitems);

;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Archives</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</head>
<body id="vbsa">

<div class="container"> 

    <!-- Include header -->
<?php include '../includes/header.php';?>


    
    <!-- Include navigation -->
<?php include '../includes/nav_vbsa.php';?>
    
  </div> 

</div><!--End Bootstrap Container--> 

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 

  <!--Content--> 
  
  
  <!--Left--> 
  
  <div class="row"> 
  <div class="Page_heading_container">
    <div class="page_title"><span class="red_bold">VBSA Team Photo Gallery</span> (Archives)</div>
  </div>    
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 
<!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>
    
    <div class="container">
      <div class="center-block" style="max-width:800px">
        <div style="width:45%; float:right; margin-left:15px; margin-bottom:10px; margin-right:15px"><img src="../images/teamphoto_button.jpg" class="img-responsive" /></div>
        <p>Go to our <a href="https://vbsa.org.au/vbsa_shop/shop_cart.php">Store</a> to purchase an endorsed photo.</p>
<p>Available as:</p>
<ul>
<li>A Digital copy for download</li>
<li>A Printed and delivered printed copy</li>
<li>A Framed and delivered copy</li>
</ul>
<p> When ordering quote the "Photo ID" found at the bottom of the photo</p>
    </div>
</div><!-- close container -->  

<div class="table-condensed center-block" style="max-width:800px">
<table class="table">
      <tr>
        <td align="center" class="table_header"><span class="text-center table_header">VBSA Photos organised by year</span></td>
      </tr>
    
        <tr>
            <td align="left">Please Note: These pages are being constructed and it will take some time to upload all the photos. The VBSA started taking team photographs in 2004. We will continue to take them at all grand finals. The Winning team of each grade will receive a framed copy of  their photo to be added to their Clubroom. Team members may purchase a photo if they wish.</td>
        </tr>
    
    <?php do { ?>
        <tr>
        <td align="center">
        
             
                <a href="VBSA_team_photo_index_detail.php?year=<?php echo $row_photo_year['year_photo']; ?>"><?php echo $row_photo_year['year_photo']; ?></a>
              
       
        </td>
        </tr>
    <?php } while ($row_photo_year = mysql_fetch_assoc($photo_year)); ?>
    </table>
</div>

</div><!-- close wrapper -->   
 
</body>
</html>
<?php
mysql_free_result($photo_year);
?>
