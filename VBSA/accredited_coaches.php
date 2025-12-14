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

//$query_coaches = "Select MemberID, memb_id, FirstName, LastName, Email, MobilePhone, coach_id, class, comment, URL, coach_order, active_coach FROM members, coaches_vbsa WHERE members.MemberID = coaches_vbsa.memb_id AND active_coach = 1 ORDER BY coach_order, class";
$query_coaches = "Select MemberID, memb_id, FirstName, LastName, Email, MobilePhone, coach_id, class, comment, URL, coach_order FROM members, coaches_vbsa WHERE members.MemberID = coaches_vbsa.memb_id ORDER BY coach_order, class";
//echo($query_coaches . "<br>");
$coaches = mysql_query($query_coaches, $connvbsa) or die(mysql_error());
$row_coaches = mysql_fetch_assoc($coaches);
$totalRows_coaches = mysql_num_rows($coaches);

echo("Here<br>");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Accredited Coaches</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</head>
<body id="coaching">
    
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
 		<div class="page_title">Victorian Accredited Coaches Directorate</div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 
<!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>
  <div class="text_box"> <!-- scoring system explained -->
  For coaching in any Cue sport, Snooker, Billiards, 8 Ball
      or 9 Ball. Contact any of the following. They are all Nationally Accredited
      coaches. 
</div>


<div class="table-responsive center-block" style="max-width:700px">

    <?php do { ?>
  		<table class="table">
    
      <tr>
        <td nowrap="nowrap"><?php echo $row_coaches['FirstName']; ?> <?php echo $row_coaches['LastName']; ?> - <span class="italic">Class: </span> <?php echo $row_coaches['class']; ?></td>
        </tr>
      <tr>
        <td>Phone: <a href="tel:<?php echo $row_coaches['MobilePhone']; ?>"><?php echo $row_coaches['MobilePhone']; ?></a></td>
      </tr>
      <tr>
        <td>Email: <a href="mailto:<?php echo $row_coaches['Email']; ?>"><?php echo $row_coaches['Email']; ?></a></td>
      </tr>
	  		<?php if(isset( $row_coaches['URL'])) {; // if coach has a website?>
      		<tr> 
        	<td>Website: <a href="<?php echo $row_coaches['../info/URL']; ?>" target="_blank"><?php echo $row_coaches['URL']; ?></a></td>
      		</tr>
      		<?php } else echo ""; ?>
      <tr>
        <td class="italic"><?php echo $row_coaches['comment']; ?></td>
        </tr>
      
  </table>
      <hr width="800" color="#999999" />
  <?php } while ($row_coaches = mysql_fetch_assoc($coaches)); ?>
</div>

<div class="table-responsive center-block" style="max-width:700px"></div>

</div>  <!-- close conraineing wrapper --> 
</body>
</html>
<?php

?>
