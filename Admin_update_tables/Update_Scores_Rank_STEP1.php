<?php require_once('../Connections/connvbsa.php');

error_reporting(0);

$season = $_GET['season'];

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
  
  <table width="800" align="center">
    <tr>
      <td colspan="4" align="center"><img src="../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="header_red">&nbsp;</td>
    </tr>
    <tr>
      <td width="784" colspan="4" align="center"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
    <tr>
      <td colspan="4" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="center">Please COMPLETE this sequence of pages every time you update scores</td>
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
      <td colspan="4" align="center">&nbsp;</td>
    </tr>
    
  </table>
  
  <center>
  
  <?php require_once('../Connections/connvbsa.php'); ?>
  
  
  <?php

if(isset($_POST["submit"]))
{

mysql_select_db($database_connvbsa, $connvbsa);


echo "<font face='arial' size='3'>STEP 1 completed go to ".'<span class="greenbg"><a href="Update_Scores_Rank_STEP2.php?season=' . $season . '">STEP 2</a></spsn>';
echo '<br/><br/>';

echo '<br/><br/>';
echo "<font face='arial'>Scores Table - Calculate - Average position, Count matches played, Points Won, Ranking points and Percentage won";
// Average position calculate

//tested ok Step 1 - Summ all position for division by matches played

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`scrs` SET `total_position`
=(IFNULL(scrs.r01pos,0)) 
+(IFNULL(scrs.r02pos,0))
+(IFNULL(scrs.r03pos,0))
+(IFNULL(scrs.r04pos,0))
+(IFNULL(scrs.r05pos,0))
+(IFNULL(scrs.r06pos,0))
+(IFNULL(scrs.r07pos,0))
+(IFNULL(scrs.r08pos,0))
+(IFNULL(scrs.r09pos,0))
+(IFNULL(scrs.r10pos,0))
+(IFNULL(scrs.r11pos,0))
+(IFNULL(scrs.r12pos,0))
+(IFNULL(scrs.r13pos,0))
+(IFNULL(scrs.r14pos,0))
+(IFNULL(scrs.r15pos,0))
+(IFNULL(scrs.r16pos,0))
+(IFNULL(scrs.r17pos,0))
+(IFNULL(scrs.r18pos,0))
WHERE current_year_scrs = YEAR(CURDATE( ))";

$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>1. Average position calculate - Sum position OK</font>";



//tested ok Step 2 - Count matches played by counting position played

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`scrs` SET `count_position`
=(SELECT COUNT(`r01pos`)+COUNT(`r02pos`)+COUNT(`r03pos`)+COUNT(`r04pos`)+COUNT(`r05pos`)+COUNT(`r06pos`)+COUNT(`r07pos`)+COUNT(`r08pos`)+COUNT(`r09pos`)
+COUNT(`r10pos`)+COUNT(`r11pos`)+COUNT(`r12pos`)+COUNT(`r13pos`)+COUNT(`r14pos`)+COUNT(`r15pos`)+COUNT(`r16pos`)+COUNT(`r17pos`)+COUNT(`r18pos`))
WHERE current_year_scrs = YEAR(CURDATE( ))";

$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>2. Average position calculate - Count position OK</font>";

//tested ok Step 3 - Divide Sum of position by Matches played for average position

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`scrs` SET average_position=total_position/count_position WHERE current_year_scrs = YEAR(CURDATE( ))";
//echo($querytoexecute . "<br>");
$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>3. Average position calculate - Divided Sum of position by Matches played for average position OK</font>";


// Start Scores Table - Count matches played, Points Won, Ranking points and Percentage won
//tested ok  - Count matches played

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`scrs` SET count_played
=(SELECT COUNT(r01s)+COUNT(r02s)+COUNT(r03s)+COUNT(r04s)+COUNT(r05s)+COUNT(r06s)+COUNT(r07s)+COUNT(r08s)+COUNT(r09s)
+COUNT(r10s)+COUNT(r11s)+COUNT(r12s)+COUNT(r13s)+COUNT(r14s)+COUNT(r15s)+COUNT(r16s)+COUNT(r17s)+COUNT(r18s)) 
WHERE current_year_scrs = YEAR(CURDATE( ))";

$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>4. Count matches played OK</font>";

//tested ok - Calculate points won ALL players

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`scrs` SET `pts_won`
=(IFNULL(scrs.r01s,0)) 
+(IFNULL(scrs.r02s,0))
+(IFNULL(scrs.r03s,0))
+(IFNULL(scrs.r04s,0))
+(IFNULL(scrs.r05s,0))
+(IFNULL(scrs.r06s,0))
+(IFNULL(scrs.r07s,0))
+(IFNULL(scrs.r08s,0))
+(IFNULL(scrs.r09s,0))
+(IFNULL(scrs.r10s,0))
+(IFNULL(scrs.r11s,0))
+(IFNULL(scrs.r12s,0))
+(IFNULL(scrs.r13s,0))
+(IFNULL(scrs.r14s,0))
+(IFNULL(scrs.r15s,0))
+(IFNULL(scrs.r16s,0))
+(IFNULL(scrs.r17s,0))
+(IFNULL(scrs.r18s,0))
WHERE current_year_scrs = YEAR(CURDATE( ))";
//echo("<br>" . $querytoexecute . "<br>");
$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>5. Calculated points won OK</font>";

//tested ok  - Remove points won where MemberID =1 (Bye), MemberID=100 (player forfeit) , MemberID=1000 (team forfeit)

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`scrs` SET pts_won=0 WHERE (MemberID=1 OR MemberID=100 OR MemberID=1000) AND current_year_scrs = YEAR(CURDATE( ))";

$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>6. Set points won = 0 for Bye, player forfeit and team forfeit </font>";

//tested ok  - Calculate ranking points - allocated ranking points x points won


// Season 2 in 2023 change billiards calc from allocated points to tier points
$querytoexecute = "Update `vbsa3364_vbsa2`.`scrs` SET total_RP =
(Select Case
WHEN game_type = 'Snooker' THEN allocated_rp*pts_won
WHEN game_type = 'Billiards' AND scr_season = 'S1' AND current_year_scrs <= 2023 THEN allocated_rp*(pts_won/2)
WHEN game_type = 'Billiards' AND current_year_scrs > 2023 THEN (tier_r01_rp+tier_r02_rp+tier_r03_rp+tier_r04_rp+tier_r05_rp+tier_r06_rp+tier_r07_rp+tier_r08_rp+tier_r09_rp+tier_r10_rp+tier_r11_rp+tier_r12_rp+tier_r13_rp+tier_r14_rp+tier_r15_rp+tier_r16_rp+tier_r17_rp+tier_r18_rp)
ELSE allocated_rp*(pts_won/2)
END)
WHERE current_year_scrs = YEAR(CURDATE( ))";
//echo("<br>" . $querytoexecute . "<br>");
$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");
if (isset($result)) echo "<br><br><font face='arial' color='green'>7.1. Set total ranking points OK " . date("Y") . "</font>";

// added for previous 1 year
$querytoexecute = "Update `vbsa3364_vbsa2`.`scrs` SET total_RP =
(Select Case
WHEN game_type = 'Snooker' THEN allocated_rp*pts_won
WHEN game_type = 'Billiards' AND scr_season = 'S2' AND current_year_scrs = 2023 THEN tier_rp*(pts_won/2)
WHEN game_type = 'Billiards' AND current_year_scrs > 2023 THEN (tier_r01_rp+tier_r02_rp+tier_r03_rp+tier_r04_rp+tier_r05_rp+tier_r06_rp+tier_r07_rp+tier_r08_rp+tier_r09_rp+tier_r10_rp+tier_r11_rp+tier_r12_rp+tier_r13_rp+tier_r14_rp+tier_r15_rp+tier_r16_rp+tier_r17_rp+tier_r18_rp)
ELSE allocated_rp*(pts_won/2)
END)
WHERE current_year_scrs = YEAR(CURDATE( )) -1";
//echo("<br>" . $querytoexecute . "<br>");
$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");
if (isset($result)) echo "<br><br><font face='arial' color='green'>7.2. Set total ranking points OK " . (date("Y")-1) . "</font>";

// added for previous 2 year
$querytoexecute = "Update `vbsa3364_vbsa2`.`scrs` SET total_RP =
(Select Case
WHEN game_type = 'Snooker' THEN allocated_rp*pts_won
WHEN game_type = 'Billiards' AND scr_season = 'S2' AND current_year_scrs = 2023 THEN tier_rp*(pts_won/2)
WHEN game_type = 'Billiards' AND current_year_scrs > 2023 THEN (tier_r01_rp+tier_r02_rp+tier_r03_rp+tier_r04_rp+tier_r05_rp+tier_r06_rp+tier_r07_rp+tier_r08_rp+tier_r09_rp+tier_r10_rp+tier_r11_rp+tier_r12_rp+tier_r13_rp+tier_r14_rp+tier_r15_rp+tier_r16_rp+tier_r17_rp+tier_r18_rp)
ELSE allocated_rp*(pts_won/2)
END)
WHERE current_year_scrs = YEAR(CURDATE( )) -2";
//echo("<br>" . $querytoexecute . "<br>");
$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");
if (isset($result)) echo "<br><br><font face='arial' color='green'>7.3. Set total ranking points OK " . (date("Y")-2) . "</font>";

//echo("<br>Start Function<br>");

/*
function BilliardRP($year, $current_season)
{
  //;echo("<br>");
  global $database_connvbsa;
  global $connvbsa;
  mysql_select_db($database_connvbsa, $connvbsa);
  // check that team grades has tier ranking points entered
  $querytoexecute = "Select tier1_rp, tier2_rp, tier3_rp, tier4_rp, tier5_rp, tier6_rp from `vbsa3364_vbsa2`.`Team_grade` where type = 'Billiards' and fix_cal_year = $year and season = '$current_season' and tier1_rp != 0";
  //echo($querytoexecute . "<br>");
  $Tier = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());
  $TierAll = mysql_fetch_assoc($Tier);

  $querytoexecute = "Select MemberID, scrsID from `vbsa3364_vbsa2`.`scrs` where game_type = 'Billiards' and scr_season = '$current_season' and current_year_scrs = $year and MemberID != 1 and MemberID != 100 and MemberID != 1000 Order By MemberID";
  //echo($querytoexecute . "<br>");
  $RPall = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());
  $totalRows_RPall = mysql_num_rows($RPall);
  
  while ($row_RPall = mysql_fetch_assoc($RPall))
  { 
    $querytoexecute = "Select * from `vbsa3364_vbsa2`.`scrs` where scrsID = " . $row_RPall['scrsID'] . " and game_type = 'Billiards' and scr_season = '$current_season' and current_year_scrs = $year and MemberID != 1 and MemberID != 100 and MemberID != 1000";
    //echo($querytoexecute . "<br>");
    $RPindividual = mysql_query($querytoexecute, $connvbsa) or die(mysql_error());
    $row_RPindividual = mysql_fetch_assoc($RPindividual);

    $Array = [];
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
      if($row_RPindividual['r' . $rnd_no . "s"] > 0)
      {
        $Array[$i] = $row_RPindividual['tier_r' . $rnd_no . '_rp'] . "" . $Array[$i];
      }
    }
    $result = array_count_values($Array);
    $total_rp = 
      (($result[$TierAll['tier1_rp']]*$TierAll['tier1_rp'])
      + 
      ($result[$TierAll['tier3_rp']]*$TierAll['tier3_rp'])
      + 
      ($result[$TierAll['tier4_rp']]*$TierAll['tier4_rp'])
      +
      ($result[$TierAll['tier1_rp']/2]*$TierAll['tier1_rp']/2)
      + 
      ($result[$TierAll['tier3_rp']/2]*$TierAll['tier3_rp']/2)
      + 
      ($result[$TierAll['tier4_rp']/2]*$TierAll['tier4_rp']/2));
    if($row_RPindividual['scrsID'] > 0)
    {
      $sql_tiers_rp = "Update scrs set total_RP = " . $total_rp . " Where scrsID = " . $row_RPindividual['scrsID'];
      $update = mysql_query($sql_tiers_rp, $connvbsa); 
      if(!$update )
      {
          die("Could not update tier data: " . mysqli_error($dbcnx_client));
      }
    }
  }
  echo "<br><br><font face='arial' color='green'>7.4. Set total tiered billiard ranking points OK " . $year . "</font>";
}
*/

// added for current year Season 2
//$date = date('Y-m-d \00:00:00');
//$year = date('Y');
/*
$month = date('m');
if($month < '08')
{
  $tier_season = 'S1';
}
else
{
  $tier_season = 'S2';
}
*/
$tier_season = $season;

//if(date('Y') >= 2023) && ($tier_season == 'S2')
//{
//  BilliardRP(2023, 'S2');
//}
//BilliardRP(2024, 'S1');
/*
$querytoexecute = "Update `vbsa3364_vbsa2`.`scrs` SET total_RP
=(tier01_rp+tier02_rp+tier03_rp+tier04_rp+tier05_rp+tier06_rp+tier07_rp+tier08_rp+tier09_rp
+tier10_rp+tier11_rp+tier12_rp+tier13_rp+tier14_rp+tier15_rp+tier16_rp+tier17_rp+tier18_rp) 
WHERE current_year_scrs = YEAR(CURDATE( ))";
*/


/*
$querytoexecute = "Update `vbsa3364_vbsa2`.`scrs` SET total_RP =
(Select Case
WHEN game_type = 'Snooker' THEN allocated_rp*pts_won
WHEN game_type = 'Billiards' AND scr_season = 'S1' AND current_year_scrs <= 2023 THEN allocated_rp*(pts_won/2)
WHEN game_type = 'Billiards' AND scr_season = 'S2' AND current_year_scrs >= 2023 THEN tier_rp*(pts_won/2)
ELSE 0
END)
WHERE current_year_scrs = YEAR(CURDATE( ))";
$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");
if (isset($result)) echo "<br><br><font face='arial' color='green'>7.1. Set total ranking points OK " . date("Y") . "</font>";

// added for previous 1 year
$querytoexecute = "Update `vbsa3364_vbsa2`.`scrs` SET total_RP =
(Select Case
WHEN game_type = 'Snooker' THEN allocated_rp*pts_won
WHEN game_type = 'Billiards' AND scr_season = 'S1' AND current_year_scrs <= 2023 THEN allocated_rp*(pts_won/2)
WHEN game_type = 'Billiards' AND scr_season = 'S2' AND current_year_scrs >= 2023 THEN tier_rp*(pts_won/2)
ELSE 0
END)
WHERE current_year_scrs = YEAR(CURDATE( )) -1";
$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");
if (isset($result)) echo "<br><br><font face='arial' color='green'>7.2. Set total ranking points OK " . (date("Y")-1) . "</font>";

// added for previous 2 year
$querytoexecute = "Update `vbsa3364_vbsa2`.`scrs` SET total_RP =
(Select Case
WHEN game_type = 'Snooker' THEN allocated_rp*pts_won
WHEN game_type = 'Billiards' AND scr_season = 'S1' AND current_year_scrs <= 2023 THEN allocated_rp*(pts_won/2)
WHEN game_type = 'Billiards' AND scr_season = 'S2' AND current_year_scrs >= 2023 THEN tier_rp*(pts_won/2)
ELSE 0
END)
WHERE current_year_scrs = YEAR(CURDATE( )) -2";
$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");
if (isset($result)) echo "<br><br><font face='arial' color='green'>7.3. Set total ranking points OK " . (date("Y")-2) . "</font>";

// added for previous current year (2023)
BilliardRP(2023, 'S2');
*/
//.............

//tested ok  - Calculate available match points for Snooker or Billiards

$querytoexecute = "UPDATE vbsa3364_vbsa2.scrs	
SET avail_pts = 	
(SELECT CASE
WHEN game_type = 'Snooker' THEN count_played*3
WHEN game_type = 'Billiards' THEN count_played*2
ELSE 0
END)
WHERE current_year_scrs = YEAR(CURDATE( )) ";

$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>8. Set total avail_pts OK </font>";


//tested ok  - Calculate percentage won

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`scrs` SET percent_won = pts_won/avail_pts*100 WHERE current_year_scrs = YEAR(CURDATE( ))";

$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>9. Player percentage won calculated  OK </font>";
// End Scores Table - Count matches played, Points Won, Ranking points and Percentage won

echo '<br/>'.'<br/>';
echo "<font face='arial'>Update Team Entries table - sum points for all players in each team from scores table for each match";

//tested ok calculates T01 round points in the current year

$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
INNER JOIN (
  SELECT team_id, SUM(scrs.r01s) as total
  FROM scrs
  GROUP BY team_id
) T2 ON T1.team_id = T2.team_id
SET T1.T01 = T2.total
WHERE team_cal_year = YEAR(CURDATE( ))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("T01 total points was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>1. Calculated T01 (match 1) total points OK</font>";

//tested ok calculates T02 round points in the current year

$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
INNER JOIN (
  SELECT team_id, SUM(scrs.r02s) as total
  FROM scrs
  GROUP BY team_id
) T2 ON T1.team_id = T2.team_id
SET T1.T02 = T2.total
WHERE team_cal_year = YEAR(CURDATE( ))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("T02 total points was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>2. Calculated T02 (match 2) total points OK</font>";

//tested ok calculates T03 round points in the current year

$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
INNER JOIN (
  SELECT team_id, SUM(scrs.r03s) as total
  FROM scrs
  GROUP BY team_id
) T2 ON T1.team_id = T2.team_id
SET T1.T03 = T2.total
WHERE team_cal_year = YEAR(CURDATE( ))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("T03 total points was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>3. Calculated T03 (match 3) total points OK</font>";


//tested ok calculates T04 round points in the current year

$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
INNER JOIN (
  SELECT team_id, SUM(scrs.r04s) as total
  FROM scrs
  GROUP BY team_id
) T2 ON T1.team_id = T2.team_id
SET T1.T04 = T2.total
WHERE team_cal_year = YEAR(CURDATE( ))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("T04 total points was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>4. Calculated T04 (match 4) total points OK</font>";


//tested ok calculates T05 round points in the current year

$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
INNER JOIN (
  SELECT team_id, SUM(scrs.r05s) as total
  FROM scrs
  GROUP BY team_id
) T2 ON T1.team_id = T2.team_id
SET T1.T05 = T2.total
WHERE team_cal_year = YEAR(CURDATE( ))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("T05 total points was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>5. Calculated T05 (match 5) total points OK</font>";


//tested ok calculates T06 round points in the current year

$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
INNER JOIN (
  SELECT team_id, SUM(scrs.r06s) as total
  FROM scrs
  GROUP BY team_id
) T2 ON T1.team_id = T2.team_id
SET T1.T06 = T2.total
WHERE team_cal_year = YEAR(CURDATE( ))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("T06 total points was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>6. Calculated T06 (match 6) total points OK</font>";


//tested ok calculates T07 round points in the current year

$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
INNER JOIN (
  SELECT team_id, SUM(scrs.r07s) as total
  FROM scrs
  GROUP BY team_id
) T2 ON T1.team_id = T2.team_id
SET T1.T07 = T2.total
WHERE team_cal_year = YEAR(CURDATE( ))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("T07 total points was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>7. Calculated T07 (match 7) total points OK</font>";


//tested ok calculates T08 round points in the current year

$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
INNER JOIN (
  SELECT team_id, SUM(scrs.r08s) as total
  FROM scrs
  GROUP BY team_id
) T2 ON T1.team_id = T2.team_id
SET T1.T08 = T2.total
WHERE team_cal_year = YEAR(CURDATE( ))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("T08 total points was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>8. Calculated T08 (match 8) total points OK</font>";


//tested ok calculates T09 round points in the current year

$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
INNER JOIN (
  SELECT team_id, SUM(scrs.r09s) as total
  FROM scrs
  GROUP BY team_id
) T2 ON T1.team_id = T2.team_id
SET T1.T09 = T2.total
WHERE team_cal_year = YEAR(CURDATE( ))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("T09 total points was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>9. Calculated T09 (match 9) total points OK</font>";


//tested ok calculates T10 round points in the current year

$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
INNER JOIN (
  SELECT team_id, SUM(scrs.r10s) as total
  FROM scrs
  GROUP BY team_id
) T2 ON T1.team_id = T2.team_id
SET T1.T10 = T2.total
WHERE team_cal_year = YEAR(CURDATE( ))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("T10 total points was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>10. Calculated T10 (match 10) total points OK</font>";


//tested ok calculates T11 round points in the current year

$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
INNER JOIN (
  SELECT team_id, SUM(scrs.r11s) as total
  FROM scrs
  GROUP BY team_id
) T2 ON T1.team_id = T2.team_id
SET T1.T11 = T2.total
WHERE team_cal_year = YEAR(CURDATE( ))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("T11 total points was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>11. Calculated T11 (match 11) total points OK</font>";


//tested ok calculates T12 round points in the current year

$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
INNER JOIN (
  SELECT team_id, SUM(scrs.r12s) as total
  FROM scrs
  GROUP BY team_id
) T2 ON T1.team_id = T2.team_id
SET T1.T12 = T2.total
WHERE team_cal_year = YEAR(CURDATE( ))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("T12 total points was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>12. Calculated T12 (match 12) total points OK</font>";


//tested ok calculates T13 round points in the current year

$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
INNER JOIN (
  SELECT team_id, SUM(scrs.r13s) as total
  FROM scrs
  GROUP BY team_id
) T2 ON T1.team_id = T2.team_id
SET T1.T13 = T2.total
WHERE team_cal_year = YEAR(CURDATE( ))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("T13 total points was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>13. Calculated T13 (match 13) total points OK</font>";


//tested ok calculates T14 round points in the current year

$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
INNER JOIN (
  SELECT team_id, SUM(scrs.r14s) as total
  FROM scrs
  GROUP BY team_id
) T2 ON T1.team_id = T2.team_id
SET T1.T14 = T2.total
WHERE team_cal_year = YEAR(CURDATE( ))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("T14 total points was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>14. Calculated T14 (match 14) total points OK</font>";


//tested ok calculates T15 round points in the current year

$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
INNER JOIN (
  SELECT team_id, SUM(scrs.r15s) as total
  FROM scrs
  GROUP BY team_id
) T2 ON T1.team_id = T2.team_id
SET T1.T15 = T2.total
WHERE team_cal_year = YEAR(CURDATE( ))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("T15 total points was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>15. Calculated T15 (match 15) total points OK</font>";


//tested ok calculates T16 round points in the current year

$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
INNER JOIN (
  SELECT team_id, SUM(scrs.r16s) as total
  FROM scrs
  GROUP BY team_id
) T2 ON T1.team_id = T2.team_id
SET T1.T16 = T2.total
WHERE team_cal_year = YEAR(CURDATE( ))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("T16 total points was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>16. Calculated T16 (match 16) total points OK</font>";


//tested ok calculates T17 round points in the current year

$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
INNER JOIN (
  SELECT team_id, SUM(scrs.r17s) as total
  FROM scrs
  GROUP BY team_id
) T2 ON T1.team_id = T2.team_id
SET T1.T17 = T2.total
WHERE team_cal_year = YEAR(CURDATE( ))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("T17 total points was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>17. Calculated T17 (match 17) total points OK</font>";


//tested ok calculates T18 round points in the current year

$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries  T1
INNER JOIN (
  SELECT team_id, SUM(scrs.r18s) as total
  FROM scrs
  GROUP BY team_id
) T2 ON T1.team_id = T2.team_id
SET T1.T18 = T2.total
WHERE team_cal_year = YEAR(CURDATE( ))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("T18 total points was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>18. Calculated T18 (match 18) total points OK</font>";
// end TOTAL POINTS FOR EACH MATCH


// total byes to date for each player. deducted from number of rounds in singles calculation.
/*
$month = date('m');
if($month < '08')
{
  $season = 'S1';
}
else
{
  $season = 'S2';
}
*/
$sql_bye = "Select * FROM vbsa3364_vbsa2.scrs where scr_season = '$season' and current_year_scrs = Year(CURDATE()) and MemberID = 1";
//echo("Byes " . $sql_bye . "<br>");
$result_bye = mysql_query($sql_bye, $connvbsa) or die(mysql_error());
while($build_byes = mysql_fetch_assoc($result_bye))
{
  //echo("Team ID " . $build_byes['team_id'] . ", Count Played " . $build_byes['count_played'] . "<br>");
  $sql_update = "Update scrs set byes_to_date = " . $build_byes['count_played'] . " where scr_season = '$season' and current_year_scrs = Year(CURDATE()) and team_id = " . $build_byes['team_id'];
  //echo("SQL " . $sql_update . "<br>");
  $result = mysql_query($sql_update, $connvbsa) or die("Bye to date was not updated");
}

if (isset($result)) echo "<br><br><font face='arial' color='green'>19. Byes to date calculated OK</font>";

/*
// added 20 Nov 2025
$sql_forfeit = "Select * FROM vbsa3364_vbsa2.scrs where scr_season = '$season' and current_year_scrs = Year(CURDATE()) and MemberID = 1000";
//echo("Forfeit " . $sql_forfeit . "<br>");
$result_forfeit = mysql_query($sql_forfeit, $connvbsa) or die(mysql_error());
while($build_forfeit = mysql_fetch_assoc($result_forfeit))
{
  //echo("Team ID " . $build_byes['team_id'] . ", Count Played " . $build_byes['count_played'] . "<br>");
  $sql_update = "Update scrs set forfeits_to_date = " . $build_forfeit['count_played'] . " where scr_season = '$season' and current_year_scrs = Year(CURDATE()) and team_id = " . $build_forfeit['team_id'] . " and MemberID = 1000";;
  //echo("SQL " . $sql_update . "<br>");
  $result = mysql_query($sql_update, $connvbsa) or die("Forfeit to date was not updated");
}

if (isset($result)) echo "<br><br><font face='arial' color='green'>20. Forfeit to date calculated OK</font>";
*/


mysql_close ($connvbsa);

}

else

{

echo "<form id='form1' name='form1' method='post' action='../Admin_update_tables/Update_Scores_Rank_STEP1.php?submit=1&season=" . $season . "'>";

echo "<input type='submit' id='submit' name='submit'>";

echo "</form>";

}

?>
</center>
</body>
</html>
<?php
?>