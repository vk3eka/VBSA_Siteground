<?php
require_once('../Connections/connvbsa.php'); 
mysql_select_db($database_connvbsa, $connvbsa);

$year = 2024;
$day_played = 'Wed';
$season = 'S2';

//echo($team_grades . "<br>");
$team_grade = explode(", ", $team_grades);
//echo($team_grade[1] . "<br>");
foreach($team_grade as $grade)
{
    if($grade != '')
    {
        echo($grade . "<br>");
        $team_grade = $grade;
    //}
   
//}


        //$team_grade = 'APS';
        // get data for dataset 
        $no_of_teams = 10;
        $no_of_fixtures = ($no_of_teams/2);
        $no_of_rounds = (($no_of_teams*2)-2);
        $form_no = 1;

        // get players and populate array
        echo("<script type='text/javascript'>");
        echo("function fillelementarray() {");
        //global $connvbsa;
        for($r = 0; $r < $no_of_rounds; $r++)
        {
            $sql_fixtures = 'Select * from tbl_create_fixtures where year = ' . $year . ' and dayplayed = "' . $day_played . '" and season = "' . $season . '" and team_grade = "' . $team_grade . '" and round = ' . ($r+1);
            $result_fixtures = mysql_query($sql_fixtures, $connvbsa) or die(mysql_error());
            $i = 0;
            $build_fixtures = $result_fixtures->fetch_assoc();
            for($y = 0; $y < $no_of_fixtures; $y++)
            {
                $date = date_create($build_fixtures['date']);
                $fixture_date = date_format($date, 'Y-m-d');
                $team_grade = $build_fixtures['team_grade'];

                echo("document.getElementById('" . ($form_no) . "_date_" . ($r) . "').value = '" . $fixture_date . "';");
                echo("document.getElementById('" . $team_grade . "_home_" . ($r+1) . "_" . ($i+1) . "').value = '" . $build_fixtures['fix' . ($y+1) . 'home'] . "';");
                echo("document.getElementById('" . $team_grade . "_away_" . ($r+1) . "_" . ($i+1) . "').value = '" . $build_fixtures['fix' . ($y+1) . 'away'] . "';");
                $i++;
            }
        }
        echo("}");
        echo("window.onload = function()");
        echo("{");
        echo("fillelementarray();");
        echo("}");
        echo("</script>");

        echo("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        echo("<tr>");
        for ($i = 0; $i < ($no_of_rounds); $i++) {
            echo("<tr><td>&nbsp;</td></tr>");
            echo("<td colspan=3 align='center'><b>Round " . ($i+1)  . "</b></td></tr>");
            echo("<tr><td align='right'><b>Date</b></td>");
            echo("<td colspan=2 class='text-left'><input type='text' id='" . $form_no . "_date_" . $i . "'  value='" . $form_no . "_date_" . $i . "' style='width:100px'></td></tr>");
            for ($x = 0; $x < $no_of_fixtures; $x++) {
                echo("<tr>");
                echo ("<td align='center'><input class='float-child' type='text' id='" . $team_grade . "_home_" . ($i+1) . "_" . ($x+1) . "' value='" . $team_grade . "_home_" . ($i+1) . "_" . ($x+1) . "' style='width:200px'></td>");
                echo("<td align='center'>v</td>");
                echo ("<td align='center'><input class='float-child' type='text' id='" . $team_grade . "_away_" . ($i+1) . "_" . ($x+1) . "' value='" . $team_grade . "_away_" . ($i+1) . "_" . ($x+1) . "' style='width:200px'></td>");
                echo("</tr>");
            }
        }
        echo("</tr>");
        echo("</table>");

    }
   
}

//}
/*
// get data for algorthym 
$team_grade = 'APS';
$form_no = 1;

$sql_club = 'Select * from Team_entries where team_cal_year = ' . $year . ' and team_grade = "' . $team_grade . '" and day_played = "' . $day_played . '" and team_season = "' . $season . '"';
$result_club = mysql_query($sql_club, $connvbsa) or die(mysql_error());
$teams = 0;
$fixtures = '';
// create string for fixture generator
while($row = $result_club->fetch_assoc()) 
{
    $fixtures = $row['team_name'] . ", " . $fixtures;
    $teams++;
    $comptype = $row['comptype'];
}
$fixtures = substr($fixtures, 0, strlen($fixtures)-2);

/*
// fixture generation code (dropdown)
function main($fixtures, $team_grade, $form_no, $year, $season) 
{
    echo show_fixtures(isset($teams) ?  nums(intval($teams)) : explode(", ", ($fixtures)), $team_grade, $form_no, $year, $season);
}

function nums($n) {
    $ns = array();
    for ($i = 1; $i <= $n; $i++) {
        $ns[] = $i;
    }
    return $ns;
}

function show_fixtures($names, $team_grade, $form_no, $year, $season)
{
    //global $dbcnx_client;
    $teams = sizeof($names);
    // If odd number of teams add a "ghost".
    $ghost = false;
    if ($teams % 2 == 1) {
        $teams++;
        $ghost = true;
    }
    // Generate the fixtures using the cyclic algorithm.
    $totalRounds = $teams - 1;
    $matchesPerRound = $teams / 2;
    $rounds = array();
    for ($i = 0; $i < $totalRounds; $i++) {
        $rounds[$i] = array();
    }
    for ($round = 0; $round < $totalRounds; $round++) {
        for ($match = 0; $match < $matchesPerRound; $match++) {
            $home = ($round + $match) % ($teams - 1);
            $away = ($teams - 1 - $match + $round) % ($teams - 1);
            // Last team stays in the same place while the others
            // rotate around it.
            if ($match == 0) {
                $away = $teams - 1;
            }
            $rounds[$round][$match] = team_name($home + 1, $names)
                . " v " . team_name($away + 1, $names);
        }
    }
    // Interleave so that home and away games are fairly evenly dispersed.
    $interleaved = array();
    for ($i = 0; $i < $totalRounds; $i++) {
        $interleaved[$i] = array();
    }
    $evn = 0;
    $odd = ($teams / 2);
    for ($i = 0; $i < sizeof($rounds); $i++) {
        if ($i % 2 == 0) {
            $interleaved[$i] = $rounds[$evn++];
        } else {
            $interleaved[$i] = $rounds[$odd++];
        }
    }
    $rounds = $interleaved;
    // Last team can't be away for every game so flip them
    // to home on odd rounds.
    for ($round = 0; $round < sizeof($rounds); $round++) {
        if ($round % 2 == 1) {
            $rounds[$round][0] = flip($rounds[$round][0]);
        }
    }
    echo("<table class='table table-striped table-bordered dt-responsive nowrap display' width='1000px'>
  <tr>");
    for ($i = 0; $i < sizeof($rounds); $i++) {
      echo("<tr><td>&nbsp;</td></tr>");
        echo("<td colspan=3 align='center'><b>Round " . ($i+1)  . "</b></td></tr>");
        echo("<tr><td align='right'><b>Date</b></td>");
        echo("<td colspan=2 class='text-left'><input type='text' id='" . $form_no . "_date_" . $i . "' style='width:100px'></td></tr>");
        $x = 0;
        foreach ($rounds[$i] as $r) {
            $round_data = explode(" v ", $r);
            echo("<tr>");
            echo ("<td align='center'><select id='" . $team_grade . "_home_" . ($i+1) . "_" . ($x+1) . "'>");
            echo("<option value='" . $round_data[0] . "'>" . $round_data[0] . "</option>");
            // start dropbox fill
            $sql_home_team = "Select team_id, team_name, team_grade from Team_entries where team_cal_year = " . $year . " and team_season = '" . $season . "' and team_grade = '" . $team_grade . "'";
            echo($sql_home_team . "<br>");
            $result_home_team = mysql_query($sql_home_team, $connvbsa) or die(mysql_error());
            while($build_home_team = $result_home_team->fetch_assoc()) 
            {
              echo("<option value='" . $build_home_team['team_name'] . "'>" . $build_home_team['team_name'] . "</option>");
            }
            echo("</select></td>");
            // end dropbox fill
            echo("<td align='center'>v</td>");
            echo ("<td align='center'><select id='" . $team_grade . "_away_" . ($i+1) . "_" . ($x+1) . "'>");
            echo("<option value='" . $round_data[1] . "'>" . $round_data[1] . "</option>");
            // start dropbox fill
            $sql_home_team = "Select team_id, team_name, team_grade from Team_entries where team_cal_year = " . $year . " and team_season = '" . $season . "' and team_grade = '" . $team_grade . "'";
            echo($sql_home_team . "<br>");
            $result_home_team = mysql_query($sql_home_team, $connvbsa) or die(mysql_error());
            while($build_home_team = $result_home_team->fetch_assoc()) 
            {
              echo("<option value='" . $build_home_team['team_name'] . "'>" . $build_home_team['team_name'] . "</option>");
            }
            echo("</select></td>");
            // end dropbox fill
            echo("</tr>");
            $x++;
        }
    }
    $round_counter = sizeof($rounds) + 1;
    for ($i = sizeof($rounds) - 1; $i >= 0; $i--) {
        echo("<td colspan=3 align='center'><b>Round " . $round_counter  . "</b></td>");
        echo("<tr><td align='right'><b>Date</b></td>");
        echo("<td colspan=2 class='text-left'><input type='text' id='" . $form_no . "_date_" . ($round_counter-1) . "' style='width:100px'></td></tr>");
        $round_counter += 1;
        $y = 0;    
        foreach ($rounds[$i] as $r) {
            $round_data = explode(" v ", flip($r));
            echo("<tr>");
            echo ("<td align='center'><select id='" . $team_grade . "_home_" . ($round_counter-1) . "_" . ($y+1) . "'>");
            echo("<option value='" . $round_data[0] . "'>" . $round_data[0] . "</option>");
            // start dropbox fill
            $sql_home_team = "Select team_id, team_name, team_grade from Team_entries where team_cal_year = " . $year . " and team_grade = '" . $team_grade . "'";
            echo($sql_home_team . "<br>");
            $result_home_team = mysql_query($sql_home_team, $connvbsa) or die(mysql_error());
            while($build_home_team = $result_home_team->fetch_assoc()) 
            {
              echo("<option value='" . $build_home_team['team_name'] . "'>" . $build_home_team['team_name'] . "</option>");
            }
            echo("</select></td>");
            // end dropbox fill
            echo("<td align='center'>v</td>");
            echo ("<td align='center'><select id='" . $team_grade . "_away_" . ($round_counter-1) . "_" . ($y+1) . "'>");
            echo("<option value='" . $round_data[1] . "'>" . $round_data[1] . "</option>");
            // start dropbox fill
            $sql_home_team = "Select team_id, team_name, team_grade from Team_entries where team_cal_year = " . $year . " and team_grade = '" . $team_grade . "'";
            echo($sql_home_team . "<br>");
            $result_home_team = mysql_query($sql_home_team, $connvbsa) or die(mysql_error());
            while($build_home_team = $result_home_team->fetch_assoc()) 
            {
              echo("<option value='" . $build_home_team['team_name'] . "'>" . $build_home_team['team_name'] . "</option>");
            }
            echo("</select></td>");
            // end dropbox fill
            echo("</tr>");
            $y++;
        }
    }
    if ($ghost) {
        print "Matches against team " . $teams . " are byes.";
    }
}

function flip($match) {
    $components = explode(' v ', $match);
    return $components[1] . " v " . $components[0];
}

function team_name($num, $names) {
    $i = $num - 1;
    if (sizeof($names) > $i && strlen(trim($names[$i])) > 0) {
        return trim($names[$i]);
    } else {
        return $num;
    }
}


// fixture generation code (draggable)
function main($fixtures, $team_grade, $form_no, $year, $season) 
{
    echo show_fixtures(isset($teams) ?  nums(intval($teams)) : explode(", ", ($fixtures)), $team_grade, $form_no, $year, $season);
}

function nums($n) {
    $ns = array();
    for ($i = 1; $i <= $n; $i++) {
        $ns[] = $i;
    }
    return $ns;
}

function show_fixtures($names, $team_grade, $form_no, $year, $season)
{
    //global $dbcnx_client;
    $teams = sizeof($names);
    // If odd number of teams add a "ghost".
    $ghost = false;
    if ($teams % 2 == 1) {
        $teams++;
        $ghost = true;
    }
    // Generate the fixtures using the cyclic algorithm.
    $totalRounds = $teams - 1;
    $matchesPerRound = $teams / 2;
    $rounds = array();
    for ($i = 0; $i < $totalRounds; $i++) {
        $rounds[$i] = array();
    }
    for ($round = 0; $round < $totalRounds; $round++) {
        for ($match = 0; $match < $matchesPerRound; $match++) {
            $home = ($round + $match) % ($teams - 1);
            $away = ($teams - 1 - $match + $round) % ($teams - 1);
            // Last team stays in the same place while the others
            // rotate around it.
            if ($match == 0) {
                $away = $teams - 1;
            }
            $rounds[$round][$match] = team_name($home + 1, $names)
                . " v " . team_name($away + 1, $names);
        }
    }
    // Interleave so that home and away games are fairly evenly dispersed.
    $interleaved = array();
    for ($i = 0; $i < $totalRounds; $i++) {
        $interleaved[$i] = array();
    }
    $evn = 0;
    $odd = ($teams / 2);
    for ($i = 0; $i < sizeof($rounds); $i++) {
        if ($i % 2 == 0) {
            $interleaved[$i] = $rounds[$evn++];
        } else {
            $interleaved[$i] = $rounds[$odd++];
        }
    }
    $rounds = $interleaved;
    // Last team can't be away for every game so flip them
    // to home on odd rounds.
    for ($round = 0; $round < sizeof($rounds); $round++) {
        if ($round % 2 == 1) {
            $rounds[$round][0] = flip($rounds[$round][0]);
        }
    }
    echo("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>
  <tr>");
    for ($i = 0; $i < sizeof($rounds); $i++) {
        echo("<tr><td>&nbsp;</td></tr>");
        echo("<td colspan=3 align='center'><b>Round " . ($i+1)  . "</b></td></tr>");
        echo("<tr><td align='right'><b>Date</b></td>");
        echo("<td colspan=2 class='text-left'><input type='text' id='" . $form_no . "_date_" . $i . "' style='width:100px'></td></tr>");
        $x = 0;
        foreach ($rounds[$i] as $r) {
            $round_data = explode(" v ", $r);
            echo("<tr>");
            echo ("<td align='center'><input class='float-child' type='text' id='" . $team_grade . "_home_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
            echo("<td align='center'>v</td>");
            echo ("<td align='center'><input class='float-child' type='text' id='" . $team_grade . "_away_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
            echo("</tr>");
            $x++;
        }
    }
    $round_counter = sizeof($rounds) + 1;
    for ($i = sizeof($rounds) - 1; $i >= 0; $i--) {
        echo("<td colspan=3 align='center'><b>Round " . $round_counter  . "</b></td>");
        echo("<tr><td align='right'><b>Date</b></td>");
        echo("<td colspan=2 class='text-left'><input type='text' id='" . $form_no . "_date_" . ($round_counter-1) . "' style='width:100px'></td></tr>");
        $round_counter += 1;
        $y = 0;    

        foreach ($rounds[$i] as $r) {
            $round_data = explode(" v ", $r);
            echo("<tr>");
            echo ("<td align='center'><input class='float-child' type='text' id='" . $team_grade . "_home_" . ($round_counter-1) . "_" . ($y+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
            echo("<td align='center'>v</td>");
            echo ("<td align='center'><input class='float-child' type='text' id='" . $team_grade . "_away_" . ($round_counter-1) . "_" . ($y+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
            echo("</tr>");
            $y++;
        }
    }
    if ($ghost) {
        print "Matches against team " . $teams . " are byes.";
    }
}

function flip($match) {
    $components = explode(' v ', $match);
    return $components[1] . " v " . $components[0];
}

function team_name($num, $names) {
    $i = $num - 1;
    if (sizeof($names) > $i && strlen(trim($names[$i])) > 0) {
        return trim($names[$i]);
    } else {
        return $num;
    }
}

main($fixtures, $team_grade, $form_no, $year, $season); 
//CreateDateArray($form_no, $teams, $team_grade, $year, $season);
*/
//PopulateFixtures($year, $day_played, $season, 'APS');

?>

<script>
// DOM utility functions:
const ELS = (sel, par) => (par || document).querySelectorAll(sel);

// TASK:
const ELS_child = ELS(".float-child");
let EL_drag; // Used to remember the dragged element

const addEvents = (EL_ev) => {
  EL_ev.setAttribute("draggable", "true");
  EL_ev.addEventListener("dragstart", onstart);
  EL_ev.addEventListener("dragover", (ev) => ev.preventDefault());
  EL_ev.addEventListener("drop", ondrop);
};

const onstart = (ev) => EL_drag = ev.currentTarget;

const ondrop = (ev) => {
  if (!EL_drag) return;

  ev.preventDefault();
  
  const EL_targ = ev.currentTarget;
  const EL_targClone = EL_targ.cloneNode(true);
  const EL_dragClone = EL_drag.cloneNode(true);
  
  EL_targ.replaceWith(EL_dragClone);
  EL_drag.replaceWith(EL_targClone);
  
  addEvents(EL_targClone); // Reassign events to cloned element
  addEvents(EL_dragClone); // Reassign events to cloned element
  
  EL_drag = undefined;
};

ELS_child.forEach((EL_child) => addEvents(EL_child));

</script>
<!--
<style>
/* QuickReset */ * {margin: 0; box-sizing: border-box;}

.float-container {
  width: 100%;
  
  flex-wrap: wrap;
  position: relative;
}

.float-child {
  margin: 5px;
  flex: 1 0 14%;
  width: 100px;
}

.float-child img {
  width: 100%;
  height: 90px;
  object-fit: cover;
}

</style>
-->
