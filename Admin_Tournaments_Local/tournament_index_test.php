<?php
require_once('../Connections/connvbsa.php'); 
include '../vbsa_online_scores/header_vbsa.php';

mysql_select_db($database_connvbsa, $connvbsa);

error_reporting(0);

mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn1 = "Select *, count(member_id) as number_players FROM calendar Left Join tournaments on tournaments.tourn_id = calendar.tourn_id Left Join tournament_scores on tournament_scores.tourn_id = calendar.tourn_id where calendar.tourn_id != '' and member_id != 1 group by tournament_scores.tourn_id order by tournament_scores.tourn_id";
//echo($query_tourn1 . "<br>");
$tourn1 = mysql_query($query_tourn1, $connvbsa) or die(mysql_error());

if(isset($_POST['ButtonName']) && ($_POST['ButtonName'] == 'DeleteScores'))
{
  $query_delete = "truncate tournament_scores";
  $result = mysql_query($query_delete, $connvbsa) or die(mysql_error());
  echo("<script>");
  echo("alert('Data Deleted');");
  echo("</script>");
}

?>
<script>

function DeleteScores()
{
  document.delete.ButtonName.value = 'DeleteScores';
  document.delete.submit();
}

</script>
<form action="tournament_index_test.php" method="post" name="delete" id="delete" >
<input type="hidden" name="ButtonName">
<table align="center" cellpadding="5" cellspacing="5" width=75%>
  <tr>
    <td colspan="9" align="center" class="page" >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="9" align="center"><h3>Test Tournaments</h3></td>
  </tr>
  <tr>
    <td colspan="9" align="center" class="page" >&nbsp;</td>
  </tr>
</table>
<table align="center" cellpadding="5" cellspacing="5" border=1 width=75%>
  <tr>
    <td align="center">Tourn ID</td>
    <td align="center">Tournament Name</td>
    <td align="center">No. of Players</td>
    <td align="center">Select</td>
    <!--<td align="center">Edit</td>-->
  </tr>
  <?php
  while($row_tourn1 = mysql_fetch_assoc($tourn1))
  {
    if($row_tourn1['number_players'] != 0)
    {
  ?>
  <tr>
    <td align="center"><?php echo $row_tourn1['tourn_id']; ?></td>
    <td align="left"><?php echo $row_tourn1['tourn_name']; ?></td>
    <td align="center"><?php echo $row_tourn1['number_players']; ?></td>
    <td align="center"><a class='btn btn-primary btn-xs' href="../Tournaments/tourn_draw.php?tourn_id=<?php echo $row_tourn1['tourn_id']; ?>">Select Tournament</a></td>
    <!--<td align="center"><a class='btn btn-primary btn-xs' href="../Admin_Tournaments/edit_tournament.php?tourn_id=<?php echo $row_tourn1['tourn_id']; ?>">Edit Tournament</a></td>-->
  </tr>
  <?php
    }
  }
  ?>
</table>
<div>&nbsp;</div>
<div align="center"><a class='btn btn-primary btn-xs' onclick='DeleteScores();'>Delete all Tournament Score Data (For testing only)</a></div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</form>
</body>
</html>
