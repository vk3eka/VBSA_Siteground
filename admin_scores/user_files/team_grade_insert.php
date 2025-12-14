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

// added rank_points 7 to 12 .........
// added rank_points -2 to 0 .........
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("Insert INTO Team_grade (grade, grade_name, season, type, RP, fix_upload, fix_cal_year, current, finals_teams, dayplayed, min_breaks, no_of_matches, no_of_rounds, no_of_players, games_round, tier_2_rp, tier_1_rp, tier0_rp, tier1_rp, tier2_rp, tier3_rp, tier4_rp, tier5_rp, tier6_rp, tier7_rp, tier8_rp, tier9_rp, tier10_rp, tier11_rp, tier12_rp) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
              GetSQLValueString($_POST['grade'], "text"),
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
              GetSQLValueString($_POST['rank_points_-2'], "int"),
              GetSQLValueString($_POST['rank_points_-1'], "int"),
              GetSQLValueString($_POST['rank_points_0'], "int"),
              GetSQLValueString($_POST['rank_points_1'], "int"),
              GetSQLValueString($_POST['rank_points_2'], "int"),
              GetSQLValueString($_POST['rank_points_3'], "int"),
              GetSQLValueString($_POST['rank_points_4'], "int"),
              GetSQLValueString($_POST['rank_points_5'], "int"),
              GetSQLValueString($_POST['rank_points_6'], "int"),
              GetSQLValueString($_POST['rank_points_7'], "int"),
              GetSQLValueString($_POST['rank_points_8'], "int"),
              GetSQLValueString($_POST['rank_points_9'], "int"),
              GetSQLValueString($_POST['rank_points_10'], "int"),
              GetSQLValueString($_POST['rank_points_11'], "int"),
              GetSQLValueString($_POST['rank_points_12'], "int"));

//echo($insertSQL . "<br>");
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
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
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
      <td nowrap="nowrap" align="right">Alloc. Ranking Points (Snooker):</td>
      <td><input type='text' name='RP' id='RP' style='width : 30px;' value=''></td>
    </tr>
  </table>
  <table align="center" cellpadding="5" cellspacing="5" width='500px'>
    <tr>
      <td colspan='6' align='center'>Alloc. Ranking Points (Billiards)</td>
    </tr>
    <tr>
      <td align='center'>Tier -2</td>
      <td align='center'>Tier -1</td>
      <td align='center'>Tier 0</td>
      <td align='center'>Tier 1</td>
      <td align='center'>Tier 2</td>
      <td align='center'>Tier 3</td>
      <td align='center'>Tier 4</td>
      <td align='center'>Tier 5</td>
      
    </tr>
    <tr>
      <td align='center'><input type='text' name='rank_points_-2' id='rank_points_-2' style='width : 30px;' value=''></td>
      <td align='center'><input type='text' name='rank_points_-1' id='rank_points_-1' style='width : 30px;' value=''></td>
      <td align='center'><input type='text' name='rank_points_0' id='rank_points_0' style='width : 30px;' value=''></td>
      <td align='center'><input type='text' name='rank_points_1' id='rank_points_1' style='width : 30px;' value=''></td>
      <td align='center'><input type='text' name='rank_points_2' id='rank_points_2' style='width : 30px;' value=''></td>
      <td align='center'><input type='text' name='rank_points_3' id='rank_points_3' style='width : 30px;' value=''></td>
      <td align='center'><input type='text' name='rank_points_4' id='rank_points_4' style='width : 30px;' value=''></td>
      <td align='center'><input type='text' name='rank_points_5' id='rank_points_5' style='width : 30px;' value=''></td>
    </tr>
    <tr>
      <td align='center'>Tier 6</td>
      <td align='center'>Tier 7</td>
      <td align='center'>Tier 8</td>
      <td align='center'>Tier 9</td>
      <td align='center'>Tier 10</td>
      <td align='center'>Tier 11</td>
      <td align='center'>Tier 12</td>
      <td align='center'>&nbsp</td>
    </tr>
    <tr>
      <td align='center'><input type='text' name='rank_points_6' id='rank_points_6' style='width : 30px;' value=''></td>
      <td align='center'><input type='text' name='rank_points_7' id='rank_points_7' style='width : 30px;' value=''></td>
      <td align='center'><input type='text' name='rank_points_8' id='rank_points_8' style='width : 30px;' value=''></td>
      <td align='center'><input type='text' name='rank_points_9' id='rank_points_9' style='width : 30px;' value=''></td>
      <td align='center'><input type='text' name='rank_points_10' id='rank_points_10' style='width : 30px;' value=''></td>
      <td align='center'><input type='text' name='rank_points_11' id='rank_points_11' style='width : 30px;' value=''></td>
      <td align='center'><input type='text' name='rank_points_12' id='rank_points_12' style='width : 30px;' value=''></td>

    </tr>
  </table>
  <table align="center" cellpadding="5" cellspacing="5" width='500px'>
    <tr>
      <td align='center'>Min. Break Points</td>
      <td align='center'>No. of Matches</td>
      <td align='center'>No. of Rounds</td>
      <td align='center'>No. of Players</td>
      <td align='center'>Games per Match</td>
    </tr>
    <tr>
      <td align='center'>&nbsp;</td>
      <td align='center'>&nbsp;</td>
      <td align='center'>(inc Finals)</td>
      <td align='center'>&nbsp;</td>
      <td align='center'>&nbsp;</td>
    </tr>
    <tr>
      <td align='center'><input type='text' name='min_break_points' id='min_break_points"' style='width : 30px;' value=''></td>
      <td align='center'><input type='text' name='matches' id='matches' style='width : 30px;' value=''></td>
      <td align='center'><input type='text' name='rounds' id='rounds' style='width : 30px;' value=''></td>
      <td align='center'><input type='text' name='players' id='players' style='width : 30px;' value=''></td>
      <td align='center'><input type='text' name='games_round' id='games_round' style='width : 30px;' value=''></td>
    </tr>
     <tr valign="baseline">
      <td colspan='6' align='center'><input type="submit" value="Insert New Grade" /></td>
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

