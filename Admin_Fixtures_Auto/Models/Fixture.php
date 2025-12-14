<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php
require_once('../Connections/connvbsa.php');

require_once('Models/Club.php');
require_once('Models/Grade.php');
require_once('Models/Team.php');
require_once('Models/Round.php');

mysql_select_db($database_connvbsa, $connvbsa);

class Fixture {
	public $rounds = array();
	public $teams = array();
	public $grades = array();
	public $clubs = array();
	public $matches = array();

	public function __construct()
	{
        //$rounds = [];
        //$teams = [];
        //$grades = [];
        //$clubs = [];
        //$matches = [];
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

    
    public function addMatch(Clash $match)
    {    
    	array_push($this->matches, $match); 
    }

    public function addRound(Round $round)
    {    
    	array_push($this->rounds, $round); 
    }

    public function LoadFixture($year, $season, $dayplayed)
    {
    	global $connvbsa;

    	// Add Clubs

		// Pull Data and Loop over the data and load objects
		$sql_clubs = 'Select ClubTitle, ClubNumber, ClubTables from clubs';
		//echo($sql_clubs . "<br>");
		$result_clubs = mysql_query($sql_clubs, $connvbsa) or die(mysql_error());
		while($grade_clubs = $result_clubs->fetch_assoc())
		{
			if($grade_clubs['ClubTables'] == 0)
			{
				$tables = 0;
			}
			else
			{
				$tables = $grade_clubs['ClubTables'];
			}
			$club = new Club();
			$club->LoadData($grade_clubs['ClubTitle'], $grade_clubs['ClubNumber'], $tables);
			$this->addClub($club);
		}

		// Add Grades

		$sql_grades = 'Select distinct team_grade, team_id from Team_entries where team_cal_year = ' . $year . ' and day_played = "' . $dayplayed . '" and team_season = "' . $season . '" order by team_grade';
		//echo($sql_grades . "<br>");
		$result_grades = mysql_query($sql_grades, $connvbsa) or die(mysql_error());
		while($grade_data = $result_grades->fetch_assoc())
		{
			$grade = new Grade();
			$grade->LoadData($grade_data['team_grade'], $grade_data['team_id']);
			$this->addGrade($grade);
		}


		// Add Rounds
		function addDays($date, $days, $round) 
		{
		    $result = date('Y-m-d', strtotime($date . ' + ' . ($days*$round) . ' days'));
		    return $result;
		}
		$sql_rounds = 'Select grade_start_date, grade, no_of_rounds from Team_grade where fix_cal_year = ' . $year . ' and dayplayed = "' . $dayplayed . '" and season = "' . $season . '" order by grade, grade_start_date';
		//echo($sql_rounds . "<br>");
		$result_rounds = mysql_query($sql_rounds, $connvbsa) or die(mysql_error());
		/*
		function GetRounds($teams) 
		{
		    // get number of rounds per number of teams
		    switch ($teams) {
		        case 4:
		            $rounds = 15;
		            break;
		        case 6:
		            $rounds = 15;
		            break;
		        case 8:
		            $rounds = 14;
		            break;
		        case 10:
		            $rounds = 18;
		            break;
		        case 12:
		            $rounds = 16;
		            break;
		        case 14:
		            $rounds = 13;
		            break;
		        default:
		            $rounds = 18;
		    }
		    return $rounds;
		}
		*/
		while($grade_rounds = $result_rounds->fetch_assoc())
		{
			for($i = 0; $i < $grade_rounds['no_of_rounds']; $i++)
			{
				$round = new Round();
				$fix_date = addDays($grade_rounds['grade_start_date'], 7, ($i+1));
				$round->LoadData(($i+1), $fix_date, $grade_rounds['grade']);
				$this->addRound($round);
			}
		}

		// Add Teams

		$sql_team = 'Select * from Team_entries where team_cal_year = ' . $year . ' and day_played = "' . $dayplayed . '" and team_season = "' . $season . '"  and team_name != "Bye" Order By RAND()';
    	//echo($sql_team . "<br>");
    	$result_team = mysql_query($sql_team, $connvbsa) or die(mysql_error());
		while($team_data = $result_team->fetch_assoc())
		{
		  $team = new Team();
		  $team->LoadData($team_data['team_name'], $team_data['team_id'], $team_data['team_grade'], $team_data['team_club']);
		  $this->addTeam($team);
		}
/*
		// Add Matches

		for ($x = 0; $x <= 10; $x++)
		{
		  $match = new Clash();
		  $home = "Team Home";
		  $away = "Team Away";
		  $grade = "BVS1";
		  $club = "Burwood";
		  $round = $x;
		  $match->LoadData($home, $away, $grade, $club, $round);
		  $this->addMatch($match);
		}
*/
	}
}
?>
<div id='output'></div>


