<?php require_once('../Connections/connvbsa.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$page = "../Admin_DB_VBSA/vbsa_players_email_byseason.php?season=$season";
$_SESSION['page'] = $page;

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
$query_members = "Select scrsID, scrs.MemberID, Team_grade, scr_season, FirstName, LastName,  MobilePhone, Email, ReceiveEmail, current_year_scrs, SUM(count_played) AS played, current_year_scrs FROM scrs  LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE scr_season='$season'  AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberId != 1 AND Email is not null  AND ReceiveEmail=1 GROUP BY scrs.MemberID ORDER BY LastName, FirstName";
$members = mysql_query($query_members, $connvbsa) or die(mysql_error());
$row_members = mysql_fetch_assoc($members);
$totalRows_members = mysql_num_rows($members);

$myRecordset=$members; $myTotalRecords=$totalRows_members; 

include 'php_mail_include.php'; // local file with the previous emailling code

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

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_Reports/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

<table align="center">
  <tr>
    <td colspan="3" align="center"><p class="red_bold">This list contains all players that have played  1 match or more in season <?php echo $season; ?> of <span class="header_red"><?php echo date("Y") ?></span>, </p>
    <p class="red_bold">have an email and have &quot;Receive Email&quot; set as &quot;Yes&quot;</p></td>
  </tr>
  <tr>
    <td align="center">Scroll to the bottom of the page to send an email to all that are listed here</td>
    <td align="center">Total email contacts:<?php echo $totalRows_members ?></td>
    <td class="greenbg"><a href="A_memb_index.php">Return to Members index</a></td>
  </tr>
</table>
<table align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td>Total to email: <?php echo $totalRows_members ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="3" align="right">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">ID</td>
    <td align="left">First Name</td>
    <td align="left">Last Name</td>
    <td align="left">Phone</td>
    <td align="left">Email</td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
<?php do { ?>
  <tr>
    <td align="center"><?php echo $row_members['MemberID']; ?></td>
    <td align="left"><?php echo $row_members['FirstName']; ?></td>
    <td align="left"><?php echo $row_members['LastName']; ?></td>
    <td align="left" class="page"><?php echo $row_members['MobilePhone']; ?></td>
    <td align="left" class="page"><?php echo $row_members['Email']; ?></td>
    <td><a href="../A_common/vbsa_personal_detail.php?memb_id=<?php echo $row_members['MemberID']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" title="detail" /></a></td>
  	<td align="center"><a href="../A_common/vbsa_member_edit.php?memb_id=<?php echo $row_members['MemberID']; ?>"><img src="../Admin_Images/edit_butt.fw.png" width="20" /> </a></td>
  </tr>
<?php } while ($row_members = mysql_fetch_assoc($members)); ?>
</table>
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="134" class="page">&nbsp;</td>
    <td width="551" align="right" class="greenbg">&nbsp;</td>
  </tr>
  <tr>
    <td>Would you like to send an attachment?</td>
    <td>&nbsp;</td>
    <td class="greenbg"><a href="attach_upload.php">Please upload it now</a></td>
    <td align="right" class="greenbg"><a href="Bulk_email_help.pdf">Bulk Email help</a></td>
  </tr>
  <tr>
    <td width="211" align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>To Send a group email: </td>
    <td width="11">&nbsp;</td>
    <td colspan="2">1. Type your email address in the &quot;From&quot; field</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">2. Type VBSA in &quot;Name&quot; field.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">3. From the &quot;Recordset fields&quot; select &quot;Email&quot;. Click the <img src="php_mail_merge/dynamic_e.gif" alt="1" width="17" height="17" /> button and it will add this field into the &quot;To&quot; field.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">4. In the message area type a greeting &quot;Hi&quot; followed by a space. From the&quot;recordset fields&quot; select &quot;Firstname&quot; </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;&nbsp;&nbsp;click the <img src="php_mail_merge/dynamic_t.gif" alt="1" /> button and it will add a field that will reflect the first name of the person to receive the email</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">5. Tick the box for &quot;Plain Text&quot; alongside &quot;Send as&quot; </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">6. Type in the subject and your message in the appropriate fields and click &quot;Send&quot;.</td>
  </tr>
</table>
<form action="" method="post" name="editor_form" id="editor_form">
<br />
<table width="960" border="0" align="center" cellpadding="3" cellspacing="0" id="filters">
  <tr>
    <td title="Area designated for Recordset filters (form fields)"><fieldset>
      <legend>Filters</legend>
      <br />
      <br />
      Reset Editor:
      <input name="reset_editor" type="checkbox" id="reset_editor" title="Reset Editor fields when filtering the Recordset" value="1" />
      <input name="Filter" type="submit" value="Filter" onclick="refreshSource();document.getElementById('Do_Send').value=''" id="Filter" title="Filter the Recordset."/>
    </fieldset></td>
  </tr>
</table>
<?php include("php_mail_merge.php"); ?>
</form>
<script language="javascript" src="php_mail_merge/php_mail_merge.js" type="text/javascript">
</script>
<script language="javascript" type="text/javascript">initPMM();<?php if (isset($_POST["SendAs"]) && !isset($_POST['reset_editor'])){?>flipMessageFormat(document.getElementById('SendAs'));<?php }?></script>
</body>
</html>

