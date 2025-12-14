<?php

$teams = [
    'Team A', 'Team B', 'Team C', 'Team D',
    'Team E', 'Team F', 'Team G', 'Team H'
];

$venues = [
    ['venue_name' => 'Venue 1', 'tables' => 0],
    ['venue_name' => 'Venue 2', 'tables' => 2],
    ['venue_name' => 'Venue 3', 'tables' => 4]
];



function generateFixture($teams, $venues) {
    $fixtures = [];
    $num_teams = count($teams);
    $num_venues = count($venues);

    // Create a round-robin fixture for each team
    for ($round = 1; $round <= ($num_teams - 1); $round++) {
        $round_fixtures = [];
        
        // Loop through all teams and schedule matches
        for ($i = 0; $i < $num_teams / 2; $i++) {
            $home = $teams[$i];
            $away = $teams[$num_teams - 1 - $i];
            
            // Find an available venue for this round (based on available tables)
            $venue = findAvailableVenue($venues);
            
            $round_fixtures[] = [
                'home' => $home,
                'away' => $away,
                'venue' => $venue['venue_name'],
                'round' => $round
            ];
        }

        // Add this round's fixtures to the main list
        $fixtures[] = $round_fixtures;
    }

    //echo("<pre>");
    //echo(var_dump($fixtures));
    //echo("</pre>");
    return $fixtures;
}

function findAvailableVenue($venues) {
    // Simple strategy: just pick the first venue with enough tables for a match
    foreach ($venues as $venue) {
        if ($venue['tables'] > 0) {
            return $venue;
        }
    }

    return null;
}

$fixtures = generateFixture($teams, $venues);

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

    foreach ($fixtures as $round => $round_fixtures) {
        echo '<h2>Round ' . ($round + 1) . '</h2>';
        echo '<table>';
        echo '<tr><th>Home Team</th><th>Away Team</th><th>Venue</th></tr>';
        foreach ($round_fixtures as $fixture) {
            echo '<tr>';
            echo '<td>' . $fixture['home'] . '</td>';
            echo '<td>' . $fixture['away'] . '</td>';
            echo '<td>' . $fixture['venue'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
    ?>
</body>
</html>

