<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
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

if (isset($_GET['pagename'])) {
  $pagename = $_GET['pagename'];
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_teamlist = "SELECT scrs.MemberID, LastName, FirstName, MobilePhone, Email, scrs.team_id , captain_scrs, scrs.Team_grade, Team_entries.team_name, Team_entries.team_club, count_played, memb_by FROM scrs, Team_entries, members WHERE Team_entries.team_id=scrs.team_id AND scrs.MemberID=members.MemberID AND current_year_scrs = YEAR( CURDATE( ) ) AND members.FirstName <> 'Player Forfeit' AND members.FirstName <> 'bye' AND Team_entries.team_name <> 'Bye' AND scr_season='$season' GROUP BY scrs.scrsID ORDER BY Team_grade, team_id, captain_scrs DESC, LastName";
$teamlist = mysql_query($query_teamlist, $connvbsa) or die(mysql_error());
$row_teamlist = mysql_fetch_assoc($teamlist);
$totalRows_teamlist = mysql_num_rows($teamlist);
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
<link href="php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

<table width="1000" border="0" align="center" cellpadding="8" cellspacing="0">
  <tr>
  <td class="red_bold">Team Detail <?php echo $season ?></td>
  <td align="right">&nbsp;</td>
  <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<p>&nbsp;</p>
<table align="center" cellpadding="3" cellspacing="3">
  <tr>
    <th align="center">ID</th>
    <th>&nbsp;</th>
    <th align="left">Last Name</th>
    <th align="left">First Name</th>
    <th align="left">Mobile Phone</th>
    <th align="left">Email</th>
    <th align="center">Team ID</th>
    <th align="center">Grade</th>
    <th align="left">Team name</th>
    <th align="left">Club</th>
    <th align="center">Played</th>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <th align="center">M'ship data</th>
    <th align="center">&nbsp;</th>
  </tr>
	<?php do { ?>
    <?php if($row_teamlist['captain_scrs']=='Yes') echo '<tr style="background-color:#CCC">'; else echo '<tr>' ?>
      <tr>
        <td align="center"><?php echo $row_teamlist['MemberID']; ?></td>
      <td><?php if($row_teamlist['captain_scrs']=='Yes') echo "Captain"; else echo ""; ?></td>
      <td align="left"><?php echo $row_teamlist['LastName']; ?></td>
      <td align="left"><?php echo $row_teamlist['FirstName']; ?></td>
      <td align="left" class="page"><a href="tel:<?php echo $row_teamlist['MobilePhone']; ?>"><?php echo $row_teamlist['MobilePhone']; ?></a></td>
      <td align="left" class="page"><a href="mailto:<?php echo $row_teamlist['Email']; ?>"><?php echo $row_teamlist['Email']; ?></a></td>
      <td align="center"><?php echo $row_teamlist['team_id']; ?></td>
      <td align="center"><?php echo $row_teamlist['Team_grade']; ?></td>
      <td align="left"><?php echo $row_teamlist['team_name']; ?></td>
      <td align="left"><?php echo $row_teamlist['team_club']; ?></td>
      <td align="center"><?php echo $row_teamlist['count_played']; ?></td>
      <td><a href="../A_common/vbsa_personal_detail.php?memb_id=<?php echo $row_teamlist['MemberID']; ?>"><img src="../Admin_Images/detail.fw.png" alt="" width="20" title="detail" /></a></td>
      <td align="center"><a href="../A_common/vbsa_member_edit.php?memb_id=<?php echo $row_teamlist['MemberID']; ?>&amp;pagename=<?php echo $pagename; ?>"><img src="../Admin_Images/edit_butt.fw.png" width="20" /></a></td>
      <td align="center" nowrap="nowrap"><?php if(isset($row_teamlist['memb_by'])) echo "<font color=green>Yes</font>"; else echo "<font color=red>No</font>"; ?></td>
      <td align="center" nowrap="nowrap" class="greenbg"><a href="../A_common/vbsa_member_edit_form.php?memb_id=<?php echo $row_teamlist['MemberID']; ?>  &amp;pagename=<?php echo $pagename; ?>" title="Insert / update membership form details">Memb</a> </td>
    </tr>
    <?php } while ($row_teamlist = mysql_fetch_assoc($teamlist)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($teamlist);

?>