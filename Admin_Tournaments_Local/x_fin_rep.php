<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../page_error.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}

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

if (isset($_GET['tourn_id'])) {
  $tourn_id = $_GET['tourn_id'];
}

if (isset($_GET['tourn_year'])) {
  $tourn_year = $_GET['tourn_year'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn_name = "SELECT tourn_id, tourn_name, tourn_year FROM tournaments WHERE tourn_id = '$tourn_id'";
$tourn_name = mysql_query($query_tourn_name, $connvbsa) or die(mysql_error());
$row_tourn_name = mysql_fetch_assoc($tourn_name);
$totalRows_tourn_name = mysql_num_rows($tourn_name);

mysql_select_db($database_connvbsa, $connvbsa);
$query_entries = "SELECT tournament_number, SUM( amount_entry ) AS ent_total, (SELECT SUM(amount_entry*.024) + COUNT(amount_entry)*.3 FROM tourn_entry WHERE how_paid='PP' AND tournament_number = '$tourn_id') AS pp_cost FROM tourn_entry WHERE tournament_number = '$tourn_id'";
$entries = mysql_query($query_entries, $connvbsa) or die(mysql_error());
$row_entries = mysql_fetch_assoc($entries);
$totalRows_entries = mysql_num_rows($entries);

mysql_select_db($database_connvbsa, $connvbsa);
$query_entries_count = "SELECT COUNT(tourn_memb_id) AS entries_all FROM tourn_entry WHERE tournament_number= '$tourn_id'";
$entries_count = mysql_query($query_entries_count, $connvbsa) or die(mysql_error());
$row_entries_count = mysql_fetch_assoc($entries_count);
$totalRows_entries_count = mysql_num_rows($entries_count);

mysql_select_db($database_connvbsa, $connvbsa);
$query_income = "SELECT ID, tourn_fin_id, item_type, item_desc, item_amount FROM tourn_fin WHERE tourn_fin_id =  '$tourn_id' AND item_type='Income'";
$income = mysql_query($query_income, $connvbsa) or die(mysql_error());
$row_income = mysql_fetch_assoc($income);
$totalRows_income = mysql_num_rows($income);

mysql_select_db($database_connvbsa, $connvbsa);
$query_expend = "SELECT ID, tourn_fin_id, item_type, item_desc, item_amount FROM tourn_fin WHERE tourn_fin_id =  '$tourn_id' AND item_type='Expenditure' AND item_cat<>'Prizefund'";
$expend = mysql_query($query_expend, $connvbsa) or die(mysql_error());
$row_expend = mysql_fetch_assoc($expend);
$totalRows_expend = mysql_num_rows($expend);

mysql_select_db($database_connvbsa, $connvbsa);
$query_exp_subtotal = "SELECT (SELECT SUM(IFNULL(amount_entry*.024,0))+COUNT(IFNULL(amount_entry,0))*.3 FROM tourn_entry WHERE tournament_number = '$tourn_id' AND how_paid='PP')+(SELECT COALESCE(SUM(item_amount),0) FROM tourn_fin WHERE tourn_fin_id = '$tourn_id' AND item_type='Expenditure') AS exp_total";
$exp_subtotal = mysql_query($query_exp_subtotal, $connvbsa) or die(mysql_error());
$row_exp_subtotal = mysql_fetch_assoc($exp_subtotal);
$totalRows_exp_subtotal = mysql_num_rows($exp_subtotal);

mysql_select_db($database_connvbsa, $connvbsa);
$query_inc_subtotal = "SELECT (SELECT SUM(IFNULL(amount_entry,0)) FROM tourn_entry WHERE tournament_number = '$tourn_id')+(SELECT COALESCE(SUM(item_amount),0) FROM tourn_fin WHERE tourn_fin_id = '$tourn_id' AND item_type='Income') AS inc_total";
$inc_subtotal = mysql_query($query_inc_subtotal, $connvbsa) or die(mysql_error());
$row_inc_subtotal = mysql_fetch_assoc($inc_subtotal);
$totalRows_inc_subtotal = mysql_num_rows($inc_subtotal);

mysql_select_db($database_connvbsa, $connvbsa);
$query_balance = "SELECT ( SELECT SUM( IFNULL( amount_entry, 0 ) ) FROM tourn_entry WHERE tournament_number = '$tourn_id' ) + ( SELECT COALESCE( SUM( item_amount ) , 0 ) FROM tourn_fin WHERE tourn_fin_id = '$tourn_id' AND item_type = 'Income' ) - ( SELECT SUM( IFNULL( amount_entry * .024, 0 ) ) + COUNT( IFNULL( amount_entry, 0 ) ) * .3 FROM tourn_entry WHERE tournament_number = '$tourn_id' AND how_paid = 'PP' ) - ( SELECT COALESCE( SUM( item_amount ) , 0 ) FROM tourn_fin WHERE tourn_fin_id = '$tourn_id' AND item_type = 'Expenditure' ) AS balance";
$balance = mysql_query($query_balance, $connvbsa) or die(mysql_error());
$row_balance = mysql_fetch_assoc($balance);
$totalRows_balance = mysql_num_rows($balance);

mysql_select_db($database_connvbsa, $connvbsa);
$query_expend_PF = "SELECT ID, tourn_fin_id, item_type, item_desc, item_amount, paid_to, prizefund_rd FROM tourn_fin WHERE tourn_fin_id =  '$tourn_id' AND item_type='Expenditure' AND item_cat='Prizefund' ORDER BY prizefund_rd";
$expend_PF = mysql_query($query_expend_PF, $connvbsa) or die(mysql_error());
$row_expend_PF = mysql_fetch_assoc($expend_PF);
$totalRows_expend_PF = mysql_num_rows($expend_PF);

mysql_select_db($database_connvbsa, $connvbsa);
$query_expend_PF_total = "SELECT SUM(item_amount) as PFtotal FROM tourn_fin WHERE tourn_fin_id =  '$tourn_id' AND item_type='Expenditure' AND item_cat='Prizefund'";
$expend_PF_total = mysql_query($query_expend_PF_total, $connvbsa) or die(mysql_error());
$row_expend_PF_total = mysql_fetch_assoc($expend_PF_total);
$totalRows_expend_PF_total = mysql_num_rows($expend_PF_total);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />


<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch_treas.php';?>
<table width="1000" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td align="left"><?php echo $_SESSION['tourn_page'] ?></td>
    <td align="right" class="greenbg"><a href="<?php echo $_SESSION['tourn_page'] ?>?tourn_id=<?php echo $tourn_id ?>&tourn_year=<?php echo $tourn_year ?>">Return to previous page</a></td>
  </tr>
</table>

  <table width="1000" align="center" cellpadding="3" cellspacing="3">
    <tr>
      <td colspan="5"><span class="red_bold">Tournament Financial Report </span>for the
        <?php $date = $row_tourn_name['tourn_year']; echo date("Y", strtotime($date)); ?>
      <?php echo $row_tourn_name['tourn_name']; ?> (Tournament ID: <?php echo $row_tourn_name['tourn_id']; ?>)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>Insert Income item</td>
      <td class="greenbg"><a href="user_files/x_fin_rep_exp_insert.php?tourn_id=<?php echo $tourn_id ?>">Insert Expenditure item</a></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="black_bld_txt">Income </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="left"><span class="title">Paid Entries (<?php echo $row_entries_count['entries_all']; ?> entries)</span></td>
      <td align="left">&nbsp;</td>
      <td align="left"><?php echo "$ ".number_format ($row_entries['ent_total'], 2); ?></td>
      <td align="left">&nbsp;</td>
      <td align="left">Edit inTournaments</td>
    </tr>
    <?php do { ?> 
      <tr>
        
        <td align="left"><?php echo $row_income['item_desc']; ?></td>
        <td align="left">&nbsp;</td>
        <td align="left"><?php echo "$ ".number_format ($row_income['item_amount'], 2); ?></td>
        <td align="left">&nbsp;</td>
        <td align="left"><a href="user_files/x_fin_rep_item_edit.php?item_id=<?php echo $row_income['ID']; ?>?tourn_id=<?php echo $tourn_id ?>"><img src="../Admin_Images/edit_butt.png" alt="" width="16" height="16" /></a></td>   
      </tr>
      <?php } while ($row_income = mysql_fetch_assoc($income)); ?>
    
    <tr>
        <td align="left">&nbsp;</td>
        <td align="left">&nbsp;</td>
        <td align="left">&nbsp;</td>
        <td align="left">&nbsp;</td>
        <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td align="left"><span class="income"><span class="red_bold">Expenditure</span></span></td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td align="left">Paypal Costs on entries (2.4% +30 cents per entry)</td>
      <td align="center">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left"><span class="red_text"><?php echo "-$ ".number_format ($row_entries['pp_cost'], 2); ?></span></td>
      <td align="left">Auto calculated</td>
    </tr>
    <tr>
      <td align="left">Prizefund (total paid prizefund = $<?php echo $row_expend_PF_total['PFtotal']; ?>)</td>
      <td align="center">Round</td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left">Auto calculated</td>
    </tr>
    <?php do { ?>
      <tr>
        <td align="left"><?php echo $row_expend_PF['paid_to']; ?></td>
        <td align="center"><?php echo $row_expend_PF['prizefund_rd']; ?></td>
        <td align="left">&nbsp;</td>
        <td align="left"><Span class="red_text"><?php echo "-$ ".number_format ($row_expend_PF['item_amount'], 2); ?></Span></td>
        <td align="left"><a href="user_files/x_fin_rep_item_edit.php?item_id=<?php echo $row_expend_PF['ID']; ?>?tourn_id=<?php echo $tourn_id ?>"><img src="../Admin_Images/edit_butt.png" alt="" width="16" height="16" /></a></td>
      </tr>
      <?php } while ($row_expend_PF = mysql_fetch_assoc($expend_PF)); ?>
   
	
	<?php do { ?>
      <tr>
        
        <td align="left"><?php echo $row_expend['item_desc']; ?></td>
        <td align="left">&nbsp;</td>
        <td align="left">&nbsp;</td>
        <td align="left"><Span class="red_text"><?php echo "-$ ".number_format ($row_expend['item_amount'], 2); ?></Span></td>
        <td align="left"><a href="user_files/x_fin_rep_item_edit.php?item_id=<?php echo $row_expend['ID']; ?>?tourn_id=<?php echo $tourn_id ?>"><img src="../Admin_Images/edit_butt.png" alt="" width="16" height="16" /></a></td>
      </tr>
      <?php } while ($row_expend = mysql_fetch_assoc($expend)); ?>
     <tr>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td align="left">Sub Totals</td>
      <td align="left">&nbsp;</td>
      <td align="left"><?php echo "$ ".number_format ($row_inc_subtotal['inc_total'], 2); ?></td>
      <td align="left"><Span class="red_text"><?php echo "-$ ".number_format ($row_exp_subtotal['exp_total'], 2); ?></Span></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td align="left">Balance - Profit / Loss</td>
      <td align="left">&nbsp;</td>
      <td align="left"><?php
	if ($row_balance['balance']>=0)
	{
	echo '<span class="black_bld_txt">';
    echo "$ ".number_format ($row_balance['balance'], 2); 
	echo '</span>';
	}
	else
	{
	echo"";	
	}
	
	?></td>
      <td align="left"><?php
	if ($row_balance['balance']<0)
	{
	echo '<span class="red_bold">';
    echo "$ ".number_format ($row_balance['balance'], 2); 
	echo '</span>';
	}
	else
	{
	echo"";	
	}
	
	?></td>
      <td align="left">&nbsp;</td>
    </tr>
  </table>
  
</div>
</body>
</html>
<?php


?>