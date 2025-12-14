<?php require_once('../Connections/connvbsa.php'); ?>
<?php require_once("../webassist/ckeditor/ckeditor.php"); ?>
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
  $updateSQL = sprintf("UPDATE forum_posts SET post_order=%s, post_category=%s, post_topic=%s, post_content=%s, post_date=%s, post_edit_on=%s, post_edit_by=%s, post_current=%s, last_post=%s, last_reply=%s WHERE post_ID=%s",
                       GetSQLValueString($_POST['post_order'], "int"),
                       GetSQLValueString($_POST['post_category'], "text"),
                       GetSQLValueString($_POST['post_topic'], "text"),
                       GetSQLValueString($_POST['post_content'], "text"),
                       GetSQLValueString($_POST['post_date'], "date"),
                       GetSQLValueString($_POST['post_edit_on'], "date"),
                       GetSQLValueString($_POST['post_edit_by'], "text"),
                       GetSQLValueString($_POST['post_current'], "text"),
                       GetSQLValueString($_POST['last_post'], "date"),
                       GetSQLValueString($_POST['last_reply'], "date"),
                       GetSQLValueString($_POST['post_ID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "forum_index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_post_edit = "-1";
if (isset($_GET['post_edit'])) {
  $colname_post_edit = $_GET['post_edit'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_post_edit = sprintf("SELECT post_ID, post_order, post_category, post_topic, post_content, post_by, post_date, post_edit_on, post_edit_by, post_current, forum_posts.last_post, forum_posts.last_reply FROM forum_posts WHERE post_ID = %s", GetSQLValueString($colname_post_edit, "int"));
$post_edit = mysql_query($query_post_edit, $connvbsa) or die(mysql_error());
$row_post_edit = mysql_fetch_assoc($post_edit);
$totalRows_post_edit = mysql_num_rows($post_edit);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Board Members Forum</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />

<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/forum_db.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/forum_db_links.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="forum_header">
  <div id="logo"><img src="../images/VBSA1.jpg" alt="" width="90" height="87" /></div>

<table width="870" align="right">
  <tr>

    <td class="red_bold">VBSA Administrators Forum</td>
    <td align="right" class="bluebg"><a href="How%20to%20use%20the%20VBSA%20Board%20Forum.pdf" target="_blank">How to use the Forum</a></td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="red_bold"><span class="red_bold">Forum Archives</span></td>
  </tr>
  </table>
  
<?php include '../admin_xx_includes/forum_nav.php';?>

</div>

<div id="ContentDB">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table width="1000" align="center" class="table_text">
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">Topic: </td>
        <td valign="middle"><input name="post_topic" type="text" id="post_topic" value="<?php echo $row_post_edit['post_topic']; ?>" size="80" /></td>
        </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right" valign="middle">General topic or Meeting?</td>
        <td valign="middle"><select name="post_category">
          <option value="General" <?php if (!(strcmp("General", htmlentities($row_post_edit['post_category'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>General</option>
          <option value="Meeting" <?php if (!(strcmp("Meeting", htmlentities($row_post_edit['post_category'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Meeting</option>
          </select></td>
        </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right" valign="top">Content: </td>
        <td><?php
// The initial value to be displayed in the editor.
$CKEditor_initialValue = "".$row_post_edit['post_content']  ."";
$CKEditor = new CKEditor();
$CKEditor->basePath = "../webassist/ckeditor/";
$CKEditor_config = array();
$CKEditor_config["wa_preset_name"] = "forum1";
$CKEditor_config["wa_preset_file"] = "forum1.xml";
$CKEditor_config["width"] = "100%";
$CKEditor_config["height"] = "200px";
$CKEditor_config["skin"] = "v2";
$CKEditor_config["docType"] = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
$CKEditor_config["dialog_startupFocusTab"] = false;
$CKEditor_config["fullPage"] = false;
$CKEditor_config["tabSpaces"] = 4;
$CKEditor_config["toolbar"] = array(
array( 'Bold','Italic','Underline'),
array( 'TextColor'),
array( 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Print','SpellChecker'),
array( 'NumberedList','BulletedList'),
array( 'Link','Unlink'),
array( 'Image','HorizontalRule','Smiley'),
array( 'Undo','Redo','-','Find','Replace'));
$CKEditor_config["contentsLangDirection"] = "ltr";
$CKEditor_config["entities"] = false;
$CKEditor_config["pasteFromWordRemoveFontStyles"] = false;
$CKEditor_config["pasteFromWordRemoveStyles"] = false;
$CKEditor->editor("post_content", $CKEditor_initialValue, $CKEditor_config);
?></td>
        </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Topic by:</td>
        <td><?php echo $row_post_edit['post_by']; ?></td>
        </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Current or Archived:</td>
        <td><select name="post_current">
          <option value="Current" <?php if (!(strcmp("Current", htmlentities($row_post_edit['post_current'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Current</option>
          <option value="Archived" <?php if (!(strcmp("Archived", htmlentities($row_post_edit['post_current'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Archived</option>
          </select> 
          Current will appear on the home page, Archived will appear in archives only      </td>
        </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" value="Update topic" /></td>
        </tr>
      </table>
    <input type="hidden" name="post_ID" value="<?php echo $row_post_edit['post_ID']; ?>" />
    <input type="hidden" name="post_order" value="<?php echo $row_post_edit['post_order']; ?>" />
    <input type="hidden" name="post_order" value="<?php echo htmlentities($row_post_edit['post_order'], ENT_COMPAT, 'utf-8'); ?>" />
    <input type="hidden" name="post_date" value="<?php echo htmlentities($row_post_edit['post_date'], ENT_COMPAT, 'utf-8'); ?>" />
    <input type="hidden" name="post_edit_on" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d H:i:s"); ?> " />
    <input type="hidden" name="last_post" value="<?php echo date("Y-m-d H:i:s")?> " />
    <input type="hidden" name="last_reply" value="<?php echo htmlentities($row_post_edit['last_reply'], ENT_COMPAT, 'utf-8'); ?>" />
    <input type="hidden" name="post_edit by" value="<?php echo $row_post_edit['post_edit_by']; ?>" />
    <input type="hidden" name="MM_update" value="form1" />
    
  </form>
  
</div>
</div>

</body>
</html>
<?php
mysql_free_result($post_edit);
?>
