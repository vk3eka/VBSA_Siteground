<?php 
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

include "save_fixtures_include.php";

$allData = $_GET['allData'];
$year = $_GET['year'];
$season = $_GET['season'];
$form_no = $_GET['formno'];

// delete existing fixtures
//$sql_delete = "Delete FROM tbl_create_fixtures where year = " . $year . " and season = '" . $season . "' and team_grade = '". $team_grade . "'";
//$result_delete = mysql_query($sql_delete, $connvbsa) or die(mysql_error());
if($allData != '')
{
    $i = 0;
    $splitData = explode(",", $allData);
    for($b = 0; $b < count($splitData); $b+=2)
    {
        if($splitData[$b] != null)
        {
            $sql = "Update Team_entries SET fix_sort = " . ($i+1) . " WHERE team_id = " . $splitData[$b+1] . " and team_cal_year = '$year' and team_grade = '" . $splitData[$b] . "' and team_season = '$season'";
            $update = mysql_query($sql, $connvbsa) or die(mysql_error());
        }
        else
        {
            $b++;
        }
        $i++;
    }
}
?>
<script>

$(document).ready(function()
{
    $.fn.save_fixtures(form_no);
});

</script>
<?php

echo("Updated");

?>