<?php
error_reporting(0);
function getMatches($teams) {
    shuffle($teams);
    return call_user_func_array('array_combine', array_chunk($teams, sizeof($teams) / 2));
}
$form_no = 1;

$names = "Brunswick Champs Test
Brunswick Mafia Test
Bye
Camberwell All Stars
Camberwell Club All Stars
Camberwell Junction Test
Chelt Baulkers Test
FRSL Laggards Test
Kooyong Nets Test
RACV Blue Bloods Test
Test Ballarat Diggers
Yarra Crossing Test";
/*
$names_set_3 = "Brunswick Champs Test
Brunswick Mafia Test
Bye
Camberwell All Stars
Camberwell Club All Stars
Camberwell Junction Test
Chelt Baulkers Test
FRSL Laggards Test
Kooyong Nets Test
RACV Blue Bloods Test
Test Ballarat Diggers
Yarra Crossing Test";

$names_set_1 = "Brunswick Champs Test
Brunswick Mafia Test
Bye
Camberwell All Stars
Camberwell Club All Stars
Camberwell Junction Test";

$names_set_2 = "Chelt Baulkers Test
FRSL Laggards Test
Kooyong Nets Test
RACV Blue Bloods Test
Test Ballarat Diggers
Yarra Crossing Test";
*/
/*
echo("Names 1 " . $names . "<br>");

$names_set_3 = ($names_set_1 . "\n" . $names_set_2);
*/
//$names_set = explode("\n", $names);
//echo("Count " . count($names_set) . "<br>");
/*
echo("First 6<br>");
for ($i = 0; $i < (count($names_set)/2); $i++)
{
    echo($names_set[$i] . "<br>"); 
}
echo("<br>");
echo("Second 6<br>");
for ($i = (count($names_set)/2); $i < count($names_set); $i++)
{
    echo($names_set[$i] . "<br>");
}
echo("<br>");
echo("Total 12<br>");
for ($i = 0; $i < count($names_set); $i++)
{
    echo($names_set[$i] . "<br>"); 
}
*/
//$names_set_1 = explode("\n", $names, 6);
//echo("Count 1 " . count($names_set_1) . "<br>");

//$names_set_2 = explode("\n", $names, -6);
//echo("Count 2 " . count($names_set_2) . "<br>");

$names_set_1 = [];
$names_set_2 = [];
$names_set_3 = [];

$names = explode("\n", $names);
//echo("Count 1 " . count($names) . "<br>");
//echo("First 6<br>");
for ($i = 0; $i < (count($names)/2); $i++)
{
    //echo($names[$i] . "<br>");
    array_push($names_set_1, $names[$i]); 
}
//echo("<br>");

//echo("Second 6<br>");
for ($i = (count($names)/2); $i < count($names); $i++)
{
    //echo($names[$i] . "<br>");
    array_push($names_set_2, $names[$i]); 
}
//echo("<br>");

//echo("Total 12<br>");
for ($i = 0; $i < count($names); $i++)
{
    //echo($names[$i] . "<br>"); 
    array_push($names_set_3, $names[$i]); 
}
/*
echo("<pre>");
echo(var_dump($names_set_1));
echo("</pre>");
echo("<br>");
echo("<pre>");
echo(var_dump($names_set_2));
echo("</pre>");
echo("<br>");
echo("<pre>");
echo(var_dump($names_set_3));
echo("</pre>");
*/
//$names_set_1 = explode("\n", $names_set_1);
//$names_set_2 = explode("\n", $names_set_2);
//$names_set_3 = explode("\n", $names_set_3);

/*
 * This code owes an enormous debt to
 * http://www.barrychessclub.org.uk/berger2001.htm
*/

function main($names_set_1, $names_set_2, $names_set_3) {
    ?>
    <style>
    input, textarea { display: block; margin-bottom: 1em; }
    label { font-weight: bold; display: block; }
    </style>
    <h1>Fixtures Generator</h1>
    <p>This page is part of <a
    href="http://bluebones.net/2005/05/league-fixtures-generator/">bluebones.net</a>.</p>
    <?php
    // Find out how many teams we want fixtures for.
    //if (! isset($_GET['teams']) && ! isset($_GET['names'])) {
    //    print get_form();
    //} else {
        # XXX check for int
        //echo($_GET['names'] . "<br>");
        //echo("Names 1 " . $names_set_1 . "<br>Names 2 " . $names_set_2 . "<br>");
        //echo("Both " . ($names_set_3) . "<br>");
        //print show_fixtures(isset($_GET['teams']) ?  nums(intval($_GET['teams'])) : explode("\n", trim($_GET['names'])));
        print show_fixtures(isset($_GET['teams']) ?  nums(intval($_GET['teams'])) : $names_set_1, $names_set_2, $names_set_3);
        //print show_fixtures(isset($_GET['teams']) ?  nums(intval($_GET['teams'])) : explode("\n", $names_set_1), explode("\n", $names_set_2), explode("\n", $names_set_3));
    //}
}

function nums($n) {
    $ns = array();
    for ($i = 1; $i <= $n; $i++) {
        $ns[] = $i;
    }
    return $ns;
}

function show_fixtures($names_set_1, $names_set_2, $names_set_3) {
    $teams = sizeof($names_set_1);

    //print "<p>Fixtures for $teams teams.</p>";

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

    //print "<p>Group 1</p>"; // (Set 1 First Group)
    // Display the fixtures (Set 1 First Group)
    for ($i = 0; $i < sizeof($rounds); $i++) {
        echo("<tr><td>&nbsp;</td></tr>");
        echo("<td colspan=3 align='center'><b>Round " . ($i+1)  . "</b></td></tr>");
        echo("<tr><td align='right'><b>Date</b></td>");
        echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . $i . "' style='width:100px'></td></tr>");
        $x = 0;
        //print "<p>Round " . ($i + 1) . "</p>\n";
        foreach ($rounds[$i] as $r) {
            $round_data = explode(" v ", $r);
            //print $r . "<br />";
            echo("<tr>");
            echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
            echo("<td align='center'>v</td>");
            echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
            echo("</tr>");
            $x++;
        }
        //print "<br />";

    }
    

    //print "<p>Second half is mirror of first half</p>"; // (Set 2 First Group)
    $round_counter = sizeof($rounds) + 1;
    for ($i = 0; $i < sizeof($rounds); $i++) {
        //print "<p>Round " . $round_counter . "</p>\n";
        echo("<tr><td>&nbsp;</td></tr>");
        echo("<td colspan=3 align='center'><b>Round " . ($round_counter)  . "</b></td></tr>");
        echo("<tr><td align='right'><b>Date</b></td>");
        echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . $round_counter . "' style='width:100px'></td></tr>");
        $x = 0;
        $round_counter += 1;
        foreach ($rounds[$i] as $r) {
            //print flip($r) . "<br />";
            $round_data = explode(" v ", flip($r));
            //print $r . "<br />";
            echo("<tr>");
            echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
            echo("<td align='center'>v</td>");
            echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
            echo("</tr>");
            $x++;
        }
        print "<br />";
    }

    $teams = sizeof($names_set_2);

    //print "<p>Fixtures for $teams teams.</p>";

    //print "<p>Group 2</p>"; // (Set 1 First Group)
    // Display the fixtures (Set 1 First Group)
    for ($i = 0; $i < sizeof($rounds); $i++) {
        echo("<tr><td>&nbsp;</td></tr>");
        echo("<td colspan=3 align='center'><b>Round " . ($i+1)  . "</b></td></tr>");
        echo("<tr><td align='right'><b>Date</b></td>");
        echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . $i . "' style='width:100px'></td></tr>");
        $x = 0;
        //print "<p>Round " . ($i + 1) . "</p>\n";
        foreach ($rounds[$i] as $r) {
            $round_data = explode(" v ", $r);
            //print $r . "<br />";
            echo("<tr>");
            echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
            echo("<td align='center'>v</td>");
            echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
            echo("</tr>");
            $x++;
        }
        //print "<br />";

    }
    
    //print "<p>Second half is mirror of first half</p>"; // (Set 2 First Group)
    $round_counter = sizeof($rounds) + 1;
    for ($i = 0; $i < sizeof($rounds); $i++) {
        //print "<p>Round " . $round_counter . "</p>\n";
        echo("<tr><td>&nbsp;</td></tr>");
        echo("<td colspan=3 align='center'><b>Round " . ($round_counter)  . "</b></td></tr>");
        echo("<tr><td align='right'><b>Date</b></td>");
        echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . $round_counter . "' style='width:100px'></td></tr>");
        $x = 0;
        $round_counter += 1;
        foreach ($rounds[$i] as $r) {
            //print flip($r) . "<br />";
            $round_data = explode(" v ", flip($r));
            //print $r . "<br />";
            echo("<tr>");
            echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
            echo("<td align='center'>v</td>");
            echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
            echo("</tr>");
            $x++;
        }
        print "<br />";
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

    //print "<p>Both Groups</p>"; // (Set 1 Both Groups)
    $teams = sizeof($names_set_3);

    //print "<p>Fixtures for $teams teams.</p>";

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
            $rounds[$round][$match] = team_name($home + 1, $names_set_3)
                . " v " . team_name($away + 1, $names_set_3);
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
    //print "<p>Group 3</p>"; // (Set 1 First Group)
    for ($i = 0; $i < sizeof($rounds); $i++) {
        echo("<tr><td>&nbsp;</td></tr>");
        echo("<td colspan=3 align='center'><b>Round " . ($i+1)  . "</b></td></tr>");
        echo("<tr><td align='right'><b>Date</b></td>");
        echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . $i . "' style='width:100px'></td></tr>");
        $x = 0;
        //print "<p>Round " . ($i + 1) . "</p>\n";
        foreach ($rounds[$i] as $r) {
            $round_data = explode(" v ", $r);
            //print $r . "<br />";
            echo("<tr>");
            echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
            echo("<td align='center'>v</td>");
            echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
            echo("</tr>");
            $x++;
        }
        //print "<br />";

    }
    
    echo("</tbody>");
    echo("</table>");


    
}

function flip($match) {
    $components = explode(' v ', $match);
    return $components[1] . " v " . $components[0];
}

function team_name($num, $names_set_3) {
    $i = $num - 1;
    if (sizeof($names_set_3) > $i && strlen($names_set_3[$i]) > 0) {
        return ($names_set_3[$i]);
    } else {
        return $num;
    }
}

function get_form() {
    $s = '';
    $s = '<p>Enter number of teams OR team names</p>' . "\n";
    $s .= '<form action="' . $_SERVER['SCRIPT_NAME'] . '">' . "\n";
    $s .= '<label for="teams">Number of Teams</label><input type="text" name="teams" />' . "\n";
    $s .= '<input type="submit" value="Generate Fixtures" />' . "\n";
    $s .= '</form>' . "\n";

    $s .= '<form action="' . $_SERVER['SCRIPT_NAME'] . '">' . "\n";
    $s .= '<div><strong>OR</strong></div>' . "\n";
    $s .= '<label for="names">Names of Teams (one per line)</label>'
        . '<textarea name="names" rows="8" cols="40"></textarea>' . "\n";
    $s .= '<input type="submit" value="Generate Fixtures" />' . "\n";
    $s .= "</form>\n";
    return $s;
}

main($names_set_1, $names_set_2, $names_set_3);

?>


