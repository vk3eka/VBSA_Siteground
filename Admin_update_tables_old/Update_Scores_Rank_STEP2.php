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
      <td colspan="4" align="center">STEP 2 of the calculation process - please complete all steps</td>
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


echo "<font face='arial' size='3'>STEP 2 completed go to ".'<span class="greenbg"><a href="Update_Scores_Rank_STEP3.php">'. "STEP 3". '</a></spsn>';
echo '<br/><br/>';
echo '<br/>';
echo "<font face='arial'>Updated Team Entries table - Calculated match points";

//tested ok calculates P01 - points for round 1 in Team_entries
$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
SET P01 = 	
(SELECT CASE
WHEN players = 4 AND comptype = 'Snooker' AND T01 > 6 THEN '4'
WHEN players = 4 AND comptype = 'Snooker' AND T01 = 6 THEN '2'
WHEN players = 6 AND comptype = 'Snooker' AND T01 > 9 THEN '4'
WHEN players = 6 AND comptype = 'Snooker' AND T01 = 9 THEN '2'
WHEN players = 4 AND comptype = 'Billiards' AND T01 > 4 THEN '4'
WHEN players = 4 AND comptype = 'Billiards' AND T01 = 4 THEN '2'
WHEN players = 6 AND comptype = 'Billiards' AND T01 > 6 THEN '4'
WHEN players = 6 AND comptype = 'Billiards' AND T01 = 6 THEN '2'
WHEN players = 4 AND comptype = '2x2' AND T01 > 6 THEN '4'
WHEN players = 4 AND comptype = '2x2' AND T01 = 6 THEN '2'
ELSE 0
END as P01)
WHERE team_cal_year = YEAR(CURDATE( )) 
";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("PO1 was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>1. Table was successfully updated - calculated P01 (Match 1) OK </font>";


//tested ok calculates P02 - points for round 2 in Team_entries
$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
SET P02 = 	
(SELECT CASE
WHEN players = 4 AND comptype = 'Snooker' AND T02 > 6 THEN '4'
WHEN players = 4 AND comptype = 'Snooker' AND T02 = 6 THEN '2'
WHEN players = 6 AND comptype = 'Snooker' AND T02 > 9 THEN '4'
WHEN players = 6 AND comptype = 'Snooker' AND T02 = 9 THEN '2'
WHEN players = 4 AND comptype = 'Billiards' AND T02 > 4 THEN '4'
WHEN players = 4 AND comptype = 'Billiards' AND T02 = 4 THEN '2'
WHEN players = 6 AND comptype = 'Billiards' AND T02 > 6 THEN '4'
WHEN players = 6 AND comptype = 'Billiards' AND T02 = 6 THEN '2'
WHEN players = 4 AND comptype = '2x2' AND T02 > 6 THEN '4'
WHEN players = 4 AND comptype = '2x2' AND T02 = 6 THEN '2'
ELSE 0
END as P02)
WHERE team_cal_year = YEAR(CURDATE( )) 
";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("PO2 was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>2. Table was successfully updated - calculated P02 (Match 2) OK </font>";

//tested ok calculates P03 - points for round 3 in Team_entries
$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
SET P03 = 	
(SELECT CASE
WHEN players = 4 AND comptype = 'Snooker' AND T03 > 6 THEN '4'
WHEN players = 4 AND comptype = 'Snooker' AND T03 = 6 THEN '2'
WHEN players = 6 AND comptype = 'Snooker' AND T03 > 9 THEN '4'
WHEN players = 6 AND comptype = 'Snooker' AND T03 = 9 THEN '2'
WHEN players = 4 AND comptype = 'Billiards' AND T03 > 4 THEN '4'
WHEN players = 4 AND comptype = 'Billiards' AND T03 = 4 THEN '2'
WHEN players = 6 AND comptype = 'Billiards' AND T03 > 6 THEN '4'
WHEN players = 6 AND comptype = 'Billiards' AND T03 = 6 THEN '2'
WHEN players = 4 AND comptype = '2x2' AND T03 > 6 THEN '4'
WHEN players = 4 AND comptype = '2x2' AND T03 = 6 THEN '2'
ELSE 0
END as P03)
WHERE team_cal_year = YEAR(CURDATE( ))
";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("PO3 was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>3. Table was successfully updated - calculated P03 (Match 3) OK </font>";


//tested ok calculates P04 - points for round 4 in Team_entries
$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
SET P04 = 	
(SELECT CASE
WHEN players = 4 AND comptype = 'Snooker' AND T04 > 6 THEN '4'
WHEN players = 4 AND comptype = 'Snooker' AND T04 = 6 THEN '2'
WHEN players = 6 AND comptype = 'Snooker' AND T04 > 9 THEN '4'
WHEN players = 6 AND comptype = 'Snooker' AND T04 = 9 THEN '2'
WHEN players = 4 AND comptype = 'Billiards' AND T04 > 4 THEN '4'
WHEN players = 4 AND comptype = 'Billiards' AND T04 = 4 THEN '2'
WHEN players = 6 AND comptype = 'Billiards' AND T04 > 6 THEN '4'
WHEN players = 6 AND comptype = 'Billiards' AND T04 = 6 THEN '2'
WHEN players = 4 AND comptype = '2x2' AND T04 > 6 THEN '4'
WHEN players = 4 AND comptype = '2x2' AND T04 = 6 THEN '2'
ELSE 0
END as P04)
WHERE team_cal_year = YEAR(CURDATE( ))
";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("PO4 was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>4. Table was successfully updated - calculated P04 (Match 4) OK </font>";


//tested ok calculates P05 - points for round 5 in Team_entries
$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
SET P05 = 	
(SELECT CASE
WHEN players = 4 AND comptype = 'Snooker' AND T05 > 6 THEN '4'
WHEN players = 4 AND comptype = 'Snooker' AND T05 = 6 THEN '2'
WHEN players = 6 AND comptype = 'Snooker' AND T05 > 9 THEN '4'
WHEN players = 6 AND comptype = 'Snooker' AND T05 = 9 THEN '2'
WHEN players = 4 AND comptype = 'Billiards' AND T05 > 4 THEN '4'
WHEN players = 4 AND comptype = 'Billiards' AND T05 = 4 THEN '2'
WHEN players = 6 AND comptype = 'Billiards' AND T05 > 6 THEN '4'
WHEN players = 6 AND comptype = 'Billiards' AND T05 = 6 THEN '2'
WHEN players = 4 AND comptype = '2x2' AND T05 > 6 THEN '4'
WHEN players = 4 AND comptype = '2x2' AND T05 = 6 THEN '2'
ELSE 0
END as P05)
WHERE team_cal_year = YEAR(CURDATE( ))
";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("PO5 was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>5. Table was successfully updated - calculated P05 (Match 5) OK </font>";


//tested ok calculates P06 - points for round 6 in Team_entries
$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
SET P06 = 	
(SELECT CASE
WHEN players = 4 AND comptype = 'Snooker' AND T06 > 6 THEN '4'
WHEN players = 4 AND comptype = 'Snooker' AND T06 = 6 THEN '2'
WHEN players = 6 AND comptype = 'Snooker' AND T06 > 9 THEN '4'
WHEN players = 6 AND comptype = 'Snooker' AND T06 = 9 THEN '2'
WHEN players = 4 AND comptype = 'Billiards' AND T06 > 4 THEN '4'
WHEN players = 4 AND comptype = 'Billiards' AND T06 = 4 THEN '2'
WHEN players = 6 AND comptype = 'Billiards' AND T06 > 6 THEN '4'
WHEN players = 6 AND comptype = 'Billiards' AND T06 = 6 THEN '2'
WHEN players = 4 AND comptype = '2x2' AND T06 > 6 THEN '4'
WHEN players = 4 AND comptype = '2x2' AND T06 = 6 THEN '2'
ELSE 0
END as P06)
WHERE team_cal_year = YEAR(CURDATE( ))
";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("PO6 was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>6. Table was successfully updated - calculated P06 (Match 6) OK </font>";


//tested ok calculates P07 - points for round 7 in Team_entries
$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
SET P07 = 	
(SELECT CASE
WHEN players = 4 AND comptype = 'Snooker' AND T07 > 6 THEN '4'
WHEN players = 4 AND comptype = 'Snooker' AND T07 = 6 THEN '2'
WHEN players = 6 AND comptype = 'Snooker' AND T07 > 9 THEN '4'
WHEN players = 6 AND comptype = 'Snooker' AND T07 = 9 THEN '2'
WHEN players = 4 AND comptype = 'Billiards' AND T07 > 4 THEN '4'
WHEN players = 4 AND comptype = 'Billiards' AND T07 = 4 THEN '2'
WHEN players = 6 AND comptype = 'Billiards' AND T07 > 6 THEN '4'
WHEN players = 6 AND comptype = 'Billiards' AND T07 = 6 THEN '2'
WHEN players = 4 AND comptype = '2x2' AND T07 > 6 THEN '4'
WHEN players = 4 AND comptype = '2x2' AND T07 = 6 THEN '2'
ELSE 0
END as P07)
WHERE team_cal_year = YEAR(CURDATE( ))
";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("PO7 was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>7. Table was successfully updated - calculated P07 (Match 7) OK </font>";


//tested ok calculates P08 - points for round 8 in Team_entries
$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
SET P08 = 	
(SELECT CASE
WHEN players = 4 AND comptype = 'Snooker' AND T08 > 6 THEN '4'
WHEN players = 4 AND comptype = 'Snooker' AND T08 = 6 THEN '2'
WHEN players = 6 AND comptype = 'Snooker' AND T08 > 9 THEN '4'
WHEN players = 6 AND comptype = 'Snooker' AND T08 = 9 THEN '2'
WHEN players = 4 AND comptype = 'Billiards' AND T08 > 4 THEN '4'
WHEN players = 4 AND comptype = 'Billiards' AND T08 = 4 THEN '2'
WHEN players = 6 AND comptype = 'Billiards' AND T08 > 6 THEN '4'
WHEN players = 6 AND comptype = 'Billiards' AND T08 = 6 THEN '2'
WHEN players = 4 AND comptype = '2x2' AND T08 > 6 THEN '4'
WHEN players = 4 AND comptype = '2x2' AND T08 = 6 THEN '2'
ELSE 0
END as P08)
WHERE team_cal_year = YEAR(CURDATE( ))
";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("PO8 was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>8. Table was successfully updated - calculated P08 (Match 8) OK </font>";


//tested ok calculates P09 - points for round 9 in Team_entries
$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
SET P09 = 	
(SELECT CASE
WHEN players = 4 AND comptype = 'Snooker' AND T09 > 6 THEN '4'
WHEN players = 4 AND comptype = 'Snooker' AND T09 = 6 THEN '2'
WHEN players = 6 AND comptype = 'Snooker' AND T09 > 9 THEN '4'
WHEN players = 6 AND comptype = 'Snooker' AND T09 = 9 THEN '2'
WHEN players = 4 AND comptype = 'Billiards' AND T09 > 4 THEN '4'
WHEN players = 4 AND comptype = 'Billiards' AND T09 = 4 THEN '2'
WHEN players = 6 AND comptype = 'Billiards' AND T09 > 6 THEN '4'
WHEN players = 6 AND comptype = 'Billiards' AND T09 = 6 THEN '2'
WHEN players = 4 AND comptype = '2x2' AND T09 > 6 THEN '4'
WHEN players = 4 AND comptype = '2x2' AND T09 = 6 THEN '2'
ELSE 0
END as P09)
WHERE team_cal_year = YEAR(CURDATE( ))
";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("PO9 was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>9. Table was successfully updated - calculated P09 (Match 9) OK </font>";


//tested ok calculates P10 - points for round 10 in Team_entries
$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
SET P10 = 	
(SELECT CASE
WHEN players = 4 AND comptype = 'Snooker' AND T10 > 6 THEN '4'
WHEN players = 4 AND comptype = 'Snooker' AND T10 = 6 THEN '2'
WHEN players = 6 AND comptype = 'Snooker' AND T10 > 9 THEN '4'
WHEN players = 6 AND comptype = 'Snooker' AND T10 = 9 THEN '2'
WHEN players = 4 AND comptype = 'Billiards' AND T10 > 4 THEN '4'
WHEN players = 4 AND comptype = 'Billiards' AND T10 = 4 THEN '2'
WHEN players = 6 AND comptype = 'Billiards' AND T10 > 6 THEN '4'
WHEN players = 6 AND comptype = 'Billiards' AND T10 = 6 THEN '2'
WHEN players = 4 AND comptype = '2x2' AND T10 > 6 THEN '4'
WHEN players = 4 AND comptype = '2x2' AND T10 = 6 THEN '2'
ELSE 0
END as P10)
WHERE team_cal_year = YEAR(CURDATE( ))
";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("P10 was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>10. Table was successfully updated - calculated P10 (Match 10) OK </font>";


//tested ok calculates P11 - points for round 11 in Team_entries
$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
SET P11 = 	
(SELECT CASE
WHEN players = 4 AND comptype = 'Snooker' AND T11 > 6 THEN '4'
WHEN players = 4 AND comptype = 'Snooker' AND T11 = 6 THEN '2'
WHEN players = 6 AND comptype = 'Snooker' AND T11 > 9 THEN '4'
WHEN players = 6 AND comptype = 'Snooker' AND T11 = 9 THEN '2'
WHEN players = 4 AND comptype = 'Billiards' AND T11 > 4 THEN '4'
WHEN players = 4 AND comptype = 'Billiards' AND T11 = 4 THEN '2'
WHEN players = 6 AND comptype = 'Billiards' AND T11 > 6 THEN '4'
WHEN players = 6 AND comptype = 'Billiards' AND T11 = 6 THEN '2'
WHEN players = 4 AND comptype = '2x2' AND T11 > 6 THEN '4'
WHEN players = 4 AND comptype = '2x2' AND T11 = 6 THEN '2'
ELSE 0
END as P11)
WHERE team_cal_year = YEAR(CURDATE( ))
";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("P11 was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>11. Table was successfully updated - calculated P11 (Match 11) OK </font>";


//tested ok calculates P12 - points for round 12 in Team_entries
$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
SET P12 = 	
(SELECT CASE
WHEN players = 4 AND comptype = 'Snooker' AND T12 > 6 THEN '4'
WHEN players = 4 AND comptype = 'Snooker' AND T12 = 6 THEN '2'
WHEN players = 6 AND comptype = 'Snooker' AND T12 > 9 THEN '4'
WHEN players = 6 AND comptype = 'Snooker' AND T12 = 9 THEN '2'
WHEN players = 4 AND comptype = 'Billiards' AND T12 > 4 THEN '4'
WHEN players = 4 AND comptype = 'Billiards' AND T12 = 4 THEN '2'
WHEN players = 6 AND comptype = 'Billiards' AND T12 > 6 THEN '4'
WHEN players = 6 AND comptype = 'Billiards' AND T12 = 6 THEN '2'
WHEN players = 4 AND comptype = '2x2' AND T12 > 6 THEN '4'
WHEN players = 4 AND comptype = '2x2' AND T12 = 6 THEN '2'
ELSE 0
END as P12)
WHERE team_cal_year = YEAR(CURDATE( ))
";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("P12 was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>12. Table was successfully updated - calculated P12 (Match 12) OK </font>";


//tested ok calculates P13 - points for round 13 in Team_entries
$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
SET P13 = 	
(SELECT CASE
WHEN players = 4 AND comptype = 'Snooker' AND T13 > 6 THEN '4'
WHEN players = 4 AND comptype = 'Snooker' AND T13 = 6 THEN '2'
WHEN players = 6 AND comptype = 'Snooker' AND T13 > 9 THEN '4'
WHEN players = 6 AND comptype = 'Snooker' AND T13 = 9 THEN '2'
WHEN players = 4 AND comptype = 'Billiards' AND T13 > 4 THEN '4'
WHEN players = 4 AND comptype = 'Billiards' AND T13 = 4 THEN '2'
WHEN players = 6 AND comptype = 'Billiards' AND T13 > 6 THEN '4'
WHEN players = 6 AND comptype = 'Billiards' AND T13 = 6 THEN '2'
WHEN players = 4 AND comptype = '2x2' AND T13 > 6 THEN '4'
WHEN players = 4 AND comptype = '2x2' AND T13 = 6 THEN '2'
ELSE 0
END as P13)
WHERE team_cal_year = YEAR(CURDATE( ))
";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("P13 was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>13. Table was successfully updated - calculated P13 (Match 13) OK </font>";


//tested ok calculates P14 - points for round 14 in Team_entries
$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
SET P14 = 	
(SELECT CASE
WHEN players = 4 AND comptype = 'Snooker' AND T14 > 6 THEN '4'
WHEN players = 4 AND comptype = 'Snooker' AND T14 = 6 THEN '2'
WHEN players = 6 AND comptype = 'Snooker' AND T14 > 9 THEN '4'
WHEN players = 6 AND comptype = 'Snooker' AND T14 = 9 THEN '2'
WHEN players = 4 AND comptype = 'Billiards' AND T14 > 4 THEN '4'
WHEN players = 4 AND comptype = 'Billiards' AND T14 = 4 THEN '2'
WHEN players = 6 AND comptype = 'Billiards' AND T14 > 6 THEN '4'
WHEN players = 6 AND comptype = 'Billiards' AND T14 = 6 THEN '2'
WHEN players = 4 AND comptype = '2x2' AND T14 > 6 THEN '4'
WHEN players = 4 AND comptype = '2x2' AND T14 = 6 THEN '2'
ELSE 0
END as P14)
WHERE team_cal_year = YEAR(CURDATE( ))
";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("P14 was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>14. Table was successfully updated - calculated P14 (Match 14) OK </font>";


//tested ok calculates P15 - points for round 15 in Team_entries
$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
SET P15 = 	
(SELECT CASE
WHEN players = 4 AND comptype = 'Snooker' AND T15 > 6 THEN '4'
WHEN players = 4 AND comptype = 'Snooker' AND T15 = 6 THEN '2'
WHEN players = 6 AND comptype = 'Snooker' AND T15 > 9 THEN '4'
WHEN players = 6 AND comptype = 'Snooker' AND T15 = 9 THEN '2'
WHEN players = 4 AND comptype = 'Billiards' AND T15 > 4 THEN '4'
WHEN players = 4 AND comptype = 'Billiards' AND T15 = 4 THEN '2'
WHEN players = 6 AND comptype = 'Billiards' AND T15 > 6 THEN '4'
WHEN players = 6 AND comptype = 'Billiards' AND T15 = 6 THEN '2'
WHEN players = 4 AND comptype = '2x2' AND T15 > 6 THEN '4'
WHEN players = 4 AND comptype = '2x2' AND T15 = 6 THEN '2'
ELSE 0
END as P15)
WHERE team_cal_year = YEAR(CURDATE( ))
";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("P15 was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>15. Table was successfully updated - calculated P15 (Match 15) OK </font>";


//tested ok calculates P16 - points for round 16 in Team_entries
$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
SET P16 = 	
(SELECT CASE
WHEN players = 4 AND comptype = 'Snooker' AND T16 > 6 THEN '4'
WHEN players = 4 AND comptype = 'Snooker' AND T16 = 6 THEN '2'
WHEN players = 6 AND comptype = 'Snooker' AND T16 > 9 THEN '4'
WHEN players = 6 AND comptype = 'Snooker' AND T16 = 9 THEN '2'
WHEN players = 4 AND comptype = 'Billiards' AND T16 > 4 THEN '4'
WHEN players = 4 AND comptype = 'Billiards' AND T16 = 4 THEN '2'
WHEN players = 6 AND comptype = 'Billiards' AND T16 > 6 THEN '4'
WHEN players = 6 AND comptype = 'Billiards' AND T16 = 6 THEN '2'
WHEN players = 4 AND comptype = '2x2' AND T16 > 6 THEN '4'
WHEN players = 4 AND comptype = '2x2' AND T16 = 6 THEN '2'
ELSE 0
END as P16)
WHERE team_cal_year = YEAR(CURDATE( ))
";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("P16 was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>16. Table was successfully updated - calculated P16 (Match 16) OK </font>";


//tested ok calculates P17 - points for round 17 in Team_entries
$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
SET P17 = 	
(SELECT CASE
WHEN players = 4 AND comptype = 'Snooker' AND T17 > 6 THEN '4'
WHEN players = 4 AND comptype = 'Snooker' AND T17 = 6 THEN '2'
WHEN players = 6 AND comptype = 'Snooker' AND T17 > 9 THEN '4'
WHEN players = 6 AND comptype = 'Snooker' AND T17 = 9 THEN '2'
WHEN players = 4 AND comptype = 'Billiards' AND T17 > 4 THEN '4'
WHEN players = 4 AND comptype = 'Billiards' AND T17 = 4 THEN '2'
WHEN players = 6 AND comptype = 'Billiards' AND T17 > 6 THEN '4'
WHEN players = 6 AND comptype = 'Billiards' AND T17 = 6 THEN '2'
WHEN players = 4 AND comptype = '2x2' AND T17 > 6 THEN '4'
WHEN players = 4 AND comptype = '2x2' AND T17 = 6 THEN '2'
ELSE 0
END as P17)
WHERE team_cal_year = YEAR(CURDATE( ))
";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("P17 was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>17. Table was successfully updated - calculated P17 (Match 17) OK </font>";


//tested ok calculates P18 - points for round 18 in Team_entries
$querytoexecute = "UPDATE vbsa3364_vbsa2.Team_entries 	
SET P18 = 	
(SELECT CASE
WHEN players = 4 AND comptype = 'Snooker' AND T18 > 6 THEN '4'
WHEN players = 4 AND comptype = 'Snooker' AND T18 = 6 THEN '2'
WHEN players = 6 AND comptype = 'Snooker' AND T18 > 9 THEN '4'
WHEN players = 6 AND comptype = 'Snooker' AND T18 = 9 THEN '2'
WHEN players = 4 AND comptype = 'Billiards' AND T18 > 4 THEN '4'
WHEN players = 4 AND comptype = 'Billiards' AND T18 = 4 THEN '2'
WHEN players = 6 AND comptype = 'Billiards' AND T18 > 6 THEN '4'
WHEN players = 6 AND comptype = 'Billiards' AND T18 = 6 THEN '2'
WHEN players = 4 AND comptype = '2x2' AND T18 > 6 THEN '4'
WHEN players = 4 AND comptype = '2x2' AND T18 = 6 THEN '2'
ELSE 0
END as P18)
WHERE team_cal_year = YEAR(CURDATE( ))
";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("P18 was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>18. Table was successfully updated - calculated P18 (Match 18) OK </font>";




mysql_close ($connvbsa);

}

else

{

echo "<form id='form1' name='form1' method='post' action='../Admin_update_tables/Update_Scores_Rank_STEP2.php?submit=1'>";

echo "<input type='submit' id='submit' name='submit'>";

echo "</form>";

}

?>
</center>
</body>
</html>
<?php
?>