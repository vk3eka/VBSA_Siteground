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
$query_contact = "SELECT * FROM DVSA_contact WHERE contact_current=1 ORDER BY contact_order";
$contact = mysql_query($query_contact, $connvbsa) or die(mysql_error());
$row_contact = mysql_fetch_assoc($contact);
$totalRows_contact = mysql_num_rows($contact);

mysql_select_db($database_connvbsa, $connvbsa);
$query_about = "SELECT item_title, news_content FROM DVSA WHERE DVSA_id =7";
$about = mysql_query($query_about, $connvbsa) or die(mysql_error());
$row_about = mysql_fetch_assoc($about);
$totalRows_about = mysql_num_rows($about);

mysql_select_db($database_connvbsa, $connvbsa);
$query_header = "SELECT item_title, news_content, pagezone_header_desc, pagezone_header FROM DVSA WHERE DVSA_id=8";
$header = mysql_query($query_header, $connvbsa) or die(mysql_error());
$row_header = mysql_fetch_assoc($header);
$totalRows_header = mysql_num_rows($header);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
	<?php 
	if(!empty($row_contact['contact_id']) && !empty($row_about['item_title']))  echo "DVSA Contact & About"; 
	if(empty($row_contact['contact_id']) && !empty($row_about['item_title']))  echo "DVSA About"; 
	if(!empty($row_contact['contact_id']) && empty($row_about['item_title']))  echo "DVSA Contact";
	?>
</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</head>
<body id="affiliates">
    
    <!-- Include Google Tracking -->
<?php include_once("../includes/analyticstracking.php") ?>

<div class="container"> 

<!-- Affiliate header from appropriate database table -->
<!-- Large Screen header -->
<div class="row visible-xs">
  <h1 style="text-align: center; padding-bottom: 10px"><?php echo $row_header['pagezone_header']; ?></h1> 
  <h6 style="text-align: center; padding-bottom: 10px"><?php echo $row_header['pagezone_header_desc']; ?></h6>   
  <h5 style="color:#900; text-align:center; padding-bottom:10px" class="italic"><?php echo $row_header['news_content']; ?></h5> 
</div>
  
<!-- Small Screen header -->
<div class="row hidden-xs">
  <h1><?php echo $row_header['item_title']; ?></h1>   
  <h4 style="color:#666; text-align:center; padding-bottom:10px" class="italic"><?php echo $row_header['news_content']; ?></h4> 
</div> 
<!-- End header -->   
   
  <!-- Include navigation -->
<?php include '../includes/nav_vbsa.php';?>

</div><!--End Bootstrap Container--> 

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 

  <!--Content--> 

  
  <div class="row"> 
  <div class="Page_heading_container">
 		<div class="page_title">
        <?php 
		if(!empty($row_contact['contact_id']) && !empty($row_about['item_title']))  echo "DVSA Contact & About"; 
		if(empty($row_contact['contact_id']) && !empty($row_about['item_title']))  echo "DVSA About"; 
		if(!empty($row_contact['contact_id']) && empty($row_about['item_title']))  echo "DVSA Contact";
		?>
        </div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 
<!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>

<?php if(!empty($row_contact['contact_id'])) { ?>
<div class="table-responsive center-block" style="max-width:600px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<tbody>
      <tr>
        <th colspan="2" class="text-center italic">Board Members and contact details</th>
        </tr>
        </tbody>
        </table>
        
		<?php do { ?>
        <table class="table">
      <tr>
        <td colspan="2"><?php echo $row_contact['contact_name']; ?>: <?php echo $row_contact['contact_position']; ?></td>
        </tr>
      
        <tr>
          <td width="30%">Phone: <a href="tel:<?php echo $row_contact['contact_phone']; ?>"><?php echo $row_contact['contact_phone']; ?></a></td>
          <td>Email:<a href="mailto:<?php echo $row_contact['contact_email']; ?>"><?php echo $row_contact['contact_email']; ?></a></td>
        </tr>
        </table>
        <?php } while ($row_contact = mysql_fetch_assoc($contact)); ?>
    </tbody>
  </table>
</div>
<?php  }  else echo ""; ?>

<?php if(!empty($row_about['item_title'])) { ?>
  <table class="table center-block" style="max-width:600px">
  	<tbody>
      <tr>
        <th class="text-center"><?php echo $row_about['item_title']; ?></th>
        </tr>
      <tr>
        <td class="text_box"><?php echo $row_about['news_content']; ?></td>
        </tr>
    </tbody>
  </table>
  <?php  }  else echo ""; ?>


</div>  <!-- close containing wrapper --> 
</body>
</html>
<?php

?>
