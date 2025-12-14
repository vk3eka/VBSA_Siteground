<?php require_once('../Connections/connvbsa.php');
error_reporting(0);
mysql_select_db($database_connvbsa, $connvbsa);

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

$query_CurrentMemb = "Select MemberID, FirstName, LastName, ReceiveSMS, MobilePhone, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach, hon_memb FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR affiliate_player=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1 OR Gender != 'Male' OR affiliate_player = 1 OR hon_memb = 1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND (ReceiveSMS = 1 AND MobilePhone != '')";
$CurrentMemb = mysql_query($query_CurrentMemb, $connvbsa) or die(mysql_error());
$row_CurrentMemb = mysql_fetch_assoc($CurrentMemb);
$totalRows_CurrentMemb = mysql_num_rows($CurrentMemb);

$query_Affiliate = "Select MemberID, FirstName, LastName, Email, ReceiveEmail, ReceiveSMS, MobilePhone, affiliate_player, Affiliate_1, Affiliate_2, Affiliate_3, Affiliate_4 FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE affiliate_player = 1 AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND (ReceiveSMS = 1 AND MobilePhone != '')";
$Affiliate = mysql_query($query_Affiliate, $connvbsa) or die(mysql_error());
$row_Affiliate = mysql_fetch_assoc($Affiliate);
$totalRows_Affiliate = mysql_num_rows($Affiliate);

$query_Captains_S1 = "Select distinct members.MemberID, members.ReceiveSMS, LastName, FirstName, MobilePhone, Email, captain_scrs, authoriser_scrs, Team_entries.team_id, Team_entries.team_grade, Team_entries.team_club, Team_entries.team_name, day_played, comptype FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID LEFT JOIN Team_entries on Team_entries.team_id=scrs.team_id WHERE (captain_scrs=1 OR authoriser_scrs=1) AND team_cal_year = YEAR( CURDATE( ) ) AND scr_season='S1' AND MobilePhone != '' group by members.memberID ORDER BY Team_entries.team_grade, Team_entries.team_club";
$captains_s1 = mysql_query($query_Captains_S1, $connvbsa) or die(mysql_error());
$row_captains_s1 = mysql_fetch_assoc($captains_s1);
$totalRows_captains_s1 = mysql_num_rows($captains_s1);

$query_Captains_S2 = "Select distinct members.MemberID, members.ReceiveSMS, LastName, FirstName, MobilePhone, Email, captain_scrs, authoriser_scrs, Team_entries.team_id, Team_entries.team_grade, Team_entries.team_club, Team_entries.team_name, day_played, comptype FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID LEFT JOIN Team_entries on Team_entries.team_id=scrs.team_id WHERE (captain_scrs=1 OR authoriser_scrs=1) AND team_cal_year = YEAR( CURDATE( ) ) AND scr_season='S2' AND MobilePhone != '' group by members.memberID ORDER BY Team_entries.team_grade, Team_entries.team_club";
$captains_s2 = mysql_query($query_Captains_S2, $connvbsa) or die(mysql_error());
$row_captains_s2 = mysql_fetch_assoc($captains_s2);
$totalRows_captains_s2 = mysql_num_rows($captains_s2);

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

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
</div>

<table width="1200" border="0" align="center" cellpadding="8" cellspacing="0">
	<tr>
    <td align="center"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<?php
$criteria_caption = "This criteria is deactivated for Captains/Authoriser List.";
?>
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td style="border: 1px solid black;"><span class="red_text">SMS lists are based on the following criteria</span>
  <ul>
      <li>Player has a Mobile number.</li>
      <li>*Player has not chosen to "Unsubscribe". <span class="red_text"><?= strtoupper($criteria_caption) ?></span></li>
  </ul>
  *If a player chooses to Unsubscribe, find the player in the members table and uncheck "Receive SMS"
    </td>
  </tr>
</table>
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan="6">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" style="padding-right:10px">
<!-- Nested table 1  -->                  	
      <table align="center">
        
        <tr>
          <?php $genCSVmemb = "download_csv_sms.php?page_from=vbsa_members";?>
          <td colspan="2"  class="greenbg"><a href="<?PHP echo $genCSVmemb?>">Download .csv</a></td>
        </tr>
        <tr>
          <td colspan="2" class="red_text">Current Members</td>
        </tr>
        <tr>
          <td colspan="2">Total: <?php echo $totalRows_CurrentMemb ?></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><strong>Name</strong></td>
          <td><strong>Mobile</strong></td>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_CurrentMemb['FirstName'] . " " . $row_CurrentMemb['LastName']; ?></td>
            <td><?php echo $row_CurrentMemb['MobilePhone']; ?></td>
          </tr>
          <?php } while ($row_CurrentMemb = mysql_fetch_assoc($CurrentMemb)); ?>
      </table>    
    </td>    
<!-- END Nested table 1  -->     
    <td valign="top" style="padding-right:10px">
<!-- Nested table 2  -->                  	
      <table align="center">
        
        <tr>
          <?php $genCSVmemb = "download_csv_sms.php?page_from=vbsa_affiliates";?>
          <td colspan="2"  class="greenbg"><a href="<?PHP echo $genCSVmemb?>">Download .csv</a></td>
        </tr>
        <tr>
          <td colspan="2" class="red_text">Affiliate Members</td>
        </tr>
        <tr>
          <td colspan="2">Total: <?php echo $totalRows_Affiliate ?></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><strong>Name</strong></td>
          <td><strong>Mobile</strong></td>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_Affiliate['FirstName'] . " " . $row_Affiliate['LastName']; ?></td>
            <td><?php echo $row_Affiliate['MobilePhone']; ?></td>
          </tr>
          <?php } while ($row_Affiliate = mysql_fetch_assoc($Affiliate)); ?>
      </table>    
    </td>    
<!-- END Nested table 2  -->     
    <td valign="top" style="padding-right:10px">
<!--Nested table 3 -->   
      <table>
        <tr>
          <?php $genCSV_Capt_S1 = "download_csv_sms.php?page_from=vbsa_Captains_S1";?>
          <td colspan="2"  class="greenbg"><a href="<?PHP echo $genCSV_Capt_S1 ?>">Download .csv</a></td>
        </tr>
        <tr>
          <td colspan="2" class="red_text">Captains/Authoriser Season S1</td>
        </tr>
        <tr>
          <td colspan="2">Total: <?php echo $totalRows_captains_s1 ?></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td><strong>Name</strong></td>
          <td><strong>Mobile</strong></td>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_captains_s1['FirstName'] . " " . $row_captains_s1['LastName']; ?></td>
            <td><?php echo $row_captains_s1['MobilePhone']; ?></td>
          </tr>
          <?php } while ($row_captains_s1 = mysql_fetch_assoc($captains_s1)); ?>
      </table>
<!-- END Nested table 3  --> 
    </td>
    <td valign="top" style="padding-right:10px">
<!--Nested Table 4 -->    
      <table>
        <tr>
          <?php $genCSV_Capt_S2 = "download_csv_sms.php?page_from=vbsa_Captains_S2";?>
          <td colspan="2"  class="greenbg"><a href="<?PHP echo $genCSV_Capt_S2 ?>">Download .csv</a></td>
        </tr>
        <tr>
          <td colspan="2" class="red_text">Captains/Authoriser Season S2</td>
        </tr>
        <tr>
          <td colspan="2">Total: <?php echo $totalRows_captains_s2 ?></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td><strong>Name</strong></td>
          <td><strong>Mobile</strong></td>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_captains_s2['FirstName'] . " " . $row_captains_s2['LastName']; ?></td>
            <td><?php echo $row_captains_s2['MobilePhone']; ?></td>
          </tr>
          <?php } while ($row_captains_s2 = mysql_fetch_assoc($captains_s2)); ?>
      </table>
<!-- END Nested table 4  --> 
    </td>    
    <td valign="top" style="padding-right:10px"></td>
  </tr>
</table>
</body>
<!-- END Outer table  --> 
</body>
</html>
<?php
mysql_free_result($CurrentMemb);

mysql_free_result($Affiliate);

mysql_free_result($captains_s1);

mysql_free_result($captains_s2);

?>