<?php 
function tfm_cleanOrderBy($theValue, $defaultSort) {
	if (preg_match("/^[\w,]{1,50}\s+(asc|desc)\s*$/i",$theValue, $matches)) {
		return $matches[0];
	}
	return $defaultSort;
}
?>
<?php require_once('../Connections/connvbsa.php'); ?>
<?php
$tfm_orderby =(!isset($_GET["tfm_orderby"]))?"MemberID":$_GET["tfm_orderby"];
$tfm_order =(!isset($_GET["tfm_order"]))?"ASC":$_GET["tfm_order"];
$sql_orderby = $tfm_orderby." ".$tfm_order;
$sql_orderby = tfm_cleanOrderBy($sql_orderby, "MemberID");
?>
<?php

if (!isset($_SESSION)) {
  session_start();
}

$page = "../Admin_DB_VBSA/vbsa_referees.php";
$_SESSION['page'] = $page;

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

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

$currentPage = $_SERVER["PHP_SELF"];

//TOMLR Special List Recordset
// Defining List Recordset variable
$sqlorderby_History = "MemberID";
if (isset($sql_orderby)) {
  $sqlorderby_History = $sql_orderby;
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_History = "SELECT members.MemberID, members.LastName, members.FirstName, members.HomePhone, members.WorkPhone, members.MobilePhone, members.Email, members.ReceiveEmail, members.Referee, members.Ref_Class FROM members WHERE  Referee = 1 ORDER BY {$sqlorderby_History}";
$History = mysql_query($query_History, $connvbsa) or die(mysql_error());
$row_History = mysql_fetch_assoc($History);
$totalRows_History = mysql_num_rows($History);
//End TOMLR Special List Recordset//TOMLR Special List Recordset

$queryString_History = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_History") == false && 
        stristr($param, "totalRows_History") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_History = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_History = sprintf("&totalRows_History=%d%s", $totalRows_History, $queryString_History);
?>
<?php
//sort column headers for History
$tfm_saveParams = explode(",","");
$tfm_keepParams = "";
if($tfm_order == "ASC") {
	$tfm_order = "DESC";
}else{
	$tfm_order = "ASC";
};
foreach ($tfm_saveParams as $key => $val) {
	if(isset($_GET[$val]))$tfm_keepParams .= ($val)."=".urlencode($_GET[$val])."&";	
	if(isset($_POST[$val]))$tfm_keepParams .= ($val)."=".urlencode($_POST[$val])."&";
}
$tfm_orderbyURL = $_SERVER["PHP_SELF"]."?".$tfm_keepParams."tfm_order=".$tfm_order."&tfm_orderby=";


$query_memb = "Select members.MemberID, members.LastName, members.FirstName, members.HomePhone, members.WorkPhone, members.MobilePhone, members.Email, members.ReceiveEmail, members.Referee, members.Ref_Class FROM members WHERE  Referee = 1 AND (ReceiveEmail = 1 AND Email != '')";
$memb = mysql_query($query_memb, $connvbsa) or die(mysql_error());
$row_memb = mysql_fetch_assoc($memb);
$totalRows_memb = mysql_num_rows($memb);

$myRecordset=$memb; $myTotalRecords=$totalRows_memb;

include 'php_mail_include.php'; // local file with the previous emailling code


/*PHP EMAIL MERGE 2008 - COPYRIGHT ALEX JULY (LINECRAFT STUDIO)*/
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
	
	$current_record=0;
	$valid_email=true;
	do {
		$current_record++;
		if($_POST['StartNumber']==$current_record){
			$error="";
			//Send the message
			$to=($_POST["SendMode"]!="Test")?(bind_email($_POST["To"], $row_History)):$_POST["To"];
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
				$email=$_POST["From"];
				$realname=$_POST["FromName"]; 
				$from=$realname."<".$email.">";
				//If it's a plain text
				$html_header="Content-type: text/plain;";
				if($_POST["SendAs"]!="Plain Text"){
					$html_header="Content-type: text/html;";
				}
				$html_header.=" charset=".$_POST["encoding"].";";
				$headers="From:".$from."\r\n";
				$headers.="Reply-To:<".$email.">\r\n";
				$headers.="Return-Path:<".$email.">\r\n";
				$headers.="MIME-Version: 1.0\n";
				$headers.=$html_header;
				$subject=$_POST["Subject"];
				$message=$_POST["m_source"];

				
				//Bind fields if the subject contains variables
				if($dyn_subject==1){
					$subject=bind_fields($_POST["Subject"],$History,$row_History);
				}
				//Bind fields if the message contains variables
				if($dyn_message){
					$message=bind_fields($_POST["m_source"],$History,$row_History);
				}
	
				//Check if email message exists
				if(!mail($to, stripslashes($subject),stripslashes($message),$headers)){
					$error=$to."\n";
				}
			}
			break;
		}
	}while ($row_History = mysql_fetch_assoc($History)); 
	//Render Server Response
	header("Content-Type: text/xml");
	echo "<sent to=\"".$to."\" total=\"".$totalRows_History."\"><errors>".$error."</errors></sent>";
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

  <table align="center" cellpadding="5" cellspacing="5" class="greenbg">
    <tr>
      <td><a href="vbsa_referees.php">Qualified Referees</a></td>
      <td><a href="vbsa_referees_prospective.php">Prospective Referees</a></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td colspan="3"><p>To insert a person as a referee or as a Prospective Referee:</p>
        <ul>
          <li>The person must exist in the members table, if they do not then enter as a new member</li>
          <li>Search and find the person in the members table and edit</li>
          <li>Check the appropriate checkboxes and put in the ref class if required</li>
      </ul></td>
    </tr>
    <tr>
      <td class="red_bold">&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right" class="greenbg">&nbsp;</td>
    </tr>
    <tr>
    <td class="red_bold">Lists all Referees and qualifications</td>
      <td>Total Records: <?php echo $totalRows_History ?></td>
      <td align="right" class="greenbg"><a href="A_memb_index.php">Return to Members</a></td>
      </tr>
</table>
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
</table>
<table border="1" align="center" cellpadding="1" class="page">
  <tr>
    <td><a href="<?Php echo ($tfm_orderbyURL); ?>MemberID">ID Number</a></td>
      <td><a href="<?Php echo ($tfm_orderbyURL); ?>LastName">Last Name</a></td>
      <td><a href="<?Php echo ($tfm_orderbyURL); ?>FirstName">First Name</a></td>
      <td>Home Phone</td>
      <td>Work Phone</td>
      <td>Mobile Phone</td>
      <td>Email</td>
      <td align="center">Receive Email</td>
      <td align="center">Referee</td>
      <td><a href="<?Php echo ($tfm_orderbyURL); ?>Ref_Class">Referee Class</a></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_History['MemberID']; ?></td>
      <td><?php echo $row_History['LastName']; ?></td>
        <td><?php echo $row_History['FirstName']; ?></td>
        <td><?php echo $row_History['HomePhone']; ?></td>
        <td><?php echo $row_History['WorkPhone']; ?></td>
        <td><a href="tel:<?php echo $row_History['MobilePhone']; ?>"><?php echo $row_History['MobilePhone']; ?></a></td>
        <td><a href="mailto:<?php echo $row_History['Email']; ?>"><?php echo $row_History['Email']; ?></a></td>
        <td align="center"><?php echo $row_History['ReceiveEmail']; ?></td>
        <td align="center"><?php if ($row_History['Referee']==1)
			echo "Yes";
			else
			echo "No"; 
			?></td>
        <td><?php echo $row_History['Ref_Class']; ?></td>
        <td><a href="../A_common/vbsa_personal_detail.php?memb_id=<?php echo $row_History['MemberID']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" title="detail" /></a></td>
      <td align="center"><a href="../A_common/vbsa_member_edit.php?memb_id=<?php echo $row_History['MemberID']; ?>"><img src="../Admin_Images/edit_butt.fw.png" width="20" /> </a></td>
      </tr>
    <?php } while ($row_History = mysql_fetch_assoc($History)); ?>
</table>
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
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">7. Bulk Emails only sent to members who have consented to receive emails and have an email address.</td>
  </tr>
</table>
<p><br />
    <script language="JavaScript" src="php_mail_merge/php_mail_merge.js" type="text/javascript">
                </script>
      <script language="JavaScript" type="text/javascript">initPMM();<?php if (isset($_POST["SendAs"]) && !isset($_POST['reset_editor'])){?>flipMessageFormat(document.getElementById('SendAs'));<?php }?></script>
    </p>
<form action="" method="post" name="editor_form" id="editor_form">
    <br />
    <table width="778" border="0" align="center" cellpadding="3" cellspacing="0" id="filters">
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
<script language="JavaScript" src="php_mail_merge/php_mail_merge.js" type="text/javascript">
    </script>
    <script language="JavaScript" type="text/javascript">initPMM();<?php if (isset($_POST["SendAs"]) && !isset($_POST['reset_editor'])){?>flipMessageFormat(document.getElementById('SendAs'));<?php }?></script>
</body>
</html>
<?php

?>
