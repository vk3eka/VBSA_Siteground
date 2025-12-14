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
            type: "GET",
            url: "<?= $url ?>/get_team_dd.php",
            data: {season: $('#season').val(), grade: $('#grade_dd').val()},
            success: function(data){
                obj = jQuery.parseJSON(data);
                var output = "";
                output += ("<option value='all'>All Teams</option>");
                for(k = 0; k < obj.length; k++)
                {
                    output += ("<option value='" + obj[k] + "'>" + obj[k] + "</option>");
                }
                $($.parseHTML(output)).appendTo('#team_dd');
            }
        });
    });

    $('#team_dd').change(function () {
        $('#player_dd').empty();
        $.ajax({
            type: "GET",
            url: "<?= $url ?>/get_player_dd.php",
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
        var grade = $('#grade_dd').val();
        var team = $('#team_dd').val();
        var player = $('#player_dd').val();
        var sort = $('#sort').val();
        var sort_order = $('#sort_order').val();
        $.ajax({
            type: "GET",
            url: "<?= $url ?>/get_player_scores.php",
            data: {season: season, grade: grade, team: team, player: player, sort: sort, sort_order: sort_order},
            success: function(data){
                obj = jQuery.parseJSON(data);
                console.log(obj);;
                var output = "";
                output += ("<table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>");
                output += ("<tr>"); 
                output += ("<td  align='center'>Name</td>");
                output += ("<td  align='center'>Playing Position</td>");
                output += ("<td  align='center'>Team</td>");
                output += ("<td  align='center'>Opposition</td>");
                output += ("<td  align='center'>Year</td>");
                output += ("<td  align='center'>Season</td>");
                output += ("<td  align='center'>Team Grade</td>");
                output += ("<td  align='center'>Round</td>");
                output += ("<td  align='center'>Date Played</td>");
                output += ("<td  align='center'>Win 1</td>");
                output += ("<td  align='center'>Win 2</td>");
                output += ("<td  align='center'>Win 3</td>");
                output += ("<td  align='center'>Win 4</td>");
                output += ("<td  align='center'>Raw Scores</td>");
                output += ("<td  align='center'>Scores 1</td>");
                output += ("<td  align='center'>Scores 2</td>");
                output += ("<td  align='center'>Scores 3</td>");
                output += ("<td  align='center'>Scores 4</td>");
                output += ("<td  align='center'>Breaks 1</td>");
                output += ("<td  align='center'>Breaks 2</td>");
                output += ("<td  align='center'>Breaks 3</td>");
                output += ("<td  align='center'>Breaks 4</td>");
                output += ("<td  align='center'>Tier</td>");
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
});

</script>
<table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
    <tr>
       <td colspan=20 align=center><b>View Players Scores</b></td>
    </tr>
    <tr> 
        <td colspan=20 align=center>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=5 align='center' valign='top'><b>Select Year/Season</b></td> 
        <td colspan=5 align='center' valign='top'><b>Select Team Grade</b></td> 
        <td colspan=5 align='center' valign='top'><b>Select Team</b></td> 
        <td colspan=5 align='center' valign='top'><b>Select Player</b></td>  
     </tr>
    <tr>
        <td colspan=5 align='center' valign='top'><select id='season'> 
            <option value=''>Select Season/Year</option>
            <option value='2023, S1'>2023, S1</option>
            <option value='2023, S2'>2023, S2</option>
            <option value='2024, S1'>2024, S1</option>
            <option value='2024, S2'>2024, S2</option>
            <option value='2025, S1'>2025, S1</option>
            <option value='2025, S2'>2025, S2</option>
        </select></b> 
        </td> 
     <td colspan=5 align='center' valign='top'><select id='grade_dd'> 
        </select></b> 
        </td> 
        <td colspan=5 align='center' valign='top'><select id='team_dd'> 
        </select></b> 
        </td> 
        <td colspan=5 align='center' valign='top'><select id='player_dd'> 
        </select></b> 
        </td> 
    </tr>  
    <tr> 
        <td colspan='20'>&nbsp;</td>
    </tr>  
    <tr> 
        <td colspan='10' align='center' valign='top'>Sort By&nbsp;&nbsp;<select id='sort'> 
                <option value=''>&nbsp;</option>
                <option value='players_name'>players_name</option>
                <option value='playing_position'>playing_position</option>
                <option value='team'>team</option>
                <option value='year'>year</option>
                <option value='season'>season</option>
                <option value='team_grade'>team_grade</option>
                <option value='round'>round</option>
                <option value='date_played'>date_played</option>
                <option value='win_1'>win_1</option>
                <option value='win_2'>win_2</option>
                <option value='win_3'>win_3</option>
                <option value='win_4'>win_4</option>
                <option value='score_1'>score_1</option>
                <option value='score_2'>score_2</option>
                <option value='score_3'>score_3</option>
                <option value='score_4'>score_4</option>
                <option value='break_1'>break_1</option>
                <option value='break_2'>break_2</option>
                <option value='break_3'>break_3</option>
                <option value='break_4'>break_4</option>
                <option value='tier'>tier</option>
            </select> 
        </td> 
        <td colspan='10' align='center' valign='top'>Ascending/Descending&nbsp;&nbsp;<select id='sort_order'> 
            <option value=''>&nbsp;</option>
            <option value='asc'>Ascending</option>
            <option value='desc'>Desending</option>
            </select> 
        </td> 
    </tr>
    <tr> 
        <td class='text-center' colspan='20'><a class='btn btn-primary btn-xs' id='add_data' >Add Data to Table</a></td>
    </tr>
    <tr> 
        <td colspan='20'>&nbsp;</td>
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
<?php 

include('footer.php'); 

?>