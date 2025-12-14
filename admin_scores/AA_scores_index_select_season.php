<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster,Secretary,Scores";
$MM_donotCheckaccess = "false";

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
    if (($strUsers == "") && false) { 
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
//if (!isset($_SESSION)) {
//  session_start();
//}


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

$colname_getusername = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getusername = $_SESSION['MM_Username'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_getusername = sprintf("SELECT id, name, username, usertype FROM vbsaorga_users WHERE username = %s", GetSQLValueString($colname_getusername, "text"));
$getusername = mysql_query($query_getusername, $connvbsa) or die(mysql_error());
$row_getusername = mysql_fetch_assoc($getusername);
$totalRows_getusername = mysql_num_rows($getusername);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

	<table align="center" cellpadding="3" cellspacing="3">
	  <tr>
	    <th colspan="2" scope="col">&nbsp;</th>
      </tr>
	  <tr>
	    <td colspan="2" align="center">Logged in:<?php echo $row_getusername['name']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; User type:<?php echo $row_getusername['usertype']; ?></td>
    </tr>
	  <tr>
	    <td colspan="2" class="red_bold">Scores, Administrators have access to all views, please do not edit or insert scores without contacting the Score Registrar </td>
      </tr>
	  <tr>
	    <td colspan="2">&nbsp;</td>
      </tr>
	  <tr>
	    <td colspan="2" align="center">All information, data entry, rankings update etc is performed from either of these 2 pages</td>
      </tr>
	  <tr>
	    <td colspan="2" align="center">Access - Score Registrar, Treasurer and Webmaster</td>
      </tr>
	  <tr>
	    <td width="594">&nbsp;</td>
	    <td width="261" align="center" class="greenbg">&nbsp;</td>
      </tr>
	  <tr>
	    <td align="right">Season 1 - Premier &amp; State Snooker and State grade Billiards</td>
	    <td align="left" class="greenbg"><a href="AA_scores_index_grades.php?season=S1">Enter</a></td>
      </tr>
	  <tr>
	    <td align="right">&nbsp;</td>
	    <td align="left">&nbsp;</td>
      </tr>
	  <tr>
	    <td align="right">Season 2 - Premier Billliards and Willis and State Snooker</td>
	    <td align="left" class="greenbg"><a href="AA_scores_index_grades.php?season=S2">Enter</a></td>
      </tr>
	  <tr>
	    <td>&nbsp;</td>
	    <td align="left">&nbsp;</td>
      </tr>
</table>
	<table align="center" class="greenbg_menu">
	  <tr>
	    <td width="288">&nbsp;</td>
	    <td>&nbsp;</td>
      </tr>
	  <tr>
	    <td><a href="players_current.php">All   players</a></td>
	    <td>View all players currently playing  and edit (Both S1 and S2)</td>
      </tr>
	  <tr>
	    <td><a href="players_snooker.php">All players Snooker </a></td>
	    <td>View all qualified players currently playing snooker in the current yearand edit (Both S1 and S2)</td>
      </tr>
	  <tr>
	    <td><a href="players_billiards.php">All players Billiards </a></td>
	    <td>View all players currently playing billiards in the current year and edit (Both S1 and S2)</td>
      </tr>
	  <tr>
	    <td colspan="2">&nbsp;</td>
      </tr>
	  <tr>
	    <td><a href="brks_last_50.php">Breaks - Last 50 recorded</a></td>
	    <td>&nbsp;</td>
      </tr>
	  <tr>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
      </tr>
	  <tr>
	    <td>Help files</td>
	    <td>&nbsp;</td>
      </tr>
	  <tr>
	    <td><a href="../admin_help/files/VBSA_database_about.pdf" target="_blank">About the VBSA database</a></td>
	    <td>&nbsp;</td>
      </tr>
	  <tr>
	    <td><a href="../admin_help/files/VBSA_Admin_enter_teams.pdf" target="_blank">Inserting teams</a></td>
	    <td>&nbsp;</td>
      </tr>
	  <tr>
	    <td><a href="../admin_help/files/VBSA_Admin_enter_BYE.pdf" target="_blank">Inserting a BYE</a></td>
	    <td>&nbsp;</td>
      </tr>
	  <tr>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
      </tr>
	  <tr>
	    <td><a href="mailto:web@vbsa.org.au">web@vbsa.org.au</a></td>
	    <td>If there is a view that is not listed that would suit your purpose please let me know </td>
      </tr>
</table>
	<p>&nbsp;</p>
</body>
</html>
<?php

?>
