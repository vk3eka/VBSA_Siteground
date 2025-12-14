<?php
require_once('../Connections/connvbsa.php'); 

//include "save_fixtures_include.php";

mysql_select_db($database_connvbsa, $connvbsa);

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
$to_id = $_GET['to_id'];
$from_team = $_GET['from_team'];
$to_team = $_GET['to_team'];

$searchItem = "_";
$first_pos = strposX($from_id, $searchItem, 1);
$second_pos = strposX($from_id, $searchItem, 2);
$third_pos = strposX($from_id, $searchItem, 3);
$forth_pos = strposX($from_id, $searchItem, 4);

$team_grade = substr($from_id, ($first_pos+1), ($second_pos-$first_pos-1));
$round_home = substr($from_id, ($third_pos+1), ($forth_pos-$third_pos-1));
$home_away_from = substr($from_id, ($second_pos+1), ($third_pos-$second_pos-1));
$fixture_home = substr($from_id, ($forth_pos+1), strlen($from_id));
$round_away = substr($to_id, ($third_pos+1), ($forth_pos-$third_pos-1));
$home_away_to = substr($to_id, ($second_pos+1), ($third_pos-$second_pos-1));
$fixture_away = substr($to_id, ($forth_pos+1), strlen($to_id));

$sql = 'Update tbl_create_fixtures set ' . 'fix' . $fixture_home . '' . $home_away_from . ' = "' . $to_team . '", fix' . $fixture_away . '' . $home_away_to . ' = "' . $from_team . '" Where round = ' . $round_home . ' and team_grade = "' . $team_grade . '" and year = ' . $year . ' and season = "' . $season . '"';

$update = mysql_query($sql, $connvbsa) or die(mysql_error());
/*
echo("<script>");
echo("$(document).ready(function(){");
echo("$.fn.save_fixtures(" . $form_no . ", 'Response');");
echo("});");
echo("</script>");
*/
echo("Fixtures have been saved.");

?>