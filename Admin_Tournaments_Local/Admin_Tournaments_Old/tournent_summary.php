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
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?><?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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
$query_ABTimInc = "SELECT SUM(members.NB_timed_ent) FROM members";
$ABTimInc = mysql_query($query_ABTimInc, $connvbsa) or die(mysql_error());
$row_ABTimInc = mysql_fetch_assoc($ABTimInc);
$totalRows_ABTimInc = mysql_num_rows($ABTimInc);

mysql_select_db($database_connvbsa, $connvbsa);
$query_ABOpenEnt = "SELECT COUNT(members.NB_open_ent) FROM members";
$ABOpenEnt = mysql_query($query_ABOpenEnt, $connvbsa) or die(mysql_error());
$row_ABOpenEnt = mysql_fetch_assoc($ABOpenEnt);
$totalRows_ABOpenEnt = mysql_num_rows($ABOpenEnt);

mysql_select_db($database_connvbsa, $connvbsa);
$query_ABTimEnt = "SELECT COUNT(members.NB_timed_ent) FROM members";
$ABTimEnt = mysql_query($query_ABTimEnt, $connvbsa) or die(mysql_error());
$row_ABTimEnt = mysql_fetch_assoc($ABTimEnt);
$totalRows_ABTimEnt = mysql_num_rows($ABTimEnt);

mysql_select_db($database_connvbsa, $connvbsa);
$query_ABOpenInc = "SELECT SUM(members.NB_open_ent) FROM members";
$ABOpenInc = mysql_query($query_ABOpenInc, $connvbsa) or die(mysql_error());
$row_ABOpenInc = mysql_fetch_assoc($ABOpenInc);
$totalRows_ABOpenInc = mysql_num_rows($ABOpenInc);

mysql_select_db($database_connvbsa, $connvbsa);
$query_TotEnt = "SELECT COUNT( members.RF_Ent ) + COUNT( members.JMcK_Ent ) + COUNT( members.GC_Ent ) + COUNT( members.GG_Ent ) + COUNT( members.Com_Ent ) + COUNT( members.NB_timed_Ent ) + COUNT( members.NB_open_Ent ) + COUNT( members.Aust_Nat_Ent ) + COUNT( members.Vic_Mens_Ent ) + COUNT( members.Vic_Wom_Ent ) + COUNT(members.Under_12_Vic) + COUNT(members.Under_15_Vic) + COUNT(members.Under_18_Vic) + COUNT(members.Under_18_VicBill) + COUNT(members.Under_21_VicBill) + COUNT(members.Vic_Bill_ent) + COUNT(members.Under_21_Vic) FROM members";
$TotEnt = mysql_query($query_TotEnt, $connvbsa) or die(mysql_error());
$row_TotEnt = mysql_fetch_assoc($TotEnt);
$totalRows_TotEnt = mysql_num_rows($TotEnt);

mysql_select_db($database_connvbsa, $connvbsa);
$query_TotalInc = "SELECT SUM(members.NB_timed_ent) + SUM(members.NB_open_ent) + SUM(members.RF_ent) + SUM(members.GC_ent) + SUM(members.GG_ent) + SUM(members.JMcK_ent) + SUM(members.COM_ent) + SUM(members.Vic_Mens_Ent) + SUM(members.Vic_Wom_Ent) + SUM(members.Under_15_Vic) + SUM(members.Under_12_Vic) + SUM(members.Under_18_Vic) FROM members";
$TotalInc = mysql_query($query_TotalInc, $connvbsa) or die(mysql_error());
$row_TotalInc = mysql_fetch_assoc($TotalInc);
$totalRows_TotalInc = mysql_num_rows($TotalInc);

mysql_select_db($database_connvbsa, $connvbsa);
$query_RFent = "SELECT COUNT(members.RF_ent) FROM members";
$RFent = mysql_query($query_RFent, $connvbsa) or die(mysql_error());
$row_RFent = mysql_fetch_assoc($RFent);
$totalRows_RFent = mysql_num_rows($RFent);

mysql_select_db($database_connvbsa, $connvbsa);
$query_RFInc = "SELECT SUM(members.RF_ent) FROM members";
$RFInc = mysql_query($query_RFInc, $connvbsa) or die(mysql_error());
$row_RFInc = mysql_fetch_assoc($RFInc);
$totalRows_RFInc = mysql_num_rows($RFInc);

mysql_select_db($database_connvbsa, $connvbsa);
$query_GCent = "SELECT COUNT(members.GC_ent) FROM members";
$GCent = mysql_query($query_GCent, $connvbsa) or die(mysql_error());
$row_GCent = mysql_fetch_assoc($GCent);
$totalRows_GCent = mysql_num_rows($GCent);

mysql_select_db($database_connvbsa, $connvbsa);
$query_JMcKent = "SELECT COUNT(members.JMcK_ent) FROM members";
$JMcKent = mysql_query($query_JMcKent, $connvbsa) or die(mysql_error());
$row_JMcKent = mysql_fetch_assoc($JMcKent);
$totalRows_JMcKent = mysql_num_rows($JMcKent);

mysql_select_db($database_connvbsa, $connvbsa);
$query_GGent = "SELECT COUNT(members.GG_ent) FROM members";
$GGent = mysql_query($query_GGent, $connvbsa) or die(mysql_error());
$row_GGent = mysql_fetch_assoc($GGent);
$totalRows_GGent = mysql_num_rows($GGent);

mysql_select_db($database_connvbsa, $connvbsa);
$query_COMent = "SELECT COUNT(members.COM_ent) FROM members";
$COMent = mysql_query($query_COMent, $connvbsa) or die(mysql_error());
$row_COMent = mysql_fetch_assoc($COMent);
$totalRows_COMent = mysql_num_rows($COMent);

mysql_select_db($database_connvbsa, $connvbsa);
$query_NSent = "SELECT COUNT(members.Aust_Nat_Ent) FROM members";
$NSent = mysql_query($query_NSent, $connvbsa) or die(mysql_error());
$row_NSent = mysql_fetch_assoc($NSent);
$totalRows_NSent = mysql_num_rows($NSent);

mysql_select_db($database_connvbsa, $connvbsa);
$query_VSent = "SELECT COUNT(members.Vic_Mens_Ent) FROM members";
$VSent = mysql_query($query_VSent, $connvbsa) or die(mysql_error());
$row_VSent = mysql_fetch_assoc($VSent);
$totalRows_VSent = mysql_num_rows($VSent);

mysql_select_db($database_connvbsa, $connvbsa);
$query_VSLent = "SELECT COUNT(members.Vic_Wom_Ent) FROM members";
$VSLent = mysql_query($query_VSLent, $connvbsa) or die(mysql_error());
$row_VSLent = mysql_fetch_assoc($VSLent);
$totalRows_VSLent = mysql_num_rows($VSLent);

mysql_select_db($database_connvbsa, $connvbsa);
$query_VJSent = "SELECT COUNT(members.Under_12_Vic) + COUNT(members.Under_15_Vic) + COUNT(members.Under_18_Vic) FROM members";
$VJSent = mysql_query($query_VJSent, $connvbsa) or die(mysql_error());
$row_VJSent = mysql_fetch_assoc($VJSent);
$totalRows_VJSent = mysql_num_rows($VJSent);

mysql_select_db($database_connvbsa, $connvbsa);
$query_VJBent = "SELECT COUNT(members.Under_18_VicBill) + COUNT(members.Under_21_VicBill) FROM members";
$VJBent = mysql_query($query_VJBent, $connvbsa) or die(mysql_error());
$row_VJBent = mysql_fetch_assoc($VJBent);
$totalRows_VJBent = mysql_num_rows($VJBent);

mysql_select_db($database_connvbsa, $connvbsa);
$query_VBent = "SELECT COUNT(members.Vic_Bill_ent) FROM members";
$VBent = mysql_query($query_VBent, $connvbsa) or die(mysql_error());
$row_VBent = mysql_fetch_assoc($VBent);
$totalRows_VBent = mysql_num_rows($VBent);

mysql_select_db($database_connvbsa, $connvbsa);
$query_VU21Sent = "SELECT COUNT(members.Under_21_Vic) FROM members";
$VU21Sent = mysql_query($query_VU21Sent, $connvbsa) or die(mysql_error());
$row_VU21Sent = mysql_fetch_assoc($VU21Sent);
$totalRows_VU21Sent = mysql_num_rows($VU21Sent);

mysql_select_db($database_connvbsa, $connvbsa);
$query_GCinc = "SELECT SUM(members.GC_ent) FROM members";
$GCinc = mysql_query($query_GCinc, $connvbsa) or die(mysql_error());
$row_GCinc = mysql_fetch_assoc($GCinc);
$totalRows_GCinc = mysql_num_rows($GCinc);

mysql_select_db($database_connvbsa, $connvbsa);
$query_JMcKinc = "SELECT SUM(members.JMcK_ent) FROM members";
$JMcKinc = mysql_query($query_JMcKinc, $connvbsa) or die(mysql_error());
$row_JMcKinc = mysql_fetch_assoc($JMcKinc);
$totalRows_JMcKinc = mysql_num_rows($JMcKinc);

mysql_select_db($database_connvbsa, $connvbsa);
$query_GGinc = "SELECT SUM(members.GG_ent) FROM members";
$GGinc = mysql_query($query_GGinc, $connvbsa) or die(mysql_error());
$row_GGinc = mysql_fetch_assoc($GGinc);
$totalRows_GGinc = mysql_num_rows($GGinc);

mysql_select_db($database_connvbsa, $connvbsa);
$query_COMinc = "SELECT SUM(members.COM_ent) FROM members";
$COMinc = mysql_query($query_COMinc, $connvbsa) or die(mysql_error());
$row_COMinc = mysql_fetch_assoc($COMinc);
$totalRows_COMinc = mysql_num_rows($COMinc);

mysql_select_db($database_connvbsa, $connvbsa);
$query_VSinc = "SELECT SUM(members.Vic_Mens_Ent) FROM members";
$VSinc = mysql_query($query_VSinc, $connvbsa) or die(mysql_error());
$row_VSinc = mysql_fetch_assoc($VSinc);
$totalRows_VSinc = mysql_num_rows($VSinc);

mysql_select_db($database_connvbsa, $connvbsa);
$query_VSLinc = "SELECT SUM(members.Vic_Wom_Ent) FROM members";
$VSLinc = mysql_query($query_VSLinc, $connvbsa) or die(mysql_error());
$row_VSLinc = mysql_fetch_assoc($VSLinc);
$totalRows_VSLinc = mysql_num_rows($VSLinc);

mysql_select_db($database_connvbsa, $connvbsa);
$query_VJSinc = "SELECT SUM(members.Under_15_Vic) + SUM(members.Under_12_Vic) + SUM(members.Under_18_Vic) FROM members";
$VJSinc = mysql_query($query_VJSinc, $connvbsa) or die(mysql_error());
$row_VJSinc = mysql_fetch_assoc($VJSinc);
$totalRows_VJSinc = mysql_num_rows($VJSinc);

mysql_select_db($database_connvbsa, $connvbsa);
$query_VJBinc = "SELECT SUM(members.Under_18_VicBill) + SUM(members.Under_21_VicBill) FROM members";
$VJBinc = mysql_query($query_VJBinc, $connvbsa) or die(mysql_error());
$row_VJBinc = mysql_fetch_assoc($VJBinc);
$totalRows_VJBinc = mysql_num_rows($VJBinc);

$currentPage = $_SERVER["PHP_SELF"];

$queryString_MembHistory = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_MembHistory") == false && 
        stristr($param, "totalRows_MembHistory") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_MembHistory = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_MembHistory = sprintf("&totalRows_MembHistory=%d%s", $totalRows_MembHistory, $queryString_MembHistory);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
  
  <table width="1000" border="1" align="center">
    <tr>
      <th width="46" align="left">Ref</th>
      <th width="222" align="left">Tournament</th>
      <th width="150" align="left">Entries</th>
      <th width="150" align="left">Entry Fee Income</th>
      <th width="150" align="left">Other Income</th>
      <th width="150" align="left">Expenses</th>
      <th width="150" align="left">Balance</th>
    </tr>
    <tr>
      <td>GC</td>
      <td>Gary Cullen Handicap</td>
      <td width="150"><?php echo $row_GCent['COUNT(members.GC_ent)']; ?></td>
      <td width="150"><?php echo $row_GCinc['SUM(members.GC_ent)']; ?></td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr>
      <td>RF</td>
      <td>Rob Foldvari Handicap</td>
      <td width="150"><?php echo $row_RFent['COUNT(members.RF_ent)']; ?> </td>
      <td width="150"><?php echo $row_RFInc['SUM(members.RF_ent)']; ?></td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr>
      <td>JMcK</td>
      <td>John Mc Kay Handicap</td>
      <td width="150"><?php echo $row_JMcKent['COUNT(members.JMcK_ent)']; ?></td>
      <td width="150"><?php echo $row_JMcKinc['SUM(members.JMcK_ent)']; ?></td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr>
      <td>GG</td>
      <td>George Ganim Handicap</td>
      <td width="150"><?php echo $row_GGent['COUNT(members.GG_ent)']; ?> </td>
      <td width="150"><?php echo $row_GGinc['SUM(members.GG_ent)']; ?></td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr>
      <td>HSF</td>
      <td>Handicap Series Final</td>
      <td width="150" class="greytext">Top 16 Qualifiers</td>
      <td width="150" class="greytext">Invitational No ent Fee</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr>
      <td>COM</td>
      <td>City of Melbourne</td>
      <td width="150"><?php echo $row_COMent['COUNT(members.COM_ent)']; ?></td>
      <td width="150"><?php echo $row_COMinc['SUM(members.COM_ent)']; ?></td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr>
      <td>NBO</td>
      <td>Australian Billiards - Open</td>
      <td width="150"><?php echo $row_ABOpenEnt['COUNT(members.NB_open_ent)']; ?> </td>
      <td width="150"><?php echo $row_ABOpenInc['SUM(members.NB_open_ent)']; ?></td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr>
      <td>NBT</td>
      <td>Australian Billiards - Timed</td>
      <td width="150"><?php echo $row_ABTimEnt['COUNT(members.NB_timed_ent)']; ?> </td>
      <td width="150"><?php echo $row_ABTimInc['SUM(members.NB_timed_ent)']; ?></td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr>
      <td>NS</td>
      <td>National Snooker</td>
      <td width="150"><?php echo $row_NSent['COUNT(members.Aust_Nat_Ent)']; ?> </td>
      <td width="150"><?php echo $row_VSinc['SUM(members.Vic_Mens_Ent)']; ?></td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr>
      <td>VS</td>
      <td>Victorian Snooker</td>
      <td width="150"><?php echo $row_VSent['COUNT(members.Vic_Mens_Ent)']; ?> </td>
      <td width="150"><?php echo $row_VSinc['SUM(members.Vic_Mens_Ent)']; ?></td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr>
      <td>VSL</td>
      <td>Victorian Snooker - Ladies</td>
      <td width="150"><?php echo $row_VSLent['COUNT(members.Vic_Wom_Ent)']; ?></td>
      <td width="150"><?php echo $row_VSLinc['SUM(members.Vic_Wom_Ent)']; ?></td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr>
      <td>VJS</td>
      <td>Victorian Junior Snooker</td>
      <td width="150"><?php echo $row_VJSent['COUNT(members.Under_12_Vic) + COUNT(members.Under_15_Vic) + COUNT(members.Under_18_Vic)']; ?></td>
      <td width="150"><?php echo $row_VJSinc['SUM(members.Under_15_Vic) + SUM(members.Under_12_Vic) + SUM(members.Under_18_Vic)']; ?></td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr>
      <td>VJB</td>
      <td>Victorian Under 18 / 21 Billiards</td>
      <td width="150"><?php echo $row_VJBent['COUNT(members.Under_18_VicBill) + COUNT(members.Under_21_VicBill)']; ?></td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr>
      <td>VB</td>
      <td>Victorian Billiards</td>
      <td width="150"><?php echo $row_VBent['COUNT(members.Vic_Bill_ent)']; ?></td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr>
      <td>VU21S</td>
      <td>Victorian Under 21 Snooker</td>
      <td width="150"><?php echo $row_VU21Sent['COUNT(members.Under_21_Vic)']; ?></td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr>
      <td>PB</td>
      <td>Pot Black Snooker</td>
      <td width="150" class="greytext">Not Recorded in database</td>
      <td width="150"><span class="greytext">Not Recorded in database</span></td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr>
      <td>AJ</td>
      <td>Australian Juniors</td>
      <td><span class="greytext">Not Recorded in database</span></td>
      <td><span class="greytext">Not Recorded in database</span></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>FI</td>
      <td>Frankston RSL Invitational</td>
      <td width="150" class="greytext">Top 8 Vic ranked  players</td>
      <td width="150" class="greytext">Invitational No ent Fee</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
      <td width="150">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td class="pagetitle">Totals</td>
      <td width="150" class="pagetitle">$<?php echo $row_TotEnt['COUNT( members.RF_Ent ) + COUNT( members.JMcK_Ent ) + COUNT( members.GC_Ent ) + COUNT( members.GG_Ent ) + COUNT( members.Com_Ent ) + COUNT( members.NB_timed_Ent ) + COUNT( members.NB_open_Ent ) + COUNT( members.Aust_Nat_Ent ) + COUNT( members.Vic_Mens_Ent']; ?></td>
      <td width="150" class="pagetitle">$<?php echo $row_TotalInc['SUM(members.NB_timed_ent) + SUM(members.NB_open_ent) + SUM(members.RF_ent) + SUM(members.GC_ent) + SUM(members.GG_ent) + SUM(members.JMcK_ent) + SUM(members.COM_ent) + SUM(members.Vic_Mens_Ent) + SUM(members.Vic_Wom_Ent) + SUM(members.Under_15_Vic) + SUM(']; ?></td>
      <td width="150" class="pagetitle">&nbsp;</td>
      <td width="150" class="pagetitle">&nbsp;</td>
      <td width="150" class="pagetitle">&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
  </table>
  <p></p>
</center>
</body>
</html>
<?php
mysql_free_result($ABTimInc);

mysql_free_result($ABOpenEnt);

mysql_free_result($ABTimEnt);

mysql_free_result($ABOpenInc);

mysql_free_result($TotEnt);

mysql_free_result($TotalInc);

mysql_free_result($RFent);

mysql_free_result($RFInc);

mysql_free_result($GCent);

mysql_free_result($JMcKent);

mysql_free_result($GGent);

mysql_free_result($COMent);

mysql_free_result($NSent);

mysql_free_result($VSent);

mysql_free_result($VSLentent);

mysql_free_result($VJSent);

mysql_free_result($VJBent);

mysql_free_result($VBent);

mysql_free_result($VU21Sent);

mysql_free_result($GCinc);

mysql_free_result($JMcKinc);

mysql_free_result($GGinc);

mysql_free_result($COMinc);

mysql_free_result($VSinc);

mysql_free_result($VSLinc);

mysql_free_result($VJSinc);

mysql_free_result($VJBinc);

mysql_free_result($VSLent);
?>