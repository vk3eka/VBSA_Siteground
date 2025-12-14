<?php
error_reporting(E_ALL);
ob_start();
require_once('../../Connections/connvbsa.php');

function createSummary($row_inv_pay){	

	$ret="";

	if($row_inv_pay['inv_paid_amount']==0)
	{
		$ret.=" Total Includes $";
		$ret.=$row_inv_pay['inv_GST_total'];
		$ret.=" In GST ";
	}
	elseif($row_inv_pay['inv_paid_amount']<>$row_inv_pay['inv_total_all'])
	{
			$ret = "This invoice is part paid: Received $";
			$ret.= $row_inv_pay['inv_paid_amount'];
			$ret.="&nbsp &nbsp &nbsp";
			$ret.=" Outstanding $";
			$ret.=$row_inv_pay['outstanding'];
	}
	elseif($row_inv_pay['inv_paid_amount']==$row_inv_pay['inv_total_all'])
	{
			$ret ="Thankyou, Invoice paid in full";
	}
	return $ret;
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

$colname_Inv = "-1";
if (isset($_GET['inv_id'])) {
  $colname_Inv = $_GET['inv_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Inv = sprintf("SELECT * FROM inv_to WHERE inv_id = %s", GetSQLValueString($colname_Inv, "int"));
$Inv = mysql_query($query_Inv) or die(mysql_error());
$row_Inv = mysql_fetch_assoc($Inv);
$totalRows_Inv = mysql_num_rows($Inv);

$colname_Item = "-1";
if (isset($_GET['inv_id'])) {
  $colname_Item = $_GET['inv_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Item = sprintf("SELECT inv_item_id, inv_no, item_name, item_discount, item_amount, inv_to.inv_id, inv_items.discount_total, inv_items.apply_GST, inv_items.item_total, inv_items.GST, inv_items.item_total_all FROM inv_items, inv_to WHERE inv_no=inv_to.inv_id AND inv_no = %s ORDER BY inv_item_id", GetSQLValueString($colname_Item, "int"));
$Item = mysql_query($query_Item, $connvbsa) or die(mysql_error());
$row_Item = mysql_fetch_assoc($Item);
$totalRows_Item = mysql_num_rows($Item);

$colname_Inv_det = "-1";
if (isset($_GET['inv_id'])) {
  $colname_Inv_det = $_GET['inv_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Inv_det = sprintf("SELECT * FROM inv_to WHERE inv_id = %s", GetSQLValueString($colname_Inv_det, "int"));
$Inv_det = mysql_query($query_Inv_det, $connvbsa) or die(mysql_error());
$row_Inv_det = mysql_fetch_assoc($Inv_det);
$totalRows_Inv_det = mysql_num_rows($Inv_det);

$colname_Inv_no = "-1";
if (isset($_GET['inv_id'])) {
  $colname_Inv_no = $_GET['inv_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Inv_no = sprintf("SELECT date_format( inv_date, '%%b %%e, %%Y' ) AS date, DATE_ADD( inv_date, INTERVAL 14 DAY ) AS duedate, inv_id, inv_date FROM inv_to WHERE inv_id = %s", GetSQLValueString($colname_Inv_no, "int"));
$Inv_no = mysql_query($query_Inv_no, $connvbsa) or die(mysql_error());
$row_Inv_no = mysql_fetch_assoc($Inv_no);
$totalRows_Inv_no = mysql_num_rows($Inv_no);

$colname_invtot = "-1";
if (isset($_GET['inv_id'])) {
  $colname_invtot = $_GET['inv_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_invtot = sprintf("SELECT * FROM inv_to WHERE inv_id = %s", GetSQLValueString($colname_invtot, "int"));
$invtot = mysql_query($query_invtot, $connvbsa) or die(mysql_error());
$row_invtot = mysql_fetch_assoc($invtot);
$totalRows_invtot = mysql_num_rows($invtot);

$colname_Inv_comment = "-1";
if (isset($_GET['inv_id'])) {
  $colname_Inv_comment = $_GET['inv_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Inv_comment = sprintf("SELECT inv_id, inv_comment FROM inv_to WHERE inv_id = %s", GetSQLValueString($colname_Inv_comment, "int"));
$Inv_comment = mysql_query($query_Inv_comment, $connvbsa) or die(mysql_error());
$row_Inv_comment = mysql_fetch_assoc($Inv_comment);
$totalRows_Inv_comment = mysql_num_rows($Inv_comment);

$colname_inv_pay = "-1";
if (isset($_GET['inv_id'])) {
  $colname_inv_pay = $_GET['inv_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_inv_pay = sprintf("SELECT DATE_ADD( inv_date, INTERVAL 14 DAY ) AS duedate, inv_id, inv_date, inv_GST_total, inv_total_all, inv_paid_amount, IFNULL( SUM( inv_total_all - inv_paid_amount ) , 0 ) AS outstanding FROM inv_to WHERE inv_id = %s", GetSQLValueString($colname_inv_pay, "int"));
$inv_pay = mysql_query($query_inv_pay, $connvbsa) or die(mysql_error());
$row_inv_pay = mysql_fetch_assoc($inv_pay);
$totalRows_inv_pay = mysql_num_rows($inv_pay);


/************************************** Make Change ******************************/

/**/	$invoice_id = $row_Inv_no['inv_id'];
/**/	$issue_date = $row_Inv_no['date'];
/**/	$due_date   = date("M d, Y", strtotime($row_inv_pay['duedate']));
/**/	$invoice_to = $row_Inv_det['inv_busname'];
/**/	$attention  = $row_Inv_det['inv_to'];
/**/	$email  = $row_Inv_det['inv_email'];
/**/	$address    = $row_Inv_det['inv_street'];
/**/	$suburb     = $row_Inv_det['inv_suburb'];
/**/	$city       = $row_Inv_det['inv_city'];
/**/	$postcode   = $row_Inv_det['inv_postcode'];
/**/	$comment   = $row_Inv_det['inv_comment'];
/**/	$total_amount          = $row_invtot['inv_amount_total'];
/**/    $inv_discount_total    = $row_invtot['inv_discount_total'];
/**/    $total_discount        = $row_invtot['total_less_disc'];
/**/	$total_gst             = $row_invtot['inv_GST_total'];
/**/	$total_amount_with_gst = $row_invtot['inv_total_all'];
/**/	$summary=createSummary($row_inv_pay);
/**/	$instruction="<strong>Payment Details</strong> - By Cheque or Money order, please make payable to VBSA. Post to: VBSA Treasurer, Reventon Snooker Academy, 175D Stephen Street, Yarraville, 3013.";
/**/	$instruction2="<strong>To pay moneys directly to the VBSA by EFT</strong> - Our Account is with the ANZ bank - our BSB number is 013236 - Our Account Number is 297730994 Account Name VBSA. Please state the Invoice Number";

/**/	
/**/	$data=array(
/**/		"description"=>"item description",
/**/		"cost"=>"$300.00",
/**/		"rate"=>"$50.00",
/**/		"discount"=>"$00.00",
/**/		"total"=>"$300.00",
/**/		"is_gst"=>"No",
/**/		"gst_amount"=>"$00.00",
/**/		"including_gst"=>"$300.00",
/**/	);
/**/
/**************************************		 Ends 	******************************/
$html .= "
		<div style='width:850px; margin:10px; float:left;padding:15px'>

		<table style='font-size:14px;width:820px;margin-left:10px;cellspacing=5px;'>
			<tr>
				<td><img src='../../Admin_Images/Inv_Header.jpg' height='145px' width='850'></td>
			</tr>
			<tr>
    			<td>&nbsp;</td>
  			</tr>
			<tr>
    			<td>&nbsp;</td>
  			</tr>
			<tr>
    			<td style='font-size:14px; text-align:left; font-weight:bold'> INVOICE   <b>".$invoice_id."<b/> </td>
  			</tr>
			<tr>
    			<td style='font-size:14px; text-align:left; font-weight:bold'> Issue Date   <b>".$issue_date."<b/> </td>
  			</tr>
			<tr>
    			<td>&nbsp;</td>
  			</tr>
			<tr>
    			<td style='font-size:14px; text-align:left'> Invoice To:   <b>".$invoice_to."<b/> </td>
  			</tr>
			<tr>
    			<td style='font-size:14px; text-align:left'> Attention:   <b>".$attention."<b/> </td>
  			</tr>
		</table>
		
		</div>";
			
// Invoice Items
		  		  
$html .= "

<div style='width:850px; margin:10px; float:left;padding:15px'>

		<table style='font-size:12px; width:820px; cellspacing=5px;'>
			<tr>
				<th style='width:350px;text-align:left'>Description</th>
				<th style='text-align:center'>Cost</th>
				<th style='text-align:center'>Discount %</th>
				<th style='text-align:center'>Inc GST</th>
			</tr>";
			
do{
	$html.="<tr>
				<td style='text-align:left'>".$row_Item['item_name']."</td>
				<td style='text-align:center'>$".$row_Item['item_amount']."</td>
				<td style='text-align:center'>".$row_Item['item_discount']."</td>
				<td style='text-align:center'>$".$row_Item['item_total_all']."</td>
			</tr>";
}while ($row_Item = mysql_fetch_assoc($Item)); 

$html .="<tr>
				<th style='text-align:left></th>
				<th style='text-align:center'></th>
				<th style='text-align:center'></th>
				<th style='text-align:center></th>
			</tr>
			
			<tr>
				<th style='text-align:left; font-weight:bold'>Please Pay</th>
				<th style='text-align:center'></th>
				<th style='text-align:center'></th>
				<th style='text-align:center; font-weight:bold'>$".$total_amount_with_gst."</th>
			</tr>
		</table>
		</div>";
		
//Summary - Due Date, invoice includes GST amount

$html .="<div style='width:850px; margin:10px; float:left;padding:15px'>

		<table style='font-size:14px; width:820px; cellspacing=5px;'>
  		<tr>
    		<td><strong>Due Date: ".$due_date."</strong></td>
    		<td  style='text-align:right'><strong>".$summary."</strong></td>
  		</tr>
		</table>
		</div>";
		
// Comment
		
$html .="<div style='width:800px; margin-left:40px; margin-bottom:20px; float:left'>

		<table style='font-size:12px; width:600px; cellspacing=5px;'>
  		<tr>
    		<td style=text-justify>".$comment."</td>
  		</tr>
		</table>
		</div>";
		
// Instruction 1
		
$html .="<div style='width:850px; margin-left:25px; margin-right:25px; float:left'>

		<table style='font-size:12px; width:820px; cellspacing=5px;'>
  		<tr>
    		<td>".$instruction."</td>
  		</tr>
		</table>
		</div>";

// Instruction 2
		
$html .="<div style='width:850px; margin-left:25px; margin-right:25px; float:left'>

		<table style='font-size:12px; width:820px; cellspacing=5px;'>
  		<tr>
    		<td>".$instruction2."</td>
  		</tr>
		</table>
		</div>";
		
// Cut here image
		
$html .= "
		<div style='width:850px; margin:10px; float:left;padding:15px'>

		<img src='images/cut_here.JPG' width='850'></td>
		
		</div>";
		
// Footer
		
$html .= "

<div style='width:850px; margin-left:25px; margin-right:25px; float:left'>

		<table style='font-size:12px; width:820px; cellspacing=5px;'>
			<tr>
				<td style='text-align:left; width:350px'><strong>Payment Advice</strong></td>
				<td style='text-align:left'></td>
				<td style='text-align:left'>Customer:</td>
				<td style='text-align:left'>".$invoice_to."</td>
			</tr>
			
			<tr>
				<td style='text-align:left'></td>
				<td style='text-align:left'></td>
				<td style='text-align:left'>Invoice Number:</td>
				<td style='text-align:left'>".$invoice_id."</td>
			</tr>
			
			<tr>
				<td style='text-align:left'></td>
				<td style='text-align:left'></td>
				<td style='text-align:left'>Amount Due:</td>
				<td style='text-align:left'>$".$total_amount_with_gst."</td>
			</tr>
			
			<tr>
				<td style='text-align:left'></td>
				<td style='text-align:left'></td>
				<td style='text-align:left'>Due Date:</td>
				<td style='text-align:left'>".$due_date."</td>
			</tr>
			
			<tr>
				<td style='text-align:left'></td>
				<td style='text-align:left'></td>
				<td style='text-align:left'>Enclosed:</td>
				<td style='text-align:left'>$</td>
			</tr>		
			
			<tr>
				<td style='text-align:left'></td>
				<td style='text-align:left'></td>
				<td style='text-align:left'></td>
				<td style='text-align:left'></td>
			</tr>
			
			<tr>
				<td colspan=4 style='text-align:left'>To: VBSA Treasurer, Reventon Snooker Academy, 175D Stephen Street, Yarraville, 3013</td>
			</tr>		
					
			</table>
		</div>";

include("mpdf/mpdf.php");
$mpdf=new mPDF();
echo $html;
exit;
$mpdf->WriteHTML($html);
$mpdf->Output("Invoice.pdf","D");
exit;
?>