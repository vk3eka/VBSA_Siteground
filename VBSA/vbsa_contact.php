<?php require_once('../Connections/connvbsa.php'); ?>
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
$query_Board = "SELECT name, vbsaorga_users.email, vbsaorga_users.username, display, register_year, MemberID, BoardMemb, board_desc FROM vbsaorga_users, members WHERE board_member_id=MemberID AND assist=0 AND display=1 ORDER BY order_display ASC";
$Board = mysql_query($query_Board, $connvbsa) or die(mysql_error());
$row_Board = mysql_fetch_assoc($Board);
$totalRows_Board = mysql_num_rows($Board);

mysql_select_db($database_connvbsa, $connvbsa);
$query_AssistBoard = "SELECT name, vbsaorga_users.email, vbsaorga_users.username, display, register_year, MemberID, BoardMemb, board_desc FROM vbsaorga_users, members WHERE board_member_id=MemberID AND assist=1 AND display=1 ORDER BY order_display ASC";
$AssistBoard = mysql_query($query_AssistBoard, $connvbsa) or die(mysql_error());
$row_AssistBoard = mysql_fetch_assoc($AssistBoard);
$totalRows_AssistBoard = mysql_num_rows($AssistBoard);


// send email on submit

if($_POST["submit"]) {
	// to modify recipient change email address here
    $recipient="info@vbsa.org.au";
    $subject="VBSA website message";
    $Name=$_POST["Name"];
	$Phone=$_POST["Phone"];
    $Email=$_POST["Email"];
    $Comment=$_POST["Comment"];

    $mailBody="Name: $Name\nPhone: $Phone\nEmail: $Email\n\nSent this message: \n$Comment";

    mail($recipient, $subject, $mailBody, "From: $Name <$Email>");

    $thankYou="<p>Thank you! Your message has been sent to the VBSA Secretary. <br/> The Secretary will respond as soon as possible.</p>";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Contact</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  
<script type="text/javascript">
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' should be 5.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
</script>
  
</head>

<body id="vbsa">
    
    <!-- Include Google Tracking -->
<?php include_once("../includes/analyticstracking.php") ?>

<div class="container"> 

    <!-- Include header -->
<?php include '../includes/header.php';?>
    
    <!-- Include navigation -->
<?php include '../includes/nav_vbsa.php';?>

</div><!--End Bootstrap Container--> 


<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 

  <!--Content--> 
  
  
<div class="row"> 
  <div class="Page_heading_container">
 		<div class="page_title">VBSA Administration - Contact</div>
        <div class="info_important"></div> 
  </div>  	
<div class="container">
<div class="table-responsive center-block" style="max-width:600px" >
  <table class="table">
    <tr>
      <th colspan="3">The Board</th>
    </tr>
    <?php do { ?>
    <tr>
      <td nowrap="nowrap"><?php echo $row_Board['name']; ?></td>
      <td nowrap="nowrap"><?php echo $row_Board['board_desc']; ?></td>
      <td nowrap="nowrap"><a href="mailto:<?php echo $row_Board['username']; ?>"><?php echo $row_Board['username']; ?></a></td>
    </tr>
    <?php } while ($row_Board = mysql_fetch_assoc($Board)); ?>
    <tr>
      <td nowrap="nowrap">&nbsp;</td>
      <td nowrap="nowrap">&nbsp;</td>
      <td nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr>
      <th colspan="3">Assistants to the Board</th>
    </tr>
    <?php do { ?>
    <tr>
      <td nowrap="nowrap"><?php echo $row_AssistBoard['name']; ?></td>
      <td nowrap="nowrap"><?php echo $row_AssistBoard['board_desc']; ?></td>
      <td nowrap="nowrap"><a href="mailto:<?php echo $row_AssistBoard['username']; ?>"><?php echo $row_AssistBoard['username']; ?></a></td>
    </tr>
    <?php } while ($row_AssistBoard = mysql_fetch_assoc($AssistBoard)); ?>
  </table>
  
</div>
</div>

</div>  <!-- close containing wrapper --> 





</body>
</html>
<?php

?>
