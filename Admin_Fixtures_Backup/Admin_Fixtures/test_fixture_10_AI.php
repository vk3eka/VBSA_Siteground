<?php

require_once('../Connections/connvbsa.php'); 

error_reporting(0);

mysqli_select_db($connvbsa, $database_connvbsa);

$dayplayed = 'Mon';
$season = 'S1';
$year = 2025;

// Sample data
$fixtures = '';

// delete existing fixtures
$sql_delete = "Delete FROM tbl_create_fixtures where year = " . $year . " and season = '" . $season . "' and dayplayed = '". $dayplayed . "'";
$result_delete = mysqli_query($connvbsa, $sql_delete) or die(mysqli_error($connvbsa));

//$sql_teams = "Select team_name, team_grade, team_club, team_club_id, comptype, team_cal_year, team_season, day_played FROM vbsa3364_vbsa2.Team_entries where team_season = '$season' and team_cal_year = $year and day_played = '$dayplayed' and team_id >= (SELECT FLOOR(RAND() * (SELECT MAX(team_id) FROM vbsa3364_vbsa2.Team_entries))) order by team_id";
$sql_teams = "Select team_name, team_grade, team_club, team_club_id, comptype, team_cal_year, team_season, day_played FROM vbsa3364_vbsa2.Team_entries where team_season = '$season' and team_cal_year = $year and day_played = '$dayplayed' order by RAND()";
echo($sql_teams . "<br>");
$result_teams = mysqli_query($connvbsa, $sql_teams) or die(mysqli_error($connvbsa));
while($row = $result_teams->fetch_assoc())
{
    $teams[$row['team_name']] = [
            'division' => $row['team_grade'],
            'venue' => $row['team_club'],
            'venue_id' => $row['team_club_id'],
            'type' => $row['comptype'],
            'dayplayed' => $row['day_played'],
            'year' => $row['team_cal_year'],
            'season' => $row['team_season'],
        ];
}

$sql_venue = "Select team_club FROM vbsa3364_vbsa2.Team_entries where team_season = '$season' and team_cal_year = $year and day_played = '$dayplayed' order by team_grade";
$result_venue = mysqli_query($connvbsa, $sql_venue) or die(mysqli_error($connvbsa));
while($row = $result_venue->fetch_assoc())
{
    $venues[] = $row['team_club'];
}

$sql_tables = "Select distinct team_club, ClubTables FROM vbsa3364_vbsa2.Team_entries left Join clubs on ClubNumber = team_club_id where team_season = '$season' and team_cal_year = $year and day_played = '$dayplayed' order by team_grade";
$result_tables = mysqli_query($connvbsa, $sql_tables) or die(mysqli_error($connvbsa));
while($row = $result_tables->fetch_assoc())
{
    $tables[$row['team_club']] = (int)$row['ClubTables'];
}

$sql_grades = "Select distinct team_grade FROM vbsa3364_vbsa2.Team_entries where team_season = '$season' and team_cal_year = $year and day_played = '$dayplayed'";
$result_grades = mysqli_query($connvbsa, $sql_grades) or die(mysqli_error($connvbsa));
while($row = $result_grades->fetch_assoc())
{
    $grades[] = $row['team_grade'];
}

$sql_start = "Select grade_start_date FROM vbsa3364_vbsa2.Team_grade where season = '$season' and fix_cal_year = $year and dayplayed = '$dayplayed' Order By grade_start_date LIMIT 1";
$result_start = mysqli_query($connvbsa, $sql_start) or die(mysqli_error($connvbsa));
$row = $result_start->fetch_assoc();
$startDate = $row['grade_start_date'];

class BilliardsFixture {
    private $teams;
    private $venues;
    private $tables;
    private $grades;
    private $fixtures;
    private $startDate;

    public function __construct($teams, $venues, $tables, $grades, $startDaten) {
        $this->teams = $teams;
        $this->venues = $venues;
        $this->tables = $tables;
        $this->grades = $grades;
        $this->fixtures = [];
        $this->startDate = new DateTime($startDate);
    }

    // Generate double round-robin fixtures for each division
    private function generateDoubleRoundRobin($teams) {
        if (count($teams) % 2 !== 0) {
            $teams[] = "Bye"; // Add a bye if odd number of teams
        }
        $schedule = [];
        $scheduleAll = [];
        $numTeams = count($teams);
        //echo($numTeams . "<br>");
        $numRounds = $numTeams - 1;
        
        if($numTeams == 6)
        {
            for ($round = 0; $round < $numRounds; $round++) {
                $matches = [];
                for ($i = 0; $i < $numTeams / 2; $i++) {
                    $home = $teams[$i];
                    $away = $teams[$numTeams - 1 - $i];
                    $matches[] = ["home" => $home, "away" => $away, "home_venue" => $this->teams[$home]["venue"], "away_venue" => $this->teams[$away]["venue"], "home_venue_id" => $this->teams[$home]["venue_id"], "away_venue_id" => $this->teams[$away]["venue_id"], "date" => null, "type" => $this->teams[$home]["type"], "year" => $this->teams[$home]["year"], "season" => $this->teams[$home]["season"], "dayplayed" => $this->teams[$home]["dayplayed"]];
                }
                $schedule[$round + 1] = $matches;
                array_splice($teams, 1, 0, array_pop($teams)); // Rotate teams
            }
            
            // Second half - reverse fixtures
            $secondHalf = [];
            foreach ($schedule as $round => $matches) {
                $reversedMatches = [];
                foreach ($matches as $match) {
                    $reversedMatches[] = ["home" => $match["away"], "away" => $match["home"], "away_venue" => $this->teams[$match["away"]]["venue"], "home_venue" => $this->teams[$match["home"]]["venue"],"away_venue_id" => $this->teams[$match["away"]]["venue_id"], "home_venue_id" => $this->teams[$match["home"]]["venue_id"], "date" => null, "type" => $this->teams[$home]["type"], "year" => $this->teams[$home]["year"], "season" => $this->teams[$home]["season"], "dayplayed" => $this->teams[$home]["dayplayed"]];
                }
                $secondHalf[$round + $numRounds] = $reversedMatches;
            }

            // Third half - reverse fixtures
            $thirdHalf = [];
            foreach ($schedule as $round => $matches) {
                $reversedMatches = [];
                foreach ($matches as $match) {
                    $reversedMatches[] = ["home" => $match["away"], "away" => $match["home"], "away_venue" => $this->teams[$match["away"]]["venue"], "home_venue" => $this->teams[$match["home"]]["venue"],"away_venue_id" => $this->teams[$match["away"]]["venue_id"], "home_venue_id" => $this->teams[$match["home"]]["venue_id"], "date" => null, "type" => $this->teams[$home]["type"], "year" => $this->teams[$home]["year"], "season" => $this->teams[$home]["season"], "dayplayed" => $this->teams[$home]["dayplayed"]];
                }
                $thirdHalf[$round + $numRounds] = $reversedMatches;
            }
            $scheduleAll = array_merge($schedule, $secondHalf, $thirdHalf);
            //return array_merge($schedule, $secondHalf, $thirdHalf);
        }

        if(($numTeams == 8) || ($numTeams == 10))
        {
            //echo("Here");
            for ($round = 0; $round < $numRounds; $round++) {
                $matches = [];
                for ($i = 0; $i < $numTeams / 2; $i++) {
                    $home = $teams[$i];
                    $away = $teams[$numTeams - 1 - $i];
                    $matches[] = ["home" => $home, "away" => $away, "home_venue" => $this->teams[$home]["venue"], "away_venue" => $this->teams[$away]["venue"], "home_venue_id" => $this->teams[$home]["venue_id"], "away_venue_id" => $this->teams[$away]["venue_id"], "date" => null, "type" => $this->teams[$home]["type"], "year" => $this->teams[$home]["year"], "season" => $this->teams[$home]["season"], "dayplayed" => $this->teams[$home]["dayplayed"]];
                }
                $schedule[$round + 1] = $matches;
                array_splice($teams, 1, 0, array_pop($teams)); // Rotate teams
            }
            
            // Second half - reverse fixtures
            $secondHalf = [];
            foreach ($schedule as $round => $matches) {
                $reversedMatches = [];
                foreach ($matches as $match) {
                    $reversedMatches[] = ["home" => $match["away"], "away" => $match["home"], "away_venue" => $this->teams[$match["away"]]["venue"], "home_venue" => $this->teams[$match["home"]]["venue"],"away_venue_id" => $this->teams[$match["away"]]["venue_id"], "home_venue_id" => $this->teams[$match["home"]]["venue_id"], "date" => null, "type" => $this->teams[$home]["type"], "year" => $this->teams[$home]["year"], "season" => $this->teams[$home]["season"], "dayplayed" => $this->teams[$home]["dayplayed"]];
                }
                $secondHalf[$round + $numRounds] = $reversedMatches;
            }

            $scheduleAll = array_merge($schedule, $secondHalf);
            //return array_merge($schedule, $secondHalf, $thirdHalf);
        }


        if($numTeams == 12)
        {
            for ($round = 0; $round < $numRounds; $round++) {
                $matches = [];
                for ($i = 0; $i < $numTeams / 2; $i++) {
                    $home = $teams[$i];
                    $away = $teams[$numTeams - 1 - $i];
                    $matches[] = ["home" => $home, "away" => $away, "home_venue" => $this->teams[$home]["venue"], "away_venue" => $this->teams[$away]["venue"], "home_venue_id" => $this->teams[$home]["venue_id"], "away_venue_id" => $this->teams[$away]["venue_id"], "date" => null, "type" => $this->teams[$home]["type"], "year" => $this->teams[$home]["year"], "season" => $this->teams[$home]["season"], "dayplayed" => $this->teams[$home]["dayplayed"]];
                }
                $schedule[$round + 1] = $matches;
                array_splice($teams, 1, 0, array_pop($teams)); // Rotate teams
            }
            
            // Second half - reverse fixtures
            $secondHalf = [];
            foreach ($schedule as $round => $matches) {
                $reversedMatches = [];
                foreach ($matches as $match) {
                    $reversedMatches[] = ["home" => $match["away"], "away" => $match["home"], "away_venue" => $this->teams[$match["away"]]["venue"], "home_venue" => $this->teams[$match["home"]]["venue"],"away_venue_id" => $this->teams[$match["away"]]["venue_id"], "home_venue_id" => $this->teams[$match["home"]]["venue_id"], "date" => null, "type" => $this->teams[$home]["type"], "year" => $this->teams[$home]["year"], "season" => $this->teams[$home]["season"], "dayplayed" => $this->teams[$home]["dayplayed"]];
                }
                $secondHalf[$round + $numRounds] = $reversedMatches;
            }

            $scheduleAll = array_merge($schedule, $secondHalf);
            //return array_merge($schedule, $secondHalf, $thirdHalf);
        }

        if($numTeams == 14)
        {
            for ($round = 0; $round < $numRounds; $round++) {
                $matches = [];
                for ($i = 0; $i < $numTeams / 2; $i++) {
                    $home = $teams[$i];
                    $away = $teams[$numTeams - 1 - $i];
                    $matches[] = ["home" => $home, "away" => $away, "home_venue" => $this->teams[$home]["venue"], "away_venue" => $this->teams[$away]["venue"], "home_venue_id" => $this->teams[$home]["venue_id"], "away_venue_id" => $this->teams[$away]["venue_id"], "date" => null, "type" => $this->teams[$home]["type"], "year" => $this->teams[$home]["year"], "season" => $this->teams[$home]["season"], "dayplayed" => $this->teams[$home]["dayplayed"]];
                }
                $schedule[$round + 1] = $matches;
                array_splice($teams, 1, 0, array_pop($teams)); // Rotate teams
            }
            
            // Second half - reverse fixtures
            $secondHalf = [];
            foreach ($schedule as $round => $matches) {
                $reversedMatches = [];
                foreach ($matches as $match) {
                    $reversedMatches[] = ["home" => $match["away"], "away" => $match["home"], "away_venue" => $this->teams[$match["away"]]["venue"], "home_venue" => $this->teams[$match["home"]]["venue"],"away_venue_id" => $this->teams[$match["away"]]["venue_id"], "home_venue_id" => $this->teams[$match["home"]]["venue_id"], "date" => null, "type" => $this->teams[$home]["type"], "year" => $this->teams[$home]["year"], "season" => $this->teams[$home]["season"], "dayplayed" => $this->teams[$home]["dayplayed"]];
                }
                $secondHalf[$round + $numRounds] = $reversedMatches;
            }

            $scheduleAll = array_merge($schedule, $secondHalf);
            //return array_merge($schedule, $secondHalf, $thirdHalf);
        }

        return $scheduleAll;
    }
    

    // Generate the full fixture
    public function createFixture() {
        foreach ($this->grades as $division) {
            $teams = array_keys(array_filter($this->teams, fn($t) => $t["division"] === $division));
            $this->fixtures[$division] = $this->generateDoubleRoundRobin($teams);
        }
        $this->assignMatchDates();
        $this->adjustForVenueCapacity();
        $this->ensureOneMatchPerDay();
    }

    // Assign match dates, ensuring all divisions start on the same date
    private function assignMatchDates() {
        $currentDate = clone $this->startDate;
        $maxRounds = max(array_map('count', $this->fixtures));        
        for ($round = 0; $round <= $maxRounds; $round++) {
            foreach ($this->grades as $division) {
                if (isset($this->fixtures[$division][$round])) {
                    foreach ($this->fixtures[$division][$round] as &$match) {
                        $match['date'] = $currentDate->format('Y-m-d');
                    }
                }
            }
            $currentDate->modify('+7 days'); // Increment by a week for each round
        }
    }

    private function adjustForVenueCapacity() {
        $rescheduledMatches = [];

        foreach ($this->fixtures as $division => &$rounds) {
            foreach ($rounds as $round => &$matches) {
                $venueMatches = [];
                $newRoundMatches = [];

                //echo("<pre>");
                //echo(var_dump($matches));
                //echo("</pre>");

                foreach ($matches as $match) {
                    // If it's a BYE match, skip venue restrictions
                    if ($match["away"] === "Bye") {
                        $venueMatches["Bye"][] = $match;
                        continue;
                    }

                    $venue = $match["home_venue"];
                    //echo("Venue " . ($match["home_venue"]) . "<br>");

                    // Initialize venue tracking
                    if (!isset($venueMatches[$venue])) {
                        $venueMatches[$venue] = [];
                    }

                    // Check if venue has capacity
                    //echo("Count " . (count($venueMatches[$venue])) . "<br>");
                    //echo("Tables " . $this->tables[$venue] . "<br>");
                    if (count($venueMatches[$venue]) < $this->tables[$venue]) {
                        $venueMatches[$venue][] = $match;
                        //echo("Match " . ($match) . "<br>");
                    } else {
                        // Venue full, reschedule match
                        //echo("Count " . (count($venueMatches[$venue])) . "<br>");
                        //echo("Tables " . $this->tables[$venue] . "<br>");
                        $rescheduledMatches[$division][] = $match;
                        //echo("Re Match " . ($match) . "<br>");
                    }
                    //echo("Match " . ($match) . "<br>");
                }

                // Flatten matches back into the round (including BYEs)
                $matches = array_merge(...array_values($venueMatches));
            }
        }

        // Reschedule matches that exceeded venue capacity
        foreach ($rescheduledMatches as $division => $matches) {
            foreach ($matches as $match) {
                $this->rescheduleMatch($division, $match);
            }
        }

        // Balance home and away matches (excluding BYEs)
        $this->balanceHomeAway();
    }

    private function rescheduleMatch($division, $match) {
        // Iterate through future rounds within the same division
        foreach ($this->fixtures[$division] as &$roundMatches) {
            //$venue = $match["home_venue"];
            $venue = $this->teams[$match["home"]]["venue"];

            // Count how many matches are already at this venue
            $venueCount = array_count_values(array_column($roundMatches, "home_venue"))[$venue] ?? 0;

            // If there's space at the venue, move the match here
            if ($venueCount < $this->tables[$venue]) {
                $roundMatches[] = $match;
                return;
            }
        }

        // If no space, create a new round in the same division
        $newRound = count($this->fixtures[$division]) + 1;
        $this->fixtures[$division][$newRound] = [$match];
    }

    private function balanceHomeAway() {
        $homeCounts = [];
        $totalTeams = [];

        // Count home games per team (excluding BYEs)
        foreach ($this->fixtures as $division => &$rounds) {
            foreach ($rounds as &$matches) {
                foreach ($matches as &$match) {
                    if ($match["away"] === "Bye") {
                        continue; // Ignore BYEs
                    }

                    $homeTeam = $match["home"];
                    $awayTeam = $match["away"];

                    // Track all teams
                    $totalTeams[$homeTeam] = true;
                    $totalTeams[$awayTeam] = true;

                    // Initialize home game count
                    if (!isset($homeCounts[$homeTeam])) {
                        $homeCounts[$homeTeam] = 0;
                    }
                    $homeCounts[$homeTeam]++;
                }
            }
        }

        // Calculate the ideal number of home games per team
        $totalTeams = array_keys($totalTeams);
        $totalMatches = array_sum($homeCounts);
        $idealHomeGames = intval($totalMatches / count($totalTeams)); // Equal home games for all

        // Adjust home/away matches to balance home games
        foreach ($this->fixtures as $division => &$rounds) {
            foreach ($rounds as &$matches) {
                foreach ($matches as &$match) {
                    if ($match["away"] === "Bye") {
                        continue; // Skip BYE matches
                    }

                    $homeTeam = $match["home"];
                    $awayTeam = $match["away"];

                    // If home team has too many home games, swap home/away teams
                    if ($homeCounts[$homeTeam] > $idealHomeGames && $homeCounts[$awayTeam] < $idealHomeGames) {
                        $match["home"] = $awayTeam;
                        $match["away"] = $homeTeam;

                        // Update home game counts
                        $homeCounts[$homeTeam]--;
                        $homeCounts[$awayTeam]++;
                    }
                }
            }
        }
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

    // Display the final schedule
    public function displayFixture() {
        echo("<center><h1>ChatGPT Generated Fixture</h1></center>");
        echo('<center><select name="dayplayed" id="dayplayed" onchange="DayPlayed()"></center>');
        echo('<option value="" selected>Select Match Day</option>');
        echo('<option value="Mon">Monday</option>');
        echo('<option value="Wed">Wednesday</option>');
        echo('</select>');
        //echo("<pre>");
        //echo(var_dump($this->fixtures));
        //echo("</pre>");
        echo("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px' align='center'>");
        foreach ($this->fixtures as $division => $rounds) {
            echo("<tr><td colspan=5 >&nbsp;</td></tr>");
            echo("<tr><td colspan=5 align='center'><h2>" . $division . "</h2></td></tr>");
            //$round_number = 1;
            foreach ($rounds as $round => $matches) {
                echo("<tr><td colspan=5>&nbsp;</td></tr>");
                echo("<tr><td colspan=5 align='center'><b>Round " . ($round + 1)  . "</b></td></tr>");
                echo("<tr><td align='right'><b>Date</b></td>");
                echo("<td colspan=4 class='text-left'><input type='text' style='width:100px' value=" . $matches[0]['date'] . "></td></tr>");
                $k = 0;

                echo("<pre>");
                echo(var_dump($matches));
                echo("</pre>");


                foreach ($matches as $match) {
                    if ($match['away'] === "Bye") {
                        echo("<tr>");
                        echo("<td align='center'><input class='float-child' type='text' style='width:200px' value='" . $match['home'] . "'></td>");
                        echo("<td align='center'>v</td>");
                        echo("<td align='center'><input class='float-child' type='text' style='width:200px' value='Bye'></td>");
                        echo("<td align='center'> at </td>");
                        echo("<td align='left'>Not Available</td>");
                        echo("</tr>");
                    } else {
                        echo("<tr>");
                        echo("<td align='center'><input class='float-child' type='text' style='width:200px' value='" . $match['home'] . "'></td>");
                        echo("<td align='center'>v</td>");
                        echo("<td align='center'><input class='float-child' type='text' style='width:200px' value='" . $match['away'] . "'></td>");
                        echo("<td align='center'> at </td>");
                        echo("<td align='left'>" . $match['home_venue'] . "</td>");
                        echo("</tr>");
                    }
                    if($k == 0)
                    {
                        $sql_insert = "Insert into tbl_create_fixtures (
                        date, 
                        type, 
                        grade, 
                        round, 
                        fix" . ($k+1) . "home, 
                        fix" . ($k+1) . "away, 
                        year, 
                        season, 
                        team_grade, 
                        dayplayed,
                        fix" . ($k+1) . "home_club, 
                        fix" . ($k+1) . "away_club) 
                        Values ('" . 
                        $matches[0]['date'] . "', '" . 
                        $match['type'] . "', '" . 
                        $division[0] . "', " . 
                        ($round + 1) . ", '" . 
                        $match['home'] . "', '" . 
                        $match['away'] . "', " . 
                        $match['year'] . ", '" . 
                        $match['season'] .  "', '" . 
                        $division . "', '" . 
                        $match['dayplayed'] . "', '" . 
                        $match['home_venue_id'] . "', '" . 
                        $match['away_venue_id'] . "')"; 
                        //echo("1 " . $sql_insert . "<br>");
                        $update = mysqli_query($connvbsa, $sql_insert) or die(mysqli_error($connvbsa));
                    }
                    elseif($k == 1)
                    {
                        $sql_update_1 = "Update tbl_create_fixtures Set 
                        fix" . ($k+1) . "home = '" . $match['home'] . "', " . "
                        fix" . ($k+1) . "away = '" . $match['away'] . "', " . "
                        fix" . ($k+1) . "home_club = '" . $match['home_venue_id'] . "', " . "
                        fix" . ($k+1) . "away_club = '" . $match['away_venue_id'] . "'" . "
                        where round = " . ($round + 1) . " and year = " . $match['year'] . " and team_grade = '" . $division . "' and dayplayed = '" . $match['dayplayed'] . "' and season = '" . $match['season'] . "'";
                        //echo("2 " . $sql_update_1 . "<br>");
                        $update = mysqli_query($connvbsa, $sql_update_1) or die(mysqli_error($connvbsa));
                    }
                    elseif($k == 2)
                    {
                        $sql_update_2 = "Update tbl_create_fixtures Set 
                        fix" . ($k+1) . "home = '" . $match['home'] . "', " . "
                        fix" . ($k+1) . "away = '" . $match['away'] . "', " . "
                        fix" . ($k+1) . "home_club = '" . $match['home_venue_id'] . "', " . "
                        fix" . ($k+1) . "away_club = '" . $match['away_venue_id'] . "'" . "
                        where round = " . ($round + 1) . " and year = " . $match['year'] . " and team_grade = '" . $division . "' and dayplayed = '" . $match['dayplayed'] . "' and season = '" . $match['season'] . "'";
                        //echo("2 " . $sql_update_2 . "<br>");
                        $update = mysqli_query($connvbsa, $sql_update_2) or die(mysqli_error($connvbsa));
                    }
                    elseif($k == 3)
                    {
                        $sql_update_3 = "Update tbl_create_fixtures Set 
                        fix" . ($k+1) . "home = '" . $match['home'] . "', " . "
                        fix" . ($k+1) . "away = '" . $match['away'] . "', " . "
                        fix" . ($k+1) . "home_club = '" . $match['home_venue_id'] . "', " . "
                        fix" . ($k+1) . "away_club = '" . $match['away_venue_id'] . "'" . "
                        where round = " . ($round + 1) . " and year = " . $match['year'] . " and team_grade = '" . $division . "' and dayplayed = '" . $match['dayplayed'] . "' and season = '" . $match['season'] . "'";
                        //echo("3 " . $sql_update_3 . "<br>");
                        $update = mysqli_query($connvbsa, $sql_update_3) or die(mysqli_error($connvbsa));
                    }
                    elseif($k == 4)
                    {
                        $sql_update_4 = "Update tbl_create_fixtures Set 
                        fix" . ($k+1) . "home = '" . $match['home'] . "', " . "
                        fix" . ($k+1) . "away = '" . $match['away'] . "', " . "
                        fix" . ($k+1) . "home_club = '" . $match['home_venue_id'] . "', " . "
                        fix" . ($k+1) . "away_club = '" . $match['away_venue_id'] . "'" . "
                        where round = " . ($round + 1) . " and year = " . $match['year'] . " and team_grade = '" . $division . "' and dayplayed = '" . $match['dayplayed'] . "' and season = '" . $match['season'] . "'";
                        //echo("4 " . $sql_update_4 . "<br>");
                        $update = mysqli_query($connvbsa, $sql_update_4) or die(mysqli_error($connvbsa));
                    }
                    elseif($k == 5)
                    {
                        $sql_update_5 = "Update tbl_create_fixtures Set 
                        fix" . ($k+1) . "home = '" . $match['home'] . "', " . "
                        fix" . ($k+1) . "away = '" . $match['away'] . "', " . "
                        fix" . ($k+1) . "home_club = '" . $match['home_venue_id'] . "', " . "
                        fix" . ($k+1) . "away_club = '" . $match['away_venue_id'] . "'" . "
                        where round = " . ($round + 1) . " and year = " . $match['year'] . " and team_grade = '" . $division . "' and dayplayed = '" . $match['dayplayed'] . "' and season = '" . $match['season'] . "'";
                        //echo("5 " . $sql_update_5 . "<br>");
                        $update = mysqli_query($connvbsa, $sql_update_5) or die(mysqli_error($connvbsa));
                    }
                    elseif($k == 6)
                    {
                        $sql_update_6 = "Update tbl_create_fixtures Set 
                        fix" . ($k+1) . "home = '" . $match['home'] . "', " . "
                        fix" . ($k+1) . "away = '" . $match['away'] . "', " . "
                        fix" . ($k+1) . "home_club = '" . $match['home_venue_id'] . "', " . "
                        fix" . ($k+1) . "away_club = '" . $match['away_venue_id'] . "'" . "
                        where round = " . ($round + 1) . " and year = " . $match['year'] . " and team_grade = '" . $division . "' and dayplayed = '" . $match['dayplayed'] . "' and season = '" . $match['season'] . "'";
                        //echo("6 " . $sql_update_6 . "<br>");
                        $update = mysqli_query($connvbsa, $sql_update_6) or die(mysqli_error($connvbsa));
                    }
                    $k++;
                    //$round_number++;
                }
            }
            // add finals fixture positions to fixtures table.
            $round_date = $matches[0]['date'];
            for($x = ($round+1); $x < ($round+3); $x++)
            {
                $sql_insert_finals = "Insert into tbl_create_fixtures (
                date, 
                type, 
                grade, 
                round, 
                fix1home, 
                fix1away, 
                fix2home, 
                fix2away, 
                fix3home,
                fix3away,
                fix4home,
                fix4away,
                fix5home,
                fix5away,
                fix6home,
                fix6away,
                fix7home,
                fix7away,
                year, 
                season, 
                team_grade, 
                dayplayed,
                fix1home_club, 
                fix1away_club,
                fix2home_club, 
                fix2away_club) 
                Values ('" . 
                $round_date . "', '" . 
                $match['type'] . "', '" . 
                $division[0] . "', " . 
                ($x+1) . ", 'TBA', 'TBA', 'TBA', 'TBA', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', " . 
                $match['year'] . ", '" . 
                $match['season'] .  "', '" . 
                $division . "', '" . 
                $match['dayplayed'] . "', '', '', '', '')"; 
                //echo($sql_insert_finals . "<br>");
                $update = mysql_query($sql_insert_finals, $connvbsa) or die(mysql_error()); 
                $round_date =  date('Y-m-d', strtotime($round_date . ' + 7 days'));    
            }
        }
        echo("</tbody>");
        echo("</table>");
    }
}

$fixture = new BilliardsFixture($teams, $venues, $tables, $grades, $startDate);
$fixture->createFixture();
$fixture->displayFixture();

?>