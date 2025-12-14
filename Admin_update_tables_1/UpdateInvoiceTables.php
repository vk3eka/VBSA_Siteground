<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
  
  <table width="800" border="1" align="center">
    <tr>
      <td align="center" class="header_red">When the &quot;Submit Query&quot; Button is clicked, the &quot;Invoice&quot; tables will be recalculated.</td>
    </tr>
    <tr>
      <td align="center">Please use this page every time you create or modify an invoice</td>
    </tr>
    <tr>
      <td align="center">If any calculation shows "Error - Table was not updated" please contact the webmaster</td>
    </tr>
    <tr>
      <td align="center" class="greenbg"><a href="../Admin_Treasurer_Invoicing/AA_inv_index.php">Return to Main Menu</a></td>
    </tr>
  </table>
  <center>
  <?php require_once('../Connections/connvbsa.php'); ?>
  
  <?php

if(isset($_POST["submit"]))

{

mysql_select_db($database_connvbsa, $connvbsa);


echo "<br><br><font face='arial' color='red'>Step 1 - Calculate Invoice Items (inv_items table)</font>";


//tested and ok calculates discount total - inv_items table

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`inv_items` SET `discount_total`
=(SELECT SUM(IFNULL(item_amount * item_discount /100,0)))";

$result=mysql_query($querytoexecute, $connvbsa) or die("Error - Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>inv_items Table was successfully updated - calculated discount total. SELECT SUM(IFNULL(item_amount * item_discount /100,0))</font>";



//tested and ok calculates item total - inv_items table

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`inv_items` SET `item_total`
=(SELECT SUM(IFNULL(item_amount - discount_total,0)))";

$result=mysql_query($querytoexecute, $connvbsa) or die("Error - Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>inv_items Table was successfully updated - calculated item total. SELECT SUM(IFNULL(item_amount - discount_total,0))</font>";


//tested and ok calculates GST - inv_items table

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`inv_items` SET `GST`
=(SELECT SUM(IFNULL(item_total*.1,0)))
WHERE apply_GST='Yes'";

$result=mysql_query($querytoexecute, $connvbsa) or die("Error - Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>inv_items Table was successfully updated - calculated GST. SELECT SUM(IFNULL(item_total*.1,0))</font>";


//tested and ok recalculates GST if set to "No" - inv_items table

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`inv_items` SET `GST`
=(SELECT SUM(IFNULL(item_total*0,0)))
WHERE apply_GST='No'";

$result=mysql_query($querytoexecute, $connvbsa) or die("Error - Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>inv_items Table was successfully updated - recalculates GST if set to No. SELECT SUM(IFNULL(item_total*1,0))</font>";


//tested and ok calculates total inc GST - inv_items table

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`inv_items` SET `item_total_all`
=(SELECT SUM(IFNULL(item_total+GST,0)))";

$result=mysql_query($querytoexecute, $connvbsa) or die("Error - Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>inv_items Table was successfully updated - calculated item inc GST. SELECT SUM(IFNULL(item_total+item_total_GST,0))</font>";

//end inv_item calculate

echo "<br><br><font face='arial' color='red'>Step 2 - Calculate Invoice To totals (inv_to table)</font>";

//start inv_to calculate

//tested and ok calculates SET inv_discount_total OK - inv_items table

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`inv_to` 	
SET `inv_discount_total` = (SELECT SUM(IFNULL(inv_items.discount_total,0))
FROM inv_items
WHERE inv_to.inv_id = inv_items.inv_no)";

$result=mysql_query($querytoexecute, $connvbsa) or die("Error - Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>inv_items Table was successfully updated - SET `inv_discount_total` = (SELECT SUM(IFNULL(inv_items.discount_total,0)) FROM inv_items WHERE inv_to.inv_id = inv_items.inv_no)</font>";


//tested and ok calculates SET inv_amount_total OK - inv_items table

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`inv_to` 	
SET `inv_amount_total` = (SELECT SUM(IFNULL(inv_items.item_amount,0))
FROM inv_items
WHERE inv_to.inv_id = inv_items.inv_no)";

$result=mysql_query($querytoexecute, $connvbsa) or die("Error - Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>inv_items Table was successfully updated - SET `inv_amount_total` = (SELECT SUM(IFNULL(inv_items.item_amount,0)) FROM inv_items WHERE inv_to.inv_id = inv_items.inv_no)</font>";


//tested and ok calculates SET total_less_disc OK - inv_items table

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`inv_to` 	
SET `total_less_disc` = (SELECT SUM(IFNULL(inv_items.item_total,0))
FROM inv_items
WHERE inv_to.inv_id = inv_items.inv_no)";

$result=mysql_query($querytoexecute, $connvbsa) or die("Error - Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>inv_items Table was successfully updated - SET `total_less_disc` = (SELECT SUM(IFNULL(inv_items.item_total,0)) FROM inv_items WHERE inv_to.inv_id = inv_items.inv_no)</font>";



//tested and ok calculates SET inv_GST_total OK - inv_items table

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`inv_to` 	
SET `inv_GST_total` = (SELECT SUM(IFNULL(inv_items.GST,0))
FROM inv_items
WHERE inv_to.inv_id = inv_items.inv_no)";

$result=mysql_query($querytoexecute, $connvbsa) or die("Error - Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>inv_items Table was successfully updated - SET `inv_GST_total` = (SELECT SUM(IFNULL(inv_items.GST,0)) FROM inv_items WHERE inv_to.inv_id = inv_items.inv_no)</font>";



//tested and ok calculates SET inv_total_all OK - inv_items table

$querytoexecute = "UPDATE `vbsa3364_vbsa2`.`inv_to` 	
SET `inv_total_all` = (SELECT SUM(IFNULL(inv_items.item_total_all,0))
FROM inv_items
WHERE inv_to.inv_id = inv_items.inv_no)";

$result=mysql_query($querytoexecute, $connvbsa) or die("Error - Table was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>inv_items Table was successfully updated - SET `inv_total_all` = (SELECT SUM(IFNULL(inv_items.item_total_all,0)) FROM inv_items WHERE inv_to.inv_id = inv_items.inv_no)</font>";


//tested and sets invoice status

$querytoexecute = "UPDATE vbsa3364_vbsa2.inv_to	
SET inv_status = 	
(SELECT CASE
WHEN inv_date is null THEN 'Not Sent'
WHEN inv_paid_amount != inv_total_all AND inv_date is not null AND inv_bad_debt = 0 THEN 'Sent'
WHEN inv_paid_amount = inv_total_all  AND inv_bad_debt = 0 THEN 'Paid'
WHEN inv_bad_debt = 1 THEN 'Bad Debt'
ELSE 'Error'
END as inv_status)";

$result=mysql_query($querytoexecute, $connvbsa) or die("Error - Invoice Status was not updated");

if (isset($result)) echo "<br><br><font face='arial' color='green'>inv_to table was successfully updated - SET `inv_status` to Paid, Sent, Not Sent or Bad Debt</font>";



mysql_close ($connvbsa);

}

else

{

echo "<form id='form1' name='form1' method='post' action='../Admin_update_tables/UpdateInvoiceTables.php?submit=1'>";

echo "<input type='submit' id='submit' name='submit' value='Recalculate the Invoice tables'>";

echo "</form>";

}
?>
  
</center>
</body>
</html>
<?php
?>