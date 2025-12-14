<?php require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

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
  $colname_tournRP = $_GET['RP_sname'];
}

if(isset($_GET['cohort']) && ($_GET['cohort'] != ''))
{
  $cohort = $_GET['cohort'];
  switch ($cohort)
  {
    case 'all':
      $query_tournRP = sprintf("Select ranknum, previous_rank, rank_S_open_tourn.memb_id, FirstName, LastName, tourn_total FROM rank_S_open_tourn LEFT JOIN members ON MemberID = rank_S_open_tourn.memb_id LEFT JOIN rank_aa_snooker_master ON rank_aa_snooker_master.memb_id = rank_S_open_tourn.memb_id WHERE LastName LIKE %s ORDER BY FirstName, LastName", GetSQLValueString($colname_tournRP . "%", "text"));
      $previous_rank = "temp_open_snooker_ranking";
      break;
    case 'woman':
      $query_tournRP = sprintf("Select ranknum, rank_S_open_tourn.memb_id, FirstName, LastName, tourn_total FROM rank_S_open_tourn LEFT JOIN members ON MemberID = rank_S_open_tourn.memb_id LEFT JOIN rank_aa_snooker_master ON rank_aa_snooker_master.memb_id = rank_S_open_tourn.memb_id WHERE Gender = 'Female' and LastName LIKE %s ORDER BY FirstName, LastName", GetSQLValueString($colname_tournRP . "%", "text"));
      $previous_rank = "temp_womens_snooker_ranking";
      break;
    case 'junior': 
      $query_tournRP = sprintf("Select ranknum, rank_S_open_tourn.memb_id, FirstName, LastName, tourn_total FROM rank_S_open_tourn LEFT JOIN members ON MemberID = rank_S_open_tourn.memb_id LEFT JOIN rank_aa_snooker_master ON rank_aa_snooker_master.memb_id = rank_S_open_tourn.memb_id WHERE Junior != 'na' and LastName LIKE %s ORDER BY FirstName, LastName", GetSQLValueString($colname_tournRP . "%", "text"));
      $previous_rank = "temp_junior_snooker_ranking";
      break;
  }
}
else
{
  $query_tournRP = sprintf("Select ranknum, rank_S_open_tourn.memb_id, FirstName, LastName, tourn_total FROM rank_S_open_tourn LEFT JOIN members ON MemberID = rank_S_open_tourn.memb_id LEFT JOIN rank_aa_snooker_master ON rank_aa_snooker_master.memb_id = rank_S_open_tourn.memb_id WHERE LastName LIKE %s ORDER BY FirstName, LastName", GetSQLValueString($colname_tournRP . "%", "text"));
}
//echo("SQL " . $query_tournRP . "<br>");

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
    <td>
      <form action="rankings_vic_snooker_name_srch_res.php" method="get" enctype="<?php echo $ranknum; ?>" >
        <label class="glyphicon glyphicon-search" style="margin-right:3px"></label>
        <input type='hidden' name='cohort' id='cohort' value='<?= $_GET['cohort'] ?>'>
        <input name="RP_sname" type="text" id="RP_sname" placeholder="Search by Surname" />
      </form>
    </td>
  </tr>
  </table>
  </div>

<div class="table-responsive center-block" style="max-width:700px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
        <tr>
          <th>Member ID </th>
        <th class="text-center" colspan='2'>Rank</th>
        <th class="text-left">Name</th>
        <th class="text-center">Total Points</th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th class="text-center">Current</th>
        <th class="text-center">Previous</th>
      </tr>
        <?php do { 
          $query_Previous = "Select previous_rank, memb_id, total_rp, date_format(last_update,'%D %b %Y') AS last_update FROM " . $previous_rank . " Where memb_id = " . $row_tournRP['memb_id'];
          $Previous = mysql_query($query_Previous, $connvbsa) or die(mysql_error());
          $rows = $Previous->num_rows;
          $row_Previous = mysql_fetch_assoc($Previous);
          if($rows > 0)
          {
            $Previous_rank = $row_Previous['previous_rank'];
          }
          else
          {
            $Previous_rank = '';
          }
    ?>
        <tr>
          <td class="text-center"><?php echo $row_tournRP['memb_id']; ?></td>
          <td class="text-center"><?php echo $row_tournRP['ranknum']; ?></td>
          <td class="text-center"><?php echo $Previous_rank; ?></td>
          <td nowrap="nowrap"><?php echo $row_tournRP['FirstName']; ?> <?php echo $row_tournRP['LastName']; ?></td>
          <td class="text-center"><?php echo $row_tournRP['tourn_total']; ?></td>
          <td nowrap="nowrap"><a href="rankings_vic_snooker_detail.php?rank=<?php echo $row_tournRP['memb_id']; ?>&cohort=all">how these points were awarded</a></td>
        </tr>
        <?php } while ($row_tournRP = mysql_fetch_assoc($tournRP)); ?>
      </table>

</div>  <!-- close containing wrapper --> 
</body>
</html>
<?php

?>
