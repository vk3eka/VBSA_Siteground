<?php require_once('../Connections/connvbsa.php'); 

error_reporting(0);

mysql_select_db($database_connvbsa, $connvbsa);

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

$current_season = 'S2';
$current_year = 2024;

function GetGradeData($grade, $member_id, $strength)
{
  //echo("Inside<br>");
  global $connvbsa;
  // get all A grade data for each player
  $query_A_Test_assess = "Select SUM(pts_won) as won, SUM(avail_pts) as played FROM scrs left join members on members.MemberID = scrs.MemberID WHERE scrs.MemberID = " . $member_id . " and current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND scrs.team_grade LIKE '" . $grade . "%' AND avail_pts > 0 Group by scrs.MemberID";
  $A_Test_assess = mysql_query($query_A_Test_assess, $connvbsa) or die(mysql_error());
  $A_build_assess = mysql_fetch_assoc($A_Test_assess);

  $query_A_AVG_member = "Select AVG(percent_won) AS percent FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $member_id . " AND team_grade LIKE '" . $grade . "%'";
  $A_AVG_member = mysql_query($query_A_AVG_member, $connvbsa) or die(mysql_error());
  $A_build_AVG_member = mysql_fetch_assoc($A_AVG_member);
  //$A_percent = $A_build_AVG_member['percent'];

  $query_A_Pos_member = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $member_id . " AND team_grade LIKE '" . $grade . "%'";
  $A_Pos_member = mysql_query($query_A_Pos_member, $connvbsa) or die(mysql_error());
  $A_build_Pos_member = mysql_fetch_assoc($A_Pos_member);
  //$A_position = $A_build_Pos_member['position'];

  if(!isset($A_build_AVG_member['percent']))
  {
    $A_percent = 0;
  }
  else
  {
    $A_percent = $A_build_AVG_member['percent'];
  }

  if(!isset($A_build_Pos_member['position']))
  {
    $A_position = 0;
  }
  else
  {
    $A_position = $A_build_Pos_member['position'];
  }

  if(!isset($A_build_assess['won']))
  {
    $A_Won = 0;
  }
  else
  {
    $A_Won = $A_build_assess['won'];
  }

  if(!isset($A_build_assess['played']))
  {
    $A_Played = 0;
  }
  else
  {
    $A_Played = $A_build_assess['played'];
  }

  if(($A_position > 0) && ($A_percent > 0))
  { 
    $A_strength = ($A_percent/$A_position); 
  }
  else
  { 
    $A_strength = 0;
  }

  if(($A_position > 0) && ($A_percent > 0))
  {
    $A_variation = (($A_percent/$A_position)-($strength)/($strength));
  }
  else
  {
    $A_variation = 0;
  }

  $player_data = ($A_Won . ", " . $A_Played . ", " . $A_percent . ", " . $A_position . ", " . $A_strength . ", " . $A_variation);
  //return $A_won, $A_Played, $A_strength, $A_variation;
  return $player_data;

}

//$playerData = GetGradeData('A', 5583, 17.68);
//$player_data = explode(", ", $playerData);
//echo("Player Data " . $player_data[0] . "<br>");

function GetTeamAssessment($grade, $current_season, $current_year)
{
  global $connvbsa;

  $query_A_AVG = "Select AVG(percent_won) AS percent FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND team_grade LIKE 'A%'";
  $A_AVG = mysql_query($query_A_AVG, $connvbsa) or die(mysql_error());
  $row_A_AVG = mysql_fetch_assoc($A_AVG);

  $query_B_AVG = "Select AVG(percent_won) AS percent FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND team_grade LIKE 'B%'";
  $B_AVG = mysql_query($query_B_AVG, $connvbsa) or die(mysql_error());
  $row_B_AVG = mysql_fetch_assoc($B_AVG);

  $query_C_AVG = "Select AVG(percent_won) AS percent FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND team_grade LIKE 'C%'";
  $C_AVG = mysql_query($query_C_AVG, $connvbsa) or die(mysql_error());
  $row_C_AVG = mysql_fetch_assoc($C_AVG);


  $query_A_AVG_Pos = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND team_grade LIKE 'A%'";
  $A_AVG_Pos = mysql_query($query_A_AVG_Pos, $connvbsa) or die(mysql_error());
  $row_A_AVG_Pos = mysql_fetch_assoc($A_AVG_Pos);

  $query_B_AVG_Pos = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND team_grade LIKE 'B%'";
  $B_AVG_Pos = mysql_query($query_B_AVG_Pos, $connvbsa) or die(mysql_error());
  $row_B_AVG_Pos = mysql_fetch_assoc($B_AVG_Pos);

  $query_C_AVG_Pos = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND team_grade LIKE 'C%'";
  $C_AVG_Pos = mysql_query($query_C_AVG_Pos, $connvbsa) or die(mysql_error());
  $row_C_AVG_Pos = mysql_fetch_assoc($C_AVG_Pos);

  $query_A_Test_team = "Select Distinct team_name FROM scrs left join Team_entries on Team_entries.team_id = scrs.team_id  where team_cal_year = '$current_year' and team_season = '$current_season' and scrs.team_grade LIKE '" . $grade . "%' Order by team_name";
  $A_Test_team = mysql_query($query_A_Test_team, $connvbsa) or die(mysql_error());
  while ($build_team = mysql_fetch_assoc($A_Test_team))
  {
    if($build_team['team_name'] != 'Bye')
    {
      $query_A_Test_member = "Select Distinct scrs.MemberID, FirstName, LastName, pts_won, count_played FROM scrs left join Team_entries on Team_entries.team_id = scrs.team_id left join members on scrs.MemberID = members.MemberID WHERE team_name = '" . $build_team['team_name'] . "' and LastName != 'Bye' and LastName != 'Forfeit' and current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND scrs.team_grade LIKE '" . $grade . "%' Group By scrs.MemberID  Order by LastName";
      $A_Test_member = mysql_query($query_A_Test_member, $connvbsa) or die(mysql_error());
      $team_index = 0; 
      while ($build_member = mysql_fetch_assoc($A_Test_member))
      {
      /*  // get all A grade data for each player
        $query_A_Test_assess = "Select SUM(pts_won) as won, SUM(avail_pts) as played FROM scrs left join members on members.MemberID = scrs.MemberID WHERE scrs.MemberID = " . $build_member['MemberID'] . " and current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND scrs.team_grade LIKE 'A%' Group by scrs.MemberID";
        $A_Test_assess = mysql_query($query_A_Test_assess, $connvbsa) or die(mysql_error());
        $A_build_assess = mysql_fetch_assoc($A_Test_assess);

        $query_A_AVG_member = "Select AVG(percent_won) AS percent FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND team_grade LIKE 'A%'";
        $A_AVG_member = mysql_query($query_A_AVG_member, $connvbsa) or die(mysql_error());
        $A_build_AVG_member = mysql_fetch_assoc($A_AVG_member);

        $query_A_Pos_member = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND team_grade LIKE 'A%'";
        $A_Pos_member = mysql_query($query_A_Pos_member, $connvbsa) or die(mysql_error());
        $A_build_Pos_member = mysql_fetch_assoc($A_Pos_member);

        if(!isset($A_build_assess['won']))
        {
          $A_Won = 0;
        }
        else
        {
          $A_Won = $A_build_assess['won'];
        }
        if(!isset($A_build_assess['played']))
        {
          $A_Played = 0;
        }
        else
        {
          $A_Played = $A_build_assess['played'];
        }

        if(($A_build_Pos_member['position'] > 0) && ($A_build_AVG_member['percent'] > 0))
        { 
          $A_strength = ($A_build_AVG_member['percent']/$A_build_Pos_member['position']); 
        }
        else
        { 
          $A_strength = 0;
        }

        if(($A_build_Pos_member['position'] > 0) && ($A_build_AVG_member['percent'] > 0))
        {
          $A_variation = (($A_build_AVG_member['percent']/$A_build_Pos_member['position'])-($row_A_AVG['percent']/$row_A_AVG_Pos['position'])/($row_A_AVG['percent']/$row_A_AVG_Pos['position']));
        }
        else
        {
          $A_variation = 0;
        }

        if($team_index == 0) 
        { 
          $team_name = $build_team['team_name']; 
        }
        else
        {
          $team_name = '';
        }

        // get all B grade data for each player
        $query_B_Test_assess = "Select SUM(pts_won) as won, SUM(avail_pts) as played FROM scrs left join members on members.MemberID = scrs.MemberID WHERE scrs.MemberID = " . $build_member['MemberID'] . " and current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND scrs.team_grade LIKE 'B%' Group by scrs.MemberID";
        $B_Test_assess = mysql_query($query_B_Test_assess, $connvbsa) or die(mysql_error());
        $B_build_assess = mysql_fetch_assoc($B_Test_assess);
        if(!isset($B_build_assess['won']))
        {
          $B_Won = 0;
        }
        else
        {
          $B_Won = $B_build_assess['won'];
        }
        if(!isset($B_build_assess['played']))
        {
          $B_Played = 0;
        }
        else
        {
          $B_Played = $B_build_assess['played'];
        }
        $query_B_AVG_member = "Select AVG(percent_won) AS percent FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND team_grade LIKE 'B%'";
        $B_AVG_member = mysql_query($query_B_AVG_member, $connvbsa) or die(mysql_error());
        $B_build_AVG_member = mysql_fetch_assoc($B_AVG_member);

        $query_B_Pos_member = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND team_grade LIKE 'B%'";
        $B_Pos_member = mysql_query($query_B_Pos_member, $connvbsa) or die(mysql_error());
        $B_build_Pos_member = mysql_fetch_assoc($B_Pos_member);

        if(($B_build_Pos_member['position'] > 0) && ($B_build_AVG_member['percent'] > 0))
        { 
          $B_strength = ($B_build_AVG_member['percent']/$B_build_Pos_member['position']); 
        }
        else
        { 
          $B_strength = 0;
        }
      
        if(($B_build_Pos_member['position'] > 0) && ($B_build_AVG_member['percent'] > 0))
        {
          $B_variation = (($B_build_AVG_member['percent']/$B_build_Pos_member['position'])-($row_B_AVG['percent']/$row_B_AVG_Pos['position'])/($row_B_AVG['percent']/$row_B_AVG_Pos['position']));
        }
        else
        {
          $B_variation = 0;
        }

        // get all C grade data for each player
        $query_C_Test_assess = "Select SUM(pts_won) as won, SUM(avail_pts) as played FROM scrs left join members on members.MemberID = scrs.MemberID WHERE scrs.MemberID = " . $build_member['MemberID'] . " and current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND (scrs.team_grade LIKE 'C%' OR scrs.team_grade LIKE 'D%') Group by scrs.MemberID";
        $C_Test_assess = mysql_query($query_C_Test_assess, $connvbsa) or die(mysql_error());
        $C_build_assess = mysql_fetch_assoc($C_Test_assess);
        if(!isset($C_build_assess['won']))
        {
          $C_Won = 0;
        }
        else
        {
          $C_Won = $C_build_assess['won'];
        }
        if(!isset($C_build_assess['played']))
        {
          $C_Played = 0;
        }
        else
        {
          $C_Played = $C_build_assess['played'];
        }

        $query_C_AVG_member = "Select AVG(percent_won) AS percent FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND (scrs.team_grade LIKE 'C%' OR scrs.team_grade LIKE 'D%')";
        $C_AVG_member = mysql_query($query_C_AVG_member, $connvbsa) or die(mysql_error());
        $C_build_AVG_member = mysql_fetch_assoc($C_AVG_member);

        $query_C_Pos_member = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND (scrs.team_grade LIKE 'C%' OR scrs.team_grade LIKE 'D%')";
        $C_Pos_member = mysql_query($query_C_Pos_member, $connvbsa) or die(mysql_error());
        $C_build_Pos_member = mysql_fetch_assoc($C_Pos_member);

        if(!isset($C_build_Pos_member['position']) || ($C_build_Pos_member['position'] == '') || ($C_build_Pos_member['position'] < 1)) 
        { 
          $C_strength = 0;
        }
        else
        { 
          $C_strength = ($C_build_AVG_member['percent']/$C_build_Pos_member['position']); 
        }

        if(!isset($C_build_Pos_member['position']) || ($C_build_Pos_member['position'] == '') || ($C_build_Pos_member['position'] < 1)) 
        {
          $C_variation = 0;
        }
        else
        {
          $C_variation = (($C_build_AVG_member['percent']/$C_build_Pos_member['position'])-($row_C_AVG['percent']/$row_C_AVG_Pos['position'])/($row_C_AVG['percent']/$row_C_AVG_Pos['position']));
        }
        */
        //echo("Here " . $A_build_AVG_member['percent'] . "<br>");
        $A_playerData = GetGradeData('A', $build_member['MemberID'], ($row_A_AVG_Pos['position'])/($row_A_AVG['percent']));
        //echo("Here<br>");
        $A_player_data = explode(", ", $A_playerData);
        //echo("Player Data A " . $A_player_data[0] . "<br>");

        $B_playerData = GetGradeData('B', $build_member['MemberID'], ($row_B_AVG_Pos['position'])/($row_B_AVG['percent']));
        $B_player_data = explode(", ", $B_playerData);
        //echo("Player Data B " . $B_player_data[0] . "<br>");

        $C_playerData = GetGradeData('C', $build_member['MemberID'], ($row_C_AVG_Pos['position'])/($row_C_AVG['percent']));
        $C_player_data = explode(", ", $C_playerData);
        //echo("Player Data C " . $C_player_data[0] . "<br>");


        if($team_index == 0) 
        { 
          $team_name = $build_team['team_name']; 
        }
        else
        {
          $team_name = '';
        }

        echo 
        "<tr>
          <td class='border'  align='center'>" . $team_name . "</td>
          <td class='border'  align='center'>" . $build_member['FirstName'] . " " . $build_member['LastName'] . "</td>
          <td class='border'  align='center'>" . $A_player_data[0] . "</td>
          <td class='border'  align='center'>" . $A_player_data[1] . "</td>
          <td class='border'  align='center'>" . number_format($A_player_data[2], 2) . "</td>
          <td class='border'  align='center'>" . number_format($A_player_data[3], 2) . "</td>
          <td class='border'  align='center'>" . number_format($A_player_data[4], 2) . "</td>
          <td class='border'  align='center'>" . number_format($A_player_data[5], 2) . "</td>
          <td class='border'  align='center' style='background-color:black;'></td>
          <td class='border'  align='center'>" . $B_player_data[0] . "</td>
          <td class='border'  align='center'>" . $B_player_data[1] . "</td>
          <td class='border'  align='center'>" . number_format($B_player_data[2], 2) . "</td>
          <td class='border'  align='center'>" . number_format($B_player_data[3], 2) . "</td>
          <td class='border'  align='center'>" . number_format($B_player_data[4], 2) . "</td>
          <td class='border'  align='center'>" . number_format($B_player_data[5], 2) . "</td>
          <td class='border'  align='center' style='background-color:black;'></td>
          <td class='border'  align='center'>" . $C_player_data[0] . "</td>
          <td class='border'  align='center'>" . $C_player_data[1] . "</td>
          <td class='border'  align='center'>" . number_format($C_player_data[2], 2) . "</td>
          <td class='border'  align='center'>" . number_format($C_player_data[3], 2) . "</td>
          <td class='border'  align='center'>" . number_format($C_player_data[4], 2) . "</td>
          <td class='border'  align='center'>" . number_format($C_player_data[5], 2) . "</td>
        </tr>";
        if($team_index == ($A_Test_team->num_rows-1))
        {
          echo("<tr><td colspan='22' class='border' align='center'>&nbsp;</td></tr>");
        }

        /*
        echo 
        "<tr>
          <td class='border'  align='center'>" . $team_name . "</td>
          <td class='border'  align='center'>" . $build_member['FirstName'] . " " . $build_member['LastName'] . "</td>
          <td class='border'  align='center'>" . $A_Won . "</td>
          <td class='border'  align='center'>" . $A_Played . "</td>
          <td class='border'  align='center'>" . number_format($A_build_AVG_member['percent'], 2) . "</td>
          <td class='border'  align='center'>" . number_format($A_build_Pos_member['position'], 2) . "</td>
          <td class='border'  align='center'>" . number_format($A_strength, 2) . "</td>
          <td class='border'  align='center'>" . number_format($A_variation, 2) . "</td>
          <td class='border'  align='center' style='background-color:black;'></td>
          <td class='border'  align='center'>" . $B_Won . "</td>
          <td class='border'  align='center'>" . $B_Played . "</td>
          <td class='border'  align='center'>" . number_format($B_build_AVG_member['percent'], 2) . "</td>
          <td class='border'  align='center'>" . number_format($B_build_Pos_member['position'], 2) . "</td>
          <td class='border'  align='center'>" . number_format($B_strength, 2) . "</td>
          <td class='border'  align='center'>" . number_format($B_variation, 2) . "</td>
          <td class='border'  align='center' style='background-color:black;'></td>
          <td class='border'  align='center'>" . $C_Won . "</td>
          <td class='border'  align='center'>" . $C_Played . "</td>
          <td class='border'  align='center'>" . number_format($C_build_AVG_member['percent'], 2) . "</td>
          <td class='border'  align='center'>" . number_format($C_build_Pos_member['position'], 2) . "</td>
          <td class='border'  align='center'>" . number_format($C_strength, 2) . "</td>
          <td class='border'  align='center'>" . number_format($C_variation, 2) . "</td>
        </tr>";
        */
        $team_index++;
      }
    }
  }
}

$query_A_AVG = "Select AVG(percent_won) AS percent FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND team_grade LIKE 'A%'";
$A_AVG = mysql_query($query_A_AVG, $connvbsa) or die(mysql_error());
$row_A_AVG = mysql_fetch_assoc($A_AVG);

$query_B_AVG = "Select AVG(percent_won) AS percent FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND team_grade LIKE 'B%'";
$B_AVG = mysql_query($query_B_AVG, $connvbsa) or die(mysql_error());
$row_B_AVG = mysql_fetch_assoc($B_AVG);

$query_C_AVG = "Select AVG(percent_won) AS percent FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND (team_grade LIKE 'C%' OR team_grade LIKE 'D%')";
$C_AVG = mysql_query($query_C_AVG, $connvbsa) or die(mysql_error());
$row_C_AVG = mysql_fetch_assoc($C_AVG);


$query_A_AVG_Pos = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND team_grade LIKE 'A%'";
$A_AVG_Pos = mysql_query($query_A_AVG_Pos, $connvbsa) or die(mysql_error());
$row_A_AVG_Pos = mysql_fetch_assoc($A_AVG_Pos);

$query_B_AVG_Pos = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND team_grade LIKE 'B%'";
$B_AVG_Pos = mysql_query($query_B_AVG_Pos, $connvbsa) or die(mysql_error());
$row_B_AVG_Pos = mysql_fetch_assoc($B_AVG_Pos);

$query_C_AVG_Pos = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND team_grade LIKE 'C%' OR team_grade LIKE 'D%'";
$C_AVG_Pos = mysql_query($query_C_AVG_Pos, $connvbsa) or die(mysql_error());
$row_C_AVG_Pos = mysql_fetch_assoc($C_AVG_Pos);

$A_player_AVG = $row_A_AVG['percent'];
$A_player_Pos = $row_A_AVG_Pos['position'];
$A_player_Strength = ($row_A_AVG['percent']/$row_A_AVG_Pos['position']);

$B_player_AVG = $row_B_AVG['percent'];
$B_player_Pos = $row_B_AVG_Pos['position'];
$B_player_Strength = ($row_B_AVG['percent']/$row_B_AVG_Pos['position']);

$C_player_AVG = $row_C_AVG['percent'];
$C_player_Pos = $row_C_AVG_Pos['position'];
$C_player_Strength = ($row_C_AVG['percent']/$row_C_AVG_Pos['position']);

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
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
</head>
<body>
<style>
  table.border, th.border, td.border {
    border: 1px solid black;
    border-collapse: collapse;
  }
</style>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

<table width="746" align="center">
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" ><span class="red_bold"><?php echo date("Y"); ?> VBSA Pennant Snooker Team & Player Assessment – Preceding 3 years</span></td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="greenbg"></td>
  </tr>
</table>
<table align="center" class='border' cellpadding="5" cellspacing="5">
  <tr>
    <th class='border' colspan=2>Teams and Players Currently Registered</th>
    <th class='border' colspan=6 align="center">A Grade</th>
    <td class='border'  align="center" style='background-color:black;'></td>
    <th class='border' colspan=6 align="center">B Grade</th>
    <td class='border'  align="center" style='background-color:black;'></td>
    <th class='border' colspan=6 align="center">C Grade</th>
  </tr>
  <tr>
    <td class='border'  align="center">Team Name</td>
    <td class='border'  align="center">Player Name</td>
    <td class='border'  align="center">Won</td>
    <td class='border'  align="center">Played</td>
    <td class='border'  align="center">% Won</td>
    <td class='border'  align="center">Ave Pos</td>
    <td class='border'  align="center">Strength Index</td>
    <td class='border'  align="center">% Variation</td>
    <td class='border'  align="center" style='background-color:black;'></td>
    <td class='border'  align="center">Won</td>
    <td class='border'  align="center">Played</td>
    <td class='border'  align="center">% Won</td>
    <td class='border'  align="center">Ave Pos</td>
    <td class='border'  align="center">Strength Index</td>
    <td class='border'  align="center">% Variation</td>
    <td class='border'  align="center" style='background-color:black;'></td>
    <td class='border'  align="center">Won</td>
    <td class='border'  align="center">Played</td>
    <td class='border'  align="center">% Won</td>
    <td class='border'  align="center">Ave Pos</td>
    <td class='border'  align="center">Strength Index</td>
    <td class='border'  align="center">% Variation</td>
  </tr>
  <tr>
    <td class='border'  colspan='2' align="center"><b>ALL Players Average</b></td>
    <td class='border'  align="center"></td>
    <td class='border'  align="center"></td>
    <td class='border'  align="center"><?php echo number_format($A_player_AVG, 2); ?></td>
    <td class='border'  align="center"><?php echo number_format($A_player_Pos, 2); ?></td>
    <td class='border'  align="center"><?php echo number_format($A_player_Strength, 2); ?></td>
    <td class='border'  align="center"></td>
    <td class='border'  align="center" style='background-color:black;'></td>
    <td class='border'  align="center"></td>
    <td class='border'  align="center"></td>
    <td class='border'  align="center"><?php echo number_format($B_player_AVG, 2); ?></td>
    <td class='border'  align="center"><?php echo number_format($B_player_Pos, 2); ?></td>
    <td class='border'  align="center"><?php echo number_format($B_player_Strength, 2); ?></td>
    <td class='border'  align="center"></td>
    <td class='border'  align="center" style='background-color:black;'></td>
    <td class='border'  align="center"></td>
    <td class='border'  align="center"></td>
    <td class='border'  align="center"><?php echo number_format($C_player_AVG, 2); ?></td>
    <td class='border'  align="center"><?php echo number_format($C_player_Pos, 2); ?></td>
    <td class='border'  align="center"><?php echo number_format($C_player_Strength, 2); ?></td>
    <td class='border'  align="center"></td>
  </tr>
  <tr>
    <th colspan=20 align="left"><h1>A Grade</h1></th>
  </tr>
  <tr>
    <td class='border'  align="center">Team Name</td>
    <td class='border'  align="center">Player Name</td>
    <td class='border'  align="center">Won</td>
    <td class='border'  align="center">Played</td>
    <td class='border'  align="center">% Won</td>
    <td class='border'  align="center">Ave Pos</td>
    <td class='border'  align="center">Strength Index</td>
    <td class='border'  align="center">% Variation</td>
    <td class='border'  align='center' style='background-color:black;'></td>
    <td class='border'  align="center">Won</td>
    <td class='border'  align="center">Played</td>
    <td class='border'  align="center">% Won</td>
    <td class='border'  align="center">Ave Pos</td>
    <td class='border'  align="center">Strength Index</td>
    <td class='border'  align="center">% Variation</td>
    <td class='border'  align='center' style='background-color:black;'></td>
    <td class='border'  align="center">Won</td>
    <td class='border'  align="center">Played</td>
    <td class='border'  align="center">% Won</td>
    <td class='border'  align="center">Ave Pos</td>
    <td class='border'  align="center">Strength Index</td>
    <td class='border'  align="center">% Variation</td>
  </tr>
  <?php
  GetTeamAssessment('A', $current_season, $current_year);
  ?>
  <tr>
    <th colspan=20 align="left"><h1>B Grade</h1></th>
  </tr>
  <tr>
    <td class='border'  align="center">Team Name</td>
    <td class='border'  align="center">Player Name</td>
    <td class='border'  align="center">Won</td>
    <td class='border'  align="center">Played</td>
    <td class='border'  align="center">% Won</td>
    <td class='border'  align="center">Ave Pos</td>
    <td class='border'  align="center">Strength Index</td>
    <td class='border'  align="center">% Variation</td>
    <td class='border'  align='center' style='background-color:black;'></td>
    <td class='border'  align="center">Won</td>
    <td class='border'  align="center">Played</td>
    <td class='border'  align="center">% Won</td>
    <td class='border'  align="center">Ave Pos</td>
    <td class='border'  align="center">Strength Index</td>
    <td class='border'  align="center">% Variation</td>
    <td class='border'  align='center' style='background-color:black;'></td>
    <td class='border'  align="center">Won</td>
    <td class='border'  align="center">Played</td>
    <td class='border'  align="center">% Won</td>
    <td class='border'  align="center">Ave Pos</td>
    <td class='border'  align="center">Strength Index</td>
    <td class='border'  align="center">% Variation</td>
  </tr>
  <?php
  GetTeamAssessment('B', $current_season, $current_year);
  ?>
  <tr>
    <th colspan=20 align="left"><h1>C Grade</h1></th>
  </tr>
  <tr>
    <td class='border'  align="center">Team Name</td>
    <td class='border'  align="center">Player Name</td>
    <td class='border'  align="center">Won</td>
    <td class='border'  align="center">Played</td>
    <td class='border'  align="center">% Won</td>
    <td class='border'  align="center">Ave Pos</td>
    <td class='border'  align="center">Strength Index</td>
    <td class='border'  align="center">% Variation</td>
    <td class='border'  align='center' style='background-color:black;'></td>
    <td class='border'  align="center">Won</td>
    <td class='border'  align="center">Played</td>
    <td class='border'  align="center">% Won</td>
    <td class='border'  align="center">Ave Pos</td>
    <td class='border'  align="center">Strength Index</td>
    <td class='border'  align="center">% Variation</td>
    <td class='border'  align='center' style='background-color:black;'></td>
    <td class='border'  align="center">Won</td>
    <td class='border'  align="center">Played</td>
    <td class='border'  align="center">% Won</td>
    <td class='border'  align="center">Ave Pos</td>
    <td class='border'  align="center">Strength Index</td>
    <td class='border'  align="center">% Variation</td>
  </tr>
  <?php
  GetTeamAssessment('C', $current_season, $current_year);
  ?>
</table>
</body>
</html>

