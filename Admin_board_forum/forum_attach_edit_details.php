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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE forum_attach SET attach_id=%s, attach_name=%s, Attachment=%s, upload_on=%s, upload_by=%s WHERE ID=%s",
                       GetSQLValueString($_POST['attach_id'], "int"),
                       GetSQLValueString($_POST['attach_name'], "text"),
                       GetSQLValueString($_POST['Attachment'], "text"),
                       GetSQLValueString($_POST['upload_on'], "date"),
                       GetSQLValueString($_POST['upload_by'], "text"),
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

$colname_att_edit = "-1";
if (isset($_GET['ID'])) {
  $colname_att_edit = $_GET['ID'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_att_edit = sprintf("SELECT date_format(upload_on,'%%b %%e, %%Y, %%r') AS UpOn, ID, attach_id, attach_name, Attachment, upload_on, upload_by, forum_posts.post_ID, forum_posts.post_topic FROM forum_attach, forum_posts WHERE forum_posts.post_ID=attach_id AND ID = %s", GetSQLValueString($colname_att_edit, "int"));
$att_edit = mysql_query($query_att_edit, $connvbsa) or die(mysql_error());
$row_att_edit = mysql_fetch_assoc($att_edit);
$totalRows_att_edit = mysql_num_rows($att_edit);
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
    <td colspan="2" align="left" class="red_bold"><span class="red_bld_txt">Edit attachment details</span></td>
  </tr>
  </table>
  
<?php include '../admin_xx_includes/forum_nav.php';?>

</div>

<div id="ContentDB">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table align="center">
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Topic ID</td>
        <td><?php echo $row_att_edit['attach_id']; ?></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Topic Title</td>
        <td><?php echo $row_att_edit['post_topic']; ?></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Attachment Title (Max 25 characters)</td>
        <td><input type="text" name="attach_name" value="<?php echo htmlentities($row_att_edit['attach_name'], ENT_COMPAT, 'utf-8'); ?>" size="40" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Attachment</td>
        <td class="page"><?php echo $row_att_edit['Attachment']; ?> <a href="forum_attach_edit.php?ID=<?php echo $row_att_edit['ID']; ?>">Edit Attachment</a></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td class="button">
          <input type="submit" value="Update record" />
        </td>
      </tr>
    </table>
    <input type="hidden" name="ID" value="<?php echo $row_att_edit['ID']; ?>" />
    <input type="hidden" name="attach_id" value="<?php echo htmlentities($row_att_edit['attach_id'], ENT_COMPAT, 'utf-8'); ?>" />
    <input type="hidden" name="Attachment" value="<?php echo htmlentities($row_att_edit['Attachment'], ENT_COMPAT, 'utf-8'); ?>" />
    <input type="hidden" name="upload_on" value="<?php echo htmlentities($row_att_edit['upload_on'], ENT_COMPAT, 'utf-8'); ?>" />
    <input type="hidden" name="MM_update" value="form1" />
    <input type="hidden" name="ID" value="<?php echo $row_att_edit['ID']; ?>" />
  </form>
</div>

</body>
</html>
<?php
mysql_free_result($att_edit);
?>
