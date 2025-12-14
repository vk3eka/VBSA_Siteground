<?php 
require_once('../Connections/connvbsa.php');
require_once('../MailerLite/mailerlite_functions.php'); 

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
?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

  $dob_year = $_POST['dob_year'];
  if(($dob_year >= (date("Y")-18)) AND ($dob_year <= (date("Y")-16)))
  {
    $junior = 'U18';
  }
  else if(($dob_year >= (date("Y")-15)) AND ($dob_year <= (date("Y")-13)))
  {
    $junior = 'U15';
  }
  else if(($dob_year >= (date("Y")-12)) AND ($dob_year <= (date("Y"))))
  {
    $junior = 'U12';
  }
  else
  {
    $junior = 'na';
  }

  $updateSQL = sprintf("Update members SET LastName=%s, FirstName=%s, HomeState=%s, HomePostcode=%s, MobilePhone=%s, Overseas=%s, ReceiveSMS=%s, Email=%s, ReceiveEmail=%s, promo_optin_out=%s, promo_pref=%s, memb_by=%s, memb_date=%s, memb_submit_date=%s, memb_occupation=%s, Gender=%s, dob_day=%s, dob_mnth=%s, dob_year=%s, Junior=%s WHERE MemberID=%s",
                       GetSQLValueString($_POST['LastName'], "text"),
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['HomeState'], "text"),
                       GetSQLValueString($_POST['HomePostcode'], "text"),
                       GetSQLValueString($_POST['MobilePhone'], "text"),
                       GetSQLValueString($_POST['Overseas'], "text"),
					             GetSQLValueString(isset($_POST['ReceiveSMS']) ? "true" : "", "defined","'1'","'0'"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString(isset($_POST['ReceiveEmail']) ? "true" : "", "defined","'1'","'0'"),
          					   GetSQLValueString(isset($_POST['promo_optin_out']) ? "true" : "", "defined","'1'","'0'"),
          					   GetSQLValueString($_POST['promo_pref'], "text"),
          					   GetSQLValueString($_POST['memb_by'], "text"),
                       GetSQLValueString($_POST['memb_date'], "date"),
          					   GetSQLValueString($_POST['memb_submit_date'], "date"),
          					   GetSQLValueString($_POST['memb_occupation'], "text"),
                       GetSQLValueString($_POST['Gender'], "text"),
                       GetSQLValueString($_POST['dob_day'], "int"),
                       GetSQLValueString($_POST['dob_mnth'], "int"),
                       GetSQLValueString($_POST['dob_year'], "date"),
                       GetSQLValueString($junior, "text"),
                       GetSQLValueString($_POST['MemberID'], "int"));
  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  // get existing email to check if changed.
  $query_email = "Select Email FROM members WHERE MemberID = " . $_POST['MemberID'];
  $memb_email = mysql_query($query_email, $connvbsa) or die(mysql_error());
  $row_email = mysql_fetch_assoc($memb_email);
  $existing_email = $row_email['Email'];

  if($_POST['Email'] != $existing_email)
  {
    UpdateEmail($_POST['Email'], $existing_email);
    // update SEWS authorier page if required.
    // check if a SEWS login.
    $query_sews_email = "Select Email FROM tbl_authorise WHERE PlayerNo = " . $_POST['MemberID'];
    $memb_sews_email = mysql_query($query_sews_email, $connvbsa) or die(mysql_error());
    $total_rows_sews = mysql_num_rows($memb_sews_email);
    if($total_rows_sews > 0)
    {
      $update_sews = sprintf("Update tbl_authorise SET Email=%s WHERE PlayerNo=%s",
      GetSQLValueString($_POST['Email'], "text"),
      GetSQLValueString($_POST['MemberID'], "int"));
      $result_sews = mysql_query($update_sews, $connvbsa) or die(mysql_error());
    }
  }


  $updateGoTo = $_SESSION['page'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


if (isset($_GET['memb_id'])) {
  $membID = $_GET['memb_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_memb_id = "Select MemberID, LastName, FirstName, HomeState, HomePostcode, MobilePhone, Overseas, ReceiveSMS, Email, ReceiveEmail, promo_optin_out, promo_pref, memb_by, memb_date,  memb_submit_date, memb_occupation, Gender, dob_day, dob_mnth, dob_year FROM members WHERE members.MemberID='$membID'";
$memb_id = mysql_query($query_memb_id, $connvbsa) or die(mysql_error());
$row_memb_id = mysql_fetch_assoc($memb_id);
$totalRows_memb_id = mysql_num_rows($memb_id);

// Get list of Clubs for Dropdown menu

// Get Username
$colname_getusername = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getusername = $_SESSION['MM_Username'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_getusername = sprintf("SELECT id, name, usertype FROM vbsaorga_users WHERE username = %s", GetSQLValueString($colname_getusername, "text"));
$getusername = mysql_query($query_getusername, $connvbsa) or die(mysql_error());
$row_getusername = mysql_fetch_assoc($getusername);
$totalRows_getusername = mysql_num_rows($getusername);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>VBSA Member Edit</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>

<table width="1000" align="center" cellpadding="2">
  <tr>
    <td class="red_bold">UPDATE MEMBER DETAIL FROM MEMBERSHIP FORM</td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table> 
<div id="DBcontent">
<table width="1000" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td class="red_text">When using this page, on submit your name will be stored as the person who entered / updated the information along with the date of entry.</td>
  </tr>
  <tr>
    <td>If you see an error in the name please correct, check Email and Mobile phone number and correct or insert as necessary, insert postcode if available.</td>
  </tr>
  <tr>
    <td>If the person is a junior and a Date of Birth is available please insert.</td>
  </tr>
  <tr>
    <td>When a person is entered into the database the &quot;Receive Email &amp; Receive SMS&quot; are auto inserted as checked. These checkboxes decide if VBSA realated correspondance is sent. eg. News letters, tournament notifications</td>
  </tr>
  <tr>
    <td>The receive Promo checkbox decides if a person is to receive promotional material from sponsors or advertisers. If you check this box please then select a preference in the &quot;Preferred Comm&quot; dropdown list.</td>
  </tr>
</table>
</div>

<div id="DBcontent">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" >
              <table align="center" cellpadding="4" cellspacing="4">
                      <tr>
                        <td align="right" nowrap="nowrap">Member ID: </td>
                        <td align="left"><?php echo $membID; ?></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right">First Name: </td>
                        <td><input type="text" name="FirstName" value="<?php echo $row_memb_id['FirstName']; ?>" size="32" /></td>
                        <td align="right">Email: </td>
                        <td ><span class="page">
                          <input type="text" name="Email" value="<?php echo $row_memb_id['Email']; ?>" size="30" />
                        </span></td>
                        <td align="right">&nbsp;</td>
                        <td >&nbsp;</td>
                        <td >&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right">Last Name: </td>
                        <td><input type="text" name="LastName" value="<?php echo $row_memb_id['LastName']; ?>" size="32" /></td>
                        <td align="right">Mobile</td>
                        <td><input type="text" name="MobilePhone" value="<?php echo $row_memb_id['MobilePhone']; ?>" size="30" /></td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right">Postcode</td>
                        <td><input type="text" name="HomePostcode" value="<?php echo $row_memb_id['HomePostcode']; ?>" size="6" /></td>
                        <td align="right">Occupation</td>
                        <td><input type="text" name="memb_occupation" value="<?php echo $row_memb_id['memb_occupation']; ?>" size="30" /></td>
                        <td align="right">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right">State</td>
                        <td align="left"><span class="page">
                          <select name="HomeState">
                            <option value="" >No Entry</option>
                            <option value="Vic" <?php if (!(strcmp("Victoria", htmlentities($row_memb_id['HomeState'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Vic</option>
                            <option value="Vic" <?php if (!(strcmp("Vic", htmlentities($row_memb_id['HomeState'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Vic</option>
                            <option value="ACT" <?php if (!(strcmp("ACT", htmlentities($row_memb_id['HomeState'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>ACT</option>
                            <option value="NT" <?php if (!(strcmp("NT", htmlentities($row_memb_id['HomeState'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>NT</option>
                            <option value="NSW" <?php if (!(strcmp("NSW", htmlentities($row_memb_id['HomeState'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>NSW</option>
                            <option value="Qld" <?php if (!(strcmp("Qld", htmlentities($row_memb_id['HomeState'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Qld</option>
                            <option value="SA" <?php if (!(strcmp("SA", htmlentities($row_memb_id['HomeState'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>SA</option>
                            <option value="Tas" <?php if (!(strcmp("Tas", htmlentities($row_memb_id['HomeState'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Tas</option>
                            <option value="WA" <?php if (!(strcmp("WA", htmlentities($row_memb_id['HomeState'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>WA</option>
                          </select>
                        </span></td>
                        <td align="right">Country:</td>
                        <td align="left"><select id="Overseas" name="Overseas" style="width: 211px; height: 20px;">
                            <option value="<?php echo $row_memb_id['Overseas']; ?>"><?php echo $row_memb_id['Overseas']; ?></option>
                            <option value="">&nbsp;</option>
                            <option value="Afghanistan">Afghanistan</option>
                            <option value="Åland Islands">Åland Islands</option>
                            <option value="Albania">Albania</option>
                            <option value="Algeria">Algeria</option>
                            <option value="American Samoa">American Samoa</option>
                            <option value="Andorra">Andorra</option>
                            <option value="Angola">Angola</option>
                            <option value="Anguilla">Anguilla</option>
                            <option value="Antarctica">Antarctica</option>
                            <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                            <option value="Argentina">Argentina</option>
                            <option value="Armenia">Armenia</option>
                            <option value="Aruba">Aruba</option>
                            <option value="Australia">Australia</option>
                            <option value="Austria">Austria</option>
                            <option value="Azerbaijan">Azerbaijan</option>
                            <option value="Bahamas">Bahamas</option>
                            <option value="Bahrain">Bahrain</option>
                            <option value="Bangladesh">Bangladesh</option>
                            <option value="Barbados">Barbados</option>
                            <option value="Belarus">Belarus</option>
                            <option value="Belgium">Belgium</option>
                            <option value="Belize">Belize</option>
                            <option value="Benin">Benin</option>
                            <option value="Bermuda">Bermuda</option>
                            <option value="Bhutan">Bhutan</option>
                            <option value="Bolivia">Bolivia</option>
                            <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                            <option value="Botswana">Botswana</option>
                            <option value="Bouvet Island">Bouvet Island</option>
                            <option value="Brazil">Brazil</option>
                            <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                            <option value="Brunei Darussalam">Brunei Darussalam</option>
                            <option value="Bulgaria">Bulgaria</option>
                            <option value="Burkina Faso">Burkina Faso</option>
                            <option value="Burundi">Burundi</option>
                            <option value="Cambodia">Cambodia</option>
                            <option value="Cameroon">Cameroon</option>
                            <option value="Canada">Canada</option>
                            <option value="Cape Verde">Cape Verde</option>
                            <option value="Cayman Islands">Cayman Islands</option>
                            <option value="Central African Republic">Central African Republic</option>
                            <option value="Chad">Chad</option>
                            <option value="Chile">Chile</option>
                            <option value="China">China</option>
                            <option value="Christmas Island">Christmas Island</option>
                            <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                            <option value="Colombia">Colombia</option>
                            <option value="Comoros">Comoros</option>
                            <option value="Congo">Congo</option>
                            <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                            <option value="Cook Islands">Cook Islands</option>
                            <option value="Costa Rica">Costa Rica</option>
                            <option value="Cote D'ivoire">Cote D'ivoire</option>
                            <option value="Croatia">Croatia</option>
                            <option value="Cuba">Cuba</option>
                            <option value="Cyprus">Cyprus</option>
                            <option value="Czechia">Czechia</option>
                            <option value="Denmark">Denmark</option>
                            <option value="Djibouti">Djibouti</option>
                            <option value="Dominica">Dominica</option>
                            <option value="Dominican Republic">Dominican Republic</option>
                            <option value="Ecuador">Ecuador</option>
                            <option value="Egypt">Egypt</option>
                            <option value="El Salvador">El Salvador</option>
                            <option value="Equatorial Guinea">Equatorial Guinea</option>
                            <option value="Eritrea">Eritrea</option>
                            <option value="Estonia">Estonia</option>
                            <option value="Ethiopia">Ethiopia</option>
                            <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                            <option value="Faroe Islands">Faroe Islands</option>
                            <option value="Fiji">Fiji</option>
                            <option value="Finland">Finland</option>
                            <option value="France">France</option>
                            <option value="French Guiana">French Guiana</option>
                            <option value="French Polynesia">French Polynesia</option>
                            <option value="French Southern Territories">French Southern Territories</option>
                            <option value="Gabon">Gabon</option>
                            <option value="Gambia">Gambia</option>
                            <option value="Georgia">Georgia</option>
                            <option value="Germany">Germany</option>
                            <option value="Ghana">Ghana</option>
                            <option value="Gibraltar">Gibraltar</option>
                            <option value="Greece">Greece</option>
                            <option value="Greenland">Greenland</option>
                            <option value="Grenada">Grenada</option>
                            <option value="Guadeloupe">Guadeloupe</option>
                            <option value="Guam">Guam</option>
                            <option value="Guatemala">Guatemala</option>
                            <option value="Guernsey">Guernsey</option>
                            <option value="Guinea">Guinea</option>
                            <option value="Guinea-bissau">Guinea-bissau</option>
                            <option value="Guyana">Guyana</option>
                            <option value="Haiti">Haiti</option>
                            <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                            <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                            <option value="Honduras">Honduras</option>
                            <option value="Hong Kong">Hong Kong</option>
                            <option value="Hungary">Hungary</option>
                            <option value="Iceland">Iceland</option>
                            <option value="India">India</option>
                            <option value="Indonesia">Indonesia</option>
                            <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                            <option value="Iraq">Iraq</option>
                            <option value="Ireland">Ireland</option>
                            <option value="Isle of Man">Isle of Man</option>
                            <option value="Israel">Israel</option>
                            <option value="Italy">Italy</option>
                            <option value="Jamaica">Jamaica</option>
                            <option value="Japan">Japan</option>
                            <option value="Jersey">Jersey</option>
                            <option value="Jordan">Jordan</option>
                            <option value="Kazakhstan">Kazakhstan</option>
                            <option value="Kenya">Kenya</option>
                            <option value="Kiribati">Kiribati</option>
                            <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                            <option value="Korea, Republic of">Korea, Republic of</option>
                            <option value="Kuwait">Kuwait</option>
                            <option value="Kyrgyzstan">Kyrgyzstan</option>
                            <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                            <option value="Latvia">Latvia</option>
                            <option value="Lebanon">Lebanon</option>
                            <option value="Lesotho">Lesotho</option>
                            <option value="Liberia">Liberia</option>
                            <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                            <option value="Liechtenstein">Liechtenstein</option>
                            <option value="Lithuania">Lithuania</option>
                            <option value="Luxembourg">Luxembourg</option>
                            <option value="Macao">Macao</option>
                            <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                            <option value="Madagascar">Madagascar</option>
                            <option value="Malawi">Malawi</option>
                            <option value="Malaysia">Malaysia</option>
                            <option value="Maldives">Maldives</option>
                            <option value="Mali">Mali</option>
                            <option value="Malta">Malta</option>
                            <option value="Marshall Islands">Marshall Islands</option>
                            <option value="Martinique">Martinique</option>
                            <option value="Mauritania">Mauritania</option>
                            <option value="Mauritius">Mauritius</option>
                            <option value="Mayotte">Mayotte</option>
                            <option value="Mexico">Mexico</option>
                            <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                            <option value="Moldova, Republic of">Moldova, Republic of</option>
                            <option value="Monaco">Monaco</option>
                            <option value="Mongolia">Mongolia</option>
                            <option value="Montenegro">Montenegro</option>
                            <option value="Montserrat">Montserrat</option>
                            <option value="Morocco">Morocco</option>
                            <option value="Mozambique">Mozambique</option>
                            <option value="Myanmar">Myanmar</option>
                            <option value="Namibia">Namibia</option>
                            <option value="Nauru">Nauru</option>
                            <option value="Nepal">Nepal</option>
                            <option value="Netherlands">Netherlands</option>
                            <option value="Netherlands Antilles">Netherlands Antilles</option>
                            <option value="New Caledonia">New Caledonia</option>
                            <option value="New Zealand">New Zealand</option>
                            <option value="Nicaragua">Nicaragua</option>
                            <option value="Niger">Niger</option>
                            <option value="Nigeria">Nigeria</option>
                            <option value="Niue">Niue</option>
                            <option value="Norfolk Island">Norfolk Island</option>
                            <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                            <option value="Norway">Norway</option>
                            <option value="Oman">Oman</option>
                            <option value="Pakistan">Pakistan</option>
                            <option value="Palau">Palau</option>
                            <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                            <option value="Panama">Panama</option>
                            <option value="Papua New Guinea">Papua New Guinea</option>
                            <option value="Paraguay">Paraguay</option>
                            <option value="Peru">Peru</option>
                            <option value="Philippines">Philippines</option>
                            <option value="Pitcairn">Pitcairn</option>
                            <option value="Poland">Poland</option>
                            <option value="Portugal">Portugal</option>
                            <option value="Puerto Rico">Puerto Rico</option>
                            <option value="Qatar">Qatar</option>
                            <option value="Reunion">Reunion</option>
                            <option value="Romania">Romania</option>
                            <option value="Russian Federation">Russian Federation</option>
                            <option value="Rwanda">Rwanda</option>
                            <option value="Saint Helena">Saint Helena</option>
                            <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                            <option value="Saint Lucia">Saint Lucia</option>
                            <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                            <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                            <option value="Samoa">Samoa</option>
                            <option value="San Marino">San Marino</option>
                            <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                            <option value="Saudi Arabia">Saudi Arabia</option>
                            <option value="Senegal">Senegal</option>
                            <option value="Serbia">Serbia</option>
                            <option value="Seychelles">Seychelles</option>
                            <option value="Sierra Leone">Sierra Leone</option>
                            <option value="Singapore">Singapore</option>
                            <option value="Slovakia">Slovakia</option>
                            <option value="Slovenia">Slovenia</option>
                            <option value="Solomon Islands">Solomon Islands</option>
                            <option value="Somalia">Somalia</option>
                            <option value="South Africa">South Africa</option>
                            <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                            <option value="Spain">Spain</option>
                            <option value="Sri Lanka">Sri Lanka</option>
                            <option value="Sudan">Sudan</option>
                            <option value="Suriname">Suriname</option>
                            <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                            <option value="Swaziland">Swaziland</option>
                            <option value="Sweden">Sweden</option>
                            <option value="Switzerland">Switzerland</option>
                            <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                            <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                            <option value="Tajikistan">Tajikistan</option>
                            <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                            <option value="Thailand">Thailand</option>
                            <option value="Timor-leste">Timor-leste</option>
                            <option value="Togo">Togo</option>
                            <option value="Tokelau">Tokelau</option>
                            <option value="Tonga">Tonga</option>
                            <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                            <option value="Tunisia">Tunisia</option>
                            <option value="Turkey">Turkey</option>
                            <option value="Turkmenistan">Turkmenistan</option>
                            <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                            <option value="Tuvalu">Tuvalu</option>
                            <option value="Uganda">Uganda</option>
                            <option value="Ukraine">Ukraine</option>
                            <option value="United Arab Emirates">United Arab Emirates</option>
                            <option value="United Kingdom">United Kingdom</option>
                            <option value="United States">United States</option>
                            <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                            <option value="Uruguay">Uruguay</option>
                            <option value="Uzbekistan">Uzbekistan</option>
                            <option value="Vanuatu">Vanuatu</option>
                            <option value="Venezuela">Venezuela</option>
                            <option value="Viet Nam">Viet Nam</option>
                            <option value="Virgin Islands, British">Virgin Islands, British</option>
                            <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                            <option value="Wallis and Futuna">Wallis and Futuna</option>
                            <option value="Western Sahara">Western Sahara</option>
                            <option value="Yemen">Yemen</option>
                            <option value="Zambia">Zambia</option>
                            <option value="Zimbabwe">Zimbabwe</option>
                          </select>
                        <td colspan="4">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right">Gender:</td>
                        <td><select name="Gender">
                          <option value="<?= $row_memb_id['Gender'] ?>" selected="selected" ><?= $row_memb_id['Gender'] ?></option>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                          <option value="NonBinary">Non Binary</option>
                          <option value="NoGender">No Gender</option>
                        </select></td>
                      </tr>
                      <tr>
                        <td align="right">DOB</td>
                        <td colspan="6"><input type="text" name="dob_day" value="<?php echo $row_memb_id['dob_day']; ?>" size="2" /> <input type="text" name="dob_mnth" value="<?php echo $row_memb_id['dob_mnth']; ?>" size="2" /> 
                          <input type="text" name="dob_year" value="<?php echo $row_memb_id['dob_year']; ?>" size="4" /> 
                        Please insert as dd - mm - yyyy eg 01-01-2001. <span class="red_text">NOTE: For a player to appear as a junior they MUST have a date of birth listed</span></td>
                      </tr>
                      <tr>
                        <td align="right">Junior?:</td>
                        <td colspan="3">
            						 <?php
            						$age = (date("Y") -$row_memb_id['dob_year']);
            						if($age <=21)
            						{
            						echo "Yes";
            						}
            						elseif($age >21)
            						{
            						echo "No";
            						}
            						?>
                                    
                        <?php
            						$age = (date("Y") -$row_memb_id['dob_year']);
            						if($age==19 or $age==20 or $age==21)
            						{
            						echo "Minimum Competing age group: Under ";
            						echo "21";
            						echo "(Calculated by : current year ";
            						echo date("Y");
            						echo " - year of birth ";
            						echo $row_memb_id['dob_year'];
            						}
            						elseif($age==16 or $age==17 or $age==18)
            						{
            						echo "Minimum Competing age group: Under ";
            						echo "18";
            						echo "(Calculated by : current year ";
            						echo date("Y");
            						echo " - year of birth ";
            						echo $row_memb_id['dob_year'];
            						}
            						elseif($age==13 or $age==14 or $age==15)
            						{
            						echo "Minimum Competing age group: Under ";
            						echo "15";
            						echo "(Calculated by : current year ";
            						echo date("Y");
            						echo " - year of birth ";
            						echo $row_memb_id['dob_year'];
            						}
            						elseif($age==12)
            						{
            						echo "Minimum Competing age group: Under ";
            						echo "12";
            						echo "(Calculated by : current year ";
            						echo date("Y");
            						echo " - year of birth ";
            						echo $row_memb_id['dob_year'];
            						}
            						else
            						{
            						echo"";
            						}
            						?>
                        
                        </td>
                        <td align="left" nowrap="nowrap">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="7" align="center"><strong>Membership Form details</strong></td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">Receive Email</td>
                        <td align="left"><input type="checkbox" name="ReceiveEmail" id="ReceiveEmail"  <?php if (!(strcmp(htmlentities($row_memb_id['ReceiveEmail'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                        <td align="left">Receive SMS</td>
                        <td align="left"><input type="checkbox" name="ReceiveSMS" id="ReceiveSMS"  <?php if (!(strcmp(htmlentities($row_memb_id['ReceiveSMS'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">Receive Promo</td>
                        <td align="left"><input type="checkbox" name="promo_optin_out" id="promo_optin_out"  <?php if (!(strcmp(htmlentities($row_memb_id['promo_optin_out'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
                        <td align="left">M'ship form submitted</td>
                        <td align="left"><input type="text" name="memb_submit_date" value="<?php echo $row_memb_id['memb_submit_date']; ?>" size="15" />
                        <input type="button" value="Select Date" onclick="displayDatePicker('memb_submit_date', false, 'ymd', '.');" /></td>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">Preferred comm</td>
                        <td align="left"><select name="promo_pref">
                          <option value="" >No Entry</option>
                          <option value="Email" <?php if ($row_memb_id['promo_pref'] == 'Email') {echo "SELECTED";} ?>>Email</option>
                          <option value="SMS" <?php if ($row_memb_id['promo_pref'] == 'SMS') {echo "SELECTED";} ?>>SMS</option>
                          <option value="Both" <?php if ($row_memb_id['promo_pref'] == 'Both') {echo "SELECTED";} ?>>Both SMS & Email</option>
                        </select></td>
                        <td align="left">Form Details inserted / updated by</td>
                        <td align="left">
                        <?php if(isset($row_memb_id['memb_by'])) { ?>
                        <input type="text" name="memb_by" value="<?php echo $row_memb_id['memb_by']; ?>" size="30" /></td>
                        <?php } else { ?><input type="hidden" name="memb_by" value="<?php echo $row_getusername['name']; ?>" /><?php echo  $row_getusername['name']."(auto inserted)" ?>
                        <?php } ?>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="7" align="left">Member Form info inserted / updated on:
                        <?php
            							$year = $row_memb_id['memb_date'] ? date("Y", strtotime($row_memb_id['memb_date'])) : null;
            							if($year >2000) { $newDate = date("l jS F Y \: g:ia", strtotime($row_memb_id['memb_date'])); echo $newDate; }
            							else
            							echo "Not Known";
            							?>, by <?php echo $row_memb_id['memb_by']; ?>.</td>
                      </tr>
                      <tr>
                        <td colspan="7" align="left">DO NOT alter Receive email or Receive SMS unless directed to by the member</td>
                      </tr>
                      <tr>
                        <td colspan="7" align="left" class="red_text">To remove M'ship uncheck &quot; Receive Promo&quot;, clear submit date, set &quot;Preferred comm&quot; to No Entry and clear the inserted by field. </td>
                      </tr>
                      <tr>
                        <td colspan="7" align="center"><input type="submit" value="Update Membership Form Details" /></td>
                      </tr>
    </table>
      	<input type="hidden" name="MemberID" value="<?php echo $row_memb_id['MemberID']; ?>" />
        <input type="hidden" name="memb_date" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d H:i:s"); ?> " />
      	<input type="hidden" name="memb_by" value="<?php echo $row_getusername['name']; ?>" />
      	<input type="hidden" name="MM_update" value="form1" />
  </form>
  </div>
  
  
 

</body>
</html>
<?php

?>
