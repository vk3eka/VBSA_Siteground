<?php require_once('../../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../../page_error.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php require_once('../../Connections/connvbsa.php'); ?><?php
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

$editFormAction = "ajax/player_edit_scrs.php";
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE scrs SET MemberID=%s, team_grade=%s, allocated_rp=%s, game_type=%s, scr_season=%s, team_id=%s, r01s=%s, r02s=%s, r03s=%s, r04s=%s, r05s=%s, r06s=%s, r07s=%s, r08s=%s, r09s=%s, r10s=%s, r11s=%s, r12s=%s, r13s=%s, r14s=%s, r15s=%s, r16s=%s, r17s=%s, r18s=%s, r01pos=%s, r02pos=%s, r03pos=%s, r04pos=%s, r05pos=%s, r06pos=%s, r07pos=%s, r08pos=%s, r09pos=%s, r10pos=%s, r11pos=%s, r12pos=%s, r13pos=%s, r14pos=%s, r15pos=%s, r16pos=%s, r17pos=%s, r18pos=%s, SF1=%s, SF2=%s, PF=%s, GF=%s, captain_scrs=%s, final_sub=%s WHERE scrsID=%s",
                       GetSQLValueString($_POST['MemberID'], "int"),
                       GetSQLValueString($_POST['team_grade'], "text"),
					   GetSQLValueString($_POST['allocated_rp'], "int"),
					   GetSQLValueString($_POST['game_type'], "text"),
					   GetSQLValueString($_POST['scr_season'], "text"),
                       GetSQLValueString($_POST['team_id'], "int"),
                       GetSQLValueString($_POST['r01s'], "int"),
                       GetSQLValueString($_POST['r02s'], "int"),
                       GetSQLValueString($_POST['r03s'], "int"),
                       GetSQLValueString($_POST['r04s'], "int"),
                       GetSQLValueString($_POST['r05s'], "int"),
                       GetSQLValueString($_POST['r06s'], "int"),
                       GetSQLValueString($_POST['r07s'], "int"),
                       GetSQLValueString($_POST['r08s'], "int"),
                       GetSQLValueString($_POST['r09s'], "int"),
                       GetSQLValueString($_POST['r10s'], "int"),
                       GetSQLValueString($_POST['r11s'], "int"),
                       GetSQLValueString($_POST['r12s'], "int"),
                       GetSQLValueString($_POST['r13s'], "int"),
                       GetSQLValueString($_POST['r14s'], "int"),
                       GetSQLValueString($_POST['r15s'], "int"),
                       GetSQLValueString($_POST['r16s'], "int"),
                       GetSQLValueString($_POST['r17s'], "int"),
                       GetSQLValueString($_POST['r18s'], "int"),
                       GetSQLValueString($_POST['r01pos'], "int"),
                       GetSQLValueString($_POST['r02pos'], "int"),
                       GetSQLValueString($_POST['r03pos'], "int"),
                       GetSQLValueString($_POST['r04pos'], "int"),
                       GetSQLValueString($_POST['r05pos'], "int"),
                       GetSQLValueString($_POST['r06pos'], "int"),
                       GetSQLValueString($_POST['r07pos'], "int"),
                       GetSQLValueString($_POST['r08pos'], "int"),
                       GetSQLValueString($_POST['r09pos'], "int"),
                       GetSQLValueString($_POST['r10pos'], "int"),
                       GetSQLValueString($_POST['r11pos'], "int"),
                       GetSQLValueString($_POST['r12pos'], "int"),
                       GetSQLValueString($_POST['r13pos'], "int"),
                       GetSQLValueString($_POST['r14pos'], "int"),
                       GetSQLValueString($_POST['r15pos'], "int"),
                       GetSQLValueString($_POST['r16pos'], "int"),
                       GetSQLValueString($_POST['r17pos'], "int"),
                       GetSQLValueString($_POST['r18pos'], "int"),
                       GetSQLValueString($_POST['SF1'], "int"),
                       GetSQLValueString($_POST['SF2'], "int"),
                       GetSQLValueString($_POST['PF'], "int"),
                       GetSQLValueString($_POST['GF'], "int"),
					   GetSQLValueString($_POST['captain_scrs'], "text"),
					   GetSQLValueString($_POST['final_sub'], "text"),
                       GetSQLValueString($_POST['scrsID'], "int"));
  
  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());
  echo "Successfully updated!";
  exit;
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$scrs = "-1";
if (isset($_GET['scrs'])) {
  $scrs = $_GET['scrs'];
}

$grade = "-1";
if (isset($_GET['grade'])) {
  $grade = $_GET['grade'];
}


mysql_select_db($database_connvbsa, $connvbsa);
$query_Scrs_Edit = "SELECT scrs.scrsID, scrs.MemberID, scrs.team_grade, scrs.team_id, scr_season, game_type, scrs.r01s, scrs.r02s, scrs.r03s, scrs.r04s, scrs.r05s, scrs.r06s, scrs.r07s, scrs.r08s, scrs.r09s, scrs.r10s, scrs.r11s, scrs.r12s, scrs.r13s, scrs.r14s, scrs.r15s, scrs.r16s, scrs.r17s, scrs.r18s, scrs.r01pos, scrs.r02pos, scrs.r03pos, scrs.r04pos, scrs.r05pos, scrs.r06pos, scrs.r07pos, scrs.r08pos, scrs.r09pos, scrs.r10pos, scrs.r11pos, scrs.r12pos, scrs.r13pos, scrs.r14pos, scrs.r15pos, scrs.r16pos, scrs.r17pos, scrs.r18pos, scrs.SF1, scrs.SF2, scrs.PF, scrs.GF, members.MemberID, members.FirstName, members.LastName, scrs.final_sub, scrs.captain_scrs FROM scrs, members WHERE scrs.MemberID=members.MemberID AND scrs.scrsID = '$scrs'";
$Scrs_Edit = mysql_query($query_Scrs_Edit, $connvbsa) or die(mysql_error());
$row_Scrs_Edit = mysql_fetch_assoc($Scrs_Edit);
$totalRows_Scrs_Edit = mysql_num_rows($Scrs_Edit);

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
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>
</head>

<body>
  
  <table width="800" border="0" align="center">
    <tr>
      <td align="center" class="red_bold">Edit a player score details for: <?php echo $row_Scrs_Edit['FirstName']; ?> <?php echo $row_Scrs_Edit['LastName']; ?><span class="page"> (<?php echo $row_Scrs_Edit['team_grade']; ?>)</span></td>
    </tr>
    <tr>
      <td align="center" class="page">&nbsp;</td>
    </tr>
  </table>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="return doit()">
    <table width="930" border="1" align="center">
      <tr>
        <td colspan="2">Memb ID
          <input type="text" name="MemberID" value="<?php echo htmlentities($row_Scrs_Edit['MemberID'], ENT_COMPAT, 'utf-8'); ?>" size="6" />
        </td>
        <td colspan="4" align="center">Grade
          <select name="team_grade">
            <?php if($season=='S1') do {  ?>
            <option value="<?php echo $row_grades_S1['grade']?>"<?php if (!(strcmp($row_grades_S1['grade'], $row_Scrs_Edit['team_grade']))) {echo "selected=\"selected\"";} ?>> <?php echo $row_grades_S1['grade_name']?></option>
            <?php
				} while ($row_grades_S1 = mysql_fetch_assoc($grades_S1));
  				$rows = mysql_num_rows($grades_S1);
  				if($rows > 0) {
      			mysql_data_seek($grades_S1, 0);
	  			$row_grades_S1 = mysql_fetch_assoc($grades_S1);
				
  			}if($season=='S2') do {  ?>
            <option value="<?php echo $row_grades_S2['grade']?>"<?php if (!(strcmp($row_grades_S2['grade'], $row_Scrs_Edit['team_grade']))) {echo "selected=\"selected\"";} ?>> <?php echo $row_grades_S2['grade_name']?></option>
            <?php
				} while ($row_grades_S2 = mysql_fetch_assoc($grades_S2));
 				 $rows = mysql_num_rows($grades_S2);
 				if($rows > 0) {
     			 mysql_data_seek($grades_S2, 0);
	 			 $row_grades_S2 = mysql_fetch_assoc($grades_S2);
  			}  ?>
        </select></td>
        <td colspan="4" align="center">Game Type: 
          <select name="game_type">
            <option value="Snooker" <?php if (!(strcmp("Snooker", htmlentities($row_Scrs_Edit['game_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Snooker</option>
            <option value="Billiards" <?php if (!(strcmp("Billiards", htmlentities($row_Scrs_Edit['game_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Billiards</option>
        </select></td>
        <td colspan="3" align="center" nowrap="nowrap">Season: <?php echo $row_Scrs_Edit['scr_season']; ?></td>
        <td colspan="3" align="center">Capt
          <select name="captain_scrs">
            <option value="No" <?php if (!(strcmp("No", htmlentities($row_Scrs_Edit['captain_scrs'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
            <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_Scrs_Edit['captain_scrs'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
        </select></td>
        <td colspan="3" align="center">Team ID
        <input type="text" name="team_id" value="<?php echo htmlentities($row_Scrs_Edit['team_id'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
      </tr>
      <tr>
        <td width="92">scrs ID : <?php echo $row_Scrs_Edit['scrsID']; ?></td>
        <td width="40" align="center">1</td>
        <td width="40" align="center">2</td>
        <td width="40" align="center">3</td>
        <td width="40" align="center">4</td>
        <td width="40" align="center">5</td>
        <td width="40" align="center">6</td>
        <td width="40" align="center">7</td>
        <td width="40" align="center">8</td>
        <td width="40" align="center">9</td>
        <td width="40" align="center">10</td>
        <td width="40" align="center">11</td>
        <td width="40" align="center">12</td>
        <td width="40" align="center">13</td>
        <td width="40" align="center">14</td>
        <td width="40" align="center">15</td>
        <td width="40" align="center">16</td>
        <td width="40" align="center">17</td>
        <td width="40" align="center">18</td>
      </tr>
      <tr>
        <td align="right">Score</td>
        <td width="40" align="center"><input type="text" name="r01s" value="<?php echo htmlentities($row_Scrs_Edit['r01s'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r02s" value="<?php echo htmlentities($row_Scrs_Edit['r02s'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r03s" value="<?php echo htmlentities($row_Scrs_Edit['r03s'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r04s" value="<?php echo htmlentities($row_Scrs_Edit['r04s'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r05s" value="<?php echo htmlentities($row_Scrs_Edit['r05s'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r06s" value="<?php echo htmlentities($row_Scrs_Edit['r06s'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r07s" value="<?php echo htmlentities($row_Scrs_Edit['r07s'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r08s" value="<?php echo htmlentities($row_Scrs_Edit['r08s'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r09s" value="<?php echo htmlentities($row_Scrs_Edit['r09s'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r10s" value="<?php echo htmlentities($row_Scrs_Edit['r10s'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r11s" value="<?php echo htmlentities($row_Scrs_Edit['r11s'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r12s" value="<?php echo htmlentities($row_Scrs_Edit['r12s'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r13s" value="<?php echo htmlentities($row_Scrs_Edit['r13s'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r14s" value="<?php echo htmlentities($row_Scrs_Edit['r14s'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r15s" value="<?php echo htmlentities($row_Scrs_Edit['r15s'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r16s" value="<?php echo htmlentities($row_Scrs_Edit['r16s'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r17s" value="<?php echo htmlentities($row_Scrs_Edit['r17s'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r18s" value="<?php echo htmlentities($row_Scrs_Edit['r18s'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
      </tr>
      <tr>
        <td align="right">        Position</td>
        <td width="40" align="center"><input type="text" name="r01pos" value="<?php echo htmlentities($row_Scrs_Edit['r01pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r02pos" value="<?php echo htmlentities($row_Scrs_Edit['r02pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r03pos" value="<?php echo htmlentities($row_Scrs_Edit['r03pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r04pos" value="<?php echo htmlentities($row_Scrs_Edit['r04pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r05pos" value="<?php echo htmlentities($row_Scrs_Edit['r05pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r06pos" value="<?php echo htmlentities($row_Scrs_Edit['r06pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r07pos" value="<?php echo htmlentities($row_Scrs_Edit['r07pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r08pos" value="<?php echo htmlentities($row_Scrs_Edit['r08pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r09pos" value="<?php echo htmlentities($row_Scrs_Edit['r09pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r10pos" value="<?php echo htmlentities($row_Scrs_Edit['r10pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r11pos" value="<?php echo htmlentities($row_Scrs_Edit['r11pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r12pos" value="<?php echo htmlentities($row_Scrs_Edit['r12pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r13pos" value="<?php echo htmlentities($row_Scrs_Edit['r13pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r14pos" value="<?php echo htmlentities($row_Scrs_Edit['r14pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r15pos" value="<?php echo htmlentities($row_Scrs_Edit['r15pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r16pos" value="<?php echo htmlentities($row_Scrs_Edit['r16pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r17pos" value="<?php echo htmlentities($row_Scrs_Edit['r17pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td width="40" align="center"><input type="text" name="r18pos" value="<?php echo htmlentities($row_Scrs_Edit['r18pos'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">SF1</td>
        <td align="center"><input type="text" name="SF1" value="<?php echo htmlentities($row_Scrs_Edit['SF1'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td align="center">SF2</td>
        <td align="center"><input type="text" name="SF2" value="<?php echo htmlentities($row_Scrs_Edit['SF2'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td align="center">PF</td>
        <td align="center"><input type="text" name="PF" value="<?php echo htmlentities($row_Scrs_Edit['PF'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td align="center">GF</td>
        <td align="center"><input type="text" name="GF" value="<?php echo htmlentities($row_Scrs_Edit['GF'], ENT_COMPAT, 'utf-8'); ?>" size="3" /></td>
        <td align="center">&nbsp;</td>
        <td colspan="5" align="center">Final Sub
          <select name="final_sub">
            <option value="No" <?php if (!(strcmp("No", htmlentities($row_Scrs_Edit['final_sub'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
            <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_Scrs_Edit['final_sub'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
        </select></td>
      </tr>
    </table>
    <table align="center">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="submit" value="Update player" /></td>
      </tr>
    </table>
	<input type="hidden" name="MM_update" value="form1" />
  	<input type="hidden" name="scrsID" value="<?php echo $row_Scrs_Edit['scrsID']; ?>" />
    <input type="hidden" name="allocated_rp" value="<?php echo $row_grade_det['RP']; ?>" />
    <input type="hidden" name="scr_season" value="<?php echo $row_grade_det['season']; ?>" />
</form>
  <p>&nbsp;
  </p>
  <p>&nbsp;</p>
</center>
</body>
</html>
<?php
mysql_free_result($Scrs_Edit);

mysql_free_result($grades_S1);

mysql_free_result($grades_S2);

mysql_free_result($grade_det);
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>

<script type="text/javascript">

function doit(){     
	
	var tx = jQuery.noConflict();
        tx.ajax({
            url     : '<?PHP echo $editFormAction ?>',
            type    : tx('#form1').attr('method'),
            data    : tx('#form1').serialize(),
            success : function( data ) {
                        alert('Updated Succesfully!');
						location.reload(); 
                      },
            error   : function( xhr, err ) {
                        alert('Error');     
                      }
        }); 
        return false;
}

</script>