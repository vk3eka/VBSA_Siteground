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
  $updateSQL = sprintf("UPDATE Team_grade SET grade_name=%s, season=%s, type=%s, RP=%s, fix_upload=%s, fix_cal_year=%s, `current`=%s, finals_teams=%s, dayplayed=%s WHERE grade=%s",
                       GetSQLValueString($_POST['grade_name'], "text"),
                       GetSQLValueString($_POST['season'], "text"),
                       GetSQLValueString($_POST['type'], "text"),
					   GetSQLValueString($_POST['RP'], "int"),
                       GetSQLValueString($_POST['fix_upload'], "text"),
                       GetSQLValueString($_POST['fix_cal_year'], "date"),
                       GetSQLValueString($_POST['current'], "text"),
                       GetSQLValueString($_POST['finals_teams'], "int"),
                       GetSQLValueString($_POST['dayplayed'], "text"),
					   GetSQLValueString($_POST['grade'], "text"));


  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../team_grades.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$grade = "-1";
if (isset($_GET['grade'])) {
  $grade = $_GET['grade'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_teamgrades_fix = "SELECT * FROM Team_grade WHERE grade = '$grade'";
$teamgrades_fix = mysql_query($query_teamgrades_fix, $connvbsa) or die(mysql_error());
$row_teamgrades_fix = mysql_fetch_assoc($teamgrades_fix);
$totalRows_teamgrades_fix = mysql_num_rows($teamgrades_fix);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
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
  <tr>
    <td>&nbsp;</td>
  </tr>
  </table>

<table align="center" cellpadding="5" cellspacing="5">
      <tr>
        <td align="center" class="red_bold" nowrap="nowrap"> TEAM GRADES - Edit. If a grade is not playing please select &quot;Current&quot; as &quot;No&quot;</td>
        <td align="center" class="red_bold" nowrap="nowrap">&nbsp;</td>
        <td align="center" class="red_bold" nowrap="nowrap"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1"  >
  <table align="center" cellpadding="5" cellspacing="5">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Grade:</td>
      <td><?php echo $grade; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Grade Name:</td>
      <td><input type="text" name="grade_name" value="<?php echo $row_teamgrades_fix['grade_name']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Season:</td>
      <td><select name="season">
        <option value="S1" <?php if (!(strcmp("S1", htmlentities($row_teamgrades_fix['season'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>S1</option>
        <option value="S2" <?php if (!(strcmp("S2", htmlentities($row_teamgrades_fix['season'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>S2</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Type</td>
      <td><select name="type">
        <option value="Snooker" <?php if (!(strcmp("Snooker", htmlentities($row_teamgrades_fix['type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Snooker</option>
        <option value="Billiards" <?php if (!(strcmp("Billiards", htmlentities($row_teamgrades_fix['type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Billiards</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Allocated Ranking points this grade:</td>
      <td>
      <select name="RP">
        <option value="50" <?php if (!(strcmp("50", htmlentities($row_teamgrades_fix['RP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>100 points per game won - Billiards A Grade, A State</option>
        <option value="25" <?php if (!(strcmp("25", htmlentities($row_teamgrades_fix['RP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>50 points per game won - Billiards B Grade, B State</option>
        <option value="80" <?php if (!(strcmp("80", htmlentities($row_teamgrades_fix['RP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>80 points per frame won - Snooker A Premier, A Willis</option>
        <option value="70" <?php if (!(strcmp("70", htmlentities($row_teamgrades_fix['RP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>70 points per frame won - Snooker A State</option>
        <option value="60" <?php if (!(strcmp("60", htmlentities($row_teamgrades_fix['RP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>60 points per frame won - Snooker B Premier, B Willis</option>
        <option value="50" <?php if (!(strcmp("50", htmlentities($row_teamgrades_fix['RP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>50 points per frame won - Snooker B State</option>
        <option value="40" <?php if (!(strcmp("40", htmlentities($row_teamgrades_fix['RP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>40 points per frame won - Snooker C Premier, C Willis</option>
        <option value="30" <?php if (!(strcmp("30", htmlentities($row_teamgrades_fix['RP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>30 points per frame won - Snooker C State</option>
        <option value="20" <?php if (!(strcmp("20", htmlentities($row_teamgrades_fix['RP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>20 points per frame won - Snooker D Premier, D Willis</option>
        <option value="15" <?php if (!(strcmp("15", htmlentities($row_teamgrades_fix['RP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>15 points per frame won - Snooker D State</option>
      </select>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Day Played:</td>
      <td nowrap="nowrap"><select name="dayplayed">
        <option value="Mon" <?php if (!(strcmp("Mon", htmlentities($row_teamgrades_fix['dayplayed'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Mon</option>
        <option value="Wed" <?php if (!(strcmp("Wed", htmlentities($row_teamgrades_fix['dayplayed'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Wed</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Teams in Finals</td>
      <td nowrap="nowrap">4</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Playing this season</td>
      <td nowrap="nowrap"><select name="current">
        <option value="No" <?php if (!(strcmp("No", htmlentities($row_teamgrades_fix['current'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
        <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_teamgrades_fix['current'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Current fixture</td>
      <td nowrap="nowrap"><input type="text" name="fix_upload" value="<?php echo $row_teamgrades_fix['fix_upload']; ?>" size="32" /> 
      Delete to remove</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update Grade" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="finals_teams" value="4" />
  <input type="hidden" name="grade" value="<?php echo $row_teamgrades_fix['grade']; ?>" />
  <input type="hidden" name="fix_cal_year" value="<?php echo date("Y")?>" />
  
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($teamgrades_fix);
?>
