<?php require_once('../../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster,Treasurer";
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

$MM_restrictGoTo = "../../page_error.php";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tournaments SET site_visible=%s, tourn_type=%s, ranking_type=%s, tourn_class=%s, how_seed=%s, status=%s WHERE tourn_id=%s",
                       GetSQLValueString($_POST['site_visible'], "text"),
                       GetSQLValueString($_POST['tourn_type'], "text"),
                       GetSQLValueString($_POST['ranking_type'], "text"),
                       GetSQLValueString($_POST['tourn_class'], "text"),
                       GetSQLValueString($_POST['how_seed'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['tourn_id'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

    $updateGoTo = $_SESSION['../calendar_list'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['event_id'])) {
  $tourn_id = $_GET['event_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn1 = "SELECT * FROM tournaments WHERE tourn_id = '$event_id'";
$tourn1 = mysql_query($query_tourn1, $connvbsa) or die(mysql_error());
$row_tourn1 = mysql_fetch_assoc($tourn1);
$totalRows_tourn1 = mysql_num_rows($tourn1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

<table width="800" align="center">
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="772" align="center" class="red_bold">Edit selected tournament Entry list</td>
    <td width="216" align="center"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" > 
      <table align="center" cellpadding="5" cellspacing="5">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tournament ID: </td>
          <td><?php echo $row_tourn1['tourn_id']; ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tournament Name:</td>
          <td><?php echo $row_tourn1['tourn_name']; ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tournament Class:</td>
          <td><select name="tourn_class">
            <option value="Aust Rank" <?php if (!(strcmp("Aust Rank", htmlentities($row_tourn1['tourn_class'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Aust Rank</option>
            <option value="Victorian" <?php if (!(strcmp("Victorian", htmlentities($row_tourn1['tourn_class'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Victorian</option>
            <option value="Junior" <?php if (!(strcmp("Junior", htmlentities($row_tourn1['tourn_class'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Junior</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">How is this tournament seeded?</td>
          <td><select name="how_seed">
          <option value="NA" <?php if (!(strcmp("NA", htmlentities($row_tourn1['how_seed'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Not Applicable</option>
          <option value="Aust Rankings" <?php if (!(strcmp("Aust Rankings", htmlentities($row_tourn1['how_seed'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Aust Rankings</option>
          <option value="Vic Rankings" <?php if (!(strcmp("Vic Rankings", htmlentities($row_tourn1['how_seed'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Victorian Rankings</option>
          <option value="Aust Womens Rankings" <?php if (!(strcmp("Aust Womens Rankings", htmlentities($row_tourn1['how_seed'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Aust Womens Rankings</option>
          <option value="Vic Womens Rankings" <?php if (!(strcmp("Vic Womens Rankings", htmlentities($row_tourn1['how_seed'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Vic Womens Rankings</option>
          <option value="Junior Rankings" <?php if (!(strcmp("Junior Rankings", htmlentities($row_tourn1['how_seed'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Junior Rankings</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Does this tournament attract ranking points in Victoria?</td>
          <td><select name="ranking_type">
          <option value="No Entry" <?php if (!(strcmp("No Entry", htmlentities($row_tourn1['ranking_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No Entry</option>
            <option value="Vic Rank" <?php if (!(strcmp("Vic Rank", htmlentities($row_tourn1['ranking_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Vic Rank</option>
            <option value="Womens Rank" <?php if (!(strcmp("Womens Rank", htmlentities($row_tourn1['ranking_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Womens Rank</option>
            <option value="Junior Rank" <?php if (!(strcmp("Junior Rank", htmlentities($row_tourn1['ranking_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Junior Rank</option>
          </select> 
            Select type</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tournament Year:</td>
          <td><?php $newDate = date("Y", strtotime($row_tourn1['tourn_year'])); echo $newDate; ?> (cannot be edited)</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Do you want this tournament to be visible on the website: </td>
          <td><select name="site_visible">
            <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_tourn1['site_visible'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
            <option value="No" <?php if (!(strcmp("No", htmlentities($row_tourn1['site_visible'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tournament Type: </td>
          <td><select name="tourn_type">
            <option value="Snooker" <?php if (!(strcmp("Snooker", htmlentities($row_tourn1['tourn_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Snooker</option>
            <option value="Billiards" <?php if (!(strcmp("Billiards", htmlentities($row_tourn1['tourn_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Billiards</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Entries:</td>
          <td><select name="status">
            <option value="Open" <?php if (!(strcmp("Open", htmlentities($row_tourn1['status'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Open</option>
            <option value="Closed" <?php if (!(strcmp("Closed", htmlentities($row_tourn1['status'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Closed</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Update Tournament" /></td>
        </tr>
      </table>
      <input type="hidden" name="tourn_id" value="<?php echo $row_tourn1['tourn_id']; ?>" />
      <input type="hidden" name="MM_update" value="form1" />
</form>
    <p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php

?>

