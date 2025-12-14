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
		/*case 'community':
			$sortby = "Order By community DESC, LastName, FirstName";
			break;*/
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

mysql_select_db($database_connvbsa, $connvbsa);
$query_memb_bulk = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, referee, coach_id, ccc_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach, dob_year, Junior, hon_memb, community FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR (Junior !='na' AND Junior !='U21') OR active_coach=1 OR Gender!='Male' OR hon_memb=1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 " . $sortby;

$query_memb = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach, hon_memb, community FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR (Junior !='na' AND Junior !='U21') OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1 OR Gender != 'Male' OR hon_memb=1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND (ReceiveEmail = 1 AND Email != '')";

//echo("Mail " . $query_memb . "<br><br>");
$memb = mysql_query($query_memb, $connvbsa) or die(mysql_error());
$row_memb = mysql_fetch_assoc($memb);
$totalRows_memb = mysql_num_rows($memb);

//echo("Bulk " . $query_memb_bulk . "<br>");
$memb_bulk = mysql_query($query_memb_bulk, $connvbsa) or die(mysql_error());
$row_memb_bulk = mysql_fetch_assoc($memb_bulk);
$totalRows_memb_bulk = mysql_num_rows($memb_bulk);

//$myRecordset=$memb_bulk; $myTotalRecords=$totalRows_memb_bulk;
$myRecordset=$memb; $myTotalRecords=$totalRows_memb;

//mysql_select_db($database_connvbsa, $connvbsa);
$query_Count20 = "SELECT COUNT(paid_memb) FROM members WHERE paid_memb>0 AND YEAR(paid_date)=YEAR(NOW( ) )";
$Count20 = mysql_query($query_Count20, $connvbsa) or die(mysql_error());
$row_Count20 = mysql_fetch_assoc($Count20);
$totalRows_Count20 = mysql_num_rows($Count20);

// Male
//$query_Count_Male = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND Gender = 'Male'";
$query_Count_Male = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach, hon_memb, community FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1 OR hon_memb = 1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND Gender = 'Male'";

$Count_Male = mysql_query($query_Count_Male, $connvbsa) or die(mysql_error());
$row_Count_Male = mysql_fetch_assoc($Count_Male);
//$total_male = $row_Count_Male['total_male'];
$totalRows_Count_Male = mysql_num_rows($Count_Male);

//Female
//$query_Count_Female = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (Gender = 'Female') AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0";
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
$query_Count_Junior = "Select COUNT(MemberID) as total_junior FROM members WHERE (Junior != 'na' and Junior != 'U21')";
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

/*
$query_community = "Select COUNT(MemberID) AS tot_community FROM members WHERE community=1";
$community = mysql_query($query_community, $connvbsa) or die(mysql_error());
$row_community = mysql_fetch_assoc($community);
$totalRows_community = mysql_num_rows($community);
*/

//Affiliates

/*
$query_Count_Affiliate = "Select count(affiliate_player) as tot_affiliate FROM members WHERE affiliate_player=1";
$Count_Affiliate = mysql_query($query_Count_Affiliate, $connvbsa) or die(mysql_error());
$row_Count_Affiliate = mysql_fetch_assoc($Count_Affiliate);
$totalRows_Count_Affiliate = mysql_num_rows($Count_Affiliate);
*/
//include 'php_mail_include.php'; // local file with the previous emailling code


/*PHP EMAIL MERGE 2013 - COPYRIGHT ALEX JULY (LINECRAFT STUDIO)*/
//Account for magic_quotes
//Commented out by Alec Spyrou 26.03.22 Deprecated PHP function
//if (get_magic_quotes_gpc()) {
//    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
//    while (list($key, $val) = each($process)) {
//        foreach ($val as $k => $v) {
//            unset($process[$key][$k]);
//            if (is_array($v)) {
//                $process[$key][stripslashes($k)] = $v;
//                $process[] = &$process[$key][stripslashes($k)];
//            } else {
//                $process[$key][stripslashes($k)] = stripslashes($v);
//            }
//        }
//    }
//    unset($process);
//}

//Saving file locally?
if(isset($_POST["m_source"]) && $_POST["Do_Send"]=="save"){
	header("Content-Type: application/html");
	header("Content-Disposition:attachment; filename=template.html");
	header("Content-Type: application/force-download");
	header("Cache-Control: post-check=0, pre-check=0", false);
	echo $_POST["m_source"];
	die();
}

//If PEAR is not installed - send using sendmail
@include_once("Mail.php");
@include_once("Mail/mime.php");
if(!class_exists('Mail') || !class_exists('Mail_mime')){
	$pear_enabled=false;
}else{
	$pear_enabled=true;
}

//Mail function triggered?
if(isset($_POST["Do_Send"]) && $_POST["Do_Send"]=="Send"){
	function bind_fields($field,$rs,$row){
	  $totalColumns=mysql_num_fields($rs);
	  $hatch=$field;
		  for ($x=0; $x<$totalColumns; $x++) {
			  $fieldName=mysql_field_name($rs, $x);
			  $hatch=str_replace("##".$fieldName."##",$row[$fieldName],$hatch);
		  }
		  return ($hatch);
	}
	function bind_email($field,$row){
		$fieldName=substr($field,2,strlen($field)-4);
		$hatch=$row[$fieldName];
		return ($hatch);
	}
	function win_checkdnsrr($hostName){
	$isvalid=false;
	if(!empty($hostName)) { 
	   exec("nslookup ".$hostName, $result); 
	   foreach ($result as $line) {
		 if(preg_match("/$hostName/i",$line)) {
		 	$isvalid=true;
		 } 		 
	   } 
	 }
	return  $isvalid; 
	}
	
	$replyto=$_POST["ReplyTo"];
	$return_path=$_POST["ReturnPath"];
	
	$current_record=0;
	$valid_email=true;
	do {
		$current_record++;
		if($_POST['StartNumber']==$current_record){
			$error="";
			//Send the message
			$to=($_POST["SendMode"]!="Test")?(bind_email($_POST["To"], $row_memb)):$_POST["To"];
			//If email field is empty - error
			if($to=="" || stristr($to,"@")===FALSE){
				$error=$to." - no email\r\n";
				break;
			}
			list($user, $domain) = explode("@", $to);
			//Email is valid by default
			$valid_email=true;
			//Validate if required
			if($_POST["Validate"]=="1"){
				if(function_exists('checkdnsrr')){
					$valid_email=checkdnsrr($domain.".","MX");
				}
				else{
					$valid_email=win_checkdnsrr($domain);
				}
			}
			if (!$valid_email){
				$error=$to."(Non-existent domain) \r\n";
			}else{
				$regex="/##"."[A-Za-z0-9\-\_]+"."##/";
				//Find if there is possible bound field in the subject line
				$dyn_subject=preg_match($regex,$_POST["Subject"]);
				//Find if there is possible bound field in the message box
				$dyn_message=preg_match($regex,$_POST["m_source"]);
				//From fields
				$subject=$_POST["Subject"];
				$message=$_POST["m_source"];

				
				//Bind fields if the subject contains variables
				if($dyn_subject===1){
					//$subject=bind_fields($_POST["Subject"],$memb_bulk,$row_memb);
					$subject=bind_fields($_POST["Subject"],$memb,$row_memb);
				}
				//Bind fields if the message contains variables
				if($dyn_message===1){
					//$message=bind_fields($_POST["m_source"],$memb_bulk,$row_memb);
					$message=bind_fields($_POST["m_source"],$memb,$row_memb);
				}
				$email=$_POST["From"];
				$realname=$_POST["FromName"]; 
				$from=$realname."<".$email.">";
	
				//If PEAR is not installed - send using sendmail
				if(!$pear_enabled){
					//If it is a plain text
					$html_header="Content-type: text/plain;";
					if($_POST["SendAs"]!="Plain Text"){
						$html_header="Content-type: text/html;";
					}
					$html_header.=" charset=".$_POST["encoding"].";";
					$headers="From:".$from."\r\n";
					$headers.=($replyto!="")?("Reply-To:".$replyto."\r\n"):"";
					$headers.="MIME-Version: 1.0\n";
					$headers.=$html_header;

					if($return_path!=""){
						$mail_sent=mail($to, stripslashes($subject),stripslashes($message), $headers, $return_path);
					}else{
						$mail_sent=mail($to, stripslashes($subject),stripslashes($message), $headers);
					}
					if(!$mail_sent){
						$error=$to."\n";
					}
				}else{
					$host=$_POST["Host"];
					$username=$_POST["UserName"];
					$password=$_POST["Password"];
					if($host!="" && $username!="" && $password!=""){
						$smtp_array = array ('host' => $host,'auth' => true,'username' => $username,'password' => $password);
						if ($return_path!=""){
							$headers['Return-Path']=$return_path;
							$smtp_array = array ('host' => $host,'auth' => true,'username' => $username,'password' => $password,'Return-Path' => $return_path);
						}
						$contructor = Mail::factory('smtp',$smtp_array);
					}else{
						$contructor = ($return_path=="")?(Mail::factory('mail')):(Mail::factory('mail', array('Return-Path' => $return_path)));
					}
					$headers = array ('From' => $from,'To' => $to,'Subject' => $subject);
					if($replyto!=""){$headers['Reply-To']=$replyto;}
					if($return_path==""){$headers['Return-Path']=$return_path;}
					
					$mime = new Mail_mime("\n");
					if(isset($_POST["FileAttachment"]) && $_POST["FileAttachment"]!=""){
						if(file_exists($_POST["FileAttachment"])){
							$mime->addAttachment(file_get_contents($_POST["FileAttachment"]),"application/octet-stream",basename($_POST["FileAttachment"]),0);
						}else{
							$error="Error attaching file (wrong path)\n";
						}
					}
					//Send if no errors
					if($error==""){
						if($_POST["SendAs"]!="Plain Text"){
							$mime->setHTMLBody($message);
							$message = $mime->get(array('html_charset' => $_POST["encoding"]));
						}else{
							$mime->setTxtBody($message);
							$message = $mime->get(array('text_charset' => $_POST["encoding"]));
						}
						$headers = $mime->headers($headers);
						$mail = $contructor->send($to, $headers, $message);
				
						if (PEAR::isError($mail)){
							$error=$error.$to."\n";
						}
					}
				}
			}
			break;
		}
	//}while ($row_memb = mysql_fetch_assoc($memb_bulk)); 
	}while ($row_memb = mysql_fetch_assoc($memb)); 
	//Render Server Response
	header("Content-Type: text/xml");
	//echo "<sent to=\"".$to."\" total=\"".$totalRows_memb_bulk."\"><errors>".$error."</errors></sent>";
	echo "<sent to=\"".$to."\" total=\"".$totalRows_memb."\"><errors>".$error."</errors></sent>";
	die();
}

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
<form name='sort'  method="post" action='vbsa_members.php'>
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
  <!--<tr>
    <td>Total Community Members: <?php echo $totalRows_community ?></td>
    <td colspan='3'></td>
  </tr>-->
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
			  <!--<option value="community">Community Member</option>-->
			  <option value="membership">Membership Data</option>
			  <option value="paid">Paid</option>
			  <option value="total">Total Games</option>
			</select> (Current Sort Order - <?= $sortOrder ?>)
		</td>
		<td colspan="2">&nbsp;</td>
	</tr>
</table>
<table id="member-table" align="center" cellpadding="3" cellspacing="3">
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
    <!--<td align="center">&nbsp;</td>-->
    <td colspan="3" align="center">Matches in Current year</td>
    <td align="center">&nbsp;</td>
   
  </tr>
  <tr class="sticky-header">
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
    <td align="center" nowrap="nowrap">Honorary</td>
    <!--<td align="center" nowrap="nowrap">Community</td>-->
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
	      
	      if($row_memb_bulk['hon_memb'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='hon_memb' checked disabled></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='hon_memb' disabled></td>");
	      }
	      
	      /*
	      if($row_memb_bulk['community'] == 1)
	      {
	      	echo("<td align='center'><input type='checkbox' id='community' checked disabled></td>");
	      }
	      else
	      {
	      	echo("<td align='center'><input type='checkbox' id='community' disabled></td>");
	      }
				*/
	      ?>
	      <td align="center"><?php echo $row_memb_bulk['paid_memb']; ?></td>
	      <td align="center"><?php echo ($row_memb_bulk['CSnooker']+$row_memb_bulk['CBilliards']); ?></td>
	      <td align="center"><?php echo ($row_memb_bulk['CSnooker']); ?></td>
	      <td align="center"><?php echo ($row_memb_bulk['CBilliards']); ?></td>
	      <td><a href="../A_common/vbsa_personal_detail.php?memb_id=<?php echo $row_memb_bulk['MemberID']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" title="detail" /></a></td>
	      <td align="center"><a href="../A_common/vbsa_member_edit.php?memb_id=<?php echo $row_memb_bulk['MemberID']; ?>"><img src="../Admin_Images/edit_butt.fw.png" width="20" title="Edit Personal & Financial" /> </a></td>
	      <td align="center" nowrap="nowrap">
	        <?php if(isset($row_memb_bulk['memb_by'])) echo "<font color=green>Yes</font>"; else echo "<font color=red>No</font>"; ?>  
	      </td>
	      <td align="center" nowrap="nowrap" class="greenbg"><a href="../A_common/vbsa_member_edit_form.php?memb_id=<?php echo $row_memb_bulk['MemberID']; ?>" title="Insert / update membership form details">Memb</a> </td>
	    </tr>
	    <?php 
	  } while ($row_memb_bulk = mysql_fetch_assoc($memb_bulk)); 
	  //} while ($row_memb = mysql_fetch_assoc($memb)); 
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
    <td colspan="2">1. Type your email address in the &quot;From&quot; field</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">2. Type VBSA in &quot;Name&quot; field.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">3. From the &quot;Recordset fields&quot; select &quot;Email&quot;. Click the <img src="php_mail_merge/dynamic_e.gif" alt="1" width="17" height="17" /> button and it will add this field into the &quot;To&quot; field.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">4. In the message area type a greeting &quot;Hi&quot; followed by a space. From the&quot;recordset fields&quot; select &quot;Firstname&quot; </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;&nbsp;&nbsp;click the <img src="php_mail_merge/dynamic_t.gif" alt="1" /> button and it will add a field that will reflect the first name of the person to receive the email</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">5. Tick the box for &quot;Plain Text&quot; alongside &quot;Send as&quot; </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">6. Type in the subject and your message in the appropriate fields and click &quot;Send&quot;.</td>
  </tr>
</table>
<br />

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
<?php include("php_mail_merge.php"); ?>
</form>
<script language="javascript" src="php_mail_merge/php_mail_merge.js" type="text/javascript">
</script>
<script language="javascript" type="text/javascript">initPMM();<?php if (isset($_POST["SendAs"]) && !isset($_POST['reset_editor'])){?>flipMessageFormat(document.getElementById('SendAs'));<?php }?></script>

</body>
</html>

