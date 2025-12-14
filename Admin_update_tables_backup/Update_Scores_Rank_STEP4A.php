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
      <td colspan="4" align="center">STEP 4A of the calculation process</td>
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


echo "<font face='arial' size='3'>Test calculations completed ".'<span class="greenbg"><a href="Update_Scores_Rank_STEP4C.php">'. "STEP 4C". '</a></spsn>';
echo '<br/><br/>';


echo '<br/>'.'<br/>';
echo "<font face='arial'>Victorian Snooker Rankings";

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
WHERE entry_cal_year=YEAR(CURDATE( ))-2 and tournaments.tourn_type = 'Snooker' and tournaments.ranking_type = 'Vic Rank' 
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
WHERE entry_cal_year=YEAR(CURDATE( ))-1 and tournaments.tourn_type = 'Snooker' and tournaments.ranking_type = 'Vic Rank' 
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
WHERE entry_cal_year=YEAR(CURDATE( )) and tournaments.tourn_type = 'Snooker' and tournaments.ranking_type = 'Vic Rank' 
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
//$querytoexecute = "UPDATE `rank_aa_snooker_master` SET tourn_total=weekly_percent+tourn_2+tourn_1+tourn_curr
//WHERE (weekly_percent+tourn_2+tourn_1+tourn_curr)>0";

//$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>15. Error did not calculate total tournament ranking points</font>");

//if (isset($result)) echo "<br><br><font face='arial' color='green'>15. Calculate total tournament ranking points successfully</font>";



/*
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
WHERE entry_cal_year=YEAR(CURDATE( )) and tournaments.tourn_type = 'Snooker' and tournaments.ranking_type = 'Womens Rank' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_curr_w = T2.RPcurr";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>1. Error current year tournament womens ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>1. Current year tournament womens ranking points updated successfully</font>";

// Add Womens Year 1 Data
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, Round(SUM(rank_pts)*.65) AS RP_Yr1
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( ))-1 and tournaments.tourn_type = 'Snooker' and tournaments.ranking_type = 'Womens Rank' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_1_w = T2.RP_Yr1";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>2. Error current year tournament womens ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>2. Year 1 tournament womens ranking points updated successfully</font>";

// Add Womens Year 2 Data
$querytoexecute = "Update `vbsa3364_vbsa2`.`rank_aa_snooker_master` T1
Inner Join (SELECT tourn_memb_id, tourn_id, Round(SUM(rank_pts)*.35) AS RP_Yr2
FROM tourn_entry 
LEFT JOIN tournaments ON tournaments.tourn_id = tourn_entry.tournament_number
WHERE entry_cal_year=YEAR(CURDATE( ))-2 and tournaments.tourn_type = 'Snooker' and tournaments.ranking_type = 'Womens Rank' 
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
WHERE entry_cal_year=YEAR(CURDATE( )) and tournaments.tourn_type = 'Snooker' and tournaments.ranking_type = 'Junior Rank' 
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
WHERE entry_cal_year=YEAR(CURDATE( ))-1 and tournaments.tourn_type = 'Snooker' and tournaments.ranking_type = 'Junior Rank' 
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
WHERE entry_cal_year=YEAR(CURDATE( ))-2 and tournaments.tourn_type = 'Snooker' and tournaments.ranking_type = 'Junior Rank' 
GROUP BY tourn_memb_id 
) T2 ON T1.memb_id= T2.tourn_memb_id
SET T1.tourn_2_j = T2.RP_Yr2";

$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>3. Error current year tournament junior ranking points not updated</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>3. Year 2 tournament junior ranking points updated successfully</font>";
*/
  //echo '<br/>'.'<br/>';
  //echo "<font face='arial'>End of Test Junior Snooker Rankings";

  mysql_close ($connvbsa);

}
else
{

  echo "<form id='form1' name='form1' method='post' action='../Admin_update_tables/Update_Scores_Rank_STEP4A.php?submit=1'>";
  echo "<input type='submit' id='submit' name='submit'>";
  echo "</form>";

}

?>
</center>
</body>
</html>
<?php
?>