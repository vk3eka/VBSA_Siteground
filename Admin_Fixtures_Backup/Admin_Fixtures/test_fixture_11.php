<?php
class BilliardsFixture {
    private $teams;
    private $venues;
    private $tables;
    private $fixtures = [];
    private $startDate;

    public function __construct($teams, $venues, $tables, $startDate) {
        $this->teams = $teams; // Teams with their home venue
        $this->venues = $venues; // List of venue names
        $this->tables = $tables; // Number of tables per venue
        $this->startDate = new DateTime($startDate);
    }

    public function generateFixtures() {
        foreach ($this->getDivisions() as $division) {
            $teams = array_keys(array_filter($this->teams, fn($t) => $t["division"] === $division));
            
            // Add BYE if teams are odd
            if (count($teams) % 2 !== 0) {
                $teams[] = "BYE";
            }
            
            $this->fixtures[$division] = $this->generateDoubleRoundRobin($teams);
        }
        
        $this->adjustForVenueCapacity();
        $this->balanceHomeAway();
    }

    private function generateDoubleRoundRobin($teams) {
        $rounds = [];
        $numRounds = count($teams) - 1;
        $numMatchesPerRound = count($teams) / 2;

        // Generate first half
        for ($r = 0; $r < $numRounds; $r++) {
            $matches = [];
            for ($m = 0; $m < $numMatchesPerRound; $m++) {
                $home = $teams[$m];
                $away = $teams[$numRounds - $m];
                if ($away === "BYE") {
                    $matches[] = ["home" => $home, "away" => "BYE", "venue" => null];
                } else {
                    $venue = $this->teams[$home]["venue"];
                    $matches[] = ["home" => $home, "away" => $away, "venue" => $venue];
                }
            }
            $rounds[] = $matches;
            array_splice($teams, 1, 0, array_pop($teams));
        }

        // Generate second half (reverse home/away)
        $secondHalf = array_map(fn($round) => array_map(fn($match) => $match["away"] !== "BYE" ?
            ["home" => $match["away"], "away" => $match["home"], "venue" => $this->teams[$match["away"]]["venue"]] :
            $match, $round), $rounds);

        return array_merge($rounds, $secondHalf);
    }

    private function adjustForVenueCapacity() {
        $rescheduledMatches = [];

        foreach ($this->fixtures as $division => &$rounds) {
            foreach ($rounds as &$matches) {
                $venueMatches = [];
                foreach ($matches as $match) {
                    if ($match["away"] === "BYE") {
                        $venueMatches["BYE"][] = $match;
                        continue;
                    }
                    $venue = $match["venue"];
                    if (!isset($venueMatches[$venue])) {
                        $venueMatches[$venue] = [];
                    }
                    if (count($venueMatches[$venue]) < $this->tables[$venue]) {
                        $venueMatches[$venue][] = $match;
                    } else {
                        $rescheduledMatches[$division][] = $match;
                    }
                }
                $matches = array_merge(...array_values($venueMatches));
            }
        }
        foreach ($rescheduledMatches as $division => $matches) {
            foreach ($matches as $match) {
                $this->rescheduleMatch($division, $match);
            }
        }
    }

    private function rescheduleMatch($division, $match) {
        $venue = $this->teams[$match["home"]]["venue"];
        foreach ($this->fixtures[$division] as &$roundMatches) {
            $venueCount = array_count_values(array_column($roundMatches, "venue"))[$venue] ?? 0;
            if ($venueCount < $this->tables[$venue]) {
                $roundMatches[] = $match;
                return;
            }
        }
        $newRound = count($this->fixtures[$division]) + 1;
        $this->fixtures[$division][$newRound] = [$match];
    }

    private function balanceHomeAway() {
        $homeCounts = [];
        $totalTeams = [];
        foreach ($this->fixtures as $division => &$rounds) {
            foreach ($rounds as &$matches) {
                foreach ($matches as &$match) {
                    if ($match["away"] === "BYE") continue;
                    $homeCounts[$match["home"]] = ($homeCounts[$match["home"]] ?? 0) + 1;
                    $totalTeams[$match["home"]] = true;
                    $totalTeams[$match["away"]] = true;
                }
            }
        }
        $idealHomeGames = intdiv(array_sum($homeCounts), count($totalTeams));
        foreach ($this->fixtures as $division => &$rounds) {
            foreach ($rounds as &$matches) {
                foreach ($matches as &$match) {
                    if ($match["away"] === "BYE") continue;
                    if ($homeCounts[$match["home"]] > $idealHomeGames && $homeCounts[$match["away"]] < $idealHomeGames) {
                        [$match["home"], $match["away"]] = [$match["away"], $match["home"]];
                        $match["venue"] = $this->teams[$match["home"]]["venue"];
                        $homeCounts[$match["home"]]--;
                        $homeCounts[$match["away"]]++;
                    }
                }
            }
        }
    }

    private function getDivisions() {
        return array_unique(array_column($this->teams, "division"));
    }

    public function getFixtures() {
        return $this->fixtures;
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
    "Team X" => ["division" => "Division 3", "venue" => "Venue X"],
    "Team Y" => ["division" => "Division 3", "venue" => "Venue Z"],
    "Team Z" => ["division" => "Division 3", "venue" => "Venue X"]
];

$venues = ["Venue X", "Venue Y", "Venue Z"];
$tables = ["Venue X" => 2, "Venue Y" => 1, "Venue Z" => 2];
$startDate = '2025-03-08';

$fixture = new BilliardsFixture($teams, $venues, $tables, $startDate);
$fixture->generateFixtures();
$fixture->displayFixture();

?>