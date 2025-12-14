<?php require_once('../../Connections/connvbsa.php'); ?>
<?php require_once("../../webassist/ckeditor/ckeditor.php"); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
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
  $updateSQL = sprintf("UPDATE tourn_archives SET tourn_name=%s, ranked=%s, status=%s, about=%s, footer=%s, arch_order=%s WHERE tournament_ID=%s",
                       GetSQLValueString($_POST['tourn_name'], "text"),
                       GetSQLValueString($_POST['ranked'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['about'], "text"),
                       GetSQLValueString($_POST['footer'], "text"),
                       GetSQLValueString($_POST['arch_order'], "text"),
                       GetSQLValueString($_POST['tournament_ID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['tid'])) {
  $tid = $_GET['tid'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_tournedit = "SELECT * FROM tourn_archives WHERE tournament_ID = '$tid'";
$tournedit = mysql_query($query_tournedit, $connvbsa) or die(mysql_error());
$row_tournedit = mysql_fetch_assoc($tournedit);
$totalRows_tournedit = mysql_num_rows($tournedit);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>

<link href="../../Admin_xx_CSS/Archives.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />

</head>

<body>
<?php include '../../admin_xx_includes/db_nav.php';?>
<?php include '../../admin_xx_includes/db_srch.php';?>
<table width="1000" align="center">
  <tr>
    <td colspan="2" class="red_bold">&nbsp;</td>
  </tr>
  <tr>
    <td class="red_bold">Archives for <?php echo $row_tournedit['tourn_name']; ?></td>
    <td align="right" class="red_bold"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td colspan="2" align="right" class="greenbg">&nbsp;</td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table width="1000" align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Tournament name:</td>
      <td><input type="text" name="tourn_name" value="<?php echo htmlentities($row_tournedit['tourn_name'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Ranked ? </td>
      <td><select name="ranked">
            <option value="Australian ranking tournament" <?php if (!(strcmp('Australian ranking tournament', htmlentities($row_tournedit['ranked'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Australian ranking tournament</option>
            <option value="Victorian ranking tournament" <?php if (!(strcmp('Victorian ranking tournament', htmlentities($row_tournedit['ranked'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Victorian ranking tournament</option>
            <option value="Non ranking tournament" <?php if (!(strcmp('Non ranking tournament', htmlentities($row_tournedit['ranked'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Non ranking tournament</option>
          </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Status:</td>
      <td><select name="status">
        <option value="Current" <?php if (!(strcmp("Current", htmlentities($row_tournedit['status'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Current</option>
        <option value="No longer played" <?php if (!(strcmp("No longer played", htmlentities($row_tournedit['status'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No longer played</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="middle">About:</td>
      <td><?php
// The initial value to be displayed in the editor.
$CKEditor_initialValue = "".$row_tournedit['about']  ."";
$CKEditor = new CKEditor();
$CKEditor->basePath = "../../webassist/ckeditor/";
$CKEditor_config = array();
$CKEditor_config["wa_preset_name"] = "VBSA";
$CKEditor_config["wa_preset_file"] = "VBSA.xml";
$CKEditor_config["width"] = "100%";
$CKEditor_config["height"] = "200px";
$CKEditor_config["docType"] = "<" ."!" ."DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
$CKEditor_config["contentsLanguage"] = "";
$CKEditor_config["dialog_startupFocusTab"] = false;
$CKEditor_config["fullPage"] = false;
$CKEditor_config["tabSpaces"] = 4;
$CKEditor_config["toolbar"] = array(
array( 'FontSize'),
array( 'Bold','Italic'),
array( 'TextColor'),
array( 'JustifyLeft','JustifyCenter','JustifyRight'),
array( 'NumberedList','BulletedList','-'),
array( 'Paste','PasteText','PasteFromWord','-','SpellChecker'),
array( 'Link','Unlink'),
array( 'Undo','Redo','-','Find','Replace'),
array( 'Source','-'));
$CKEditor_config["contentsLangDirection"] = "ltr";
$CKEditor_config["entities"] = false;
$CKEditor_config["forcePasteAsPlainText"] = true;
$CKEditor_config["pasteFromWordRemoveFontStyles"] = false;
$CKEditor_config["pasteFromWordRemoveStyles"] = false;
$CKEditor->editor("about", $CKEditor_initialValue, $CKEditor_config);
?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="middle">Footer:</td>
      <td><?php
// The initial value to be displayed in the editor.
$CKEditor_initialValue = "".$row_tournedit['footer']  ."";
$CKEditor = new CKEditor();
$CKEditor->basePath = "../../webassist/ckeditor/";
$CKEditor_config = array();
$CKEditor_config["wa_preset_name"] = "VBSA";
$CKEditor_config["wa_preset_file"] = "VBSA.xml";
$CKEditor_config["width"] = "100%";
$CKEditor_config["height"] = "120px";
$CKEditor_config["docType"] = "<" ."!" ."DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
$CKEditor_config["contentsLanguage"] = "";
$CKEditor_config["dialog_startupFocusTab"] = false;
$CKEditor_config["fullPage"] = false;
$CKEditor_config["tabSpaces"] = 4;
$CKEditor_config["toolbar"] = array(
array( 'FontSize'),
array( 'Bold','Italic'),
array( 'TextColor'),
array( 'JustifyLeft','JustifyCenter','JustifyRight'),
array( 'NumberedList','BulletedList','-'),
array( 'Paste','PasteText','PasteFromWord','-','SpellChecker'),
array( 'Link','Unlink'),
array( 'Undo','Redo','-','Find','Replace'),
array( 'Source','-'));
$CKEditor_config["contentsLangDirection"] = "ltr";
$CKEditor_config["entities"] = false;
$CKEditor_config["forcePasteAsPlainText"] = true;
$CKEditor_config["pasteFromWordRemoveFontStyles"] = false;
$CKEditor_config["pasteFromWordRemoveStyles"] = false;
$CKEditor->editor("footer", $CKEditor_initialValue, $CKEditor_config);
?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Ordered ? :</td>
      <td><select name="arch_order">
        <option value="not ordered" <?php if (!(strcmp('not ordered', htmlentities($row_tournedit['arch_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>not ordered</option>
        <option value="1" <?php if (!(strcmp(1, htmlentities($row_tournedit['arch_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>1</option>
        <option value="2" <?php if (!(strcmp(2, htmlentities($row_tournedit['arch_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>2</option>
        <option value="3" <?php if (!(strcmp(3, htmlentities($row_tournedit['arch_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>3</option>
        <option value="4" <?php if (!(strcmp(4, htmlentities($row_tournedit['arch_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>4</option>
        <option value="5" <?php if (!(strcmp(5, htmlentities($row_tournedit['arch_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>5</option>
        <option value="6" <?php if (!(strcmp(6, htmlentities($row_tournedit['arch_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>6</option>
        <option value="7" <?php if (!(strcmp(7, htmlentities($row_tournedit['arch_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>7</option>
        <option value="8" <?php if (!(strcmp(8, htmlentities($row_tournedit['arch_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>8</option>
        <option value="9" <?php if (!(strcmp(9, htmlentities($row_tournedit['arch_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>9</option>
        <option value="10" <?php if (!(strcmp(10, htmlentities($row_tournedit['arch_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>10</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update Tournament" /></td>
    </tr>
  </table>
  <input type="hidden" name="tournament_ID" value="<?php echo $row_tournedit['tournament_ID']; ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="tournament_ID" value="<?php echo $row_tournedit['tournament_ID']; ?>" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
	<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($tournedit);
?>
