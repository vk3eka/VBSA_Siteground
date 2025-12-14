<?php require_once('../Connections/connvbsa.php');

include('../security_header.php'); 

error_reporting(0);

$page = "../Admin_DB_VBSA/vbsa_members_filter.php";
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
		case 'total':
			$sortby = "Order By Current DESC";
			break;
		case 'affiliate':
			$sortby = "Order By affiliate_player DESC, LastName, FirstName";
			break;
		default:
			$sortby = "Order By LastName, FirstName";
			break;
	}
}
else
{
	$sortby = "Order By paid_memb DESC, LifeMember DESC, Referee DESC, active_coach DESC, ccc_player DESC, LastName, FirstName";
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_memb_bulk = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1 OR Gender!='Male') AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 " . $sortby;
$memb_bulk = mysql_query($query_memb_bulk, $connvbsa) or die(mysql_error());
$row_memb_bulk = mysql_fetch_assoc($memb_bulk);
$totalRows_memb_bulk = mysql_num_rows($memb_bulk);

// default $query_memb
$query_memb = "Select * FROM members WHERE affiliate_player = 1";
//$query_memb = "Select * FROM members WHERE MemberID = 0";

$filter_title = '';
if((isset($_POST['filter_select'])) && ($_POST['filter_select'] == 'Filter'))
{
	if(($_POST['filter_array'] == 'all, ') || ($_POST['filter_array'] == 'all'))
	{
		//echo("Query for all members<br>");
		$query_memb = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1 OR Gender != 'Male' OR affiliate_player = 1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND (ReceiveEmail = 1 AND Email != '')"; 
		$filter_title = 'all';
	}
	else
	{
		//echo("Filter Set.<br>");
		$filter_elements = explode(", ", $_POST['filter_array']);
		$filterby = '';
		$filter_text = '';
		$x = 0;
		foreach($filter_elements as $elements)
		{
			$filter_element_title = '';
			if($x == 0)
			{
				$and_or = ' AND ';
			}
			else
			{
				$and_or = ' OR ';
			}
			switch ($elements) {
				case 'gender':
					$filterby = $and_or . " Gender != 'Male' ";
					$filter_element_title = 'gender';
					break;
				case 'junior':
					$filterby = $and_or . " Junior != 'na' ";
					$filter_element_title = 'junior';
					break;
				case 'life':
					$filterby = $and_or . " LifeMember = 1 ";
					$filter_element_title = 'life';
					break;
				case 'referee':
					$filterby = $and_or . " referee = 1 ";
					$filter_element_title = 'referee';
					break;
				case 'coach':
					$filterby = $and_or . " active_coach = 1 ";
					$filter_element_title = 'coach';
					break;
				case 'ccc':
					$filterby = $and_or . " ccc_player = 1 ";
					$filter_element_title = 'ccc';
					break;
				case 'paid':
					$filterby = $and_or . " (paid_memb = 20 AND YEAR(paid_date) = YEAR(NOW())) ";
					$filter_element_title = 'paid';
					break;
				case 'affiliate':
					$filterby = $and_or . " affiliate_player = 1 ";
					$filter_element_title = 'affiliate';
					break;
				default:
					$filterby = "";
					$filter_element_title = '';
					break;
			}
			$filter_text = $filter_text . " " . $filterby;
			$filter_title = $filter_title . ", " . $filter_element_title;
			$x++;
		}
		//echo("Query for filtered " . $filter_text . " members<br>");
		$query_memb = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND ReceiveEmail = 1 AND Email != '' " . $filter_text;
	}

}
else
{
	//echo("Filter NOT Set.<br>");
}


//else
//{
//	echo("Query if nothing selected at all<br>");
//	$query_memb = "Select * FROM members WHERE MemberID = 1";
//}

//$query_memb = "Select MemberID, LastName, FirstName, HomePhone, MobilePhone, Email, ReceiveEmail, Junior, dob_day, dob_mnth, dob_year, Homestate FROM members WHERE dob_year between YEAR( CURDATE( ) ) -18 AND YEAR( CURDATE( ) ) AND (ReceiveEmail = 1 AND Email != '') ORDER BY LastName";

//$query_memb = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, junior, referee, coach_id, ccc_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (LifeMember=1 OR Female=1 OR Junior!='na' OR (totplayed_curr+totplaybill_curr) > 0 OR ccc_player=1 OR referee=1 AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1 OR active_coach = 1 AND curr_memb = 0)) AND (ReceiveEmail = 1 AND Email != '') order by MemberID DESC";

//echo("Mail query " . $query_memb . "<br><br>");
$memb = mysql_query($query_memb, $connvbsa) or die(mysql_error());
$row_memb = mysql_fetch_assoc($memb);
$totalRows_memb = mysql_num_rows($memb);

$myRecordset=$memb; $myTotalRecords=$totalRows_memb;

include 'php_mail_include.php'; // local file with the previous emailling code


// Male
$query_Count_Male = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND Gender = 'Male'";
$Count_Male = mysql_query($query_Count_Male, $connvbsa) or die(mysql_error());
$row_Count_Male = mysql_fetch_assoc($Count_Male);
$totalRows_Count_Male = mysql_num_rows($Count_Male);

//Female
$query_Count_Female = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (Gender = 'Female') AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0";
$Count_Female = mysql_query($query_Count_Female, $connvbsa) or die(mysql_error());
$row_Count_Female = mysql_fetch_assoc($Count_Female);
$totalRows_Count_Female = mysql_num_rows($Count_Female);

// Non Binary
$query_Count_NonBinary = "Select Count(MemberID) as tot_NB FROM members WHERE ((((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) )) AND curr_memb = 0 AND Gender = 'NonBinary'";
$Count_NonBinary = mysql_query($query_Count_NonBinary, $connvbsa) or die(mysql_error());
$row_Count_NonBinary = mysql_fetch_assoc($Count_NonBinary);

//No Gender
$query_Count_NoGender = "Select Count(MemberID) as tot_NG FROM members WHERE ((((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) )) AND curr_memb = 0 AND Gender = 'NoGender'";
$Count_NoGender = mysql_query($query_Count_NoGender, $connvbsa) or die(mysql_error());
$row_Count_NoGender = mysql_fetch_assoc($Count_NoGender);

//Junior
$query_Count_Junior = "Select COUNT(MemberID) as total_junior FROM members WHERE junior != 'na'";
$Count_Junior = mysql_query($query_Count_Junior, $connvbsa) or die(mysql_error());
$row_Count_Junior = mysql_fetch_assoc($Count_Junior);
$totalRows_Count_Junior = mysql_num_rows($Count_Junior);

//Life Member
$query_lifemembers = "SELECT MemberID, LifeMember FROM members WHERE LifeMember>0";
$lifemembers = mysql_query($query_lifemembers, $connvbsa) or die(mysql_error());
$row_lifemembers = mysql_fetch_assoc($lifemembers);
$totalRows_lifemembers = mysql_num_rows($lifemembers);

//Paid
$query_paid_memb = "SELECT MemberID, paid_memb FROM members WHERE paid_memb=20 AND YEAR(paid_date) = YEAR( CURDATE( ) )";
$paid_memb = mysql_query($query_paid_memb, $connvbsa) or die(mysql_error());
$row_paid_memb = mysql_fetch_assoc($paid_memb);
$totalRows_paid_memb = mysql_num_rows($paid_memb);

//Referee
$query_refs = "SELECT COUNT(MemberID) AS tot_refs FROM members WHERE referee>0";
$refs = mysql_query($query_refs, $connvbsa) or die(mysql_error());
$row_refs = mysql_fetch_assoc($refs);
$totalRows_refs = mysql_num_rows($refs);

//CCC
$query_ccc_players = "SELECT COUNT(MemberID) AS tot_ccc FROM members WHERE ccc_player>0";
$ccc_players = mysql_query($query_ccc_players, $connvbsa) or die(mysql_error());
$row_ccc_players = mysql_fetch_assoc($ccc_players);
$totalRows_ccc_players = mysql_num_rows($ccc_players);

//Coaches
$query_coaches = "Select COUNT(MemberID) AS tot_coaches FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE active_coach=1";
$coaches = mysql_query($query_coaches, $connvbsa) or die(mysql_error());
$row_coaches = mysql_fetch_assoc($coaches);
$totalRows_coaches = mysql_num_rows($coaches);

//Affiliates
$query_Count_Affiliate = "Select count(affiliate_player) as tot_affiliate FROM members WHERE affiliate_player=1";
$Count_Affiliate = mysql_query($query_Count_Affiliate, $connvbsa) or die(mysql_error());
$row_Count_Affiliate = mysql_fetch_assoc($Count_Affiliate);
$totalRows_Count_Affiliate = mysql_num_rows($Count_Affiliate);

include 'php_mail_include.php'; // local file with the previous emailling code

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
  case 'affiliate':
        $sortOrder = 'Affiliate Player';
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

function filter_members() {
	var all_records = document.getElementById('all').checked;
	var filter_array = [];
	if(all_records == true)
	{
		filter_array += 'all';
	}
	else
	{
		var cboxes = document.getElementsByClassName('query_select');
    var len = cboxes.length;
    for (var i=0; i<len; i++) {
        if(cboxes[i].checked)
        {
        	filter_array += cboxes[i].value + ', ';
        }
    }
  }
  document.sort.filter_array.value = filter_array;
  document.sort.filter_select.value = "Filter";
  document.sort.action = "vbsa_members_filter.php?put_peram=token#filter_anchor"
  if(filter_array == '')
  {
  	alert("Nothing has been selected!");
  	return;
  }
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
</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<form name='sort'  method="post" action='vbsa_members_filter.php'>
<input type='hidden' name='filter_select'>
<input type='hidden' name='filter_array'>
<table width="1000" align="center">
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="center" nowrap="nowrap"><span class="red_bold" >Players that satisfy Membership requirements in <?php echo date("Y") ?></span><span class="greenbg">&nbsp;&nbsp;&nbsp; <a href="user_files/member.php">When is a person considered a member?</a></span></td>
  </tr>
  <tr>
    <td class="greenbg">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td class="greenbg"><a href="../A_common/vbsa_member_insert.php">Insert a new person to the members table</a></td>
  	<td colspan="2" class="greenbg" align="center"><a href="export_csv.php?page=vbsa_members">Export Current Data To CSV File</a></td>
    <td align="right" class="greenbg"><a href="A_memb_index.php">Return to Members index</a></td>
  </tr>
  <tr>
    <td colspan="4" align="left">&nbsp;</td>
  </tr>
</table>
<table width="1000" align="center">
  <tr>
    <td>Total Members: <?php echo $totalRows_memb_bulk ?></td>
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
    <td>Total Affiliate:<?php echo $row_Count_Affiliate['tot_affiliate']; ?></td>
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
			  <option value="affiliate">Affiliate Player</option>
			  <option value="paid">Paid</option>
			  <option value="total">Total Games</option>
			</select> (Current Sort Order - <?= $sortOrder ?>)
		</td>
		<td colspan="2">&nbsp;</td>
	</tr>
</table>
<?php

$checkboxes = explode(', ', $filter_title);
foreach($checkboxes as $checkbox)
{
	if($checkbox != '')
	{
		echo("<script type='text/javascript'>");
		echo("$(document).ready(function()");
		echo("{");
		echo("$('input[id=" . $checkbox . "]').prop('checked', true);");
		echo("});");
		echo("</script>");
	}
}

?>
<script>

$(document).ready(function()
{
	$('input.query_select').on('change', function() {
	  $('#all').attr('checked', false);  
	});

	$('#all').on('change', function() {
	  $('input.query_select').attr('checked', false);  
	});

});
</script>

<table align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td align="center">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td align="left" nowrap="nowrap">&nbsp;</td>
    <td align="left" nowrap="nowrap">&nbsp;</td>
    <td align="center" nowrap="nowrap">&nbsp;</td>
    <td align="center" nowrap="nowrap">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td colspan="3" align="center">Matches in Current year</td>
    <td align="center">&nbsp;</td>
   
  </tr>
  <tr>
    <td align="center">ID</td>
    <td align="left">Last Name</td>
    <td align="left">First Name</td>
    <td align="left" nowrap="nowrap">Mobile Phone</td>
    <td align="left" nowrap="nowrap">Email</td>
    <td align="left" nowrap="nowrap">Rec. Email</td>
    <td align="left" nowrap="nowrap">Rec. SMS</td>
    <td align="left" nowrap="nowrap">Occupation</td>
    <td align="center" nowrap="nowrap">Life Member</td>
    <td align="center">Gender</td>
    <td align="center">Junior</td>
    <td align="center">Referee</td>
    <td align="center">Coach</td>
    <td align="center" nowrap="nowrap">CCC Player</td>
    <td align="center" nowrap="nowrap">Affiliate Player</td>
    <td align="center">Paid</td>
    <td align="center">Total</td>
    <td align="center">Snooker</td>
    <td align="center">Billiards</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">M'ship data</td>
    <td align="center">&nbsp;</td>
  </tr>
  <?php 
  	do {
	?>
	    <tr>
	      <td align="center"><?php echo $row_memb_bulk['MemberID']; ?></td>
	      <td align="left"><?php echo $row_memb_bulk['LastName']; ?></td>
	      <td align="left"><?php echo $row_memb_bulk['FirstName']; ?></td>
	      <td class="page"><a href="tel:<?php echo $row_memb_bulk['MobilePhone']; ?>"><?php echo $row_memb_bulk['MobilePhone']; ?></a></td>
	      <td class="page"><a href="mailto:<?php echo $row_memb_bulk['Email']; ?>" target="_blank"><?php echo $row_memb_bulk['Email']; ?></a></td>
	      <?php 
	      if($row_memb_bulk['ReceiveEmail'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='ReceiveEmail' checked disabled ></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='ReceiveEmail' disabled></td>");
	      }
	      if($row_memb_bulk['ReceiveSMS'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='ReceiveSMS' checked disabled ></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='ReceiveSMS' disabled></td>");
	      }

	      echo("<td align='left'>" . $row_memb_bulk['memb_occupation'] . "</td>");

	      if($row_memb_bulk['LifeMember'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='life' checked disabled ></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='life' disabled></td>");
	      }

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
	      echo("<td align='left'>" . $gender_abv . "</td>");
	      /*
	      if($row_memb_bulk['Female'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='woman' checked disabled ></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='woman' disabled></td>");
	      }
				*/
	      if($row_memb_bulk['Junior'] != 'na')
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
	      
	      if($row_memb_bulk['active_coach'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='active_coach' checked disabled></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='active_coach' disabled></td>");
	      }
	     
	      if($row_memb_bulk['ccc_player'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='ccc_player' checked disabled></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='ccc_player' disabled></td>");
	      }
	      
	      if($row_memb_bulk['affiliate_player'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='affiliate_player' checked disabled></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='affiliate_player' disabled></td>");
	      }

	      ?>
	      <td align="center"><?php echo $row_memb_bulk['paid_memb']; ?></td>
	      <td align="center"><?php echo ($row_memb_bulk['CSnooker']+$row_memb_bulk['CBilliards']); ?></td>
	      <td align="center"><?php echo ($row_memb_bulk['CSnooker']); ?></td>
	      <td align="center"><?php echo ($row_memb_bulk['CBilliards']); ?></td>
	      <td><a href="../A_common/vbsa_personal_detail.php?memb_id=<?php echo $row_memb_bulk['MemberID']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" title="detail" /></a></td>
	      <td align="center"><a href="../A_common/vbsa_member_edit.php?memb_id=<?php echo $row_memb_bulk['MemberID']; ?>"><img src="../Admin_Images/edit_butt.fw.png" width="20" /> </a></td>
	      <td align="center" nowrap="nowrap">
	        <?php if(isset($row_memb_bulk['memb_by'])) echo "<font color=green>Yes</font>"; else echo "<font color=red>No</font>"; ?>  
	      </td>
	      <td align="center" nowrap="nowrap" class="greenbg"><a href="../A_common/vbsa_member_edit_form.php?memb_id=<?php echo $row_memb_bulk['MemberID']; ?>" title="Insert / update membership form details">Memb</a> </td>
	    </tr>
	    <?php 
	  } while ($row_memb_bulk = mysql_fetch_assoc($memb_bulk)); 
  ?>
</table>
</form>

<form action="" method="post" name="editor_form" id="editor_form">
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="134" class="page">&nbsp;</td>
    <td width="551" align="right" class="greenbg">&nbsp;</td>
  </tr>
  <tr>
    <td>Would you like to send an attachment?</td>
    <td>&nbsp;</td>
    <td class="greenbg"><a href="../Admin_DB_VBSA/attach_upload.php">Please upload it now</a></td>
    <td align="right" class="greenbg"><a href="../Admin_DB_VBSA/Bulk_email_help.pdf">Bulk Email help</a></td>
  </tr>
  <tr>
    <td width="211" align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>To Send a group email: </td>
    <td width="11">&nbsp;</td>
    <td colspan="2">1. Type your email address in both the &quot;From&quot; and the &quot;Reply to&quot; fields.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">2. Type your name, e.g. &quot;VBSA Secratary&quot; in the &quot;Name&quot; field.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">3. From the &quot;Recordset fields&quot; select &quot;Email&quot;. Click the <img src="php_mail_merge/dynamic_e.gif" alt="1" width="17" height="17" /> button and it will add this field into the &quot;To&quot; field.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">4. Enter the subject fo your email.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">5. If required, attach a file. See above. Only certain file types allowed to be attached.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">6. Select &quot;Design View&quot;. This allows a greater degree of formatting options.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">7. Type your message.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">8. To personalise your message, at the start of the message area, type a greeting e.g. &quot;Hi&quot;
followed by a space. Then from &quot;Recordset fields&quot; select &quot;Firstname&quot;. Click the <img src="php_mail_merge/dynamic_t.gif" alt="1" /> button
and it will add the &quot;Firstname&quot; field into the Message box. This will reflect the first name
of the person to receive the email. You can add additional personalisations if you wish.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">9. Click &quot;Send&quot; then OK to Continue when prompted.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">10. Bulk Emails are only sent to members who have consented to receive emails AND have a valid email address.</td>
  </tr>
</table>
<br />
<br>
<table border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan='8' align='center'><b>Bulk Email Filter</b></td>
  </tr>
  <tr>
		<td colspan='8' align='center'>&nbsp;</td>
  </tr>
  <tr>
		<td colspan='8' align='center'>Click all checkboxes that you need to send emails to (Consent to email is given).</td>
  </tr>
  <tr>
		<td colspan='8' align='center'>The 'Total of x messages pending' will change to show the number of messages in the category/s checked.</td>
  </tr>
  <tr>
		<td colspan='8' align='center'>No emails are sent unless the checkboxes are checked.</td>
  </tr>
  <tr>
		<td colspan='8' align='center' style="color:red">Click on the 'Change Filter Selection' to set the filter.</td>
  </tr>
   <tr>
		<td colspan='8' align='center'>&nbsp;</td>
  </tr>
  <tr>
		<td colspan='8' align='center'><input type='checkbox' name='all' id='all' value='all'>&nbsp;&nbsp;All Members</td>
  </tr>
  <tr>
		<td width="120" align='center'><input type='checkbox' class='query_select' value='life' id='life'>&nbsp;&nbsp;Life Members</td>
		<td width="120" align='center'><input type='checkbox' class='query_select' value='coach' id='coach'>&nbsp;&nbsp;Coaches</td>
		<td width="120" align='center'><input type='checkbox' class='query_select' value='referee' id='referee'>&nbsp;&nbsp;Referees</td>
		<td width="120" align='center'><input type='checkbox' class='query_select' value='affiliate' id='affiliate'>&nbsp;&nbsp;Affiliates</td>
		<td width="120" align='center'><input type='checkbox' class='query_select' value='gender' id='gender'>&nbsp;&nbsp;F/NB/NS</td>
		<td width="120" align='center'><input type='checkbox' class='query_select' value='junior' id='junior'>&nbsp;&nbsp;Junior</td>
		<td width="120" align='center'><input type='checkbox' class='query_select' value='ccc' id='ccc'>&nbsp;&nbsp;CCC Players</td>
		<td width="120" align='center'><input type='checkbox' class='query_select' value='paid' id='paid'>&nbsp;&nbsp;Paid</td>
  </tr>
  <tr>
		<td colspan='8' align='center'>&nbsp;</td>
  </tr>
  <tr>
		<td colspan='8' align='center' class="greenbg"><a name="filter_anchor" href="#filter_anchor" onclick='filter_members();'>Change Filter Selection</a></td>
  </tr>
</table>
<br>
<table width="960" border="0" align="center" cellpadding="3" cellspacing="0" id="filters">
  <tr>
    <td title="Area designated for Recordset filters (form fields)"><fieldset>
      <legend>Filters</legend>
      <br />
      <br />
      Reset Editor:
      <input name="reset_editor" type="checkbox" id="reset_editor" title="Reset Editor fields when filtering the Recordset" value="1" />
      <input name="Filter" type="submit" value="Filter" onclick="refreshSource();document.getElementById('Do_Send').value=''" id="Filter" title="Filter the Recordset."/>
    </fieldset></td>
  </tr>
</table>
<?php 
//echo($query_memb . "<br>");

include("php_mail_merge.php"); 

?>
</form>
<script language="javascript" src="php_mail_merge/php_mail_merge.js" type="text/javascript">
</script>
<script language="javascript" type="text/javascript">initPMM();<?php if (isset($_POST["SendAs"]) && !isset($_POST['reset_editor'])){?>flipMessageFormat(document.getElementById('SendAs'));<?php }?></script>
</body>
</html>

