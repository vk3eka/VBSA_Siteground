<?php require_once('../../Connections/connvbsa.php'); ?>
<?php
error_reporting(0);

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

$MM_restrictGoTo = "../../page_error.php";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE members SET  LastUpdated=%s, UpdateBy=%s, paid_memb=%s, paid_how=%s, paid_date=%s WHERE MemberID=%s",
                       GetSQLValueString($_POST['LastUpdated'], "date"),
                       GetSQLValueString($_POST['UpdateBy'], "text"),
					             GetSQLValueString($_POST['paid_memb'], "int"),
          					   GetSQLValueString($_POST['paid_how'], "text"),
          					   GetSQLValueString($_POST['paid_date'], "date"),
                       GetSQLValueString($_POST['MemberID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = $_SESSION['page'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['tourn_id'])) {
  $tourn_id = $_GET['tourn_id'];
}

if (isset($_GET['memb_id'])) {
  $memb_id = $_GET['memb_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_membedit = "SELECT MemberID, LastName, FirstName, LastUpdated, UpdateBy,  entered_on,  paid_memb, paid_how, paid_date FROM members WHERE members.MemberID='$memb_id'";
$membedit = mysql_query($query_membedit, $connvbsa) or die(mysql_error());
$row_membedit = mysql_fetch_assoc($membedit);
$totalRows_membedit = mysql_num_rows($membedit);


$colname_getusername = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getusername = $_SESSION['MM_Username'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_getusername = sprintf("SELECT id, name, usertype FROM vbsaorga_users WHERE username = %s", GetSQLValueString($colname_getusername, "text"));
$getusername = mysql_query($query_getusername, $connvbsa) or die(mysql_error());
$row_getusername = mysql_fetch_assoc($getusername);
$totalRows_getusername = mysql_num_rows($getusername);

mysql_select_db($database_connvbsa, $connvbsa);
$query_played = "SELECT FirstName, LastName, lifemember, team_grade, count_played, game_type,  current_year_scrs FROM scrs  LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE scrs.MemberID='$memb_id' AND (current_year_scrs = YEAR(CURDATE( )) OR current_year_scrs = YEAR(CURDATE( ))-1) ORDER BY current_year_scrs DESC";
$played = mysql_query($query_played, $connvbsa) or die(mysql_error());
$row_played = mysql_fetch_assoc($played);
$totalRows_played = mysql_num_rows($played);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />


<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>

<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>

<table width="1000" align="center" cellpadding="2">
  <tr>
    <td colspan="2"><?php echo $_SESSION['page'] ?></td>
  </tr>
  <tr>
    <td class="red_bold">EDIT PLAYER FINANCIAL</td>
    <td align="right" class="red_bold"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<div id="DBcontent">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1"  >
              <table width="1000" align="center" cellpadding="4" cellspacing="4">
                      <tr>
                        <td colspan="6" align="left" nowrap="nowrap">To edit personal detail please go to the members area</td>
                      </tr>
                      <tr>
                        <td align="right" nowrap="nowrap">Member ID: </td>
                        <td align="left"><?php echo $row_membedit['MemberID']; ?></td>
                        <td align="right">Last Name: </td>
                        <td align="left"><?php echo $row_membedit['LastName']; ?></td>
                        <td align="right">First Name</td>
                        <td align="left"><?php echo $row_membedit['FirstName']; ?></td>
                      </tr>
                      <tr>
                        <td align="right">Entered on</td>
                        <td>&nbsp;
    						<?php
							$year = date("Y", strtotime($row_membedit['entered_on']));
							if($year >1900) {
							$newDate = date("d M Y", strtotime($row_membedit['entered_on'])); 
							echo $newDate; }
							else
							echo "Not Known";
							?>
                        </td>
                        <td align="right">Last Updated</td>
                        <td><?php $newDate = date("d M Y", strtotime($row_membedit['LastUpdated'])); echo $newDate; ?></td>
                        <td align="right">Updated By</td>
                        <td><?php echo $row_membedit['UpdateBy']; ?></td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                      </tr>
    </table>



  <table width="1000" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan="6"><span class="red_bold"><?php echo date("Y"); ?> Financial Detail</span></td>
  </tr>
  <tr>
    <td align="right">Paid $</td>
    <td align="left"><input type="text" name="paid_memb" value="<?php echo htmlentities($row_membedit['paid_memb'], ENT_COMPAT, 'utf-8'); ?>" size="10" /></td>
    <td align="right">How Paid:</td>
    <td><select name="paid_how">
            <option value="" >No Entry</option>
            <option value="PP" <?php if (!(strcmp("PP", htmlentities($row_membedit['paid_how'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>PP</option>
            <option value="Cash" <?php if (!(strcmp("Cash", htmlentities($row_membedit['paid_how'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Cash</option>
            <option value="BT" <?php if (!(strcmp("BT", htmlentities($row_membedit['paid_how'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>BT</option>
            <option value="CHQ" <?php if (!(strcmp("CHQ", htmlentities($row_membedit['paid_how'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>CHQ</option>
            <option value="Other" <?php if (!(strcmp("Other", htmlentities($row_membedit['paid_how'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Other</option>
          </select>
    </td>
    <td align="right">Date Paid:&nbsp;</td>
    <td align="left"><input type="text" name="paid_date" value="<?php echo htmlentities($row_membedit['paid_date'], ENT_COMPAT, 'utf-8'); ?>" size="15" /> <input type="button" value="Select Date Paid" onclick="displayDatePicker('paid_date', false, 'ymd', '.');" />      
      Please select Date (remove ALL fields if removing as paid)</td>
    </tr>
  </table>
  <table width="1000" align="center" cellpadding="5" cellspacing="5">
  	<tr>
    <td align="center"><input type="submit" value="Update Member Financials" /></td>
    </tr>
  </table>
  
<input type="hidden" name="MM_update" value="form1" />
      	<input type="hidden" name="MemberID" value="<?php echo $row_membedit['MemberID']; ?>" />
        <input type="hidden" name="UpdateBy" value="<?php echo $row_getusername['name']; ?>" />
        <input type="hidden" name="LastUpdated" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d"); ?> " />
      	<input type="hidden" name="MemberID" value="<?php echo $row_membedit['MemberID']; ?>" />
      	<input type="hidden" name="MM_update" value="form1" />
  </form>
  </div>
<div id="DBcontent">
  <table width="1000" align="center" cellpadding="2" cellspacing="2">
    <tr>
      <td colspan="6" class="red_bold">Playing History, Past 2 years</td>
    </tr>
    <tr>
      <td align="center">Grade</td>
      <td align="center">Matches played</td>
      <td align="center">In year</td>
      <td align="center">&nbsp;</td>
      <td width="150" align="center">&nbsp;</td>
      <td width="150" align="center">&nbsp;</td>
    </tr>
    <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_played['team_grade']; ?></td>
      <td align="center"><?php echo $row_played['count_played']; ?></td>
      <td align="center"><?php echo $row_played['current_year_scrs']; ?></td>
      <td align="center"><?php echo $row_played['game_type']; ?></td>
      <td width="150" align="center">&nbsp;</td>
      <td width="150" align="center">&nbsp;</td>
    </tr>
    <?php } while ($row_played = mysql_fetch_assoc($played)); ?>
  </table>
</div>
</body>
</html>
<?php

?>
