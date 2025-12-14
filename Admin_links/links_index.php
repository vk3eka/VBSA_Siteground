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

mysql_select_db($database_connvbsa, $connvbsa);
$query_links = "SELECT * FROM links ORDER BY link_order, link_title ASC";
$links = mysql_query($query_links, $connvbsa) or die(mysql_error());
$row_links = mysql_fetch_assoc($links);
$totalRows_links = mysql_num_rows($links);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

<table align="center" cellpadding="5" cellspacing="5">
  <tr>
          <td colspan="8" align="center" nowrap="nowrap" class="red_bold">VBSA Links</td>
  </tr>
  <tr>
    <td colspan="8" align="center" nowrap="nowrap"><p>Insert a new link</p>
      <p>If you set visble to &quot;No&quot; link will not appear on the site</p>
      <p>By deleting a link you remove it completely - it cannot be restored</p>
      <p>The first 10 links can be ordered.</p>
    <p>Links will be sorted by order then by title alphabetically</p></td>
  </tr>
  <tr>
    <td colspan="8" align="center" nowrap="nowrap" class="greenbg"><a href="user_files/link_insert.php" >Insert a new link</a></td>
  </tr>
</table>

<table border="1" align="center" cellpadding="5" cellspacing="5">
    <tr>
      	<th align="center" nowrap="nowrap">ID</th>

      	<th nowrap="nowrap">Link Title</th>
      	<th nowrap="nowrap">URL</th>
	  	<th align="center" nowrap="nowrap">Order</th>
	  	<th align="center" nowrap="nowrap">&nbsp;</th>
	  	<th align="center" nowrap="nowrap">Visible</th>
      <th nowrap="nowrap">&nbsp;</th>
        <th nowrap="nowrap">&nbsp;</th>
      </tr>
	  <?php do { ?>
      
      <tr>
      	<td align="center" nowrap="nowrap"><?php echo $row_links['link_id']; ?></td>
      	<td nowrap="nowrap"><?php echo $row_links['link_title']; ?></td>
      	<td nowrap="nowrap"><?php echo $row_links['url']; ?></td>
	  	<td align="center" nowrap="nowrap"><?php if($row_links['link_order']>10) echo "Not Set"; else echo $row_links['link_order']; ?></td>
	  	<td align="center" nowrap="nowrap" class="page"><a href="<?php echo $row_links['url']; ?>" target="_blank">Visit this site</a></td>
	  	<td align="center"><?php if($row_links['visible']==1) echo "Yes"; else echo "No";?></td>
        <td nowrap="nowrap" class="page"><a href="user_files/link_edit.php?link_id=<?php echo $row_links['link_id']; ?>" ><img src="../Admin_Images/edit_butt.fw.png" width="20"  /></a></td>
        <td nowrap="nowrap" class="page"><a href="user_files/link_delete_confirm.php?link_id=<?php echo $row_links['link_id']; ?>" ><img src="../Admin_Images/Trash.fw.png" width="20" /></a></td>
      </tr>
	  <?php } while ($row_links = mysql_fetch_assoc($links)); ?>
</table>
	<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($links);
?>
