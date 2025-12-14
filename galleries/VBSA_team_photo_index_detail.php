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

$colname_team_photo = "-1";
if (isset($_GET['year'])) {
  $colname_team_photo = $_GET['year'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_team_photo = sprintf("SELECT * FROM gallery_team_photos WHERE gallery_team_photos.id <>1 AND gallery_team_photos.year_photo=%s AND Current ='Yes' ORDER BY season, grade", GetSQLValueString($colname_team_photo, "date"));
$team_photo = mysql_query($query_team_photo, $connvbsa) or die(mysql_error());
$row_team_photo = mysql_fetch_assoc($team_photo);
$totalRows_team_photo = mysql_num_rows($team_photo);

mysql_select_db($database_connvbsa, $connvbsa);
$query_grade = "SELECT * FROM Team_grade";
$grade = mysql_query($query_grade, $connvbsa) or die(mysql_error());
$row_grade = mysql_fetch_assoc($grade);
$totalRows_grade = mysql_num_rows($grade);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Archives</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</head>
<body id="vbsa">

<div class="container"> 

    <!-- Include header -->
<?php include '../includes/header.php';?>
    
    <!-- Include navigation -->
<?php include '../includes/nav_vbsa.php';?>
    
  </div>  

</div><!--End Bootstrap Container--> 

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 

  <!--Content--> 
  
  
  <!--Left--> 
  
<div class="row"> 
  <div class="Page_heading_container">
 		<div class="page_title"><span class="red_bold">Team Photo Gallery for <?php echo $row_team_photo['year_photo']; ?></span></div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 <!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>

    
  <div class="container center-block" style="max-width:800px; font-style:italic"> <!-- class table-responsive -->
    
    <?php do { ?>
  			<div style="width:45%; float:left; text-align:center; font-size:12px">
            <img src="../../galleries/team_photo/<?php echo $row_team_photo['club_photo']; ?>" width="90%" class="center-block" />
            <p>Grade: <?php echo $row_team_photo['grade']; ?></p>
            <p>Year: <?php echo $row_team_photo['year_photo']; ?> &nbsp;&nbsp;&nbsp;&nbsp;Season: <?php echo $row_team_photo['season']; ?></p>
            <p>To order please quote - Photo ID: <?php echo $row_team_photo['id']; ?></p>
  			</div>
            
  <?php } while ($row_team_photo = mysql_fetch_assoc($team_photo)); ?>
  </div>
  
  
  </div>

 

</div><!-- close conraineing wrapper -->   
 
</body>
</html>
<?php
mysql_free_result($team_photo);
?>
