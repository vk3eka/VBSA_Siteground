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
$query_Clubs = "SELECT * FROM clubs WHERE Club_Aff_Assoc = 'Club' AND clubs.WSBSA=1 AND inactive=1 ORDER BY ClubTitle ASC";
$Clubs = mysql_query($query_Clubs, $connvbsa) or die(mysql_error());
$row_Clubs = mysql_fetch_assoc($Clubs);
$totalRows_Clubs = mysql_num_rows($Clubs);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Victorian Clubs</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</head>
<body id="vbsa">
    
    <!-- Include Google Tracking -->
<?php include_once("../includes/analyticstracking.php") ?>

<div class="container"> 

    <!-- Include header --><!-- Include navigation -->
<?php include '../includes/nav_vbsa.php';?>
<?php include '../includes/header.php';?>

</div><!--End Bootstrap Container--> 

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 
   
  <div class="row"> 
  <div class="Page_heading_container">
 		<div class="page_title">Victorian Club Directory - Western Suburbs BSA  </div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
  <!-- Include link to previous page or Club Directory index -->
<?php include '../includes/prev_page_clubz.php';?>

  <div class="row"> 
  		 <div class="text_box text-justify">
         Looking for somewhere to play Snooker or Billiards in Melbourne or country Victoria. Clubs are listed  here to foster the sport. The VBSA offer this listing free. 
  If your Club, or Association is not listed please contact the <a href="mailto:web@vbsa.org.au">VBSA Webmaster.</a>&nbsp;&nbsp;Search criteria: To search clubs by name, you do not have to type the whole name simply the starting lettter or the first 2 letters to refine the search.&nbsp;&nbsp;Total Clubs listed : <?php echo $totalRows_Clubs ?>
  		</div>
  </div>
  
  <!-- Club Search -->
  <div class="center-block" style="max-width:250px; padding:10px">
        <form id="clubfind" name="clubfind" method="get" action="club_search_res.php?clubfind=<?php echo $row_Clubs['ClubTitle']; ?>">
        <label class="glyphicon glyphicon-search" style="margin-right:3px"></label>
        <input type="text" name="clubfind" id="clubfind" placeholder="Search clubs by name" />
        </form>
  </div> 
  
  <!-- Club Search by Association -->
  <div class="center-block" style="max-width:600px; height:35px; clear:both"> 
        
                <!-- Include Club menu -->
		<?php include '../includes/clubs_menu.php';?>

        <div class="dropdown pull-right"><input type="button" class="btn-sm" onclick="location.href='clubs_aff_assoc.php'" value='Association/Affiliate Details' /></div>	
   </div> <!-- Close Club Search by Association -->
   
   <div class="center-block" style="max-width:600px"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div>    
   
<!-- Club Information in repeated table -->
<?php do { 
    // Generate Google Maps link using address details
    $mapsAddress = urlencode($row_Clubs['ClubStreet'] . ', ' . $row_Clubs['ClubSuburb'] . ', ' . $row_Clubs['ClubPcode']);
    $googleMapsLink = "https://www.google.com/maps/search/?api=1&query=" . $mapsAddress;
?>
   <div class="table-responsive center-block" style="max-width:600px; margin-top:10px; clear:both">
   <table class="table no-border">
  		<thead>
    		<tr>
      			<th colspan="2" nowrap="nowrap"><?php echo $row_Clubs['ClubTitle']; ?>, <?php echo $row_Clubs['ClubStreet']; ?>, <?php echo $row_Clubs['ClubSuburb']; ?>, <?php echo $row_Clubs['ClubPcode']; ?></th>
      		</tr>
   		</thead>
   		<tbody>    
    		<tr>
      			<td>Phone: <a href="tel:<?php echo $row_Clubs['ClubPhone1']; ?>"><?php echo $row_Clubs['ClubPhone1']; ?></a></td>
      			<td rowspan="5"><img src="http://www.vbsa.org.au/ClubImages/<?php echo $row_Clubs['ClubLogo']; ?>" class="img-responsive pull-right" style="max-height:120px" /></td>
      		</tr>
    		<tr>
      			<td>Tables: <?php echo $row_Clubs['ClubTables']; ?></td>
      		</tr>
    		<tr>
      			<td>Email: <a href="mailto:<?php echo $row_Clubs['ClubEmail']; ?>"><?php echo $row_Clubs['ClubEmail']; ?></a></td>
      		</tr>
    		<tr>
      			<td>Web Address: <a href="<?php echo $row_Clubs['ClubLink']; ?>" target="_blank"><?php echo $row_Clubs['ClubLink']; ?></a></td>
     		</tr>
            <tr>
                <td>Maps Link: <a href="<?php echo $googleMapsLink; ?>" target="_blank">View on Google Maps</a></td>
            </tr>
    	</tbody>
   </table>
   </div>  
<?php } while ($row_Clubs = mysql_fetch_assoc($Clubs)); ?> 

</div>  <!-- close containing wrapper --> 
</body>