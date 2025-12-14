<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
  
  $logoutGoTo = "../Admin_Extra/vbsa_extra_logout.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
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

$MM_restrictGoTo = "Access_Denied.php";
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
?>
<?php require_once('../Connections/connvbsa.php'); ?><?php
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
$query_fix_list = "SELECT * FROM affiliate_extra_help WHERE file_type='fixture'";
$fix_list = mysql_query($query_fix_list, $connvbsa) or die(mysql_error());
$row_fix_list = mysql_fetch_assoc($fix_list);
$totalRows_fix_list = mysql_num_rows($fix_list);

mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn_list = "SELECT * FROM affiliate_extra_help WHERE file_type='tournament'";
$tourn_list = mysql_query($query_tourn_list, $connvbsa) or die(mysql_error());
$row_tourn_list = mysql_fetch_assoc($tourn_list);
$totalRows_tourn_list = mysql_num_rows($tourn_list);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Affiliate database entry</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />


</head>

<body>
<table width="1000" align="center">
  <tr>
    <td><img src="../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>
<table align="center" cellpadding="10" class="extra_text">
  <tr>
    <td align="right">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center"><span class="greenbg"><a href="Extra_change_pwd.php">Change Password</a></span></td>
    <td align="center"><span class="greenbg"><a href="<?php echo $logoutAction ?>">Logout</a></span></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="right">Access by:</td>
    <td align="center">Ballarat BSA users</td>
    <td align="center">Church Users</td>
    <td align="center">City Clubs users</td>
    <td align="center">DVSA Users</td>
    <td align="center">MSBA users</td>
    <td align="center">Over 55's users</td>
    <td align="center">RSL users</td>
    <td align="center">SBSA users</td>
    <td align="center">VBSA gallery</td>
    </tr>
  <tr>
    <td align="right">Enter:</td>
    <td align="center" class="greenbg"><a href="BBSA/BBSA_index_admin.php">Ballarat BSA Index</a></td>
    <td align="center" class="greenbg"><a href="Church/Church_index_admin.php">Church Index</a></td>
    <td align="center" class="greenbg"><a href="CityClubs/CC_index_admin.php">City Clubs Index</a></td>
    <td align="center" class="greenbg"><a href="DVSA/DVSA_index_admin.php">DVSA Index</a></td>
    <td align="center" class="greenbg"><a href="MSBA/MSBA_index_admin.php">MSBA Index</a></td>
    <td align="center" class="greenbg"><a href="O55/O55_index_admin.php">O55 Index</a></td>
    <td align="center" class="greenbg"><a href="RSL/RSL_index_admin.php">RSL Index</a></td>
    <td align="center" class="greenbg"><a href="SBSA/SBSA_index_admin.php">SBSA Index</a></td>
    <td align="center" class="greenbg"><a href="VBSA_gallery/index_admin.php">Gallery  Index</a></td>
    </tr>
  <tr>
    <td height="10" align="right">&nbsp;</td>
    <td align="center" class="greenbg">&nbsp;</td>
    <td align="center" class="greenbg">&nbsp;</td>
    <td align="center" class="greenbg">&nbsp;</td>
    <td align="center" class="greenbg">&nbsp;</td>
    <td align="center" class="greenbg">&nbsp;</td>
    <td align="center" class="greenbg">&nbsp;</td>
    <td align="center" class="greenbg">&nbsp;</td>
    <td align="center" class="greenbg">&nbsp;</td>
    <td align="center" class="greenbg">&nbsp;</td>
  </tr>
</table>