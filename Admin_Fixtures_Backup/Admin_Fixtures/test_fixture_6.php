<?php

$teams = [
    "Team A" => ["division" => "Division 1", "venue" => "Venue X"],
    "Team B" => ["division" => "Division 1", "venue" => "Venue Y"],
    "Team C" => ["division" => "Division 1", "venue" => "Venue X"],
    "Team D" => ["division" => "Division 1", "venue" => "Venue Z"],
    "Team E" => ["division" => "Division 1", "venue" => "Venue Y"],
    "Team F" => ["division" => "Division 1", "venue" => "Venue X"],
    "Team G" => ["division" => "Division 1", "venue" => "Venue Z"],
    "Team H" => ["division" => "Division 1", "venue" => "Venue X"],

    "Team I" => ["division" => "Division 2", "venue" => "Venue X"],
    "Team J" => ["division" => "Division 2", "venue" => "Venue Y"],
    "Team K" => ["division" => "Division 2", "venue" => "Venue X"],
    "Team L" => ["division" => "Division 2", "venue" => "Venue Z"],
    "Team M" => ["division" => "Division 2", "venue" => "Venue Y"],
    "Team N" => ["division" => "Division 2", "venue" => "Venue X"],
    "Team O" => ["division" => "Division 2", "venue" => "Venue Z"],
    "Team P" => ["division" => "Division 2", "venue" => "Venue X"],

    "Team Q" => ["division" => "Division 3", "venue" => "Venue X"],
    "Team R" => ["division" => "Division 3", "venue" => "Venue Y"],
    "Team S" => ["division" => "Division 3", "venue" => "Venue X"],
    "Team T" => ["division" => "Division 3", "venue" => "Venue Z"],
    "Team U" => ["division" => "Division 3", "venue" => "Venue Y"],
    "Team V" => ["division" => "Division 3", "venue" => "Venue X"],
    "Team W" => ["division" => "Division 3", "venue" => "Venue Z"],
    "Team X" => ["division" => "Division 3", "venue" => "Venue X"]
];

$venues = ["Venue X", "Venue Y", "Venue Z"];

$tables = ["Venue X" => 2, "Venue Y" => 1, "Venue Z" => 2];

$grades = ["Division 1", "Division 2", "Division 3"];

class BilliardsFixture {
    private $teams;
    private $venues;
    private $tables;
    private $grades;
    private $fixtures;

    public function __construct($teams, $venues, $tables, $grades) {
        $this->teams = $teams;
        $this->venues = $venues;
        $this->tables = $tables;
        $this->grades = $grades;
        $this->fixtures = [];
    }

    // Generate home and away fixtures for each division
    private function generateHomeAndAway($teams) {
        $schedule = [];
        $numTeams = count($teams);
        
        for ($i = 0; $i < $numTeams; $i++) {
            for ($j = $i + 1; $j < $numTeams; $j++) {
                $home = $teams[$i];
                $away = $teams[$j];
                
                // Home and away matches
                $schedule[] = ["home" => $home, "away" => $away, "venue" => $this->teams[$home]["venue"]];
                $schedule[] = ["home" => $away, "away" => $home, "venue" => $this->teams[$away]["venue"]];
            }
        }
        return $schedule;
    }

    // Generate the full fixture
    public function createFixture() {
        foreach ($this->grades as $division) {
            $teams = array_keys(array_filter($this->teams, fn($t) => $t["division"] === $division));
            $this->fixtures[$division] = $this->generateHomeAndAway($teams);
        }
        $this->adjustForVenueCapacity();
    }

    // Adjust matches to respect venue constraints
    private function adjustForVenueCapacity() {
        $schedule = [];
        //echo("<pre>");
        //echo(var_dump($this->fixtures));
        //echo("</pre>");

        foreach ($this->fixtures as $division => $rounds) {
            foreach ($rounds as $roundIndex => $matches) {
                foreach ($matches as $match) {
                    echo("<pre>");
                    echo(var_dump($match));
                    echo("</pre>");
                    //$venue = $match["venue"];
                    $venue = $match;
                    $schedule[$division][$roundIndex][$venue][] = $match;
                }
            }
        }

        // Check for venue overbooking and adjust
        foreach ($schedule as $division => $rounds) {
            foreach ($rounds as $roundIndex => $venues) {
                foreach ($venues as $venue => $matches) {
                    if (count($matches) > $this->tables[$venue]) {
                        // Postpone excess matches to a later round
                        $excessMatches = array_splice($matches, $this->tables[$venue]);
                        $schedule[$division][$roundIndex][$venue] = $matches;
                        $schedule[$division][$roundIndex + 1][$venue] = array_merge($schedule[$division][$roundIndex + 1][$venue] ?? [], $excessMatches);
                    }
                }
            }
        }
        $this->fixtures = $schedule;
    }

    // Fetch teams from database
    /*
    public static function fetchTeamsFromDB($conn) {
        $sql = "SELECT team_name, division, venue FROM teams";
        $result = $conn->query($sql);
        $teams = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $teams[$row['team_name']] = [
                    'division' => $row['division'],
                    'venue' => $row['venue']
                ];
            }
        }
        return $teams;
    }
    */

    // Display the final schedule
    public function displayFixture() {
        echo "<h1>Billiards Fixture</h1>";
        foreach ($this->fixtures as $division => $matches) {
            echo "<h2>$division</h2><ul>";
            foreach ($matches as $match) {
                echo "<li>{$match['home']} vs {$match['away']} at {$match['venue']}</li>";
            }
            echo "</ul>";
        }
    }
}


$fixture = new BilliardsFixture($teams, $venues, $tables, $grades);
$fixture->createFixture();
$fixture->displayFixture();

?>
