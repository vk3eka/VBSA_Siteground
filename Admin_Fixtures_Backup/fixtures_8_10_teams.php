<?php 

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
    
    echo("<table style='background-color: grey; display: none' class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
    //echo("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
    echo("<tbody class='row_position_10'>");
    echo("<tr><td colspan=3 align='center'>(Algorithm)</td></tr>");
    for ($i = 0; $i < sizeof($rounds); $i++) {
        echo("<tr><td>&nbsp;</td></tr>");
        echo("<td colspan=3 align='center'><b>Round " . ($i+1)  . "</b></td></tr>");
        echo("<tr><td align='right'><b>Date</b></td>");
        echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . $i . "' style='width:100px'></td></tr>");
        $x = 0;
        foreach ($rounds[$i] as $r) {
            $round_data = explode(" v ", $r);
            echo("<tr>");
            echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
            echo("<td align='center'>v</td>");
            echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
            echo("</tr>");
            $x++;
        }
    }
    // Second half is mirror of first half
    $round_counter = sizeof($rounds) + 1;
    for ($b = (sizeof($rounds) - 1); $b >= 0; $b--) {
        echo("<tr><td>&nbsp;</td></tr>");
        echo("<td colspan=3 align='center'><b>Round " . $round_counter  . "</b></td>");
        echo("<tr><td align='right'><b>Date</b></td>");
        echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . ($round_counter-1) . "' style='width:100px'></td></tr>");
        $round_counter += 1;
        $y = 0;    
        foreach ($rounds[$b] as $r) {
            $round_data = explode(" v ", $r);
            echo("<tr>");
            echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($round_counter-1) . "_" . ($y+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
            echo("<td align='center'>v</td>");
            echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($round_counter-1) . "_" . ($y+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
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

?>