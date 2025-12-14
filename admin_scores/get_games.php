<?php

$team_grade = $_POST['TeamGrades'];
$round = $_POST['Round'];
$season = $_POST['Season'];
$year = $_POST['Year'];
$dayplayed = $_POST['DayPlayed'];
$form_no = $_POST["FormNo"];

$team_grade = explode(", ", $team_grades);
$form = 0;
$round = 0;
echo("var fixArray_grades = [];");
foreach($team_grade as $grade)
{
    echo("var fixArray = '';");
    echo("var fixArray_home_games = '';");
    if($grade != '')
    {
        $form++;
        $sql = "Select team_name FROM Team_entries WHERE team_season ='$season' AND team_cal_year = $year AND team_grade = '$grade' AND day_played = '$dayplayed' ORDER BY team_name";
        $result_home_games = mysql_query($sql, $connvbsa) or die(mysql_error());
        $teams = $result_home_games->num_rows;
        //$rounds = (($teams*2)-2);
        $fixtures = ($teams/2);
        //for($i = 0; $i < $rounds; $i++)
        //{
            for($j = 0; $j < ($fixtures); $j++)
            {
                echo("fixArray_home_games = document.getElementById('" . $grade . "_home_" . ($i+1) . "_" . ($j+1) . "').value;");
                echo("fixArray = fixArray + ', ' + fixArray_home_games;");
                echo("console.log('" . $grade . "_home_" . ($i+1) . "_" . ($j+1) . "');");
            }
        //}
    }
    echo("fixArray_grades = fixArray_grades + ', ' + fixArray;");
}
//echo("console.log('Test Array (Home " . $round . ") ' + fixArray_grades);");
echo("return fixArray_grades;");

?>
