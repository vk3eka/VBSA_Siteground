<?php

$dbcnx_client = new mysqli("localhost", "peterj", "abj059XZ@!", "vbsa3364_vbsa2");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
    <meta name="robots" content="noarchive,noindex,nofollow" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>VBSA Administration</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
</head>
<body>

<?php
$year = 2024;
$team_grade = 'APS';
$dayplayed = 'Wed';
$season = 'S2';
$form_no = 1;

$sql_club = 'Select * from Team_entries where team_cal_year = ' . $year . ' and team_grade = "' . $team_grade . '" and day_played = "' . $dayplayed . '" and team_season = "' . $season . '"';
$result_club = $dbcnx_client->query($sql_club) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
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

main($fixtures, $team_grade, $form_no, $year, $season); 

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
    // Home team
    echo("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
    echo("<tbody class='row_position_10'>");
    echo("<tr><td colspan=3 align='center'>(Test Drag 'n Drop)</td></tr>");
    for ($i = 0; $i < 2; $i++) {
        echo("<tr><td>&nbsp;</td></tr>");
        echo("<td colspan=3 align='center'><b>Round " . ($i+1)  . "</b></td></tr>");
        $x = 0;
        foreach ($rounds[$i] as $r) {
            $round_data = explode(" v ", $r);
            echo("<tr>");
            echo("<td align='center'><input class='float-child' type='text' id='" . $team_grade . "_home_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
            echo("<td align='center'>v</td>");
            echo("<td align='center'><input class='float-child' type='text' id='" . $team_grade . "_away_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
            echo("</tr>");
            $x++;
        }
    }
    
    // Away Team
    $round_counter = 2 + 1;
    for ($b = (2 - 1); $b >= 0; $b--) {
        echo("<tr><td>&nbsp;</td></tr>");
        echo("<td colspan=3 align='center'><b>Round " . $round_counter  . "</b></td>");
        $round_counter += 1;
        $y = 0;    
        foreach ($rounds[$b] as $r) {
            $round_data = explode(" v ", $r);
            echo("<tr>");
            echo("<td align='center'><input class='float-child' type='text' id='" . $team_grade . "_home_" . ($round_counter-1) . "_" . ($y+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
            echo("<td align='center'>v</td>");
            echo("<td align='center'><input class='float-child' type='text' id='" . $team_grade . "_away_" . ($round_counter-1) . "_" . ($y+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
            echo("</tr>");
            $y++;
        }
    }
    echo("</tbody>");
    echo("</table>");

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

?>
<script>
// draggable functions
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
  
  console.log("From ID " + EL_drag.id);
  console.log("To ID " + EL_targ.id);
  console.log("From Team " + EL_drag.value);
  console.log("To Team " + EL_targ.value);
  EL_drag = undefined;
};

ELS_child.forEach((EL_child) => addEvents(EL_child));

</script> <!-- dragand drop fixtures -->

</body>
</html>