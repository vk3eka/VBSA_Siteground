<?php 

include('header.php'); 
include('connection.inc'); 

?>
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">-->

<script>

function FirstLoad() {
  document.getElementById("form1").style.display = "block";
  document.getElementById("form2").style.display = "none";
  document.getElementById("form3").style.display = "none";
  document.getElementById("form4").style.display = "none";
  document.getElementById("form5").style.display = "none";
  document.getElementById("form6").style.display = "none"; 
}

function Viewtab(sel){
  
  switch (sel) {
    case 1:
        document.getElementById("form1").style.display = "block";
        document.getElementById("form2").style.display = "none";
        document.getElementById("form3").style.display = "none";
        document.getElementById("form4").style.display = "none";
        document.getElementById("form5").style.display = "none";
        document.getElementById("form6").style.display = "none";
        break;
    case 2:
        document.getElementById("form1").style.display = "none";
        document.getElementById("form2").style.display = "block";
        document.getElementById("form3").style.display = "none";
        document.getElementById("form4").style.display = "none";
        document.getElementById("form5").style.display = "none";
        document.getElementById("form6").style.display = "none";
        break;
    case 3:
        document.getElementById("form1").style.display = "none";
        document.getElementById("form2").style.display = "none";
        document.getElementById("form3").style.display = "block";
        document.getElementById("form4").style.display = "none";
        document.getElementById("form5").style.display = "none";
        document.getElementById("form6").style.display = "none";
        break;
    case 4:
        document.getElementById("form1").style.display = "none";
        document.getElementById("form2").style.display = "none";
        document.getElementById("form3").style.display = "none";
        document.getElementById("form4").style.display = "block";
        document.getElementById("form5").style.display = "none";
        document.getElementById("form6").style.display = "none";
        break;
    case 5:
        document.getElementById("form1").style.display = "none";
        document.getElementById("form2").style.display = "none";
        document.getElementById("form3").style.display = "none";
        document.getElementById("form4").style.display = "none";
        document.getElementById("form5").style.display = "block";
        document.getElementById("form6").style.display = "none";
        break;
    case 6:
        document.getElementById("form1").style.display = "none";
        document.getElementById("form2").style.display = "none";
        document.getElementById("form3").style.display = "none";
        document.getElementById("form4").style.display = "none";
        document.getElementById("form5").style.display = "none";
        document.getElementById("form6").style.display = "block";
        break;
    }
}

window.onload = function() 
{
  FirstLoad();
}

</script>


<table class='table dt-responsive nowrap display' width='100%'>
  <tr>
    <td colspan=6 class="text-center"><h1>2024 VBSA Fixture Generation</h1></td>
  </tr>
  <tr>
    <th class='text-center'><button type="button" class="btn btn-primary" onclick="Viewtab(1);" style="width:120px">APS</button></th>
    <th class='text-center'><button type="button" class="btn btn-primary" onclick="Viewtab(2);" style="width:120px">BPS</button></th>
    <th class='text-center'><button type="button" class="btn btn-primary" onclick="Viewtab(3);" style="width:120px">BVS1</button></th>
    <th class='text-center'><button type="button" class="btn btn-primary" onclick="Viewtab(4);" style="width:120px">CPS</button></th>
    <th class='text-center'><button type="button" class="btn btn-primary" onclick="Viewtab(5);" style="width:120px">CVS1</button></th>
    <th class='text-center'><button type="button" class="btn btn-primary" onclick="Viewtab(6);" style="width:120px">SP(T)</button></th>
  </tr>
</table>
<?php

// get team grades from Team entries table
$sql_grades = 'Select distinct team_grade from Team_entries where team_cal_year = 2024 order by team_grade';
$result_grades = $dbcnx_client->query($sql_grades);
$i = 1;
//while($build_data_grades = $result_grades->fetch_assoc())
//{
  //echo($build_data_grades['team_grade'] . "<br>"); insert where BPS is hardcoded
  // get team grades from Team entries table
  $sql_club = 'Select team_name, team_club_id, team_club, team_grade from Team_entries where team_cal_year = 2024 and team_grade = "BPS" order by team_grade, team_club';
  $result_club = $dbcnx_client->query($sql_club);
  $num_club = $result_club->num_rows;
  echo("<table class='table table-striped table-bordered dt-responsive nowrap display' class='col-6'>
         <tr>
          <td>");
      echo('<table class="table table-striped table-bordered dt-responsive display text-center" id="form' . $i . '" class="col-6">
            <tr>
              <td colspan=4 align="center"><b>BPS</b> No of Teams - ' . $num_club . '</td>
            </tr>
            <tr>
              <td align="center">Position ID</td>
              <td align="center">Club ID</td>
              <td align="center">Club Name</td>
              <td align="center">Team Name</td>
            </tr>');
      $x = 1;
      while($build_data_club = $result_club->fetch_assoc())
      {
          echo('
              <tr>
                <td scope="row" align="center">' . $x . '</td>
                <td align="center">' . $build_data_club["team_club_id"] . '</td>
                <td align="center">' . $build_data_club["team_club"] . '</td>
                <td align="center" id-"club_' . $i . '>' . $build_data_club["team_name"] . '</td>
              </tr>
            ');
          $x++;
      }
      echo("</table>");
//}

  echo("</td>");
  echo("</tr>");
  echo("</table>");
  $i++;
//}

?>
<table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
  <tr>
    <td>
      <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
        <thead>
      <tr>
        <th colspan = 4 class='text-center'>Master Table</th>
      </tr>
      </thead>
      <tbody>
        <tr>
          <td align='center'>Team 1</td>
          <td align='center'>Brunswick Titans</td>
          <td align='center'>Team 5</td>
          <td align='center'>Brunswick Taylors</td>
        </tr>
        <tr>
          <td align='center'>Team 2</td>
          <td align='center'>Cheltenham Ball Breakers</td>
          <td align='center'>Team 6</td>
          <td align='center'>Focus Yellow</td>
        </tr>
        <tr>
          <td align='center'>Team 3</td>
          <td align='center'>North Brighton Mustangs</td>
          <td align='center'>Team 7</td>
          <td align='center'>Yarraville Misfits</td>
        </tr>
        <tr>
          <td align='center'>Team 4</td>
          <td align='center'>Yarraville Hawks</td>
          <td align='center'>Team 8</td>
          <td align='center'>Bye</td>
        </tr>
      </tbody>
    </table>
  </td>
<td>
  <table class='table table-striped table-bordered dt-responsive nowrap display' class='col-6'>
    <thead>
      <tr>
        <th colspan = 3 class='text-center'>Pairs</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td align='center'>1</td>
        <td align='center'>v</td>
        <td align='center'>8</td>
      </tr>
      <tr>
        <td align='center'>2</td>
        <td align='center'>v</td>
        <td align='center'>7</td>
      </tr>
      <tr>
        <td align='center'>3</td>
        <td align='center'>v</td>
        <td align='center'>6</td>
      </tr>
      <tr>
        <td align='center'>4</td>
        <td align='center'>v</td>
        <td align='center'>5</td>
      </tr>
    </tbody>
  </table>
</td>
</td>
</table>


        <?php
        for($y = 0; $y < 14; $y++)
        {
        ?>
        <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
         <tr>
          <td>
            
            <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
              <thead>
              <tr>
                <th class='text-center'>Date</th>
                <th class='text-center'>Home Team</th>
                <th class='text-center'>v</th>
                <th class='text-center'>Away Team</th>
              </tr>
              </thead>
              <tbody>
                <tr>
                  <td align='center'>&nbsp;</td>
                  <td align='center'>Brunswick Titans</td>
                  <td align='center'>&nbsp;</td>
                  <td align='center'>Brunswick Taylors</td>
                </tr>
                <tr>
                  <td align='center'>&nbsp;</td>
                  <td align='center'>Cheltenham Ball Breakers</td>
                  <td align='center'>&nbsp;</td>
                  <td align='center'>Focus Yellow</td>
                </tr>
                <tr>
                  <td align='center'>&nbsp;</td>
                  <td align='center'>North Brighton Mustangs</td>
                  <td align='center'>&nbsp;</td>
                  <td align='center'>Yarraville Misfits</td>
                </tr>
                <tr>
                  <td align='center'>&nbsp;</td>
                  <td align='center'>Yarraville Hawks</td>
                  <td align='center'>&nbsp;</td>
                  <td align='center'>Bye</td>
                </tr>
              </tbody>
              </table>
            </td>
            <td>
              <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
                <thead>
                  <tr>
                    <th colspan = 3 class='text-center'>Round <?= ($y+1) ?></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td align='center'>1</td>
                    <td align='center'>v</td>
                    <td align='center'>8</td>
                  </tr>
                  <tr>
                    <td align='center'>2</td>
                    <td align='center'>v</td>
                    <td align='center'>7</td>
                  </tr>
                  <tr>
                    <td align='center'>3</td>
                    <td align='center'>v</td>
                    <td align='center'>6</td>
                  </tr>
                  <tr>
                    <td align='center'>4</td>
                    <td align='center'>v</td>
                    <td align='center'>5</td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </table>
        <?php
        }
        ?>
      </div>
    </div>
  </div>
</div>


