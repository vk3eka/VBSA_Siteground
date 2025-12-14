<?php
/*
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
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />

</head>

<body>

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
</center>
</body>
</html>
<?php

?>
