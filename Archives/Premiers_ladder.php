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

$year = "-1";
if (isset($_GET['year'])) {
$year = $_GET['year'];
}

$grade = "-1";
if (isset($_GET['grade'])) {
$grade = $_GET['grade'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_ladder = "SELECT team_id, team_club, team_name, team_grade, match_pts_total AS Pts, team_perc, match_won_count AS W, match_drawn_count AS D, total_score AS F,  pts_against AS A, rounds_played AS P, count_byes AS B, Updated, Countback, comptype, team_season, audited FROM Team_entries WHERE team_grade='$grade' AND include_draw ='Yes' AND team_cal_year = '$year' AND team_name != 'Bye' GROUP BY team_id ORDER BY Pts DESC, team_perc DESC, W DESC, D DESC ";
$ladder = mysql_query($query_ladder, $connvbsa) or die(mysql_error());
$row_ladder = mysql_fetch_assoc($ladder);
$totalRows_ladder = mysql_num_rows($ladder);

mysql_select_db($database_connvbsa, $connvbsa);
$query_final_T1 = "SELECT * FROM Team_entries WHERE team_grade ='$grade' AND audited ='Yes' AND team_cal_year = '$year' ORDER BY match_pts_total DESC, team_perc DESC, match_won_count DESC, match_drawn_count DESC, Result_pos DESC Limit 1";
$final_T1 = mysql_query($query_final_T1, $connvbsa) or die(mysql_error());
$row_final_T1 = mysql_fetch_assoc($final_T1);
$totalRows_final_T1 = mysql_num_rows($final_T1);

mysql_select_db($database_connvbsa, $connvbsa);
$query_final_T2 = "SELECT * FROM `Team_entries` WHERE `team_grade`='$grade' AND `audited`='Yes' AND team_cal_year = '$year' ORDER BY `match_pts_total` DESC, `team_perc` DESC, `match_won_count` DESC, `match_drawn_count` DESC, `Result_pos` DESC Limit 1,1";
$final_T2 = mysql_query($query_final_T2, $connvbsa) or die(mysql_error());
$row_final_T2 = mysql_fetch_assoc($final_T2);
$totalRows_final_T2 = mysql_num_rows($final_T2);

mysql_select_db($database_connvbsa, $connvbsa);
$query_final_T3 = "SELECT * FROM Team_entries WHERE team_grade ='$grade' AND audited ='Yes' AND team_cal_year = '$year' ORDER BY match_pts_total DESC, team_perc DESC, match_won_count DESC, match_drawn_count DESC, Result_pos DESC Limit 2,1";
$final_T3 = mysql_query($query_final_T3, $connvbsa) or die(mysql_error());
$row_final_T3 = mysql_fetch_assoc($final_T3);
$totalRows_final_T3 = mysql_num_rows($final_T3);

mysql_select_db($database_connvbsa, $connvbsa);
$query_final_T4 = "SELECT * FROM Team_entries WHERE team_grade ='$grade' AND audited ='Yes' AND team_cal_year = '$year' ORDER BY match_pts_total DESC, team_perc DESC, match_won_count DESC, match_drawn_count DESC, Result_pos DESC Limit 3,1";
$final_T4 = mysql_query($query_final_T4, $connvbsa) or die(mysql_error());
$row_final_T4 = mysql_fetch_assoc($final_T4);
$totalRows_final_T4 = mysql_num_rows($final_T4);

mysql_select_db($database_connvbsa, $connvbsa);
$query_SF1win = "SELECT team_id, team_name, team_grade, GFtot, GF_pts, SF1tot, SF1_pts FROM Team_entries WHERE Team_entries.team_grade ='$grade' AND  team_cal_year = '$year' AND SF1tot is not null ORDER BY SF1tot DESC, SF1_pts DESC   LIMIT 1";
$SF1win = mysql_query($query_SF1win, $connvbsa) or die(mysql_error());
$row_SF1win = mysql_fetch_assoc($SF1win);
$totalRows_SF1win = mysql_num_rows($SF1win);

mysql_select_db($database_connvbsa, $connvbsa);
$query_SF2win = "SELECT team_id, team_name, team_grade, GFtot, GF_pts, SF2tot, SF2_pts FROM Team_entries WHERE Team_entries.team_grade ='$grade' AND  team_cal_year = '$year' AND SF2tot is not null ORDER BY SF2tot DESC, SF2_pts DESC   LIMIT 1";
$SF2win = mysql_query($query_SF2win, $connvbsa) or die(mysql_error());
$row_SF2win = mysql_fetch_assoc($SF2win);
$totalRows_SF2win = mysql_num_rows($SF2win);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Archives</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</head>
<body id="vbsa">

   <!-- Include Google Tracking -->
<?php include_once("includes/analyticstracking.php") ?>

<div class="container"> 

    <!-- Include header -->
<?php include '../includes/header.php';?>
    
    <!-- Include navigation -->
<?php include '../includes/nav_vbsa.php';?>
    
  </div>
</nav>  

</div><!--End Bootstrap Container--> 

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 

  <!--Content--> 
  
  
  <!--Left--> 
  
  <div class="row"> 
  <div class="Page_heading_container">
 		<div class="page_title">VBSA Archives - <?php echo $year; ?> <?php echo $grade; ?> Final Ladder &amp; Results</div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 
    <!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>

    
  <div class="table-responsive center-block" style="max-width:900px"> <!-- class table-responsive -->
  
  

	<table class="table">
	  <tr>
	    <th align="center" nowrap="nowrap">Team ID</td>
	    <th align="left">Club</td>
	    <th align="left" nowrap="nowrap">Team Name</td>
	    <th align="left">Grade</td>
	    <th>Played</th>
	    <th class="text-center">Byes</th>
	    <th class="text-center">Won</th>
	    <th class="text-center">Drawn</th>
	    <th class="text-center">For</th>
	    <th class="text-center">Against</th>
	    <th class="text-center">%</th>
	    <th class="text-center">Points</th>
	    <th class="text-center">&nbsp;</th>
      </tr>
	  <?php do { ?>
	  <tr>
	    <td align="center"><?php echo $row_ladder['team_id']; ?></td>
	    <td align="left" nowrap="nowrap"><?php echo $row_ladder['team_club']; ?></td>
	    <td align="left" nowrap="nowrap"><?php echo $row_ladder['team_name']; ?></td>
	    <td align="left"><?php echo $row_ladder['team_grade']; ?></td>
	    <td align="center"><?php echo $row_ladder['P']; ?></td>
	    <td align="center"><?php echo $row_ladder['B']; ?></td>
	    <td align="center"><?php echo $row_ladder['W']; ?></td>
	    <td align="center"><?php echo $row_ladder['D']; ?></td>
	    <td align="center"><?php echo $row_ladder['F']; ?></td>
	    <td align="center"><?php echo $row_ladder['A']; ?></td>
	    <td align="center"><?php echo $row_ladder['team_perc']; ?></td>
	    <td align="center"><?php echo $row_ladder['P']; ?></td>
	    <td align="center" nowrap="nowrap"><a href="Ladder_team_detail.php?teamdet=<?php echo $row_ladder['team_id']; ?> & comptype=<?php echo $row_ladder['comptype']; ?>" class="btn-sm btn-primary btn-responsive" role="button">Team Detail</a></td>
      </tr>
	  <?php } while ($row_ladder = mysql_fetch_assoc($ladder)); ?>
</table>  
</div><!-- close ladder --> 

<!-- finals results -->  
<div class="table-responsive center-block" style="max-width:500px"> 
<table class="table">
          <tr>
            <td class="italic">&nbsp;</td>
            <td colspan="5" class="italic">Finals Results (* = win on points)</td>
          </tr>
          <tr>
            <th>&nbsp;</th>
            <th>Team Name</th>
            <th class="text-center">Score</th>
            <th class="text-center">&nbsp;</th>
            <th>Team Name</th>
            <th class="text-center">Score</th>
          </tr>
            <tr>
              <td class="italic">SF1 </td>
              <td nowrap="nowrap"><?php echo $row_final_T1['team_name']; ?></td>
              <td class="text-center" ><?php echo $row_final_T1['SF1tot']; if ($row_final_T1['SF1_pts']==1)echo "*"; else echo ""; ?></td>
              <td class="text-center">v</td>
              <td nowrap="nowrap"><?php echo $row_final_T4['team_name']; ?></td>
              <td class="text-center"><?php echo $row_final_T4['SF1tot']; if ($row_final_T4['SF2_pts']==1)echo "*"; else echo "";?></td>
            </tr>
            <tr>
              <td class="italic">SF2 </td>
              <td nowrap="nowrap"><?php echo $row_final_T2['team_name']; ?></td>
              <td class="text-center"><?php echo $row_final_T2['SF2tot']; if ($row_final_T2['SF2_pts']==1)echo "*"; else echo ""; ?></td>
              <td class="text-center">v</td>
              <td nowrap="nowrap"><?php echo $row_final_T3['team_name']; ?></td>
              <td class="text-center"><?php echo $row_final_T3['SF2tot']; if ($row_final_T3['SF2_pts']==1)echo "*"; else echo "";?></td>
            </tr>
            <tr>
              <td class="italic">GF </td>
              <td nowrap="nowrap"><?php echo $row_SF1win['team_name']; ?></td>
              <td class="text-center"><?php echo $row_SF1win['GFtot']; if ($row_SF1win['GF_pts']==1)echo "*"; else echo ""; ?></td>
              <td>v</td>
              <td nowrap="nowrap"><?php echo $row_SF2win['team_name']; ?></td>
              <td class="text-center"><?php echo $row_SF2win['GFtot']; if ($row_SF2win['GF_pts']==1)echo "*"; else echo "";  ?></td>
            </tr>
        </table>
</div>  
<!-- close finals results -->  
  
</div><!-- close containing wrapper -->   
 
</body>
</html>
<?php

?>
