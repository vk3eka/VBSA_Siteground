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

/*$query_RPall = "Select 
Round((ROUND(tourn_2)) + 
(ROUND(tourn_1)) + 
tourn_curr + 
brks_curr + 
(ROUND(brks_2)) + 
(ROUND(brks_1)) +
(ROUND(scr_curr_S2)) + 
(ROUND(scr_curr_S1)) + 
(ROUND(scr_1yr_S1)) +
(ROUND(scr_1yr_S2)) +
(ROUND(scr_2yr_S1)) +
(ROUND(scr_2yr_S2))
) 
as total_rp,
rank_a_billiards_master.memb_id, 
rank_Billiards.ranknum, 
rank_Billiards.previous_rank, 
T1.FirstName, 
T1.LastName, 
T1.Female, 
T1.Junior, 
date_format(rank_a_billiards_master.last_update,'%D %b %Y') AS last_update
FROM rank_a_billiards_master
LEFT JOIN members T1 ON T1.MemberID = rank_a_billiards_master.memb_id 
LEFT JOIN rank_Billiards ON rank_Billiards.memb_id = T1.MemberID 
ORDER BY total_rp DESC";
*/
$query_RPall = "Select total_rp, memb_id, ranknum, previous_rank, FirstName, LastName, Female, Junior, date_format(last_update,'%D %b %Y') AS last_update FROM rank_Billiards LEFT JOIN members ON members.MemberID = rank_Billiards.memb_id ORDER BY total_rp DESC";

$RPall = mysql_query($query_RPall, $connvbsa) or die(mysql_error());
$row_RPall = mysql_fetch_assoc($RPall);
$totalRows_RPall = mysql_num_rows($RPall);
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
  <script type="text/javascript">
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
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
 		<div class="page_title">Victorian Rankings - Billiards (Open)</div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
 <div class="table-condensed" style="padding-bottom:10px">
      <table align="center">
          <tr>
            <td style="padding-right:10px"><input type="button" class="btn-xs btn-default btn-responsive center-block" role="button" value="Return to previous page" onclick="window.history.go(-1); return false;"/></td>
            <td><input type="button" class="btn-xs btn-default btn-responsive center-block" onclick="MM_goToURL('parent','rankings_index.php');return document.MM_returnValue" value="Return to Rankings index page" role="button"/></td>
          </tr>
      </table>
 </div>
    
  <!-- Search Bar -->
  <div class="table-responsive center-block" style="max-width:250px" > 
  <table class="table">
  <tr>
    <td><form id="RP_sname" name="RP_sname" method="get" action="rankings_vic_billiards_name_srch_res.php">
        <label class="glyphicon glyphicon-search" style="margin-right:3px"></label>
        <input type='hidden' name='cohort'name='cohort' value='<?php echo($_GET['cohort']); ?>'>
        <input name="RP_sname" type="text" id="RP_sname"  placeholder="Search by Surname" />
        </form>
    </td>
  </tr>
  </table>
  </div>
<div class="table-responsive center-block" style="max-width:500px; padding-left:3px">
   </div> 
   <div class="table-responsive center-block" style="max-width:750px; padding-left:3px">
   <table class="table">
    <tr>
        <th class="text-center" colspan='2'>Rank</th>
        <th class="text-left">Name</th>
        <th class="text-center">Total Points</th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th class="text-center">Current</th>
        <th class="text-center">Previous</th>
      </tr>
      <!--<tr>
        <th class="text-center">Current Rank</th>
        <th class="text-left">Name</th>
        <th class="text-center">Total Points</th>
        <th>&nbsp;</th>
      </tr>>-->
      <?php 
        //$rank_number = 0;
        do { 
          $query_Previous = "Select previous_rank, memb_id, total_rp, date_format(last_update,'%D %b %Y') AS last_update FROM temp_open_billiards_ranking Where memb_id = " . $row_RPall['memb_id'];
          $Previous = mysql_query($query_Previous, $connvbsa) or die(mysql_error());
          $row_Previous = mysql_fetch_assoc($Previous);

          ?>
        <tr>
          <td class="text-center"><?php echo $row_RPall['ranknum']; ?></td>
          <td class="text-center"><?php echo $row_Previous['previous_rank']; ?></td>
          <td class="text-left" nowrap="nowrap"><?php echo $row_RPall['FirstName']. " ". $row_RPall['LastName']; ?> </td>
          <td class="text-center"><?php echo $row_RPall['total_rp']; ?></td>
          <td class="text-center" nowrap="nowrap"><a href="rankings_vic_billiards_detail.php?rank=<?php echo $row_RPall['memb_id']; ?>&cohort=all">How awarded</a></td>
        </tr>
        <?php 
      } while ($row_RPall = mysql_fetch_assoc($RPall)); 
      ?>
  </table>
  </div>
</div>  <!-- close containing wrapper --> 
</body>
</html>
