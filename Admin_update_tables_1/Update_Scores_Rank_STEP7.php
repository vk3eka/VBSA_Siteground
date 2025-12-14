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
  echo "<b><font face='arial' color='green'>Update Previous Ranking Points</font></b><br>";
  echo '<br/>';

  //************************************************************//
  //  Get last round a date for previous ranking calculation    //
  //************************************************************//

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
  
  echo("Last Round Played " . $last_round . "<br>");

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

  $query_breaks_round = "Select round, date from tbl_fixtures where season = '$season' and year = $year and round = " . $last_round . " Order by date DESC Limit 1";
  //echo($query_breaks_round . "<br>");
  $result_breaks_round = mysql_query($query_breaks_round, $connvbsa) or die(mysql_error());
  $build_breaks_round = mysql_fetch_assoc($result_breaks_round);  
  $last_date = ($build_round['date']);

  echo("Last Date Played " . $last_date . "<br>");

  //************************************************************//
  //  truncate all temp tables and insert all member id's       //
  //************************************************************//

  $querytoexecute = "Truncate TABLE temp_snooker_master";
  //echo($querytoexecute . "<br>");
  $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_snooker_master not truncated</font>");
  if (isset($result)) echo "<br><font face='arial' color='green'> 1.1. Truncated temp_snooker_master table successfully</font><br>";

  //insert player member id from scrs table for all players that have 'total_rp' >0
  $querytoexecute = "Insert INTO `temp_snooker_master` SELECT 0, scrs.MemberID as memb_id, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, members.Female, members.Junior, CURRENT_TIMESTAMP, 0, 0, 0, 0, 0, 0 FROM `vbsa3364_vbsa2`.`scrs` LEFT JOIN `vbsa3364_vbsa2`.`members` ON members.MemberID=scrs.MemberID WHERE current_year_scrs > YEAR(CURDATE( ))-3 AND(scrs.MemberID != 1 AND scrs.MemberID != 10 AND scrs.MemberID != 100 AND scrs.MemberID != 1000) AND game_type = 'Snooker' AND total_rp > 0 GROUP BY scrs.MemberID";
  //echo($querytoexecute . "<br>");
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>2. Error snooker player member id's from scrs table not entered</font>");

  //if (isset($result)) echo "<br><br><font face='arial' color='green'>2. Inserted snooker player member id from scrs table</font>";

   //insert players from tourn_entry table that have 'rank_pts' >0 NOTE ignore member id's that are already in the table

  $querytoexecute = "Insert IGNORE INTO `temp_snooker_master` (memb_id, junior, gender) 
  SELECT tourn_entry.tourn_memb_id as memb_id, members.Junior as junior, Female AS gender
  FROM `vbsa3364_vbsa2`.`tourn_entry`
  LEFT JOIN `vbsa3364_vbsa2`.`members` ON members.MemberID=tourn_entry.tourn_memb_id
  WHERE `entry_cal_year` >YEAR(CURDATE( ))-3 AND tourn_entry.tourn_type='Snooker'
  GROUP BY tourn_memb_id
  HAVING SUM(rank_pts)>0";
  //echo($querytoexecute . "<br>");
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error snooker players from tourn_entry table that have 'rank_pts' >0 were not inserted</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>1.2. Inserted snooker players from tourn_entry table that have 'rank_pts' >0 Ignored players that had been inserted previously.</font><br>";


  $querytoexecute = "Truncate TABLE temp_billiards_master";
  //echo($querytoexecute . "<br>");
  $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_billiards_master not truncated</font>");
  if (isset($result)) echo "<br><font face='arial' color='green'> 1.3. Truncated temp_billiards_master table successfully</font><br>";

  //insert players billiards from scrs table all players that have 'total_rp' >0
  $querytoexecute = "Insert INTO `temp_billiards_master` SELECT 0, scrs.MemberID as memb_id, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, members.Female, members.Junior, CURRENT_TIMESTAMP, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 FROM `vbsa3364_vbsa2`.`scrs` LEFT JOIN `vbsa3364_vbsa2`.`members` ON members.MemberID=scrs.MemberID WHERE current_year_scrs > YEAR(CURDATE( ))-3 AND(scrs.MemberID != 1 AND scrs.MemberID != 10 AND scrs.MemberID != 100 AND scrs.MemberID != 1000) AND game_type = 'Billiards' AND total_rp > 0 GROUP BY scrs.MemberID";
  //echo($querytoexecute . "<br>");
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>2. Error billiard player member id's from scrs table not entered</font>");

   //if (isset($result)) echo "<br><br><font face='arial' color='green'>2. Inserted billiard player member id from scrs table</font>";

  //insert players from tourn_entry table that have 'rank_pts' >0 NOTE ignore member id's that are already in the table

  $querytoexecute = "Insert IGNORE INTO `temp_billiards_master` (memb_id, junior, gender) 
  SELECT tourn_entry.tourn_memb_id as memb_id, members.Junior as junior, Female AS gender
  FROM `vbsa3364_vbsa2`.`tourn_entry`
  LEFT JOIN `vbsa3364_vbsa2`.`members` ON members.MemberID=tourn_entry.tourn_memb_id
  WHERE `entry_cal_year` >YEAR(CURDATE( ))-3 AND tourn_entry.tourn_type='Billiards'
  GROUP BY tourn_memb_id
  HAVING SUM(rank_pts)>0";

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error billiard players from tourn_entry table that have 'rank_pts' >0 were not inserted</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>1.4. Inserted billiard players from tourn_entry table that have 'rank_pts' >0 Ignored players that had been inserted previously.</font>";
  echo '<br/>';

  //************************************************************//
  //  copy all master ranking tables to temp tables             //
  //************************************************************//


  echo '<br/>';
  echo("<font face='arial' color='green'>2.1. Insert ranking points from Snooker Master</font><br>");
  echo '<br/>';

  $query_previous_snooker_rp = "Select scr_2yr_S1, scr_2yr_S2, scr_1yr_S1, scr_1yr_S2, scr_curr_S1, scr_curr_S2, weekly_total, weekly_percent, tourn_1, tourn_2, tourn_curr, tourn_1_w, tourn_2_w, tourn_curr_w, tourn_1_j, tourn_2_j, tourn_curr_j, memb_id from rank_aa_snooker_master Group By memb_id Order By memb_id Desc";
  //echo($query_previous_snooker_rp . "<br>");
  $result_previous_snooker_rp = mysql_query($query_previous_snooker_rp, $connvbsa) or die(mysql_error());
  while($build_result_snooker_rp = $result_previous_snooker_rp->fetch_assoc())
  {
      $sql_update_snooker = "Update temp_snooker_master Set 
      scr_2yr_S1 = " . $build_result_snooker_rp['scr_2yr_S1'] . ", 
      scr_2yr_S2 = " . $build_result_snooker_rp['scr_2yr_S2'] . ", 
      scr_1yr_S1 = " . $build_result_snooker_rp['scr_1yr_S1'] . ", 
      scr_1yr_S2 = " . $build_result_snooker_rp['scr_1yr_S2'] . ", 
      scr_curr_S1 = " . $build_result_snooker_rp['scr_curr_S1'] . ", 
      scr_curr_S2 = " . $build_result_snooker_rp['scr_curr_S2'] . ", 
      weekly_total = " . $build_result_snooker_rp['weekly_total'] . ", 
      weekly_percent = " . $build_result_snooker_rp['weekly_percent'] . ", 
      tourn_curr = " . $build_result_snooker_rp['tourn_curr'] . ", 
      tourn_1 = " . $build_result_snooker_rp['tourn_1'] . ", 
      tourn_2 = " . $build_result_snooker_rp['tourn_2'] . ", 
      tourn_curr_w = " . $build_result_snooker_rp['tourn_curr_w'] . ", 
      tourn_1_w = " . $build_result_snooker_rp['tourn_1_w'] . ", 
      tourn_2_w = " . $build_result_snooker_rp['tourn_2_w'] . ", 
      tourn_curr_j = " . $build_result_snooker_rp['tourn_curr_j'] . ", 
      tourn_1_j = " . $build_result_snooker_rp['tourn_1_j'] . ", 
      tourn_2_j = " . $build_result_snooker_rp['tourn_2_j'] . "
      Where memb_id = " . $build_result_snooker_rp['memb_id'];
      //echo($sql_update_snooker . "<br>");
      $result_update_snooker = mysql_query($sql_update_snooker, $connvbsa) or die(mysql_error());
  }


  echo '<br/>';
  echo("<font face='arial' color='green'>2.2. Insert ranking points from Billiards Master</font><br>");
  echo '<br/>';

  $query_previous_billiards_rp = "Select scr_2yr_S1, scr_2yr_S2, scr_1yr_S1, scr_1yr_S2, scr_curr_S1, scr_curr_S2, tourn_1, tourn_2, tourn_curr, tourn_1_w, tourn_2_w, tourn_curr_w, tourn_1_j, tourn_2_j, tourn_curr_j, brks_1, brks_2, brks_curr, brks_2yr_S1, brks_2yr_S2, brks_1yr_S1, brks_1yr_S2, brks_curr_S1, brks_curr_S2, memb_id from rank_a_billiards_master Group By memb_id Order By memb_id Desc";
  //echo($query_previous_billiards_rp . "<br>");
  $result_previous_billiards_rp = mysql_query($query_previous_billiards_rp, $connvbsa) or die(mysql_error());
  while($build_result_billiards_rp = $result_previous_billiards_rp->fetch_assoc())
  {
      $sql_update_billiards = "Update temp_billiards_master Set 
      scr_2yr_S1 = " . $build_result_billiards_rp['scr_2yr_S1'] . ", 
      scr_2yr_S2 = " . $build_result_billiards_rp['scr_2yr_S2'] . ", 
      scr_1yr_S1 = " . $build_result_billiards_rp['scr_1yr_S1'] . ", 
      scr_1yr_S2 = " . $build_result_billiards_rp['scr_1yr_S2'] . ", 
      scr_curr_S1 = " . $build_result_billiards_rp['scr_curr_S1'] . ", 
      scr_curr_S2 = " . $build_result_billiards_rp['scr_curr_S2'] . ", 
      tourn_curr = " . $build_result_billiards_rp['tourn_curr'] . ", 
      tourn_1 = " . $build_result_billiards_rp['tourn_1'] . ", 
      tourn_2 = " . $build_result_billiards_rp['tourn_2'] . ", 
      tourn_curr_w = " . $build_result_billiards_rp['tourn_curr_w'] . ", 
      tourn_1_w = " . $build_result_billiards_rp['tourn_1_w'] . ", 
      tourn_2_w = " . $build_result_billiards_rp['tourn_2_w'] . ", 
      tourn_curr_j = " . $build_result_billiards_rp['tourn_curr_j'] . ", 
      tourn_1_j = " . $build_result_billiards_rp['tourn_1_j'] . ", 
      tourn_2_j = " . $build_result_billiards_rp['tourn_2_j'] . ",
      brks_1 = " . $build_result_billiards_rp['brks_1'] . ", 
      brks_2 = " . $build_result_billiards_rp['brks_2'] . ",  
      brks_curr = " . $build_result_billiards_rp['brks_curr'] . ", 
      brks_2yr_S1 = " . $build_result_billiards_rp['brks_2yr_S1'] . ", 
      brks_2yr_S2 = " . $build_result_billiards_rp['brks_2yr_S2'] . ", 
      brks_1yr_S1 = " . $build_result_billiards_rp['brks_1yr_S1'] . ", 
      brks_1yr_S2 = " . $build_result_billiards_rp['brks_1yr_S2'] . ", 
      brks_curr_S1 = " . $build_result_billiards_rp['brks_curr_S1'] . ", 
      brks_curr_S2 = " . $build_result_billiards_rp['brks_curr_S2'] . "
      Where memb_id = " . $build_result_billiards_rp['memb_id'];
      //echo($sql_update_billiards . "<br>");
      $result_update_billiards = mysql_query($sql_update_billiards, $connvbsa) or die(mysql_error());
  }

  //************************************************************//
  //  get snooker OPEN pennant scores and copy to temp tables   //
  //************************************************************//

  // need a S1/S2 check. Use S1 if S2 is previous year or S2 if S1 is current year.

  echo '<br/>';
  echo("<font face='arial' color='green'>2.3. Get previous ranking points (pennant snooker OPEN)</font><br>");
  echo '<br/>';

  $rp_rounds =  rtrim($rp_rounds,'+');

  $query_rounds_rp = "Select ((" . $rp_rounds . ")*allocated_rp) as total_rp, MemberID from scrs where scr_season = '$season' and current_year_scrs = $year and MemberID != 1 and game_type = 'Snooker' Group By MemberID Order By total_rp Desc";
  echo($query_rounds_rp . "<br>");
  //$query_rounds_rp = "Select ((" . $rp_rounds . ")*allocated_rp) as total_rp, SUM(total_rp) as grand_total_rp, MemberID from scrs where scr_season = '$season' and current_year_scrs = $year and MemberID != 1 and game_type = 'Snooker' Group By MemberID Order By grand_total_rp Desc";

  $result_rounds_rp = mysql_query($query_rounds_rp, $connvbsa) or die(mysql_error());

  while($build_result = $result_rounds_rp->fetch_assoc())
  {
    if($build_result['total_rp'] > 0)
    {
      $sql_insert = "Update temp_snooker_master Set scr_curr_s1 = " . $build_result['total_rp'] . " Where memb_id = " . $build_result['MemberID'];
      echo($sql_insert . "<br>");
      $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    }
  }
  
  //echo("<pre>");
  //echo(var_dump($build_rounds));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'>2.3.1. temp_snooker_master table updated successfully</font>";
  echo '<br/>';

  //**************************************************************//
  //  get snooker OPEN tournament scores and copy to temp tables  //
  //**************************************************************//

  echo '<br/>';
  echo("<font face='arial' color='green'>2.4. Get previous ranking points (tournament snooker OPEN)</font><br>");
  echo '<br/>';

  $query_tournament = "Select tourn_memb_id, rank_pts, tourn_date_ent, tourn_entry.tourn_type as comptype, tournament_number FROM vbsa3364_vbsa2.tournaments left Join calendar on calendar.tourn_id = tournaments.tourn_id left Join tourn_entry on tourn_entry.tournament_number = tournaments.tourn_id where tourn_entry.tourn_type = 'Snooker' and finishdate > '2025-01-01' and finishdate <= '" . $last_date . "' and rank_pts > 0 group by tourn_memb_id";
  //echo($query_tournament . "<br>");
  $result_tournament = mysql_query($query_tournament, $connvbsa) or die(mysql_error());
  $i = 1;
  while($build_tournament = $result_tournament->fetch_assoc())
  {
    $sql_insert = "Update temp_snooker_master Set tourn_curr = " . $build_tournament['rank_pts'] . " Where memb_id = " . $build_tournament['tourn_memb_id'];
    //echo($sql_insert . "<br>");
    $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    $i++;
  }
  //echo("<pre>");
  //echo(var_dump($build_tourn));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'> 2.4.1. temp_snooker_master table updated successfully</font>";
  echo '<br/>';



  //***************************************************************//
  //  get snooker Womens tournament scores and copy to temp tables //
  //***************************************************************//


  echo '<br/>';
  echo("<font face='arial' color='green'>2.5. Get previous ranking points (tournament snooker Womens)</font><br>");
  echo '<br/>';

  $query_tournament = "Select tourn_memb_id, rank_pts, tourn_date_ent, tourn_entry.tourn_type as comptype, tournament_number FROM vbsa3364_vbsa2.tournaments left Join calendar on calendar.tourn_id = tournaments.tourn_id left Join tourn_entry on tourn_entry.tournament_number = tournaments.tourn_id LEFT JOIN members ON members.MemberID = tourn_entry.tourn_memb_id where tourn_entry.tourn_type = 'Snooker' and Gender = 'Female' and finishdate <= '" . $last_date . "' and finishdate > '2025-01-01' and rank_pts > 0 group by tourn_memb_id";
  //echo($query_tournament . "<br>");
  $result_tournament = mysql_query($query_tournament, $connvbsa) or die(mysql_error());
  $i = 1;
  while($build_tournament = $result_tournament->fetch_assoc())
  {
    $sql_insert = "Update temp_snooker_master Set tourn_curr_w = " . $build_tournament['rank_pts'] . " Where memb_id = " . $build_tournament['tourn_memb_id'];
    $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    $i++;
  }
  //echo("<pre>");
  //echo(var_dump($build_tourn));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'> 2.5.1. temp_snooker_master table updated successfully</font>";
  echo '<br/>';


  //***************************************************************//
  //  get snooker Junior tournament scores and copy to temp tables //
  //***************************************************************//


  echo '<br/>';
  echo("<font face='arial' color='green'>2.6. Get previous ranking points (tournament snooker Juniors)</font><br>");
  echo '<br/>';

  $query_tournament = "Select tourn_memb_id, rank_pts, tourn_date_ent, tourn_entry.tourn_type as comptype, tournament_number FROM vbsa3364_vbsa2.tournaments left Join calendar on calendar.tourn_id = tournaments.tourn_id left Join tourn_entry on tourn_entry.tournament_number = tournaments.tourn_id LEFT JOIN members ON members.MemberID = tourn_entry.tourn_memb_id where tourn_entry.tourn_type = 'Snooker' and Junior != 'na' and Gender != 'Female' and finishdate > '2025-01-01' and finishdate <= '" . $last_date . "' and rank_pts > 0 group by tourn_memb_id";
  //echo($query_tournament . "<br>");
  $result_tournament = mysql_query($query_tournament, $connvbsa) or die(mysql_error());
  $i = 1;
  while($build_tournament = $result_tournament->fetch_assoc())
  {
    $sql_insert = "Update temp_snooker_master Set tourn_curr_j = " . $build_tournament['rank_pts'] . " Where memb_id = " . $build_tournament['tourn_memb_id'];
    $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    $i++;
  }
  //echo("<pre>");
  //echo(var_dump($build_tourn));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'> 2.6.1. temp_snooker_master table updated successfully</font>";
  echo '<br/>';


  //************************************************************//
  // sum snooker weekly scores and copy to temp tables          //
  //************************************************************//


  //set total weekly ranking points

  $querytoexecute = "Update temp_snooker_master SET weekly_total=scr_2yr_S2+scr_2yr_S1+scr_1yr_S2+scr_1yr_S1+scr_curr_S2+scr_curr_S1";

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>10. Error total weekly ranking points not set</font>");

  //if (isset($result)) echo "<br><br><font face='arial' color='green'>10. Set total weekly snooker ranking points successfully</font>";

  //END weekly ranking points 

  //START tournament ranking points
  // calculate and insert 15% of total weekly ranking points to be addded to tournament points
  $querytoexecute = "Update temp_snooker_master SET weekly_percent=ROUND(weekly_total*15/100)";

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>11. Error weekly ranking points to be addded to tournament points were not set</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>3. Weekly ranking points to be addded to tournament points were set successfully</font><br>";



  //************************************************************//
  //  sum snooker pennant scores and copy to temp tables        //
  //************************************************************//

  // truncate temp_weekly_snooker_ranking
  $querytoexecute = "Truncate TABLE temp_weekly_snooker_ranking";
  $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_weekly_snooker_ranking not truncated</font>");
  if (isset($result)) echo "<br><font face='arial' color='green'> 3.1. Truncated temp_weekly_snooker_ranking table successfully</font>";

  //calculate total tournament ranking points
  $querytoexecute = "Update temp_snooker_master SET weekly_total=scr_2yr_S2+scr_2yr_S1+scr_1yr_S2+scr_1yr_S1+scr_curr_S2+scr_curr_S1 WHERE (scr_2yr_S2+scr_2yr_S1+scr_1yr_S2+scr_1yr_S1+scr_curr_S2+scr_curr_S1) > 0";

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>15. Error did not calculate total tournament ranking points</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>3.2. Calculate total tournament ranking points successfully</font>";


  //Snooker Tourn Rankings
  $querytoexecute = "Insert INTO temp_weekly_snooker_ranking 
  SELECT 
  0,
  temp_snooker_master.memb_id AS memb_id,
  temp_snooker_master.weekly_total AS total_rp,
  CURRENT_TIMESTAMP
  FROM temp_snooker_master
  Group By temp_snooker_master.memb_id
  ORDER BY total_rp DESC";

  //echo("Snooker Weekly - " . $querytoexecute . "<br>");

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error - temp_weekly_snooker_ranking - Snooker Weekly Rankings data not inserted</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'> 3.3. Data inserted to temp_weekly_snooker_ranking table successfully</font><br>";


  //**********************************************************//
  //  sum snooker tournament scores and copy to temp tables   //
  //**********************************************************//

  // truncate temp_open_snooker_ranking
  $querytoexecute = "Truncate TABLE temp_open_snooker_ranking";
  $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_open_snooker_ranking not truncated</font>");
  if (isset($result)) echo "<br><font face='arial' color='green'> 4.1. Truncated temp_open_snooker_ranking table successfully</font>";

  //calculate total tournament ranking points
  $querytoexecute = "Update temp_snooker_master SET tourn_total=weekly_percent+tourn_2+tourn_1+tourn_curr WHERE (weekly_percent+tourn_2+tourn_1+tourn_curr) > 0";
  //echo("Snooker Open - " . $querytoexecute . "<br>");
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>15. Error did not calculate total tournament ranking points</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>4.2. Calculate total tournament ranking points successfully</font>";


  //Snooker Weekly Rankings
  $querytoexecute = "Insert INTO temp_open_snooker_ranking 
  SELECT 
  0,
  temp_snooker_master.memb_id AS memb_id,
  temp_snooker_master.tourn_total AS total_rp,
  CURRENT_TIMESTAMP
  FROM temp_snooker_master
  Group By temp_snooker_master.memb_id
  ORDER BY total_rp DESC";

  //echo("Snooker Open - " . $querytoexecute . "<br>");

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error - temp_open_snooker_ranking - Snooker Open Rankings data not inserted</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'> 4.3. Data inserted to temp_open_snooker_ranking table successfully</font><br>";


  //**********************************************************//
  //  sum snooker womens scores and copy to temp tables   //
  //**********************************************************//

  // truncate temp_open_snooker_ranking
  $querytoexecute = "Truncate TABLE temp_womens_snooker_ranking";
  $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_womens_snooker_ranking not truncated</font>");
  if (isset($result)) echo "<br><font face='arial' color='green'> 5.1. Truncated temp_womens_snooker_ranking table successfully</font>";

  //calculate total tournament ranking points
  $querytoexecute = "Update temp_snooker_master SET tourn_total=weekly_percent+tourn_2_w+tourn_1_w+tourn_curr_w WHERE (weekly_percent+tourn_2_w+tourn_1_w+tourn_curr_w) > 0";

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>15. Error did not calculate total tournament ranking points</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>5.2. Calculate total tournament ranking points successfully</font>";


  //Snooker Weekly Rankings
  $querytoexecute = "Insert INTO temp_womens_snooker_ranking 
  SELECT 
  0,
  temp_snooker_master.memb_id AS memb_id,
  temp_snooker_master.tourn_total AS total_rp,
  CURRENT_TIMESTAMP
  FROM temp_snooker_master
  LEFT JOIN members T1 ON T1.MemberID = temp_snooker_master.memb_id 
  Where T1.Gender = 'Female' and tourn_total > 0
  Group By temp_snooker_master.memb_id
  ORDER BY total_rp DESC";

  //echo("Snooker Womens - " . $querytoexecute . "<br>");

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error - temp_womens_snooker_ranking - Snooker Womens Rankings data not inserted</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'> 5.3. Data inserted to temp_womens_snooker_ranking table successfully</font><br>";


  //**********************************************************//
  //  sum snooker junior scores and copy to temp tables   //
  //**********************************************************//

  // truncate temp_open_snooker_ranking
  $querytoexecute = "Truncate TABLE temp_junior_snooker_ranking";
  $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_junior_snooker_ranking not truncated</font>");
  if (isset($result)) echo "<br><font face='arial' color='green'> 6.1. Truncated temp_womens_junior_ranking table successfully</font>";

  //calculate total tournament ranking points
  $querytoexecute = "Update temp_snooker_master SET tourn_total=weekly_percent+tourn_2_j+tourn_1_j+tourn_curr_j WHERE (weekly_percent+tourn_2_j+tourn_1_j+tourn_curr_j) > 0";

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>15. Error did not calculate total tournament ranking points</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>6.2. Calculate total tournament ranking points successfully</font>";


  //Snooker Weekly Rankings
  $querytoexecute = "Insert INTO temp_junior_snooker_ranking 
  SELECT 
  0,
  temp_snooker_master.memb_id AS memb_id,
  temp_snooker_master.tourn_total AS total_rp,
  CURRENT_TIMESTAMP
  FROM temp_snooker_master
  LEFT JOIN members T1 ON T1.MemberID = temp_snooker_master.memb_id 
  where T1.Junior != 'na' and T1.Gender != 'Female' and tourn_total > 0
  Group By temp_snooker_master.memb_id
  ORDER BY total_rp DESC";

  //echo("Snooker Junior - " . $querytoexecute . "<br>");

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error - temp_junior_snooker_ranking - Snooker Junior Rankings data not inserted</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'> 6.3. Data inserted to temp_junior_snooker_ranking table successfully</font><br>";




  //************************************************************//
  //  get billiard OPEN scores and copy to temp tables          //
  //************************************************************//



  //echo '<br/>'.'<br/>';
  //echo "<font face='arial'>Update Previous Ranking Points (Billiards)</font><br>";
  echo '<br/>';
  echo("<font face='arial' color='green'>7. Get previous ranking points (pennant billiards OPEN)</font>");
  echo '<br/>';

  $rp_rounds =  rtrim($rp_rounds,'+');

  $query_rounds_rp = "Select ((" . $rp_rounds . ")*allocated_rp) as total_rp, MemberID from scrs where scr_season = 'S1' and current_year_scrs = 2025 and MemberID != 1 and game_type = 'Billiards' Group By MemberID Order By total_rp Desc";
  //echo($query_rounds_rp . "<br>");
  $result_rounds_rp = mysql_query($query_rounds_rp, $connvbsa) or die(mysql_error());

  while($build_result = $result_rounds_rp->fetch_assoc())
  {
    if($build_result['total_rp'] > 0)
    {
      $sql_insert = "Update temp_billiards_master Set scr_curr_s1 = " . $build_result['total_rp'] . " Where memb_id = " . $build_result['MemberID'];
      //echo($sql_insert . "<br>");
      $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    }
  }
  //echo("<pre>");
  //echo(var_dump($build_rounds));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'> 7.1. temp_billiards_master table updated successfully</font>";
  echo '<br/>';



  //***********************************************************************//
  //  get billiard OPEN tournamnet/pennant breaks and copy to temp tables  //
  //***********************************************************************//


  echo '<br/>';
  echo("<font face='arial' color='green'>8.1. Get previous break ranking points (billiards OPEN)</font><br>");
  //echo '<br/>';
/*
  $query_breaks_round = "Select round, date from tbl_fixtures where season = '$season' and year = $year and round = " . $last_round . " Order by date DESC Limit 1";
  //echo($query_breaks_round . "<br>");
  $result_breaks_round = mysql_query($query_breaks_round, $connvbsa) or die(mysql_error());
  $build_breaks_round = mysql_fetch_assoc($result_breaks_round);  
  $last_date = ($build_round['date']);
*/
  $query_breaks = "Select sum(bill_rp) as total_rp, member_ID_brks FROM vbsa3364_vbsa2.breaks where  recvd <= '" . $last_date . "' and brk_type = 'Billiards' and season = 'S1' Group By member_ID_brks order by member_ID_brks";
  //echo($query_breaks . "<br>");
  $result_breaks = mysql_query($query_breaks, $connvbsa) or die(mysql_error());
  
  while($build_breaks = $result_breaks->fetch_assoc())
  {
    $sql_breaks = "Update temp_billiards_master Set brks_curr_S1 = " . $build_breaks['total_rp'] . " Where memb_id = " . $build_breaks['member_ID_brks'];
    //echo($sql_breaks . "<br>");
    $result_update_breaks = mysql_query($sql_breaks, $connvbsa) or die(mysql_error());
  }
  //echo("<pre>");
  //echo(var_dump($build_breaks));
  //echo("</pre>");

  echo "<br><font face='arial' color='green'> 8.2. temp_billiards_master table updated successfully (breaks)</font>";
  echo '<br/>';

  //**************************************************************//
  //  get billiard OPEN tournament scores and copy to temp tables //
  //**************************************************************//


  echo '<br/>';
  echo("<font face='arial' color='green'>9.1. Get previous ranking points (tournament billiards OPEN)</font><br>");
  //echo '<br/>';

  //$query_tournament = "Select tourn_memb_id, rank_pts, tourn_date_ent, tourn_entry.tourn_type as comptype, tournament_number FROM vbsa3364_vbsa2.tournaments left Join calendar on calendar.tourn_id = tournaments.tourn_id left Join tourn_entry on tourn_entry.tournament_number = tournaments.tourn_id where tourn_entry.tourn_type = 'Billiards' and finishdate < (curdate()) and finishdate > '2024-01-01' and rank_pts > 0 group by tourn_memb_id";

  $query_tournament = "Select tourn_memb_id, rank_pts, tourn_date_ent, tourn_entry.tourn_type as comptype, tournament_number FROM vbsa3364_vbsa2.tournaments left Join calendar on calendar.tourn_id = tournaments.tourn_id left Join tourn_entry on tourn_entry.tournament_number = tournaments.tourn_id where tourn_entry.tourn_type = 'Billiards' and finishdate > '2025-01-01' and finishdate <= '" . $last_date . "' and rank_pts > 0 group by tourn_memb_id";


  //echo($query_tournament . "<br>");
  $result_tournament = mysql_query($query_tournament, $connvbsa) or die(mysql_error());
  $i = 1;
  while($build_tournament = $result_tournament->fetch_assoc())
  {
    $sql_insert = "Update temp_billiards_master Set tourn_curr = " . $build_tournament['rank_pts'] . " Where memb_id = " . $build_tournament['tourn_memb_id'];
    $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    $i++;
  }
  //echo("<pre>");
  //echo(var_dump($build_tourn));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'> 9.2. temp_billiards_master table updated successfully</font>";
  echo '<br/>';


  //*************************************************************************//
  //  get billiard Womens tournamnet/pennant breaks and copy to temp tables  //
  //*************************************************************************//



  echo '<br/>';
  echo("<font face='arial' color='green'>10.1. Get previous ranking points (tournament billiards Womens)</font><br>");
  //echo '<br/>';

  $query_tournament = "Select tourn_memb_id, rank_pts, tourn_date_ent, tourn_entry.tourn_type as comptype, tournament_number FROM vbsa3364_vbsa2.tournaments left Join calendar on calendar.tourn_id = tournaments.tourn_id left Join tourn_entry on tourn_entry.tournament_number = tournaments.tourn_id LEFT JOIN members ON members.MemberID = tourn_entry.tourn_memb_id where tourn_entry.tourn_type = 'Billiards' and Gender = 'Female' and finishdate > '2025-01-01' and finishdate <= '" . $last_date . "' and rank_pts > 0 group by tourn_memb_id";
  //echo($query_tournament . "<br>");
  $result_tournament = mysql_query($query_tournament, $connvbsa) or die(mysql_error());
  $i = 1;
  while($build_tournament = $result_tournament->fetch_assoc())
  {
    $sql_insert = "Update temp_billiards_master Set tourn_curr_w = " . $build_tournament['rank_pts'] . " Where memb_id = " . $build_tournament['tourn_memb_id'];
    $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    $i++;
  }
  //echo("<pre>");
  //echo(var_dump($build_tourn));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'> 10.2. temp_billiards_master table updated successfully</font>";
  echo '<br/>';

  //*************************************************************************//
  //  get billiard Junior tournamnet/pennant breaks and copy to temp tables  //
  //*************************************************************************//


  echo '<br/>';
  echo("<font face='arial' color='green'>11.1. Get previous ranking points (tournament billiards Juniors)</font><br>");
  //echo '<br/>';

  $query_tournament = "Select tourn_memb_id, rank_pts, tourn_date_ent, tourn_entry.tourn_type as comptype, tournament_number FROM vbsa3364_vbsa2.tournaments left Join calendar on calendar.tourn_id = tournaments.tourn_id left Join tourn_entry on tourn_entry.tournament_number = tournaments.tourn_id LEFT JOIN members ON members.MemberID = tourn_entry.tourn_memb_id where tourn_entry.tourn_type = 'Billiards' and Junior != 'na' and Gender != 'Female' and finishdate > '2025-01-01'  and finishdate <= '" . $last_date . "' and rank_pts > 0 group by tourn_memb_id";
  //echo($query_tournament . "<br>");
  $result_tournament = mysql_query($query_tournament, $connvbsa) or die(mysql_error());
  $i = 1;
  while($build_tournament = $result_tournament->fetch_assoc())
  {
    $sql_insert = "Update temp_billiards_master Set tourn_curr_j = " . $build_tournament['rank_pts'] . " Where memb_id = " . $build_tournament['tourn_memb_id'];
    $result_insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    $i++;
  }
  //echo("<pre>");
  //echo(var_dump($build_tourn));
  //echo("</pre>");
  echo "<br><font face='arial' color='green'> 11.2. temp_billiards_master table updated successfully</font>";
  echo '<br/>';




  //*************************************************************************//
  //  total all billiard tournamnet/pennant breaks and copy to temp tables  //
  //*************************************************************************//

  // truncate temp_open_snooker_ranking
  $querytoexecute = "Truncate TABLE temp_open_billiards_ranking";
  $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_open_biiliards_ranking not truncated</font>");
  if (isset($result)) echo "<br><font face='arial' color='green'> 12.1. Truncated temp_open_billiards_ranking table successfully</font>";


  //total for Victorian Billiards rankings ranking points

  $querytoexecute = "Update temp_billiards_master SET total_rp=scr_2yr_S2+scr_2yr_S1+scr_1yr_S2+scr_1yr_S1+scr_curr_S2+scr_curr_S1+brks_2yr_S1+brks_1yr_S1+brks_curr_S1+brks_2yr_S2+brks_1yr_S2+brks_curr_S2+tourn_2+tourn_1+tourn_curr
  ";
  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>16. Error current year tournament ranking points not totalled</font>");
  if (isset($result)) echo "<br><br><font face='arial' color='green'>12.2. Current year tournament ranking points totalled successfully</font>";

  $querytoexecute = "Insert INTO temp_open_billiards_ranking 
  SELECT 
  0,
  temp_billiards_master.memb_id AS memb_id,
  temp_billiards_master.total_rp AS total_rp,
  CURRENT_TIMESTAMP
  FROM temp_billiards_master
  Group By temp_billiards_master.memb_id
  ORDER BY total_rp DESC";

  //echo("Billiards Weekly - " . $querytoexecute . "<br>");

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error - temp_open_billiards_ranking table - Billiards Open Rankings data not inserted</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'> 12.3. Data inserted to temp_open_billiards_ranking table successfully</font><br>";


  //**********************************************************//
  //  sum snooker womens scores and copy to temp tables   //
  //**********************************************************//

  // truncate temp_open_snooker_ranking
  $querytoexecute = "Truncate TABLE temp_womens_billiards_ranking";
  $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_womens_billiards_ranking not truncated</font>");
  if (isset($result)) echo "<br><font face='arial' color='green'> 13.1. Truncated temp_womens_billiards_ranking table successfully</font>";

  //calculate total tournament ranking points
  $querytoexecute = "Update temp_billiards_master SET tourn_total=weekly_percent+tourn_2_w+tourn_1_w+tourn_curr_w WHERE (weekly_percent+tourn_2_w+tourn_1_w+tourn_curr_w) > 0";
  //echo("Billiards Womens - " . $querytoexecute . "<br>");

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>15. Error did not calculate total tournament ranking points</font>");


  if (isset($result)) echo "<br><br><font face='arial' color='green'>13.2. Calculate total tournament ranking points successfully</font>";


  $querytoexecute = "Insert INTO temp_womens_billiards_ranking 
  SELECT 
  0,
  temp_billiards_master.memb_id AS memb_id,
  SUM(temp_billiards_master.tourn_2_w+temp_billiards_master.tourn_1_w+temp_billiards_master.tourn_curr_w) AS total_rp,
  CURRENT_TIMESTAMP
  FROM temp_billiards_master
  LEFT JOIN members T1 ON T1.MemberID = temp_billiards_master.memb_id 
  where T1.Gender = 'Female' and tourn_total > 0
  Group By temp_billiards_master.memb_id
  ORDER BY total_rp DESC";

  //echo("Billiards Womens - " . $querytoexecute . "<br>");

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error - temp_womens_billiards_ranking - Billiards Womens Rankings data not inserted</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'> 13.3. Data inserted to temp_womens_billiards_ranking table successfully</font><br>";



  //**********************************************************//
  //  sum snooker junior scores and copy to temp tables   //
  //**********************************************************//

  // truncate temp_open_snooker_ranking
  $querytoexecute = "Truncate TABLE temp_junior_billiards_ranking";
  $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - temp_junior_billiards_ranking not truncated</font>");
  if (isset($result)) echo "<br><font face='arial' color='green'> 14.1. Truncated temp_junior_billiards_ranking table successfully</font>";

  //calculate total tournament ranking points
  $querytoexecute = "Update temp_billiards_master SET tourn_total=weekly_percent+tourn_2_j+tourn_1_j+tourn_curr_j WHERE (weekly_percent+tourn_2_j+tourn_1_j+tourn_curr_j) > 0";

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>15. Error did not calculate total tournament ranking points</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'>14.2. Calculate total tournament ranking points successfully</font>";


  $querytoexecute = "Insert INTO temp_junior_billiards_ranking 
  SELECT 
  0,
  temp_billiards_master.memb_id AS memb_id,
  SUM(temp_billiards_master.tourn_2_j+temp_billiards_master.tourn_1_j+temp_billiards_master.tourn_curr_j) AS total_rp,
  CURRENT_TIMESTAMP
  FROM temp_billiards_master
  LEFT JOIN members T1 ON T1.MemberID = temp_billiards_master.memb_id 
  where T1.Junior != 'na' and T1.Gender != 'Female' and tourn_total > 0
  Group By temp_billiards_master.memb_id
  ORDER BY total_rp DESC";

  //echo("Billiards Junior - " . $querytoexecute . "<br>");

  $result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error - temp_junior_billiards_ranking - Billiards Junior Rankings data not inserted</font>");

  if (isset($result)) echo "<br><br><font face='arial' color='green'> 14.3. Data inserted to temp_junior_billiards_ranking table successfully</font>";





  echo '<br/>'.'<br/>';
  echo '<br/>'.'<br/>';
  echo "<font face='arial'  color='green' size='3'>Calculations completed ".'<span class="greenbg"><a href="../Admin_DB_VBSA/A_memb_index.php">'. "Thank you". '</a></spsn>';
  echo '<br/><br/>';

  mysql_close ($connvbsa);

  //***********//
  // Complete  //
  //***********//


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
