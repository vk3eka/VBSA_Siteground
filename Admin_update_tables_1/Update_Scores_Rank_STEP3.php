<?php require_once('../Connections/connvbsa.php'); ?>
<?php
error_reporting(0);
if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
  {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
        $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />

<script type="text/javascript" src="Scripts/AC_RunActiveContent.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>
<body>
  <table align="center">
    <tr>
      <td><img src="../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="header_red">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="center">STEP 3 of the calculation process - please complete all steps.</td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="red_text">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="red_text">WARNING - PLEASE WAIT UNTIL PAGE HAS STOPPED RUNNING IN YOUR BROWSER</td>
    </tr>
    <tr>
      <td colspan="4" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="red_text">ALL CALCULATIONS ARE FOR THE CURRENT YEAR. TO RECALCULATE PREVIOUS YEARS CONTACT THE WEBMASTER</td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="red_text">&nbsp;</td>
    </tr>
  </table>
  <center>
<?php require_once('../Connections/connvbsa.php'); ?>
<?php

if(isset($_POST["submit"]))
{
  mysql_select_db($database_connvbsa, $connvbsa);
  echo "<font face='arial' size='3'>STEP 3 completed go to ".'<span class="greenbg"><a href="Update_Scores_Rank_STEP4.php">'. "STEP 4". '</a></span>';
  echo '<br/><br/>';
  echo '<br/>';
  echo "<font face='arial'>Updated Team Entries table - Calculated Finals scores</font>";
  echo '<br/>';

  // TEAM ENTRIES FINALS

  // added 8/10/2024, Finals Scores Percentages for Billiards

  $month = date('m');
  if($month < '08')
  {
    $current_season = 'S1';
  }
  else
  {
    $current_season = 'S2';
  }

  // calculate finals position if match points won are the same for two or more teams
  $total_scores_for = 0;

  // get billiards teams (team grade removed)
  $query_scores_ladder = "Select team_id, team_club, team_name, team_grade FROM Team_entries WHERE comptype='Billiards' AND team_cal_year = YEAR( CURDATE( ) ) AND team_name != 'Bye' AND team_season = '" . $current_season . "' GROUP BY team_id";
  //echo("Sum scores " . $query_scores_ladder . "<br>");
  $scores_ladder = mysql_query($query_scores_ladder, $connvbsa) or die(mysql_error());
  while($row_scores_ladder = mysql_fetch_assoc($scores_ladder))
  {
    $query_scores_for = "Select SUM(score_1) as total_scores_1 FROM tbl_scoresheet WHERE team_id = " . $row_scores_ladder['team_id'] . " AND year = YEAR( CURDATE( ) ) AND season = '" . $current_season . "'";
    //echo("Sum scores " . $query_scores_for . "<br>");
    $scores_for = mysql_query($query_scores_for, $connvbsa) or die(mysql_error());
    $row_scores_for = mysql_fetch_assoc($scores_for);
    $total_scores_for = $row_scores_for['total_scores_1'];
    if(!isset($total_scores_for))
    {
      $total_scores_for = 0;
    }
    else
    {
      $total_scores_for = $row_scores_for['total_scores_1'];
    }
    // get teams played against
     $query_teams_against = "Select distinct opposition FROM tbl_scoresheet WHERE team_id =" . $row_scores_ladder['team_id'] . " AND year = YEAR( CURDATE( ) ) AND season = '" . $current_season . "'";
    //echo("Opposition " . $query_teams_against . "<br>");
    $teams_against = mysql_query($query_teams_against, $connvbsa) or die(mysql_error());
    $total_scores_against = 0;
    while($row_teams_against = mysql_fetch_assoc($teams_against))
    {
      //echo("Teams Against " . $row_teams_against['opposition'] . "<br>");
      $query_scores_against = "Select SUM(score_1) as total_scores_1 FROM tbl_scoresheet WHERE team = '" . $row_teams_against['opposition'] . "' AND opposition = '" . $row_scores_ladder['team_name'] . "' AND year = YEAR( CURDATE( ) ) AND season = '" . $current_season . "'";
      //echo("Sum scores against " . $query_scores_against . "<br>");
      $scores_against = mysql_query($query_scores_against, $connvbsa) or die(mysql_error());
      $row_scores_against = mysql_fetch_assoc($scores_against);
      $total_scores_against = ($row_scores_against['total_scores_1'] + $total_scores_against);
    }
    //echo("Total Against " . $total_scores_against . "<br>");
    if(($total_scores_for == 0) || ($total_scores_against == 0))
    {
      $team_percentage = 0;
    }
    else
    {
      $team_percentage = (($total_scores_for/$total_scores_against)*100);
    }
    
    //echo("Scores For " . $total_scores_for . "<br>");
    //echo("Scores Against " . $total_scores_against . "<br>");
    //echo("Total Percent " . $team_percentage . "<br>");

    $querytoexecute = "Update `vbsa3364_vbsa2`.`Team_entries` SET scrs_for_finals = " . $total_scores_for . ", scrs_against_finals = " . $total_scores_against . ", scrs_percent_finals = " . $team_percentage . " WHERE comptype='Billiards' AND team_cal_year = YEAR( CURDATE( ) ) AND team_id = " . $row_scores_ladder['team_id'] . " AND team_season = '" . $current_season . "' ";
    //echo("Update " . $querytoexecute . "<br>");
    $result = mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

    // finish calculation
  }

  // added 8/10/2024, Elimination Finals for Billiards

  //calculates Team_entries EF1tot by (SELECT SUM(scrs.EF1)
  $querytoexecute = "Update `vbsa3364_vbsa2`.`Team_entries` T1 
  JOIN
  (   SELECT team_id, SUM(EF1) AS elim1tot 
      FROM scrs
      GROUP BY team_id
  ) T2
  SET T1.EF1tot = T2.elim1tot 
  WHERE T2.team_id = T1.team_id";
    
  $result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>1. Table was successfully updated - calculated Elimination Final 1 totals OK</font>";

  //calculates Team_entries EF2tot by (SELECT SUM(scrs.EF2)

  $querytoexecute = "Update `vbsa3364_vbsa2`.`Team_entries` T1 
  JOIN
  (   SELECT team_id, SUM(EF2) AS elim2tot 
      FROM scrs
      GROUP BY team_id
  ) T2
  SET T1.EF2tot = T2.elim2tot 
  WHERE T2.team_id = T1.team_id";
    
  $result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>2. Table was successfully updated - calculated Elimination Final 2 totals OK</font>";

  //tested ok calculates Team_entries SF1tot by (SELECT SUM(scrs.SF1)

  $querytoexecute = "Update `vbsa3364_vbsa2`.`Team_entries` T1 
  JOIN
  (   SELECT team_id, SUM(SF1) AS semi1tot 
      FROM scrs
      GROUP BY team_id
  ) T2
  SET T1.SF1tot = T2.semi1tot 
  WHERE T2.team_id = T1.team_id";
  	
  $result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>3. Table was successfully updated - calculated Semi Final 1 totals OK</font>";

  //tested ok calculates Team_entries SF2tot by (SELECT SUM(scrs.SF2)

  $querytoexecute = "Update `vbsa3364_vbsa2`.`Team_entries` T1 
  JOIN
  (   SELECT team_id, SUM(SF2) AS semi2tot 
      FROM scrs
      GROUP BY team_id
  ) T2
  SET T1.SF2tot = T2.semi2tot 
  WHERE T2.team_id = T1.team_id";
  	
  $result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>4. Table was successfully updated - calculated Semi Final 2 totals OK</font>";

  //tested ok calculates Team_entries GFtot by (SELECT SUM(scrs.GF)

  $querytoexecute = "Update `vbsa3364_vbsa2`.`Team_entries` T1 
  JOIN
  (   SELECT team_id, SUM(GF) AS Grandtot 
      FROM scrs
      GROUP BY team_id
  ) T2
  SET T1.GFtot = T2.Grandtot 
  WHERE T2.team_id = T1.team_id
  ";
  	
  $result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>5. Table was successfully updated - calculated Grand Final totals OK</font>";

  // end TEAM ENTRIES FINALS

  echo '<br/>'.'<br/>';
  echo "<font face='arial'>Update Team Entries table - Calculate Ladder. match points total, count match win, count match drawn, percentage, played, for, against";

  //tested ok calculates count_matches in the Team entries table from the scrs table

  $querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
  INNER JOIN (
  SELECT team_id, COUNT(r01s) + COUNT(r02s) + COUNT(r03s) + COUNT(r04s) + COUNT(r05s) + COUNT(r06s) + COUNT(r07s) + COUNT(r08s) 
  + COUNT(r09s) + COUNT(r10s) + COUNT(r11s) + COUNT(r12s) + COUNT(r13s) + COUNT(r14s) + COUNT(r15s) + COUNT(r16s) 
  + COUNT(r17s) + COUNT(r18s) AS matches
  FROM scrs
  GROUP BY team_id
  ) T2 ON T1.team_id = T2.team_id
  SET T1.count_matches = T2.matches
  WHERE team_cal_year = YEAR(CURDATE( ))";
  	
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>Error count_matches was not calculated</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>1. Table was successfully updated - calculated count_matches (Individual matches) OK</font>";

  //tested ok calculates count_matches in the Team entries table from the scrs table

  $querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
  INNER JOIN (
    SELECT team_id, COUNT( r01s ) + COUNT( r02s ) + COUNT( r03s ) + COUNT( r04s ) + COUNT( r05s ) + COUNT( r06s ) + COUNT( r07s ) + COUNT( r08s ) 
  + COUNT( r09s ) + COUNT( r10s ) + COUNT( r11s ) + COUNT( r12s ) + COUNT( r13s ) + COUNT( r14s ) + COUNT( r15s ) + COUNT( r16s ) 
  + COUNT( r17s ) + COUNT( r18s ) as byes
    FROM scrs
    WHERE scrs.MemberID =1
    GROUP BY team_id
  ) T2 ON T1.team_id = T2.team_id
  SET T1.count_byes = T2.byes
  WHERE team_cal_year = YEAR(CURDATE( ))";
  	
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>Error count_byes was not calculated</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>2. Table was successfully updated - calculated count_byes (Byes) OK</font>";

  //tested ok calculates count_forfeits in the Team entries table from the scrs table

  $querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
  INNER JOIN (
    SELECT team_id, COUNT( r01s ) + COUNT( r02s ) + COUNT( r03s ) + COUNT( r04s ) + COUNT( r05s ) + COUNT( r06s ) + COUNT( r07s ) + COUNT( r08s ) 
  + COUNT( r09s ) + COUNT( r10s ) + COUNT( r11s ) + COUNT( r12s ) + COUNT( r13s ) + COUNT( r14s ) + COUNT( r15s ) + COUNT( r16s ) 
  + COUNT( r17s ) + COUNT( r18s ) as forfeits
    FROM scrs
    WHERE scrs.MemberID =1000
    GROUP BY team_id
  ) T2 ON T1.team_id = T2.team_id
  SET T1.count_forfeits = T2.forfeits
  WHERE team_cal_year = YEAR(CURDATE( ))";
  	
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>Error count_forfeits was not calculated</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>3. Table was successfully updated - calculated count_forfeits (Forfeits) OK</font>";

  //tested ok calculates rounds_played in the Team entries

  $querytoexecute = "UPDATE `vbsa3364_vbsa2`.`Team_entries` 	
  SET `rounds_played` = 	
  (SELECT SUM(count_matches-count_byes-count_forfeits)/players+count_forfeits)";
  	
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>Error rounds_played was not calculated</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>4. Table was successfully updated - calculated rounds_played (progressive round count) OK</font>";

  //tested ok calculates points avail

  $querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
  SET pts_avail = 	
  (SELECT CASE
  WHEN comptype = 'Snooker' THEN (rounds_played+count_byes)*players*3
  WHEN comptype = 'Billiards' THEN (rounds_played+count_byes)*players*2
  ELSE 0
  END as pts_avail)
  WHERE team_cal_year = YEAR(CURDATE( ))";
  	
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>Error pts_avail was not calculated</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>5. Table was successfully updated - calculated pts_avail (Points available from rounds completed) OK</font>";

  //tested ok calculates points against

  $querytoexecute = "UPDATE `vbsa3364_vbsa2`.`Team_entries` 	
  SET pts_against = pts_avail-total_score
  WHERE team_cal_year = YEAR(CURDATE( ))";
  	
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>Error pts_against was not calculated</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>6. Table was successfully updated - calculated pts_against (Against) OK</font>";

  //tested ok calculates percentage

  $querytoexecute = "UPDATE `vbsa3364_vbsa2`.`Team_entries` 	
  SET team_perc = (total_score/pts_against)*100
  WHERE team_cal_year = YEAR(CURDATE( ))";
  	
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>Error team_perc was not calculated</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>7. Table was successfully updated - calculated team_perc (Perentage) OK</font>";

  //tested ok calculates total match points in Team_entries
  $querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
  SET match_pts_total = (SELECT SUM(P01)+SUM(P02)+SUM(P03)+SUM(P04)+SUM(P05)+SUM(P06)+SUM(P07)+SUM(P08)+SUM(P09)+SUM(P10)+SUM(P11)+SUM(P12)+SUM(P13)+SUM(P14)+SUM(P15)+SUM(P16)+SUM(P17)+SUM(P18))
  WHERE team_cal_year = YEAR(CURDATE( )) 
  ";
  	
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>Error Match points total was not updated</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>8. Table was successfully updated - calculated match_pts_total (Points) OK</font>";

  //calculates match_won_count in Team_entries
  $querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
  SET match_won_count = (SELECT COUNT(IF(P01=4,1,NULL))+COUNT(IF(P02=4,1,NULL))+COUNT(IF(P03=4,1,NULL))+COUNT(IF(P04=4,1,NULL))+COUNT(IF(P05=4,1,NULL))+COUNT(IF(P06=4,1,NULL))
  +COUNT(IF(P07=4,1,NULL)) +COUNT(IF(P08=4,1,NULL))+COUNT(IF(P09=4,1,NULL))+COUNT(IF(P10=4,1,NULL))+COUNT(IF(P11=4,1,NULL))+COUNT(IF(P12=4,1,NULL))
  +COUNT(IF(P13=4,1,NULL))+COUNT(IF(P14=4,1,NULL)) +COUNT(IF(P15=4,1,NULL))+COUNT(IF(P16=4,1,NULL))+COUNT(IF(P17=4,1,NULL))+COUNT(IF(P18=4,1,NULL)))
  WHERE team_cal_year = YEAR(CURDATE( )) 
  ";
  	
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>Error match_won_count was not updated</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>9. Table was successfully updated - calculated match_won_count (Won) OK</font>";

  //tested ok calculates match_drawn_count in Team_entries
  $querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
  SET match_drawn_count = (SELECT COUNT(IF(P01=2,1,NULL))+COUNT(IF(P02=2,1,NULL))+COUNT(IF(P03=2,1,NULL))+COUNT(IF(P04=2,1,NULL))+COUNT(IF(P05=2,1,NULL))+COUNT(IF(P06=2,1,NULL))
  +COUNT(IF(P07=2,1,NULL)) +COUNT(IF(P08=2,1,NULL))+COUNT(IF(P09=2,1,NULL))+COUNT(IF(P10=2,1,NULL))+COUNT(IF(P11=2,1,NULL))+COUNT(IF(P12=2,1,NULL))
  +COUNT(IF(P13=2,1,NULL))+COUNT(IF(P14=2,1,NULL)) +COUNT(IF(P15=2,1,NULL))+COUNT(IF(P16=2,1,NULL))+COUNT(IF(P17=2,1,NULL))+COUNT(IF(P18=2,1,NULL)))
  WHERE team_cal_year = YEAR(CURDATE( )) 
  ";
  	
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>Error match_drawn_count was not updated</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>10. Table was successfully updated - calculated match_drawn_count (Drawn) OK</font>";

  //END Calculate Ladder. match points total, count match win, count match drawn, percentage, played, for, against

  echo '<br/>'.'<br/>';
  echo "<font face='arial'>Update Members table - Set Matches played (Snooker & Billiards), Junior Age Group and Clear previous year financial members<br>";

  //echo "<br/><br/><font face='arial'>Calculation Follows<br>";
  //SET CURRENT YEAR BILLIARDS AND SNOOKER MATCHES PLAYED TOTALS
  // need to check for which season S1 or S2 ???????

  $month = date('m');
  $current_year = date('Y');


  if($month < '08')
  {
    $season = 'S1';
  }
  else
  {
    $season = 'S2';
  }

  // get current round
  for($i = 0; $i < 18; $i++)
  {
    // format round number
    if($i > 8)
    {
        $rnd_no = ($i+1);
    }
    else
    {
        $rnd_no = '0' . ($i+1);
    }

    $sql = "Select count(r" . $rnd_no . "pos) as Count, scr_season FROM scrs WHERE current_year_scrs = '" . $current_year . "' AND scr_season = '" . $season . "'";
    //echo($sql . "<br>");
    $count_text = mysql_query($sql, $connvbsa) or die(mysql_error());
    $build_data = mysql_fetch_assoc($count_text);
    //$season = $build_data['scr_season'];
    if(($build_data['Count'] == 0) || ($rnd_no == 18))
    {
      $last_round_snooker_s1 = ($rnd_no-1);
      break;
    }
    
  }

  // get current count text
  for($i = 0; $i < $last_round_snooker_s1; $i++)
  {
    // format round number
    if($i > 8)
    {
        $rnd_no = ($i+1);
    }
    else
    {
        $rnd_no = '0' . ($i+1);
    }
    $count_text_1 = $count_text_1 . " COUNT(r" . $rnd_no . "pos) + ";
  }
  $count_text_1 = substr($count_text_1, 0, strlen($count_text_1)-3);

  $count_text_2 = "COUNT(`r01pos`)+COUNT(`r02pos`)+COUNT(`r03pos`)+COUNT(`r04pos`)+COUNT(`r05pos`)+COUNT(`r06pos`)+COUNT(`r07pos`)+COUNT(`r08pos`)+COUNT(`r09pos`) +COUNT(`r10pos`)+COUNT(`r11pos`)+COUNT(`r12pos`)+COUNT(`r13pos`)+COUNT(`r14pos`)+COUNT(`r15pos`)+COUNT(`r16pos`)+COUNT(`r17pos`)+COUNT(`r18pos`)";

  // get previous count text
  for($i = ($last_round_snooker_s1); $i < 18; $i++)
  {
    // format round number
    if($i > 8)
    {
        $rnd_no = ($i+1);
    }
    else
    {
        $rnd_no = '0' . ($i+1);
    }
    $count_text_3 = $count_text_3 . " COUNT(r" . $rnd_no . "pos) + ";
  }
  $count_text_3 = substr($count_text_3, 0, strlen($count_text_3)-3);
  
  if($season == "S1")
  {
    $season_1 = "S1";
    $year_1 = $current_year;
    $season_2 = "S2";
    $year_2 = ($current_year-1);
    $season_3 = "S1";
    $year_3 = ($current_year-1);
  }
  elseif($season == "S2")
  {
    $season_1 = "S2";
    $year_1 = $current_year;
    $season_2 = "S1";
    $year_2 = $current_year;
    $season_3 = "S2";
    $year_3 = ($current_year-1);
  }
  
  // clear previous data from members total snooker and billiards
  $querytoexecute = "Update vbsa3364_vbsa2.members SET totplayed_curr = 0, totplaybill_curr = 0";

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - Not cleaned  - matches played in current year in members table</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'> 1. Set totplayed_curr and totplaybill matches to 0 in members table successfully</font><br>";
  //updates totplayed_curr - Snooker matches played in members table
  // totplayed_curr = total of matches played in S1 and S2 of the current year
  /*
  $querytoexecute = "Update vbsa3364_vbsa2.members, 
  (SELECT SUM(count_played) AS curr, scrs.MemberID
  FROM scrs
  WHERE current_year_scrs = YEAR(NOW()) AND game_type='Snooker'
  GROUP BY scrs.MemberID) AS played
  SET members.totplayed_curr = played.curr
  WHERE members.MemberID = played.MemberID ";
  */
  
  /*$querytoexecute = "Update vbsa3364_vbsa2.members, (SELECT (SELECT " . $count_text_2 . ") AS Sprev, scrs.MemberID FROM scrs WHERE current_year_scrs = " . ($current_year-1) . " AND game_type='Snooker' AND scr_season = 'S2' GROUP BY scrs.MemberID) AS played SET members.totplayed_prev = played.Sprev WHERE members.MemberID = played.MemberID";

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>2. Error - Not Set totplayed_prev Snooker matches played in current year in members table</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'> 1. Set totplayed_prev - Snooker matches played in current year in members table successfully</font><br>";
  */
/*
  $querytoexecute = "Update vbsa3364_vbsa2.members, (SELECT (SELECT " . $count_text_1 . ") AS Scurr, scrs.MemberID FROM scrs WHERE current_year_scrs = " . $current_year . " AND game_type='Snooker' AND scr_season = 'S1' GROUP BY scrs.MemberID) AS played SET members.totplayed_curr = played.Scurr WHERE members.MemberID = played.MemberID";
  
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error - Not Set totplayed_curr Snooker matches played in current year in members table</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'> 1. Set totplayed_curr - Snooker matches played in current year in members table successfully</font>";
*/

  if($last_round_snooker_s1 > 0)
  {
    $query_select_count_text_1 = 
    "Select scrs.MemberID,
      (Select " . $count_text_1 . ") as count1 
    FROM scrs, members
    WHERE current_year_scrs = " . $year_1 . "
      AND members.MemberID = scrs.MemberID
      AND game_type = 'Snooker'
      AND scr_season = '" . $season_1 . "'
    GROUP BY scrs.MemberID

    UNION ALL";
  }
  
  $querytoexecute = "Update members AS M Join
  (SELECT P.MemberID, SUM(P.count1) count1
  FROM 
  ( " . $query_select_count_text_1 . "
    
    Select scrs.MemberID,
      (SELECT " . $count_text_2 . ") as count1 
    FROM scrs, members
    WHERE current_year_scrs = " . $year_2 . "
      AND members.MemberID = scrs.MemberID
      AND game_type = 'Snooker'
      AND scr_season = '" . $season_2 . "'
    GROUP BY scrs.MemberID

    UNION ALL

    Select scrs.MemberID,
      (SELECT " . $count_text_3 . ") as count1 
    FROM scrs, members
    WHERE current_year_scrs = " . $year_3 . "
      AND members.MemberID = scrs.MemberID
      AND game_type = 'Snooker'
      AND scr_season = '" . $season_3 . "'
    GROUP BY scrs.MemberID
  ) AS P
  GROUP BY P.MemberID) as D
  Set M.totplayed_curr = (D.count1) Where M.MemberID = D.MemberID;";
  
  //echo("Snooker " . $querytoexecute . "<br>");

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error - Not Set totplayed_curr Snooker matches played in current year in members table</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'> 1. Set totplayed_curr - Snooker matches played in current year in members table successfully</font><br>";

  //testing ok - calculates totplaybill_curr - Billiard matches played in members table
  // totplaybill_curr = total billiard matches played in S1 and S2 of the current year
  /*
  $querytoexecute = "Update vbsa3364_vbsa2.members, 
  (SELECT SUM(count_played) AS currbill, scrs.MemberID
  FROM scrs
  WHERE current_year_scrs = YEAR(NOW( ) ) AND game_type='Billiards'
  GROUP BY scrs.MemberID) AS played
  SET members.totplaybill_curr = played.currbill
  WHERE members.MemberID = played.MemberID";
  */
  /*
  $querytoexecute = "Update vbsa3364_vbsa2.members, (SELECT (SELECT " . $count_text_2 . ") AS Bprev, scrs.MemberID FROM scrs WHERE current_year_scrs = 2022 AND game_type='Billiards' AND scr_season = 'S2' GROUP BY scrs.MemberID) AS played SET members.totplaybill_prev = played.Bprev WHERE members.MemberID = played.MemberID";
  */
  //echo($querytoexecute . "<br>");
  /*$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>4. Error - Not Set totplaybill_prev Billiards matches played in current year in members table</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'> 1. Set totplaybill_prev - Billiards matches played in current year in members table successfully</font>";
  
  $querytoexecute = "Update vbsa3364_vbsa2.members, (SELECT (SELECT " . $count_text_1 . ") AS Bcurr, scrs.MemberID FROM scrs WHERE current_year_scrs = 2023 AND game_type='Billiards' AND scr_season = 'S1' GROUP BY scrs.MemberID) AS played SET members.totplaybill_curr = played.Bcurr WHERE members.MemberID = played.MemberID";
  */
  
  if($last_round_snooker_s1 > 0)
  {
    $query_select_count_text_1 = "Select scrs.MemberID,
      (Select " . $count_text_1 . ") as count1 
    FROM scrs, members
    WHERE current_year_scrs = " . $year_1 . "
      AND members.MemberID = scrs.MemberID
      AND game_type = 'Billiards'
      AND scr_season = '" . $season_1 . "'
    GROUP BY scrs.MemberID

    UNION ALL";
  }
  else
  {
    $query_select_count_text_1 = "";
  }

  $querytoexecute = "Update members AS M Join
  (SELECT P.MemberID, SUM(P.count1) count1
  FROM 
  ( " . $query_select_count_text_1 . "

    Select scrs.MemberID,
      (SELECT " . $count_text_2 . ") as count1 
    FROM scrs, members
    WHERE current_year_scrs = " . $year_2 . "
      AND members.MemberID = scrs.MemberID
      AND game_type = 'Billiards'
      AND scr_season = '" . $season_2 . "'
    GROUP BY scrs.MemberID
      
    UNION ALL
      
    SELECT scrs.MemberID,
      (SELECT " . $count_text_3 . ") as count1 
    FROM scrs, members
    WHERE current_year_scrs = " . $year_3 . "
      AND members.MemberID = scrs.MemberID
      AND game_type = 'Billiards'
      AND scr_season = '" . $season_3 . "'
    GROUP BY scrs.MemberID
  ) AS P
  GROUP BY P.MemberID) as D
  Set M.totplaybill_curr = (D.count1) Where M.MemberID = D.MemberID;";
  
  //echo("Billiards " . $querytoexecute . "<br>");
  
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error - Not Set totplaybill_curr Billiards matches played in current year in members table</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'> 2. Set totplaybill_curr - Billiards matches played in current year in members table successfully</font>";


  //END CURRENT YEAR BILLIARDS AND SNOOKER MATCHES PLAYED TOTALS

  //testing ok - sets Junior age group in members table
  $querytoexecute = "Update vbsa3364_vbsa2.members	
  SET Junior = 	
  (SELECT CASE
  WHEN dob_year>=year(curdate())-18 AND dob_year<=year(curdate())-16 THEN 'U18'
  WHEN dob_year>=year(curdate())-15 AND dob_year<=year(curdate())-13 THEN 'U15'
  WHEN dob_year>=year(curdate())-12 AND dob_year<=year(curdate()) THEN 'U12'
  ELSE 'na'
  END)";
//echo($querytoexecute . "<br>");
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error - Junior age group not set</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>3. Set column Junior Age Group in members table successfully</font>";

  //testing ok - sets last year paid members to null if year of payment was previous year
  $querytoexecute = "UPDATE members SET paid_memb=NULL, paid_how=NULL, paid_date=NULL
  WHERE YEAR(paid_date) = YEAR(CURDATE())-1";

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>4. Error - Did not set last year paid members to null</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'> 4. Set last year paid members to null</font>";
    mysql_close ($connvbsa);
}
else
{
  echo "<form id='form1' name='form1' method='post' action='../Admin_update_tables/Update_Scores_Rank_STEP3.php?submit=1'>";
  echo "<input type='submit' id='submit' name='submit'>";
  echo "</form>";
}

?>
</center>
</body>
</html>
