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
// Set Variables
$colname_ladd = "-1";
if (isset($_GET['ladder'])) {
  $colname_ladd = $_GET['ladder'];
}

$cur_year = date("Y");
if( isset( $_GET['team_cur_year'])) {
    $cur_year = $_GET['team_cur_year'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_ladd = sprintf("SELECT Team_entries.team_id, Team_entries.team_club, Team_entries.team_name, Team_entries.team_grade, Team_entries.total_score, Team_entries.Updated, Team_entries.Countback, Team_entries.rounds_played,  Team_entries.count_byes, SUM( total_score *100 ) / ( (rounds_played * players *3 + count_byes *12+count_forfeits*12) - total_score ) AS perc, SUM( IFNULL( P01 + P02 + P03 + P04 + P05 + P06 + P07 + P08 + P09 + P10 + P11 + P12 + P13 + P14 + P15 + P16 + P17 + P18, 0 ) ) AS points, SUM(rounds_played*`players`*3-total_score) as against, COUNT(IF(P01=4,1,NULL))+COUNT(IF(P02=4,1,NULL))+COUNT(IF(P03=4,1,NULL))+COUNT(IF(P04=4,1,NULL))+COUNT(IF(P05=4,1,NULL))+COUNT(IF(P06=4,1,NULL))+COUNT(IF(P07=4,1,NULL))+COUNT(IF(P08=4,1,NULL))+COUNT(IF(P09=4,1,NULL))+COUNT(IF(P10=4,1,NULL))+COUNT(IF(P11=4,1,NULL))+COUNT(IF(P12=4,1,NULL))+COUNT(IF(P13=4,1,NULL))+COUNT(IF(P14=4,1,NULL))+COUNT(IF(P15=4,1,NULL))+COUNT(IF(P16=4,1,NULL))+COUNT(IF(P17=4,1,NULL))+COUNT(IF(P18=4,1,NULL)) AS Won, COUNT(IF(P01=2,1,NULL))+COUNT(IF(P02=2,1,NULL))+COUNT(IF(P03=2,1,NULL))+COUNT(IF(P04=2,1,NULL))+COUNT(IF(P05=2,1,NULL))+COUNT(IF(P06=2,1,NULL))+COUNT(IF(P07=2,1,NULL))+COUNT(IF(P08=2,1,NULL))+COUNT(IF(P09=2,1,NULL))+COUNT(IF(P10=2,1,NULL))+COUNT(IF(P11=2,1,NULL))+COUNT(IF(P12=2,1,NULL))+COUNT(IF(P13=2,1,NULL))+COUNT(IF(P14=2,1,NULL))+COUNT(IF(P15=2,1,NULL))+COUNT(IF(P16=2,1,NULL))+COUNT(IF(P17=2,1,NULL))+COUNT(IF(P18=2,1,NULL)) AS Drawn, team_cal_year FROM Team_entries WHERE Team_entries.team_grade=%s AND team_cal_year =' ".$cur_year."' AND Team_entries.include_draw ='Yes' AND Team_entries.team_name<>'Bye' GROUP BY Team_entries.team_id ORDER BY points DESC, perc DESC, Won DESC", GetSQLValueString($colname_ladd, "text"),GetSQLValueString($colname_ladd, "text"));
$ladd = mysql_query($query_ladd, $connvbsa) or die(mysql_error());
$row_ladd = mysql_fetch_assoc($ladd);
$totalRows_ladd = mysql_num_rows($ladd);

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

<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<table width="850" border="0" align="center" cellpadding="5" cellspacing="5">
  <tr>
	    <td align="center" class="red_bold"><?php echo $row_ladd['team_cal_year']; ?> <?php echo $row_ladd['team_grade']; ?> Final Ladder </td>
	    <td align="center" class="red_bold"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
	<table border="1" align="center" cellpadding="2" class="page">
	  <tr>
	    <td align="center">Team ID</td>
	    <td>Club</td>
	    <td>Team Name</td>
	    <td>Grade</td>
	    <td align="center">Played</td>
	    <td align="center">Byes</td>
	    <td align="center">Won</td>
	    <td align="center">Drawn</td>
	    <td align="center">For</td>
	    <td align="center">Against</td>
	    <td align="center">%</td>
	    <td align="center">Points</td>
      </tr>
	  <?php do { ?>
	  <tr>
	    <td align="center"><?php echo $row_ladd['team_id']; ?></td>
	    <td><?php echo $row_ladd['team_club']; ?></td>
	    <td><?php echo $row_ladd['team_name']; ?></td>
	    <td><?php echo $row_ladd['team_grade']; ?></td>
	    <td width="15" align="center"><?php echo $row_ladd['rounds_played']; ?></td>
	    <td width="15" align="center"><?php echo $row_ladd['count_byes']; ?></td>
	    <td width="15" align="center"><?php echo $row_ladd['Won']; ?></td>
	    <td width="15" align="center"><?php echo $row_ladd['Drawn']; ?></td>
	    <td width="15" align="center"><?php echo $row_ladd['total_score']; ?></td>
	    <td width="15" align="center"><?php echo $row_ladd['against']; ?></td>
	    <td width="15" align="center"><?php echo number_format ($row_ladd['perc'] ? $row_ladd['perc'] : 0,3); ?></td>
	    <td width="15" align="center"><?php echo $row_ladd['points']; ?></td>
      </tr>
	  <?php } while ($row_ladd = mysql_fetch_assoc($ladd)); ?>
</table>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
</body>
</html>
<?php

?>
