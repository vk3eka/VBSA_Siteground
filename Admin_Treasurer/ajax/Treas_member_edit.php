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

$editFormAction = "ajax/Treas_member_edit.php";
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE members SET LastName=%s, FirstName=%s, HomeAddress=%s, HomeSuburb=%s, HomeState=%s, HomePostcode=%s, HomePhone=%s, WorkPhone=%s, MobilePhone=%s, ReceiveSMS=%s, Email=%s, ReceiveEmail=%s, Club=%s, BoardMemb=%s, LifeMember=%s, Deceased=%s, Junior=%s, Female=%s, Overseas=%s, Referee=%s, Ref_Class=%s, LastUpdated=%s, UpdateBy=%s, AffiliateMemb=%s, board_position=%s, Club2=%s, paid_memb=%s, paid_how=%s, paid_date=%s, dob_day=%s, dob_mnth=%s, dob_year=%s, prospective_ref=%s WHERE MemberID=%s",
                       GetSQLValueString($_POST['LastName'], "text"),
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['HomeAddress'], "text"),
                       GetSQLValueString($_POST['HomeSuburb'], "text"),
                       GetSQLValueString($_POST['HomeState'], "text"),
                       GetSQLValueString($_POST['HomePostcode'], "text"),
                       GetSQLValueString($_POST['HomePhone'], "text"),
                       GetSQLValueString($_POST['WorkPhone'], "text"),
					   GetSQLValueString($_POST['MobilePhone'], "text"),
                       GetSQLValueString(isset($_POST['ReceiveSMS']) ? "true" : "", "defined","'1'","'0'"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString(isset($_POST['ReceiveEmail']) ? "true" : "", "defined","'1'","'0'"),
                       GetSQLValueString($_POST['Club'], "text"),
					   GetSQLValueString(isset($_POST['BoardMemb']) ? "true" : "", "defined","'1'","'0'"),
                       GetSQLValueString(isset($_POST['LifeMember']) ? "true" : "", "defined","'1'","'0'"),
                       GetSQLValueString(isset($_POST['Deceased']) ? "true" : "", "defined","'1'","'0'"),
                       GetSQLValueString($_POST['Junior'], "int"),
                       GetSQLValueString(isset($_POST['Female']) ? "true" : "", "defined","'1'","'0'"),
                       GetSQLValueString($_POST['Overseas'], "text"),
                       GetSQLValueString(isset($_POST['Referee']) ? "true" : "", "defined","'1'","'0'"),
                       GetSQLValueString($_POST['Ref_Class'], "text"),
                       GetSQLValueString($_POST['LastUpdated'], "date"),
                       GetSQLValueString($_POST['UpdateBy'], "text"),
                       GetSQLValueString($_POST['AffiliateMemb'], "text"),
                       GetSQLValueString($_POST['board_position'], "text"),
                       GetSQLValueString($_POST['Club2'], "text"),
					   GetSQLValueString($_POST['paid_memb'], "int"),
					   GetSQLValueString($_POST['paid_how'], "text"),
					   GetSQLValueString($_POST['paid_date'], "date"),
                       GetSQLValueString($_POST['dob_day'], "int"),
                       GetSQLValueString($_POST['dob_mnth'], "int"),
                       GetSQLValueString($_POST['dob_year'], "date"),
					   GetSQLValueString(isset($_POST['prospective_ref']) ? "true" : "", "defined","'1'","'0'"),
                       GetSQLValueString($_POST['MemberID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());
  exit;
}


$colname_membedit = "-1";
if (isset($_GET['membedit'])) {
  $colname_membedit = $_GET['membedit'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_membedit = sprintf("SELECT members.MemberID, LastName, FirstName, HomeAddress, HomeSuburb, HomeState, HomePostcode, HomePhone, WorkPhone,  MobilePhone, ReceiveSMS,  Email, ReceiveEmail, Club, BoardMemb, LifeMember, Deceased,  Female, Overseas, Referee,  Ref_Class, members.LastUpdated, members.UpdateBy, AffiliateMemb, Club2, entered_on, board_position, dob_day, dob_mnth, dob_year, prospective_ref, members.paid_memb, members.paid_how, members.paid_date FROM members WHERE members.MemberID=%s", GetSQLValueString($colname_membedit, "int"));
$membedit = mysql_query($query_membedit, $connvbsa) or die(mysql_error());
$row_membedit = mysql_fetch_assoc($membedit);
$totalRows_membedit = mysql_num_rows($membedit);


$colname_getusername = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getusername = $_SESSION['MM_Username'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_getusername = sprintf("SELECT id, name, usertype FROM vbsaorga_users WHERE username = %s", GetSQLValueString($colname_getusername, "text"));
$getusername = mysql_query($query_getusername, $connvbsa) or die(mysql_error());
$row_getusername = mysql_fetch_assoc($getusername);
$totalRows_getusername = mysql_num_rows($getusername);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Clubs = "SELECT ClubNumber, ClubNameVBSA FROM clubs WHERE clubs.ClubNameVBSA is not null ORDER BY clubs.ClubNameVBSA";
$Clubs = mysql_query($query_Clubs, $connvbsa) or die(mysql_error());
$row_Clubs = mysql_fetch_assoc($Clubs);
$totalRows_Clubs = mysql_num_rows($Clubs);

$colname_fin = "-1";
if (isset($_GET['membedit'])) {
  $colname_fin = $_GET['membedit'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_fin = sprintf("SELECT MemberID, paid_memb, paid_how, paid_date FROM members WHERE paid_memb is not null AND YEAR(paid_date)=YEAR( CURDATE( ) ) AND MemberID=%s", GetSQLValueString($colname_fin, "int"));
$fin = mysql_query($query_fin, $connvbsa) or die(mysql_error());
$row_fin = mysql_fetch_assoc($fin);
$totalRows_fin = mysql_num_rows($fin);

mysql_select_db($database_connvbsa, $connvbsa);
$query_vbsa_clubs = "SELECT ClubNumber, ClubTitle, ClubNameVBSA FROM clubs WHERE VBSAteam=1 ORDER BY ClubNameVBSA";
$vbsa_clubs = mysql_query($query_vbsa_clubs, $connvbsa) or die(mysql_error());
$row_vbsa_clubs = mysql_fetch_assoc($vbsa_clubs);
$totalRows_vbsa_clubs = mysql_num_rows($vbsa_clubs);

$colname_played = "-1";
if (isset($_GET['membedit'])) {
  $colname_played = $_GET['membedit'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_played = sprintf("SELECT FirstName, LastName, lifemember, team_grade, count_played, game_type,  current_year_scrs FROM scrs  LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE scrs.MemberID=%s AND (current_year_scrs = YEAR(CURDATE( )) OR (fin_year_scrs = YEAR(CURDATE( )) AND MONTH(NOW( ))<7 )) ORDER BY current_year_scrs DESC", GetSQLValueString($colname_played, "int"));
$played = mysql_query($query_played, $connvbsa) or die(mysql_error());
$row_played = mysql_fetch_assoc($played);
$totalRows_played = mysql_num_rows($played);

$colname_Details = "-1";
if (isset($_GET['membedit'])) {
  $colname_Details = $_GET['membedit'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Details = sprintf("SELECT members.MemberID, LastName, FirstName, HomeAddress, HomeSuburb, HomeState, HomePostcode, HomePhone, WorkPhone,  MobilePhone, Email, ReceiveEmail, Club, BoardMemb, LifeMember, Deceased, Junior, Female, Referee,  Ref_Class, members.LastUpdated, members.UpdateBy, AffiliateMemb, Club2, entered_on, board_position, dob_day, dob_mnth, dob_year, Overseas, members.paid_memb, members.paid_how, members.paid_date FROM members WHERE members.MemberID = %s", GetSQLValueString($colname_Details, "int"));
$Details = mysql_query($query_Details, $connvbsa) or die(mysql_error());
$row_Details = mysql_fetch_assoc($Details);
$totalRows_Details = mysql_num_rows($Details);

$col1_playedfinyear = "-1";
if (isset($_GET['membedit'])) {
  $col1_playedfinyear = $_GET['membedit'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_playedfinyear = sprintf("SELECT MemberID , totplayed_prev + totplaybill_prev AS totplayfin FROM members WHERE MemberID=%s ", GetSQLValueString($col1_playedfinyear, "int"));
$playedfinyear = mysql_query($query_playedfinyear, $connvbsa) or die(mysql_error());
$row_playedfinyear = mysql_fetch_assoc($playedfinyear);
$totalRows_playedfinyear = mysql_num_rows($playedfinyear);

$col1_playedcalyear = "-1";
if (isset($_GET['membedit'])) {
  $col1_playedcalyear = $_GET['membedit'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_playedcalyear = sprintf("SELECT scrsID, MemberID , SUM(count_played) as totplayedcal FROM scrs WHERE MemberID=%s AND current_year_scrs = YEAR( CURDATE( ) )", GetSQLValueString($col1_playedcalyear, "int"));
$playedcalyear = mysql_query($query_playedcalyear, $connvbsa) or die(mysql_error());
$row_playedcalyear = mysql_fetch_assoc($playedcalyear);
$totalRows_playedcalyear = mysql_num_rows($playedcalyear);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Member Edit</title>
<script src="../../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>
</head>

<body>
<table width="1000" align="center" cellpadding="2">
  <tr>
    <td class="red_bold">EDIT PERSONAL DETAIL</td>
  </tr>
</table>
<div id="DBcontent">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="return doit()" >
              <table width="1000" align="center" cellpadding="4" cellspacing="4">
                      <tr>
                        <td align="right" nowrap="nowrap">Member ID: </td>
                        <td align="left"><?php echo $row_membedit['MemberID']; ?></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right">Last Name: </td>
                        <td><input type="text" name="LastName" value="<?php echo htmlentities($row_membedit['LastName'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                        <td align="right">Home Address</td>
                        <td ><input type="text" name="HomeAddress" value="<?php echo htmlentities($row_membedit['HomeAddress'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                        <td align="right">Work Phone</td>
                        <td ><input type="text" name="WorkPhone" value="<?php echo htmlentities($row_membedit['WorkPhone'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                      </tr>
                      <tr>
                        <td align="right">First Name: </td>
                        <td><input type="text" name="FirstName" value="<?php echo htmlentities($row_membedit['FirstName'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                        <td align="right">Suburb</td>
                        <td><input type="text" name="HomeSuburb" value="<?php echo htmlentities($row_membedit['HomeSuburb'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                        <td align="right">Mobile</td>
                        <td><input type="text" name="MobilePhone" value="<?php echo htmlentities($row_membedit['MobilePhone'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                      </tr>
                      <tr>
                        <td align="right">Home Phone: </td>
                        <td><input type="text" name="HomePhone" value="<?php echo htmlentities($row_membedit['HomePhone'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                        <td align="right">Postcode</td>
                        <td><input type="text" name="HomePostcode" value="<?php echo htmlentities($row_membedit['HomePostcode'], ENT_COMPAT, 'utf-8'); ?>" size="6" /></td>
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
                        <td align="right">Email: </td>
                        <td class="page"><input type="text" name="Email" value="<?php echo htmlentities($row_membedit['Email'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
                        <td align="right" nowrap="nowrap">Receive Email</td>
                        <td><input type="checkbox" name="ReceiveEmail" id="ReceiveEmail"  <?php if (!(strcmp(htmlentities($row_membedit['ReceiveEmail'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                        <td align="right">Club</td>
                        <td>
                        <select name="Club">
                        <option value=""  <?php if (!(strcmp("", htmlentities($row_membedit['Club'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>>No Entry</option>
                        <?php do {  ?>
                        <option value="<?php echo $row_vbsa_clubs['ClubNameVBSA']?>"<?php if (!(strcmp($row_vbsa_clubs['ClubNameVBSA'], htmlentities($row_membedit['Club'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_vbsa_clubs['ClubNameVBSA']?></option>
                        <?php
} while ($row_vbsa_clubs = mysql_fetch_assoc($vbsa_clubs));
  $rows = mysql_num_rows($vbsa_clubs);
  if($rows > 0) {
      mysql_data_seek($vbsa_clubs, 0);
	  $row_vbsa_clubs = mysql_fetch_assoc($vbsa_clubs);
  }
?>
                        </select>
                        </td>
                      </tr>
                      <tr>
                        <td align="right">Female</td>
                        <td><input type="checkbox" name="Female"  id="Female"  <?php if (!(strcmp(htmlentities($row_membedit['Female'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                        <td align="right" nowrap="nowrap">Receive SMS</td>
                        <td><input type="checkbox" name="ReceiveSMS" id="ReceiveSMS"  <?php if (!(strcmp(htmlentities($row_membedit['ReceiveSMS'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                        <td align="right" nowrap="nowrap">Club 2</td>
                        <td>
                        <select name="Club2">
                        <option value=""  <?php if (!(strcmp("", htmlentities($row_membedit['Club2'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>>No Entry</option>
                        <?php do {  ?>
                        <option value="<?php echo $row_vbsa_clubs['ClubNameVBSA']?>"<?php if (!(strcmp($row_vbsa_clubs['ClubNameVBSA'], htmlentities($row_membedit['Club2'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_vbsa_clubs['ClubNameVBSA']?></option>
                        <?php
} while ($row_vbsa_clubs = mysql_fetch_assoc($vbsa_clubs));
  $rows = mysql_num_rows($vbsa_clubs);
  if($rows > 0) {
      mysql_data_seek($vbsa_clubs, 0);
	  $row_vbsa_clubs = mysql_fetch_assoc($vbsa_clubs);
  }
?>
                      </select>
                      </td>
                      </tr>
                      <tr>
                        <td align="right">DOB</td>
                        <td colspan="5"><input type="text" name="dob_day" value="<?php echo htmlentities($row_membedit['dob_day'], ENT_COMPAT, 'utf-8'); ?>" size="2" /> <input type="text" name="dob_mnth" value="<?php echo htmlentities($row_membedit['dob_mnth'], ENT_COMPAT, 'utf-8'); ?>" size="2" /> 
                          <input type="date" name="dob_year" value="<?php echo htmlentities($row_membedit['dob_year'], ENT_COMPAT, 'utf-8'); ?>" size="4" /> 
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
                        <td align="left" nowrap="nowrap">Prospective Ref</td>
                        <td align="left"><input type="checkbox" name="prospective_ref" id="prospective_ref"  <?php if (!(strcmp(htmlentities($row_membedit['prospective_ref'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="right">&nbsp;</td>
                        <td colspan="4" align="left">&nbsp;</td>
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
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
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
                        <td>Life Member:</td>
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
                        <td><?php $newDate = date("d M Y", strtotime($row_membedit['LastUpdated'])); echo $newDate; ?></td>
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



  <table width="1000" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan="6"><span class="red_bold"><?php echo date("Y"); ?> Financial Detail - (Tournament Members only) . IF PAYMENT RECEIVED, DATE PAID MUST BE SELECTED, OR MEMBER WILL NOT SHOW</span></td>
  </tr>
  <tr>
    <td align="right">Paid $</td>
    <td align="left"><input type="text" name="paid_memb" value="<?php echo htmlentities($row_membedit['paid_memb'], ENT_COMPAT, 'utf-8'); ?>" size="10" /></td>
    <td align="right">How Paid:</td>
    <td><select name="paid_how">
            <option value="" >No Entry</option>
            <option value="PP" <?php if (!(strcmp("PP", htmlentities($row_membedit['paid_how'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>PP</option>
            <option value="Cash" <?php if (!(strcmp("Cash", htmlentities($row_membedit['paid_how'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Cash</option>
            <option value="BT" <?php if (!(strcmp("BT", htmlentities($row_membedit['paid_how'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>BT</option>
            <option value="CHQ" <?php if (!(strcmp("CHQ", htmlentities($row_membedit['paid_how'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>CHQ</option>
            <option value="Other" <?php if (!(strcmp("Other", htmlentities($row_membedit['paid_how'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Other</option>
          </select>
    </td>
    <td align="right">Date Paid:&nbsp;</td>
    <td align="left"><input type="text" name="paid_date" value="<?php echo htmlentities($row_membedit['paid_date'], ENT_COMPAT, 'utf-8'); ?>" size="15" /> <input type="button" value="Select Date Paid" onclick="displayDatePicker('paid_date', false, 'ymd', '.');" />      
      Please select Date (remove ALL fields if removing as paid)</td>
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
        <td colspan="6" class="red_bold">Playing History, Current year and S2 of Prev year if monthe &lt;7 - Editable by the Score Registrar only</td>
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
          				if ($row_Details['LifeMember'] ==1)
          				{
		  				echo "<img src='/../../Admin_Images/tick.JPG' width=\"16\">";
		  				}
		  				else
						echo '<span class="red_text"> No </span>';
          				?>
      </td>
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
						if (isset($row_Details['paid_memb']))
          				{
		  				echo "<img src='/../../Admin_Images/tick.JPG' width=\"16\">";
		  				}
		  				else
		  				echo '<span class="red_text"> No </span>';
          				?></td>
    </tr>
</table>

</div>


</body>
</html>
<?php
mysql_free_result($membedit);

mysql_free_result($fin);

mysql_free_result($vbsa_clubs);

mysql_free_result($played);

mysql_free_result($Details);

mysql_free_result($playedfinyear);

mysql_free_result($playedcalyear);

mysql_free_result($getusername);

mysql_free_result($Clubs);

?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>

<script type="text/javascript">

function doit(){     
	
	var tx = jQuery.noConflict();
        tx.ajax({
            url     : '<?PHP echo $editFormAction ?>',
            type    : tx('#form1').attr('method'),
            data    : tx('#form1').serialize(),
            success : function( data ) {
                        alert('Updated Succesfully!');
						location.reload();
                      },
            error   : function( xhr, err ) {
                        alert('Error please contact the webmaster');     
                      }
        }); 
        return false;
}

</script>