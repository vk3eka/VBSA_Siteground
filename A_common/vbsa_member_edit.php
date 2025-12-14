<?php 
require_once('../Connections/connvbsa.php'); 
require_once('../MailerLite/mailerlite_functions.php'); 

error_reporting(0);

include('../security_header.php');

if (!isset($_SESSION)) {
  session_start();
}

$existing_array = [];

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

  if(($_POST['ReceiveEmail'] == 1) OR ($_POST['Email'] != '') OR ($_POST['curr_memb'] == 0))
  {
    //Current Members
    //((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1 OR Gender != 'Male' OR hon_memb=1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0
    if((($_POST['paid_memb'] == 20 
      AND $_POST['paid_date'] == 2024) 
      OR $_POST['LifeMember'] == 1 
      OR $_POST['Junior'] != 'na' 
      OR $_POST['ccc_player'] == 1 
      OR $_POST['referee'] == 1 
      OR $_POST['coach'] == 1 
      OR $_POST['Gender'] != 'Male' 
      OR $_POST['hon_memb'] == 1
      AND $_POST['affiliate_player'] != 1))

    //if(($_POST['curr_memb'] == 0) && ($_POST['affiliate_player'] != 1))
    {
      $current = 'Current Members';
    }
    //Affiliates
    //affiliate_player = 1"
    if($_POST['affiliate_player'] == 1)
      /*AND $_POST['paid_memb'] != 20 
      AND $_POST['LifeMember'] != 1 
      AND $_POST['ccc_player'] != 1 
      AND $_POST['referee'] != 1 
      AND $_POST['coach'] != 1 
      AND $_POST['hon_memb'] != 1)*/
    {
      $affiliate = 'Affiliates';
    }


    //Members/Affiliates
    // ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR affiliate_player=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1 OR Gender != 'Male' OR affiliate_player = 1 OR hon_memb = 1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0"
    if((($_POST['paid_memb'] == 20 
      AND $_POST['paid_date'] == 2024) 
      OR $_POST['LifeMember'] == 1 
      OR $_POST['Junior'] != 'na' 
      OR $_POST['ccc_player'] == 1 
      OR $_POST['referee'] == 1 
      OR $_POST['coach'] == 1 
      OR $_POST['Gender'] != 'Male' 
      OR $_POST['hon_memb'] == 1
      OR $_POST['affiliate_player'] == 1))
    {
      $member_affiliates = 'Members/Affiliates';
    }

    if($_POST['LifeMember'] == 1)
    {
      $life = 'Life';
    }

    if(($_POST['paid_memb'] == 20) AND (date('Y', strtotime($_POST['paid_date'])) == 2024))
    {
      $paid = 'Paid';
    }

    if($_POST['ccc_player'] == 1)
    {
      $ccc = 'CCC';
    }

    if($_POST['Gender'] != 'Male')
    {
      $female = 'Female/NB/NS';
    }

    if((date('Y') - 18) == $_POST['dob_year'])
    {
      $junior = 'Junior';
    }
    
    if($_POST['Referee'] == true)
    {
      $referee = 'Referees';
    }

    if($_POST['coach'] == true)
    {
      $coach = 'Coaches';
    }

    if($_POST['curr_memb'] == true)
    {
      $deactive = 'Deactivated';
    }

    if($_POST['community'] == true)
    {
      $community = 'Community';
    }

    if($_POST['hon_memb'] == true)
    {
      $hon = 'Honorary';
    }
  }

  //echo($_POST['paid_date'] . "<br>");
  //echo(date('Y', strtotime($_POST['paid_date'])) . "<br>");
/*
  $new_array = [
    $life . "," .
    $current . "," .
    $affiliate . "," .
    $member_affiliates . "," .
    $ccc . "," .
    $paid . "," .
    $junior . "," .
    $referee . "," .
    $coach . "," .
    $female . "," .
    $deactive . "," .
    $community . "," .
    $hon 
  ];
*/
  mysql_select_db($database_connvbsa, $connvbsa);

  // get existing email to check if changed.
  $query_email = "Select Email FROM members WHERE MemberID = " . $_POST['MemberID'];
  $memb_email = mysql_query($query_email, $connvbsa) or die(mysql_error());
  $row_email = mysql_fetch_assoc($memb_email);
  $existing_email = $row_email['Email'];

  $dob_year = $_POST['dob_year'];
  if(($dob_year >= (date("Y")-18)) AND ($dob_year <= (date("Y")-16)))
  {
    $junior = 'U18';
  }
  else if(($dob_year >= (date("Y")-15)) AND ($dob_year <= (date("Y")-13)))
  {
    $junior = 'U15';
  }
  else if(($dob_year >= (date("Y")-12)) AND ($dob_year <= (date("Y"))))
  {
    $junior = 'U12';
  }
  else
  {
    $junior = 'na';
  }

  $updateSQL = sprintf("Update members SET LastName=%s, FirstName=%s, HomeState=%s, HomePostcode=%s, HomePhone=%s, MobilePhone=%s, ReceiveSMS=%s, curr_memb=%s, Email=%s, memb_occupation=%s, hon_memb=%s, ReceiveEmail=%s, LifeMember=%s, ccc_player=%s, affiliate_player=%s, Affiliate_1=%s, Affiliate_2=%s, Affiliate_3=%s, Affiliate_4=%s, contact_only=%s, Deceased=%s, Gender=%s, Overseas=%s, Referee=%s, Ref_Class=%s, LastUpdated=%s, UpdateBy=%s, dob_day=%s, dob_mnth=%s, dob_year=%s, prospective_ref=%s, paid_memb=%s, paid_how=%s, paid_date=%s, community=%s, Junior=%s WHERE MemberID=%s",
     GetSQLValueString($_POST['LastName'], "text"),
     GetSQLValueString($_POST['FirstName'], "text"),
     GetSQLValueString($_POST['HomeState'], "text"),
     GetSQLValueString($_POST['HomePostcode'], "text"),
     GetSQLValueString($_POST['HomePhone'], "text"),
     GetSQLValueString($_POST['MobilePhone'], "text"),
     GetSQLValueString(isset($_POST['ReceiveSMS']) ? "true" : "", "defined","1","0"),
     GetSQLValueString(isset($_POST['curr_memb']) ? "true" : "", "defined","1","0"),
     GetSQLValueString($_POST['Email'], "text"),
     GetSQLValueString($_POST['Occupation'], "text"),
     GetSQLValueString(isset($_POST['hon_memb']) ? "true" : "", "defined","1","0"),
     GetSQLValueString(isset($_POST['ReceiveEmail']) ? "true" : "", "defined","1","0"),
     GetSQLValueString(isset($_POST['LifeMember']) ? "true" : "", "defined","1","0"),
     GetSQLValueString(isset($_POST['ccc_player']) ? "true" : "", "defined","1","0"),
     GetSQLValueString(isset($_POST['affiliate_player']) ? "true" : "", "defined","1","0"),
     GetSQLValueString($_POST['affiliate_1'], "text"),
     GetSQLValueString($_POST['affiliate_2'], "text"),
     GetSQLValueString($_POST['affiliate_3'], "text"),
     GetSQLValueString($_POST['affiliate_4'], "text"),
     GetSQLValueString(isset($_POST['contact_only']) ? "true" : "", "defined","1","0"),
     GetSQLValueString(isset($_POST['Deceased']) ? "true" : "", "defined","1","0"),
     GetSQLValueString($_POST['Gender'], "text"),
     GetSQLValueString($_POST['Overseas'], "text"),
     GetSQLValueString(isset($_POST['Referee']) ? "true" : "", "defined","1","0"),
     GetSQLValueString($_POST['Ref_Class'], "text"),
     GetSQLValueString($_POST['LastUpdated'], "date"),
     GetSQLValueString($_POST['UpdateBy'], "text"),
     GetSQLValueString($_POST['dob_day'], "int"),
     GetSQLValueString($_POST['dob_mnth'], "int"),
     GetSQLValueString($_POST['dob_year'], "int"),
     GetSQLValueString(isset($_POST['prospective_ref']) ? "true" : "", "defined","1","0"),
     GetSQLValueString($_POST['paid_memb'], "int"),
     GetSQLValueString($_POST['paid_how'], "text"),
     GetSQLValueString($_POST['paid_date'], "date"),
     GetSQLValueString(isset($_POST['community']) ? "true" : "", "defined","1","0"),
     GetSQLValueString($junior, "text"),
     GetSQLValueString($_POST['MemberID'], "int"));
  //echo("Select " . $updateSQL . "<br>");
  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  //echo($_POST['Email'] . "<br>");
  //echo($existing_email . "<br>");

  if($_POST['Email'] != $existing_email)
  {
    //UpdateEmail($_POST['Email'], $existing_email);

    // update SEWS authoriser page and admin login page if required.
    // check if a SEWS login.
    $query_sews_email = "Select Email FROM tbl_authorise WHERE PlayerNo = " . $_POST['MemberID'];
    $memb_sews_email = mysql_query($query_sews_email, $connvbsa) or die(mysql_error());
    $total_rows_sews = mysql_num_rows($memb_sews_email);
    if($total_rows_sews > 0)
    {
      $update_sews = sprintf("Update tbl_authorise SET Email=%s WHERE PlayerNo=%s",
      GetSQLValueString($_POST['Email'], "text"),
      GetSQLValueString($_POST['MemberID'], "int"));
      $result_sews = mysql_query($update_sews, $connvbsa) or die(mysql_error());
    }

    // check if an Admin login.
    $query_admin_email = "Select email FROM vbsaorga_users WHERE board_member_id = " . $_POST['MemberID'];
    $memb_admin_email = mysql_query($query_admin_email, $connvbsa) or die(mysql_error());
    $total_rows_admin = mysql_num_rows($memb_admin_email);
    if($total_rows_admin > 0)
    {
      $update_admin = sprintf("Update vbsaorga_users SET email=%s, username=%s WHERE board_member_id=%s",
      GetSQLValueString($_POST['Email'], "text"),
      GetSQLValueString($_POST['Email'], "text"),
      GetSQLValueString($_POST['MemberID'], "int"));
      $result_admin = mysql_query($update_admin, $connvbsa) or die(mysql_error());
    }
  }

  //ListMemberGroups($_POST['Email'], $new_array, $existing_email);
  
  $updateGoTo = $_SESSION['page'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  
  // add coaching data
  $updateSQL2 = sprintf("Update coaches_vbsa SET coach_id=%s, class=%s, comment=%s, active_coach=%s WHERE memb_id=%s",
   GetSQLValueString($_POST['coach_id'], "int"),
   GetSQLValueString($_POST['class'], "text"),
   GetSQLValueString($_POST['comment'], "text"),
   GetSQLValueString(isset($_POST['active_coach']) ? "true" : "", "defined","1","0"),
   GetSQLValueString($_POST['MemberID'], "int"));
  //echo("Coach Update " . $updateSQL2 . "<br>");
  $Result2 = mysql_query($updateSQL2, $connvbsa) or die(mysql_error());
  
  $updateGoTo = $_SESSION['page'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  $updateGoTo = "../Admin_DB_VBSA/A_memb_index.php";
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['memb_id'])) {
  $memb_id = $_GET['memb_id'];
}

// check if member is a coach.
mysql_select_db($database_connvbsa, $connvbsa);
$query_coach = "Select coach_id FROM coaches_vbsa WHERE memb_id='$memb_id'";
$coach = mysql_query($query_coach, $connvbsa) or die(mysql_error());
$row_coach = mysql_fetch_assoc($coach);
$totalRows_coach = mysql_num_rows($coach);
//echo("<br>Coach Rows "  . $totalRows_coach . "<br>");

mysql_select_db($database_connvbsa, $connvbsa);
if($totalRows_coach > 0)
{
  $query_membedit = "Select members.MemberID, LastName, FirstName, HomeAddress, HomeSuburb, HomeState, HomePostcode, HomePhone, WorkPhone,  MobilePhone, ReceiveSMS, Email, memb_occupation, ReceiveEmail, LifeMember, Deceased,  Gender, Overseas, Referee, Ref_Class, members.LastUpdated, members.UpdateBy, AffiliateMemb, entered_on, dob_day, dob_mnth, dob_year, prospective_ref, ccc_player, Affiliate_1, Affiliate_2, Affiliate_3, Affiliate_4, AffiliateMemb, memb_by, contact_only, coach_id, class, comment, active_coach, curr_memb, hon_memb, paid_memb, paid_how, paid_date, community FROM members JOIN coaches_vbsa on memb_id = members.memberid WHERE members.MemberID = '$memb_id'";
}
else
{
  $query_membedit = "Select MemberID, LastName, FirstName, HomeAddress, HomeSuburb, HomeState, HomePostcode, HomePhone, WorkPhone, MobilePhone, ReceiveSMS, Email, memb_occupation, ReceiveEmail, Club, LifeMember, ccc_player, affiliate_player, Deceased, Junior, Gender, Referee, Ref_Class, prospective_ref, LastUpdated, UpdateBy, Affiliate_1, Affiliate_2, Affiliate_3, Affiliate_4, AffiliateMemb, entered_on, memb_by, dob_day, dob_mnth, dob_year, Overseas, paid_memb, paid_how, members.paid_date, curr_memb, hon_memb, paid_memb, paid_how, paid_date, community FROM members WHERE MemberID = '$memb_id'";
}
//$query_membedit = "Select members.MemberID, LastName, FirstName, HomeAddress, HomeSuburb, HomeState, HomePostcode, HomePhone, WorkPhone,  MobilePhone, ReceiveSMS, Email, ReceiveEmail, LifeMember, Deceased,  Female, Overseas, Referee, Ref_Class, members.LastUpdated, members.UpdateBy, AffiliateMemb, entered_on,  dob_day, dob_mnth, dob_year, prospective_ref, ccc_player, memb_by, contact_only, coach_id, class, comment FROM members JOIN coaches_vbsa on memb_id = members.memberid WHERE members.MemberID = '$memb_id'";

//$query_membedit = "Select members.MemberID, LastName, FirstName, HomeAddress, HomeSuburb, HomeState, HomePostcode, HomePhone, WorkPhone, MobilePhone, ReceiveSMS, Email, ReceiveEmail, Club, LifeMember, ccc_player, Deceased, Junior, Female, Referee, Ref_Class, prospective_ref, members.LastUpdated, members.UpdateBy, AffiliateMemb, entered_on, memb_by, dob_day, dob_mnth, dob_year, Overseas, members.paid_memb, members.paid_how, members.paid_date FROM members WHERE members.MemberID = '$memb_id'";
//echo("<br>Details "  . $query_membedit . "<br>");

$membedit = mysql_query($query_membedit, $connvbsa) or die(mysql_error());
$row_membedit = mysql_fetch_assoc($membedit);
$totalRows_membedit = mysql_num_rows($membedit);

mysql_select_db($database_connvbsa, $connvbsa);
$query_played = "SELECT FirstName, LastName, lifemember, team_grade, count_played, game_type,  current_year_scrs, scr_season FROM scrs   LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE scrs.MemberID='$memb_id'  AND (current_year_scrs = YEAR(CURDATE( )) OR current_year_scrs = (YEAR(CURDATE( ))-1) OR current_year_scrs = (YEAR(CURDATE( ))-2)) ORDER BY current_year_scrs DESC";
//echo("Played " . $query_played . "<br>");
$played = mysql_query($query_played, $connvbsa) or die(mysql_error());
$row_played = mysql_fetch_assoc($played);
$totalRows_played = mysql_num_rows($played);

mysql_select_db($database_connvbsa, $connvbsa);
$query_life_paid = "SELECT MemberID,  LifeMember, paid_memb, paid_how, paid_date FROM members WHERE MemberID='$memb_id'";
$life_paid = mysql_query($query_life_paid, $connvbsa) or die(mysql_error());
$row_life_paid = mysql_fetch_assoc($life_paid);
$totalRows_life_paid = mysql_num_rows($life_paid);

mysql_select_db($database_connvbsa, $connvbsa);
//$query_playedcalyear = "SELECT scrsID, MemberID , SUM(count_played) as totplayedcal FROM scrs WHERE MemberID='$memb_id' AND current_year_scrs = YEAR( CURDATE( ) )";
$query_playedcalyear = "SELECT MemberID, (totplayed_curr+totplaybill_curr) as totplayedcal FROM members WHERE MemberID='$memb_id'";

//echo("<br>Cal Year "  . $query_playedcalyear . "<br>");
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
$query_MonClub = "SELECT scrs.team_id, team_club, team_name, Team_entries.team_grade, comptype FROM scrs, Team_entries WHERE Team_entries.team_id = scrs.team_id  AND scrs.MemberID ='$memb_id'  AND Team_entries.day_played='Mon' AND current_year_scrs = YEAR( CURDATE( ) ) ORDER BY scr_season DESC, count_played DESC LIMIT 1";
$MonClub = mysql_query($query_MonClub, $connvbsa) or die(mysql_error());
$row_MonClub = mysql_fetch_assoc($MonClub);
$totalRows_MonClub = mysql_num_rows($MonClub);

mysql_select_db($database_connvbsa, $connvbsa);
$query_WedClub = "SELECT scrs.team_id, team_club, team_name, Team_entries.team_grade, comptype FROM scrs, Team_entries WHERE Team_entries.team_id = scrs.team_id  AND scrs.MemberID ='$memb_id' AND Team_entries.day_played='Wed' AND current_year_scrs = YEAR( CURDATE( ) ) ORDER BY scr_season DESC, count_played DESC LIMIT 1";
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Member Edit</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>

<table width="1000" border="0" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td><img src="../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>
<table width="1000" align="center"  cellpadding="3" cellspacing="3">
  <tr>
    <td class="red_bold">EDIT PERSONAL DETAILS</td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<div id="DBcontent">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" >
    <table width="1000" align="center" cellpadding="3" cellspacing="3">
      <tr>
        <td align="right" nowrap="nowrap">Member ID: </td>
        <td align="left"><?php echo $memb_id; ?></td>
        <td><?php echo $page; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="2" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">First Name: </td>
        <td><input type="text" name="FirstName" value="<?php echo $row_membedit['FirstName']; ?>" size="32" /></td>
        <td align="right">&nbsp;</td>
        <td >&nbsp;</td>
        <td align="right">Mobile</td>
        <td colspan="2" align="left" ><input type="text" name="MobilePhone" value="<?php echo $row_membedit['MobilePhone']; ?>" size="32" /></td>
      </tr>
      <tr>
        <td align="right">Last Name: </td>
        <td><input type="text" name="LastName" value="<?php echo $row_membedit['LastName']; ?>" size="32" /></td>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">State</td>
        <td colspan="2" align="left"><select name="HomeState">
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
        <td align="right">Land Line: </td>
        <td><input type="text" name="HomePhone" value="<?php echo $row_membedit['HomePhone']; ?>" size="32" /></td>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">Postcode</td>
        <td colspan="2" align="left"><input type="text" name="HomePostcode" value="<?php echo $row_membedit['HomePostcode']; ?>" size="6" /></td>
      </tr>
      <tr>
        <td align="right">Email: </td>
        <td class="page"><input type="text" name="Email" value="<?php echo $row_membedit['Email']; ?>" size="32" /></td>
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">Country:</td>
        <!--<td align="left"><input type="text" name="Overseas" value="<?php echo $row_membedit['Overseas']; ?>" size="10" /></td>-->
        <td align="left"><select id="Overseas" name="Overseas" style="width: 185px; height: 25px;">
              <option value="<?php echo $row_membedit['Overseas']; ?>"><?php echo $row_membedit['Overseas']; ?></option>
              <option value="">&nbsp;</option>
              <option value="Afghanistan">Afghanistan</option>
              <option value="Åland Islands">Åland Islands</option>
              <option value="Albania">Albania</option>
              <option value="Algeria">Algeria</option>
              <option value="American Samoa">American Samoa</option>
              <option value="Andorra">Andorra</option>
              <option value="Angola">Angola</option>
              <option value="Anguilla">Anguilla</option>
              <option value="Antarctica">Antarctica</option>
              <option value="Antigua and Barbuda">Antigua and Barbuda</option>
              <option value="Argentina">Argentina</option>
              <option value="Armenia">Armenia</option>
              <option value="Aruba">Aruba</option>
              <option value="Australia">Australia</option>
              <option value="Austria">Austria</option>
              <option value="Azerbaijan">Azerbaijan</option>
              <option value="Bahamas">Bahamas</option>
              <option value="Bahrain">Bahrain</option>
              <option value="Bangladesh">Bangladesh</option>
              <option value="Barbados">Barbados</option>
              <option value="Belarus">Belarus</option>
              <option value="Belgium">Belgium</option>
              <option value="Belize">Belize</option>
              <option value="Benin">Benin</option>
              <option value="Bermuda">Bermuda</option>
              <option value="Bhutan">Bhutan</option>
              <option value="Bolivia">Bolivia</option>
              <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
              <option value="Botswana">Botswana</option>
              <option value="Bouvet Island">Bouvet Island</option>
              <option value="Brazil">Brazil</option>
              <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
              <option value="Brunei Darussalam">Brunei Darussalam</option>
              <option value="Bulgaria">Bulgaria</option>
              <option value="Burkina Faso">Burkina Faso</option>
              <option value="Burundi">Burundi</option>
              <option value="Cambodia">Cambodia</option>
              <option value="Cameroon">Cameroon</option>
              <option value="Canada">Canada</option>
              <option value="Cape Verde">Cape Verde</option>
              <option value="Cayman Islands">Cayman Islands</option>
              <option value="Central African Republic">Central African Republic</option>
              <option value="Chad">Chad</option>
              <option value="Chile">Chile</option>
              <option value="China">China</option>
              <option value="Christmas Island">Christmas Island</option>
              <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
              <option value="Colombia">Colombia</option>
              <option value="Comoros">Comoros</option>
              <option value="Congo">Congo</option>
              <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
              <option value="Cook Islands">Cook Islands</option>
              <option value="Costa Rica">Costa Rica</option>
              <option value="Cote D'ivoire">Cote D'ivoire</option>
              <option value="Croatia">Croatia</option>
              <option value="Cuba">Cuba</option>
              <option value="Cyprus">Cyprus</option>
              <option value="Czechia">Czechia</option>
              <option value="Denmark">Denmark</option>
              <option value="Djibouti">Djibouti</option>
              <option value="Dominica">Dominica</option>
              <option value="Dominican Republic">Dominican Republic</option>
              <option value="Ecuador">Ecuador</option>
              <option value="Egypt">Egypt</option>
              <option value="El Salvador">El Salvador</option>
              <option value="Equatorial Guinea">Equatorial Guinea</option>
              <option value="Eritrea">Eritrea</option>
              <option value="Estonia">Estonia</option>
              <option value="Ethiopia">Ethiopia</option>
              <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
              <option value="Faroe Islands">Faroe Islands</option>
              <option value="Fiji">Fiji</option>
              <option value="Finland">Finland</option>
              <option value="France">France</option>
              <option value="French Guiana">French Guiana</option>
              <option value="French Polynesia">French Polynesia</option>
              <option value="French Southern Territories">French Southern Territories</option>
              <option value="Gabon">Gabon</option>
              <option value="Gambia">Gambia</option>
              <option value="Georgia">Georgia</option>
              <option value="Germany">Germany</option>
              <option value="Ghana">Ghana</option>
              <option value="Gibraltar">Gibraltar</option>
              <option value="Greece">Greece</option>
              <option value="Greenland">Greenland</option>
              <option value="Grenada">Grenada</option>
              <option value="Guadeloupe">Guadeloupe</option>
              <option value="Guam">Guam</option>
              <option value="Guatemala">Guatemala</option>
              <option value="Guernsey">Guernsey</option>
              <option value="Guinea">Guinea</option>
              <option value="Guinea-bissau">Guinea-bissau</option>
              <option value="Guyana">Guyana</option>
              <option value="Haiti">Haiti</option>
              <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
              <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
              <option value="Honduras">Honduras</option>
              <option value="Hong Kong">Hong Kong</option>
              <option value="Hungary">Hungary</option>
              <option value="Iceland">Iceland</option>
              <option value="India">India</option>
              <option value="Indonesia">Indonesia</option>
              <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
              <option value="Iraq">Iraq</option>
              <option value="Ireland">Ireland</option>
              <option value="Isle of Man">Isle of Man</option>
              <option value="Israel">Israel</option>
              <option value="Italy">Italy</option>
              <option value="Jamaica">Jamaica</option>
              <option value="Japan">Japan</option>
              <option value="Jersey">Jersey</option>
              <option value="Jordan">Jordan</option>
              <option value="Kazakhstan">Kazakhstan</option>
              <option value="Kenya">Kenya</option>
              <option value="Kiribati">Kiribati</option>
              <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
              <option value="Korea, Republic of">Korea, Republic of</option>
              <option value="Kuwait">Kuwait</option>
              <option value="Kyrgyzstan">Kyrgyzstan</option>
              <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
              <option value="Latvia">Latvia</option>
              <option value="Lebanon">Lebanon</option>
              <option value="Lesotho">Lesotho</option>
              <option value="Liberia">Liberia</option>
              <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
              <option value="Liechtenstein">Liechtenstein</option>
              <option value="Lithuania">Lithuania</option>
              <option value="Luxembourg">Luxembourg</option>
              <option value="Macao">Macao</option>
              <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
              <option value="Madagascar">Madagascar</option>
              <option value="Malawi">Malawi</option>
              <option value="Malaysia">Malaysia</option>
              <option value="Maldives">Maldives</option>
              <option value="Mali">Mali</option>
              <option value="Malta">Malta</option>
              <option value="Marshall Islands">Marshall Islands</option>
              <option value="Martinique">Martinique</option>
              <option value="Mauritania">Mauritania</option>
              <option value="Mauritius">Mauritius</option>
              <option value="Mayotte">Mayotte</option>
              <option value="Mexico">Mexico</option>
              <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
              <option value="Moldova, Republic of">Moldova, Republic of</option>
              <option value="Monaco">Monaco</option>
              <option value="Mongolia">Mongolia</option>
              <option value="Montenegro">Montenegro</option>
              <option value="Montserrat">Montserrat</option>
              <option value="Morocco">Morocco</option>
              <option value="Mozambique">Mozambique</option>
              <option value="Myanmar">Myanmar</option>
              <option value="Namibia">Namibia</option>
              <option value="Nauru">Nauru</option>
              <option value="Nepal">Nepal</option>
              <option value="Netherlands">Netherlands</option>
              <option value="Netherlands Antilles">Netherlands Antilles</option>
              <option value="New Caledonia">New Caledonia</option>
              <option value="New Zealand">New Zealand</option>
              <option value="Nicaragua">Nicaragua</option>
              <option value="Niger">Niger</option>
              <option value="Nigeria">Nigeria</option>
              <option value="Niue">Niue</option>
              <option value="Norfolk Island">Norfolk Island</option>
              <option value="Northern Mariana Islands">Northern Mariana Islands</option>
              <option value="Norway">Norway</option>
              <option value="Oman">Oman</option>
              <option value="Pakistan">Pakistan</option>
              <option value="Palau">Palau</option>
              <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
              <option value="Panama">Panama</option>
              <option value="Papua New Guinea">Papua New Guinea</option>
              <option value="Paraguay">Paraguay</option>
              <option value="Peru">Peru</option>
              <option value="Philippines">Philippines</option>
              <option value="Pitcairn">Pitcairn</option>
              <option value="Poland">Poland</option>
              <option value="Portugal">Portugal</option>
              <option value="Puerto Rico">Puerto Rico</option>
              <option value="Qatar">Qatar</option>
              <option value="Reunion">Reunion</option>
              <option value="Romania">Romania</option>
              <option value="Russian Federation">Russian Federation</option>
              <option value="Rwanda">Rwanda</option>
              <option value="Saint Helena">Saint Helena</option>
              <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
              <option value="Saint Lucia">Saint Lucia</option>
              <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
              <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
              <option value="Samoa">Samoa</option>
              <option value="San Marino">San Marino</option>
              <option value="Sao Tome and Principe">Sao Tome and Principe</option>
              <option value="Saudi Arabia">Saudi Arabia</option>
              <option value="Senegal">Senegal</option>
              <option value="Serbia">Serbia</option>
              <option value="Seychelles">Seychelles</option>
              <option value="Sierra Leone">Sierra Leone</option>
              <option value="Singapore">Singapore</option>
              <option value="Slovakia">Slovakia</option>
              <option value="Slovenia">Slovenia</option>
              <option value="Solomon Islands">Solomon Islands</option>
              <option value="Somalia">Somalia</option>
              <option value="South Africa">South Africa</option>
              <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
              <option value="Spain">Spain</option>
              <option value="Sri Lanka">Sri Lanka</option>
              <option value="Sudan">Sudan</option>
              <option value="Suriname">Suriname</option>
              <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
              <option value="Swaziland">Swaziland</option>
              <option value="Sweden">Sweden</option>
              <option value="Switzerland">Switzerland</option>
              <option value="Syrian Arab Republic">Syrian Arab Republic</option>
              <option value="Taiwan, Province of China">Taiwan, Province of China</option>
              <option value="Tajikistan">Tajikistan</option>
              <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
              <option value="Thailand">Thailand</option>
              <option value="Timor-leste">Timor-leste</option>
              <option value="Togo">Togo</option>
              <option value="Tokelau">Tokelau</option>
              <option value="Tonga">Tonga</option>
              <option value="Trinidad and Tobago">Trinidad and Tobago</option>
              <option value="Tunisia">Tunisia</option>
              <option value="Turkey">Turkey</option>
              <option value="Turkmenistan">Turkmenistan</option>
              <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
              <option value="Tuvalu">Tuvalu</option>
              <option value="Uganda">Uganda</option>
              <option value="Ukraine">Ukraine</option>
              <option value="United Arab Emirates">United Arab Emirates</option>
              <option value="United Kingdom">United Kingdom</option>
              <option value="United States">United States</option>
              <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
              <option value="Uruguay">Uruguay</option>
              <option value="Uzbekistan">Uzbekistan</option>
              <option value="Vanuatu">Vanuatu</option>
              <option value="Venezuela">Venezuela</option>
              <option value="Viet Nam">Viet Nam</option>
              <option value="Virgin Islands, British">Virgin Islands, British</option>
              <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
              <option value="Wallis and Futuna">Wallis and Futuna</option>
              <option value="Western Sahara">Western Sahara</option>
              <option value="Yemen">Yemen</option>
              <option value="Zambia">Zambia</option>
              <option value="Zimbabwe">Zimbabwe</option>
            </select>
          </td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Occupation: </td>
        <td align="left"><input type="text" name="Occupation" value="<?php echo $row_membedit['memb_occupation']; ?>" size="32" /></td>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="right">DOB</td>
        <td colspan="6" class="page"><input type="text" name="dob_day" value="<?php echo $row_membedit['dob_day']; ?>" size="2" />
          <input type="text" name="dob_mnth" value="<?php echo $row_membedit['dob_mnth']; ?>" size="2" />
          <input type="text" name="dob_year" value="<?php echo $row_membedit['dob_year']; ?>" size="4" />
    Please insert as dd - mm - yyyy .</td>
      </tr>
      <tr>
        <td align="right">Junior?:</td>
        <td colspan="6"><?php
						$age = (date("Y") - $row_membedit['dob_year']);
						if($age <=18)
						{
						echo "Yes";
						}
						elseif($age >18)
						{
						echo "No";
						}
						?>
            <?php
						$age = (date("Y") - $row_membedit['dob_year']);
						/*if($age==19 or $age==20 or $age==21)
						{
						echo "Minimum Competing age group: Under ";
						echo "21";
						echo "(Calculated by : current year ";
						echo date("Y");
						echo " - year of birth ";
						echo $row_membedit['dob_year'];
						}
						else
            */
            if($age==16 or $age==17 or $age==18)
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
          <span class="red_text">NOTE: For a player to appear as a junior they MUST have a date of birth listed and year of birth &lt;18 years ago</span></td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td colspan="6">&nbsp;</td>
        </tr>
      </table>

      <table width="1000" align="center"  cellpadding="3" cellspacing="3">
      <tr>
        <td align="right">Deactivated Member</td>
        <td><input type="checkbox" name="curr_memb" id="curr_memb"  <?php if (!(strcmp(htmlentities($row_membedit['curr_memb'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
        <td colspan=5 class='red_text'>A member who has had their membership cancelled at the direction of the VBSA Board.</td>
      </tr>
      <tr>
        <td align="right">Honorary Member</td>
        <td><input type="checkbox" name="hon_memb" id="hon_memb"  <?php if (!(strcmp(htmlentities($row_membedit['hon_memb'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
        <td colspan=5 class='red_text'>A current Board member or assistant to the Board of the VBSA.</td>
      </tr>
      <tr>
        <td align="right">Community Member</td>
        <td><input type="checkbox" name="community" id="community"  <?php if (!(strcmp(htmlentities($row_membedit['community'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
        <td colspan=3 class='red_text'>&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Receive Email</td>
        <td><input type="checkbox" name="ReceiveEmail" id="ReceiveEmail"  <?php if (!(strcmp(htmlentities($row_membedit['ReceiveEmail'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
        <td align="right">Gender:</td>
        <td ><select name="Gender">
          <option value="<?= $row_membedit['Gender'] ?>" selected="selected" ><?= $row_membedit['Gender'] ?></option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
          <option value="NonBinary">Non Binary</option>
          <option value="NoGender">No Gender</option>
        </select></td>
        <td align="right" nowrap="nowrap">Monday Club</td>
        <td align="left"><?php if(isset($row_MonClub['team_id'])) echo $row_MonClub['team_name'] . ", " . $row_MonClub['team_grade'] . ", (" . $row_MonClub['comptype'].")"; else echo "Not Playing" ?></td>
      </tr>
      <tr>
        <td align="right">Receive SMS</td>
        <td><input type="checkbox" name="ReceiveSMS" id="ReceiveSMS"  <?php if (!(strcmp(htmlentities($row_membedit['ReceiveSMS'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
        <td align="right">Deceased</td>
        <td><input type="checkbox" name="Deceased" id="Deceased"  <?php if (!(strcmp(htmlentities($row_membedit['Deceased'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
        <td align="right">Wednesday Club</td>
        <td colspan="2"><?php if(isset($row_WedClub['team_id'])) echo $row_WedClub['team_name'] . ", " . $row_WedClub['team_grade'] . ", (" . $row_WedClub['comptype'] . ")"; else echo "Not Playing" ?></td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">Life Member:</td>
        <td><input type="checkbox" name="LifeMember" value="1" id="LifeMember"  <?php if (!(strcmp(htmlentities($row_membedit['LifeMember'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
        <td align="right">Referee</td>
        <td><input type="checkbox" name="Referee" id="Referee"  <?php if (!(strcmp(htmlentities($row_membedit['Referee'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> />&nbsp;<input type="text" name="Ref_Class" value="<?php echo $row_membedit['Ref_Class']; ?>" size="20" /></td>
        <td align="right">Prospective Ref</td>
        <td><input type="checkbox" name="prospective_ref" id="prospective_ref"  <?php if (!(strcmp(htmlentities($row_membedit['prospective_ref'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
      </tr>
      <tr>
        <td align="right">CCC Player:</td>
        <td><input type="checkbox" name="ccc_player" value="1" id="ccc_player"  <?php if (!(strcmp(htmlentities($row_membedit['ccc_player'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
        <td align="right">Paid Member</td>
        <td align="left"><input name="paid_memb" type="checkbox" <?php if((isset($row_membedit['paid_memb'])) && ($row_membedit['paid_memb'] != 0)) { ?> checked <?php } ?>/></td>
        <td align="right">M'ship form OK</td>
        <td colspan="2">
        <?php
        if(isset($row_membedit['memb_by']))
        {
          echo "<img src='../Admin_Images/tick.JPG' width=\"16\">";
        }
        else
        {
          echo '<span class="red_text"> No </span>';
        }
        ?>
        &nbsp;
        </td>
      </tr>
      <tr>
        <td align="right">Affiliate</td>
        <td align="left"><input name="affiliate_player" type="checkbox" <?php if($row_membedit['affiliate_player']==1)  { ?> checked="checked" <?php } ?>/></td>

        <td align="center">Affiliate 1&nbsp;<select name="affiliate_1">
          <option value="<?= $row_membedit['Affiliate_1'] ?>" selected="selected" ><?= $row_membedit['Affiliate_1'] ?></option>
          <option value="">&nbsp;</option>
          <option value="DVSA">DVSA</option>
          <option value="MSBA">MSBA</option>
          <option value="LVABA">LVABA</option>
        </select></td>
        <td align="center">Affiliate 2&nbsp;<select name="affiliate_2">
          <option value="<?= $row_membedit['Affiliate_2'] ?>" selected="selected" ><?= $row_membedit['Affiliate_2'] ?></option>
          <option value="">&nbsp;</option>
          <option value="DVSA">DVSA</option>
          <option value="MSBA">MSBA</option>
          <option value="LVABA">LVABA</option>
        </select></td>
        <td align="center">Affiliate 3&nbsp;<select name="affiliate_3">
          <option value="<?= $row_membedit['Affiliate_3'] ?>" selected="selected" ><?= $row_membedit['Affiliate_3'] ?></option><option value="">&nbsp;</option>
          <option value="">&nbsp;</option>
          <option value="DVSA">DVSA</option>
          <option value="MSBA">MSBA</option>
          <option value="LVABA">LVABA</option>
        </select></td>
        <td align="center">Affiliate 4&nbsp;<select name="affiliate_4">
          <option value="<?= $row_membedit['Affiliate_4'] ?>" selected="selected" ><?= $row_membedit['Affiliate_4'] ?></option><option value="">&nbsp;</option>
          <option value="">&nbsp;</option>
          <option value="DVSA">DVSA</option>
          <option value="MSBA">MSBA</option>
          <option value="LVABA">LVABA</option>
        </select></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Coach</td>
        <td align="left"><input name="coach" type="checkbox" <?php if($row_membedit['coach_id']>0){ ?> checked <?php } ?>/></td>
        <td align="right">Class</td>
        <td><input name="class" type="text" value='<?php echo($row_membedit['class']) ?>'/></td>
        <td align="right">Coach ID</td>
        <td align="left"><input type="text" name='coach_id' value='<?php echo($row_membedit['coach_id']) ?>' size=5 ></td>
      </tr>
      <tr>
         <td align="right">Is Coach Active?</td>
         <td><input type="checkbox" name="active" id="active" <?php if($row_membedit['active'] == 1) {echo "checked=\"checked\"";} ?> /></td>
         <td align="right" >Comment</td>
         <td valign='top' colspan=5 align="left"><textarea name='comment' rows="3" cols="86" align=top><?php echo($row_membedit['comment']) ?></textarea></td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td align="left">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="left">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td colspan="2" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap" bgcolor="#CDCDCD">Contact only:</td>
        <td colspan="6" bgcolor="#CDCDCD"><input type="checkbox" name="contact_only" value="1" id="contact_only"  <?php if (!(strcmp(htmlentities($row_membedit['contact_only'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /> <span class="red_text">* Use this checkbox if this person is a contact for a club only. The VBSA do not record address</span></td>
      </tr>
      <tr>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Paid $</td>
        <?php 
        if($row_membedit['paid_memb'] == 0)
        {
          $paid = NULL;
        }
        else
        {
          $paid = $row_membedit['paid_memb'];
        }
        ?>
        <!--<td align="left"><input type="text" name="paid_memb" value="<?php echo htmlentities($row_membedit['paid_memb'], ENT_COMPAT, 'utf-8'); ?>" size="10" /></td>-->
        <td align="left"><input type="text" name="paid_memb" value="<?php echo $paid; ?>" size="10" /></td>
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
        <td align="left"><input type="text" name="paid_date" value="<?php echo htmlentities($row_membedit['paid_date'], ENT_COMPAT, 'utf-8'); ?>" size="15" /> <input type="button" value="Select Date Paid" onclick="displayDatePicker('paid_date', false, 'ymd', '-');" /></td>
      </tr>
      <tr>
        <td align='center' colspan="6">(Remove ALL fields if removing as paid)</td>
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
        <td colspan="2"><?php echo $row_membedit['UpdateBy']; ?></td>
      </tr>
      <tr>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td align="left">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="left"><input type="submit" value="Update Member Details" /></td>
        <td align="right">&nbsp;</td>
        <td colspan="2" align="left">&nbsp;</td>
      </tr>
    </table>
        <input type="hidden" name="MM_update" value="form1" />
      	<input type="hidden" name="MemberID" value="<?php echo $row_membedit['MemberID']; ?>" />
        <input type="hidden" name="UpdateBy" value="<?php echo $row_getusername['name']; ?>" />
        <input type="hidden" name="LastUpdated" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d"); ?> " />
      	<input type="hidden" name="MemberID" value="<?php echo $row_membedit['MemberID']; ?>" />
  </form>
  </div>
  
  <div id="DBcontent">
    <table width="1000" align="center" cellpadding="2" cellspacing="2">
      <tr>
        <td colspan="5" class="red_bold">Playing History, last 3 years</td>
      </tr>
      <tr>
        <td align="center">Grade</td>
        <td align="center">Season</td>
        <td align="center">Matches played</td>
        <td align="center">In year</td>
        <td align="center">Game Type</td>
        
      </tr>
      <?php do { ?>
      <tr>
        <td align="center"><?php echo $row_played['team_grade']; ?></td>
        <td align="center"><?php echo $row_played['scr_season']; ?></td>
        <td align="center"><?php echo $row_played['count_played']; ?></td>
        <td align="center"><?php echo $row_played['current_year_scrs']; ?></td>
        <td align="center"><?php echo $row_played['game_type']; ?></td>
        
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
      if ($row_life_paid['LifeMember']==1)
      {
        echo "<img src='../Admin_Images/tick.JPG' width=\"16\">";
      }
      else
      {
        echo '<span class="red_text"> No </span>';
      }
      ?>
      </td>
    <td align="left">2. Be an &quot;Active&quot; referee (Reviewed annually by the Victorian head of     referees)
      <?php
      if ($row_membedit['Referee']==1)
      {
      	echo "<img src='../Admin_Images/tick.JPG' width=\"16\">";
      }
      else
      {
        echo '<span class="red_text"> No </span>';
      }
      ?> 
      </td>
    </tr>
  <tr>
    <td align="left">3. Having played 1 or more matches in the current year.
      <?php
      if ($row_playedcalyear['totplayedcal'] >0)
      {
        echo "<img src='../Admin_Images/tick.JPG' width=\"16\">";
      }
      else
      {
        echo '<span class="red_text"> No </span>';
      }
      ?>
    </td>
    <td align="left">4. Having paid $20 in the current year. 
      <?php
      $curYear = date('Y');
      echo $curYear; 
      if ((isset($row_life_paid['paid_memb'])) && ($row_life_paid['paid_memb'] > 0))
      {
      	echo "<img src='../Admin_Images/tick.JPG' width=\"16\">";
      }
      else
      {
      	echo '<span class="red_text"> No </span>';
      }
      ?>
    </td>
  </tr>
</table>

</div>
<!--  
<div id="DBcontent">

<table width="1000" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan="6"><span class="red_bold"><?php echo date("Y"); ?> Financial Detail</span></td>
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
-->
<!--
  <table width="1000" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan="6"><span class="red_bold"><?php echo date("Y"); ?> Financial Detail - (Tournament Members only) editable by the treasurer only - if you see a mistake or have a query email <a href="mailto:treasurer@vbsa.org.au">VBSA Treasurer</a></span></td>
  </tr>
  <tr>
    <td align="right">Paid $</td>
    <td align="left"><?php echo $row_life_paid['paid_memb']; ?></td>
    <td align="right">How Paid:</td>
    <td align="left"><?php echo $row_life_paid['paid_how']; ?></td>
    <td align="right">Date Paid:&nbsp;</td>
    <td align="left">&nbsp;
    <?php
    echo $row_life_paid['paid_date'];
//	$year = date("Y", strtotime($row_life_paid['paid_date']));
//	if($year >2000) {
//	$newDate = date("d M Y", strtotime($row_life_paid['paid_date'])); 
//	echo $newDate; }
//	else
//	echo "";
	?>
	</td>
  </tr>
  </table>

  </div>
-->
</body>
</html>

