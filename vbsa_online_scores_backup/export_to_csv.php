<?php 

include('connection.inc');

$season = $_GET['season'];
$team_grade = $_GET['grade'];
$team = $_GET['team'];
$player = $_GET['player'];
$sort = $_GET['sort'];
$sort_order = $_GET['sort_order'];
$season_data = explode(",", $season);
$current_year = $season_data[0];
$current_season = $season_data[1];

$sql_part = " and scrs.team_grade = '" . trim($team_grade) . "' and members.MemberID = " . $player;

if(($team_grade == 'all') || ($team_grade == ''))
{
  $sql_part = " ";
}
elseif(($team == 'all') || ($team == ''))
{
  $sql_part = " and scrs.team_id = '" . $team_id . "' ";
}
elseif(($player == 'all') || ($player == ''))
{
  $sql_part = " and scrs.team_grade = '" . trim($team_grade) . "' and scrs.team_id = '" . $team_id . "' and members.MemberID = " . $player . " ";
}
if($sort != '')
{
  $sql_sort = " order by " . $sort . " " . $sort_order;
}
else
{
  $sql_sort = "";
}
$sql = "Select members.MemberID, members.FirstName, members.LastName, team_grade, current_year_scrs, scr_season, allocated_rp, game_type, count_played, avail_pts, pts_won, percent_won, average_position FROM scrs LEFT JOIN members on members.MemberID = scrs.MemberID where scr_season = '". trim($current_season) . "' and current_year_scrs = " . $current_year . " and members.MemberID != 1 " . " " . $sql_part . $sql_sort;

//echo($sql . "<br>");
$result = $dbcnx_client->query($sql);
if (!$result) die('Couldn\'t fetch records');
$headers = $result->fetch_fields();
foreach($headers as $header) {
    $head[] = $header->name;
}

$fp = fopen('php://output', 'w');
if ($fp && $result) 
{
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="export.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    fputcsv($fp, array_values($head)); 
    while ($row = $result->fetch_array(MYSQLI_NUM)) 
    {
        fputcsv($fp, array_values($row));
    }
    fclose( $fp );
}

