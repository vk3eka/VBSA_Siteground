/*
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

function CreateTeamTables(data, form_no, dayplayed, year, season)
{
    const pages = uniqueMultidimArray(data.grades, 'name');
    console.log("pages " + pages);
    output = '';
    pages.forEach(grade => {
        const teamGrade = grade.name;
        let fixtures = '';
        const filtered = data.teams.filter(r => r.grade === teamGrade);
        let comptype = '';
        filtered.forEach(fix => {
            comptype = fix.type;
        });

        output += ("<div id='page" + form_no + ">");
        output += ("<table class='table table-striped table-bordered dt-responsive nowrap display' border='1'");
        output += ("<tbody class='row_position_10'");
       
        output += ('<tr><td colspan="3" align="center">(Algorithm)</td></tr>');
        output += ('<tr>');
        output += ('<td align="center">' + teamGrade + '</td>');
        output += ('<td align="center" id="' + comptype + '_' + form_no + '">' + comptype + '</td>');
        output += ('</tr>');
        output += ('<tr>');
        output += ('<td>');

        output += ('<table class="table table-striped table-bordered dt-responsive display text-center" class="col-6">');
        output += ('<thead>');
        output += ('<th align="center">Position ID</th>');
        output += ('<th align="center">Team ID</th>');
        output += ('<th align="center">Club ID</th>');
        output += ('<th align="center">Tables</th>');
        output += ('<th align="center">Club Name</th>');
        output += ('<th align="center">Team Name</th>');
        output += ('<th align="center">Home Games</th>');
        output += ('</thead>');
        output += ('<tbody class="row_position_${form_no}">');

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

            output += ('<tr draggable="true" ondragstart="start()" ondragover="dragover()" data-index=' + x + ' id=' + x + '>'); 
            output += ('<td align="center" id="sort_' + form_no + '_' + x + '_id">' + x + '</td>');
            output += ('<td align="center" id="' + form_no + '_' + x + '_id">' + teamId + '</td>');
            output += ('<input type="hidden" id="sort_form" value=' + form_no + '>');
            output += ('<td align="center" id="club_' + form_no + '_' + x + '_id">' + clubId + '</td>');
            output += ('<td align="center">' + clubTablesValue + '</td>');
            output += ('<td align="left" id="club_name_' + form_no + '_' + x + '">' + clubName + '</td>');
            output += ('<td align="left" id="club_' + form_no + '_' + x + '">' + teamName + '</td>');
            output += ('<td align="center" id="games_' + form_no + '_' + x + '">5</td>');
            output += ('</tr>');
        });

        const noOfTeams = x;
        output += ('<tr><td>&nbsp;</td></tr>');
        output += ('<tr>');
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

        let num_club = 10;
        output += ('<td colspan="7" align="center">');
        output += ('<select name="sort_order" id="sort_order">');   
        output += ('<option value="fix_sort" selected>Sort Table By:</option>');
        output += ('<option value="fix_sort">Position ID (Descending</option>' +
                       '<option value="team_id_dec">Team ID (Descending)</option>' +
                       '<option value="team_id_asc">Team ID (Ascending)</option>' +
                       '<option value="team_name_dec">Team Name (Descending)</option>' +
                       '<option value="team_name_asc">Team Name (Ascending)</option>' +
                       '<option value="rand">Shuffle</option>' +
                       '</select>');
        output += ('</td>');
        output += ("</tr>");
        output += ("<tr><td colspan=2>&nbsp;</td></tr>");
        output += ("<tr><td colspan=2>&nbsp;</td></tr>");
        output += ("<tr><td colspan=2>&nbsp;</td></tr>");
        output += ("</tbody>");
        output += ("</table>");
        output += ("</td>");
        output += ("<td>");
        output += ('<table class="table table-striped table-bordered dt-responsive display text-center">' +
                       '<thead>' +
                       '<th align="center">Analysis Data</th>' +
                       '<th align="center" id="team_grade_' + form_no + '">' + teamGrade + '</th>' +
                       '</thead>' +
                       '<tbody>' +
                       '<tr>' +
                       '<td align="left">No of Teams</td>' +
                       '<td align="center" id="no_of_teams_' + form_no + '">' + noOfTeams + '</td>' +
                       '</tr>' +
                       '<tr>' +
                       '<td align="left">No of Rounds</td>' +
                       '<td align="center" id="no_of_rounds_' + form_no + '">' + roundCount + '</td>' +
                       '</tr>' +
                       '<tr>' +
                       '<td align="left">Rounds (inc Finals)</td>' +
                       '<td align="center" id="finals_rounds_' + form_no + '">' + totalRounds + '</td>' +
                       '</tr>' +
                       '<tr>' +
                       '<td align="left">Start Date</td>' +
                       '<td align="center"><input type="text" name="startdate" id="startdate_' + form_no + '" value="' + startDate + '" style="width:100px"></td>' +
                       '</tr>' +
                       '<tr>' +
                       '<td align="left">Day Played</td>' +
                       '<td align="center" id="dayplayed_' + form_no + '">' + dayplayed + '</td>' +
                       '</tr>' +
                       '<tr>' +
                       '<td align="left" valign="top">Non available dates</td>' +
                       '<td align="center" rowspan="' + num_club + '"><textarea cols="10" rows="10">' + dates + '</textarea></td>' +
                       '</tr>');
        output += ("<tr>");
        output += ("<td align='center' class='greenbg' colspan='2'><button type='button' style='color:red;' class='btn btn-primary savebutton' data-id='" + form_no + "'>Save Fixtures for " + teamGrade + "</button></td>");
        output += ("</tr>");
        output += ('</tbody>');
        output += ("</table>");
        output += ("</td>");
        output += ("</tr>");
        output += ("<tr>");
        output += ("<td align='center' colspan='2' class='greenbg' style='height: 20px'><a href='create_fixtures_pdf.php?Year=" + year + "&Season=" + season + "&Team_Grade=" + teamGrade + "&DayPlayed=" + dayplayed + "&Rounds=" + totalRounds + "'>Generate Fixtures PDF for " + teamGrade + "</a></td>");
        output += ("</tr>");
        output += ("</table>");

        //fixtures = fixtures.substring(0, fixtures.length - 1);
        //main(fixtures, teamGrade, form_no, year, season, startDate); 

        // all_six_teams = JSON.parse(all_six_teams);
        //console.log(output);

        output += ("</div>");
        
        //console.log(output);
        //console.log("Grade " + teamGrade + ", Form No " + form_no);
        $($.parseHTML(output)).appendTo('#fixture-output_' + form_no);
        form_no++;

        

        //container.append(tbody);
    });
}
*/

function uniqueMultidimArray(array, key) {
    var tempArray = [];
    var keyArray = [];

    $.each(array, function(index, val) {
        if ($.inArray(val[key], keyArray) === -1) {
            keyArray.push(val[key]);
            tempArray.push(val);
        }
    });

    return tempArray;
}

function CreateTeamTables(data, form_no, dayplayed, year, season) {
    console.log("here");
    const pages = uniqueMultidimArray(data.grades, 'name');

    $.each(pages, function(_, grade) {
        const teamGrade = grade.name;
        let output = '';
        const filtered = $.grep(data.teams, r => r.grade === teamGrade);

        let comptype = filtered.length ? filtered[0].type : '';

        output += `<div id="page${form_no}">`;
        output += `<table class="table table-striped table-bordered dt-responsive nowrap display" border="1">`;
        output += `<tbody class="row_position_10">`;

        output += `<tr><td colspan="3" align="center">(Algorithm)</td></tr>`;
        output += `<tr>
            <td align="center">${teamGrade}</td>
            <td align="center" id="${comptype}_${form_no}">${comptype}</td>
        </tr>`;
        output += `<tr><td>`;

        output += `<table class="table table-striped table-bordered dt-responsive display text-center col-6">
            <thead>
                <th>Position ID</th><th>Team ID</th><th>Club ID</th><th>Tables</th><th>Club Name</th><th>Team Name</th><th>Home Games</th>
            </thead>
            <tbody class="row_position_${form_no}">`;

        let x = 0;

        $.each(filtered, function(_, fix) {
            x++;
            const clubTables = $.grep(data.clubs, d => d.name === fix.club);
            let clubId = clubTables[0]?.id || '';
            let clubTablesValue = clubTables[0]?.tables || '';

            output += `<tr draggable="true" ondragstart="start()" ondragover="dragover()" data-index="${x}" id="${x}"> 
                <td align="center" id="sort_${form_no}_${x}_id">${x}</td>
                <td align="center" id="${form_no}_${x}_id">${fix.id}</td>
                <input type="hidden" id="sort_form" value="${form_no}">
                <td align="center" id="club_${form_no}_${x}_id">${clubId}</td>
                <td align="center">${clubTablesValue}</td>
                <td align="left" id="club_name_${form_no}_${x}">${fix.club}</td>
                <td align="left" id="club_${form_no}_${x}">${fix.name}</td>
                <td align="center" id="games_${form_no}_${x}">5</td>
            </tr>`;
        });

        let startDate = '';
        let roundCount = 0;

        $.each(data.rounds, function(_, r) {
            if (r.grade === teamGrade) {
                roundCount = r.number;
                startDate = r.date;
            }
        });

        const totalRounds = comptype === 'Billiards' ? roundCount + 3 : roundCount + 2;

        let dates = '';
        $.each(data.non_dates, function(_, date) {
            dates += `${date.date}\n`;
        });

        output += `</tbody></table></td><td>`;

        output += `<table class="table table-striped table-bordered dt-responsive display text-center">
            <thead><th>Analysis Data</th><th id="team_grade_${form_no}">${teamGrade}</th></thead>
            <tbody>
                <tr><td>No of Teams</td><td id="no_of_teams_${form_no}">${x}</td></tr>
                <tr><td>No of Rounds</td><td id="no_of_rounds_${form_no}">${roundCount}</td></tr>
                <tr><td>Rounds (inc Finals)</td><td id="finals_rounds_${form_no}">${totalRounds}</td></tr>
                <tr><td>Start Date</td><td><input type="text" id="startdate_${form_no}" value="${startDate}" style="width:100px"></td></tr>
                <tr><td>Day Played</td><td id="dayplayed_${form_no}">${dayplayed}</td></tr>
                <tr><td valign="top">Non available dates</td><td rowspan="10"><textarea cols="10" rows="10">${dates}</textarea></td></tr>
                <tr><td colspan="2" class="greenbg"><button type="button" class="btn btn-primary savebutton" data-id="${form_no}">Save Fixtures for ${teamGrade}</button></td></tr>
            </tbody>
        </table></td></tr>`;

        output += `<tr><td colspan="2" class="greenbg" style="height: 20px">
            <a href="create_fixtures_pdf.php?Year=${year}&Season=${season}&Team_Grade=${teamGrade}&DayPlayed=${dayplayed}&Rounds=${totalRounds}">
                Generate Fixtures PDF for ${teamGrade}
            </a></td></tr>`;
        output += `</tbody></table></div>`;

        echo(output);
        // Append to output container
        //$('#fixture-output_' + form_no).append(output);
        $('#output').append(output);
        form_no++;
    });
}

