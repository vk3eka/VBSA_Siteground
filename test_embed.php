<?php //require_once('Connections/connvbsa.php'); ?>
<?php
/*
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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_page_items = 10;
$pageNum_page_items = 0;
if (isset($_GET['pageNum_page_items'])) {
  $pageNum_page_items = $_GET['pageNum_page_items'];
}
$startRow_page_items = $pageNum_page_items * $maxRows_page_items;

mysql_select_db($database_connvbsa, $connvbsa);
$query_page_items = "SELECT webpage_items.ID, webpage_items.Header, webpage_items.`Comment`, webpage_items.`By`, webpage_items.created_on, webpage_items.blocked, webpage_items.img_orientation, webpage_items.item_image,  webpage_items.event_id,  webpage_items.page_help, webpage_items.OrderFP, webpage_items.OrderRef, webpage_items.OrderWomens, webpage_items.OrderHelp, webpage_items.OrderWomens, webpage_items.img_size FROM webpage_items WHERE webpage_items.blocked='No' AND webpage_items.page_front='Y' ORDER BY OrderFP, created_on DESC";
$query_limit_page_items = sprintf("%s LIMIT %d, %d", $query_page_items, $startRow_page_items, $maxRows_page_items);
$page_items = mysql_query($query_limit_page_items, $connvbsa) or die(mysql_error());
$row_page_items = mysql_fetch_assoc($page_items);

if (isset($_GET['totalRows_page_items'])) {
  $totalRows_page_items = $_GET['totalRows_page_items'];
} else {
  $all_page_items = mysql_query($query_page_items, $connvbsa);
  $totalRows_page_items = mysql_num_rows($all_page_items);
}
$totalPages_page_items = ceil($totalRows_page_items/$maxRows_page_items)-1;

mysql_select_db($database_connvbsa, $connvbsa);
$query_BBSA = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM BBSA";
$BBSA = mysql_query($query_BBSA, $connvbsa) or die(mysql_error());
$row_BBSA = mysql_fetch_assoc($BBSA);
$totalRows_BBSA = mysql_num_rows($BBSA);

mysql_select_db($database_connvbsa, $connvbsa);
$query_BendBSA = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM BendBSA";
$BendBSA = mysql_query($query_BendBSA, $connvbsa) or die(mysql_error());
$row_BendBSA = mysql_fetch_assoc($BendBSA);
$totalRows_BendBSA = mysql_num_rows($BendBSA);
mysql_select_db($database_connvbsa, $connvbsa);

$query_CC = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM CC";
$CC = mysql_query($query_CC, $connvbsa) or die(mysql_error());
$row_CC = mysql_fetch_assoc($CC);
$totalRows_CC = mysql_num_rows($CC);

//mysql_select_db($database_connvbsa, $connvbsa);
//$query_Church = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM Church";
//$Church = mysql_query($query_Church, $connvbsa) or die(mysql_error());
//$row_Church = mysql_fetch_assoc($Church);
//$totalRows_Church = mysql_num_rows($Church);

mysql_select_db($database_connvbsa, $connvbsa);
$query_DVSA = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM DVSA";
$DVSA = mysql_query($query_DVSA, $connvbsa) or die(mysql_error());
$row_DVSA = mysql_fetch_assoc($DVSA);
$totalRows_DVSA = mysql_num_rows($DVSA);

mysql_select_db($database_connvbsa, $connvbsa);
$query_MSBA = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM MSBA";
$MSBA = mysql_query($query_MSBA, $connvbsa) or die(mysql_error());
$row_MSBA = mysql_fetch_assoc($MSBA);
$totalRows_MSBA = mysql_num_rows($MSBA);

mysql_select_db($database_connvbsa, $connvbsa);
$query_O55 = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM O55";
$O55 = mysql_query($query_O55, $connvbsa) or die(mysql_error());
$row_O55 = mysql_fetch_assoc($O55);
$totalRows_O55 = mysql_num_rows($O55);

mysql_select_db($database_connvbsa, $connvbsa);
$query_RSL = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM RSL";
$RSL = mysql_query($query_RSL, $connvbsa) or die(mysql_error());
$row_RSL = mysql_fetch_assoc($RSL);
$totalRows_RSL = mysql_num_rows($RSL);

mysql_select_db($database_connvbsa, $connvbsa);
$query_SBSA = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM SBSA";
$SBSA = mysql_query($query_SBSA, $connvbsa) or die(mysql_error());
$row_SBSA = mysql_fetch_assoc($SBSA);
$totalRows_SBSA = mysql_num_rows($SBSA);

mysql_select_db($database_connvbsa, $connvbsa);
$query_WSBSA = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM WSBSA";
$WSBSA = mysql_query($query_WSBSA, $connvbsa) or die(mysql_error());
$row_WSBSA = mysql_fetch_assoc($WSBSA);
$totalRows_WSBSA = mysql_num_rows($WSBSA);

mysql_select_db($database_connvbsa, $connvbsa);
$query_VBSAmax = "SELECT  Updated  AS MAXDATE FROM Team_entries WHERE Updated=(SELECT MAX(Updated) FROM Team_entries)";
$VBSAmax = mysql_query($query_VBSAmax, $connvbsa) or die(mysql_error());
$row_VBSAmax = mysql_fetch_assoc($VBSAmax);
$totalRows_VBSAmax = mysql_num_rows($VBSAmax);
mysql_select_db($database_connvbsa, $connvbsa);

$query_Cal = "SELECT event, startdate, event_id FROM calendar WHERE calendar.visible='Yes' AND calendar.startdate is not null AND calendar.startdate >= NOW() ORDER BY calendar.startdate LIMIT 8";
$Cal = mysql_query($query_Cal, $connvbsa) or die(mysql_error());
$row_Cal = mysql_fetch_assoc($Cal);
$totalRows_Cal = mysql_num_rows($Cal);

$queryString_page_items = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_page_items") == false && 
        stristr($param, "totalRows_page_items") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_page_items = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_page_items = sprintf("&totalRows_page_items=%d%s", $totalRows_page_items, $queryString_page_items);
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Home Page</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href="CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <link rel="icon" type="image/x-icon" href="images/image001.png">
</head>
<body id="home">

<!-- Include Google Tracking -->
<?php include_once("includes/analyticstracking.php") ?>

<div class="container"> 

<!-- Include header -->
<?php include 'includes/header.php';?>
    
<!-- Include navigation -->
<?php include 'includes/nav_vbsa.php';?>

</div><!--End Bootstrap Container-->

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px-->

<!-- Include reventon advertising -->
<?php include 'includes/reventon_image_swap.php';?>  

<!--Content--> 
  
<!--Right--> 
  
<div class="pull-left" style="width:25%; margin-left:15px"></div>


<!--<embed src="http://vbsa.cpc-world.com/wordpress/" style="width:1200px; height: 1500px;">-->
<embed src="https://samm197.sg-host.com/?post_type=tribe_events" style="width:1200px; height: 1500px;">

</div>  <!-- close conraining wrapper --> 
</body>
</html>
