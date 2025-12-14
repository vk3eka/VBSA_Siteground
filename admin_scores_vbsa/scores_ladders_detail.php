<?php require_once('../Connections/connvbsa.php'); 

error_reporting(0); 
?>
<?php

if (!isset($_SESSION)) {
  session_start();
}

if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}

$grade = "-1";
if (isset($_GET['grade'])) {
  $grade = $_GET['grade'];
}

$team_id = "-1";
if (isset($_GET['team_id'])) {
  $team_id = $_GET['team_id'];
}

$page = "http://www.vbsa.org.au/admin_scores/scores_ladders_detail.php?team_id=$team_id&grade=$grade&comptype=$comptype&season=$season";
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

$comptype = "-1";
if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_ladd_det = "SELECT members.MemberID, scrsID, scrs.team_grade, scrs.team_id, game_type, FirstName, LastName, ranknum, MobilePhone, Email, scrs.captain_scrs, scrs.authoriser_scrs,  pts_won, count_played,  scrs.r01s,scrs.r02s, scrs.r03s, scrs.r04s, scrs.r05s, scrs.r06s, scrs.r07s, scrs.r08s,   scrs.r09s, scrs.r10s, scrs.r11s, scrs.r12s, scrs.r13s, scrs.r14s, scrs.r15s, scrs.r16s, scrs.r17s, scrs.r18s,  scrs.SF1, scrs.SF2, GF, team_club, team_name, LifeMember, paid_memb, SUM(totplayed_curr + totplayed_prev + totplaybill_curr + totplaybill_prev) AS memb, memb_by FROM scrs    LEFT JOIN members ON members.MemberID = scrs.MemberID    LEFT JOIN Team_entries ON Team_entries.team_id = scrs.team_id   LEFT JOIN rank_S_open_weekly ON memb_id = scrs.MemberID WHERE scrs.MemberID=members.MemberID    AND scrs.team_id='$team_id' GROUP BY scrs.scrsID ORDER BY members.FirstName, members.LastName";
//echo("Select Ladder " . $query_ladd_det . "<br>");
$ladd_det = mysql_query($query_ladd_det, $connvbsa) or die(mysql_error());
$row_ladd_det = mysql_fetch_assoc($ladd_det);
$totalRows_ladd_det = mysql_num_rows($ladd_det);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Rds = "SELECT COUNT(scrs.r01s)AS RO1S, COUNT(scrs.r02s)AS RO2S, COUNT(scrs.r03s)AS RO3S, COUNT(scrs.r04s)AS RO4S, COUNT(scrs.r05s)AS RO5S, COUNT(scrs.r06s)AS RO6S, COUNT(scrs.r07s)AS RO7S, COUNT(scrs.r08s)AS RO8S, COUNT(scrs.r09s)AS RO9S, COUNT(scrs.r10s)AS R10S, COUNT(scrs.r11s)AS R11S, COUNT(scrs.r12s)AS R12S, COUNT(scrs.r13s)AS R13S, COUNT(scrs.r14s)AS R14S, COUNT(scrs.r15s)AS R15S, COUNT(scrs.r16s)AS R16S, COUNT(scrs.r17s)AS R17S, COUNT(scrs.r18s)AS R18S, MemberID, Team_entries.team_id, scrs.team_id, scrs.r01s FROM scrs, Team_entries WHERE Team_entries.team_id=scrs.team_id AND Team_entries.team_id='$team_id'";
//echo("Count Rounds " . $query_Rds . "<br>");
$Rds = mysql_query($query_Rds, $connvbsa) or die(mysql_error());
$row_Rds = mysql_fetch_assoc($Rds);
$totalRows_Rds = mysql_num_rows($Rds);


mysql_select_db($database_connvbsa, $connvbsa);
$query_pos = "SELECT COUNT(scrs.r01pos)AS R01p, COUNT(scrs.r02pos)AS R02p, COUNT(scrs.r03pos)AS R03p, COUNT(scrs.r04pos)AS R04p, COUNT(scrs.r05pos)AS R05p, COUNT(scrs.r06pos)AS R06p, COUNT(scrs.r07pos)AS R07p, COUNT(scrs.r08pos)AS R08p, COUNT(scrs.r09pos)AS R09p, COUNT(scrs.r10pos)AS R10p, COUNT(scrs.r11pos)AS R11p, COUNT(scrs.r12pos)AS R12p, COUNT(scrs.r13pos)AS R13p, COUNT(scrs.r14pos)AS R14p,  COUNT(scrs.r15pos)AS R15p, COUNT(scrs.r16pos)AS R16p, COUNT(scrs.r17pos)AS R17p, COUNT(scrs.r18pos)AS R18p, MemberID, Team_entries.team_id, scrs.team_id, scrs.r01pos FROM scrs, Team_entries WHERE Team_entries.team_id=scrs.team_id AND Team_entries.team_id='$team_id'";
//echo("Query Position " . $query_pos . "<br>");
$pos = mysql_query($query_pos, $connvbsa) or die(mysql_error());
$row_pos = mysql_fetch_assoc($pos);
$totalRows_pos = mysql_num_rows($pos);


mysql_select_db($database_connvbsa, $connvbsa);
$query_scr = "SELECT Team_entries.team_id, scrs.team_id, T01, T02, T03, T04, T05, T06,  T07, T08, T09, T10, T11, T12,  T13, T14, T15, T16, T17, T18, P01, P02, P03, P04, P05, P06,  P07, P08, P09, P10, P11, P12,  P13, P14, P15, P16, P17, P18 FROM Team_entries, scrs WHERE Team_entries.team_id=scrs.team_id AND scrs.MemberID <>'500' AND Team_entries.team_id='$team_id'";
//echo("Select Team Entries " . $query_scr . "<br>");
$scr = mysql_query($query_scr, $connvbsa) or die(mysql_error());
$row_scr = mysql_fetch_assoc($scr);
$totalRows_scr = mysql_num_rows($scr);

mysql_select_db($database_connvbsa, $connvbsa);
$query_team_brks = "SELECT breaks.Break_ID, breaks.member_ID_brks, breaks.brk, breaks.grade, breaks.brk_team_id, breaks.finals_brk, breaks.recvd, breaks.brk_type, breaks.season, FirstName, LastName FROM breaks LEFT JOIN members ON member_ID_brks=MemberID WHERE brk_team_id = '$team_id' ORDER BY brk DESC";
//echo("Select Breaks " . $query_team_brks . "<br>");
$team_brks = mysql_query($query_team_brks, $connvbsa) or die(mysql_error());
$row_team_brks = mysql_fetch_assoc($team_brks);
$totalRows_team_brks = mysql_num_rows($team_brks);


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
      <td height="17" colspan="3" align="center" class="red_bold">&nbsp;</td>
    </tr>
    <tr>
      <td height="17" colspan="3" align="center" class="red_bold">Team detail for: Team ID <?php echo $team_id; ?> in <?php echo $grade; ?> (<?php echo $comptype ?>)</td>
    </tr>
    <tr>
      <td colspan="3" align="center" class="greenbg">Club: <?php echo $row_ladd_det['team_club']; ?>&nbsp;&nbsp;&nbsp; Team Name: <?php echo $row_ladd_det['team_name']; ?></td>
    </tr>
    <tr>
      <td colspan="3" align="center" class="greenbg">&nbsp;</td>
    </tr>
    <tr>
      <td align="center" class="greenbg"><a href="scores_ladders.php?grade=<?php echo $row_ladd_det['team_grade']; ?>&comptype=<?php echo $comptype; ?>&season=<?php echo $season; ?>">Return to <?php echo $row_ladd_det['team_grade']; ?> ladder</a></td>
      <td align="center" class="greenbg"><a href="user_files/scrs_player_insert_to_team.php?team_id=<?php echo $team_id ?>&grade=<?php echo $grade ?>&season=<?php echo $season; ?>&comptype=<?php echo $comptype; ?>">Insert a player to this team</a></td>
      <td align="center" class="greenbg"><a href="../A_common/vbsa_member_insert.php">Insert a new player to the members table</a></td>
    </tr>
    <tr>
      <td align="center" class="greenbg">&nbsp;</td> 
      <td align="center" class="greenbg">&nbsp;</td>
      <td align="center" class="greenbg">&nbsp;</td>
    </tr>
    
  </table>
  <table border="1" align="center">
    <tr>
      <td><table border="1">
        <tr>
          <td align="center" nowrap="nowrap"> Memb ID</td>
          <td align="center" nowrap="nowrap">Scrs ID</td>
          <td align="center">Grade</td>
          <td align="left" nowrap="nowrap">Game type</td>
          <td align="center">Member </td>
          <td align="center" nowrap="nowrap">Form Rcvd?</td>
          <td align="left">Name</td>
			  <?php if($comptype=='Snooker') { ?>
              <td width="40" align="center" nowrap="nowrap">Weekly Rank</td>
              <?php } else { ?>
              <td width="40" align="center" nowrap="nowrap">&nbsp;</td>
              <?php } ?>
          <td align="left" nowrap="nowrap">Mobile</td>
          <td align="center" nowrap="nowrap">Email</td>
          <td align="center" nowrap="nowrap">Pts won</td>
          <td align="center">Qualified</td>
          <td align="center">Played</td>
          <td align="center">&nbsp;</td>
          <td width="20" align="center">1</td>
          <td width="20" align="center">2</td>
          <td width="20" align="center">3</td>
          <td width="20" align="center">4</td>
          <td width="20" align="center">5</td>
          <td width="20" align="center">6</td>
          <td width="20" align="center">7</td>
          <td width="20" align="center">8</td>
          <td width="20" align="center">9</td>
          <td width="20" align="center">10</td>
          <td width="20" align="center">11</td>
          <td width="20" align="center">12</td>
          <td width="20" align="center">13</td>
          <td width="20" align="center">14</td>
          <td width="20" align="center">15</td>
          <td width="20" align="center">16</td>
          <td width="20" align="center">17</td>
          <td width="20" align="center">18</td>
        </tr>
        <?php do { ?>
          <tr>
            <td width="18" align="center"><?php echo $row_ladd_det['MemberID']; ?></td>
            <td width="20" align="center"><?php echo $row_ladd_det['scrsID']; ?></td>
            <td width="60" align="center" nowrap="nowrap">
				<?php if($grade != $row_ladd_det['team_grade']) echo '<span class=red_text>'."!!Edit Scrs".'</span>'; else echo $row_ladd_det['team_grade'];?>
            </td>
            <td width="60" align="left"><?php if($row_ladd_det['game_type'] != $comptype) echo '<span class=red_text>'."!!Edit Scrs".'</span>'; else echo $row_ladd_det['game_type']; ?></td>
            <td width="60" align="center">
            <?php
    				if(isset($row_ladd_det['memb_by']))
    				{
    				echo "Yes";
    				}
    				else
    				{
    				echo "No";
    				}
    			?> 
            </td>
            <td align="center" nowrap="nowrap">
				<?php if(isset($row_ladd_det['memb_by'])) echo "Yes"; else echo '<span style=color:#F00>'."No".'</span>' ?>
            </td>
        <?php if($row_ladd_det['captain_scrs'] > 0) 
        {
          $authoriser = ' (Capt)';
        }
        else if($row_ladd_det['authoriser_scrs'] > 0)
        {
          $authoriser = ' (Auth)';
        }
        else
        {
          $authoriser = '';
        }
        ?>
            <td align="left" nowrap="nowrap"><?php echo $row_ladd_det['FirstName']; ?> <?php echo $row_ladd_det['LastName']; ?> <?php echo $authoriser; ?></td>
            
            <?php // if gametype = snooker
			if($comptype=='Snooker') { ?>
            <td align="center" nowrap="nowrap" class="page"><a href="user_files/scrs_player_hist.php?memb_id=<?php echo $row_ladd_det['MemberID']; ?>&comptype=<?php echo $comptype ?>" title="View player history"><?php echo $row_ladd_det['ranknum']; ?></a></td>
            
            <?php // gametype = billiards
			} elseif($comptype=='Billiards') { ?>
            <td align="center" nowrap="nowrap" class="greenbg"><a href="user_files/scrs_player_hist.php?memb_id=<?php echo $row_ladd_det['MemberID']; ?>&amp;comptype=<?php echo $comptype ?>" title="View player history">Player History</a></td>
            
            <?php } else echo "" ?>
            
            <td align="left" nowrap="nowrap" class="page"><a href="tel:<?php echo $row_ladd_det['MobilePhone']; ?>"><?php echo $row_ladd_det['MobilePhone']; ?></a></td>
            <td align="left" nowrap="nowrap" class="page"><a href="mailto:<?php echo $row_ladd_det['Email']; ?>"><?php echo $row_ladd_det['Email']; ?></a></td>
            <td align="center"><?php echo $row_ladd_det['pts_won']; ?></td>
            <td align="center"><?php if($row_ladd_det['count_played'] >3) echo "Yes"; else echo "No"; ?></td>
            <td align="center">
            <?php if($row_ladd_det['count_played']>0) echo $row_ladd_det['count_played']; else { ?>
            <a href="user_files/scrs_player_delete_confirm.php?scrsID=<?php echo $row_ladd_det['scrsID']; ?>&team_id=<?php echo $team_id ?>&grade=<?php echo $grade ?>&season=<?php echo $season ?>&comptype=<?php echo $comptype ?>"><img src="../Admin_Images/Trash.fw.png" width="16"/></a>            
			<?php } ?>
            </td>
            <td nowrap="nowrap" class="greenbg"><a href="user_files/scrs_player_edit.php?scrs_id=<?php echo $row_ladd_det['scrsID']; ?>&amp;team_id=<?php echo $row_ladd_det['team_id']; ?>&amp;season=<?php echo $season; ?>&amp;grade=<?php echo $grade; ?>&amp;comptype=<?php echo $comptype; ?>" >Edit Scrs</a></td>
            <td width="20" align="center"><?php echo $row_ladd_det['r01s']; ?></td>
            <td width="20" align="center"><?php echo $row_ladd_det['r02s']; ?></td>
            <td width="20" align="center"><?php echo $row_ladd_det['r03s']; ?></td>
            <td width="20" align="center"><?php echo $row_ladd_det['r04s']; ?></td>
            <td width="20" align="center"><?php echo $row_ladd_det['r05s']; ?></td>
            <td width="20" align="center"><?php echo $row_ladd_det['r06s']; ?></td>
            <td width="20" align="center"><?php echo $row_ladd_det['r07s']; ?></td>
            <td width="20" align="center"><?php echo $row_ladd_det['r08s']; ?></td>
            <td width="20" align="center"><?php echo $row_ladd_det['r09s']; ?></td>
            <td width="20" align="center"><?php echo $row_ladd_det['r10s']; ?></td>
            <td width="20" align="center"><?php echo $row_ladd_det['r11s']; ?></td>
            <td width="20" align="center"><?php echo $row_ladd_det['r12s']; ?></td>
            <td width="20" align="center"><?php echo $row_ladd_det['r13s']; ?></td>
            <td width="20" align="center"><?php echo $row_ladd_det['r14s']; ?></td>
            <td width="20" align="center"><?php echo $row_ladd_det['r15s']; ?></td>
            <td width="20" align="center"><?php echo $row_ladd_det['r16s']; ?></td>
            <td width="20" align="center"><?php echo $row_ladd_det['r17s']; ?></td>
            <td width="20" align="center"><?php echo $row_ladd_det['r18s']; ?></td>
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
      <td width="20" align="center"><?php echo $row_scr['T01']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['T02']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['T03']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['T04']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['T05']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['T06']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['T07']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['T08']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['T09']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['T10']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['T11']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['T12']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['T13']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['T14']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['T15']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['T16']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['T17']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['T18']; ?></td>
      </tr>
    <tr>
      <td width="100" align="right">Points</td>
      <td width="20" align="center"><?php echo $row_scr['P01']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['P02']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['P03']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['P04']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['P05']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['P06']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['P07']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['P08']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['P09']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['P10']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['P11']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['P12']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['P13']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['P14']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['P15']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['P16']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['P17']; ?></td>
      <td width="20" align="center"><?php echo $row_scr['P18']; ?></td>
      </tr>
  </table>    
        
        
      </td>
    </tr>
    <tr>
      <td><table border="1" align="right">
        <tr>
          <td width="100" align="right">Count Round :</td>
          <td width="20" align="center"><?php echo $row_Rds['RO1S']; ?></td>
          <td width="20" align="center"><?php echo $row_Rds['RO2S']; ?></td>
          <td width="20" align="center"><?php echo $row_Rds['RO3S']; ?></td>
          <td width="20" align="center"><?php echo $row_Rds['RO4S']; ?></td>
          <td width="20" align="center"><?php echo $row_Rds['RO5S']; ?></td>
          <td width="20" align="center"><?php echo $row_Rds['RO6S']; ?></td>
          <td width="20" align="center"><?php echo $row_Rds['RO7S']; ?></td>
          <td width="20" align="center"><?php echo $row_Rds['RO8S']; ?></td>
          <td width="20" align="center"><?php echo $row_Rds['RO9S']; ?></td>
          <td width="20" align="center"><?php echo $row_Rds['R10S']; ?></td>
          <td width="20" align="center"><?php echo $row_Rds['R11S']; ?></td>
          <td width="20" align="center"><?php echo $row_Rds['R12S']; ?></td>
          <td width="20" align="center"><?php echo $row_Rds['R13S']; ?></td>
          <td width="20" align="center"><?php echo $row_Rds['R14S']; ?></td>
          <td width="20" align="center"><?php echo $row_Rds['R15S']; ?></td>
          <td width="20" align="center"><?php echo $row_Rds['R16S']; ?></td>
          <td width="20" align="center"><?php echo $row_Rds['R17S']; ?></td>
          <td width="20" align="center"><?php echo $row_Rds['R18S']; ?></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><table border="1" align="right">
        <tr>
          <td width="100" align="right">Count Position :</td>
          <td width="20" align="center"><?php echo $row_pos['R01p']; ?></td>
          <td width="20" align="center"><?php echo $row_pos['R02p']; ?></td>
          <td width="20" align="center"><?php echo $row_pos['R03p']; ?></td>
          <td width="20" align="center"><?php echo $row_pos['R04p']; ?></td>
          <td width="20" align="center"><?php echo $row_pos['R05p']; ?></td>
          <td width="20" align="center"><?php echo $row_pos['R06p']; ?></td>
          <td width="20" align="center"><?php echo $row_pos['R07p']; ?></td>
          <td width="20" align="center"><?php echo $row_pos['R08p']; ?></td>
          <td width="20" align="center"><?php echo $row_pos['R09p']; ?></td>
          <td width="20" align="center"><?php echo $row_pos['R10p']; ?></td>
          <td width="20" align="center"><?php echo $row_pos['R11p']; ?></td>
          <td width="20" align="center"><?php echo $row_pos['R12p']; ?></td>
          <td width="20" align="center"><?php echo $row_pos['R13p']; ?></td>
          <td width="20" align="center"><?php echo $row_pos['R14p']; ?></td>
          <td width="20" align="center"><?php echo $row_pos['R15p']; ?></td>
          <td width="20" align="center"><?php echo $row_pos['R16p']; ?></td>
          <td width="20" align="center"><?php echo $row_pos['R17p']; ?></td>
          <td width="20" align="center"><?php echo $row_pos['R18p']; ?></td>
        </tr>
      </table></td>
    </tr>
</table>
  <p>&nbsp;</p>
  <table border="1" align="center" cellpadding="2" cellspacing="2">
    <tr>
      <td colspan="9" class="red_text">Recorded Breaks for this team </td>
    </tr>
    <tr>
      <td align="center">Break ID</td>
      <td align="center">Member ID</td>
      <td align="center">Break</td>
      <td align="left">Name</td>
      <td>Finals</td>
      <td>Recvd</td>
      <td>Type</td>
      <td align="center">Season</td>
      <td>&nbsp;</td>
    </tr>
    <?php do { ?>
      <tr>
        <td align="center"><?php echo $row_team_brks['Break_ID']; ?></td>
        <td align="center"><?php echo $row_team_brks['member_ID_brks']; ?></td>
        <td align="center"><?php echo $row_team_brks['brk']; ?></td>
        <td align="left"><?php echo $row_team_brks['FirstName']; ?> <?php echo $row_team_brks['LastName']; ?></td>
        <td><?php echo $row_team_brks['finals_brk']; ?></td>
        <td><?php echo $row_team_brks['recvd']; ?></td>
        <td><?php echo $row_team_brks['brk_type']; ?></td>
        <td align="center"><?php echo $row_team_brks['season']; ?></td>
        <td><a href="user_files/break_edit.php?brk_id=<?php echo $row_team_brks['Break_ID']; ?>" title="Edit"><img src="../Admin_Images/edit_butt.fw.png" width="20" /></a></td>
      </tr>
      <?php } while ($row_team_brks = mysql_fetch_assoc($team_brks)); ?>
  </table>
</center>
</body>
</html>
<?php

?>