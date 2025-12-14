<?php
require_once('../Connections/connvbsa.php');

require_once('Models/Club.php');
require_once('Models/Grade.php');
require_once('Models/Team.php');
require_once('Models/Round.php');
require_once('Models/NonDates.php');
require_once('Models/Holidays.php');

mysql_select_db($database_connvbsa, $connvbsa);

class Fixture {
	public $rounds = array();
	public $teams = array();
	public $grades = array();
	public $clubs = array();
	public $non_dates = array();
	public $holidays = array();

	public function __construct()
	{
        
    }

    public function addClub(Club $club)
    {    
    	array_push($this->clubs, $club); 
    }

    public function addGrade(Grade $grade)
    {    
    	array_push($this->grades, $grade); 
    }

    public function addTeam(Team $team)
    {    
    	array_push($this->teams, $team); 
    }

    public function addRound(Round $round)
    {    
    	array_push($this->rounds, $round); 
    }

    public function addDates(NonDates $date)
    {    
    	array_push($this->non_dates, $date); 
    }

    public function addHolidays(Holidays $date)
    {    
    	array_push($this->holidays, $date); 
    }
    
    public function LoadFixture($year, $season, $dayplayed)
    {
    	global $connvbsa;

    	// Add Clubs
		$sql_clubs = 'Select ClubTitle, ClubNumber, PennantTables from clubs order by ClubNumber desc';
		$result_clubs = mysql_query($sql_clubs, $connvbsa) or die(mysql_error());
		$clubID = '';
		while($grade_clubs = $result_clubs->fetch_assoc())
		{
			if($grade_clubs['ClubTitle'] == 'Bye')
			{
				$clubID = $grade_clubs['ClubNumber'];
			}
			if($grade_clubs['PennantTables'] > 0)
			{
				$tables = $grade_clubs['PennantTables'];
			}
			else
			{
				$tables = 0;
			}
			$club = new Club();
			$club->LoadData(trim($grade_clubs['ClubTitle']), $grade_clubs['ClubNumber'], $tables);
			$this->addClub($club);
		}
		
		// Add Grades
		$sql_grades = 'Select Count(team_grade) as count, team_grade, team_id, comptype from Team_entries where team_cal_year = ' . $year . ' and day_played = "' . $dayplayed . '" and team_season = "' . $season . '" group by team_grade order by team_grade';
		//echo($sql_grades . "<br>");
		$result_grades = mysql_query($sql_grades, $connvbsa) or die(mysql_error());
		while($grade_data = $result_grades->fetch_assoc())
		{
			if(($grade_data['count'] % 2) == 1)
			{
				$sql_insert_bye = "Insert INTO Team_entries (team_club_id, team_name, team_club, team_grade, team_season, day_played, players, Final5, include_draw, audited, team_cal_year, comptype, entered_by) VALUES (" . $clubID . ", 'Bye', 'Bye', '" . $grade_data['team_grade'] . "', '" . $season . "', '" . $dayplayed . "', 4, 4, 'Yes', 'No', " . $year . ", '" . $grade_data['comptype'] . "', 'Fixturing')";
				$result_insert_bye = mysql_query($sql_insert_bye, $connvbsa) or die(mysql_error());
			}
			$grade = new Grade();
			$grade->LoadData($grade_data['team_grade'], $grade_data['team_id'], $grade_data['count']);
			$this->addGrade($grade);
		}
	
		// Add Rounds
		// get array of non available dates.
		$date_array = array();
		$sql_playing_dates = 'Select * from tbl_ics_dates where Year(DTSTART) = Year(CURDATE()) and ok_to_use = 0 Order by DTSTART';
		$result_playing_dates = mysql_query($sql_playing_dates, $connvbsa) or die(mysql_error());
		$i = 0;
		while($build_date = $result_playing_dates->fetch_assoc())
		{
		    $date_array[$i] = $build_date['DTSTART'];
		    $i++;
		}

		function addDays($date, $days, $round) 
		{
		    $result = date('Y-m-d', strtotime($date . ' + ' . ($days*$round) . ' days'));
		    return $result;
		}
		
		$sql_rounds = 'Select grade_start_date, grade, no_of_rounds, type, finals_teams from Team_grade where fix_cal_year = ' . $year . ' and dayplayed = "' . $dayplayed . '" and season = "' . $season . '" order by grade, grade_start_date';
		//echo($sql_rounds . "<br>");
		$result_rounds = mysql_query($sql_rounds, $connvbsa) or die(mysql_error());

		$daysBetweenRounds = 7;

		while($grade_rounds = $result_rounds->fetch_assoc())
		{
			$start_date = $grade_rounds['grade_start_date'];
			$normal_rounds = $grade_rounds['no_of_rounds'] - ($grade_rounds['finals_teams']/2);
			$round_index = 0;
			$valid_rounds = 0;
			while ($valid_rounds < $normal_rounds) 
			{
				$fix_date = addDays($start_date, $daysBetweenRounds, $round_index);
		        // Skip dates in holiday array
		        if (in_array($fix_date, $date_array)) {
		            // Advance one week if it's a holiday
		            $holiday = new Holidays();
			        $holiday->LoadData($fix_date);
			        $this->addHolidays($holiday);
		            $round_index++;
		            continue;
		        }
		        // Add round with a valid date
		        $round = new Round();
		        $round->LoadData(($valid_rounds + 1), $fix_date, $grade_rounds['grade'], $grade_rounds['type']);
		        $this->addRound($round);

		        $round_index++;
		        $valid_rounds++;
		    }
		}
		/*
		echo("<pre>");
		echo(var_dump($round));
		echo("</pre>");

		echo("<pre>");
		echo(var_dump($holiday));
		echo("</pre>");
*/		

		// Add Teams
		$sql_team = 'Select * from Team_entries where team_cal_year = ' . $year . ' and day_played = "' . $dayplayed . '" and team_season = "' . $season . '" Order By RAND()';
    	$result_team = mysql_query($sql_team, $connvbsa) or die(mysql_error());
    	$max_count = $result_team->num_rows;
		while($team_data = $result_team->fetch_assoc())
		{
			if($team_data['team_name'] != 'Bye')
			{
				$team_club = $team_data['team_club'];
			}
			else
			{
				$team_club = "Bye";
			}
			$team = new Team();
			$team->LoadData(trim($team_data['team_name']), $team_data['team_id'], $team_data['team_grade'], trim($team_data['team_club']), $team_data['comptype'], $team_data['day_played'], $max_count);
			$this->addTeam($team);
		}

		//echo("<pre>");
		//echo(var_dump($team));
		//echo("</pre>");

		// Add Non Dates
		// get unavailable dates
		$sql_dates = 'Select * from tbl_ics_dates where Year(DTSTART) >= YEAR(CURDATE() - 1) order by DTSTART';
		$result_dates = mysql_query($sql_dates, $connvbsa) or die(mysql_error());
		while($row_dates = $result_dates->fetch_assoc()) 
		{
			$date = new NonDates();
		    $date->LoadData($row_dates['DTSTART'], $row_dates['SUMMARY']);
		    $this->addDates($date);
		}
		//echo("<pre>");
		//echo(var_dump($date));
		//echo("</pre>");

	}
}
?>

