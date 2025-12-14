<?php require_once('../Connections/connvbsa.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}


$page = "../Admin_DB_VBSA/vbsa_hist.php";
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

//$cal_year = date('Y'); 

if(isset($_POST['state']) && ($_POST['state'] != ""))
{
  $varstate = $_POST['state'];
  if($varstate == 'ALL')
  {
   $filter = ""; 
  }
  else
  {
    $filter = " AND lower(HomeState) = '" . strtolower($varstate) . "'";
  }
}
else
{
  $filter = "";
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_History = "Select members.MemberID, members.LastName, FirstName, HomePhone, MobilePhone, Email, ReceiveEmail, HomeState FROM members WHERE (MemberID != 1 AND MemberID != 100 AND MemberID != 500 AND MemberID != 1000) AND contact_only = 0 " . $filter . " ORDER BY LastName, FirstName";
//echo("SQL " . $query_History . "<br>");
$History = mysql_query($query_History, $connvbsa) or die(mysql_error());
$row_History = mysql_fetch_assoc($History);
$totalRows_History = mysql_num_rows($History);

$query_memb = "Select members.MemberID, members.LastName, FirstName, HomePhone, MobilePhone, Email, ReceiveEmail, HomeState FROM members WHERE (MemberID != 1 AND MemberID != 100 AND MemberID != 500 AND MemberID != 1000) AND contact_only = 0 and (ReceiveEmail = 1 AND Email != '') " . $filter . " ORDER BY LastName, FirstName";

//echo("SQL " . $query_memb . "<br>");

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
<script type='text/javascript'>

function GetState(sel) {
  var state = sel.options[sel.selectedIndex].value;
  document.getElementById("state").value = state;
  //var year = <?= $cal_year ?>;
  //document.cal_state.CalYear.value = year;
  document.cal_state.submit();
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

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />


</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
  <form name='cal_state'  method="post" action='vbsa_hist.php'>
  <table align="center" cellpadding="5" cellspacing="5">
  	<!--<input type='hidden' name='CalYear'name='CalYear' value='<?php echo $cal_year; ?>'>-->
    <tr>
      <td class="red_bold">Lists everyone that has been a member since Terry Chapman originated the database</td>
      <td>&nbsp;</td>
      <td colspan="2" align="right" class="greenbg"><a href="A_memb_index.php">Return to Members index</a></td>
    </tr>
    <tr>
      <td colspan="3" align="center">
        <select name="state" id="state" onchange="GetState(this)">
          <option value="">Filter by State</option>
          <option value="ALL">All States</option>
          <option value="SA">SA</option>
          <option value="QLD">QLD</option>
          <option value="NSW">NSW</option>
          <option value="TAS">TAS</option>
          <option value="VIC">VIC</option>
          <option value="NT">NT</option>
          <option value="WA">WA</option>
          <option value="ACT">ACT</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>Total Records: <?php echo $totalRows_History ?>  (Receive Email <?php echo $totalRows_memb ?>)</td>
      <td>&nbsp;</td>
      <td colspan="2" class="greenbg" align="center"><a href="export_csv.php?page=vbsa_history">Export Current Data To CSV File</a></td>
      <td align="right">&nbsp;</td>
    </tr>

  </table>
</form>
  <table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
  </table>
<table border="1" align="center" cellpadding="1" class="page">
    <tr>
      <td>ID</td>
      <td>Last Name</td>
      <td>First Name</td>
      <td>State</td>
      <td>Land Line</td>
      <td>Mobile Phone</td>
      <td>Email</td>
      <td>Receive Email</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_History['MemberID']; ?></td>
        <td><?php echo $row_History['LastName']; ?></td>
        <td><?php echo $row_History['FirstName']; ?></td>
        <td><?php echo $row_History['HomeState']; ?></td>
        <td><?php echo $row_History['HomePhone']; ?></td>
        <td><a href="tel:<?php echo $row_History['MobilePhone']; ?>"><?php echo $row_History['MobilePhone']; ?></a></td>
        <td><a href="mailto:<?php echo $row_History['Email']; ?>"><?php echo $row_History['Email']; ?></a></td>
        <td><?php echo $row_History['ReceiveEmail']; ?></td>
        <td><a href="../A_common/vbsa_personal_detail.php?memb_id=<?php echo $row_History['MemberID']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" title="detail" /></a></td>
        <td><a href="../A_common/vbsa_member_edit.php?memb_id=<?php echo $row_History['MemberID']; ?>"><img src="../Admin_Images/edit_butt.fw.png" width="20" title="edit"  /></a></td>
      </tr>
      <?php } while ($row_History = mysql_fetch_assoc($History)); ?>
  </table>
  <form action="" method="post" name="editor_form" id="editor_form">
<table border="0" align="center" cellpadding="0" cellspacing="0">
<?php
/* Commented Out by Alec Spyrou 22nd Aug 2024
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
*/
?>
</body>
</html>
<?php

?>
