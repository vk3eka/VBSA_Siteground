<?php
include("../vbsa_online_scores/connection.inc");

if (isset($_GET['id'])) {
    $tourn_id = $_GET['id'];
} else {
    exit("Error!");
}

if (isset($_GET['tourn_type'])) {
    $tourn_type = $_GET['tourn_type'];
} else {
    exit("Error!");
}

if($tourn_type == 'Snooker')
{
  // Snooker Tournaments
  $sql = "Select ID, tournament_number AS TournID, MemberID, CONCAT(FirstName, ' ', LastName) AS FullName, Email, MobilePhone, amount_entry, how_paid, entry_confirmed, (CASE WHEN memb_by IS NOT NULL then 'Yes' WHEN memb_by IS NULL then 'No' end) AS memb, seed, tourn_date_ent, entered_by, ranknum, wcard, Junior, rank_S_open_tourn.total_tourn_rp as rank_pts FROM tourn_entry, members  LEFT JOIN rank_S_open_tourn ON memb_id=MemberID WHERE tournament_number = '$tourn_id' AND MemberID=tourn_memb_id AND entry_confirmed=1 ORDER BY FirstName, Junior";
}
elseif($tourn_type == 'Billiards')
{
  // Billiard Tournaments
  $sql = "Select ID, tournament_number AS TournID, MemberID, CONCAT(FirstName, ' ', LastName) AS FullName, Email, MobilePhone, amount_entry, how_paid, entry_confirmed, (CASE WHEN memb_by IS NOT NULL then 'Yes' WHEN memb_by IS NULL then 'No' end) AS memb, seed, tourn_date_ent, entered_by, ranknum, wcard, junior_cat, rank_Billiards.total_rp as rank_pts  FROM tourn_entry, members  LEFT JOIN rank_Billiards ON memb_id=MemberID WHERE tournament_number = '$tourn_id' AND MemberID=tourn_memb_id AND entry_confirmed=1 ORDER BY FirstName, junior_cat";
}

//$sql = 'Select * from tournament_players Where tourn_id = 202462';

$result = $dbcnx_client->query($sql);
if (!$result) die('Couldn\'t fetch records');
$headers = $result->fetch_fields();
foreach($headers as $header) {
    $head[] = $header->name;
}
$fp = fopen('php://output', 'w');
if ($fp && $result) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="export.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
    fputcsv($fp, array_values($head)); 
    while ($row = $result->fetch_array(MYSQLI_NUM)) 
    {
        fputcsv($fp, array_values($row));
    }
    fclose($fp);
}

?>
