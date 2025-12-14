<?php require_once('../Connections/connvbsa.php'); ?>
<?php
error_reporting(0);

if (!isset($_SESSION)) {
  session_start();
}

$page = "../Admin_Tournaments/tournament_detail.php?searchthis=$searchthis"  ;
$_SESSION['page'] = $page; 

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

if (isset($_GET['tourn_type'])) {
  $tourn_type = $_GET['tourn_type'];
}

if (isset($_GET['tourn_year'])) {
  $tourn_year = $_GET['tourn_year'];
}
//echo("Here " . $tourn_type . "<br>");
mysql_select_db($database_connvbsa, $connvbsa);

$sql_get_type = 'Select tourn_type from tournaments where tourn_id = ' . $tourn_id;
//echo($sql_get_type . "<br>");
$get_type = mysql_query($sql_get_type, $connvbsa) or die(mysql_error());
$row_get_type = mysql_fetch_assoc($get_type);
$tourn_type = $row_get_type['tourn_type'];
//echo("Here " . $tourn_type . "<br>");
//$totalRows_get_type = mysql_num_rows($get_type);
//echo("Tourn Type " . $tourn_id . "<br>");

if($tourn_type == 'Snooker')
{
  // Snooker Tournaments
  $query_players_confirmed = "Select ID, tournament_number AS TournID, MemberID, CONCAT(FirstName, ' ', LastName) AS FullName, Email, MobilePhone, Gender, amount_entry, how_paid, entry_confirmed, HomeState, (CASE WHEN memb_by IS NOT NULL then 'Yes' WHEN memb_by IS NULL then 'No' end) AS memb, seed, tourn_date_ent, entered_by, ranknum, wcard, Junior, rank_S_open_tourn.total_tourn_rp as rank_pts FROM tourn_entry, members  LEFT JOIN rank_S_open_tourn ON memb_id=MemberID WHERE tournament_number = '$tourn_id' AND MemberID=tourn_memb_id AND entry_confirmed=1 ORDER BY FirstName, Junior";

  //$query_players_confirmed = "Select ID, tournament_number AS TournID, MemberID, CONCAT(FirstName, ' ', LastName) AS FullName, Email, MobilePhone, amount_entry, how_paid, entry_confirmed, (CASE WHEN memb_by IS NOT NULL then 'Yes' WHEN memb_by IS NULL then 'No' end) AS memb, seed, tourn_date_ent, entered_by, ranknum, wcard, rank_pts, Junior FROM tourn_entry, members  LEFT JOIN rank_S_open_tourn ON memb_id=MemberID WHERE tournament_number = '$tourn_id' AND MemberID=tourn_memb_id AND entry_confirmed=1 ORDER BY FirstName, Junior";

}
elseif($tourn_type == 'Billiards')
{
  //echo("Here<br>");
  // Billiard Tournaments
  $query_players_confirmed = "Select ID, tournament_number AS TournID, MemberID, CONCAT(FirstName, ' ', LastName) AS FullName, Email, MobilePhone, Gender, amount_entry, how_paid, entry_confirmed, HomeState, (CASE WHEN memb_by IS NOT NULL then 'Yes' WHEN memb_by IS NULL then 'No' end) AS memb, seed, tourn_date_ent, entered_by, ranknum, wcard, Junior, rank_Billiards.total_rp as rank_pts  FROM tourn_entry, members  LEFT JOIN rank_Billiards ON memb_id=MemberID WHERE tournament_number = '$tourn_id' AND MemberID=tourn_memb_id AND entry_confirmed=1 ORDER BY FirstName, Junior";

  //$query_players_confirmed = "Select ID, tournament_number AS TournID, MemberID, CONCAT(FirstName, ' ', LastName) AS FullName, Email, MobilePhone, amount_entry, how_paid, entry_confirmed, (CASE WHEN memb_by IS NOT NULL then 'Yes' WHEN memb_by IS NULL then 'No' end) AS memb, seed, tourn_date_ent, entered_by, ranknum, wcard, rank_pts, junior_cat FROM tourn_entry, members  LEFT JOIN rank_Billiards ON memb_id=MemberID WHERE tournament_number = '$tourn_id' AND MemberID=tourn_memb_id AND entry_confirmed=1 ORDER BY FirstName, junior_cat";
}

//echo($query_players_confirmed . "<br>");
$players_confirmed = mysql_query($query_players_confirmed, $connvbsa) or die(mysql_error());
$row_players_confirmed = mysql_fetch_assoc($players_confirmed);
$totalRows_players_confirmed = mysql_num_rows($players_confirmed);

$memb = mysql_query($query_players_confirmed, $connvbsa) or die(mysql_error());
$row_memb = mysql_fetch_assoc($memb);
$totalRows_memb = mysql_num_rows($memb);


mysql_select_db($database_connvbsa, $connvbsa);
//$query_players_unconfirmed = "SELECT ID, tournament_number AS TournID, MemberID, CONCAT(FirstName, ' ', LastName) AS FullName, Email, MobilePhone, amount_entry, how_paid, entry_confirmed, (CASE WHEN memb_by IS NOT NULL then 'Yes' WHEN memb_by IS NULL then 'No' end) AS memb, seed, tourn_date_ent, entered_by, wcard, rank_pts, ranknum, Junior FROM tourn_entry, members  LEFT JOIN rank_S_open_tourn ON memb_id=MemberID WHERE tournament_number = '$tourn_id' AND MemberID=tourn_memb_id AND entry_confirmed=0 ORDER BY FirstName, Junior";

$query_players_unconfirmed = "Select ID, tournament_number AS TournID, MemberID, CONCAT(FirstName, ' ', LastName) AS FullName, Email, MobilePhone, Gender, amount_entry, how_paid, entry_confirmed, (CASE WHEN memb_by IS NOT NULL then 'Yes' WHEN memb_by IS NULL then 'No' end) AS memb, seed, tourn_date_ent, entered_by, wcard, rank_pts, ranknum, junior_cat FROM tourn_entry, members  LEFT JOIN rank_S_open_tourn ON memb_id=MemberID WHERE tournament_number = '$tourn_id' AND MemberID=tourn_memb_id AND entry_confirmed=0 ORDER BY FirstName, junior_cat";


$players_unconfirmed = mysql_query($query_players_unconfirmed, $connvbsa) or die(mysql_error());
$row_players_unconfirmed = mysql_fetch_assoc($players_unconfirmed);
$totalRows_players_unconfirmed = mysql_num_rows($players_unconfirmed);

mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn1 = "SELECT * FROM tournaments WHERE tourn_id =  '$tourn_id'";
$tourn1 = mysql_query($query_tourn1, $connvbsa) or die(mysql_error());
$row_tourn1 = mysql_fetch_assoc($tourn1);
$totalRows_tourn1 = mysql_num_rows($tourn1);

mysql_select_db($database_connvbsa, $connvbsa);
$query_tot_entry_income = "SELECT tournament_number, SUM(amount_entry) FROM tourn_entry WHERE tournament_number = '$tourn_id'";
$tot_entry_income = mysql_query($query_tot_entry_income, $connvbsa) or die(mysql_error());
$row_tot_entry_income = mysql_fetch_assoc($tot_entry_income);
$totalRows_tot_entry_income = mysql_num_rows($tot_entry_income);

mysql_select_db($database_connvbsa, $connvbsa);
$query_tot_0 = "SELECT tournament_number, COUNT(amount_entry) FROM tourn_entry WHERE tournament_number =  '$tourn_id' AND amount_entry=0";
$tot_0 = mysql_query($query_tot_0, $connvbsa) or die(mysql_error());
$row_tot_0 = mysql_fetch_assoc($tot_0);
$totalRows_tot_0 = mysql_num_rows($tot_0);

mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn_status = "SELECT status FROM tournaments WHERE tourn_id =  '$tourn_id'";
$tourn_status = mysql_query($query_tourn_status, $connvbsa) or die(mysql_error());
$row_tourn_status = mysql_fetch_assoc($tourn_status);
$totalRows_tourn_status = mysql_num_rows($tourn_status);

mysql_select_db($database_connvbsa, $connvbsa);
$query_pp_tot = "SELECT tournament_number, SUM(amount_entry) FROM tourn_entry WHERE tournament_number =  '$tourn_id' AND how_paid='PP'";
$pp_tot = mysql_query($query_pp_tot, $connvbsa) or die(mysql_error());
$row_pp_tot = mysql_fetch_assoc($pp_tot);
$totalRows_pp_tot = mysql_num_rows($pp_tot);

mysql_select_db($database_connvbsa, $connvbsa);
$query_BT_tot = "SELECT tournament_number, SUM(amount_entry) FROM tourn_entry WHERE tournament_number =  '$tourn_id' AND how_paid='BT'";
$BT_tot = mysql_query($query_BT_tot, $connvbsa) or die(mysql_error());
$row_BT_tot = mysql_fetch_assoc($BT_tot);
$totalRows_BT_tot = mysql_num_rows($BT_tot);

mysql_select_db($database_connvbsa, $connvbsa);
$query_cash_tot = "SELECT tournament_number, SUM(amount_entry) FROM tourn_entry WHERE tournament_number =  '$tourn_id' AND how_paid='Cash'";
$cash_tot = mysql_query($query_cash_tot, $connvbsa) or die(mysql_error());
$row_cash_tot = mysql_fetch_assoc($cash_tot);
$totalRows_cash_tot = mysql_num_rows($cash_tot);

mysql_select_db($database_connvbsa, $connvbsa);
$query_other_tot = "SELECT tournament_number, SUM(amount_entry) FROM tourn_entry WHERE tournament_number =  '$tourn_id' AND how_paid='Other'";
$other_tot = mysql_query($query_other_tot, $connvbsa) or die(mysql_error());
$row_other_tot = mysql_fetch_assoc($other_tot);
$totalRows_other_tot = mysql_num_rows($other_tot);

mysql_select_db($database_connvbsa, $connvbsa);
$query_chq_tot = "SELECT tournament_number, SUM(amount_entry) FROM tourn_entry WHERE tournament_number =  '$tourn_id' AND how_paid='Chq'";
$chq_tot = mysql_query($query_chq_tot, $connvbsa) or die(mysql_error());
$row_chq_tot = mysql_fetch_assoc($chq_tot);
$totalRows_chq_tot = mysql_num_rows($chq_tot);

mysql_select_db($database_connvbsa, $connvbsa);
$query_dup_seed = "SELECT tourn_entry.seed, tourn_memb_id, FirstName, LastName FROM tourn_entry LEFT JOIN members ON MemberID = tourn_memb_id  INNER JOIN (SELECT seed FROM tourn_entry WHERE tourn_entry.tournament_number= '$tourn_id'  and seed > 0 GROUP BY tourn_entry.seed HAVING count(seed) > 1) dup ON tourn_entry.seed = dup.seed";
//echo($query_dup_seed . "<br>");
$dup_seed = mysql_query($query_dup_seed, $connvbsa) or die(mysql_error());
$row_dup_seed = mysql_fetch_assoc($dup_seed);
$totalRows_dup_seed = mysql_num_rows($dup_seed);

//Saving file locally?
if(isset($_POST["m_source"]) && $_POST["Do_Send"]=="save"){
  header("Content-Type: application/html");
  header("Content-Disposition:attachment; filename=template.html");
  header("Content-Type: application/force-download");
  header("Cache-Control: post-check=0, pre-check=0", false);
  echo $_POST["m_source"];
  die();
}

//If PEAR is not installed - send using sendmail
@include_once("Mail.php");
@include_once("Mail/mime.php");
if(!class_exists('Mail') || !class_exists('Mail_mime')){
  $pear_enabled=false;
}else{
  $pear_enabled=true;
}

//Mail function triggered?
if(isset($_POST["Do_Send"]) && $_POST["Do_Send"]=="Send"){
  function bind_fields($field,$rs,$row){
    $totalColumns=mysql_num_fields($rs);
    $hatch=$field;
      for ($x=0; $x<$totalColumns; $x++) {
        $fieldName=mysql_field_name($rs, $x);
        $hatch=str_replace("##".$fieldName."##",$row[$fieldName],$hatch);
      }
      return ($hatch);
  }
  function bind_email($field,$row){
    $fieldName=substr($field,2,strlen($field)-4);
    $hatch=$row[$fieldName];
    return ($hatch);
  }
  function win_checkdnsrr($hostName){
  $isvalid=false;
  if(!empty($hostName)) { 
     exec("nslookup ".$hostName, $result); 
     foreach ($result as $line) {
     if(preg_match("/$hostName/i",$line)) {
      $isvalid=true;
     }     
     } 
   }
  return  $isvalid; 
  }
  
  $replyto=$_POST["ReplyTo"];
  $return_path=$_POST["ReturnPath"];
  
  $current_record=0;
  $valid_email=true;
  do {
    $current_record++;
    if($_POST['StartNumber']==$current_record){
      $error="";
      //Send the message
      $to=($_POST["SendMode"]!="Test")?(bind_email($_POST["To"], $row_memb)):$_POST["To"];
      //If email field is empty - error
      if($to=="" || stristr($to,"@")===FALSE){
        $error=$to." - no email\r\n";
        break;
      }
      list($user, $domain) = explode("@", $to);
      //Email is valid by default
      $valid_email=true;
      //Validate if required
      if($_POST["Validate"]=="1"){
        if(function_exists('checkdnsrr')){
          $valid_email=checkdnsrr($domain.".","MX");
        }
        else{
          $valid_email=win_checkdnsrr($domain);
        }
      }
      if (!$valid_email){
        $error=$to."(Non-existent domain) \r\n";
      }else{
        $regex="/##"."[A-Za-z0-9\-\_]+"."##/";
        //Find if there is possible bound field in the subject line
        $dyn_subject=preg_match($regex,$_POST["Subject"]);
        //Find if there is possible bound field in the message box
        $dyn_message=preg_match($regex,$_POST["m_source"]);
        //From fields
        $subject=$_POST["Subject"];
        $message=$_POST["m_source"];

        
        //Bind fields if the subject contains variables
        if($dyn_subject===1){
          $subject=bind_fields($_POST["Subject"],$memb,$row_memb);
        }
        //Bind fields if the message contains variables
        if($dyn_message===1){
          $message=bind_fields($_POST["m_source"],$memb,$row_memb);
        }
        $email=$_POST["From"];
        $realname=$_POST["FromName"]; 
        $from=$realname."<".$email.">";
  
        //If PEAR is not installed - send using sendmail
        if(!$pear_enabled){
          //If it is a plain text
          $html_header="Content-type: text/plain;";
          if($_POST["SendAs"]!="Plain Text"){
            $html_header="Content-type: text/html;";
          }
          $html_header.=" charset=".$_POST["encoding"].";";
          $headers="From:".$from."\r\n";
          $headers.=($replyto!="")?("Reply-To:".$replyto."\r\n"):"";
          $headers.="MIME-Version: 1.0\n";
          $headers.=$html_header;

          if($return_path!=""){
            $mail_sent=mail($to, stripslashes($subject),stripslashes($message), $headers, $return_path);
          }else{
            $mail_sent=mail($to, stripslashes($subject),stripslashes($message), $headers);
          }
          if(!$mail_sent){
            $error=$to."\n";
          }
        }else{
          $host=$_POST["Host"];
          $username=$_POST["UserName"];
          $password=$_POST["Password"];
          if($host!="" && $username!="" && $password!=""){
            $smtp_array = array ('host' => $host,'auth' => true,'username' => $username,'password' => $password);
            if ($return_path!=""){
              $headers['Return-Path']=$return_path;
              $smtp_array = array ('host' => $host,'auth' => true,'username' => $username,'password' => $password,'Return-Path' => $return_path);
            }
            $contructor = Mail::factory('smtp',$smtp_array);
          }else{
            $contructor = ($return_path=="")?(Mail::factory('mail')):(Mail::factory('mail', array('Return-Path' => $return_path)));
          }
          $headers = array ('From' => $from,'To' => $to,'Subject' => $subject);
          if($replyto!=""){$headers['Reply-To']=$replyto;}
          if($return_path==""){$headers['Return-Path']=$return_path;}
          
          $mime = new Mail_mime("\n");
          if(isset($_POST["FileAttachment"]) && $_POST["FileAttachment"]!=""){
            if(file_exists($_POST["FileAttachment"])){
              $mime->addAttachment(file_get_contents($_POST["FileAttachment"]),"application/octet-stream",basename($_POST["FileAttachment"]),0);
            }else{
              $error="Error attaching file (wrong path)\n";
            }
          }
          //Send if no errors
          if($error==""){
            if($_POST["SendAs"]!="Plain Text"){
              $mime->setHTMLBody($message);
              $message = $mime->get(array('html_charset' => $_POST["encoding"]));
            }else{
              $mime->setTxtBody($message);
              $message = $mime->get(array('text_charset' => $_POST["encoding"]));
            }
            $headers = $mime->headers($headers);
            $mail = $contructor->send($to, $headers, $message);
        
            if (PEAR::isError($mail)){
              $error=$error.$to."\n";
            }
          }
        }
      }
      break;
    }
  }while ($row_memb = mysql_fetch_assoc($memb)); 
  //Render Server Response
  header("Content-Type: text/xml");
  echo "<sent to=\"".$to."\" total=\"".$totalRows_memb."\"><errors>".$error."</errors></sent>";
  die();
}

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
  $(function(){
      $('#quick_search').keyup(function(e){
          console.log("Got call");
          var input = $(this).val();
          input = input.trim();
          if(input.length == 0) {
              return;
          }
          $.ajax({
              type: "get",
              url: "ajax/quick_member_search.php",
              data: {last_name: input},
              async: true,
              success: function(data){
                  var member = $.parseJSON(data);
                  $('#qs_result').html('');
                  for(x = 0; x < member.length; x++){
                      $('#qs_result').prepend('<div>' + member[x].MemberID + ' ' + member[x].FirstName + ' ' + member[x].LastName +  '</div>'); //Fills the #auto div with the options
                  }
              }
          })
      })
  });
});
</script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />

</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch_treas.php';?>
<table width="1100" align="center">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="middle"><span class="red_bold">Tournament Detail - lists all players and information </span>for the  <?php echo $row_tourn1['tourn_name']; ?> in <?php $newDate = date("Y", strtotime($row_tourn1['tourn_year'])); echo $newDate; ?></td>
    <td align="center" class="greenbg"><a href="aa_tourn_index.php?tourn_year=<?php echo $tourn_year; ?>">Return to Previous page</a></td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td>Entries Confirmed:<?php echo $totalRows_players_confirmed ?>&nbsp;&nbsp;&nbsp;&nbsp; Unconfirmed:<?php echo $totalRows_players_unconfirmed ?> </td>
    <td colspan="2"><span class="page">Entry status: <?php echo $row_tourn_status['status']; ?></span></td>
  </tr>
</table>
	<table border="1" align="center" cellpadding="5" class="italicise">
	  <tr>
	    <td>Total Income from entries : $<?php echo $row_tot_entry_income['SUM(amount_entry)']; ?></td>
	    <td>Total Entries by paypal : $<?php echo $row_pp_tot['SUM(amount_entry)']; ?></td>
	    <td>Total entries by BT: $<?php echo $row_BT_tot['SUM(amount_entry)']; ?></td>
	    <td>Total Entries cash: $<?php echo $row_cash_tot['SUM(amount_entry)']; ?></td>
	    <td>Total Entries Chq :<?php echo $row_chq_tot['SUM(amount_entry)']; ?></td>
	    <td>Total entries &quot;Other&quot;: $<?php echo $row_other_tot['SUM(amount_entry)']; ?></td>
	    <td>Total Entries =Zero : <?php echo $row_tot_0['COUNT(amount_entry)']; ?></td>
    </tr>
</table>
	<table align="center" cellpadding="10">
	  <tr>
	    <td valign="middle" class="greenbg"><a href="user_files/player_insert.php?tourn_id=<?php echo $row_tourn1['tourn_id']; ?>">Enter single player by Member ID</a></td>
	    <td valign="middle" class="greenbg"><a href="user_files/player_insert_multiple_id.php?tourn_id=<?php echo $row_tourn1['tourn_id']; ?>">Enter multiple players by Member ID</a></td>
	    <td valign="middle" class="greenbg">
	      <?php if($row_tourn1['how_seed']=='Vic Rankings') { ?>
          <a href="../Admin_update_tables/UpdateTournVicRank.php?tournID=<?php echo $tourn_id; ?>">Seed Tournament</a>
          <?php }
		else
		echo "";
        ?>
        </td>
	    <td valign="middle" class="greenbg">&nbsp;</td>
      </tr>
</table>
<?php if(isset($row_dup_seed['seed'])) { // Duplicate seed warning?>
<div class="warning_border">
  <table align="center" cellpadding="5" cellspacing="5">
    <tr>
    <td colspan="3" class="warning">WARNING YOU HAVE A DUPLICATE SEED</td>
    </tr>
    <tr>
      <td colspan="3" class="red_bold">Tournament Draw will not function please correct</td>
    </tr>
    <tr>
      <th align="center">Seed</th>
      <th align="center">Member ID</th>
      <th align="left">Name</th>
    </tr>
      <?php do { ?>
        <tr>
          <td align="center"><?php echo $row_dup_seed['seed']; ?></td>
          <td align="center"><?php echo $row_dup_seed['tourn_memb_id']; ?></td>
          <td align="left"><?php echo $row_dup_seed['FirstName']; ?> <?php echo $row_dup_seed['LastName']; ?></td>
        </tr>
        <?php } while ($row_dup_seed = mysql_fetch_assoc($dup_seed)); ?>
    </table>
    </div>
    <?php } else echo ""; //end duplicate seed warning ?>
    
    
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan="8" align="center" class="red_bold">Confirmed Entries</td>
    <td colspan="10" align="center" class="greenbg">&nbsp;</td>
    <?php if ($totalRows_players_confirmed > 0) { // Show if recordset not empty ?> 
  </tr>
  <tr>
    <td align="center" nowrap="nowrap">Tourn ID</td>
    <td align="center" nowrap="nowrap">Member ID</td>
    <td align="left">Name</td>
    <td align="left">Email</td>
    <td align="left">Phone</td>
    <td align="center">Paid</td>
    <td align="center">How Paid</td>
    <td align="center">Junior?</td>
    <td align="center">Gender</td>
    <td align="center">State</td>
    <td align="center">Member?</td>
    <td align="left" nowrap="nowrap">Entered On</td>
    <td align="left" nowrap="nowrap">Entered By</td>
    <td align="center" nowrap="nowrap">Rank Points</td>
    <td align="center" nowrap="nowrap">Vic Rank</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <?php do { ?>
  <tr class="page">
    <td align="center"><?php echo $row_players_confirmed['TournID']; ?></td>
    <td align="center"><?php echo $row_players_confirmed['MemberID']; ?></td>
    <td align="left"><?php echo $row_players_confirmed['FullName']; ?></td>
    <td align="left"><a href="mailto:<?php echo $row_players_confirmed['Email']; ?>"><?php echo $row_players_confirmed['Email']; ?></a></td>
    <td align="left"><a href="tel:<?php echo $row_players_confirmed['MobilePhone']; ?>"><?php echo $row_players_confirmed['MobilePhone']; ?></a></td>
    <td align="center"><?php echo $row_players_confirmed['amount_entry']; ?></td>
    <td align="center"><?php echo $row_players_confirmed['how_paid']; ?></td>
    <td align="center"><?php echo $row_players_confirmed['Junior']; ?></td>
    <td align="center"><?php echo $row_players_confirmed['Gender']; ?></td>
    <td align="center"><?php echo $row_players_confirmed['HomeState']; ?></td>
    <td align="center"><?php echo $row_players_confirmed['memb']; ?></td>
    <td align="left"><?php $date = $row_players_confirmed['tourn_date_ent']; echo date("d M", strtotime($date)); ?></td>
    <td align="left"><?php echo $row_players_confirmed['entered_by']; ?></td>
    <td align="center"><?php echo $row_players_confirmed['rank_pts']; ?></td>
    <td align="center"><?php echo $row_players_confirmed['ranknum']; ?></td>
    <td nowrap="nowrap"><a href="user_files/player_financial_edit.php?memb_id=<?php echo $row_players_confirmed['MemberID']; ?>&tourn_id=<?php echo $tourn_id ?>">edit member financials</a></td>
    <td nowrap="nowrap"><a href="user_files/player_edit.php?player_id=<?php echo $row_players_confirmed['ID']; ?>&tourn_id=<?php echo $tourn_id ?>"><img src="../Admin_Images/edit_butt.png" alt="" width="16" height="16" title="Edit player details" /></a></td>
    <td nowrap="nowrap"><a href="user_files/player_delete_confirm.php?del=<?php echo $row_players_confirmed['ID']; ?>&tourn_id=<?php echo $tourn_id; ?>"><img src="../Admin_Images/Trash.fw.png" width="17" height="17" /></a></td>
  </tr>
  <?php } while ($row_players_confirmed = mysql_fetch_assoc($players_confirmed)); ?>
  <?php } else echo '<td colspan=18 align=center>'."No Confirmed Entries".'</td>'; ?>
</table>
    <?php
//$genCSVLink = "download_csv.php?id=" . $tourn_id . "&tourn_type=" . $tourn_type;
?>
<?php
$ExportCSVLink = "export_tournament_csv.php?id=" . $tourn_id . "&tourn_type=" . $tourn_type;
?>
<table cellspacing="5" cellpadding="5" align="center">
  <tr>
    <!--<td class="greenbg" nowrap="nowrap"><a href="<?PHP echo $genCSVLink?>">Download .csv (Confirmed entries only) </a></td>-->
    <td colspan="2" class="greenbg" align="center"><a href="<?php echo $ExportCSVLink ?>">Export Confirmed entries To CSV File</a></td>
  </tr>
</table>

<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan="19" align="center" class="red_bold">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="18" align="center" class="red_bold">Unconfirmed Entries (where confirmed is unchecked)</td>
  </tr>
  <?php if ($totalRows_players_unconfirmed > 0) { // Show if recordset not empty ?> 
  <tr>
    <td align="center" nowrap="nowrap">Tourn ID</td>
    <td align="center" nowrap="nowrap">Member ID </td>
    <td align="left">Name</td>
    <td align="left">Email</td>
    <td align="left">Phone</td>
    <td align="center">Paid</td>
    <td align="center" nowrap="nowrap">How Paid</td>
    <td align="center">Member?</td>
    <td align="center">Seed</td>
    <td align="left" nowrap="nowrap">Entered On</td>
    <td align="left" nowrap="nowrap">Entered By</td>
    <td align="center" nowrap="nowrap">Wildcard</td>
    <td align="center" nowrap="nowrap">Rank Points</td>
    <td align="center">Vic Rank</td>
    <td align="center">Junior?</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <?php do { ?>
  <tr class="page">
    <td align="center"><?php echo $row_players_unconfirmed['TournID']; ?></td>
    <td align="center"><?php echo $row_players_unconfirmed['MemberID']; ?></td>
    <td align="left"><?php echo $row_players_unconfirmed['FullName']; ?></td>
    <td align="left"><a href="mailto:<?php echo $row_players_unconfirmed['Email']; ?>"><?php echo $row_players_unconfirmed['Email']; ?></a></td>
    <td><a href="tel:<?php echo $row_players_unconfirmed['MobilePhone']; ?>"><?php echo $row_players_unconfirmed['MobilePhone']; ?></a></td>
    <td align="center"><?php echo $row_players_unconfirmed['amount_entry']; ?></td>
    <td align="center"><?php echo $row_players_unconfirmed['how_paid']; ?></td>
    <td align="center"><?php echo $row_players_unconfirmed['memb']; ?></td>
    <td align="center"><?php echo $row_players_unconfirmed['seed']; ?></td>
    <td align="left"><?php $date = $row_players_unconfirmed['tourn_date_ent']; echo date("d M", strtotime($date)); ?></td>
    <td align="left"><?php echo $row_players_unconfirmed['entered_by']; ?></td>
    <td align="center"><?php echo $row_players_unconfirmed['wcard']; ?></td>
    <td align="center"><?php echo $row_players_unconfirmed['rank_pts']; ?></td>
    <td align="center"><?php echo $row_players_unconfirmed['ranknum']; ?></td>
    <td align="center"><?php echo $row_players_unconfirmed['junior_cat']; ?></td>
    <td nowrap="nowrap"><a href="user_files/player_financial_edit.php?memb_id=<?php echo $row_players_unconfirmed['MemberID']; ?>&tourn_id=<?php echo $tourn_id ?>">edit member financials</a></td>
    <td nowrap="nowrap"><a href="user_files/player_edit.php?player_id=<?php echo $row_players_unconfirmed['ID']; ?>&tourn_id=<?php echo $tourn_id ?>"><img src="../Admin_Images/edit_butt.png" height="20" title="Edit player details" /></a></td>
    <td nowrap="nowrap"><a href="user_files/player_delete_confirm.php?del=<?php echo $row_players_unconfirmed['ID']; ?>"><img src="../Admin_Images/Trash.fw.png" height="20" /></a></td>
  </tr>
  <?php } while ($row_players_unconfirmed = mysql_fetch_assoc($players_unconfirmed)); ?>
  <?php } else echo '<td colspan=18 align=center>'."No Unconfirmed Entries".'</td>'; ?>
</table>
<p>&nbsp;</p>
<p align="center"></p>

<!-- add php mail merge form -->
<form action="" method="post" name="editor_form" id="editor_form">
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="134" class="page">&nbsp;</td>
    <td width="551" align="right" class="greenbg">&nbsp;</td>
  </tr>
  <tr>
    <td>Would you like to send an attachment?</td>
    <td>&nbsp;</td>
    <td class="greenbg"><a href="attach_upload.php">Please upload it now</a></td>
    <td align="right" class="greenbg"><a href="Bulk_email_help.pdf">Bulk Email help</a></td>
  </tr>
  <tr>
    <td width="211" align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>To Send a group email: </td>
    <td width="11">&nbsp;</td>
    <td colspan="2">1. Type your email address in the &quot;From&quot; field</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">2. Type VBSA in &quot;Name&quot; field.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">3. From the &quot;Recordset fields&quot; select &quot;Email&quot;. Click the <img src="php_mail_merge/dynamic_e.gif" alt="1" width="17" height="17" /> button and it will add this field into the &quot;To&quot; field.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">4. In the message area type a greeting &quot;Hi&quot; followed by a space. From the&quot;recordset fields&quot; select &quot;Firstname&quot; </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;&nbsp;&nbsp;click the <img src="php_mail_merge/dynamic_t.gif" alt="1" /> button and it will add a field that will reflect the first name of the person to receive the email</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">5. Tick the box for &quot;Plain Text&quot; alongside &quot;Send as&quot; </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">6. Type in the subject and your message in the appropriate fields and click &quot;Send&quot;.</td>
  </tr>
</table>

<br />
<?php $myRecordset=$memb; $myTotalRecords=$totalRows_memb; ?>
<?php //$myRecordset = $players_confirmed; $myTotalRecords = $totalRows_players_confirmed; ?>
<table width="960" border="0" align="center" cellpadding="3" cellspacing="0" id="filters">
  <tr>
    <td title="Area designated for Recordset filters (form fields)"><fieldset>
      <legend>Filters</legend>
      <br />
      <br />
      Reset Editor:
      <input name="reset_editor" type="checkbox" id="reset_editor" title="Reset Editor fields when filtering the Recordset" value="1" />
      <input name="Filter" type="submit" value="Filter" onclick="refreshSource();document.getElementById('Do_Send').value=''" id="Filter" title="Filter the Recordset."/>
    </fieldset></td>
  </tr>
</table>
<?php include("php_mail_merge.php"); ?>
</form>
<script language="javascript" src="php_mail_merge/php_mail_merge.js" type="text/javascript">
</script>
<script language="javascript" type="text/javascript">initPMM();<?php if (isset($_POST["SendAs"]) && !isset($_POST['reset_editor'])){?>flipMessageFormat(document.getElementById('SendAs'));<?php }?></script>

<!-- end mail merge form -->

</body>
</html>

