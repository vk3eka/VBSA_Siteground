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

$comptype = "-1";
if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}
//echo($comptype . "<br>");

//$year = "-1";
$year = date("Y");
//if (isset($_GET['year'])) {
//  $year = $_GET['year'];
//}
//echo($year . "<br>");

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$team_id = "-1";
if (isset($_GET['team_id'])) {
  $team_id = $_GET['team_id'];
}
//$team_id = 1289;

mysql_select_db($database_connvbsa, $connvbsa);

$query_team_name = "SELECT Team_entries.team_grade, Team_entries.team_name, Team_entries.team_id, comptype FROM Team_entries WHERE Team_entries.team_id='$team_id'";
$team_name = mysql_query($query_team_name, $connvbsa) or die(mysql_error());
$row_team_name = mysql_fetch_assoc($team_name);
$totalRows_team_name = mysql_num_rows($team_name);
$grade = $row_team_name['team_grade'];

//mysql_select_db($database_connvbsa, $connvbsa);
$query_ladd_det = "SELECT members.FirstName, members.LastName,  SUM(totplayed_curr + totplayed_prev + totplaybill_curr + totplaybill_prev) AS playing_memb, LifeMember, paid_memb, memb_by, pts_won, count_played, ccc_player, referee, Deceased, scrs.byes_to_date, scrs.r01s,scrs.r02s, scrs.r03s, scrs.r04s, scrs.r05s, scrs.r06s,  scrs.r07s, scrs.r08s, scrs.r09s,  scrs.r10s, scrs.r11s, scrs.r12s, scrs.r13s, scrs.r14s, scrs.r15s, scrs.r16s,  scrs.r17s, scrs.r18s, scrs.EF1, scrs.EF2, scrs.SF1, scrs.SF2, scrs.PF, scrs.GF, scrs.team_grade, scrs.team_id, game_type  FROM scrs LEFT JOIN members ON members.MemberID = scrs.MemberID WHERE scrs.team_id='$team_id' GROUP BY scrs.scrsID ORDER BY members.FirstName, members.LastName";
//echo($query_ladd_det . "<br>");
$ladd_det = mysql_query($query_ladd_det, $connvbsa) or die(mysql_error());
$row_ladd_det = mysql_fetch_assoc($ladd_det);
$totalRows_ladd_det = mysql_num_rows($ladd_det);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_scr = "SELECT SUM(IFNULL(scrs.r01s,0)) AS RO1S, SUM(IFNULL(scrs.r02s,0)) AS RO2S, SUM(IFNULL(scrs.r03s,0)) AS RO3S, SUM(IFNULL(scrs.r04s,0)) AS RO4S,  SUM(IFNULL(scrs.r05s,0)) AS RO5S, SUM(IFNULL(scrs.r06s,0)) AS RO6S, SUM(IFNULL(scrs.r07s,0)) AS RO7S, SUM(IFNULL(scrs.r08s,0)) AS RO8S, SUM(IFNULL(scrs.r09s,0)) AS RO9S, SUM(IFNULL(scrs.r10s,0)) AS R10S, SUM(IFNULL(scrs.r11s,0)) AS R11S, SUM(IFNULL(scrs.r12s,0)) AS R12S, SUM(IFNULL(scrs.r13s,0)) AS R13S, SUM(IFNULL(scrs.r14s,0)) AS R14S, SUM(IFNULL(scrs.r15s,0)) AS R15S, SUM(IFNULL(scrs.r16s,0)) AS R16S, SUM(IFNULL(scrs.r17s,0)) AS R17S, SUM(IFNULL(scrs.r18s,0)) AS R18S, MemberID, Team_entries.team_id, scrs.team_id, Team_entries.EF1tot, Team_entries.EF2tot, Team_entries.SF1tot, Team_entries.SF2tot, Team_entries.PFtot, Team_entries.GFtot FROM scrs, Team_entries WHERE Team_entries.team_id=scrs.team_id AND Team_entries.team_id='$team_id'";
//echo($query_scr . "<br>");
$scr = mysql_query($query_scr, $connvbsa) or die(mysql_error());
$row_scr = mysql_fetch_assoc($scr);
$totalRows_scr = mysql_num_rows($scr);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_team_brks = "SELECT breaks.Break_ID, breaks.member_ID_brks, breaks.brk, breaks.grade, breaks.brk_team_id, breaks.finals_brk, breaks.recvd, breaks.brk_type, breaks.season, FirstName, LastName FROM breaks LEFT JOIN members ON member_ID_brks=MemberID WHERE brk_team_id = '$team_id' and brk > 0 ORDER BY brk DESC";
$team_brks = mysql_query($query_team_brks, $connvbsa) or die(mysql_error());
$row_team_brks = mysql_fetch_assoc($team_brks);
$totalRows_team_brks = mysql_num_rows($team_brks);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_individual = "SELECT comptype, scrs.MemberID, scrs.team_grade, scrs.team_id, FirstName, LastName, scr_season, count_played,   pts_won, percent_won, average_position,  r01s, r02s, r03s, r04s, r05s, r06s,  r07s, scrs.r08s, scrs.r09s, scrs.r10s, r11s,  r12s, r13s, r14s, r15s, r16s,  r17s, r18s,   r01pos, r02pos, r03pos, r04pos, r05pos, r06pos, r07pos, r08pos, r09pos, r10pos, r11pos,  r12pos, r13pos, r14pos, r15pos, r16pos, r17pos, r18pos, EF1, EF2, SF1, SF2, scrs.GF, EF1_pos, EF2_pos, SF1_pos, SF2_pos, scrs.GF_pos, current_year_scrs , rank_S_open_tourn.ranknum AS t_rank, rank_S_open_weekly.ranknum AS w_rank, captain_scrs FROM scrs   LEFT JOIN members ON scrs.MemberID=members.MemberID    LEFT JOIN rank_S_open_tourn ON rank_S_open_tourn.memb_id = scrs.MemberID LEFT JOIN rank_S_open_weekly ON rank_S_open_weekly.memb_id = scrs.MemberID LEFT JOIN Team_entries ON scrs.team_id = Team_entries.team_id WHERE scrs.MemberID != 1 AND scrs.team_id='$team_id' GROUP BY scrsID ORDER BY FirstName, LastName";
$individual = mysql_query($query_individual, $connvbsa) or die(mysql_error());
$row_individual = mysql_fetch_assoc($individual);
$totalRows_individual = mysql_num_rows($individual);

// get number of byes

$sql_byes = "Select 
  SUM((fix1home = 'Bye') +
  (fix2home = 'Bye') +
  (fix3home = 'Bye') +
  (fix4home = 'Bye') +
  (fix5home = 'Bye') +
  (fix6home = 'Bye') +
  (fix7home = 'Bye') +
  (fix1away = 'Bye') +
  (fix2away = 'Bye') +
  (fix3away = 'Bye') +
  (fix4away = 'Bye') +
  (fix5away = 'Bye') +
  (fix6away = 'Bye') +
  (fix7away = 'Bye'))
  as byes FROM vbsa3364_vbsa2.tbl_fixtures Where season = '$season' and year = $year and team_grade = '$grade'";
$byes = mysql_query($sql_byes, $connvbsa) or die(mysql_error());
$row_byes = mysql_fetch_assoc($byes);
$total_byes = $row_byes['byes'];
//echo("Total byes " . $total_byes . "<br>");

$sql_teams = "Select * FROM vbsa3364_vbsa2.Team_entries Where team_season = '$season' and team_cal_year = $year and team_grade = '$grade' and team_name != 'Bye'";
$teams = mysql_query($sql_teams, $connvbsa) or die(mysql_error());
//$row_teams = mysql_fetch_assoc($teams);
$no_of_teams = mysql_num_rows($teams);
//echo("Number of teams " . $no_of_teams . "<br>");

if($total_byes != '') // remove a divide by zero error
{
  $no_of_byes = ($total_byes/$no_of_teams);
}
else
{
  $no_of_byes = 0;
}


//echo("Number of byes " . $no_of_byes . "<br>");


// get last round played

$query_total_rounds = "Select no_of_rounds, grade, season, fix_cal_year, RP FROM Team_grade WHERE grade = '$grade' and season = '$season' and fix_cal_year = $year and current = 'Yes'";
//echo($query_total_rounds . "<br>");
$total_rounds = mysql_query($query_total_rounds, $connvbsa) or die(mysql_error());
$row_total_rounds = mysql_fetch_assoc($total_rounds);
$total_rounds_available = ($row_total_rounds['no_of_rounds']);
if($comptype == 'Snooker')
{
  $total_rounds_available = ($total_rounds_available-2); // two finals
  $RP = $row_total_rounds['RP'];
  $rp_caption = "Allocated Ranking Point for this team is " . $RP . ". Multiply by player’s game points (Gm Pts) i.e. frames won, to get Ranking Points for this player for this team.<br> For Total Ranking Points, go to <a href = 'https://vbsa.org.au/PreviousRank/rankings_index.php'> here</a>.";
}
else if($comptype == 'Billiards')
{
  $total_rounds_available = ($total_rounds_available-3); // three finals
}
//echo("Total Rounds " . $total_rounds_available . "<br>");
//echo("Rounds " . (($total_rounds_available-$no_of_byes)*0.5) . "<br>");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Scores</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
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
 		<div class="page_title">
        Statistics for <?php echo $row_team_name['team_name']; ?> (<?php echo $row_team_name['team_grade']; ?>) Team Number: <?php echo $row_team_name['team_id'];  echo " (" . $row_team_name['comptype'] . ")"; ?>
        </div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 
<!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>

  <div class="table-responsive center-block" style="max-width:1100px"> <!-- class table-responsive -->
    <div style="color: black; font-weight: margin-bottom: 10px;">
      <ul style="list-style-type: none; padding-left: 15px; margin: 0;">
       <li><b>Memb</b> - See criteria in the <a href="https://vbsa.org.au/Admin_DB_VBSA/membership_application_online.php" >“online membership form”</a></li>
       <li><b>Form</b> - Membership form lodged.</li>
       <li><b>Gm Pts</b> - Game Points.</li>
       <li><b>Finals</b> - Qualified for Finals.</li>
       <br>
       <?= $rp_caption ?>
      </ul>
    </div>
    <?php
    if($comptype == 'Snooker')
    {
    ?>
    <table class="table text-center table-striped">
    <thead> 
      <tr>
      <br>
        <th colspan="25" class="text-left italic">Team Stats</th>
      </tr>
      <tr>
        <th class="text-left">Name</th>
        <th>Memb </th>
        <th>Form</th>
        <th nowrap>Gm Pts</th>
        <th>Finals</th>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
        <th>6</th>
        <th>7</th>
        <th>8</th>
        <th>9</th>
        <th>10</th>
        <th>11</th>
        <th>12</th>
        <th>13</th>
        <th>14</th>
        <th>15</th>
        <th>16</th>
        <th>17</th>
        <th>18</th>
        <th>SF1</th>
        <th>SF2</th>
        <th>GF</th>
      </tr>
      </thead>
    <tbody><?php do { ?>
      <tr>
        <td nowrap="nowrap" class="text-left"><?php echo $row_ladd_det['FirstName']; ?> <?php echo $row_ladd_det['LastName']; ?></td>
        <td>
        <?php
        /*
        if(isset($row_ladd_det['memb_by']))
        {
        echo "Yes";
        }
        else
        {
        echo "<font color=red>No</font>";
        }
        */    
        if($row_ladd_det['count_played'] > 0
          /*($row_ladd_det['paid_memb'] > 0 OR
          $row_ladd_det['LifeMember'] == 1 OR
          $row_ladd_det['ccc_player'] == 1 OR
          $row_ladd_det['playing_memb'] > 0 OR
          ($row_ladd_det['totplayed_curr']+$row_ladd_det['totplaybill_curr'])>0 OR 
          $row_ladd_det['referee'] == 1) AND 
          ($row_ladd_det['MemberID'] != 1 AND
          $row_ladd_det['MemberID'] != 100 AND
          $row_ladd_det['MemberID'] != 1000 AND
          $row_ladd_det['MemberID'] != 1500 AND
          $row_ladd_det['Deceased'] != 1)*/
        )
    		//if($row_ladd_det['LifeMember']==1 OR $row_ladd_det['paid_memb']>0 OR $row_ladd_det['playing_memb']>=4)
    		{
    			echo "Yes";
    		}
        else
        {
          echo "<font color=red>No</font>";
        }
        
        echo "</td>";
        echo "<td>";
        if(isset($row_ladd_det['memb_by'])) echo "Yes"; else echo "<font color=red>No</font>";
        echo "</td>";
        echo("<td>" . $row_ladd_det['pts_won'] . "</td>");

        //if(($row_ladd_det['count_played']) >= ($total_rounds_available-$row_ladd_det['byes_to_date'])*0.5)

        //if((($row_ladd_det['count_played']) >= ($total_rounds_available-$no_of_byes)*0.5))
        if($row_ladd_det['count_played'] >= 6)
        {
        echo "<td>Yes</td>";
        }
        else 
        {   
        echo "<td>No</td>";
        }
        ?>
        <td><?php echo $row_ladd_det['r01s']; ?></td>
        <td><?php echo $row_ladd_det['r02s']; ?></td>
        <td><?php echo $row_ladd_det['r03s']; ?></td>
        <td><?php echo $row_ladd_det['r04s']; ?></td>
        <td><?php echo $row_ladd_det['r05s']; ?></td>
        <td><?php echo $row_ladd_det['r06s']; ?></td>
        <td><?php echo $row_ladd_det['r07s']; ?></td>
        <td><?php echo $row_ladd_det['r08s']; ?></td>
        <td><?php echo $row_ladd_det['r09s']; ?></td>
        <td><?php echo $row_ladd_det['r10s']; ?></td>
        <td><?php echo $row_ladd_det['r11s']; ?></td>
        <td><?php echo $row_ladd_det['r12s']; ?></td>
        <td><?php echo $row_ladd_det['r13s']; ?></td>
        <td><?php echo $row_ladd_det['r14s']; ?></td>
        <td><?php echo $row_ladd_det['r15s']; ?></td>
        <td><?php echo $row_ladd_det['r16s']; ?></td>
        <td><?php echo $row_ladd_det['r17s']; ?></td>
        <td><?php echo $row_ladd_det['r18s']; ?></td>
        <td><?php echo $row_ladd_det['SF1']; ?></td>
        <td><?php echo $row_ladd_det['SF2']; ?></td>
        <td><?php echo $row_ladd_det['GF']; ?></td>
      </tr>
      <?php } while ($row_ladd_det = mysql_fetch_assoc($ladd_det)); ?>
      <tr>
        <td colspan="5" class="text-right italic">Round Total Score</td>
        <td><?php echo $row_scr['RO1S']; ?></td>
        <td><?php echo $row_scr['RO2S']; ?></td>
        <td><?php echo $row_scr['RO3S']; ?></td>
        <td><?php echo $row_scr['RO4S']; ?></td>
        <td><?php echo $row_scr['RO5S']; ?></td>
        <td><?php echo $row_scr['RO6S']; ?></td>
        <td><?php echo $row_scr['RO7S']; ?></td>
        <td><?php echo $row_scr['RO8S']; ?></td>
        <td><?php echo $row_scr['RO9S']; ?></td>
        <td><?php echo $row_scr['R10S']; ?></td>
        <td><?php echo $row_scr['R11S']; ?></td>
        <td><?php echo $row_scr['R12S']; ?></td>
        <td><?php echo $row_scr['R13S']; ?></td>
        <td><?php echo $row_scr['R14S']; ?></td>
        <td><?php echo $row_scr['R15S']; ?></td>
        <td><?php echo $row_scr['R16S']; ?></td>
        <td><?php echo $row_scr['R17S']; ?></td>
        <td><?php echo $row_scr['R18S']; ?></td>
        <td><?php echo $row_scr['SF1tot']; ?></td>
        <td><?php echo $row_scr['SF2tot']; ?></td>
        <td><?php echo $row_scr['GFtot']; ?></td>
      </tr>
    </tbody>
</table>
<?php
}
else if($comptype == 'Billiards')
{
?>
    <table class="table text-center table-striped">
    <thead> 
      <tr>
        <th colspan="25" class="text-left italic">Team Stats</th>
      </tr>
      <tr>
        <th class="text-left">Name</th>
        <th>Memb </th>
        <th>Form</th>
        <th>Qual</th>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
        <th>6</th>
        <th>7</th>
        <th>8</th>
        <th>9</th>
        <th>10</th>
        <th>11</th>
        <th>12</th>
        <th>13</th>
        <th>14</th>
        <th>15</th>
        <th>16</th>
        <th>17</th>
        <th>18</th>
        <th>EF1</th>
        <th>EF2</th>
        <th>SF1</th>
        <th>SF2</th>
        <th>GF</th>
      </tr>
    </thead>
    <tbody><?php do { ?>
      <tr>
        <td nowrap="nowrap" class="text-left"><?php echo $row_ladd_det['FirstName']; ?> <?php echo $row_ladd_det['LastName']; ?></td>
        <td>
        <?php
        if(
          ($row_ladd_det['paid_memb'] > 0 OR
          $row_ladd_det['LifeMember'] == 1 OR
          $row_ladd_det['ccc_player'] == 1 OR
          $row_ladd_det['playing_memb'] > 0 OR
          $row_ladd_det['referee'] == 1) AND 
          ($row_ladd_det['MemberID'] != 1 AND
          $row_ladd_det['MemberID'] != 100 AND
          $row_ladd_det['MemberID'] != 1000 AND
          $row_ladd_det['MemberID'] != 1500 AND
          $row_ladd_det['Deceased'] != 1)
        )
        {
          echo "Yes";
        }
        else
        {
          echo "No";
        }
        echo "</td>";
        echo "<td>";
        if(isset($row_ladd_det['memb_by'])) echo "<font color=green>Yes</font>"; else echo "<font color=red>No</font>";
        echo "</td>";
        //if(($row_ladd_det['count_played']) >= ($total_rounds_available-$no_of_byes)*0.5)
        //if(($row_ladd_det['count_played']-$no_of_byes) >= ($total_rounds_available*0.5))
        if($row_ladd_det['count_played'] >= 4)
        {
        echo "<td>Yes</td>";
        }
        else 
        {   
        echo "<td>No</td>";
        }
        ?>
        <td><?php if($row_ladd_det['r01s'] == '') echo ""; else echo ($row_ladd_det['r01s']/2); ?></td>
        <td><?php if($row_ladd_det['r02s'] == '') echo ""; else echo ($row_ladd_det['r02s']/2); ?></td>
        <td><?php if($row_ladd_det['r03s'] == '') echo ""; else echo ($row_ladd_det['r03s']/2); ?></td>
        <td><?php if($row_ladd_det['r04s'] == '') echo ""; else echo ($row_ladd_det['r04s']/2); ?></td>
        <td><?php if($row_ladd_det['r05s'] == '') echo ""; else echo ($row_ladd_det['r05s']/2); ?></td>
        <td><?php if($row_ladd_det['r06s'] == '') echo ""; else echo ($row_ladd_det['r06s']/2); ?></td>
        <td><?php if($row_ladd_det['r07s'] == '') echo ""; else echo ($row_ladd_det['r07s']/2); ?></td>
        <td><?php if($row_ladd_det['r08s'] == '') echo ""; else echo ($row_ladd_det['r08s']/2); ?></td>
        <td><?php if($row_ladd_det['r09s'] == '') echo ""; else echo ($row_ladd_det['r09s']/2); ?></td>
        <td><?php if($row_ladd_det['r10s'] == '') echo ""; else echo ($row_ladd_det['r10s']/2); ?></td>
        <td><?php if($row_ladd_det['r11s'] == '') echo ""; else echo ($row_ladd_det['r11s']/2); ?></td>
        <td><?php if($row_ladd_det['r12s'] == '') echo ""; else echo ($row_ladd_det['r12s']/2); ?></td>
        <td><?php if($row_ladd_det['r13s'] == '') echo ""; else echo ($row_ladd_det['r13s']/2); ?></td>
        <td><?php if($row_ladd_det['r14s'] == '') echo ""; else echo ($row_ladd_det['r14s']/2); ?></td>
        <td><?php if($row_ladd_det['r15s'] == '') echo ""; else echo ($row_ladd_det['r15s']/2); ?></td>
        <td><?php if($row_ladd_det['r16s'] == '') echo ""; else echo ($row_ladd_det['r16s']/2); ?></td>
        <td><?php if($row_ladd_det['r17s'] == '') echo ""; else echo ($row_ladd_det['r17s']/2); ?></td>
        <td><?php if($row_ladd_det['r18s'] == '') echo ""; else echo ($row_ladd_det['r18s']/2); ?></td>
        <td><?php if($row_ladd_det['EF1'] == '') echo ""; else echo ($row_ladd_det['EF1']/2); ?></td>
        <td><?php if($row_ladd_det['EF2'] == '') echo ""; else echo ($row_ladd_det['EF2']/2); ?></td>
        <td><?php if($row_ladd_det['SF1'] == '') echo ""; else echo ($row_ladd_det['SF1']/2); ?></td>
        <td><?php if($row_ladd_det['SF2'] == '') echo ""; else echo ($row_ladd_det['SF2']/2); ?></td>
        <td><?php if($row_ladd_det['GF'] == '') echo ""; else echo ($row_ladd_det['GF']/2); ?></td>
      </tr>
      <?php } while ($row_ladd_det = mysql_fetch_assoc($ladd_det)); ?>
      <tr>
        <td colspan="4" class="text-right italic">Round Total Score</td>
        <td><?php echo ($row_scr['RO1S']/2); ?></td>
        <td><?php echo ($row_scr['RO2S']/2); ?></td>
        <td><?php echo ($row_scr['RO3S']/2); ?></td>
        <td><?php echo ($row_scr['RO4S']/2); ?></td>
        <td><?php echo ($row_scr['RO5S']/2); ?></td>
        <td><?php echo ($row_scr['RO6S']/2); ?></td>
        <td><?php echo ($row_scr['RO7S']/2); ?></td>
        <td><?php echo ($row_scr['RO8S']/2); ?></td>
        <td><?php echo ($row_scr['RO9S']/2); ?></td>
        <td><?php echo ($row_scr['R10S']/2); ?></td>
        <td><?php echo ($row_scr['R11S']/2); ?></td>
        <td><?php echo ($row_scr['R12S']/2); ?></td>
        <td><?php echo ($row_scr['R13S']/2); ?></td>
        <td><?php echo ($row_scr['R14S']/2); ?></td>
        <td><?php echo ($row_scr['R15S']/2); ?></td>
        <td><?php echo ($row_scr['R16S']/2); ?></td>
        <td><?php echo ($row_scr['R17S']/2); ?></td>
        <td><?php echo ($row_scr['R18S']/2); ?></td>
        <td><?php echo ($row_scr['EF1tot']/2); ?></td>
        <td><?php echo ($row_scr['EF2tot']/2); ?></td>
        <td><?php echo ($row_scr['SF1tot']/2); ?></td>
        <td><?php echo ($row_scr['SF2tot']/2); ?></td>
        <td><?php echo ($row_scr['GFtot']/2); ?></td>
      </tr>
    </tbody>
</table>
<?php
}
?>
</div> 
<div class="table-responsive center-block" style="max-width:1100px"> <!-- class table-responsive -->
  <p class="italic" style="padding-left:5px">Individual Stats</p>
    <table class="table table-striped">
    <tbody>
	<?php do { ?>
        <tr>
        <td colspan="22" nowrap="nowrap">
		  <strong><?php echo $row_individual['FirstName']; ?> <?php echo $row_individual['LastName']; ?></strong>&nbsp;
          <?php if($row_individual['captain_scrs']>0) echo " (Capt)"; else echo ""; ?>
          &nbsp;|&nbsp;Matches Played: 
		      <?php echo $row_individual['count_played']; ?>&nbsp;&nbsp;|&nbsp;
          <?php if($comptype=='Billiards') echo "Games Won: "; else echo "Frames Won: "; ?>
          <?php if($comptype=='Billiards') echo ($row_individual['pts_won']/2); else echo $row_individual['pts_won']; ?>&nbsp;&nbsp;|&nbsp;Avg Pos:
          <?php echo $row_individual['average_position']; ?>&nbsp;&nbsp;|&nbsp;Win%: <?php echo $row_individual['percent_won']; ?>&nbsp;&nbsp;|&nbsp;Qual:
          <?php //if(($row_individual['count_played']) >= ($total_rounds_available-$no_of_byes)*0.5) echo "Yes"; elseif($row_individual['MemberID']=="1") echo "na"; else echo "No"; ?>
          <?php
          if($comptype == 'Billiards')
          {
           if($row_individual['count_played'] >= 4) echo "Yes"; elseif($row_individual['MemberID']=="1") echo "na"; else echo "No";
          }
           else if($comptype == 'Snooker')
          {
            if($row_individual['count_played'] >= 6) echo "Yes"; elseif($row_individual['MemberID']=="1") echo "na"; else echo "No";
          } 
          ?>&nbsp;&nbsp;|&nbsp;
          <?php if(isset($row_individual['w_rank'])) echo "Weekly ranking: ".$row_individual['w_rank']; ?>&nbsp;&nbsp;|&nbsp;</td>
        </tr>
        <?php 
        if($comptype == 'Billiards')
        {
        ?>
        <tr>
          <td nowrap="nowrap" class="text-center italic">Rd</td>
          <td nowrap="nowrap" class="text-center">1</td>
          <td nowrap="nowrap" class="text-center">2</td>
          <td nowrap="nowrap" class="text-center">3</td>
          <td nowrap="nowrap" class="text-center">4</td>
          <td nowrap="nowrap" class="text-center">5</td>
          <td nowrap="nowrap" class="text-center">6</td>
          <td nowrap="nowrap" class="text-center">7</td>
          <td nowrap="nowrap" class="text-center">8</td>
          <td nowrap="nowrap" class="text-center">9</td>
          <td nowrap="nowrap" class="text-center">10</td>
          <td nowrap="nowrap" class="text-center">11</td>
          <td nowrap="nowrap" class="text-center">12</td>
          <td nowrap="nowrap" class="text-center">13</td>
          <td nowrap="nowrap" class="text-center">14</td>
          <td nowrap="nowrap" class="text-center">15</td>
          <td nowrap="nowrap" class="text-center">16</td>
          <td nowrap="nowrap" class="text-center">17</td>
          <td nowrap="nowrap" class="text-center">18</td>
          <td nowrap="nowrap" class="text-center">EF1</td>
          <td nowrap="nowrap" class="text-center">EF2</td>
          <td nowrap="nowrap" class="text-center">SF1</td>
          <td nowrap="nowrap" class="text-center">SF2</td>
          <td nowrap="nowrap" class="text-center">GF</td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="text-center italic">Scr</td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['r01s']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['r02s']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['r03s']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['r04s']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['r05s']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['r06s']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['r07s']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['r08s']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['r09s']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['r10s']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['r11s']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['r12s']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['r13s']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['r14s']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['r15s']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['r16s']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['r17s']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['r18s']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['EF1']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['EF2']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['SF1']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['SF2']/2); ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo ($row_individual['GF']/2); ?></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="text-center italic">Pos</td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r01pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r02pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r03pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r04pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r05pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r06pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r07pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r08pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r09pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r10pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r11pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r12pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r13pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r14pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r15pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r16pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r17pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r18pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['EF1_pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['EF2_pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['SF1_pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['SF2_pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['GF_pos']; ?></td>
        </tr>
        <?php 
        }
        if($comptype == 'Snooker')
        {
        ?>
        <tr>
          <td nowrap="nowrap" class="text-center italic">Rd</td>
          <td nowrap="nowrap" class="text-center">1</td>
          <td nowrap="nowrap" class="text-center">2</td>
          <td nowrap="nowrap" class="text-center">3</td>
          <td nowrap="nowrap" class="text-center">4</td>
          <td nowrap="nowrap" class="text-center">5</td>
          <td nowrap="nowrap" class="text-center">6</td>
          <td nowrap="nowrap" class="text-center">7</td>
          <td nowrap="nowrap" class="text-center">8</td>
          <td nowrap="nowrap" class="text-center">9</td>
          <td nowrap="nowrap" class="text-center">10</td>
          <td nowrap="nowrap" class="text-center">11</td>
          <td nowrap="nowrap" class="text-center">12</td>
          <td nowrap="nowrap" class="text-center">13</td>
          <td nowrap="nowrap" class="text-center">14</td>
          <td nowrap="nowrap" class="text-center">15</td>
          <td nowrap="nowrap" class="text-center">16</td>
          <td nowrap="nowrap" class="text-center">17</td>
          <td nowrap="nowrap" class="text-center">18</td>
          <td nowrap="nowrap" class="text-center">SF1</td>
          <td nowrap="nowrap" class="text-center">SF2</td>
          <td nowrap="nowrap" class="text-center">GF</td>
        </tr>
          <tr>
            <td nowrap="nowrap" class="text-center italic">Scr</td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r01s']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r02s']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r03s']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r04s']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r05s']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r06s']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r07s']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r08s']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r09s']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r10s']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r11s']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r12s']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r13s']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r14s']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r15s']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r16s']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r17s']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r18s']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['SF1']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['SF2']; ?></td>
            <td nowrap="nowrap" class="text-center"><?php echo $row_individual['GF']; ?></td>
          </tr>
        <tr>
          <td nowrap="nowrap" class="text-center italic">Pos</td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r01pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r02pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r03pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r04pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r05pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r06pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r07pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r08pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r09pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r10pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r11pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r12pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r13pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r14pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r15pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r16pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r17pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['r18pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['SF1_pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['SF2_pos']; ?></td>
          <td nowrap="nowrap" class="text-center"><?php echo $row_individual['GF_pos']; ?></td>
        </tr>
        <?php
        }
     } while ($row_individual = mysql_fetch_assoc($individual)); ?>
    </tbody>
  </table>
</div> 
  
<div class="table-responsive center-block" style="max-width:700px"> <!-- class table-responsive -->
    <table class="table table-striped">
    <tr>
    <td colspan="3" class="text-left italic">Recorded Breaks</td>
    <td class="text-left italic">&nbsp;</td>
    <td class="text-left italic">&nbsp;</td>
    </tr>
  <tr>
    <th>Name</th>
    <th class="text-center">Break</th>
    <th class="text-center">Finals</th>
    <th class="text-left">Recorded</th>
    <th class="text-center">Season</th>
  </tr>
  <?php 
  if($totalRows_team_brks>0) do { ?>
    <tr>
      <td nowrap="nowrap" class="text-left"><?php echo $row_team_brks['FirstName']; ?>&nbsp; <?php echo $row_team_brks['LastName']; ?></td>
      <td class="text-center"><?php echo $row_team_brks['brk']; ?></td>
      <td class="text-center"><?php echo $row_team_brks['finals_brk']; ?></td>
      <td class="text-left"><?php $newDate = date("M j, Y", strtotime($row_team_brks['recvd'])); echo $newDate; ?></td>
      <td class="text-center"><?php echo $row_team_brks['season']; ?></td>
    </tr>
    <?php } while ($row_team_brks = mysql_fetch_assoc($team_brks)); else echo '<tr><td colspan="5"  class="text-center  italic">'."No Breaks Recorded".'</td></tr>'; ?>
    </table>
</div>  
  

</div>  <!-- close containing wrapper --> 
</body>
</html>
<?php

?>
