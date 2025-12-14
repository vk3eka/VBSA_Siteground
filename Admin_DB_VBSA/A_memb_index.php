<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
// set page url in session for insert / update files
$page = "../Admin_DB_VBSA/A_memb_index.php";
$_SESSION['page'] = $page;

$MM_authorizedUsers = "Webmaster,Treasurer,Administrator,Secretary,Scores";
$MM_donotCheckaccess = "false";

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
    if (($strUsers == "") && false) { 
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
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}

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
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

<table width="1000" border="0" align="center" cellpadding="8" cellspacing="0">
  <tr>
  <td class="red_bold">Members Area, Administrators have access to all views, cannot edit or insert financials.</td>
  <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>

<table align="center">
  <tr>
    <td align="left"  class="greenbg_menu"><a href="../A_common/vbsa_member_insert.php">Insert a new person</a></td>
    <td>&nbsp;</td>
    <td>Insert a new person into the member history.</td>
  </tr>
  <tr>
    <td align="left"  class="greenbg_menu"><a href="../Admin_DB_VBSA/vbsa_online_member_list.php">Online Applications</a></td>
    <td>&nbsp;</td>
    <td>View Online Applications.</td>
  </tr>
  <tr>
    <td align="left"  class="greenbg_menu"><a href="../Admin_update_tables/Update_Scores_Rank_STEP4D.php">Update External Mailing Lists</a></td>
    <td>&nbsp;</td>
    <td>Update External Mailing Lists. Use when membership lists change e.g. newly qualified players, new/removed members (update takes a few minutes).</td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class="red_bold">Choose a view</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="vbsa_members.php">All Current Members</a></td>
    <td>&nbsp;</td>
    <td nowrap="nowrap">Lists all players that satisfy the member criteria.<span class="page" ><a href="https://vbsa.org.au/Admin_DB_VBSA/membership_application_online.php">When is a person considered a member? - See criteria in online membership form</a></span></td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="Members_AA_Affiliates.php">All Affiliate Members</a></td>
    <td>&nbsp;</td>
    <td>View All Members of Affiliate Associations – Bulk Email</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="Members_AA_Community.php">All Community Members</a></td>
    <td>&nbsp;</td>
    <td>View all players in the database that are listed as Community - Bulk Email</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="vbsa_members_affiliates.php">All Current + All Affiliate Members</a></td>
    <td>&nbsp;</td>
    <td nowrap="nowrap">Lists all members both current and affiliate - Bulk Email.</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu" ><a href="vbsa_hist.php">History</a></td>
    <td width="10">&nbsp;</td>
    <td width="742">Complete history of VBSA members, all players that have been included in the database. For information purposes only, not for emailing</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="vbsa_ALL_with_sms.php">All records with SMS</a></td>
    <td>&nbsp;</td>
    <td nowrap="nowrap">All membership records active or not who approved SMS messages.</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="vbsa_ALL_with_email.php">All records with Email</a></td>
    <td>&nbsp;</td>
    <td nowrap="nowrap">All membership records active or not who approved email messages & have an email address.</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="vbsa_players_email_2years.php">Players Bulk Email</a></td>
    <td>&nbsp;</td>
    <td>Lists all players that have played more than 1 match, have an email and have &quot;Receive Email&quot; set as &quot;Yes&quot; from <span class="header_red"><?php echo date("Y") ?></span> and <span class="header_red"><?php echo date("Y")-1 ?>.</span></td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="vbsa_captains.php?season=S1">Captains & SEWS Authorisers S1 <?php echo date("Y") ?></a></td>
    <td>&nbsp;</td>
    <td>A List of all Captains (&amp; team members) - Bulk Email</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="vbsa_captains.php?season=S2">Captains & SEWS Authorisers S2 <?php echo date("Y") ?></a></td>
    <td>&nbsp;</td>
    <td>A List of all Captains (&amp; team members) - Bulk Email</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="vbsa_members_playingbyseason.php?season=S1">Playing <?php echo date("Y") ?> - Season S1</a></td>
    <td>&nbsp;</td>
    <td class="greenbg">View all players that are currently playing and have played at least 1 match - <a href="vbsa_players_email_byseason.php?season=S1">Bulk Email</a></td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="vbsa_members_playingbyseason.php?season=S2"> Playing <?php echo date("Y") ?> - Season S2</a></td>
    <td>&nbsp;</td>
    <td class="greenbg">View all players that are currently playing and have played at least 1 match - <a href="vbsa_players_email_byseason.php?season=S2">Bulk Email</a></td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="vbsa_members_playing.php">Playing <?php echo date("Y") ?></a></td>
    <td>&nbsp;</td>
    <td class="greenbg">View all players that are currently playing</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="vbsa_members_playingbygame.php?comptype=Snooker">Playing <?php echo date("Y") ?> - Snooker</a></td>
    <td>&nbsp;</td>
    <td class="greenbg">View all players that are currently playing Snooker in <?php echo date("Y") ?> and have played more than 1 match - <a href="vbsa_players_email_bygame.php?comptype=Snooker">Bulk Email</a></td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="vbsa_members_playingbygame.php?comptype=Billiards">Playing <?php echo date("Y") ?> - Billiards</a></td>
    <td>&nbsp;</td>
    <td class="greenbg">View all players that are currently playing Billiards in <?php echo date("Y") ?> and have played more than 1 match - <a href="vbsa_players_email_bygame.php?comptype=Billiards">Bulk Email</a></td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="vbsa_finals_qual.php?season=S1">Qualified for finals S1 <?php echo date("Y") ?></a></td>
    <td>&nbsp;</td>
    <td>Qualified for finals players S1 - Bulk Email</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="vbsa_finals_qual.php?season=S2">Qualified for finals S2 <?php echo date("Y") ?></a></td>
    <td>&nbsp;</td>
    <td>Qualified for finals players S2 - Bulk Email</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="Members_AA_Life.php">Life</a></td>
    <td>&nbsp;</td>
    <td>View all players in the database that are listed as life members - Bulk Email</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="Members_AA_Paid.php">Paid</a></td>
    <td>&nbsp;</td>
    <td>View all players in the database that are listed as paid members - Bulk Email</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="Members_AA_CCC.php">CCC</a></td>
    <td>&nbsp;</td>
    <td>View all players in the database that are listed as CCC - Bulk Email</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="Members_AA_Female.php">Female/NB/NS</a></td>
    <td>&nbsp;</td>
    <td>View all players in the database that are listed as <b>NOT</b> male - Bulk Email</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="Members_AA_Junior.php">Juniors</a></td>
    <td>&nbsp;</td>
    <td>View all players in the database that are listed as junior - Bulk Email</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="vbsa_referees.php">Referees</a></td>
    <td>&nbsp;</td>
    <td>View all persons in the database that are listed as a participating referee - Bulk Email</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="coaches.php">Coaches</a></td>
    <td>&nbsp;</td>
    <td>View all players in the database that are listed as coaches - Bulk Email </td>
  </tr>
  <?php
  /* Moved to clubs admin page as requested by Mark Dunn 25/9/24 - Alec Spyrou 
  <tr>
    <td align="left" class="greenbg_menu"><a href="vbsa_contact_only.php">Club contacts</a></td>
    <td>&nbsp;</td>
    <td>View all persons in the members table that are listed as &quot;Contacts Only&quot; (do not show in any other lists)</td>
  </tr>
  */
  ?>
  <tr>
    <td align="left" class="greenbg_menu"><a href="vbsa_members_inactive.php">Deactivated Members</a></td>
    <td>&nbsp;</td>
    <td nowrap="nowrap">Lists all players that have been de-activated. <span class="page" ><a href="https://vbsa.org.au/Admin_DB_VBSA/membership_application_online.php">When is a person considered a member? - See criteria in online membership form</a></span></td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="absc_report.php">ABSC Membership</a></td>
    <td>&nbsp;</td>
    <td>Report Generated for ABSC</td>
  </tr>

</table>
<table width="1000" align="center" class="page">
  <tr>
    <td align="center">Where <b>Bulk Email</b> is noted, mail is sent to only those members with and email address and have approved receiving emails.</td>
  </tr>
  <tr>
    <td align="center">If there is a view that is not listed that would suit your purpose please let me know <a href="mailto:web@vbsa.org.au">web@vbsa.org.au</a></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
</center>
</body>
</html>
