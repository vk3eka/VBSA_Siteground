<?php require_once('../Connections/connvbsa.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}

$comptype = "-1";
if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}

$page = "../Admin_DB_VBSA/vbsa_players_email_bygame.php?comptype=$comptype";
$_SESSION['page'] = $page;

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
$query_members = "SELECT scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, SUM(count_played) AS TotPlayed, game_type, scr_season FROM scrs LEFT JOIN members ON members.MemberID=scrs.MemberID WHERE game_type='$comptype' AND current_year_scrs = YEAR( CURDATE( ) ) AND (scrs.MemberID!=1 AND scrs.MemberID!=100 AND scrs.MemberID!=1000) AND Email is not null AND ReceiveEmail=1 GROUP BY scrs.MemberID HAVING TotPlayed>0 ORDER BY LastName, FirstName";
$members = mysql_query($query_members, $connvbsa) or die(mysql_error());
$row_members = mysql_fetch_assoc($members);
$totalRows_members = mysql_num_rows($members);

/*PHP EMAIL MERGE 2013 - COPYRIGHT ALEX JULY (LINECRAFT STUDIO)*/
//Account for magic_quotes
/*
if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}
*/

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
			$to=($_POST["SendMode"]!="Test")?(bind_email($_POST["To"], $row_members)):$_POST["To"];
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
					$subject=bind_fields($_POST["Subject"],$members,$row_members);
				}
				//Bind fields if the message contains variables
				if($dyn_message===1){
					$message=bind_fields($_POST["m_source"],$members,$row_members);
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
	}while ($row_members = mysql_fetch_assoc($members)); 
	//Render Server Response
	header("Content-Type: text/xml");
	echo "<sent to=\"".$to."\" total=\"".$totalRows_members."\"><errors>".$error."</errors></sent>";
	die();
}


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

<table align="center">
  <tr>
    <td colspan="3" align="center" class="red_bold">This list contains all players that have played  1 <?php echo $comptype ?> match or more in <?php echo date("Y") ?>, <br/>have an email and have &quot;Receive Email&quot; set as &quot;Yes&quot;</td>
  </tr>
  <tr>
    <td align="center">Scroll to the bottom of the page to send an email to all that are listed here</td>
    <td align="center">Total email contacts:<?php echo $totalRows_members ?></td>
    <td class="greenbg"><a href="A_memb_index.php">Return to Members index</a></td>
  </tr>
</table>
<table align="center" cellpadding="2" cellspacing="2">
      <tr>
        <td colspan="6">Total to email: <?php echo $totalRows_members ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="center">ID</td>
        <td align="left">Last Name</td>
        <td align="left">First Name</td>
        <td align="left">&nbsp;</td>
        <td align="left">Email</td>
        <td align="center">Total played</td>
        <td align="center">Receive Email</td>
        <td align="left">&nbsp;</td>
        <td align="left">&nbsp;</td>
      </tr>
      <?php do { ?>
        <tr>
          <td align="center"><?php echo $row_members['MemberID']; ?></td>
          <td align="left"><?php echo $row_members['LastName']; ?></td>
          <td align="left"><?php echo $row_members['FirstName']; ?></td>
          <td align="left">&nbsp;</td>
          <td align="left" class="page"><a href="mailto:<?php echo $row_members['Email']; ?>"><?php echo $row_members['Email']; ?></a></td>
          <td align="center"><?php echo $row_members['TotPlayed']; ?></td>
          <td align="center"><?php echo $row_members['ReceiveEmail']; ?></td>
          <td><a href="../A_common/vbsa_personal_detail.php?memb_id=<?php echo $row_members['MemberID']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" title="detail" /></a></td>
          <td align="center"><a href="../A_common/vbsa_member_edit.php?memb_id=<?php echo $row_members['MemberID']; ?>"><img src="../Admin_Images/edit_butt.fw.png" width="20" /> </a></td>
      </tr>
        <?php } while ($row_members = mysql_fetch_assoc($members)); ?>
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
<?php $myRecordset=$members; $myTotalRecords=$totalRows_members; ?>
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
<?php

?>
