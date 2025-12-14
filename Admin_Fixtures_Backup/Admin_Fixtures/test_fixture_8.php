<?php
/*

require_once('../Connections/connvbsa.php'); 

error_reporting(0);

mysql_select_db($database_connvbsa, $connvbsa);
$day_played = 'Mon';
// Sample data
$fixtures = '';
$sql_teams = "Select team_name, team_grade, team_club FROM vbsa3364_vbsa2.Team_entries where team_season = 'S1' and team_cal_year = 2025 and team_name != 'Bye' and day_played = '$day_played' order by team_grade";
$result_teams = mysql_query($sql_teams, $connvbsa) or die(mysql_error());
while($row = $result_teams->fetch_assoc())
{
    $teams[$row['team_name']] = [
            'team_grade' => $row['team_grade'],
            'team_club' => $row['team_club'],
        ];
}

$sql_venue = "Select team_club FROM vbsa3364_vbsa2.Team_entries where team_season = 'S1' and team_cal_year = 2025 and team_name != 'Bye' and day_played = '$day_played' order by team_grade";
$result_venue = mysql_query($sql_venue, $connvbsa) or die(mysql_error());
while($row = $result_venue->fetch_assoc())
{
    $venues[] = $row['team_club'];
}

$sql_tables = "Select distinct team_club, ClubTables FROM vbsa3364_vbsa2.Team_entries left Join clubs on ClubNumber = team_club_id where team_season = 'S1' and team_cal_year = 2025 and team_name != 'Bye' and day_played = '$day_played' order by team_grade";
$result_tables = mysql_query($sql_tables, $connvbsa) or die(mysql_error());
while($row = $result_tables->fetch_assoc())
{
    $tables[$row['team_club']] = (int)$row['ClubTables'];
}

$sql_grades = "Select distinct team_grade FROM vbsa3364_vbsa2.Team_entries where team_season = 'S1' and team_cal_year = 2025 and day_played = '$day_played'";
$result_grades = mysql_query($sql_grades, $connvbsa) or die(mysql_error());
while($row = $result_grades->fetch_assoc())
{
    $grades[] = $row['team_grade'];
}

*/
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
                //$schedule[] = ["home" => $home, "away" => $away, "venue" => $this->teams[$home]["team_club"]];
                //$schedule[] = ["home" => $away, "away" => $home, "venue" => $this->teams[$away]["team_club"]];
            }
        }
        return $schedule;
    }

    // Generate the full fixture
    public function createFixture() {
        foreach ($this->grades as $division) {
            $teams = array_keys(array_filter($this->teams, fn($t) => $t["division"] === $division));
            //$teams = array_keys(array_filter($this->teams, fn($t) => $t["team_grade"] === $division));
            $this->fixtures[$division] = $this->generateHomeAndAway($teams);
            //echo("<pre>");
            //echo(var_dump($this->fixtures[$division]));
            //echo("</pre>");
        }
        //$this->balanceRounds();
        $this->adjustForVenueCapacity();
    }



    // Ensure an equal number of matches per round
    private function balanceRounds() {
        foreach ($this->fixtures as $division => &$matches) {
            shuffle($matches);
            $numRounds = ceil(count($matches) / count($this->teams) * 2);
            $balancedRounds = array_fill(1, $numRounds, []);

            foreach ($matches as $index => $match) {
                $round = ($index % $numRounds) + 1;
                $balancedRounds[$round][] = $match;
            }
            $matches = $balancedRounds;
        }
    }


    // Adjust matches to respect venue constraints
    private function adjustForVenueCapacity() {
        $scheduledRounds = [];
        
        foreach ($this->fixtures as $division => $matches) {
            $rounds = [];
            
            foreach ($matches as $match) {
                $venue = $match["venue"];
                //$venue = $match["team_club"];
                
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
    "Team X" => ["division" => "Division 3", "venue" => "Venue X"],
    "Team Y" => ["division" => "Division 3", "venue" => "Venue Z"],
    "Team Z" => ["division" => "Division 3", "venue" => "Venue X"]
];

$venues = ["Venue X", "Venue Y", "Venue Z"];
$tables = ["Venue X" => 2, "Venue Y" => 1, "Venue Z" => 2];
$grades = ["Division 1", "Division 2", "Division 3"];



$fixture = new BilliardsFixture($teams, $venues, $tables, $grades);
//echo("<pre>");
//echo(var_dump($fixture));
//echo("</pre>");
$fixture->createFixture();
$fixture->displayFixture();

?>
