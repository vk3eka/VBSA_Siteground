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
  $updateSQL = sprintf("UPDATE scrs SET MemberID=%s, team_grade=%s, team_id=%s, captain_scrs=%s WHERE scrsID=%s",
                       GetSQLValueString($_POST['MemberID'], "int"),
                       GetSQLValueString($_POST['team_grade'], "text"),
                       GetSQLValueString($_POST['team_id'], "int"),
                       GetSQLValueString($_POST['captain_scrs'], "text"),
                       GetSQLValueString($_POST['scrsID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../Player_multiple_insert_S2.php?&team_club=".$colname_club;
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_club = "-1";
if (isset($_GET['team_club'])) {
  $colname_club = $_GET['team_club'];
}

$colname_capt = "-1";
if (isset($_GET['capt'])) {
  $colname_capt = $_GET['capt'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_capt = sprintf("SELECT scrsID, scrs.MemberID, team_id, captain_scrs, FirstName, LastName FROM scrs, members WHERE scrs.MemberID=members.MemberID AND scrsID = %s", GetSQLValueString($colname_capt, "int"));
$capt = mysql_query($query_capt, $connvbsa) or die(mysql_error());
$row_capt = mysql_fetch_assoc($capt);
$totalRows_capt = mysql_num_rows($capt);

$colname_cap_edit = "-1";
if (isset($_GET['capt'])) {
  $colname_cap_edit = $_GET['capt'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_cap_edit = sprintf("SELECT MemberID, scrsID, team_grade, team_id, captain_scrs FROM scrs WHERE scrsID = %s", GetSQLValueString($colname_cap_edit, "int"));
$cap_edit = mysql_query($query_cap_edit, $connvbsa) or die(mysql_error());
$row_cap_edit = mysql_fetch_assoc($cap_edit);
$totalRows_cap_edit = mysql_num_rows($cap_edit);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<p>&nbsp;</p>
<table border="1" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td colspan="3" align="center" class="red_bold" nowrap="nowrap">Set this player as captain / change team id or grade</td>
  </tr>
  <tr>
    <td>MemberID</td>
    <td>FirstName</td>
    <td>LastName</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_capt['MemberID']; ?></td>
      <td><?php echo $row_capt['FirstName']; ?></td>
      <td><?php echo $row_capt['LastName']; ?></td>
    </tr>
    <?php } while ($row_capt = mysql_fetch_assoc($capt)); ?>
</table>
<p>&nbsp;</p>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="return doit()">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Member ID:</td>
      <td><input type="text" name="MemberID" value="<?php echo htmlentities($row_cap_edit['MemberID'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Select as Captain:</td>
      <td><select name="captain_scrs">
      <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_cap_edit['captain_scrs'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
      <option value="No" <?php if (!(strcmp("No", htmlentities($row_cap_edit['captain_scrs'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
      </select>
      
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Team ID:</td>
      <td><input type="text" name="team_id" value="<?php echo htmlentities($row_cap_edit['team_id'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Grade:</td>
      <td><select name="team_grade">
        <option value="APB" <?php if (!(strcmp("APB", htmlentities($row_cap_edit['team_grade'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>APB</option>
        <option value="BPB" <?php if (!(strcmp("BPB", htmlentities($row_cap_edit['team_grade'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>BPB</option>
        <option value="AWS" <?php if (!(strcmp("AWS", htmlentities($row_cap_edit['team_grade'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>AWS</option>
        <option value="BWS" <?php if (!(strcmp("BWS", htmlentities($row_cap_edit['team_grade'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>BWS</option>
        <option value="CWS" <?php if (!(strcmp("CWS", htmlentities($row_cap_edit['team_grade'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>CWS</option>
        <option value="CWSeast" <?php if (!(strcmp("CWSeast", htmlentities($row_cap_edit['team_grade'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>CWS East</option>
        <option value="CWSwest" <?php if (!(strcmp("CWSwest", htmlentities($row_cap_edit['team_grade'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>CWS West</option>
        <option value="DWS" <?php if (!(strcmp("DWS", htmlentities($row_cap_edit['team_grade'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>DWS</option>
        <option value="ARS2" <?php if (!(strcmp("ARS2", htmlentities($row_cap_edit['team_grade'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>ARS2</option>
        <option value="BRS2" <?php if (!(strcmp("BRS2", htmlentities($row_cap_edit['team_grade'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>BRS2</option>
        <option value="CRS2" <?php if (!(strcmp("CRS2", htmlentities($row_cap_edit['team_grade'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>CRS2</option>
        <option value="DRS2" <?php if (!(strcmp("DRS2", htmlentities($row_cap_edit['team_grade'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>DRS2</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update Player" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="scrsID" value="<?php echo $row_cap_edit['scrsID']; ?>" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($capt);

mysql_free_result($cap_edit);
?>

