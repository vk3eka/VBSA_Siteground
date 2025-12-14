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

if(isset($_GET['cohort']))
{
  switch ($_GET['cohort'])
  {
    case 'all':
    $query_RPall = "Select scr_2yr_s1+scr_2yr_s2 as scr_2yr, scr_1yr_s1+scr_1yr_s2 as scr_1yr, scr_curr_s1+scr_curr_s2 as scr_curr, tourn_2+tourn_1+tourn_curr+rank_a_billiards_master.total_rp as total_points, ranknum, rank_Billiards.memb_id, FirstName, LastName, Female, Junior, rank_Billiards.total_rp, date_format(rank_Billiards.last_update,'%D %b %Y') AS last_update FROM rank_Billiards LEFT JOIN members ON MemberID = rank_Billiards.memb_id LEFT JOIN rank_a_billiards_master ON rank_a_billiards_master.memb_id = rank_Billiards.memb_id ORDER BY rank_Billiards.total_rp DESC";
      break;
    case 'woman':
      $query_RPall = "Select scr_2yr_s1+scr_2yr_s2 as scr_2yr, scr_1yr_s1+scr_1yr_s2 as scr_1yr, scr_curr_s1+scr_curr_s2 as scr_curr, tourn_2+tourn_1+tourn_curr+rank_a_billiards_master.total_rp as total_points, ranknum, rank_Billiards.memb_id, FirstName, LastName, Female, Junior, rank_Billiards.total_rp, date_format(rank_Billiards.last_update,'%D %b %Y') AS last_update FROM rank_Billiards LEFT JOIN members ON MemberID = rank_Billiards.memb_id LEFT JOIN rank_a_billiards_master ON rank_a_billiards_master.memb_id = rank_Billiards.memb_id where Female = 1 ORDER BY rank_Billiards.total_rp DESC";
      break;
    case 'junior': 
      $query_RPall = "Select scr_2yr_s1+scr_2yr_s2 as scr_2yr, scr_1yr_s1+scr_1yr_s2 as scr_1yr, scr_curr_s1+scr_curr_s2 as scr_curr, tourn_2+tourn_1+tourn_curr+rank_a_billiards_master.total_rp as total_points, ranknum, rank_Billiards.memb_id, FirstName, LastName, Female, Junior, rank_Billiards.total_rp, date_format(rank_Billiards.last_update,'%D %b %Y') AS last_update FROM rank_Billiards LEFT JOIN members ON MemberID = rank_Billiards.memb_id LEFT JOIN rank_a_billiards_master ON rank_a_billiards_master.memb_id = rank_Billiards.memb_id where Junior != 'na' ORDER BY rank_Billiards.total_rp DESC";
      break;
  }
}
else
{
  //$query_RPall = "Select scr_2yr_s1+scr_2yr_s2 as scr_2yr, scr_1yr_s1+scr_1yr_s2 as scr_1yr, scr_curr_s1+scr_curr_s2 as scr_curr, tourn_2+tourn_1+tourn_curr+rank_a_billiards_master.total_rp as total_points, ranknum, rank_Billiards.memb_id, FirstName, LastName, Female, Junior, rank_Billiards.total_rp, date_format(rank_Billiards.last_update,'%D %b %Y') AS last_update FROM rank_Billiards LEFT JOIN members ON MemberID = rank_Billiards.memb_id LEFT JOIN rank_a_billiards_master ON rank_a_billiards_master.memb_id = rank_Billiards.memb_id ORDER BY rank_Billiards.total_rp DESC";
  $query_RPall = "Select 
  tourn_2, 
  tourn_1, 
  tourn_curr, 
  scr_curr_S1, 
  scr_curr_S2,
  scr_1yr_S1, 
  scr_1yr_S2,
  scr_2yr_S1, 
  scr_2yr_S2,
  brks_1, 
  brks_2, 
  brks_curr, 
  brks_1yr_S1, 
  brks_1yr_S2, 
  brks_2yr_S1,
  brks_2yr_S2, 
  brks_curr_S1,
  brks_curr_S2, 
  Round((ROUND(tourn_2)) + 
  (ROUND(tourn_1)) + 
  tourn_curr + 
  brks_curr + 
  (ROUND(brks_2)*35/100) + 
  (ROUND(brks_1)*65/100) +
  (ROUND(scr_curr_S2)) + 
  (ROUND(scr_curr_S1)) + 
  (ROUND(scr_1yr_S1)) +
  (ROUND(scr_1yr_S2)) +
  (ROUND(scr_2yr_S1)) +
  (ROUND(scr_2yr_S2))
  ) 
  as total_points,
  ranknum, 
  rank_Billiards.memb_id, 
  FirstName, 
  LastName, 
  Female, 
  Junior, 
  date_format(rank_Billiards.last_update,'%D %b %Y') AS last_update 
  FROM rank_Billiards 
  LEFT JOIN members ON MemberID = rank_Billiards.memb_id 
  LEFT JOIN rank_a_billiards_master ON rank_a_billiards_master.memb_id = rank_Billiards.memb_id 
  ORDER BY rank_Billiards.total_rp DESC";
}

//echo($query_RPall . "<br>");

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
 		<div class="page_title">Victorian Rankings - Billiards</div>
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
            <input name="RP_sname" type="text" id="RP_sname"  placeholder="Search by Surname" />
            </form>
        </td>
      </tr>
    </table>
  </div>
<!--<div class="table-responsive center-block" style="max-width:500px; padding-left:3px">
<form name='cohort_selection' method="post" action='rankings_vic_billiards.php'>
  <table class="table">
    <input type='hidden' name='cohort'name='cohort'>
  <tr>
    <td align='center' width="200" ><a class="btn btn-sm btn-primary" style='width:150px;' href="rankings_vic_billiards.php?cohort=all">All</a></td>
    <td align='center' width="200" ><a class="btn btn-sm btn-primary" style='width:150px;' href="rankings_vic_billiards.php?cohort=woman">Womens</a></td>
    <td align='center' width="200" ><a class="btn btn-sm btn-primary" style='width:150px;' href="rankings_vic_billiards.php?cohort=junior">Juniors</a></td>
  </tr>
</table>
</form>
</div>--> 
 <div class="table-responsive center-block" style="max-width:1000px; padding-left:3px">
   <table class="table" border =1>
    <tr>
      <th rowspan=2 class="text-center">Currently ranked</th>
      <th rowspan=2 class="text-center">Member ID </th>
      <th rowspan=2 class="text-left">Name</th>
      <th colspan=3 class="text-center">Tournaments</th>
      <th colspan=3 class="text-center">Breaks</th>
      <th colspan=2 class="text-center">Pennant</th>
      <th rowspan=2 class="text-center">Total Points</th>
      <th rowspan=2 >&nbsp;</th>
    </tr>
    <tr>
      
      <th class="text-center">2021 Points</th>
      <th class="text-center">2022 Points</th>
      <th class="text-center">2023 Points</th>

      <th class="text-center">2021 Points</th>
      <th class="text-center">2022 Points</th>
      <th class="text-center">2023 Points</th>

      <th class="text-center">2023 Pennant S1</th>
      <th class="text-center">2023 Pennant S2</th>
    </tr>
    <?php do { 
      /*
      if($row_RPall['memb_id'] == 3833)
      {
      echo("Tourn 2 ". (($row_RPall['tourn_2'])) . "<br>");
      echo("Tourn 1 ". (($row_RPall['tourn_1'])) . "<br>");
      echo("Tourn Curr ". $row_RPall['tourn_curr'] . "<br>");
      echo("Breaks Curr ". $row_RPall['brks_curr'] . "<br>");
      echo("Breaks 2 ". (($row_RPall['brks_2'])*35/100) . "<br>");
      echo("Breaks 1 ". (($row_RPall['brks_1'])*65/100) . "<br>");
      echo("Scrs Curr ". (($row_RPall['scr_curr_S2']+$row_RPall['scr_curr_S1'])) . "<br>");
      echo("Scrs 1 ". (($row_RPall['scr_1yr_S1']+$row_RPall['scr_1yr_S2'])) . "<br>");
      echo("Scrs 2 ". (($row_RPall['scr_2yr_S1']+$row_RPall['scr_2yr_S2'])) . "<br>");
    }*/
      ?>
    <tr>
      <td class="text-center"><?php echo $row_RPall['ranknum']; ?></td>
      <td class="text-center"><?php echo $row_RPall['memb_id']; ?></td>
      <td class="text-left" nowrap="nowrap"><?php echo $row_RPall['FirstName']. " ". $row_RPall['LastName']; ?></td>
      <td class="text-center"><?php echo $row_RPall['tourn_2']; ?></td>
      <td class="text-center"><?php echo $row_RPall['tourn_1']; ?></td>
      <td class="text-center"><?php echo $row_RPall['tourn_curr']; ?></td>
      <td class="text-center"><?php echo Round($row_RPall['brks_2']*.35); ?></td>
      <td class="text-center"><?php echo Round($row_RPall['brks_1']*.65); ?></td>
      <td class="text-center"><?php echo $row_RPall['brks_curr']; ?></td>
      <td class="text-center"><?php echo $row_RPall['scr_curr_S1']; ?></td>
      <td class="text-center"><?php echo $row_RPall['scr_curr_S2']; ?></td>
      <td class="text-center"><?php echo $row_RPall['total_points']; ?></td>
      <td class="text-center" nowrap="nowrap"><a href="rankings_vic_billiards_detail.php?rank=<?php echo $row_RPall['memb_id']; ?>">How awarded</a></td>
    </tr>
    <?php } while ($row_RPall = mysql_fetch_assoc($RPall)); ?>
    </table>
  </div>
</div>  <!-- close containing wrapper --> 
</body>
</html>
