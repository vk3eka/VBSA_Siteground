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
/*
function GetTeamName($team_id)
{
  global $connvbsa;
  $sql = "Select team_name FROM vbsa3364_vbsa2.Team_entries where team_id = " . $team_id;
  $result = mysql_query($sql, $connvbsa) or die(mysql_error());
  $row_data = mysql_fetch_assoc($result);
  return $row_data['team_name'];
}
function GetPlayerName($member_id)
{
  global $connvbsa;
  $sql = "Select FirstName, LastName FROM vbsa3364_vbsa2.members where MemberID = " . $member_id;
  $result = mysql_query($sql, $connvbsa) or die(mysql_error());
  $row_data = mysql_fetch_assoc($result);
  return $row_data['FirstName'] . " " . $row_data['LastName'];
}
*/

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

  //echo($grade . "<br>");
  $query_A_Test_team = "Select Distinct team_name FROM scrs left join Team_entries on Team_entries.team_id = scrs.team_id  where team_cal_year = '$current_year' and team_season = '$current_season' and scrs.team_grade LIKE '" . $grade . "%' Order by team_name";
  $A_Test_team = mysql_query($query_A_Test_team, $connvbsa) or die(mysql_error());
  while ($build_team = mysql_fetch_assoc($A_Test_team))
  {
    //echo($build_team['team_name'] . "<br>");
    if($build_team['team_name'] != 'Bye')
    {
      $query_A_Test_member = "Select Distinct scrs.MemberID, FirstName, LastName, pts_won, count_played FROM scrs left join Team_entries on Team_entries.team_id = scrs.team_id left join members on scrs.MemberID = members.MemberID WHERE team_name = '" . $build_team['team_name'] . "' and LastName != 'Bye' and current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND scrs.team_grade LIKE '" . $grade . "%' Group By scrs.MemberID  Order by LastName";
      //echo($query_A_Test_member  . "<br>");
      $A_Test_member = mysql_query($query_A_Test_member, $connvbsa) or die(mysql_error());
      $team_index = 0; 
      while ($build_member = mysql_fetch_assoc($A_Test_member))
      {
        // get all A grade data for each player
        $query_A_Test_assess = "Select SUM(pts_won) as won, SUM(count_played) as played FROM scrs left join members on members.MemberID = scrs.MemberID WHERE scrs.MemberID = " . $build_member['MemberID'] . " and current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND scrs.team_grade LIKE 'A%' Group by scrs.MemberID";
        //echo($query_A_Test_assess  . "<br>");
        $A_Test_assess = mysql_query($query_A_Test_assess, $connvbsa) or die(mysql_error());
        $A_build_assess = mysql_fetch_assoc($A_Test_assess);

        $query_A_AVG_member = "Select AVG(percent_won) AS percent FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND team_grade LIKE 'A%'";
        //echo($query_A_AVG_member  . "<br>");
        $A_AVG_member = mysql_query($query_A_AVG_member, $connvbsa) or die(mysql_error());
        $A_build_AVG_member = mysql_fetch_assoc($A_AVG_member);

        $query_A_Pos_member = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND team_grade LIKE 'A%'";
        //echo($query_A_Pos_member  . "<br>");
        $A_Pos_member = mysql_query($query_A_Pos_member, $connvbsa) or die(mysql_error());
        $A_build_Pos_member = mysql_fetch_assoc($A_Pos_member);

        //if($A_build_Pos_member['position'] != 0)
        //{
        //echo($team_name . " " . $A_build_Pos_member['position'] . " " . $A_build_AVG_member['percent'] . "<br>");
        //}


        //if(!isset($A_build_Pos_member['position']) || ($A_build_Pos_member['position'] == '') || ($A_build_Pos_member['position'] < 1)) 
        if(($A_build_Pos_member['position'] > 0) && ($A_build_AVG_member['percent'] > 0))
        { 
          //$A_strength = 1; 
          $A_strength = ($A_build_AVG_member['percent']/$A_build_Pos_member['position']); 
        }
        else
        { 
          $A_strength = 0;
        }

        if(($A_build_Pos_member['position'] > 0) && ($A_build_AVG_member['percent'] > 0))
        //if(!isset($A_build_Pos_member['position']) || ($A_build_Pos_member['position'] == '') || ($A_build_Pos_member['position'] < 1)) 
        {
          //$A_variation = 1;
          $A_variation = (($A_build_AVG_member['percent']/$A_build_Pos_member['position'])-($row_A_AVG['percent']/$row_A_AVG_Pos['position'])/($row_A_AVG['percent']/$row_A_AVG_Pos['position']));
        }
        else
        {
          $A_variation = 0;
        }

        //echo($team_name . "<br>");
        //echo($team_name . " " . $A_variation . " " . $A_strength . "<br>");

        if($team_index == 0) 
        { 
          $team_name = $build_team['team_name']; 
        }
        else
        {
          $team_name = '';
        }

        // get all B grade data for each player
        $query_B_Test_assess = "Select SUM(pts_won) as won, SUM(count_played) as played FROM scrs left join members on members.MemberID = scrs.MemberID WHERE scrs.MemberID = " . $build_member['MemberID'] . " and current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND scrs.team_grade LIKE 'B%' Group by scrs.MemberID";
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
          $B_Played = $B_build_assess['won'];
        }
        //echo($build_member['MemberID'] . "<br>");
        $query_B_AVG_member = "Select AVG(percent_won) AS percent FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND team_grade LIKE 'B%'";
        //echo($query_B_AVG_member . "<br>");
        $B_AVG_member = mysql_query($query_B_AVG_member, $connvbsa) or die(mysql_error());
        $B_build_AVG_member = mysql_fetch_assoc($B_AVG_member);

        $query_B_Pos_member = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND team_grade LIKE 'B%'";
        //echo($query_B_Pos_member . "<br>");
        $B_Pos_member = mysql_query($query_B_Pos_member, $connvbsa) or die(mysql_error());
        $B_build_Pos_member = mysql_fetch_assoc($B_Pos_member);

        if(($B_build_Pos_member['position'] > 0) && ($B_build_AVG_member['percent'] > 0))
        //if($B_build_Pos_member['position'] > 0) 
        { 
          $B_strength = ($B_build_AVG_member['percent']/$B_build_Pos_member['position']); 
        }
        else
        { 
          $B_strength = 0;
        }
        //echo("Strength " . $B_strength . "<br>");
        //echo("Pos " . $B_build_Pos_member['position'] . "<br>");
        //echo("Percent " . $B_build_AVG_member['percent'] . "<br>");
        //echo("Percent B " . $row_B_AVG['percent'] . "<br>");

        if(($B_build_Pos_member['position'] > 0) && ($B_build_AVG_member['percent'] > 0))
        //if(($B_build_Pos_member['position']) > 0)
        {
          $B_variation = (($B_build_AVG_member['percent']/$B_build_Pos_member['position'])-($row_B_AVG['percent']/$row_B_AVG_Pos['position'])/($row_B_AVG['percent']/$row_B_AVG_Pos['position']));
        }
        else
        {
          $B_variation = 0;
        }
        //echo("Var " . $B_variation . "<br>");

        // get all C grade data for each player
        $query_C_Test_assess = "Select SUM(pts_won) as won, SUM(count_played) as played FROM scrs left join members on members.MemberID = scrs.MemberID WHERE scrs.MemberID = " . $build_member['MemberID'] . " and current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND (scrs.team_grade LIKE 'C%' OR scrs.team_grade LIKE 'D%') Group by scrs.MemberID";
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
          $C_Played = $C_build_assess['won'];
        }

        $query_C_AVG_member = "Select AVG(percent_won) AS percent FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND (scrs.team_grade LIKE 'C%' OR scrs.team_grade LIKE 'D%')";
        $C_AVG_member = mysql_query($query_C_AVG_member, $connvbsa) or die(mysql_error());
        $C_build_AVG_member = mysql_fetch_assoc($C_AVG_member);

        $query_C_Pos_member = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND (scrs.team_grade LIKE 'C%' OR scrs.team_grade LIKE 'D%')";
        //echo($query_C_Pos_member . "<br>");
        $C_Pos_member = mysql_query($query_C_Pos_member, $connvbsa) or die(mysql_error());
        $C_build_Pos_member = mysql_fetch_assoc($C_Pos_member);

        //if(($C_build_Pos_member['position'] > 0) && ($C_build_AVG_member['percent'] > 0))
        if(!isset($C_build_Pos_member['position']) || ($C_build_Pos_member['position'] == '') || ($C_build_Pos_member['position'] < 1)) 
        { 
          $C_strength = 0;
        }
        else
        { 
          $C_strength = ($C_build_AVG_member['percent']/$C_build_Pos_member['position']); 
        }

        //if(($C_build_Pos_member['position'] > 0) && ($C_build_AVG_member['percent'] > 0))
        if(!isset($C_build_Pos_member['position']) || ($C_build_Pos_member['position'] == '') || ($C_build_Pos_member['position'] < 1)) 
        {
          $C_variation = 0;
        }
        else
        {
          $C_variation = (($C_build_AVG_member['percent']/$C_build_Pos_member['position'])-($row_C_AVG['percent']/$row_C_AVG_Pos['position'])/($row_C_AVG['percent']/$row_C_AVG_Pos['position']));
        }

        //echo("Strength " . $C_strength . "<br>");
        //echo("Pos " . $C_build_Pos_member['position'] . "<br>");
        //echo("Percent " . $C_build_AVG_member['percent'] . "<br>");
        //echo("Percent B " . $row_C_AVG['percent'] . "<br>");

        //echo($team_name . " " . $build_member['FirstName'] . " " . $build_member['LastName'] . "<br>");


        //$player[] = $team_name . ", " . $build_member['FirstName'] . " " . $build_member['LastName'] . ", " . $A_build_assess['won'] . ", " . $A_build_assess['played'] . ", " . number_format($A_build_AVG_member['percent'], 2) . ", " . number_format($A_build_Pos_member['position'], 2) . ", " . number_format($A_strength, 2) . ", " . number_format($A_variation, 2) . ", " . $B_Won . ", " . $B_Played  . ", " . number_format($B_build_AVG_member['percent'], 2) . ", " . number_format($B_build_Pos_member['position'], 2) . ", " . number_format($B_strength, 2) . ", " . number_format($B_variation, 2) . ", " . $C_Won . ", " . $C_Played . ", " . number_format($C_build_AVG_member['percent'], 2) . ", " . number_format($C_build_Pos_member['position'], 2) . ", " . number_format($C_strength, 2) . ", " . number_format($C_variation, 2);
        //echo($player[$team_index] . "<br>");
        //echo($team_index . "<br>");
        //$player[] = $team_name . ", " . $build_member['FirstName'] . " " . $build_member['LastName'] . ", " . $A_variation . ", " . $A_strength;

        echo 
        "<tr>
          <td class='border'  align='center'>" . $team_name . "</td>
          <td class='border'  align='center'>" . $build_member['FirstName'] . " " . $build_member['LastName'] . "</td>
          <td class='border'  align='center'>" . $A_build_assess['won'] . "</td>
          <td class='border'  align='center'>" . $A_build_assess['played'] . "</td>
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
        $team_index++;
      }
    }
  }
  //echo($player);
}
//GetTeamAssessment('B', $current_season, $current_year);
//cho("<pre>");
//echo(var_dump(GetTeamAssessment('B', $current_season, $current_year)));
//echo("</pre>");

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

/*
$query_A_Test_team = "Select Distinct team_name FROM scrs left join Team_entries on Team_entries.team_id = scrs.team_id  where team_cal_year = '$current_year' and team_season = '$current_season' and scrs.team_grade LIKE 'A%' Order by team_name";
$A_Test_team = mysql_query($query_A_Test_team, $connvbsa) or die(mysql_error());

$query_B_Test_team = "Select Distinct team_name FROM scrs left join Team_entries on Team_entries.team_id = scrs.team_id  where team_cal_year = '$current_year' and team_season = '$current_season' and scrs.team_grade LIKE 'B%' Order by team_name";
$B_Test_team = mysql_query($query_B_Test_team, $connvbsa) or die(mysql_error());

$query_C_Test_team = "Select Distinct team_name FROM scrs left join Team_entries on Team_entries.team_id = scrs.team_id  where team_cal_year = '$current_year' and team_season = '$current_season' and (scrs.team_grade LIKE 'C%' OR scrs.team_grade LIKE 'D%') Order by team_name";
$C_Test_team = mysql_query($query_C_Test_team, $connvbsa) or die(mysql_error());
*/

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

  /*
  while ($build_team = mysql_fetch_assoc($A_Test_team))
  {
    if($build_team['team_name'] != 'Bye')
    {
      $query_A_Test_member = "Select Distinct scrs.MemberID, FirstName, LastName, pts_won, count_played, avail_pts FROM scrs left join Team_entries on Team_entries.team_id = scrs.team_id left join members on scrs.MemberID = members.MemberID WHERE team_name = '" . $build_team['team_name'] . "' and LastName != 'Bye' and LastName != 'Forfeit' and count_played > 0 and current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND scrs.team_grade LIKE 'A%' Group By scrs.MemberID  Order by LastName";
      $A_Test_member = mysql_query($query_A_Test_member, $connvbsa) or die(mysql_error());
      //echo("Team members " . $A_Test_member->num_rows . "<br>");
      $team_index = 0; 
      while ($build_member = mysql_fetch_assoc($A_Test_member))
      {
        // get all A grade data for each player
        $query_A_Test_assess = "Select SUM(pts_won) as won, SUM(avail_pts) as played FROM scrs left join members on members.MemberID = scrs.MemberID WHERE scrs.MemberID = " . $build_member['MemberID'] . " and current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND scrs.team_grade LIKE 'A%'";
        $A_Test_assess = mysql_query($query_A_Test_assess, $connvbsa) or die(mysql_error());
        $A_build_assess = mysql_fetch_assoc($A_Test_assess);

        $query_A_AVG_member = "Select AVG(percent_won) AS percent FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND team_grade LIKE 'A%'";
        $A_AVG_member = mysql_query($query_A_AVG_member, $connvbsa) or die(mysql_error());
        $A_build_AVG_member = mysql_fetch_assoc($A_AVG_member);

        $query_A_Pos_member = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND team_grade LIKE 'A%'";
        $A_Pos_member = mysql_query($query_A_Pos_member, $connvbsa) or die(mysql_error());
        $A_build_Pos_member = mysql_fetch_assoc($A_Pos_member);
        $A_position = number_format($A_build_AVG_member['position'], 2);

        if($A_build_AVG_member['percent'] > 0) 
        { 
          $A_percent = number_format($A_build_AVG_member['percent'], 2); 
        }
        else
        { 
          $A_percent = 0;
        }
        if($A_build_Pos_member['position'] > 0) 
        { 
          $A_position = number_format($A_build_Pos_member['position'], 2); 
        }
        else
        { 
          $A_position = 0;
        }


        if($A_build_assess['won'] != 0) 
        { 
          $A_won = $A_build_assess['won']; 
        }
        else
        { 
          $A_won = 0;
        }
        if($A_build_assess['played'] != 0) 
        { 
          $A_played = $A_build_assess['played']; 
        }
        else
        { 
          $A_played = 0;
        }


        if($A_build_Pos_member['position'] > 0) 
        { 
          $A_strength = number_format(($A_build_AVG_member['percent']/$A_build_Pos_member['position']), 2); 
        }
        else
        { 
          $A_strength = 0;
        }

        if(($A_build_Pos_member['position']) > 0)
        {
          $A_variation = number_format(((($A_strength-$A_player_Strength)/$A_player_Strength)*100), 2);
        }
        else
        {
          $A_variation = 0;
        }

        if($team_index == 0) 
        { 
          $team_name =  $build_team['team_name']; 
        }
        else
        {
          $team_name = '';
        }

        // get all B grade data for each player
        $query_B_Test_assess = "Select SUM(pts_won) as won, SUM(avail_pts) as played FROM scrs left join members on members.MemberID = scrs.MemberID WHERE scrs.MemberID = " . $build_member['MemberID'] . " and current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND scrs.team_grade LIKE 'B%'";
        $B_Test_assess = mysql_query($query_B_Test_assess, $connvbsa) or die(mysql_error());
        $B_build_assess = mysql_fetch_assoc($B_Test_assess);

        if($B_build_AVG_member['percent'] > 0) 
        { 
          $B_percent = number_format($B_build_AVG_member['percent'], 2); 
        }
        else
        { 
          $B_percent = 0;
        }
        if($B_build_Pos_member['position'] > 0) 
        { 
          $B_position = number_format($B_build_Pos_member['position'], 2); 
        }
        else
        { 
          $B_position = 0;
        }

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
          $B_Played = $B_build_assess['won'];
        }

        $query_B_AVG_member = "Select AVG(percent_won) AS percent FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND team_grade LIKE 'B%'";
        $B_AVG_member = mysql_query($query_B_AVG_member, $connvbsa) or die(mysql_error());
        $B_build_AVG_member = mysql_fetch_assoc($B_AVG_member);

        $query_B_Pos_member = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND team_grade LIKE 'B%'";
        $B_Pos_member = mysql_query($query_B_Pos_member, $connvbsa) or die(mysql_error());
        $B_build_Pos_member = mysql_fetch_assoc($B_Pos_member);

        if($B_build_Pos_member['position'] > 0) 
        { 
          $B_strength = number_format(($B_build_AVG_member['percent']/$B_build_Pos_member['position']), 2); 
        }
        else
        { 
          $B_strength = 0;
        }

        if(($B_build_Pos_member['position']) > 0)
        {
          $B_variation = number_format(((($B_strength-$B_player_Strength)/$B_player_Strength)*100), 2);
          //$B_variation = (($B_build_AVG_member['percent']/$B_build_Pos_member['position'])-($row_B_AVG['percent']/$row_B_AVG_Pos['position'])/($row_B_AVG['percent']/$row_B_AVG_Pos['position']));
        }
        else
        {
          $B_variation = 0;
        }

        // get all C grade data for each player
        $query_C_Test_assess = "Select SUM(pts_won) as won, SUM(avail_pts) as played FROM scrs left join members on members.MemberID = scrs.MemberID WHERE scrs.MemberID = " . $build_member['MemberID'] . " and current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND (scrs.team_grade LIKE 'C%' OR scrs.team_grade LIKE 'D%')";
        $C_Test_assess = mysql_query($query_C_Test_assess, $connvbsa) or die(mysql_error());
        $C_build_assess = mysql_fetch_assoc($C_Test_assess);

        if($C_build_AVG_member['percent'] > 0) 
        { 
          $C_percent = number_format($C_build_AVG_member['percent'], 2); 
        }
        else
        { 
          $C_percent = 0;
        }
        if($C_build_Pos_member['position'] > 0) 
        { 
          $C_position = number_format($C_build_Pos_member['position'], 2); 
        }
        else
        { 
          $C_position = 0;
        }

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
          $C_Played = $C_build_assess['won'];
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
          $C_strength = number_format(($C_build_AVG_member['percent']/$C_build_Pos_member['position']), 2); 
        }

        if(!isset($C_build_Pos_member['position']) || ($C_build_Pos_member['position'] == '') || ($C_build_Pos_member['position'] < 1)) 
        {
          $C_variation = 0;
        }
        else
        {
          $C_variation = number_format(((($C_strength-$C_player_Strength)/$C_player_Strength)*100), 2);
          //$C_variation = (($C_build_AVG_member['percent']/$C_build_Pos_member['position'])-($row_C_AVG['percent']/$row_C_AVG_Pos['position'])/($row_C_AVG['percent']/$row_C_AVG_Pos['position']));
        }
        echo 
        "<tr>
          <td class='border'  align='center'>" . $team_name . "</td>
          <td class='border'  align='center'>" . $build_member['FirstName'] . " " . $build_member['LastName'] . "</td>
          <td class='border'  align='center'>" . $A_won . "</td>
          <td class='border'  align='center'>" . $A_played . "</td>
          <td class='border'  align='center'>" . $A_percent . "</td>
          <td class='border'  align='center'>" . $A_position . "</td>
          <td class='border'  align='center'>" . $A_strength . "</td>
          <td class='border'  align='center'>" . $A_variation . "</td>
          <td class='border'  align='center' style='background-color:black;'></td>
          <td class='border'  align='center'>" . $B_Won . "</td>
          <td class='border'  align='center'>" . $B_Played . "</td>
          <td class='border'  align='center'>" . $B_percent . "</td>
          <td class='border'  align='center'>" . $B_position . "</td>
          <td class='border'  align='center'>" . $B_strength . "</td>
          <td class='border'  align='center'>" . $B_variation . "</td>
          <td class='border'  align='center' style='background-color:black;'></td>
          <td class='border'  align='center'>" . $C_Won . "</td>
          <td class='border'  align='center'>" . $C_Played . "</td>
          <td class='border'  align='center'>" . $C_percent . "</td>
          <td class='border'  align='center'>" . $C_position . "</td>
          <td class='border'  align='center'>" . $C_strength . "</td>
          <td class='border'  align='center'>" . $C_variation . "</td>
        </tr>";  
        //echo("Index " . $team_index . "<br>");
        //echo("Rows " . ($A_Test_member->num_rows-1) . "<br>");
        if($team_index == ($A_Test_member->num_rows-1))
        {
          echo("<tr><td colspan='22' class='border' align='center'>&nbsp;</td></tr>");
        }
        $team_index++;
      }
    }
  }
*/  ?>
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
  /*
  while ($build_team = mysql_fetch_assoc($B_Test_team))
  {
    if($build_team['team_name'] != 'Bye')
    {
      $query_A_Test_member = "Select Distinct scrs.MemberID, FirstName, LastName, pts_won, count_played, avail_pts FROM scrs left join Team_entries on Team_entries.team_id = scrs.team_id left join members on scrs.MemberID = members.MemberID WHERE team_name = '" . $build_team['team_name'] . "' and LastName != 'Bye' and LastName != 'Forfeit' and count_played > 0 and current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND scrs.team_grade LIKE 'B%' Group By scrs.MemberID  Order by LastName";
      $A_Test_member = mysql_query($query_A_Test_member, $connvbsa) or die(mysql_error());
      $team_index = 0; 
      while ($build_member = mysql_fetch_assoc($A_Test_member))
      {
        $query_A_Test_assess = "Select SUM(pts_won) as won, SUM(avail_pts) as played FROM scrs left join Team_entries on Team_entries.team_id = scrs.team_id left join members on members.MemberID = scrs.MemberID WHERE scrs.MemberID = " . $build_member['MemberID'] . " and current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND scrs.team_grade LIKE 'B%' Group by scrs.MemberID";
        $A_Test_assess = mysql_query($query_A_Test_assess, $connvbsa) or die(mysql_error());
        $A_build_assess = mysql_fetch_assoc($A_Test_assess);

        $query_A_AVG_member = "Select AVG(percent_won) AS percent FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND team_grade LIKE 'B%'";
        $A_AVG_member = mysql_query($query_A_AVG_member, $connvbsa) or die(mysql_error());
        $build_AVG_member = mysql_fetch_assoc($A_AVG_member);

        $query_A_Pos_member = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND team_grade LIKE 'B%'";
        $A_Pos_member = mysql_query($query_A_Pos_member, $connvbsa) or die(mysql_error());
        $build_Pos_member = mysql_fetch_assoc($A_Pos_member);

        if($A_build_assess['won'] > 0) 
        { 
          $A_won = $A_build_assess['won']; 
        }
        else
        { 
          $A_won = 0;
        }
        if($A_build_assess['played'] > 0) 
        { 
          $A_played = $A_build_assess['played']; 
        }
        else
        { 
          $A_played = 0;
        }

        if($build_Pos_member['position'] > 0) 
        { 
          $strength = ($build_AVG_member['percent']/$build_Pos_member['position']); 
        }
        else
        { 
          $strength = 0;
        }

        if(($build_Pos_member['position']) > 0)
        {
          $variation = (($build_AVG_member['percent']/$build_Pos_member['position'])-($row_A_AVG['percent']/$row_A_AVG_Pos['position'])/($row_A_AVG['percent']/$row_A_AVG_Pos['position']));
        }
        else
        {
          $variation = 0;
        }

        if($team_index == 0) 
        { 
          $team_name =  $build_team['team_name']; 
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
          $B_Played = $B_build_assess['won'];
        }

        $query_B_AVG_member = "Select AVG(percent_won) AS percent FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND team_grade LIKE 'B%'";
        $B_AVG_member = mysql_query($query_B_AVG_member, $connvbsa) or die(mysql_error());
        $B_build_AVG_member = mysql_fetch_assoc($B_AVG_member);

        $query_B_Pos_member = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND team_grade LIKE 'B%'";
        $B_Pos_member = mysql_query($query_B_Pos_member, $connvbsa) or die(mysql_error());
        $B_build_Pos_member = mysql_fetch_assoc($B_Pos_member);

        if($B_build_Pos_member['position'] > 0) 
        { 
          $B_strength = ($B_build_AVG_member['percent']/$B_build_Pos_member['position']); 
        }
        else
        { 
          $B_strength = 0;
        }

        if(($B_build_Pos_member['position']) > 0)
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
          $C_Played = $C_build_assess['won'];
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

        echo 
        "<tr>
          <td class='border'  align='center'>" . $team_name . "</td>
          <td class='border'  align='center'>" . $build_member['FirstName'] . " " . $build_member['LastName'] . "</td>
          <td class='border'  align='center'>" . $A_won . "</td>
          <td class='border'  align='center'>" . $A_played . "</td>
          <td class='border'  align='center'>" . number_format($build_AVG_member['percent'], 2) . "</td>
          <td class='border'  align='center'>" . number_format($build_Pos_member['position'], 2) . "</td>
          <td class='border'  align='center'>" . number_format($strength, 2) . "</td>
          <td class='border'  align='center'>" . number_format($variation, 2) . "</td>
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
        if($team_index == ($A_Test_member->num_rows-1))
        {
          echo("<tr><td colspan='22' class='border' align='center'>&nbsp;</td></tr>");
        } 
        $team_index++;
      }
    }
  }
*/  ?>
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
/*
  while ($build_team = mysql_fetch_assoc($C_Test_team))
  {
    if($build_team['team_name'] != 'Bye')
    {
      $query_A_Test_member = "Select Distinct scrs.MemberID, FirstName, LastName, pts_won, count_played, avail_pts FROM scrs left join Team_entries on Team_entries.team_id = scrs.team_id left join members on scrs.MemberID = members.MemberID WHERE team_name = '" . $build_team['team_name'] . "' and LastName != 'Bye' and LastName != 'Forfeit' and count_played > 0 and current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND (scrs.team_grade LIKE 'C%' OR scrs.team_grade LIKE 'D%') Group By scrs.MemberID Order by LastName";
      $A_Test_member = mysql_query($query_A_Test_member, $connvbsa) or die(mysql_error());
      $team_index = 0; 
      while ($build_member = mysql_fetch_assoc($A_Test_member))
      {
        $query_A_Test_assess = "Select SUM(pts_won) as won, SUM(avail_pts) as played FROM scrs left join Team_entries on Team_entries.team_id = scrs.team_id left join members on members.MemberID = scrs.MemberID WHERE scrs.MemberID = " . $build_member['MemberID'] . " and current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND (scrs.team_grade LIKE 'C%' OR scrs.team_grade LIKE 'D%') Group by scrs.MemberID";
        $A_Test_assess = mysql_query($query_A_Test_assess, $connvbsa) or die(mysql_error());
        $A_build_assess = mysql_fetch_assoc($A_Test_assess);

        $query_A_AVG_member = "Select AVG(percent_won) AS percent FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND (scrs.team_grade LIKE 'C%' OR scrs.team_grade LIKE 'D%')";
        $A_AVG_member = mysql_query($query_A_AVG_member, $connvbsa) or die(mysql_error());
        $build_AVG_member = mysql_fetch_assoc($A_AVG_member);

        $query_A_Pos_member = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND (scrs.team_grade LIKE 'C%' OR scrs.team_grade LIKE 'D%')";
        $A_Pos_member = mysql_query($query_A_Pos_member, $connvbsa) or die(mysql_error());
        $build_Pos_member = mysql_fetch_assoc($A_Pos_member);

        if($A_build_assess['won'] > 0) 
        { 
          $A_won = $A_build_assess['won']; 
        }
        else
        { 
          $A_won = 0;
        }
        if($A_build_assess['played'] > 0) 
        { 
          $A_played = $A_build_assess['played']; 
        }
        else
        { 
          $A_played = 0;
        }

        if($build_Pos_member['position'] > 0) 
        { 
          $strength = ($build_AVG_member['percent']/$build_Pos_member['position']); 
        }
        else
        { 
          $strength = 0;
        }

        if(($build_Pos_member['position']) > 0)
        {
          $variation = (($build_AVG_member['percent']/$build_Pos_member['position'])-($row_A_AVG['percent']/$row_A_AVG_Pos['position'])/($row_A_AVG['percent']/$row_A_AVG_Pos['position']));
        }
        else
        {
          $variation = 0;
        }

        if($team_index == 0) 
        { 
          $team_name =  $build_team['team_name']; 
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
          $B_Played = $B_build_assess['won'];
        }

        $query_B_AVG_member = "Select AVG(percent_won) AS percent FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND team_grade LIKE 'B%'";
        $B_AVG_member = mysql_query($query_B_AVG_member, $connvbsa) or die(mysql_error());
        $B_build_AVG_member = mysql_fetch_assoc($B_AVG_member);

        $query_B_Pos_member = "Select AVG(average_position) AS position FROM scrs WHERE current_year_scrs > DATE_ADD(NOW(), INTERVAL -3 YEAR) AND MemberID = " . $build_member['MemberID'] . " AND team_grade LIKE 'B%'";
        $B_Pos_member = mysql_query($query_B_Pos_member, $connvbsa) or die(mysql_error());
        $B_build_Pos_member = mysql_fetch_assoc($B_Pos_member);

        if($B_build_Pos_member['position'] > 0) 
        { 
          $B_strength = ($B_build_AVG_member['percent']/$B_build_Pos_member['position']); 
        }
        else
        { 
          $B_strength = 0;
        }

        if(($B_build_Pos_member['position']) > 0)
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
          $C_Played = $C_build_assess['won'];
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
      
        echo 
        "<tr>
          <td class='border'  align='center'>" . $team_name . "</td>
          <td class='border'  align='center'>" . $build_member['FirstName'] . " " . $build_member['LastName'] . "</td>
          <td class='border'  align='center'>" . $A_won . "</td>
          <td class='border'  align='center'>" . $A_played . "</td>
          <td class='border'  align='center'>" . number_format($build_AVG_member['percent'], 2) . "</td>
          <td class='border'  align='center'>" . number_format($build_Pos_member['position'], 2) . "</td>
          <td class='border'  align='center'>" . number_format($strength, 2) . "</td>
          <td class='border'  align='center'>" . number_format($variation, 2) . "</td>
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
        if($team_index == ($A_Test_member->num_rows-1))
        {
          echo("<tr><td colspan='22' class='border' align='center'>&nbsp;</td></tr>");
        }
        $team_index++;
      }
    }
  }
*/
  ?>

</table>
</body>
</html>

