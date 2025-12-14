<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include('header.php');
include('connection.inc');

// get current round season 1 Snooker
for($i = 1; $i <= 18; $i++)
{
	// format round number
  if($i > 8)
  {
      $rnd_no = ($i+1);
  }
  else
  {
      $rnd_no = '0' . ($i+1);
  }
	$sql = "Select count(r" . $rnd_no . "pos) as Count, scr_season FROM scrs WHERE current_year_scrs = 2023";
	$result = $dbcnx_client->query($sql);
	$build_data = $result->fetch_assoc();
	$season = $build_data['scr_season'];
	if(($build_data['Count'] == 0) || ($rnd_no == 18))
	{
		$last_round = ($rnd_no-1);
		break;
	}
}
// get current count text
for($i = 0; $i < $last_round; $i++)
{
	// format round number
  if($i > 8)
  {
      $rnd_no = ($i+1);
  }
  else
  {
      $rnd_no = '0' . ($i+1);
  }
	$count_text_1 = $count_text_1 . " COUNT(r" . $rnd_no . "s) + ";
}
$count_text_1 = substr($count_text_1, 0, strlen($count_text_1)-3);

// get previuos count text
for($i = $last_round; $i < 18; $i++)
{
	// format round number
  if($i > 8)
  {
      $rnd_no = ($i+1);
  }
  else
  {
      $rnd_no = '0' . ($i+1);
  }
	$count_text_2 = $count_text_2 . " COUNT(r" . $rnd_no . "s) + ";
}
$count_text_2 = substr($count_text_2, 0, strlen($count_text_2)-3);

?>
<table class='table table-striped table-bordered dt-responsive nowrap display' width='80%'>
    <tr>
        <td>
            <table class='table table-striped table-bordered dt-responsive nowrap display' width='80%'>
            <?php
            $sql = "Select MemberID, FirstName, LastName, MobilePhone, Email, paid_memb, LifeMember, referee, ccc_player, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by FROM members WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW( ) ))          OR LifeMember=1          OR totplayed_curr+totplaybill_curr>0          OR ccc_player=1         OR referee=1)    AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND Deceased !=1) ORDER BY paid_memb DESC, LifeMember DESC, Referee DESC, ccc_player DESC, LastName, FirstName";
            $result = $dbcnx_client->query($sql);
            echo("<tr>"); 
            echo("<td colspan=7 align=center><b>List of all Players (Matches Played)</b></td>");
            echo("</tr>");
            echo("<tr>"); 
            echo("<td colspan=7 align=center>&nbsp;</td>");
            echo("</tr>");
            echo("<tr>"); 
            echo("<td width='25' align='left'>Member ID</td>");
            echo("<td width='25' align='left'>Name</td>");
            echo("<td width='25' align='left'>Email</td>");
            echo("<td align='center'>Total</td>");
    				echo("<td align='center'>Snooker</td>");
    				echo("<td align='center'>Billiards</td>");
            echo("</tr>");
        	  $i = 0;
            while ($build_data = $result->fetch_assoc()) 
            {
	          	echo("<tr>"); 
              echo("<td width='25' align='left' id='player_id_". $i . "'>" . $build_data['MemberID'] . "</td>");
              echo("<td width='25' align='left'>" . $build_data['FirstName'] . " " . $build_data['LastName'] . "</td>");
              echo("<td width='25' align='left'>" . $build_data['Email'] . "</td>");
              echo("<td width='25' align='left'>" . ($build_data['CSnooker']+$build_data['CBilliards']) . "</td>");
              echo("<td width='25' align='left'>" . ($build_data['CSnooker']) . "</td>");
              echo("<td width='25' align='left'>" . ($build_data['CBilliards']) . "</td>");
              echo("</tr>");
              $i++;
           	}
            ?>
        </table>
	   </td>
    </tr>
</table>

<?php include('footer.php'); ?>