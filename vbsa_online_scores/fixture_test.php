<?php

include('header_fixture.php'); 
include('connection.inc'); 
include('php_functions.php'); 

?>
<script src="https://kit.fontawesome.com/3da1a747b2.js" crossorigin="anonymous"></script>
<style>

@import url('https://fonts.googleapis.com/css?family=Lato&display=swap');

:root {
  --border-color: #e3e5e4;
  --background-color: #c3c7ca;
  --text-color: #34444f;
}

* {
  box-sizing: border-box;
}

body {
  background-color: #fff;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  height: 100vh;
  margin: 0;
  font-family: 'Lato', sans-serif;
}

.draggable-list {
  border: 1px solid var(--border-color);
  color: var(--text-color);
  padding: 0;
  list-style-type: none;
}

.draggable-list tr {
  background-color: #fff;
  display: flex;
  flex: 1;
}

.draggable-list tr:not(:last-of-type) {
  border-bottom: 1px solid var(--border-color);
}

.draggable-list .number {
  background-color: var(--background-color);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  height: 60px;
  width: 60px;
}

.draggable-list td.over .draggable {
  background-color: #eaeaea;
}

.draggable-list .person-name {
  margin: 0 20px 0 0;
}

.draggable-list td.right .person-name {
  color: #3ae374;
}

.draggable-list td.wrong .person-name {
  color: #ff3838;
}

.draggable {
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 15px;
  flex: 1;
}

.check-btn {
  background-color: var(--background-color);
  border: none;
  color: var(--text-color);
  font-size: 16px;
  padding: 10px 20px;
  cursor: pointer;
}

.check-btn:active {
  transform: scale(0.98);
}

.check-btn:focus {
  outline: none;
}

</style>

<?php

$sql_club = 'Select team_name, team_club_id, team_club, team_grade, day_played, comptype from Team_entries where team_cal_year = 2024 and team_grade = "APS" and day_played = "Wed"';
$result_club = $dbcnx_client->query($sql_club);
$result_club_display = $dbcnx_client->query($sql_club);
echo('<table class="draggable-list" id="draggable-list">');
$index = 0;
//$richestPeople = [];
while($build_data_club = $result_club_display->fetch_assoc())
{
    $sql_club_tables = "Select ClubNumber, ClubTables FROM clubs where ClubNumber = " . $build_data_club["team_club_id"];
    $result_club_tables = $dbcnx_client->query($sql_club_tables) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
    $tables = $result_club_tables->fetch_assoc();
    $club_tables = $tables['ClubTables'];
    $day_played = $build_data_club["day_played"];
    echo('
      <tr draggable="true" ondragstart="start()"  ondragover="dragover()"> 
        <td align="center" data-index="'. $index . '">' . ($index+1) . '</td>
        <td align="center" class="draggable" draggable="true">' . $build_data_club["team_name"] . '<i class="fas fa-grip-lines"></i></td>
      </tr>
    ');

    //$richestPeople = $build_data_club["team_name"];
    $index++;
}
echo("</table>");

?>
<script>
  var row;

function start(){  
  row = event.target; 
}
function dragover(){
  var e = event;
  e.preventDefault(); 
  
  let children= Array.from(e.target.parentNode.parentNode.children);
  
  if(children.indexOf(e.target.parentNode)>children.indexOf(row))
    e.target.parentNode.after(row);
  else
    e.target.parentNode.before(row);
}
</script>
