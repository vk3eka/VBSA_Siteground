<?php require_once('../Connections/connvbsa.php'); ?>
<?php include('../security_header.php'); 

error_reporting(0);

$page = "../admin_scores/team_rego_fees.php";
$_SESSION['page'] = $page;

if (!function_exists("GetSQLValueString")) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
	{
	  echo "X" . PHP_VERSION . "X";
	  if (PHP_VERSION < 6) {
	    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
	  }

	  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

	  switch ($theType) {
	    case "text":
	      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
	      break;    
	    case "long":
	    case "int":
	      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
	      break;
	    case "double":
	      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
	      break;
	    case "date":
	      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
	      break;
	    case "defined":
	      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
	      break;
	  }
	  return $theValue;
	}
}

if (isset($_GET['pagename'])) {
  $pagename = $_GET['pagename'];
}

if (isset($_GET['year'])) {
  $current_year = $_GET['year'];
}

if (isset($_GET['season'])) {
  $current_season = $_GET['season'];
}

mysql_select_db($database_connvbsa, $connvbsa);

function GetMemberName($memberID)
{
	global $connvbsa;
	$query_id = "Select FirstName, LastName, Email from members left join clubs_contact on members.MemberID = cont_memb_id where MemberID = " . $memberID;
	$membID = mysql_query($query_id, $connvbsa) or die(mysql_error());
	$row_id = mysql_fetch_assoc($membID);
	$row_data = [];
	$row_data['name'] = $row_id['FirstName'] . " " . $row_id['LastName'];
	$row_data['email'] = $row_id['Email'];
	return $row_data;
}
/*
function GetTeamID($team_name)
{
	global $connvbsa;
	$query_id = "Select team_id, team_name from Team_entries where team_name = '" . $team_name . "'";
	$teamID = mysql_query($query_id, $connvbsa) or die(mysql_error());
	$row_id = mysql_fetch_assoc($teamID);
	return $row_id['team_id'];
}
*/
$query_clubs = "Select ClubTitle, cont_memb_id, cont_type from clubs left Join clubs_contact on ClubNumber = club_id where VBSATeam = 1 and trim(cont_type) = 'Invoice to' group by ClubTitle order by ClubTitle";
$clubs = mysql_query($query_clubs, $connvbsa) or die(mysql_error());
//echo($query_clubs . "<br>");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 

<style>
.table-container {
  max-height: 70vh;
  overflow-y: auto;
  border: 1px solid #ccc;
}
#member-table {
  border-collapse: collapse;
  width: 100%;
}
#member-table td, #member-table th {
  padding: 4px 6px;
}
/* Sticky header row */
#member-table .sticky-header td, #member-table .sticky-header th {
  position: sticky;
  top: 0;
  background: #fff;
  z-index: 3;
}

</style>
</head>
<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<script>
$(document).ready(function() 
{
	$('#pennant_fee').focusout(function() 
	{
		var season = '<?= $current_season ?>';
		var year = <?= $current_year ?>;
		var fee = $('#pennant_fee').val();
		$.ajax({
      url:"save_fee_data.php?season=" + season + "&year=" + year + "&pennant_fee=" + fee,
      method: 'GET',
      success:function(response)
      {
        alert(response);
        location.reload(true);
      },
    });
	});
});
</script>
<?php
$query_fee = "Select pennant_fee from Team_entries where team_season = '" . $current_season . "' and team_cal_year = " . $current_year . " Limit 1";
$fee = mysql_query($query_fee, $connvbsa) or die(mysql_error());
$row_fee = mysql_fetch_assoc($fee);
?>
<form name='fees'  method="post" action='team_rego_fees.php'>
<table width="1000" align="center">
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="center" nowrap="nowrap"><span class="red_bold" >Pennant Billing Reports for Season <?= $current_season ?> <?= $current_year ?></a></span></td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
  	<td class="greenbg" align="center"><a href="#" id='export'>Export Current Data To CSV File</a></td>
  	  	<!--<td class="greenbg" align="center"><a href="../Admin_DB_VBSA/export_csv.php?page=vbsa_financials&season=<?= $current_season ?>&year=<?= $current_year ?>">Export Current Data To CSV File</a></td>-->
    <td align='right'>Current Fee&nbsp;$<input type='text' id='pennant_fee' style="width: 35px;" value='<?= number_format($row_fee['pennant_fee'], 2) ?>'></td>
  	<td>(Auto Saved)</td>
  	<td align="right" class="greenbg"><a href="AA_scores_index_grades.php?season=<?= $current_season ?>">Return to Scores index</a></td>
  </tr>
  <tr>
    <td colspan="4" align="left">&nbsp;</td>
  </tr>
</table>

<table id="member-table" border='1' align="center" cellpadding="3" cellspacing="3">
  <tr class="sticky-header">
  	<td align="center">&nbsp;</td>
    <td align="center" nowrap="nowrap">Team ID</td>
    <td align="center" nowrap="nowrap">Club</td>
    <td align="center" nowrap="nowrap">Team Name</td>
    <td align="center" nowrap="nowrap">Grade</td>
    <td align="center" nowrap="nowrap">Day Played</td>
    <td align="center" nowrap="nowrap">No. of Players</td>
    <td align="center" nowrap="nowrap">No. of Rounds</td>
    <td align="center" nowrap="nowrap">Byes</td>
    <td align="center" nowrap="nowrap">Player Forfeits (@ $<?= number_format($row_fee['pennant_fee'], 2) ?>)</td>
    <td align="center" nowrap="nowrap">Team Forfeits (@ $<?= number_format(($row_fee['pennant_fee']*4), 2) ?>)</td>
    <td align="center" nowrap="nowrap">Juniors Discount</td>
    <td align="center" nowrap="nowrap">Fees</td>
    <td align="center" nowrap="nowrap">No. Of Teams</td>
    <td align="center" nowrap="nowrap">Multiteam Discount</td>
    <td align="center" nowrap="nowrap">Club Totals</td>
    <td align="center" nowrap="nowrap">Bill To:</td>
    <td align="center" nowrap="nowrap">E-mail</td>
    <td align="center">&nbsp;</td>
  </tr>
  <?php 
  	$memberData = [];
  	while ($row_clubs = mysql_fetch_assoc($clubs))
  	{
  		$fullname = '';
  		$email_address = '';
  		$team_count = 0;
  		if($row_clubs['cont_memb_id'] != '')
  		{
  			$memberData = GetMemberName($row_clubs['cont_memb_id']);
	  		$fullname = $memberData['name'];
	  		$email_address = $memberData['email'];
	  	}
	  	//echo($fullname . "<br>");
  		$query_count = "Select team_id, team_club, team_name, team_grade from Team_entries where team_season = '" . $current_season . "' and team_cal_year = " . $current_year . " and team_club = '" . $row_clubs['ClubTitle'] . "' and team_name != 'Bye' order by team_club";
				$count = mysql_query($query_count, $connvbsa) or die(mysql_error());
				$team_count = $count->num_rows;
				$total_teams = ($team_count+$total_teams);

			if($current_year > '2023')
			{
				$query_fees = "Select distinct team_id, team_club, team_name, team_grade, day_played, players, (no_of_rounds-(finals_teams/2)) as rounds, count_byes, count_team_forfeits, count_player_forfeits FROM vbsa3364_vbsa2.Team_entries left join Team_grade on Team_grade.grade = Team_entries.team_grade where team_name != 'Bye' and team_season = '" . $current_season . "' and team_cal_year = " . $current_year  . " and team_club = '" . $row_clubs['ClubTitle'] . "' and current = 'Yes' order by team_grade";
			}
			else
			{
				$query_fees = "Select distinct team_id, team_club, team_name, team_grade, day_played, players, (no_of_rounds-(finals_teams/2)) as rounds, count_byes, count_team_forfeits, count_player_forfeits FROM vbsa3364_vbsa2.Team_entries left join Team_grade on Team_grade.grade = Team_entries.team_grade where team_name != 'Bye' and team_season = '" . $current_season . "' and team_cal_year = " . $current_year  . " and team_club = '" . $row_clubs['ClubTitle'] . "' order by team_grade";
			}
  		//echo($query_fees . "<br>");
			$fees = mysql_query($query_fees, $connvbsa) or die(mysql_error());
			$total_fees = 0;
			$club_juniors = 0;
			while ($row_fees = mysql_fetch_assoc($fees))
			{ 
	      //$total_forfeit = ($row_fees['count_team_forfeits']+$row_fees['count_player_forfeits']);
				$fees_in_dollars = ((($row_fees['rounds']-$row_fees['count_byes'])*4*$row_fee['pennant_fee'])-($row_fees['count_player_forfeits']*$row_fee['pennant_fee'])-($row_fees['count_team_forfeits']*($row_fee['pennant_fee']*4)));

				//$total_fees = ($fees_in_dollars+$total_fees);
				// get number of juniors
				$query_juniors = "Select FirstName, LastName, dob_year, team_id, SUM(count_played) as juniors FROM members left join scrs on members.MemberID = scrs.memberID where (dob_year >= year(curdate())-18) and team_id = " . $row_fees['team_id'];
				//echo($query_juniors . "<br>");
				$juniors = mysql_query($query_juniors, $connvbsa) or die(mysql_error());
				$row_juniors = mysql_fetch_assoc($juniors);
				$no_of_juniors = $juniors->num_rows;
				//$grand_total_fees = ($fees_in_dollars+$grand_total_fees);
				//$club_juniors = ($row_juniors['juniors']+$club_juniors);

				//echo($club_juniors . " Juniors<br>");
				if($row_juniors['juniors'] > 0)
				{
					$team_junior_discount = number_format((int)(($row_juniors['juniors']*$row_fee['pennant_fee']*-1)), 2);
					$fees_in_dollars = ($fees_in_dollars+$team_junior_discount);
				}
				else
				{
					$team_junior_discount = '';
				}
				$total_fees = ($fees_in_dollars+$total_fees);
				$grand_total_fees = ($fees_in_dollars+$grand_total_fees);
				$club_juniors = ($row_juniors['juniors']+$club_juniors);
	?>
	    <tr>
	    	<input type='hidden' id='juniors' value='<?= $no_of_juniors?>'>
	    	<td align="center">&nbsp;</td>
	      <td nowrap="nowrap" align="center"><?php echo $row_fees['team_id']; ?></td>
	      <td nowrap="nowrap" align="center">&nbsp;</td>
	      <td nowrap="nowrap" align="center"><?php echo $row_fees['team_name']; ?></td>
	      <td nowrap="nowrap" align="center"><?php echo $row_fees['team_grade']; ?></td>
	      <td nowrap="nowrap" align="center"><?php echo $row_fees['day_played']; ?></td>
	      <td nowrap="nowrap" align="center"><?php echo $row_fees['players']; ?></td>
	      <td nowrap="nowrap" align="center"><?php echo (int) $row_fees['rounds']; ?></td>
	      <td nowrap="nowrap" align="center"><?php echo $row_fees['count_byes']; ?></td>
	      <td nowrap="nowrap" align="center"><?php echo $row_fees['count_player_forfeits']; ?></td>
	      <td nowrap="nowrap" align="center"><?php echo $row_fees['count_team_forfeits']; ?></td>
	      <td nowrap="nowrap" align="center"><font color='red'><?= $team_junior_discount ?></td>
	      <td nowrap="nowrap" align="center"><?php echo number_format($fees_in_dollars, 2); ?></td>
	      <td nowrap="nowrap" align="center">&nbsp;</td>
	      <td nowrap="nowrap" align="center">&nbsp;</td>
	      <td nowrap="nowrap" align="center">&nbsp;</td>
	      <td nowrap="nowrap" align="center">&nbsp;</td>
	      <td nowrap="nowrap" align="center">&nbsp;</td>
	      <td align="center">&nbsp;</td>
	    </tr>
	<?php 
	  	}
	  	
		  if(($team_count > 5) && ($team_count < 10))
		  {
		  	$multi_discount = ($total_fees*-0.05);
		  }
		  else if($team_count > 10)
		  {
		  	$multi_discount = ($total_fees*-0.10);
		  }
		  else
		  {
		  	$multi_discount = 0;
		  }

		  if($club_juniors > 0)
		  {
		  	$junior_discount = ($club_juniors*$row_fee['pennant_fee']*-1);
		  }
		  else
		  {
		  	$junior_discount = '';
		  }
		  $club_total = ((int)$total_fees+(floatval($multi_discount))+(int)$junior_discount);
		  $grand_total_club = ($club_total+$grand_total_club);
		  if($multi_discount != '')
			{
			  $total_multi = (floatval($multi_discount)+(floatval($total_multi)));
			}
			if($junior_discount != '')
			{
		  	$total_junior = ((int)$junior_discount+(int)$total_junior);
		  }

  ?>
  <tr>
  	<td align="center">&nbsp;</td>
  	<td align="center" nowrap="nowrap"><b>Club</b></td>
    <td align="center" nowrap="nowrap"><b><?= $row_clubs['ClubTitle'] ?></b></td>
    <td align="center" nowrap="nowrap">&nbsp;</td>
    <td align="center" nowrap="nowrap">&nbsp;</td>
    <td align="center" nowrap="nowrap">&nbsp;</td>
    <td align="center" nowrap="nowrap">&nbsp;</td>
    <td align="center" nowrap="nowrap">&nbsp;</td>
    <td align="center" nowrap="nowrap">&nbsp;</td>
    <td align="center" nowrap="nowrap">&nbsp;</td>
    <td align="center" nowrap="nowrap">&nbsp;</td>
    <td align="center" nowrap="nowrap"><b><font color='red'><?= number_format((int)$junior_discount, 2) ?></font></b></td>
    <td align="center" nowrap="nowrap"><b><?= number_format($total_fees, 2) ?></b></td>
    <td align="center" nowrap="nowrap"><b><?= $team_count ?></b></td>
    <td align="center" nowrap="nowrap"><b><font color='red'><?= number_format($multi_discount, 2) ?></font></b></td>
    <td align="center" nowrap="nowrap"><b><?= number_format($club_total, 2) ?></b></td>
    <td align="center" nowrap="nowrap"><?= $fullname ?></td>
    <td align="center" nowrap="nowrap"><?= $email_address ?></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
  	<td colspan='19'>&nbsp;</td>
  </tr>
  <?php
}
?>
	<tr>
  	<td colspan='19'>&nbsp;</td>
  </tr>
  <tr>
  	<td colspan='7'>&nbsp;</td>
    <td align="center" nowrap="nowrap"><b>Totals</b></td>
    <td colspan='3'>&nbsp;</td>
    <td align="center" nowrap="nowrap"><b><?= number_format($total_junior, 2) ?></b></td>
    <td align="center" nowrap="nowrap"><b><?= number_format($grand_total_fees, 2) ?></b></td>
    <td align="center" nowrap="nowrap"><b><?= $total_teams ?></b></td>
    <td align="center" nowrap="nowrap"><b><?= number_format($total_multi, 2) ?></b></td>
    <td align="center" nowrap="nowrap"><b><?= number_format($grand_total_club, 2) ?></b></td>
    <td colspan='3' align="center" nowrap="nowrap"></td>
  </tr>
</table>
<table align="center" cellpadding="5" cellspacing="5"> 
  <tr>
    <td colspan='10' align='center'>View previous Pennant Billing Reports</td>
  </tr>
  <tr>
  <?php 
  $query_archives = "Select distinct team_cal_year, team_season from Team_entries where team_season != '' order by team_cal_year, team_season";
	$archives = mysql_query($query_archives, $connvbsa) or die(mysql_error());
	$row_archives = mysql_fetch_assoc($archives);
do { 
		if($row_archives['team_cal_year'] > 2022)
		{
?>
	  <td align="center" class="greenbg" ><a href="team_rego_fees.php?season=<?php echo $row_archives['team_season']; ?>&year=<?php echo $row_archives['team_cal_year']; ?>"><?php echo $row_archives['team_season'] . " " . $row_archives['team_cal_year']; ?></a></td>
<?php 
	  }
  } while ($row_archives = mysql_fetch_assoc($archives)); ?>
  </tr>
</table>
</form>
<script src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js"></script>
<script>
	document.getElementById("export").addEventListener("click", function() {
  var wb = XLSX.utils.table_to_book(document.getElementById("member-table"));
  XLSX.writeFile(wb, "teams.xlsx");
});
</script>

</body>
</html>

