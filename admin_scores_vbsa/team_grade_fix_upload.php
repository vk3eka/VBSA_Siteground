<?php require_once('../Connections/connvbsa.php'); ?>
<?php require_once('../ScriptLibrary/incPureUpload.php'); ?>
<?php
// Pure PHP Upload 2.1.12
$ppu = new pureFileUpload();
$ppu->path = "../fix_upload";
$ppu->extensions = "";
$ppu->formName = "form1";
$ppu->storeType = "file";
$ppu->sizeLimit = "";
$ppu->nameConflict = "over";
$ppu->nameToLower = false;
$ppu->requireUpload = true;
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

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
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
  $updateSQL = sprintf("UPDATE Team_grade SET fix_upload=IFNULL(%s,fix_upload), fix_cal_year=%s WHERE grade=%s",
                       GetSQLValueString($_POST['fix_upload'], "text"),
                       GetSQLValueString($_POST['fix_cal_year'], "date"),
                       GetSQLValueString($_POST['grade'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "team_grades.php?season=".$season;
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_teamgrades_fix = "-1";
if (isset($_GET['grade'])) {
  $colname_teamgrades_fix = $_GET['grade'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_teamgrades_fix = sprintf("SELECT grade, fix_upload, grade_name, season, fix_cal_year FROM Team_grade WHERE grade = %s", GetSQLValueString($colname_teamgrades_fix, "text"));
$teamgrades_fix = mysql_query($query_teamgrades_fix, $connvbsa) or die(mysql_error());
$row_teamgrades_fix = mysql_fetch_assoc($teamgrades_fix);
$totalRows_teamgrades_fix = mysql_num_rows($teamgrades_fix);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script language='javascript' src='../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 

</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

<table width="1000" align="center" cellpadding="5" cellspacing="5">
      <tr>
        <td align="center" class="red_bold"> TEAM GRADES Season <?php echo $season ?>  - Upload a fixture (pdf only)</td>
        <td width="262" align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="checkFileUpload(this,'',true,'','','','','','','');return document.MM_returnValue">
  <table align="center">
    <tr valign="baseline">
      <td colspan="2" align="left" nowrap="nowrap">You are about to upload a file for <?php echo $row_teamgrades_fix['grade']; ?> in season <?php echo $season; ?> of <?php echo date('Y') ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Current file:</td>
      <td><?php echo $row_teamgrades_fix['fix_upload']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Select file to upload</td>
      <td><input name="fix_upload" type="file" id="fix_upload" onchange="checkOneFileUpload(this,'',true,'','','','','','','')" value="<?php echo $row_teamgrades_fix['fix_upload']; ?>" />
        Upload again to overwrite or replace</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Upload file" /></td>
    </tr>
  </table>
  <input type="hidden" name="grade" value="<?php echo $row_teamgrades_fix['grade']; ?>" />
  <input type="hidden" name="fix_cal_year" value="<?php echo date('Y') ?>" />
  <input type="hidden" name="MM_update" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php

?>
