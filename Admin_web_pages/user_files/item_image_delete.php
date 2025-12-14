<?php require_once('../../Connections/connvbsa.php'); ?>
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
  $updateSQL = sprintf("UPDATE webpage_items SET img_orientation=%s, item_image=%s WHERE ID=%s",
                       GetSQLValueString($_POST['img_orientation'], "text"),
                       GetSQLValueString($_POST['item_image'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../item_detail.php?item_id=" . $_REQUEST['item_id'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['item_id'])) {
  $item_id = $_GET['item_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_image_del = "SELECT Header, ID, img_orientation, item_image FROM webpage_items WHERE ID = '$item_id'";
$image_del = mysql_query($query_image_del, $connvbsa) or die(mysql_error());
$row_image_del = mysql_fetch_assoc($image_del);
$totalRows_image_del = mysql_num_rows($image_del);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>

<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA database</title>
</head>

<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>
<table align="center">
  <tr>
    <td colspan="2" align="right" nowrap="nowrap" class="red_bold"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td colspan="2" class="red_bold" nowrap="nowrap">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" nowrap="nowrap" class="red_bold">Remove the image from: <?php echo $row_image_del['Header']; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right">Image to delete:</td>
    <td align="left"><?php echo $row_image_del['item_image']; ?></td>
  </tr>
  <tr>
    <td align="right">Set Orientation to:</td>
    <td align="left"><?php echo $row_image_del['img_orientation']; ?></td>
  </tr>

    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>

</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td><input type="submit" value="Delete Image" /></td>
    </tr>
  </table>
  <input type="hidden" name="ID" value="<?php echo $row_image_del['ID']; ?>" />
  <input type="hidden" name="img_orientation" value="No Image" />
  <input type="hidden" name="item_image" value="" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="ID" value="<?php echo $row_image_del['ID']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($image_del);
?>
