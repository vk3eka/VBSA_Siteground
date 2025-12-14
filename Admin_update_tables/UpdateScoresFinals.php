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
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
  
  <table width="800" align="center">
    <tr>
      <td colspan="2" align="center" class="header_red">&nbsp;</td>
    </tr>
    <tr>
      <td width="784" colspan="2" align="center" class="header_red">When the &quot;Submit Query&quot; Button is clicked, the &quot;scrs&quot; &amp; &quot;Team_entries&quot; tables will be recalculated.</td>
    </tr>
    <tr>
      <td colspan="2" align="center">Please use this page every time you update scores</td>
    </tr>
    <tr>
      <td colspan="2" align="center" class="red_bld_txt">WARNING - PLEASE WAIT UNTIL PAGE HAS STOPPED RUNNING IN YOUR BROWSER</td>
    </tr>
    <tr>
      <td align="center" class="greenbg">&nbsp;</td>
      <td align="center" class="greenbg">&nbsp; </td>
    </tr>
    <tr>
      <td align="center" class="greenbg"><a href="../../admin_scores/AA_scores_index_grades.php?=S1">Return to Pennant Scores</a></td>
      <td align="center" class="greenbg"><a href="../admin_scores/AA_scores_index_grades.php?season=S2">Return to Billiards &amp; Willis Scores</a></td>
    </tr>
    <tr>
      <td colspan="2" align="center" class="red_bld_txt">&nbsp;</td>
    </tr>
    
  </table>
  
  
  
  <?php require_once('../Connections/connvbsa.php'); ?>
  
  
  <?php

if(isset($_POST["submit"]))

{

mysql_select_db($database_connvbsa, $connvbsa);

//***********************************************2016 finals modifications - Elim finals and Prelim finals commented out *******************

//tested ok calculates Team_entries EF1tot by (SELECT SUM(scrs.EF1)

//$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`Team_entries` 	
//SET `EF1tot` = 	(SELECT SUM(scrs.EF1)
//FROM scrs
//WHERE Team_entries.team_id = scrs.team_id)";
	
//$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

//if (isset($result)) echo "<br><br><font face='arial' color='green'>1 Table was successfully updated - calculated Team_entries EF1tot by (SELECT SUM(scrs.EF1)</font>";

//tested ok calculates Team_entries EF2tot by (SELECT SUM(scrs.EF2)

//$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`Team_entries` 	
//SET `EF2tot` = 	(SELECT SUM(scrs.EF2)
//FROM scrs
//WHERE Team_entries.team_id = scrs.team_id)";
	
//$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

//if (isset($result)) echo "<br><br><font face='arial' color='green'>2. Table was successfully updated - calculated Team_entries EF2tot by (SELECT SUM(scrs.EF2)</font>";


//tested ok calculates Team_entries SF1tot by (SELECT SUM(scrs.SF1)

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`Team_entries` 	
SET `SF1tot` = 	(SELECT SUM(scrs.SF1)
FROM scrs
WHERE Team_entries.team_id = scrs.team_id 
AND team_cal_year= YEAR(CURDATE( )))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>1. Table was successfully updated - calculated Team_entries SF1tot by (SELECT SUM(scrs.SF1)</font>";

//tested ok calculates Team_entries SF2tot by (SELECT SUM(scrs.SF2)

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`Team_entries` 	
SET `SF2tot` = 	(SELECT SUM(scrs.SF2)
FROM scrs
WHERE Team_entries.team_id = scrs.team_id
AND team_cal_year= YEAR(CURDATE( )))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>2. Table was successfully updated - calculated Team_entries SF2tot by (SELECT SUM(scrs.SF2)</font>";

//tested ok calculates Team_entries PFtot by (SELECT SUM(scrs.PF)

//$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`Team_entries` 	
//SET `PFtot` = 	(SELECT SUM(scrs.PF)
//FROM scrs
//WHERE Team_entries.team_id = scrs.team_id)";
	
//$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

//if (isset($result)) echo "<br><br><font face='arial' color='green'>5. Table was successfully updated - calculated Team_entries PFtot by (SELECT SUM(scrs.PF)</font>";

//tested ok calculates Team_entries PFtot by (SELECT SUM(scrs.GF)

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`Team_entries` 	
SET `GFtot` = 	(SELECT SUM(scrs.GF)
FROM scrs
WHERE Team_entries.team_id = scrs.team_id
AND team_cal_year= YEAR(CURDATE( )))";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>3. Table was successfully updated - calculated Team_entries GFtot by (SELECT SUM(scrs.GF)</font>";


mysql_close ($connvbsa);

}

else

{

echo "<form id='form1' name='form1' method='post' action='../Admin_update_tables/UpdateScoresFinals.php?submit=1'>";

echo "<input type='submit' id='submit' name='submit'>";

echo "</form>";

}

?>

</body>
</html>
<?php
?>