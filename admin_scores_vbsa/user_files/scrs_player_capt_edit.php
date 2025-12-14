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
  $updateSQL = sprintf("UPDATE scrs SET MemberID=%s, team_grade=%s, team_id=%s, scr_season=%s, captain_scrs=%s, authoriser_scrs=%s WHERE scrsID=%s",
                       GetSQLValueString($_POST['MemberID'], "int"),
                       GetSQLValueString($_POST['team_grade'], "text"),
                       GetSQLValueString($_POST['team_id'], "int"),
                       GetSQLValueString($_POST['scr_season'], "text"),
                       GetSQLValueString(isset($_POST['captain_scrs']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['authoriser_scrs']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['scrsID'], "int"));
//echo($updateGoTo . "<Br>");
  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../team_entries_player_multiple_insert.php?team_id=".$team_id."&season=".$season."&team_club=".$team_club."&team_grade=".$team_grade;
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$team_id = "-1";
if (isset($_GET['team_id'])) {
  $team_id = $_GET['team_id'];
}

$team_grade = "-1";
if (isset($_GET['team_grade'])) {
  $team_grade = $_GET['team_grade'];
}

$team_club = "-1";
if (isset($_GET['team_club'])) {
  $team_club = $_GET['team_club'];
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$scrsID = "-1";
if (isset($_GET['scrsID'])) {
  $scrsID = $_GET['scrsID'];
}

//echo("Grade " . $_GET['grade'] . "<br>");

mysql_select_db($database_connvbsa, $connvbsa);
$query_scrs_edit = "SELECT scrs.scrsID, scrs.MemberID, scrs.team_grade, scrs.team_id,  members.FirstName, members.LastName, scrs.captain_scrs, scrs.authoriser_scrs, scr_season, Club FROM scrs, members WHERE scrs.MemberID=members.MemberID AND scrs.scrsID ='$scrsID'";
$scrs_edit = mysql_query($query_scrs_edit, $connvbsa) or die(mysql_error());
$row_scrs_edit = mysql_fetch_assoc($scrs_edit);
$totalRows_scrs_edit = mysql_num_rows($scrs_edit);

mysql_select_db($database_connvbsa, $connvbsa);
$query_vbsa_grades = "SELECT grade, grade_name, season  FROM Team_grade  WHERE season='$season' AND current='Yes' ORDER BY type, grade";
$vbsa_grades = mysql_query($query_vbsa_grades, $connvbsa) or die(mysql_error());
$row_vbsa_grades = mysql_fetch_assoc($vbsa_grades);
$totalRows_vbsa_grades = mysql_num_rows($vbsa_grades);
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
</table>

<table align="center">
  <tr>
    <td align="center">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="red_bold">Edit player details</td>
    <td align="right">&nbsp;</td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" cellpadding="3" cellspacing="3">
    <tr>
      <td align="right" nowrap="nowrap">Scrs ID:</td>
      <td>&nbsp;<?php echo $scrsID; ?></td>
    </tr>
    <tr>
      <td align="right" >Season:</td>
      <td align="left">&nbsp;<?php echo $season; ?></td>
    </tr>
    <tr>
      <td align="right" valign="middle" nowrap="nowrap">Member ID:</td>
      <td valign="middle"><input type="text" name="MemberID" value="<?php echo $row_scrs_edit['MemberID']; ?>" size="10" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle" nowrap="nowrap">Grade:</td>
      <!--<td>
            <select name="team_grade">
            <?php
            do {  
            ?>
                          <option value='test'><?php echo $row_scrs_edit['team_grade']?></option>
                          <option value="<?php echo $row_vbsa_grades['grade']?>"<?php if (!(strcmp($row_vbsa_grades['grade'], $row_scrs_edit['team_grade']))) {echo "selected=\"selected\"";} ?>><?php echo $row_vbsa_grades['grade_name']?></option>
                          <?php
            } while ($row_vbsa_grades = mysql_fetch_assoc($vbsa_grades));


              $rows = mysql_num_rows($vbsa_grades);
              if($rows > 0) {
                  mysql_data_seek($vbsa_grades, 0);
            	  $row_vbsa_grades = mysql_fetch_assoc($vbsa_grades);
              }
            ?>
            </select>
      </td>-->
       <td valign="middle"><input type="text" name="grade" value="<?php echo $row_scrs_edit['team_grade']; ?>" size="10" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle" nowrap="nowrap">Team ID:</td>
      <td valign="middle"><input type="text" name="team_id" value="<?php echo $row_scrs_edit['team_id']; ?>" size="10" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle" nowrap="nowrap">Season:</td>
      <td valign="middle"><select name="scr_season">
        <option value="S1" <?php if (!(strcmp("S1", htmlentities($row_scrs_edit['scr_season'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>S1</option>
        <option value="S2" <?php if (!(strcmp("S2", htmlentities($row_scrs_edit['scr_season'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>S2</option>
      </select>
      
      </td>
    </tr>
    <tr>
      <td align="right" valign="middle" nowrap="nowrap">Captain:</td>
      <td colspan="3" align="left"><input type="checkbox" name="captain_scrs" id="captain_scrs"  <?php if (!(strcmp(htmlentities($row_scrs_edit['captain_scrs'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
    <tr>
      <td align="right" valign="middle" nowrap="nowrap">Authoriser:</td>
      <td colspan="3" align="left"><input type="checkbox" name="authoriser_scrs" id="authoriser_scrs"  <?php if (!(strcmp(htmlentities($row_scrs_edit['authoriser_scrs'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr>
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update Player" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="scrsID" value="<?php echo $row_scrs_edit['scrsID']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php

?>


