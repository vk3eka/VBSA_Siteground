<?php require_once('../../Connections/connvbsa.php'); ?>
<?php

error_reporting(0);

if (!isset($_SESSION)) {
  session_start();
}

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
  $updateSQL = sprintf("UPDATE Team_entries SET team_club=%s, team_club_id=%s, team_name=%s, team_grade=%s, team_season=%s, day_played=%s, players=%s, include_draw=%s, comptype=%s, need_players=%s WHERE team_id=%s",
                GetSQLValueString($_POST['team_club'], "text"),
                GetSQLValueString($_POST['team_club_id'], "int"),
                GetSQLValueString($_POST['team_name'], "text"),
                GetSQLValueString($_POST['team_grade'], "text"),
                GetSQLValueString($_POST['team_season'], "text"),
                GetSQLValueString($_POST['day_played'], "text"),
                GetSQLValueString($_POST['players'], "text"),
                GetSQLValueString($_POST['include_draw'], "text"),
                GetSQLValueString($_POST['comptype'], "text"),
                GetSQLValueString(isset($_POST['need_players']) ? "true" : "", "defined","1","0"),
                GetSQLValueString($_POST['team_id'], "int"));

//echo($updateSQL . "<br>");

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());
  
  $updateGoTo = $_SESSION['page'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


$team_idt = "-1";
if (isset($_GET['team_id'])) {
  $team_id= $_GET['team_id'];
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_team_entries = "SELECT * FROM Team_entries WHERE team_id = '$team_id'";
//echo("Team Entries " . $query_team_entries . "<br>");
$team_entries = mysql_query($query_team_entries, $connvbsa) or die(mysql_error());
$row_team_entries = mysql_fetch_assoc($team_entries);
$totalRows_team_entries = mysql_num_rows($team_entries);

mysql_select_db($database_connvbsa, $connvbsa);
$query_vbsa_clubs = "SELECT distinct team_club FROM vbsa3364_vbsa2.Team_entries where team_season = '$season' and team_cal_year = " . date("Y") . " Order By team_club";
//$query_vbsa_clubs = "SELECT ClubNumber, ClubTitle, ClubNameVBSA FROM clubs WHERE VBSAteam=1 ORDER BY ClubNameVBSA";
//echo("Club Entries " . $query_vbsa_clubs . "<br>");
$vbsa_clubs = mysql_query($query_vbsa_clubs, $connvbsa) or die(mysql_error());
//$row_vbsa_clubs = mysql_fetch_assoc($vbsa_clubs);
//$totalRows_vbsa_clubs = mysql_num_rows($vbsa_clubs);

mysql_select_db($database_connvbsa, $connvbsa);
$query_grades = "SELECT distinct grade, grade_name, season, type, current, dayplayed FROM Team_grade WHERE season = '$season' AND current = 'Yes' ORDER BY type, dayplayed, grade";
//echo("Grades " . $query_grades . "<br>");
$grades = mysql_query($query_grades, $connvbsa) or die(mysql_error());
$row_grades = mysql_fetch_assoc($grades);
$totalRows_grades = mysql_num_rows($grades);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<table border="0" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td align="center" class="red_bold"><?php echo $_SESSION['page']; ?></td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="red_bold">Edit a team in  Season <?php echo $row_team_entries['team_season']; ?></td>
    <td>&nbsp;</td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" cellpadding="5" cellspacing="5">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Team id:</td>
      <td><?php echo $team_id; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Team club:</td>
      <td><select name="team_club">
        <option value="<?php echo $row_team_entries['team_club']?>"><?php echo $row_team_entries['team_club']; ?></option>
        <?php do {  ?>
        <option value="<?php echo $row_vbsa_clubs['team_club']?>"><?php echo $row_vbsa_clubs['team_club']?></option>
        <?php
        } while ($row_vbsa_clubs = mysql_fetch_assoc($vbsa_clubs));
          /*$rows = mysql_num_rows($vbsa_clubs);
          if($rows > 0) {
              mysql_data_seek($vbsa_clubs, 0);
        	  $row_vbsa_clubs = mysql_fetch_assoc($vbsa_clubs);
          }*/
        ?>
      </select> Please select       
      </td>
<!--
      <td><select name="team_club">
        <option value="" <?php if (!(strcmp("", htmlentities($row_team_entries['team_club'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>>No Entry</option>
        <?php do {  ?>
        <option value="<?php echo $row_vbsa_clubs['ClubTitle']?>"<?php if (!(strcmp($row_vbsa_clubs['ClubTitle'], htmlentities($row_team_entries['team_club'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_vbsa_clubs['ClubTitle']?></option>
        <?php
        } while ($row_vbsa_clubs = mysql_fetch_assoc($vbsa_clubs));
          $rows = mysql_num_rows($vbsa_clubs);
          if($rows > 0) {
              mysql_data_seek($vbsa_clubs, 0);
            $row_vbsa_clubs = mysql_fetch_assoc($vbsa_clubs);
          }
        ?>
      </select> Please select       
     </td>
--> 

    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Club ID:</td>
      <td><input type="text" name="team_club_id" value="<?php echo $row_team_entries['team_club_id']; ?>" size="10" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Team name:</td>
      <td><input type="text" name="team_name" value="<?php echo $row_team_entries['team_name']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Team grade:</td>
      <td><select name="team_grade">
          <option value="" <?php if (!(strcmp("", htmlentities($row_team_entries['team_grade'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>>No Entry</option>
          <?php do {  ?>
          <option value="<?php echo $row_grades['grade']?>"<?php if (!(strcmp($row_grades['grade'], htmlentities($row_team_entries['team_grade'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_grades['grade']?></option>
          <?php
          } while ($row_grades = mysql_fetch_assoc($grades));
            $rows = mysql_num_rows($grades);
            if($rows > 0) {
                mysql_data_seek($grades, 0);
          	  $row_grades = mysql_fetch_assoc($grades);
            }
          ?>
        </select> Please select
      </td>
    </tr>
    <tr>
      <td align="right">Day played:</td>
        <td><select name="day_played">
          <option value="Mon" <?php if (!(strcmp("Mon", htmlentities($row_team_entries['day_played'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Mon</option>
          <option value="Wed" <?php if (!(strcmp("Wed", htmlentities($row_team_entries['day_played'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Wed</option>
          </select>        
      </td>
    </tr>
    <tr>
      <td align="right">Type</td>
      <td><select name="comptype">
        <option value="Billiards" <?php if (!(strcmp("Billiards", htmlentities($row_team_entries['comptype'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Billiards</option>
        <option value="Snooker" <?php if (!(strcmp("Snooker", htmlentities($row_team_entries['comptype'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Snooker</option>
        </select>
      </td>
    </tr>
    <tr>
      <td align="right">Players:</td>
        <td><select name="players">
          <option value="4" <?php if (!(strcmp("4", htmlentities($row_team_entries['players'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>4</option>
          <option value="6" <?php if (!(strcmp("6", htmlentities($row_team_entries['players'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>6</option>
          </select>        
      </td>
    </tr>
    <tr>
      <td align="right">Need Players?</td>
      <td><input type="checkbox" name="need_players" id="need_players"  <?php if (!(strcmp(htmlentities($row_team_entries['need_players'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Final 4 </td>
      <td>Auto entered &quot;4&quot; when inserted</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Include in draw ?</td>
      <td nowrap="nowrap"><select name="include_draw">
        <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_team_entries['include_draw'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
        <option value="No" <?php if (!(strcmp("No", htmlentities($row_team_entries['include_draw'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
      </select> 
        If &quot;No&quot; will not appear in team count, or appear on the website ladder</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Season:</td>
      <td nowrap="nowrap"><select name="team_season">
        <option value="S1" <?php if (!(strcmp("S1", htmlentities($row_team_entries['team_season'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>S1</option>
        <option value="S2" <?php if (!(strcmp("S2", htmlentities($row_team_entries['team_season'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>S2</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Year entered:</td>
      <td><?php echo date("Y")?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Entered By:</td>
      <td><?php echo $row_team_entries['entered_by']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"></td>
      <td><input type="submit" value="Update Team" /></td>
    </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="team_id" value="<?php echo $row_team_entries['team_id']; ?>" />
</form>
<p>&nbsp;</p>
</center>
</body>
</html>
