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


echo "<font face='arial' size='3'>All calculations completed ".'<span class="greenbg"><a href="Update_Scores_Rank_STEP5.php">'. "Thank you". '</a></spsn>';
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
CURRENT_TIMESTAMP
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
CURRENT_TIMESTAMP
FROM `vbsa3364_vbsa2`.`scrs`
LEFT JOIN `vbsa3364_vbsa2`.`members` ON members.MemberID=scrs.MemberID
WHERE current_year_scrs >YEAR(CURDATE( ))-3
AND(scrs.MemberID !=1 AND scrs.MemberID !=10 AND scrs.MemberID !=100 AND scrs.MemberID !=1000)
AND `game_type`='Billiards' AND `total_rp`>0
GROUP BY scrs.MemberID";

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
INNER JOIN (SELECT ROUND(MAX(total_RP*35/100),0) AS yr2, MemberID
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
INNER JOIN (SELECT ROUND(MAX(total_RP*35/100),0) AS yr2, MemberID
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
INNER JOIN (SELECT ROUND(MAX(total_RP*65/100),0) AS yr1, MemberID
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
INNER JOIN (SELECT ROUND(MAX(total_RP*65/100),0) AS yr1, MemberID
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
INNER JOIN (SELECT MAX(total_RP) AS curr, MemberID
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
INNER JOIN (SELECT MAX(total_RP) AS curr, MemberID
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

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN 
(SELECT member_ID_brks, SUM(bill_rp*.35) AS BRP2
FROM breaks 
WHERE YEAR(recvd)=YEAR(CURDATE( ))-2
GROUP BY member_ID_brks
) T2 ON T1.memb_id= T2.member_ID_brks
SET T1.brks_2 = T2.BRP2";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>10. Error ranking points for billiard breaks 2 years prior not awarded </font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>10. Ranking points for billiard breaks 2 years prior awarded successfully</font>";


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

if (isset($result)) echo "<br><br><font face='arial' color='green'>11. Ranking points for billiard breaks 1 year prior awarded successfully</font>";


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

if (isset($result)) echo "<br><br><font face='arial' color='green'>12. Ranking points for billiard breaks current year awarded successfully</font>";


//completed weekly ranking points 
//begin tournament ranking points

//set 35% of tournament ranking points for 2 years prior to current year

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN (SELECT tourn_memb_id, ROUND(SUM(rank_pts*35/100)) AS RP2
FROM tourn_entry 
WHERE entry_cal_year=YEAR(CURDATE( ))-2
GROUP BY tourn_memb_id
HAVING SUM(rank_pts)>0
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_2 = T2.RP2";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>13. Error 35% of -2 year tournament ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>13. 35% of -2 year tournament ranking points updated successfully</font>";


//set 65% of tournament ranking points for 1 year prior to current year

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN (SELECT tourn_memb_id, ROUND(SUM(rank_pts*65/100)) AS RP1
FROM tourn_entry 
WHERE entry_cal_year=YEAR(CURDATE( ))-1
GROUP BY tourn_memb_id
HAVING SUM(rank_pts)>0
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_1 = T2.RP1";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>14. Error 65% of -1 year tournament ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>14. 65% of -1 year tournament ranking points updated successfully</font>";


//set current year tournament ranking points

$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_a_billiards_master` T1
INNER JOIN (SELECT tourn_memb_id, SUM(rank_pts) AS RPcurr
FROM tourn_entry 
WHERE entry_cal_year=YEAR(CURDATE( ))
GROUP BY tourn_memb_id
HAVING SUM(rank_pts)>0
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_curr = T2.RPcurr";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>15. Error current year tournament ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>15. Current year tournament ranking points updated successfully</font>";


//total for Victorian Billiards rankings ranking points

$querytoexecute = "UPDATE `rank_a_billiards_master` 
SET total_rp=scr_2yr_S2+scr_2yr_S1+scr_1yr_S2+scr_1yr_S1+scr_curr_S2+scr_curr_S1+
brks_2+brks_1+brks_curr+tourn_2+tourn_1+tourn_curr
";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>16. Error current year tournament ranking points not totalled</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>16. Current year tournament ranking points totalled successfully</font>";

//tournament ranking points completed

//END BILLIARDS master table



echo '<br/>'.'<br/>';
echo "<font face='arial'>Empty - Open snooker weekly rankings, Open snooker tournament rankings, Womens snooker, junior snooker and Billiards tables and copy data from Master tables";

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

//rank_Billiards Billiards Rankings
$querytoexecute = "TRUNCATE TABLE `rank_Billiards`";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error - rank_Billiards table - Billiards Rankings not truncated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 3. Truncated rank_Billiards table - Billiards Rankings successfully</font>";

//rank_Billiards Womens Snooker Rankings
$querytoexecute = "TRUNCATE TABLE `rank_S_womens` ";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>4. Error - rank_S_womens table -  Womens Snooke Rankings
 not truncated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 4. Truncated rank_S_womens table - Womens Snooker Rankings successfully</font>";

//rank_Billiards Junior Snooker Rankings
$querytoexecute = "TRUNCATE TABLE `rank_S_junior`";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>5. Error - rank_S_junior table - Junior Snooker Rankings not truncated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 5. Truncated rank_S_junior table - Junior Snooker successfully</font>";


//Insert data into ranking tables
//rank_S_open_weekly Snooker Weekly Rankings
$querytoexecute = "INSERT INTO `rank_S_open_weekly` 
SELECT 
0,
rank_aa_snooker_master.memb_id AS memb_id,
rank_aa_snooker_master.weekly_total AS total_weekly_rp,
CURRENT_TIMESTAMP
FROM rank_aa_snooker_master
GROUP BY rank_aa_snooker_master.memb_id
ORDER BY weekly_total DESC";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>6. Error - rank_S_open_weekly table - Snooker Weekly Rankings data not inserted</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 6. Data inserted to rank_S_open_weekly table - Snooker Weekly Rankings successfully</font>";

//rank_S_open_tourn Snooker Tournament Rankings
$querytoexecute = "INSERT INTO `rank_S_open_tourn`
SELECT 
0,
rank_aa_snooker_master.memb_id AS memb_id,
rank_aa_snooker_master.tourn_total AS total_tourn_rp,
CURRENT_TIMESTAMP
FROM rank_aa_snooker_master
WHERE tourn_total>0
GROUP BY rank_aa_snooker_master.memb_id
ORDER BY tourn_total DESC";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>7. Error - rank_S_open_tourn table - Snooker Tournament Rankings data not inserted</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 7. Data inserted to rank_S_open_tourn table - Snooker Tournament Rankings successfully</font>";


//rank_Billiards Billiard Rankings
$querytoexecute = "INSERT INTO `rank_Billiards`
SELECT 
0,
rank_a_billiards_master.memb_id AS memb_id,
tourn_2+tourn_1+tourn_curr AS total_rp,
CURRENT_TIMESTAMP
FROM rank_a_billiards_master
GROUP BY rank_a_billiards_master.memb_id
ORDER BY total_rp DESC";
/*$querytoexecute = "INSERT INTO `rank_Billiards`
SELECT 
0,
rank_a_billiards_master.memb_id AS memb_id,
total_rp+tourn_2+tourn_1+tourn_curr AS total_rp,
CURRENT_TIMESTAMP
FROM rank_a_billiards_master
GROUP BY rank_a_billiards_master.memb_id
ORDER BY total_rp DESC";
*/
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>8. Error - rank_Billiards table - Billiard Rankings data not inserted</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 8. Data inserted to rank_Billiards table - Billiard Rankings successfully</font>";


//rank_S_womens Womens Snooker Rankings
$querytoexecute = "INSERT INTO `rank_S_womens`
SELECT 
0,
rank_aa_snooker_master.memb_id AS memb_id,
rank_aa_snooker_master.tourn_total AS total_rp,
CURRENT_TIMESTAMP
FROM rank_aa_snooker_master
WHERE m_f='1' AND tourn_total>0 
GROUP BY rank_aa_snooker_master.memb_id
ORDER BY tourn_total DESC";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>9. Error - rank_S_womens table - Womens Snooker Rankings data not inserted</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 9. Data inserted to rank_S_womens table - Womens Snooker Rankings successfully</font>";


//rank_S_junior Junior Snooker Rankings
$querytoexecute = "INSERT INTO `rank_S_junior`
SELECT 
0,
rank_aa_snooker_master.memb_id AS memb_id,
rank_aa_snooker_master.tourn_total AS total_rp,
CURRENT_TIMESTAMP
FROM rank_aa_snooker_master
WHERE jun !='na' AND tourn_total>0 
GROUP BY rank_aa_snooker_master.memb_id
ORDER BY tourn_total DESC";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>10. Error - rank_S_junior table - Junior Snooker Rankings data not inserted</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> 10. Data inserted to rank_S_junior table - Junior Snooker Rankings successfully</font>";

//END RANKING TABLES


mysql_close ($connvbsa);

}

else

{

echo "<form id='form1' name='form1' method='post' action='../Admin_update_tables/Update_Scores_Rank_STEP4.php?submit=1'>";

echo "<input type='submit' id='submit' name='submit'>";

echo "</form>";

}

?>
</center>
</body>
</html>
<?php
?>