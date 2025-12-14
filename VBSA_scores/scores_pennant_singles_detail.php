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

$comptype = "-1";
if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}

$year = "-1";
if (isset($_GET['year'])) {
  $year = $_GET['year'];
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$grade = "-1";
if (isset($_GET['grade'])) {
  $grade = $_GET['grade'];
}

$display = "-1";
if (isset($_GET['display'])) {
  $display = $_GET['display'];
}

$year = (date('Y'));
//$year = (date('Y') - 1);

mysql_select_db($database_connvbsa, $connvbsa);

$query_no_of_rounds = "Select * FROM vbsa3364_vbsa2.tbl_scoresheet where team_grade = '$grade' and season = '$season' and year = '$year' and date_played < CURDATE() order by round DESC Limit 1";
$rounds = mysql_query($query_no_of_rounds, $connvbsa) or die(mysql_error());
$row_no_of_rounds = mysql_fetch_assoc($rounds);

$query_total_rounds = "Select no_of_rounds, grade, season, fix_cal_year FROM Team_grade WHERE grade = '$grade' and season = '$season' and fix_cal_year = '$year' and current = 'Yes'";
//echo($query_total_rounds . "<br>");
$total_rounds = mysql_query($query_total_rounds, $connvbsa) or die(mysql_error());
$row_total_rounds = mysql_fetch_assoc($total_rounds);

if(($row_no_of_rounds['round'] < ($row_total_rounds['no_of_rounds']-2)))
{
  $rounds_played = $row_no_of_rounds['round'];
}
else
{
  $rounds_played = ($row_total_rounds['no_of_rounds']-2);
}
$total_rounds_available = ($row_total_rounds['no_of_rounds']);
//echo("Total Rounds " . $total_rounds_available . "<br>");
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
<?php
$help_caption = "
<center><br>
<div class='table-responsive center-block' style='max-width:700px' align='center'>This table tracks your position for Pennant singles through the season.
<br>
<p align='left'>Scoring is based on:</p>
<p align='left'><b>1. Encouraging participation</b> (how many matches you play) – You must have played 4 or more matches to be included in the Singles Finals. (Qualified = played 6 matches or higher).</p>
<p align='left'><b>2. Recognizes games won and playing position</b> The more you win at a higher position in the team, the higher your total points. (Win % divided by ave pos index).</p>
<p align='left'>If rounded(average_position) = 1, ave pos index is 1.25<br>If rounded(average_position) = 2, ave pos index is 1.50<br>If rounded(average_position) = 3, ave pos index is 1.75<br>If rounded(average_position) = 4, ave pos index is 2.00<br></p></div></div></center>";

if($comptype == 'Snooker')
{
?>
  <!--Content--> 
  <div class="row"> 
    <div class="Page_heading_container">
   		<div class="page_title">
          Statistics for <?php echo $comptype; ?> (Singles)
      </div>
    </div>  	
  </div>
  <!-- Include link to previous page -->
  <?php include '../includes/prev_page.php';?>
    <div class="table-responsive center-block" style="max-width:800px">
      <?= $help_caption . "<br>" ?>
      <table class="table text-center table-striped">
        <thead> 
          <tr>
            <th colspan="5" class="text-left italic">Player Stats <?php echo $grade; ?></th>
          </tr>
          <tr>
            <th class="text-center">Name</th>
            <th class="text-center">Qualified</th>
            <th class="text-center">Win%</th>
            <th class="text-center">Ave Position (Rounded)</th>
            <th class="text-center">Total Pts</th>
          </tr>
        </thead>
        <tbody>
           <?php
            $no_of_rounds = $row_no_of_rounds['round'];
            if(isset($no_of_rounds) && ($no_of_rounds > 0))
            {
              //$query_ladd_det = "Select members.MemberID, members.FirstName, members.LastName, percent_won, average_position, percent_won/average_position as total_score, count_played, byes_to_date, ((count_played/(" . $total_rounds_available . "-byes_to_date)*100)) as qualified, team_id FROM scrs left Join members on members.MemberID = scrs.MemberID where team_grade='$grade' and members.memberID != 1 and scr_season = '$season' and current_year_scrs = '$year' and average_position > 0 order by total_score DESC";

              $query_ladd_det = "Select members.MemberID, members.FirstName, members.LastName, percent_won, average_position, 
              CASE 
                WHEN round(average_position) = 1 THEN (percent_won/1.25)
                WHEN round(average_position) = 2 THEN (percent_won/1.50)
                WHEN round(average_position) = 3 THEN (percent_won/1.75)
                WHEN round(average_position) = 4 THEN (percent_won/2.00)
              ELSE 0
              END as total_score, count_played, byes_to_date, team_id FROM scrs left Join members on members.MemberID = scrs.MemberID where team_grade='$grade' and members.memberID != 1 and scr_season = '$season' and current_year_scrs = '$year' and average_position > 0 order by total_score DESC";
              //echo($query_ladd_det . "<br>");
              $ladd_det = mysql_query($query_ladd_det, $connvbsa) or die(mysql_error());
              $totalRows_ladd_det = mysql_num_rows($ladd_det);
              $row_ladd_det = mysql_fetch_assoc($ladd_det);
            }
            else
            {
              $totalRows_ladd_det = 0;
            }
            ?>
          <?php 
          if($totalRows_ladd_det > 0)
          {
            $i = 0;
            do {
          ?>
            <tr>
              <?php
              if(($i < 6) && ($display == 'top'))
              //if(($row_ladd_det['qualified'] > 69.6) && ($i < 6))
              {
              ?>
                <td><?php echo $row_ladd_det['FirstName'] . ' ' . $row_ladd_det['LastName']; ?></td>
                <?php
                //if(($row_ladd_det['count_played']-$row_ladd_det['byes_to_date']) > ($total_rounds_available*0.5))
                if($row_ladd_det['count_played'] >= 6)
                //if(($row_ladd_det['qualified'] > 69.6))
                {
                echo "<td>Yes</td>";
                }
                else 
                {   
                echo "<td>No</td>";
                }
                ?>
                <!--<td><?php echo $row_ladd_det['MemberID']; ?></td>-->
                <td><?php echo $row_ladd_det['percent_won']; ?></td>
                <td><?php echo round($row_ladd_det['average_position']); ?></td>
                <td><?php echo round($row_ladd_det['total_score']); ?></td>
              <?php 
              $i++;
              }
              elseif($display == 'all')
              {
                ?>
                <td><?php echo $row_ladd_det['FirstName'] . ' ' . $row_ladd_det['LastName']; ?></td>
                <?php
                //if(($row_ladd_det['count_played']-$row_ladd_det['byes_to_date']) > ($total_rounds_available*0.5))
                if($row_ladd_det['count_played'] >= 6)
                //if(($row_ladd_det['qualified'] > 69.6))
                {
                echo "<td>Yes</td>";
                }
                else 
                {   
                echo "<td>No</td>";
                }
                ?>
                <!--<td><?php echo $row_ladd_det['MemberID']; ?></td>-->
                <td><?php echo $row_ladd_det['percent_won']; ?></td>
                <td><?php echo $row_ladd_det['average_position']; ?></td>
                <td><?php echo round($row_ladd_det['total_score']); ?></td>
              <?php 
              $i++;
              }
              ?>
            </tr>
            <?php
          } while($row_ladd_det = mysql_fetch_assoc($ladd_det));
          if($i == 0)
          {
            echo("<tr><td colspan=6>No Data to display</td></tr>");
          }
        }
        else
        {
          echo("<tr><td colspan=6>No Data to display</td></tr>");
        }
        ?>
        </tbody>
      </table>
    </div> 
<?php 
}
else if($comptype == 'Billiards')
{
?>
<!--Content--> 
  <div class="row"> 
    <div class="Page_heading_container">
      <div class="page_title">
          Statistics for <?php echo $comptype; ?> (Singles)
      </div>
    </div>    
  </div>
  <!-- Include link to previous page -->
  <?php include '../includes/prev_page.php';?>
  <?php
    /*$help_caption = "
    <center><br>
    <div class='table-responsive center-block' style='max-width:700px' align='left'><p>This table tracks your progress for Billiards Pennant Singles through the season.</p>
    <p align='left'>The table ranking is determined based on total number of games won by players, followed by their Win %, followed by their Scores ratio (calculated by dividing total points scored by a player by the total scores of their opponents).</p>
    <p align='left'>Players will compete in the Singles Finals series based on the tier they are playing in at the completion of the season regardless of whether they were re-handicapped and competed on a different tier during the season.</p>
    </div></center>";*/

    $help_caption = "
    <center><br>
    <div class='table-responsive center-block' style='max-width:700px' align='left'><p>This table tracks the progress of players for the Billiards Pennant Singles through the season.</p>
    <p align='left'>The table ranking is based on 
    <ul>
      <li>total number of game points won by a player, (2 points for a win, 1 point for a draw and 0 points for a loss) then</li>
      <li>by their win %, then</li>
      <li>by their scores ratio (total points scored by a player in all games divided by total of points scored against that player during the season by their opponents).</li>
    </ul>
    </p>
    <p align='left'>Players will qualify to compete in the Singles event based on the tier they are playing in at the completion of the season regardless of whether they had their tier adjusted and competed on a different tier during the season.</p>
    <p align='left'>Players are required to have played in 70% or more of the seasons matches to qualify for the Singles event.</p>
    </div></center>";

    echo($help_caption . "<br>");
    ?>
    <div class="table-responsive center-block" style="max-width:800px">
      <table class="table text-center table-striped">
        <thead> 
          <tr>
            <th colspan="4" class="text-left italic">Player Stats <?php echo $grade; ?></th>
          </tr>
          <tr>
            <th rowspan='2' class="text-center">Name</th>
            <th rowspan='2' class="text-center">Qualified</th>
            <th rowspan='2' class="text-center">Total Game Points Won</th>
            <th rowspan='2' class="text-center">Win %</th>
            <th colspan='3' class="text-center">Scores</th>
          </tr>
          <tr>
            <th class="text-center">For</th>
            <th class="text-center">Against</th>
            <th class="text-center">Ratio</th>
          </tr>
        </thead>
        <tbody>
    <?php 

    //$title_array = ['Tiers 5 and below', 'Tiers 6 and 7', 'Tiers 8 and 9', 'Tiers 10 and above'];

    if($grade)
    {
      // added scores for/against 1/11/2024
      // add calc for total game points when player has played in more than one team 8/11/2024
      switch ($grade)
      {
        // added game type = billiards for 0 tiers 27/08/2024
        case 'Tiers 5 and below':
          $query_ladd_det = "Select count(*) as Multi, sum(pts_won) as multipoints, sum(avail_pts) as multi_avail, members.FirstName, members.LastName, scrs.* FROM vbsa3364_vbsa2.scrs left Join members on members.MemberID = scrs.MemberID where current_year_scrs = '$year' and scr_season = '$season' and (tier = -1 or tier = 0 or tier = 1 or tier = 2 or tier = 3 or tier = 4 or tier = 5) and total_rp > 0 and game_type = 'Billiards' group by scrs.MemberID Order By pts_won DESC, percent_won DESC, scores_percent DESC ";
          break;
        case 'Tiers 6 and 7':
          $query_ladd_det = "Select count(*) as Multi, sum(pts_won) as multipoints, sum(avail_pts) as multi_avail, members.FirstName, members.LastName, scrs.* FROM vbsa3364_vbsa2.scrs left Join members on members.MemberID = scrs.MemberID where current_year_scrs = '$year' and scr_season = '$season' and (tier = 6 or tier = 7) and total_rp > 0 and game_type = 'Billiards' group by scrs.MemberID Order By pts_won DESC, percent_won DESC, scores_percent DESC";
          break;
        case 'Tiers 8 and 9': 
          $query_ladd_det = "Select count(*) as Multi, sum(pts_won) as multipoints, sum(avail_pts) as multi_avail, members.FirstName, members.LastName, scrs.* FROM vbsa3364_vbsa2.scrs left Join members on members.MemberID = scrs.MemberID where current_year_scrs = '$year' and scr_season = '$season' and (tier = 8 or tier = 9) and total_rp > 0 and game_type = 'Billiards' group by scrs.MemberID Order By pts_won DESC, percent_won DESC, scores_percent DESC";
          break;
        case 'Tiers 10 and above': 
          $query_ladd_det = "Select count(*) as Multi, sum(pts_won) as multipoints, sum(avail_pts) as multi_avail, members.FirstName, members.LastName, scrs.* FROM vbsa3364_vbsa2.scrs left Join members on members.MemberID = scrs.MemberID where current_year_scrs = '$year' and scr_season = '$season' and (tier = 10 or tier = 11 or tier = 12) and total_rp > 0 and game_type = 'Billiards' group by scrs.MemberID Order By pts_won DESC, percent_won DESC, scores_percent DESC";
          break;
      }
    }

    $query_billard_rounds = "Select no_of_rounds, grade, season, fix_cal_year FROM Team_grade WHERE type='Billiards' and season = '$season' and fix_cal_year = '$year' and current = 'Yes'";
    //echo($query_total_rounds . "<br>");
    $billard_rounds = mysql_query($query_billard_rounds, $connvbsa) or die(mysql_error());
    $row_billard_rounds = mysql_fetch_assoc($billard_rounds);
    $billard_rounds_available = ($row_billard_rounds['no_of_rounds']-3); // remove finals
    //echo("Total Rounds " . $total_rounds_available . "<br>");
    //echo($query_ladd_det . "<br>");
    $ladd_det = mysql_query($query_ladd_det, $connvbsa) or die(mysql_error());
    $totalRows_ladd_det = mysql_num_rows($ladd_det);
    $limit = 0;
    if($totalRows_ladd_det > 0)
    {
      while($row_ladd_det = mysql_fetch_assoc($ladd_det))
      { 
        //echo("Qual " . ($row_ladd_det['count_played']/($billard_rounds_available-$row_ladd_det['byes_to_date'])*100) . "<br>"); 
        if($row_ladd_det['Multi'] > 1)
        {
          $points = $row_ladd_det['multipoints'];
          $percent = number_format(($row_ladd_det['multipoints']/$row_ladd_det['multi_avail']*100),2);
        } 
        else
        {
          $points = $row_ladd_det['pts_won'];
          $percent = $row_ladd_det['percent_won'];
        }
        if(($limit < 8) && ($display == 'top'))
        //if($limit < 8)
        {
        ?>
        <tr>
          <td><?php echo $row_ladd_det['FirstName'] . ' ' . $row_ladd_det['LastName']; ?></td>
          <?php
          //echo("Qual " . ($row_ladd_det['count_played']/($billard_rounds_available-$row_ladd_det['byes_to_date'])*100) . "<br>"); 
          //if(($row_ladd_det['count_played']-$row_ladd_det['byes_to_date']) > ($total_rounds_available*0.5))
          if((($row_ladd_det['count_played']/($billard_rounds_available-$row_ladd_det['byes_to_date'])*100) >= 70))
          //if($row_ladd_det['count_played'] >= 4)
          {
          echo "<td>Yes</td>";
          }
          else 
          {   
          echo "<td>No</td>";
          }
          ?>
          <td><?php echo $points; ?></td>
          <td><?php echo $percent; ?></td>
          <td><?php echo ($row_ladd_det['scores_for']); ?></td>
          <td><?php echo $row_ladd_det['scores_against'] ?></td>
          <td><?php echo round($row_ladd_det['scores_percent']); ?></td>
        </tr>
        <?php
        }
        elseif($display == 'all')
        {
          ?>
        <tr>
          <td><?php echo $row_ladd_det['FirstName'] . ' ' . $row_ladd_det['LastName']; ?></td>
          <?php
          //echo(($row_ladd_det['count_played']/($billard_rounds_available-$row_ladd_det['byes_to_date'])*100) . "<br>"); 
          //if(($row_ladd_det['count_played']-$row_ladd_det['byes_to_date']) > ($total_rounds_available*0.5))
          //if($row_ladd_det['count_played'] >= 4)
          if((($row_ladd_det['count_played']/($billard_rounds_available-$row_ladd_det['byes_to_date'])*100) >= 70))
          {
          echo "<td>Yes</td>";
          }
          else 
          {   
          echo "<td>No</td>";
          }
          ?>
          <td><?php echo $points; ?></td>
          <td><?php echo $percent; ?></td>
          <td><?php echo ($row_ladd_det['scores_for']); ?></td>
          <td><?php echo $row_ladd_det['scores_against'] ?></td>
          <td><?php echo round($row_ladd_det['scores_percent']); ?></td>
        </tr>
        <?php
        }
        $limit++;
      }
    }
    else
    {
      echo("<tr><td colspan=4>No Data to display</td></tr>");
    }
}
?>
        </tbody>
      </table>
    </div> 
 <!-- close containing wrapper --> 
</body>
</html>

