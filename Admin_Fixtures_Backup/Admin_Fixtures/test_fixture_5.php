<?php
require_once('../Connections/connvbsa.php'); 

error_reporting(0);

mysql_select_db($database_connvbsa, $connvbsa);

// Sample data
$fixtures = '';
$sql_teams = "Select team_name, team_grade, team_club FROM vbsa3364_vbsa2.Team_entries where team_season = 'S1' and team_cal_year = 2025 and team_name != 'Bye' order by team_grade";
$result_teams = mysql_query($sql_teams, $connvbsa) or die(mysql_error());
while($row = $result_teams->fetch_assoc())
{
    $teams[$row['team_name']] = [
            'team_grade' => $row['team_grade'],
            'team_club' => $row['team_club'],
        ];
}

$sql_venue = "Select team_club FROM vbsa3364_vbsa2.Team_entries where team_season = 'S1' and team_cal_year = 2025 and team_name != 'Bye' order by team_grade";
$result_venue = mysql_query($sql_venue, $connvbsa) or die(mysql_error());
while($row = $result_venue->fetch_assoc())
{
    $venues[] = $row['team_club'];
}

$sql_tables = "Select distinct team_club, ClubTables FROM vbsa3364_vbsa2.Team_entries left Join clubs on ClubNumber = team_club_id where team_season = 'S1' and team_cal_year = 2025 and team_name != 'Bye' order by team_grade";
$result_tables = mysql_query($sql_tables, $connvbsa) or die(mysql_error());
while($row = $result_tables->fetch_assoc())
{
    $tables[$row['team_club']] = (int)$row['ClubTables'];
}

$sql_grades = "Select distinct team_grade FROM vbsa3364_vbsa2.Team_entries where team_season = 'S1' and team_cal_year = 2025 and day_played = 'Mon'";
$result_grades = mysql_query($sql_grades, $connvbsa) or die(mysql_error());
while($row = $result_grades->fetch_assoc())
{
    $grades[] = $row['team_grade'];
}

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
/*
// Generate home and away fixtures for each division
    private function generateHomeAndAway($teams) {
        $schedule = [];
        $numTeams = count($teams);
        
        for ($i = 0; $i < $numTeams; $i++) {
            for ($j = $i + 1; $j < $numTeams; $j++) {
                $home = $teams[$i];
                $away = $teams[$j];
                
                // Home and away matches
                $schedule[] = ["home" => $home, "away" => $away, "venue" => $this->teams[$home]["team_club"]];
                $schedule[] = ["home" => $away, "away" => $home, "venue" => $this->teams[$away]["team_club"]];
            }
        }
        return $schedule;
    }
*/

    // Generate round-robin fixtures for each division
    private function generateRoundRobin($teams) {
        $schedule = [];
        $numTeams = count($teams);
        $rounds = $numTeams - 1;
        
        for ($round = 0; $round < $rounds; $round++) {
            $matches = [];
            for ($i = 0; $i < $numTeams / 2; $i++) {
                $home = $teams[$i];
                $away = $teams[$numTeams - 1 - $i];
                $matches[] = ["home" => $home, "away" => $away, "venue" => $this->teams[$home]["team_club"]];
            }
            $schedule[] = $matches;
            array_splice($teams, 1, 0, array_pop($teams)); // Rotate teams
        }
        return $schedule;
    }

    // Generate the full fixture
    public function createFixture() {
        foreach ($this->grades as $division) {
            $teams = array_keys(array_filter($this->teams, fn($t) => $t["team_grade"] === $division));
            $this->fixtures[$division] = $this->generateRoundRobin($teams);
        }
        $this->adjustForVenueCapacity();
    }

    // Adjust matches to respect venue constraints
    private function adjustForVenueCapacity() {
        $schedule = [];
        echo("<pre>");
        echo(var_dump($this->fixtures));
        echo("</pre>");
        foreach ($this->fixtures as $division => $matches) {
            foreach ($matches as $match) {
                $venue = $match["team_club"];
                $schedule[$division][$venue][] = $match;
            }
        }

        // Check for venue overbooking and adjust
        foreach ($schedule as $division => $venues) {
            foreach ($venues as $venue => $matches) {
                if (count($matches) > $this->tables[$venue]) {
                    // Postpone excess matches to a later round
                    $excessMatches = array_splice($matches, $this->tables[$venue]);
                    $schedule[$division][$venue] = $matches;
                    $schedule[$division][$venue . "_overflow"] = $excessMatches;
                }
            }
        }

        $this->fixtures = $schedule;
    }


/*
    // Fetch teams from database
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
        foreach ($this->fixtures as $division => $rounds) {
            echo "<h2>$division</h2>";
            foreach ($rounds as $round => $venues) {
                echo "<h3>Round " . ($round + 1) . "</h3><ul>";
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

/*
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "billiards_db";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$teams = BilliardsFixture::fetchTeamsFromDB($conn);
$venues = ["Venue X", "Venue Y", "Venue Z"];
$tables = ["Venue X" => 2, "Venue Y" => 1, "Venue Z" => 2];

$conn->close();
*/

$fixture = new BilliardsFixture($teams, $venues, $tables, $grades);
$fixture->createFixture();
$fixture->displayFixture();

?>
