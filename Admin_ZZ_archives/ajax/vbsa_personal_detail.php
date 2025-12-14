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

$colname_Details = "-1";
if (isset($_GET['MembDetail'])) {
  $colname_Details = $_GET['MembDetail'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Details = sprintf("SELECT members.MemberID, LastName, FirstName, HomeAddress, HomeSuburb, HomeState, HomePostcode, HomePhone, WorkPhone,  MobilePhone, Email, ReceiveEmail, Club, BoardMemb, LifeMember, Deceased, Junior, Female, Referee,  Ref_Class, members.LastUpdated, members.UpdateBy, AffiliateMemb, Club2, entered_on, board_position, dob_day, dob_mnth, dob_year, Overseas, members.paid_memb, members.paid_how, members.paid_date FROM members WHERE members.MemberID = %s", GetSQLValueString($colname_Details, "int"));
$Details = mysql_query($query_Details, $connvbsa) or die(mysql_error());
$row_Details = mysql_fetch_assoc($Details);
$totalRows_Details = mysql_num_rows($Details);

$colname_played = "-1";
if (isset($_GET['MembDetail'])) {
  $colname_played = $_GET['MembDetail'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_played = sprintf("SELECT members.MemberID, scrs.team_grade, scrs.MemberID,  scrs.played_S1, scrs.played_S2, scrs.played_bill_S1, scrs.played_bill_S2 FROM members, scrs WHERE members.MemberID = scrs.MemberID AND scrs.current_year_scrs = YEAR( CURDATE( ) )  AND members.MemberID=%s", GetSQLValueString($colname_played, "int"));
$played = mysql_query($query_played, $connvbsa) or die(mysql_error());
$row_played = mysql_fetch_assoc($played);
$totalRows_played = mysql_num_rows($played);

$colname_fin = "-1";
if (isset($_GET['MembDetail'])) {
  $colname_fin = $_GET['MembDetail'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_fin = sprintf("SELECT members.MemberID, members.LifeMember, members_fin.Fin_ID, members_fin.Paid, members_fin.DatePaid, members_fin.HowMembPaid FROM members, members_fin WHERE members.MemberID=members_fin.Fin_ID AND members.MemberID=%s AND memb_cal_year = YEAR( CURDATE( ) )", GetSQLValueString($colname_fin, "int"));
$fin = mysql_query($query_fin, $connvbsa) or die(mysql_error());
$row_fin = mysql_fetch_assoc($fin);
$totalRows_fin = mysql_num_rows($fin);

$col1_playedfinyear = "-1";
if (isset($_GET['MembDetail'])) {
  $col1_playedfinyear = $_GET['MembDetail'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_playedfinyear = sprintf("SELECT scrsID, MemberID , SUM(played_S2 + played_bill_S2) as totplayedfin FROM scrs WHERE MemberID=%s AND fin_year_scrs = YEAR( CURDATE( ) )-1 AND MONTH( CURDATE( ) ) <=06", GetSQLValueString($col1_playedfinyear, "int"));
$playedfinyear = mysql_query($query_playedfinyear, $connvbsa) or die(mysql_error());
$row_playedfinyear = mysql_fetch_assoc($playedfinyear);
$totalRows_playedfinyear = mysql_num_rows($playedfinyear);

$col1_playedcalyear = "-1";
if (isset($_GET['MembDetail'])) {
  $col1_playedcalyear = $_GET['MembDetail'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_playedcalyear = sprintf("SELECT scrsID, MemberID , SUM(played_S1 + played_bill_S1 + played_S2 + played_bill_S2) as totplayedcal FROM scrs WHERE MemberID=%s AND current_year_scrs = YEAR( CURDATE( ) )", GetSQLValueString($col1_playedcalyear, "int"));
$playedcalyear = mysql_query($query_playedcalyear, $connvbsa) or die(mysql_error());
$row_playedcalyear = mysql_fetch_assoc($playedcalyear);
$totalRows_playedcalyear = mysql_num_rows($playedcalyear);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>
<link href="../facebox/facebox.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="1000" align="center" cellpadding="2">
  <tr>
    <td width="742" class="red_bold">PERSONAL DETAIL</td>
    <td width="262">&nbsp;</td>
  </tr>
</table>
<div id="DBcontent">  
              <table width="1000" align="center" cellpadding="4" cellspacing="4">
                      <tr>
                        <td align="right" nowrap="nowrap">Member ID: </td>
                        <td align="left"><?php echo $row_Details['MemberID']; ?></td>
                        <td colspan="4">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right">Last Name: </td>
                        <td><?php echo $row_Details['LastName']; ?></td>
                        <td align="right">Home Address</td>
                        <td><?php echo $row_Details['HomeAddress']; ?></td>
                        <td align="right">Work Phone</td>
                        <td><?php echo $row_Details['WorkPhone']; ?></td>
                      </tr>
                      <tr>
                        <td align="right">First Name: </td>
                        <td><?php echo $row_Details['FirstName']; ?></td>
                        <td align="right">Suburb</td>
                        <td><?php echo $row_Details['HomeSuburb']; ?></td>
                        <td align="right">Mobile</td>
                        <td class="page"><a href="tel:<?php echo $row_Details['MobilePhone']; ?>"><?php echo $row_Details['MobilePhone']; ?></a></td>
                      </tr>
                      <tr>
                        <td align="right">Home Phone: </td>
                        <td><?php echo $row_Details['HomePhone']; ?></td>
                        <td align="right">Postcode</td>
                        <td><?php echo $row_Details['HomePostcode']; ?></td>
                        <td align="right">State</td>
                        <td><?php echo $row_Details['HomeState']; ?></td>
                      </tr>
                      <tr>
                        <td align="right">Email: </td>
                        <td class="page"><a href="mailto:<?php echo $row_Details['Email']; ?>"><?php echo $row_Details['Email']; ?></a></td>
                        <td align="right">Receive Email</td>
                        <td><?php echo $row_Details['ReceiveEmail']; ?></td>
                        <td align="right">Overseas:</td>
                        <td><?php echo $row_Details['Overseas']; ?></td>
                      </tr>
                      <tr>
                        <td align="right">Female</td>
                        <td><?php if ($row_Details['Female']==1) echo "Yes"; else echo "No"; ?></td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right" nowrap="nowrap">Club</td>
                        <td><?php echo $row_Details['Club']; ?></td>
                      </tr>
                      <tr>
                        <td align="right">Deceased</td>
                        <td><?php if ($row_Details['Deceased']==1) echo "Yes"; else echo "No"; ?></td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right" nowrap="nowrap">Club 2</td>
                        <td><?php echo $row_Details['Club2']; ?></td>
                      </tr>
                      <tr>
                        <td align="right">DOB</td>
                        <td><?php echo $row_Details['dob_day']; ?> <?php echo $row_Details['dob_mnth']; ?> <?php echo $row_Details['dob_year']; ?></td>
                        <td colspan="4" align="left">Junior? 
                          <?php
						$age = (date("Y") -$row_Details['dob_year']);
						if($age <=21)
						{
						echo "Yes";
						}
						elseif($age >21)
						{
						echo "No";
						}
						?>
                        <span class="red_text"> &nbsp;&nbsp;&nbsp;&nbsp;NOTE: For a player to appear as a junior they MUST have a date of birth listed</span></td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                        <td colspan="5" align="left">
                          <em>
                          <?php
						$age = (date("Y") -$row_Details['dob_year']);
						if($age==19 or $age==20 or $age==21)
						{
						echo "Minimum Competing age group: Under ";
						echo "21";
						echo "(Calculated by : current year ";
						echo date("Y");
						echo " - year of birth ";
						echo $row_Details['dob_year'];
						}
						elseif($age==16 or $age==17 or $age==18)
						{
						echo "Minimum Competing age group: Under ";
						echo "18";
						echo "(Calculated by : current year ";
						echo date("Y");
						echo " - year of birth ";
						echo $row_Details['dob_year'];
						}
						elseif($age==13 or $age==14 or $age==15)
						{
						echo "Minimum Competing age group: Under ";
						echo "15";
						echo "(Calculated by : current year ";
						echo date("Y");
						echo " - year of birth ";
						echo $row_Details['dob_year'];
						}
						elseif($age==12)
						{
						echo "Minimum Competing age group: Under ";
						echo "12";
						echo "(Calculated by : current year ";
						echo date("Y");
						echo " - year of birth ";
						echo $row_Details['dob_year'];
						}
						else
						{
						echo"";
						}
						?>
                          </em></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="right">Tournament member? (Treasurer input only)</td>
                        <td colspan="4" align="left">Paid $: <?php echo $row_Details['paid_memb']; ?> &nbsp;&nbsp;&nbsp; How Paid: <?php echo $row_Details['paid_how']; ?> &nbsp;&nbsp;&nbsp; Date Paid: <?php echo $row_Details['paid_date']; ?></td>
                      </tr>
                      <tr>
                        <td align="right">Affiliate Memb</td>
                        <td><?php echo $row_Details['AffiliateMemb']; ?></td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right" nowrap="nowrap">Referee</td>
                        <td><?php if($row_Details['Referee']==1) echo "Yes"; else echo "No"; ?></td>
                      </tr>
                      <tr>
                        <td align="right">Board Member</td>
                        <td><?php if($row_Details['BoardMemb']==1) echo "Yes"; else echo "No"; ?></td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right">Ref Class</td>
                        <td><?php echo $row_Details['Ref_Class']; ?></td>
                      </tr>
                      <tr>
                        <td align="right" nowrap="nowrap">Board Position: </td>
                        <td colspan="3"><?php echo $row_Details['board_position']; ?></td>
                        <td align="right">Life Member ?</td>
                        <td><?php if($row_Details['LifeMember']==1) echo "Yes"; else echo "No"; ?></td>
                      </tr>
                      <tr>
                        <td align="right">Entered on</td>
                        <td><?php echo $row_Details['entered_on']; ?></td>
                        <td align="right">Last Updated</td>
                        <td><?php echo $row_Details['LastUpdated']; ?></td>
                        <td align="right">Updated By</td>
                        <td><?php echo $row_Details['UpdateBy']; ?></td>
                      </tr>
    </table>
</div>
  <div id="DBcontent">
  <table width="1000" align="center" cellpadding="5" cellspacing="5">
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
    <td colspan="-1" align="left">2. Having played 4 or more matches in season 2 of the previous year.
      <?php
          				if ($row_playedfinyear['totplayedfin'] >=4)
          				{
		  				echo "<img src='/../../Admin_Images/tick.JPG' width=\"16\">";
		  				}
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
    <td colspan="-1" align="left">4. Having paid $20 in the current year. 
      <?php
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
<div id="DBcontent">
  <table width="1000" align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td colspan="6" class="red_bold"> Playing Detail (Current Year only) - Editable by the Score Registrar only</td>
    </tr>
    <tr>
      <td align="center">ID</td>
      <td align="center">Team Grade</td>
      <td align="center">Played Snooker S1</td>
      <td align="center">Played Snooker S2</td>
      <td align="center">Played Billiards S1</td>
      <td align="center">Played Billiards S2</td>
    </tr>
    <?php do { ?>
      <tr>
        <td align="center"><?php echo $row_played['MemberID']; ?></td>
        <td align="center"><?php echo $row_played['team_grade']; ?></td>
        <td align="center"><?php echo $row_played['played_S1']; ?></td>
        <td align="center"><?php echo $row_played['played_S2']; ?></td>
        <td align="center"><?php echo $row_played['played_bill_S1']; ?></td>
        <td align="center"><?php echo $row_played['played_bill_S2']; ?></td>
      </tr>
      <?php } while ($row_played = mysql_fetch_assoc($played)); ?>
  </table>
</div>
</body>
</html>
<?php
mysql_free_result($Details);

mysql_free_result($played);

mysql_free_result($fin);

mysql_free_result($playedfinyear);

mysql_free_result($playedcalyear);
?>