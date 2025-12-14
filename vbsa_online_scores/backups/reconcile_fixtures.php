<?php

include('connection.inc');
include('header.php');
include('php_functions.php'); 

?>
<script>

$(document).ready(function()
{
    $('#reconcile').click(function(event){
        event.preventDefault();
        $('#ButtonName').val("Reconcile");
        var ButtonName = $('#ButtonName').val();
        $('#reconcile').submit();
    })
});    

</script>
<?php

$current_year = $_SESSION['year'];
$current_season = $_SESSION['season'];

if($_POST['ButtonName'] == 'Reconcile')
{
    echo("<table class='table table-striped dt-responsive nowrap display' width='100%'>");
    echo("<tr>");
    echo("<td>&nbsp;</td>");
    echo("<td>&nbsp;</td>");
    echo("<td>&nbsp;</td>");
    echo("</tr>");
    $sql_team = "Select distinct team_name FROM Team_entries where team_season = '" . $current_season . "' and team_cal_year = " . $current_year . " order by team_name";
    $result_team = $dbcnx_client->query($sql_team) or die("Couldn't execute team query. " . mysqli_error($dbcnx_client));
    while($build_team = $result_team->fetch_assoc()) 
    {
        $sql_fixtures = "Select fix1home From tbl_fixtures Where year = " . $current_year . " AND season = '" . $current_season . "' and fix1home = '" . $build_team['team_name'] . "'";
        $result_fixtures = $dbcnx_client->query($sql_fixtures) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
        $build_fixtures = $result_fixtures->fetch_assoc();
        if(($build_team['team_name'] != $build_fixtures['fix1home']))
        {
          if($build_team['team_name'] != 'Bye')
          {
            echo("<tr>");
            echo("<td>&nbsp;</td>");
            echo("<td align='center'>Team " . $build_team['team_name'] . " does not exist in the fixtures table</td>");
            echo("<td>&nbsp;</td>");
            echo("</tr>");
          }
        }
    }
    echo("<tr>");
    echo("<td colspan=3>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td>&nbsp;</td>");
    echo("<td align='center'><a class='btn btn-primary btn-xs' id='reconcile'>Reconcile Fixtures</a></td>");
    echo("<td>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td colspan=3>&nbsp;</td>");
    echo("</tr>");
    echo("</table>");
}
else
{
?>
<center>
<form name="reconcile" id="reconcile" method="post" action="reconcile_fixtures.php">
<input type="hidden" name="ButtonName" id="ButtonName"/>
<table class='table table-striped dt-responsive nowrap display' width='100%'>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">This page will find team entries from the fixture list that do not match the team entries list.</td>
    <td>&nbsp;</td>
  </tr>  
  <tr>
    <td>&nbsp;</td>
    <td align="center">You will be asked if you want to reconcile the entries.</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center"><a class='btn btn-primary btn-xs' id='reconcile'>Find Unmatched Fixtures</a></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>  
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>  
</table>
</form>
</center>
<?php
}

include("footer.php"); 

?>
