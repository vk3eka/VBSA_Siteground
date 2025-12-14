<?php

include('connection.inc');
include "../admin_scores/save_fixtures_include.php";

function strposX($haystack, $needle, $number) 
{
    if ($number == 1) {
        return strpos($haystack, $needle);
    } elseif ($number > 1) {
        return strpos($haystack, $needle, strposX($haystack, $needle, $number - 1) + strlen($needle));
    } else {
        return error_log('Error: Value for parameter $number is out of range');
    }
}

$season = $_GET['season'];
$year  = $_GET['year'];
$dayplayed = $_GET['dayplayed'];
$form_no = $_GET['form_no'];

$from_id = $_GET['from_id'];
//echo("From " . $from_id . "<br>");
$to_id = $_GET['to_id'];
//echo("To " . $to_id . "<br>");
$from_team = $_GET['from_team'];
$to_team = $_GET['to_team'];

//echo($from_id . "<br>");
$searchItem = "_";
//echo('Position on first - is ' . strpos($from_id, $searchItem) . "<br>");

$first_pos = strposX($from_id, $searchItem, 1);
//echo('Position on first - is ' . $first_pos . "<br>");

$second_pos = strposX($from_id, $searchItem, 2);
//echo('Position on second - is ' . $second_pos . "<br>");

$third_pos = strposX($from_id, $searchItem, 3);
//echo('Position on third - is ' . $third_pos . "<br><br>");


/*
//APS_home_1_3
$team_grade = substr($from_id, 0, 3);
$round_home = substr($from_id, 9, 1);
$home_away_from = substr($from_id, 4, 4);
$fixture_home = substr($from_id, 11, 1);

//APS_away_1_3
$team_grade = substr($to_id, 0, 3);
$round_away = substr($to_id, 9, 1);
$home_away_to = substr($to_id, 4, 4);
$fixture_away = substr($to_id, 11, 1);


//AVS2_home_1_3
$team_grade = substr($from_id, 0, 4);
$round_home = substr($from_id, 10, 1);
$home_away_from = substr($from_id, 5, 4);
$fixture_home = substr($from_id, 12, 1);

//AVS2_away_1_3
$team_grade = substr($to_id, 0, 4);
$round_away = substr($to_id, 10, 1);
$home_away_to = substr($to_id, 5, 4);
$fixture_away = substr($to_id, 12, 1);
*/

//AVS2_home_1_3
$team_grade = substr($from_id, (strpos($from_id, $searchItem)+1), 4);
$round_home = substr($from_id, (strpos($from_id, $searchItem)+11), 1);
$home_away_from = substr($from_id, (strpos($from_id, $searchItem)+6), 4);
$fixture_home = substr($from_id, (strpos($from_id, $searchItem)+13), 1);

//AVS2_away_1_3
//$team_grade = substr($to_id, 0, strpos($to_id, $searchItem));
$round_away = substr($to_id, (strpos($to_id, $searchItem)+6), 1);
$home_away_to = substr($to_id, (strpos($to_id, $searchItem)+6), 4);
$fixture_away = substr($to_id, (strpos($to_id, $searchItem)+13), 1);


// need to check for round 10 and above.......................

//AVS2_home_10_1
//$team_grade = substr($from_id, 0, $first_pos);
//$round_home = substr($from_id, ($first_pos+6), 1);
//$home_away_from = substr($from_id, ($first_pos+1), 4);
//$fixture_home = substr($from_id, ($first_pos+8), 1);


/*
$team_grade = substr($to_id, 0, 4);
$round_away = substr($to_id, 10, 1);
$home_away_to = substr($to_id, 5, 4);
$fixture_away = substr($to_id, 12, 1);
*/


/*
echo("Update the following home field " . "fix" . $fixture_home . "" . $home_away_from . "<br>");

echo("Update the following away field " . "fix" . $fixture_away . "" . $home_away_to . "<br>");

echo("Team Grade " . $team_grade . "<br>");
echo("Round " . $round_home . "<br>");
echo("Home " . $home_away_from . "<br>");
echo("Away " . $home_away_to . "<br>");
echo("Fixture Home " . $fixture_home . "<br>");
echo("Fixture Away " . $fixture_away . "<br>");
echo("Year " . $year . "<br>");
echo("Season " . $season . "<br>");
echo("Day Played " . $dayplayed . "<br>");
echo("From ID " . $from_id . "<br>");
echo("To ID " . $to_id . "<br>");
echo("From Team " . $from_team . "<br>");
echo("To Team " . $to_team . "<br>");
*/

$sql = 'Update tbl_create_fixtures set ' . 'fix' . $fixture_home . '' . $home_away_from . ' = "' . $to_team . '", fix' . $fixture_away . '' . $home_away_to . ' = "' . $from_team . '" Where round = ' . $round_home . ' and team_grade = "' . $team_grade . '" and year = ' . $year . ' and season = "' . $season . '"';
//echo($sql . "<br>");
$update = $dbcnx_client->query($sql);


echo("<script>");
echo("$(document).ready(function(){");
echo("$.fn.save_fixtures(" . $form_no . ", 'Response');");
echo("});");
echo("</script>");

//echo("Changes updated");






?>