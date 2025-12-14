<?php require_once('../../Connections/connvbsa.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "../../AA_DB_VBSA/vbsa_logout.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
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

$colname_player_det = "-1";
if (isset($_GET['playdetS2'])) {
  $colname_player_det = $_GET['playdetS2'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_player_det = sprintf("SELECT scrs.scrsID, scrs.MemberID, scrs.team_grade, scrs.team_id, scrs.r01s, scrs.r02s, scrs.r03s, scrs.r04s, scrs.r05s, scrs.r06s,  scrs.r07s, scrs.r08s, scrs.r09s, scrs.r10s, scrs.r11s, scrs.r12s, scrs.r13s, scrs.r14s, scrs.r15s, scrs.r16s, scrs.r17s, scrs.r18s, scrs.r01pos, scrs.r02pos, scrs.r03pos, scrs.r04pos,  scrs.r05pos, scrs.r06pos, scrs.r07pos, scrs.r08pos, scrs.r09pos, scrs.r10pos, scrs.r11pos, scrs.r12pos, scrs.r13pos, scrs.r14pos, scrs.r15pos, scrs.r16pos, scrs.r17pos, scrs.r18pos,  members.MemberID, members.FirstName, members.LastName, scrs.played_S2, scrs.pts_S2, scrs.avg_posS2, members.LifeMember, MAX(members_fin.memb_cal_year) AS most_recent_member, members_fin.HowMembPaid FROM scrs, members  LEFT JOIN members_fin ON members.MemberID = members_fin.Fin_ID WHERE scrs.MemberID = members.MemberID  AND scrs.scrsID =%s", GetSQLValueString($colname_player_det, "int"));
$player_det = mysql_query($query_player_det, $connvbsa) or die(mysql_error());
$row_player_det = mysql_fetch_assoc($player_det);
$totalRows_player_det = mysql_num_rows($player_det);

$colname_plyed_perc = "-1";
if (isset($_GET['playdetS2'])) {
  $colname_plyed_perc = $_GET['playdetS2'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_plyed_perc = sprintf("SELECT scrs.scrsID, scrs.MemberID, avg_posS2, pts_S2, played_S2, SUM( scrs.pts_S2 ) / ( scrs.played_S2 *3 ) *100 AS perc FROM scrs WHERE scrs.scrsID =%s", GetSQLValueString($colname_plyed_perc, "int"));
$plyed_perc = mysql_query($query_plyed_perc, $connvbsa) or die(mysql_error());
$row_plyed_perc = mysql_fetch_assoc($plyed_perc);
$totalRows_plyed_perc = mysql_num_rows($plyed_perc);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Scores</title>
<link href="../../CSS/VBSA_Page_Template.css" rel="stylesheet" type="text/css" />
</head>
<body>
	
<center>
  <table width="800">
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="center" class="red_bold">Player Detail</td>
    </tr>
  </table>
  
  <?php do { ?>
    <table width="800">
      <tr>
        <td><span class="grey_text">Member Id: </span><?php echo $row_player_det['MemberID']; ?></td>
        <td><span class="grey_text">First name: </span><?php echo $row_player_det['FirstName']; ?></td>
        <td><span class="grey_text">Surname: </span><?php echo $row_player_det['LastName']; ?></td>
        <td><span class="grey_text">Grade: </span><?php echo $row_player_det['team_grade']; ?></td>
        <td><span class="grey_text">Team ID: </span><?php echo $row_player_det['team_id']; ?></td>
        <td align="center"><span class="grey_text">Qualified:</span>
          <?php
    if($row_player_det['played_S2']>="4")
		{
		echo "Yes";
		}
	    elseif($row_player_det['MemberID']=="1")
		{
		echo "na";
		}
		else 
		{		
		echo "No";
		}
		?>  
        </td>
      </tr>
  </table>
    <table width="800">
      <tr>
        <td align="center">&nbsp;</td>
        <td align="center" class="grey_text">Rd 1</td>
        <td align="center" class="grey_text">Rd 2</td>
        <td align="center" class="grey_text">Rd 3</td>
        <td align="center" class="grey_text">Rd 4</td>
        <td align="center" class="grey_text">Rd 5</td>
        <td align="center" class="grey_text">Rd 6</td>
        <td align="center" class="grey_text">Rd 7</td>
        <td align="center" class="grey_text">Rd 8</td>
        <td align="center" class="grey_text">Rd 9</td>
        <td align="center" class="grey_text">Rd 10</td>
        <td align="center" class="grey_text">Rd 11</td>
        <td align="center" class="grey_text">Rd 12</td>
        <td align="center" class="grey_text">Rd 13</td>
        <td align="center" class="grey_text">Rd 14</td>
        <td align="center" class="grey_text">Rd 15</td>
        <td align="center" class="grey_text">Rd 16</td>
        <td align="center" class="grey_text">Rd 17</td>
        <td align="center" class="grey_text">Rd 18</td>
      </tr>
      <tr>
        <td align="right" class="grey_text">Frames</td>
        <td align="center" class="bord"><?php echo $row_player_det['r01s']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r02s']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r03s']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r04s']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r05s']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r06s']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r07s']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r08s']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r09s']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r10s']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r11s']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r12s']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r13s']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r14s']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r15s']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r16s']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r17s']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r18s']; ?></td>
      </tr>
      <tr>
        <td align="right" class="grey_text">Position</td>
        <td align="center" class="bord"><?php echo $row_player_det['r01pos']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r02pos']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r03pos']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r04pos']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r05pos']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r06pos']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r07pos']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r08pos']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r09pos']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r10pos']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r11pos']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r12pos']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r13pos']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r14pos']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r15pos']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r16pos']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r17pos']; ?></td>
        <td align="center" class="bord"><?php echo $row_player_det['r18pos']; ?></td>
      </tr>
      
    </table>
    <?php } while ($row_player_det = mysql_fetch_assoc($player_det)); ?>
  <table width="800" align="center">
    <?php do { ?>
      <tr>
        <td><span class="grey_text">Average Pos: </span><?php echo $row_plyed_perc['avg_posS2']; ?></td>
        <td><span class="grey_text">Total Points: </span><?php echo $row_plyed_perc['pts_S2']; ?></td>
        <td><span class="grey_text">Rounds Played: </span><?php echo $row_plyed_perc['played_S2']; ?></td>
        <td><span class="grey_text">Win % : </span><?php printf ("%01.2f", $row_plyed_perc['perc']); ?></td>
      </tr>
      <?php } while ($row_plyed_perc = mysql_fetch_assoc($plyed_perc)); ?>
  </table>
</center>


</body>
</html>
<?php
mysql_free_result($player_det);

mysql_free_result($plyed_perc);
?>