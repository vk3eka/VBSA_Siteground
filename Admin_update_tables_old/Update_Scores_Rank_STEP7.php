<?php 
require_once('../Connections/connvbsa.php'); 

error_reporting(0);
?>
<?php
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
    <td colspan="4" align="center">STEP 7 of the calculation process</td>
  </tr>
  <tr>
    <td colspan="4" align="center" class="red_text">&nbsp;</td>
  </tr>
</table>

<center>
<?php
mysql_select_db($database_connvbsa, $connvbsa);

if(isset($_POST["submit"]))
{

  echo "<font face='arial' size='3'><span class='greenbg'><a href='Update_Scores_Rank_STEP7.php'>Step 7</a></span>";
  echo '<br/><br/>';

  echo '<br/>'.'<br/>';
  echo "<font face='arial'>Update Previous Ranking Points (Snooker)</font><br>";
  echo '<br/>';

  $season = 'S1';
  $year = 2025;

  $query_round = "Select round, date from tbl_fixtures where season = '$season' and year = $year and date < curdate() Order by date DESC Limit 1";
  //echo($query_round . "<br>");
  $result_round = mysql_query($query_round, $connvbsa) or die(mysql_error());
  $build_round = mysql_fetch_assoc($result_round);
  $last_round = $build_round['round'];
  if($last_round > 18)
  {
    $last_round = 18;
  }
  else
  {
    //$last_round = ($build_round['round']-1);
    $last_round = ($build_round['round']);
  }
  
  $rp_rounds = '';
  $rp_tier_rounds = '';
  for($i = 1; $i <= $last_round; $i++)
  {
    if($i < 10 )
    {
      $i = '0' . $i;
    }
    $rp_rounds =  $rp_rounds . "IFNULL(r". $i . "s, 0)+";
    $rp_tier_rounds =  $rp_tier_rounds . "IFNULL(tier_r". $i . "_rp, 0)+";
  }

  $querytoexecute = "Truncate TABLE temp_snooker_master";
  $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_snooker_master not truncated</font>");
  if (isset($result)) echo "<br><font face='arial' color='green'> 1. Truncated temp_snooker_master table successfully</font>";

  //insert player member id from scrs table for all players that have 'total_rp' >0
  $querytoexecute = "Insert INTO `temp_snooker_master` SELECT 0, scrs.MemberID as memb_id, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, members.Female, members.Junior, CURRENT_TIMESTAMP, 0, 0, 0, 0, 0, 0 FROM `vbsa3364_vbsa2`.`scrs` LEFT JOIN `vbsa3364_vbsa2`.`members` ON members.MemberID=scrs.MemberID WHERE current_year_scrs > YEAR(CURDATE( ))-3 AND(scrs.MemberID != 1 AND scrs.MemberID != 10 AND scrs.MemberID != 100 AND scrs.MemberID != 1000) AND game_type = 'Snooker' AND total_rp > 0 GROUP BY scrs.MemberID";
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>2. Error snooker player member id's from scrs table not entered</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>2. Inserted snooker player member id from scrs table</font>";

  $querytoexecute = "Truncate TABLE temp_billiards_master";
  $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_billiards_master not truncated</font>");
  if (isset($result)) echo "<br><font face='arial' color='green'> 1. Truncated temp_billiards_master table successfully</font>";

  //insert players billiards from scrs table all players that have 'total_rp' >0
  $querytoexecute = "Insert INTO `temp_billiards_master` SELECT 0, scrs.MemberID as memb_id, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, members.Female, members.Junior, CURRENT_TIMESTAMP, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 FROM `vbsa3364_vbsa2`.`scrs` LEFT JOIN `vbsa3364_vbsa2`.`members` ON members.MemberID=scrs.MemberID WHERE current_year_scrs > YEAR(CURDATE( ))-3 AND(scrs.MemberID != 1 AND scrs.MemberID != 10 AND scrs.MemberID != 100 AND scrs.MemberID != 1000) AND game_type = 'Billiards' AND total_rp > 0 GROUP BY scrs.MemberID";
  //echo($querytoexecute . "<br>");
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>2. Error billiard player member id's from scrs table not entered</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>2. Inserted billiard player member id from scrs table</font>";


  //insert players from tourn_entry table that have 'rank_pts' >0 NOTE ignore member id's that are already in the table
  $querytoexecute = "Insert IGNORE INTO `temp_billiards_master` (memb_id, junior, gender) 
  SELECT tourn_entry.tourn_memb_id as memb_id, members.Junior as junior, Female AS gender
  FROM `vbsa3364_vbsa2`.`tourn_entry`
  LEFT JOIN `vbsa3364_vbsa2`.`members` ON members.MemberID=tourn_entry.tourn_memb_id
  WHERE `entry_cal_year` >YEAR(CURDATE( ))-3 AND tourn_entry.tourn_type='Billiards'
  GROUP BY tourn_memb_id
  HAVING SUM(rank_pts)>0";

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error billiard players from tourn_entry table that have 'rank_pts' >0 were not inserted</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>3. Inserted billiard players from tourn_entry table that have 'rank_pts' >0 Ignored players that had been inserted previously.</font>";


  //insert players from tourn_entry table that have 'rank_pts' >0 NOTE ignore member id's that are already in the table

  $querytoexecute = "Insert IGNORE INTO `temp_billiards_master` (memb_id, junior, gender) 
  SELECT tourn_entry.tourn_memb_id as memb_id, members.Junior as junior, Female AS gender
  FROM `vbsa3364_vbsa2`.`tourn_entry`
  LEFT JOIN `vbsa3364_vbsa2`.`members` ON members.MemberID=tourn_entry.tourn_memb_id
  WHERE `entry_cal_year` >YEAR(CURDATE( ))-3 AND tourn_entry.tourn_type='Snooker'
  GROUP BY tourn_memb_id
  HAVING SUM(rank_pts)>0";
  //echo("Tourn " . $querytoexecute . "<br>");
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error snooker players from tourn_entry table that have 'rank_pts' >0 were not inserted</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>3. Inserted snooker players from tourn_entry table that have 'rank_pts' >0 Ignored players that had been inserted previously.</font>";




  echo '<br/>';
  echo("<font face='arial'>2. Get previous ranking points (pennant snooker OPEN)</font><br>");
  echo '<br/>';

  $rp_rounds =  rtrim($rp_rounds,'+');

  $query_rounds_rp = "Select ((" . $rp_rounds . ")*allocated_rp) as total_rp, MemberID from scrs where scr_season = '$season' and current_year_scrs = $year and MemberID != 1 and game_type = 'Snooker' Group By MemberID Order By total_rp Desc";
  //echo($query_rounds_rp . "<br>");
  $result_rounds_rp = mysql_query($query_rounds_rp, $connvbsa) or die(mysql_error());

  //$querytoexecute = "Truncate TABLE temp_open_snooker_ranking";
  //$result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_weekly_snooker_ranking not truncated</font>");
  //if (isset($result)) echo "<br><font face='arial' color='green'> 1. Truncated temp_weekly_snooker_ranking table successfully</font>";

  while($build_result = $result_rounds_rp->fetch_assoc())
  {
    if($build_result['total_rp'] > 0)
    {
      //$sql_insert = "Insert into temp_weekly_snooker_ranking (previous_rank, memb_id, total_rp, last_update) Values (0, " . $build_result['MemberID'] . ", " . $build_result['total_rp'] . ", '" . date('Y-m-d') . "')";
      $sql_insert = "Update temp_snooker_master Set scr_curr_s1 = " . $build_result['total_rp'] . ", last_update = 'CURRENT_TIMESTAMP' Where memb_id = " . $build_result['MemberID'];
      //echo($sql_insert . "<br>");
      $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    }
  }
  
  //echo("<pre>");
  //echo(var_dump($build_rounds));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'> 1. temp_snooker_master table updated successfully</font>";
  echo '<br/>';
/*
  echo '<br/>';
  echo("<font face='arial'>2. Get previous ranking points (pennant snooker Womens)</font><br>");
  echo '<br/>';

  $rp_rounds =  rtrim($rp_rounds,'+');

  $query_rounds_rp = "Select ((" . $rp_rounds . ")*allocated_rp) as total_rp, scrs.MemberID from scrs LEFT JOIN members ON members.MemberID = scrs.MemberID where Gender = 'Female' and scr_season = 'S1' and current_year_scrs = 2025 and scrs.MemberID != 1 and game_type = 'Snooker' Group By MemberID Order By total_rp Desc";
  //echo($query_rounds_rp . "<br>");
  $result_rounds_rp = mysql_query($query_rounds_rp, $connvbsa) or die(mysql_error());

  //$querytoexecute = "Truncate TABLE temp_womens_snooker_ranking";
  //$result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_womens_snooker_ranking not truncated</font>");
  //if (isset($result)) echo "<br><font face='arial' color='green'> 1. Truncated temp_womens_snooker_ranking table successfully</font>";

  while($build_result = $result_rounds_rp->fetch_assoc())
  {
    if($build_result['total_rp'] > 0)
    {
      //$sql_insert = "Insert into temp_womens_snooker_ranking (previous_rank, memb_id, total_rp, last_update) Values (0, " . $build_result['MemberID'] . ", " . $build_result['total_rp'] . ", '" . date('Y-m-d') . "')";
      $sql_insert = "Update temp_snooker_master Set scr_curr_s1 = " . $build_result['total_rp'] . ", gender = 'Female', last_update = 'CURRENT_TIMESTAMP' Where memb_id = " . $build_result['MemberID'];
      //echo($sql_insert . "<br>");
      $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    }
  }
  //echo("<pre>");
  //echo(var_dump($build_result));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'> 1. temp_weekly_snooker_ranking table updated successfully</font>";
  echo '<br/>';

  echo '<br/>';
  echo("<font face='arial'>2. Get previous ranking points (pennant snooker Junior)</font><br>");
  echo '<br/>';

  $rp_rounds =  rtrim($rp_rounds,'+');

  $query_rounds_rp = "Select ((" . $rp_rounds . ")*allocated_rp) as total_rp, scrs.MemberID from scrs LEFT JOIN members ON members.MemberID = scrs.MemberID where Junior != 'na' and Gender != 'Female' and scr_season = 'S1' and current_year_scrs = 2025 and members.MemberID != 1 and game_type = 'Snooker' Group By members.MemberID Order By total_rp Desc";
  //echo($query_rounds_rp . "<br>");
  $result_rounds_rp = mysql_query($query_rounds_rp, $connvbsa) or die(mysql_error());

  $querytoexecute = "Truncate TABLE temp_junior_snooker_ranking";
  $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_junior_snooker_ranking not truncated</font>");
  if (isset($result)) echo "<br><font face='arial' color='green'> 1. Truncated temp_junior_snooker_ranking table successfully</font>";

  while($build_result = $result_rounds_rp->fetch_assoc())
  {
    if($build_result['total_rp'] > 0)
    {
      $sql_insert = "Insert into temp_junior_snooker_ranking (previous_rank, memb_id, total_rp, last_update) Values (0, " . $build_result['MemberID'] . ", " . $build_result['total_rp'] . ", '" . date('Y-m-d') . "')";
      //echo($sql_insert . "<br>");
      $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    }
  }
  //echo("<pre>");
  //echo(var_dump($build_rounds));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'> 1. temp_junior_snooker_ranking table updated successfully</font>";
  echo '<br/>';

*/
  echo '<br/>';
  echo("<font face='arial'>2. Get previous ranking points (tournament snooker OPEN)</font><br>");
  echo '<br/>';

  //$querytoexecute = "Truncate TABLE temp_open_snooker_ranking";
  //$result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_weekly_snooker_ranking not truncated</font>");
  //if (isset($result)) echo "<br><font face='arial' color='green'> 1. Truncated temp_open_snooker_ranking table successfully</font>";

  $query_tournament = "Select tourn_memb_id, rank_pts, tourn_date_ent, tourn_entry.tourn_type as comptype, tournament_number FROM vbsa3364_vbsa2.tournaments left Join calendar on calendar.tourn_id = tournaments.tourn_id left Join tourn_entry on tourn_entry.tournament_number = tournaments.tourn_id where tourn_entry.tourn_type = 'Snooker' and finishdate < (curdate()) and finishdate > '2024-01-01' and rank_pts > 0 group by tourn_memb_id";
  //echo($query_tournament . "<br>");
  $result_tournament = mysql_query($query_tournament, $connvbsa) or die(mysql_error());
  $i = 1;
  while($build_tournament = $result_tournament->fetch_assoc())
  {
    //$sql_insert = "Insert into temp_open_snooker_ranking (previous_rank, memb_id, total_rp, last_update) Values (0, " . $build_tournament['tourn_memb_id'] . ", " . $build_tournament['rank_pts'] . ", '" . date('Y-m-d') . "')";
    $sql_insert = "Update temp_snooker_master Set tourn_curr = " . $build_tournament['rank_pts'] . ", last_update = 'CURRENT_TIMESTAMP' Where memb_id = " . $build_tournament['tourn_memb_id'];
    $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    $i++;
  }
  //echo("<pre>");
  //echo(var_dump($build_tourn));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'> 1. temp_snooker_master table updated successfully</font>";
  echo '<br/>';


  echo '<br/>';
  echo("<font face='arial'>2. Get previous ranking points (tournament snooker Womens)</font><br>");
  echo '<br/>';

  //$querytoexecute = "Truncate TABLE temp_womens_snooker_ranking";
  //$result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_weekly_snooker_ranking not truncated</font>");
  //if (isset($result)) echo "<br><font face='arial' color='green'> 1. Truncated temp_womens_snooker_ranking table successfully</font>";

  $query_tournament = "Select tourn_memb_id, rank_pts, tourn_date_ent, tourn_entry.tourn_type as comptype, tournament_number FROM vbsa3364_vbsa2.tournaments left Join calendar on calendar.tourn_id = tournaments.tourn_id left Join tourn_entry on tourn_entry.tournament_number = tournaments.tourn_id LEFT JOIN members ON members.MemberID = tourn_entry.tourn_memb_id where tourn_entry.tourn_type = 'Snooker' and Gender = 'Female' and finishdate < (curdate()) and finishdate > '2024-01-01' and rank_pts > 0 group by tourn_memb_id";
  //echo($query_tournament . "<br>");
  $result_tournament = mysql_query($query_tournament, $connvbsa) or die(mysql_error());
  $i = 1;
  while($build_tournament = $result_tournament->fetch_assoc())
  {
    //$sql_insert = "Insert into temp_womens_snooker_ranking (previous_rank, memb_id, total_rp, last_update) Values (0, " . $build_tournament['tourn_memb_id'] . ", " . $build_tournament['rank_pts'] . ", '" . date('Y-m-d') . "')";
    $sql_insert = "Update temp_snooker_master Set tourn_curr_w = " . $build_tournament['rank_pts'] . ", last_update = 'CURRENT_TIMESTAMP' Where memb_id = " . $build_tournament['tourn_memb_id'];
    $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    $i++;
  }
  //echo("<pre>");
  //echo(var_dump($build_tourn));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'> 1. temp_snooker_master table updated successfully</font>";
  echo '<br/>';



  echo '<br/>';
  echo("<font face='arial'>2. Get previous ranking points (tournament snooker Juniors)</font><br>");
  echo '<br/>';

  //$querytoexecute = "Truncate TABLE temp_womens_snooker_ranking";
  //$result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_junior_snooker_ranking not truncated</font>");
  //if (isset($result)) echo "<br><font face='arial' color='green'> 1. Truncated temp_junior_snooker_ranking table successfully</font>";

  $query_tournament = "Select tourn_memb_id, rank_pts, tourn_date_ent, tourn_entry.tourn_type as comptype, tournament_number FROM vbsa3364_vbsa2.tournaments left Join calendar on calendar.tourn_id = tournaments.tourn_id left Join tourn_entry on tourn_entry.tournament_number = tournaments.tourn_id LEFT JOIN members ON members.MemberID = tourn_entry.tourn_memb_id where tourn_entry.tourn_type = 'Snooker' and Junior != 'na' and Gender != 'Female' and finishdate < (curdate()) and finishdate > '2024-01-01' and rank_pts > 0 group by tourn_memb_id";
  //echo($query_tournament . "<br>");
  $result_tournament = mysql_query($query_tournament, $connvbsa) or die(mysql_error());
  $i = 1;
  while($build_tournament = $result_tournament->fetch_assoc())
  {
    //$sql_insert = "Insert into temp_junior_snooker_ranking (previous_rank, memb_id, total_rp, last_update) Values (0, " . $build_tournament['tourn_memb_id'] . ", " . $build_tournament['rank_pts'] . ", '" . date('Y-m-d') . "')";
    $sql_insert = "Update temp_snooker_master Set tourn_curr_j = " . $build_tournament['rank_pts'] . ", last_update = 'CURRENT_TIMESTAMP' Where memb_id = " . $build_tournament['tourn_memb_id'];
    $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    $i++;
  }
  //echo("<pre>");
  //echo(var_dump($build_tourn));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'> 1. temp_snooker_master table updated successfully</font>";
  echo '<br/>';



  echo '<br/>'.'<br/>';
  echo "<font face='arial'>Update Previous Ranking Points (Billiards)</font><br>";
  echo '<br/>';



  
  echo("<font face='arial'>2. Get previous ranking points (pennant billiards OPEN)</font><br>");
  echo '<br/>';

  $rp_rounds =  rtrim($rp_rounds,'+');

  $query_rounds_rp = "Select ((" . $rp_rounds . ")*allocated_rp) as total_rp, MemberID from scrs where scr_season = 'S1' and current_year_scrs = 2025 and MemberID != 1 and game_type = 'Billiards' Group By MemberID Order By total_rp Desc";
  //echo($query_rounds_rp . "<br>");
  $result_rounds_rp = mysql_query($query_rounds_rp, $connvbsa) or die(mysql_error());

  //$querytoexecute = "Truncate TABLE temp_open_billiards_ranking";
  //$result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_weekly_billiards_ranking not truncated</font>");
  //if (isset($result)) echo "<br><font face='arial' color='green'> 1. Truncated temp_weekly_billiards_ranking table successfully</font>";

  while($build_result = $result_rounds_rp->fetch_assoc())
  {
    if($build_result['total_rp'] > 0)
    {
      //$sql_insert = "Insert into temp_weekly_billiards_ranking (previous_rank, memb_id, total_rp, last_update) Values (0, " . $build_result['MemberID'] . ", " . $build_result['total_rp'] . ", '" . date('Y-m-d') . "')";
      $sql_insert = "Update temp_billiards_master Set scr_curr_s1 = " . $build_result['total_rp'] . ", last_update = 'CURRENT_TIMESTAMP' Where memb_id = " . $build_result['MemberID'];
      //echo($sql_insert . "<br>");
      $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    }
  }
  //echo("<pre>");
  //echo(var_dump($build_rounds));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'> 1. temp_billiards_master table updated successfully</font>";
  echo '<br/>';
/*
  echo '<br/>';
  echo("<font face='arial'>2. Get previous ranking points (pennant billiards Womens)</font><br>");
  echo '<br/>';

  $rp_rounds =  rtrim($rp_rounds,'+');

  $query_rounds_rp = "Select ((" . $rp_rounds . ")*allocated_rp) as total_rp, scrs.MemberID from scrs LEFT JOIN members ON members.MemberID = scrs.MemberID where Gender = 'Female' and scr_season = 'S1' and current_year_scrs = 2025 and scrs.MemberID != 1 and game_type = 'Billiards' Group By MemberID Order By total_rp Desc";
  //echo($query_rounds_rp . "<br>");
  $result_rounds_rp = mysql_query($query_rounds_rp, $connvbsa) or die(mysql_error());

  //$querytoexecute = "Truncate TABLE temp_womens_billiards_ranking";
  //$result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_womens_billiards_ranking not truncated</font>");
  //if (isset($result)) echo "<br><font face='arial' color='green'> 1. Truncated temp_womens_billiards_ranking table successfully</font>";

  while($build_result = $result_rounds_rp->fetch_assoc())
  {
    if($build_result['total_rp'] > 0)
    {
      //$sql_insert = "Insert into temp_womens_billiards_ranking (previous_rank, memb_id, total_rp, last_update) Values (0, " . $build_result['MemberID'] . ", " . $build_result['total_rp'] . ", '" . date('Y-m-d') . "')";
      $sql_insert = "Update temp_billiards_master Set scrs_curr_s1 = " . $build_result['total_rp'] . ", last_update = 'CURRENT_TIMESTAMP' Where memb_id = " . $build_result['MemberID'];
      //echo($sql_insert . "<br>");
      $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    }
  }
  //echo("<pre>");
  //echo(var_dump($build_result));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'> 1. temp_billiards_master table updated successfully</font>";
  echo '<br/>';

  echo '<br/>';
  echo("<font face='arial'>2. Get previous ranking points (pennant billiards Junior)</font><br>");
  echo '<br/>';

  $rp_rounds =  rtrim($rp_rounds,'+');

  $query_rounds_rp = "Select ((" . $rp_rounds . ")*allocated_rp) as total_rp, scrs.MemberID from scrs LEFT JOIN members ON members.MemberID = scrs.MemberID where Junior != 'na' and Gender != 'Female' and scr_season = 'S1' and current_year_scrs = 2025 and members.MemberID != 1 and game_type = 'Billiards' Group By members.MemberID Order By total_rp Desc";
  //echo($query_rounds_rp . "<br>");
  $result_rounds_rp = mysql_query($query_rounds_rp, $connvbsa) or die(mysql_error());

  //$querytoexecute = "Truncate TABLE temp_junior_billiards_ranking";
  //$result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_junior_billiards_ranking not truncated</font>");
  //if (isset($result)) echo "<br><font face='arial' color='green'> 1. Truncated temp_junior_billiards_ranking table successfully</font>";

  while($build_result = $result_rounds_rp->fetch_assoc())
  {
    if($build_result['total_rp'] > 0)
    {
      //$sql_insert = "Insert into temp_junior_billiards_ranking (previous_rank, memb_id, total_rp, last_update) Values (0, " . $build_result['MemberID'] . ", " . $build_result['total_rp'] . ", '" . date('Y-m-d') . "')";
      $sql_insert = "Update temp_billiards_master Set scrs_curr_s1 = " . $build_result['total_rp'] . ", last_update = 'CURRENT_TIMESTAMP' Where memb_id = " . $build_result['MemberID'];
      //echo($sql_insert . "<br>");
      $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    }
  }
  //echo("<pre>");
  //echo(var_dump($build_rounds));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'> 1. temp_billiards_master table updated successfully</font>";
  echo '<br/>';
*/



  echo '<br/>';
  echo("<font face='arial'>2. Get previous ranking points (tournament billiards OPEN)</font><br>");
  echo '<br/>';

  //$querytoexecute = "Truncate TABLE temp_open_billiards_ranking";
  //$result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_open_billiards_ranking not truncated</font>");
  //if (isset($result)) echo "<br><font face='arial' color='green'> 1. Truncated temp_open_billiards_ranking table successfully</font>";

  $query_tournament = "Select tourn_memb_id, rank_pts, tourn_date_ent, tourn_entry.tourn_type as comptype, tournament_number FROM vbsa3364_vbsa2.tournaments left Join calendar on calendar.tourn_id = tournaments.tourn_id left Join tourn_entry on tourn_entry.tournament_number = tournaments.tourn_id where tourn_entry.tourn_type = 'Billiards' and finishdate < (curdate()) and finishdate > '2024-01-01' and rank_pts > 0 group by tourn_memb_id";
  //echo($query_tournament . "<br>");
  $result_tournament = mysql_query($query_tournament, $connvbsa) or die(mysql_error());
  $i = 1;
  while($build_tournament = $result_tournament->fetch_assoc())
  {
    //$sql_insert = "Insert into temp_open_billiards_ranking (previous_rank, memb_id, total_rp, last_update) Values (0, " . $build_tournament['tourn_memb_id'] . ", " . $build_tournament['rank_pts'] . ", '" . date('Y-m-d') . "')";
    $sql_insert = "Update temp_billiards_master Set tourn_curr = " . $build_tournament['rank_pts'] . ", last_update = 'CURRENT_TIMESTAMP' Where memb_id = " . $build_tournament['tourn_memb_id'];
    $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    $i++;
  }
  //echo("<pre>");
  //echo(var_dump($build_tourn));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'> 1. temp_billiards_master table updated successfully</font>";
  echo '<br/>';


  echo '<br/>';
  echo("<font face='arial'>2. Get previous ranking points (tournament billiards Womens)</font><br>");
  echo '<br/>';

  //$querytoexecute = "Truncate TABLE temp_womens_billiards_ranking";
  //$result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_womens_billiards_ranking not truncated</font>");
  //if (isset($result)) echo "<br><font face='arial' color='green'> 1. Truncated temp_womens_billiards_ranking table successfully</font>";

  $query_tournament = "Select tourn_memb_id, rank_pts, tourn_date_ent, tourn_entry.tourn_type as comptype, tournament_number FROM vbsa3364_vbsa2.tournaments left Join calendar on calendar.tourn_id = tournaments.tourn_id left Join tourn_entry on tourn_entry.tournament_number = tournaments.tourn_id LEFT JOIN members ON members.MemberID = tourn_entry.tourn_memb_id where tourn_entry.tourn_type = 'Billiards' and Gender = 'Female' and finishdate < (curdate()) and finishdate > '2024-01-01' and rank_pts > 0 group by tourn_memb_id";
  //echo($query_tournament . "<br>");
  $result_tournament = mysql_query($query_tournament, $connvbsa) or die(mysql_error());
  $i = 1;
  while($build_tournament = $result_tournament->fetch_assoc())
  {
    //$sql_insert = "Insert into temp_womens_billiards_ranking (previous_rank, memb_id, total_rp, last_update) Values (0, " . $build_tournament['tourn_memb_id'] . ", " . $build_tournament['rank_pts'] . ", '" . date('Y-m-d') . "')";
    $sql_insert = "Update temp_billiards_master Set tourn_curr_w = " . $build_tournament['rank_pts'] . ", last_update = 'CURRENT_TIMESTAMP' Where memb_id = " . $build_tournament['tourn_memb_id'];
    $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    $i++;
  }
  //echo("<pre>");
  //echo(var_dump($build_tourn));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'> 1. temp_womens_billiards_ranking table updated successfully</font>";
  echo '<br/>';


  echo '<br/>';
  echo("<font face='arial'>2. Get previous ranking points (tournament billiards Juniors)</font><br>");
  echo '<br/>';

  //$querytoexecute = "Truncate TABLE temp_junior_billiards_ranking";
  //$result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_junior_billiards_ranking not truncated</font>");
  //if (isset($result)) echo "<br><font face='arial' color='green'> 1. Truncated temp_junior_snooker_ranking table successfully</font>";

  $query_tournament = "Select tourn_memb_id, rank_pts, tourn_date_ent, tourn_entry.tourn_type as comptype, tournament_number FROM vbsa3364_vbsa2.tournaments left Join calendar on calendar.tourn_id = tournaments.tourn_id left Join tourn_entry on tourn_entry.tournament_number = tournaments.tourn_id LEFT JOIN members ON members.MemberID = tourn_entry.tourn_memb_id where tourn_entry.tourn_type = 'Billiards' and Junior != 'na' and Gender != 'Female' and finishdate < (curdate()) and finishdate > '2024-01-01' and rank_pts > 0 group by tourn_memb_id";
  //echo($query_tournament . "<br>");
  $result_tournament = mysql_query($query_tournament, $connvbsa) or die(mysql_error());
  $i = 1;
  while($build_tournament = $result_tournament->fetch_assoc())
  {
    //$sql_insert = "Insert into temp_junior_billiards_ranking (previous_rank, memb_id, total_rp, last_update) Values (0, " . $build_tournament['tourn_memb_id'] . ", " . $build_tournament['rank_pts'] . ", '" . date('Y-m-d') . "')";
    $sql_insert = "Update temp_billiards_master Set tourn_curr_j = " . $build_tournament['rank_pts'] . ", last_update = 'CURRENT_TIMESTAMP' Where memb_id = " . $build_tournament['tourn_memb_id'];
    $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    $i++;
  }
  //echo("<pre>");
  //echo(var_dump($build_tourn));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'> 1. temp_junior_billiards_ranking table updated successfully</font>";
  echo '<br/>';


  echo '<br/>'.'<br/>';
  echo '<br/>'.'<br/>';
  echo "<font face='arial'  color='green' size='3'>Calculations completed ".'<span class="greenbg"><a href="../Admin_DB_VBSA/A_memb_index.php">'. "Thank you". '</a></spsn>';
  echo '<br/><br/>';

  mysql_close ($connvbsa);
}
else
{
  echo '<center/>';
  echo "<form id='form1' name='form1' method='post' action='../Admin_update_tables/Update_Scores_Rank_STEP7.php?submit=1'>";
  echo "<input type='submit' id='submit' name='submit'>";
  echo "</form>";
}

?>
</center>
</body>
</html>
