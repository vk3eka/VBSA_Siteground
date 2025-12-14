<?php require_once('../../Connections/connvbsa.php'); ?>
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

$MM_restrictGoTo = "../../page_error.php";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE members SET LastName=%s, FirstName=%s, HomeState=%s, HomePostcode=%s, HomePhone=%s, WorkPhone=%s, MobilePhone=%s, ReceiveSMS=%s, Email=%s, ReceiveEmail=%s, BoardMemb=%s, LifeMember=%s, Deceased=%s, Female=%s, Overseas=%s, Referee=%s, Ref_Class=%s, LastUpdated=%s, UpdateBy=%s, AffiliateMemb=%s, board_position=%s, dob_day=%s, dob_mnth=%s, dob_year=%s, prospective_ref=%s, `size`=%s WHERE MemberID=%s",
                       GetSQLValueString($_POST['LastName'], "text"),
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['HomeState'], "text"),
                       GetSQLValueString($_POST['HomePostcode'], "text"),
                       GetSQLValueString($_POST['HomePhone'], "text"),
                       GetSQLValueString($_POST['WorkPhone'], "text"),
                       GetSQLValueString($_POST['MobilePhone'], "text"),
                       GetSQLValueString(isset($_POST['ReceiveSMS']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString(isset($_POST['ReceiveEmail']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['BoardMemb']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['LifeMember']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['Deceased']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['Female']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['Overseas'], "text"),
                       GetSQLValueString(isset($_POST['Referee']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['Ref_Class'], "text"),
                       GetSQLValueString($_POST['LastUpdated'], "date"),
                       GetSQLValueString($_POST['UpdateBy'], "text"),
                       GetSQLValueString($_POST['AffiliateMemb'], "text"),
                       GetSQLValueString($_POST['board_position'], "text"),
                       GetSQLValueString($_POST['dob_day'], "int"),
                       GetSQLValueString($_POST['dob_mnth'], "int"),
                       GetSQLValueString($_POST['dob_year'], "int"),
                       GetSQLValueString(isset($_POST['prospective_ref']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['size'], "text"),
                       GetSQLValueString($_POST['MemberID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "club_detail.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


if (isset($_GET['memb_id'])) {
  $memb_id = $_GET['memb_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_membedit = "SELECT members.MemberID, LastName, FirstName, HomeAddress, HomeSuburb, HomeState, HomePostcode, HomePhone, WorkPhone,  MobilePhone, ReceiveSMS, Email, ReceiveEmail, Club, BoardMemb, LifeMember, Deceased,  Female, Overseas, Referee,  Ref_Class, members.LastUpdated, members.UpdateBy, AffiliateMemb, Club2, entered_on, board_position, dob_day, dob_mnth, dob_year, prospective_ref, members.paid_memb, members.paid_how, members.paid_date, size FROM members WHERE members.MemberID='$memb_id'";
$membedit = mysql_query($query_membedit, $connvbsa) or die(mysql_error());
$row_membedit = mysql_fetch_assoc($membedit);
$totalRows_membedit = mysql_num_rows($membedit);

mysql_select_db($database_connvbsa, $connvbsa);
$query_played = "SELECT FirstName, LastName, lifemember, team_grade, count_played, game_type,  current_year_scrs FROM scrs  LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE scrs.MemberID='$memb_id' AND (current_year_scrs = YEAR(CURDATE( )) OR fin_year_scrs = YEAR(CURDATE( ))) ORDER BY current_year_scrs DESC";
$played = mysql_query($query_played, $connvbsa) or die(mysql_error());
$row_played = mysql_fetch_assoc($played);
$totalRows_played = mysql_num_rows($played);

mysql_select_db($database_connvbsa, $connvbsa);
$query_life_paid = "SELECT MemberID,  LifeMember, paid_memb, paid_how, paid_date FROM members WHERE MemberID='$memb_id'";
$life_paid = mysql_query($query_life_paid, $connvbsa) or die(mysql_error());
$row_life_paid = mysql_fetch_assoc($life_paid);
$totalRows_life_paid = mysql_num_rows($life_paid);

mysql_select_db($database_connvbsa, $connvbsa);
$query_playedcalyear = "SELECT scrsID, MemberID , SUM(count_played) as totplayedcal FROM scrs WHERE MemberID='$memb_id' AND current_year_scrs = YEAR( CURDATE( ) )";
$playedcalyear = mysql_query($query_playedcalyear, $connvbsa) or die(mysql_error());
$row_playedcalyear = mysql_fetch_assoc($playedcalyear);
$totalRows_playedcalyear = mysql_num_rows($playedcalyear);

mysql_select_db($database_connvbsa, $connvbsa);
$query_playedfinyear = "SELECT MemberID , totplayed_prev + totplaybill_prev AS totplayfin FROM members WHERE MemberID='$memb_id' ";
$playedfinyear = mysql_query($query_playedfinyear, $connvbsa) or die(mysql_error());
$row_playedfinyear = mysql_fetch_assoc($playedfinyear);
$totalRows_playedfinyear = mysql_num_rows($playedfinyear);

mysql_select_db($database_connvbsa, $connvbsa);
$query_fin = "SELECT MemberID, paid_memb, paid_how, paid_date FROM members WHERE paid_memb is not null AND YEAR(paid_date)=YEAR( CURDATE( ) ) AND MemberID='$memb_id'";
$fin = mysql_query($query_fin, $connvbsa) or die(mysql_error());
$row_fin = mysql_fetch_assoc($fin);
$totalRows_fin = mysql_num_rows($fin);

mysql_select_db($database_connvbsa, $connvbsa);
$query_MonClub = "SELECT scrs.team_id, team_club, team_name, comptype FROM scrs, Team_entries WHERE Team_entries.team_id = scrs.team_id  AND scrs.MemberID ='$memb_id'  AND Team_entries.day_played='Mon' AND current_year_scrs = YEAR( CURDATE( ) ) ORDER BY scr_season DESC, count_played DESC LIMIT 1";
$MonClub = mysql_query($query_MonClub, $connvbsa) or die(mysql_error());
$row_MonClub = mysql_fetch_assoc($MonClub);
$totalRows_MonClub = mysql_num_rows($MonClub);

mysql_select_db($database_connvbsa, $connvbsa);
$query_WedClub = "SELECT scrs.team_id, team_club, team_name, comptype FROM scrs, Team_entries WHERE Team_entries.team_id = scrs.team_id  AND scrs.MemberID ='$memb_id' AND Team_entries.day_played='Wed' AND current_year_scrs = YEAR( CURDATE( ) ) ORDER BY scr_season DESC, count_played DESC LIMIT 1";
$WedClub = mysql_query($query_WedClub, $connvbsa) or die(mysql_error());
$row_WedClub = mysql_fetch_assoc($WedClub);
$totalRows_WedClub = mysql_num_rows($WedClub);

// Get Username
$colname_getusername = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getusername = $_SESSION['MM_Username'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_getusername = sprintf("SELECT id, name, usertype FROM vbsaorga_users WHERE username = %s", GetSQLValueString($colname_getusername, "text"));
$getusername = mysql_query($query_getusername, $connvbsa) or die(mysql_error());
$row_getusername = mysql_fetch_assoc($getusername);
$totalRows_getusername = mysql_num_rows($getusername);



?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Member Edit</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>

 <link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../../A_common/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />

</head>

<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>

<table width="1000" align="center" cellpadding="2">
  <tr>
    <td class="red_bold">EDIT PERSONAL DETAILS</td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<div id="DBcontent">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" >
              <table width="1000" align="center" cellpadding="4" cellspacing="4">
                      <tr>
                        <td align="right" nowrap="nowrap">Member ID: </td>
                        <td align="left"><?php echo $memb_id; ?></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right">First Name: </td>
                        <td><input type="text" name="FirstName" value="<?php echo htmlentities($row_membedit['FirstName'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                        <td align="right">&nbsp;</td>
                        <td >&nbsp;</td>
                        <td align="right">Work Phone</td>
                        <td ><input type="text" name="WorkPhone" value="<?php echo htmlentities($row_membedit['WorkPhone'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                      </tr>
                      <tr>
                        <td align="right">Last Name: </td>
                        <td><input type="text" name="LastName" value="<?php echo htmlentities($row_membedit['LastName'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right">Mobile</td>
                        <td><input type="text" name="MobilePhone" value="<?php echo htmlentities($row_membedit['MobilePhone'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                      </tr>
                      <tr>
                        <td align="right">Home Phone: </td>
                        <td><input type="text" name="HomePhone" value="<?php echo htmlentities($row_membedit['HomePhone'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right">State</td>
                        <td><select name="HomeState">
                          <option value="" >No Entry</option>
                          <option value="Vic" <?php if (!(strcmp("Vic", htmlentities($row_membedit['HomeState'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Vic</option>
                          <option value="ACT" <?php if (!(strcmp("ACT", htmlentities($row_membedit['HomeState'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>ACT</option>
                          <option value="NT" <?php if (!(strcmp("NT", htmlentities($row_membedit['HomeState'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>NT</option>
                          <option value="NSW" <?php if (!(strcmp("NSW", htmlentities($row_membedit['HomeState'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>NSW</option>
                          <option value="Qld" <?php if (!(strcmp("Qld", htmlentities($row_membedit['HomeState'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Qld</option>
                          <option value="SA" <?php if (!(strcmp("SA", htmlentities($row_membedit['HomeState'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>SA</option>
                          <option value="Tas" <?php if (!(strcmp("Tas", htmlentities($row_membedit['HomeState'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Tas</option>
                          <option value="WA" <?php if (!(strcmp("WA", htmlentities($row_membedit['HomeState'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>WA</option>
                        </select></td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                        <td class="page">&nbsp;</td>
                        <td align="right" nowrap="nowrap">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right">Postcode</td>
                        <td><input type="text" name="HomePostcode" value="<?php echo htmlentities($row_membedit['HomePostcode'], ENT_COMPAT, 'utf-8'); ?>" size="6" /></td>
                      </tr>
                      <tr>
                        <td align="right">Email: </td>
                        <td class="page"><input type="text" name="Email" value="<?php echo htmlentities($row_membedit['Email'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                        <td align="right" nowrap="nowrap">Receive Email</td>
                        <td><input type="checkbox" name="ReceiveEmail" id="ReceiveEmail"  <?php if (!(strcmp(htmlentities($row_membedit['ReceiveEmail'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                        <td align="right">Monday Club</td>
                        <td><?php if(isset($row_MonClub['team_id'])) echo $row_MonClub['team_club'].", " .$row_MonClub['team_name']. "(". $row_MonClub['comptype'].")"; else echo "Not Playing" ?></td>
                      </tr>
                      <tr>
                        <td align="right">Female</td>
                        <td><input type="checkbox" name="Female"  id="Female"  <?php if (!(strcmp(htmlentities($row_membedit['Female'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                        <td align="right" nowrap="nowrap">Receive SMS</td>
                        <td><input type="checkbox" name="ReceiveSMS" id="ReceiveSMS"  <?php if (!(strcmp(htmlentities($row_membedit['ReceiveSMS'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                        <td align="right" nowrap="nowrap">Wednesday Club</td>
                        <td><?php if(isset($row_WedClub['team_id'])) echo $row_WedClub['team_club'].", " .$row_WedClub['team_name']. "(". $row_WedClub['comptype'].")"; else echo "Not Playing" ?></td>
                      </tr>
                      <tr>
                        <td align="right">DOB</td>
                        <td colspan="5"><input type="text" name="dob_day" value="<?php echo htmlentities($row_membedit['dob_day'], ENT_COMPAT, 'utf-8'); ?>" size="2" /> <input type="text" name="dob_mnth" value="<?php echo htmlentities($row_membedit['dob_mnth'], ENT_COMPAT, 'utf-8'); ?>" size="2" /> 
                          <input type="text" name="dob_year" value="<?php echo htmlentities($row_membedit['dob_year'], ENT_COMPAT, 'utf-8'); ?>" size="4" /> 
                        Please insert as dd - mm - yyyy eg 01-01-2001. <span class="red_text">NOTE: For a player to appear as a junior they MUST have a date of birth listed</span></td>
                      </tr>
                      <tr>
                        <td align="right">Junior?:</td>
                        <td colspan="3">
						 <?php
						$age = (date("Y") -$row_membedit['dob_year']);
						if($age <=21)
						{
						echo "Yes";
						}
						elseif($age >21)
						{
						echo "No";
						}
						?>
                        
                         <?php
						$age = (date("Y") -$row_membedit['dob_year']);
						if($age==19 or $age==20 or $age==21)
						{
						echo "Minimum Competing age group: Under ";
						echo "21";
						echo "(Calculated by : current year ";
						echo date("Y");
						echo " - year of birth ";
						echo $row_membedit['dob_year'];
						}
						elseif($age==16 or $age==17 or $age==18)
						{
						echo "Minimum Competing age group: Under ";
						echo "18";
						echo "(Calculated by : current year ";
						echo date("Y");
						echo " - year of birth ";
						echo $row_membedit['dob_year'];
						}
						elseif($age==13 or $age==14 or $age==15)
						{
						echo "Minimum Competing age group: Under ";
						echo "15";
						echo "(Calculated by : current year ";
						echo date("Y");
						echo " - year of birth ";
						echo $row_membedit['dob_year'];
						}
						elseif($age==12)
						{
						echo "Minimum Competing age group: Under ";
						echo "12";
						echo "(Calculated by : current year ";
						echo date("Y");
						echo " - year of birth ";
						echo $row_membedit['dob_year'];
						}
						else
						{
						echo"";
						}
						?>
                        
                        </td>
                        <td align="left" nowrap="nowrap">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="right">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left">Prospective Ref</td>
                        <td align="left"><input type="checkbox" name="prospective_ref" id="prospective_ref"  <?php if (!(strcmp(htmlentities($row_membedit['prospective_ref'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                      </tr>
                      <tr>
                        <td align="right">Affiliate Memb</td>
                        <td><select name="AffiliateMemb">
                          <option value="" >No Entry</option>
                          <option value="ChurchBill" <?php if (!(strcmp("ChurchBill", htmlentities($row_membedit['AffiliateMemb'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Church Bill</option>
                          <option value="DVSA" <?php if (!(strcmp("DVSA", htmlentities($row_membedit['AffiliateMemb'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>DVSA</option>
                          <option value="MSBA" <?php if (!(strcmp("MSBA", htmlentities($row_membedit['AffiliateMemb'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>MSBA</option>
                          <option value="O55" <?php if (!(strcmp("O55", htmlentities($row_membedit['AffiliateMemb'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>O55</option>
                          <option value="RSL" <?php if (!(strcmp("RSL", htmlentities($row_membedit['AffiliateMemb'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>RSL</option>
                          <option value="Southern" <?php if (!(strcmp("Southern", htmlentities($row_membedit['AffiliateMemb'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Southern</option>
                          <option value="SSA" <?php if (!(strcmp("SSA", htmlentities($row_membedit['AffiliateMemb'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>SSA</option>
                        </select></td>
                        <td align="right">Size:</td>
                        <td><select name="size">
                          <option value="" >No Entry</option>
                          <option value="XS" <?php if (!(strcmp("XS", htmlentities($row_membedit['size'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>XS</option>
                          <option value="S" <?php if (!(strcmp("S", htmlentities($row_membedit['size'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>S</option>
                          <option value="L" <?php if (!(strcmp("M", htmlentities($row_membedit['size'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>M</option>
                          <option value="L" <?php if (!(strcmp("L", htmlentities($row_membedit['size'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>L</option>
                          <option value="XL" <?php if (!(strcmp("XL", htmlentities($row_membedit['size'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>XL</option>
                          <option value="2XL" <?php if (!(strcmp("2XL", htmlentities($row_membedit['size'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>2XL</option>
                          <option value="3XL" <?php if (!(strcmp("3XL", htmlentities($row_membedit['size'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>3XL</option>
                          <option value="4XL" <?php if (!(strcmp("4XL", htmlentities($row_membedit['size'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>4XL</option>
                          <option value="5XL" <?php if (!(strcmp("5XL", htmlentities($row_membedit['size'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>5XL</option>
                          <option value="6XL" <?php if (!(strcmp("6XL", htmlentities($row_membedit['size'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>6XL</option>
                          <option value="7XL" <?php if (!(strcmp("7XL", htmlentities($row_membedit['size'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>7XL</option>              
                        </select></td>
                        <td align="right" nowrap="nowrap">Referee</td>
                        <td><input type="checkbox" name="Referee" id="Referee"  <?php if (!(strcmp(htmlentities($row_membedit['Referee'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                      </tr>
                      <tr>
                        <td align="right">Board Member</td>
                        <td><input type="checkbox" name="BoardMemb" value="1" id="BoardMemb"  <?php if (!(strcmp(htmlentities($row_membedit['BoardMemb'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                        <td align="right">Overseas?</td>
                        <td><input type="text" name="Overseas" value="<?php echo htmlentities($row_membedit['Overseas'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                        <td align="right">Ref Class</td>
                        <td><input type="text" name="Ref_Class" value="<?php echo htmlentities($row_membedit['Ref_Class'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                      </tr>
                      <tr>
                        <td align="right" nowrap="nowrap">Board Position: </td>
                        <td colspan="3"><input type="text" name="board_position" value="<?php echo htmlentities($row_membedit['board_position'], ENT_COMPAT, 'utf-8'); ?>" size="80" /></td>
                        <td align="right">Life Member:</td>
                        <td><input type="checkbox" name="LifeMember" value="1" id="LifeMember"  <?php if (!(strcmp(htmlentities($row_membedit['LifeMember'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                      </tr>
                      <tr>
                        <td align="right">Entered on</td>
                        <td>&nbsp;
    						<?php
							$year = date("Y", strtotime($row_membedit['entered_on']));
							if($year >1900) {
							$newDate = date("d M Y", strtotime($row_membedit['entered_on'])); 
							echo $newDate; }
							else
							echo "Not Known";
							?>
                        </td>
                        <td align="right">Last Updated</td>
                        <td>
							<?php $newDate = date("d M Y", strtotime($row_membedit['LastUpdated']));
								if($row_membedit['LastUpdated'] != NULL)
							 	echo $newDate; else echo "No update" ?>
                        </td>
                        <td align="right">Updated By</td>
                        <td><?php echo $row_membedit['UpdateBy']; ?></td>
                      </tr>
                      <tr>
                        <td align="right">Deceased</td>
                        <td align="left"><input type="checkbox" name="Deceased" id="Deceased"  <?php if (!(strcmp(htmlentities($row_membedit['Deceased'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                        <td align="right">&nbsp;</td>
                        <td align="left"><input type="submit" value="Update Member Details" /></td>
                        <td align="right">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                      </tr>
    </table>
        <input type="hidden" name="MM_update" value="form1" />
      	<input type="hidden" name="MemberID" value="<?php echo $row_membedit['MemberID']; ?>" />
        <input type="hidden" name="UpdateBy" value="<?php echo $row_getusername['name']; ?>" />
        <input type="hidden" name="LastUpdated" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d"); ?> " />
      	<input type="hidden" name="MemberID" value="<?php echo $row_membedit['MemberID']; ?>" />
      	<input type="hidden" name="MM_update" value="form1" />
  </form>
  </div>
  
  <div id="DBcontent">
    <table width="1000" align="center" cellpadding="2" cellspacing="2">
      <tr>
        <td colspan="6" class="red_bold">Playing History, Current year and S2 of Prev year if month &lt;7 - Editable by the Score Registrar only</td>
      </tr>
      <tr>
        <td align="center">Grade</td>
        <td align="center">Matches played</td>
        <td align="center">In year</td>
        <td align="center">&nbsp;</td>
        <td width="150" align="center">&nbsp;</td>
        <td width="150" align="center">&nbsp;</td>
      </tr>
      <?php do { ?>
      <tr>
        <td align="center"><?php echo $row_played['team_grade']; ?></td>
        <td align="center"><?php echo $row_played['count_played']; ?></td>
        <td align="center"><?php echo $row_played['current_year_scrs']; ?></td>
        <td align="center"><?php echo $row_played['game_type']; ?></td>
        <td width="150" align="center">&nbsp;</td>
        <td width="150" align="center">&nbsp;</td>
      </tr>
      <?php } while ($row_played = mysql_fetch_assoc($played)); ?>
    </table>
  <table width="1000" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="3"><span class="red_bold"> Member?</span></td>
    </tr>
  <tr>
    <td colspan="3" align="left">What constitutes as membership? (one of the following conditions must be true)</td>
    </tr>
  <tr>
    <td align="left">1. Life member of the VBSA.
   	  <?php
          				if ($row_life_paid['LifeMember'] ==1)
          				{
		  				echo "<img src='/../../Admin_Images/tick.JPG' width=\"16\">";
		  				}
		  				else
						echo '<span class="red_text"> No </span>';
          				?></td>
    <td align="left">2. Having played &gt;3 matches in season 2 of the previous year &amp; current month &lt;7.
		<?php
						
						if ($row_playedfinyear['totplayfin'] >=4)
          				{
		  				echo "<img src='/../../Admin_Images/tick.JPG' width=\"16\">";
		  				}
						elseif( date ('m') >6)
						echo '<span class="red_text"> After June 30th </span>';
		  				else
						echo '<span class="red_text"> No </span>';
          				?></td>
    </tr>
  <tr>
    <td align="left">3 Having played 4 or more matches in the current year.
      <?php
          				if ($row_playedcalyear['totplayedcal'] >=4)
          				{
		  				echo "<img src='/../../Admin_Images/tick.JPG' width=\"16\">";
		  				}
		  				else
						echo '<span class="red_text"> No </span>';
          				?></td>
    <td align="left">4. Having paid $20 in the current year. 
      <?php
          				$curYear = date('Y');
						echo $curYear; 
						if (isset($row_life_paid['paid_memb']))
          				{
		  				echo "<img src='/../../Admin_Images/tick.JPG' width=\"16\">";
		  				}
		  				else
		  				echo '<span class="red_text"> No </span>';
          				?></td>
    </tr>
</table>

</div>
  
  <div id="DBcontent">
  <table width="1000" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan="6"><span class="red_bold"><?php echo date("Y"); ?> Financial Detail - (Tournament Members only) editable by the treasurer only - if you see a mistake or have a query email <a href="mailto:treasurer@vbsa.org.au">George Hoy</a></span></td>
  </tr>
  <tr>
    <td align="right">Paid $</td>
    <td align="left"><?php echo $row_life_paid['paid_memb']; ?></td>
    <td align="right">How Paid:</td>
    <td align="left"><?php echo $row_life_paid['paid_how']; ?></td>
    <td align="right">Date Paid:&nbsp;</td>
    <td align="left">&nbsp;
    <?php
	$year = date("Y", strtotime($row_life_paid['paid_date']));
	if($year >2000) {
	$newDate = date("d M Y", strtotime($row_life_paid['paid_date'])); 
	echo $newDate; }
	else
	echo "";
	?>
	</td>
  </tr>
  </table>

  </div>

</body>
</html>
<?php
mysql_free_result($membedit);

mysql_free_result($fin);

mysql_free_result($life_paid);

mysql_free_result($playedcalyear);

mysql_free_result($playedfinyear);

mysql_free_result($played);

mysql_free_result($getusername);

mysql_free_result($MonClub);

mysql_free_result($WedClub);
?>
