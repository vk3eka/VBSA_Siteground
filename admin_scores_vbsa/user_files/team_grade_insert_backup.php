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

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO Team_grade (grade, grade_name, season, type, RP, fix_upload, fix_cal_year, `current`, finals_teams, dayplayed) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['grade'], "text"),
                       GetSQLValueString($_POST['grade_name'], "text"),
                       GetSQLValueString($_POST['season'], "text"),
                       GetSQLValueString($_POST['type'], "text"),
					   GetSQLValueString($_POST['RP'], "int"),
                       GetSQLValueString($_POST['fix_upload'], "text"),
                       GetSQLValueString($_POST['fix_cal_year'], "date"),
                       GetSQLValueString($_POST['current'], "text"),
                       GetSQLValueString($_POST['finals_teams'], "int"),
                       GetSQLValueString($_POST['dayplayed'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "../team_grades.php?season=".$season ;
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

<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td align="left" class="red_bold" nowrap="nowrap">TEAM GRADES - Insert grade into <?php echo $season ?></td>
    <td align="left" class="red_bold" nowrap="nowrap">&nbsp;</td>
    <td align="left" class="red_bold" nowrap="nowrap">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class="red_bold" nowrap="nowrap">
    <p>Use only when new grades structure has been changed at the direction of the Board</p></td>
    <td align="left" class="red_bold" nowrap="nowrap">&nbsp;</td>
    <td align="right"><input type="button" value="Return to previous page"/></td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Grade (Acronym):</td>
      <td><input type="text" name="grade" value="" size="32" /> 
      EG. APB</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Grade name (Complete Name):</td>
      <td><input type="text" name="grade_name" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Season:</td>
      <td><?php echo $season ?> (Auto inserted)</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Type:</td>
      <td><select name="type">
        <option value="Snooker" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Snooker</option>
        <option value="Billiards" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>Billiards</option>
      </select></td>
    </tr>
      <tr valign="baseline">
      <td nowrap="nowrap" align="right">Allocated Ranking points this grade:</td>
      <td><select name="RP">
            <option value="50">100 points per game won - Billiards A Grade, A State</option>
            <option value="25">50 points per game won - Billiards B Grade, B State</option>
            <option value="80">80 points per frame won - Snooker A Premier, A Willis  </option>
            <option value="70">70 points per frame won - Snooker A State </option>
            <option value="60">60 points per frame won - Snooker B Premier, B Willis  </option>
            <option value="50">50 points per frame won - Snooker B State </option>
            <option value="40">40 points per frame won - Snooker C Premier, C Willis  </option>
            <option value="30">30 points per frame won - Snooker C State </option>
            <option value="20">20 points per frame won - Snooker D Premier, D Willis  </option>
            <option value="15">15 points per frame won - Snooker D State </option>
          </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Current:</td>
      <td><select name="current">
        <option value="Yes" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Yes</option>
        <option value="No" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>No</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Day played:</td>
      <td><select name="dayplayed">
        <option value="Mon" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Mon</option>
        <option value="Wed" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>Wed</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insert New Grade" /></td>
    </tr>
  </table>
  <input type="hidden" name="fix_upload" value="" />
  <input type="hidden" name="fix_cal_year" value="<?php echo date("Y"); ?>" />
  <input type="hidden" name="season" value="<?php echo $season; ?>" />
  <input type="hidden" name="finals_teams" value="4" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>

