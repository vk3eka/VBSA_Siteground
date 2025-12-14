<?php require_once('../Connections/connvbsa.php'); ?>
<?php include('../security_header.php'); ?>
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
$query_memb_display = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1 OR Gender != 'Male' OR affiliate_player = 1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 " . $sortby;
//echo($query_memb_display . "<br>");
$memb_display = mysql_query($query_memb_display, $connvbsa) or die(mysql_error());
$row_memb_display = mysql_fetch_assoc($memb_display);
$totalRows_memb_display = mysql_num_rows($memb_display);

$query_Count20 = "Select COUNT(paid_memb) FROM members WHERE paid_memb>0 AND YEAR(paid_date)=YEAR(NOW( ) )";
$Count20 = mysql_query($query_Count20, $connvbsa) or die(mysql_error());
$row_Count20 = mysql_fetch_assoc($Count20);
$totalRows_Count20 = mysql_num_rows($Count20);

// Male
$query_Count_Male = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND Gender = 'Male'";

$Count_Male = mysql_query($query_Count_Male, $connvbsa) or die(mysql_error());
$row_Count_Male = mysql_fetch_assoc($Count_Male);
$totalRows_Count_Male = mysql_num_rows($Count_Male);

//Female
$query_Count_Female = "Select * FROM members WHERE Gender = 'Female'";
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

$query_Count_Junior = "Select COUNT(MemberID) as total_junior FROM members WHERE junior != 'na'";
$Count_Junior = mysql_query($query_Count_Junior, $connvbsa) or die(mysql_error());
$row_Count_Junior = mysql_fetch_assoc($Count_Junior);
$totalRows_Count_Junior = mysql_num_rows($Count_Junior);

$query_lifemembers = "SELECT MemberID, LifeMember FROM members WHERE LifeMember>0";
$lifemembers = mysql_query($query_lifemembers, $connvbsa) or die(mysql_error());
$row_lifemembers = mysql_fetch_assoc($lifemembers);
$totalRows_lifemembers = mysql_num_rows($lifemembers);

$query_paid_memb = "SELECT MemberID, paid_memb FROM members WHERE paid_memb=20 AND YEAR(paid_date) = YEAR( CURDATE( ) )";
$paid_memb = mysql_query($query_paid_memb, $connvbsa) or die(mysql_error());
$row_paid_memb = mysql_fetch_assoc($paid_memb);
$totalRows_paid_memb = mysql_num_rows($paid_memb);

$query_refs = "SELECT COUNT(MemberID) AS tot_refs FROM members WHERE referee>0";
$refs = mysql_query($query_refs, $connvbsa) or die(mysql_error());
$row_refs = mysql_fetch_assoc($refs);
$totalRows_refs = mysql_num_rows($refs);

$query_ccc_players = "SELECT COUNT(MemberID) AS tot_ccc FROM members WHERE ccc_player>0";
$ccc_players = mysql_query($query_ccc_players, $connvbsa) or die(mysql_error());
$row_ccc_players = mysql_fetch_assoc($ccc_players);
$totalRows_ccc_players = mysql_num_rows($ccc_players);

$query_coaches = "Select COUNT(MemberID) AS tot_coaches FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE active_coach=1";
//echo $query_coaches . "<br>";
$coaches = mysql_query($query_coaches, $connvbsa) or die(mysql_error());
$row_coaches = mysql_fetch_assoc($coaches);
$totalRows_coaches = mysql_num_rows($coaches);

//$query_affiliates = "Select usertype FROM members LEFT JOIN vbsaorga_users2 ON MemberID = vbsa_id WHERE usertype != ''";
$query_affiliates = "Select COUNT(MemberID) as total_affiliate FROM members WHERE affiliate_player=1";
//echo $query_affiliates . "<br>";
$affiliates = mysql_query($query_affiliates, $connvbsa) or die(mysql_error());
$row_affiliates = mysql_fetch_assoc($affiliates);
$totalRows_affiliates = mysql_num_rows($affiliates);

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
        $sortOrder = 'Affiliate';
        break;
  default:
        $sortOrder = 'Players';
        break;
}

?>
<script type='text/javascript'>

function GetSort(sel) {
	var sort_order = sel.options[sel.selectedIndex].value;
	document.getElementById("sort_order").value = sort_order;
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
<form name='sort'  method="post" action='vbsa_members.php'>
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
    <td>Total Members: <?php echo $totalRows_memb_display ?></td>
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
    <td>Total Affiliates:<?php echo $row_affiliates['total_affiliate']; ?></td>
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
			  <option value="affiliate">Affiliate</option>
			  <option value="paid">Paid</option>
			  <option value="total">Total Games</option>
			</select> (Current Sort Order - <?= $sortOrder ?>)
		</td>
		<td colspan="2">&nbsp;</td>
	</tr>
</table>
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
    <td align="center" nowrap="nowrap">Affiliate</td>
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
	      <td align="center"><?php echo $row_memb_display['MemberID']; ?></td>
	      <td align="left"><?php echo $row_memb_display['LastName']; ?></td>
	      <td align="left"><?php echo $row_memb_display['FirstName']; ?></td>
	      <td class="page"><a href="tel:<?php echo $row_memb_display['MobilePhone']; ?>"><?php echo $row_memb_display['MobilePhone']; ?></a></td>
	      <td class="page"><a href="mailto:<?php echo $row_memb_display['Email']; ?>" target="_blank"><?php echo $row_memb_display['Email']; ?></a></td>
	      <?php 
	      if($row_memb_display['ReceiveEmail'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='ReceiveEmail' checked disabled ></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='ReceiveEmail' disabled></td>");
	      }
	      if($row_memb_display['ReceiveSMS'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='ReceiveSMS' checked disabled ></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='ReceiveSMS' disabled></td>");
	      }
	      echo("<td align='left'>" . $row_memb_display['memb_occupation'] . "</td>");
	      if($row_memb_display['LifeMember'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='life' checked disabled ></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='life' disabled></td>");
	      }
	      switch ($row_memb_display['Gender']) {
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
	      if($row_memb_display['Junior'] != 'na')
	      {
	      	echo("<td align='center'><input type='checkbox' id='junior' checked disabled ></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='junior' disabled></td>");
	      }
	      if($row_memb_display['referee'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='ref' checked disabled></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='ref' disabled></td>");
	      }
	      if($row_memb_display['active_coach'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='active_coach' checked disabled></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='active_coach' disabled></td>");
	      }
	      if($row_memb_display['ccc_player'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='ccc_player' checked disabled></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='ccc_player' disabled></td>");
	      }
	      if($row_memb_display['affiliate_player'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='affiliate_player' checked disabled></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='affiliate_player' disabled></td>");
	      }
	      ?>
	      <td align="center"><?php echo $row_memb_display['paid_memb']; ?></td>
	      <td align="center"><?php echo ($row_memb_display['CSnooker']+$row_memb_display['CBilliards']); ?></td>
	      <td align="center"><?php echo ($row_memb_display['CSnooker']); ?></td>
	      <td align="center"><?php echo ($row_memb_display['CBilliards']); ?></td>
	      <td><a href="../A_common/vbsa_personal_detail.php?memb_id=<?php echo $row_memb_display['MemberID']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" title="detail" /></a></td>
	      <td align="center"><a href="../A_common/vbsa_member_edit.php?memb_id=<?php echo $row_memb_display['MemberID']; ?>"><img src="../Admin_Images/edit_butt.fw.png" width="20" /> </a></td>
	      <td align="center" nowrap="nowrap">
	        <?php if(isset($row_memb_display['memb_by'])) echo "<font color=green>Yes</font>"; else echo "<font color=red>No</font>"; ?>  
	      </td>
	      <td align="center" nowrap="nowrap" class="greenbg"><a href="../A_common/vbsa_member_edit_form.php?memb_id=<?php echo $row_memb_display['MemberID']; ?>" title="Insert / update membership form details">Memb</a> </td>
	    </tr>
	    <?php 
	  } while ($row_memb_display = mysql_fetch_assoc($memb_display)); 
  ?>
</table>
</form>
<br>
</center>
</body>
</html>
