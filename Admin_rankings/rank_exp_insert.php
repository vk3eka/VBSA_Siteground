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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form5")) {
  $insertSQL = sprintf("INSERT INTO rank_info (rank_exp_id, rank_exp_title, rank_exp_type, rank_exp_order) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['rank_exp_id'], "int"),
                       GetSQLValueString($_POST['rank_exp_title'], "text"),
                       GetSQLValueString($_POST['rank_exp_type'], "text"),
                       GetSQLValueString($_POST['rank_exp_order'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "rank_exp.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
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

</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form5" id="form5">
  <table align="center" cellpadding="3" cellspacing="3">
        <tr valign="baseline">
          <td colspan="2" align="center" nowrap="nowrap">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td colspan="2" align="center" nowrap="nowrap"><span class="red_bold">Insert an item to the rankings info page</span></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Title:</td>
          <td><input type="text" name="rank_exp_title" value="" size="50" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Select Type:</td>
          <td><select name="rank_exp_type">
            <option value="Billiards" <?php if (!(strcmp("Billiards", ""))) {echo "SELECTED";} ?>>Billiards</option>
            <option value="Snooker" <?php if (!(strcmp("Snooker", ""))) {echo "SELECTED";} ?>>Snooker</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">PDF:</td>
          <td>Edit to upload file</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Set the order you want item to appear in:</td>
          <td><select name="rank_exp_order">
            <option value="01" <?php if (!(strcmp(01, ""))) {echo "SELECTED";} ?>>01</option>
            <option value="02" <?php if (!(strcmp(02, ""))) {echo "SELECTED";} ?>>02</option>
            <option value="03" <?php if (!(strcmp(01, ""))) {echo "SELECTED";} ?>>03</option>
            <option value="04" <?php if (!(strcmp(01, ""))) {echo "SELECTED";} ?>>04</option>
            <option value="05" <?php if (!(strcmp(01, ""))) {echo "SELECTED";} ?>>05</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Current? (appear on the website):</td>
          <td>Auto inserted as yes, edit to alter</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Insert item" /></td>
        </tr>
      </table>
      <input type="hidden" name="rank_exp_id" value="" />
  <input type="hidden" name="MM_insert" value="form5" />
</form>
    <p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($billrank);

mysql_free_result($billrank);
?>
