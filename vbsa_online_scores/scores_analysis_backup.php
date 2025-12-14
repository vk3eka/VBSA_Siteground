<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include('header.php');
include('connection.inc');
include('php_functions.php');

$year = $_SESSION['year'];
$season = $_SESSION['season'];

?>
<script type="text/JavaScript">
$(document).ready(function()
{
    $('#season').change(function () {
        $('#grade_dd').empty();
        $('#team_dd').empty();
        $('#player_dd').empty();
        $.ajax({
            type: "GET",
            url: "<?= $url ?>/get_grade_dd.php",
            data: "season=" + $('#season').val(),
            success: function(data){
                obj = jQuery.parseJSON(data);
                var output = "";
                output += ("<option value='all'>All Grades</option>");
                for(k = 0; k < obj.length; k++)
                {
                    output += ("<option value='" + obj[k] + "'>" + obj[k] + "</option>");
                }
                $($.parseHTML(output)).appendTo('#grade_dd');
            }
        });
    });

    $('#grade_dd').change(function () {
        $('#player_dd').empty();
        $('#team_dd').empty();
        $.ajax({
            ype: "GET",
            url: "<?= $url ?>/get_team_dd.php",
            data: {season: $('#season').val(), grade: $('#grade_dd').val()},
            success: function(data){
                obj = jQuery.parseJSON(data);
                var output = "";
                output += ("<option value='all'>All Teams</option>");
                for(k = 0; k < obj.length; k++)
                {
                    $name = obj[k].split(' - ');
                    output += ("<option value='" + $name[1] + "'>" + $name[0] + "</option>");
                }
                $($.parseHTML(output)).appendTo('#team_dd');
            }
        });
    });

    $('#team_dd').change(function () {
        $('#player_dd').empty();
        $.ajax({
            type: "GET",
            url: "<?= $url ?>/get_player_id_dd.php",
            data: {season: $('#season').val(), grade: $('#grade_dd').val(), team: $('#team_dd').val()},
            success: function(data){
                obj = jQuery.parseJSON(data);
                var output = "";
                output += ("<option value='all'>All Players</option>");
                for(k = 0; k < obj.length; k++)
                {
                    output += ("<option value='" + obj[k] + "'>" + obj[k] + "</option>");
                }
                $($.parseHTML(output)).appendTo('#player_dd');
            }
        });
    });

    $('#add_data').click(function () {
        $('#score_data').empty();
        var season = $('#season').val();
        if(season == '')
        {
            alert("No Year/Season selected!");
            return;
        }
        var grade = $('#grade_dd').val();
        var team = $('#team_dd').val();
        var player = $('#player_dd').val();
        var sort = $('#sort').val();
        var sort_order = $('#sort_order').val();

        alert(team );

        $.ajax({
            type: "GET",
            url: "<?= $url ?>/get_scores_data.php",
            data: {season: season, grade: grade, team: team, player: player, sort: sort, sort_order: sort_order},
            success: function(data){
                obj = jQuery.parseJSON(data);
                var output = "";
                output += ("<table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>");
                output += ("<tr>"); 
                output += ("<td  align='center'>Member ID</td>");
                output += ("<td  align='center'>Name</td>");
                output += ("<td  align='center'>Team</td>");
                output += ("<td  align='center'>Team Grade</td>");
                output += ("<td  align='center'>Year</td>");
                output += ("<td  align='center'>Season</td>");
                output += ("<td  align='center'>Allocated RP</td>");
                output += ("<td  align='center'>Type</td>");
                output += ("<td  align='center'>Games Played</td>");
                output += ("<td  align='center'>Available Points</td>");
                output += ("<td  align='center'>Points Won</td>");
                //output += ("<td  align='center'>Win Ratio</td>");
                output += ("<td  align='center'>Percentage Won</td>");
                output += ("<td  align='center'>Average Position</td>");
                output += ("</tr>");
                for(k = 0; k < obj.length; k++)
                {
                    output += ("<tr>"); 
                    var obj_data = obj[k].split(",");
                    for(j = 0; j < obj_data.length; j++)
                    {
                        output += ("<td  align='center'>" + obj_data[j] + "</td>");
                    }
                    output += ("</tr>");
                } 
                output += ("</table>");
                $($.parseHTML(output)).appendTo('#score_data');
            }
        });
    });

    $('#export_data').click(function () {
        var season = $('#season').val();
        var grade = $('#grade_dd').val();
        var player = $('#player_dd').val();
        var sort = $('#sort').val();
        var sort_order = $('#sort_order').val();
        $.ajax({
            type: "GET",
            url: "<?= $url ?>/export_to_csv.php",
            data: {season: season, grade: grade, player: player, sort: sort, sort_order: sort_order},
            success: function(data){
                /*
                * Make CSV downloadable
                */
                var downloadLink = document.createElement("a");
                var fileData = ['\ufeff' + data];
                var blobObject = new Blob(fileData,{
                 type: "text/csv;charset=utf-8;"
                });
                var url = URL.createObjectURL(blobObject);
                downloadLink.href = url;
                downloadLink.download = "export.csv";
                /*
                * Actually download CSV
                */
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
                alert("File 'export.csv' has been downloaded.");
            }
        });
    });

});

</script>
<table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
    <tr>
       <td colspan=12 align=center><b>View Players Scores</b></td>
    </tr>
    <tr> 
        <td colspan=12 align=center>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=3 align='center' valign='top'><b>Select Year/Season</b></td> 
        <td colspan=3 align='center' valign='top'><b>Select Team Grade</b></td> 
        <td colspan=3 align='center' valign='top'><b>Select Team</b></td> 
        <td colspan=3 align='center' valign='top'><b>Select Player</b></td>  
     </tr>
    <tr>
        <td colspan=3 align='center' valign='top'><select id='season'> 
            <option value=''>Select Season/Year</option>
            <option value='2023, S1'>2023, S1</option>
            <option value='2023, S2'>2023, S2</option>
            <option value='2024, S1'>2024, S1</option>
            <option value='2024, S2'>2024, S2</option>
            <option value='2025, S1'>2025, S1</option>
            <option value='2025, S2'>2025, S2</option>
        </select></b></td> 
        <td colspan=3 align='center' valign='top'><select id='grade_dd'></select></b></td> 
        <td colspan=3 align='center' valign='top'><select id='team_dd'></select></b></td> 
        <td colspan=3 align='center' valign='top'><select id='player_dd'></select></b></td> 
    </tr>  
    <tr> 
        <td colspan='12'>&nbsp;</td>
    </tr>  
    <tr> 
        <td colspan='6' align='center' valign='top'>Sort By&nbsp;&nbsp;<select id='sort'> 
                <option value=''>&nbsp;</option>
                <option value='scrs.MemberID'>MemberID</option>
                <option value='LastName'>Lastname</option>
                <option value='team_name'>Team Name</option>
                <option value='team_grade'>Team Grade</option>
                <option value='year'>Year</option>
                <option value='season'>Season</option>
                <option value='allocated_rp'>Allocated RP</option>
                <option value='game_type'>Type</option>
                <option value='count_played'>Games Played</option>
                <option value='avail_pts'>Available Points</option>
                <option value='pts_won'>Points Won</option>
                <option value='percent_won'>Percentage Won</option>
                <option value='average_position'>Average Position</option>
            </select> 
        </td> 
        <td colspan='6' align='center' valign='top'>Ascending/Descending&nbsp;&nbsp;<select id='sort_order'> 
            <option value=''>&nbsp;</option>
            <option value='asc'>Ascending</option>
            <option value='desc'>Desending</option>
            </select> 
        </td> 
    </tr>
    <tr> 
        <td class='text-center' colspan='12'><a class='btn btn-primary btn-xs' id='add_data' >Add Data to Datagrid</a></td>
    </tr>
    <tr> 
        <td colspan='12'>&nbsp;</td>
    </tr>
  </tr>
</table>
<br>
<table class='table table-striped table-bordered dt-responsive nowrap display' width='80%'>
    <tr>
        <td>
            <div id='score_data'></div>
	    </td>
    </tr>
</table>  
<br>
<table class='table table-striped table-bordered dt-responsive nowrap display' width='80%'>
    <tr> 
        <td class='text-center' colspan='12'><a class='btn btn-primary btn-xs' id='export_data' >Export Datagrid to CSV file.</a></td>
    </tr>
</table>
<?php 

include('footer.php'); 

?>