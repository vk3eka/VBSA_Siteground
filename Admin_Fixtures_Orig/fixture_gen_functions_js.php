<script>

console.log("Fixtures_js");

// Add Dates

function addWeek(date, days, round) 
{
    var date = new Date(date);
    date.setDate(date.getDate() + days*round);
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();
    var start_date = day + '/' + month + '/' + year;
    return start_date;
}

// fixture generation code (draggable)
function main(fixtures, team_grade, form_no, year, season, start_date) {
    console.log(fixtures, team_grade + ", " + form_no + ", " + year + ", " + season + ", " + start_date);
    //let teams = isNaN(parseInt(fixtures)) ? fixtures.split(", ") : nums(parseInt(fixtures));
    let teams = fixtures.join(", ");
    show_fixtures(teams, team_grade, form_no, year, season, start_date);
}

function nums(n) {
    let ns = [];
    for (let i = 1; i <= n; i++) {
        ns.push(i.toString());
    }
    return ns;
}

function team_name(num, names) {
    let i = num - 1;
    return names[i] && names[i].trim().length > 0 ? names[i].trim() : num;
}

function flip(match) {
    let components = match.split(" v ");
    return components[1] + " v " + components[0];
}

function show_fixtures(names, team_grade, form_no, year, season, start_date)
{
    let teams = names.length;
    let team_text = "Teams " + teams + "\n";
    //console.log(team_text);
    let text = '';

    if(teams == 4) // needs checking with a four team fixture.
    {
/*        // If odd number of teams add a "ghost".
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
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . $i . "' style='width:100px' value=" . addWeek($start_date, 7, ($i+1)) . "></td></tr>");
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
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . ($round_counter-1) . "' style='width:100px' value=" . addWeek($start_date, 7, ($i+1)) . "></td></tr>");
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

        // Third half is mirror of first half (set 3)
        for ($i = 0; $i < sizeof($rounds); $i++) {
            echo("<tr><td>&nbsp;</td></tr>");
            echo("<td colspan=3 align='center'><b>Round " . ($i+7)  . "</b></td></tr>");
            echo("<tr><td align='right'><b>Date</b></td>");
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . ($i+6) . "' style='width:100px' value=" . addWeek($start_date, 7, ($i+1)) . "></td></tr>");
            $x = 0;
            foreach ($rounds[$i] as $r) {
                $round_data = explode(" v ", $r);
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
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . ($round_counter+5) . "' style='width:100px' value=" . addWeek($start_date, 7, ($i+1)) . "></td></tr>");
            $round_counter += 1;
            $y = 0;    
            foreach ($rounds[$b] as $r) {
                $round_data = explode(" v ", $r);
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
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . ($i+12) . "' style='width:100px' value=" . addWeek($start_date, 7, ($i+1)) . "></td></tr>");
            $x = 0;
            foreach ($rounds[$i] as $r) { // set 5
                $round_data = explode(" v ", $r);
                echo("<tr>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($i+13) . "_" . ($x+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
                echo("<td align='center'>v</td>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($i+13) . "_" . ($x+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
                echo("</tr>");
                $x++;
            }
        }
        echo("</tbody>");
        echo("</table>");
*/        // end 4 teams
    }
    //else if(teams == 6)
    //{
        //echo("Teams " . $teams . "<br>");
        // If odd number of teams add a "ghost".
        let ghost = false;
        if (teams % 2 === 1) {
            names.push("Ghost");
            teams++;
            ghost = true;
        }

        let totalRounds = teams - 1;
        let matchesPerRound = teams / 2;
        let rounds = [];

        for (let i = 0; i < totalRounds; i++) {
            rounds[i] = [];
        }

        for (let round = 0; round < totalRounds; round++) {
            for (let match = 0; match < matchesPerRound; match++) {
                let home = (round + match) % (teams - 1);
                let away = (teams - 1 - match + round) % (teams - 1);
                if (match === 0) away = teams - 1;
                rounds[round][match] = team_name(home + 1, names) + " v " + team_name(away + 1, names);
            }
        }

        // Interleave rounds
        let interleaved = new Array(totalRounds).fill(null).map(() => []);
        let evn = 0, odd = matchesPerRound;
        for (let i = 0; i < (rounds.length); i++) {
            interleaved[i] = (i % 2 === 0) ? rounds[evn++] : rounds[odd++];
        }

        rounds = interleaved;

        // Flip home/away for last team in odd rounds
        for (let round = 0; round < (rounds.length); round++) {
            if (round % 2 === 1) {
                rounds[round][0] = flip(rounds[round][0]);
            }
        }

        // Display the fixtures (for 6 teams)
        text = '';
        //echo("<table style='background-color: grey; display: none' class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        text += ("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        text += ("<tbody class='row_position_10'>");
        text += ("<tr><td colspan=3 align='center'>(Algorithm)</td></tr>");
        for (let i = 0; i < rounds.length; i++) {
            text += ("<tr><td>&nbsp;</td></tr>");
            text += ("<td colspan=3 align='center'><b>Round " + (i+1)  + "</b></td></tr>");
            text += ("<tr><td align='right'><b>Date</b></td>");
            text += ("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + i + "' style='width:100px' value=" + addWeek(start_date, 7, (i)) + "></td></tr>");
            x = 0;
            rounds[i].forEach((r, x) => {
            let [home, away] = r.split(" v ");
            //foreach (rounds[i] as r) {
                text += ("<tr>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (i+1) + "_" + (x+1) + "' value='" + home + "' style='width:200px'></td>");
                text += ("<td align='center'>v</td>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (i+1) + "_" + (x+1) + "' value='" + away + "' style='width:200px'></td>");
                text += ("</tr>");
                x++;
            });
        }
        // Second half is mirror of first half
        let round_counter = rounds.length + 1;
        for (let b = 0; b < rounds.length; b++) {
            text += ("<tr><td>&nbsp;</td></tr>");
            text += ("<td colspan=3 align='center'><b>Round " + round_counter  + "</b></td>");
            text += ("<tr><td align='right'><b>Date</b></td>");
            text += ("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + (round_counter-1) + "' style='width:100px' value=" + addWeek(start_date, 7, (round_counter-1)) + "></td></tr>");
            round_counter += 1;
            y = 0;    
            rounds[b].forEach((r, y) => {
            let [home, away] = r.split(" v ");
                text += ("<tr>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (round_counter-1) + "_" + (y+1) + "' value='" + away + "' style='width:200px'></td>");
                text += ("<td align='center'>v</td>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (round_counter-1) + "_" + (y+1) + "' value='" + home + "' style='width:200px'></td>");
                text += ("</tr>");
                y++;
            });
        }
        // Third half is mirror of first half
        for (let round_counter = 0; round_counter < rounds.length; round_counter++) {
            text += ("<tr><td>&nbsp;</td></tr>");
            text += ("<td colspan=3 align='center'><b>Round " + (round_counter + 11)  + "</b></td></tr>");
            text += ("<tr><td align='right'><b>Date</b></td>");
            text += ("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + (round_counter + 10) + "' style='width:100px' value=" + addWeek(start_date, 7, (round_counter + 10)) + "></td></tr>");
            x = 0;
            rounds[round_counter].forEach((r, x) => {
            let [home, away] = r.split(" v ");
                text += ("<tr>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (round_counter+11) + "_" + (x+1) + "' value=" + home + " style='width:200px'></td>");
                text += ("<td align='center'>v</td>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (round_counter+11) + "_" + (x+1) + "' value=" + away + " style='width:200px'></td>");
                text += ("</tr>");
                x++;
            });
        }
        text += ("</tbody>");
        text += ("</table>");
        // end 6 teams
    //}
    //else if((teams == 8) || (teams == 10))
    //{
/*        //echo("Teams " . $teams . "<br>");
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

        // Display the fixtures (for 8/10 teams)
        //echo("<table style='background-color: grey; display: none' class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        echo("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        echo("<tbody class='row_position_10'>");
        echo("<tr><td colspan=3 align='center'>(Algorithm)</td></tr>");
        for ($i = 0; $i < sizeof($rounds); $i++) {
            echo("<tr><td>&nbsp;</td></tr>");
            echo("<td colspan=3 align='center'><b>Round " . ($i+1)  . "</b></td></tr>");
            echo("<tr><td align='right'><b>Date</b></td>");
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . $i . "' style='width:100px' value=" . addWeek($start_date, 7, ($i)) . "></td></tr>");
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
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . ($round_counter-1) . "' style='width:100px' value=" . addWeek($start_date, 7, ($i+1)) . "></td></tr>");
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
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . $i . "' style='width:100px' value=" . addWeek($start_date, 7, ($i+1)) . "></td></tr>");
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
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . ($round_counter-1) . "' style='width:100px' value=" . addWeek($start_date, 7, $round_counter) . "></td></tr>");
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
            echo("<td colspan=2 class='text-left'><input type='text' id='B_" . $form_no . "_date_" . ($i) . "' style='width:100px' value=" . addWeek($start_date, 7, ($i+1)) . "></td></tr>");
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
            echo("<td colspan=2 class='text-left'><input type='text' id='B_" . $form_no . "_date_" . ($round_counter-1) . "' style='width:100px' value=" . addWeek($start_date, 7, $round_counter) . "></td></tr>");
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
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . ($i+10) . "' style='width:100px' value=" . addWeek($start_date, 7, ($i+11)) . "></td></tr>");
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
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . $i . "'  style='width:100px' value=" . addWeek($start_date, 7, ($i+1)) . "></td></tr>");
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
 */
    //}
    //console.log(text);
    document.getElementById('output').append(text);
    //$('#output').append(text);
   
}


// Example usage
fixtures = ["Brunswick Titan", "YCBSC Break Builders", "Brunswick Taylor", "Dandy RSL Ball Breakers", "Brunswick Eagles", "NBC Mustangs"];
team_grade = "APS";
form_no = 1;
year = 2025;
season = "S1";
startdate = "2025-06-01";


main(fixtures, team_grade, form_no, year, season, startdate); 

</script>