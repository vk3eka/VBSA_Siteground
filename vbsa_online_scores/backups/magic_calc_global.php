<?php

include('connection.inc');
include('header.php'); 

//$grade = trim($_POST['Grade']);
//$type = $_POST['Type'];
//$teamgrade = $_POST['TeamGrade'];
//$current_year = $_SESSION['year'];
//$season = $_SESSION['season'];
//$round = $_POST['RoundSelected'];

// get current round season 1 Snooker
for($i = 1; $i <= 18; $i++)
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
  $sql = "Select count(r" . $rnd_no . "pos) as Count, scr_season FROM scrs WHERE current_year_scrs = 2023";
  $result = $dbcnx_client->query($sql);
  $build_data = $result->fetch_assoc();
  $season = $build_data['scr_season'];
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
  $count_text_1 = $count_text_1 . " COUNT(r" . $rnd_no . "s) + ";
}
$count_text_1 = substr($count_text_1, 0, strlen($count_text_1)-3);

// get previuos count text
for($i = $last_round_snooker_s1; $i < 18; $i++)
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
  $count_text_2 = $count_text_2 . " COUNT(r" . $rnd_no . "s) + ";
}
$count_text_2 = substr($count_text_2, 0, strlen($count_text_2)-3);

$count_text_3 = "COUNT(`r01pos`)+COUNT(`r02pos`)+COUNT(`r03pos`)+COUNT(`r04pos`)+COUNT(`r05pos`)+COUNT(`r06pos`)+COUNT(`r07pos`)+COUNT(`r08pos`)+COUNT(`r09pos`) +COUNT(`r10pos`)+COUNT(`r11pos`)+COUNT(`r12pos`)+COUNT(`r13pos`)+COUNT(`r14pos`)+COUNT(`r15pos`)+COUNT(`r16pos`)+COUNT(`r17pos`)+COUNT(`r18pos`)";
//echo("Start " . $count_text_1 . "<br>");
//echo("End " . $count_text_2 . "<br>");




/* sql queries for the calculate all

Step 1
1 Total position calculate  
*/
$sql = "Update `vbsa3364_vbsa2`.`scrs` SET `total_position` =(IFNULL(scrs.r01pos,0)) +(IFNULL(scrs.r02pos,0)) +(IFNULL(scrs.r03pos,0)) +(IFNULL(scrs.r04pos,0)) +(IFNULL(scrs.r05pos,0)) +(IFNULL(scrs.r06pos,0)) +(IFNULL(scrs.r07pos,0)) +(IFNULL(scrs.r08pos,0)) +(IFNULL(scrs.r09pos,0)) +(IFNULL(scrs.r10pos,0)) +(IFNULL(scrs.r11pos,0)) +(IFNULL(scrs.r12pos,0)) +(IFNULL(scrs.r13pos,0)) +(IFNULL(scrs.r14pos,0)) +(IFNULL(scrs.r15pos,0)) +(IFNULL(scrs.r16pos,0)) +(IFNULL(scrs.r17pos,0)) +(IFNULL(scrs.r18pos,0)) WHERE current_year_scrs = YEAR(CURDATE( ))";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update total position calculate: " . mysqli_error($dbcnx_client));
} 
/*
2 Count position calculate  
*/
$sql = "Update `vbsa3364_vbsa2`.`scrs` SET `count_position` =(SELECT COUNT(`r01pos`)+COUNT(`r02pos`)+COUNT(`r03pos`)+COUNT(`r04pos`)+COUNT(`r05pos`)+COUNT(`r06pos`)+COUNT(`r07pos`)+COUNT(`r08pos`)+COUNT(`r09pos`) +COUNT(`r10pos`)+COUNT(`r11pos`)+COUNT(`r12pos`)+COUNT(`r13pos`)+COUNT(`r14pos`)+COUNT(`r15pos`)+COUNT(`r16pos`)+COUNT(`r17pos`)+COUNT(`r18pos`)) WHERE current_year_scrs = YEAR(CURDATE( ))";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update count position calculate: " . mysqli_error($dbcnx_client));
} 
/*
3 Average position calculate  
*/
$sql = "Update `vbsa3364_vbsa2`.`scrs` SET average_position=total_position/count_position WHERE current_year_scrs = YEAR(CURDATE( ))"; /// divide by zero error
//echo("SQL = " . $sql . "<br>");
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update average position calculate: " . mysqli_error($dbcnx_client));
}

// update count_played with count of all rounds played

//4 Count matches played  

$sql = "Update `vbsa3364_vbsa2`.`scrs` SET count_played =(SELECT COUNT(r01s)+COUNT(r02s)+COUNT(r03s)+COUNT(r04s)+COUNT(r05s)+COUNT(r06s)+COUNT(r07s)+COUNT(r08s)+COUNT(r09s) +COUNT(r10s)+COUNT(r11s)+COUNT(r12s)+COUNT(r13s)+COUNT(r14s)+COUNT(r15s)+COUNT(r16s)+COUNT(r17s)+COUNT(r18s)) WHERE current_year_scrs = YEAR(CURDATE( ))";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update count_played calculate: " . mysqli_error($dbcnx_client));
}

/*
$sql = "Update `vbsa3364_vbsa2`.`scrs` SET count_played =(SELECT COUNT(r01s)+COUNT(r02s)+COUNT(r03s)+COUNT(r04s)+COUNT(r05s)) WHERE current_year_scrs = 2023 AND scr_season = 'S1' AND game_type = 'Snooker'";
echo("1 Snooker " . $sql . "<br>");
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Count 1 matches played: " . mysqli_error($dbcnx_client));
}

$sql = "Update `vbsa3364_vbsa2`.`scrs` SET count_played =(SELECT COUNT(r06s)+COUNT(r07s)+COUNT(r08s)+COUNT(r09s) +COUNT(r10s)+COUNT(r11s)+COUNT(r12s)+COUNT(r13s)+COUNT(r14s)+COUNT(r15s)+COUNT(r16s)+COUNT(r17s)+COUNT(r18s)) WHERE current_year_scrs = 2022 AND scr_season = 'S2' AND game_type = 'Snooker'";
echo("2 Snooker " . $sql . "<br>");
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Count 2 matches played: " . mysqli_error($dbcnx_client));
}

$sql = "Update `vbsa3364_vbsa2`.`scrs` SET count_played =(SELECT COUNT(r01s)+COUNT(r02s)+COUNT(r03s)+COUNT(r04s)+COUNT(r05s)) WHERE current_year_scrs = 2023 AND scr_season = 'S1' AND game_type = 'Billiards'";
echo("1 Billiards " . $sql . "<br>");
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Count 1 matches played: " . mysqli_error($dbcnx_client));
}

$sql = "Update `vbsa3364_vbsa2`.`scrs` SET count_played =(SELECT COUNT(r06s)+COUNT(r07s)+COUNT(r08s)+COUNT(r09s) +COUNT(r10s)+COUNT(r11s)+COUNT(r12s)+COUNT(r13s)+COUNT(r14s)+COUNT(r15s)+COUNT(r16s)+COUNT(r17s)+COUNT(r18s)) WHERE current_year_scrs = 2022 AND scr_season = 'S2' AND game_type = 'Billiards'";
echo("2 Billiards " . $sql . "<br>");
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Count 2 matches played: " . mysqli_error($dbcnx_client));
}
*/

/*
5 Calculated points won 
*/
$sql = "Update `vbsa3364_vbsa2`.`scrs` SET `pts_won` =(IFNULL(scrs.r01s,0)) +(IFNULL(scrs.r02s,0)) +(IFNULL(scrs.r03s,0)) +(IFNULL(scrs.r04s,0)) +(IFNULL(scrs.r05s,0)) +(IFNULL(scrs.r06s,0)) +(IFNULL(scrs.r07s,0)) +(IFNULL(scrs.r08s,0)) +(IFNULL(scrs.r09s,0)) +(IFNULL(scrs.r10s,0)) +(IFNULL(scrs.r11s,0)) +(IFNULL(scrs.r12s,0)) +(IFNULL(scrs.r13s,0)) +(IFNULL(scrs.r14s,0)) +(IFNULL(scrs.r15s,0)) +(IFNULL(scrs.r16s,0)) +(IFNULL(scrs.r17s,0)) +(IFNULL(scrs.r18s,0)) WHERE current_year_scrs = YEAR(CURDATE( ))";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Calculated points won: " . mysqli_error($dbcnx_client));
}
/*
6 Set points won = 0 for Bye  
*/
$sql = "Update `vbsa3364_vbsa2`.`scrs` SET pts_won=0 WHERE (MemberID=1 OR MemberID=100 OR MemberID=1000) AND current_year_scrs = YEAR(CURDATE( ))";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Set points won = 0 for Bye: " . mysqli_error($dbcnx_client));
}
/*

7 Total Ranking Points  
*/
$sql = "Update `vbsa3364_vbsa2`.`scrs` SET total_RP=allocated_rp*pts_won WHERE current_year_scrs = YEAR(CURDATE( ))"; // total_RP cannot be null
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Set points won = 0 for Bye: " . mysqli_error($dbcnx_client));
}
/*
8 Total available point 
*/
$sql = "Update vbsa3364_vbsa2.scrs SET avail_pts = (SELECT CASE WHEN game_type = 'Snooker' THEN count_played*3 WHEN game_type = 'Billiards' THEN count_played*2 ELSE 0 END) WHERE current_year_scrs = YEAR(CURDATE( ))";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Total available point: " . mysqli_error($dbcnx_client));
}
/*
9 Player Percentage 
*/
$sql = "Update `vbsa3364_vbsa2`.`scrs` SET percent_won = pts_won/avail_pts*100 WHERE current_year_scrs = YEAR(CURDATE( ))"; // divide by zero
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Player Percentage: " . mysqli_error($dbcnx_client));
}
/*
1-18 TO1 match total points 
*/
$sql = "Update vbsa3364_vbsa2.Team_entries T1 INNER JOIN ( SELECT team_id, SUM(scrs.r01s) as total FROM scrs GROUP BY team_id ) T2 ON T1.team_id = T2.team_id SET T1.T01 = T2.total WHERE team_cal_year = YEAR(CURDATE( ))"; // for 18 rounds
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update TO1 match total points: " . mysqli_error($dbcnx_client));
}
/*

Step 2

Calculated match points  
*/
$sql = "Update vbsa3364_vbsa2.Team_entries SET P01 = (SELECT CASE WHEN players = 4 AND comptype = 'Snooker' AND T01 > 6 THEN '4' WHEN players = 4 AND comptype = 'Snooker' AND T01 = 6 THEN '2' WHEN players = 6 AND comptype = 'Snooker' AND T01 > 9 THEN '4' WHEN players = 6 AND comptype = 'Snooker' AND T01 = 9 THEN '2' WHEN players = 4 AND comptype = 'Billiards' AND T01 > 4 THEN '4' WHEN players = 4 AND comptype = 'Billiards' AND T01 = 4 THEN '2' WHEN players = 6 AND comptype = 'Billiards' AND T01 > 6 THEN '4' WHEN players = 6 AND comptype = 'Billiards' AND T01 = 6 THEN '2' WHEN players = 4 AND comptype = '2x2' AND T01 > 6 THEN '4' WHEN players = 4 AND comptype = '2x2' AND T01 = 6 THEN '2' ELSE 0 END as P01) WHERE team_cal_year = YEAR(CURDATE( ))";   // 18 rounds
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Calculated match points: " . mysqli_error($dbcnx_client));
}
/*

Step 3

Semi Final 1  
*/
$sql = "Update `vbsa3364_vbsa2`.`Team_entries` T1 JOIN ( SELECT team_id, SUM(SF1) AS semi1tot FROM scrs GROUP BY team_id ) T2 SET T1.SF1tot = T2.semi1tot WHERE T2.team_id = T1.team_id";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Semi Final 1: " . mysqli_error($dbcnx_client));
}
/*
Semi Final 2  
*/
$sql = "Update `vbsa3364_vbsa2`.`Team_entries` T1 JOIN ( SELECT team_id, SUM(SF2) AS semi2tot FROM scrs GROUP BY team_id ) T2 SET T1.SF2tot = T2.semi2tot WHERE T2.team_id = T1.team_id";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Semi Final 2: " . mysqli_error($dbcnx_client));
}
/*
Grand Final   
*/
$sql = "Update `vbsa3364_vbsa2`.`Team_entries` T1 JOIN ( SELECT team_id, SUM(GF) AS Grandtot FROM scrs GROUP BY team_id ) T2 SET T1.GFtot = T2.Grandtot WHERE T2.team_id = T1.team_id";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Semi Final 2: " . mysqli_error($dbcnx_client));
}
/*
Match Totals (individual)  
*/
$sql = "Update vbsa3364_vbsa2.Team_entries T1 INNER JOIN ( SELECT team_id, COUNT(r01s) + COUNT(r02s) + COUNT(r03s) + COUNT(r04s) + COUNT(r05s) + COUNT(r06s) + COUNT(r07s) + COUNT(r08s) + COUNT(r09s) + COUNT(r10s) + COUNT(r11s) + COUNT(r12s) + COUNT(r13s) + COUNT(r14s) + COUNT(r15s) + COUNT(r16s) + COUNT(r17s) + COUNT(r18s) AS matches FROM scrs GROUP BY team_id ) T2 ON T1.team_id = T2.team_id SET T1.count_matches = T2.matches WHERE team_cal_year = YEAR(CURDATE( ))";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Semi Final 2: " . mysqli_error($dbcnx_client));
}
/*
Count Byes 
*/
$sql = "Update vbsa3364_vbsa2.Team_entries T1 INNER JOIN ( SELECT team_id, COUNT( r01s ) + COUNT( r02s ) + COUNT( r03s ) + COUNT( r04s ) + COUNT( r05s ) + COUNT( r06s ) + COUNT( r07s ) + COUNT( r08s ) + COUNT( r09s ) + COUNT( r10s ) + COUNT( r11s ) + COUNT( r12s ) + COUNT( r13s ) + COUNT( r14s ) + COUNT( r15s ) + COUNT( r16s ) + COUNT( r17s ) + COUNT( r18s ) as byes FROM scrs WHERE scrs.MemberID =1 GROUP BY team_id ) T2 ON T1.team_id = T2.team_id SET T1.count_byes = T2.byes WHERE team_cal_year = YEAR(CURDATE( ))";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Count Byes: " . mysqli_error($dbcnx_client));
}
/*
Count Forfeits 
*/
$sql = "Update vbsa3364_vbsa2.Team_entries T1 INNER JOIN ( SELECT team_id, COUNT( r01s ) + COUNT( r02s ) + COUNT( r03s ) + COUNT( r04s ) + COUNT( r05s ) + COUNT( r06s ) + COUNT( r07s ) + COUNT( r08s ) + COUNT( r09s ) + COUNT( r10s ) + COUNT( r11s ) + COUNT( r12s ) + COUNT( r13s ) + COUNT( r14s ) + COUNT( r15s ) + COUNT( r16s ) + COUNT( r17s ) + COUNT( r18s ) as forfeits FROM scrs WHERE scrs.MemberID =1000 GROUP BY team_id ) T2 ON T1.team_id = T2.team_id SET T1.count_forfeits = T2.forfeits WHERE team_cal_year = YEAR(CURDATE( ))";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Count Forfeits: " . mysqli_error($dbcnx_client));
}
/*
Count Rounds Played 
*/
$sql = "Update `vbsa3364_vbsa2`.`Team_entries` SET `rounds_played` = (SELECT SUM(count_matches-count_byes-count_forfeits)/players+count_forfeits)";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Count Rounds Played: " . mysqli_error($dbcnx_client));
}
/*
Points Available 
*/
$sql = "Update vbsa3364_vbsa2.Team_entries SET pts_avail = (SELECT CASE WHEN comptype = 'Snooker' THEN (rounds_played+count_byes)*players*3 WHEN comptype = 'Billiards' THEN (rounds_played+count_byes)*players*2 ELSE 0 END as pts_avail) WHERE team_cal_year = YEAR(CURDATE( ))";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Points Available: " . mysqli_error($dbcnx_client));
}
/*
Points Against 
*/
$sql = "Update `vbsa3364_vbsa2`.`Team_entries` SET pts_against = pts_avail-total_score WHERE team_cal_year = YEAR(CURDATE( ))";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Points Against: " . mysqli_error($dbcnx_client));
}
/*
Team Percentage 
*/
$sql = "Update `vbsa3364_vbsa2`.`Team_entries` SET team_perc = (total_score/pts_against)*100 WHERE team_cal_year = YEAR(CURDATE( ))";  /// divide by zero
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Team Percentage: " . mysqli_error($dbcnx_client));
}
/*
Match Point Total 
*/
$sql = "Update vbsa3364_vbsa2.Team_entries SET match_pts_total = (SELECT SUM(P01)+SUM(P02)+SUM(P03)+SUM(P04)+SUM(P05)+SUM(P06)+SUM(P07)+SUM(P08)+SUM(P09)+SUM(P10)+SUM(P11)+SUM(P12)+SUM(P13)+SUM(P14)+SUM(P15)+SUM(P16)+SUM(P17)+SUM(P18)) WHERE team_cal_year = YEAR(CURDATE( ))";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Match Point Total: " . mysqli_error($dbcnx_client));
}
/*
Matches Won 
*/
$sql = "Update vbsa3364_vbsa2.Team_entries SET match_won_count = (SELECT COUNT(IF(P01=4,1,NULL))+COUNT(IF(P02=4,1,NULL))+COUNT(IF(P03=4,1,NULL))+COUNT(IF(P04=4,1,NULL))+COUNT(IF(P05=4,1,NULL))+COUNT(IF(P06=4,1,NULL)) +COUNT(IF(P07=4,1,NULL)) +COUNT(IF(P08=4,1,NULL))+COUNT(IF(P09=4,1,NULL))+COUNT(IF(P10=4,1,NULL))+COUNT(IF(P11=4,1,NULL))+COUNT(IF(P12=4,1,NULL)) +COUNT(IF(P13=4,1,NULL))+COUNT(IF(P14=4,1,NULL)) +COUNT(IF(P15=4,1,NULL))+COUNT(IF(P16=4,1,NULL))+COUNT(IF(P17=4,1,NULL))+COUNT(IF(P18=4,1,NULL))) WHERE team_cal_year = YEAR(CURDATE( ))";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Matches Won: " . mysqli_error($dbcnx_client));
}
/*
Matches Drawn 
*/
$sql = "Update vbsa3364_vbsa2.Team_entries SET match_drawn_count = (SELECT COUNT(IF(P01=2,1,NULL))+COUNT(IF(P02=2,1,NULL))+COUNT(IF(P03=2,1,NULL))+COUNT(IF(P04=2,1,NULL))+COUNT(IF(P05=2,1,NULL))+COUNT(IF(P06=2,1,NULL)) +COUNT(IF(P07=2,1,NULL)) +COUNT(IF(P08=2,1,NULL))+COUNT(IF(P09=2,1,NULL))+COUNT(IF(P10=2,1,NULL))+COUNT(IF(P11=2,1,NULL))+COUNT(IF(P12=2,1,NULL)) +COUNT(IF(P13=2,1,NULL))+COUNT(IF(P14=2,1,NULL)) +COUNT(IF(P15=2,1,NULL))+COUNT(IF(P16=2,1,NULL))+COUNT(IF(P17=2,1,NULL))+COUNT(IF(P18=2,1,NULL))) WHERE team_cal_year = YEAR(CURDATE( ))";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Matches Drawn: " . mysqli_error($dbcnx_client));
}

//update members totplayed_curr etc with count_played values from scrs

// clear previous data from members total snooker and billiards
$sql = "Update vbsa3364_vbsa2.members SET totplayed_prev = 0, totplayed_curr = 0, totplaybill_prev = 0, totplaybill_curr = 0";
//echo("SQL Clean all " . $sql . "<br>");
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not zero Matches Played: " . mysqli_error($dbcnx_client));
}
/*
// Snooker
// snooker 2022
$sql = "Update vbsa3364_vbsa2.members, (SELECT (SELECT " . $count_text_2 . ") AS Sprev, scrs.MemberID FROM scrs WHERE current_year_scrs = 2022 AND game_type='Snooker' AND scr_season = 'S2' GROUP BY scrs.MemberID) AS played SET members.totplayed_prev = played.Sprev WHERE members.MemberID = played.MemberID";
//echo("SQL 2022 Snooker " . $sql . "<br>");
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Snooker Matches Played: " . mysqli_error($dbcnx_client));
}

// snooker 2023
$sql = "Update vbsa3364_vbsa2.members, (SELECT (SELECT " . $count_text_1 . ") AS Scurr, scrs.MemberID FROM scrs WHERE current_year_scrs = 2023 AND game_type='Snooker' AND scr_season = 'S1' GROUP BY scrs.MemberID) AS played SET members.totplayed_curr = played.Scurr WHERE members.MemberID = played.MemberID";
//echo("SQL 2023 Snooker " . $sql . "<br>");
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Snooker Matches Played: " . mysqli_error($dbcnx_client));
}

// Billiards
// billiards 2022
$sql = "Update vbsa3364_vbsa2.members, (SELECT (SELECT " . $count_text_2 . ") AS Bprev, scrs.MemberID FROM scrs WHERE current_year_scrs = 2022 AND game_type='Billiards' AND scr_season = 'S2' GROUP BY scrs.MemberID) AS played SET members.totplaybill_prev = played.Bprev WHERE members.MemberID = played.MemberID";
//echo("SQL 2022 Billiards " . $sql . "<br>");
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Billiards Matches Played: " . mysqli_error($dbcnx_client));
}

// billiards 2023
$sql = "Update vbsa3364_vbsa2.members, (SELECT (SELECT " . $count_text_1 . ") AS Bcurr, scrs.MemberID FROM scrs WHERE current_year_scrs = 2023 AND game_type='Billiards' AND scr_season = 'S1' GROUP BY scrs.MemberID) AS played SET members.totplaybill_curr = played.Bcurr WHERE members.MemberID = played.MemberID";
//echo("SQL 2023 Billiards " . $sql . "<br>");
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Billiards Matches Played: " . mysqli_error($dbcnx_client));
}
*/

$sql = "Update members AS M Join
(SELECT P.MemberID, SUM(P.count1) count1
FROM 
(
  SELECT scrs.MemberID,
    (SELECT " . $count_text_2 . ") as count1 
  FROM scrs, members
  WHERE current_year_scrs = 2022
    AND members.MemberID = scrs.MemberID
    AND game_type = 'Snooker'
    AND scr_season = 'S1'
  GROUP BY scrs.MemberID

  UNION ALL
    
  SELECT scrs.MemberID,
    (SELECT " . $count_text_3 . ") as count1 
  FROM scrs, members
  WHERE current_year_scrs = 2022
    AND members.MemberID = scrs.MemberID
    AND game_type = 'Snooker'
    AND scr_season = 'S2'
  GROUP BY scrs.MemberID
    
  UNION ALL
    
  SELECT scrs.MemberID,
    (SELECT " . $count_text_1 . ") as count1 
  FROM scrs, members
  WHERE current_year_scrs = 2023
    AND members.MemberID = scrs.MemberID
    AND game_type = 'Snooker'
    AND scr_season = 'S1'
  GROUP BY scrs.MemberID
) AS P
GROUP BY P.MemberID) as D
Set M.totplayed_curr = (D.count1) Where M.MemberID = D.MemberID;";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Snooker Matches Played: " . mysqli_error($dbcnx_client));
}

$sql = "Update members AS M Join
(SELECT P.MemberID, SUM(P.count1) count1
FROM 
(
  SELECT scrs.MemberID,
    (SELECT " . $count_text_2 . ") as count1 
  FROM scrs, members
  WHERE current_year_scrs = 2022
    AND members.MemberID = scrs.MemberID
    AND game_type = 'Billiards'
    AND scr_season = 'S1'
  GROUP BY scrs.MemberID

  UNION ALL
    
  SELECT scrs.MemberID,
    (SELECT " . $count_text_3 . ") as count1 
  FROM scrs, members
  WHERE current_year_scrs = 2022
    AND members.MemberID = scrs.MemberID
    AND game_type = 'Billiards'
    AND scr_season = 'S2'
  GROUP BY scrs.MemberID
    
  UNION ALL
    
  SELECT scrs.MemberID,
    (SELECT " . $count_text_1 . ") as count1 
  FROM scrs, members
  WHERE current_year_scrs = 2023
    AND members.MemberID = scrs.MemberID
    AND game_type = 'Billiards'
    AND scr_season = 'S1'
  GROUP BY scrs.MemberID
) AS P
GROUP BY P.MemberID) as D
Set M.totplaybill_curr = (D.count1) Where M.MemberID = D.MemberID;";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Billiards Matches Played: " . mysqli_error($dbcnx_client));
}

/*
Snooker Matches Played 
*/
/*
$sql = "Update vbsa3364_vbsa2.members, (SELECT SUM(count_played) AS curr, scrs.MemberID FROM scrs WHERE current_year_scrs = year(curdate()) AND game_type='Snooker' GROUP BY scrs.MemberID) AS played SET members.totplayed_curr = played.curr WHERE members.MemberID = played.MemberID";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Snooker Matches Played: " . mysqli_error($dbcnx_client));
}
*/
/*
Billards Matches Played 
*/
/*
$sql = "Update vbsa3364_vbsa2.members, (SELECT SUM(count_played) AS currbill, scrs.MemberID FROM scrs WHERE current_year_scrs = year(curdate()) AND game_type='Billiards' GROUP BY scrs.MemberID) AS played SET members.totplaybill_prev = played.currbill WHERE members.MemberID = played.MemberID";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Billards Matches Played: " . mysqli_error($dbcnx_client));
}
*/
/*
Juniors 
*/
$sql = "Update vbsa3364_vbsa2.members SET Junior = (SELECT CASE WHEN dob_year>=year(curdate())-21 AND dob_year<=year(curdate())-19 THEN 'U21' WHEN dob_year>=year(curdate())-18 AND dob_year<=year(curdate())-16 THEN 'U18' WHEN dob_year>=year(curdate())-15 AND dob_year<=year(curdate())-13 THEN 'U15' WHEN dob_year>=year(curdate())-12 AND dob_year<=year(curdate()) THEN 'U12' ELSE 'na' END)";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Juniors: " . mysqli_error($dbcnx_client));
}
/*
Set last year paid members to null 
*/
$sql = "Update members SET paid_memb=NULL, paid_how=NULL, paid_date=NULL WHERE YEAR(paid_date) = YEAR(CURDATE())-1";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update paid members: " . mysqli_error($dbcnx_client));
}
/*
Step 4

*/
$sql = "Update vbsa3364_vbsa2.breaks SET bill_rp = (SELECT CASE WHEN brk>39 AND brk<100 THEN '40' WHEN brk>99 AND brk<200 THEN '100' WHEN brk>199 AND brk<300 THEN '200' WHEN brk>299 AND brk<400 THEN '300' WHEN brk>399 AND brk<500 THEN '400' WHEN brk>499 AND brk<600 THEN '500' ELSE 0 END as bill_rp) WHERE finals_brk='No' AND brk_type='Billiards'";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update paid members: " . mysqli_error($dbcnx_client));
}
/*
Truncate/Insert Players 
*/
$sql = "Truncate `vbsa3364_vbsa2`.`rank_aa_snooker_master`";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Truncate Snooker Ranking: " . mysqli_error($dbcnx_client));
}
/*
Snooker Ranking 
*/
$sql = "Insert INTO `rank_aa_snooker_master` SELECT scrs.MemberID as memb_id, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, members.Female, members.Junior, CURRENT_TIMESTAMP FROM `vbsa3364_vbsa2`.`scrs` LEFT JOIN `vbsa3364_vbsa2`.`members` ON members.MemberID=scrs.MemberID WHERE current_year_scrs >YEAR(CURDATE( ))-3 AND(scrs.MemberID !=1 AND scrs.MemberID !=10 AND scrs.MemberID !=100 AND scrs.MemberID !=1000) AND `game_type`='Snooker' AND `total_rp`>0 GROUP BY scrs.MemberID";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Insert Snooker Ranking: " . mysqli_error($dbcnx_client));
}
/*
Insert players in Tournaments 
*/
$sql = "Insert IGNORE INTO `rank_aa_snooker_master` (memb_id, jun, m_f) SELECT tourn_entry.tourn_memb_id as memb_id, members.Junior as jun, Female AS m_f FROM `vbsa3364_vbsa2`.`tourn_entry` LEFT JOIN `vbsa3364_vbsa2`.`members` ON members.MemberID=tourn_entry.tourn_memb_id WHERE `entry_cal_year` >YEAR(CURDATE( ))-3 AND tourn_entry.tourn_type='Snooker' GROUP BY tourn_memb_id HAVING SUM(rank_pts)>0";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Insert Tournaments: " . mysqli_error($dbcnx_client));
}
/*
Begin weekly ranking points 
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1 INNER JOIN (SELECT ROUND(MAX(total_RP*35/100),0) AS yr2, MemberID FROM scrs WHERE current_year_scrs = YEAR(CURDATE( ))-2 AND game_type='Snooker' AND scr_season='S2' GROUP BY MemberID ) T2 ON T1.memb_id= T2.MemberID SET T1.scr_2yr_S2 = T2.yr2";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Insert Tournaments: " . mysqli_error($dbcnx_client));
}
/*
Set 35% of ranking points  
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1 INNER JOIN (SELECT ROUND(MAX(total_RP*35/100),0) AS yr2, MemberID FROM scrs WHERE current_year_scrs = YEAR(CURDATE( ))-2 AND game_type='Snooker' AND scr_season='S1' GROUP BY MemberID ) T2 ON T1.memb_id= T2.MemberID SET T1.scr_2yr_S1 = T2.yr2";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Set 35pc of ranking points: " . mysqli_error($dbcnx_client));
}
/*
Set 65% of ranking points (S2)  
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1 INNER JOIN (SELECT ROUND(MAX(total_RP*65/100),0) AS yr1, MemberID FROM scrs WHERE current_year_scrs = YEAR(CURDATE( ))-1 AND game_type='Snooker' AND scr_season='S2' GROUP BY MemberID ) T2 ON T1.memb_id= T2.MemberID SET T1.scr_1yr_S2 = T2.yr1";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Set 65pc of ranking points (S2): " . mysqli_error($dbcnx_client));
}
/*
Set 65% of ranking points (S1)  
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1 INNER JOIN (SELECT ROUND(MAX(total_RP*65/100),0) AS yr1, MemberID FROM scrs WHERE current_year_scrs = YEAR(CURDATE( ))-1 AND game_type='Snooker' AND scr_season='S1' GROUP BY MemberID ) T2 ON T1.memb_id= T2.MemberID SET T1.scr_1yr_S1 = T2.yr1";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Set 65pc of ranking points (S1): " . mysqli_error($dbcnx_client));
}
/*
Set 100% of ranking points (S2)  
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1 INNER JOIN (SELECT MAX(total_RP) AS curr, MemberID FROM scrs WHERE current_year_scrs = YEAR(CURDATE( )) AND game_type='Snooker' AND scr_season='S2' GROUP BY MemberID ) T2 ON T1.memb_id= T2.MemberID SET T1.scr_curr_S2 = T2.curr";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Set 100pc of ranking points (S2): " . mysqli_error($dbcnx_client));
}
/*
Set 100% of ranking points (S1)  
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1 INNER JOIN (SELECT MAX(total_RP) AS curr, MemberID FROM scrs WHERE current_year_scrs = YEAR(CURDATE( )) AND game_type='Snooker' AND scr_season='S1' GROUP BY MemberID ) T2 ON T1.memb_id= T2.MemberID SET T1.scr_curr_S1 = T2.curr";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Set 100pc of ranking points (S1): " . mysqli_error($dbcnx_client));
}
/*
Set total weekly ranking points  
*/
$sql = "Update `rank_aa_snooker_master` SET weekly_total=scr_2yr_S2+scr_2yr_S1+scr_1yr_S2+scr_1yr_S1+scr_curr_S2+scr_curr_S1";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update total weekly ranking points: " . mysqli_error($dbcnx_client));
}
/*
Calculate and insert 15%   
*/
$sql = "Update `rank_aa_snooker_master` SET weekly_percent=ROUND(weekly_total*15/100)";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Calculate and insert: " . mysqli_error($dbcnx_client));
}
/*
Set 35% of tournament ranking points    
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1 INNER JOIN (SELECT tourn_memb_id, ROUND(SUM(rank_pts*35/100)) AS RP2 FROM tourn_entry WHERE entry_cal_year=YEAR(CURDATE( ))-2 GROUP BY tourn_memb_id HAVING SUM(rank_pts)>0 ) T2 ON T1.memb_id= T2.tourn_memb_id SET T1.tourn_2 = T2.RP2";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update 35pc of tournament ranking points: " . mysqli_error($dbcnx_client));
}
/*
Set 65% of tournament ranking points   
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1 INNER JOIN (SELECT tourn_memb_id, ROUND(SUM(rank_pts*65/100)) AS RP1 FROM tourn_entry WHERE entry_cal_year=YEAR(CURDATE( ))-1 GROUP BY tourn_memb_id HAVING SUM(rank_pts)>0 ) T2 ON T1.memb_id= T2.tourn_memb_id SET T1.tourn_1 = T2.RP1";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update 65pc of tournament ranking points: " . mysqli_error($dbcnx_client));
}
/*
Set current year ranking points   
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1 INNER JOIN (SELECT tourn_memb_id, SUM(rank_pts) AS RPcurr FROM tourn_entry WHERE entry_cal_year=YEAR(CURDATE( )) GROUP BY tourn_memb_id HAVING SUM(rank_pts)>0 ) T2 ON T1.memb_id= T2.tourn_memb_id SET T1.tourn_curr = T2.RPcurr";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update current year ranking points: " . mysqli_error($dbcnx_client));
}
/*
Calculate total tournament ranking points   
*/
$sql = "Update `rank_aa_snooker_master` SET tourn_total=weekly_percent+tourn_2+tourn_1+tourn_curr WHERE (weekly_percent+tourn_2+tourn_1+tourn_curr)>0";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update total tournament ranking points: " . mysqli_error($dbcnx_client));
}
/*
Clear all data from billiards rankings    
*/
$sql = "Truncate `vbsa3364_vbsa2`.`rank_a_billiards_master`";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update truncate billiards: " . mysqli_error($dbcnx_client));
}
/*
Insert players billiards from scrs    
*/
$sql = "Insert INTO `rank_a_billiards_master` SELECT scrs.MemberID as memb_id, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, members.Female, members.Junior, CURRENT_TIMESTAMP FROM `vbsa3364_vbsa2`.`scrs` LEFT JOIN `vbsa3364_vbsa2`.`members` ON members.MemberID=scrs.MemberID WHERE current_year_scrs >YEAR(CURDATE( ))-3 AND(scrs.MemberID !=1 AND scrs.MemberID !=10 AND scrs.MemberID !=100 AND scrs.MemberID !=1000) AND `game_type`='Billiards' AND `total_rp`>0 GROUP BY scrs.MemberID";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update players billiards: " . mysqli_error($dbcnx_client));
}
/*
Insert players from tourn_entry table    
*/
$sql = "Insert IGNORE INTO `rank_a_billiards_master` (memb_id, jun, m_f) SELECT tourn_entry.tourn_memb_id as memb_id, members.Junior as jun, Female AS m_f FROM `vbsa3364_vbsa2`.`tourn_entry` LEFT JOIN `vbsa3364_vbsa2`.`members` ON members.MemberID=tourn_entry.tourn_memb_id WHERE `entry_cal_year` >YEAR(CURDATE( ))-3 AND tourn_entry.tourn_type='Billiards' GROUP BY tourn_memb_id HAVING SUM(rank_pts)>0";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update players from tourn_entry table: " . mysqli_error($dbcnx_client));
}
/*
Begin weekly ranking points   
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1 INNER JOIN (SELECT ROUND(MAX(total_RP*35/100),0) AS yr2, MemberID FROM scrs WHERE current_year_scrs = YEAR(CURDATE( ))-2 AND game_type='Billiards' AND scr_season='S2' GROUP BY MemberID ) T2 ON T1.memb_id= T2.MemberID SET T1.scr_2yr_S2 = T2.yr2";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update weekly ranking points: " . mysqli_error($dbcnx_client));
}
/*
Set 35% of ranking points   
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1 INNER JOIN (SELECT ROUND(MAX(total_RP*35/100),0) AS yr2, MemberID FROM scrs WHERE current_year_scrs = YEAR(CURDATE( ))-2 AND game_type='Billiards' AND scr_season='S1' GROUP BY MemberID ) T2 ON T1.memb_id= T2.MemberID SET T1.scr_2yr_S1 = T2.yr2";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update 35pc of ranking points: " . mysqli_error($dbcnx_client));
}
/*
Set 65% of ranking points (S2)  
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1 INNER JOIN (SELECT ROUND(MAX(total_RP*65/100),0) AS yr1, MemberID FROM scrs WHERE current_year_scrs = YEAR(CURDATE( ))-1 AND game_type='Billiards' AND scr_season='S2' GROUP BY MemberID ) T2 ON T1.memb_id= T2.MemberID SET T1.scr_1yr_S2 = T2.yr1";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update 65pc of ranking points (S2): " . mysqli_error($dbcnx_client));
}
/*
Set 65% of ranking points (S1)  
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1 INNER JOIN (SELECT ROUND(MAX(total_RP*65/100),0) AS yr1, MemberID FROM scrs WHERE current_year_scrs = YEAR(CURDATE( ))-1 AND game_type='Billiards' AND scr_season='S1' GROUP BY MemberID ) T2 ON T1.memb_id= T2.MemberID SET T1.scr_1yr_S1 = T2.yr1";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update 65pc of ranking points (S1): " . mysqli_error($dbcnx_client));
}
/*
Set 100% of ranking points (S2)  
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1 INNER JOIN (SELECT MAX(total_RP) AS curr, MemberID FROM scrs WHERE current_year_scrs = YEAR(CURDATE( )) AND game_type='Billiards' AND scr_season='S2' GROUP BY MemberID ) T2 ON T1.memb_id= T2.MemberID SET T1.scr_curr_S2 = T2.curr";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update 100pc of ranking points (S2): " . mysqli_error($dbcnx_client));
}
/*
Set 100% of ranking points (S1)  
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1 INNER JOIN (SELECT MAX(total_RP) AS curr, MemberID FROM scrs WHERE current_year_scrs = YEAR(CURDATE( )) AND game_type='Billiards' AND scr_season='S1' GROUP BY MemberID ) T2 ON T1.memb_id= T2.MemberID SET T1.scr_curr_S1 = T2.curr";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update 100pc of ranking points (S1): " . mysqli_error($dbcnx_client));
}
/*
Award ranking points for billiard breaks   
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1 INNER JOIN (SELECT member_ID_brks, SUM(bill_rp*.35) AS BRP2 FROM breaks WHERE YEAR(recvd)=YEAR(CURDATE( ))-2 GROUP BY member_ID_brks ) T2 ON T1.memb_id= T2.member_ID_brks SET T1.brks_2 = T2.BRP2";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update billiard breaks: " . mysqli_error($dbcnx_client));
}
/*
Award ranking points for billiard breaks   
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1 INNER JOIN (SELECT member_ID_brks, SUM(bill_rp*.65) AS BRP1 FROM breaks WHERE YEAR(recvd)=YEAR(CURDATE( ))-1 GROUP BY member_ID_brks ) T2 ON T1.memb_id= T2.member_ID_brks SET T1.brks_1 = T2.BRP1";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update billiard breaks: " . mysqli_error($dbcnx_client));
}
/*
Award ranking points for billiard breaks (Current)   
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1 INNER JOIN (SELECT member_ID_brks, SUM(bill_rp) AS BRP FROM breaks WHERE YEAR(recvd)=YEAR(CURDATE( )) GROUP BY member_ID_brks ) T2 ON T1.memb_id= T2.member_ID_brks SET T1.brks_curr = T2.BRP";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update billiard breaks: " . mysqli_error($dbcnx_client));
}
/*
Set 35% of tournament ranking points    
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1 INNER JOIN (SELECT tourn_memb_id, ROUND(SUM(rank_pts*35/100)) AS RP2 FROM tourn_entry WHERE entry_cal_year=YEAR(CURDATE( ))-2 GROUP BY tourn_memb_id HAVING SUM(rank_pts)>0 ) T2 ON T1.memb_id= T2.tourn_memb_id SET T1.tourn_2 = T2.RP2";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update 35pc of tournament ranking points: " . mysqli_error($dbcnx_client));
}
/*
Set 65% of tournament ranking points   
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1 INNER JOIN (SELECT tourn_memb_id, ROUND(SUM(rank_pts*65/100)) AS RP1 FROM tourn_entry WHERE entry_cal_year=YEAR(CURDATE( ))-1 GROUP BY tourn_memb_id HAVING SUM(rank_pts)>0 ) T2 ON T1.memb_id= T2.tourn_memb_id SET T1.tourn_1 = T2.RP1";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update 65pc of tournament ranking points: " . mysqli_error($dbcnx_client));
}
/*
Set current year tournament ranking points   
*/
$sql = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1 INNER JOIN (SELECT tourn_memb_id, SUM(rank_pts) AS RPcurr FROM tourn_entry WHERE entry_cal_year=YEAR(CURDATE( )) GROUP BY tourn_memb_id HAVING SUM(rank_pts)>0 ) T2 ON T1.memb_id= T2.tourn_memb_id SET T1.tourn_curr = T2.RPcurr";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update current year tournament ranking points: " . mysqli_error($dbcnx_client));
}
/*
Total for Victorian Billiards rankings    
*/
$sql = "Update `rank_a_billiards_master` SET total_rp=scr_2yr_S2+scr_2yr_S1+scr_1yr_S2+scr_1yr_S1+scr_curr_S2+scr_curr_S1+ brks_2+brks_1+brks_curr+tourn_2+tourn_1+tourn_curr";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Victorian Billiards rankings: " . mysqli_error($dbcnx_client));
}
/*
END BILLIARDS master table

Truncate tables
*/

$sql = "Truncate TABLE `rank_S_open_weekly`";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update truncate rank s weekly: " . mysqli_error($dbcnx_client));
}

$sql = "Truncate TABLE `rank_S_open_tourn`";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update truncate rank s tourn: " . mysqli_error($dbcnx_client));
}

$sql = "Truncate TABLE `rank_S_womens`";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update truncate rank s womens: " . mysqli_error($dbcnx_client));
}

$sql = "Truncate TABLE `rank_S_junior`";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update truncate rank s junior: " . mysqli_error($dbcnx_client));
}
/*
Insert data into ranking tables

Rank_S_open_weekly Snooker Weekly Rankings   
*/
$sql = "Insert INTO `rank_S_open_weekly` SELECT 0, rank_aa_snooker_master.memb_id AS memb_id, rank_aa_snooker_master.weekly_total AS total_weekly_rp, CURRENT_TIMESTAMP FROM rank_aa_snooker_master GROUP BY rank_aa_snooker_master.memb_id ORDER BY weekly_total DESC";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Snooker Weekly Rankings: " . mysqli_error($dbcnx_client));
}
/*
Rank_S_open_tourn Snooker Tournament Rankings   
*/
$sql = "Insert INTO `rank_S_open_tourn` SELECT 0, rank_aa_snooker_master.memb_id AS memb_id, rank_aa_snooker_master.tourn_total AS total_tourn_rp, CURRENT_TIMESTAMP FROM rank_aa_snooker_master WHERE tourn_total>0 GROUP BY rank_aa_snooker_master.memb_id ORDER BY tourn_total DESC";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Snooker Tournament Rankings: " . mysqli_error($dbcnx_client));
}
/*
Billiard Rankings   
*/
$sql = "Insert INTO `rank_Billiards` SELECT 0, rank_a_billiards_master.memb_id AS memb_id, total_rp+tourn_2+tourn_1+tourn_curr AS total_rp, CURRENT_TIMESTAMP FROM rank_a_billiards_master GROUP BY rank_a_billiards_master.memb_id ORDER BY total_rp DESC";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Billiards Weekly Rankings: " . mysqli_error($dbcnx_client));
}
/*
Rank_S_womens Womens Snooker Rankings   
*/
$sql = "Insert INTO `rank_S_womens` SELECT 0, rank_aa_snooker_master.memb_id AS memb_id, rank_aa_snooker_master.tourn_total AS total_rp, CURRENT_TIMESTAMP FROM rank_aa_snooker_master WHERE m_f='F' AND tourn_total>0 GROUP BY rank_aa_snooker_master.memb_id ORDER BY tourn_total DESC";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Womens Snooker Rankings: " . mysqli_error($dbcnx_client));
}
/*
Rank_S_junior Junior Snooker Rankings   
*/
$sql ="Insert INTO `rank_S_junior` SELECT 0, rank_aa_snooker_master.memb_id AS memb_id, rank_aa_snooker_master.tourn_total AS total_rp, CURRENT_TIMESTAMP FROM rank_aa_snooker_master WHERE jun !='na' AND tourn_total>0 GROUP BY rank_aa_snooker_master.memb_id ORDER BY tourn_total DESC";
$update = $dbcnx_client->query($sql);
if(!$update)
{
  die("Could not update Junior Snooker Rankings: " . mysqli_error($dbcnx_client));
}
/*
END RANKING TABLES
*/

?>
<center>
<table class='table table-striped dt-responsive nowrap display' width='100%'>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>  
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">The Magic Calc Records have been updated.</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>  
  <tr>
    <td>&nbsp;</td>
    <td align="center">Please make a selection from the top menu.</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>  
</table>
</center>
<?php
include("footer.php"); 
?>