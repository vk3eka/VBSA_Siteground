<?php
error_reporting(E_ALL);
ob_start();
require_once('../../Connections/connvbsa.php');

function createSummary($row_inv_pay){	

	$ret="";

	if($row_inv_pay['inv_paid_amount']==0)
	{
		$ret.="Please Pay $";
		$ret.=$row_inv_pay['inv_total_all'];
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
/**/	$instruction="Payment Details- By Cheque or Money order, please make payable to VBSA. Post to: George Hoy, 31 Speakmen St, Kensington, 3031. To pay moneys directly to the VBSA by bank transfer - Our Account is with the ANZ bank - our BSB number is 013236 - Our Account Number is 297730994 Account Name VBSA When you transfer money to our account we need to know who has sent it and what it is for so please send an email to treasurer@vbsa.org.au with the details of the transfer.";

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
$html = "";
$html .= "<div style='font-size:12px'><img src='images/header.png' height='100px' width='850'>";
$html .= "<div style='width:150px; height:60px; border:1px solid #dadada; margin:10px; float:left;padding:15px'>
			<span style='font-size:12px;font-weight:bold'>INVOICE   <b>".$invoice_id."<b/></span>
			<br>Issue Date:<b/>
			<br>".$issue_date."<b/>
			</div>";
			
$html .= "

		  <div style='width:450px; height:60px; border:1px solid #dadada; float:right;padding:10px'>
		  
		<table style='font-size:10px;width:440px;margin-left:10px;cellspacing=5px;'>
		<tr>
			<td style='text-align:right;font-weight:bold;'>Invoice to:</td>
			<td style='text-align:left;font-weight:bold;'>".$invoice_to."</td>
		</tr>
		
		<tr>
			<td style='text-align:right;font-weight:bold;'>Attention:</td>
			<td style='text-align:left;font-weight:bold;'>".$attention."</td>
		</tr>
		
		<tr>
			<td style='text-align:right;font-weight:bold;'>Email:</td>
			<td style='text-align:left;font-weight:bold;'>".$email."</td>
		</tr>
		
		<tr>
			<td style='text-align:right;font-weight:bold;'>Address:</td>
			<td style='text-align:left;font-weight:bold;'>".$address.", ".$suburb.", ".$city.", ".$postcode."</td>
		</tr>
		
		</table>
		
		</div>
		  
		  
		  ";
$html .= "
<div style='width:850px; margin:10px; float:left;padding:15px'>

<table style='font-size:12px;text-align:left;width:850px;margin-left:10px;'>
			<tr>
				<th style='width:280px;text-align:left'>Item description</th>
				<th style='width:80px;text-align:left'>Cost</th>
				<th style='text-align:center: nowrap:nowrap'>Disc%</th>
				<th style='width:80px;text-align:left'>Discount</th>
				<th style='width:80px;text-align:left'>Total</th>
				<th style='width:80px;text-align:left'>GST ?</th>
				<th style='width:80px;text-align:left'>GST</th>
				<th style='width:80px;text-align:left'>Inc GST</th>
			</tr>";
			
do{
	$html.="<tr>
				<td style='text-align:left'>".$row_Item['item_name']."</td>
				<td style='text-align:center'>".$row_Item['item_amount']."</td>
				<td style='text-align:center'>".$row_Item['item_discount']."</td>
				<td style='text-align:center'>".$row_Item['discount_total']."</td>
				<td style='text-align:center'>".$row_Item['item_total']."</td>
				<td style='text-align:center'>".$row_Item['apply_GST']."</td>
				<td style='text-align:center'>".$row_Item['GST']."</td>
				<td style='text-align:center'>".$row_Item['item_total_all']."</td>
			</tr>";
}while ($row_Item = mysql_fetch_assoc($Item)); 

$html .="<tr>
				<th style='text-align:left'>Totals</th>
				<th style='text-align:center'>$total_amount</th>
				<th style='text-align:center'></th>
				<th style='text-align:center'>$inv_discount_total</th>
				<th style='text-align:center'>$total_discount</th>
				<th style='text-align:center'></th>
				<th style='text-align:center'>$total_gst</th>
				<th style='text-align:center'>$total_amount_with_gst</th>
			</tr>
		</table>
		
		</div>";
$html .="<div style='width:850px; margin:10px; float:left;padding:15px'>

		<table style='font-size:10px;width:850px;margin-left:10px;cellspacing=5px;'>
  		<tr>
    		<td>Due Date: <strong>".$due_date."</strong></td>
    		<td  style='text-align:right'><strong>".$summary."</strong></td>
  		</tr>
		</table>
		</div>";
		
$html .="<div style='width:850px; margin:10px; float:left;padding:15px'>

		<table style='font-size:10px;width:850px;margin-left:10px;cellspacing=5px;'>
  		<tr>
    		<td>".$comment."</td>
  		</tr>
		</table>
		</div>";
		
		
$html .="<div style='border:1px solid #dadada; height:68px; width:850px;margin-top:20px;font-size:10px;padding:10px;'>
			".$instruction." 
		</div>";

include("mpdf/mpdf.php");
$mpdf=new mPDF();
echo $html;
exit;
$mpdf->WriteHTML($html);
$mpdf->Output("Invoice.pdf","D");
exit;
?>