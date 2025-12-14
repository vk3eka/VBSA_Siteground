<?php require_once('../Connections/connvbsa.php'); ?>
<?php include('../security_header.php'); 

error_reporting(0);

?>
<?php

$page = "../Admin_DB_VBSA/vbsa_members.php";
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

if(isset($_POST['sort_order']))
{
	$varSort = $_POST['sort_order'];
	switch ($varSort) {
		case 'gender':
			$sortby = "Order By Gender DESC, LastName, FirstName";
			break;
		case 'junior':
			$sortby = "Order By Junior DESC, LastName, FirstName";
			break;
		case 'life':
			$sortby = "Order By LifeMember DESC, LastName, FirstName";
			break;
		case 'memberid':
			$sortby = "Order By MemberID";
			break;
		case 'ref':
			$sortby = "Order By Referee DESC, LastName, FirstName";
			break;
		case 'coach':
			$sortby = "Order By active_coach DESC, LastName, FirstName";
			break;
		case 'ccc':
			$sortby = "Order By ccc_player DESC, LastName, FirstName";
			break;
		case 'paid':
			$sortby = "Order By paid_memb DESC, LastName, FirstName";
			break;
		case 'players':
			$sortby = "Order By LastName, FirstName";
			break;
		case 'honorary':
			$sortby = "Order By hon_memb DESC, LastName, FirstName";
			break;
		case 'total':
			$sortby = "Order By Current DESC";
			break;
		case 'membership':
			$sortby = "Order By memb_by ASC, LastName, FirstName";
			break;
		default:
			$sortby = "Order By LastName, FirstName";
			break;
	}
}
else
{
	$sortby = "Order By LifeMember DESC, hon_memb DESC, Referee DESC, active_coach DESC, ccc_player DESC, paid_memb DESC, LastName ASC, FirstName ASC";
}


//$sortby = "Order By LifeMember DESC, hon_memb DESC, Referee DESC, active_coach DESC, ccc_player DESC, paid_memb DESC, LastName ASC, FirstName ASC";
//SELECT id, board_member_id, name, vbsaorga_users.email, vbsaorga_users.username, hashed_password, usertype, display, register_year, order_display, `comment`, MemberID, BoardMemb, board_desc, assist FROM vbsaorga_users, members WHERE board_member_id=MemberID AND assist=0 ORDER BY order_display ASC";

//SELECT id, board_member_id, name, vbsaorga_users.email, vbsaorga_users.username, hashed_password, usertype, display, register_year, order_display, `comment`, MemberID, BoardMemb, board_desc, assist FROM vbsaorga_users, members WHERE board_member_id=MemberID AND assist=1 ORDER BY order_display ASC";


mysql_select_db($database_connvbsa, $connvbsa);
$query_memb_bulk = "Select MemberID, FirstName, LastName, HomeAddress, HomeSuburb, HomePostCode, HomeState, MobilePhone, members.Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, referee, Ref_class, coach_id, class, ccc_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach, dob_day, dob_mnth, dob_year, Junior, hon_memb, community, board_member_id, board_desc, display, assist FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id LEFT JOIN vbsaorga_users ON MemberID = vbsaorga_users.board_member_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR (Junior !='na' AND Junior !='U21') OR active_coach=1 OR Gender!='Male' OR hon_memb=1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 " . $sortby;

//echo("Bulk " . $query_memb_bulk . "<br>");
$memb_bulk = mysql_query($query_memb_bulk, $connvbsa) or die(mysql_error());
$row_memb_bulk = mysql_fetch_assoc($memb_bulk);
$totalRows_memb_bulk = mysql_num_rows($memb_bulk);

$query_Count20 = "Select COUNT(paid_memb) FROM members WHERE paid_memb>0 AND YEAR(paid_date)=YEAR(NOW( ) )";
$Count20 = mysql_query($query_Count20, $connvbsa) or die(mysql_error());
$row_Count20 = mysql_fetch_assoc($Count20);
$totalRows_Count20 = mysql_num_rows($Count20);

// Male
$query_Count_Male = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach, hon_memb, community FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1 OR hon_memb = 1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND Gender = 'Male'";

$Count_Male = mysql_query($query_Count_Male, $connvbsa) or die(mysql_error());
$row_Count_Male = mysql_fetch_assoc($Count_Male);
//$total_male = $row_Count_Male['total_male'];
$totalRows_Count_Male = mysql_num_rows($Count_Male);

//Female
$query_Count_Female = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach, hon_memb, community FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (Gender = 'Female') AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0";

$Count_Female = mysql_query($query_Count_Female, $connvbsa) or die(mysql_error());
$row_Count_Female = mysql_fetch_assoc($Count_Female);
$totalRows_Count_Female = mysql_num_rows($Count_Female);

// Non Binary
$query_Count_NonBinary = "Select Count(MemberID) as tot_NB FROM members WHERE ((((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR hon_memb = 1 OR community = 1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) )) AND curr_memb = 0 AND Gender = 'NonBinary'";
$Count_NonBinary = mysql_query($query_Count_NonBinary, $connvbsa) or die(mysql_error());
$row_Count_NonBinary = mysql_fetch_assoc($Count_NonBinary);
//$totalRows_Count_NonBinary = mysql_num_rows($Count_NonBinary);

//No Gender
$query_Count_NoGender = "Select Count(MemberID) as tot_NG FROM members WHERE ((((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR hon_memb = 1 OR community = 1 OR LifeMember=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) )) AND curr_memb = 0 AND Gender = 'NoGender'";
$Count_NoGender = mysql_query($query_Count_NoGender, $connvbsa) or die(mysql_error());
$row_Count_NoGender = mysql_fetch_assoc($Count_NoGender);
//$totalRows_Count_NoGender = mysql_num_rows($Count_NoGender);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_Count_Junior = "Select COUNT(MemberID) as total_junior FROM members WHERE (Junior !='na' AND Junior !='U21')";
//$query_Count_Junior = "Select * FROM members Where (dob_year between YEAR( CURDATE( )) -18 AND YEAR( CURDATE( )))";
$Count_Junior = mysql_query($query_Count_Junior, $connvbsa) or die(mysql_error());
$row_Count_Junior = mysql_fetch_assoc($Count_Junior);
$totalRows_Count_Junior = mysql_num_rows($Count_Junior);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_lifemembers = "SELECT MemberID, LifeMember FROM members WHERE LifeMember>0";
$lifemembers = mysql_query($query_lifemembers, $connvbsa) or die(mysql_error());
$row_lifemembers = mysql_fetch_assoc($lifemembers);
$totalRows_lifemembers = mysql_num_rows($lifemembers);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_paid_memb = "Select MemberID, paid_memb FROM members WHERE (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND (paid_memb = 20 AND YEAR(paid_date) = YEAR(NOW()))";
$paid_memb = mysql_query($query_paid_memb, $connvbsa) or die(mysql_error());
$row_paid_memb = mysql_fetch_assoc($paid_memb);
$totalRows_paid_memb = mysql_num_rows($paid_memb);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_refs = "SELECT COUNT(MemberID) AS tot_refs FROM members WHERE referee>0";
$refs = mysql_query($query_refs, $connvbsa) or die(mysql_error());
$row_refs = mysql_fetch_assoc($refs);
$totalRows_refs = mysql_num_rows($refs);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_ccc_players = "SELECT COUNT(MemberID) AS tot_ccc FROM members WHERE ccc_player>0";
$ccc_players = mysql_query($query_ccc_players, $connvbsa) or die(mysql_error());
$row_ccc_players = mysql_fetch_assoc($ccc_players);
$totalRows_ccc_players = mysql_num_rows($ccc_players);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_coaches = "Select COUNT(MemberID) AS tot_coaches FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE active_coach=1";
//echo $query_coaches . "<br>";
$coaches = mysql_query($query_coaches, $connvbsa) or die(mysql_error());
$row_coaches = mysql_fetch_assoc($coaches);
$totalRows_coaches = mysql_num_rows($coaches);

$query_honorary = "Select COUNT(MemberID) AS tot_honorary FROM members WHERE hon_memb=1";
$honorary = mysql_query($query_honorary, $connvbsa) or die(mysql_error());
$row_honorary = mysql_fetch_assoc($honorary);
$totalRows_honorary = mysql_num_rows($honorary);

// added to display current sort order
switch ($_POST['sort_order'])
{
	case 'gender':
        $sortOrder = 'Gender';
        break;
  case 'junior':
        $sortOrder = 'Junior';
        break;
  case 'life':
        $sortOrder = 'Life member';
        break;
  case 'memberid':
        $sortOrder = 'Member ID';
        break;
  case 'ref':
        $sortOrder = 'Referee';
        break;
  case 'coach':
        $sortOrder = 'Coach';
        break;
  case 'ccc':
        $sortOrder = 'CCC Player';
        break;
  case 'paid':
        $sortOrder = 'Paid Member';
        break;
  case 'players':
        $sortOrder = 'Players';
        break;
  case 'total':
        $sortOrder = 'Total Games';
        break;
  case 'honorary':
        $sortOrder = 'Honorary';
        break;
  /*case 'community':
        $sortOrder = 'Community';
        break;*/
  case 'membership':
        $sortOrder = 'membership';
        break;
  default:
        $sortOrder = 'Players';
        break;
}

?>
<script type='text/javascript'>

function GetSort(sel) {
	var sort_order = sel.options[sel.selectedIndex].value;
	document.getElementById("sort_order").value = sort_order
	document.sort.submit();
}
</script>
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
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
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
<form name='sort'  method="post" action='absc_report.php'>
<table width="1000" align="center">
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
  	<td colspan="2" class="greenbg" align="center"><a href="export_csv.php?page=absc_members">Export Current Data To CSV File</a></td>
    <td colspan="2" align="right" class="greenbg"><a href="A_memb_index.php">Return to Members index</a></td>
  </tr>
  <tr>
    <td colspan="4" align="left">&nbsp;</td>
  </tr>
</table>
<table width="1000" align="center">
  <tr>
    <td>Total Members: <?php echo $totalRows_memb_bulk ?>  (Receive Email <?php echo $totalRows_memb ?>)</td>
    <td>Total Paid Members: <?php echo $totalRows_paid_memb ?></td>
    <td>Total Coaches: <?php echo $row_coaches['tot_coaches'] ?></td>
    <td>Total Junior: <?php echo $row_Count_Junior['total_junior']; ?></td>
  </tr>
   <tr>
    <td>Total Gender M: <?php echo $totalRows_Count_Male ?></td>
    <td>Total Gender F: <?php echo $totalRows_Count_Female ?></td>
    <td>Total Gender NB: <?php echo $row_Count_NonBinary['tot_NB'] ?></td>
    <td>Total Gender NS <?php echo $row_Count_NoGender['tot_NG']; ?></td>
  </tr>
  <tr>
    <td>Total Life Members: <?php echo $totalRows_lifemembers ?></td>
    <td>Total CCC players <?php echo $row_ccc_players['tot_ccc']; ?></td>
    <td>Total Referees:<?php echo $row_refs['tot_refs']; ?></td>
    <td>Total Honorary:<?php echo $row_honorary['tot_honorary']; ?></td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
</table>
<table width="1000" align="center">
  <tr>
  	<td>
			<p><b>Gender Legend</b></p>
			<p>M - Male</p>
			<p>F - Female</p>
			<p>NB – Non-Binary</p>
			<p>NS – Not specified (prefer not to say)</p>
		</td>
	</tr>
</table>
<table align="center" cellpadding="3" cellspacing="3">
  <tr>
  	<td>&nbsp;</td>
  <td>
	  	<select name="sort_order" id="sort_order" onchange="GetSort(this)">
			  <option value="">Please select a sort option</option>
			  <option value="players">Players</option>
			  <option value="memberid">Member ID</option>
			  <option value="life">Life Member</option>
			  <option value="ref">Referee</option>
			  <option value="gender">Gender</option>
			  <option value="junior">Junior</option>
			  <option value="coach">Coach</option>
			  <option value="ccc">CCC Player</option>
			  <option value="honorary">Honorary Member</option>
			  <option value="paid">Paid</option>
			</select> (Current Sort Order - <?= $sortOrder ?>)
		</td>
		<td colspan="3">&nbsp;</td>
		<td colspan="2">&nbsp;</td>
	</tr>
</table>
<br><br>
<table id="member-table" align="center" cellpadding="3" cellspacing="3" border='1'>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td colspan="2" align="center">Played in Current year</td> 
  </tr>
  <tr class="sticky-header">
    <td align="center">ID</td>
    <td align="left">Last Name</td>
    <td align="left">First Name</td>
    <td align="left">Address</td>
    <td align="left">Suburb</td>
    <td align="left">Post Code</td>
    <td align="left">State</td>
    <td align="left">Mobile Phone</td>
    <td align="left">Email</td>
    <td align="left">DoB</td>
    <td align="center">Life Member</td>
    <td align="center">Board Member</td>
    <td align="center">Assistant</td>
    <td align="center">Position</td>
    <td align="center">Gender</td>
    <td align="center">Junior</td>
    <td align="center">Referee</td>
    <td align="center">Ref Class</td>
    <td align="center">Coach</td>
    <td align="center">Coach Class</td>
    <td align="center">CCC Player</td>
    <td align="center">Honorary</td>
    <td align="center">Paid</td>
    <td align="center">Snooker</td>
    <td align="center">Billiards</td>
  </tr>
  <?php 
  	do {
	?>
	    <tr>
	      <td align="center"><?php echo $row_memb_bulk['MemberID']; ?></td>
	      <td align="left"><?php echo $row_memb_bulk['LastName']; ?></td>
	      <td align="left"><?php echo $row_memb_bulk['FirstName']; ?></td>
	      <td align="left"><?php echo $row_memb_bulk['HomeAddress']; ?></td>
	      <td align="left"><?php echo $row_memb_bulk['HomeSuburb']; ?></td>
	      <td align="left"><?php echo $row_memb_bulk['HomePostCode']; ?></td>
	      <td align="left"><?php echo $row_memb_bulk['HomeState']; ?></td>
	      <td align="left"><?php echo $row_memb_bulk['MobilePhone']; ?></td>
	      <td align="left"><?php echo $row_memb_bulk['Email']; ?></td>
	      <?php 
	      if($row_memb_bulk['dob_year'] != '')
	      {
	      	$dob_day = $row_memb_bulk['dob_day'];
	      	$dob_mnth = $row_memb_bulk['dob_mnth'];
	      	$dob_year = $row_memb_bulk['dob_year'];
	      	if(($dob_day != '') && ($dob_mnth != '') && ($dob_year != ''))
	      	{
	      		$date_of_birth = $row_memb_bulk['dob_day'] . '-' . $row_memb_bulk['dob_mnth'] . '-' . $row_memb_bulk['dob_year'];
	      	}
	      	if(($dob_day == '') && ($dob_mnth == '') && ($dob_year != ''))
	      	{
	      		$date_of_birth = $row_memb_bulk['dob_year'];
	      	}
	      	if(($dob_day != '') && ($dob_mnth != '') && ($dob_year == ''))
	      	{
	      		$date_of_birth = $row_memb_bulk['dob_day'] . '-' . $row_memb_bulk['dob_mnth'] . '-0000';
	      	}
	      }
      	else
      	{
      		$date_of_birth = '';
      	}
	      echo('<td align="center">' . $date_of_birth . '</td>');
	      if($row_memb_bulk['LifeMember'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='life' checked disabled ></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='life' disabled></td>");
	      }
	      if(($row_memb_bulk['board_member_id'] != '') && ($row_memb_bulk['display'] == 1) && ($row_memb_bulk['assist'] == 0))
	      {
	      	echo("<td align='center'><input type='checkbox' id='board' checked disabled ></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='board' disabled></td>");
	      }

	      if(($row_memb_bulk['board_member_id'] != '') && ($row_memb_bulk['display'] == 1) && ($row_memb_bulk['assist'] == 1))
	      {
	      	echo("<td align='center'><input type='checkbox' id='assist' checked disabled ></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='assist' disabled></td>");
	      }
	      echo("<td align='center'>" . $row_memb_bulk['board_desc'] . "</td>");
	      switch ($row_memb_bulk['Gender']) {
		      case "Male":
		        $gender_abv = 'M';
		        break;    
		      case "Female":
		        $gender_abv = 'F';
		        break;
		      case "NonBinary":
		        $gender_abv = 'NB';
		        break;
		      case "NoGender":
		       $gender_abv = 'NS';
		        break;
		      default;
		        $gender_abv = 'M';
		        break;
		    }
	      echo("<td align='center'>" . $gender_abv . "</td>");
	      if(($row_memb_bulk['Junior'] != 'na') && ($row_memb_bulk['Junior'] != 'U21'))
	      {
	      	echo("<td align='center'><input type='checkbox' id='junior' checked disabled ></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='junior' disabled></td>");
	      }
	      if($row_memb_bulk['referee'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='ref' checked disabled></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='ref' disabled></td>");
	      }
	      echo("<td align='center'>" . $row_memb_bulk['Ref_class'] . "</td>");
	      if($row_memb_bulk['active_coach'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='active_coach' checked disabled></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='active_coach' disabled></td>");
	      }
	      echo("<td align='center'>" . $row_memb_bulk['class'] . "</td>");
	      if($row_memb_bulk['ccc_player'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='ccc_player' checked disabled></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='ccc_player' disabled></td>");
	      }
	      if($row_memb_bulk['hon_memb'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='hon_memb' checked disabled></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='hon_memb' disabled></td>");
	      }
	      if($row_memb_bulk['paid_memb'] > 0)
	      {
	      	echo("<td align='center'><input type='checkbox' id='paid' checked disabled></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='paid' disabled></td>");
	      }
	      if($row_memb_bulk['CSnooker'] > 0)
	      {
	      	echo("<td align='center'><input type='checkbox' id='Snooker' checked disabled ></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='Snooker' disabled></td>");
	      }
	      if($row_memb_bulk['CBilliards'] > 0)
	      {
	      	echo("<td align='center'><input type='checkbox' id='Billiards' checked disabled ></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='Billiards' disabled></td>");
	      }
	      ?>
	    </tr>
	    <?php 
	  } while ($row_memb_bulk = mysql_fetch_assoc($memb_bulk)); 
  ?>
</table>
</form>

</body>
</html>

