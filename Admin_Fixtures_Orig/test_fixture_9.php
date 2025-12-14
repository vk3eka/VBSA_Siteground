<?php

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

    // Generate double round-robin fixtures for each division
    private function generateDoubleRoundRobin($teams) {
        if (count($teams) % 2 !== 0) {
            $teams[] = "BYE"; // Add a bye if odd number of teams
        }
        
        $schedule = [];
        $numTeams = count($teams);
        $numRounds = $numTeams - 1;
        
        for ($round = 0; $round < $numRounds; $round++) {
            $matches = [];
            for ($i = 0; $i < $numTeams / 2; $i++) {
                $home = $teams[$i];
                $away = $teams[$numTeams - 1 - $i];
                
                if ($home !== "BYE" && $away !== "BYE") {
                    $matches[] = ["home" => $home, "away" => $away, "venue" => $this->teams[$home]["venue"]];
                }
            }
            $schedule[$round + 1] = $matches;
            array_splice($teams, 1, 0, array_pop($teams)); // Rotate teams
        }
        
        // Second half - reverse fixtures
        $secondHalf = [];
        foreach ($schedule as $round => $matches) {
            $reversedMatches = [];
            foreach ($matches as $match) {
                $reversedMatches[] = ["home" => $match["away"], "away" => $match["home"], "venue" => $this->teams[$match["away"]]["venue"]];
            }
            $secondHalf[$round + $numRounds] = $reversedMatches;
        }
        
        return array_merge($schedule, $secondHalf);
    }

    // Generate the full fixture
    public function createFixture() {
        foreach (["Division 1", "Division 2", "Division 3"] as $division) {
            $teams = array_keys(array_filter($this->teams, fn($t) => $t["division"] === $division));
            $this->fixtures[$division] = $this->generateDoubleRoundRobin($teams);
        }
        $this->adjustForVenueCapacity();
        $this->ensureOneMatchPerDay();
    }

    // Ensure a team plays only one fixture per day
    private function ensureOneMatchPerDay() {
        foreach ($this->fixtures as $division => &$rounds) {
            foreach ($rounds as $round => &$matches) {
                $scheduledTeams = [];
                $filteredMatches = [];
                
                foreach ($matches as $match) {
                    if (!in_array($match['home'], $scheduledTeams) && !in_array($match['away'], $scheduledTeams)) {
                        $filteredMatches[] = $match;
                        $scheduledTeams[] = $match['home'];
                        $scheduledTeams[] = $match['away'];
                    }
                }
                $matches = $filteredMatches;
            }
        }
    }

    // Adjust matches to respect venue constraints
    private function adjustForVenueCapacity() {
        foreach ($this->fixtures as $division => &$rounds) {
            foreach ($rounds as $round => &$matches) {
                $venueMatches = [];
                foreach ($matches as $match) {
                    $venue = $match["venue"];
                    if (!isset($venueMatches[$venue])) {
                        $venueMatches[$venue] = [];
                    }
                    if (count($venueMatches[$venue]) < $this->tables[$venue]) {
                        $venueMatches[$venue][] = $match;
                    }
                }
                $matches = array_merge(...array_values($venueMatches));
            }
        }
    }

    // Display the final schedule
    public function displayFixture() {
        echo "<h1>Billiards Fixture</h1>";
        foreach ($this->fixtures as $division => $rounds) {
            echo "<h2>$division</h2>";
            foreach ($rounds as $round => $matches) {
                echo "<h3>Round $round</h3><ul>";
                foreach ($matches as $match) {
                    if ($match['home'] === "BYE" || $match['away'] === "BYE") {
                        echo "<li>{$match['home']} has a bye</li>";
                    } else {
                        echo "<li>{$match['home']} vs {$match['away']} at {$match['venue']}</li>";
                    }
                }
                echo "</ul>";
            }
        }
    }
}


// Example teams and venues setup
$teams = [
    "Team A" => ["division" => "Division 1", "venue" => "Venue X"],
    "Team B" => ["division" => "Division 1", "venue" => "Venue Y"],
    "Team C" => ["division" => "Division 1", "venue" => "Venue X"],
    "Team D" => ["division" => "Division 1", "venue" => "Venue Z"],
    "Team E" => ["division" => "Division 1", "venue" => "Venue Y"],
    "Team F" => ["division" => "Division 1", "venue" => "Venue X"],
    "Team G" => ["division" => "Division 1", "venue" => "Venue Z"],

    "Team H" => ["division" => "Division 2", "venue" => "Venue X"],
    "Team I" => ["division" => "Division 2", "venue" => "Venue X"],
    "Team J" => ["division" => "Division 2", "venue" => "Venue Y"],
    "Team K" => ["division" => "Division 2", "venue" => "Venue X"],
    "Team L" => ["division" => "Division 2", "venue" => "Venue Z"],
    "Team M" => ["division" => "Division 2", "venue" => "Venue Y"],

    "Team N" => ["division" => "Division 3", "venue" => "Venue X"],
    "Team O" => ["division" => "Division 3", "venue" => "Venue Z"],
    "Team P" => ["division" => "Division 3", "venue" => "Venue X"],
    "Team Q" => ["division" => "Division 3", "venue" => "Venue X"],
    "Team R" => ["division" => "Division 3", "venue" => "Venue Y"],
    "Team S" => ["division" => "Division 3", "venue" => "Venue X"],
    "Team T" => ["division" => "Division 3", "venue" => "Venue Z"],
    "Team U" => ["division" => "Division 3", "venue" => "Venue Y"],
    "Team V" => ["division" => "Division 3", "venue" => "Venue X"],
    "Team W" => ["division" => "Division 3", "venue" => "Venue Z"],
    "Team X" => ["division" => "Division 3", "venue" => "Venue X"],
    "Team Y" => ["division" => "Division 3", "venue" => "Venue Z"],
    "Team Z" => ["division" => "Division 3", "venue" => "Venue X"]
];


$venues = ["Venue X", "Venue Y", "Venue Z"];
$tables = ["Venue X" => 2, "Venue Y" => 1, "Venue Z" => 2];
$grades = ["Division 1", "Division 2", "Division 3"];

$fixture = new BilliardsFixture($teams, $venues, $tables, $grades);
$fixture->createFixture();
$fixture->displayFixture();

?>