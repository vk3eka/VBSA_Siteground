<?php

include ("connection.inc");
include('server_name.php');
//include ("header.php");

if($_POST['ButtonName'] == 'SaveData')
{
    $sql_players = "Update tbl_test_scores SET `total_position` =(IFNULL(tbl_test_scores.r01pos,0)) +(IFNULL(tbl_test_scores.r02pos,0)) +(IFNULL(tbl_test_scores.r03pos,0)) +(IFNULL(tbl_test_scores.r04pos,0)) +(IFNULL(tbl_test_scores.r05pos,0)) +(IFNULL(tbl_test_scores.r06pos,0)) +(IFNULL(tbl_test_scores.r07pos,0)) +(IFNULL(tbl_test_scores.r08pos,0)) +(IFNULL(tbl_test_scores.r09pos,0)) +(IFNULL(tbl_test_scores.r10pos,0)) +(IFNULL(tbl_test_scores.r11pos,0)) +(IFNULL(tbl_test_scores.r12pos,0)) +(IFNULL(tbl_test_scores.r13pos,0)) +(IFNULL(tbl_test_scores.r14pos,0)) +(IFNULL(tbl_test_scores.r15pos,0)) +(IFNULL(tbl_test_scores.r16pos,0)) +(IFNULL(tbl_test_scores.r17pos,0)) +(IFNULL(tbl_test_scores.r18pos,0)) WHERE current_year_scrs = YEAR(CURDATE( )) and team_grade = 'BVB' and season = 'S1'";

    //$sql_players = "Update tbl_test_scores SET `count_position` =(SELECT COUNT(`r01pos`)+COUNT(`r02pos`)+COUNT(`r03pos`)+COUNT(`r04pos`)+COUNT(`r05pos`)+COUNT(`r06pos`)+COUNT(`r07pos`)+COUNT(`r08pos`)+COUNT(`r09pos`) +COUNT(`r10pos`)+COUNT(`r11pos`)+COUNT(`r12pos`)+COUNT(`r13pos`)+COUNT(`r14pos`)+COUNT(`r15pos`)+COUNT(`r16pos`)+COUNT(`r17pos`)+COUNT(`r18pos`)) WHERE current_year_scrs = YEAR(CURDATE( )) and team_grade = 'BVB'";

    //$sql_players = "Update tbl_test_scores SET average_position=total_position/count_position WHERE current_year_scrs = YEAR(CURDATE( )) and team_grade = 'BVB' and season = 'S1'";

    //$sql_players = "Update tbl_test_scores SET count_played =(SELECT COUNT(r01s)+COUNT(r02s)+COUNT(r03s)+COUNT(r04s)+COUNT(r05s)+COUNT(r06s)+COUNT(r07s)+COUNT(r08s)+COUNT(r09s) +COUNT(r10s)+COUNT(r11s)+COUNT(r12s)+COUNT(r13s)+COUNT(r14s)+COUNT(r15s)+COUNT(r16s)+COUNT(r17s)+COUNT(r18s)) WHERE current_year_scrs = YEAR(CURDATE( )) and team_grade = 'BVB' and season = 'S1'";

    echo("SQL - " . $sql_players . "<br>");
    echo("Data updated<br>");


    $update = $dbcnx_client->query($sql_players);
    if(!$update)
    {
      die("Could not update score data: " . mysqli_error($dbcnx_client));
    } 
}

?>
<script>

function SaveButton() 
{  
  alert("Here");  
  document.authorise.ButtonName.value = "SaveData";
  document.authorise.submit();
}  
</script>
<center>
<form name="authorise" method="post" action="<?= $url ?>/test_scores.php">
<input type="hidden" name="ButtonName" />
<table class='table dt-responsive nowrap display'>
    <tr>
        <td>
            <table class='table table-striped table-bordered dt-responsive nowrap display'>
                <tr>
                    <td colspan=3 align=center><b>Test Score Update</b></td>
                </tr>
                <tr> 
                    <td colspan=3 align=center>&nbsp;</td>
                </tr>
                 <tr> 
                    <td colspan=3 align=center><a class='btn btn-primary btn-xs' href="javascript:;" onclick="SaveButton();">Update</a></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</form>
</center>
<?php

//include("footer.php"); 

?>
