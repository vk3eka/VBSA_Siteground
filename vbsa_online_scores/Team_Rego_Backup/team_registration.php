<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include('header.php');
include('connection.inc');
include('php_functions.php'); 

$current_year = $_SESSION['year'];
//$current_season = $_SESSION['season'];
$current_season = 'S2';
$team = $_SESSION['clubname'];
$memberid = $_SESSION['playerID'];

$sql_captain = "Select MemberID, FirstName, LastName From members Where MemberID = " . $memberid . " Order By LastName";
$result = $dbcnx_client->query($sql_captain);
$build_data = $result->fetch_assoc();
$captain_name = $build_data['FirstName'] . " " . $build_data['LastName'];

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

// data for team dropdown
$teams = array();
$sql_teams = "Select distinct team_name From Team_entries where team_name != '' order by team_name";
$result_teams = $dbcnx_client->query($sql_teams);
$num_rows = $result_teams->num_rows;
if ($num_rows > 0) 
{
  $teams[] = $_SESSION['clubname'];
  while($build_teams = $result_teams->fetch_assoc()) 
  {
    $teams[] = $build_teams['team_name'];
  }
  $teams_data = json_encode($teams);
}

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
/*
    var availableTeamTags = <?php echo $teams_data; ?>;
    $("#team_tags").autocomplete(
    {
        source:  availableTeamTags,
        appendTo: "#autocompleteAppendTeamToMe"
    });
*/
    // add emergency player to the database
    $.fn.add_emergency = function () {
        event.preventDefault();
        var club_name = $('#new_club').val();
        var team_name = $('#team_tags').val();
        var firstname = $('#em_first').val();
        var surname = $('#em_last').val();
        var email = $('#em_email').val();
        var mobile = $('#em_mobile').val();
        var previous = $('#em_previous').val();
        var user = '<?php echo($_SESSION['username']); ?>';
        var year = '<?php echo($_SESSION['year']); ?>';
        var season = '<?= $current_season ?>';
        var team_grade = $('input[name="grade"]:checked').val();
        var type = 'Snooker'; //............................
        //var type = $('#type').val();
        var team_id = $('#team_id').val();

        if((firstname != '') && (surname != '') && (email != '') && (mobile != ''))
        {
          $.ajax({
            url:"<?= $url ?>/add_new_team_player.php?teamname=" + team_name + "&clubname=" + club_name + "&firstname=" + firstname + "&surname=" + surname + "&team_grade=" + team_grade + "&type=" + type + "&year=" + year + "&season=" + season + "&email=" + email + "&mobile=" + mobile + "&previous=" + previous + "&teamID=" + team_id + "&user=" + user,
            method: 'POST',
            success:function(response)
            {
              $('#tags').val(''); 
              $('#add').empty();
              $.fn.display_club_players();
            },
          });
          return true;
        }
        else
        {
          alert("All boxes should be filled.");
          return false;
        }
    }

    $('#em_previous').click(function(event){
        event.preventDefault();
        var previous_caption = "Please repeat your search and make sure of spelling.  If still not found, continue with registering a new player";
        $($.parseHTML(previous_caption)).appendTo('#add_previous');
    });

    $('#newplayer').click(function(event)
    {
        event.preventDefault();
        /*
        alert($('#rows').val());
        if($('#rows').val() > 9)
        {
            alert("10 players have already been entered!");
            $('#tags').val('');
            return;
        }
        else
        {
        */
            var club_name = $('#new_club').val();
            var team_name = $('#team_tags').val();
            var team_grade = $('input[name="grade"]:checked').val();
            var team_id;
            $.ajax({
                url:"<?= $url ?>/get_team_id.php",
                data: {team_name: team_name, team_grade: team_grade, club_name: club_name},
                success : function(data)
                {
                    //console.log("Team ID " + data);
                    team_id = data;
                    $('#team_id').val(team_id);
                    //alert(team_id);
                }
            });
            team_id = $('#team_id').val();
            //alert($('#team_id').val());
            var year = <?= date("Y") ?>;
            var new_player = $('#tags').val();
            if(new_player == '')
            {
                alert("No member to add!");
                return;
            }
            $.ajax({
                url:"<?= $url ?>/check_player.php?fullname=" + new_player,
                success : function(data)
                {
                    //console.log("Check Player " + data);
                    player_obj = jQuery.parseJSON(data);
                    player_id = player_obj[2];
                    if(player_obj[2] == '')
                    {
                      $('#em_first').val(player_obj[0]);
                      $('#em_last').val(player_obj[1]);
                      $('#EmPlayer').modal('show');
                      $('#em_player').click(function (event) {
                        event.preventDefault();
                        console.log("Energency " + $.fn.add_emergency());
                        if($.fn.add_emergency() === true)
                        {
                          $('#EmPlayer').modal('hide');
                          $.fn.display_club_players();
                        }
                      });
                    }
                    else
                    {
                        firstname = player_obj[0];
                        surname = player_obj[1];
                        var year = '<?php echo($_SESSION['year']); ?>';
                        var season = '<?php echo($_SESSION['season']); ?>';
                        var type = 'Snooker'; //............................
                        var round = 0;
                        $.ajax({
                            url:"<?= $url ?>/add_new_player.php?clubname=" + team_name + "&firstname=" + firstname + "&surname=" + surname + "&team_grade=" + team_grade + "&type=" + type + "&year=" + year + "&season=" + season + "&round=" + round,
                            method: 'POST',
                            success:function(response)
                            {
                              $('#tags').val(''); 
                              $('#add').empty();
                              $.fn.display_club_players();
                            },
                        });
                    }
                }
            });
        //}
    });

    $('#remove').click(function()
    {
        event.preventDefault();
        for(i = 0; i < 9; i++)
        {
            if($('#delete_' + i).is(":checked"))
            {
                console.log('Member ID ' + $('#member_ID_' + i).val());
                console.log('List ID ' + $('#scrs_id_' + i).val());
                $('#captain_' + i).prop('checked',false);
                //var team_grade = $('input[name="grade"]:checked').val();
                //var year = <?= date("Y") ?>;
                //var season = '<?= $current_season ?>';
                $.ajax({
                    url:"<?= $url ?>/remove_player.php?scrs_id=" + $('#scrs_id_' + i).val(),
                    //url:"<?= $url ?>/remove_player.php?scrs_id=" + $('#scrs_id_' + i).html() + "&team_grade=" + team_grade + "&season=" + season + "&year=" + year,
                    success : function(data)
                    {
                        $.fn.display_team_players();
                    }
                });
            }
        }
    });

    $('#add_to_team').click(function(event)
    {
        event.preventDefault();
        var team_grade = $('input[name="grade"]:checked').val();
        var club_name = $('#new_club').val();
        var team_name = $('#team_tags').val();
        var year = <?= date("Y") ?>;
        var team_id = $('#team_id').val();

        // get team ID
        $.ajax({
            url:"<?= $url ?>/get_team_id.php",
            data: {team_name: team_name, team_grade: team_grade, club_name: club_name},
            success : function(data)
            {
                console.log(data);
                team_id = data;
                $('#team_id').val(team_id);
            }
        });
        team_id = $('#team_id').val();
        team_member_array = new Array;
        z = 0;
        // get players from list
        for(i = 0; i < 9; i++)
        {
            if($('#name_' + i).val() != '')
            {
                selected = 1;
                if($('#captain_' + i).is(":checked"))
                {
                    captain = 1;
                }
                else
                {
                     captain = 0;
                }
                var player_id = $('#member_ID_' + i).val();
                /*
                console.log("Captain " + captain);
                console.log("Selected " + selected);
                console.log("ID " + player_id);
                console.log("Team ID " + team_id);
                console.log("Team Name " + team_name);
                console.log("Team Grade " + team_grade);
                */
                team_member_array[z] = player_id + ", " + team_id + ", " + team_grade + ", " + captain + ", " + selected + ";";
                //console.log("Array[z] " + team_member_array[z]);
                z++;
            }
        }
        $.ajax({
            url:"<?= $url ?>/save_team_members.php?members_array=" + team_member_array + "&teamgrade=" + team_grade,
            method: 'POST',
            success : function(response)
            {
                if(response === 'true')
                {
                     alert('Team list has been updated.');
                }
            },
            error: function (request, error) 
            {
                alert("Error");
            }
        });
        $('#tags').val('');
        $.fn.display_club_players();
        //alert("Team Data Saved");
    });

    //declare function to display players in modal box
    $.fn.display_club_players = function () 
    {
        $('#add').empty();
        var team_name = $('#team_tags').val();
        var club_name = $('#new_club').val();
        $('#modal_title').html(team_name);
        var team_grade = $('input[name="grade"]:checked').val();
        var obj = "";
        var fullname = "";
        $.ajax({
            url:"<?= $url ?>/get_club_players.php",
            data: {year: <?= $_SESSION['year'] ?>, team_grade: team_grade, clubname: club_name},
            success : function(data)
            {
                obj = jQuery.parseJSON(data);
                //console.log("Get Players " + obj);
                //console.log("Get Length " + obj.length);
                var output = "";
                output += ("<table class='table table-striped table-bordered dt-responsive nowrap display fetched-data' width='100%'>");
                output += ("<thead>");
                output += ("<tr>");
                //output += ("<th style='text-align: center;'>ID</th>");
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
                        output += ("<input type='hidden' id='member_id_" + k + "' value='" + fullobj[2] + "' />");
                        output += ("<input type='hidden' id='scrs_id_" + k + "' value='" + fullobj[7] + "' />");
                        output += ("<tr>");
                        //output += ("<td align='left' style='width:250px' id='scrs_id_" + k + "' name='scrs_id_" + k + "'>" + fullobj[7] + "</td>");
                        output += ("<td align='left' style='width:250px' id='fullname_" + k + "' name='fullname_" + k + "'>" + fullname + "</td>");
                        if(fullobj[8] == 1)
                        {
                            output += ("<td align='center'><input type='checkbox' data-row='" + k + "' id='select_" + k + "' checked></td>");
                        }
                        else if(fullobj[8] == 0)
                        {
                            output += ("<td align='center'><input type='checkbox' data-row='" + k + "' id='select_" + k + "'></td>");
                        }
                        output += ("</tr>");
                    }
                }
                output += ("<input type='hidden' id='no_of_players' value=" + k + " />");
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
        $.fn.display_club_players();
        $('#myPlayers').modal('show');
    });

    $.fn.display_team_players = function()
    {
        console.log("Display Team");
        var team_grade = $('input[name="grade"]:checked').val();
        var team = $('#team_tags').val();
        $.ajax({
            url:"<?= $url ?>/get_team_players.php",
            data: {year: <?= $_SESSION['year'] ?>, team_grade: team_grade, team_name: team},
            success : function(data)
            {
                obj = jQuery.parseJSON(data);
                //console.log("Club Data " + obj);
                //console.log("Club Length " + obj.length);
                var x = 0;
                // clear existing list
                for(k = 0; k < 9; k++)
                //for(k = 0; k < obj.length; k++)
                {
                    $("input[id='name_" + x + "']").val('');
                    $("input[id='scrs_ID_" + x + "']").val('');
                    $('#captain_' + x).prop('checked',false);
                    $('#delete_' + x).prop('checked',false);
                    x++;
                    
                }
                y = 0;
                for(k = 0; k < obj.length; k++)
                {
                    for(i = 0; i < 7; i++) // number of elements in array
                    {
                        fullobj = obj[k].split(', ');
                        fullname = fullobj[0] + ' ' + fullobj[1];
                        $("input[id='name_" + y + "']").val(fullname);
                        $("input[id='scrs_ID_" + y + "']").val(fullobj[7]);

                        if(fullobj[9] == 1)
                        {
                            $('#captain_' + y).prop('checked',true);
                        }
                        else
                        {
                            $('#captain_' + y).prop('checked',false);
                        }
                    }
                    y++;
                }
            }
        });
    };

    $('#populate_positions').click(function(event)
    {
        event.preventDefault();
        var no_of_checked = 0;
        var no_of_players = $('#no_of_players').val();
        var year = '<?= $current_year ?>';
        var season = '<?= $current_season ?>';
        var club_name = $('#new_club').val();
        var team_name = $('#team_tags').val();
        var team_grade = $('input[name="grade"]:checked').val();
        var team_id;
        var x = 0;
        // clear existing list
        for(k = 0; k < 9; k++)
        {
            $("input[id='name_" + x + "']").val('');
            $("input[id='scrs_id_" + x + "']").val('');
            $('#captain_' + x).prop('checked',false);
            $('#delete_' + x).prop('checked',false);
            x++;
        }
        $.ajax({
            url:"<?= $url ?>/get_team_id.php",
            data: {team_name: team_name, team_grade: team_grade, club_name: club_name},
            success : function(data)
            {
                console.log("Team ID " + data);
                $('#team_id').val(team_id);
                var selected = 0;
                var captain = 0;
                
                for(i = 0; i < no_of_players; i++)
                {
                    //alert($('#select_' + i).is(":checked"));
                    if($('#select_' + i).is(":checked"))
                    {
                        //alert($('#scrs_id_' + i).html());

                        $('#member_ID_' + no_of_checked).val($('#member_id_' + i).val());
                        $('#name_' + no_of_checked).val($('#fullname_' + i).html());
                        $('#scrs_ID_' + no_of_checked).val($('#scrs_id_' + i).val());
                        $.ajax({
                            url:"<?= $url ?>/add_team_players.php",
                             data: {MemberID: $('#member_ID_' + no_of_checked).val(), TeamID: team_id, team_name: team_name, TeamGrade: team_grade, captain: captain, selected: selected},
                            success : function(response)
                            {
                                console.log("Save Players");
                                //alert(response);
                            },
                            error: function (request, error) 
                            {
                                alert("Error, Team ID " + team_id + ".");
                            }
                        });
                        no_of_checked++;
                    }
                }
                $('#myPlayers').modal('hide');
            }
        });
    });

    $('#team_tags').on('focusout', function()
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
        
        var year = '<?= $current_year ?>';
        var season = '<?= $current_season ?>'
        var team_name = $('#team_tags').val();
        var team_grade = $('input[name="grade"]:checked').val();
        team_name = $.trim(team_name);
        $.ajax({
            url:"<?= $url ?>/check_team_exists.php?team_name=" + team_name + "&team_grade=" + team_name + "&season=" + season + "&year=" + year,
            success : function(response)
            {
                if(response === 'true')
                {
                     alert(team_name + " is already in use!");
                     return;
                }
            }
        });
        
        $.fn.display_team_players();
    });

    $(".captain").change(function() 
    {
        $(".captain").prop('checked', false);
        $(this).prop('checked', true);
    });

    $("#un_select").click(function() 
    {
        x = 0;
        for(k = 0; k < 9; k++)
        {
            $('#captain_' + x).prop('checked',false);
            x++;
        }
    });
});

</script>
<form id="form1" name="form1" method="post" action="team_registration.php">
<table class='table table-striped table-bordered dt-responsive nowrap display' width='80%'>
    <tr>
        <td align='center'><h2>VBSA Team Selection</h2><br><font color="red">* required</font></td>
    </tr>
    <tr>
        <td align='center'>(For <?= $current_season ?>, <?= $current_year ?>)</h2></td>
    </tr>
    <tr>
        <td>
        <?php
            echo('<table class="table table-striped table-bordered dt-responsive display text-center">');
            echo('<tr>');
            echo("<td align='left'>Who is completing this Registration?</td>");
            echo('<td align="center">' . $captain_name . '</td>');
            //echo('<td align="center"><input type="text" id="captain_name" value="' . $captain_name . '" style="width:300px; height:25px;" readonly></td>');
            echo('</r>');
            echo('<tr>');
            echo('<td align="left">Select Grade<font color="red">&nbsp;*</font></td>');
            echo('<td align="left">');
            $sql_grade = "Select grade from Team_grade where current = 'Yes' and fix_cal_year = " . $current_year . " and season = '" . $current_season . "' order by grade";
            $result_grade = $dbcnx_client->query($sql_grade);
            while($build_grade = $result_grade->fetch_assoc()) 
            {
                echo('<input type="radio" id="' . $build_grade['grade'] . '" name="grade" value="' . $build_grade['grade'] . '">');
                echo('<label for="' . $build_grade['grade'] . '">&nbsp;' . $build_grade['grade'] . '</label><br>');
            }
            echo('</td>');
            echo('</tr>');
            echo('<tr>');
            echo('<tr>');
            echo('<td align="left">Select or Enter Club Name<font color="red">&nbsp;*</font></td>');
            echo("<td align='center'><select id='new_club' style='width:300px; height:25px;'>");
            echo("<option value=''>&nbsp;</option>");
            $sql_club = "Select ClubTitle from clubs where inactive = 1 Order By ClubTitle";
            $result_club = $dbcnx_client->query($sql_club);
            while($build_club = $result_club->fetch_assoc()) 
            {
            echo("<option value='" . $build_club['ClubTitle'] . "'>" . $build_club['ClubTitle'] . "</option>");
            }
            echo("</select></td>");
            echo('</tr>');
            echo('<tr>');
            //echo('<td align="left" class="ui-widget" id="new_team">Select or Enter Team Name<font color="red">&nbsp;*</font></td>');
            echo('<td align="left" class="ui-widget" id="new_team">Enter Team Name<font color="red">&nbsp;*</font></td>');
            echo('<td align="center"><input id="team_tags"  style="width:300px; height:25px;">');
            //echo('<div id="autocompleteAppendTeamToMe"></div>');
            echo('</td>');
            echo('</tr>');
            echo('<tr>');
            echo('<td colspan="2">Please provide a unique team name, not just your Club name e.g. Frankston 147</td>');
            echo('</tr>');
            //echo('<tr>');
            //echo('<td class="text-center" colspan=2><a class="btn btn-primary btn-xs" id="add_players">Add Players to Team</a></td>');
            //echo('</tr>');
            echo("<input type='hidden' id='team_id' name='team_id'>");
            echo("</table>");
        ?>
        </td>
    </tr>
</table>
<input type='hidden' id='team_id'>
<table class='table table-striped table-bordered dt-responsive display'>
    <tr>
        <td colspan=4 align=center><b>Players currently in this team (9 max)</b></td>
    </tr>
    <tr>
        <td colspan=4 align=center>&nbsp;</td>
    </tr>
    <tr>
        <!--<td align='center'>ID</td>-->
        <td align='center'>Name</td>
        <td align='center'>Captain</td>
        <td align='center'>Remove</td>
    </tr>
<?php
for($j = 0; $j < 9; $j++)
{
    echo("<tr>");
    echo("<input type='hidden' id='member_ID_". $j . "'>");
    echo("<input type='hidden' id='scrs_id_". $j . "'>");
    //echo("<td align='center'><input type='text' id='scrs_ID_". $j . "'>&nbsp;</td>");
    echo("<td align='center'><input type='text' id='name_". $j . "' readonly>&nbsp;</td>");
    echo("<td align='center'>");
    echo('<input type="checkbox" id="captain_' . $j . '" class="captain">');
    //echo('<input type="radio" name="captain" id="captain_' . $j . '">');
    echo("</td>");
    echo("<td align='center'>");
    echo('<input type="checkbox" id="delete_' . $j . '">');
    echo("</td>");
    echo("</tr>");
}
?>
    <!--<tr>
        <td class="text-center">&nbsp;</td>
        <td class="text-center"><a class="btn btn-primary btn-xs" id="un_select" style='width:130px'>Un-Select Captain</a></td>
        <td class="text-center">&nbsp;</td>
    </tr>-->
    <tr>
        <td class="text-center" colspan=3><a class="btn btn-primary btn-xs" id="add_players" style='width:250px'>Add Players to Team</a></td>
    </tr>
    <tr>
        <td class="text-center" colspan=3><a class="btn btn-primary btn-xs" id="remove" style='width:250px'>Remove Selected Players</a></td>
    </tr>
    <tr>
        <td class="text-center" colspan=3><a class="btn btn-primary btn-xs" id="add_to_team" style='width:250px'>Submit Team</a></td>
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
              <div class='text-center'><b>Players that previously played for this club.</b></div>
              <br>
              <div id='add'></div>
              <br>
              <div class='text-center'><a class='btn btn-primary btn-xs' id='populate_positions'>Add Selected Players to Team</a>
              </div>
              <br>
              <div class='text-center'><i>This action will clear any existing players.</i></div>
              <br>
              <hr>
              <div class='text-center ui-widget'>
              <label for='tags'>Enter Name in this field to add players from other Clubs or New players:&nbsp;&nbsp;</label>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Emergency Player Modal -->
<div class="modal fade" id="EmPlayer" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Player Name</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
          <tr>
            <td>Firstname:</td><td><input type='text' id='em_first' style='width:200px; text-transform: capitalize;' placeholder="Firstname"></td>
          <tr>
          </tr>
            <td>Lastname:</td><td><input type='text' id='em_last' style='width:200px; text-transform: capitalize;'></td>
          <tr>
          </tr>
            <td>Email Address:</td><td><input type='text' id='em_email' style='width:200px;'></td>
          </tr>
          </tr>
            <td>Mobile No.:</td><td><input type='text' id='em_mobile' style='width:200px;'></td>
          </tr>
          </tr>
            <td>Have they ever played VBSA Pennant before?</td><td><input type='checkbox' id='em_previous' style='width:200px;'></td>
          </tr>
          <tr>
            <td colspan=2><div id='add_previous'></div></td>
          </tr>
          </tr>
        </table>
        <div class='text-center'>All boxes should be filled.</div>
        <br>
        <div class='text-center'>You must now lodge an online VBSA Membership form for this new player.  See <a href='https://vbsa.org.au/Admin_DB_VBSA/membership_application.php' target="_blank">https://vbsa.org.au/Admin_DB_VBSA/membership_application.php.</a></div>
        <br>
        <br>
        <div class='text-center'><a class='btn btn-primary btn-xs' id='em_player'>Add to List</a></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</form>
<?php 
include('footer.php'); 
?>


