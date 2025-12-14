<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../page_error.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

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
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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

$colname_tournID = "-1";
if (isset($_GET['tournID'])) {
  $colname_tournID = $_GET['tournID'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_tournID = sprintf("SELECT * FROM tournaments WHERE tourn_id = %s", GetSQLValueString($colname_tournID, "int"));
$tournID = mysql_query($query_tournID, $connvbsa) or die(mysql_error());
$row_tournID = mysql_fetch_assoc($tournID);
$totalRows_tournID = mysql_num_rows($tournID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

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
      <td align="center" class="red_bld_txt"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
    <tr>
      <td align="center" class="red_bld_txt">&nbsp;</td>
    </tr>
    <tr>
      <td width="784" align="center" class="red_bold">When the &quot;Submit Query&quot; Button is clicked, the <?php echo $row_tournID['tourn_name']; ?> (tournament ID: <?php echo $row_tournID['tourn_id']; ?>) will be seeded.</td>
    </tr>
    <tr>
      <td align="center" class="red_boldt">Seeding will be based on the current "Victorian Tournament Rankings"</td>
    </tr>
    <tr>
      <td align="center" class="red_bold">Please make sure entries are closed before using this page</td>
    </tr>
    <tr>
      <td align="center" class=" red_bold">&nbsp;</td>
    </tr>
  </table>
  <center>
  <?php require_once('../Connections/connvbsa.php'); ?>
  <?php

if(isset($_POST["submit"]))

{

mysql_select_db($database_connvbsa, $connvbsa);

//The object of this page is to seed a tournament using the current Vic Tournament Rankings for snooker
//It creates a temporary table rankings_vic_temp from tourn_RP_co_curr the table is then altered so only entries that 
//have been entered into the "Tournament ID" are included, their seeding is set, then copied back to the tourn_RP_co_curr table
//On completion the temporary table is dropped



// create a temporary table rankings_vic_temp from tourn_RP_co_curr
$querytoexecute = "CREATE TABLE `vbsa3364_vbsa2`.`rankings_vic_temp` SELECT * FROM `vbsa3364_vbsa2`.`rankings_tourn_ordered`";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>Error temp table not created</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>rankings_vic_temp table was created and data copied successfully from the rankings_tourn_ordered table</font>";



// Add the tourn id column
$querytoexecute = "ALTER TABLE `rankings_vic_temp` ADD `tourn_id` INT( 10 ) NOT NULL DEFAULT '0';";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>Error tourn_id column not added</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>Successfully added tourn_id column </font>";


// Add the tourn id to the appropriate players


$colname_tournID = "-1";
if (isset($_GET['tournID'])) {
  $colname_tournID = $_GET['tournID'];
}

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`rankings_vic_temp` 	 
SET `tourn_id` = (SELECT tourn_entry.tournament_number FROM tourn_entry
WHERE tourn_entry.tourn_memb_id=rankings_vic_temp.member_tid AND tourn_entry.tournament_number='$colname_tournID')";


       
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>Error did not set the tourn_id for players as per the passed variable</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>Successfully set the tourn_id for players as per the passed variable </font>";



// Delete members that have a tourn_id that =0

$querytoexecute = "DELETE FROM `rankings_vic_temp` WHERE `tourn_id` =0 ";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>Error tourn_id = 0 not deleted</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>Successfully Deleted members that have a tourn_id =0 </font>";


// Drop the original Vic ranking column "t_rank"
$querytoexecute = "ALTER TABLE `rankings_vic_temp` DROP `t_rank`;";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>Error column t_rank not dropped</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>Successfully dropped the original Vic ranking column t_rank</font>";



// Add temp_rank column auto increment
$querytoexecute = "ALTER TABLE `rankings_vic_temp` ADD `temp_rank` INT NOT NULL AUTO_INCREMENT FIRST ,
ADD PRIMARY KEY ( `temp_rank` ) ";


$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>Error did not add temp_rank column auto increment</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> Successfully added temp_rank column auto increment</font>";


// Update tourn_entry table with temp rank from rankings_vic_temp table 
$querytoexecute = "UPDATE vbsa3364_vbsa2.tourn_entry  T1
INNER JOIN (
  SELECT member_tid, temp_rank AS tr
  FROM rankings_vic_temp
) T2 ON T1.tourn_memb_id = T2.member_tid
SET T1.seed = T2.tr
WHERE tournament_number=92 ";


$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>Error did not update tourn_entry table with temp rank from rankings_vic_temp table </font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> Successfully updated tourn_entry table with temp rank from rankings_vic_temp table </font>";


// Drop the rankings_vic_temp table now tournament ranking has been updated
$querytoexecute = "DROP TABLE rankings_vic_temp ";


$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='red'>Error did not drop the rankings_vic_temp table</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'> Successfully dropped the rankings_vic_temp table</font>";



mysql_close ($connvbsa);

}

else

{

echo "<form id='form1' name='form1' method='post' action='UpdateTournVicRank.php?tournID=$colname_tournID'>";

echo "<input type='submit' id='submit' name='submit'>";

echo "</form>";

}
?>
</center> 
</body>
</html>
<?php
mysql_free_result($tournID);
?>
