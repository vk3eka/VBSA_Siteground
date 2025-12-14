<?php require_once('Connections/connvbsa.php'); ?>
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
$query_links = "SELECT * FROM links WHERE visible=1 ORDER BY link_order, link_title ASC";
$links = mysql_query($query_links, $connvbsa) or die(mysql_error());
$row_links = mysql_fetch_assoc($links);
$totalRows_links = mysql_num_rows($links);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Links</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link href="CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</head>
<body id="info">
    
    <!-- Include Google Tracking -->
<?php include_once("includes/analyticstracking.php") ?>

<div class="container"> 

    <!-- Include header -->
<?php include 'includes/header.php';?>
    
    <!-- Include navigation -->
<?php include 'includes/nav_vbsa.php';?>

</div><!--End Bootstrap Container--> 

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 

  <!--Content--> 
  
  
  <div class="row"> 
  <div class="Page_heading_container">
 		<div class="page_title">VBSA Links</div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 
<!-- Include link to previous page -->
<?php include 'includes/prev_page.php';?>
  <div class="text_box"> <!-- scoring system explained -->The VB&amp;SA accept no responsibility for the content of any external website or page linked to this site.&nbsp; The provision of these links is not an endorsement of any product or service that may be supplied, purchased, or provided by the company or individual to which the hyperlink pertains.</div>


<div class="table-responsive center-block" style="max-width:600px">

    
  		<table class="table">
    <?php do { ?>
      <tr>
        <td nowrap="nowrap"><?php echo $row_links['link_title']; ?></td>
        <td nowrap="nowrap" class=" text-right"><a href="<?php echo $row_links['url']; ?>" target="_blank">Visit this site</a></td>
      </tr>
	<?php } while ($row_links = mysql_fetch_assoc($links)); ?>		
      
  </table>
    
  
</div>

<div class="table-responsive center-block" style="max-width:700px"></div>

</div>  <!-- close conraineing wrapper --> 
</body>
</html>
<?php

?>
