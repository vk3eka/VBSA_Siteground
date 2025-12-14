<?php require_once('../Connections/connvbsa.php'); ?>
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


//set variable
$colname_player_det = "-1";
if (isset($_GET['playdet'])) {
  $colname_player_det = $_GET['playdet'];
}

$cur_year = date("Y");
if( isset( $_GET['team_cur_year'])) {
    $cur_year = $_GET['team_cur_year'];
}

?>
<?php

mysql_select_db($database_connvbsa, $connvbsa);
if(isset($_GET['sortby'])){
$sortby = $_GET['sortby'];
if(strcasecmp($sortby, "desc")==0){
$newsort = "ASC";
}
else{
$newsort = "DESC";
}
}
else{
$sortby = "ASC";
$newsort = "DESC";
}
if(isset($_GET['orderby'])){
$orderby = $_GET['orderby'];
$query_player_det = "SELECT members.FirstName, members.LastName, scrs.team_grade, Team_entries.team_club, Team_entries.team_name, Team_entries.team_cal_year, Team_entries.team_id AS teamnumber, ROUND(SUM( scrs.pts_S1 / scrs.played_S1 /3 *100 ),2) AS percS1, ROUND(SUM( scrs.pts_S2 / scrs.played_S2 /3 *100 ),2) AS percS2, ROUND(SUM( scrs.pts_bill_S1 / scrs.played_bill_S1 /2 *100 ),2) AS percBillS1, scrs.played_S1, scrs.pts_S1, scrs.avg_posS1, scrs.played_S2, scrs.pts_S2, scrs.avg_posS2, scrs.played_bill_S1, scrs.pts_bill_S1, scrs.avg_pos_bill_S1 FROM scrs, members, Team_entries WHERE scrs.MemberID = members.MemberID AND Team_entries.team_id = scrs.team_id AND members.FirstName <> 'bye' AND members.FirstName <> 'forfeit' AND Team_entries.team_club='$colname_player_det' AND team_cal_year =' ".$cur_year."' GROUP BY scrs.scrsID ORDER BY ".mysql_real_escape_string($orderby);

if(strcasecmp($sortby, "desc")==0){
$query_player_det.= " DESC";
}
else{
$query_player_det.= " ASC";
}
}
//default query
else{
$query_player_det = sprintf("SELECT members.FirstName, members.LastName, scrs.team_grade, Team_entries.team_club, Team_entries.team_name, Team_entries.team_cal_year, Team_entries.team_id AS teamnumber, ROUND(SUM( scrs.pts_S1 / scrs.played_S1 /3 *100 ),2) AS percS1, ROUND(SUM( scrs.pts_S2 / scrs.played_S2 /3 *100 ),2) AS percS2, ROUND(SUM( scrs.pts_bill_S1 / scrs.played_bill_S1 /2 *100 ),2) AS percBillS1, scrs.played_S1, scrs.pts_S1, scrs.avg_posS1, scrs.played_S2, scrs.pts_S2, scrs.avg_posS2, scrs.played_bill_S1, scrs.pts_bill_S1, scrs.avg_pos_bill_S1 FROM scrs, members, Team_entries WHERE scrs.MemberID = members.MemberID AND Team_entries.team_id = scrs.team_id AND members.FirstName <> 'bye' AND members.FirstName <> 'forfeit' AND Team_entries.team_club = %s  AND team_cal_year = %s GROUP BY scrs.scrsID ORDER BY scrs.team_grade", GetSQLValueString($colname_player_det, "text"), $cur_year); }
$player_det = mysql_query($query_player_det, $connvbsa) or die(mysql_error());
$row_player_det = mysql_fetch_assoc($player_det);
$totalRows_player_det = mysql_num_rows($player_det);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />


<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/Archives.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
  <table align="center" cellpadding="5" cellspacing="5" class="page">
    <tr>
      <td colspan="4" class="red_bold">Player stats for : <?php echo $row_player_det['team_club']; ?> in <?php echo $row_player_det['team_cal_year']; ?></td>
      <td colspan="6" align="right"><span class="red_bold">
        <input type="button" value="Return to previous page" onclick="history.go(-1)"/>
        </span></td>
    </tr>
    <tr>
      <td align="left"><a href="<? echo $_SERVER['PHP_SELF']."?orderby=FirstName&sortby=$newsort&playdet=".$colname_player_det."&team_cur_year=".$cur_year;?>">First Name</a></td>
      <td align="left"><a href="<? echo $_SERVER['PHP_SELF']."?orderby=LastName&sortby=$newsort&playdet=".$colname_player_det."&team_cur_year=".$cur_year;?>">Surname</a></td>
      <td align="left"><a href="<? echo $_SERVER['PHP_SELF']."?orderby=team_name&sortby=$newsort&playdet=".$colname_player_det."&team_cur_year=".$cur_year;?>">Team Name</a></td>
      <td align="left"><a href="<? echo $_SERVER['PHP_SELF']."?orderby=team_grade&sortby=$newsort&playdet=".$colname_player_det."&team_cur_year=".$cur_year;?>">Grade</a></td>
      <td align="center"><a href="<? echo $_SERVER['PHP_SELF']."?orderby=teamnumber&sortby=$newsort&playdet=".$colname_player_det."&team_cur_year=".$cur_year;?>">Team ID</a></td>
      <td align="center">Matches played</td>
      <td align="center">Frames/points won</td>
      <td align="center">Average Position</td>
      <td align="center">% Won</td>
      <td align="center">&nbsp;</td>
      
    </tr>
    <?php do { ?>
      <tr>
        <td align="left"><?php echo $row_player_det['FirstName']; ?></td>
        <td align="left"><?php echo $row_player_det['LastName']; ?></td>
        <td align="left"><?php echo $row_player_det['team_name']; ?></td>
        <td align="left"><?php echo $row_player_det['team_grade']; ?></td>
        <td align="center"><?php echo $row_player_det['teamnumber']; ?></td>
        <td align="center">
          <?php
	if($row_player_det['played_S1']<>"")
		{
		echo $row_player_det['played_S1']; ;
		}
	elseif($row_player_det['played_S2']<>"")
		{
		echo $row_player_det['played_S2']; ;
		}
	elseif($row_player_det['played_Bill']<>"")
		{
		echo $row_player_det['played_Bill']; ;
		}
	?>
        </td>
        <td align="center">
          <?php
	if($row_player_det['played_S1']<>"")
		{
		echo $row_player_det['pts_S1']; ;
		}
	elseif($row_player_det['played_S2']<>"")
		{
		echo $row_player_det['pts_S2']; ;
		}
	elseif($row_player_det['played_Bill']<>"")
		{
		echo $row_player_det['pts_Bill']; ;
		}
	?>
        </td>
        <td align="center"><?php
	if($row_player_det['played_S1']<>"")
		{
		echo $row_player_det['avg_posS1']; ;
		}
	elseif($row_player_det['played_S2']<>"")
		{
		echo $row_player_det['avg_posS2']; ;
		}
	elseif($row_player_det['played_Bill']<>"")
		{
		echo $row_player_det['avg_posBill']; ;
		}
	?></td>
        <td align="center"><?php
	if($row_player_det['played_S1']<>"")
		{
		echo $row_player_det['percS1']; ;
		}
	elseif($row_player_det['played_S2']<>"")
		{
		echo $row_player_det['percS2']; ;
		}
	elseif($row_player_det['played_Bill']<>"")
		{
		echo $row_player_det['percBill']; ;
		}
	?></td>
        <td align="center"><?php echo $row_player_det['team_cal_year']; ?></td>
      </tr>
      
      <?php } while ($row_player_det = mysql_fetch_assoc($player_det)); ?>
    
  </table>

</body>
</html>
<?php


?>

