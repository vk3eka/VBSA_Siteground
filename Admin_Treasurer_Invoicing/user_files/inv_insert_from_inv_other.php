<?php require_once('../../Connections/connvbsa.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO inv_to (inv_id, club_id, inv_busname, inv_to, inv_email, inv_phone, inv_type, inv_status, inv_cal_year, inv_fin_year, inv_random) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['inv_id'], "int"),
                       GetSQLValueString($_POST['club_id'], "int"),
                       GetSQLValueString($_POST['inv_busname'], "text"),
                       GetSQLValueString($_POST['inv_to'], "text"),
                       GetSQLValueString($_POST['inv_email'], "text"),
                       GetSQLValueString($_POST['inv_phone'], "text"),
                       GetSQLValueString($_POST['inv_type'], "text"),
                       GetSQLValueString($_POST['inv_status'], "text"),
                       GetSQLValueString($_POST['inv_cal_year'], "date"),
                       GetSQLValueString($_POST['inv_fin_year'], "date"),
                       GetSQLValueString($_POST['inv_random'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "../inv_2year_other.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO inv_to (inv_id, inv_busname, inv_to, inv_email, inv_phone, inv_type, inv_status, inv_cal_year, inv_fin_year, inv_random) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['inv_id'], "int"),
                       GetSQLValueString($_POST['inv_busname'], "text"),
                       GetSQLValueString($_POST['inv_to'], "text"),
                       GetSQLValueString($_POST['inv_email'], "text"),
                       GetSQLValueString($_POST['inv_phone'], "text"),
					   GetSQLValueString($_POST['inv_type'], "text"),
                       GetSQLValueString($_POST['inv_status'], "text"), 
                       GetSQLValueString($_POST['inv_cal_year'], "date"),
                       GetSQLValueString($_POST['inv_fin_year'], "date"),
                       GetSQLValueString($_POST['inv_random'], "int"));

  $insertGoTo = $_SESSION['inv_page'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if (isset($_GET['inv_type'])) {
  $inv_type = $_GET['inv_type'];
}

if (isset($_GET['inv_id'])) {
  $inv_id = $_GET['inv_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_create_inv = "SELECT inv_id, inv_busname, inv_to, inv_street, inv_suburb, inv_suburb, inv_postcode, inv_email, inv_phone FROM inv_to  WHERE inv_id='$inv_id'";
$create_inv = mysql_query($query_create_inv, $connvbsa) or die(mysql_error());
$row_create_inv = mysql_fetch_assoc($create_inv);
$totalRows_create_inv = mysql_num_rows($create_inv);

mysql_select_db($database_connvbsa, $connvbsa);
$query_inv_no = "SELECT MAX(inv_id) FROM inv_to";
$inv_no = mysql_query($query_inv_no, $connvbsa) or die(mysql_error());
$row_inv_no = mysql_fetch_assoc($inv_no);
$totalRows_inv_no = mysql_num_rows($inv_no);

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


<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>

<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>

<table width="1000" border="0" align="center">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>

<form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2" >
  <table width="900" align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td>&nbsp;<?php echo $_SESSION['inv_page']; ?></td>
      <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
    <tr>
      <td colspan="2" align="center" class="red_bold">You are about to create an invoice to the following:</td>
    </tr>
    <tr>
      <td colspan="2" align="center">Invoice type: <?php echo $inv_type; ?></td>
    </tr>
    <tr>
      <td colspan="2" align="center" class="red_bold">All details from the previous invoice</td>
    </tr>
    <tr>
      <td colspan="2" align="center">Invoice to (Business Name): <?php echo $row_create_inv['inv_busname']; ?></td>
    </tr>
    <tr>
      <td colspan="2" align="center">Contact Name: 
			<?php if (isset($row_create_inv['inv_to'])) {
                echo $row_create_inv['inv_to'];
            } else {
                echo "<span class=red_text>No contact Name</span>";
            } ?>
	  </td>
    </tr>
    <tr>
      <td colspan="2" align="center">Email address: 
	  		<?php if (isset($row_create_inv['Email'])) {
                echo $row_create_inv['Email'];
            } else {
                //echo "No Email address available";
				 echo "<span class=red_text>No email</span>";
            } ?>
       </td>
    </tr>
    <tr>
      <td colspan="2" align="center">Phone Number: 
	  		<?php if (isset($row_create_inv['MobilePhone'])) {
                echo $row_create_inv['MobilePhone'];
            } else {
                //echo "No Email address available";
				 echo "<span class=red_text>No mobile number</span>";
            } ?>
	  </td>
    </tr>
    <tr>
      <td colspan="2" align="center" class="red_txt">Status will be set as &quot;Not Sent&quot;, when you print the invoice it will be updated to &quot;Sent&quot;</td>
    </tr>
    <tr>
      <td colspan="2" align="center">All the above details will be copied into your new invoice, you may edit after inserting</td>
    </tr>
    <tr>
      <td colspan="2" align="center" nowrap="nowrap" class="red_text">If any of these details are incorrect please edit the invoice</td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="submit" value="Create New Invoice" /></td>
    </tr>
  </table>
  <input type="hidden" name="inv_id" value="<?php echo $row_inv_no['MAX(inv_id)']+1; ?>" />
  <input type="hidden" name="inv_busname" value="<?php echo $row_create_inv['inv_busname']; ?>" />
  <input type="hidden" name="inv_to" value="<?php echo $row_create_inv['inv_to']; ?>" />
  <input type="hidden" name="inv_email" value="<?php echo $row_create_inv['Email']; ?>" />
  <input type="hidden" name="inv_phone" value="<?php echo $row_create_inv['MobilePhone']; ?>" />
  <input type="hidden" name="inv_status" value="Not Sent" />
  <input type="hidden" name="inv_type" value="<?php echo $inv_type; ?>"/>
  <input type="hidden" name="inv_random" value="<?php echo(rand(100000000000,9999999999999)); ?> " />
  <input type="hidden" name="inv_cal_year" value="<?php echo date("Y"); ?> " />
  <input type="hidden" name="inv_fin_year" value="<?php if(date('m') >"06") { echo date("Y"); } elseif(date('m') <"07") { echo date("Y")-1; } ?> " />      
  <input type="hidden" name="MM_insert" value="form2" />
</form>

<p>&nbsp;</p>
<div id="inv_wrapper">
  
</div>
</body>
</html>
<?php
mysql_free_result($create_inv);

mysql_free_result($inv_no);
?>