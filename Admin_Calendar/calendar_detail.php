<?php require_once('../Connections/connvbsa.php'); 

error_reporting(0);
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../page_error.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}

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

if (isset($_GET['eventID'])) {
  $eventID = $_GET['eventID'];
}


mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_list = "SELECT * FROM calendar WHERE event_id = '$eventID'";
$Cal_list = mysql_query($query_Cal_list, $connvbsa) or die(mysql_error());
$row_Cal_list = mysql_fetch_assoc($Cal_list);
$totalRows_Cal_list = mysql_num_rows($Cal_list);

mysql_select_db($database_connvbsa, $connvbsa);
$query_event_id = "SELECT event_id FROM calendar WHERE event_id = '$eventID'";
$event_id = mysql_query($query_event_id, $connvbsa) or die(mysql_error());
$row_event_id = mysql_fetch_assoc($event_id);
$totalRows_event_id = mysql_num_rows($event_id);

mysql_select_db($database_connvbsa, $connvbsa);
$query_attach = "SELECT ID, event_number, attach_name, Attachment, type FROM calendar_attach WHERE event_number = '$eventID' ORDER BY type";
$attach = mysql_query($query_attach, $connvbsa) or die(mysql_error());
$row_attach = mysql_fetch_assoc($attach);
$totalRows_attach = mysql_num_rows($attach);

mysql_select_db($database_connvbsa, $connvbsa);
$query_info = "SELECT attach_name, Attachment, type FROM calendar_attach WHERE event_number = '$eventID' ORDER BY type";
$info = mysql_query($query_info, $connvbsa) or die(mysql_error());
$row_info = mysql_fetch_assoc($info);
$totalRows_info = mysql_num_rows($info);

mysql_select_db($database_connvbsa, $connvbsa);
$query_itemID = "SELECT ID, Header, event, calendar.event_id, webpage_items.event_id AS event_id_number,  webpage_items.`By`, webpage_items.created_on, webpage_items.blocked FROM webpage_items, calendar WHERE calendar.event_id=webpage_items.event_id AND  calendar.event_id='$eventID' ORDER BY ID DESC ";
$itemID = mysql_query($query_itemID, $connvbsa) or die(mysql_error());
$row_itemID = mysql_fetch_assoc($itemID);
$totalRows_itemID = mysql_num_rows($itemID);
?>
<?php
mysql_select_db($database_connvbsa, $connvbsa);
$query_max_id = "SELECT MAX(ID) AS next_id FROM webpage_items";
$max_id = mysql_query($query_max_id, $connvbsa) or die(mysql_error());
$row_max_id = mysql_fetch_assoc($max_id);
$totalRows_max_id = mysql_num_rows($max_id);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" />

</head>

<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>


<table align="center" cellpadding="5" cellspacing="5" class="greenbg">
  <tr>
    <td><a href="calendar_list.php?cal_year=<?php echo date("Y") ?>" title="View, Insert and edit the current calendar">Calendar for the current year</a></td>
    <td><a href="calendar_list.php?cal_year=<?php echo date("Y")+1 ?>" title="View, Insert and edit calendar for next year">Calendar for next year</a></td>
    <td><a href="calendar_event_xx_archive.php" title="No Start Date, Start Date is out of date or Visible is set to No">Archives</a></a></td>
    <td align="right" class="greenbg"><a href="calendar_event_previous.php">Insert a new event</a></td>
    <td><a href="../Admin_web_pages/aa_webpage_index.php">Webpage Menu</a></td>
    <td><a href="../Admin_DB_VBSA/vbsa_login_success.php">Admin Menu</a></td>
  </tr>
</table>
<table width="1000" align="center">
  <tr>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td class="red_bold">Event Detail for Event ID: <?php echo $row_Cal_list['event_id']; ?> </td>
      <td align="right">&nbsp;</td>
  </tr>
</table>
<table width="1000" align="center">
  <tr>
    <td><?php echo $row_max_id['next_id']; ?></td>
  </tr>
  <tr>
    <td>
    <?php
	// if this item has not been published to a web page then the passed variable will not exist and this will not show
	if($eventID != $row_itemID['event_id_number'])
	{
		echo '<p class="italicise">';
		echo "This calendar item has not been published to a web page in the current year"; 		
	?>
    
  	<span class="greenbg">
    <a href="../Admin_web_pages/calendar_insert.php?eventID=<?php echo $row_Cal_list['event_id']; ?>&item_id=<?php echo $row_max_id['next_id']+1; ?>">Insert this event as a new topic into a public view web page/s</a>
    </span>
    
    <?php
	echo '</p>';
	}
	else
	{
	echo "";	
	}
	?> 
    
    
    <?php // if this item has  been published to a web page then the passed variable will exist and the link to the front page will show
	if($eventID == $row_itemID['event_id_number'])
	{
		 echo '<p class="italicise">';
		 echo "This calendar item has been published to a web page in the current year";
	?>
	
	<span class="greenbg"><a href="../Admin_web_pages/item_detail.php?item_id=<?php echo $row_itemID['ID']; ?>">Update the web page item</a></span>
	<?php
	echo '</p>';
	}
	else
	{
	echo "";	
	}
	?>
    </td>
  </tr>
</table>
<div class="cal_table_border">
  <table width="990" align="center">
    <tr>
      <td colspan="6"><span class="red_bold">Visible in the Calendar: <?php echo $row_Cal_list['visible']; ?></span> (if &quot;Yes&quot; will appear on website providing &quot;Start Date&quot; is set within the current or next year)</td>
    </tr>
    <tr>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td>Tournament ?</td>
      <td>&nbsp;</td>
      <td width="322" align="left"><?php echo $row_Cal_list['tourn']; ?></td>
      <td width="139" align="right">&nbsp;</td>
      <td width="13" align="right">&nbsp;</td>
      <td align="right"><a href="../Admin_Tournaments/edit_previous_tournament.php?eventID=<?php echo $row_Cal_list['event_id']; ?>&page=calendar" title="Edit this event"><img src="../Admin_Images/edit_butt.png" width="20" height="19" /></a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td width="190">Event Title</td>
      <td width="10">&nbsp;</td>
      <td colspan="4"><?php echo $row_Cal_list['event']; ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Event Description</td>
      <td>&nbsp;</td>
      <td colspan="3"><?php echo $row_Cal_list['about']; ?></td>
      <td width="278">&nbsp;</td>
    </tr>
    <tr>
      <td>State</td>
      <td>&nbsp;</td>
      <td><?php echo $row_Cal_list['state']; ?></td>
      <td colspan=3></td>
    </tr>
    <tr>
      <td>Aust Rank?</td>
      <td>&nbsp;</td>
      <td><?php echo $row_Cal_list['aust_rank']; ?></td>
      <td>Ranking Type?</td>
      <td>&nbsp;</td>
      <td><?php echo $row_Cal_list['ranking_type']; ?></td>
    </tr>
    <tr>
      <td align="left">Start Date</td>
      <td>&nbsp;</td>
    <td><?php if (!isset($row_Cal_list['startdate'])) 
        echo "na"; 
        else
      $datestart = date_create($row_Cal_list['startdate']); 
      echo date_format($datestart, 'M d, Y');
    ?></td>
      <td align="left">Finish Date</td>
      <td>&nbsp;</td>
      <td><?php if (!isset($row_Cal_list['finishdate'])) 
	  		echo "na"; 
	  		else
			$datefinish = date_create($row_Cal_list['finishdate']); 
			echo date_format($datefinish, 'M d, Y');
	  ?></td>
    </tr>
    <tr>
      <td>Venue</td>
      <td>&nbsp;</td>
      <td><?php echo $row_Cal_list['venue']; ?></td>
      <td align="left">Entries Close</td>
      <td>&nbsp;</td>
      <td><?php if (!isset($row_Cal_list['closedate'])) 
	  		echo "na"; 
	  		else
			$dateclose = date_create($row_Cal_list['closedate']); 
			echo date_format($dateclose, 'M d, Y');
	  ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td valign="top"><p>Footer</p></td>
      <td>&nbsp;</td>
      <td colspan="4" align="left" class="page">
        <?php
		if ($row_Cal_list['footer1']=='Y')
		{
		?>
        <p> To enter this event, pay your membership or make a payment to the VBSA please go to the <a href="../vbsa_store/frontend/index.php" target="_blank">payments page</a></p> <p> Enquiries. <a href="mailto:treasurer@vbsa.org.au">Email the Treasurer</a></p>
        <?php
		}
		else
		{
			echo "";
		}
        ?>
        
        <?php
		if ($row_Cal_list['footer2']=='Y')
		{
		?>
        <p> To check the VBSA have received your entry please go to <a href="http://www.vbsa.org.au/Tournaments/tournindex.php">&quot;VBSA Tournament entries&quot;</a>. Allow a couple of days for your entry to be processed and then check that your name is listed for the tournament.</p>
        <?php
		}
		else
		{
			echo "";
		}
        ?>
        
        <?php
		if ($row_Cal_list['footer3']=='Y')
		{
		?>
        <p>Please Note: The VBSA do not accept entries for this event, please refer the entry form for details on how to enter</p>
        <?php
		}
		else
		{
			echo "";
		}
        ?>
        
        <?php
		if ($row_Cal_list['footer4']=='Y')
		{
		?>
        <p>Please go to the <span class="page"><a href="http://absc.com.au/results.aspx">ABSC Site for results</a></span></p>
        <?php
		}
		else
		{
			echo "";
		}
        ?>
      </td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="4" class="page">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top">Information / Attachments</td>
      <td>&nbsp;</td>
      <td colspan="4" class="page">
        
        <?php do { ?>
          <?php if ($row_info['type'] == 'Uploaded Attachment'): ?>
          <div class="info_item"><img src="../images/attach_entry.gif" width="25" height="16" title="Attachment" /> <a href="../calendar/cal_upload/<?php echo $row_info['Attachment']; ?>" target="_blank"><?php echo $row_info['attach_name']; ?></a> </div>
          <?php endif; ?>
          <?php if ($row_info['type'] == 'URL'): ?>
          <div class="info_item"><img src="../images/attach_entry.gif" width="25" height="16" title="Visit another page / site" /> <a href="<?php echo $row_info['Attachment']; ?>" target="_blank"><?php echo $row_info['attach_name']; ?></a> </div>
          <?php endif; ?>
          <?php if ($row_info['type'] == 'Email'): ?>
          <div class="info_item"><img src="../images/attach_entry.gif" width="25" height="16" title="Send an email" /> <a href="mailto:<?php echo $row_info['Attachment']; ?>" target="_blank"><?php echo $row_info['attach_name']; ?></a> </div>
          <?php endif; ?>
          <?php } while ($row_info = mysql_fetch_assoc($info)); ?>
        
      </td>
    </tr>
  </table>
</div>

<div class="cal_table_border">
  <table width="990">
    <tr>
      <td colspan="2" class="red_bold">&quot;Information&quot; for this event </td>
      <td colspan="3" align="right" class="greenbg"><a href="user_files/attach_insert.php?eventID=<?php echo $row_Cal_list['event_id']; ?>">Create Information links, Email address, URL or Upload an Attachment</a> </td>
    </tr>
    <tr>
      <td>Event ID</td>
      <td>Type</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_attach['event_number']; ?></td>
        <td><?php echo $row_attach['type']; ?></td>
        <td class="page"><?php if ($row_attach['type'] == 'Uploaded Attachment'): ?>
          <a href="../calendar/cal_upload/<?php echo $row_attach['Attachment']; ?>" target="_blank"><?php echo $row_attach['attach_name']; ?></a>
          <?php endif; ?>
          <?php if ($row_attach['type'] == 'URL'): ?>
          <a href="<?php echo $row_attach['Attachment']; ?>" target="_blank"><?php echo $row_attach['attach_name']; ?></a>
          <?php endif; ?>
          <?php if ($row_attach['type'] == 'Email'): ?>
          <a href="mailto:<?php echo $row_attach['Attachment']; ?>" target="_blank"><?php echo $row_attach['attach_name']; ?></a>
          <?php endif; ?></td>
        <td width="37" class="page"><?php if ($row_attach['type'] == 'Uploaded Attachment'): ?>
          <a href="user_files/attach_upload_edit.php?id=<?php echo $row_attach['ID']; ?>&eventID=<?php echo $row_attach['event_number']; ?>"><img src="../Admin_Images/edit_butt.png" height="22" title="Edit Attachment" /></a>
          <?php endif; ?>
          <?php if ($row_attach['type'] == 'Email'): ?>
          <a href="user_files/attach_email_edit.php?id=<?php echo $row_attach['ID']; ?>&eventID=<?php echo $row_attach['event_number']; ?>" ><img src="../Admin_Images/edit_butt.png" height="22" title="Edit Email" /></a>
          <?php endif; ?>
          <?php if ($row_attach['type'] == 'URL'): ?>
          <a href="user_files/attach_URL_edit.php?id=<?php echo $row_attach['ID']; ?>&eventID=<?php echo $row_attach['event_number']; ?>"><img src="../Admin_Images/edit_butt.png" height="22" title="Edit URL" /></a>
          <?php endif; ?></td>
        <td>
        <?php if(isset($row_attach['ID'])) { ?>
        <a href="user_files/attach_delete_confirm.php?id=<?php echo $row_attach['ID']; ?>&eventID=<?php echo $row_attach['event_number']; ?>" title="Delete"><img src="../Admin_Images/Trash.fw.png" height="22" /></a></td>
        <?php } else echo '<td>&nbsp;</td>'; ?>
        
      </tr>
      <?php } while ($row_attach = mysql_fetch_assoc($attach)); ?>
  </table>
</div>
</body>
</html>
<?php

?>