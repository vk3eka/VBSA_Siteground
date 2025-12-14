<?php require_once('../Connections/connvbsa.php'); ?>
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
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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
$query_tourn1 = "SELECT * FROM tournaments WHERE tourn_year = YEAR(CURDATE()) AND tourn_type='Snooker' AND status='Open' ORDER BY tournaments.tourn_name";
$tourn1 = mysql_query($query_tourn1, $connvbsa) or die(mysql_error());
$row_tourn1 = mysql_fetch_assoc($tourn1);
$totalRows_tourn1 = mysql_num_rows($tourn1);

mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn2 = "SELECT * FROM tournaments WHERE tourn_year = YEAR(CURDATE()) AND tourn_type='Billiards' AND status='Open' ORDER BY tournaments.tourn_name";
$tourn2 = mysql_query($query_tourn2, $connvbsa) or die(mysql_error());
$row_tourn2 = mysql_fetch_assoc($tourn2);
$totalRows_tourn2 = mysql_num_rows($tourn2);

mysql_select_db($database_connvbsa, $connvbsa);
$query_tournclosed = "SELECT * FROM tournaments WHERE tourn_year = YEAR(CURDATE()) AND status='closed' ORDER BY tournaments.tourn_name";
$tournclosed = mysql_query($query_tournclosed, $connvbsa) or die(mysql_error());
$row_tournclosed = mysql_fetch_assoc($tournclosed);
$totalRows_tournclosed = mysql_num_rows($tournclosed);
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
<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<table width="1000" align="center">
  <tr class="greenbg">
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="red_bold"><?php echo date("Y"); ?> Tournaments, Administrators have access to all views, cannot edit or insert financials.</td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="greenbg"><a href="../Admin_rankings/a_rankindex.php">Tournament Rankings</a></td>
  </tr>
</table>
    <table align="center" cellpadding="5" cellspacing="5">
      <tr>
        <td colspan="9" align="center" class="page" >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="9" align="center" class="red_bold" >SNOOKER TOURNAMENTS </td>
      </tr>
      <tr>
        <td align="center">Tourn ID</td>
        <td align="left">Tournament Name</td>
        <td align="left">Year</td>
        <td align="left">Class</td>
        <td>Draw Type</td>
        <td align="center">View on site</td>
        <td>Type</td>
        <td>Entries</td>
        <td>&nbsp;</td>
      </tr>
      <?php if($totalRows_tourn1 >=1) do { ?>
        <tr>
          <td align="center"><?php echo $row_tourn1['tourn_id']; ?></td>
          <td align="left"><?php echo $row_tourn1['tourn_name']; ?></td>
          <td align="left"><?php $newDate = date("Y", strtotime($row_tourn1['tourn_year'])); echo $newDate; ?></td>
          <td align="left"><?php echo $row_tourn1['tourn_class']; ?></td>
          <td><?php echo $row_tourn1['tourn_draw']; ?></td>
          <td align="center"><?php echo $row_tourn1['site_visible']; ?></td>
          <td><?php echo $row_tourn1['tourn_type']; ?></td>
          <td><?php echo $row_tourn1['status']; ?></td>
          <td><a href="general_tourn_detail.php?tourn_id=<?php echo $row_tourn1['tourn_id']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" height="20" title="View all entries" /></a></td>
        </tr>
        <?php } while ($row_tourn1 = mysql_fetch_assoc($tourn1)); else echo '<tr><td colspan="9" align="center">'."No tournaments listed".'</td></tr>'; ?>
    </table>
<table align="center" cellpadding="5" cellspacing="5">
      <tr>
        <td colspan="9" align="center" class="red_bold" >BILLIARDS TOURNAMENTS</td>
      </tr>
      <tr>
        <td align="center">Tourn ID</td>
        <td align="left">Tournament Name</td>
        <td align="left">Year</td>
        <td align="left">Class</td>
        <td>Draw Type</td>
        <td align="center">View on site</td>
        <td>Type</td>
        <td>Entries</td>
        <td>&nbsp;</td>
      </tr>
      <?php if($totalRows_tourn2 >=1) do { ?>
      <tr>
        <td align="center"><?php echo $row_tourn2['tourn_id']; ?></td>
        <td align="left"><?php echo $row_tourn2['tourn_name']; ?></td>
        <td align="left"><?php $newDate = date("Y", strtotime($row_tourn2['tourn_year'])); echo $newDate; ?></td>
        <td align="left"><?php echo $row_tourn2['tourn_class']; ?></td>
        <td><?php echo $row_tourn2['tourn_draw']; ?></td>
        <td align="center"><?php echo $row_tourn2['site_visible']; ?></td>
        <td><?php echo $row_tourn2['tourn_type']; ?></td>
        <td><?php echo $row_tourn2['status']; ?></td>
        <td><a href="general_tourn_detail.php?tourn_id=<?php echo $row_tourn2['tourn_id']; ?>"><img src="../Admin_Images/detail.fw.png" alt="1" width="20" height="20" title="View all entries" /></a></td>
      </tr>
      <?php } while ($row_tourn2 = mysql_fetch_assoc($tourn2)); else echo '<tr><td colspan="9" align="center">'."No tournaments listed".'</td></tr>'; ?>
</table>
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan="9" align="center" class="red_bold" >CLOSED TOURNAMENTS </td>
  </tr>
  <tr>
    <td align="center">Tourn ID</td>
    <td align="left">Tournament Name</td>
    <td align="left">Year</td>
    <td align="left">Class</td>
    <td>Draw Type</td>
    <td align="center">View on site</td>
    <td>Type</td>
    <td>Entries</td>
    <td>&nbsp;</td>
  </tr>
  <?php if($totalRows_tournclosed >=1) do { ?>
  <tr>
    <td align="center"><?php echo $row_tournclosed['tourn_id']; ?></td>
    <td align="left"><?php echo $row_tournclosed['tourn_name']; ?></td>
    <td align="left"><?php $newDate = date("Y", strtotime($row_tournclosed['tourn_year'])); echo $newDate; ?></td>
    <td align="left"><?php echo $row_tournclosed['tourn_class']; ?></td>
    <td><?php echo $row_tournclosed['tourn_draw']; ?></td>
    <td align="center"><?php echo $row_tournclosed['site_visible']; ?></td>
    <td><?php echo $row_tournclosed['tourn_type']; ?></td>
    <td><?php echo $row_tournclosed['status']; ?></td>
    <td><a href="general_tourn_detail.php?tourn_id=<?php echo $row_tournclosed['tourn_id']; ?>"><img src="../Admin_Images/detail.fw.png" alt="1" width="20" height="20" title="View all entries" /></a></td>
  </tr>
  <?php } while ($row_tournclosed = mysql_fetch_assoc($tournclosed)); else echo '<tr><td colspan="9" align="center">'."No tournaments listed".'</td></tr>'; ?>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php

?>
