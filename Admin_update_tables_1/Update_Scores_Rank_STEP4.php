<?php require_once('../Connections/connvbsa.php'); ?>
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
      <td colspan="4" align="center">STEP 4 of the calculation process</td>
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


echo '<font face="arial" size="3">STEP 4 completed go to <span class="greenbg"><a href="Update_Scores_Rank_STEP4B.php">STEP 4B</a></span>';
echo '<br/><br/>';


echo '<br/>'.'<br/>';
echo "<font face='arial'>Update Billiard ranking points in the breaks table";

//tested ok REMOVES rankings_snooker table table

$querytoexecute = "UPDATE vbsa3364_vbsa2.breaks	
SET bill_rp = 	
(SELECT CASE
WHEN brk>39 AND brk<100 THEN '40'
WHEN brk>99 AND brk<200 THEN '100'
WHEN brk>199 AND brk<300 THEN '200'
WHEN brk>299 AND brk<400 THEN '300'
WHEN brk>399 AND brk<500 THEN '400'
WHEN brk>499 AND brk<600 THEN '500'
ELSE 0
END as bill_rp)
WHERE finals_brk='No'
AND brk_type='Billiards'";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>Error billiard ranking points in breaks table not reset</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>1. Billiard ranking points reset successfully in breaks table</font>";

echo '<br/>'.'<br/>';
echo "<font face='arial'>Snooker Rankings Master table";

//snooker rankings base data is stored in the `rank_aa_snooker_master` table.
//billiard rankings base data is stored in the `rank_a_billiards_master` table.
//this data is then used to create all other rankings tables for open weekly and tournament, womens and junior rankings

echo '<br/>'.'<br/>';
echo "<font face='arial'>Truncate / Insert Players that have ranking points & Update Snooker Master Rankings table - Set current and previously played ranking points";

//START SNOOKER clear all data from snooker rankings master table in preparation for recalculation

$querytoexecute = "TRUNCATE `vbsa3364_vbsa2`.`rank_aa_snooker_master`";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error rank_aa_snooker_master was not truncated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>1. Table - rank_aa_snooker_master was successfully truncated</font>";



//insert player member id from scrs table for all players that have 'total_rp' >0

$querytoexecute = "INSERT INTO `rank_aa_snooker_master`
SELECT scrs.MemberID as memb_id,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
members.Female,
members.Junior,
CURRENT_TIMESTAMP,
0,
0,
0,
0,
0,
0
FROM `vbsa3364_vbsa2`.`scrs`
LEFT JOIN `vbsa3364_vbsa2`.`members` ON members.MemberID=scrs.MemberID
WHERE current_year_scrs >YEAR(CURDATE( ))-3
AND(scrs.MemberID !=1 AND scrs.MemberID !=10 AND scrs.MemberID !=100 AND scrs.MemberID !=1000)
AND `game_type`='Snooker' AND `total_rp`>0
GROUP BY scrs.MemberID";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>2. Error snooker player member id's from scrs table not entered</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>2. Inserted snooker player member id from scrs table</font>";


//insert players from tourn_entry table that have 'rank_pts' >0 NOTE ignore member id's that are already in the table

$querytoexecute = "INSERT IGNORE INTO `rank_aa_snooker_master` (memb_id, jun, m_f) 
SELECT tourn_entry.tourn_memb_id as memb_id, members.Junior as jun, Female AS m_f
FROM `vbsa3364_vbsa2`.`tourn_entry`
LEFT JOIN `vbsa3364_vbsa2`.`members` ON members.MemberID=tourn_entry.tourn_memb_id
WHERE `entry_cal_year` >YEAR(CURDATE( ))-3 AND tourn_entry.tourn_type='Snooker'
GROUP BY tourn_memb_id
HAVING SUM(rank_pts)>0";
//echo("Tourn " . $querytoexecute . "<br>");
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error snooker players from tourn_entry table that have 'rank_pts' >0 were not inserted</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>3. Inserted snooker players from tourn_entry table that have 'rank_pts' >0 Ignored players that had been inserted previously.</font>";


//begin weekly ranking points 
//set 35% of ranking points from 2 years prior to current year for S2

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
INNER JOIN (SELECT ROUND(MAX(total_RP*35/100),0) AS yr2, MemberID
FROM scrs
WHERE current_year_scrs = YEAR(CURDATE( ))-2 AND game_type='Snooker' AND scr_season='S2'
GROUP BY MemberID
) T2 ON T1.memb_id= T2.MemberID
SET T1.scr_2yr_S2 = T2.yr2";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>4. Error 35% of -2 year S2 ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>4. 35% of -2 year S2 ranking points updated successfully</font>";


//set 35% of ranking points from 2 years prior to current year for S1

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
INNER JOIN (SELECT ROUND(MAX(total_RP*35/100),0) AS yr2, MemberID
FROM scrs
WHERE current_year_scrs = YEAR(CURDATE( ))-2 AND game_type='Snooker' AND scr_season='S1'
GROUP BY MemberID
) T2 ON T1.memb_id= T2.MemberID
SET T1.scr_2yr_S1 = T2.yr2";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error 35% of -2 year S1 ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>5. 35% of -2 year S1 ranking points updated successfully</font>";


//set 65% of ranking points from 1 year prior to current year for S2

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
INNER JOIN (SELECT ROUND(MAX(total_RP*65/100),0) AS yr1, MemberID
FROM scrs
WHERE current_year_scrs = YEAR(CURDATE( ))-1 AND game_type='Snooker' AND scr_season='S2'
GROUP BY MemberID
) T2 ON T1.memb_id= T2.MemberID
SET T1.scr_1yr_S2 = T2.yr1";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>6. Error 65% of -1 year S2 ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>6. 65% of -1 year S2 ranking points updated successfully</font>";


//set 65% of ranking points from 1 year prior to current year for S1

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
INNER JOIN (SELECT ROUND(MAX(total_RP*65/100),0) AS yr1, MemberID
FROM scrs
WHERE current_year_scrs = YEAR(CURDATE( ))-1 AND game_type='Snooker' AND scr_season='S1'
GROUP BY MemberID
) T2 ON T1.memb_id= T2.MemberID
SET T1.scr_1yr_S1 = T2.yr1";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>7. Error 65% of -1 year S1 ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>7. 65% of -1 year S1 ranking points updated successfully</font>";

//set 100% of ranking points for current year for S2

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
INNER JOIN (SELECT MAX(total_RP) AS curr, MemberID
FROM scrs
WHERE current_year_scrs = YEAR(CURDATE( )) AND game_type='Snooker' AND scr_season='S2'
GROUP BY MemberID
) T2 ON T1.memb_id= T2.MemberID
SET T1.scr_curr_S2 = T2.curr";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>8. Error 100% of current year S2 ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>8. 100% of current year S2 ranking points updated successfully</font>";

//set 100% of ranking points for current year for S1

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
INNER JOIN (SELECT MAX(total_RP) AS curr, MemberID
FROM scrs
WHERE current_year_scrs = YEAR(CURDATE( )) AND game_type='Snooker' AND scr_season='S1'
GROUP BY MemberID
) T2 ON T1.memb_id= T2.MemberID
SET T1.scr_curr_S1 = T2.curr";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>9. Error 100% of current year S1 ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>9. 100% of current year S1 ranking points updated successfully</font>";


//set total weekly ranking points

$querytoexecute = "UPDATE `rank_aa_snooker_master` SET weekly_total=scr_2yr_S2+scr_2yr_S1+scr_1yr_S2+scr_1yr_S1+scr_curr_S2+scr_curr_S1";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>10. Error total weekly ranking points not set</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>10. Set total weekly snooker ranking points successfully</font>";

//END weekly ranking points 

//START tournament ranking points
// calculate and insert 15% of total weekly ranking points to be addded to tournament points
$querytoexecute = "UPDATE `rank_aa_snooker_master` SET weekly_percent=ROUND(weekly_total*15/100)";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>11. Error weekly ranking points to be addded to tournament points were not set</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>11. Weekly ranking points to be addded to tournament points were set successfully</font>";

/*
//set 35% of tournament ranking points for 2 years prior to current year

// added 'and tourn_type = 'Snooker' to sql 30/10/2023
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
INNER JOIN (SELECT tourn_memb_id, ROUND(SUM(rank_pts*35/100)) AS RP2
FROM tourn_entry 
WHERE entry_cal_year=YEAR(CURDATE( ))-2
and tourn_type = 'Snooker' 
GROUP BY tourn_memb_id
HAVING SUM(rank_pts)>0
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_2 = T2.RP2";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>12. Error 35% of tournament ranking points for 2 years prior to current year were not set</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>12. 35% of tournament ranking points for 2 years prior to current year were set successfully</font>";


//set 65% of tournament ranking points for 1 year prior to current year


// added 'and tourn_type = 'Snooker' to sql 30/10/2023
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
INNER JOIN (SELECT tourn_memb_id, ROUND(SUM(rank_pts*65/100)) AS RP1
FROM tourn_entry 
WHERE entry_cal_year=YEAR(CURDATE( ))-1
and tourn_type = 'Snooker' 
GROUP BY tourn_memb_id
HAVING SUM(rank_pts)>0
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_1 = T2.RP1";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>13. Error 65% of tournament ranking points for 1 year prior to current year were not set</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>13. 35% of tournament ranking points for 1 year prior to current year were set successfully</font>";


//set current year ranking points

// added 'and tourn_type = 'Snooker' to sql 30/10/2023
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
INNER JOIN (SELECT tourn_memb_id, SUM(rank_pts) AS RPcurr
FROM tourn_entry 
WHERE entry_cal_year=YEAR(CURDATE( ))
and tourn_type = 'Snooker' 
GROUP BY tourn_memb_id
HAVING SUM(rank_pts)>0
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_curr = T2.RPcurr";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>14. Error 100% of tournament ranking points for current year were not set</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>14. 100% of tournament ranking points for current year were set successfully</font>";
*/

// **************************************
// Add Vic Ranking Snooker Data
// **************************************


//START tournament ranking points

//set 35% of tournament ranking points for 2 years prior to current year

// Add Vic Rank Year 2 Data
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, Round(SUM(rank_pts)*.35) AS RP_Yr2
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( ))-2 and tournaments.tourn_type = 'Snooker' and tournaments.ranking_type = 'Victorian' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_2 = T2.RP_Yr2";

// added 'and tourn_type = 'Snooker' to sql 30/10/2023
/*
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
INNER JOIN (SELECT tourn_memb_id, ROUND(SUM(rank_pts*35/100)) AS RP2
FROM tourn_entry 
WHERE entry_cal_year=YEAR(CURDATE( ))-2
and tourn_type = 'Snooker' 
GROUP BY tourn_memb_id
HAVING SUM(rank_pts)>0
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_2 = T2.RP2";
*/
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>12. Error 35% of tournament ranking points for 2 years prior to current year were not set</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>12. 35% of tournament ranking points for 2 years prior to current year were set successfully</font>";


//set 65% of tournament ranking points for 1 year prior to current year

// Add Vic Rank Year 1 Data
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, Round(SUM(rank_pts)*.65) AS RP_Yr1
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( ))-1 and tournaments.tourn_type = 'Snooker' and tournaments.ranking_type = 'Victorian' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_1 = T2.RP_Yr1";

// added 'and tourn_type = 'Snooker' to sql 30/10/2023
/*
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
INNER JOIN (SELECT tourn_memb_id, ROUND(SUM(rank_pts*65/100)) AS RP1
FROM tourn_entry 
WHERE entry_cal_year=YEAR(CURDATE( ))-1
and tourn_type = 'Snooker' 
GROUP BY tourn_memb_id
HAVING SUM(rank_pts)>0
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_1 = T2.RP1";
*/
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>13. Error 65% of tournament ranking points for 1 year prior to current year were not set</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>13. 35% of tournament ranking points for 1 year prior to current year were set successfully</font>";


//set current year ranking points

// Add Vic Rank Year 2 Data
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, SUM(rank_pts) AS RP_curr
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( )) and tournaments.tourn_type = 'Snooker' and tournaments.ranking_type = 'Victorian' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_curr = T2.RP_curr";

// added 'and tourn_type = 'Snooker' to sql 30/10/2023
/*
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
INNER JOIN (SELECT tourn_memb_id, SUM(rank_pts) AS RPcurr
FROM tourn_entry 
WHERE entry_cal_year=YEAR(CURDATE( ))
and tourn_type = 'Snooker' 
GROUP BY tourn_memb_id
HAVING SUM(rank_pts)>0
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_curr = T2.RPcurr";
*/
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>14. Error 100% of tournament ranking points for current year were not set</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>14. 100% of tournament ranking points for current year were set successfully</font>";


//calculate total tournament ranking points
$querytoexecute = "UPDATE `rank_aa_snooker_master` SET tourn_total=weekly_percent+tourn_2+tourn_1+tourn_curr
WHERE (weekly_percent+tourn_2+tourn_1+tourn_curr)>0";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>15. Error did not calculate total tournament ranking points</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>15. Calculate total tournament ranking points successfully</font>";

//END SNOOKER master table

//START BILLIARDS master table
echo '<br/>'.'<br/>';
echo "<font face='arial'>Billiard Rankings Master table";

//clear all data from billiards rankings in preparation for recalculation

$querytoexecute = "TRUNCATE `vbsa3364_vbsa2`.`rank_a_billiards_master`";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error rank_a_billiards_master was not truncated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>1. Table - rank_a_billiards_master was successfully truncated</font>";


//insert players billiards from scrs table all players that have 'total_rp' >0


//added 6 '0's for added fields
$querytoexecute = "INSERT INTO `rank_a_billiards_master`
SELECT scrs.MemberID as memb_id,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
members.Female,
members.Junior,
CURRENT_TIMESTAMP, 
0, 
0, 
0, 
0, 
0, 
0, 
0, 
0, 
0, 
0, 
0, 
0 
FROM `vbsa3364_vbsa2`.`scrs`
LEFT JOIN `vbsa3364_vbsa2`.`members` ON members.MemberID=scrs.MemberID
WHERE current_year_scrs >YEAR(CURDATE( ))-3
AND(scrs.MemberID !=1 AND scrs.MemberID !=10 AND scrs.MemberID !=100 AND scrs.MemberID !=1000)
AND `game_type`='Billiards' AND `total_rp`>0
GROUP BY scrs.MemberID";
//echo($querytoexecute . "<br>");
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>2. Error billiard player member id's from scrs table not entered</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>2. Inserted billiard player member id from scrs table</font>";


//insert players from tourn_entry table that have 'rank_pts' >0 NOTE ignore member id's that are already in the table
$querytoexecute = "INSERT IGNORE INTO `rank_a_billiards_master` (memb_id, jun, m_f) 
SELECT tourn_entry.tourn_memb_id as memb_id, members.Junior as jun, Female AS m_f
FROM `vbsa3364_vbsa2`.`tourn_entry`
LEFT JOIN `vbsa3364_vbsa2`.`members` ON members.MemberID=tourn_entry.tourn_memb_id
WHERE `entry_cal_year` >YEAR(CURDATE( ))-3 AND tourn_entry.tourn_type='Billiards'
GROUP BY tourn_memb_id
HAVING SUM(rank_pts)>0";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error billiard players from tourn_entry table that have 'rank_pts' >0 were not inserted</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>3. Inserted billiard players from tourn_entry table that have 'rank_pts' >0 Ignored players that had been inserted previously.</font>";


//begin weekly ranking points 
//set 35% of ranking points from 2 years prior to current year for S2

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN (SELECT ROUND(SUM(total_RP*35/100),0) AS yr2, MemberID
FROM scrs
WHERE current_year_scrs = YEAR(CURDATE( ))-2 
AND game_type='Billiards' 
AND scr_season='S2'
GROUP BY MemberID
) T2 ON T1.memb_id= T2.MemberID
SET T1.scr_2yr_S2 = T2.yr2";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>4. Error 35% of -2 year S2 ranking points not inserted</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>4. 35% of -2 year S2 ranking points not updated successfully</font>";


//set 35% of ranking points from 2 years prior to current year for S1

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN (SELECT ROUND(SUM(total_RP*35/100),0) AS yr2, MemberID
FROM scrs
WHERE current_year_scrs = YEAR(CURDATE( ))-2 
AND game_type='Billiards' 
AND scr_season='S1'
GROUP BY MemberID
) T2 ON T1.memb_id= T2.MemberID
SET T1.scr_2yr_S1 = T2.yr2";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error 35% of -2 year S1 ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>5. 35% of -2 year S1 ranking points updated successfully</font>";


//set 65% of ranking points from 1 year prior to current year for S2

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN (SELECT ROUND(SUM(total_RP*65/100),0) AS yr1, MemberID
FROM scrs
WHERE current_year_scrs = YEAR(CURDATE( ))-1 
AND game_type='Billiards' 
AND scr_season='S2'
GROUP BY MemberID
) T2 ON T1.memb_id= T2.MemberID
SET T1.scr_1yr_S2 = T2.yr1";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>6. Error 65% of -1 year S2 ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>6. 65% of -1 year S2 ranking points updated successfully</font>";


//set 65% of ranking points from 1 year prior to current year for S1

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN (SELECT ROUND(SUM(total_RP*65/100),0) AS yr1, MemberID
FROM scrs
WHERE current_year_scrs = YEAR(CURDATE( ))-1 
AND game_type='Billiards' 
AND scr_season='S1'
GROUP BY MemberID
) T2 ON T1.memb_id= T2.MemberID
SET T1.scr_1yr_S1 = T2.yr1";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>7. Error 65% of -1 year S1 ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>7. 65% of -1 year S1 ranking points updated successfully</font>";

//set 100% of ranking points for current year for S2

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN (SELECT SUM(total_RP) AS curr, MemberID
FROM scrs
WHERE current_year_scrs = YEAR(CURDATE( )) 
AND game_type='Billiards' 
AND scr_season='S2'
GROUP BY MemberID
) T2 ON T1.memb_id= T2.MemberID
SET T1.scr_curr_S2 = T2.curr";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>8. Error 100% of current year S2 ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>8. 100% of current year S2 ranking points updated successfully</font>";

//set 100% of ranking points for current year for S1

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN (SELECT SUM(total_RP) AS curr, MemberID
FROM scrs
WHERE current_year_scrs = YEAR(CURDATE( )) 
AND game_type='Billiards' 
AND scr_season='S1'
GROUP BY MemberID
) T2 ON T1.memb_id= T2.MemberID
SET T1.scr_curr_S1 = T2.curr";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>9. Error 100% of current year S1 ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>9. 100% of current year S1 ranking points updated successfully</font>";

//award ranking points for billiard breaks 2 years ago includes tournament breaks

// now split into s1 and s2.

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN 
(SELECT member_ID_brks, SUM(bill_rp*.35) AS BRPY2_S1
FROM breaks 
WHERE YEAR(recvd)=YEAR(CURDATE( ))-2 and Season = 'S1'
GROUP BY member_ID_brks
) T2 ON T1.memb_id= T2.member_ID_brks
SET T1.brks_2yr_S1 = T2.BRPY2_S1";
//echo("<br>" . $querytoexecute . "<br>");
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>10. Error ranking points for billiard breaks 2 years, S1 prior not awarded </font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>10. Ranking points for billiard breaks 2 years, S1 prior awarded successfully</font>";

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN 
(SELECT member_ID_brks, SUM(bill_rp*.35) AS BRPY2_S2
FROM breaks 
WHERE YEAR(recvd)=YEAR(CURDATE( ))-2 and Season = 'S2'
GROUP BY member_ID_brks
) T2 ON T1.memb_id= T2.member_ID_brks
SET T1.brks_2yr_S2 = T2.BRPY2_S2";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>10. Error ranking points for billiard breaks 2 years, S2 prior not awarded </font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>11. Ranking points for billiard breaks 2 years, S2 prior awarded successfully</font>";

//award ranking points for billiard breaks 1 year ago includes tournament breaks

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN 
(SELECT member_ID_brks, SUM(bill_rp*.65) AS BRP1_S1
FROM breaks 
WHERE YEAR(recvd)=YEAR(CURDATE( ))-1 and Season = 'S1'
GROUP BY member_ID_brks
) T2 ON T1.memb_id= T2.member_ID_brks
SET T1.brks_1yr_S1 = T2.BRP1_S1";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>11. Error ranking points for billiard breaks 1 year prior not awarded </font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>12. Ranking points for billiard breaks 1 year, S1 prior awarded successfully</font>";

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN 
(SELECT member_ID_brks, SUM(bill_rp*.65) AS BRP1_S2
FROM breaks 
WHERE YEAR(recvd)=YEAR(CURDATE( )) -1 and Season = 'S2'
GROUP BY member_ID_brks
) T2 ON T1.memb_id= T2.member_ID_brks
SET T1.brks_1yr_S2 = T2.BRP1_S2";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>12. Error ranking points for billiard breaks current year not awarded </font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>13. Ranking points for billiard breaks 1 year, S2 prior awarded successfully</font>";

//award ranking points for billiard breaks current year includes tournament breaks

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN 
(SELECT member_ID_brks, SUM(bill_rp) AS BRPCurr_S1
FROM breaks 
WHERE YEAR(recvd)=YEAR(CURDATE( )) and Season = 'S1'
GROUP BY member_ID_brks
) T2 ON T1.memb_id= T2.member_ID_brks
SET T1.brks_curr_S1 = T2.BRPCurr_S1";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>12. Error ranking points for billiard breaks current year not awarded </font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>14. Ranking points for billiard breaks current S1 awarded successfully</font>";

//award ranking points for billiard breaks current year includes tournament breaks

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN 
(SELECT member_ID_brks, SUM(bill_rp) AS BRPCurr_S2
FROM breaks 
WHERE YEAR(recvd)=YEAR(CURDATE( )) and Season = 'S2'
GROUP BY member_ID_brks
) T2 ON T1.memb_id= T2.member_ID_brks
SET T1.brks_curr_S2 = T2.BRPCurr_S2";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>12. Error ranking points for billiard breaks current year not awarded </font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>15. Ranking points for billiard breaks current S2 awarded successfully</font>";

//award ranking points for billiard breaks 1 year ago includes tournament breaks
// not split into S1 and S2

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN 
(SELECT member_ID_brks, SUM(bill_rp*.35) AS BRP2
FROM breaks 
WHERE YEAR(recvd)=YEAR(CURDATE( ))-2
GROUP BY member_ID_brks
) T2 ON T1.memb_id= T2.member_ID_brks
SET T1.brks_2 = T2.BRP2";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>10. Error ranking points for billiard breaks 2 years prior not awarded </font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>16. Ranking points for billiard breaks 2 years prior awarded successfully</font>";


//award ranking points for billiard breaks 1 year ago includes tournament breaks

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN 
(SELECT member_ID_brks, SUM(bill_rp*.65) AS BRP1
FROM breaks 
WHERE YEAR(recvd)=YEAR(CURDATE( ))-1
GROUP BY member_ID_brks
) T2 ON T1.memb_id= T2.member_ID_brks
SET T1.brks_1 = T2.BRP1";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>11. Error ranking points for billiard breaks 1 year prior not awarded </font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>17. Ranking points for billiard breaks 1 year prior awarded successfully</font>";


//award ranking points for billiard breaks current year includes tournament breaks

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN 
(SELECT member_ID_brks, SUM(bill_rp) AS BRP
FROM breaks 
WHERE YEAR(recvd)=YEAR(CURDATE( ))
GROUP BY member_ID_brks
) T2 ON T1.memb_id= T2.member_ID_brks
SET T1.brks_curr = T2.BRP";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>12. Error ranking points for billiard breaks current year not awarded </font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>18. Ranking points for billiard breaks current year awarded successfully</font>";

//completed weekly ranking points (pennants)

//begin tournament ranking points

//set 35% of tournament ranking points for 2 years prior to current year

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, Round(SUM(rank_pts)*.35) AS RP_Yr2
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( ))-2 and tournaments.tourn_type = 'Billiards' and tournaments.ranking_type = 'Victorian' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_2 = T2.RP_Yr2";
/*
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN (SELECT tourn_memb_id, ROUND(SUM(rank_pts*35/100)) AS RP2
FROM tourn_entry 
WHERE entry_cal_year=YEAR(CURDATE( ))-2 and tourn_type = 'Billiards'
GROUP BY tourn_memb_id
HAVING SUM(rank_pts)>0
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_2 = T2.RP2";
*/
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>13. Error 35% of -2 year tournament ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>19. 35% of -2 year tournament ranking points updated successfully</font>";


//set 65% of tournament ranking points for 1 year prior to current year
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, Round(SUM(rank_pts)*.65) AS RP_Yr1
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( ))-1 and tournaments.tourn_type = 'Billiards' and tournaments.ranking_type = 'Victorian' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_1 = T2.RP_Yr1";
/*
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN (SELECT tourn_memb_id, ROUND(SUM(rank_pts*65/100)) AS RP1
FROM tourn_entry 
WHERE entry_cal_year=YEAR(CURDATE( ))-1 and tourn_type = 'Billiards'
GROUP BY tourn_memb_id
HAVING SUM(rank_pts)>0
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_1 = T2.RP1";
*/
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>14. Error 65% of -1 year tournament ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>20. 65% of -1 year tournament ranking points updated successfully</font>";


//set current year tournament ranking points

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, SUM(rank_pts) AS RPCurr
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( )) and tournaments.tourn_type = 'Billiards' and tournaments.ranking_type = 'Victorian' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_curr = T2.RPCurr";
/*
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN (SELECT tourn_memb_id, SUM(rank_pts) AS RPcurr
FROM tourn_entry 
WHERE entry_cal_year=YEAR(CURDATE( )) and tourn_type = 'Billiards'
GROUP BY tourn_memb_id
HAVING SUM(rank_pts)>0
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_curr = T2.RPcurr";
*/
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>15. Error current year tournament ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>21. Current year tournament ranking points updated successfully</font>";


//total for Victorian Billiards rankings ranking points

$querytoexecute = "UPDATE `rank_a_billiards_master` 
SET total_rp=scr_2yr_S2+scr_2yr_S1+scr_1yr_S2+scr_1yr_S1+scr_curr_S2+scr_curr_S1+
brks_2+brks_1+brks_curr+tourn_2+tourn_1+tourn_curr
";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>16. Error current year tournament ranking points not totalled</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>22. Current year tournament ranking points totalled successfully</font>";

//tournament ranking points completed

//END BILLIARDS master table



echo '<br/>'.'<br/>';
//echo "<font face='arial'>Empty - Open snooker weekly rankings, Open snooker tournament rankings, Womens snooker, junior snooker and Billiards tables and copy data from Master tables";

//START empty ranking tables then insert updated data from master tables
//FOR open snooker weekly rankings, open snooker tournament rankings, womens snooker, junior snooker and Billiards


//Truncate tables
//rank_S_open_weekly Snooker Weekly Rankings
$querytoexecute = "TRUNCATE TABLE `rank_S_open_weekly`";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error - rank_S_open_weekly table - Snooker Weekly Rankings not truncated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 1. Truncated rank_S_open_weekly table - Snooker Weekly Rankings successfully</font>";

//rank_S_open_tourn Snooker Tournament Rankings
$querytoexecute = "TRUNCATE TABLE `rank_S_open_tourn`";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>2. Error - rank_S_open_tourn table - Snooker Tournament Rankings not truncated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 2. Truncated rank_S_open_tourn table - Snooker Tournament Rankings successfully</font>";

//rank_S_womens Womens Snooker Rankings
$querytoexecute = "TRUNCATE TABLE `rank_S_womens` ";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error - rank_S_womens table -  Womens Snooker Rankings
 not truncated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 3. Truncated rank_S_womens table - Womens Snooker Rankings successfully</font>";

//rank_S_junior Junior Snooker Rankings
$querytoexecute = "TRUNCATE TABLE `rank_S_junior`";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>4. Error - rank_S_junior table - Junior Snooker Rankings not truncated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 4. Truncated rank_S_junior table - Junior Snooker successfully</font>";

//Insert data into ranking tables
//rank_S_open_weekly Snooker Weekly Rankings
$querytoexecute = "INSERT INTO `rank_S_open_weekly` 
SELECT 
0,
0,
rank_aa_snooker_master.memb_id AS memb_id,
rank_aa_snooker_master.weekly_total AS total_weekly_rp,
CURRENT_TIMESTAMP
FROM rank_aa_snooker_master
GROUP BY rank_aa_snooker_master.memb_id
ORDER BY weekly_total DESC";

//echo("Snooker Weekly - " . $querytoexecute . "<br>");

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error - rank_S_open_weekly table - Snooker Weekly Rankings data not inserted</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 5. Data inserted to rank_S_open_weekly table - Snooker Weekly Rankings successfully</font>";

//rank_S_open_tourn Snooker Tournament Rankings
$querytoexecute = "INSERT INTO `rank_S_open_tourn`
SELECT 
0,
0,
rank_aa_snooker_master.memb_id AS memb_id,
rank_aa_snooker_master.tourn_total AS total_tourn_rp,
CURRENT_TIMESTAMP
FROM rank_aa_snooker_master
WHERE tourn_total>0
GROUP BY rank_aa_snooker_master.memb_id
ORDER BY tourn_total DESC";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>6. Error - rank_S_open_tourn table - Snooker Tournament Rankings data not inserted</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 6. Data inserted to rank_S_open_tourn table - Snooker Tournament Rankings successfully</font>";

//rank_S_womens Womens Snooker Rankings
$querytoexecute = "INSERT INTO `rank_S_womens`
SELECT 
0,
0,
rank_aa_snooker_master.memb_id AS memb_id,
rank_aa_snooker_master.tourn_total AS total_rp,
CURRENT_TIMESTAMP
FROM rank_aa_snooker_master
WHERE m_f='1' AND tourn_total>0 
GROUP BY rank_aa_snooker_master.memb_id
ORDER BY tourn_total DESC";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>7. Error - rank_S_womens table - Womens Snooker Rankings data not inserted</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 7. Data inserted to rank_S_womens table - Womens Snooker Rankings successfully</font>";

//rank_S_junior Junior Snooker Rankings
$querytoexecute = "INSERT INTO `rank_S_junior`
SELECT 
0,
0,
rank_aa_snooker_master.memb_id AS memb_id,
rank_aa_snooker_master.tourn_total AS total_rp,
CURRENT_TIMESTAMP
FROM rank_aa_snooker_master
WHERE jun !='na' AND tourn_total>0 
GROUP BY rank_aa_snooker_master.memb_id
ORDER BY tourn_total DESC";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>8. Error - rank_S_junior table - Junior Snooker Rankings data not inserted</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 8. Data inserted to rank_S_junior table - Junior Snooker Rankings successfully</font>";


/*
// see page 4C

//rank_Billiards Billiards Rankings
$querytoexecute = "TRUNCATE TABLE `rank_Billiards`";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><br><font face='arial' color='red'>1. Error - rank_Billiards table - Billiards Rankings not truncated</font>");

if (isset($result)) echo "<br><br><br><font face='arial' color='green'> 1. Truncated rank_Billiards table - Billiards Rankings successfully</font>";

//rank_B_womens Womens Billiard Rankings
$querytoexecute = "TRUNCATE TABLE `rank_B_womens` ";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>2. Error - rank_B_womens table -  Womens Billiard Rankings not truncated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 2. Truncated rank_B_womens table - Womens Billiard Rankings successfully</font>";

//rank_B_junior Junior Billiard Rankings
$querytoexecute = "TRUNCATE TABLE `rank_B_junior`";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error - rank_B_junior table -  Junior's Billiard Rankings
 not truncated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 3. Truncated rank_B_junior table - Junior's Billiard Rankings successfully</font>";

$querytoexecute = "Insert INTO rank_Billiards
SELECT 
0,
0,
rank_a_billiards_master.memb_id AS memb_id,
Round((ROUND(tourn_2)) + 
      (ROUND(tourn_1)) + 
      tourn_curr + 
      brks_curr + 
      (ROUND(brks_2)) + 
      (ROUND(brks_1)) +
      (ROUND(scr_curr_S2)) + 
      (ROUND(scr_curr_S1)) + 
      (ROUND(scr_1yr_S1)) +
      (ROUND(scr_1yr_S2)) +
      (ROUND(scr_2yr_S1)) +
      (ROUND(scr_2yr_S2))) AS total_rp,
CURRENT_DATE
FROM rank_a_billiards_master
GROUP BY rank_a_billiards_master.memb_id
ORDER BY total_rp DESC";
//echo("Billiards " . $querytoexecute . "<br>");

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>4. Error - rank_Billiards table - Billiard Rankings data not inserted</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 4. Data inserted to rank_Billiards table - Billiard Rankings successfully</font>";

$querytoexecute = "Insert INTO rank_B_womens
SELECT 
0,
0,
rank_a_billiards_master.memb_id AS memb_id,
(ROUND(tourn_2_w)) + 
(ROUND(tourn_1_w)) + 
tourn_curr_w + 
brks_curr + 
(ROUND(brks_2)) + 
(ROUND(brks_1)) +
(ROUND(scr_curr_S2)) + 
(ROUND(scr_curr_S1)) + 
(ROUND(scr_1yr_S1)) +
(ROUND(scr_1yr_S2)) +
(ROUND(scr_2yr_S1)) +
(ROUND(scr_2yr_S2)) AS total_rp,
CURRENT_DATE
FROM rank_a_billiards_master
GROUP BY rank_a_billiards_master.memb_id";
//echo("Billiards " . $querytoexecute . "<br>");

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error - rank_B_womens table - Billiard Rankings data not inserted</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 5. Data inserted to rank_B_womens table - Billiard Rankings successfully</font>";

$querytoexecute = "Insert INTO rank_B_junior
SELECT 
0,
0,
rank_a_billiards_master.memb_id AS memb_id,
Round((ROUND(tourn_2_j)) + 
      (ROUND(tourn_1_j)) + 
      tourn_curr_j + 
      brks_curr + 
      (ROUND(brks_2)) + 
      (ROUND(brks_1)) +
      (ROUND(scr_curr_S2)) + 
      (ROUND(scr_curr_S1)) + 
      (ROUND(scr_1yr_S1)) +
      (ROUND(scr_1yr_S2)) +
      (ROUND(scr_2yr_S1)) +
      (ROUND(scr_2yr_S2))) AS total_rp,
CURRENT_DATE
FROM rank_a_billiards_master
GROUP BY rank_a_billiards_master.memb_id
ORDER BY total_rp DESC";
//echo("Billiards " . $querytoexecute . "<br>");

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>6. Error - rank_B_junior table - Billiard Rankings data not inserted</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 6. Data inserted to rank_B_junior table - Billiard Rankings successfully</font>";

*/

// **************************************
// Add Womens and Juniors Billiards Data
// **************************************
echo '<br/>'.'<br/>';
echo "<font face='arial'>Billiards Womens and Junior Rankings Master table";

echo '<br/>'.'<br/>';
echo "<font face='arial'>Start Womens Billiard Rankings";

// Add Womens Current Data
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, SUM(rank_pts) AS RPcurr
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( )) and tournaments.tourn_type = 'Billiards' and tournaments.ranking_type = 'Womens' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_curr_w = T2.RPcurr";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error current year tournament womens ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>1. Current year tournament womens ranking points updated successfully</font>";

// Add Womens Year 1 Data
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, Round(SUM(rank_pts)*.65) AS RP_Yr1
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( ))-1 and tournaments.tourn_type = 'Billiards' and tournaments.ranking_type = 'Womens' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_1_w = T2.RP_Yr1";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>2. Error current year tournament womens ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>2. Year 1 tournament womens ranking points updated successfully</font>";

// Add Womens Year 2 Data
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, Round(SUM(rank_pts)*.35) AS RP_Yr2
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( ))-2 and tournaments.tourn_type = 'Billiards' and tournaments.ranking_type = 'Womens' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_2_w = T2.RP_Yr2";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error current year tournament victorian ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>3. Year 2 tournament womens ranking points updated successfully</font>";

echo '<br/>'.'<br/>';
echo "<font face='arial'>End of Womens Billiard Rankings";


echo '<br/>'.'<br/>';
echo "<font face='arial'>Start Juniors Billiard Rankings";

// Add Juniors Current Data
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, SUM(rank_pts) AS RPcurr
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( )) and tournaments.tourn_type = 'Billiards' and tournaments.ranking_type = 'Junior' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_curr_j = T2.RPcurr";

//echo($querytoexecute . "<br>");

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error current year tournament junior ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>1. Current year tournament junior ranking points updated successfully</font>";

// Add Juniors Year 1 Data
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, Round(SUM(rank_pts)*.65) AS RP_Yr1
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( ))-1 and tournaments.tourn_type = 'Billiards' and tournaments.ranking_type = 'Junior' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_1_j = T2.RP_Yr1";

//echo($querytoexecute . "<br>");

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>2. Error current year tournament junior ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>2. Year 1 tournament junior ranking points updated successfully</font>";

// Add Juniors Year 2 Data
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, Round(SUM(rank_pts)*.35) AS RP_Yr2
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( ))-2 and tournaments.tourn_type = 'Billiards' and tournaments.ranking_type = 'Junior' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_2_j = T2.RP_Yr2";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error current year tournament junior ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>3. Year 2 tournament junior ranking points updated successfully</font>";

  echo '<br/>'.'<br/>';
  echo "<font face='arial'>End of Test Junior Billiard Rankings";


// **************************************
// Add Womens and Juniors Snooker Data
// **************************************
echo '<br/>'.'<br/>';
echo "<font face='arial'>Snooker Womens and Junior Rankings Master table";

echo '<br/>'.'<br/>';
echo "<font face='arial'>Start Womens Snooker Rankings";

// Add Womens Current Data
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, SUM(rank_pts) AS RPcurr
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( )) and tournaments.tourn_type = 'Snooker' and tournaments.ranking_type = 'Womens' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_curr_w = T2.RPcurr";
//echo("<br>" . $querytoexecute . "<br>");

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error current year tournament womens ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>1. Current year tournament womens ranking points updated successfully</font>";

// Add Womens Year 1 Data
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, Round(SUM(rank_pts)*.65) AS RP_Yr1
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( ))-1 and tournaments.tourn_type = 'Snooker' and tournaments.ranking_type = 'Womens' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_1_w = T2.RP_Yr1";
//echo("<br>" . $querytoexecute . "<br>");
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>2. Error current year tournament womens ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>2. Year 1 tournament womens ranking points updated successfully</font>";

// Add Womens Year 2 Data
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, Round(SUM(rank_pts)*.35) AS RP_Yr2
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( ))-2 and tournaments.tourn_type = 'Snooker' and tournaments.ranking_type = 'Womens' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_2_w = T2.RP_Yr2";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error current year tournament victorian ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>3. Year 2 tournament womens ranking points updated successfully</font>";

echo '<br/>'.'<br/>';
echo "<font face='arial'>End of Womens Snooker Rankings";


echo '<br/>'.'<br/>';
echo "<font face='arial'>Start Juniors Snooker Rankings";

// Add Juniors Current Data
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, SUM(rank_pts) AS RPcurr
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( )) and tournaments.tourn_type = 'Snooker' and tournaments.ranking_type = 'Junior' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_curr_j = T2.RPcurr";

//echo($querytoexecute . "<br>");

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error current year tournament junior ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>1. Current year tournament junior ranking points updated successfully</font>";

// Add Juniors Year 1 Data
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, Round(SUM(rank_pts)*.65) AS RP_Yr1
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( ))-1 and tournaments.tourn_type = 'Snooker' and tournaments.ranking_type = 'Junior' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_1_j = T2.RP_Yr1";

//echo($querytoexecute . "<br>");

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>2. Error current year tournament junior ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>2. Year 1 tournament junior ranking points updated successfully</font>";

// Add Juniors Year 2 Data
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, Round(SUM(rank_pts)*.35) AS RP_Yr2
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( ))-2 and tournaments.tourn_type = 'Snooker' and tournaments.ranking_type = 'Junior' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_2_j = T2.RP_Yr2";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error current year tournament junior ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>3. Year 2 tournament junior ranking points updated successfully</font>";

  echo '<br/>'.'<br/>';
  echo "<font face='arial'>End of Test Junior Snooker Rankings";

echo '<br/>'.'<br/>';
echo "<font face='arial'>Start Update Billiards scores for and scores against<br>";
// may need to diffirentiate between billiards grades if more than one grade.

$month = date('m');
if($month < '08')
{
  $season = 'S1';
}
else
{
  $season = 'S2';
}

// get max round (Home and Away only)
$query_round = "Select round FROM tbl_fixtures WHERE type = 'Billiards' AND year = YEAR( CURDATE( ) ) AND season = '" . $season . "' order by round DESC LIMIT 1";
$scores_round = mysql_query($query_round, $connvbsa) or die(mysql_error());
$row_scores_round = mysql_fetch_assoc($scores_round);
$max_round = $row_scores_round['round'];  // last round to save inc finals

// get finals team number
$query_final = "Select finals_teams FROM Team_grade WHERE type = 'Billiards' AND fix_cal_year = YEAR( CURDATE( ) ) AND season = '" . $season . "' and current = 'Yes'";
$scores_final = mysql_query($query_final, $connvbsa) or die(mysql_error());
$row_scores_final = mysql_fetch_assoc($scores_final);
$finals_teams = $row_scores_final['finals_teams'];

//get last saved round
$query_saved = "Select calculated_round FROM scrs WHERE game_type = 'Billiards' AND current_year_scrs = YEAR( CURDATE( ) ) AND scr_season = '" . $season . "' Order By calculated_round DESC LIMIt 1";
$scores_saved = mysql_query($query_saved, $connvbsa) or die(mysql_error());
$row_scores_saved = mysql_fetch_assoc($scores_saved);
$last_saved_round = $row_scores_saved['calculated_round'];

//get last round played
$query_last = "Select round FROM tbl_scoresheet WHERE type = 'Billiards' AND year = YEAR( CURDATE( ) ) AND season = '" . $season . "' Order by round DESC Limit 1";
$scores_last = mysql_query($query_last, $connvbsa) or die(mysql_error());
$row_scores_last = mysql_fetch_assoc($scores_last);
$last_round = $row_scores_last['round'];

//$last_round = 13;
// check if saved round is 0.............

$max_round_history = ($max_round - ($finals_teams/2)); // last round to save (no finals)
//echo("Max Round " . $max_round . "<br>");
//echo("Last Saved " . $last_saved_round . "<br>");
//echo("Last Round Played " . $last_round . "<br>");
//echo("Max Round (no finals) " . $max_round_history . "<br>");


//if($max_round_history <= $saved_round)
if(($last_saved_round < $max_round_history) && ($last_round > $last_saved_round) && ($last_round > 0))
{
  //$max_round_history = 1;
  //$round_to_save = ($saved_round+1);
  If($last_round > $max_round_history)
  {
    $round_to_save = $max_round_history;
  }
  else
  {
    $round_to_save = $last_round;
  }

  echo "<font face='arial' color='green'><br>1. Last Round Saved is " . $last_saved_round . ", Round being saved is " . $round_to_save . "<br>";
  //echo "<font face='arial'><br>Last Round Saved is " . $last_saved_round . ", Max Round is " . $max_round_history. ", Round being saved is " . $round_to_save . "<br>";
  echo("<font face='arial' color='green'>2. Saving Data<br>");

  // calculate scores for and against for billiard players
  // get a list of players
  $query_scores_ladder = "Select distinct MemberID, team, playing_position, FirstName, LastName, round FROM tbl_scoresheet WHERE type='Billiards' AND year = YEAR( CURDATE( ) ) AND season = '" . $season . "' AND round = " . $round_to_save . " AND (MemberID != 100) AND (MemberID != 1000) Order by MemberID, round";
  $scores_ladder = mysql_query($query_scores_ladder, $connvbsa) or die(mysql_error());
  while($row_scores_ladder = mysql_fetch_assoc($scores_ladder))
  {
    // get existing scores for/against
    $query_existing_scores = "Select scores_for, scores_against FROM scrs WHERE game_type='Billiards' AND current_year_scrs = YEAR( CURDATE( ) ) AND scr_season = '" . $season . "' AND MemberID = " . $row_scores_ladder['MemberID'] . " Order by MemberID";
    $existing_scores = mysql_query($query_existing_scores, $connvbsa) or die(mysql_error());
    $row_existing_scores = mysql_fetch_assoc($existing_scores);
    $existing_scores_for = $row_existing_scores['scores_for'];
    $existing_scores_against = $row_existing_scores['scores_against'];

    //calculate scores for for this round
    $query_scores_for = "Select SUM(score_1) as total_scores_1 FROM tbl_scoresheet WHERE memberid = " . $row_scores_ladder['MemberID'] . " AND year = YEAR( CURDATE( ) ) AND season = '" . $season . "' AND round = " . $round_to_save . "  Order By round";
    $scores_for = mysql_query($query_scores_for, $connvbsa) or die(mysql_error());
    $row_scores_for = mysql_fetch_assoc($scores_for);
    $total_scores_for = $row_scores_for['total_scores_1'];
    
    // get players played against
     $query_teams_against = "Select distinct opposition FROM tbl_scoresheet WHERE memberid =" . $row_scores_ladder['MemberID'] . " AND type='Billiards' AND year = YEAR( CURDATE( ) ) AND season = '" . $season . "'";
    $teams_against = mysql_query($query_teams_against, $connvbsa) or die(mysql_error());
    $total_scores_against = 0;
    while($row_teams_against = mysql_fetch_assoc($teams_against))
    {
       //calculate scores against for this round
      $query_scores_against = "Select SUM(score_1) as total_scores_1, round FROM tbl_scoresheet WHERE team = '" . $row_teams_against['opposition'] . "' AND opposition = '" . $row_scores_ladder['team'] . "' AND playing_position = " . $row_scores_ladder['playing_position'] . " AND year = YEAR( CURDATE( ) ) AND season = '" . $season . "' AND round = " . $round_to_save . " ";
      $scores_against = mysql_query($query_scores_against, $connvbsa) or die(mysql_error());
      $row_scores_against = mysql_fetch_assoc($scores_against);
      $total_scores_against = ($row_scores_against['total_scores_1'] + $total_scores_against);
    }

    // total all scores
    $total_scores_for = ($total_scores_for+$existing_scores_for);
    $total_scores_against = ($total_scores_against+$existing_scores_against);

    $team_percentage = (($total_scores_for/$total_scores_against)*100);

    // update scrs table
    $querytoexecute = "Update scrs SET scores_for = " . $total_scores_for . ", scores_against = " . $total_scores_against . ", scores_percent = " . $team_percentage . ", calculated_round = " . $round_to_save . " WHERE game_type='Billiards' AND current_year_scrs = YEAR( CURDATE( ) ) AND MemberID = " . $row_scores_ladder['MemberID'] . " AND scr_season = '" . $season . "' ";
    $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error Billiard scores for and scores against not updated</font>");
  //}
    // finish scores for/against calculation
  }

}
else
{
  echo "<font face='arial' color='green'><br>1. Last Round Saved is " . $last_saved_round . ", Round to save is " . $last_round . "<br>";
  //echo "<font face='arial'><br>Last Round Saved is " . $last_saved_round . ", Max Round is " . $max_round_history . "<br>";
  echo("<font face='arial' color='green'>2. No need to save data<br>");
}

if (isset($result)) echo "<br><font face='arial' color='green'>3. Billiard scores for and scores against updated successfully</font>";
echo '<br/>'.'<br/>';
echo "<font face='arial'>End of Update Billiards scores for and scores against";

} // end submit
else
{

echo "<form id='form1' name='form1' method='post' action='../Admin_update_tables/Update_Scores_Rank_STEP4.php?submit=1'>";

echo "<input type='submit' id='submit' name='submit'>";

echo "</form>";

}
// bulk update of for/against scores
/*
echo '<br/>'.'<br/>';
echo "<font face='arial'>Start Update Billiards scores for and scores against<br>";
// may need to diffirentiate between billiards grades if more than one grade.

$month = date('m');
if($month < '08')
{
  $season = 'S1';
}
else
{
  $season = 'S2';
}

// get max round (Home and Away only)
$query_round = "Select round FROM tbl_fixtures WHERE type = 'Billiards' AND year = YEAR( CURDATE( ) ) AND season = '" . $season . "' order by round DESC LIMIT 1";
$scores_round = mysql_query($query_round, $connvbsa) or die(mysql_error());
$row_scores_round = mysql_fetch_assoc($scores_round);
$last_round = $row_scores_round['round'];
$query_final = "Select finals_teams FROM Team_grade WHERE type = 'Billiards' AND fix_cal_year = YEAR( CURDATE( ) ) AND season = '" . $season . "' and current = 'Yes'";
$scores_final = mysql_query($query_final, $connvbsa) or die(mysql_error());
$row_scores_final = mysql_fetch_assoc($scores_final);
$finals_teams = $row_scores_final['finals_teams'];
$max_round = ($last_round - ($finals_teams/2));

// calculate scores for and against for billiard players
$query_scores_ladder = "Select distinct MemberID, team, playing_position, FirstName, LastName, round FROM tbl_scoresheet WHERE type='Billiards' AND year = YEAR( CURDATE( ) ) AND season = '" . $season . "' AND round <= " . $max_round . " AND (MemberID != 100) AND (MemberID != 1000) Order by MemberID, round";
$scores_ladder = mysql_query($query_scores_ladder, $connvbsa) or die(mysql_error());
while($row_scores_ladder = mysql_fetch_assoc($scores_ladder))
{
  $query_scores_for = "Select SUM(score_1) as total_scores_1 FROM tbl_scoresheet WHERE memberid = " . $row_scores_ladder['MemberID'] . " AND year = YEAR( CURDATE( ) ) AND season = '" . $season . "' order by round";
  $scores_for = mysql_query($query_scores_for, $connvbsa) or die(mysql_error());
  $row_scores_for = mysql_fetch_assoc($scores_for);
  $total_scores_for = $row_scores_for['total_scores_1'];
  
  // get players played against
   $query_teams_against = "Select distinct opposition FROM tbl_scoresheet WHERE memberid =" . $row_scores_ladder['MemberID'] . " AND type='Billiards' AND year = YEAR( CURDATE( ) ) AND season = '" . $season . "'";
  $teams_against = mysql_query($query_teams_against, $connvbsa) or die(mysql_error());
  $total_scores_against = 0;
  while($row_teams_against = mysql_fetch_assoc($teams_against))
  {
    $query_scores_against = "Select SUM(score_1) as total_scores_1, round FROM tbl_scoresheet WHERE team = '" . $row_teams_against['opposition'] . "' AND opposition = '" . $row_scores_ladder['team'] . "' AND playing_position = " . $row_scores_ladder['playing_position'] . " AND year = YEAR( CURDATE( ) ) AND season = '" . $season . "'";
    $scores_against = mysql_query($query_scores_against, $connvbsa) or die(mysql_error());
    $row_scores_against = mysql_fetch_assoc($scores_against);
    $total_scores_against = ($row_scores_against['total_scores_1'] + $total_scores_against);
  }
  $team_percentage = (($total_scores_for/$total_scores_against)*100);

  $querytoexecute = "Update scrs SET scores_for = " . $total_scores_for . ", scores_against = " . $total_scores_against . ", scores_percent = " . $team_percentage . " WHERE game_type='Billiards' AND current_year_scrs = YEAR( CURDATE( ) ) AND MemberID = " . $row_scores_ladder['MemberID'] . " AND scr_season = '" . $season . "' ";
  $result = mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error Billiard scores for and scores against not updated</font>");

  // finish scores for/against calculation
}
if (isset($result)) echo "<br><br><font face='arial' color='green'>1. Billiard scores for and scores against updated successfully</font>";
echo '<br/>'.'<br/>';
echo "<font face='arial'>End of Update Billiards scores for and scores against";
*/
?>
</center>
</body>
</html>
