<?php require_once('../Connections/connvbsa.php'); ?>
<?php

//echo("Cohort " . $_GET['cohort'] . "<br>");

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

$colname_tournRP = "-1";
if (isset($_GET['RP_sname'])) {
  //echo("Cohort " . $_GET['cohort'] . "<br>");
  $colname_tournRP = $_GET['RP_sname'];
}

if(isset($_GET['cohort']) && ($_GET['cohort'] != ''))
{
  $cohort = $_GET['cohort'];
  switch ($_GET['cohort'])
  {
      case 'all':
      //$query_tournRP = "Select ranknum, memb_id, FirstName, LastName, Female, total_tourn_rp, date_format(last_update,'%D %b %Y') AS last_update FROM rank_S_open_tourn  LEFT JOIN members ON MemberID = memb_id ORDER BY total_tourn_rp DESC";
      $query_tournRP = sprintf("Select ranknum, rank_S_open_tourn.memb_id, FirstName, LastName, tourn_total FROM rank_S_open_tourn LEFT JOIN members ON MemberID = rank_S_open_tourn.memb_id LEFT JOIN rank_aa_snooker_master ON rank_aa_snooker_master.memb_id = rank_S_open_tourn.memb_id WHERE LastName LIKE %s ORDER BY FirstName, LastName", GetSQLValueString($colname_tournRP . "%", "text"));
      break;
      case 'woman':
        //$query_tournRP = "Select ranknum, memb_id, FirstName, LastName, Female, total_tourn_rp, date_format(last_update,'%D %b %Y') AS last_update FROM rank_S_open_tourn  LEFT JOIN members ON MemberID = memb_id where Female = 1 ORDER BY total_tourn_rp DESC";
      $query_tournRP = sprintf("Select ranknum, rank_S_open_tourn.memb_id, FirstName, LastName, tourn_total FROM rank_S_open_tourn LEFT JOIN members ON MemberID = rank_S_open_tourn.memb_id LEFT JOIN rank_aa_snooker_master ON rank_aa_snooker_master.memb_id = rank_S_open_tourn.memb_id WHERE Female = 1 and LastName LIKE %s ORDER BY FirstName, LastName", GetSQLValueString($colname_tournRP . "%", "text"));
        break;
      case 'junior': 
        //$query_tournRP = "Select ranknum, memb_id, FirstName, LastName, Female, total_tourn_rp, date_format(last_update,'%D %b %Y') AS last_update FROM rank_S_open_tourn  LEFT JOIN members ON MemberID = memb_id where Junior != 'na' ORDER BY total_tourn_rp DESC";
      $query_tournRP = sprintf("Select ranknum, rank_S_open_tourn.memb_id, FirstName, LastName, tourn_total FROM rank_S_open_tourn LEFT JOIN members ON MemberID = rank_S_open_tourn.memb_id LEFT JOIN rank_aa_snooker_master ON rank_aa_snooker_master.memb_id = rank_S_open_tourn.memb_id WHERE Junior = 1 and LastName LIKE %s ORDER BY FirstName, LastName", GetSQLValueString($colname_tournRP . "%", "text"));
        break;
  }
}
else
{
  $query_tournRP = sprintf("Select ranknum, rank_S_open_tourn.memb_id, FirstName, LastName, tourn_total FROM rank_S_open_tourn LEFT JOIN members ON MemberID = rank_S_open_tourn.memb_id LEFT JOIN rank_aa_snooker_master ON rank_aa_snooker_master.memb_id = rank_S_open_tourn.memb_id WHERE LastName LIKE %s ORDER BY FirstName, LastName", GetSQLValueString($colname_tournRP . "%", "text"));
}


mysql_select_db($database_connvbsa, $connvbsa);
//$query_tournRP = sprintf("SELECT ranknum, rank_S_open_tourn.memb_id, FirstName, LastName, tourn_total FROM rank_S_open_tourn LEFT JOIN members ON MemberID = rank_S_open_tourn.memb_id LEFT JOIN rank_aa_snooker_master ON rank_aa_snooker_master.memb_id = rank_S_open_tourn.memb_id WHERE LastName LIKE %s ORDER BY FirstName, LastName", GetSQLValueString($colname_tournRP . "%", "text"));
$tournRP = mysql_query($query_tournRP, $connvbsa) or die(mysql_error());
$row_tournRP = mysql_fetch_assoc($tournRP);
$totalRows_tournRP = mysql_num_rows($tournRP);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Victorian Rankings</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />

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
 		<div class="page_title">Victorian Tournament Rankings - Search results</div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
<!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>

  <!-- Search Bar -->
  <div class="table-responsive center-block" style="max-width:250px" > 
  <table class="table">
  <tr>
    <td><form action="rankings_vic_snooker_name_srch_res.php" method="get" enctype="<?php echo $ranknum; ?>" name="RP_sname" id="RP_sname" >
        <label class="glyphicon glyphicon-search" style="margin-right:3px"></label>
        <input type='hidden' name='cohort'name='cohort' value='<?php echo($_GET['cohort']); ?>'>
        <input name="RP_sname" type="text" id="RP_sname" placeholder="Search by Surname" />
        </form>
    </td>
  </tr>
  </table>
  </div>

<div class="table-responsive center-block" style="max-width:700px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
        <tr>
          <th>Name</th>
          <th class="text-center">Ranked</th>
          <th class="text-center">Total RP</th>
          <th >&nbsp;</th>
        </tr>
        <?php do { ?>
        <tr>
          <td nowrap="nowrap"><?php echo $row_tournRP['FirstName']; ?> <?php echo $row_tournRP['LastName']; ?></td>
          <td class="text-center"><?php echo $row_tournRP['ranknum']; ?></td>
          <td class="text-center"><?php echo $row_tournRP['tourn_total']; ?></td>
          <td nowrap="nowrap"><a href="rankings_vic_snooker_detail.php?rank=<?php echo $row_tournRP['memb_id']; ?>">how these points were awarded</a></td>
        </tr>
        <?php } while ($row_tournRP = mysql_fetch_assoc($tournRP)); ?>
      </table>

</div>  <!-- close containing wrapper --> 
</body>
</html>
<?php

?>
