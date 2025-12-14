<?php require_once('../Connections/connvbsa.php'); 
error_reporting(0);
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

$rank = "-1";
if (isset($_GET['rank'])) {
  $rank = $_GET['rank'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_RPALL = "SELECT ranknum, memb_id, FirstName, LastName, total_weekly_rp, date_format(last_update,'%D %b %Y') AS last_update FROM rank_S_open_weekly  LEFT JOIN members ON MemberID = memb_id  WHERE memb_id = '$rank'";
$RPALL = mysql_query($query_RPALL, $connvbsa) or die(mysql_error());
$row_RPALL = mysql_fetch_assoc($RPALL);
$totalRows_RPALL = mysql_num_rows($RPALL);

$colname_personal_brks = "-1";
if (isset($_GET['rank'])) {
  $colname_personal_brks = $_GET['rank'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_personal_brks = sprintf("SELECT member_ID_brks, FirstName, LastName, grade, brk, brk_type, finals_brk, season, date_format( recvd, '%%b %%e, %%Y') AS 'Reported on' FROM breaks, members WHERE member_ID_brks=MemberID AND (YEAR(breaks.recvd) > YEAR( CURDATE( ))-3) AND member_ID_brks=%s AND breaks.brk_type='Snooker' ORDER BY recvd DESC", GetSQLValueString($colname_personal_brks, "int"));
$personal_brks = mysql_query($query_personal_brks, $connvbsa) or die(mysql_error());
$row_personal_brks = mysql_fetch_assoc($personal_brks);
$totalRows_personal_brks = mysql_num_rows($personal_brks);

//echo($query_personal_brks . "<br>");

$colname_high_brk = "-1";
if (isset($_GET['rank'])) {
  $colname_high_brk = $_GET['rank'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_high_brk = sprintf("SELECT member_ID_brks, MAX(brk) FROM breaks WHERE (YEAR(breaks.recvd) >  YEAR( CURDATE( ))-3) AND member_ID_brks=%s AND breaks.brk_type='Snooker'", GetSQLValueString($colname_high_brk, "int"));
$high_brk = mysql_query($query_high_brk, $connvbsa) or die(mysql_error());
$row_high_brk = mysql_fetch_assoc($high_brk);
$totalRows_high_brk = mysql_num_rows($high_brk);

mysql_select_db($database_connvbsa, $connvbsa);
$query_RP3yr = "SELECT `team_id`, `team_grade`, `total_RP`, `scr_season`, `average_position`, `current_year_scrs` FROM `scrs`  WHERE `MemberID`='$rank' AND current_year_scrs >YEAR(CURDATE( ))-3 AND `game_type`='Snooker' ORDER BY `current_year_scrs` DESC";
$RP3yr = mysql_query($query_RP3yr, $connvbsa) or die(mysql_error());
$row_RP3yr = mysql_fetch_assoc($RP3yr);
$totalRows_RP3yr = mysql_num_rows($RP3yr);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S1curr = "SELECT total_RP  FROM scrs  WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))  AND game_type='Snooker' AND scr_season='S1' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
//$query_S1curr = "Select SUM(total_RP) as Totals  FROM scrs  WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))  AND game_type='Snooker' AND scr_season='S1' GROUP BY MemberID";
$S1curr = mysql_query($query_S1curr, $connvbsa) or die(mysql_error());
$row_S1curr = mysql_fetch_assoc($S1curr);
$totalRows_S1curr = mysql_num_rows($S1curr);
//echo("RP " . $query_S1curr . "<br>");
mysql_select_db($database_connvbsa, $connvbsa);
$query_S2curr = "SELECT total_RP FROM scrs WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))  AND game_type='Snooker' AND scr_season='S2' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
//$query_S2curr = "Select SUM(total_RP) as Totals  FROM scrs  WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))  AND game_type='Snooker' AND scr_season='S2' GROUP BY MemberID";
$S2curr = mysql_query($query_S2curr, $connvbsa) or die(mysql_error());
$row_S2curr = mysql_fetch_assoc($S2curr);
$totalRows_S2curr = mysql_num_rows($S2curr);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S1_1year = "SELECT total_RP  FROM scrs  WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-1 AND game_type='Snooker' AND scr_season='S1' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
//$query_S1_1year = "Select SUM(total_RP) as Totals  FROM scrs  WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( )) -1 AND game_type='Snooker' AND scr_season='S1' GROUP BY MemberID";
$S1_1year = mysql_query($query_S1_1year, $connvbsa) or die(mysql_error());
$row_S1_1year = mysql_fetch_assoc($S1_1year);
$totalRows_S1_1year = mysql_num_rows($S1_1year);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S2_1year = "SELECT total_RP FROM scrs WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-1 AND game_type='Snooker' AND scr_season='S2' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
//$query_S2_1year = "Select SUM(total_RP) as Totals  FROM scrs  WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( )) -1 AND game_type='Snooker' AND scr_season='S2' GROUP BY MemberID";
$S2_1year = mysql_query($query_S2_1year, $connvbsa) or die(mysql_error());
$row_S2_1year = mysql_fetch_assoc($S2_1year);
$totalRows_S2_1year = mysql_num_rows($S2_1year);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S1_2year = "SELECT total_RP FROM scrs WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-2 AND game_type='Snooker' AND scr_season='S1' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
//$query_S1_2year = "Select SUM(total_RP) as Totals  FROM scrs  WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( )) -2 AND game_type='Snooker' AND scr_season='S1' GROUP BY MemberID";
$S1_2year = mysql_query($query_S1_2year, $connvbsa) or die(mysql_error());
$row_S1_2year = mysql_fetch_assoc($S1_2year);
$totalRows_S1_2year = mysql_num_rows($S1_2year);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S2_2year = "SELECT total_RP FROM scrs WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-2 AND game_type='Snooker' AND scr_season='S2' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
//$query_S2_2year = "Select SUM(total_RP) as Totals  FROM scrs  WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( )) -2 AND game_type='Snooker' AND scr_season='S2' GROUP BY MemberID";
$S2_2year = mysql_query($query_S2_2year, $connvbsa) or die(mysql_error());
$row_S2_2year = mysql_fetch_assoc($S2_2year);
$totalRows_S2_2year = mysql_num_rows($S2_2year);

$totalRP = ROUND(round($row_S1curr['total_RP']) + round($row_S2curr['total_RP']) + round($row_S1_1year['total_RP']*65/100) + round($row_S2_1year['total_RP']*65/100) + round($row_S1_2year['total_RP']*35/100) + round($row_S2_2year['total_RP']*35/100));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Victorian Rankings</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript">
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
  </script>
</head>
<body id="vbsa">
    
    <!-- Include Google Tracking -->
<?php include_once("../includes/analyticstracking.php") ?>

<div class="container"> 

    <!-- Include header -->
<?php include '../includes/header.php';?>
    
    <!-- Include navigation -->
<?php include '../includes/nav_vbsa.php';?>

</div><!--End Bootstrap Container--> 

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 

  <!--Content--> 
  
  
<div class="row"> 
  <div class="Page_heading_container">
 		<div class="page_title">Victorian Rankings - Pennant & Willis Snooker, Individual detail</div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 
<div class="table-condensed" style="padding-bottom:10px">
      <table align="center">
          <tr>
            <td style><input type="button" class="btn-xs btn-default btn-responsive center-block" role="button" value="Return to previous page" onclick="window.history.go(-1); return false;"/></td>
      </table>
 </div>

<div class="row"> 
  		 <div class="text_box text-justify">
         Player detail will update every time weekly competition is played. (Current rankings will not appear until play starts)
  		</div>
  </div>
  
  <!-- Search Bar -->
  <div class="table-responsive center-block" style="max-width:250px" > 
  <table class="table">
  <tr>
    <td><form id="RP_sname" name="RP_sname" method="get" action="../Rankings/rankings_weekly_name_srch_res.php">
        <label class="glyphicon glyphicon-search" style="margin-right:3px"></label>
        <input type="text" name="RP_sname" id="RP_sname" placeholder="Search by Surname" />
        </form>
    </td>
  </tr>
  </table>
</div>
    
 <div class="table-responsive center-block" style="max-width:600px" > 
<table class="table">
        <tr>
          <td colspan="2" >Rankings detail for: <?php echo $row_RPALL['FirstName']; ?> <?php echo $row_RPALL['LastName']; ?></td>
    </tr>
        <tr>
          <td>Currently Ranked: <span class="red_bold"><strong><?php echo $row_RPALL['ranknum']; ?></strong></span></td>
          <td>Total Ranking Points: <span class="red_bold"><strong><?php echo Round($totalRP); ?></strong></span></td>
        </tr>
   </table>
</div>



 <div class="table-responsive center-block" style="max-width:700px" > 
<table class="table">
        <tr>
          <td colspan="7"  class="text-center italic">All  Ranking Points for <?php echo date("Y", strtotime(date("Y-m-d")." -2 year")); ?>, <?php echo date("Y", strtotime(date("Y-m-d")." -1 year")); ?> &amp; <?php echo date("Y", strtotime(date("Y-m-d"))); ?></td>
        </tr>
        <tr>
          <td class="text-center">Team ID</td>
          <td class="text-center">Grade</td>
          <td class="text-center">Total RP</td>
          <td class="text-center">Season</td>
          <td class="text-center">Average position</td>
          <td class="text-center">Year</td>
        </tr>
        <?php do { ?>
        <tr>
          <td class="text-center"><?php echo $row_RP3yr['team_id']; ?></td>
          <td class="text-center"><?php echo $row_RP3yr['team_grade']; ?></td>
          <td class="text-center"><?php echo $row_RP3yr['total_RP']; ?></td>
          <td class="text-center"><?php echo $row_RP3yr['scr_season']; ?></td>
          <td class="text-center"><?php echo $row_RP3yr['average_position']; ?></td>
          <td class="text-center"><?php echo $row_RP3yr['current_year_scrs']; ?></td>
        </tr>
        <?php } while ($row_RP3yr = mysql_fetch_assoc($RP3yr)); ?>
      </table>
</div>


<div class="table-responsive center-block" style="max-width:600px" > 
	<table class="table">
        <tr>
          <td colspan="6" class="text-center italic">Calculation of Ranking Points (to the nearest whole number)</td>
      </tr>
        <tr>
          <td><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d"))); ?></span> - S1 Best total Ranking Points</td>
          <td class="text-center"><?php echo $row_S1curr['total_RP']; ?></td>
          <td class="text-center">x</td>
          <td class="text-center">100%</td>
          <td class="text-center">=</td>
          <td class="text-center"><?php echo $row_S1curr['total_RP']; ?></td>
        </tr>
        <tr>
          <td><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d"))); ?></span> - S2 Best total Ranking Points</td>
          <td class="text-center"><?php echo $row_S2curr['total_RP']; ?></td>
          <td class="text-center">x</td>
          <td class="text-center">100%</td>
          <td class="text-center">=</td>
          <td class="text-center"><?php echo $row_S2curr['total_RP']; ?></td>
          </tr>
        <tr>
          <td><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d")." -1 year")); ?></span> - S1 Best total Ranking Points </td>
          <td class="text-center"><?php echo $row_S1_1year['total_RP']; ?></td>
          <td class="text-center">x</td>
          <td class="text-center">65%</td>
          <td class="text-center">=</td>
          <td class="text-center"><?php if(isset($row_S1_1year['total_RP'])) echo round($row_S1_1year['total_RP']*65/100); else echo "0"; ?></td>
          </tr>
        <tr>
          <td><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d")." -1 year")); ?></span> - S2 Best total Ranking Points </td>
          <td class="text-center"><?php echo $row_S2_1year['total_RP']; ?></td>
          <td class="text-center">x</td>
          <td class="text-center">65%</td>
          <td class="text-center">=</td>
          <td class="text-center"><?php if(isset($row_S2_1year['total_RP'])) echo round($row_S2_1year['total_RP']*65/100); else echo "0"; ?></td>
          </tr>
        <tr>
          <td><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d")." -2 year")); ?></span> - S1 Best total Ranking Points </td>
          <td class="text-center"><?php echo $row_S1_2year['total_RP']; ?></td>
          <td class="text-center">x</td>
          <td class="text-center">35%</td>
          <td class="text-center">=</td>
          <td class="text-center"><?php if(isset($row_S1_2year['total_RP'])) echo round($row_S1_2year['total_RP']*35/100); else echo "0"; ?></td>
          </tr>
        <tr>
          <td><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d")." -2 year")); ?></span> -  S2 Best total Ranking Points</td>
          <td class="text-center"><?php echo $row_S2_2year['total_RP']; ?></td>
          <td class="text-center">x</td>
          <td class="text-center">35%</td>
          <td class="text-center">=</td>
          <td class="text-center"><?php if(isset($row_S2_2year['total_RP'])) echo round($row_S2_2year['total_RP']*35/100); else echo "0"; ?></td>
        </tr>
        <tr>
          <td colspan="6" class="text-right">Total &nbsp;&nbsp;&nbsp;<strong><?php echo Round($totalRP); ?></strong></td>
        </tr>
      </table>
</div>


<div class="table-responsive center-block" style="max-width:600px" > 
<table class="table">
        <tr>
          <td colspan="6" class="text-center italic">Breaks made in <?php echo date("Y", strtotime(date("Y-m-d")." -2 year")); ?>, <?php echo date("Y", strtotime(date("Y-m-d")." -1 year")); ?> & <?php echo date("Y", strtotime(date("Y-m-d"))); ?> - High Break <?php echo $row_high_brk['MAX(brk)']; ?></td>
        </tr>
        <tr>
          <td align="left">Grade</td>
          <td class="text-center">&nbsp;</td>
          <td class="text-center">Break</td>
          <td class="text-center">Finals?</td>
          <td class="text-center">Season</td>
          <td class="text-center">Reported </td>
        </tr>
        <?php do { 
            if($row_personal_brks['brk'] > 0)
            {
        ?>
        <tr>
          <td align="left"><?php echo $row_personal_brks['grade']; ?></td>
          <td class="text-center">&nbsp;</td>
          <td class="text-center"><?php echo $row_personal_brks['brk']; ?></td>
          <td class="text-center"><?php echo $row_personal_brks['finals_brk']; ?></td>
          <td class="text-center"><?php echo $row_personal_brks['season']; ?></td>
          <td class="text-center"><?php echo $row_personal_brks['Reported on']; ?></td>
        </tr>
        <?php 
            }
          }
       while ($row_personal_brks = mysql_fetch_assoc($personal_brks)); ?>
      </table>
</div>


</div>  <!-- close containing wrapper --> 
</body>
</html>
<?php

?>
