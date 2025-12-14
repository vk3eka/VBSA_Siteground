<?php require_once('../Connections/connvbsa.php'); ?>
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

$MM_restrictGoTo = "../page_error.php";
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
<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
  {
    //if (PHP_VERSION < 6) {
    //  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    //}

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

$grade = "-1";
if (isset($_GET['grade'])) {
  $grade = mysql_real_escape_string($_GET['grade']);
}

$comptype = "-1";
if (isset($_GET['comptype'])) {
  $comptype = mysql_real_escape_string($_GET['comptype']);
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = mysql_real_escape_string($_GET['season']);
}

mysql_select_db($database_connvbsa, $connvbsa);
if($comptype == 'Snooker')
{
  $query_ladder = "SELECT team_id, team_club, team_name, team_grade, match_pts_total AS Pts, team_perc, match_won_count AS W, match_drawn_count AS D, total_score AS F,  pts_against AS A, rounds_played AS P, count_byes AS B, Updated, Countback, comptype, team_season, audited FROM Team_entries WHERE team_grade='$grade' AND comptype='$comptype' AND include_draw ='Yes' AND team_cal_year = YEAR( CURDATE( ) ) GROUP BY team_id ORDER BY Pts DESC, team_perc DESC, W DESC, D DESC ";
}
elseif ($comptype == 'Billiards') {
  $query_ladder = "SELECT team_id, team_club, team_name, team_grade, match_pts_total AS Pts, team_perc, match_won_count AS W, match_drawn_count AS D, total_score AS F,  pts_against AS A, rounds_played AS P, count_byes AS B, Updated, Countback, comptype, team_season, audited FROM Team_entries WHERE team_grade='$grade' AND comptype='$comptype' AND include_draw ='Yes' AND team_cal_year = YEAR( CURDATE( ) ) GROUP BY team_id ORDER BY F DESC, team_perc DESC, W DESC, D DESC ";

}
//$query_ladder = "SELECT team_id, team_club, team_name, team_grade, match_pts_total AS Pts, team_perc, match_won_count AS W, match_drawn_count AS D, total_score AS F,  pts_against AS A, rounds_played AS P, count_byes AS B, Updated, Countback, comptype, team_season, audited FROM Team_entries WHERE team_grade='$grade' AND comptype='$comptype' AND include_draw ='Yes' AND team_cal_year = YEAR( CURDATE( ) ) GROUP BY team_id ORDER BY Pts DESC, team_perc DESC, W DESC, D DESC ";

//$query_ladder = "SELECT team_id, team_club, team_name, team_grade, match_pts_total AS Pts, team_perc, match_won_count AS W, match_drawn_count AS D, total_score AS F,  pts_against AS A, rounds_played AS P, count_byes AS B, Updated, Countback, comptype, team_season, audited FROM Team_entries WHERE team_grade='$grade' AND comptype='$comptype' AND include_draw ='Yes' AND team_cal_year = YEAR( CURDATE( ) ) GROUP BY team_id ORDER BY F DESC, team_perc DESC, W DESC, D DESC ";

//echo("<br>Query Ladder " . $query_ladder . "<br>");
$ladder = mysql_query($query_ladder, $connvbsa) or die(mysql_error());
$row_ladder = mysql_fetch_assoc($ladder);
$totalRows_ladder = mysql_num_rows($ladder);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Oddteams = "SELECT team_name, team_grade, Result_pos, Result_score, HB FROM Team_entries WHERE team_grade='$grade' AND team_cal_year = YEAR( CURDATE( ) ) AND (Result_pos % 2) <> 0 AND include_draw='Yes' ORDER BY Team_entries.Result_pos";
//echo("<br>Query Odd teams " . $query_Oddteams . "<br>"); // added sf1tot = 0 to display nothing after finals commence
$Oddteams = mysql_query($query_Oddteams, $connvbsa) or die(mysql_error());
$row_Oddteams = mysql_fetch_assoc($Oddteams);
$totalRows_Oddteams = mysql_num_rows($Oddteams);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Eventeams = "SELECT team_name, team_grade, Result_pos, Result_score, HB FROM Team_entries WHERE team_grade='$grade' AND team_cal_year = YEAR( CURDATE( ) ) AND (Result_pos % 2) = 0 AND include_draw='Yes' ORDER BY Team_entries.Result_pos";
//echo("<br>Query Even teams " . $query_Eventeams . "<br>"); // added sf1tot = 0 to display nothing after finals commence
$Eventeams = mysql_query($query_Eventeams, $connvbsa) or die(mysql_error());
$row_Eventeams = mysql_fetch_assoc($Eventeams);
$totalRows_Eventeams = mysql_num_rows($Eventeams);

mysql_select_db($database_connvbsa, $connvbsa);
$query_rounds = "SELECT comptype,  ROUND(((SUM(total_score)+SUM(scr_adjust))/(players*2))/COUNT(team_grade)*2,2) AS B_rds,    ROUND(((SUM(total_score)+SUM(scr_adjust))/(players*3))/COUNT(team_grade)*2,2) AS S_rds, COUNT(Team_entries.team_grade)AS teams FROM Team_entries WHERE Team_entries.team_grade ='$grade'  AND include_draw='Yes'  AND comptype='$comptype'  AND team_cal_year = YEAR( CURDATE( ) ) ";
//echo("<br>Query Rounds " . $query_rounds . "<br>");
$rounds = mysql_query($query_rounds, $connvbsa) or die("Cannot " . mysql_error());
$row_rounds = mysql_fetch_assoc($rounds);
$totalRows_rounds = mysql_num_rows($rounds);
//echo("<br>Query Rounds After<br>");

mysql_select_db($database_connvbsa, $connvbsa);
$query_breaks_10 = "SELECT members.FirstName, members.LastName, member_ID_brks, MAX( breaks.brk ) AS HB, recvd, finals_brk, season FROM members, breaks WHERE members.MemberID = breaks.member_ID_brks AND YEAR( recvd ) = YEAR( CURDATE( ) ) AND grade = '$grade' AND finals_brk = 'No' AND brk_type='$comptype' GROUP BY breaks.member_ID_brks ORDER BY HB DESC LIMIT 10";
//echo("<br>Query 10 Breaks " . $query_breaks_10 . "<br>");
$breaks_10 = mysql_query($query_breaks_10, $connvbsa) or die(mysql_error());
$row_breaks_10 = mysql_fetch_assoc($breaks_10);
$totalRows_breaks_10 = mysql_num_rows($breaks_10);

mysql_select_db($database_connvbsa, $connvbsa);
$query_pts_all = "SELECT scrs.MemberID, team_grade, FirstName, LastName, pts_won FROM scrs, members WHERE scrs.MemberID = members.MemberID AND scrs.MemberID <>1 AND current_year_scrs = YEAR( CURDATE( ) ) AND team_grade='$grade' AND pts_won >0 ORDER BY pts_won DESC LIMIT 10";
//echo("<br>Query All Points " . $query_pts_all . "<br>");
$pts_all = mysql_query($query_pts_all, $connvbsa) or die(mysql_error());
$row_pts_all = mysql_fetch_assoc($pts_all);
$totalRows_pts_all = mysql_num_rows($pts_all);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Adjust_notes = "SELECT scr_adjust, scr_adj_rd, adj_comment, team_grade FROM Team_entries WHERE adj_comment is not null AND Team_entries.team_grade ='$grade' AND team_cal_year = YEAR( CURDATE( ) ) ORDER BY Team_entries.scr_adj_rd";
//echo("<br>Query Adjust Notes " . $query_Adjust_notes . "<br>");
$Adjust_notes = mysql_query($query_Adjust_notes, $connvbsa) or die(mysql_error());
$row_Adjust_notes = mysql_fetch_assoc($Adjust_notes);
$totalRows_Adjust_notes = mysql_num_rows($Adjust_notes);


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 

</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

  <table width="800" border="0" align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td align="center" class="red_bold"> Ladder for <?php echo $grade; ?> (<?php echo $comptype; echo " ".$season; ?>) </td>
      <td align="right" class="greenbg"><a href="user_files/scrs_check_round_total.php?grade=<?php echo $grade; ?>" rel="facebox">Check round totals for <?php echo $grade; ?></a></td>
      <td align="right" class="greenbg"><a href="AA_scores_index_grades.php?season=<?php echo $season; ?>">return to index</a></td>
    </tr>
  </table>
  <table border="1" align="center" cellpadding="2" class="greenbg">
    <tr>
    <td align="center">Team ID</td>
      <td>Club</td>
      <td>Team Name</td>
      <td>Grade</td>
      <td nowrap="nowrap">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="center">Audited?</td>
      <?php
        if($comptype == 'Snooker')
        {
          echo("<td align='center'>Points</td>");
        }
      ?>
      <td align="center">%</td>
      <?php
        if($comptype == 'Snooker')
        {
          echo("<td align='center'>Won</td>");
          echo("<td align='center'>Drawn</td>");
        }
      ?>
      <!--<td align="center">Won</td>
      <td align="center">Drawn</td>-->
      <td align="center">For</td>
      <td align="center">Against</td>
      <td align="center">Played</td>
      <td align="center">Byes</td>
      <td align="center">Updated</td>
    </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_ladder['team_id']; ?></td>
        <td><?php echo $row_ladder['team_club']; ?></td>
        <td><?php echo $row_ladder['team_name']; ?></td>
        <td><?php echo $row_ladder['team_grade']; ?></td>
        <td nowrap="nowrap"><a href="user_files/scrs_player_edit_multiple.php?team_id=<?php echo $row_ladder['team_id']; ?>&season=<?php echo $season; ?>&grade=<?php echo $grade; ?>&comptype=<?php echo $comptype; ?>">Edit All Player Score</a></td>
        <td nowrap="nowrap"><a href="user_files/break_insert.php?grade=<?php echo $grade; ?>&amp;season=<?php echo $season; ?>&amp;comptype=<?php echo $comptype; ?>&amp;team_id=<?php echo $row_ladder['team_id']; ?>">Ins Breaks</a></td>
        <td nowrap="nowrap"><a href="scores_ladders_detail.php?team_id=<?php echo $row_ladder['team_id']; ?>&grade=<?php echo $grade; ?>&season=<?php echo $season; ?>&comptype=<?php echo $comptype; ?>">Detail</a></td>
        <td nowrap="nowrap"><a href="user_files/scrs_team_score_edit.php?team_id=<?php echo $row_ladder['team_id']; ?>&grade=<?php echo $grade; ?>&season=<?php echo $season; ?>&comptype=<?php echo $comptype; ?>">Finalise Score</a></td>
        <td width="15" align="center"><?php echo $row_ladder['audited']; ?></td>
        <?php
        if($comptype == 'Snooker')
        {
          echo("<td width='15' align='center'>" . $row_ladder['Pts'] . "</td>");
        }
        ?>
        <td width="15" align="center"><?php echo $row_ladder['team_perc']; ?></td>
        <?php
        if($comptype == 'Snooker')
        {
          echo("<td width='15' align='center'>" . $row_ladder['W'] . "</td>");
          echo("<td width='15' align='center'>" . $row_ladder['D'] . "</td>");
        }
        ?>
        <!--<td width="15" align="center"><?php echo $row_ladder['W']; ?></td>
        <td width="15" align="center"><?php echo $row_ladder['D']; ?></td>-->
        <td width="15" align="center"><?php echo $row_ladder['F']; ?></td>
        <td width="15" align="center"><?php echo $row_ladder['A']; ?></td>
        <td width="15" align="center"><?php echo $row_ladder['P']; ?></td>
        <td width="15" align="center"><?php echo $row_ladder['B']; ?></td>
        <td width="140" align="center"><?php echo $row_ladder['Updated']; ?></td>
    </tr>
    <?php } while ($row_ladder = mysql_fetch_assoc($ladder)); ?>
</table>
  <table width="800" border="0" align="center">
    <tr>
    <td width="116" align="right">Total Teams:</td>
      <td width="134"><?php echo $row_rounds['teams']; ?></td>
      <td width="169" align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td width="105" align="right">After Round: </td>
      <td width="66" align="center">
	  <?php 
	  if ($comptype=='Billiards')
	  echo $row_rounds['B_rds'];
	  else
	  echo $row_rounds['S_rds'];
	  ?>
      </td>
    </tr>
</table>
<table width="800" border="1" align="center">
  <tr>
    <td><table width="800" border="0">
      <tr>
        <td width="375"><table width="375" border="0">
          <tr>
            <td>Home Team</td>
            <td>Score</td>
            <td>High Break</td>
            <td>&nbsp;</td>
            </tr>
          <?php do { ?>
            <tr>
              <td><?php echo $row_Oddteams['team_name']; ?></td>
              <td><?php echo $row_Oddteams['Result_score']; ?></td>
              <td><?php echo $row_Oddteams['HB']; ?></td>
              <td>v</td>
              </tr>
            <?php } while ($row_Oddteams = mysql_fetch_assoc($Oddteams)); ?>
          </table></td>
        <td width="375"><table width="375" border="0">
          <tr>
            <td>Away Team</td>
            <td>Score</td>
            <td>High Break</td>
          </tr>
          <?php do { ?>
            <tr>
              <td><?php echo $row_Eventeams['team_name']; ?></td>
              <td><?php echo $row_Eventeams['Result_score']; ?></td>
              <td><?php echo $row_Eventeams['HB']; ?></td>
              </tr>
            <?php } while ($row_Eventeams = mysql_fetch_assoc($Eventeams)); ?>
          </table></td>
      </tr>
      </table></td>
  </tr>
</table>
<table width="800" border="1" align="center" class="greenbg">
  <tr>
    <td width="409">
      <table width="395" border="1"><!--Nested table to10 breaks -->
        <tr>
          <td colspan="3">Top 10 Breaks <a href="brks_byseason.php?season=<?php echo $season ?>&comptype=<?php echo $comptype; ?>">View All</a>

          </td>
        </tr>
        <tr>
          <td align="left">Member ID</td>
          <td align="left">Name</td>
          <td align="center">Break</td>
        </tr>
        <?php do { ?>
          <tr>
            <td align="left"><?php echo $row_breaks_10['member_ID_brks']; ?></td>
            <td align="left"><?php echo $row_breaks_10['FirstName']; ?> <?php echo $row_breaks_10['LastName']; ?></td>
            <td align="center"><?php echo $row_breaks_10['HB']; ?></td>
          </tr>
          <?php } while ($row_breaks_10 = mysql_fetch_assoc($breaks_10)); ?>
      </table>
    </td><!--End nested table -->
    <td width="363">
    <!--Nested table - points -->
    <table width="395" border="1">
      <tr>
        <td colspan="3">
		<?php if($comptype=='Billiards') { echo "Top 10 Most Game Points  ";  ?> 
        	<a href="points_billiards.php?season=<?php echo $season ?>">View All</a>
		<?php } else echo ""; ?>
        <?php if($comptype=='Snooker') { echo "Top 10 Most Frames  ";  ?> 
        	<a href="points_snooker.php?season=<?php echo $season ?>">View All</a>
		<?php } else echo ""; ?>
        </td>
        </tr>
      <tr>
        <td align="center">Memb ID</td>
        <td>Name</td>
        <td align="center"><?php if($comptype=='Snooker') echo "Frames"; else echo "Points"; ?></td>
      </tr>
	  <?php do { ?> 
    <tr>
      <td align="center"><?php echo $row_pts_all['MemberID']; ?></td>
      <td nowrap="nowrap"><?php echo $row_pts_all['FirstName']; ?> <?php echo $row_pts_all['LastName']; ?></td>
      <td align="center"><?php echo $row_pts_all['pts_won']; ?></td>
      </tr>
	  <?php } while ($row_pts_all = mysql_fetch_assoc($pts_all));?>
     
</table><!--End nested table -->
    
    </td>
  </tr>
</table>
<table width="1000" border="1" align="center">
  <tr>
    <td colspan="4">Adjustments to this ladder and explanations.</td>
  </tr>
  <tr>
    <td width="40">Grade</td>
      <td width="40">Round</td>
      <td width="40">Pts adj.</td>
      <td>Explanation</td>
  </tr>
  <?php do { ?>
    <tr>
      <td width="40"><?php echo $row_Adjust_notes['team_grade']; ?></td>
      <td width="40"><?php echo $row_Adjust_notes['scr_adj_rd']; ?></td>
      <td width="40"><?php echo $row_Adjust_notes['scr_adjust']; ?></td>
      <td width="382"><?php echo $row_Adjust_notes['adj_comment']; ?></td>
    </tr>
    <?php } while ($row_Adjust_notes = mysql_fetch_assoc($Adjust_notes)); ?>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</center>
</body>
</html>
<?php

?>