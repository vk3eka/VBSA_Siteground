<?php require_once('../Connections/connvbsa.php'); 

if (!isset($_SESSION)) 
{
  session_start();
}

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

$year = date('Y');
$month = date('m');

$current_year = date('Y');
if($month < '08')
{
  $current_season = 'S1';
}
else
{
  $current_season = 'S2';
}

function GetGradeData($grade, $member_id, $strength)
{
  global $connvbsa;
  $player_data = [];

  // get all grade data for each player
  $query_A_Test_assess = "Select SUM(pts_won) as won, SUM(avail_pts) as played FROM scrs left join members on members.MemberID = scrs.MemberID WHERE scrs.MemberID = " . $member_id . " and current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND scrs.team_grade LIKE '" . $grade . "%' AND avail_pts > 0 Group by scrs.MemberID";
  $A_Test_assess = mysql_query($query_A_Test_assess, $connvbsa) or die(mysql_error());
  $A_build_assess = mysql_fetch_assoc($A_Test_assess);

  $query_A_Pos_member = "Select AVG(average_position) AS position FROM scrs WHERE average_position > 0 and current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $member_id . " AND team_grade LIKE '" . $grade . "%'";
  $A_Pos_member = mysql_query($query_A_Pos_member, $connvbsa) or die(mysql_error());
  $A_build_Pos_member = mysql_fetch_assoc($A_Pos_member);

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

  if((!isset($A_Played)) || ($A_Played == 0))
  {
    $A_percent = 0;
  }
  else
  {
    $A_percent = (($A_Won/$A_Played)*100);
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
    $A_variation = ((($A_strength)-($strength))/($strength)*100);
  }
  else
  {
    $A_variation = 0;
  }
  $player_data = ($A_Won . ", " . $A_Played . ", " . $A_percent . ", " . $A_position . ", " . $A_strength . ", " . $A_variation);
  return $player_data;
}

function GetTeamAssessment($grade, $current_season, $current_year)
{
  global $connvbsa;

  echo("
      <tr>
        <td class='border'  align='center'>Team Name (Team Grade/Day Played)</td>
        <td class='border'  align='center'>Player Name</td>
        <td class='border'  align='center'>Won</td>
        <td class='border'  align='center'>Played</td>
        <td class='border'  align='center'>% Won</td>
        <td class='border'  align='center'>Ave Pos</td>
        <td class='border'  align='center'>Strength Index</td>
        <td class='border'  align='center'>% Variation</td>
        <td class='border'  align='center' style='background-color:black;'></td>
        <td class='border'  align='center'>Won</td>
        <td class='border'  align='center'>Played</td>
        <td class='border'  align='center'>% Won</td>
        <td class='border'  align='center'>Ave Pos</td>
        <td class='border'  align='center'>Strength Index</td>
        <td class='border'  align='center'>% Variation</td>
        <td class='border'  align='center' style='background-color:black;'></td>
        <td class='border'  align='center'>Won</td>
        <td class='border'  align='center'>Played</td>
        <td class='border'  align='center'>% Won</td>
        <td class='border'  align='center'>Ave Pos</td>
        <td class='border'  align='center'>Strength Index</td>
        <td class='border'  align='center'>% Variation</td>
      </tr>");

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

  $query_C_AVG_Pos = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND (team_grade LIKE 'C%' OR team_grade LIKE 'D%')";
  $C_AVG_Pos = mysql_query($query_C_AVG_Pos, $connvbsa) or die(mysql_error());
  $row_C_AVG_Pos = mysql_fetch_assoc($C_AVG_Pos);

  $query_A_Test_team = "Select Distinct team_name, day_played FROM scrs left join Team_entries on Team_entries.team_id = scrs.team_id  where team_cal_year = " . $current_year . " and team_season = '" . $current_season . "' and Team_entries.team_grade LIKE '" . $grade . "%' Order by day_played, team_name";
  $A_Test_team = mysql_query($query_A_Test_team, $connvbsa) or die(mysql_error());
  while ($build_team = mysql_fetch_assoc($A_Test_team))
  {
    if($build_team['team_name'] != 'Bye')
    {
      $query_A_Test_member = "Select Distinct scrs.MemberID, FirstName, LastName, pts_won, count_played, Team_entries.team_grade, team_name FROM scrs left join Team_entries on Team_entries.team_id = scrs.team_id left join members on scrs.MemberID = members.MemberID WHERE team_name = '" . $build_team['team_name'] . "' and scr_season = '" . $current_season . "' and current_year_scrs = " . $current_year . " and LastName != 'Bye' and LastName != 'Forfeit' AND Team_entries.team_grade LIKE '" . $grade . "%' Group By scrs.MemberID  Order by LastName";
      $A_Test_member = mysql_query($query_A_Test_member, $connvbsa) or die(mysql_error());
      $team_index = 0; 
      while ($build_member = mysql_fetch_assoc($A_Test_member))
      {
        $A_playerData = GetGradeData('A', $build_member['MemberID'], ($row_A_AVG['percent']/$row_A_AVG_Pos['position']));
        $A_player_data = explode(", ", $A_playerData);

        $B_playerData = GetGradeData('B', $build_member['MemberID'], ($row_B_AVG['percent']/$row_B_AVG_Pos['position']));
        $B_player_data = explode(", ", $B_playerData);

        $C_playerData = GetGradeData('C', $build_member['MemberID'], ($row_C_AVG['percent']/$row_C_AVG_Pos['position']));
        $C_player_data = explode(", ", $C_playerData);

        if($team_index == 0) 
        { 
          $team_name = $build_team['team_name'];
          $team_grade = "(" . $build_member['team_grade']; 
          $day_played = "/" . $build_team['day_played'] . ")"; 
        }
        else
        {
          $team_name = '';
          $team_grade = '';
          $day_played = ''; 
        }

        $A_grade_strength = ($row_A_AVG['percent']/$row_A_AVG_Pos['position']);
        $B_grade_strength = ($row_B_AVG['percent']/$row_B_AVG_Pos['position']);
        $C_grade_strength = ($row_C_AVG['percent']/$row_C_AVG_Pos['position']);

        if(($A_player_data[5] != '') && (($A_player_data[5] >= 100) && ($A_player_data[5] <= 200)) && ($A_player_data[5] > 0))
        {
          $A_style = " style='background-color: green; color: white; font-weight:bold' ";
          $A_export_style = "Green";
        }
        elseif(($A_player_data[5] != '') && ($A_player_data[5] > 200) && ($A_player_data[5] > 0))
        {
          $A_style = " style='background-color: red; color: white; font-weight:bold' ";
          $A_export_style = "Red";
        }
        else
        {
          $A_style = '';
          $A_export_style = "";
        }

        if(($B_player_data[5] != '') && (($B_player_data[5] >= 100) && ($B_player_data[5] <= 200)) && ($B_player_data[5] > 0))
        {
          $B_style = " style='background-color: green; color: white; font-weight:bold' ";
          $B_export_style = "Green";
        }
        elseif(($B_player_data[5] != '') && ($B_player_data[5] > 200) && ($B_player_data[5] > 0))
        {
          $B_style = " style='background-color: red; color: white; font-weight:bold' ";
          $B_export_style = "Red";
        }
        else
        {
          $B_style = '';
          $B_export_style = "";
        }
      
        if(($C_player_data[5] != '') && (($C_player_data[5] >= 100) && ($C_player_data[5] <= 200)) && ($C_player_data[5] > 0))
        {
          $C_style = " style='background-color: green; color: white; font-weight:bold' ";
          $C_export_style = "Green";
        }
        elseif(($C_player_data[5] != '') && ($C_player_data[5] > 200) && ($C_player_data[5] > 0))
        {
          $C_style = " style='background-color: red; color: white; font-weight:bold' ";
          $C_export_style = "Red";
        }
        else
        {
          $C_style = '';
          $C_export_style = "";
        }
        
        // display A grade data
        if(($A_player_data[0] == 0) && ($A_player_data[1] == 0))
        {
          $a_won = '';
        }
        else
        {
          $a_won = $A_player_data[0];
        }
        if($A_player_data[1] == 0)
        {
          $a_played = '';
        }
        else
        {
          $a_played = $A_player_data[1];
        }
        if(($A_player_data[2] == 0) && ($A_player_data[1] == 0))
        {
          $a_percent = '';
        }
        else
        {
          $a_percent = number_format($A_player_data[2], 2);
        }
        if($A_player_data[3] == 0)
        {
          $a_position = '';
        }
        else
        {
          $a_position = number_format($A_player_data[3], 2);
        }
        if(($A_player_data[4] == 0) && ($A_player_data[1] == 0))
        {
          $a_strength = '';
        }
        else
        {
          $a_strength = number_format($A_player_data[4], 2);
        }
        if(($A_player_data[5] == 0) && ($A_player_data[1] == 0))
        {
          $a_variation = '';
        }
        else
        {
          $a_variation = number_format($A_player_data[5], 2);
        }

        // display B grade data
        if(($B_player_data[0] == 0) && ($B_player_data[1] == 0))
        {
          $b_won = '';
        }
        else
        {
          $b_won = $B_player_data[0];
        }
        if($B_player_data[1] == 0)
        {
          $b_played = '';
        }
        else
        {
          $b_played = $B_player_data[1];
        }
        if(($B_player_data[2] == 0) && ($B_player_data[1] == 0))
        {
          $b_percent = '';
        }
        else
        {
          $b_percent = number_format($B_player_data[2], 2);
        }
        if($B_player_data[3] == 0)
        {
          $b_position = '';
        }
        else
        {
          $b_position = number_format($B_player_data[3], 2);
        }
        if(($B_player_data[4] == 0) && ($B_player_data[1] == 0))
        {
          $b_strength = '';
        }
        else
        {
          $b_strength = number_format($B_player_data[4], 2);
        }
        if(($B_player_data[5] == 0) && ($B_player_data[1] == 0))
        {
          $b_variation = '';
        }
        else
        {
          $b_variation = number_format($B_player_data[5], 2);
        }

        // display C grade data
        if(($C_player_data[0] == 0) && ($C_player_data[1] == 0))
        {
          $c_won = '';
        }
        else
        {
          $c_won = $C_player_data[0];
        }
        if($C_player_data[1] == 0)
        {
          $c_played = '';
        }
        else
        {
          $c_played = $C_player_data[1];
        }
        if(($C_player_data[2] == 0) && ($C_player_data[1] == 0))
        {
          $c_percent = '';
        }
        else
        {
          $c_percent = number_format($C_player_data[2], 2);
        }
        if($C_player_data[3] == 0)
        {
          $c_position = '';
        }
        else
        {
          $c_position = number_format($C_player_data[3], 2);
        }
        if(($C_player_data[4] == 0) && ($C_player_data[1] == 0))
        {
          $c_strength = '';
        }
        else
        {
          $c_strength = number_format($C_player_data[4], 2);
        }
        if(($C_player_data[5] == 0) && ($C_player_data[1] == 0))
        {
          $c_variation = '';
        }
        else
        {
          $c_variation = number_format($C_player_data[5], 2);
        }

        echo "<tr>";
        echo "<td class='border'  align='center'>" . $team_name . " " . $team_grade . $day_played . "</td>";
        echo "<td class='border'  align='center'>" . $build_member['FirstName'] . " " . $build_member['LastName'] . "</td>";
        echo "<td class='border'  align='center'>" . $a_won . "</td>";
        echo "<td class='border'  align='center'>" . $a_played . "</td>";
        echo "<td class='border'  align='center'>" . $a_percent . "</td>";
        echo "<td class='border'  align='center'>" . $a_position . "</td>";
        echo "<td class='border'  align='center'>" . $a_strength . "</td>";
        echo "<td class='border'  align='center' " . $A_style . ">" . $a_variation . "</td>";
        echo "<td class='border'  align='center' style='background-color:black;'></td>";
        echo "<td class='border'  align='center'>" . $b_won . "</td>";
        echo "<td class='border'  align='center'>" . $b_played . "</td>";
        echo "<td class='border'  align='center'>" . $b_percent . "</td>";
        echo "<td class='border'  align='center'>" . $b_position . "</td>";
        echo "<td class='border'  align='center'>" . $b_strength . "</td>";
        echo "<td class='border'  align='center' " . $B_style . ">" . $b_variation . "</td>";
        echo "<td class='border'  align='center' style='background-color:black;'></td>";
        echo "<td class='border'  align='center'>" . $c_won . "</td>";
        echo "<td class='border'  align='center'>" . $c_played . "</td>";
        echo "<td class='border'  align='center'>" . $c_percent . "</td>";
        echo "<td class='border'  align='center'>" . $c_position . "</td>";
        echo "<td class='border'  align='center'>" . $c_strength . "</td>";
        echo "<td class='border'  align='center' " . $C_style . ">" . $c_variation . "</td>";
        echo "</tr>";
        if($team_index == ($A_Test_member->num_rows-1))
        {
          echo("<tr><td colspan='22' class='border' align='center'>&nbsp;</td></tr>");
        }

        // get array for csv export
        $player_array_grade[] = 
        ['grade' => $grade, 
        'team_name' => $build_team['team_name'],
        'name' => $build_member['FirstName'] . " " . $build_member['LastName'],
        'AWon' => $a_won,
        'APlayed' => $a_played,
        'APercent' => $a_percent,
        'APosition' => $a_position,
        'AStrength' => $a_strength,
        'AExport' => $A_export_style,
        'AVariation' => $a_variation,
        'BWon' => $b_won,
        'BPlayed' => $b_played,
        'BPercent' => $b_percent,
        'BPosition' => $b_position,
        'BStrength' => $b_strength, 
        'BExport' => $B_export_style,
        'BVariation' => $b_variation,
        'CWon' => $c_won,
        'CPlayed' => $c_played,
        'CPercent' => $c_percent,
        'CPosition' => $c_position,
        'CStrength' => $c_strength,
        'CExport' => $C_export_style,
        'CVariation' => $c_variation];

        $team_index++;

      }
    }
  }
  return ($player_array_grade);
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

$query_C_AVG_Pos = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND (team_grade LIKE 'C%' OR team_grade LIKE 'D%')";
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
<script language="JavaScript" type="text/JavaScript">

function ExportCSV() 
{
  
  document.export.ButtonName.value = "ExportData";
  document.export.submit();
}

</script>
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
<form name="export" method="post" action="export_team_assessment.php">
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
<table width="500" align="center">
  <tr>
    <td colspan="2" align="center" class="greenbg"></td>
  </tr>
  <tr>
    <td align="center" style='background-color: green; color: white; font-weight:bold' width='150px' height='20px'>% Variation is between 100 & 200</td>
    <td align="center" style='background-color: red; color: white; font-weight:bold' width='150px' height='20px'>% Variation is greater than 200</td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="greenbg"></td>
  </tr>
      <td colspan="2" class="greenbg" align="center"><a href="javascript:;" onclick="ExportCSV();">Export Data To CSV File</a></td>
   <tr>
    <td colspan="2" align="center" class="greenbg"></td>
  </tr>
</table>
<table align="center" class='border' cellpadding="5" cellspacing="5">
  <tr>
    <th colspan=20 align="left"><h1>A Grade</h1></th>
  </tr>
  <tr>
    <th class='border' colspan=2>Teams and Players Currently Registered</th>
    <th class='border' colspan=6 align="center">A Grade</th>
    <td class='border'  align="center" style='background-color:black;'></td>
    <th class='border' colspan=6 align="center">B Grade</th>
    <td class='border'  align="center" style='background-color:black;'></td>
    <th class='border' colspan=6 align="center">C Grade</th>
  </tr>
  <tr>
    <td class='border' colspan=2 align="center">&nbsp;</td>
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
    <th colspan=20>&nbsp;</th>
  </tr>
  <?php
  $player_array_A = GetTeamAssessment('A', $current_season, $current_year);
  ?>
  <tr>
    <th colspan=20 align="left"><h1>B Grade</h1></th>
  </tr>
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
    <th colspan=20>&nbsp;</th>
  </tr>
  <?php
  $player_array_B = GetTeamAssessment('B', $current_season, $current_year);
  ?>
  <tr>
    <th colspan=20 align="left"><h1>C Grade</h1></th>
  </tr>
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
    <th colspan=20>&nbsp;</th>
  </tr>
  <?php
  $player_array_C = GetTeamAssessment('C', $current_season, $current_year);
  ?>
</table>

<?php

  $header_array = $A_player_AVG . ", " . $A_player_Pos . ", " . $A_player_Strength . ", " . $B_player_AVG . ", " . $B_player_Pos . ", " . $B_player_Strength . ", " . $C_player_AVG . ", " . $C_player_Pos . ", " . $C_player_Strength;
  $_SESSION['HeaderData'] = $header_array;
  $player_array = array_merge($player_array_A, $player_array_B, $player_array_C);
  $_SESSION['PlayerData'] = $player_array;
?>

<input type='hidden' name='ButtonName' id='ButtonName' value='ExportData'>
</form>
</body>
</html>

