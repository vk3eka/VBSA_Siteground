<?php
include("../vbsa_online_scores/connection.inc");

$sql = '';

for($i = 1; $i < 13; $i++)
{
    $sql =  $sql . "Select event_id, startdate, finishdate, closedate, special_dates, visible, event, venue, next_venue, state, aust_rank, ranking_type, tourn_director, referee_early, referee_later, current_trophy_numbers, current_trophy_costs, next_trophy_numbers, next_trophy_costs, comments, event_number, attach_name FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE YEAR(startdate)=" . $_POST['CalYear'] . " AND MONTH( startdate ) = " . $i . "  " . $_POST['Filter'] . " GROUP BY calendar.event_id Union All ";
}

$sql = rtrim($sql,'Union All ');

/*

$sql = "Select event_id, event, venue, state, aust_rank, ranking_type, startdate, finishdate, closedate,  visible, event_number, attach_name  FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE YEAR( startdate )=" . $_POST['CalYear'] . " AND MONTH( startdate ) = 1  " . $_POST['Filter'] . " GROUP BY calendar.event_id 
    UNION All
    Select event_id, event, venue, state, aust_rank, ranking_type, startdate, finishdate, closedate,  visible, event_number, attach_name  FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE YEAR( startdate )=" . $_POST['CalYear'] . " AND MONTH( startdate ) = 2  " . $_POST['Filter'] . " GROUP BY calendar.event_id
    UNION All
    Select event_id, event, venue, state, aust_rank, ranking_type, startdate, finishdate, closedate,  visible, event_number, attach_name  FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE YEAR( startdate )=" . $_POST['CalYear'] . " AND MONTH( startdate ) = 3  " . $_POST['Filter'] . " GROUP BY calendar.event_id
    UNION All
    Select event_id, event, venue, state, aust_rank, ranking_type, startdate, finishdate, closedate,  visible, event_number, attach_name  FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE YEAR( startdate )=" . $_POST['CalYear'] . " AND MONTH( startdate ) = 4  " . $_POST['Filter'] . " GROUP BY calendar.event_id
    UNION All
    Select event_id, event, venue, state, aust_rank, ranking_type, startdate, finishdate, closedate,  visible, event_number, attach_name  FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE YEAR( startdate )=" . $_POST['CalYear'] . " AND MONTH( startdate ) = 5  " . $_POST['Filter'] . " GROUP BY calendar.event_id
    UNION All
    Select event_id, event, venue, state, aust_rank, ranking_type, startdate, finishdate, closedate,  visible, event_number, attach_name  FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE YEAR( startdate )=" . $_POST['CalYear'] . " AND MONTH( startdate ) = 6  " . $_POST['Filter'] . " GROUP BY calendar.event_id
    UNION All
    Select event_id, event, venue, state, aust_rank, ranking_type, startdate, finishdate, closedate,  visible, event_number, attach_name  FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE YEAR( startdate )=" . $_POST['CalYear'] . " AND MONTH( startdate ) = 7  " . $_POST['Filter'] . " GROUP BY calendar.event_id
    UNION All
    Select event_id, event, venue, state, aust_rank, ranking_type, startdate, finishdate, closedate,  visible, event_number, attach_name  FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE YEAR( startdate )=" . $_POST['CalYear'] . " AND MONTH( startdate ) = 8  " . $_POST['Filter'] . " GROUP BY calendar.event_id
    UNION All
    Select event_id, event, venue, state, aust_rank, ranking_type, startdate, finishdate, closedate,  visible, event_number, attach_name  FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE YEAR( startdate )=" . $_POST['CalYear'] . " AND MONTH( startdate ) = 9  " . $_POST['Filter'] . " GROUP BY calendar.event_id
    UNION All
    Select event_id, event, venue, state, aust_rank, ranking_type, startdate, finishdate, closedate,  visible, event_number, attach_name  FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE YEAR( startdate )=" . $_POST['CalYear'] . " AND MONTH( startdate ) = 10  " . $_POST['Filter'] . " GROUP BY calendar.event_id
    UNION All
    Select event_id, event, venue, state, aust_rank, ranking_type, startdate, finishdate, closedate,  visible, event_number, attach_name  FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE YEAR( startdate )=" . $_POST['CalYear'] . " AND MONTH( startdate ) = 11  " . $_POST['Filter'] . " GROUP BY calendar.event_id
    UNION All
    Select event_id, event, venue, state, aust_rank, ranking_type, startdate, finishdate, closedate,  visible, event_number, attach_name  FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE YEAR( startdate )=" . $_POST['CalYear'] . " AND MONTH( startdate ) = 12  " . $_POST['Filter'] . " GROUP BY calendar.event_id";
*/

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