<?php require_once('../../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../../page_error_extra.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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
  $updateSQL = sprintf("UPDATE DVSA SET DVSA_type=%s, item_title=%s, current=%s, list_order=%s, edited_on=%s WHERE DVSA_id=%s",
                       GetSQLValueString($_POST['DVSA_type'], "text"),
                       GetSQLValueString($_POST['item_title'], "text"),
					             GetSQLValueString(isset($_POST['current']) ? "true" : "", "defined","'1'","'0'"),
                       GetSQLValueString($_POST['list_order'], "text"),
                       GetSQLValueString($_POST['edited_on'], "date"),
                       GetSQLValueString($_POST['DVSA_id'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../DVSA/DVSA_index_admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$zoned = "-1";
if (isset($_GET['zoned'])) {
  $zoned = $_GET['zoned'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_DVSAedit = "SELECT date_format(uploaded_on,'%D %M %Y %H:%i') AS uploaded_on_date, date_format(edited_on,'%D %M %Y %H:%i') AS edited_on_date, DVSA_id, DVSA_type, item_title, list_order, news_content, pdf_name, edited_on, current FROM DVSA WHERE DVSA_id = '$zoned'";
$DVSAedit = mysql_query($query_DVSAedit, $connvbsa) or die(mysql_error());
$row_DVSAedit = mysql_fetch_assoc($DVSAedit);
$totalRows_DVSAedit = mysql_num_rows($DVSAedit);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Extra Administation Area</title>
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="1000" align="center">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table width="909" align="center" cellpadding="5" cellspacing="5">
    <tr valign="baseline">
      <td align="left" nowrap="nowrap" class="red_bold">Edit an item</td>
      <td width="229" align="left" nowrap="nowrap" class="red_bold">&nbsp;</td>
      <td width="555" align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td width="109" align="right" nowrap="nowrap">Type:</td>
      <td colspan="2"><select name="DVSA_type">
        <option value="a_info" <?php if (!(strcmp("a_info", htmlentities($row_DVSAedit['DVSA_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Information</option>
        <option value="b_news" <?php if (!(strcmp("b_news", htmlentities($row_DVSAedit['DVSA_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>News Item</option>
        <option value="c_zone1" <?php if (!(strcmp("c_zone1", htmlentities($row_DVSAedit['DVSA_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Zone 1</option>
        <option value="d_zone2" <?php if (!(strcmp("d_zone2", htmlentities($row_DVSAedit['DVSA_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Zone 2</option>
        <option value="e_zone3" <?php if (!(strcmp("e_zone3", htmlentities($row_DVSAedit['DVSA_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Zone 3</option>
        <option value="f_zone4" <?php if (!(strcmp("f_zone4", htmlentities($row_DVSAedit['DVSA_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Zone 4</option>
        <option value="g_zone5" <?php if (!(strcmp("g_zone5", htmlentities($row_DVSAedit['DVSA_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Zone 5</option>
        <option value="f_history" <?php if (!(strcmp("f_history", htmlentities($row_DVSAedit['DVSA_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>History</option>
      </select>        
        This defines where  the item will appear .</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Item Title:</td>
      <td><input type="text" name="item_title" value="<?php echo htmlentities($row_DVSAedit['item_title'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Visible: </td>
      <td colspan="2"><input type="checkbox" name="current" id="current"  <?php if (!(strcmp(htmlentities($row_DVSAedit['current'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> />
      (if unchecked item will not appear on the site and will be &quot;Archived&quot;)</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Uploaded on :</td>
      <td colspan="2"> <?php echo $row_DVSAedit['uploaded_on_date']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Edited on</td>
      <td colspan="2"><?php echo $row_DVSAedit['edited_on_date']; ?> (Will only show if an item has been edited)</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Order :</td>
      <td colspan="2">        <select name="list_order">
        <option value="no" <?php if (!(strcmp("no", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Not Ordered</option>
        <option value="01" <?php if (!(strcmp("01", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>01</option>
        <option value="02" <?php if (!(strcmp("02", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>02</option>
        <option value="03" <?php if (!(strcmp("03", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>03</option>
        <option value="04" <?php if (!(strcmp("04", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>04</option>
        <option value="05" <?php if (!(strcmp("05", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>05</option>
        <option value="06" <?php if (!(strcmp("06", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>06</option>
        <option value="07" <?php if (!(strcmp("07", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>07</option>
        <option value="08" <?php if (!(strcmp("08", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>08</option>
        <option value="09" <?php if (!(strcmp("09", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>09</option>
        <option value="10" <?php if (!(strcmp("10", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>10</option>
        <option value="11" <?php if (!(strcmp("11", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>11</option>
        <option value="12" <?php if (!(strcmp("12", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>12</option>
        <option value="13" <?php if (!(strcmp("13", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>13</option>
        <option value="14" <?php if (!(strcmp("14", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>14</option>
        <option value="15" <?php if (!(strcmp("15", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>15</option>
        <option value="16" <?php if (!(strcmp("16", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>16</option>
        <option value="17" <?php if (!(strcmp("17", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>17</option>
        <option value="18" <?php if (!(strcmp("18", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>18</option>
        <option value="19" <?php if (!(strcmp("19", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>19</option>
        <option value="20" <?php if (!(strcmp("20", htmlentities($row_DVSAedit['list_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>20</option>
      </select>
      Items given an &quot;Order&quot; will appear at the top of their area as per the number</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update item" /></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="DVSA_id" value="<?php echo $row_DVSAedit['DVSA_id']; ?>" />
  <input type="hidden" name="edited_on" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d H:i:s"); ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($DVSAedit);
?>
