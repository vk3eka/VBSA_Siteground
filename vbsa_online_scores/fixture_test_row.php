<?php

include('header_fixture.php'); 
include('connection.inc'); 
include('php_functions.php'); 

?>
<center>
<h1>Swapping Rows in Table</h1>
<hr>
<?php

$sql_club = 'Select team_name, team_club_id, team_club, team_grade, day_played, comptype from Team_entries where team_cal_year = 2024 and team_grade = "APS" and day_played = "Wed" order by fix_sort';
$result_club = $dbcnx_client->query($sql_club);
$result_club_display = $dbcnx_client->query($sql_club);
echo('<table class="table table-striped table-bordered dt-responsive display text-center" class="col-6">
  <thead>
  <tr>
    <td align="center">Position ID</td>
    <td align="center">Club ID</td>
    <td align="center">Tables</td>
    <td align="center">Club Name</td>
    <td align="center">Team Name</td>
  </tr>
  </thead>
  <tbody>');
$index = 0;
$form_no = 1;
while($build_data_club = $result_club_display->fetch_assoc())
{
    $sql_club_tables = "Select ClubNumber, ClubTables FROM clubs where ClubNumber = " . $build_data_club["team_club_id"];
    $result_club_tables = $dbcnx_client->query($sql_club_tables) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
    $tables = $result_club_tables->fetch_assoc();
    $club_tables = $tables['ClubTables'];
    $day_played = $build_data_club["day_played"];
    echo('
      <tr draggable="true" ondragstart="start()"  ondragover="dragover()" data-index=' . $index . '> 
        <td align="center" id="sort_' . $form_no . '_' . $index . '_id" >' . ($index+1) . '</td>
        <td align="center" id="club_' . $form_no . '_' . $index . '_id">' . $build_data_club["team_club_id"] . '</td>
        <td align="center">' . $club_tables . '</td>
        <td align="center">' . $build_data_club["team_club"] . '</td>
        <td align="center">' . $build_data_club["team_name"] . '</td>
      </tr>
    ');
    $index++;
}
echo("</tbody></table>");

?>
</center>
<script>
var row;

function start(){  
  row = event.target; 

  row.addEventListener('dragover', dragOver);
  row.addEventListener('drop', dragDrop);
  row.addEventListener('dragenter', dragEnter);
  row.addEventListener('dragleave', dragLeave);

}

function dragover(){
  var e = event;
  e.preventDefault(); 
  let children = Array.from(e.target.parentNode.parentNode.children);
  console.log("Child Start " + children.indexOf(row));
  if(children.indexOf(e.target.parentNode) > children.indexOf(row))
  {
    e.target.parentNode.after(row);
    console.log("Child After " + children.indexOf(row));
  }
  else
  {
    e.target.parentNode.before(row);
    console.log("Child Before " + children.indexOf(row));
  }
}


function dragStart() {
  console.log('Event: ', 'dragstart');
  dragStartIndex = +this.closest('tr').getAttribute('data-index');
  console.log('Start Index: ', dragStartIndex);
}

function dragEnter() {
  console.log('Event: ', 'dragenter');
  this.classList.add('over');
}

function dragLeave() {
  console.log('Event: ', 'dragleave');
  this.classList.remove('over');
}

function dragOver(e) {
  console.log('Event: ', 'dragover');
  e.preventDefault();
}

function dragDrop() {
  console.log('Event: ', 'drop');
  const dragEndIndex = +this.getAttribute('data-index');
  console.log('End Index: ', dragEndIndex);
  //swapItems(dragStartIndex, dragEndIndex);

  this.classList.remove('over');
}
/*
// Swap list items that are drag and drop
function swapItems(fromIndex, toIndex) {
  const itemOne = row[fromIndex].querySelector('.draggable');
  const itemTwo = row[toIndex].querySelector('.draggable');

  row[fromIndex].appendChild(itemTwo);
  row[toIndex].appendChild(itemOne);
}
*/

</script>

</body>
</html>

