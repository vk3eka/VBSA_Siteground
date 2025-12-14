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

$colname_attach = "-1";
if (isset($_GET['attach_id'])) {
  $colname_attach = $_GET['attach_id'];
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
    <td colspan="2" align="left" class="red_bold"><span class="red_bold">Attachments</span></td>
  </tr>
  </table>
  
<?php include '../admin_xx_includes/forum_nav.php';?>

</div>

  <div id="ContentDB">
  <table width="990" align="center" cellpadding="5">
    
    <tr>
      <td align="center">Topic Title</td>
      <td>Attachment Title</td>
      <td>&nbsp;</td>
      <td>Attachment URL</td>
      <td class="page">&nbsp;</td>
      <td class="page">&nbsp;</td>
      <td class="page">Uploaded On</td>
      </tr>
  <?php do { ?>    <tr>
    <td align="center">
      <?php 
      if($row_attach['post_topic']=='')
		{
		echo "No Attachments ";
		}
	    elseif($row_attach['post_topic']<>'')
		{
		echo $row_attach['post_topic'];
		}
		?>
      </td>
    
    <td><?php echo $row_attach['attach_name']; ?></td>
    <td><a href="forum_attach_edit_details.php?ID=<?php echo $row_attach['ID']; ?>">
      <img src="../Admin_Images/edit_butt.png" width="16" height="16" title="Edit Attachment details" /></a></td>
    <td><span class="page"><?php echo $row_attach['Attachment']; ?></span></td>
    <td><a href="forum_attach_edit.php?ID=<?php echo $row_attach['ID']; ?>" >
      <img src="../Admin_Images/edit_butt.png" width="16" height="16" title="Edit/Replace Attachment" /></a></td>
    <td><a href="<?php echo $row_attach['Attachment']; ?>" target="_blank">
      <img src="../Admin_Images/Open_attach.fw.png" width="26" height="18"  title="Open Attachment" /></a></td>
    <td class="page"><?php echo $row_attach['UpOn']; ?></td>
    </tr>
  <?php } while ($row_attach = mysql_fetch_assoc($attach)); ?>    
  </table>
  
</div>

</body>
</html>
<?php
mysql_free_result($attach);
?>
