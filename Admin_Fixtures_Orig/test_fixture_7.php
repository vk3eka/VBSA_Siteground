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
        $scheduledRounds = [];
        
        foreach ($this->fixtures as $division => $matches) {
            $rounds = [];
            
            foreach ($matches as $match) {
                $venue = $match["venue"];
                
                // Find a suitable round
                $roundNumber = 1;
                while (isset($rounds[$roundNumber][$venue]) && count($rounds[$roundNumber][$venue]) >= $this->tables[$venue]) {
                    $roundNumber++;
                }
                
                // Schedule the match
                $rounds[$roundNumber][$venue][] = $match;
            }
            $scheduledRounds[$division] = $rounds;
        }
        $this->fixtures = $scheduledRounds;
    }

    // Display the final schedule
    public function displayFixture() {
        echo "<h1>Billiards Fixture</h1>";
        foreach ($this->fixtures as $division => $rounds) {
            echo "<h2>$division</h2>";
            foreach ($rounds as $round => $venues) {
                echo "<h3>Round $round</h3><ul>";
                foreach ($venues as $venue => $matches) {
                    echo "<li><strong>Venue: $venue</strong><ul>";
                    foreach ($matches as $match) {
                        echo "<li>{$match['home']} vs {$match['away']}</li>";
                    }
                    echo "</ul></li>";
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
$fixture = new BilliardsFixture($teams, $venues, $tables, $grades);
$fixture->createFixture();
$fixture->displayFixture();

?>
