<?php
require_once('../Connections/connvbsa.php'); 
//include '../vbsa_online_scores/header_admin.php';
include '../vbsa_online_scores/header_vbsa.php';

mysql_select_db($database_connvbsa, $connvbsa);

?>
<link rel="stylesheet" type="text/css" href="tournament_draw.css">
<script>

if(/iPhone|iPad|iPod|Android|webOS|BlackBerry|Windows Phone/i.test(navigator.userAgent) || screen.availWidth < 480)
{
  //alert("Its a mobile");
 
  //document.getElementById('backBtn').classList.add('content-div.non-mobile');
  /*document.getElementById('backBtn').style.display = 'block';
  document.getElementById('nextBtn').style.display = 'none';

  document.getElementById('content1').classList.remove('active');
  document.getElementById('content1').classList.add('content-div non-mobile');
  //element2.classList.remove('content-div');
  document.getElementById('content2').classList.add('non-mobile');
  //element3.classList.remove('content-div');
  document.getElementById('content3').classList.add('non-mobile');
  */
}
else
{
  //alert("Its not a mobile");
  /*
  var element1 = document.getElementById('content1');
  element1.classList.remove('non-mobile');
  var element2 = document.getElementById('content2');
  element2.classList.remove('non-mobile');
  var element3 = document.getElementById('content3');
  element3.classList.remove('non-mobile');
  */
}

</script>
<br>
<br>
<table width="300" border="0" align="center">
  <tr>
    <td align='left'><button id="backBtn">Back</button></td>
    <td align='right'><button id="nextBtn">Next</button></td>
  </tr>
</table>
<br>
<!--
<table width="300" border="0" align="center">
  <tr>
    <td colspan='2'>
      <table width="300" border="2" align="center" id="content1" class="bracket bracket-1 content-div active">
        <tr>
          <td width="150" align="center">Ref</td>
          <td width="150" align="center">Tournament</td>
        </tr>
        <tr>
          <td>GC</td>
          <td>Gary Cullen Handicap</td>
        </tr>
        <tr>
          <td width="150">&nbsp;</td>
          <td width="150">&nbsp;</td>
        </tr>
        <tr>
          <td width="150">&nbsp;</td>
          <td width="150">&nbsp;</td>
        </tr>
      </table>
    </td>
    <td>
      <table width="300" border="1" align="center" id="content2" class="bracket bracket-2 content-div">
        <tr>
          <td width="150" align="center">Entries</td>
          <td width="150" align="center">Entry Fee Income</td>
        </tr>
        <tr>
          <td>GC</td>
          <td>Bert Hinkler Handicap</td>
        </tr>
        <tr>
          <td width="150">&nbsp;</td>
          <td width="150">&nbsp;</td>
        </tr>
        <tr>
          <td width="150">&nbsp;</td>
          <td width="150">&nbsp;</td>
        </tr>
      </table>
    </td>
    <td>
      <table width="300" border="1" align="center" id="content3" class="bracket bracket-3 content-div">
        <tr>
          <td width="150" align="center">Other Income</td>
          <td width="150" align="center">Expenses</td>
        </tr>
        <tr>
          <td>GC</td>
          <td>Fred Bloggs Handicap</td>
        </tr>
        <tr>
          <td width="150">&nbsp;</td>
          <td width="150">&nbsp;</td>
        </tr>
        <tr>
          <td width="150">&nbsp;</td>
          <td width="150">&nbsp;</td>
        </tr>
      </table>
      <table width="300" border="1" align="center" id="content4" class="content-div bracket bracket-4 ">
        <tr>
          <td width="150" align="center">More Income</td>
          <td width="150" align="center">Other Expenses</td>
        </tr>
        <tr>
          <td>GC</td>
          <td>Fred Bloggs Handicap</td>
        </tr>
        <tr>
          <td width="150">&nbsp;</td>
          <td width="150">&nbsp;</td>
        </tr>
        <tr>
          <td width="150">&nbsp;</td>
          <td width="150">&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
-->
<div class="tournament-brackets">
  <?php  
  $total_players = 120;
  $total_tourn = 128;
  $tournamentID = 202269;

    $query_tourn_players = 'Select * FROM tournament_scores LEFT JOIN members on member_id=MemberID LEFT JOIN tournaments on tournament_scores.tourn_id=tournaments.tourn_id where tournament_scores.tourn_id = ' . $tournamentID . ' and LastName != "" ';
  //echo("SQL " . $query_tourn_players . "<br>");
  $result_tourn_players = mysql_query($query_tourn_players, $connvbsa) or die(mysql_error());

  $x = 0; 
  $bye_index = 10000; 
  echo("<div class='bracket bracket-1 content-div active' id='content1'>");
  while($build_tourn = $result_tourn_players->fetch_assoc())
  {
    echo("<div class='team-item' style='width:200px'><input type='text' id='R" . $total_players . "_seed_" . ($x+1) . "' value='" . $build_tourn['ranknum'] . "' style='width:35px'><input type='text' value='" . $build_tourn['FirstName'] . " " . $build_tourn['LastName'] . "' id='R" . $total_players . "_name_" . ($x+1) . "' style='width:120px'><input type='text' id='R" . $total_players . "_score_" . ($x+1) . "' style='width:20px' readonly value=''>");
    echo("<input type='hidden' value=" . $build_tourn['MemberID'] . " id='R" . $total_players . "_id_" . ($x+1) . "' style='width:20px'></div>");
    $x++;
  }
  for($i = ($total_tourn); $i < $total_players; $i++) // name = 'Bye'
  {
    echo("<div class='team-item' style='width:200px'><input type='text' id='R" . $total_players . "_seed_" . ($x+1) . "' value='0' style='width:35px'><input type='text' value='Bye' id='R" . $total_players . "_name_" . ($x+1) . "'  style='width:120px'><input type='text' id='R" . $total_players . "_score_" . ($x+1) . "' style='width:20px' readonly value=''>");
    echo("<input type='hidden' value=" . ($build_tourn['MemberID']+$bye_index) . " id='R" . $total_players . "_id_" . ($x+1) . "' style='width:50px'></div>");
    $x++;
    $bye_index++;
  }
  echo("</div>");

  if($total_players > 64)
  {
    echo("<div class='bracket bracket-2 content-div active' id='content2'>");
    for($i = 0; $i < 64; $i++)
    {
      echo("<div class='team-item' style='width:200px'><input type='text' id='R64_name_" . ($i+1) . "'  value='Fred Bloggs' style='width:120px'><input type='text' id='R64_score_" . ($i+1) . "' value='' style='width:20px'>");
      echo("<input type='hidden' value='' id='R64_id_" . ($i+1) . "' style='width:20px'></div>");
    }
    echo("</div>");
  }

  if($total_players > 32)
  {
    echo("<div class='bracket bracket-2 content-div' id='content3'>");
    for($i = 0; $i < 32; $i++)
    {
      echo("<div class='team-item' style='width:200px'><input type='text' id='R32_name_" . ($i+1) . "' value='Peter Johnson' style='width:120px'><input type='text' id='R32_score_" . ($i+1) . "' value='' style='width:20px'>");
      echo("<input type='hidden' value='' id='R32_id_" . ($i+1) . "' style='width:20px'></div>");
    }
    echo("</div>");
  }

  if($total_players > 16)
  {
    echo("<div class='bracket bracket-2 content-div' id='content4'>");
    for($i = 0; $i < 16; $i++)
    {
      echo("<div class='team-item' style='width:200px'><input type='text' id='R16_name_" . ($i+1) . "' style='width:120px' value='Mai Johnson'><input type='text' id='R16_score_" . ($i+1) . "'  value='' style='width:20px'>");
      echo("<input type='hidden' value='' id='R16_id_" . ($i+1) . "' style='width:20px'></div>");
    }
    echo("</div>");
  }

  if($total_players > 8)
  {
    echo("<div class='bracket bracket-3 content-div' id='content5'>");
    for($i = 0; $i < 8; $i++)
    {
      echo("<div class='team-item' style='width:200px'><input type='text' id='R8_name_" . ($i+1) . "' style='width:120px' value='Molly Ringwold'><input type='text' id='R8_score_" . ($i+1) . "' value='' style='width:20px'><input type='hidden' value='' id='R8_id_" . ($i+1) . "' style='width:20px'></div>");
    }
    echo("</div>");
  }

  if($total_players > 4)
  {
    echo("<div class='bracket bracket-4 content-div' id='content6'>");
    for($i = 0; $i < 4; $i++)
    {
      echo("<div class='team-item' style='width:200px'><input type='text' id='R4_name_" . ($i+1) . "' style='width:120px' value='Bert Hinkler'><input type='text' id='R4_score_" . ($i+1) . "' value='' style='width:20px'><input type='hidden' value='' id='R4_id_" . ($i+1) . "' style='width:20px'></div>");
    }
    echo("</div>");
  }

  if($total_players > 2)
  {
    echo("<div class='bracket bracket-5 content-div' id='content7'>");
    for($i = 0; $i < 2; $i++)
    {
      echo("<div class='team-item' style='width:200px'><input type='text' id='R2_name_" . ($i+1) . "' style='width:120px' value='Mark Bacon'><input type='text' id='R2_score_" . ($i+1) . "' value='' style='width:20px'><input type='hidden' value='' id='R2_id_" . ($i+1) . "' style='width:20px'></div>");
    }
    echo("</div>");
  }

  if($total_players > 1)
  {
    echo("<div class='bracket bracket-6 content-div' id='content8'>");
    echo("<div class='team-item' style='width:200px'><input type='text' id='R1_name_1' style='width:120px' value='Alec Spyrou'><input type='hidden' value='' id='R1_id_1' style='width:20px'></div>");
    echo("</div>");
  }

  ?>

<style>
    .content-div {
        display: none;
    }
    .content-div.active {
        display: block;
    }
    .content-div.non-mobile {
        display: block;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        let currentDiv = 0;
        const divs = $(".content-div");

        function updateDivs() {
            divs.removeClass("active");
            $(divs[currentDiv]).addClass("active");
        }

        $("#nextBtn").click(function(){
            if (currentDiv < divs.length - 1) {
                currentDiv++;
                updateDivs();
            }
        });

        $("#backBtn").click(function(){
            if (currentDiv > 0) {
                currentDiv--;
                updateDivs();
            }
        });
    });
</script>

</body>
</html>