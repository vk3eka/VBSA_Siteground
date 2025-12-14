<?php 

include('../connection.inc'); 

$allData = $_GET['allData'];

echo("<pre>");
echo(var_dump($allData));
echo("</pre>");

$i = 0;
$allIndex = json_decode($allData);
//echo("Index " . $allIndex . "<br>");
foreach($allIndex as $index)
{
    //$test_index = explode(', ', $index);
    //echo($test_index[0] . "<br>");
    //echo($allIndex . "<br>");

    $sql = "Update Team_entries SET fix_sort = " . $i . " WHERE team_id = " . $index . " and team_cal_year = 2024 and team_grade = 'APS'";
    echo($sql . "<br>");
    $update = $dbcnx_client->query($sql);
    $i++;
}
