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
  $updateSQL = sprintf("UPDATE forum_reply SET reply_id=%s, reply_content=%s, reply_date=%s, reply_edit=%s WHERE ID=%s",
                       GetSQLValueString($_POST['reply_id'], "int"),
                       GetSQLValueString($_POST['reply_content'], "text"),
                       GetSQLValueString($_POST['reply_date'], "date"),
                       GetSQLValueString($_POST['reply_edit'], "date"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "forum_index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_reply_edit = "-1";
if (isset($_GET['reply_edit'])) {
  $colname_reply_edit = $_GET['reply_edit'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_reply_edit = sprintf("SELECT * FROM forum_reply WHERE ID = %s", GetSQLValueString($colname_reply_edit, "int"));
$reply_edit = mysql_query($query_reply_edit, $connvbsa) or die(mysql_error());
$row_reply_edit = mysql_fetch_assoc($reply_edit);
$totalRows_reply_edit = mysql_num_rows($reply_edit);

mysql_select_db($database_connvbsa, $connvbsa);
$query_forum_users = "SELECT * FROM vbsaorga_users WHERE email is not null ORDER BY vbsaorga_users.name";
$forum_users = mysql_query($query_forum_users, $connvbsa) or die(mysql_error());
$row_forum_users = mysql_fetch_assoc($forum_users);
$totalRows_forum_users = mysql_num_rows($forum_users);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Board Members Forum</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />


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
    <td colspan="2" align="left" class="red_bold"><span class="red_bold">Edit a reply - if a reply is edited it will then appear at the top.</span></td>
  </tr>
  </table>
  
<?php include '../admin_xx_includes/forum_nav.php';?>

</div>

  <div id="ContentDB">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table width="1000" align="center" class="table_text">
      <tr valign="baseline">
        <td colspan="2" align="center">
          You may only edit the content of a reply. When a reply is edited, the date and time will show as &quot;Updated on&quot; in the reply header.</td>
        </tr>
      <tr valign="baseline">
        <td width="95" height="26" align="right" valign="top" nowrap="nowrap">:</td>
        <td rowspan="2"><?php
// The initial value to be displayed in the editor.
$CKEditor_initialValue = "".$row_reply_edit['reply_content']  ."";
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
$CKEditor->editor("reply_content", $CKEditor_initialValue, $CKEditor_config);
?> 
          
          </td>
        </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap="nowrap">Reply content</td>
        </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Reply By: </td>
        <td><?php echo $row_reply_edit['reply_by']; ?></td>
        </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" value="Update reply" /></td>
        </tr>
      </table>
    <input type="hidden" name="ID" value="<?php echo $row_reply_edit['ID']; ?>" />
    <input type="hidden" name="reply_id" value="<?php echo htmlentities($row_reply_edit['reply_id'], ENT_COMPAT, ''); ?>" />
    <input type="hidden" name="reply_date" value="<?php echo htmlentities($row_reply_edit['reply_date'], ENT_COMPAT, ''); ?>" />
    <input type="hidden" name="reply_edit" value="<?php echo date("Y-m-d H:i:s")?> " />
    <input type="hidden" name="MM_update" value="form1" />
    <input type="hidden" name="ID" value="<?php echo $row_reply_edit['ID']; ?>" />
    </form>
  
</div>


</body>
</html>
<?php
mysql_free_result($reply_edit);

mysql_free_result($forum_users);
?>
