<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include('header.php');
include('connection.inc');
include('php_functions.php'); 

$current_year = $_SESSION['year'];
$current_season = $_SESSION['season'];
$team = $_SESSION['clubname'];
$memberid = $_SESSION['playerID'];
//$memberid = 171;

$sql_captain = "Select MemberID, FirstName, LastName From members Where MemberID = " . $memberid . " Order By LastName";
$result = $dbcnx_client->query($sql_captain);
$build_data = $result->fetch_assoc();
$captain_name = $build_data['FirstName'] . " " . $build_data['LastName'];

/*
$sql_team_grade = "Select * FROM scrs where MemberID = " . $memberid . " and current_year_scrs = " . $current_year;
echo($sql_team_grade . "<br>");
$result_team_grade = $dbcnx_client->query($sql_team_grade);
$row_team_grade = $result_team_grade->fetch_assoc();
$num_rows = $result_team_grade->num_rows;
if($num_rows > 0)
{
    $team_grade = $row_team_grade['team_grade'];
    $team_id = $row_team_grade['team_id'];
    //$team_id = 1397;
    
    $sql_club_id = "Select * FROM vbsa3364_vbsa2.Team_entries where team_id = " . $team_id;
    $result_club_id = $dbcnx_client->query($sql_club_id);
    //echo($sql_club_id . "<br>");
    $row_club_id = $result_club_id->fetch_assoc();
    $club_id = $row_club_id['team_club_id'];
    $team = $row_club_id['team_name'];
*/
// data for add player dropdown
$players = array();
$sql_players = "Select MemberID, FirstName, LastName From members Order By LastName";
$result = $dbcnx_client->query($sql_players);
$num_rows = $result->num_rows;
if ($num_rows > 0) 
{
    while($build_data = $result->fetch_assoc())
    {
      $firstname = $build_data['FirstName'];
      $lastname = $build_data['LastName'];
      $players[] = $build_data['FirstName'] . " " . $build_data['LastName']; 
    }
    $player_data = json_encode($players);
}
//$result->free_result();

// data for team dropdown
$teams = array();
$sql_teams = "Select distinct team_name From Team_entries where team_name != '' order by team_name";
$result_teams = $dbcnx_client->query($sql_teams);
//echo($sql_teams);
$num_rows = $result_teams->num_rows;
if ($num_rows > 0) 
{
  $teams[] = $_SESSION['clubname'];
  while($build_teams = $result_teams->fetch_assoc()) 
  {
    $teams[] = $build_teams['team_name'];
  }
  $teams_data = json_encode($teams);
  //echo($teams_data . "<br>");
}
//$result_teams->free_result();

?>
<!-- check jquery for autocomplete dropdown -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<!-- end -->
<script>

$(document).ready(function()
{
    var availableTags = <?php echo $player_data; ?>;
    $("#tags").autocomplete(
    {
        source:  availableTags,
        appendTo: "#autocompleteAppendToMe"
    });

    var availableTeamTags = <?php echo $teams_data; ?>;
    $("#team_tags").autocomplete(
    {
        source:  availableTeamTags,
        appendTo: "#autocompleteAppendTeamToMe"
    });
    
    $('#newplayer').click(function(event)
    {
        event.preventDefault();
        if($('#rows').val() > 9)
        {
            alert("10 players have already been entered!");
            $('#tags').val('');
            return;
        }
        else
        {
            var club_name = $('#new_club').val();
            var team_name = $('#team_tags').val();
            var team_grade = $('input[name="grade"]:checked').val();
            var team_id;
            //alert(team_name);
            $.ajax({
                url:"<?= $url ?>/get_team_id.php?team_name=" + team_name + '&team_grade=' + team_grade + '&club_name=' + club_name,
                success : function(data)
                {
                    //team_obj = jQuery.parseJSON(data);
                    console.log(data);
                    team_id = data;
                    $('#team_id').val(team_id);
                }
            });
            //var teamgrade = $('#team_grade').val();
            //var team_grade = $('input[name="grade"]:checked').val();
            team_id = $('#team_id').val();
            var year = <?= date("Y") ?>;
            var new_player = $('#tags').val();
            //alert($('#team_id').val());
            if(new_player == '')
            {
                alert("No member to add!");
                return;
            }
            $.ajax({
                url:"<?= $url ?>/check_player.php?fullname=" + new_player,
                success : function(data)
                {
                    player_obj = jQuery.parseJSON(data);
                    player_id = player_obj[2];
                    $.ajax({
                        url:"<?= $url ?>/save_team_members.php?MemberID=" + player_id + "&TeamGrade=" + team_grade + "&TeamID=" + team_id + "&Year=" + year,
                        success : function(data)
                        {
                            $('#tags').val('');
                            $.fn.displayplayers();
                            //location.reload(true);
                        },
                        error: function (request, error) 
                        {
                            alert("Error");
                        }
                    });
                }
            });
        }
    });

    $('#remove').click(function()
    {
        //alert("Here");
        event.preventDefault();
        for(i = 0; i < 9; i++)
        {
            if($('#delete_' + i).is(":checked"))
            {
                //alert("Here " + i);
                $.ajax({
                    url:"<?= $url ?>/remove_player.php?scrs_id=" + $('#scrs_id_' + i).val(),
                    success : function(data)
                    {
                        //$.fn.displayplayers();
                        $.fn.displayteam();
                        //location.reload(true);
                    }
                });
            }
        }
    });

    $('#add_to_team').click(function(event)
    {
        alert("Here");
        $.fn.displayteam();
        event.preventDefault();
        /*
        var no_of_players = $('#no_of_players').val();
        var team_grade = $('input[name="grade"]:checked').val();
        var teamid = $('#team_id').val();
        //alert(teamid);
        var year = $('#year').val();
        for(i = 0; i < no_of_players; i++)
        {
            if($('#select_' + i).is(":checked"))
            {
                var player_id = $('#select_' + i).val();
                //alert(player_id);
                $.ajax({
                    url:"<?= $url ?>/save_team_members.php?MemberID=" + player_id + "&TeamGrade=" + team_grade + "&TeamID=" + teamid + "&Year=" + year,
                    success : function(response)
                    {
                        //alert(response);
                        location.reload(true);
                    },
                    error: function (request, error) 
                    {
                        alert("Error");
                    }
                });
            }
        }
        */
        //alert(response);
    });

    //declare function to display players in modal box
    $.fn.displayplayers = function () 
    {
        $('#add').empty();
        var team_name = $('#team_tags').val();
        $('#modal_title').html(team_name);
        var team_grade = $('input[name="grade"]:checked').val();
        var obj = "";
        var fullname = "";
        $.ajax({
            url:"<?= $url ?>/get_players.php?clubname=" + team_name + "&TeamGrade=" + team_grade + "&year=" + <?= $_SESSION['year'] ?>,
            success : function(data)
            {
                obj = jQuery.parseJSON(data);
                console.log("Get Players " + obj);
                var output = "";
                output += ("<table class='table table-striped table-bordered dt-responsive nowrap display fetched-data' width='100%'>");
                output += ("<thead>");
                output += ("<tr>");
                output += ("<th style='text-align: center;'>Name</th>");
                output += ("<th style='text-align: center;'>Select Player</th>");
                output += ("</tr>");
                output += ("<thead>");
                output += ("<tbody>");
                for(k = 0; k < obj.length; k++)
                {
                    fullobj = obj[k].split(', ');
                    fullname = fullobj[0] + ' ' + fullobj[1];
                    if(fullobj[2] != 1) // remove Bye
                    {
                        output += ("<input type='hidden' id='scrs_id_" + k + "' value='" + fullobj[7] + "' />");
                        output += ("<tr>");
                        output += ("<td align='left' style='width:250px' id='fullname" + k + "' name='fullname" + k + "'>" + fullname + "</td>");
                        output += ("<td align='center'><input type='radio' data-row='" + k + "' id='select_" + k + "'></td>");
                        output += ("</tr>");
                    }
                }
                output += ("</tbody>");
                output += ("</table>");
                $($.parseHTML(output)).appendTo('#add');
            }
        });
    };

    $('#add_players').click(function()
    {
        var error_caption = "";
        if(!$('input:radio[name="grade"]').is(':checked'))
        {
            error_caption += "Grade ";
        }
        if($('#new_club').val() == '')
        {
            error_caption += "Club ";
        }
        if($('#team_tags').val() == '')
        {
            error_caption += "Team ";
        }
        if(error_caption != "")
        {
            alert('The following data is missing, ' + error_caption);
            return;
        }
        $.fn.displayplayers();
        $('#myPlayers').modal('show');
    });

    $.fn.displayteam = function()
    {
        var team_grade = $('input[name="grade"]:checked').val();
        var team = $('#team_tags').val();
        $.ajax({
            url:"<?= $url ?>/get_players.php?clubname=" + team + "&year=" + <?php echo($_SESSION['year']); ?> + "&TeamGrade=" + team_grade,
            success : function(data)
            {
                obj = jQuery.parseJSON(data);
                //console.log("Get Team Players " + obj);
                no_of_players = obj.length;
                //console.log("Get Player No. " + no_of_players);
                //var k = 0;
                //var fullname = "Peter J";
                /*var output = "";
                output += ("<table class='table table-striped table-bordered dt-responsive display'>");
                output += ("<tr>");
                output += ("<td colspan=4 align=center><b>Players currently in this team (9 max)</b></td>");
                output += ("</tr>");
                output += ("<tr>");
                output += ("<td colspan=4 align=center>&nbsp;</td>");
                output += ("</tr>");
                output += ("<tr>");
                output += ("<td align='center'>Name</td>");
                output += ("<td align='center'>Remove</td>");
                output += ("</tr>");
                //for(k = 0; k < obj.length; k++)
                //{
                //    fullobj = obj[k].split(', ');
                 //   fullname = fullobj[0] + ' ' + fullobj[1];

                for(i = 0; i < no_of_players; i++)
                {
                    fullobj = obj[i].split(', ');
                    output += ("<tr>");
                    output += ("<td align='center'><input type='text' id='name_" + i + "' value='" + fullobj[0] + " " + fullobj[1] + "'></td>");
                    output += ("<td align='center'><input type='checkbox' id='delete_" + i + "'></td>");
                    //output += ("</tr>");
                    //output += ("<tr>");
                    //output += ("<td align='center'><input type='text' id='name'>&nbsp;</td>");
                    //output += ("<td align='center'>");
                    //output += ("<input type='checkbox' id='delete' value='1234'>");
                    //output += ("</td>");
                    output += ("</tr>");
                }
                
                
                output += ("<tr>");
                output += ("<tr>");
                output += ("<td align='center' colspan=4><input type='button' value='Remove Selected Players' id='remove'/></td>");
                output += ("</tr>");
                output += ("<td align='center' colspan=4><input type='button' value='Submit Team' id='add_to_team'/></td>");
                output += ("</tr>");
                
                output += ("</table>")
                */
                for(k = 0; k < obj.length; k++) // max number of players to choose from
                {
                    for(i = 0; i < obj.length; i++)
                    {
                        fullobj = obj[i].split(', ');
                        fullname = fullobj[0] + ' ' + fullobj[1];

                        //var memberID = $('#memberID' + j).val();
                        //name_array = fullname.split(" ");
                        //var firstname = name_array[0];
                        //var lastname = '';
                        //for (name = 1; name < name_array.length; name++)
                        //{
                        //lastname += name_array[name] + " ";
                        //}
                        //var teamID = $('#team_id' + j).val();
                        //var tierID = $('#tier' + j).val();
                        //tierID = parseFloat(tierID).toFixed(1);
                        $("input[id='name_" + k + "']").val(fullname);
                        /*$("input[id='member_id_" + (i-1) + "']").val(memberID);
                        $("input[id='firstname_" + (i-1) + "']").val(firstname);
                        $("input[id='lastname_" + (i-1) + "']").val(lastname);
                        $("input[id='team_id_" + (i-1) + "']").val(teamID);
                        $("input[id='tier_" + (i-1) + "']").val(tierID);*/
                        k++;
                    }
                }
                //$('#no_of_players').val(k);
                //$($.parseHTML(output)).appendTo('#add_player_list');
                //$('#myPlayers').modal('hide');
            }
        });
    };

    $('#populate_positions').click(function()
    {
        $.fn.displayteam();
        /*
        var team_grade = $('input[name="grade"]:checked').val();
        var team = $('#team_tags').val();
        $.ajax({
            url:"<?= $url ?>/get_players.php?clubname=" + team + "&year=" + <?php echo($_SESSION['year']); ?> + "&TeamGrade=" + team_grade,
            success : function(data)
            {
                obj = jQuery.parseJSON(data);
                console.log("Get Team Players " + obj);
                no_of_players = obj.length;
                console.log("Get Player No. " + no_of_players);
                //var k = 0;
                //var fullname = "Peter J";
                /*var output = "";
                output += ("<table class='table table-striped table-bordered dt-responsive display'>");
                output += ("<tr>");
                output += ("<td colspan=4 align=center><b>Players currently in this team (9 max)</b></td>");
                output += ("</tr>");
                output += ("<tr>");
                output += ("<td colspan=4 align=center>&nbsp;</td>");
                output += ("</tr>");
                output += ("<tr>");
                output += ("<td align='center'>Name</td>");
                output += ("<td align='center'>Remove</td>");
                output += ("</tr>");
                //for(k = 0; k < obj.length; k++)
                //{
                //    fullobj = obj[k].split(', ');
                 //   fullname = fullobj[0] + ' ' + fullobj[1];

                for(i = 0; i < no_of_players; i++)
                {
                    fullobj = obj[i].split(', ');
                    output += ("<tr>");
                    output += ("<td align='center'><input type='text' id='name_" + i + "' value='" + fullobj[0] + " " + fullobj[1] + "'></td>");
                    output += ("<td align='center'><input type='checkbox' id='delete_" + i + "'></td>");
                    //output += ("</tr>");
                    //output += ("<tr>");
                    //output += ("<td align='center'><input type='text' id='name'>&nbsp;</td>");
                    //output += ("<td align='center'>");
                    //output += ("<input type='checkbox' id='delete' value='1234'>");
                    //output += ("</td>");
                    output += ("</tr>");
                }
                
                
                output += ("<tr>");
                output += ("<tr>");
                output += ("<td align='center' colspan=4><input type='button' value='Remove Selected Players' id='remove'/></td>");
                output += ("</tr>");
                output += ("<td align='center' colspan=4><input type='button' value='Submit Team' id='add_to_team'/></td>");
                output += ("</tr>");
                
                output += ("</table>")
                */
        /*
                for(k = 0; k < obj.length; k++) // max number of players to choose from
                {
                    for(i = 0; i < obj.length; i++)
                    {
                        fullobj = obj[i].split(', ');
                        fullname = fullobj[0] + ' ' + fullobj[1];

                        //var memberID = $('#memberID' + j).val();
                        //name_array = fullname.split(" ");
                        //var firstname = name_array[0];
                        //var lastname = '';
                        //for (name = 1; name < name_array.length; name++)
                        //{
                        //lastname += name_array[name] + " ";
                        //}
                        //var teamID = $('#team_id' + j).val();
                        //var tierID = $('#tier' + j).val();
                        //tierID = parseFloat(tierID).toFixed(1);
                        $("input[id='name_" + k + "']").val(fullname);
                        /*$("input[id='member_id_" + (i-1) + "']").val(memberID);
                        $("input[id='firstname_" + (i-1) + "']").val(firstname);
                        $("input[id='lastname_" + (i-1) + "']").val(lastname);
                        $("input[id='team_id_" + (i-1) + "']").val(teamID);
                        $("input[id='tier_" + (i-1) + "']").val(tierID);*/
                        //k++;
                    //}
                //}
                //$('#no_of_players').val(k);
                //$($.parseHTML(output)).appendTo('#add_player_list');
                $('#myPlayers').modal('hide');
            //}
        //});
    });
});

</script>
<form id="form1" name="form1" method="post" action="team_registration.php">
<table class='table table-striped table-bordered dt-responsive nowrap display' width='80%'>
    <tr>
        <td align='center'><h2>VBSA Team Selection</h2><br><font color="red">* required</font></td>
    </tr>
    <tr>
        <td>
        <?php
            /*$sql = "Select * from Team_entries where team_cal_year = 2024 and team_name = '" . $_SESSION['clubname'] . "'";
            $result_grade = $dbcnx_client->query($sql);
            $row_grade = $result_grade->fetch_assoc();
            $team_grade = $row_grade["team_grade"];
            $team_club = $row_grade["team_club"];
            $team_id = $row_grade["team_id"];*/
            echo('<table class="table table-striped table-bordered dt-responsive display text-center">');
            echo('<tr>');
            echo('<td align="left">Captain Name</td>');
            echo('<td align="center"><input type="text" id="captain_name" value="' . $captain_name . '" style="width:250px; height:25px;"></td>');
            echo('</r>');
            echo('<tr>');
            echo('<td align="left">Select Grade<font color="red">&nbsp;*</font></td>');
            echo('<td align="left">');
            $sql_grade = "Select grade from Team_grade where current = 'Yes' and fix_cal_year = 2024";
            $result_grade = $dbcnx_client->query($sql_grade);
            while($build_grade = $result_grade->fetch_assoc()) 
            {
                //if($team_grade == $build_grade['grade'])
                //{
                //    $selected = ' checked="checked"';
                //}
                //else
                //{
                    $selected = '';
                //}
                echo('<input type="radio" id="' . $build_grade['grade'] . '" name="grade" value="' . $build_grade['grade'] . '" ' . $selected . '>');
                echo('<label for="' . $build_grade['grade'] . '">&nbsp;' . $build_grade['grade'] . '</label><br>');
            }
            echo('</td>');
            echo('</tr>');
            echo('<tr>');
            echo('<tr>');
            echo('<td align="left">Select Club Name<font color="red">&nbsp;*</font></td>');
            echo("<td align='center'><select id='new_club' style='width:250px; height:25px;'>");
            echo("<option value=''>&nbsp;</option>");
            //$sql_club = "Select ClubTitle from clubs Order By ClubTitle";
            $sql_club = "Select ClubTitle from clubs where inactive = 0 Order By ClubTitle";
            //echo($sql_club . "<br>");
            $result_club = $dbcnx_client->query($sql_club);
            while($build_club = $result_club->fetch_assoc()) 
            {
            echo("<option value='" . $build_club['ClubTitle'] . "'>" . $build_club['ClubTitle'] . "</option>");
            }
            echo("</select></td>");
            echo('</tr>');
            echo('<tr>');
            echo('<td align="left" class="ui-widget" id="new_team">Select or Enter Team Name<font color="red">&nbsp;*</font></td>');
            echo('<td align="center"><input id="team_tags"  style="width:250px; height:25px;">');
            echo('<div id="autocompleteAppendTeamToMe"></div>');
            echo('</td>');
            echo('</tr>');
            echo('<tr>');
            echo('<td class="text-center" colspan=2><a class="btn btn-primary btn-xs" id="add_players">Add Players to Team</a></td>');
            echo('</tr>');
            echo("<input type='hidden' id='team_id' name='team_id'>");
            echo("</table>");
        ?>
        </td>
    </tr>
</table>
<!--<div id='add_player_list'></div>-->

<table class='table table-striped table-bordered dt-responsive display'>
    <tr>
        <td colspan=2 align=center><b>Players currently in this team (9 max)</b></td>
    </tr>
    <tr>
        <td colspan=2 align=center>&nbsp;</td>
    </tr>
    <tr>
        <td align='center'>Name</td>
        <td align='center'>Remove</td>
    </tr>
<?php
for($j = 0; $j < 10; $j++)
{
    echo("<tr>");
    echo("<td align='center'><input type='text' id='name_". $j . "'>&nbsp;</td>");
    echo("<td align='center'>");
    echo('<input type="checkbox" id="delete_' . $j . '">');
    echo("</td>");
    echo("</tr>");
}
?>
    <tr>
        <td colspan=2 align='center'><input type='button' value='Remove Selected Players' id='remove'/></td>
    </tr>
        <td colspan=2 align='center'><input type='button' value='Submit Team' id='add_to_team'/></td>
    </tr>
</table>



<!-- Select Players Modal -->
<div class="modal fade" id="myPlayers" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header ui-front">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 id='modal_title' class="modal-title"></h4>
            </div>
            <div class="modal-body">
              <div></div>
              <br>
              <div class='text-center ui-widget'>
              <label for='tags'>Enter name in this field to add players:&nbsp;&nbsp;</label>
              <input id='tags'>
              <br>
              <div id='autocompleteAppendToMe'></div>
              <br>
              <div>If player is listed in the drop down, select it, if not, you will be requested to provide player details.</div>
              <br>
              <div><a class='btn btn-primary btn-xs' id='newplayer'>Add to List</a>
              <br>
              <div></div>
              </div>
              <input type='hidden' id='temp_first'>
              <input type='hidden' id='temp_last'>
              <br>
              <div></div>
              </div>
              <br>
              <div id='add'></div>
              <br>
              <div class='text-center'><a class='btn btn-primary btn-xs' id='populate_positions'>Add Players to Selection List</a>
              </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</form>
<?php 
include('footer.php'); 
?>


