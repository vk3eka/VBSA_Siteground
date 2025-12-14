<?php
include("connection.inc");
include("header.php"); 
include("php_functions.php"); 

// on page load.

if ((isset($_POST['Year']) and $_POST['Year'] <> '') and (isset($_POST['Season']) and $_POST['Season'] <> '')) 
{
  $season = $_POST['Season'];
  $year = $_POST['Year'];
}
elseif ((isset($_GET['Year']) and $_GET['Year'] <> '') and (isset($_GET['Season']) and $_GET['Season'] <> '')) 
{
  $season = $_GET['Season'];
  $year = $_GET['Year'];
  //SelectSeasonButton();
}
else
{
  $season = $_SESSION['season'];
  $year = $_SESSION['year'];
}
//echo($_GET['Season'] . ", " . $_GET['Year'] . "<br>");


if(isset($_POST['ButtonName']) && ($_POST['ButtonName'] == "SaveSettings")) 
{
    $settingdata = [];
    $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
    $settingdata = explode(":", $packeddata[0]);
    for($i = 0; $i < $_POST['Grades']; $i++)
    {
        if($settingdata[$i][7] == '')
        {
            $year = date("Y");
        }
        else
        {
            $year = $settingdata[$i][7];
        }
        $year = date("Y");
        $settingdata[$i] = explode(", ", $packeddata[$i]);
        $sql = "Update tbl_team_grade SET 
        grade = '" . $settingdata[$i][0] . "', " . "
        grade_name = '" . $settingdata[$i][1] . "', " . "
        season = '" . $settingdata[$i][2] . "', " . "
        type = '" . $settingdata[$i][3] . "', " . "
        RP = " . $settingdata[$i][4] . ", " . "
        min_breaks = " . $settingdata[$i][5] . ", " . "
        fix_upload = '" . $settingdata[$i][6] . "', " . "
        fix_cal_year = " . $year . ", " . "
        current = '" . $settingdata[$i][8] . "', " . "
        finals_teams = " . $settingdata[$i][9] . ", " . "
        dayplayed = '" . $settingdata[$i][10] . "', " . "
        no_of_matches = " . $settingdata[$i][11] . ", " . "
        no_of_rounds = " . $settingdata[$i][12] . ", " . "
        no_of_players = " . $settingdata[$i][13] . ", " . "
        games_round = " . $settingdata[$i][14] . " " . "
        Where id = '" . $settingdata[$i][15] . "'";
        //echo($sql . "<br>");
        $update = $dbcnx_client->query($sql);
        if(!$update )
        {
          die("Could not update grade settings data: " . mysqli_error($dbcnx_client));
        }
    }
    echo("<script type='text/javascript'>");
    echo("alert('Settings have been saved.');");
    echo("</script>");
}

if(isset($_POST['ButtonName']) && ($_POST['ButtonName'] == "SaveChanges")) {
    $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
    for ($i = 0; $i < count($packeddata); $i++) {
        $grade = explode(", ", $packeddata[$i]);
        $sql = "Update tbl_grade_settings Set changePW =  " . $player[1] . ", enable = " . $player[2] . " where RegistrationNo  = " . $player[0];
        $update = $dbcnx_client->query($sql);
    }
}

?>
<script type="text/javaScript">

function SelectSeasonButton() 
{
    season_year = document.getElementById('season').value.split(",");
    document.season.Season.value = season_year[1];
    document.season.Year.value = season_year[0];
    document.season.Select.value = 'true';
    document.season.submit();
}

function SaveSettings(no_of_grades) {
    var transferdata = {};
    for (var i = 0; i < no_of_grades; i++) { // get number of grades
       if (document.getElementById("current_" + i).checked === true) {
            current_chk = 'Yes';
        }
        else
        {
            current_chk = 'No';
        }
        transferdata[i] = 
        document.getElementById("grade_" + i ).value + ", " + 
        document.getElementById("grade_name_" + i ).value + ", " + 
        document.getElementById("season_" + i ).value + ", " + 
        document.getElementById("type_" + i ).value + ", " + 
        document.getElementById("rank_points_" + i ).value + ", " + 
        document.getElementById("min_break_points_" + i ).value + ", " + 
        document.getElementById("fix_upload_" + i ).value + ", " + 
        document.getElementById("year_" + i ).value + ", " + 
        current_chk + ", " + 
        document.getElementById("finals_" + i ).value + ", " + 
        document.getElementById("day_played_" + i ).value + ", " + 
        document.getElementById("matches_" + i ).value + ", " + 
        document.getElementById("rounds_" + i ).value + ", " + 
        document.getElementById("players_" + i ).value + ", " + 
        document.getElementById("games_round_" + i ).value + ", " + 
        document.getElementById("id_" + i ).value;
    }
    var data = JSON.stringify(transferdata);
    document.settings.PackedData.value = data;
    document.settings.Grades.value = no_of_grades;
    document.settings.ButtonName.value = "SaveSettings"; 
    document.settings.submit();
}
</script>
<script>
$(document).ready(function()
{

    $('#season').change(function(event){
        event.preventDefault(); 
        season_year = $('#season').val().split(",");

        dataParams = { 
            Season: season_year[1],
            Year: season_year[0],
            Select: 'true'
        }
        //console.log("Data " + dataParams[0]);
        $.ajax({
            url: '<?= $url ?>/grade_settings.php',
            dataType: 'text',
            type: 'POST',
            data: dataParams,
            success: function(data)
            {                
                $('form#seasonID').submit();
            }       
        });
       
    });


    // add grade to the database
    $('#add_grade').click(function(event){
        event.preventDefault();
        $('#addGrade').modal('show');
    });

    $('#new_grade').click(function(event){
        event.preventDefault();
        var response;
        var no_of_grades = $('#mod_grades').val();
        for(i = 0; i < no_of_grades; i++)
        {
            if($("input[id='mod_add_" + i  + "']").is(":checked"))
            {
                var team_grade = $('#mod_grade_' + i).html();
                var grade_name = $('#mod_grade_name_' + i).html();
                var grade_type = $('#mod_type_' + i).html();
                var day_played = $('#mod_day_' + i).html();
                var season = '<?= $season ?>';
                var year = <?= $year ?>;
                $.ajax({
                    url:"<?= $url ?>/add_grade.php?grade=" + team_grade + "&name=" + grade_name + "&type=" + grade_type + "&dayplayed=" + day_played + "&season=" + season + "&year=" + year,
                    method: 'POST',
                    success:function(response)
                    {
                        if(response == "Added")
                        {
                            success = "True";
                        }
                    },
                });
            }
        }
        $('#addGrade').modal('hide');
        alert("Grade/s Added.");
        window.location.href = 'grade_settings.php';
    });

    $('#add_data').click(function(event){
        event.preventDefault();
        var season = '<?= $season ?>';
        var year = <?= $year ?>;
        //alert(season + ", " +  year);
        $.ajax({
            url:"<?= $url ?>/add_data.php?year=" + year + "&season=" + season,
            method: 'POST',
            success:function(response)
            {
                //window.location.href = 'grade_settings.php?Season=' + season +'&Year=' + year;
                window.location.href = 'grade_settings.php';
            },
        });
    });

});

</script>
<center>
<form name="season" id="seasonID" method="post" action="grade_settings.php">
<input type='hidden' name='Year' />
<input type='hidden' name='Season' />
<input type='hidden' name='Select' />
<table class='table table-striped table-bordered dt-responsive nowrap display' width='90%'>
    <tr>
        <td colspan=4 align=center><h1>Grade Settings</h1></td>
    </tr>
 <tr> 
 <td colspan='4'>&nbsp;</td> 
 </tr> 
 <tr> 
 <!--<td align='center' valign='top'><b>Select Season:&nbsp;&nbsp; <select id='season' > -->
    <td align='center' valign='top'><b>Select Season:&nbsp;&nbsp; <select id='season' onchange="SelectSeasonButton()">
<?php
if(isset($season)) 
{
     echo("<option value='' selected='selected'>" . $year . ", " . $season . "</option>"); 
}
else
{
     echo("<option value='' selected='selected'></option>");
}
echo("<option value=''>---------</option>");
echo("<option value='2023, S1'>2023, S1</option>");
echo("<option value='2023, S2'>2023, S2</option>");
echo("<option value='2024, S1'>2024, S1</option>");
echo("<option value='2024, S2'>2024, S2</option>");
echo("<option value='2025, S1'>2025, S1</option>");
echo("<option value='2025, S2'>2025, S2</option>");
?>
</select></b> 
</td> 
</tr> 
<tr> 
<td colspan='4'>&nbsp;</td> 
</tr> 
</table> 
</form>
<form name="settings" id="settings" method="post" action="grade_settings.php">
<input type="hidden" name="PackedData">
<input type="hidden" name="Grades">
<input type="hidden" name="ButtonName">
<table class='table table-striped table-bordered dt-responsive nowrap display' width='90%'>
<?php
if($_POST['Select'] == 'true')
{
    $sql_list = "Select * from tbl_team_grade where season = '" . trim($season) . "' and fix_cal_year = " . $year . " Order By grade";
    $result_list = $dbcnx_client->query($sql_list) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
    $num_rows = $result_list->num_rows;
    if($num_rows == 0)
    {
        echo("<tr><td colspan=15 align=center>");
        echo("<a class='btn btn-primary btn-xs' id='add_data' style='width:200px'>No grades to display, add to list.</a>");
        echo("</td></tr>");
    }
    echo("<tr>"); 
    echo("<td colspan=13 align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>"); 
    echo("<td align='center'>Grade Code</td>");
    echo("<td align='center'>Grade Name</td>");
    echo("<td align='center'>Season</td>");
    echo("<td align='center'>Type</td>");
    echo("<td align='center' style='width : 60px;'>Finals Teams</td>");
    echo("<td align='center' style='width : 60px;'>Day Played</td>");
    echo("<td align='center' style='width : 60px;'>Alloc. Rank Points</td>");
    echo("<td align='center' style='width : 60px;'>Min. Break Points</td>");
    echo("<td align='center' style='width : 60px;'>No. of Matches</td>");
    echo("<td align='center' style='width : 60px;'>No. of Rounds (inc Finals)</td>");
    echo("<td align='center' style='width : 60px;'>No. of Players</td>");
    echo("<td align='center' style='width : 60px;'>Games per Match</td>");
    echo("<td align='center' style='width : 60px;'>Current</td>");
    echo("</tr>");
    $i = 0;
    while($build_data = $result_list->fetch_assoc()) 
    {
        echo("<input type='hidden' id='id_" . $i . "' value=" . $build_data['id'] . ">");
        echo("<tr>"); 
        echo("<td align='center'><input type='text' id='grade_". $i . "' value='" . $build_data['grade'] . "' style='width : 100px;'></td>");
        echo("<td align='center'><input type='text' id='grade_name_". $i . "' value='" . $build_data['grade_name'] . "'style='width : 200px;'></td>");
        echo("<input type='hidden' id='year_" . $i . "' value=" . $build_data['fix_cal_year'] . ">");
        echo("<td align='center'><input type='text' id='season_". $i . "' value='" . $build_data['season'] . "'style='width : 50px;'></td>");
        echo("<td align='center'><input type='text' id='type_". $i . "' value='" . $build_data['type'] . "'style='width : 100px;'></td>");
        echo("<input type='hidden' id='fix_upload_" . $i . "'>");
        echo("<td align='center'><input type='text' id='finals_" . $i . "' value='" . $build_data['finals_teams'] . "'style='width : 30px;'></td>");
        echo("<td align='center'><select id='day_played_". $i . "'>");
        echo("<option value='" . $build_data['dayplayed'] . "' selected='selected'>" . $build_data['dayplayed'] . "</option>");
        echo("<option value='Mon'>Mon</option>");
        echo("<option value='Wed'>Wed</option>");
        echo("</select>");
        echo("</td>");
        echo("<td align='center'><input type='text' id='rank_points_". $i . "' value='" . $build_data['RP'] . "'style='width : 30px;'></td>");
        echo("<td align='center'><input type='text' id='min_break_points_". $i . "' value='" . $build_data['min_breaks'] . "'style='width : 30px;'></td>");
        echo("<td align='center'><input type='text' id='matches_". $i . "' value='" . $build_data['no_of_matches'] . "'style='width : 30px;'></td>");
        echo("<td align='center'><input type='text' id='rounds_". $i . "' value='" . $build_data['no_of_rounds'] . "'style='width : 30px;'></td>");
        echo("<td align='center'><input type='text' id='players_". $i . "' value='" . $build_data['no_of_players'] . "'style='width : 30px;'></td>");
        echo("<td align='center'><input type='text' id='games_round_". $i . "' value='" . $build_data['games_round'] . "'style='width : 30px;'></td>");
        if ($build_data['current'] == "Yes") {                  
            echo("<td align='center'><input type='checkbox' name='current' id='current_". $i . "' checked></td>");
        }
        else
        {
            echo("<td align='center'><input type='checkbox' name='current' id='current_". $i . "'></td>");
        }
        $i++;
        echo("</tr>");
    } 
    echo("<tr>");
    echo("<td colspan=15 align=center>");
    echo("<a class='btn btn-primary btn-xs' id='submit_button' href='javascript:;' onclick='SaveSettings(" . $i . ");' style='width:200px'>Save Grade Settings</a>");
    echo("</td></tr>");
    echo("<tr><td colspan=15 align=center>");
    echo("<a class='btn btn-primary btn-xs' id='add_grade' style='width:200px'>Add New Grade</a>");
    echo("</td></tr>");
    echo("<tr><td colspan=15 align=center>");
    echo("<a class='btn btn-primary btn-xs' id='add_data' style='width:200px'>Test</a>");
    echo("</td></tr>");

}
?>
</table>
</form>
</center>  

<!-- Add Grade Modal -->
<div class="modal fade" id="addGrade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Grade</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form name="add_grade" method="post" action="grade_settings.php">
        <table class='table table-striped table-bordered dt-responsive nowrap display' width='80%'>
            <?php
            $sql = "Select * FROM Team_grade Order by grade_name";
            $result = $dbcnx_client->query($sql);
            echo("<tr>"); 
            echo("<td colspan=8 align=center><b>Existing Grades</b></td>");
            echo("</tr>");
            echo("<tr>"); 
            echo("<td colspan=8 align=center>&nbsp;</td>");
            echo("</tr>");
            echo("<tr>"); 
            echo("<td align='center'>Grade</td>");
            echo("<td align='center'>Grade Name</td>");
            echo("<td align='center'>Season</td>");
            echo("<td align='center'>Type</td>");
            echo("<td align='center'>Day Played</td>");
            echo("<td align='center'>Add Grade</td>");
            echo("</tr>");
            $j = 0;
            while ($build_data = $result->fetch_assoc()) {
                echo("<input type='hidden' id='mod_year_" . $j . "' value=" . $_POST['Year'] . " />");
                echo("<tr>"); 
                echo("<td align='left' id='mod_grade_" . $j . "'>" . $build_data['grade'] . "</td>");
                echo("<td align='left' id='mod_grade_name_" . $j . "'>" . $build_data['grade_name'] . "</td>");
                echo("<td align='center' id='mod_season_" . $j . "'>" . $build_data['season'] . "</td>");
                echo("<td align='left' id='mod_type_" . $j . "'>" . $build_data['type'] . "</td>");
                
                echo("<td align='center' id='mod_day_" . $j . "'>" . $build_data['dayplayed'] . "</td>");
                echo("<td align='center'><input type='checkbox' name='mod_add_". $j . "' id='mod_add_" . $j . "'></td>");
                echo("</tr>");
                $j++;
            } 
            echo("<input type='hidden' name='mod_grades' id='mod_grades' value=" . ($j) . " />");
            ?>
        </table>
            <div class='text-center'><a class='btn btn-primary btn-xs' id='new_grade'>Add Selected Grade to List</a></div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
include("footer.php"); 
?>
