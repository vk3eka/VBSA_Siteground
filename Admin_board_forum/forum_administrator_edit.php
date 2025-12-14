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
  $updateSQL = sprintf("UPDATE forum_posts SET post_order=%s, post_category=%s, post_topic=%s, post_content=%s, post_date=%s, post_edit_on=%s, post_edit_by=%s, post_current=%s, Blocked=%s, admin_comment=%s WHERE post_ID=%s",
                       GetSQLValueString($_POST['post_order'], "int"),
                       GetSQLValueString($_POST['post_category'], "text"),
                       GetSQLValueString($_POST['post_topic'], "text"),
                       GetSQLValueString($_POST['post_content'], "text"),
                       GetSQLValueString($_POST['post_date'], "date"),
                       GetSQLValueString($_POST['post_edit_on'], "date"),
                       GetSQLValueString($_POST['post_edit_by'], "text"),
                       GetSQLValueString($_POST['post_current'], "text"),
                       GetSQLValueString($_POST['Blocked'], "text"),
                       GetSQLValueString($_POST['admin_comment'], "text"),
                       GetSQLValueString($_POST['post_ID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "forum_administrator.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_top_ed = "-1";
if (isset($_GET['postID'])) {
  $colname_top_ed = $_GET['postID'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_top_ed = sprintf("SELECT * FROM forum_posts WHERE post_ID = %s", GetSQLValueString($colname_top_ed, "int"));
$top_ed = mysql_query($query_top_ed, $connvbsa) or die(mysql_error());
$row_top_ed = mysql_fetch_assoc($top_ed);
$totalRows_top_ed = mysql_num_rows($top_ed);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Board Members Forum</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />

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
    <td colspan="2" align="left" class="red_bold">Administrator - Edit a topic</td>
  </tr>
  </table>
  
<?php include '../admin_xx_includes/forum_nav.php';?>
</div>

  


<div id="ContentDB">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table width="1000" align="center">
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">ID:</td>
        <td><?php echo $row_top_ed['post_ID']; ?></td>
        </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Topic: </td>
        <td><input type="text" name="post_topic" value="<?php echo htmlentities($row_top_ed['post_topic'], ENT_COMPAT, 'utf-8'); ?>" size="60" /></td>
        </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Topic Type:</td>
        <td><select name="post_category">
          <option value="General" <?php if (!(strcmp("General", htmlentities($row_top_ed['post_category'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>General</option>
          <option value="Meeting" <?php if (!(strcmp("Meeting", htmlentities($row_top_ed['post_category'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Meeting</option>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap="nowrap"><p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>Content:</p></td>
        <td><?php
// The initial value to be displayed in the editor.
$CKEditor_initialValue = "".$row_top_ed['post_content']  ."";
$CKEditor = new CKEditor();
$CKEditor->basePath = "../webassist/ckeditor/";
$CKEditor_config = array();
$CKEditor_config["wa_preset_name"] = "forum1";
$CKEditor_config["wa_preset_file"] = "forum1.xml";
$CKEditor_config["width"] = "100%";
$CKEditor_config["height"] = "200px";
$CKEditor_config["skin"] = "kama";
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
        <td nowrap="nowrap" align="right">Post by:</td>
        <td><?php echo $row_top_ed['post_by']; ?></td>
        </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Current? : </td>
        <td><select name="post_current">
          <option value="Current" <?php if (!(strcmp("Current", htmlentities($row_top_ed['post_current'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Current</option>
          <option value="Archived" <?php if (!(strcmp("Archived", htmlentities($row_top_ed['post_current'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Archived</option>
          </select></td>
        </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap="nowrap">Administrator comment: </td>
        <td><textarea name="admin_comment" cols="100" rows="5" class="text"><?php echo htmlentities($row_top_ed['admin_comment'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
        </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap="nowrap">Blocked:</td>
        <td><select name="Blocked">
          <option value="No" <?php if (!(strcmp("No", htmlentities($row_top_ed['Blocked'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
          <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_top_ed['Blocked'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
          </select></td>
        </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" value="Update record" /></td>
        </tr>
      </table>
    <input type="hidden" name="post_order" value="<?php echo htmlentities($row_top_ed['post_order'], ENT_COMPAT, 'utf-8'); ?>" />
    <input type="hidden" name="post_category" value="<?php echo htmlentities($row_top_ed['post_category'], ENT_COMPAT, 'utf-8'); ?>" />
    <input type="hidden" name="post_date" value="<?php echo htmlentities($row_top_ed['post_date'], ENT_COMPAT, 'utf-8'); ?>" />
    <input type="hidden" name="post_edit_on" value="<?php echo htmlentities($row_top_ed['post_edit_on'], ENT_COMPAT, 'utf-8'); ?>" />
    <input type="hidden" name="post_edit_by" value="<?php echo htmlentities($row_top_ed['post_edit_by'], ENT_COMPAT, 'utf-8'); ?>" />
    <input type="hidden" name="MM_update" value="form1" />
    <input type="hidden" name="post_ID" value="<?php echo $row_top_ed['post_ID']; ?>" />
  </form>
  <p>&nbsp;</p>
</div>
</div>

</body>
</html>
<?php
mysql_free_result($top_ed);
?>
