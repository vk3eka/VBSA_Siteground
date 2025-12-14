<?php require_once('../Connections/connvbsa.php'); 

error_reporting(0);

if (!isset($_SESSION)) {
  session_start();
}

if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}

$grade = "-1";
if (isset($_GET['grade'])) {
  $grade = $_GET['grade'];
}

$year = date("Y");
//echo($year . "<br>");

$page = "../scores_index_finals.php?grade=$grade&comptype=$comptype&season=$season";
$_SESSION['page'] = $page;

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

$comptype = "-1";
if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}

$grade = "-1";
if (isset($_GET['grade'])) {
  $grade = $_GET['grade'];
}

mysql_select_db($database_connvbsa, $connvbsa);

if($comptype == 'Billiards')
{
  $query_ladder = "Select team_id, team_club, team_name, team_grade, match_pts_total AS Pts, team_perc, match_won_count AS W, match_drawn_count AS D, total_score AS F,  pts_against AS A, rounds_played AS P, count_byes AS B, Updated, Countback, comptype, team_season, audited, scrs_for_finals, scrs_against_finals, scrs_percent_finals FROM Team_entries WHERE team_grade='$grade' AND comptype='$comptype' AND audited ='Yes' AND team_cal_year = YEAR( CURDATE( ) ) AND team_name != 'Bye' GROUP BY team_id  ORDER BY F DESC, scrs_percent_finals DESC";
}
else
{
  $query_ladder = "Select team_id, team_club, team_name, team_grade, match_pts_total AS Pts, team_perc, match_won_count AS W, match_drawn_count AS D, total_score AS F,  pts_against AS A, rounds_played AS P, count_byes AS B, Updated, Countback, comptype, team_season, audited FROM Team_entries WHERE team_grade='$grade' AND comptype='$comptype' AND audited ='Yes' AND team_cal_year = YEAR( CURDATE( ) ) AND team_name != 'Bye' GROUP BY team_id ORDER BY Pts DESC, team_perc DESC, W DESC, D DESC ";
}
//echo("Ladder " . $query_ladder . "<br>");
$ladder = mysql_query($query_ladder, $connvbsa) or die(mysql_error());
$row_ladder = mysql_fetch_assoc($ladder);
$totalRows_ladder = mysql_num_rows($ladder);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_finals_brks = "SELECT members.FirstName, members.LastName, member_ID_brks, brk, recvd, finals_brk, season, Break_ID FROM breaks LEFT JOIN members ON members.MemberID = breaks.member_ID_brks WHERE YEAR( recvd ) = YEAR( CURDATE( ) ) AND grade = '$grade' AND finals_brk = 'Yes' AND brk_type='$comptype' AND season='$season' AND brk != 0 ORDER BY brk DESC";
//echo("Finals Breaks " . $query_finals_brks . "<br>");
$finals_brks = mysql_query($query_finals_brks, $connvbsa) or die(mysql_error());
$row_finals_brks = mysql_fetch_assoc($finals_brks);
$totalRows_finals_brks = mysql_num_rows($finals_brks);

if($comptype == 'Snooker')
{
  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T1 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = YEAR( CURDATE( ) ) ORDER BY `match_pts_total` DESC, `team_perc` DESC, `match_won_count` DESC, `match_drawn_count` DESC, `Result_pos` DESC Limit 1";
  //echo("Finals T1 " . $query_final_T1 . "<br>");
  $final_T1 = mysql_query($query_final_T1, $connvbsa) or die(mysql_error());
  $row_final_T1 = mysql_fetch_assoc($final_T1);
  $totalRows_final_T1 = mysql_num_rows($final_T1);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T2 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = YEAR( CURDATE( ) ) ORDER BY `match_pts_total` DESC, `team_perc` DESC, `match_won_count` DESC, `match_drawn_count` DESC, `Result_pos` DESC Limit 1,1";
  //echo("Finals T2 " . $query_final_T2 . "<br>");
  $final_T2 = mysql_query($query_final_T2, $connvbsa) or die(mysql_error());
  $row_final_T2 = mysql_fetch_assoc($final_T2);
  $totalRows_final_T2 = mysql_num_rows($final_T2);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T3 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = YEAR( CURDATE( ) ) ORDER BY `match_pts_total` DESC, `team_perc` DESC, `match_won_count` DESC, `match_drawn_count` DESC, `Result_pos` DESC Limit 2,1";
  //echo("Finals T3 " . $query_final_T3 . "<br>");
  $final_T3 = mysql_query($query_final_T3, $connvbsa) or die(mysql_error());
  $row_final_T3 = mysql_fetch_assoc($final_T3);
  $totalRows_final_T3 = mysql_num_rows($final_T3);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T4 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = YEAR( CURDATE( ) ) ORDER BY `match_pts_total` DESC, `team_perc` DESC, `match_won_count` DESC, `match_drawn_count` DESC, `Result_pos` DESC Limit 3,1";
  //echo("Finals T4 " . $query_final_T4 . "<br>");
  $final_T4 = mysql_query($query_final_T4, $connvbsa) or die(mysql_error());
  $row_final_T4 = mysql_fetch_assoc($final_T4);
  $totalRows_final_T4 = mysql_num_rows($final_T4);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_SF1win = "SELECT team_id, team_name, team_grade, GFtot, GF_pts, SF1tot, SF1_pts FROM Team_entries WHERE Team_entries.team_grade ='$grade' AND  team_cal_year = YEAR( CURDATE( ) ) AND SF1tot is not null ORDER BY SF1tot DESC, SF1_pts DESC   LIMIT 1";
  //echo("Semi Finals Wins 1 " . $query_SF1win . "<br>");
  $SF1win = mysql_query($query_SF1win, $connvbsa) or die(mysql_error());
  $row_SF1win = mysql_fetch_assoc($SF1win);
  $totalRows_SF1win = mysql_num_rows($SF1win);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_SF2win = "SELECT team_id, team_name, team_grade, GFtot, GF_pts, SF2tot, SF2_pts FROM Team_entries WHERE Team_entries.team_grade ='$grade' AND  team_cal_year = YEAR( CURDATE( ) ) AND SF2tot is not null ORDER BY SF2tot DESC, SF2_pts DESC   LIMIT 1";
  //echo("Semi Finals Wins 2 " . $query_SF2win . "<br>");
  $SF2win = mysql_query($query_SF2win, $connvbsa) or die(mysql_error());
  $row_SF2win = mysql_fetch_assoc($SF2win);
  $totalRows_SF2win = mysql_num_rows($SF2win);

}
if($comptype == 'Billiards')
{
  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T1 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = YEAR( CURDATE( ) )  ORDER BY total_score DESC, scrs_percent_finals DESC  Limit 1";
  //echo("Finals T1 " . $query_final_T1 . "<br>");
  $final_T1 = mysql_query($query_final_T1, $connvbsa) or die(mysql_error());
  $row_final_T1 = mysql_fetch_assoc($final_T1);
  $totalRows_final_T1 = mysql_num_rows($final_T1);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T2 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = YEAR( CURDATE( ) )  ORDER BY total_score DESC, scrs_percent_finals DESC  Limit 1,1";
  //echo("Finals T2 " . $query_final_T2 . "<br>");
  $final_T2 = mysql_query($query_final_T2, $connvbsa) or die(mysql_error());
  $row_final_T2 = mysql_fetch_assoc($final_T2);
  $totalRows_final_T2 = mysql_num_rows($final_T2);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T3 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = YEAR( CURDATE( ) )  ORDER BY total_score DESC, scrs_percent_finals DESC  Limit 2,1";
  //echo("Finals T3 " . $query_final_T3 . "<br>");
  $final_T3 = mysql_query($query_final_T3, $connvbsa) or die(mysql_error());
  $row_final_T3 = mysql_fetch_assoc($final_T3);
  $totalRows_final_T3 = mysql_num_rows($final_T3);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T4 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = YEAR( CURDATE( ) )  ORDER BY total_score DESC, scrs_percent_finals DESC  Limit 3,1";
  //echo("Finals T4 " . $query_final_T4 . "<br>");
  $final_T4 = mysql_query($query_final_T4, $connvbsa) or die(mysql_error());
  $row_final_T4 = mysql_fetch_assoc($final_T4);
  $totalRows_final_T4 = mysql_num_rows($final_T4);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T5 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = YEAR( CURDATE( ) ) ORDER BY total_score DESC, scrs_percent_finals DESC Limit 4,1";
  //echo("Finals T5 " . $query_final_T5 . "<br>");
  $final_T5 = mysql_query($query_final_T5, $connvbsa) or die(mysql_error());
  $row_final_T5 = mysql_fetch_assoc($final_T5);
  $totalRows_final_T5 = mysql_num_rows($final_T5);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T6 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = YEAR( CURDATE( ) ) ORDER BY total_score DESC, scrs_percent_finals DESC  Limit 5,1";
  //echo("Finals T6 " . $query_final_T6 . "<br>");
  $final_T6 = mysql_query($query_final_T6, $connvbsa) or die(mysql_error());
  $row_final_T6 = mysql_fetch_assoc($final_T6);
  $totalRows_final_T6 = mysql_num_rows($final_T6);
//}
//..............................
  //$query_EF1win = "SELECT * FROM Team_entries WHERE Team_entries.team_grade ='PB(1)' AND team_cal_year = YEAR( CURDATE( ) ) AND SF2tot is not null ORDER BY SF2tot ASC, SF2_pts ASC LIMIT 1";
 //mysql_select_db($database_connvbsa, $connvbsa);
  $query_EF1win = "SELECT * FROM Team_entries WHERE Team_entries.team_grade ='$grade' AND  team_cal_year = YEAR( CURDATE( ) ) AND EF1tot is not null ORDER BY EF1tot DESC, EF1_pts DESC LIMIT 1";
  //echo("Semi Finals Wins 1 " . $query_EF1win . "<br>");
  $EF1win = mysql_query($query_EF1win, $connvbsa) or die(mysql_error());
  $row_EF1win = mysql_fetch_assoc($EF1win);
  $totalRows_EF1win = mysql_num_rows($EF1win);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_EF2win = "SELECT * FROM Team_entries WHERE Team_entries.team_grade ='$grade' AND  team_cal_year = YEAR( CURDATE( ) ) AND EF2tot is not null ORDER BY EF2tot DESC, EF2_pts DESC LIMIT 1";
  //echo("Semi Finals Wins 2 " . $query_SF2win . "<br>");
  $EF2win = mysql_query($query_EF2win, $connvbsa) or die(mysql_error());
  $row_EF2win = mysql_fetch_assoc($EF2win);
  $totalRows_EF2win = mysql_num_rows($EF2win); 
//...............................
  
  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_SF1win = "SELECT * FROM Team_entries WHERE Team_entries.team_grade ='$grade' AND  team_cal_year = YEAR( CURDATE( ) ) AND SF1tot is not null ORDER BY SF1tot DESC, SF1_pts DESC LIMIT 1";
  //echo("Semi Finals Wins 1 " . $query_SF1win . "<br>");
  $SF1win = mysql_query($query_SF1win, $connvbsa) or die(mysql_error());
  $row_SF1win = mysql_fetch_assoc($SF1win);
  $totalRows_SF1win = mysql_num_rows($SF1win);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_SF2win = "SELECT * FROM Team_entries WHERE Team_entries.team_grade ='$grade' AND  team_cal_year = YEAR( CURDATE( ) ) AND SF2tot is not null ORDER BY SF2tot DESC, SF2_pts DESC LIMIT 1";
  //echo("Semi Finals Wins 2 " . $query_SF2win . "<br>");
  $SF2win = mysql_query($query_SF2win, $connvbsa) or die(mysql_error());
  $row_SF2win = mysql_fetch_assoc($SF2win);
  $totalRows_SF2win = mysql_num_rows($SF2win);
}
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

<table width="900" border="0" align="center">
  <tr>
    <td align="center" class="greenbg">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="greenbg"><span class="red_bold">Finals teams for : <?php echo $grade; ?> - (<?php echo $comptype." ".$season; ?> )</span></td>
  </tr>
  <tr>
    <td align="center" class="greenbg"><a href="AA_scores_index_grades.php?season=<?php echo $season; ?>">Return to Grade list</a></td>    
  </tr>
  <tr>
    <td align="center" class="red_bold"></tr>
  <tr>
    <td align="center"><p class="pagetitle"> Snooker: If there is a tie for a final placing go to Update Rounds on the ladder page and set the order of finishing for ALL teams. Final placings when teams are tied on points should be decided by the results between the teams involved during the course of the season in their head to head matches.</p>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"> <p class="pagetitle">Billiards: if there is a draw on match points then total points will decide the winning team. Click the &quot;Win on points&quot; link along side the winning team and check the checkbox against &quot;Points Win&quot; for the appropriate final</p></td>  
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
</table>

<table border="1" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td align="center">Team ID</td>
    <td>Club</td>
    <td>Team Name</td>
    <td>Grade</td>
    <td align="center"><?php echo $row_ladder['comptype']; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <?php if($row_ladder['comptype'] == 'Billiards') { ?>
    <td>&nbsp;</td>
    <?php } ?>
    <td align="center">Audited</td>
    <td align="center">Updated</td>
    <?php if($row_ladder['comptype'] == 'Billiards') { ?>
    <td align="center">Final Position (Points)</td>
    <td align="center">Scores For</td>
    <td align="center">Scores Against</td>
    <td align="center">Scores Percentage</td>
    <?php } ?>
  </tr>
  <?php 
  $i = 0;
  //$arrResult = [];
  do 
  { 
    /*
    // calculate finals position if match points won are the same for two or more teams
    $query_scores_for = "Select SUM(score_1) as total_scores_1 FROM tbl_scoresheet WHERE team_id =" . $row_ladder['team_id'] . " AND year = YEAR( CURDATE( ) ) AND season = '" . $season . "'";
    //echo("Sum scores " . $query_scores_for . "<br>");
    $scores_for = mysql_query($query_scores_for, $connvbsa) or die(mysql_error());
    $row_scores_for = mysql_fetch_assoc($scores_for);
    $total_scores_for = $row_scores_for['total_scores_1'];
    //echo("Total For " . $total_scores_for . "<br>");
    // get teams played against
     $query_teams_against = "Select distinct opposition FROM tbl_scoresheet WHERE team_id =" . $row_ladder['team_id'] . " AND year = YEAR( CURDATE( ) ) AND season = '" . $season . "'";
    //echo("Opposition " . $query_teams_against . "<br>");
    $teams_against = mysql_query($query_teams_against, $connvbsa) or die(mysql_error());
    $total_scores_against = 0;
    while($row_teams_against = mysql_fetch_assoc($teams_against))
    {
      //echo("Teams Against " . $row_teams_against['opposition'] . "<br>");
      $query_scores_against = "Select SUM(score_1) as total_scores_1 FROM tbl_scoresheet WHERE team = '" . $row_teams_against['opposition'] . "' AND opposition = '" . $row_ladder['team_name'] . "' AND year = YEAR( CURDATE( ) ) AND season = '" . $season . "'";
      //echo("Sum scores against " . $query_scores_against . "<br>");
      $scores_against = mysql_query($query_scores_against, $connvbsa) or die(mysql_error());
      $row_scores_against = mysql_fetch_assoc($scores_against);
      $total_scores_against = ($row_scores_against['total_scores_1'] + $total_scores_against);
    }
    //echo("Total Against " . $total_scores_against . "<br>");
    $team_percentage = (($total_scores_for/$total_scores_against)*100);
    //echo("Total Percent " . $team_percentage . "<br>");
    // finish calculation
  
    $arrKeys[$i] = array(
      'Points'=>$row_ladder['F'], 
      'Percent'=>number_format($team_percentage, 2), 
      'Team'=>$row_ladder['team_name'], 
      'Grade'=>$row_ladder['team_grade'], 
      'CompType'=>$row_ladder['comptype'], 
      'Audited'=>$row_ladder['audited'], 
      'Updated'=>$row_ladder['Updated'], 
      'Club'=>$row_ladder['team_club'], 
      'ScoresFor'=>number_format($total_scores_for, 2), 
      'ScoresAgainst'=>number_format($total_scores_against, 2), 
      'TeamID'=>$row_ladder['team_id'],
    );
    $i++;

    */

    if($i < 6)
    {
?>
    <tr>
      <td align="center"><?php echo $row_ladder['team_id']; ?></td>
      <td><?php echo $row_ladder['team_club']; ?></td>
      <td><?php echo $row_ladder['team_name']; ?></td>
      <td><?php echo $row_ladder['team_grade']; ?></td>
      <td nowrap="nowrap"><a href="user_files/scrs_finals_edit_scores.php?grade=<?php echo $row_ladder['team_grade']; ?>&season=<?php echo $season; ?>& comptype=<?php echo $row_ladder['comptype']; ?>&team_id=<?php echo $row_ladder['team_id']; ?>">Edit all players finals scores</a></td>
      <td nowrap="nowrap"><a href="scores_index_finals_detail.php?grade=<?php echo $row_ladder['team_grade']; ?> &season=<?php echo $season; ?> & comptype=<?php echo $row_ladder['comptype']; ?>&team_id=<?php echo $row_ladder['team_id']; ?>">Detail</a></td>
      
      <td nowrap="nowrap"><a href="user_files/break_insert_finals.php?grade=<?php echo $row_ladder['team_grade']; ?> &amp;season=<?php echo $season; ?> &amp; comptype=<?php echo $row_ladder['comptype']; ?>&amp;team_id=<?php echo $row_ladder['team_id']; ?>">Ins Breaks</a></td>
      <td>
      <?php if($row_ladder['comptype']=='Billiards') { ?>
      <a href="user_files/scrs_finals_edit_bill_pts_win.php?grade=<?php echo $row_ladder['team_grade']; ?>&season=<?php echo $season; ?>& comptype=<?php echo $row_ladder['comptype']; ?>&team_id=<?php echo $row_ladder['team_id']; ?>" >Win on points</a>
      <?php } else echo '&nbsp;'; ?>
      </td>
        <td align="center"><?php echo $row_ladder['audited']; ?></td>
        <td align="center"><?php echo $row_ladder['Updated']; ?></td>

        <td align="center"><?php echo $row_ladder['F']; ?></td>
        <td align="center"><?php echo $row_ladder['scrs_for_finals']; ?></td>
        <td align="center"><?php echo $row_ladder['scrs_against_finals']; ?></td>
        <td align="center"><?php echo $row_ladder['scrs_percent_finals']; ?></td>
    </tr>

    <?php
    $i++;
    }
  } while ($row_ladder = mysql_fetch_assoc($ladder)); 
/*
  $points  = array_column($arrKeys, 'Points');
  $percent = array_column($arrKeys, 'Percent');
  array_multisort($points, SORT_DESC, $percent, SORT_DESC, $arrKeys);
  
  $i = 0;
  foreach($arrKeys as $array)
  {
    if($i < 6)
    {
      //echo("Team " . $array['Team'] . ", Points " . $array['Points'] . ", Percentage " . $array['Percent'] . "<br>");

      echo("<tr>");
      echo("<td align='center'>" . $array['TeamID'] . "</td>");
      echo("<td>" . $array['Club'] . "</td>");
      echo("<td>" . $array['Team'] . "</td>");
      echo("<td>" . $array['Grade'] . "</td>");
      echo("<td nowrap='nowrap'><a href='user_files/scrs_finals_edit_scores.php?grade=" . $array['Grade'] . "&season=" . $season . "&comptype=" . $array['CompType'] . "&team_id=" . $array['TeamID'] . "'>Edit all players finals scores</a></td>");
      echo("<td nowrap='nowrap'><a href='scores_index_finals_detail.php?grade=" . $array['Grade'] . "&season=" . $season . "&comptype=" . $array['CompType'] . "&team_id=" . $array['TeamID'] . "'>Detail</a></td>");
      echo("<td nowrap='nowrap'><a href='user_files/break_insert_finals.php?grade=" . $array['Grade'] . "&season=" . $season . "&comptype=" . $array['CompType'] . "&team_id=" . $array['TeamID'] . "'>Ins Breaks</a></td>");
      if($array['CompType'] == 'Billiards') 
      {
        echo("<td><a href='user_files/scrs_finals_edit_bill_pts_win.php?grade=" . $array['Grade'] . "&season=" . $season . "&comptype=" . $array['CompType'] . "&team_id=" . $array['TeamID'] . "'>Win on points</a></td>");
      }
      echo("<td align='center'>" . $array['Audited'] . "</td>");
      echo("<td align=center>" . $array['Updated'] . "</td>");
      if($array['CompType'] == 'Billiards') 
      {
        echo("<td align='center'><input type='text' id='points' value='" . $array['Points'] . "' style='width:50px'></td>");
        echo("<td align='center'><input type='text' id='calc_finals_pos' value='" . $array['ScoresFor'] . "' style='width:60px'></td>");
        echo("<td align='center'><input type='text' id='calc_finals_pos' value='" . $array['ScoresAgainst'] . "' style='width:60px'></td>");
        echo("<td align='center'><input type='text' id='calc_finals_pos' value='" . $array['Percent'] . "' style='width:70px'></td>");
      }
      echo("</tr>");
    }
    $i++;

  } 
  */
  ?>
</table>

<table width="830" align="center">
  <tr>
  <td>&nbsp;</td>
  </tr>

</table>
<table width="800" border="1" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="8" class="italic">Finals Fixture / Results (* = win on points)</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">Team ID</td>
    <td>Team Name</td>
    <td align="center">Score</td>
    <td align="center">&nbsp;</td>
    <td align="center">Team ID</td>
    <td>Team Name</td>
    <td align="center">Score</td>
  </tr>
  <?php 
  if($comptype == 'Billiards')
  {
    if(($year == 2025) AND ($season == 'S1'))
    {
  ?>
   <tr>
    <td class="italic">EF1 </td>
    <td align="center" nowrap="nowrap"><?php echo $row_final_T3['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_final_T3['team_name']; ?></td>
    <td align="center" ><?php echo $row_final_T3['EF1tot']; if ($row_final_T3['EF1_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
    <td align="center" nowrap="nowrap"><?php echo $row_final_T5['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_final_T5['team_name']; ?></td>
    <td align="center"><?php echo $row_final_T5['EF1tot']; if ($row_final_T5['EF1_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">EF2 </td>
    <td align="center" nowrap="nowrap"><?php echo $row_final_T4['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_final_T4['team_name']; ?></td>
    <td align="center"><?php echo $row_final_T4['EF2tot']; if ($row_final_T4['EF2_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
    <td align="center" nowrap="nowrap"><?php echo $row_final_T6['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_final_T6['team_name']; ?></td>
    <td align="center"><?php echo $row_final_T6['EF2tot']; if ($row_final_T6['EF2_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">SF1 </td>
    <td align="center" nowrap="nowrap"><?php echo $row_final_T1['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_final_T1['team_name']; ?></td>
    <td align="center" ><?php echo $row_final_T1['SF1tot']; if ($row_final_T1['SF1_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
    <td align="center" nowrap="nowrap"><?php echo $row_EF1win['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_EF1win['team_name']; ?></td>
    <td align="center"><?php echo $row_EF1win['SF1tot']; if ($row_EF1win['SF1_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">SF2 </td>
    <td align="center" nowrap="nowrap"><?php echo $row_final_T2['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_final_T2['team_name']; ?></td>
    <td align="center"><?php echo $row_final_T2['SF2tot']; if ($row_final_T2['SF2_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
    <td align="center" nowrap="nowrap"><?php echo $row_EF2win['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_EF2win['team_name']; ?></td>
    <td align="center"><?php echo $row_EF2win['SF2tot']; if ($row_EF2win['SF2_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">GF </td>
    <td align="center" nowrap="nowrap"><?php echo $row_SF1win['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_SF1win['team_name']; ?></td>
    <td align="center"><?php echo $row_SF1win['GFtot']; if ($row_SF1win['GF_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
    <td align="center" nowrap="nowrap"><?php echo $row_SF2win['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_SF2win['team_name']; ?></td>
    <td align="center"><?php echo $row_SF2win['GFtot']; if ($row_SF2win['GF_pts']==1)echo "*"; else echo "";  ?></td>
  </tr>
<?php }
      else
      {
?>
  <tr>
    <td class="italic">EF1 </td>
    <td align="center" nowrap="nowrap"><?php echo $row_final_T3['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_final_T3['team_name']; ?></td>
    <td align="center" ><?php echo $row_final_T3['EF1tot']; if ($row_final_T3['EF1_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
    <td align="center" nowrap="nowrap"><?php echo $row_final_T6['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_final_T6['team_name']; ?></td>
    <td align="center"><?php echo $row_final_T6['EF1tot']; if ($row_final_T6['EF1_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">EF2 </td>
    <td align="center" nowrap="nowrap"><?php echo $row_final_T4['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_final_T4['team_name']; ?></td>
    <td align="center"><?php echo $row_final_T4['EF2tot']; if ($row_final_T4['EF2_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
    <td align="center" nowrap="nowrap"><?php echo $row_final_T5['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_final_T5['team_name']; ?></td>
    <td align="center"><?php echo $row_final_T5['EF2tot']; if ($row_final_T5['EF2_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">SF1 </td>
    <td align="center" nowrap="nowrap"><?php echo $row_final_T1['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_final_T1['team_name']; ?></td>
    <td align="center" ><?php echo $row_final_T1['SF1tot']; if ($row_final_T1['SF1_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
    <td align="center" nowrap="nowrap"><?php echo $row_EF2win['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_EF2win['team_name']; ?></td>
    <td align="center"><?php echo $row_EF2win['SF1tot']; if ($row_EF2win['SF1_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">SF2 </td>
    <td align="center" nowrap="nowrap"><?php echo $row_final_T2['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_final_T2['team_name']; ?></td>
    <td align="center"><?php echo $row_final_T2['SF2tot']; if ($row_final_T2['SF2_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
    <td align="center" nowrap="nowrap"><?php echo $row_EF1win['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_EF1win['team_name']; ?></td>
    <td align="center"><?php echo $row_EF1win['SF2tot']; if ($row_EF1win['SF2_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">GF </td>
    <td align="center" nowrap="nowrap"><?php echo $row_SF1win['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_SF1win['team_name']; ?></td>
    <td align="center"><?php echo $row_SF1win['GFtot']; if ($row_SF1win['GF_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
    <td align="center" nowrap="nowrap"><?php echo $row_SF2win['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_SF2win['team_name']; ?></td>
    <td align="center"><?php echo $row_SF2win['GFtot']; if ($row_SF2win['GF_pts']==1)echo "*"; else echo "";  ?></td>
  </tr>
  <?php 
    }
      }
      else if($comptype == 'Snooker')
      {
  ?>
  <tr>
    <td class="italic">SF1 </td>
    <td align="center" nowrap="nowrap"><?php echo $row_final_T1['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_final_T1['team_name']; ?></td>
    <td align="center" ><?php echo $row_final_T1['SF1tot']; if ($row_final_T1['SF1_pts']==1)echo "*"; else echo "";?></td>
    <td align="center">v</td>
    <td align="center" nowrap="nowrap"><?php echo $row_final_T4['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_final_T4['team_name']; ?></td>
    <td align="center"><?php echo $row_final_T4['SF1tot']; if ($row_final_T4['SF2_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">SF2 </td>
    <td align="center" nowrap="nowrap"><?php echo $row_final_T2['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_final_T2['team_name']; ?></td>
    <td align="center"><?php echo $row_final_T2['SF2tot']; if ($row_final_T2['SF2_pts']==1)echo "*"; else echo "";?></td>
    <td align="center">v</td>
    <td align="center" nowrap="nowrap"><?php echo $row_final_T3['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_final_T3['team_name']; ?></td>
    <td align="center"><?php echo $row_final_T3['SF2tot']; if ($row_final_T3['SF2_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">GF </td>
    <td align="center" nowrap="nowrap"><?php echo $row_SF1win['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_SF1win['team_name']; ?></td>
    <td align="center"><?php echo $row_SF1win['GFtot']; if ($row_SF1win['GF_pts']==1)echo "*"; else echo "";?></td>
    <td align="center">v</td>
    <td align="center" nowrap="nowrap"><?php echo $row_SF2win['team_id']; ?></td>
    <td nowrap="nowrap"><?php echo $row_SF2win['team_name']; ?></td>
    <td align="center"><?php echo $row_SF2win['GFtot']; if ($row_SF2win['GF_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <?php
      }
  ?>
</table>

<table align="center">
  <tr>
    <th>&nbsp;</th>
  </tr>
  <tr>
    <th>*Note: scores will not appear until recalculated</th>
  </tr>
  <tr>
    <td align="center" class="greenbg">&nbsp;</td>
  </tr>
</table>
<table width="395" align="center">
  <tr>
    <td colspan="4" align="center">Finals Breaks</td>
  </tr>
  <tr>
    <td>Member ID</td>
    <td>Name</td>
    <td>Break</td>
    <td>&nbsp;</td>
  </tr>
  <?php do { ?>
  <tr>
    <td><?php echo $row_finals_brks['member_ID_brks']; ?></td>
    <td><?php echo $row_finals_brks['FirstName']; ?> <?php echo $row_finals_brks['LastName']; ?></td>
    <td><?php echo $row_finals_brks['brk']; ?></td>
    <td><a href="user_files/break_edit.php?brk_id=<?php echo $row_finals_brks['Break_ID']; ?>" title="Edit"><img src="../Admin_Images/edit_butt.fw.png" width="20" /></a></td>
  </tr>
  <?php } while ($row_finals_brks = mysql_fetch_assoc($finals_brks)); ?>
    <input name="hiddenField" type="hidden" id="hiddenField" value="<?php echo $row_finals_brks['MemberID']; ?>" /> 
</table>
<p>&nbsp;</p>
</body>
</html>
