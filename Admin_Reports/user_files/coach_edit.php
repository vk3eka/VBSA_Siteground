<?php require_once('../../Connections/connvbsa.php'); ?>
<?php include('../../security_header.php'); ?>
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
  $updateSQL = sprintf("UPDATE coaches_vbsa SET memb_id=%s, `class`=%s, `comment`=%s, URL=%s, coach_order=%s, active_coach=%s WHERE coach_id=%s",
                       GetSQLValueString($_POST['memb_id'], "int"),
                       GetSQLValueString($_POST['class'], "text"),
                       GetSQLValueString($_POST['comment'], "text"),
                       GetSQLValueString($_POST['URL'], "text"),
                       GetSQLValueString($_POST['coach_order'], "text"),
                       GetSQLValueString(isset($_POST['active_coach']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['coach_id'], "int"));
  //echo(GetSQLValueString(isset($_POST['active_coach']) ? "true" : "", "defined","1","0") . "<br>");
  mysql_select_db($database_connvbsa, $connvbsa);
  //echo("Select " . $updateSQL . "<br>");
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../coaches.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  //header(sprintf("Location: %s", $updateGoTo));
}

$coach_edit = "-1";
if (isset($_GET['coach_edit'])) {
  $coach_edit = $_GET['coach_edit'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_coach_edit = "SELECT * FROM coaches_vbsa WHERE coach_id = '$coach_edit'";
//echo("Select " . $query_coach_edit . "<br>");
$coach_edit = mysql_query($query_coach_edit, $connvbsa) or die(mysql_error());
$row_coach_edit = mysql_fetch_assoc($coach_edit);
$totalRows_coach_edit = mysql_num_rows($coach_edit);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 


</head>

<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>

<table width="1000" align="center" cellpadding="2">
  <tr>
    <td class="red_bold">EDIT COACH DETAILS</td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>

  <div id="DBcontent">
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" >
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Member ID:</td>
          <td><input type="text" name="memb_id" value="<?php echo htmlentities($row_coach_edit['memb_id'], ENT_COMPAT, 'utf-8'); ?>" size="10" /> 
          Do not edit unless you have inserted using the wrong ID number</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Class:</td>
          <td><input type="text" name="class" value="<?php echo htmlentities($row_coach_edit['class'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Comment:</td>
          <td><textarea name="comment" cols="120" rows="5"><?php echo htmlentities($row_coach_edit['comment'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">URL:</td>
          <td><input type="text" name="URL" value="<?php echo htmlentities($row_coach_edit['URL'], ENT_COMPAT, 'utf-8'); ?>" size="60" /> 
          Optional field, copy URL from your browser and paste if required</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Order:</td>
          <td>
          <select name="coach_order">
            <option value="NA" >No Entry</option>

            <option value="01" <?php if ($row_coach_edit['coach_order'] == '01') {echo "SELECTED";} ?>>01</option>
            <option value="02" <?php if ($row_coach_edit['coach_order'] == '02') {echo "SELECTED";} ?>>02</option>
            <option value="03" <?php if ($row_coach_edit['coach_order'] == '03') {echo "SELECTED";} ?>>03</option>
            <option value="04" <?php if ($row_coach_edit['coach_order'] == '04') {echo "SELECTED";} ?>>04</option>
            <option value="05" <?php if ($row_coach_edit['coach_order'] == '05') {echo "SELECTED";} ?>>05</option>
            <option value="06" <?php if ($row_coach_edit['coach_order'] == '06') {echo "SELECTED";} ?>>06</option>
            <option value="07" <?php if ($row_coach_edit['coach_order'] == '07') {echo "SELECTED";} ?>>07</option>
            <option value="08" <?php if ($row_coach_edit['coach_order'] == '08') {echo "SELECTED";} ?>>08</option>
            <option value="09" <?php if ($row_coach_edit['coach_order'] == '09') {echo "SELECTED";} ?>>09</option>
            <option value="10" <?php if ($row_coach_edit['coach_order'] == '10') {echo "SELECTED";} ?>>10</option>
            <option value="11" <?php if ($row_coach_edit['coach_order'] == '11') {echo "SELECTED";} ?>>11</option>
            <option value="12" <?php if ($row_coach_edit['coach_order'] == '12') {echo "SELECTED";} ?>>12</option>


            <!--<option value="01" <?php //if (!(strcmp(01, htmlentities($row_coach_edit['coach_order'], ENT_COMPAT, 'utf-8'))) ) {echo "SELECTED";} ?>>01</option>
            <option value="02" <?php //if (!(strcmp(02, htmlentities($row_coach_edit['coach_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>02</option>
            <option value="03" <?php //if (!(strcmp(03, htmlentities($row_coach_edit['coach_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>03</option>
            <option value="04" <?php //if (!(strcmp(04, htmlentities($row_coach_edit['coach_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>04</option>
            <option value="05" <?php //if (!(strcmp(05, htmlentities($row_coach_edit['coach_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>05</option>
            <option value="06" <?php //if (!(strcmp(06, htmlentities($row_coach_edit['coach_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>06</option>
            <option value="07" <?php //if (!(strcmp(07, htmlentities($row_coach_edit['coach_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>07</option>
            <option value="08" <?php //if (!(strcmp(08, htmlentities($row_coach_edit['coach_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>08</option>
            <option value="09" <?php //if (!(strcmp(09, htmlentities($row_coach_edit['coach_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>09</option>
            <option value="10" <?php //if (!(strcmp(10, htmlentities($row_coach_edit['coach_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>10</option>
            <option value="11" <?php //if (!(strcmp(11, htmlentities($row_coach_edit['coach_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>11</option>
            <option value="12" <?php //if (!(strcmp(12, htmlentities($row_coach_edit['coach_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>12</option>-->

          </select> 

          Names will appear in the set order</td>
        </tr>
        <tr>
        <td align="right">Active Coach</td>

        <td><input type="checkbox" name="active_coach" id="active_coach" <?php if($row_coach_edit['active_coach'] == 1) {echo "checked=\"checked\"";} ?> /></td>
      </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Update Coach" /></td>
        </tr>
      </table>
      <input type="hidden" name="coach_id" value="<?php echo $row_coach_edit['coach_id']; ?>" />
      <input type="hidden" name="MM_update" value="form2" />
      <input type="hidden" name="coach_id" value="<?php echo $row_coach_edit['coach_id']; ?>" />
      <input type="hidden" name="MM_update" value="form1" />
    </form>
    <p>&nbsp;</p>
  </div>
</body>
</html>
<?php
mysql_free_result($coach_edit);
?>

