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
$query_refs = "SELECT * FROM members WHERE members.Referee =1 ORDER BY members.LastName";
$refs = mysql_query($query_refs, $connvbsa) or die(mysql_error());
$row_refs = mysql_fetch_assoc($refs);
$totalRows_refs = mysql_num_rows($refs);

/* Commented out as part of removing the contact us form box on this page Alec Spyrou 26/5/25
// send email on submit
if($_POST["submit"]) {
	// to modify recipient change email address here
    $recipient="larry147@optusnet.com.au";
    $subject="VBSA website message";
    $Name=$_POST["Name"];
	$Phone=$_POST["Phone"];
    $Email=$_POST["Email"];
    $Comment=$_POST["Comment"];

    $mailBody="Name: $Name\nPhone: $Phone\nEmail: $Email\n\nSent this message: \n$Comment";

    mail($recipient, $subject, $mailBody, "From: $Name <$Email>");

    $thankYou="<p>Thank you! Your message has been sent to the Victorian Director of Referees. <br/> Larry will respond as soon as possible.</p>";
}
*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Referees</title>
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
<body id="referees">
    
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
  
 
  
<!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>  


<div class="row"> 
  <div class="Page_heading_container">
 		<div class="page_title">Qualified Victorian Referees</div>
        <div class="info_important">
          <p>To Contact the Victorian Head of Referees: Larry Eforgan,</p>
          <p> or, if you wish to undergo a training course for accreditation and recognition, please email <a href="mailto:refereesdirector@vbsa.org.au">refereesdirector@vbsa.org.au</a></p>
      </div>
  </div>  	
    

<!-- Commented out contact us form to avoid bot attacks Alec Spyrou 26/5/25  
<div class="container">
<div class="contactformcenter" style="margin-top:15px">
<div class="col-sm-offset-3 col-md-6">



     
     <form method="POST" name="form1" class="form-horizontal" id="form1" onsubmit="MM_validateForm('Name','','R','email','','RisEmail','Phone','','RisNum','Comment','','R','simple_sum','','RinRange5:5');return document.MM_returnValue" role="form" >
    
    <div class="form-group">
      	<label class="control-label col-sm-2 small" for="Name">Name:</label>
      	<div class="col-sm-6">
        <input type="text" class="form-control input-sm" id="Name" name="Name" placeholder="*Enter Name">
        </div>
    </div>
           
     
     <div class="form-group">
      	<label class="control-label col-sm-2 small" for="email">Email:</label>
      	<div class="col-sm-6">
        <input type="text" class="form-control input-sm" id="email" name="Email" placeholder="*Enter email">
    	</div>
     </div>
     
     
    
    <div class="form-group">
      	<label class="control-label col-sm-2 small" for="Phone">Phone:</label>
      	<div class="col-sm-6">          
        <input type="text" class="form-control input-sm" id="Phone" name="Phone" placeholder="*10 digits no spaces please">
    	</div>
    </div>
    <div class="form-group">
  		<label class="control-label col-sm-2 small" for="Comment">Comment:</label>
        <div class="col-sm-6">
  		<textarea class="form-control input-sm"  rows="5" id="Comment" name="Comment" placeholder="*What would you like to know"></textarea>
        </div>
	</div>
    
    <div class="form-group">
        <label for="simple_sum" class="col-sm-2 control-label small">2 + 3 = ?</label>
        <div class="col-sm-6">
            <input type="text" class="form-control input-sm" id="simple_sum" name="Simple Sum answer " placeholder="*Are you a robot?">
        </div>
    </div>
    
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-4">
        <input type="submit" id="submit" name="submit" value="Submit" class="btn btn-default">
        <input type="reset" id="reset" name="reset" value="Reset" class="btn btn-default">
        
      </div>
    </div>
    <div class="info_important"><?php echo $thankYou; ?></div>
     </form>
</div> 
</div>
</div>

  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999" /> </div> 
  </div> 
-->  
  <div class="row"> 
  		 <div class="text_box text-justify">Qualified and accredited referees working with the VBSA are certified by the
Australian Billiards and Snooker Council. <br />
If you are qualified and wish to be considered for inclusion on this list please email <a href="mailto:refereesdirector@vbsa.org.au">refereesdirector@vbsa.org.au</a> <br />
Certificates can only be issued by the Australian Billiards and Snooker Referees' Committee (ABSR) on behalf of the Australian Billiards and Snooker Council (ABSC).
There is an annual fee to be paid for registration and accreditation.
       </div>
  </div>
  
  
  <div class="table-responsive center-block" style="max-width:700px; padding-left:3px">
  <table class="table">
    <tr>
         <th>Name</th>
         <th>Class</th>
    </tr>
       <?php do { ?>
         <tr>
           <td align="left"><?php echo $row_refs['FirstName']; ?> <?php echo $row_refs['LastName']; ?></td>
           <td align="left"><?php echo $row_refs['Ref_Class']; ?></td>
       </tr>
         <?php } while ($row_refs = mysql_fetch_assoc($refs)); ?>
  </table>
 </div>


</div>  <!-- close conraineing wrapper --> 
</body>
</html>
<?php
mysql_free_result($refs);
?>
