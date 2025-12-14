<script>
$(document).ready(function()
{
    $.fn.save_fixtures = function (form_no, action) {
        //alert("Save Fixtures");
        var team_grade_before = $('#team_grade_' + form_no).html();
        team_grade = $.escapeSelector(team_grade_before);
        var no_of_teams = $('#no_of_teams_' + form_no).html();
        var no_of_fixtures = (no_of_teams/2);
        var no_of_rounds = $('#no_of_rounds_' + form_no).html(); // rounds based on teams
        var dayplayed = $("#dayplayed_" + form_no).html();
        var comptype = $("#comptype_" + form_no).html();
        var grade = team_grade.substring(0,1);
        var scoredata = new Array;
        var scoredata_teams = new Array;
        var sortdata = new Array;
        var sortdata_index = new Array;
        var season = '<?= $season ?>';
        var year = '<?= $year?>';
        var round;
        //alert(no_of_rounds);
        for(x = 0; x < no_of_teams; x++)
        {
            sortdata_index[x] = $("#club_" + form_no + "_" + (x+1)).html() + ", " + $("#sort_" + form_no + "_" + (x+1) + "_id").html(); 
            sortdata.push(sortdata_index[x]);
        }
        sortdata = JSON.stringify(sortdata);
        for(i = 0; i < no_of_rounds; i++)
        {
            playing_date = $("#A_" + form_no + "_date_" + i).val();
            round = (i+1);
            for(j = 0; j < (no_of_teams/2); j++) 
            {
                //console.log("Field Name = " + $("#A_" + team_grade + "_home_" + round + "_" + (j+1)).val());
                scoredata_teams[i+j] = $("#A_" + team_grade + "_home_" + round + "_" + (j+1)).val() + ", " + $("#A_" + team_grade + "_away_" + round + "_" + (j+1)).val() + ", " + playing_date + ", " + round; 
                //console.log("SQL " + $("#A_" + team_grade + "_home_" + round + "_" + (j+1)).val() + ", " + $("#A_" + team_grade + "_away_" + round + "_" + (j+1)).val() + ", " + playing_date + ", " + round); 
                scoredata.push(scoredata_teams[i+j]);
            }
        }
        scoredata = JSON.stringify(scoredata);
        console.log(scoredata);
        $.ajax({
            url:"save_fixtures.php?Type=" + comptype + "&Grade=" + grade + "&TeamGrade=" + team_grade + "&FormNo=" + form_no + "&DayPlayed=" + dayplayed + "&Year=" + year + "&Season=" + season + "&SortData=" + sortdata + "&ScoreData=" + scoredata + "&Fixtures=" + no_of_fixtures + "&Rounds=" + no_of_rounds,
            method: 'GET',
            success : function(response)
            {
                if(action == 'Response')
                {
                    //alert(response);
                }
                window.location.href = '<?= $_SERVER['PHP_SELF'] ?>?DayPlayed=' + dayplayed + '&season=' + season;
            },
            error: function (request, error) 
            {
              alert("No data saved!");
            }
        });
    }


    $.fn.shuffle_fixtures = function (team_grades, action) {
        //alert("Shuffle Fixtures");
        var season = '<?= $season ?>';
        var year = '<?= $year?>';
        var dayplayed = 'Mon';
        var no_of_clashes = 0;
        var team_grades = ('BWS', "CWS", "PB(2)");
        //var grade = team_grades.split(", ");
        //for(i = 0; i < grade.length; i++)
        //{
            //if(grade != '')
            //{
                //alert(grade[i]);
            //}

            /*do 
            {
                $.fn.shuffle_fixtures('<?= $team_grades ?>', 'NoResponse');
                no_of_clashes = ($('#no_of_clashes').val());
            }
            while (no_of_clashes <= 1)
            */
            //while (no_of_clashes > 20)
            //{
                $.ajax({
                    url:"shuffle_fixtures.php?TeamGrade=" + team_grades + "&Year=" + year + "&Season=" + season + "&DayPlayed=" + dayplayed,
                    method: 'GET',
                    async: false,
                    success : function(response)
                    {
                        //no_of_clashes = response;
                        //console.log(no_of_clashes);
                        //if(action == 'Response')
                        //{
                            alert(response);
                        //}
                        //window.location.href = '<?= $_SERVER['PHP_SELF'] ?>?DayPlayed=' + dayplayed + '&season=' + season;
                    },
                    error: function (request, error) 
                    {
                      alert("No data saved!");
                    }
                });
            //}
            //form_no++;
        //}
        //window.location.href = '<?= $_SERVER['PHP_SELF'] ?>?DayPlayed=' + dayplayed + '&season=' + season;
        //}
        // random sort for grade 1
        // save fixtures for that grade
        // random sort for grade 2
        // save fixtures for that grade
        // random sort for grade 3
        // save fixtures for that grade
    }



});
</script>