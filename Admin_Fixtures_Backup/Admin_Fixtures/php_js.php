<script>
function uniqueMultidimArray(array, key) {
    const tempArray = [];
    const keyArray = new Set();

    array.forEach(val => {
        if (!keyArray.has(val[key])) {
            keyArray.add(val[key]);
            tempArray.push(val);
        }
    });
    return tempArray;
}

function CreateTeamTables(data)
{
    const pages = uniqueMultidimArray(data.grades, 'name');

    pages.forEach(grade => {
        const teamGrade = grade.name;
        let fixtures = '';
        const filtered = data.teams.filter(r => r.grade === teamGrade);
        let comptype = '';

        filtered.forEach(fix => {
            comptype = fix.type;
        });

        console.log(`<div id='page${form_no}'>`);
        console.log(`<table class='table table-striped table-bordered dt-responsive nowrap display' border='1'>
            <tr>
                <td align='center'>${teamGrade}</td>
                <td align='center' id='comptype_${form_no}'>${comptype}</td>
            </tr>
            <tr>
                <td>`);

        console.log(`<table class="table table-striped table-bordered dt-responsive display text-center" class="col-6">
            <thead>`);
        console.log(`<th align="center">Position ID</th>`);
        console.log(`<th align="center">Team ID</th>`);
        console.log(`<th align="center">Club ID</th>
              <th align="center">Tables</th>
              <th align="center">Club Name</th>
              <th align="center">Team Name</th>
              <th align="center">Home Games</th>
            </thead>
            <tbody class="row_position_${form_no}">`);

        let x = 0;
        filtered.forEach(fix => {
            x++;
            fixtures += `${fix.name}, `;
            const teamName = fix.name;
            const teamId = fix.id;
            const clubName = fix.club;
            const clubTables = data.clubs.filter(d => d.name === clubName);
            let clubId = '';
            let clubTablesValue = '';

            clubTables.forEach(tables => {
                clubTablesValue = tables.tables;
                clubId = tables.id;
            });

            console.log(`
              <tr draggable="true" ondragstart="start()" ondragover="dragover()" data-index='${x}' id='${x}'> 
                <td align="center" id="sort_${form_no}_${x}_id">${x}</td>
                <td align="center" id="${form_no}_${x}_id">${teamId}</td>
                <input type="hidden" id="sort_form" value='${form_no}'>
                <td align="center" id="club_${form_no}_${x}_id">${clubId}</td>
                <td align="center">${clubTablesValue}</td>
                <td align="left" id="club_name_${form_no}_${x}">${clubName}</td>
                <td align="left" id="club_${form_no}_${x}">${teamName}</td>
                <td align="center" id="games_${form_no}_${x}">5</td>
              </tr>
            `);
        });

        const noOfTeams = x;
        console.log("<tr><td>&nbsp;</td></tr>");
        console.log("<tr>");
        let startDate = '';
        const noOfRounds = data.rounds.filter(p => p.grade === teamGrade);
        let roundCount = 0;

        noOfRounds.forEach(count => {
            roundCount = count.number;
            startDate = count.date;
        });

        let totalRounds = 0;
        if (comptype === 'Billiards') {
            totalRounds = (roundCount + 3);
        } else if (comptype === 'Snooker') {
            totalRounds = (roundCount + 2);
        }

        let dates = '';
        data.non_dates.forEach(date => {
            dates += `${date.date}\n`;
        });

        document.write('<td colspan="7" align="center">');
        document.write('<select name="sort_order" id="sort_order">');   
        // if(!isset($_POST['Sortby']))
        // {
            document.write('<option value="fix_sort" selected>Sort Table By:</option>');
        // }
        document.write('<option value="fix_sort">Position ID (Descending</option>' +
                       '<option value="team_id_dec">Team ID (Descending)</option>' +
                       '<option value="team_id_asc">Team ID (Ascending)</option>' +
                       '<option value="team_name_dec">Team Name (Descending)</option>' +
                       '<option value="team_name_asc">Team Name (Ascending)</option>' +
                       '<option value="rand">Shuffle</option>' +
                       '</select>');
        document.write('</td>');
        document.write("</tr>");
        document.write("<tr><td colspan=2>&nbsp;</td></tr>");
        document.write("<tr><td colspan=2>&nbsp;</td></tr>");
        document.write("<tr><td colspan=2>&nbsp;</td></tr>");
        document.write("</tbody>");
        document.write("</table>");
        document.write("</td>");
        document.write("<td>");
        document.write('<table class="table table-striped table-bordered dt-responsive display text-center">' +
                       '<thead>' +
                       '<th align="center">Analysis Data</th>' +
                       '<th align="center" id="team_grade_' + form_no + '">' + team_grade + '</th>' +
                       '</thead>' +
                       '<tbody>' +
                       '<tr>' +
                       '<td align="left">No of Teams</td>' +
                       '<td align="center" id="no_of_teams_' + form_no + '">' + no_of_teams + '</td>' +
                       '</tr>' +
                       '<tr>' +
                       '<td align="left">No of Rounds</td>' +
                       '<td align="center" id="no_of_rounds_' + form_no + '">' + round_count + '</td>' +
                       '</tr>' +
                       '<tr>' +
                       '<td align="left">Rounds (inc Finals)</td>' +
                       '<td align="center" id="finals_rounds_' + form_no + '">' + total_rounds + '</td>' +
                       '</tr>' +
                       '<tr>' +
                       '<td align="left">Start Date</td>' +
                       '<td align="center"><input type="text" name="startdate" id="startdate_' + form_no + '" value="' + start_date + '" style="width:100px"></td>' +
                       '</tr>' +
                       '<tr>' +
                       '<td align="left">Day Played</td>' +
                       '<td align="center" id="dayplayed_' + form_no + '">' + dayplayed + '</td>' +
                       '</tr>' +
                       '<tr>' +
                       '<td align="left" valign="top">Non available dates</td>' +
                       '<td align="center" rowspan="' + num_club + '"><textarea cols="10" rows="10">' + dates + '</textarea></td>' +
                       '</tr>');
        document.write("<tr>");
        document.write("<td align='center' class='greenbg' colspan='2'><button type='button' style='color:red;' class='btn btn-primary savebutton' data-id='" + form_no + "'>Save Fixtures for " + team_grade + "</button></td>");
        document.write("</tr>");
        document.write('</tbody>');
        document.write("</table>");
        document.write("</td>");
        document.write("</tr>");
        document.write("<tr>");
        document.write("<td align='center' colspan='2' class='greenbg' style='height: 20px'><a href='create_fixtures_pdf.php?Year=" + year + "&Season=" + season + "&Team_Grade=" + team_grade + "&DayPlayed=" + dayplayed + "&Rounds=" + total_rounds + "'>Generate Fixtures PDF for " + team_grade + "</a></td>");
        document.write("</tr>");
        document.write("</table>");

        fixtures = fixtures.substring(0, fixtures.length - 1);
        main(fixtures, team_grade, form_no, year, season, start_date); 

        // all_six_teams = JSON.parse(all_six_teams);
        console.log(all_six_teams);

        document.write("</div>");
        form_no++;
    }
}

</script>