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
  $updateSQL = sprintf("Update Team_grade SET grade_name=%s, season=%s, type=%s, RP=%s, fix_upload=%s, fix_cal_year=%s, current=%s, finals_teams=%s, dayplayed=%s, min_breaks=%s, no_of_matches=%s, no_of_rounds=%s, no_of_players=%s, games_round=%s, tier1_rp=%s, tier2_rp=%s, tier3_rp=%s, tier4_rp=%s, tier5_rp=%s, tier6_rp=%s WHERE grade=%s",
            GetSQLValueString($_POST['grade_name'], "text"),
            GetSQLValueString($_POST['season'], "text"),
            GetSQLValueString($_POST['type'], "text"),
            GetSQLValueString($_POST['RP'], "int"),
            GetSQLValueString($_POST['fix_upload'], "text"),
            GetSQLValueString($_POST['fix_cal_year'], "date"),
            GetSQLValueString($_POST['current'], "text"),
            GetSQLValueString($_POST['finals_teams'], "int"),
            GetSQLValueString($_POST['dayplayed'], "text"),
            GetSQLValueString($_POST['min_break_points'], "int"),
            GetSQLValueString($_POST['matches'], "int"),
            GetSQLValueString($_POST['rounds'], "int"),
            GetSQLValueString($_POST['players'], "int"),
            GetSQLValueString($_POST['games_round'], "int"),
            GetSQLValueString($_POST['rank_points_1'], "int"),
            GetSQLValueString($_POST['rank_points_2'], "int"),
            GetSQLValueString($_POST['rank_points_3'], "int"),
            GetSQLValueString($_POST['rank_points_4'], "int"),
            GetSQLValueString($_POST['rank_points_5'], "int"),
            GetSQLValueString($_POST['rank_points_6'], "int"),
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
    <td align="center" class="red_bold" nowrap="nowrap"> TEAM GRADES - Edit. If a grade is not playing please select &quot;Current this season&quot; as &quot;No&quot;</td>
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
      <td nowrap="nowrap" align="right">Day Played:</td>
      <td nowrap="nowrap"><select name="dayplayed">
        <option value="Mon" <?php if (!(strcmp("Mon", htmlentities($row_teamgrades_fix['dayplayed'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Mon</option>
        <option value="Wed" <?php if (!(strcmp("Wed", htmlentities($row_teamgrades_fix['dayplayed'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Wed</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Teams in Finals</td>
      <td nowrap="nowrap"><input type='text' name='finals_teams' id='finals_teams' style='width : 30px;' value=4></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Current this season</td>
      <td nowrap="nowrap"><select name="current">
        <option value="No" <?php if (!(strcmp("No", htmlentities($row_teamgrades_fix['current'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
        <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_teamgrades_fix['current'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Current fixture pdf</td>
      <td nowrap="nowrap"><input type="text" name="fix_upload" value="<?php echo $row_teamgrades_fix['fix_upload']; ?>" size="32" /> Delete to remove</td>
    </tr>
    </table>
    <table align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td colspan='6' align='center'>Alloc. Ranking Points (Billiards)</td>
    </tr>
    <tr>
      <td align='center'>Tier 1</td>
      <td align='center'>Tier 2</td>
      <td align='center'>Tier 3</td>
      <td align='center'>Tier 4</td>
      <td align='center'>Tier 5</td>
      <td align='center'>Tier 6</td>
    </tr>
    <tr>
      <td align='center'><input type='text' name='rank_points_1' id='rank_points_1' style='width : 30px;' value='<?= $row_teamgrades_fix['tier1_rp'] ?>'></td>
      <td align='center'><input type='text' name='rank_points_2' id='rank_points_2' style='width : 30px;' value='<?= $row_teamgrades_fix['tier2_rp'] ?>'></td>
      <td align='center'><input type='text' name='rank_points_3' id='rank_points_3' style='width : 30px;' value='<?= $row_teamgrades_fix['tier3_rp'] ?>'></td>
      <td align='center'><input type='text' name='rank_points_4' id='rank_points_4' style='width : 30px;' value='<?= $row_teamgrades_fix['tier4_rp'] ?>'></td>
      <td align='center'><input type='text' name='rank_points_5' id='rank_points_5' style='width : 30px;' value='<?= $row_teamgrades_fix['tier5_rp'] ?>'></td>
      <td align='center'><input type='text' name='rank_points_6' id='rank_points_6' style='width : 30px;' value='<?= $row_teamgrades_fix['tier6_rp'] ?>'></td>
    </tr>
    <tr>
      <td align="center">Alloc. Ranking Points (Snooker)</td>
      <td align='center'>Min. Break Points</td>
      <td align='center'>No. of Matches</td>
      <td align='center'>No. of Rounds (inc Finals)</td>
      <td align='center'>No. of Players</td>
      <td align='center'>Games per Match</td>
    </tr>
    <tr>
      <td align='center'><input type='text' name='RP' id='RP' style='width : 30px;' value='<?= $row_teamgrades_fix['RP'] ?>'></td>
      <td align='center'><input type='text' name='min_break_points' id='min_break_points"' style='width : 30px;' value='<?= $row_teamgrades_fix['min_breaks'] ?>'></td>
      <td align='center'><input type='text' name='matches' id='matches' style='width : 30px;' value='<?= $row_teamgrades_fix['no_of_matches'] ?>'></td>
      <td align='center'><input type='text' name='rounds' id='rounds' style='width : 30px;' value='<?= $row_teamgrades_fix['no_of_rounds'] ?>'></td>
      <td align='center'><input type='text' name='players' id='players' style='width : 30px;' value='<?= $row_teamgrades_fix['no_of_players'] ?>'></td>
      <td align='center'><input type='text' name='games_round' id='games_round' style='width : 30px;' value='<?= $row_teamgrades_fix['games_round'] ?>'></td>
    </tr>
    </table>
    <table align="center" cellpadding="5" cellspacing="5">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update Grade" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="grade" value="<?php echo $row_teamgrades_fix['grade']; ?>" />
  <input type="hidden" name="fix_cal_year" value="<?php echo date("Y")?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($teamgrades_fix);
?>
