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
  $updateSQL = sprintf("UPDATE links SET url=%s, link_title=%s, link_order=%s, visible=%s WHERE link_id=%s",
                       GetSQLValueString($_POST['url'], "text"),
                       GetSQLValueString($_POST['link_title'], "text"),
                       GetSQLValueString($_POST['link_order'], "text"),
                       GetSQLValueString(isset($_POST['visible']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['link_id'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../links_index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_links = "-1";
if (isset($_GET['link_id'])) {
  $colname_links = $_GET['link_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_links = sprintf("SELECT * FROM links WHERE link_id=%s ORDER BY link_order ASC", GetSQLValueString($colname_links, "int"));
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
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>


<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>

<table width="1000" align="center">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" >
  <table align="center">
    <tr>
      <td align="right" nowrap="nowrap" class="red_bold">Edit a Link</td>
      <td align="right"><span class="red_bold">
        <input type="button" value="Return to previous page" onclick="history.go(-1)"/>
      </span></td>
    </tr>
    <tr>
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td nowrap="nowrap" align="right">Link ID:</td>
      <td><?php echo $row_links['link_id']; ?></td>
    </tr>
    <tr>
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right" nowrap="nowrap">Title</td>
      <td><input type="text" name="link_title" value="<?php echo htmlentities($row_links['link_title'], ENT_COMPAT, 'utf-8'); ?>" size="70" /></td>
    </tr>
    <tr>
      <td align="right" nowrap="nowrap">Copy URL from browser</td>
      <td><input type="text" name="url" value="<?php echo htmlentities($row_links['url'], ENT_COMPAT, 'utf-8'); ?>" size="70" /></td>
    </tr>
    <tr>
      <td align="right" nowrap="nowrap">Set the link order of appearance in list</td>
      <td><select name="link_order">
        <option value="11" <?php if (!(strcmp("11", htmlentities($row_links['link_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Not Set</option>
        <option value="01" <?php if (!(strcmp("01", htmlentities($row_links['link_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>01</option>
        <option value="02" <?php if (!(strcmp("02", htmlentities($row_links['link_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>02</option>
        <option value="03" <?php if (!(strcmp("03", htmlentities($row_links['link_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>03</option>
        <option value="04" <?php if (!(strcmp("04", htmlentities($row_links['link_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>04</option>
        <option value="05" <?php if (!(strcmp("05", htmlentities($row_links['link_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>05</option>
        <option value="06" <?php if (!(strcmp("06", htmlentities($row_links['link_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>06</option>
        <option value="07" <?php if (!(strcmp("07", htmlentities($row_links['link_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>07</option>
        <option value="08" <?php if (!(strcmp("08", htmlentities($row_links['link_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>08</option>
        <option value="09" <?php if (!(strcmp("09", htmlentities($row_links['link_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>09</option>
        <option value="10" <?php if (!(strcmp("10", htmlentities($row_links['link_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>10</option>
      </select></td>
    </tr>
    <tr>
      <td align="right" nowrap="nowrap">Checked = Visible on the website:</td>
      <td><input type="checkbox" name="visible" id="visible"  <?php if (!(strcmp(htmlentities($row_links['visible'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr>
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update record" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="link_id" value="<?php echo $row_links['link_id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($links);
?>

