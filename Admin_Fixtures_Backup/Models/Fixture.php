<?php
require_once('../Connections/connvbsa.php');
require_once('Models/Club.php');
require_once('Models/Grade.php');
require_once('Models/Team.php');
require_once('Models/Clash.php');
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

    public function LoadFixture()
    {
    	global $connvbsa;

    	// Add Clubs

		// Pull Data and Loop over the data and load objects
		for ($x = 0; $x <= 10; $x++) {
		  $club = new Club();

		  $club->LoadData($x, $x,$x);
		  $this->addClub($club);
		}

		// Add Grades

		$sql_grades = 'Select distinct team_grade from Team_entries where team_cal_year = 2024 and day_played = "Mon" and team_season = "S2" order by team_grade';
		$result_grades = mysql_query($sql_grades, $connvbsa) or die(mysql_error());
		$x = 1;
		while($grade_data = $result_grades->fetch_assoc())
		{
			$grade = new Grade();
			$grade->LoadData($grade_data['team_grade'], $x);
			$this->addGrade($grade);
			$x++;
		}

		// Add Rounds

		for ($x = 1; $x <= 1; $x++) {
		  $round = new Round();
		 
		  $round->LoadData("2024-08-12", $x, "2024-08-12");
		  $this->addRound($round);
		}

		// Add Teams
		for ($x = 0; $x <= 14; $x++) {
		  //$team = new Team();

		  //$team->LoadData($x, $x,$x);
		  //$this->addTeam($team);
		}

		// Add Matches
		for ($x = 0; $x <= 10; $x++) {
		  //$match = new Clash();

		  //$match->LoadData($x, $x,$x);
		  //$this->addMatch($match);
		}
    }
}

?>