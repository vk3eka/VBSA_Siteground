<?php require_once('../Connections/connvbsa.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}

if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

if (isset($_GET['team_id'])) {
  $team_id = $_GET['team_id'];
}

$page = "../team_summary.php";
$_SESSION['page'] = $page;

$MM_authorizedUsers = "Webmaster,Scores,scoring";
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

mysql_select_db($database_connvbsa, $connvbsa);
$query_vbsaclubs = "SELECT ClubNumber, ClubTitle, ClubTables, COUNT(IF(Team_entries.team_season = 'S1',1,NULL)) AS S1t, COUNT(IF(Team_entries.team_season = 'S2',1,NULL)) AS S2t FROM clubs LEFT JOIN Team_entries ON clubs.ClubNumber = Team_entries.team_club_id AND team_cal_year = YEAR( CURDATE( ) ) WHERE VBSAteam =1 GROUP BY ClubNumber ORDER BY ClubTitle";
$vbsaclubs = mysql_query($query_vbsaclubs, $connvbsa) or die(mysql_error());
$row_vbsaclubs = mysql_fetch_assoc($vbsaclubs);
$totalRows_vbsaclubs = mysql_num_rows($vbsaclubs);

mysql_select_db($database_connvbsa, $connvbsa);
$query_teams_entered = "SELECT team_id, team_club, team_club_id, team_name, team_grade, team_season, day_played, players, include_draw, comptype FROM Team_entries WHERE team_cal_year = YEAR( CURDATE( ) ) AND team_season='$season' ORDER BY team_grade, team_club";
$teams_entered = mysql_query($query_teams_entered, $connvbsa) or die(mysql_error());
$row_teams_entered = mysql_fetch_assoc($teams_entered);
$totalRows_teams_entered = mysql_num_rows($teams_entered);
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
    <td align="center" class="red_bold">&nbsp;</td>
    <td align="right" class="page">&nbsp;</td>
  </tr>
  <tr>    
    <td align="center" nowrap="nowrap" class="greenbg"><a href="team_entries.php?season=<?php if($season=='S1') echo "S2"; else echo "S1"; ?>">Go to <?php if($season=='S1') echo "S2"; else echo "S1"; ?> teams </a></td>
    <td align="center" nowrap="nowrap" class="greenbg"><a href="team_entries.php?season=<?php echo $season; ?>">Return to <?php echo $season; ?> teams </a></td>
  </tr>
  <tr>
    <td align="center" class="page">&nbsp;</td>
    <td align="center" class="greenbg">&nbsp;</td>
  </tr>
</table>
	<table align="center">
	  <tr>
	    <td valign="top"> 
        <!--nestec table left -->
	<table align="center" cellpadding="2" cellspacing="2">
	  <tr>
	    <td colspan="6" align="center"><span class="page">All clubs that are participating in the VBSA (Where VBSAteam=&quot;Yes&quot; in the &quot;Clubs&quot; table</span></td>
	    </tr>
	  <tr>
	    <td colspan="5" align="center"><span class="red_bold">Insert a new team into Season <?php echo $season ?></span></td>
	    <td align="center" class="greenbg"><a href="user_files/team_insert_bye.php?season=<?php echo $season; ?>" >Insert a bye</a> </td>
	    </tr>
	  <tr>
	    <td align="center">Club ID</td>
	    <td align="left">ClubTitle</td>
	    <td align="center">Tables</td>
	    <td align="center">S1 teams</td>
	    <td align="center">S2 Teams</td>
	    <td>&nbsp;</td>
      </tr>
	  <?php do { ?>
	  <tr>
	    <td align="center"><?php echo $row_vbsaclubs['ClubNumber']; ?></td>
	    <td align="left"><?php echo $row_vbsaclubs['ClubTitle']; ?></td>
	    <td align="center"><?php echo $row_vbsaclubs['ClubTables']; ?></td>
	    <td align="center"><?php echo $row_vbsaclubs['S1t']; ?></td>
	    <td align="center"><?php echo $row_vbsaclubs['S2t']; ?></td>
	    <td class="greenbg" nowrap="nowrap"><a href="user_files/team_insert.php?club_id=<?php echo $row_vbsaclubs['ClubNumber']; ?>&season=<?php echo $season; ?>" >Insert  team to this club</a></td>
      </tr>
	  <?php } while ($row_vbsaclubs = mysql_fetch_assoc($vbsaclubs)); ?>
</table>
<!--close nested table left -->
</td>
	    <td width="25">&nbsp;</td>
        
	    <td valign="top">
        <!--nested table right -->
        
  <table align="center" cellpadding="2" cellspacing="2">
    <tr>
      <td colspan="9" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="7" align="center"><span class="red_bold"><?php echo $season ?> team entries</span></td>
      <td colspan="2" align="center">Total Teams : <?php echo $totalRows_teams_entered;  ?></td>
      </tr>
    <tr>
      <td align="center">Team ID</td>
      <td align="center">Grade</td>
      <td align="left">Team Name</td>
      <td align="left">Club</td>
      <td align="left">Day</td>
      <td align="center">Type</td>
      <td align="center" nowrap="nowrap">Players</td>
      <td align="center">Include in Draw?</td>
      <td align="center">&nbsp;</td>
      
      </tr>
  <?php do { ?>  
    <tr>
      <td align="center"><?php echo $row_teams_entered['team_id']; ?></td>
      <td align="center"><?php echo $row_teams_entered['team_grade']; ?></td>
      <td align="left"><?php echo $row_teams_entered['team_name']; ?></td>
      <td align="left"><?php echo $row_teams_entered['team_club']; ?></td>
      <td align="left"><?php echo $row_teams_entered['day_played']; ?></td>
      <td align="center"><?php echo $row_teams_entered['comptype']; ?></td>
      <td align="center"><?php echo $row_teams_entered['players']; ?></td>
      <td align="center"><?php echo $row_teams_entered['include_draw']; ?></td>
      <td align="center" class="greenbg" nowrap="nowrap"><a href="user_files/team_entries_edit.php?team_id=<?php echo $row_teams_entered['team_id']; ?>&season=<?php echo $season; ?>" >Edit team details</a></td>
      </tr>
  <?php } while ($row_teams_entered = mysql_fetch_assoc($teams_entered)); ?> 
  
  </table>
        
        <!--close nested table right -->
        </td>
      </tr>
</table>
	<p>&nbsp;</p>
</body>
</html>
<?php

?>
