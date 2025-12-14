<?php
require_once('../../Connections/connvbsa.php');

mysql_select_db($database_connvbsa, $connvbsa);

echo("Here<br>");

/*
$team_grade = $_GET['team_grade'];
$year = $_GET['year'];
$dayplayed = $_GET['dayplayed'];
$season = $_GET['season'];
*/

$team_grade = 'BVS1';
$year = 2025;
$dayplayed = 'Mon';
$season = 'S1';
$form_no = 1;

//main($fixtures, $team_grade, $form_no, $year, $season); 

$sql_club = 'Select * from Team_entries where team_cal_year = ' . $year . ' and team_grade = "' . $team_grade . '" and day_played = "' . $dayplayed . '" and team_season = "' . $season . '"';
$result_club = mysql_query($sql_club, $connvbsa) or die(mysql_error());
$fixtures = '';
// create string for fixture generator
while($row = $result_club->fetch_assoc()) 
{
    $fixtures = $row['team_name'] . ", " . $fixtures;
}
$fixtures = substr($fixtures, 0, strlen($fixtures)-2);
$teams = $result_club->num_rows;
echo($teams . "<br>");


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

    if($teams == 4) // needs checking with a four team fixture.
    {
        //echo("Teams " . $teams . "<br>");
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

        // Display the fixtures (for 4 teams)

        // set 1
        echo("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        echo("<tbody class='row_position_10'>");
        echo("<tr><td colspan=3 align='center'>(Algorithm)</td></tr>");
        for ($i = 0; $i < sizeof($rounds); $i++) {
            echo("<tr><td>&nbsp;</td></tr>");
            echo("<td colspan=3 align='center'><b>Round " . ($i+1)  . "</b></td></tr>");
            echo("<tr><td align='right'><b>Date</b></td>");
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . $i . "' style='width:100px'></td></tr>");
            $x = 0;
            foreach ($rounds[$i] as $r) { // set 1
                $round_data = explode(" v ", $r);
                echo("<tr>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
                echo("<td align='center'>v</td>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
                echo("</tr>");
                $x++;
            }
        }
        // Second half is mirror of first half (set 2)
        $round_counter = sizeof($rounds) + 1;
        //for ($b = (sizeof($rounds) - 1); $b >= 0; $b--) {
        for ($b = 0; $b < sizeof($rounds); $b++) {
        //for ($i = 0; $i < sizeof($rounds); $i++) {
            echo("<tr><td>&nbsp;</td></tr>");
            echo("<td colspan=3 align='center'><b>Round " . $round_counter  . "</b></td>");
            echo("<tr><td align='right'><b>Date</b></td>");
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . ($round_counter-1) . "' style='width:100px'></td></tr>");
            $round_counter += 1;
            $y = 0;    
            foreach ($rounds[$b] as $r) {
                $round_data = explode(" v ", $r);
/*                echo("<tr>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($round_counter-1) . "_" . ($y+1) . "' value='A_" . $team_grade . "_home_" . ($round_counter-1) . "_" . ($y+1) . "' style='width:200px'></td>");
                echo("<td align='center'>v</td>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($round_counter-1) . "_" . ($y+1) . "' value='A_" . $team_grade . "_away_" . ($round_counter-1) . "_" . ($y+1) . "' style='width:200px'></td>");
                echo("</tr>");
*/
                echo("<tr>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($round_counter-1) . "_" . ($y+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
                echo("<td align='center'>v</td>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($round_counter-1) . "_" . ($y+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
                echo("</tr>");
                $y++;
            }
        }

        // Third half is mirror of first half (set 3)
        for ($i = 0; $i < sizeof($rounds); $i++) {
            echo("<tr><td>&nbsp;</td></tr>");
            echo("<td colspan=3 align='center'><b>Round " . ($i+7)  . "</b></td></tr>");
            echo("<tr><td align='right'><b>Date</b></td>");
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . ($i+6) . "' style='width:100px'></td></tr>");
            $x = 0;
            foreach ($rounds[$i] as $r) {
                $round_data = explode(" v ", $r);
/*                echo("<tr>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($i+7) . "_" . ($x+1) . "' value='A_" . $team_grade . "_home_" . ($i+7) . "_" . ($x+1) . "' style='width:200px'></td>");
                echo("<td align='center'>v</td>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($i+7) . "_" . ($x+1) . "' value='A_" . $team_grade . "_away_" . ($i+7) . "_" . ($x+1) . "' style='width:200px'></td>");
                echo("</tr>");
*/
                echo("<tr>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($i+7) . "_" . ($x+1) . "' value='$round_data[0]' style='width:200px'></td>");
                echo("<td align='center'>v</td>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($i+7) . "_" . ($x+1) . "' value='$round_data[1]' style='width:200px'></td>");
                echo("</tr>");
                $x++;
            }
        }
        // Second half is mirror of second half (set 4)
        $round_counter = sizeof($rounds) + 1;
        //for ($b = (sizeof($rounds) - 1); $b >= 0; $b--) {
        for ($b = 0; $b < sizeof($rounds); $b++) {
        //for ($i = 0; $i < sizeof($rounds); $i++) {
            echo("<tr><td>&nbsp;</td></tr>");
            echo("<td colspan=3 align='center'><b>Round " . ($round_counter+6)  . "</b></td>");
            echo("<tr><td align='right'><b>Date</b></td>");
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . ($round_counter+5) . "' style='width:100px'></td></tr>");
            $round_counter += 1;
            $y = 0;    
            foreach ($rounds[$b] as $r) {
                $round_data = explode(" v ", $r);
/*                  echo("<tr>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($round_counter+5) . "_" . ($y+1) . "' value='A_" . $team_grade . "_home_" . ($round_counter+5) . "_" . ($y+1) . "' style='width:200px'></td>");
                echo("<td align='center'>v</td>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($round_counter+5) . "_" . ($y+1) . "' value='A_" . $team_grade . "_away_" . ($round_counter+5) . "_" . ($y+1) . "' style='width:200px'></td>");
                echo("</tr>");
*/             
              echo("<tr>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($round_counter+5) . "_" . ($y+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
                echo("<td align='center'>v</td>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($round_counter+5) . "_" . ($y+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
                echo("</tr>");
                $y++;
            }
        }
        // Second half is mirror of first half (set 5)
        //$round_counter = sizeof($rounds) + 1;
        for ($i = 0; $i < sizeof($rounds); $i++) {
            echo("<tr><td>&nbsp;</td></tr>");
            echo("<td colspan=3 align='center'><b>Round " . ($i+13)  . "</b></td></tr>");
            echo("<tr><td align='right'><b>Date</b></td>");
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . ($i+12) . "' style='width:100px'></td></tr>");
            $x = 0;
            foreach ($rounds[$i] as $r) { // set 5
                $round_data = explode(" v ", $r);
                echo("<tr>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($i+13) . "_" . ($x+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
                echo("<td align='center'>v</td>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($i+13) . "_" . ($x+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
                echo("</tr>");

/*                echo("<tr>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($i+13) . "_" . ($x+1) . "' value='A_" . $team_grade . "_home_" . ($i+13) . "_" . ($x+1) . "' style='width:200px'></td>");
                echo("<td align='center'>v</td>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($i+13) . "_" . ($x+1) . "' value='A_" . $team_grade . "_away_" . ($i+13) . "_" . ($x+1) . "' style='width:200px'></td>");
                echo("</tr>");
*/
                $x++;
            }
        }
        echo("</tbody>");
        echo("</table>");
        // end 4 teams
    }
    elseif($teams == 6)
    {
        //echo("Teams " . $teams . "<br>");
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

        // Display the fixtures (for 6 teams)

        //echo("<table style='background-color: grey; display: none' class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        echo("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
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
        //for ($b = (sizeof($rounds) - 1); $b >= 0; $b--) {
        for ($b = 0; $b < sizeof($rounds); $b++) {
        //for ($i = 0; $i < sizeof($rounds); $i++) {
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
        // Third half is mirror of first half
        for ($round_counter = 0; $round_counter < sizeof($rounds); $round_counter++) {
            echo("<tr><td>&nbsp;</td></tr>");
            echo("<td colspan=3 align='center'><b>Round " . ($round_counter + 11)  . "</b></td></tr>");
            echo("<tr><td align='right'><b>Date</b></td>");
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . ($round_counter + 10) . "' style='width:100px'></td></tr>");
            $x = 0;
            foreach ($rounds[$round_counter] as $r) {
                $round_data = explode(" v ", $r);
                echo("<tr>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($round_counter+11) . "_" . ($x+1) . "' value='$round_data[0]' style='width:200px'></td>");
                echo("<td align='center'>v</td>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($round_counter+11) . "_" . ($x+1) . "' value='$round_data[1]' style='width:200px'></td>");
                echo("</tr>");
                $x++;
            }
        }
        echo("</tbody>");
        echo("</table>");
        // end 6 teams
    }
    elseif(($teams == 8) || ($teams == 10))
    //elseif(($teams == 8) || ($teams == 10) || ($teams == 4) || ($teams == 12))
    {
        echo("Teams " . $teams . "<br>");
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
/*
        // Display the fixtures (for 8/10 teams)
        //echo("<table style='background-color: grey; display: none' class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        echo("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
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
        for ($b = 0; $b < sizeof($rounds); $b++) {
        //for ($b = (sizeof($rounds) - 1); $b >= 0; $b--) {
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
*/        
        //$match = new Clash();
        $fixture_list = '';
        for ($i = 0; $i < sizeof($rounds); $i++) {

            $fixture_list .= "round" => ($i+1),

            $fixture_list .= "date" => ("A_" . $form_no . "_date_" . $i),
            $x = 0;
            foreach ($rounds[$i] as $r) {
                $round_data = explode(" v ", $r);
                $fixture_list .= "home" => $round_data[0],
                //echo("A_" . $team_grade . "_home_" . ($i+1) . "_" . ($x+1) . " = " . $round_data[0] . "<br>");
                $fixture_list .= "home" => $round_data[1],
                //echo("A_" . $team_grade . "_away_" . ($i+1) . "_" . ($x+1) . " = " . $round_data[1] . "<br>");

                $x++;
            }
        }
        // Second half is mirror of first half
        $round_counter = sizeof($rounds) + 1;
        for ($b = 0; $b < sizeof($rounds); $b++) {
        //for ($b = (sizeof($rounds) - 1); $b >= 0; $b--) {

            //echo("Round " . $round_counter  . "<br>");
            $fixture_list .= "round" => $round_counter,

            $fixture_list .= "date" => "A_" . $form_no . "_date_" . ($round_counter-1),
            $round_counter += 1;
            $y = 0;    
            foreach ($rounds[$b] as $r) {
                $round_data = explode(" v ", $r);
                $fixture_list .= "home" => $round_data[1],
                //echo("A_" . $team_grade . "_home_" . ($round_counter-1) . "_" . ($y+1) . " = " . $round_data[1] . "<br>");
                $fixture_list .= "home" => $round_data[0],
                //echo("A_" . $team_grade . "_away_" . ($round_counter-1) . "_" . ($y+1) . " = " . $round_data[0] . "<br>");

                $y++;
            }
            //$fixture_list .= )
        }
        echo($fixture_list);


    }
    elseif($teams == 12)
    {
        $names_set_1 = [];
        $names_set_2 = [];
        $names_set_3 = [];
        for ($i = 0; $i < (count($names)/2); $i++)
        {
            array_push($names_set_1, $names[$i]); 
        }
        for ($i = (count($names)/2); $i < count($names); $i++)
        {
            array_push($names_set_2, $names[$i]); 
        }
        for ($i = 0; $i < count($names); $i++)
        {
            array_push($names_set_3, $names[$i]); 
        }

        $teams = sizeof($names_set_1); // first set of 6

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
                $rounds[$round][$match] = team_name($home + 1, $names_set_1)
                    . " v " . team_name($away + 1, $names_set_1);
            }
        }

        // first sets are two groups of 6
        $teams = ($teams/2);
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

        echo("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        echo("<tbody class='row_position_10'>");
        echo("<tr><td colspan=3 align='center'>(Algorithm)</td></tr>");
        echo("<tr><td colspan=3 align='center'>First Set of 6 Teams</td></tr>");
        // (Set 1 First Group)
        for ($i = 0; $i < sizeof($rounds); $i++) {
            echo("<tr><td>&nbsp;</td></tr>");
            echo("<td colspan=3 align='center'><b>Round " . ($i+1)  . "</b></td></tr>");
            echo("<tr><td align='right'><b>Date</b></td>");
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . $i . "' style='width:100px' value='A_" . $form_no . "_date_" . $i . "'></td></tr>");
            $x = 0;
            foreach ($rounds[$i] as $r) {
                $round_data = explode(" v ", $r);
                echo("<tr>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
                echo("<td align='center'>v</td>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
                echo("</tr>");
                $x++;
            };
        }
        
        // (Set 2 First Group)
        $round_counter = sizeof($rounds) + 1;
        for ($i = 0; $i < sizeof($rounds); $i++) {
            echo("<tr><td>&nbsp;</td></tr>");
            echo("<td colspan=3 align='center'><b>Round " . ($round_counter)  . "</b></td></tr>");
            echo("<tr><td align='right'><b>Date</b></td>");
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . ($round_counter-1) . "' style='width:100px' value='A_" . $form_no . "_date_" . ($round_counter-1) . "'></td></tr>");
            $x = 0;
            $round_counter += 1;
            foreach ($rounds[$i] as $r) {
                $round_data = explode(" v ", flip($r));
                echo("<tr>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($i+6) . "_" . ($x+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
                echo("<td align='center'>v</td>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($i+6) . "_" . ($x+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
                echo("</tr>");
                $x++;
            }
            print "<br />";
        }

        $teams = sizeof($names_set_2);

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
                $rounds[$round][$match] = team_name($home + 1, $names_set_2)
                    . " v " . team_name($away + 1, $names_set_2);
            }
        }

        // first sets are two groups of 6
        $teams = ($teams/2);
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


        // (Set 1 Second Group)
        //$round_counter = sizeof($rounds) + 1;
        echo("<tr><td colspan=3 align='center'>Second Set of 6 Teams</td></tr>");
        for ($i = 0; $i < sizeof($rounds); $i++) {
            echo("<tr><td>&nbsp;</td></tr>");
            echo("<td colspan=3 align='center'><b>Round " . ($i+1)  . "</b></td></tr>");
            echo("<tr><td align='right'><b>Date</b></td>");
            echo("<td colspan=2 class='text-left'><input type='text' id='B_" . $form_no . "_date_" . ($i) . "' style='width:100px' value='B_" . $form_no . "_date_" . ($i) . "'></td></tr>");
            $x = 3;
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
        
        // (Set 2 Second Group)
        $round_counter = sizeof($rounds) + 1;
        for ($i = 0; $i < sizeof($rounds); $i++) {
            echo("<tr><td>&nbsp;</td></tr>");
            echo("<td colspan=3 align='center'><b>Round " . ($round_counter)  . "</b></td></tr>");
            echo("<tr><td align='right'><b>Date</b></td>");
            echo("<td colspan=2 class='text-left'><input type='text' id='B_" . $form_no . "_date_" . ($round_counter-1) . "' style='width:100px' value='B_" . $form_no . "_date_" . ($round_counter-1) . "'></td></tr>");
            $x = 3;
            $round_counter += 1;
            foreach ($rounds[$i] as $r) {
                $round_data = explode(" v ", flip($r));
                echo("<tr>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($i+6) . "_" . ($x+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
                echo("<td align='center'>v</td>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($i+6) . "_" . ($x+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
                echo("</tr>");
                $x++;
            }
            print "<br />";
        }

        // Number of rounds
        $rounds = 6;

        // Initialize fixtures array
        $fixtures = [];

        // Generate fixtures for 6 rounds
        $roundFixtures = [];

        // Determine which group plays at home in this round
        $homeGroup = $names_set_2;
        $awayGroup = $names_set_1;
        $x = 1;
        // Generate fixtures for this round
        foreach ($homeGroup as $homeTeam) 
        {
            $round = 1;
            foreach ($awayGroup as $awayTeam) 
            {
                switch($x) // sort fixtures so a team doesn't play more than one team per night 
                {
                  case 1:
                    $round = 1;
                    break;
                  case 2:
                    $round = 2;
                    break;
                  case 3:
                    $round = 3;
                    break;
                  case 4:
                    $round = 4;
                    break;
                  case 5:
                    $round = 5;
                    break;
                  case 6:
                    $round = 6;
                    break;
                  case 7:
                    $round = 2;
                    break;
                  case 8:
                    $round = 3;
                    break;
                  case 9:
                    $round = 4;
                    break;
                  case 10:
                    $round = 5;
                    break;
                  case 11:
                    $round = 6;
                    break;
                  case 12:
                    $round = 1;
                    break;
                  case 13:
                    $round = 3;
                    break;
                  case 14:
                    $round = 4;
                    break;
                  case 15:
                    $round = 5;
                    break;
                  case 16:
                    $round = 6;
                    break;
                  case 17:
                    $round = 1;
                    break;
                  case 18:
                    $round = 2;
                    break;
                  case 19:
                    $round = 4;
                    break;
                  case 20:
                    $round = 5;
                    break;
                  case 21:
                    $round = 6;
                    break;
                  case 22:
                    $round = 1;
                    break;
                  case 23:
                    $round = 2;
                    break;
                  case 24:
                    $round = 3;
                    break;
                  case 25:
                    $round = 5;
                    break;
                  case 26:
                    $round = 6;
                    break;
                  case 27:
                    $round = 1;
                    break;
                  case 28:
                    $round = 2;
                    break;
                  case 29:
                    $round = 3;
                    break;
                  case 30:
                    $round = 4;
                    break;
                  case 31:
                    $round = 6;
                    break;
                  case 32:
                    $round = 1;
                    break;
                  case 33:
                    $round = 2;
                    break;
                  case 34:
                    $round = 3;
                    break;
                  case 35:
                    $round = 4;
                    break;
                  case 36:
                    $round = 5;
                    break;
                }
                if ($round %2 == 0) { // round is even number
                    // Home vs Away
                    $matchup = "$homeTeam vs $awayTeam";
                } else {
                    // Away vs Home (reverse fixtures)
                    $matchup = "$awayTeam vs $homeTeam";
                }
                $team_names = explode(" vs ", $matchup);
                $roundFixtures[$x] = $round . ", " . $team_names[0] . ", " . $team_names[1];
                $round++;
                $x++;
            }
        }

        sort($roundFixtures); // sort into round order

        echo("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        echo("<tbody class='row_position_10'>");
        echo("<tr><td colspan=3 align='center'>Combined Set of both sets of Teams</td></tr>");
        for ($i = 0; $i < 6; $i++) // last 6 rounds
        {
            echo("<tr><td>&nbsp;</td></tr>");
            echo("<td colspan=3 align='center'><b>Round " . ($i+11) . "</b></td></tr>");
            echo("<tr><td align='right'><b>Date</b></td>");
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . ($i+10) . "' style='width:100px'></td></tr>");
            echo("<tr>");
            $y = 0;
            foreach($roundFixtures as $fixture)
            {
                $new_array = explode(", ", $fixture);
                if($new_array[0] == ($i+1))
                {
                    echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($i+11) . "_" . ($y+1) . "' value='" . ($new_array[1]) . "' style='width:200px'></td>");
                    echo("<td align='center'>v</td>");
                    echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($i+11) . "_" . ($y+1) . "' value='" . ($new_array[2]) . "' style='width:200px'></td>");
                    echo("</tr>");
                    $y++;
                }   
            }
        }
        echo("</tbody>");
        echo("</table>");
    }
    elseif($teams == 14)
    {
        //echo("Teams " . $teams . "<br>");
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

        // Display the fixtures (for 14 teams)
        echo("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        echo("<tbody class='row_position_10'>");
        echo("<tr><td colspan=3 align='center'>(Algorithm)</td></tr>");
        for ($i = 0; $i < sizeof($rounds); $i++) {
            echo("<tr><td>&nbsp;</td></tr>");
            echo("<td colspan=3 align='center'><b>Round " . ($i+1)  . "</b></td></tr>");
            echo("<tr><td align='right'><b>Date</b></td>");
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . $i . "'  style='width:100px'></td></tr>");
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
        echo("</tbody>");
        echo("</table>");

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

//main($fixtures, $team_grade, $form_no, $year, $season); 

?>
