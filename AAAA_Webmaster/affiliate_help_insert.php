<?php require_once('../Connections/connvbsa.php'); ?>
<?php require_once('../ScriptLibrary/incPureUpload.php'); ?>
<?php
// Pure PHP Upload 2.1.12
$ppu = new pureFileUpload();
$ppu->path = "Affiliate_help_upload";
$ppu->extensions = "";
$ppu->formName = "form6";
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form6")) {
  $insertSQL = sprintf("INSERT INTO affiliate_extra_help (id, file_type, file_desc, file_name) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['id'], "int"),
                       GetSQLValueString($_POST['file_type'], "text"),
                       GetSQLValueString($_POST['file_desc'], "text"),
                       GetSQLValueString($_POST['file_name'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "affiliate_users.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
<script language='javascript' src='../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>
<script language='javascript' src='../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>
<script language='javascript' src='../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>
</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>


<table align="center" style="min-width:500px">
    <tr>
      <td align="center" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr>
      <td align="center" nowrap="nowrap" class="red_txt">Upload help files to the &quot;Extra&quot; opening page for affiliate users</td>
    </tr>
    <tr>
      <td align="center" nowrap="nowrap">&nbsp;</td>
    </tr>
</table>\
  


  
  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form6" id="form6" onsubmit="checkFileUpload(this,'',true,'','','','','','','');return document.MM_returnValue">
    <table align="center">
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Type of help file: </td>
        <td><select name="file_type">
          <option value="fixture" <?php if (!(strcmp("fixture", ""))) {echo "SELECTED";} ?>>fixture</option>
          <option value="score" <?php if (!(strcmp("score", ""))) {echo "SELECTED";} ?>>score</option>
          <option value="tournament" <?php if (!(strcmp("tournament", ""))) {echo "SELECTED";} ?>>tournament</option>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Description or Title of file: </td>
        <td><input type="text" name="file_desc" value="" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Select the file to upload from your filing system: </td>
        <td><input name="file_name" type="file" id="file_name" onchange="checkOneFileUpload(this,'',true,'','','','','','','')" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" value="Insert record" /></td>
      </tr>
    </table>
    <input type="hidden" name="id" value="" />
    <input type="hidden" name="MM_insert" value="form6" />
  </form>
  <p>&nbsp;</p>
</body>
</html>