<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

error_reporting(0);

include '../vbsa_online_scores/header_admin.php';
?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script type='text/javascript'>

function DeleteDraw() {
    document.tournament_draw.TournamentID.value = '<?= $_GET['tourn_id'] ?>';
    document.tournament_draw.ButtonName.value = 'Delete';
    document.tournament_draw.submit();
}

function UploadDraw() {
    document.tournament_draw.TournamentID.value = '<?= $_GET['tourn_id'] ?>';
    document.tournament_draw.action = "upload_tourn_data.php?tourn_id=<?= $_GET['tourn_id'] ?>";
    document.tournament_draw.submit();
}

</script>
<script type='text/javascript'>

$(document).ready(function()
{
  $('.timepicker').timepicker({
    timeFormat: 'HH:mm:ss',
    interval: 15,
    minTime: '08',
    dynamic: false,
    dropdown: true,
    scrollbar: true
  });

  $('#delete_draw').click(function(){
    var tournament_id = '<?= $_GET['tourn_id'] ?>';
    $.ajax({
      url:"delete_draw_data.php?tourn_id=" + tournament_id,
      success : function(response){
        alert(response);
      }
    });
    $('#tournament_draw').attr('action', 'aa_tourn_index.php');
    $('#tournament_draw').submit();
  });

  $('#email_draw_players').click(function(){
    var tournament_name = $('#tourn_name').html();
    var tournament_id = '<?= $_GET['tourn_id'] ?>';
    /*
    $.ajax({
      url:"email_players.php?tourn_id=" + tournament_id + "&tourn_name=" + addslashes(tournament_name),
      success : function(response){
        alert(response);
      }
    });
    */
    alert('Not Activated!');
  });

});

</script>
<?php
// get tournament name
  $query_tourn_name = 'Select *, tournaments.tourn_type as type FROM vbsa3364_vbsa2.tournaments LEFT JOIN calendar ON tournaments.tourn_id = calendar.tourn_id where tournaments.tourn_id = 202462';

  $result_tourn_name = mysql_query($query_tourn_name, $connvbsa) or die(mysql_error());
  $build_tourn_name = $result_tourn_name->fetch_assoc();
  $tourn_type = $build_tourn_name['type'];

  // check if tournament has been imported
  // check if players already saved in the scoring table
  $query_import = 'Select * FROM vbsa3364_vbsa2.tournament_players where tourn_id = 202462';
  $result_import = mysql_query($query_import, $connvbsa) or die(mysql_error());
  $build_import = $result_import->fetch_assoc();
  $last_import = $build_import['last_import'];

?>
<link rel="stylesheet" type="text/css" href="tournament_draw.css">
<form name='tournament_draw_template' id='tournament_draw_template' method="post" action='tournament_draw_template.php'>
<input type='hidden' name='TournamentID' id='TournamentID'>
<input type='hidden' name='TotalPlayers'>
<input type='hidden' name='ScoreData1'>
<input type='hidden' name='ButtonName' id='ButtonName'>
<div class="container">
<br>
<table align="center" cellpadding="5" cellspacing="5" width='50%'>
  <tr>
    <td align="left"><span class="red_bold"><h3>Tournament Draw Creation</h3></span></td>
    <td align="center">&nbsp;</td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td colspan='3' align="center">&nbsp;</td>
  </tr>
</table>
<?php
echo("<hr>");
  echo("<div hidden align='center' id='tourn_name'>" . $build_tourn_name['tourn_name'] . "</div>");
  echo("<div align='center'><h3>" . $build_tourn_name['tourn_name'] . "</h3></div>");
  echo("<div align='center'>" . $tourn_caption . "</div>");
  echo("<div hidden align='center' id='tourn_id'>" . $tournament_id . "</div>");
  echo("<br>");
  echo("<div align='center'>Start Date " . $build_tourn_name['startdate'] . " - Finish Date " . $build_tourn_name['finishdate'] . "</div>");
  echo("<br>");
  if(!isset($last_import))
  {
    echo('<div align="center" class="greenbg"><button id="upload_draw" onclick="UploadDraw()" style="width: 300px; color: red;">Upload Tournament Draw Data</button></div>');
  }
  else
  {
    echo('<div align="center" class="greenbg"><button id="upload_draw" onclick="UploadDraw()" style="width: 300px;">Upload Tournament Draw Data</button></div>');
    echo("<br>");
    echo("<div align='center'>(last import " . $last_import . ")</div>");
  }
  echo("<br>");
  echo('<div align="center" class="greenbg"><button id="delete_draw" style="width: 300px;">Reset all Data for This Tournament Draw</button></div>');
  echo("<br>");
  echo('<div align="center" class="greenbg"><button id="email_draw_players" style="width: 300px;">Email all Players for This Tournament</button></div>');
  echo("<hr>");
?>
<!--
<table border="1" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td>


<table border="1" cellpadding="0" cellspacing="0" valign='top' width="10%">
  <?php
  // check if players already saved in the scoring table
  $query_players = 'Select * FROM tournament_scores Left Join members on tournament_scores.member_id = members.MemberID where tourn_id = 202462';
  $result_players = mysql_query($query_players, $connvbsa) or die(mysql_error());
  while($build_result = $result_players->fetch_assoc())
  {
    if($build_result['MemberID'] != '')
    {
      echo("<tr>");
      echo("<td width='5%'>");
      echo("<input type'text' value='" . $build_result['MemberID'] . "'>");
      echo("<td width='5%'>");
      echo("<input type'text' value='" . $build_result['FirstName'] . " " . $build_result['LastName'] . "'>");
      echo("<td width='5%'>");
      echo("<input type'text' value='" . $build_result['ranknum'] . "'>");
      echo("</td>");
      echo("<td>&nbsp;</td>");
      echo("</tr>");
    }
  }
  ?>
</table>

</td>
<td>
-->
<table border="1" cellpadding="0" cellspacing="0" width="90%" valign='top'>
 <tr>
  <td></td>
  <td rowspan="2" >Time</td>
  <td rowspan="2">Seed</td>
  <td>Round 1</td>
  <td></td>
  <td rowspan="2">Time</td>
  <td rowspan="2">Seed</td>
  <td>Round 2</td>
  <td></td>
  <td rowspan="2">Time</td>
  <td rowspan="2">Seed</td>
  <td>Round 3</td>
  <td></td>
  <td>Round 4</td>
  <td></td>
  <td>Round 5</td>
  <td></td>
  <td>Round 6</td>
  <td></td>
 </tr>
 <tr>
  <td></td>
  <td>Best of 5 frames</td>
  <td></td>
  <td>Best of 5 frames</td>
  <td></td>
  <td>Best of 5 frames</td>
  <td></td>
  <td>Best of 5 frames</td>
  <td></td>
  <td>Best of 5 frames</td>
  <td></td>
  <td>Best of 5 frames</td>
  <td></td>
 </tr>
 <tr>
  <td rowspan="23" height="391" style="writing-mode: vertical-lr;text-orientation: sideways;text-align: center;">Top Half<span>&nbsp;</span></td>
  <td>&nbsp;</td>
  <td>33</td>
  <td><input type='text' value='Luke Cody'></td>
  <td>&nbsp;</td>
  <td>9AM</td>
  <td>32</td>
  <td><input type='text' value='Kevin Stone'></td>
  <td>&nbsp;</td>
  <td>11AM</td>
  <td>1</td>
  <td><input type='text' value='Ali Daryab Shafayi'></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr>
  <td></td>
  <td>64</td>
  <td><input type='text' value='Bye'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td></td>
  <td><input type='text' value='Luke Cody'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td><input type='text' value=''></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr>
  <td>6.00</td>
  <td>48</td>
  <td><input type='text' value='James Bartolo'></td>
  <td>&nbsp;</td>
  <td>9AM</td>
  <td>17</td>
  <td><input type='text' value='Pushpinder Brar'></td>
  <td>&nbsp;</td>
  <td>11AM</td>
  <td>16</td>
  <td><input type='text' value='Shane Logan'></td>
  <td></td>
  <td><input type='text' value=''></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr>
  <td>Fri</td>
  <td>49</td>
  <td><input type='text' value='Philip Vassallo'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td></td>
  <td><input type='text' value=''></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td></td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td>41</td>
  <td><input type='text' value='Richard Ball'></td>
  <td>&nbsp;</td>
  <td>9AM</td>
  <td>24</td>
  <td><input type='text' value='John McAndrew'></td>
  <td>&nbsp;</td>
  <td>11AM</td>
  <td>9</td>
  <td><input type='text' value='Livon Kurda'></td>
  <td>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td>56</td>
  <td><input type='text' value='Bye'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td></td>
  <td><input type='text' value='Richard Ball'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td><input type='text' value=''></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td>40</td>
  <td><input type='text' value='Henry Chetcuti'></td>
  <td>&nbsp;</td>
  <td>9AM</td>
  <td>25</td>
  <td><input type='text' value='John Walmsley'></td>
  <td>&nbsp;</td>
  <td>11AM</td>
  <td>8</td>
  <td><input type='text' value='Masoud Alikhail'></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td></td>
  <td></td>
  <td></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td>57</td>
  <td><input type='text' value='Bye'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td></td>
  <td><input type='text' value='Henry Chetcuti'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td><input type='text' value=''></td>
  <td></td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td>60</td>
  <td><input type='text' value='Bye'></td>
  <td>&nbsp;</td>
  <td>9AM</td>
  <td></td>
  <td><input type='text' value='James Cockburn'></td>
  <td>&nbsp;</td>
  <td>11AM</td>
  <td></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr>
  <td></td>
  <td>37</td>
  <td><input type='text' value='James Cockburn'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>28</td>
  <td><input type='text' value='Baqer Ali'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>5</td>
  <td><input type='text' value='Luv Boricha'></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td>&nbsp;</td>
  <td></td>
  <td><input type='text' value=''></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr>
  <td>6.00</td>
  <td>53</td>
  <td><input type='text' value='Swami Sivaramakrishnan'></td>
  <td>&nbsp;</td>
  <td>9AM</td>
  <td></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>11AM</td>
  <td></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr>
  <td>Fri</td>
  <td>44</td>
  <td><input type='text' value='Rahul Jhamb'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>21</td>
  <td><input type='text' value='Darryl Tippett'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>12</td>
  <td><input type='text' value='Paul Thomerson'></td>
  <td>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td>&nbsp;</td>
  <td></td>
  <td></td>
 </tr>
 <tr>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td></td>
 </tr>
 <tr  >
  <td >6.00</td>
  <td >52</td>
  <td><input type='text' value='Nish Lekhi'></td>
  <td>&nbsp;</td>
  <td>9AM</td>
  <td ></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>11AM</td>
  <td ></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
 </tr>
 <tr  >
  <td >Fri</td>
  <td >45</td>
  <td><input type='text' value='Warren Hayden'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td >20</td>
  <td><input type='text' value='Sean Dempsey'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td >13</td>
  <td><input type='text' value='Alec Spyrou'></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
 </tr>
 <tr  >
  <td  ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td>&nbsp;</td>
  <td></td>
  <td ><input type='text' value=''></td>
  <td ></td>
  <td >FINAL</td>
  <td ></td>
 </tr>
 <tr  >
  <td >&nbsp;</td>
  <td >61</td>
  <td><input type='text' value='Bye'></td>
  <td>&nbsp;</td>
  <td>9AM</td>
  <td ></td>
  <td><input type='text' value='Michael Mihaljevic'></td>
  <td>&nbsp;</td>
  <td>11AM</td>
  <td ></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td ><input type='text' value=''></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td >Best of 7 frames</td>
  <td ></td>
 </tr>
 <tr  >
  <td  ></td>
  <td >36</td>
  <td><input type='text' value='Michael Mihaljevic'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td >29</td>
  <td><input type='text' value='Ahmad Alikhail'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td >4</td>
  <td><input type='text' value='Sumit Abrol'></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
 </tr>
 <tr  height="27">
  <td height="27"></td>
  <td ></td>
  <td ></td>
  <td></td>
  <td></td>
  <td ></td>
  <td></td>
  <td>Starting time on Saturday 9.00AM</td>
  <td ></td>
  <td></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ><input type='text' value=''></td>
  <td ></td>
 </tr>
 <tr>
  <td></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
 </tr>
 <tr  >
  <td rowspan="23" height="391" style="writing-mode: vertical-lr;text-orientation: sideways;text-align: center;" >Bottom Half</td>
  <td>&nbsp;</td>
  <td >35</td>
  <td><input type='text' value='Kelvin Small'></td>
  <td>&nbsp;</td>
  <td>8PM</td>
  <td >30</td>
  <td><input type='text' value='Jian (Tony) Li'></td>
  <td>&nbsp;</td>
  <td>1.30PM</td>
  <td >3</td>
  <td><input type='text' value='Marc Fridman'></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
 </tr>
 <tr  >
  <td  ></td>
  <td >62</td>
  <td><input type='text' value='Bye'></td>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td ></td>
  <td><input type='text' value='Kelvin Small'></td>
  <td>&nbsp;</td>
  <td>Sat</td>
  <td ></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
 </tr>
 <tr  >
  <td  ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td ><input type='text' value=''></td>
  <td ></td>
  <td ></td>
  <td ></td>
 </tr>
 <tr  >
  <td >6.00</td>
  <td >46</td>
  <td><input type='text' value='Bashir Hussain'></td>
  <td>&nbsp;</td>
  <td>8PM</td>
  <td >19</td>
  <td><input type='text' value='Jahanzaib Haroon'></td>
  <td>&nbsp;</td>
  <td>1.30PM</td>
  <td >14</td>
  <td><input type='text' value='Ray Rogers'></td>
  <td>&nbsp;</td>
  <td ><input type='text' value=''></td>
  <td ></td>
  <td></td>
  <td ></td>
  <td ></td>
  <td ></td>
 </tr>
 <tr  >
  <td >Fri</td>
  <td >51</td>
  <td><input type='text' value='Mark Crennan'></td>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td ></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>Sat</td>
  <td ></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
 </tr>
 <tr  >
  <td  ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td ><input type='text' value=''></td>
  <td ></td>
 </tr>
 <tr  >
  <td >6.00</td>
  <td >43</td>
  <td><input type='text' value='Ryan Neary'></td>
  <td>&nbsp;</td>
  <td>8PM</td>
  <td >22</td>
  <td><input type='text' value='Carlos Barrocas'></td>
  <td>&nbsp;</td>
  <td>1.30PM</td>
  <td >11</td>
  <td><input type='text' value='Tony Fridman'></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
 </tr>
 <tr  >
  <td >Fri</td>
  <td >54</td>
  <td><input type='text' value='Pulkit Sharma'></td>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td ></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>Sat</td>
  <td ></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
 </tr>
 <tr  >
  <td >&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td ><input type='text' value=''></td>
  <td ></td>
  <td >Round 6</td>
  <td ></td>
 </tr>
 <tr  >
  <td >&nbsp;</td>
  <td >38</td>
  <td><input type='text' value='Mohammad Sharif Behroz'></td>
  <td>&nbsp;</td>
  <td>8PM</td>
  <td >27</td>
  <td><input type='text' value='Paul James'></td>
  <td>&nbsp;</td>
  <td>1.30PM</td>
  <td >6</td>
  <td><input type='text' value='Henry Lau'></td>
  <td>&nbsp;</td>
  <td ><input type='text' value=''></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td >Best of 5 frames</td>
  <td ></td>
 </tr>
 <tr  >
  <td >&nbsp;</td>
  <td >59</td>
  <td><input type='text' value='Bye'></td>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td ></td>
  <td><input type='text' value='Mohammad Sharif Behroz'></td>
  <td>&nbsp;</td>
  <td>Sat</td>
  <td ></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr  >
  <td >&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
 </tr>
 <tr  >
  <td >&nbsp;</td>
  <td >58</td>
  <td><input type='text' value='Bye'></td>
  <td>&nbsp;</td>
  <td>6.00</td>
  <td ></td>
  <td><input type='text' value='Sanjay Kumar'></td>
  <td>&nbsp;</td>
  <td>3.00 PM</td>
  <td ></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
 </tr>
 <tr  >
  <td >&nbsp;</td>
  <td >39</td>
  <td><input type='text' value='Sanjay Kumar'></td>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td >26</td>
  <td><input type='text' value='Alex Bruce'></td>
  <td>&nbsp;</td>
  <td>Sat</td>
  <td >7</td>
  <td><input type='text' value='Scott Preston'></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
 </tr>
 <tr  >
  <td >&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td></td>
  <td>&nbsp;</td>
  <td ><input type='text' value=''></td>
  <td ></td>
  <td ></td>
  <td ></td>
 </tr>
 <tr  >
  <td >6.00</td>
  <td >55</td>
  <td><input type='text' value='Theo Tsaikos'></td>
  <td>&nbsp;</td>
  <td>8PM</td>
  <td ></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>3.00 PM</td>
  <td ></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td ><input type='text' value=''></td>
  <td ></td>
  <td></td>
  <td ></td>
  <td ></td>
  <td ></td>
 </tr>
 <tr  >
  <td >Fri</td>
  <td >42</td>
  <td><input type='text' value='David Heath'></td>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td >23</td>
  <td><input type='text' value='Kathy Howden'></td>
  <td>&nbsp;</td>
  <td>Sat</td>
  <td >10</td>
  <td><input type='text' value='Brendon Lang'></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
 </tr>
 <tr  >
  <td  ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td ><input type='text' value=''></td>
  <td ></td>
 </tr>
 <tr  >
  <td >6.00</td>
  <td >50</td>
  <td><input type='text' value='Graham Wilson'></td>
  <td>&nbsp;</td>
  <td>8PM</td>
  <td ></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>3.00 PM</td>
  <td ></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
 </tr>
 <tr  >
  <td >Fri</td>
  <td >47</td>
  <td><input type='text' value='Neil Maclachlan'></td>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td >18</td>
  <td><input type='text' value='Shabbir Badshah'></td>
  <td>&nbsp;</td>
  <td>Sat</td>
  <td >15</td>
  <td><input type='text' value='Ben Leung'></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
 </tr>
 <tr  >
  <td  ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td>&nbsp;</td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td ><input type='text' value=''></td>
  <td ></td>
  <td ></td>
  <td ></td>
 </tr>
 <tr  >
  <td >&nbsp;</td>
  <td >63</td>
  <td><input type='text' value='Bye'></td>
  <td>&nbsp;</td>
  <td>8PM</td>
  <td ></td>
  <td><input type='text' value='Adriam Hung'></td>
  <td>&nbsp;</td>
  <td>3.00 PM</td>
  <td ></td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td ><input type='text' value=''></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
  <td ></td>
 </tr>
 <tr>
  <td></td>
  <td>34</td>
  <td><input type='text' value='Adriam Hung'></td>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td>31</td>
  <td><input type='text' value='Ying Guo'></td>
  <td>&nbsp;</td>
  <td>Sat</td>
  <td>2</td>
  <td><input type='text' value='Bassam Elbelli'></td>
  <td>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
</tbody>
</table>
<!--
</td>
</tr>
</table>-->
</div>

</body>
</html>