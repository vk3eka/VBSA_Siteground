<?php require_once('../Connections/connvbsa.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}

$searchthis = "-1";
if (isset($_GET['searchthis'])) {
  $searchthis = $_GET['searchthis'];
}

$page = "../Admin_DB_VBSA/search_by_club.php?searchthis=$searchthis"  ;
$_SESSION['page'] = $page;

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



mysql_select_db($database_connvbsa, $connvbsa);
$query_srchres = "SELECT scrs.MemberID, scrs.scrsID, members.LastName, members.FirstName, MobilePhone, Email, scrs.team_grade, team_club, scr_season, scrs.team_id, game_type, count_played, Team_entries.day_played, memb_by FROM scrs LEFT JOIN Team_entries ON Team_entries.team_id = scrs.team_id LEFT JOIN members ON members.MemberID = scrs.MemberID WHERE current_year_scrs = YEAR(NOW()) AND scrs.MemberID !=1 AND scrs.MemberID !=500 AND scrs.MemberID !=1000 AND Team_entries.team_club LIKE CONCAT('$searchthis','%') ORDER BY LastName, FirstName, scr_season, Team_grade";
$srchres = mysql_query($query_srchres, $connvbsa) or die(mysql_error());
$row_srchres = mysql_fetch_assoc($srchres);
$totalRows_srchres = mysql_num_rows($srchres);
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
  
<table width="1000" border="0" align="center">
    <tr>
      <td>&nbsp;</td>
      <td align="left" class="greenbg">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="right">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="left" class="red_bold"> Search by Club in <?php echo date("Y") ?> - Results for "<?php echo $searchthis ?>"</td>
      <td align="right">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="greenbg">NOTE: Players may appear multiple times if they have played in more than 1 grade</td>
    </tr>
    <tr>
      <td align="left" class="greenbg"><a href="../A_common/vbsa_member_insert.php">Insert a new person to the members table</a></td>
      <td align="left" class="greenbg"><a href="user_files/member.php">When is a person considered a member?</a></td>
      <td align="left">Total Entries <?php echo $totalRows_srchres ?></td>
      <td align="right" class="greenbg"><a href="A_memb_index.php">Return to Members Index</a></td>
    </tr>
  </table>
<p>&nbsp;</p>
<table border="1" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td align="center">Membe rID</td>
    <td align="center">Scrore ID</td>
    <td align="left">Last Name</td>
    <td align="left">First Name</td>
    <td align="left" nowrap="nowrap">Mobile Phone</td>
    <td align="left" nowrap="nowrap">Email</td>
    <td align="left">Grade</td>
    <td align="left">Club</td>
    <td align="center">Season</td>
    <td align="center">Team ID</td>
    <td align="center">Game</td>
    <td align="center">Played</td>
    <td align="center">Day</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">M'ship data</td>
    <td align="center">&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_srchres['MemberID']; ?></td>
      <td align="center"><?php echo $row_srchres['scrsID']; ?></td>
      <td align="left"><?php echo $row_srchres['LastName']; ?></td>
      <td align="left"><?php echo $row_srchres['FirstName']; ?></td>
      <td class="page"><a href="tel:<?php echo $row_srchres['MobilePhone']; ?>"><?php echo $row_srchres['MobilePhone']; ?></a></td>
      <td class="page"><a href="mailto:<?php echo $row_srchres['Email']; ?>" target="_blank"><?php echo $row_srchres['Email']; ?></a></td>
      <td align="left"><?php echo $row_srchres['team_grade']; ?></td>
      <td align="left"><?php echo $row_srchres['team_club']; ?></td>
      <td align="center"><?php echo $row_srchres['scr_season']; ?></td>
      <td align="center"><?php echo $row_srchres['team_id']; ?></td>
      <td align="center"><?php echo $row_srchres['game_type']; ?></td>
      <td align="center"><?php echo $row_srchres['count_played']; ?></td>
      <td align="center"><?php echo $row_srchres['day_played']; ?></td>
      <td class="page"><a href="../A_common/vbsa_personal_detail.php?memb_id=<?php echo $row_srchres['MemberID']; ?>"><img src="../Admin_Images/detail.fw.png" alt="" width="20" title="detail" /></a></td>
      <td class="page"><a href="../A_common/vbsa_member_edit.php?memb_id=<?php echo $row_srchres['MemberID']; ?>"><img src="../Admin_Images/edit_butt.fw.png" alt="" width="20" title="edit"  /></a></td>
      <td align="center" nowrap="nowrap"><?php if(isset($row_srchres['memb_by'])) echo "<font color=green>Yes</font>"; else echo "<font color=red>No</font>"; ?></td>
      <td align="center" nowrap="nowrap"><span class="greenbg"><a href="../A_common/vbsa_member_edit_form.php?memb_id=<?php echo $row_srchres['MemberID']; ?>" title="Insert / update membership form details">Memb</a></span></td>
    </tr>
    <?php } while ($row_srchres = mysql_fetch_assoc($srchres)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($srchres);
?>
