<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$page = "../Admin_DB_VBSA/vbsa_finals_qual.php?season=$season";
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
<?php require_once('../Connections/connvbsa.php'); ?><?php
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
$query_qual_play = "SELECT scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, scrs.team_grade, scrs.team_id, game_type, count_played, captain_scrs, scr_season, audited, team_name, team_club, size FROM scrs  LEFT JOIN members ON members.MemberID = scrs.MemberID  LEFT JOIN Team_entries ON Team_entries.team_id=scrs.team_id WHERE count_played>3 AND scr_season = '$season' AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberID != 1 HAVING audited='Yes' ORDER BY team_grade, team_id, FirstName, LastName";
//echo($query_qual_play . "<br>");
$qual_play = mysql_query($query_qual_play, $connvbsa) or die(mysql_error());
$row_qual_play = mysql_fetch_assoc($qual_play);
$totalRows_qual_play = mysql_num_rows($qual_play);

$query_memb = "Select scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, scrs.team_grade, scrs.team_id, game_type, count_played, captain_scrs, scr_season, audited, team_name, team_club, size FROM scrs  LEFT JOIN members ON members.MemberID = scrs.MemberID  LEFT JOIN Team_entries ON Team_entries.team_id=scrs.team_id WHERE count_played>3 AND scr_season = '$season' AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberID != 1 AND ReceiveEmail=1 HAVING audited='Yes' ORDER BY team_grade, team_id, FirstName, LastName";
$memb = mysql_query($query_memb, $connvbsa) or die(mysql_error());
//echo($query_memb . "<br>");
$row_memb = mysql_fetch_assoc($memb);
$totalRows_memb = mysql_num_rows($memb);

$myRecordset=$memb; $myTotalRecords=$totalRows_memb;

include 'php_mail_include.php'; // local file with the previous emailling code

/*PHP EMAIL MERGE 2013 - COPYRIGHT ALEX JULY (LINECRAFT STUDIO)*/
/*
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
			$to=($_POST["SendMode"]!="Test")?(bind_email($_POST["To"], $row_qual_play)):$_POST["To"];
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
					$subject=bind_fields($_POST["Subject"],$qual_play,$row_qual_play);
				}
				//Bind fields if the message contains variables
				if($dyn_message){
					$message=bind_fields($_POST["m_source"],$qual_play,$row_qual_play);
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
	}while ($row_qual_play = mysql_fetch_assoc($qual_play)); 
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
    <td class="red_bold"><?php echo date("Y") ?></span> - All Qualified for finals players in season <?php echo $season; ?>, scroll to bottom of Page for bulk email</td>
    <td>&nbsp;</td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td>Total Players: <?php echo $totalRows_qual_play ?></td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" class="red_bold"><p>This list is for information only or bulk email. Names will only appear when ladders have been audited at the end of the season<br/>If you wish to copy to an excel file, select all cells and copy. <br/>Remember you are copying personal contact info, please treat this information with discression</p></td>
  </tr>
</table>
<?php if($totalRows_qual_play >0 )  { ?>
<table border="1" align="center" cellpadding="5" class="page">
  <tr>
    <td>ID</td>
    <td>First Name</td>
    <td>Last Name</td>
    <td>Mobile</td>
    <td>Email</td>
    <td>Captain</td>
    <td>Team ID</td>
    <td>Grade</td>
    <td>Club</td>
    <td>Team Name</td>
    <td>Played</td>
    <td>Size</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_qual_play['MemberID']; ?></td>
      <td><?php echo $row_qual_play['FirstName']; ?></td>
      <td><?php echo $row_qual_play['LastName']; ?></td>
      <td><a href="tel:<?php echo $row_qual_play['MobilePhone']; ?>"><?php echo $row_qual_play['MobilePhone']; ?></a></td>
      <td><a href="mailto:<?php echo $row_qual_play['Email']; ?>" target="_blank"><?php echo $row_qual_play['Email']; ?></a></td>
      <td><?php echo $row_qual_play['captain_scrs']; ?></td>
      <td><?php echo $row_qual_play['team_id']; ?></td>
      <td><?php echo $row_qual_play['team_grade']; ?></td>
      <td><?php echo $row_qual_play['team_club']; ?></td>
      <td><?php echo $row_qual_play['team_name']; ?></td>
      <td><?php echo $row_qual_play['count_played']; ?></td>
      <td><?php echo $row_qual_play['size']; ?></td>
    </tr>
    <?php } while ($row_qual_play = mysql_fetch_assoc($qual_play)); ?>
</table>
<?php } else { ?>
<table align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="Italic">Nothing listed</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<?php } ?>
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
    <td class="greenbg"><a href="attach_upload.php">Please upload it now</a></td>
    <td align="right" class="greenbg"><a href="Bulk_email_help.pdf">Bulk Email help</a></td>
  </tr>
  <tr>
    <td width="211" align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>To Send a group email: </td>
    <td width="11">&nbsp;</td>
    <td colspan="2">1. Type your email address in both the &quot;From&quot; and the &quot;Reply to&quot; fields</td>
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
    <td colspan="2">4. To personalise your message, in the message area type a greeting &quot;Hi&quot; followed by a space. From the&quot;recordset fields&quot; select &quot;Firstname&quot; </td>
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
<form action="" method="post" name="editor_form" id="editor_form">
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
<?php

?>