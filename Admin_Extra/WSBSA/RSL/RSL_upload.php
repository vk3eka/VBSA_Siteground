<?php require_once('../../Connections/connvbsa.php'); ?>
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

$MM_restrictGoTo = "../../page_error_extra.php";
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
$ppu->path = "../../RSL/RSL_upload";
$ppu->extensions = "";
$ppu->formName = "form1";
$ppu->storeType = "file";
$ppu->sizeLimit = "500";
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
  $updateSQL = sprintf("UPDATE RSL SET pdf_name=IFNULL(%s,pdf_name) WHERE RSL_id=%s",
                       GetSQLValueString($_POST['fileField'], "text"),
                       GetSQLValueString($_POST['RSL_id'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../RSL/RSL_index_admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$pdfup = "-1";
if (isset($_GET['pdfup'])) {
  $pdfup = $_GET['pdfup'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_pdfup = "SELECT RSL_id, RSL_type, pdf_name, RSL.item_title FROM RSL WHERE RSL_id = '$pdfup'";
$pdfup = mysql_query($query_pdfup, $connvbsa) or die(mysql_error());
$row_pdfup = mysql_fetch_assoc($pdfup);
$totalRows_pdfup = mysql_num_rows($pdfup);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Extra Administation Area</title>
<script></script>
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="1000" align="center">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>
<script language='javascript' src='../../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>

<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="checkFileUpload(this,'',true,500,'','','','','','');return document.MM_returnValue"><table width="940" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan="3" align="left">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="2" align="left"><span class="red_bold">RSL - Upload a .pdf file (Maximum File size 500kb)</span></td>
    <td align="right"><span class="page">
      <input type="button" value="Return to previous page" onclick="history.go(-1)"/>
      </span></td>
    </tr>
  <tr>
    <td width="119" align="right">&nbsp;</td>
    <td width="487" class="red_bold">&nbsp;</td>
    <td width="318" align="right" class="page">&nbsp;</td>
    </tr>
  <tr>
    <td align="right">Type :</td>
    <td colspan="2">
      <?php 
	  if ($row_pdfup['RSL_type'] =="a_info")
	  {echo "Information";}
	  elseif ($row_pdfup['RSL_type'] =="c_zone1") 
      {echo "Zone 1";}
	  elseif ($row_pdfup['RSL_type'] =="d_zone2") 
      {echo "Zone 2";}
	  elseif ($row_pdfup['RSL_type'] =="e_zone3") 
      {echo "Zone 3";}
	  elseif ($row_pdfup['RSL_type'] =="f_zone4") 
      {echo "Zone 4";}
	  elseif ($row_pdfup['RSL_type'] =="g_zone5") 
      {echo "Zone 5";}
	  elseif ($row_pdfup['RSL_type'] =="f_history") 
      {echo "History";}	  
	  ?></td>
    </tr>
  <tr>
    <td align="right">Item Title: </td>
    <td colspan="2"><?php echo $row_pdfup['item_title']; ?></td>
    </tr>
  <tr>
    <td align="right" bgcolor="#CCCCCC">Current .pdf :</td>
    <td colspan="2" bgcolor="#CCCCCC"><?php echo $row_pdfup['pdf_name']; ?></td>
    </tr>
  <tr>
    <td bgcolor="#CCCCCC" class="greenbg">&nbsp;</td>
    <td colspan="2" bgcolor="#CCCCCC"> If blank no pdf file has been uploaded, to change the uploaded file upload a new file</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><input name="fileField" type="file" id="fileField" onchange="checkOneFileUpload(this,'',true,500,'','','','','','')" value="<?php echo $row_pdfup['pdf_name']; ?>" size="80" /></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><input type="submit" name="button" id="button" value="Upload a new file" /></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">When uploading it may take a few seconds to complete, please be patient you will be redirected shortly</td>
    </tr>
  </table>
  <input type="hidden" name="RSL_id" value="<?php echo $row_pdfup['RSL_id']; ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($pdfup);
?>
