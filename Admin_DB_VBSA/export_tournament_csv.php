<?php require_once('../Connections/connvbsa.php');

mysql_select_db($database_connvbsa, $connvbsa);

$sql = "Select *  FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE YEAR( startdate )='2025' GROUP BY calendar.event_id ORDER BY calendar.startdate";
//$sql = "Select * FROM vbsa3364_vbsa2.calendar Left Join tournaments on tournaments.tourn_id = calendar.tourn_id where calendar.tourn_id != ''";
$result = mysql_query($sql, $connvbsa) or die(mysql_error());
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
