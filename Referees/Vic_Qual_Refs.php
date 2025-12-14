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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Referees</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

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
  
  
  <div class="row"> 
  <div class="Page_heading_container">
 		<div class="page_title">Victorian Qualified Referees</div>
  </div>  	
 
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
<!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>   
  
  <div class="row"> 
  		 <div class="text_box text-justify">Qualified and accredited referees working with the VBSA are certified by the
Australian Billiards and Snooker Council. <br />
If you are qualified and wish to be considered for inclusion on this list please <a href="RefereeContact.htm" target="_blank">contact</a> the Director of Referees. <br />
If you wish to undergo a training course for accreditation and recognition as a referee please <a href="RefereeContact.htm" target="_blank">contact</a> the Director of Refereess. <br />
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
