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
  $updateSQL = sprintf("UPDATE breaks SET member_ID_brks=%s, brk=%s, grade=%s, brk_team_id=%s, finals_brk=%s, brk_type=%s, season=%s WHERE Break_ID=%s",
                       GetSQLValueString($_POST['member_ID_brks'], "int"),
                       GetSQLValueString($_POST['brk'], "int"),
                       GetSQLValueString($_POST['grade'], "text"),
                       GetSQLValueString($_POST['brk_team_id'], "int"),
                       GetSQLValueString($_POST['finals_brk'], "text"),
                       GetSQLValueString($_POST['brk_type'], "text"),
                       GetSQLValueString($_POST['season'], "text"),
                       GetSQLValueString($_POST['Break_ID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../brks_last_50.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE breaks SET member_ID_brks=%s, brk=%s, grade=%s, finals_brk=%s, brk_type=%s, season=%s WHERE Break_ID=%s",
                       GetSQLValueString($_POST['member_ID_brks'], "int"),
                       GetSQLValueString($_POST['brk'], "int"),
                       GetSQLValueString($_POST['grade'], "text"),
                       GetSQLValueString($_POST['finals_brk'], "text"),
                       GetSQLValueString($_POST['brk_type'], "text"),
                       GetSQLValueString($_POST['season'], "text"),
                       GetSQLValueString($_POST['Break_ID'], "int"));

  $updateGoTo = "../brks_last_50.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['brk_id'])) {
  $brk_id = $_GET['brk_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_breaks = "SELECT breaks.Break_ID, breaks.member_ID_brks, breaks.brk, breaks.grade, breaks.finals_brk, breaks.recvd, breaks.brk_type, breaks.season, breaks.brk_team_id FROM breaks WHERE Break_ID = '$brk_id'";
$breaks = mysql_query($query_breaks, $connvbsa) or die(mysql_error());
$row_breaks = mysql_fetch_assoc($breaks);
$totalRows_breaks = mysql_num_rows($breaks);

mysql_select_db($database_connvbsa, $connvbsa);
$query_grades_S1 = "SELECT grade, grade_name FROM Team_grade WHERE `current`='Yes' AND season='S1' ORDER BY season, type, grade";
$grades_S1 = mysql_query($query_grades_S1, $connvbsa) or die(mysql_error());
$row_grades_S1 = mysql_fetch_assoc($grades_S1);
$totalRows_grades_S1 = mysql_num_rows($grades_S1);

mysql_select_db($database_connvbsa, $connvbsa);
$query_grades_S2 = "SELECT grade, grade_name FROM Team_grade WHERE `current`='Yes' AND season='S2' ORDER BY season, type, grade";
$grades_S2 = mysql_query($query_grades_S2, $connvbsa) or die(mysql_error());
$row_grades_S2 = mysql_fetch_assoc($grades_S2);
$totalRows_grades_S2 = mysql_num_rows($grades_S2);

mysql_select_db($database_connvbsa, $connvbsa);
$query_grade_det = "SELECT grade, grade_name, RP, type, season FROM Team_grade WHERE grade='$grade'";
$grade_det = mysql_query($query_grade_det, $connvbsa) or die(mysql_error());
$row_grade_det = mysql_fetch_assoc($grade_det);
$totalRows_grade_det = mysql_num_rows($grade_det);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
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

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" >
  <table align="center">
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap" class="red_bold">Edit a break</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><?php echo $grade; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Break ID:</td>
      <td><?php echo $brk_id; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Member ID:</td>
      <td><input type="text" name="member_ID_brks" value="<?php echo $row_breaks['member_ID_brks']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Break:</td>
      <td><input type="text" name="brk" value="<?php echo $row_breaks['brk']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Grade:</td>
      <td><select name="grade">
        <?php
do {  
?>
        <option value="<?php echo $row_grades_S1['grade']?>"<?php if (!(strcmp($row_grades_S1['grade'], $row_grade_det['grade']))) {echo "selected=\"selected\"";} ?>><?php echo $row_grades_S1['grade']?></option>
        <?php
} while ($row_grades_S1 = mysql_fetch_assoc($grades_S1));
  $rows = mysql_num_rows($grades_S1);
  if($rows > 0) {
      mysql_data_seek($grades_S1, 0);
	  $row_grades_S1 = mysql_fetch_assoc($grades_S1);
  }
?>
      </select>
      
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Team ID:</td>
      <td><input type="text" name="brk_team_id" value="<?php echo $row_breaks['brk_team_id']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Finals break?:</td>
      <td>
      <select name="finals_brk">
        <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_breaks['finals_brk'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
        <option value="No" <?php if (!(strcmp("No", htmlentities($row_breaks['finals_brk'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
      </select>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Recorded on:</td>
      <td><?php echo $row_breaks['recvd']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Break Type:</td>
      <td>
      <select name="brk_type">
        <option value="Snooker" <?php if (!(strcmp("Snooker", htmlentities($row_breaks['brk_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Snooker</option>
        <option value="Billiards" <?php if (!(strcmp("Billiards", htmlentities($row_breaks['brk_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Billiards</option>
      </select>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Season:</td>
      <td><select name="season">
        <option value="S1" <?php if (!(strcmp("S1", htmlentities($row_breaks['season'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>S1</option>
        <option value="S2" <?php if (!(strcmp("S2", htmlentities($row_breaks['season'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>S2</option>
    </select>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update break" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="Break_ID" value="<?php echo $row_breaks['Break_ID']; ?>" />
  <input type="hidden" name="MM_update" value="form1" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php

?>

