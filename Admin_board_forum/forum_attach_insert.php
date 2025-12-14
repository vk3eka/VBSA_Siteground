<?php require_once('../Connections/connvbsa.php'); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO forum_attach (ID, attach_id, attach_name, Attachment) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['ID'], "int"),
                       GetSQLValueString($_POST['attach_id'], "int"),
                       GetSQLValueString($_POST['attach_name'], "text"),
                       GetSQLValueString($_POST['Attachment'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "forum_attach_upload.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_Topic = "-1";
if (isset($_GET['upload'])) {
  $colname_Topic = $_GET['upload'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Topic = sprintf("SELECT post_ID, post_topic FROM forum_posts WHERE post_ID = %s", GetSQLValueString($colname_Topic, "int"));
$Topic = mysql_query($query_Topic, $connvbsa) or die(mysql_error());
$row_Topic = mysql_fetch_assoc($Topic);
$totalRows_Topic = mysql_num_rows($Topic);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Upload = "SELECT ID, attach_id, attach_name, Attachment, upload_on, upload_by FROM forum_attach";
$Upload = mysql_query($query_Upload, $connvbsa) or die(mysql_error());
$row_Upload = mysql_fetch_assoc($Upload);
$totalRows_Upload = mysql_num_rows($Upload);

$colname_attach = "-1";
if (isset($_GET['upload'])) {
  $colname_attach = $_GET['upload'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_attach = sprintf("SELECT date_format(upload_on,'%%b %%e, %%Y, %%r') AS UpOn, forum_attach.ID, forum_attach.attach_id, forum_attach.attach_name, forum_attach.Attachment, forum_attach.upload_on, forum_attach.upload_by, forum_posts.post_ID, forum_posts.post_topic FROM forum_attach, forum_posts WHERE forum_posts.post_ID=forum_attach.attach_id AND attach_id = %s ORDER BY UpOn DESC", GetSQLValueString($colname_attach, "int"));
$attach = mysql_query($query_attach, $connvbsa) or die(mysql_error());
$row_attach = mysql_fetch_assoc($attach);
$totalRows_attach = mysql_num_rows($attach);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Board Members Forum</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>
<script language='javascript' src='../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>
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
    <td colspan="2" align="left" class="red_bold"><span class="red_bld_txt">Upload an attachment to a topic</span></td>
  </tr>
  </table>
  
<?php include '../admin_xx_includes/forum_nav.php';?>

</div>

<div id="ContentDB">
  <table border="0" align="center">
    <tr>
      <td height="70" align="center">
        <input type="button" value="Return to previous page" onclick="history.go(-1)"/>
      <p class="red_bold">Insert new or edit existing attachments to : <?php echo $row_Topic['post_topic']; ?></p></td>
    </tr>
  </table>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
    <table align="center">
      <tr class="red_txt">
        <td colspan="2" align="center" class="red_txt">&nbsp;</td>
      </tr>
      <tr class="red_txt">
        <td colspan="2" align="center" class="red_bold">Upload a new attachment</td>
      </tr>
      <tr class="red_txt">
        <td align="right" class="red_txt">Attachment will be uploaded to this Topic:</td>
        <td class="red_txt"><?php echo $row_Topic['post_topic']; ?></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Create a title for your attachment (Max 25 characters)</td>
        <td><input type="text" name="attach_name" value="" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" value="Create title, go to upload" /></td>
      </tr>
    </table>
    <input type="hidden" name="ID" value="" />
    <input type="hidden" name="attach_id" value="<?php echo $row_Topic['post_ID']; ?>" />
    <input type="hidden" name="Attachment" value="Not Uploaded" />
    <input type="hidden" name="MM_insert" value="form2" />
  </form>
</div>

<div id="ContentDB">
  <table width="990" align="center" cellpadding="5">
    
    <tr>
      <td colspan="8" align="center" class="red_bold">Existing Attachments for : <?php echo $row_Topic['post_topic']; ?></td>
    </tr>
    <?php if(isset($row_attach['post_topic'])) { ?>
    <tr>
      <td align="left">Topic Title</td>
      <td align="left">Attachment Title</td>
      <td align="left">&nbsp;</td>
      <td align="left">Attachment Filename</td>
      <td align="left" class="page">&nbsp;</td>
      <td align="left" class="page">&nbsp;</td>
      <td align="left" class="page">&nbsp;</td>
      <td align="left" class="page">Uploaded On</td>
      </tr>

    
  <?php do { ?>        
  <tr>
    <td align="left"><?php echo $row_attach['post_topic']; ?></td>
    <td align="left"><?php echo $row_attach['attach_name']; ?></td>
    <td align="left"><a href="forum_attach_edit_details.php?ID=<?php echo $row_attach['ID']; ?>">
      <img src="../Admin_Images/edit_butt.png" height="20" title="Edit Attachment details" /></a></td>
    <td align="left"><span class="page"><?php echo $row_attach['Attachment']; ?></span></td>
    <td align="left"><a href="forum_attach_edit.php?ID=<?php echo $row_attach['ID']; ?>" >
      <img src="../Admin_Images/edit_butt.png" height="20" title="Edit/Replace Attachment" /></a></td>
    <td align="left"><a href="<?php echo "/Admin_board_forum/forum_upload_attachments/".$row_attach['Attachment']; ?>" target="_blank"><img src="../Admin_Images/Open_attach.fw.png" width="26" height="18"  title="Open Attachment" /></a></td>
    <td align="left"><a href="forum_attach_delete_confirm.php?ID=<?php echo $row_attach['ID']; ?>"><img src="../Admin_Images/Trash.fw.png" height="20" title="Delete this attachment" /></a></td>
    <td align="left" class="page"><?php echo $row_attach['UpOn']; ?></td>
    </tr>
  <?php } while ($row_attach = mysql_fetch_assoc($attach)); } else { ?>
  
  <tr>
      <td colspan="8" align="center" class="text_italic">No Attachments</td>
  </tr> 
  <?php } ?>   
  </table>
  
</div>

</body>
</html>
<?php
mysql_free_result($Topic);

mysql_free_result($Upload);

mysql_free_result($attach);
?>
