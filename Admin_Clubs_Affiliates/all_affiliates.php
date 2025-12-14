<?php require_once('../Connections/connvbsa.php'); 

error_reporting(0);

if (!isset($_SESSION)) {
  session_start();
}

// set page url in session for insert / update files
$detail = "../all_affiliates.php";
$_SESSION['detail'] = $detail;

$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

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
    if (($strUsers == "") && true) { 
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
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  //if (PHP_VERSION < 6) {
  //  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  //}

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

$redirect = "-1";
if (isset($_GET['redirect'])) {
  $redirect = $_GET['redirect'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_Clubs = "SELECT ClubNumber, ClubTitle, Club_Aff_Assoc, ClubStreet, ClubSuburb, ClubPcode, ClubPhone1, ClubEmail, ClubContact, VBSAteam, ChurchBill, CityClubTeam, DVSAteam, MSBAteam, Over55team, RSLteam, SouthernTeam, affiliate, aff_URL FROM clubs WHERE affiliate=1 ORDER BY ClubTitle";
$Clubs = mysql_query($query_Clubs, $connvbsa) or die(mysql_error());
$row_Clubs = mysql_fetch_assoc($Clubs);
$totalRows_Clubs = mysql_num_rows($Clubs);

/*
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
			$to=($_POST["SendMode"]!="Test")?(bind_email($_POST["To"], $row_Clubs)):$_POST["To"];
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
					$subject=bind_fields($_POST["Subject"],$Clubs,$row_Clubs);
				}
				//Bind fields if the message contains variables
				if($dyn_message===1){
					$message=bind_fields($_POST["m_source"],$Clubs,$row_Clubs);
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
	}while ($row_Clubs = mysql_fetch_assoc($Clubs)); 
	//Render Server Response
	header("Content-Type: text/xml");
	echo "<sent to=\"".$to."\" total=\"".$totalRows_Clubs."\"><errors>".$error."</errors></sent>";
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
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<table width="769" align="center">
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center"><form id="form6" name="form6" method="get" action="clubs_srch_res.php">
      <input name="clubfind" type="text" id="clubfind" size="12" />
      <input type="submit" value="Search Clubs by name" />
      </form></td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><span class="page">When you update a clubs &quot;Public View&quot; details please check the <a href="../Club_dir/club_index.php" target="_blank">web page</a></span></td>
    <td align="right">Total Clubs:</td>
    <td align="left"><?php echo $totalRows_Clubs ?></td>
  </tr>
</table>
<table width="800" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="middle" class="red_bold">All Affiliates - contact details are as per the &quot;Club Directory&quot; (Public view)</td>
  </tr>
  <tr>
    <td align="center" valign="middle" class="red_bold">to view / edit club details or contacts, upload an image, go to the detail page <img src="../Admin_Images/detail.fw.png" alt="" width="20" height="20" title="Detail" /></td>
  </tr>
  <tr>
    <td align="center" class="greenbg"><a href="A_Club_index.php?">Return to Clubs Index</a></td>
  </tr>
  <tr>
    <td align="center"><span class="red_text">Scroll to bottom of page to bulk email this list</span></td>
  </tr>
</table>
<table border="1" align="center" class="page">
  <tr>
    <td align="center">Club ID</td>
      <td>Title</td>
      <td>Club / Aff / Assoc</td>
      <td>Contact</td>
      <td>Phone</td>
      <td>Email</td>
      <td align="left">VBSA page URL / link</td>
      <td align="center">Affiliate?</td>
      <td align="center">&nbsp;</td>
    </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_Clubs['ClubNumber']; ?></td>
        <td><?php echo $row_Clubs['ClubTitle']; ?></td>
        <td><?php echo $row_Clubs['Club_Aff_Assoc']; ?></td>
        <td><?php echo $row_Clubs['ClubContact']; ?></td>
        <td><a href="tel:<?php echo $row_Clubs['ClubPhone1']; ?>"><?php echo $row_Clubs['ClubPhone1']; ?></a></td>
        <td><a href="mailto:<?php echo $row_Clubs['ClubEmail']; ?>"><?php echo $row_Clubs['ClubEmail']; ?></a></td>
        <td align="left"><a href="<?php echo $row_Clubs['aff_URL']; ?>" target="_blank"><?php echo $row_Clubs['aff_URL']; ?></a></td>
        <td align="center"><?php if($row_Clubs['affiliate']==1) echo '<img src="../Admin_Images/tick.JPG" width="15"/>'; else echo "No";?></td>
        <td align="center"><a href="user_files/clubs_detail.php?club_id=<?php echo $row_Clubs['ClubNumber']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" height="20" title="Detail" /></a></td>
    </tr>
    <?php } while ($row_Clubs = mysql_fetch_assoc($Clubs)); ?>
</table>


<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="page">&nbsp;</td>
    <td align="right" class="greenbg">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="134" class="page">&nbsp;</td>
    <td width="551" align="right" class="greenbg"><a href="help_Bulk_email.pdf">Bulk Email help</a></td>
  </tr>
  <tr>
    <td>Would you like to send an attachment?</td>
    <td>&nbsp;</td>
    <td class="greenbg"><a href="user_files/upload_attach.php?attach_redirect=affil">Please upload it now</a></td>
    <td align="left" class="page">&nbsp;</td>
  </tr>
  <tr>
    <td width="211" align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>To Send a group email: </td>
    <td width="11">&nbsp;</td>
    <td colspan="2">1. Type your email address in the &quot;From&quot; field and the &quot;Reply To&quot; field</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">2. Type VBSA in &quot;Name&quot; field.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">3. From the &quot;Recordset fields&quot; select &quot;ClubEmail&quot;. Click the <img src="php_mail_merge/dynamic_e.gif" alt="1" width="17" height="17" /> button and it will add this field into the &quot;To&quot; field.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">4. Tick the box for &quot;Plain Text&quot; alongside &quot;Send as&quot; </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">5. Type in the subject and your message in the appropriate fields and click &quot;Send&quot;.</td>
  </tr>
</table>
<form action="" method="post" name="editor_form" id="editor_form">
<br />
<?php $myRecordset=$Clubs; $myTotalRecords=$totalRows_Clubs; ?>
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
mysql_free_result($Clubs);

?>