<?php require_once('../Connections/connvbsa.php'); ?>
<?php
error_reporting(0);

if (!isset($_SESSION)) {
  session_start();
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$page = "../Admin_DB_VBSA/vbsa_captains.php?season=$season";
$_SESSION['page'] = $page;

$MM_authorizedUsers = "Webmaster,Treasurer,Administrator,Secretary,Scores";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../page_error.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
	{
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

mysql_select_db($database_connvbsa, $connvbsa);
//$query_memb_display = "Select members.MemberID, LastName, FirstName, MobilePhone, Email, captain_scrs, authoriser_scrs, Team_entries.team_id, Team_entries.team_grade, Team_entries.team_club, Team_entries.team_name, day_played, comptype FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID LEFT JOIN Team_entries on Team_entries.team_id=scrs.team_id WHERE (captain_scrs=1 or authoriser_scrs=1) AND team_cal_year = YEAR( CURDATE( ) ) AND scr_season='$season' ORDER BY Team_entries.team_grade, Team_entries.team_club";

$query_memb_display = "Select distinct members.MemberID, members.ReceiveSMS, LastName, FirstName, MobilePhone, Email, captain_scrs, authoriser_scrs, Team_entries.team_id, Team_entries.team_grade, Team_entries.team_club, Team_entries.team_name, day_played, comptype FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID LEFT JOIN Team_entries on Team_entries.team_id=scrs.team_id WHERE (captain_scrs=1 OR authoriser_scrs=1) AND team_cal_year = YEAR( CURDATE( ) ) AND scr_season='$season' AND MobilePhone != '' group by members.memberID ORDER BY Team_entries.team_grade, Team_entries.team_club";

//$query_memb = "SELECT members.MemberID, LastName, FirstName, MobilePhone, Email, captain_scrs, Team_entries.team_id, Team_entries.team_grade, Team_entries.team_club, Team_entries.team_name, day_played, comptype FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID LEFT JOIN Team_entries on Team_entries.team_id=scrs.team_id WHERE captain_scrs=1  AND current_year_scrs = YEAR( CURDATE( ) ) AND scr_season='$season' ORDER BY Team_entries.team_grade, Team_entries.team_club";

//echo($query_memb . "<br>");
$memb_display = mysql_query($query_memb_display, $connvbsa) or die(mysql_error());
$row_memb_display = mysql_fetch_assoc($memb_display);
$totalRows_memb_display = mysql_num_rows($memb_display);

// get data to email
$query_memb = "Select distinct members.MemberID, members.ReceiveSMS, LastName, FirstName, MobilePhone, Email, captain_scrs, authoriser_scrs, Team_entries.team_id, Team_entries.team_grade, Team_entries.team_club, Team_entries.team_name, day_played, comptype FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID LEFT JOIN Team_entries on Team_entries.team_id=scrs.team_id WHERE (captain_scrs=1 OR authoriser_scrs=1) AND team_cal_year = YEAR( CURDATE( ) ) AND scr_season='$season' AND MobilePhone != '' group by members.memberID AND (ReceiveEmail = 1 AND Email != '')  ORDER BY Team_entries.team_grade, Team_entries.team_club";

//$query_memb = "Select members.MemberID, members.ReceiveEmail, LastName, FirstName, MobilePhone, Email, captain_scrs, authoriser_scrs, Team_entries.team_id, Team_entries.team_grade, Team_entries.team_club, Team_entries.team_name, day_played, comptype FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID LEFT JOIN Team_entries on Team_entries.team_id=scrs.team_id WHERE (captain_scrs=1 OR authoriser_scrs=1) AND team_cal_year = YEAR( CURDATE( ) ) AND scr_season='$season' AND (ReceiveEmail = 1 AND Email != '') ORDER BY Team_entries.team_grade, Team_entries.team_club";

//$query_memb = 'Select Email from test_members';
//echo($query_memb . "<br>");

$memb = mysql_query($query_memb, $connvbsa) or die(mysql_error());
$row_memb = mysql_fetch_assoc($memb);
$totalRows_memb = mysql_num_rows($memb);

$myRecordset=$memb; $myTotalRecords=$totalRows_memb; 

include 'php_mail_include.php';

/*PHP EMAIL MERGE 2013 - COPYRIGHT ALEX JULY (LINECRAFT STUDIO)*/

/*
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
		 if(eregi("$hostName",$line)) {
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
			list($user, $domain) = split("@", $to);
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
				$regex="##"."[a-z0-9_]+"."##";
				//Find if there is possible bound field in the subject line
				$dyn_subject=eregi($regex,$_POST["Subject"]);
				//Find if there is possible bound field in the message box
				$dyn_message=eregi($regex,$_POST["m_source"]);
				//From fields
				$subject=$_POST["Subject"];
				$message=$_POST["m_source"];

				
				//Bind fields if the subject contains variables
				if($dyn_subject==1){
					$subject=bind_fields($_POST["Subject"],$memb,$row_memb);
				}
				//Bind fields if the message contains variables
				if($dyn_message){
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
	}while ($row_memb = mysql_fetch_assoc($memb)); 
	//Render Server Response
	header("Content-Type: text/xml");
	echo "<sent to=\"".$to."\" total=\"".$totalRows_memb."\"><errors>".$error."</errors></sent>";
	die();
}

*/
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

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td class="red_bold"><?php echo date("Y") ?></span> - All Captains for Season <?php echo $season; ?>, scroll to bottom of Page for bulk email</td>
    <td>&nbsp;</td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td class="greenbg">Total Captains <?php echo $season; ?>: <?php echo $totalRows_memb_display ?></td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" class="greenbg">*Note -  the only person that can change Captains is the Score Registrar</td>
  </tr>
</table>
<table border="1" align="center" cellpadding="5">
  <tr>
    <th align="center">ID</th>
    <th align="left">Last Name</th>
    <th align="left">First Name</th>
    <th align="left">Mobile</th>
    <th align="left">Email</th>
    <th align="center">Captain</th>
    <th align="center">Authoriser</th>
    <th align="center">Team ID</th>
    <th align="center">Grade</th>
    <th align="left">Club</th>
    <th align="left">Team Name</th>
    <th align="center">Day Played</th>
    <th align="left">Game Type</th>
    <th align="center">&nbsp;</th>
    <th align="center">&nbsp;</th>
    <th align="center">&nbsp;</th>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_memb_display['MemberID']; ?></td>
      <td align="left"><?php echo $row_memb_display['LastName']; ?></td>
      <td align="left"><?php echo $row_memb_display['FirstName']; ?></td>
      <td align="left" class="page"><a href="tel:<?php echo $row_memb_display['MobilePhone']; ?>"><?php echo $row_memb_display['MobilePhone']; ?></a></td>
      <td align="left" class="page"><a href="mailto:<?php echo $row_memb_display['Email']; ?>" target="_blank"><?php echo $row_memb_display['Email']; ?></a></td>
      <td align="center"><?php echo $row_memb_display['captain_scrs']; ?></td>
      <td align="center"><?php echo $row_memb_display['authoriser_scrs']; ?></td>
      <td align="center"><?php echo $row_memb_display['team_id']; ?></td>
      <td align="center"><?php echo $row_memb_display['team_grade']; ?></td>
      <td align="left"><?php echo $row_memb_display['team_club']; ?></td>
      <td align="left"><?php echo $row_memb_display['team_name']; ?></td>
      <td align="center"><?php echo $row_memb_display['day_played']; ?></td>
      <td align="left"><?php echo $row_memb_display['comptype']; ?></td>
      <td><a href="../A_common/vbsa_personal_detail.php?memb_id=<?php echo $row_memb_display['MemberID']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" title="detail" /></a></td>
      <td><a href="../A_common/vbsa_member_edit.php?memb_id=<?php echo $row_memb_display['MemberID']; ?>"><img src="../Admin_Images/edit_butt.fw.png" width="20" title="edit"  /></a></td>
      <td nowrap="nowrap" class="greenbg"><a href="vbsa_captains_team_detail.php?team_id=<?php echo $row_memb_display['team_id']; ?>&amp;season=<?php echo $season; ?>">Team detail</a></td>
    </tr>
    <?php } while ($row_memb_display = mysql_fetch_assoc($memb_display)); ?>
</table>
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
</center>
</body>
</html>