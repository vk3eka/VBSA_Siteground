<?php
error_reporting(0);
$conn = new PDO("mysql:host=localhost;dbname=demo", "peterj", "abj059XZ@!");

$teams = $conn->query("Select id, name, venue_id, division_id FROM teams")->fetchAll(PDO::FETCH_ASSOC);
$venues = $conn->query("Select id, name, tables_available FROM venues")->fetchAll(PDO::FETCH_ASSOC);

function generateBalancedFixtures($teams) {
    $numTeams = count($teams);
    if ($numTeams % 2 !== 0) {
        $teams[] = ["id" => null, "name" => "Bye"]; // Handle odd teams
        $numTeams++;
    }

    $fixtures = [];
    $homeCount = array_fill_keys(array_column($teams, 'id'), 0);
    $awayCount = array_fill_keys(array_column($teams, 'id'), 0);
/*
    echo("Home");
    echo("<pre>");
    echo(var_dump($homeCount));
    echo("</pre>");

    echo("Away");
    echo("<pre>");
    echo(var_dump($awayCount));
    echo("</pre>");
*/
    for ($round = 1; $round < $numTeams; $round++) {
        for ($i = 0; $i < $numTeams / 2; $i++) {
            $team1 = $teams[$i];
            $team2 = $teams[$numTeams - 1 - $i];

            //echo($team1['id'] . ", " . $team2['id'] . "<br>");
            if (($team1['id']) && ($team2['id'])) {
                // Decide home & away based on previous counts
                if ($homeCount[$team1["id"]] <= $homeCount[$team2["id"]]) {
                    $fixtures[] = ["home" => $team1, "away" => $team2, "round" => $round];
                    $homeCount[$team1["id"]]++;
                    $awayCount[$team2["id"]]++;
                } else {
                    $fixtures[] = ["home" => $team2, "away" => $team1, "round" => $round];
                    $homeCount[$team2["id"]]++;
                    $awayCount[$team1["id"]]++;
                }

                // Reverse fixtures for second half of the season
                $fixtures[] = ["home" => $fixtures[count($fixtures) - 1]["away"], 
                               "away" => $fixtures[count($fixtures) - 1]["home"], 
                               "round" => $round + ($numTeams - 1)];
            }
        }
        array_splice($teams, 1, 0, array_pop($teams)); // Rotate teams
    }
    return $fixtures;
}

function assignVenuesForAllDivisions($fixtures, $venues) {
    $schedule = [];
    $venueUsage = [];

    foreach ($fixtures as $match) {
        $venueId = $match["home"]["venue_id"];
        $round = $match["round"];

        if (!isset($venueUsage[$round])) {
            $venueUsage[$round] = [];
        }

        if (!isset($venueUsage[$round][$venueId])) {
            $venueUsage[$round][$venueId] = 0;
        }

        $tablesAvailable = array_filter($venues, fn($v) => $v["id"] == $venueId)[0]["tables_available"];

        if ($venueUsage[$round][$venueId] < $tablesAvailable) {
            $schedule[] = [
                "home_team_id" => $match["home"]["id"],
                "away_team_id" => $match["away"]["id"],
                "venue_id" => $venueId,
                "division_id" => $match["division_id"],
                "round" => $round
            ];
            $venueUsage[$round][$venueId]++;
        } else {
            // Venue is full, give a rest week
            $schedule[] = [
                "home_team_id" => null,
                "away_team_id" => null,
                "venue_id" => null,
                "division_id" => $match["division_id"],
                "round" => $round
            ];
        }
    }

    return $schedule;
}


function generateDivisionFixtures($teamsByDivision) {
    $allFixtures = [];

    foreach ($teamsByDivision as $divisionId => $teams) {
        $fixtures = generateBalancedFixtures($teams); // Uses previous round-robin function
        foreach ($fixtures as &$match) {
            $match['division_id'] = $divisionId;
        }
        $allFixtures = array_merge($allFixtures, $fixtures);
    }

    return $allFixtures;
}


$teamsByDivision = [];
foreach ($teams as $team) {
    $teamsByDivision[$team['division_id']][] = $team;
}

function assignVenuesWithoutRestDays($fixtures, $venues) {
    $schedule = [];
    $venueUsage = [];

    foreach ($fixtures as $match) {
        $venueId = $match["home"]["venue_id"];
        $round = $match["round"];

        if (!isset($venueUsage[$round])) {
            $venueUsage[$round] = [];
        }

        if (!isset($venueUsage[$round][$venueId])) {
            $venueUsage[$round][$venueId] = 0;
        }

        $tablesAvailable = array_filter($venues, fn($v) => $v["id"] == $venueId)[0]["tables_available"];

        // If the venue is full for this round, find the nearest available round
        if ($venueUsage[$round][$venueId] >= $tablesAvailable) {
            $newRound = $round;
            while (isset($venueUsage[$newRound][$venueId]) && $venueUsage[$newRound][$venueId] >= $tablesAvailable) {
                $newRound++;  // Move match to a later round
            }
            $round = $newRound;  // Assign the new round
        }

        // Schedule match in available round
        $schedule[] = [
            "home_team_id" => $match["home"]["id"],
            "away_team_id" => $match["away"]["id"],
            "venue_id" => $venueId,
            "division_id" => $match["division_id"],
            "round" => $round
        ];
        
        // Update venue usage
        if (!isset($venueUsage[$round])) {
            $venueUsage[$round] = [];
        }
        if (!isset($venueUsage[$round][$venueId])) {
            $venueUsage[$round][$venueId] = 0;
        }
        $venueUsage[$round][$venueId]++;
    }

    return $schedule;
}

$fixtures = generateDivisionFixtures($teamsByDivision);
//echo("<pre>");
//echo(var_dump($fixtures));
//echo("</pre>");
//$schedule = assignVenuesForAllDivisions($fixtures, $venues);
$schedule = assignVenuesWithoutRestDays($fixtures, $venues);
//echo("<pre>");
//echo(var_dump($schedule));
//echo("</pre>");
$sql = "Insert INTO fixtures (home_team_id, away_team_id, venue_id, division_id, match_date) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

foreach ($schedule as $match) {
    if ($match["home_team_id"] === null) {
        continue; // Skip rest weeks
    }

    $matchDate = date('Y-m-d', strtotime("+{$match['round']} weeks"));
    $stmt->execute([$match["home_team_id"], $match["away_team_id"], $match["venue_id"], $match["division_id"], $matchDate]);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billiard Fixture Generator</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h1>Billiard Fixtures</h1>
    <?php
    // Include the fixture generation code here
    // (e.g., $fixtures = generateFixture($teams, $venues);)

    //echo("<pre>");
    //echo(var_dump($fixtures));
    //echo("</pre>");
    echo '<table>';
    echo '<tr><th>Home Team</th><th>Away Team</th><th>Venue</th><th>Division</th></tr>';
    foreach ($fixtures as $round_fixtures) {
        //echo '<h2>Round ' . ($round + 1) . '</h2>';
       
        //echo("<pre>");
        //echo(var_dump($round_fixtures));
        //echo("</pre>");
        foreach ($round_fixtures as $fixture) {
            //echo '<h2>Round ' . ($round + 1) . '</h2>';
            //echo("<pre>");
            //echo(var_dump($fixture));
            //echo("</pre>");
            echo '<tr>';
            echo '<td>' . $fixture['name'] . '</td>';
            echo '<td>' . $fixture['name'] . '</td>';
            echo '<td>' . $fixture['venue_id'] . '</td>';
            echo '<td>' . $fixture['division_id'] . '</td>';
            echo '</tr>';
        }
        //echo '</table>';
    }
    echo '</table>';
    ?>
</body>
</html>
