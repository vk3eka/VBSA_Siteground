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

$colname_Cal_list = "-1";
if (isset($_GET['event_id'])) {
  $colname_Cal_list = $_GET['event_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_list = sprintf("SELECT * FROM calendar WHERE event_id = %s", GetSQLValueString($colname_Cal_list, "int"));
$Cal_list = mysql_query($query_Cal_list, $connvbsa) or die(mysql_error());
$row_Cal_list = mysql_fetch_assoc($Cal_list);
$totalRows_Cal_list = mysql_num_rows($Cal_list);

$colname_attach = "-1";
if (isset($_GET['event_id'])) {
  $colname_attach = $_GET['event_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_attach = sprintf("SELECT ID, event_number, attach_name, Attachment FROM calendar_attach WHERE event_number = %s", GetSQLValueString($colname_attach, "int"));
$attach = mysql_query($query_attach, $connvbsa) or die(mysql_error());
$row_attach = mysql_fetch_assoc($attach);
$totalRows_attach = mysql_num_rows($attach);

$colname_info = "-1";
if (isset($_GET['event_id'])) {
  $colname_info = $_GET['event_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_info = sprintf("SELECT attach_name, Attachment, type FROM calendar_attach WHERE event_number = %s ORDER BY type DESC", GetSQLValueString($colname_info, "int"));
//echo($query_info . "<br>");
$info = mysql_query($query_info, $connvbsa) or die(mysql_error());
$row_info = mysql_fetch_assoc($info);
$totalRows_info = mysql_num_rows($info);

$colname_Footer = "-1";
if (isset($_GET['event_id'])) {
  $colname_Footer = $_GET['event_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Footer = sprintf("SELECT * FROM calendar WHERE event_id = %s", GetSQLValueString($colname_Footer, "int"));
$Footer = mysql_query($query_Footer, $connvbsa) or die(mysql_error());
$row_Footer = mysql_fetch_assoc($Footer);
$totalRows_Footer = mysql_num_rows($Footer);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Calendar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</head>
<body id="vbsa">
    
    <!-- Include Google Tracking -->
<?php include_once("../includes/analyticstracking.php") ?>

<div class="container"> 

    <!-- Include header -->
<?php include '../includes/header.php';?>
    
    <!-- Include navigation -->
<?php include '../includes/nav_vbsa.php';?>

</div><!--End Bootstrap Container--> 

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 

  <!--Content--> 
  
  
<div class="row"> 
  <div class="Page_heading_container">
 		<div class="page_title">VBSA Calendar - event detail for: <?php echo $row_Cal_list['event']; ?></div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
  <!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>

<div class="table-responsive center-block" style="max-width:700px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<tbody>
      <tr>
        <td class="text-right">Venue:</td>
        <td><?php echo $row_Cal_list['venue']; ?></td>
      </tr>
      <tr>
        <td class="text-right">State :</td>
        <td><?php echo $row_Cal_list['state']; ?></td>
      </tr>
      <tr>
        <td class="text-right">Ranking Type :</td>
        <td><?php echo $row_Cal_list['ranking_type']; ?></td>
      </tr>
      <tr>
        <td class="text-right">Start Date :</td>
        <td><?php if(isset($row_Cal_list['startdate'])) { $newDate = date("M d, Y", strtotime($row_Cal_list['startdate'])); echo $newDate; } else echo ""; ?></td>
      </tr>
      <tr>
        <td class="text-right" nowrap="nowrap">Finish Date</td>
        <td><?php if(isset($row_Cal_list['finishdate'])) { $newDate = date("M d, Y", strtotime($row_Cal_list['finishdate'])); echo $newDate; } else echo ""; ?></td>
      </tr>
      <tr>
        <td class="text-right" nowrap="nowrap">Entries Close</td>
        <td><?php if(isset($row_Cal_list['entry_close'])) { $newDate = date("M d, Y", strtotime($row_Cal_list['entry_close'])); echo $newDate; } else echo ""; ?></td>
      </tr>
      <tr>
        <td class="text-right" nowrap="nowrap">Australian Ranking :</td>
        <td><?php echo $row_Cal_list['aust_rank']; ?></td>
      </tr>
      <tr>
        <td class="text-right">About : </td>
        <td><?php echo $row_Cal_list['about']; ?></td>
      </tr>
    </tbody>
  </table>
</div>
  
<div class="table-responsive center-block" style="max-width:700px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  <?php if ($row_info['type'] == 'Uploaded Attachment') { ?>
  <tr>
  
    <td colspan="3">
    	<div class="text-center">
        <a href="#"><span class="glyphicon glyphicon-eye-open"></span></a>
        <span class="italic"> = View &nbsp;&nbsp;&nbsp;</span>
        <a href="#"><span class="glyphicon glyphicon-download"></span></a>
        <span class="italic"> = Download</span>
        </div>
   
    </td>
  </tr>
  <?php } else echo ""; ?>
  <tr>
    <td class="italic">Information, entry form, draw etc. <?php if($row_info['type'] != 'Uploaded Attachment' & $row_info['type'] != 'URL' & $row_info['type'] != 'Email') echo " - No Attachments available"; ?></td>
  </tr>
  
  <?php do { ?>
  <tr>
    
      <?php if ($row_info['type'] == 'Uploaded Attachment') {
	  echo '<td>' . $row_info['attach_name'] . '<span class="italic">' . " (pdf)" . '</span>' . '</td>';
	  echo '<td>' . "<a href=\"http://www.vbsa.org.au//ViewerJS/?zoom=page-width#../calendar/cal_upload/" . $row_info['Attachment'] . "\"  title='View'><span class='glyphicon glyphicon-eye-open'></span></a>" . '</td>';
	  echo '<td>' . "<a href=\"../calendar/cal_upload/" . $row_info['Attachment'] . "\" title='Download'><span class='glyphicon glyphicon-download'></span></a>" . '</td>'; }
	  else echo "";
	  ?>
      
      <?php if ($row_info['type'] == 'URL') {
	  echo '<td>' . $row_info['attach_name'] . '</td>';
	  echo '<td>' . "<a target=\"_blank\" href=" . $row_info['Attachment'] . ">visit page</a>" . '</td>';
	  echo '<td>' . '&nbsp;' . '</td>'; }
	  else echo "";
	  ?>
      
      <?php
	  $aname = $row_info['attach_name']; 
	  if ($row_info['type'] == 'Email') { 
	  echo '<td>' . '&nbsp;' . '</td>';
	  echo '<td colspan="2">' . "<a href='mailto:" . $row_info['Attachment'] . "'>$aname</a>" . '</td>';  } 
	  else echo ""; 
	  ?>
  </tr>
  <?php } while ($row_info = mysql_fetch_assoc($info)); ?>
</table>
</div>


<div class="row"> 
      
      <?php
	  // Footer 1 is checked display VBSA store link in a text box class div
	  $urlpayment = '../vbsa_shop/shop_cart.php';
      echo '<div class="text_box text-center">'; 
	  if ($row_Cal_list['footer1']=='Y') {
	  echo "To enter this event, pay your membership or make a payment to the VBSA please go to the "; 
	  echo "<a target=\"_blank\" href=" . $urlpayment. ">payments page</a>";  
	  echo '</div>';}
      else echo "";
	  
	  // Footer 2 is checked display link to tourn entries page in a text box class div
	  $urltourn = '../Tournaments/tourn_index.php';
      echo '<div class="text_box text-center">'; 
	  if ($row_Cal_list['footer2']=='Y') {
	  echo "To check the VBSA have received your entry please go to "; 
	  echo "<a target=\"_blank\" href=" . $urltourn. ">VBSA Tournament entries</a>"; 
	  echo " Allow a couple of days for your entry to be processed and then check that your name is listed for the tournament. Have a problem? ";
	  //echo "<a href='mailto:\"mailto:name@domain.com\">Email the Treasurer</a>"; 
	  echo '<a href="mailto:treasurer@vbsa.org.au">Email the Treasurer</a>';  
	  echo '</div>';}
      else echo "";
	  
	  // Footer 3 is checked display message a text box class div
      echo '<div class="text_box text-center">'; 
	  if ($row_Cal_list['footer3']=='Y') {
	  echo "Please Note: The VBSA do not accept entries for this event, please refer the entry form fo details on how to enter. "; 
	  echo '</div>';}
      else echo "";
	  
	   // Footer 4 is checked display VBSA store link in a text box class div
	  $urlABSC = 'http://absc.com.au/results.aspx';
      echo '<div class="text_box text-center">'; 
	  if ($row_Cal_list['footer4']=='Y') {
	  echo "Please go to the "; 
	  echo "<a target=\"_blank\" href=" . $urlABSC. ">ABSC Site for results</a>"; 
	  echo '</div>';}
      else echo "";
      ?>  
</div> 
      
      
</div>  <!-- close containing wrapper --> 
</body>
</html>
<?php

?>
