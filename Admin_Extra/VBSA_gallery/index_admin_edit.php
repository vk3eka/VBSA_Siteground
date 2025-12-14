<?php require_once('../../Connections/connvbsa.php'); ?>
<?php include('../php_function.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster,VBSA";
$MM_donotCheckaccess = "false";
/*
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
*/
$MM_restrictGoTo = "../Access_Denied.php";
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
<?php require_once('../../ScriptLibrary/incPureUpload.php'); ?>
<?php
// Pure PHP Upload 2.1.12
$ppu = new pureFileUpload();
$ppu->path = "../../galleries/team_photo";
$ppu->extensions = "GIF,JPG,JPEG,BMP,PNG";
$ppu->formName = "form1";
$ppu->storeType = "file";
$ppu->sizeLimit = "120";
$ppu->nameConflict = "over";
$ppu->nameToLower = false;
$ppu->requireUpload = false;
$ppu->minWidth = "";
$ppu->minHeight = "";
$ppu->maxWidth = "";
$ppu->maxHeight = "";
$ppu->saveWidth = "";
$ppu->saveHeight = "";
$ppu->timeout = "600";
$ppu->progressBar = "";
$ppu->progressWidth = "300";
$ppu->progressHeight = "100";
$ppu->redirectURL = "";
$ppu->checkVersion("2.1.12");
$ppu->doUpload();

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

if (isset($editFormAction)) {
  if (isset($_SERVER['QUERY_STRING'])) {
	  if (!eregi("GP_upload=true", $_SERVER['QUERY_STRING'])) {
  	  $editFormAction .= "&GP_upload=true";
		}
  } else {
    $editFormAction .= "?GP_upload=true";
  }
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE gallery_team_photos SET grade=%s, season=%s, year_photo=%s, club_photo=IFNULL(%s,club_photo), `1or2`=%s, `current`=%s WHERE id=%s",
                       GetSQLValueString($_POST['grade'], "text"),
                       GetSQLValueString($_POST['season'], "text"),
                       GetSQLValueString($_POST['year_photo'], "date"),
                       GetSQLValueString($_POST['club_photo'], "text"),
                       GetSQLValueString($_POST['1or2'], "text"),
                       GetSQLValueString($_POST['current'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "index_admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_team_photo = "-1";
if (isset($_GET['id'])) {
  $colname_team_photo = $_GET['id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_team_photo = sprintf("SELECT * FROM gallery_team_photos WHERE gallery_team_photos.id <>1 AND gallery_team_photos.id=%s", GetSQLValueString($colname_team_photo, "date"));
$team_photo = mysql_query($query_team_photo, $connvbsa) or die(mysql_error());
$row_team_photo = mysql_fetch_assoc($team_photo);
$totalRows_team_photo = mysql_num_rows($team_photo);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Extra Administation Area</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="1000" align="center">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>
<script language='javascript' src='../../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>

<table width="1000" align="center" cellpadding="5">
  <tr>
    <td width="887" align="left"><span class="red_bold">Edit a listing in the  VBSA team photo Gallery </span></td>
    <td width="181" align="right" nowrap="nowrap"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="checkFileUpload(this,'GIF,JPG,JPEG,BMP,PNG',false,120,'','','','','','');return document.MM_returnValue">
      <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Photo ID:</td>
      <td><?php echo $row_team_photo['id']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Grade:</td>
      <td><input name="grade" type="text" id="grade" value="<?php echo $row_team_photo['grade']; ?>" />
        please type in the grade eg A Premier Snooker</td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Premiers or Runners Up?</td>
      <td><select name="1or2">
        <option value="Premiers" <?php if (!(strcmp("Premiers", htmlentities($row_team_photo['1or2'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Premiers</option>
        <option value="Runners Up" <?php if (!(strcmp("Runners Up", htmlentities($row_team_photo['1or2'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Runners Up</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Season:</td>
      <td><select name="season">
        <option value="S1" <?php if (!(strcmp("S1", htmlentities($row_team_photo['season'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>S1</option>
        <option value="S2" <?php if (!(strcmp("S2", htmlentities($row_team_photo['season'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>S2</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Year:</td>
      <td><input type="text" name="year_photo" value="<?php echo htmlentities($row_team_photo['year_photo'], ENT_COMPAT, 'utf-8'); ?>" size="32" /> 
        Please type the year the photo was taken</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Current Photo:</td>
      <td><?php echo $row_team_photo['club_photo']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Replace photo</td>
      <td><input name="club_photo" type="file" id="club_photo" onchange="checkOneFileUpload(this,'GIF,JPG,JPEG,BMP,PNG',false,120,'','','','','','')" value="<?php echo $row_team_photo['club_photo']; ?>" size="50" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Current:</td>
      <td><select name="current">
        <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_team_photo['current'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
        <option value="No" <?php if (!(strcmp("No", htmlentities($row_team_photo['current'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
      </select>
      If no photo will be archived </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update record" /></td>
    </tr>
  </table>
  
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id" value="<?php echo $row_team_photo['id']; ?>" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<div id="gallery_intro"></div>
</body>
</html>
<?php
mysql_free_result($grade);

mysql_free_result($team_photo);

mysql_free_result($Intro);
?>
