<?php require_once('../Connections/connvbsa.php'); 

error_reporting(0);

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

$grade = "-1";
if (isset($_GET['grade'])) {
  $grade = mysql_real_escape_string($_GET['grade']);
}

$year = "-1";
if (isset($_GET['year'])) {
  $year = mysql_real_escape_string($_GET['year']);
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = mysql_real_escape_string($_GET['season']);
}

$comptype = "-1";
if (isset($_GET['comptype'])) {
  $comptype = mysql_real_escape_string($_GET['comptype']);
}
	
mysql_select_db($database_connvbsa, $connvbsa);

$query_updated = "SELECT MAX( updated ) AS updated FROM Team_entries WHERE team_grade = '$grade'";
//echo("Updated " . $query_updated . "<br>");
$updated = mysql_query($query_updated, $connvbsa) or die(mysql_error());
$row_updated = mysql_fetch_assoc($updated);
$totalRows_updated = mysql_num_rows($updated);

if($comptype == 'Snooker')
{
  $query_ladder = "SELECT team_id, team_club, team_name, team_grade, match_pts_total AS Pts, team_perc, match_won_count AS W, match_drawn_count AS D, total_score AS F,  pts_against AS A, rounds_played AS P, count_byes AS B, Updated, Countback, comptype, team_season, audited FROM Team_entries WHERE team_grade='$grade' AND comptype='$comptype' AND include_draw ='Yes' AND team_cal_year = '$year' AND team_name != 'Bye' GROUP BY team_id ORDER BY Pts DESC, team_perc DESC, W DESC, D DESC ";
}
elseif ($comptype == 'Billiards') {
  $query_ladder = "SELECT team_id, team_club, team_name, team_grade, match_pts_total AS Pts, team_perc, match_won_count AS W, match_drawn_count AS D, total_score AS F,  pts_against AS A, rounds_played AS P, count_byes AS B, Updated, Countback, comptype, team_season, audited, scrs_for_finals, scrs_against_finals, scrs_percent_finals FROM Team_entries WHERE team_grade='$grade' AND comptype='$comptype' AND include_draw ='Yes' AND team_cal_year = '$year' AND team_name != 'Bye' GROUP BY team_id ORDER BY F DESC, scrs_percent_finals DESC";

//ORDER BY F DESC, team_perc DESC, W DESC, D DESC
  //ORDER BY F DESC, scrs_percent_finals DESC
}

//echo($query_ladder . "<br>");
$ladder = mysql_query($query_ladder, $connvbsa) or die(mysql_error());
$row_ladder = mysql_fetch_assoc($ladder);
$totalRows_ladder = mysql_num_rows($ladder);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_teamcount = "SELECT COUNT(Team_entries.team_grade)AS teams FROM Team_entries WHERE Team_entries.team_grade ='$grade' AND include_draw='Yes' AND Team_entries.team_name<>'Bye' AND team_cal_year = '$year' ";
$teamcount = mysql_query($query_teamcount, $connvbsa) or die(mysql_error());
$row_teamcount = mysql_fetch_assoc($teamcount);
$totalRows_teamcount = mysql_num_rows($teamcount);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_rounds = "SELECT comptype,  ROUND(((SUM(total_score)+SUM(scr_adjust))/(players*2))/COUNT(team_grade)*2,2) AS B_rds,    ROUND(((SUM(total_score)+SUM(scr_adjust))/(players*3))/COUNT(team_grade)*2,2) AS S_rds, COUNT(Team_entries.team_grade)AS teams FROM Team_entries WHERE Team_entries.team_grade ='$grade'  AND include_draw='Yes'  AND comptype='$comptype'  AND team_cal_year = '$year' ";
$rounds = mysql_query($query_rounds, $connvbsa) or die(mysql_error());
$row_rounds = mysql_fetch_assoc($rounds);
$totalRows_rounds = mysql_num_rows($rounds);

// match report team 1
//mysql_select_db($database_connvbsa, $connvbsa);
$query_MRt1 = "SELECT team_name, team_grade, Result_pos, Result_score, HB FROM Team_entries WHERE team_grade='$grade' AND Result_pos =1 AND team_cal_year = '$year'";
$MRt1 = mysql_query($query_MRt1, $connvbsa) or die(mysql_error());
$row_MRt1 = mysql_fetch_assoc($MRt1);
$totalRows_MRt1 = mysql_num_rows($MRt1);

// match report team 2
//mysql_select_db($database_connvbsa, $connvbsa);
$query_MRt2 = "SELECT team_name, team_grade, Result_pos, Result_score, HB FROM Team_entries WHERE team_grade='$grade' AND Result_pos =2 AND team_cal_year = '$year'";
$MRt2 = mysql_query($query_MRt2, $connvbsa) or die(mysql_error());
$row_MRt2 = mysql_fetch_assoc($MRt2);
$totalRows_MRt2 = mysql_num_rows($MRt2);

// match report team 3
//mysql_select_db($database_connvbsa, $connvbsa);
$query_MRt3 = "SELECT team_name, team_grade, Result_pos, Result_score, HB FROM Team_entries WHERE team_grade='$grade' AND Result_pos =3 AND team_cal_year = '$year'";
$MRt3 = mysql_query($query_MRt3, $connvbsa) or die(mysql_error());
$row_MRt3 = mysql_fetch_assoc($MRt3);
$totalRows_MRt3 = mysql_num_rows($MRt3);

// match report team 4
//mysql_select_db($database_connvbsa, $connvbsa);
$query_MRt4 = "SELECT team_name, team_grade, Result_pos, Result_score, HB FROM Team_entries WHERE team_grade='$grade' AND Result_pos =4 AND team_cal_year = '$year'";
$MRt4 = mysql_query($query_MRt4, $connvbsa) or die(mysql_error());
$row_MRt4 = mysql_fetch_assoc($MRt4);
$totalRows_MRt4 = mysql_num_rows($MRt4);

// match report team 5
//mysql_select_db($database_connvbsa, $connvbsa);
$query_MRt5 = "SELECT team_name, team_grade, Result_pos, Result_score, HB FROM Team_entries WHERE team_grade='$grade' AND Result_pos =5 AND team_cal_year = '$year'";
$MRt5 = mysql_query($query_MRt5, $connvbsa) or die(mysql_error());
$row_MRt5 = mysql_fetch_assoc($MRt5);
$totalRows_MRt5 = mysql_num_rows($MRt5);

// match report team 6
//mysql_select_db($database_connvbsa, $connvbsa);
$query_MRt6 = "SELECT team_name, team_grade, Result_pos, Result_score, HB FROM Team_entries WHERE team_grade='$grade' AND Result_pos =6 AND team_cal_year = '$year'";
$MRt6 = mysql_query($query_MRt6, $connvbsa) or die(mysql_error());
$row_MRt6 = mysql_fetch_assoc($MRt6);
$totalRows_MRt6 = mysql_num_rows($MRt6);

// match report team 7
//mysql_select_db($database_connvbsa, $connvbsa);
$query_MRt7 = "SELECT team_name, team_grade, Result_pos, Result_score, HB FROM Team_entries WHERE team_grade='$grade' AND Result_pos =7 AND team_cal_year = '$year'";
$MRt7 = mysql_query($query_MRt7, $connvbsa) or die(mysql_error());
$row_MRt7 = mysql_fetch_assoc($MRt7);
$totalRows_MRt7 = mysql_num_rows($MRt7);

// match report team 8
//mysql_select_db($database_connvbsa, $connvbsa);
$query_MRt8 = "SELECT team_name, team_grade, Result_pos, Result_score, HB FROM Team_entries WHERE team_grade='$grade' AND Result_pos =8 AND team_cal_year = '$year'";
$MRt8 = mysql_query($query_MRt8, $connvbsa) or die(mysql_error());
$row_MRt8 = mysql_fetch_assoc($MRt8);
$totalRows_MRt8 = mysql_num_rows($MRt8);

// match report team 9
//mysql_select_db($database_connvbsa, $connvbsa);
$query_MRt9 = "SELECT team_name, team_grade, Result_pos, Result_score, HB FROM Team_entries WHERE team_grade='$grade' AND Result_pos =9 AND team_cal_year = '$year'";
$MRt9 = mysql_query($query_MRt9, $connvbsa) or die(mysql_error());
$row_MRt9 = mysql_fetch_assoc($MRt9);
$totalRows_MRt9 = mysql_num_rows($MRt9);

// match report team 10
//mysql_select_db($database_connvbsa, $connvbsa);
$query_MRt10 = "SELECT team_name, team_grade, Result_pos, Result_score, HB FROM Team_entries WHERE team_grade='$grade' AND Result_pos =10 AND team_cal_year = '$year'";
$MRt10 = mysql_query($query_MRt10, $connvbsa) or die(mysql_error());
$row_MRt10 = mysql_fetch_assoc($MRt10);
$totalRows_MRt10 = mysql_num_rows($MRt10);

// match report team 11
//mysql_select_db($database_connvbsa, $connvbsa);
$query_MRt11 = "SELECT team_name, team_grade, Result_pos, Result_score, HB FROM Team_entries WHERE team_grade='$grade' AND Result_pos =11 AND team_cal_year = '$year'";
$MRt11 = mysql_query($query_MRt11, $connvbsa) or die(mysql_error());
$row_MRt11 = mysql_fetch_assoc($MRt11);
$totalRows_MRt11 = mysql_num_rows($MRt11);

// match report team 12
//mysql_select_db($database_connvbsa, $connvbsa);
$query_MRt12 = "SELECT team_name, team_grade, Result_pos, Result_score, HB FROM Team_entries WHERE team_grade='$grade' AND Result_pos =12 AND team_cal_year = '$year'";
$MRt12 = mysql_query($query_MRt12, $connvbsa) or die(mysql_error());
$row_MRt12 = mysql_fetch_assoc($MRt12);
$totalRows_MRt12 = mysql_num_rows($MRt12);

// added 26/Aug/2024

// match report team 13
//mysql_select_db($database_connvbsa, $connvbsa);
$query_MRt13 = "SELECT team_name, team_grade, Result_pos, Result_score, HB FROM Team_entries WHERE team_grade='$grade' AND Result_pos =13 AND team_cal_year = '$year'";
$MRt13 = mysql_query($query_MRt13, $connvbsa) or die(mysql_error());
$row_MRt13 = mysql_fetch_assoc($MRt13);
$totalRows_MRt13 = mysql_num_rows($MRt13);

// match report team 14
//mysql_select_db($database_connvbsa, $connvbsa);
$query_MRt14 = "SELECT team_name, team_grade, Result_pos, Result_score, HB FROM Team_entries WHERE team_grade='$grade' AND Result_pos =14 AND team_cal_year = '$year'";
$MRt14 = mysql_query($query_MRt14, $connvbsa) or die(mysql_error());
$row_MRt14 = mysql_fetch_assoc($MRt14);
$totalRows_MRt14 = mysql_num_rows($MRt14);

//..............


// if team/s have been audited do not display match results
//mysql_select_db($database_connvbsa, $connvbsa);
$query_finals = "SELECT team_grade, audited FROM Team_entries WHERE audited='Yes' AND  team_grade='$grade' AND team_cal_year = '$year' GROUP BY team_grade";
$finals = mysql_query($query_finals, $connvbsa) or die(mysql_error());
$row_finals = mysql_fetch_assoc($finals);
$totalRows_finals = mysql_num_rows($finals);

// display breaks during the season - NOT finals
//mysql_select_db($database_connvbsa, $connvbsa);
$query_breaks = " SELECT members.FirstName, members.LastName, member_ID_brks, MAX( breaks.brk ) AS HB, recvd, finals_brk, season FROM members, breaks WHERE members.MemberID = breaks.member_ID_brks AND YEAR( recvd ) = '$year' AND grade = '$grade' AND finals_brk = 'No' AND brk_type='$comptype' GROUP BY breaks.member_ID_brks ORDER BY HB DESC LIMIT 5";
$breaks = mysql_query($query_breaks, $connvbsa) or die(mysql_error());
$row_breaks = mysql_fetch_assoc($breaks);
$totalRows_breaks = mysql_num_rows($breaks);

// Top 5 most points
//mysql_select_db($database_connvbsa, $connvbsa);
$query_pts_all = "SELECT scrs.MemberID, team_grade, FirstName, LastName, pts_won FROM scrs, members WHERE scrs.MemberID = members.MemberID AND scrs.MemberID <>1 AND current_year_scrs = '$year' AND team_grade='$grade' ORDER BY pts_won DESC LIMIT 5";
$pts_all = mysql_query($query_pts_all, $connvbsa) or die(mysql_error());
$row_pts_all = mysql_fetch_assoc($pts_all);
$totalRows_pts_all = mysql_num_rows($pts_all);

// Dispaly finals breaks
//mysql_select_db($database_connvbsa, $connvbsa);
$query_brks_finals = "SELECT breaks.member_ID_brks, breaks.brk, members.FirstName, members.LastName, members.MemberID FROM breaks, members WHERE breaks.member_ID_brks=members.MemberID AND breaks.finals_brk='Yes' AND YEAR( recvd ) = '$year' AND grade='$grade' AND brk != 0 ORDER BY breaks.brk DESC";
$brks_finals = mysql_query($query_brks_finals, $connvbsa) or die(mysql_error());
$row_brks_finals = mysql_fetch_assoc($brks_finals);
$totalRows_brks_finals = mysql_num_rows($brks_finals);

if($comptype == 'Snooker')
{
  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T1 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = '$year' ORDER BY `match_pts_total` DESC, `team_perc` DESC, `match_won_count` DESC, `match_drawn_count` DESC, `Result_pos` DESC Limit 1";
  $final_T1 = mysql_query($query_final_T1, $connvbsa) or die(mysql_error());
  $row_final_T1 = mysql_fetch_assoc($final_T1);
  $totalRows_final_T1 = mysql_num_rows($final_T1);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T2 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = '$year' ORDER BY `match_pts_total` DESC, `team_perc` DESC, `match_won_count` DESC, `match_drawn_count` DESC, `Result_pos` DESC Limit 1,1";
  $final_T2 = mysql_query($query_final_T2, $connvbsa) or die(mysql_error());
  $row_final_T2 = mysql_fetch_assoc($final_T2);
  $totalRows_final_T2 = mysql_num_rows($final_T2);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T3 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = '$year' ORDER BY `match_pts_total` DESC, `team_perc` DESC, `match_won_count` DESC, `match_drawn_count` DESC, `Result_pos` DESC Limit 2,1";
  $final_T3 = mysql_query($query_final_T3, $connvbsa) or die(mysql_error());
  $row_final_T3 = mysql_fetch_assoc($final_T3);
  $totalRows_final_T3 = mysql_num_rows($final_T3);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T4 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = '$year' ORDER BY `match_pts_total` DESC, `team_perc` DESC, `match_won_count` DESC, `match_drawn_count` DESC, `Result_pos` DESC Limit 3,1";
  $final_T4 = mysql_query($query_final_T4, $connvbsa) or die(mysql_error());
  $row_final_T4 = mysql_fetch_assoc($final_T4);
  $totalRows_final_T4 = mysql_num_rows($final_T4);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_SF1win = "SELECT team_id, team_name, team_grade, GFtot, GF_pts, SF1tot, SF1_pts FROM Team_entries WHERE Team_entries.team_grade ='$grade' AND  team_cal_year = '$year' AND SF1tot is not null ORDER BY SF1tot DESC, SF1_pts DESC   LIMIT 1";
  $SF1win = mysql_query($query_SF1win, $connvbsa) or die(mysql_error());
  $row_SF1win = mysql_fetch_assoc($SF1win);
  $totalRows_SF1win = mysql_num_rows($SF1win);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_SF2win = "SELECT team_id, team_name, team_grade, GFtot, GF_pts, SF2tot, SF2_pts FROM Team_entries WHERE Team_entries.team_grade ='$grade' AND  team_cal_year = '$year' AND SF2tot is not null ORDER BY SF2tot DESC, SF2_pts DESC   LIMIT 1";
  $SF2win = mysql_query($query_SF2win, $connvbsa) or die(mysql_error());
  $row_SF2win = mysql_fetch_assoc($SF2win);
  $totalRows_SF2win = mysql_num_rows($SF2win);
}
if($comptype == 'Billiards')
{
  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T1 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = '$year'  ORDER BY total_score DESC, scrs_percent_finals DESC  Limit 1";
  //echo("Finals T1 " . $query_final_T1 . "<br>");
  $final_T1 = mysql_query($query_final_T1, $connvbsa) or die(mysql_error());
  $row_final_T1 = mysql_fetch_assoc($final_T1);
  $totalRows_final_T1 = mysql_num_rows($final_T1);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T2 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = '$year'  ORDER BY total_score DESC, scrs_percent_finals DESC  Limit 1,1";
  //echo("Finals T2 " . $query_final_T2 . "<br>");
  $final_T2 = mysql_query($query_final_T2, $connvbsa) or die(mysql_error());
  $row_final_T2 = mysql_fetch_assoc($final_T2);
  $totalRows_final_T2 = mysql_num_rows($final_T2);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T3 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = '$year'  ORDER BY total_score DESC, scrs_percent_finals DESC  Limit 2,1";
  //echo("Finals T3 " . $query_final_T3 . "<br>");
  $final_T3 = mysql_query($query_final_T3, $connvbsa) or die(mysql_error());
  $row_final_T3 = mysql_fetch_assoc($final_T3);
  $totalRows_final_T3 = mysql_num_rows($final_T3);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T4 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = '$year'  ORDER BY total_score DESC, scrs_percent_finals DESC  Limit 3,1";
  //echo("Finals T4 " . $query_final_T4 . "<br>");
  $final_T4 = mysql_query($query_final_T4, $connvbsa) or die(mysql_error());
  $row_final_T4 = mysql_fetch_assoc($final_T4);
  $totalRows_final_T4 = mysql_num_rows($final_T4);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T5 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = '$year' ORDER BY total_score DESC, scrs_percent_finals DESC Limit 4,1";
  //echo("Finals T5 " . $query_final_T5 . "<br>");
  $final_T5 = mysql_query($query_final_T5, $connvbsa) or die(mysql_error());
  $row_final_T5 = mysql_fetch_assoc($final_T5);
  $totalRows_final_T5 = mysql_num_rows($final_T5);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_final_T6 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = '$year' ORDER BY total_score DESC, scrs_percent_finals DESC  Limit 5,1";
  //echo("Finals T6 " . $query_final_T6 . "<br>");
  $final_T6 = mysql_query($query_final_T6, $connvbsa) or die(mysql_error());
  $row_final_T6 = mysql_fetch_assoc($final_T6);
  $totalRows_final_T6 = mysql_num_rows($final_T6);
//}
//..............................

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_EF1win = "SELECT * FROM Team_entries WHERE Team_entries.team_grade ='$grade' AND  team_cal_year = '$year' AND EF1tot is not null ORDER BY EF1tot DESC, EF1_pts DESC LIMIT 1";
  //echo("Semi Finals Wins 1 " . $query_SF1win . "<br>");
  $EF1win = mysql_query($query_EF1win, $connvbsa) or die(mysql_error());
  $row_EF1win = mysql_fetch_assoc($EF1win);
  $totalRows_EF1win = mysql_num_rows($EF1win);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_EF2win = "SELECT * FROM Team_entries WHERE Team_entries.team_grade ='$grade' AND  team_cal_year = '$year' AND EF2tot is not null ORDER BY EF2tot DESC, EF2_pts DESC LIMIT 1";
  //echo("Semi Finals Wins 2 " . $query_SF2win . "<br>");
  $EF2win = mysql_query($query_EF2win, $connvbsa) or die(mysql_error());
  $row_EF2win = mysql_fetch_assoc($EF2win);
  $totalRows_EF2win = mysql_num_rows($EF2win); 

  /*
 mysql_select_db($database_connvbsa, $connvbsa);
  $query_EF1win = "SELECT team_id, team_name, team_grade, EF1tot, EF1_pts FROM Team_entries WHERE Team_entries.team_grade ='$grade' AND  team_cal_year = YEAR( CURDATE( ) ) AND EF1tot is not null ORDER BY EF1tot DESC, EF1_pts DESC   LIMIT 1";
  //echo("Semi Finals Wins 1 " . $query_SF1win . "<br>");
  $EF1win = mysql_query($query_EF1win, $connvbsa) or die(mysql_error());
  $row_EF1win = mysql_fetch_assoc($EF1win);
  $totalRows_EF1win = mysql_num_rows($EF1win);

  mysql_select_db($database_connvbsa, $connvbsa);
  $query_EF2win = "SELECT team_id, team_name, team_grade, EF2tot, EF2_pts FROM Team_entries WHERE Team_entries.team_grade ='$grade' AND  team_cal_year = YEAR( CURDATE( ) ) AND EF2tot is not null ORDER BY EF2tot DESC, EF2_pts DESC   LIMIT 1";
  //echo("Semi Finals Wins 2 " . $query_SF2win . "<br>");
  $EF2win = mysql_query($query_EF2win, $connvbsa) or die(mysql_error());
  $row_EF2win = mysql_fetch_assoc($EF2win);
  $totalRows_EF2win = mysql_num_rows($EF2win); */
//...............................
  
  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_SF1win = "SELECT team_id, team_name, team_grade, GFtot, GF_pts, SF1tot, SF1_pts FROM Team_entries WHERE Team_entries.team_grade ='$grade' AND  team_cal_year = '$year' AND SF1tot is not null ORDER BY SF1tot DESC, SF1_pts DESC   LIMIT 1";
  //echo("Semi Finals Wins 1 " . $query_SF1win . "<br>");
  $SF1win = mysql_query($query_SF1win, $connvbsa) or die(mysql_error());
  $row_SF1win = mysql_fetch_assoc($SF1win);
  $totalRows_SF1win = mysql_num_rows($SF1win);

  //mysql_select_db($database_connvbsa, $connvbsa);
  $query_SF2win = "SELECT team_id, team_name, team_grade, GFtot, GF_pts, SF2tot, SF2_pts FROM Team_entries WHERE Team_entries.team_grade ='$grade' AND  team_cal_year = '$year' AND SF2tot is not null ORDER BY SF2tot DESC, SF2_pts DESC   LIMIT 1";
  //echo("Semi Finals Wins 2 " . $query_SF2win . "<br>");
  $SF2win = mysql_query($query_SF2win, $connvbsa) or die(mysql_error());
  $row_SF2win = mysql_fetch_assoc($SF2win);
  $totalRows_SF2win = mysql_num_rows($SF2win);
}


//mysql_select_db($database_connvbsa, $connvbsa);
$query_Adjust_notes = "SELECT scr_adjust, scr_adj_rd, adj_comment, team_grade FROM Team_entries WHERE adj_comment is not null AND Team_entries.team_grade ='$grade' AND team_cal_year = '$year' ORDER BY Team_entries.scr_adj_rd";
//echo("Scr Adjust " . $query_Adjust_notes ."<br>");
$Adjust_notes = mysql_query($query_Adjust_notes, $connvbsa) or die(mysql_error());
$row_Adjust_notes = mysql_fetch_assoc($Adjust_notes);
$totalRows_Adjust_notes = mysql_num_rows($Adjust_notes);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Scores</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />

</head>
<body id="vbsa">
    
    <!-- Include Google Tracking -->
<?php include_once("../includes/analyticstracking.php") ?>

<div class="container"> 

    <!-- Include header -->
<?php include '../includes/header.php';?>
    
    <!-- Include navigation -->
<?php include '../includes/nav_vbsa.php';?>

</div><!--End Bootstrap Container--> 

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 

  <!--Content--> 
  
  
  <!--Left--> 
  
  <div class="row"> 
    <div class="Page_heading_container">
   		<div class="page_title">
      <?php
      	$newDate = date("l jS F Y", strtotime($row_updated['updated'])); 
  			$newDateY = date("Y", strtotime($row_updated['updated']));
  			$TimeDate = date("g:i a", strtotime($row_updated['updated']));
  		// display grade from passed variable	  
  		echo $grade; 
  		// display "Snooker or Billiards"
  		echo " ". $comptype;
  		// if last update is in the current year display last update 
  		if ($newDateY==$year)
  		{
  		echo " - scores last updated: "; 	
  		echo $newDate. " at " . $TimeDate;  
  		}
  		else
  		echo " - New season";
  		?>
      </div>
    </div>  	
    <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 
<!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>


  <div class="table-responsive center-block" style="max-width:300px"> <!-- scoring system explained -->
  <table class="table">
  <tr>
  	<td class="text-right italic" nowrap="nowrap">Scoring system explained</td>
  	<td class="text-right">&nbsp;&nbsp;<a href="http://www.vbsa.org.au//ViewerJS/?zoom=page-width#../Front_page_upload/SCORING_SYSTEM_VBSA_2012_AGM.pdf" title="View"><span class="glyphicon glyphicon-eye-open"></span></a></td>
  	<td class="text-left"><a href="http://www.vbsa.org.au/Front_page_upload/SCORING_SYSTEM_VBSA_2012_AGM.pdf" title="Download">&nbsp;&nbsp;<span class="glyphicon glyphicon-download"></span></a></td>
    </tr>
    </table>
  </div>
  
  <div class="table-responsive center-block" style="max-width:900px"> <!-- class table-responsive -->
    <div style="color: black; font-weight: margin-bottom: 10px;">
      <ul style="list-style-type: none; padding-left: 15px; margin: 0 0 10px 0;">
        <li><b>W</b> - Matches won</li>
        <li><b>D</b> - Matches drawn</li>
        <li><b>F</b> - Games/Frames for (won)</li>
        <li><b>A</b> - Games/Frames against (lost)</li>
        <li><b>P</b> - Matches played</li>
        <li><b>B</b> - Byes</li>
        <br>
      </ul>
    </div>

  <div class="table-responsive center-block" style="max-width:900px">
  <table class="table">
    <tr>
      <!--<th class="text-center" nowrap="nowrap"> Team ID</th>-->
      <th>Club Name</th>
      <th>Team Name</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <?php
      if($comptype == 'Snooker')
      {
        echo("<th class='text-center'>Pts</th>");
      }
      ?>
      <th class="text-center">%</th>
      <?php
      if($comptype == 'Snooker')
      {
        echo("<th class='text-center'>W</th>");
        echo("<th class='text-center'>D</th>");
      }
      ?>
      <th class="text-center">F</th>
      <th class="text-center">A</th>
      <th class="text-center">P</th>
      <th class="text-center">B</th>
      <?php
      if($comptype == 'Billiards')
      {
        echo("<th class='text-center'>Pts For</th>");
        echo("<th class='text-center'>Pts Agst</th>");
        echo("<th class='text-center'>Pts %</th>");
      }
      ?>
    </tr>
    <?php do { ?>
      <tr>
        <!--<td class="text-center"><?php echo $row_ladder['team_id']; ?></td>-->
        <td><?php echo $row_ladder['team_club']; ?></td>
        <td nowrap="nowrap"><?php echo $row_ladder['team_name']; ?></td>
        <td><?php if ($row_ladder['audited']=='Yes') echo "<img src='../images/tick.jpg' width='20' height='20' /> "; else echo '&nbsp;'; ?></td>
        <td nowrap="nowrap"><a href="scores_results_ladders_team_detail.php?team_id=<?php echo $row_ladder['team_id']; ?>&comptype=<?php echo ucfirst($row_ladder['comptype']); ?>&season=<?php echo $season ?>" class=" btn-sm btn-primary btn-responsive" role="button">More</a></td>
      <?php
        if($comptype == 'Snooker')
        {
          echo("<td class='text-center'>" . $row_ladder['Pts'] . "</td>");
        }
      ?>
        <td class="text-center"><?php echo $row_ladder['team_perc']; ?></td>
        <?php
        if($comptype == 'Snooker')
        {
          echo("<td class='text-center'>" . $row_ladder['W'] . "</td>");
          echo("<td class='text-center'>" . $row_ladder['D'] . "</td>");
        }
      ?>
        <td class="text-center"><?php echo $row_ladder['F']; ?></td>
        <td class="text-center"><?php echo $row_ladder['A']; ?></td>
        <td class="text-center"><?php echo $row_ladder['P']; ?></td>
        <td class="text-center"><?php echo $row_ladder['B']; ?></td>
        
        <td class="text-center"><?php echo $row_ladder['scrs_for_finals']; ?></td>
        <td class="text-center"><?php echo $row_ladder['scrs_against_finals']; ?></td>
        <td class="text-center"><?php echo $row_ladder['scrs_percent_finals']; ?></td>
      </tr>
      <?php } while ($row_ladder = mysql_fetch_assoc($ladder)); ?>
  </table>
  </div>
  
  <?php // if team/s have been audited do not display match results
  if(isset($row_finals['audited'])) echo ""; else { ?> 
  <div class="table-responsive center-block" style="max-width:900px"> <!-- match results -->
<table class="table">
  <tr>
    <td colspan="3" class="italic">Results for round <?php if($comptype=='Snooker') echo $row_rounds['S_rds']; else echo $row_rounds['B_rds']; ?>
      </td>
    <td>&nbsp;</td>
    <td colspan="3" class="italic text-right">Total Teams <?php echo $row_teamcount['teams']; ?></td>
    </tr>
  <tr>
    <th>Home Team</th>
    <th class="text-center">Score</th>
    <th>High Break</th>
    <th>&nbsp;</th>
    <th>Away Team</th>
    <th class="text-center">Score</th>
    <th>High Break</th>
  </tr>
  <tr>
    <td nowrap="nowrap"><?php echo $row_MRt1['team_name']; ?></td>
    <td class="text-center"><?php echo $row_MRt1['Result_score']; ?></td>
    <td nowrap="nowrap"><?php echo $row_MRt1['HB']; ?></td>
    <td>v</td>
    <td nowrap="nowrap"><?php echo $row_MRt2['team_name']; ?></td>
    <td class="text-center"><?php echo $row_MRt2['Result_score']; ?></td>
    <td nowrap="nowrap"><?php echo $row_MRt2['HB']; ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap"><?php echo $row_MRt3['team_name']; ?></td>
    <td class="text-center"><?php echo $row_MRt3['Result_score']; ?></td>
    <td nowrap="nowrap"><?php echo $row_MRt3['HB']; ?></td>
    <td>v</td>
    <td nowrap="nowrap"><?php echo $row_MRt4['team_name']; ?></td>
    <td class="text-center"><?php echo $row_MRt4['Result_score']; ?></td>
    <td nowrap="nowrap"><?php echo $row_MRt4['HB']; ?></td>
  </tr>
  <tr>
    <td nowrap="nowrap"><?php echo $row_MRt5['team_name']; ?></td>
    <td class="text-center"><?php echo $row_MRt5['Result_score']; ?></td>
    <td nowrap="nowrap"><?php echo $row_MRt5['HB']; ?></td>
    <td>v</td>
    <td nowrap="nowrap"><?php echo $row_MRt6['team_name']; ?></td>
    <td class="text-center"><?php echo $row_MRt6['Result_score']; ?></td>
    <td nowrap="nowrap"><?php echo $row_MRt6['HB']; ?></td>
  </tr>
  
  <?php // show this row if teams exceed 6
  if(!isset($row_MRt7['Result_pos'])) echo ""; else { ?>
  <tr>
    <td nowrap="nowrap"><?php echo $row_MRt7['team_name']; ?></td>
    <td class="text-center"><?php echo $row_MRt7['Result_score']; ?></td>
    <td nowrap="nowrap"><?php echo $row_MRt7['HB']; ?></td>
    <td>v</td>
    <td nowrap="nowrap"><?php echo $row_MRt8['team_name']; ?></td>
    <td class="text-center"><?php echo $row_MRt8['Result_score']; ?></td>
    <td nowrap="nowrap"><?php echo $row_MRt8['HB']; ?></td>
  </tr>
  <?php } ?>
  
  <?php // show this row if teams exceed 8
  if(!isset($row_MRt9['Result_pos'])) echo ""; else { ?>
  <tr>
    <td nowrap="nowrap"><?php echo $row_MRt9['team_name']; ?></td>
    <td class="text-center"><?php echo $row_MRt9['Result_score']; ?></td>
    <td nowrap="nowrap"><?php echo $row_MRt9['HB']; ?></td>
    <td>v</td>
    <td nowrap="nowrap"><?php echo $row_MRt10['team_name']; ?></td>
    <td class="text-center"><?php echo $row_MRt10['Result_score']; ?></td>
    <td nowrap="nowrap"><?php echo $row_MRt10['HB']; ?></td>
  </tr>
  <?php } ?>

  <?php // show this row if teams exceed 10
  if(!isset($row_MRt9['Result_pos'])) echo ""; else { ?>
  <tr>
    <td nowrap="nowrap"><?php echo $row_MRt11['team_name']; ?></td>
    <td class="text-center"><?php echo $row_MRt11['Result_score']; ?></td>
    <td nowrap="nowrap"><?php echo $row_MRt11['HB']; ?></td>
    <td>v</td>
    <td nowrap="nowrap"><?php echo $row_MRt12['team_name']; ?></td>
    <td class="text-center"><?php echo $row_MRt12['Result_score']; ?></td>
    <td nowrap="nowrap"><?php echo $row_MRt12['HB']; ?></td>
  </tr>
  <?php } ?>

   <?php // show this row if teams exceed 10
  if(!isset($row_MRt9['Result_pos'])) echo ""; else { ?>
  <tr>
    <td nowrap="nowrap"><?php echo $row_MRt13['team_name']; ?></td>
    <td class="text-center"><?php echo $row_MRt13['Result_score']; ?></td>
    <td nowrap="nowrap"><?php echo $row_MRt13['HB']; ?></td>
    <td>v</td>
    <td nowrap="nowrap"><?php echo $row_MRt14['team_name']; ?></td>
    <td class="text-center"><?php echo $row_MRt14['Result_score']; ?></td>
    <td nowrap="nowrap"><?php echo $row_MRt14['HB']; ?></td>
  </tr>
  <?php } ?>


</table>
</div>
<?php } ?> 
<!-- close match results --> 

<?php // if team/s have been audited do not display match results
  if(!isset($row_finals['audited'])) echo ""; else { ?>
<div class="table-responsive center-block" style="max-width:900px"> <!-- finals about -->
  <?php
  if($comptype == 'Billiards')
  {
    ?>
  <table class="table">
  <tr>
  	<td class="text-center">Home and Away matches have been completed. Finals Format - Final 6. Qualified teams  <img src='../../../images/tick.jpg' width='20' height='20' /></td>
  </tr>
  <tr>
    <td class="text-center italic">Week 1 - Elimination Final 1, 3 v 6 at venue of 3 - Elimination final 2, 4 v 5 at venue of 4. *Providing venue  available</td>
  </tr>
  <tr>
    <td class="text-center italic">Week 2 - Semi Final 1, 1 v Lower of EF winner at venue of 1 - Semi final 2, 2 v Higher of EF winner at venue of 2. *Providing venue  available</td>
  </tr>
  <tr>
    <td class="text-center italic">Week 3 - Grand final - Winner Semi Final 1 v Semi Final 2, venue TBC</td>
  </tr>
    </table>
  </div>
  <?php
  }
  else if($comptype == 'Snooker')
  {
  ?>
  <table class="table">
  <tr>
    <td class="text-center">Home and Away matches have been completed. Finals Format - Final 4. Qualified teams  <img src='../../../images/tick.jpg' width='20' height='20' /></td>
    </tr>
  <tr>
    <td class="text-center italic">Week 1 - Semi Final 1, 1 v 4 at venue of 1 - Semi final 2, 2 v 3 at venue of 2. *Providing venue  available</td>
  </tr>
  <tr>
    <td class="text-center italic">Week 2 - Grand final - Winner Semi Final 1 v Semi Final 2, venue TBC</td>
  </tr>
    </table>
  </div>
  <?php 
  }
  ?>
 <!-- close finals about --> 
 
<div class="table-responsive center-block" style="max-width:700px"> <!-- finals results -->
<table class="table">
  <tr>
    <td class="italic">&nbsp;</td>
    <td colspan="5" class="italic">Finals Fixture / Results (* = win on points)</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <!--<td align="center">Team ID</td>-->
    <td>Team Name</td>
    <td align="center">Score</td>
    <td align="center">&nbsp;</td>
    <!--<td align="center">Team ID</td>-->
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
    <!--<td align="center" nowrap="nowrap"><?php echo $row_final_T3['team_id']; ?></td>-->
    <td nowrap="nowrap"><?php echo $row_final_T3['team_name']; ?></td>
    <td align="center" ><?php echo $row_final_T3['EF1tot']; if ($row_final_T3['EF1_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
    <!--<td align="center" nowrap="nowrap"><?php echo $row_final_T6['team_id']; ?></td>-->
    <td nowrap="nowrap"><?php echo $row_final_T5['team_name']; ?></td>
    <td align="center"><?php echo $row_final_T5['EF1tot']; if ($row_final_T5['EF1_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">EF2 </td>
    <!--<td align="center" nowrap="nowrap"><?php echo $row_final_T4['team_id']; ?></td>-->
    <td nowrap="nowrap"><?php echo $row_final_T4['team_name']; ?></td>
    <td align="center"><?php echo $row_final_T4['EF2tot']; if ($row_final_T4['EF2_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
    <!--<td align="center" nowrap="nowrap"><?php echo $row_final_T5['team_id']; ?></td>-->
    <td nowrap="nowrap"><?php echo $row_final_T6['team_name']; ?></td>
    <td align="center"><?php echo $row_final_T6['EF2tot']; if ($row_final_T6['EF2_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">SF1 </td>
    <!--<td align="center" nowrap="nowrap"><?php echo $row_final_T1['team_id']; ?></td>-->
    <td nowrap="nowrap"><?php echo $row_final_T1['team_name']; ?></td>
    <td align="center" ><?php echo $row_final_T1['SF1tot']; if ($row_final_T1['SF1_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
    <!--<td align="center" nowrap="nowrap"><?php echo $row_EF2win['team_id']; ?></td>-->
    <td nowrap="nowrap"><?php echo $row_EF1win['team_name']; ?></td>
    <td align="center"><?php echo $row_EF1win['SF1tot']; if ($row_EF1win['SF1_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">SF2 </td>
    <!--<td align="center" nowrap="nowrap"><?php echo $row_final_T2['team_id']; ?></td>-->
    <td nowrap="nowrap"><?php echo $row_final_T2['team_name']; ?></td>
    <td align="center"><?php echo $row_final_T2['SF2tot']; if ($row_final_T2['SF2_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
    <!--<td align="center" nowrap="nowrap"><?php echo $row_EF1win['team_id']; ?></td>-->
    <td nowrap="nowrap"><?php echo $row_EF2win['team_name']; ?></td>
    <td align="center"><?php echo $row_EF2win['SF2tot']; if ($row_EF2win['SF2_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">GF </td>
    <!--<td align="center" nowrap="nowrap"><?php echo $row_SF1win['team_id']; ?></td>-->
    <td nowrap="nowrap"><?php echo $row_SF1win['team_name']; ?></td>
    <td align="center"><?php echo $row_SF1win['GFtot']; if ($row_SF1win['GF_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
    <!--<td align="center" nowrap="nowrap"><?php echo $row_SF2win['team_id']; ?></td>-->
    <td nowrap="nowrap"><?php echo $row_SF2win['team_name']; ?></td>
    <td align="center"><?php echo $row_SF2win['GFtot']; if ($row_SF2win['GF_pts']==1)echo "*"; else echo "";  ?></td>
  </tr>
    <?php 
    }
    else
    {
    ?>
  <tr>
    <td class="italic">EF1 </td>
    <td nowrap="nowrap"><?php echo $row_final_T3['team_name']; ?></td>
    <td align="center" ><?php echo $row_final_T3['EF1tot']; if ($row_final_T3['EF1_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
    <td nowrap="nowrap"><?php echo $row_final_T6['team_name']; ?></td>
    <td align="center"><?php echo $row_final_T6['EF1tot']; if ($row_final_T6['EF1_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">EF2 </td>
    <td nowrap="nowrap"><?php echo $row_final_T4['team_name']; ?></td>
    <td align="center"><?php echo $row_final_T4['EF2tot']; if ($row_final_T4['EF2_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
    <td nowrap="nowrap"><?php echo $row_final_T5['team_name']; ?></td>
    <td align="center"><?php echo $row_final_T5['EF2tot']; if ($row_final_T5['EF2_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">SF1 </td>
    <td nowrap="nowrap"><?php echo $row_final_T1['team_name']; ?></td>
    <td align="center" ><?php echo $row_final_T1['SF1tot']; if ($row_final_T1['SF1_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
    <td nowrap="nowrap"><?php echo $row_EF2win['team_name']; ?></td>
    <td align="center"><?php echo $row_EF2win['SF1tot']; if ($row_EF2win['SF1_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">SF2 </td>
    <td nowrap="nowrap"><?php echo $row_final_T2['team_name']; ?></td>
    <td align="center"><?php echo $row_final_T2['SF2tot']; if ($row_final_T2['SF2_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
    <td nowrap="nowrap"><?php echo $row_EF1win['team_name']; ?></td>
    <td align="center"><?php echo $row_EF1win['SF2tot']; if ($row_EF1win['SF2_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">GF </td>
    <td nowrap="nowrap"><?php echo $row_SF1win['team_name']; ?></td>
    <td align="center"><?php echo $row_SF1win['GFtot']; if ($row_SF1win['GF_pts']==1)echo "*"; else echo ""; ?></td>
    <td align="center">v</td>
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
    <!--<td align="center" nowrap="nowrap"><?php echo $row_final_T1['team_id']; ?></td>-->
    <td nowrap="nowrap"><?php echo $row_final_T1['team_name']; ?></td>
    <td class="text-center" ><?php echo $row_final_T1['SF1tot']; if ($row_final_T1['SF1_pts']==1)echo "*"; else echo ""; ?></td>
    <td class="text-center">v</td>
    <!--<td align="center" nowrap="nowrap"><?php echo $row_final_T4['team_id']; ?></td>-->
    <td nowrap="nowrap"><?php echo $row_final_T4['team_name']; ?></td>
    <td class="text-center"><?php echo $row_final_T4['SF1tot']; if ($row_final_T4['SF2_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">SF2 </td>
    <!--<td align="center" nowrap="nowrap"><?php echo $row_final_T2['team_id']; ?></td>-->
    <td nowrap="nowrap"><?php echo $row_final_T2['team_name']; ?></td>
    <td class="text-center"><?php echo $row_final_T2['SF2tot']; if ($row_final_T2['SF2_pts']==1)echo "*"; else echo ""; ?></td>
    <td class="text-center">v</td>
    <!--<td align="center" nowrap="nowrap"><?php echo $row_final_T3['team_id']; ?></td>-->
    <td nowrap="nowrap"><?php echo $row_final_T3['team_name']; ?></td>
    <td class="text-center"><?php echo $row_final_T3['SF2tot']; if ($row_final_T3['SF2_pts']==1)echo "*"; else echo "";?></td>
  </tr>
  <tr>
    <td class="italic">GF </td>
    <!--<td align="center" nowrap="nowrap"><?php echo $row_SF1win['team_id']; ?></td>-->
    <td nowrap="nowrap"><?php echo $row_SF1win['team_name']; ?></td>
    <td class="text-center"><?php echo $row_SF1win['GFtot']; if ($row_SF1win['GF_pts']==1)echo "*"; else echo ""; ?></td>
    <td class="text-center">v</td>
    <!--<td align="center" nowrap="nowrap"><?php echo $row_SF2win['team_id']; ?></td>-->   
    <td nowrap="nowrap"><?php echo $row_SF2win['team_name']; ?></td>
    <td class="text-center"><?php echo $row_SF2win['GFtot']; if ($row_SF2win['GF_pts']==1)echo "*"; else echo "";  ?></td>
  </tr>
  <?php 
      }
?>
</table>
</div>  
<!-- close finals results --> 

<div class="table-responsive center-block" style="max-width:500px"> <!-- finals breaks -->
<table class="table">
          <tr>
            <td class="italic" colspan="2"><?php echo $colname_grade ?> Finals Breaks </td>
            <td class="text-center">&nbsp;</td>
          </tr>
          <tr>
            <th class="text-center">Memb ID</th>
            <th>Name</th>
            <th class="text-center">Break</th>
          </tr>
          <?php do { ?>
            <tr>
              <td class="text-center"><?php echo $row_brks_finals['member_ID_brks']; ?></td>
              <td nowrap="nowrap" ><?php echo $row_brks_finals['FirstName']; ?> <?php echo $row_brks_finals['LastName']; ?></td>
              <td class="text-center"><?php echo $row_brks_finals['brk']; ?></td>
            </tr>
            <?php } while ($row_brks_finals = mysql_fetch_assoc($brks_finals)); ?>
        </table>
</div>  <!-- close finals breaks --> 
<?php } ?> <!-- close display finals if audites --> 

<div class="table-responsive center-block" style="max-width:500px"> <!-- snooker breaks -->
<table class="table">
          <tr>
            <td class="italic" colspan="2">Top 5 <?php echo $colname_grade ?> Season Breaks </td>
            <td class="text-center"><a href="scores_results_ladders_breaks.php?season=<?php echo $season; ?> & year=<?php echo $year; ?> & comptype=<?php echo $comptype; ?>" class=" btn-sm btn-primary btn-responsive" role="button">All breaks</a></td>
          </tr>
          <tr>
            <th class="text-center">Memb ID</th>
            <th>Name</th>
            <th class="text-center">Break</th>
          </tr>
          <?php do { ?>
            <tr>
              <td class="text-center"><?php echo $row_breaks['member_ID_brks']; ?></td>
              <td nowrap="nowrap" ><?php echo $row_breaks['FirstName']; ?> <?php echo $row_breaks['LastName']; ?></td>
              <td class="text-center"><?php echo $row_breaks['HB']; ?></td>
            </tr>
            <?php } while ($row_breaks = mysql_fetch_assoc($breaks)); ?>
        </table>
</div>  <!-- close snooker breaks --> 

<div class="table-responsive center-block" style="max-width:500px"> <!-- class table-responsive -->
  
    <table class="table">
      <tr>
        <td class="italic" colspan="2">Top 5 <?php echo $colname_grade ?> Season Most <?php if($comptype=='Snooker') echo " Frames"; else echo "Points"; ?></td>
        <td class="text-center"><a href="scores_results_ladders_games.php?season=<?php echo $season; ?>&year=<?php echo $year; ?>&comptype=<?php echo $comptype; ?>" class=" btn-sm btn-primary btn-responsive" role="button">All </a></td>
      </tr>
      <tr>
        <th class="text-center">Memb ID</th>
        <th>Name</th>
        <th class="text-center"><?php if($comptype=='Snooker') echo "Frames"; else echo "Points"; ?></th>
      </tr>
	  <?php do { ?> 
    <tr>
      <td class="text-center"><?php echo $row_pts_all['MemberID']; ?></td>
      <td nowrap="nowrap"><?php echo $row_pts_all['FirstName']; ?> <?php echo $row_pts_all['LastName']; ?></td>
      <td class="text-center"><?php echo $row_pts_all['pts_won']; ?></td>
      </tr>
	  <?php } while ($row_pts_all = mysql_fetch_assoc($pts_all));?>
     
</table>
  </div>
<!-- close most frames / points --> 

<div class="table-responsive center-block" style="max-width:900px"> <!-- comments -->
  <table class="table">
  <tr>
      <td colspan="4" class="italic">Adjustments to this ladder and explanations.</td>
    </tr>
    <tr>
      <th>Grade</th>
      <th class="text-center">Round</th>
      <th class="text-center" nowrap="nowrap">Pts adj.</th>
      <th>Explanation</th>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_Adjust_notes['team_grade']; ?></td>
        <td class="text-center"><?php echo $row_Adjust_notes['scr_adj_rd']; ?></td>
        <td class="text-center"><?php echo $row_Adjust_notes['scr_adjust']; ?></td>
        <td><?php echo $row_Adjust_notes['adj_comment']; ?></td>
      </tr>
      <?php } while ($row_Adjust_notes = mysql_fetch_assoc($Adjust_notes)); ?>
    </table>
  </div><!-- close comments -->

</div>  <!-- close conraineing wrapper --> 
</body>
</html>

