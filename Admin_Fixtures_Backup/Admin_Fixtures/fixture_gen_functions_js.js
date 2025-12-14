// Add Dates

function addWeek(date, days, round) 
{
    var date = new Date(date);
    date.setDate(date.getDate() + days*round);
    var day = date.getDate();
    var month = date.getMonth() + 1;
    if(day < 10)
    {
        day = "0" + day;
    }
    if(month < 10)
    {
        month = "0" + month;
    }
    var year = date.getFullYear();
    var start_date = year + '-' + month + '-' + day;

/*

$current_date = $grade_start;
$days = 7;
for($i = 0; $i < $total_rounds; $i++)
//for($i = 0; $i < $no_of_rounds; $i++)
{
    if($i == 0)
    {
        $sql_update_fixtures_r1 = "Update tbl_create_fixtures Set date = '" . $grade_start . "' where year = " . $current_year . " and team_grade = '" . $team_grade . "' and season = '" . $season . "' and round = " . ($i+1);
        $update = mysql_query($sql_update_fixtures_r1, $connvbsa) or die(mysql_error());
    }
    else
    {
        $round = ($i+1);
        $current_date = date('Y-m-d', strtotime($current_date . ' + ' . $days . ' days'));
        if(in_array($current_date, $date_array))
        {
            $current_date = date('Y-m-d', strtotime($current_date . ' + ' . $days . ' days'));
        }
        $sql_update_fixtures = "Update tbl_create_fixtures Set date = '" . $current_date . "' where year = " . $current_year . " and team_grade = '" . $team_grade . "' and season = '" . $season . "' and round = " . $round;
        $update = mysql_query($sql_update_fixtures, $connvbsa) or die(mysql_error());
    }
}

*/

    return start_date;
}

// fixture generation code (draggable)
function main(fixtures, team_grade, form_no, year, season, start_date) {
    let teamArray = fixtures
      .split(", ")                         // Split on commas
      .map(t => t.trim())                  // Trim whitespace from each entry
      .filter(t => t.length > 0);          // Remove any empty strings
      //.sort();                           // Sort alphabetically

    // Fisher-Yates shuffle
    /*
    for (let i = teamArray.length - 1; i > 0; i--) {
      let j = Math.floor(Math.random() * (i + 1));
      [teamArray[i], teamArray[j]] = [teamArray[j], teamArray[i]];
    }  
    */
    home_teams_array = show_fixtures(teamArray, team_grade, form_no, year, season, start_date);
    return home_teams_array;
}

function nums(n) {
    let ns = [];
    for (let i = 1; i <= n; i++) {
        ns.push(i.toString());
    }
    return ns;
}

function team_name(num, names) {
    let i = num - 1;
    return names[i] && names[i].trim().length > 0 ? names[i].trim() : num;
}

function flip(match) {
    let components = match.split(" v ");
    return components[1] + " v " + components[0];
}

// added for 12 teams
function generateFixtures(teams, names) {
    let ghost = false;
    if (teams % 2 === 1) {
    teams++;
    ghost = true;
    names.push("BYE");
    }

    const totalRounds = teams - 1;
    const matchesPerRound = teams / 2;
    let rounds = Array.from({ length: totalRounds }, () => []);

    for (let round = 0; round < totalRounds; round++) {
    for (let match = 0; match < matchesPerRound; match++) {
      let home = (round + match) % (teams - 1);
      let away = (teams - 1 - match + round) % (teams - 1);
      if (match === 0) away = teams - 1;
      rounds[round][match] = `${names[home]} v ${names[away]}`;
    }
    }

    // Interleave
    let interleaved = [];
    let evn = 0;
    let odd = teams / 2;
    for (let i = 0; i < rounds.length; i++) {
    interleaved[i] = (i % 2 === 0) ? rounds[evn++] : rounds[odd++];
    }

    // Flip first match of odd rounds
    interleaved = interleaved.map((round, idx) => {
    if (idx % 2 === 1) {
      round[0] = flip(round[0]);
    }
    return round;
    });

    return interleaved;
}

//function flip(match) {
//  const [home, away] = match.split(" v ");
//  return `${away} v ${home}`;
//}

/*
function displayFixtures(allRounds) {
    let text = '';
    form_no = 1;
    start_date = '2025-07-30';
    text += ("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
    text += ("<tbody class='row_position_10'>");
    text += ("<tr><td colspan=3 align='center'>(Algorithm)</td></tr>");

  $.each(allRounds, function(i, round) {
    const roundNum = i + 1;
    const homeOnly = (roundNum > 11);
    text += ("<tr><td>&nbsp;</td></tr>");
    text += ("<td colspan=3 align='center'><b>Round " + (i+1)  + "</b></td></tr>");
    text += ("<tr><td align='right'><b>Date</b></td>");
    text += ("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + i + "' style='width:100px' value=" + addWeek(start_date, 7, (i+1)) + "></td></tr>");
    //x = 0;
    document.getElementById(team_grade + "_fix_" + (x+1)).innerHTML = team_grade;
    document.getElementById(team_grade + "_fix_" + (i+1) + "_pos_" + (x+1)).innerHTML = home;

    text += ("<tr><td>&nbsp;</td></tr>");
    text += `<tr><td colspan="3" align="center"><b>Round ${roundNum} ${homeOnly ? '(Home Only)' : ''}</b></td></tr>`;
    text += `<tr><td align="right"><b>Date</b></td><td colspan="2"><input type="text" id="date_${roundNum}" style="width:100px"></td></tr>`;

    $.each(round, function(x, match) {
      const [home, away] = match.split(" v ");
      text += `
        <tr>
          <td align="center"><input type="text" value="${home}" style="width:150px"></td>
          <td align="center">v</td>
          <td align="center"><input type="text" value="${away}" style="width:150px"></td>
        </tr>
      `;
    });

    text += `<tr><td colspan="3">&nbsp;</td></tr>`;
  });

  text += "</tbody></table>";
  //$('#fixture_container').append(text);
}
*/

function show_fixtures(names, team_grade, form_no, year, season, start_date)
{
    let teams = names.length;
    //let team_text = "Start Teams " + teams + ", Grade " + team_grade + "\n";
    let text = '';
    let home_teams_array = [];
    let obj = {};
/*
    if(teams == 4) // needs checking with a four team fixture.
    {
        // If odd number of teams add a "ghost".
        // should already be added from fixture model
        let ghost = false;
        if (teams % 2 === 1) {
            names.push("Ghost");
            teams++;
            ghost = true;
        }
    
        let totalRounds = teams - 1;
        let matchesPerRound = teams / 2;
        let rounds = [];
        for (let i = 0; i < totalRounds; i++) {
            rounds[i] = [];
        }

        for (let round = 0; round < totalRounds; round++) {
            for (let match = 0; match < matchesPerRound; match++) {
                let home = (round + match) % (teams - 1);
                let away = (teams - 1 - match + round) % (teams - 1);
                if (match === 0) away = teams - 1;
                rounds[round][match] = team_name(home + 1, names) + " v " + team_name(away + 1, names);
            }
        }

        // Interleave rounds
        let interleaved = new Array(totalRounds).fill(null).map(() => []);
        let evn = 0, odd = matchesPerRound;
        for (let i = 0; i < (rounds.length); i++) {
            interleaved[i] = (i % 2 === 0) ? rounds[evn++] : rounds[odd++];
        }

        rounds = interleaved;

        // Flip home/away for last team in odd rounds
        for (let round = 0; round < (rounds.length); round++) {
            if (round % 2 === 1) {
                //console.log("Error " + round);
                rounds[round][0] = flip(rounds[round][0]);
            }
        }
        
        // Display the fixtures (for 4 teams)

        // set 1
        text += ("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        text += ("<tbody class='row_position_10'>");
        text += ("<tr><td colspan=3 align='center'>(Algorithm)</td></tr>");
        for (let i = 0; i < rounds.length; i++) {
            text += ("<tr><td>&nbsp;</td></tr>");
            text += ("<td colspan=3 align='center'><b>Round " + (i+1)  + "</b></td></tr>");
            text += ("<tr><td align='right'><b>Date</b></td>");
            text += ("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + i + "' style='width:100px' value=" + addWeek(start_date, 7, (i+1)) + "></td></tr>");
            x = 0;
            rounds[i].forEach((r, x) => {
                let [home, away] = r.split(" v ");
                document.getElementById(team_grade + "_fix_" + (x+1)).innerHTML = team_grade;
                document.getElementById(team_grade + "_fix_" + (i+1) + "_pos_" + (x+1)).innerHTML = home;
                obj = {
                    home_team: {
                        round: (i+1),
                        team: home,
                        grade: team_grade
                    },
                    away_team: {
                        round: (i+1),
                        team: away,
                        grade: team_grade
                    }
                };
                home_teams_array.push(obj);
                text += ("<tr>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (i+1) + "_" + (x+1) + "' value='" + home + "' style='width:200px'></td>");
                text += ("<td align='center'>v</td>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (i+1) + "_" + (x+1) + "' value='" + away + "' style='width:200px'></td>");
                text += ("</tr>");
                x++;
            });
        }
        // Second half is mirror of first half (set 2)
        round_counter = rounds.length + 1;
        for (let b = 0; b < rounds.length; b++) {
            echo("<tr><td>&nbsp;</td></tr>");
            echo("<td colspan=3 align='center'><b>Round " + round_counter  + "</b></td>");
            echo("<tr><td align='right'><b>Date</b></td>");
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + (round_counter-1) + "' style='width:100px' value=" + addWeek(start_date, 7, (i+1)) + "></td></tr>");
            round_counter += 1;
            y = 0; 
            rounds[i].forEach((r, y) => {   
                let [home, away] = r.split(" v ");
                document.getElementById(team_grade + "_fix_" + (round_counter-1) + "_pos_" + (y+1)).innerHTML = away;
                obj = {
                    home_team: {
                        round: (round_counter-1),
                        team: home,
                        grade: team_grade
                    },
                    away_team: {
                        round: (round_counter-1),
                        team: away,
                        grade: team_grade
                    }
                };
                home_teams_array.push(obj);
                echo("<tr>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (round_counter-1) + "_" + (y+1) + "' value='" + away + "' style='width:200px'></td>");
                echo("<td align='center'>v</td>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" + $team_grade + "_away_" + (round_counter-1) + "_" + (y+1) + "' value='" + home + "' style='width:200px'></td>");
                echo("</tr>");
                y++;
            });
        }

        // Third half is mirror of first half (set 3)
        for (let i = 0; i < rounds.length; i++) {
            text += ("<tr><td>&nbsp;</td></tr>");
            text += ("<td colspan=3 align='center'><b>Round " + (i+7)  + "</b></td></tr>");
            text += ("<tr><td align='right'><b>Date</b></td>");
            text += ("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + (i+6) + "' style='width:100px' value=" + addWeek(start_date, 7, (i+6)) + "></td></tr>");
            x = 0;
            rounds[i].forEach((r, x) => {
                let [home, away] = r.split(" v ");
                document.getElementById(team_grade + "_fix_" + (x+1)).innerHTML = team_grade;
                document.getElementById(team_grade + "_fix_" + (i+1) + "_pos_" + (x+1)).innerHTML = home;
                obj = {
                    home_team: {
                        round: (i + 6),
                        team: home,
                        grade: team_grade
                    },
                    away_team: {
                        round: (i + 6),
                        team: away,
                        grade: team_grade
                    }
                };
                home_teams_array.push(obj);
                text += ("<tr>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (i+7) + "_" + (x+1) + "' value='" + home + "' style='width:200px'></td>");
                text += ("<td align='center'>v</td>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (i+7) + "_" + (x+1) + "' value='" + away + "' style='width:200px'></td>");
                text += ("</tr>");
                x++;
            });
        }
        
        // Second half is mirror of second half (set 4)
        round_counter = rounds.length + 1;
        for (let b = 0; b < rounds.length; b++) {
            echo("<tr><td>&nbsp;</td></tr>");
            echo("<td colspan=3 align='center'><b>Round " + (round_counter+6)  + "</b></td>");
            echo("<tr><td align='right'><b>Date</b></td>");
            echo("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + (round_counter+5) + "' style='width:100px' value=" + addWeek(start_date, 7, (i+1)) + "></td></tr>");
            round_counter += 1;
            y = 0;  
            rounds[i].forEach((r, y) => {  
                let [home, away] = r.split(" v ");
                document.getElementById(team_grade + "_fix_" + (round_counter+5) + "_pos_" + (y+1)).innerHTML = away;
                obj = {
                    home_team: {
                        round: (round_counter+5),
                        team: home,
                        grade: team_grade
                    },
                    away_team: {
                        round: (round_counter+5),
                        team: away,
                        grade: team_grade
                    }
                };
                home_teams_array.push(obj);
                echo("<tr>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (round_counter-1) + "_" + (y+1) + "' value='" + away + "' style='width:200px'></td>");
                echo("<td align='center'>v</td>");
                echo("<td align='center'><input class='float-child' type='text' id='A_" + $team_grade + "_away_" + (round_counter-1) + "_" + (y+1) + "' value='" + home + "' style='width:200px'></td>");
                echo("</tr>");
                y++;
            });
        }

        // Second half is mirror of first half (set 5)
        for (let i = 0; i < rounds.length; i++) {
            text += ("<tr><td>&nbsp;</td></tr>");
            text += ("<td colspan=3 align='center'><b>Round " + (i+13)  + "</b></td></tr>");
            text += ("<tr><td align='right'><b>Date</b></td>");
            text += ("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + (i+12) + "' style='width:100px' value=" + addWeek(start_date, 7, (i+12)) + "></td></tr>");
            x = 0;
            rounds[i].forEach((r, x) => {
                let [home, away] = r.split(" v ");
                document.getElementById(team_grade + "_fix_" + (x+1)).innerHTML = team_grade;
                document.getElementById(team_grade + "_fix_" + (i+13) + "_pos_" + (x+1)).innerHTML = home;
                obj = {
                    home_team: {
                        round: (i + 12),
                        team: home,
                        grade: team_grade
                    },
                    away_team: {
                        round: (i + 12),
                        team: away,
                        grade: team_grade
                    }
                };
                home_teams_array.push(obj);
                text += ("<tr>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (i+13) + "_" + (x+1) + "' value='" + home + "' style='width:200px'></td>");
                text += ("<td align='center'>v</td>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (i+13) + "_" + (x+1) + "' value='" + away + "' style='width:200px'></td>");
                text += ("</tr>");
                x++;
            });
        }
        text += ("</tbody>");
        text += ("</table>");
        // end 4 teams
    }
    else if(teams == 6)
*/  
    if(teams == 6)
    {
        // If odd number of teams add a "ghost".
        // should already be added from fixture model
        let ghost = false;
        if (teams % 2 === 1) {
            names.push("Ghost");
            teams++;
            ghost = true;
        }
    
        let totalRounds = teams - 1;
        let matchesPerRound = teams / 2;
        let rounds = [];
        for (let i = 0; i < totalRounds; i++) {
            rounds[i] = [];
        }

        for (let round = 0; round < totalRounds; round++) {
            for (let match = 0; match < matchesPerRound; match++) {
                let home = (round + match) % (teams - 1);
                let away = (teams - 1 - match + round) % (teams - 1);
                if (match === 0) away = teams - 1;
                rounds[round][match] = team_name(home + 1, names) + " v " + team_name(away + 1, names);
            }
        }

        // Interleave rounds
        let interleaved = new Array(totalRounds).fill(null).map(() => []);
        let evn = 0, odd = (teams/2);
        //let evn = 0, odd = matchesPerRound;
        for (let i = 0; i < (rounds.length); i++) {
            interleaved[i] = (i % 2 === 0) ? rounds[evn++] : rounds[odd++];
        }

        rounds = interleaved;

        // Flip home/away for last team in odd rounds
        for (let round = 0; round < (rounds.length); round++) {
            if (round % 2 === 1) {
                //console.log("Error " + round);
                rounds[round][0] = flip(rounds[round][0]);
            }
        }
/*
        // Final pass to ensure 'Bye' is always the away team
        for (let round = 0; round < totalRounds; round++) {
            for (let match = 0; match < matchesPerRound; match++) {
                const teams = rounds[round][match].split(' v ');
                const homeTeam = teams[0];
                const awayTeam = teams[1];

                if (homeTeam === 'Bye') {
                    // If the home team is 'Bye', swap it with the away team
                    rounds[round][match] = `${awayTeam} v ${homeTeam}`;
                }
            }
        }
*/
        
        // Display the fixtures (for 6 teams)
        // Set 1
        text += ("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        text += ("<tbody class='row_position_10'>");
        text += ("<tr><td colspan=3 align='center'>(Algorithm)</td></tr>");
        text += ("<tr><td colspan=3 align='center'>Dates allocated on Save</td></tr>");
        
        for (let i = 0; i < rounds.length; i++) {
            text += ("<tr><td>&nbsp;</td></tr>");
            text += ("<td colspan=3 align='center'><b>Round " + (i+1)  + " Set 1</b></td></tr>");
            text += ("<tr><td align='right'><b>Date</b></td>");
            //text += ("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + i + "' style='width:100px' value=" + addWeek(start_date, 7, (i)) + "></td></tr>");
            text += ("<input type='hidden' id='A_" + form_no + "_date_" + i + "' value=" + addWeek(start_date, 7, (i)) + ">");
            x = 0;
            rounds[i].forEach((r, x) => {
            //$.each(rounds[i], function(x, r) {
            let [home, away] = r.split(" v ");
            //console.log("Home " + home + ", Away " + away + ", Round " + (i+1) + ", Rounds[i] " + rounds[i]);
                document.getElementById(team_grade + "_fix_" + (x+1)).innerHTML = team_grade;
                document.getElementById(team_grade + "_fix_" + (i+1) + "_pos_" + (x+1)).innerHTML = home;
                obj = {
                    home_team: {
                        round: (i + 1),
                        team: home,
                        grade: team_grade
                    },
                    away_team: {
                        round: (i + 1),
                        team: away,
                        grade: team_grade
                    }
                };
                home_teams_array.push(obj);
                text += ("<tr>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (i+1) + "_" + (x+1) + "' value='" + home + "' style='width:200px'></td>");
                text += ("<td align='center'>v</td>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (i+1) + "_" + (x+1) + "' value='" + away + "' style='width:200px'></td>");
                text += ("</tr>");
                x++;
            });
        }
        // Second half is mirror of first half
        let round_counter = rounds.length + 1;
        for (let b = 0; b < rounds.length; b++) {
            text += ("<tr><td>&nbsp;</td></tr>");
            text += ("<td colspan=3 align='center'><b>Round " + round_counter  + " Set 2</b></td>");
            //text += ("<tr><td align='right'><b>Date</b></td>");
            //text += ("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + (round_counter-1) + "' style='width:100px' value=" + addWeek(start_date, 7, (round_counter-1)) + "></td></tr>");
            text += ("<input type='hidden' id='A_" + form_no + "_date_" + (round_counter-1) + "' value=" + addWeek(start_date, 7, (round_counter-1)) + ">");
            round_counter += 1;
            y = 0;    
            rounds[b].forEach((r, y) => {
            let [home, away] = r.split(" v ");
                /*if (home === 'Bye') {
                    // If the home team is 'Bye', swap it with the away team
                    //home = away;
                    away = home;
                }*/
                document.getElementById(team_grade + "_fix_" + (y+1)).innerHTML = team_grade;
                document.getElementById(team_grade + "_fix_" + (round_counter - 1) + "_pos_" + (y+1)).innerHTML = away;
                obj = {
                    home_team: {
                        round: (round_counter-1),
                        team: away,
                        grade: team_grade
                    },
                    away_team: {
                        round: (round_counter-1),
                        team: home,
                        grade: team_grade
                    }
                };
                home_teams_array.push(obj);
                text += ("<tr>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (round_counter-1) + "_" + (y+1) + "' value='" + away + "' style='width:200px'></td>");
                text += ("<td align='center'>v</td>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (round_counter-1) + "_" + (y+1) + "' value='" + home + "' style='width:200px'></td>");
                text += ("</tr>");
                y++;
            });
        }
        // Third half is same as first half
        for (let round_counter = 0; round_counter < rounds.length; round_counter++) {
            text += ("<tr><td>&nbsp;</td></tr>");
            text += ("<td colspan=3 align='center'><b>Round " + (round_counter + 11)  + " Set 3</b></td></tr>");
            //text += ("<tr><td align='right'><b>Date</b></td>");
            //text += ("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + (round_counter + 11) + "' style='width:100px' value=" + addWeek(start_date, 7, (round_counter + 10)) + "></td></tr>");
            text += ("<input type='hidden' id='A_" + form_no + "_date_" + (round_counter + 11) + "' value=" + addWeek(start_date, 7, (round_counter + 10)) + ">");
            x = 0;
            rounds[round_counter].forEach((r, x) => {
            let [home, away] = r.split(" v ");
                document.getElementById(team_grade + "_fix_" + (x+1)).innerHTML = team_grade;
                document.getElementById(team_grade + "_fix_" + (round_counter + 11) + "_pos_" + (x+1)).innerHTML = home;
                obj = {
                    home_team: {
                        round: (round_counter + 11),
                        team: home,
                        grade: team_grade
                    },
                    away_team: {
                        round: (round_counter + 11),
                        team: away,
                        grade: team_grade
                    }
                };
                home_teams_array.push(obj);
                text += ("<tr>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (round_counter+11) + "_" + (x+1) + "' value='" + home + "' style='width:200px'></td>");
                text += ("<td align='center'>v</td>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (round_counter+11) + "_" + (x+1) + "' value='" + away + "' style='width:200px'></td>");
                text += ("</tr>");
                x++;
            });
        }
        text += ("</tbody>");
        text += ("</table>");
        // end 6 teams
    }
    else if((teams == 8) || (teams == 10))
    //if((teams == 8) || (teams == 10))
    {
        //console.log("Teams " + teams);
        // If odd number of teams add a "ghost".
        // should already be added from fixture model
        let ghost = false;
        if (teams % 2 === 1) {
            names.push("Ghost");
            teams++;
            ghost = true;
        }
    
        let totalRounds = teams - 1;
        let matchesPerRound = teams / 2;
        let rounds = [];
        for (let i = 0; i < totalRounds; i++) {
            rounds[i] = [];
        }

        for (let round = 0; round < totalRounds; round++) {
            for (let match = 0; match < matchesPerRound; match++) {
                let home = (round + match) % (teams - 1);
                let away = (teams - 1 - match + round) % (teams - 1);
                if (match === 0) away = teams - 1;
                rounds[round][match] = team_name(home + 1, names) + " v " + team_name(away + 1, names);
            }
        }

        // Interleave rounds
        let interleaved = new Array(totalRounds).fill(null).map(() => []);
        let evn = 0, odd = matchesPerRound;
        for (let i = 0; i < (rounds.length); i++) {
            interleaved[i] = (i % 2 === 0) ? rounds[evn++] : rounds[odd++];
        }

        rounds = interleaved;

        // Flip home/away for last team in odd rounds
        for (let round = 0; round < (rounds.length); round++) {
            if (round % 2 === 1) {
                //console.log("Error " + round);
                rounds[round][0] = flip(rounds[round][0]);
            }
        }

        // Final pass to ensure 'Bye' is always the away team
        for (let round = 0; round < totalRounds; round++) {
            for (let match = 0; match < matchesPerRound; match++) {
                const teams = rounds[round][match].split(' v ');
                const homeTeam = teams[0];
                const awayTeam = teams[1];

                if (homeTeam === 'Bye') {
                    // If the home team is 'Bye', swap it with the away team
                    rounds[round][match] = `${awayTeam} v ${homeTeam}`;
                }
            }
        }
        
        //console.log("Teams " + teams + ", " + rounds);
        // Display the fixtures (for 8/10 teams)
        text += ("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        text += ("<tbody class='row_position_10'>");
        text += ("<tr><td colspan=3 align='center'>(Algorithm " + team_grade + ")</td></tr>");
        text += ("<tr><td colspan=3 align='center'>Dates allocated on save</td></tr>");
        for (let i = 0; i < rounds.length; i++) {
            text += ("<tr><td>&nbsp;</td></tr>");
            text += ("<td colspan=3 align='center'><b>Round " + (i+1)  + " Set 1</b></td></tr>");
            //text += ("<tr><td align='right'><b>Date</b></td>");
            //text += ("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + i + "' style='width:100px' value=" + addWeek(start_date, 7, (i)) + "></td></tr>");
            text += ("<input type='hidden' id='A_" + form_no + "_date_" + i + "' value=" + addWeek(start_date, 7, (i)) + ">");
            x = 0;
            //console.log(rounds[i]);
            rounds[i].forEach((r, x) => {
                let [home, away] = r.split(" v ");
                //console.log(home + " v " + away + ", round " + (i+1));
                document.getElementById(team_grade + "_fix_" + (x+1)).innerHTML = team_grade;
                document.getElementById(team_grade + "_fix_" + (i+1) + "_pos_" + (x+1)).innerHTML = home;
                obj = {
                    home_team: {
                        round: (i+1),
                        team: home,
                        grade: team_grade
                    },
                    away_team: {
                        round: (i+1),
                        team: away,
                        grade: team_grade
                    }
                };
                home_teams_array.push(obj);
                //console.log("Round " + (i+1));
                //console.log("Array " + JSON.stringify(home_teams_array, null, 2));
                text += ("<tr>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (i+1) + "_" + (x+1) + "' value='" + home + "' style='width:200px'></td>");
                text += ("<td align='center'>v</td>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (i+1) + "_" + (x+1) + "' value='" + away + "' style='width:200px'></td>");
                text += ("</tr>");
                x++;
            });
        }

        // Second half is mirror of first half
        round_counter = rounds.length + 1;
        for (let b = 0; b < rounds.length; b++) {
            text += ("<tr><td>&nbsp;</td></tr>");
            text += ("<td colspan=3 align='center'><b>Round " + round_counter  + " Set 2</b></td>");
            //text += ("<tr><td align='right'><b>Date</b></td>");
            //text += ("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + (round_counter-1) + "' style='width:100px' value=" + addWeek(start_date, 7, (round_counter-1)) + "></td></tr>");
            text += ("<input type='hidden' id='A_" + form_no + "_date_" + (round_counter-1) + "' value=" + addWeek(start_date, 7, (round_counter-1)) + ">");
            round_counter += 1;
            y = 0;    
            rounds[b].forEach((r, y) => {
                let [home, away] = r.split(" v ");
                document.getElementById(team_grade + "_fix_" + (round_counter-1) + "_pos_" + (y+1)).innerHTML = away;
                
                obj = {
                    home_team: {
                        round: (round_counter-1),
                        team: away,
                        grade: team_grade
                    },
                    away_team: {
                        round: (round_counter-1),
                        team: home,
                        grade: team_grade
                    }
                };
                home_teams_array.push(obj);
                //console.log("Round " + (round_counter-1));
                //console.log("Array " + JSON.stringify(home_teams_array, null, 2));
                text += ("<tr>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (round_counter-1) + "_" + (y+1) + "' value='" + away + "' style='width:200px'></td>");
                text += ("<td align='center'>v</td>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (round_counter-1) + "_" + (y+1) + "' value='" + home + "' style='width:200px'></td>");
                text += ("</tr>");
                y++;
            });
        }
        text += ("</tbody>");
        text += ("</table>");
    }
    else if(teams == 12)
    {
        const baseRounds = generateFixtures(teams, names);
        const rounds1to11 = baseRounds;                 // full single round-robin
        const rounds12to15 = baseRounds.slice(0, 4);    // pick first 7 rounds to repeat
        const rounds = rounds1to11.concat(rounds12to15);

        // set 1
        text += ("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        text += ("<tbody class='row_position_10'>");
        text += ("<tr><td colspan=3 align='center'>(Algorithm " + team_grade + ")</td></tr>");
        text += ("<tr><td colspan=3 align='center'>Dates allocated on save</td></tr>");
        round_counter = rounds.length + 1;
        for (let i = 0; i < rounds.length; i++) {
            /*
            if(i > 10)
            {
                home_tag = '(Home Only)';
            }
            else
            {
                home_tag = '';
            }
            */
            home_tag = '';
            text += ("<tr><td>&nbsp;</td></tr>");
            text += ("<td colspan=3 align='center'><b>Round " + (i+1)  + " " + home_tag + "</b></td></tr>");
            //text += ("<tr><td align='right'><b>Date</b></td>");
            //text += ("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + i + "' style='width:100px' value=" + addWeek(start_date, 7, (i+1)) + "></td></tr>");
            text += ("<input type='hidden' id='A_" + form_no + "_date_" + i + "' value=" + addWeek(start_date, 7, (i+1)) + ">");
            x = 0;  
            rounds[i].forEach((r, x) => {
                let [home, away] = r.split(" v ");
                if(i > 10)
                {
                    document.getElementById(team_grade + "_fix_" + (x+1)).innerHTML = team_grade;
                    document.getElementById(team_grade + "_fix_" + (i+1) + "_pos_" + (x+1)).innerHTML = away;
                    obj = {
                        home_team: {
                            round: (i+1),
                            team: away,
                            grade: team_grade
                        },
                        away_team: {
                            round: (i+1),
                            team: home,
                            grade: team_grade
                        }
                    };
                    home_teams_array.push(obj);
                    text += ("<tr>");
                    text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (i+1) + "_" + (x+1) + "' value='" + away + "' style='width:200px'></td>");
                    text += ("<td align='center'>v</td>");
                    text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (i+1) + "_" + (x+1) + "' value='" + home + "' style='width:200px'></td>");
                    text += ("</tr>");
                }
                else
                {
                    document.getElementById(team_grade + "_fix_" + (x+1)).innerHTML = team_grade;
                    document.getElementById(team_grade + "_fix_" + (i+1) + "_pos_" + (x+1)).innerHTML = away;
                    obj = {
                        home_team: {
                            round: (i+1),
                            team: home,
                            grade: team_grade
                        },
                        away_team: {
                            round: (i+1),
                            team: away,
                            grade: team_grade
                        }
                    };
                    home_teams_array.push(obj);
                    text += ("<tr>");
                    text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (i+1) + "_" + (x+1) + "' value='" + home + "' style='width:200px'></td>");
                    text += ("<td align='center'>v</td>");
                    text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (i+1) + "_" + (x+1) + "' value='" + away + "' style='width:200px'></td>");
                    text += ("</tr>");
                }
                x++;
            });
        }

/*        
        12 teams, first 6 play home and away, 2nd 6 do the same, lastly 12 teams play home only
        
        names_set_1 = [];
        names_set_2 = [];
        names_set_3 = [];
        for (i = 0; i < (names.length/2); i++)
        {
            names_set_1.push(names[i]); 
        }
        for (i = (names.length/2); i < names.length; i++)
        {
            names_set_2.push(names[i]); 
        }
        for (i = 0; i < names.length; i++)
        {
            names_set_3.push(names[i]);  
        }

        teams = names_set_1.length; // first set of 6
        
        // If odd number of teams add a "ghost".
        // should already be added from fixture model
        let ghost = false;
        if (teams % 2 === 1) {
            names.push("Ghost");
            teams++;
            ghost = true;
        }

        // Generate the fixtures using the cyclic algorithm.
        let totalRounds = teams - 1;
        let matchesPerRound = teams / 2;
        let rounds = [];
        for (let i = 0; i < totalRounds; i++) {
            rounds[i] = [];
        }

        for (let round = 0; round < totalRounds; round++) {
            for (let match = 0; match < matchesPerRound; match++) {
                let home = (round + match) % (teams - 1);
                let away = (teams - 1 - match + round) % (teams - 1);
                if (match === 0) away = teams - 1;
                rounds[round][match] = team_name(home + 1, names) + " v " + team_name(away + 1, names);
            }
        }

        // Interleave rounds
        let interleaved = new Array(totalRounds).fill(null).map(() => []);
        let evn = 0, odd = matchesPerRound;
        for (let i = 0; i < (rounds.length); i++) {
            interleaved[i] = (i % 2 === 0) ? rounds[evn++] : rounds[odd++];
        }

        rounds = interleaved;

        // Flip home/away for last team in odd rounds
        for (let round = 0; round < (rounds.length); round++) {
            if (round % 2 === 1) {
                //console.log("Error " + round);
                rounds[round][0] = flip(rounds[round][0]);
            }
        }
    
        // Display the fixtures (for 12 teams)
        text += ("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        text += ("<tbody class='row_position_10'>");
        text += ("<tr><td colspan=3 align='center'>(Algorithm " + team_grade + ")</td></tr>");
        text += ("<tr><td colspan=3 align='center'>First Set of 6 Teams</td></tr>");
        // (Set 1 First Group)
        for (let i = 0; i < rounds.length; i++) {
            text += ("<tr><td>&nbsp;</td></tr>");
            text += ("<td colspan=3 align='center'><b>Round " + (i+1)  + " Set 1</b></td></tr>");
            text += ("<tr><td align='right'><b>Date</b></td>");
            text += ("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + i + "' style='width:100px' value=" + addWeek(start_date, 7, (i)) + "></td></tr>");
            x = 0;
            rounds[i].forEach((r, x) => {
                let [home, away] = r.split(" v ");
                document.getElementById(team_grade + "_fix_" + (x+1)).innerHTML = team_grade;
                document.getElementById(team_grade + "_fix_" + (i+1) + "_pos_" + (x+1)).innerHTML = home;
                obj = {
                    home_team: {
                        round: (i + 1),
                        team: home,
                        grade: team_grade
                    },
                    away_team: {
                        round: (i + 1),
                        team: away,
                        grade: team_grade
                    }
                };
                home_teams_array.push(obj);
                text += ("<tr>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (i+1) + "_" + (x+1) + "' value='" + home + "' style='width:200px'></td>");
                text += ("<td align='center'>v</td>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (i+1) + "_" + (x+1) + "' value='" + away + "' style='width:200px'></td>");
                text += ("</tr>");
                x++;
            });
        }

        // (Set 2 First Group)
        round_counter = rounds.length + 1;
        for (let i = 0; i < rounds.length; i++) {
            text += ("<tr><td>&nbsp;</td></tr>");
            text += ("<td colspan=3 align='center'><b>Round " + round_counter  + " Set 2</b></td>");
            text += ("<tr><td align='right'><b>Date</b></td>");
            text += ("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + (round_counter-1) + "' style='width:100px' value=" + addWeek(start_date, 7, (round_counter)) + "></td></tr>");
            round_counter += 1;
            x = 0;    
            rounds[i].forEach((r, x) => {
                let [home, away] = r.split(" v ");
                document.getElementById(team_grade + "_fix_" + (round_counter-1) + "_pos_" + (x+1)).innerHTML = away;
                obj = {
                    home_team: {
                        round: (round_counter-1),
                        team: home,
                        grade: team_grade
                    },
                    away_team: {
                        round: (round_counter-1),
                        team: away,
                        grade: team_grade
                    }
                };
                home_teams_array.push(obj);
                text += ("<tr>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (round_counter-1) + "_" + (x+1) + "' value='" + away + "' style='width:200px'></td>");
                text += ("<td align='center'>v</td>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (round_counter-1) + "_" + (x+1) + "' value='" + home + "' style='width:200px'></td>");
                text += ("</tr>");
                x++;
            });
        }
        
        teams = names_set_2.length;

        // If odd number of teams add a "ghost".
        // should already be added from fixture model
        let ghost = false;
        if (teams % 2 === 1) {
            names.push("Ghost");
            teams++;
            ghost = true;
        }

        // Generate the fixtures using the cyclic algorithm.
        let totalRounds = teams - 1;
        let matchesPerRound = teams / 2;
        let rounds = [];
        for (let i = 0; i < totalRounds; i++) {
            rounds[i] = [];
        }

        for (let round = 0; round < totalRounds; round++) {
            for (let match = 0; match < matchesPerRound; match++) {
                let home = (round + match) % (teams - 1);
                let away = (teams - 1 - match + round) % (teams - 1);
                if (match === 0) away = teams - 1;
                rounds[round][match] = team_name(home + 1, names) + " v " + team_name(away + 1, names);
            }
        }

        // Interleave rounds
        let interleaved = new Array(totalRounds).fill(null).map(() => []);
        let evn = 0, odd = matchesPerRound;
        for (let i = 0; i < (rounds.length); i++) {
            interleaved[i] = (i % 2 === 0) ? rounds[evn++] : rounds[odd++];
        }

        rounds = interleaved;

        // Flip home/away for last team in odd rounds
        for (let round = 0; round < (rounds.length); round++) {
            if (round % 2 === 1) {
                //console.log("Error " + round);
                rounds[round][0] = flip(rounds[round][0]);
            }
        }
    
        // (Set 1 Second Group)
        //text += ("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        //text += ("<tbody class='row_position_10'>");
        //text += ("<tr><td colspan=3 align='center'>(Algorithm " + team_grade + ")</td></tr>");
        text += ("<tr><td colspan=3 align='center'>Second Set of 6 Teams</td></tr>");
        // (Set 1 First Group)
        for (let i = 0; i < rounds.length; i++) {
            text += ("<tr><td>&nbsp;</td></tr>");
            text += ("<td colspan=3 align='center'><b>Round " + (i+1)  + " Set 1</b></td></tr>");
            text += ("<tr><td align='right'><b>Date</b></td>");
            text += ("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + i + "' style='width:100px' value=" + addWeek(start_date, 7, (i+1)) + "></td></tr>");
            x = 0;
            rounds[i].forEach((r, x) => {
            let [home, away] = r.split(" v ");
                document.getElementById(team_grade + "_fix_" + (i+1) + "_pos_" + (x+1)).innerHTML = away;
                obj = {
                    home_team: {
                        round: (i+1),
                        team: home,
                        grade: team_grade
                    },
                    away_team: {
                        round: (i+1),
                        team: away,
                        grade: team_grade
                    }
                };
                home_teams_array.push(obj);
                text += ("<tr>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (i+1) + "_" + (x+1) + "' value='" + home + "' style='width:200px'></td>");
                text += ("<td align='center'>v</td>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (i+1) + "_" + (x+1) + "' value='" + away + "' style='width:200px'></td>");
                text += ("</tr>");
                x++;
            });
        }

        round_counter = rounds.length + 1;
        text += ("<tr><td colspan=3 align='center'>Second Set of 6 Teams</td></tr>");
        for (let i = 0; i < rounds.length; i++) {
            text += ("<tr><td>&nbsp;</td></tr>");
            text += ("<td colspan=3 align='center'><b>Round " + (i+1)  + "</b></td></tr>");
            text += ("<tr><td align='right'><b>Date</b></td>");
            text += ("<td colspan=2 class='text-left'><input type='text' id='B_" + form_no + "_date_" + (i) + "' style='width:100px' value=" + addWeek(start_date, 7, (i+1)) + "></td></tr>");
            x = 3;
            rounds[i].forEach((r, x) => {
            let [home, away] = r.split(" v ");
                document.getElementById(team_grade + "_fix_" + (round_counter-1) + "_pos_" + (x+1)).innerHTML = away;
                obj = {
                    home_team: {
                        round: (round_counter-1),
                        team: home,
                        grade: team_grade
                    },
                    away_team: {
                        round: (round_counter-1),
                        team: away,
                        grade: team_grade
                    }
                };
                home_teams_array.push(obj);
                text += ("<tr>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (i+1) + "_" + (x+1) + "' value='" + home + "' style='width:200px'></td>");
                text += ("<td align='center'>v</td>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (i+1) + "_" + (x+1) + "' value='" + away + "' style='width:200px'></td>");
                text += ("</tr>");
                x++;
            });
        }
        
        // (Set 2 Second Group)
        round_counter = rounds.length + 1;
        for (let i = 0; i < rounds.length; i++) {
            text += ("<tr><td>&nbsp;</td></tr>");
            text += ("<td colspan=3 align='center'><b>Round " + round_counter  + " Set 2</b></td>");
            text += ("<tr><td align='right'><b>Date</b></td>");
            text += ("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + (round_counter-1) + "' style='width:100px' value=" + addWeek(start_date, 7, (round_counter)) + "></td></tr>");
            round_counter += 1;
            x = 0;    
            rounds[i].forEach((r, x) => {
                let [home, away] = r.split(" v ");
                document.getElementById(team_grade + "_fix_" + (round_counter-1) + "_pos_" + (x+1)).innerHTML = away;
                obj = {
                    home_team: {
                        round: (round_counter-1),
                        team: home,
                        grade: team_grade
                    },
                    away_team: {
                        round: (round_counter-1),
                        team: away,
                        grade: team_grade
                    }
                };
                home_teams_array.push(obj);
                text += ("<tr>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (round_counter-1) + "_" + (x+1) + "' value='" + away + "' style='width:200px'></td>");
                text += ("<td align='center'>v</td>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (round_counter-1) + "_" + (x+1) + "' value='" + home + "' style='width:200px'></td>");
                text += ("</tr>");
                x++;
            });
        }

        // Number of rounds
        rounds = 6;

        // Initialize fixtures array
        fixtures = [];

        // Generate fixtures for 6 rounds
        roundFixtures = [];

        // Determine which group plays at home in this round
        homeGroup = names_set_2;
        awayGroup = names_set_1;
        x = 1;

        //const homeGroup = ["Team A", "Team B", "Team C"];
        //const awayGroup = ["Team D", "Team E", "Team F"];

        const roundMap = {
            1: 1, 2: 2, 3: 3, 4: 4, 5: 5, 6: 6,
            7: 2, 8: 3, 9: 4, 10: 5, 11: 6, 12: 1,
            13: 3, 14: 4, 15: 5, 16: 6, 17: 1, 18: 2,
            19: 4, 20: 5, 21: 6, 22: 1, 23: 2, 24: 3,
            25: 5, 26: 6, 27: 1, 28: 2, 29: 3, 30: 4,
            31: 6, 32: 1, 33: 2, 34: 3, 35: 4, 36: 5
        };

        let x = 1;
        let roundFixtures = [];

        homeGroup.forEach(homeTeam => {
            awayGroup.forEach(awayTeam => {
                let round = roundMap[x] ?? 1;

                let matchup = (round % 2 === 0)
                    ? `${homeTeam} vs ${awayTeam}`
                    : `${awayTeam} vs ${homeTeam}`;

                let [team1, team2] = matchup.split(" vs ");

                roundFixtures.push(`${round}, ${team1}, ${team2}`);
                x++;
            });
        });

        //console.log(roundFixtures);


/*
        // Generate fixtures for this round
        foreach ($homeGroup as $homeTeam) 
        {
            $round = 1;
            foreach ($awayGroup as $awayTeam) 
            {
                switch($x) // sort fixtures so a team doesn't play more than one team per night 
                {
                  case 1:
                    $round = 1;
                    break;
                  case 2:
                    $round = 2;
                    break;
                  case 3:
                    $round = 3;
                    break;
                  case 4:
                    $round = 4;
                    break;
                  case 5:
                    $round = 5;
                    break;
                  case 6:
                    $round = 6;
                    break;
                  case 7:
                    $round = 2;
                    break;
                  case 8:
                    $round = 3;
                    break;
                  case 9:
                    $round = 4;
                    break;
                  case 10:
                    $round = 5;
                    break;
                  case 11:
                    $round = 6;
                    break;
                  case 12:
                    $round = 1;
                    break;
                  case 13:
                    $round = 3;
                    break;
                  case 14:
                    $round = 4;
                    break;
                  case 15:
                    $round = 5;
                    break;
                  case 16:
                    $round = 6;
                    break;
                  case 17:
                    $round = 1;
                    break;
                  case 18:
                    $round = 2;
                    break;
                  case 19:
                    $round = 4;
                    break;
                  case 20:
                    $round = 5;
                    break;
                  case 21:
                    $round = 6;
                    break;
                  case 22:
                    $round = 1;
                    break;
                  case 23:
                    $round = 2;
                    break;
                  case 24:
                    $round = 3;
                    break;
                  case 25:
                    $round = 5;
                    break;
                  case 26:
                    $round = 6;
                    break;
                  case 27:
                    $round = 1;
                    break;
                  case 28:
                    $round = 2;
                    break;
                  case 29:
                    $round = 3;
                    break;
                  case 30:
                    $round = 4;
                    break;
                  case 31:
                    $round = 6;
                    break;
                  case 32:
                    $round = 1;
                    break;
                  case 33:
                    $round = 2;
                    break;
                  case 34:
                    $round = 3;
                    break;
                  case 35:
                    $round = 4;
                    break;
                  case 36:
                    $round = 5;
                    break;
                }
                if ($round %2 == 0) { // round is even number
                    // Home vs Away
                    $matchup = "$homeTeam vs $awayTeam";
                } else {
                    // Away vs Home (reverse fixtures)
                    $matchup = "$awayTeam vs $homeTeam";
                }
                $team_names = explode(" vs ", $matchup);
                $roundFixtures[$x] = $round . ", " . $team_names[0] . ", " . $team_names[1];
                $round++;
                $x++;
            }
        }

        sort($roundFixtures); // sort into round order

        text += ("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        text += ("<tbody class='row_position_10'>");
        text += ("<tr><td colspan=3 align='center'>Combined Set of both sets of Teams</td></tr>");
        for (let i = 0; i < 6; i++) // last 6 rounds
        {
            text += ("<tr><td>&nbsp;</td></tr>");
            text += ("<td colspan=3 align='center'><b>Round " + (i+11) + "</b></td></tr>");
            text += ("<tr><td align='right'><b>Date</b></td>");
            text += ("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + (i+10) + "' style='width:100px' value=" + addWeek($start_date, 7, (i+11)) + "></td></tr>");
            text += ("<tr>");
            y = 0;
            rounds[i].forEach((r, y) => {
                let [home, away] = r.split(" v ");
                {
                    document.getElementById(team_grade + "_fix_" + (round_counter-1) + "_pos_" + (y+1)).innerHTML = away;
                    obj = {
                        home_team: {
                            round: (round_counter-1),
                            team: home,
                            grade: team_grade
                        },
                        away_team: {
                            round: (round_counter-1),
                            team: away,
                            grade: team_grade
                        }
                    };
                    home_teams_array.push(obj);
                    text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (i+11) + "_" + (y+1) + "' value='" + home + "' style='width:200px'></td>");
                    text += ("<td align='center'>v</td>");
                    text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (i+11) + "_" + (y+1) + "' value='" + (away) + "' style='width:200px'></td>");
                    text += ("</tr>");
                    y++;
                }   
            });
        }
        text += ("</tbody>");
        text += ("</table>");
*/  }
    else if(teams == 14)
    {
        
        // If odd number of teams add a "ghost".
        // should already be added from fixture model
        let ghost = false;
        if (teams % 2 === 1) {
            names.push("Ghost");
            teams++;
            ghost = true;
        }
    
        let totalRounds = (teams-1);
        let matchesPerRound = teams / 2;
        let rounds = [];
        for (let i = 0; i < totalRounds; i++) {
            rounds[i] = [];
        }

        for (let round = 0; round < totalRounds; round++) {
            for (let match = 0; match < matchesPerRound; match++) {
                let home = (round + match) % (teams - 1);
                let away = (teams - 1 - match + round) % (teams - 1);
                if (match === 0) away = teams - 1;
                rounds[round][match] = team_name(home + 1, names) + " v " + team_name(away + 1, names);
            }
        }

        // Interleave rounds
        let interleaved = new Array(totalRounds).fill(null).map(() => []);
        let evn = 0, odd = matchesPerRound;
        for (let i = 0; i < (rounds.length); i++) {
            interleaved[i] = (i % 2 === 0) ? rounds[evn++] : rounds[odd++];
        }

        rounds = interleaved;

        // Flip home/away for last team in odd rounds
        for (let round = 0; round < (rounds.length); round++) {
            if (round % 2 === 1) {
                //console.log("Error " + round);
                rounds[round][0] = flip(rounds[round][0]);
            }
        }
        
        // Final pass to ensure 'Bye' is always the away team
        for (let round = 0; round < totalRounds; round++) {
            for (let match = 0; match < matchesPerRound; match++) {
                const teams = rounds[round][match].split(' v ');
                const homeTeam = teams[0];
                const awayTeam = teams[1];

                if (homeTeam === 'Bye') {
                    // If the home team is 'Bye', swap it with the away team
                    rounds[round][match] = `${awayTeam} v ${homeTeam}`;
                }
            }
        }

        // Display the fixtures (for 14 teams)
        text += ("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        text += ("<tbody class='row_position_10'>");
        text += ("<tr><td colspan=3 align='center'>(Algorithm " + team_grade + ")</td></tr>");
        text += ("<tr><td colspan=3 align='center'>Dates allocated on save</td></tr>");

        // (Set 1 First Group)
        for (let i = 0; i < rounds.length; i++) {
            text += ("<tr><td>&nbsp;</td></tr>");
            text += ("<td colspan=3 align='center'><b>Round " + (i+1)  + "</b></td></tr>");
            //text += ("<tr><td align='right'><b>Date</b></td>");
            //text += ("<td colspan=2 class='text-left'><input type='text' id='A_" + form_no + "_date_" + i + "' style='width:100px' value=" + addWeek(start_date, 7, (i)) + "></td></tr>");
            text += ("<input type='hidden' id='A_" + form_no + "_date_" + i + "' value=" + addWeek(start_date, 7, (i)) + ">");
            x = 0;
            rounds[i].forEach((r, x) => {
                let [home, away] = r.split(" v ");
                document.getElementById(team_grade + "_fix_" + (x+1)).innerHTML = team_grade;
                document.getElementById(team_grade + "_fix_" + (i+1) + "_pos_" + (x+1)).innerHTML = home;
                obj = {
                home_team: {
                        round: (i+1),
                        team: home,
                        grade: team_grade
                    },
                away_team: {
                        round: (i+1),
                        team: away,
                        grade: team_grade
                    }
                };
                home_teams_array.push(obj);
                text += ("<tr>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_home_" + (i+1) + "_" + (x+1) + "' value='" + home + "' style='width:200px'></td>");
                text += ("<td align='center'>v</td>");
                text += ("<td align='center'><input class='float-child' type='text' id='A_" + team_grade + "_away_" + (i+1) + "_" + (x+1) + "' value='" + away + "' style='width:200px'></td>");
                text += ("</tr>");
                x++;
            });
        }
        text += ("</tbody>");
        text += ("</table>");
    }
    $('#output_' + form_no).append(text);
    return home_teams_array;
}

