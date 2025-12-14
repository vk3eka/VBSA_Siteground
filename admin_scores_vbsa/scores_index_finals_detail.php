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

$comptype = "-1";
if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$grade = "-1";
if (isset($_GET['grade'])) {
  $grade = $_GET['grade'];
}

$team_id = "-1";
if (isset($_GET['team_id'])) {
  $team_id = $_GET['team_id'];
}


mysql_select_db($database_connvbsa, $connvbsa);
$query_ladd_det = "SELECT scrsID, team_id, scrs.MemberID, FirstName, team_grade,  LastName, MobilePhone, Email, scr_season, count_played,  captain_scrs, scrs.EF1, scrs.EF2, scrs.SF1, scrs.SF2, GF, final_sub FROM scrs  LEFT JOIN members ON members.MemberID = scrs.MemberID WHERE scrs.MemberID=members.MemberID   AND scrs.team_id='$team_id'  AND (count_played>3 || final_sub=1) GROUP BY scrs.scrsID ORDER BY members.FirstName, members.LastName";
$ladd_det = mysql_query($query_ladd_det, $connvbsa) or die(mysql_error());
$row_ladd_det = mysql_fetch_assoc($ladd_det);
$totalRows_ladd_det = mysql_num_rows($ladd_det);

mysql_select_db($database_connvbsa, $connvbsa);
$query_finals_tot = "SELECT SUM(IFNULL(EF1,0)) AS EF1tot, SUM(IFNULL(EF2,0)) AS EF2tot, SUM(IFNULL(SF1,0)) AS SF1tot, SUM(IFNULL(SF2,0)) AS SF2tot, SUM(IFNULL(GF,0)) AS GFtot, COUNT(`EF1_pos`) AS EF1count, COUNT(`EF2_pos`) AS EF2count, COUNT(`SF1_pos`) AS SF1count, COUNT(`SF2_pos`) AS SF2count, COUNT(`GF_pos`) AS GFcount FROM scrs WHERE `team_id`='$team_id'";
//$query_finals_tot = "SELECT SUM(IFNULL(SF1,0)) AS SF1tot, SUM(IFNULL(SF2,0)) AS SF2tot, SUM(IFNULL(GF,0)) AS GFtot, COUNT(`SF1_pos`) AS SF1count, COUNT(`SF2_pos`) AS SF2count, COUNT(`GF_pos`) AS GFcount FROM scrs WHERE `team_id`='$team_id'";
$finals_tot = mysql_query($query_finals_tot, $connvbsa) or die(mysql_error());
$row_finals_tot = mysql_fetch_assoc($finals_tot);
$totalRows_finals_tot = mysql_num_rows($finals_tot);

mysql_select_db($database_connvbsa, $connvbsa);
$query_club_display = "SELECT team_id, team_club FROM Team_entries WHERE team_id='$team_id'";
$club_display = mysql_query($query_club_display, $connvbsa) or die(mysql_error());
$row_club_display = mysql_fetch_assoc($club_display);
$totalRows_club_display = mysql_num_rows($club_display);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 

</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
  
  <table width="800" border="0" align="center">
    <tr>
      <td height="17" align="center" class="red_bold">&nbsp;</td>
    </tr>
    <tr>
      <td height="17" align="center" class="red_bold">Team detail for: Team ID <?php echo $row_ladd_det['team_id']; ?> (<?php echo $row_club_display['team_club']; ?>) in <?php echo $grade; ?> (<?php echo $comptype ?>)</td>
    </tr>
    <tr>
      <td align="center" class="greenbg"><a href="scores_index_finals.php?grade=<?php echo $row_ladd_det['team_grade']; ?>&comptype=<?php echo $comptype; ?>&season=<?php echo $season; ?>">Return to <?php echo $row_ladd_det['team_grade']; ?> finals</a></td>
    </tr>
    <tr>
      <td align="center" class="greenbg">&nbsp;</td>
    </tr>
    
  </table>
  <table border="1" align="center">
    <tr>
      <td><table border="1">
        <tr>
          <td width="20">ID</td>
          <td width="30" nowrap="nowrap">scrs id</td>
          <td width="60">Grade</td>
          <td width="60">Member </td>
          <td width="120">Surname</td>
          <td width="40" align="left" nowrap="nowrap">First Name</td>
          <td width="40" align="left" nowrap="nowrap">Mobile</td>
          <td width="40" align="left" nowrap="nowrap">Email</td>
          <td width="40" align="center" nowrap="nowrap">Capt?</td>
          <td width="49" align="center">Qualified</td>
          <td width="40" align="center" nowrap="nowrap">Finals Sub</td>
          <td width="40" align="center">&nbsp;</td>
          <?php 
          if($comptype == 'Billiards')
          {
          ?>
            <td width="40" align="center">EF1</td>
            <td width="40" align="center">EF2</td>
          <?php 
          }
          ?>
          <td width="40" align="center">SF1</td>
          <td width="40" align="center">SF2</td>
          <td width="40" align="center">GF</td>
        </tr>
        <?php do { ?>
          <tr>
            <td width="18"><?php echo $row_ladd_det['MemberID']; ?></td>
            <td width="20"><?php echo $row_ladd_det['scrsID']; ?></td>
            <td width="60" align="left"><?php echo $row_ladd_det['team_grade']; ?></td>
            <td width="60" align="center">
              <?php
				if($row_ladd_det['LifeMember']==1 || $row_ladd_det['paid_memb']>0 || $row_ladd_det['count_played']>=4)
				{
				echo "Yes";
				}
				else
				{
				echo "No";
				}
			?> 
            </td>
            <td align="left" nowrap="nowrap"><?php echo $row_ladd_det['LastName']; ?></td>
            <td align="left" nowrap="nowrap"><?php echo $row_ladd_det['FirstName']; ?></td>
            <td align="left" nowrap="nowrap" class="page"><a href="tel:<?php echo $row_ladd_det['MobilePhone']; ?>"><?php echo $row_ladd_det['MobilePhone']; ?></a></td>
            <td align="left" nowrap="nowrap" class="page"><a href="mailto:<?php echo $row_ladd_det['Email']; ?>"><?php echo $row_ladd_det['Email']; ?></a></td>
            <td align="center"><?php echo $row_ladd_det['captain_scrs']; ?></td>
            <td align="center">
              <?php
		if($row_ladd_det['count_played']>="4")
		{
		echo "Yes";
		}
	    elseif($row_ladd_det['MemberID']=="1")
		{
		echo "NA";
		}
		else 
		{		
		echo "No";
		}
		?>        </td>
            <td align="center" nowrap="nowrap" class="greenbg"><?php echo $row_ladd_det['final_sub']; ?></td>
            <td nowrap="nowrap" class="greenbg"><a href="user_files/scrs_finals_edit_player.php?scrs_id=<?php echo $row_ladd_det['scrsID']; ?>&team_id=<?php echo $row_ladd_det['team_id']; ?>&season=<?php echo $season; ?>&comptype=<?php echo $comptype; ?>&grade=<?php echo $grade; ?>" >Edit Finals Scores</a></td>
            <?php 
            if($comptype == 'Billiards')
            {
            ?>
              <td width="40" align="center"><?php echo $row_ladd_det['EF1']; ?></td>
              <td width="40" align="center"><?php echo $row_ladd_det['EF2']; ?></td>
            <?php
            }
            ?>
            <td width="40" align="center"><?php echo $row_ladd_det['SF1']; ?></td>
            <td width="40" align="center"><?php echo $row_ladd_det['SF2']; ?></td>
            <td width="40" align="center"><?php echo $row_ladd_det['GF']; ?></td>
          </tr>
          <?php } while ($row_ladd_det = mysql_fetch_assoc($ladd_det)); ?>
        </table>
      </td>
    </tr>
    <tr>
      <td>
        <!--Nested table scr -->
  <table border="1" align="right">
    <tr>
      <td width="100" align="right">Total Score</td>
      <?php 
      if($comptype == 'Billiards')
      {
      ?>
        <td width="40" align="center"><?php echo $row_finals_tot['EF1tot']; ?></td>
        <td width="40" align="center"><?php echo $row_finals_tot['EF2tot']; ?></td>
      <?php
      }
      ?>
      <td width="40" align="center"><?php echo $row_finals_tot['SF1tot']; ?></td>
      <td width="40" align="center"><?php echo $row_finals_tot['SF2tot']; ?></td>
      <td width="40" align="center"><?php echo $row_finals_tot['GFtot']; ?></td>
      </tr>
    <tr>
      <td align="right">Count Position</td>
      <?php 
      if($comptype == 'Billiards')
      {
      ?>
        <td width="40" align="center"><?php echo $row_finals_tot['EF1count']; ?></td>
        <td width="40" align="center"><?php echo $row_finals_tot['EF2count']; ?></td>
      <?php
      }
      ?>
      <td align="center"><?php echo $row_finals_tot['SF1count']; ?></td>
      <td align="center"><?php echo $row_finals_tot['SF2count']; ?></td>
      <td align="center"><?php echo $row_finals_tot['GFcount']; ?></td>
    </tr>
  </table>    
        
        
      </td>
    </tr>
</table>
  <p>&nbsp;</p>
</center>
</body>
</html>
<?php

?>