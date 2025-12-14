<?php 

require_once('../Connections/connvbsa.php'); 
require_once('../MailerLite/mailerlite_functions.php'); 

error_reporting(0);

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

//Form 2

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) 
{
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
  $insertSQL = sprintf("Insert INTO members (MemberID, LastName, FirstName, HomeState, HomePostcode, HomePhone, MobilePhone, ReceiveSMS, Email, ReceiveEmail, LifeMember, ccc_player, contact_only, entered_on, Gender, Overseas, affiliate_player, Affiliate_1, Affiliate_2, Affiliate_3, Affiliate_4, Referee, Ref_Class, dob_day, dob_mnth, dob_year, prospective_ref, paid_memb, paid_how, paid_date, hon_memb, community, Junior) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['MemberID'], "int"),
                       GetSQLValueString($_POST['LastName'], "text"),
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['HomeState'], "text"),
                       GetSQLValueString($_POST['HomePostcode'], "text"),
                       GetSQLValueString($_POST['HomePhone'], "text"),
                       GetSQLValueString($_POST['MobilePhone'], "text"),
                       GetSQLValueString($_POST['ReceiveSMS'], "int"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['ReceiveEmail'], "int"),
                       GetSQLValueString(isset($_POST['LifeMember']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['ccc_player']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['contact_only']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['entered_on'], "date"),
                       GetSQLValueString($_POST['Gender'], "text"),
                       GetSQLValueString($_POST['Overseas'], "text"),
                       GetSQLValueString(isset($_POST['Referee']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['affiliate_player']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['affiliate_1'], "text"),
                       GetSQLValueString($_POST['affiliate_2'], "text"),
                       GetSQLValueString($_POST['affiliate_3'], "text"),
                       GetSQLValueString($_POST['affiliate_4'], "text"),
                       GetSQLValueString($_POST['Ref_Class'], "text"),
                       GetSQLValueString($_POST['dob_day'], "int"),
                       GetSQLValueString($_POST['dob_mnth'], "int"),
                       GetSQLValueString($_POST['dob_year'], "int"), 
                       GetSQLValueString(isset($_POST['prospective_ref']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['paid_memb'], "int"),
                       GetSQLValueString($_POST['paid_how'], "text"),
                       GetSQLValueString($_POST['paid_date'], "date"),
                       GetSQLValueString(isset($_POST['hon_memb']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['community']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($junior, "text"));

  //echo($insertSQL . "<br>");
  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = $_SESSION['page'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
// end form 2

mysql_select_db($database_connvbsa, $connvbsa);
$query_nextmember = "SELECT Max(MemberID+1) FROM members";
$nextmember = mysql_query($query_nextmember, $connvbsa) or die(mysql_error());
$row_nextmember = mysql_fetch_assoc($nextmember);
$totalRows_nextmember = mysql_num_rows($nextmember);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA New Member Insert</title>
<script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="1000" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td><img src="../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>
<table width="1000" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td align="left" class="red_bold">INSERT A NEW  PERSON, PLAYER OR MEMBER</td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<div id="DBcontent">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
<table width="1000" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td align="right" nowrap="nowrap">New Member ID:</td>
    <td><?php echo $row_nextmember['Max(MemberID+1)']; ?></td>
    <td>&nbsp;</td>
    <td align="right">Inserted On:</td>
    <td colspan="2"><?php echo date('l jS F Y'); ?> (Auto inserted) </td>
  </tr>
  <tr>
    <td align="right" nowrap="nowrap">Last Name:</td>
    <td><input type="text" name="LastName" value="" size="32" /></td>
    <td>&nbsp;</td>
    <td align="right" nowrap="nowrap">Mobile Phone:</td>
    <td colspan="2"><input type="text" name="MobilePhone" value="" size="32" /></td>
  </tr>
  <tr>
    <td align="right" nowrap="nowrap">First Name:</td>
    <td><input type="text" name="FirstName" value="" size="32" /></td>
    <td>&nbsp;</td>
    <td align="right">State:</td>
    <td colspan="2"><select name="HomeState">
      <option value="" >No Entry</option>
      <option value="Vic" selected="selected" >Vic</option>
      <option value="NT">NT</option>
      <option value="NSW">NSW</option>
      <option value="Qld">Qld</option>
      <option value="SA">SA</option>
      <option value="Tas">Tas</option>
      <option value="WA">WA</option>
      <option value="ACT">ACT</option>
    </select></td>
  </tr>
  <tr>
    <td align="right" nowrap="nowrap">Land Line</td>
    <td><input type="text" name="HomePhone" value="" size="32" /></td>
    <td>&nbsp;</td>
    <td align="right">Postcode:</td>
    <td colspan="2"><input type="text" name="HomePostcode" value="" size="10" /></td>
  </tr>
  <tr>
    <td align="right" nowrap="nowrap">Email:</td>
    <td><input type="text" name="Email" value="" size="32" /></td>
    <td>&nbsp;</td>
    <td align="right">Country:</td>
    <!--<td><input type="text" name="Overseas" value="" size="10" /></td>-->
    <td align="left"><select id="Overseas" name="Overseas" style="width: 185px; height: 25px;">
              <option value="Australia">Australia</option>
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
          </td>
    <td nowrap="nowrap">&nbsp;</td>
  </tr>
  <tr>
    <td align="right">DOB</td>
    <td colspan="5"><input type="text" name="dob_day" value="" size="2" />
      <input type="text" name="dob_mnth" value="" size="2" />
      <input type="text" name="dob_year" value="" size="4" />
      Please insert as dd - mm - yyyy </td>
  </tr>
  <tr>
    <td colspan="6" align="center" nowrap="nowrap" class="red_text">. NOTE: For a player to appear as a junior they MUST have a date of birth listed and year of birth must be &lt;18 years ago. Insert Year of Birth only for statistical purposes</td>
  </tr>
  <tr>
    <td align="right" nowrap="nowrap">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
<table width="1000" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td align="right" nowrap="nowrap">Life Member</td>
    <td><input type="checkbox" name="LifeMember" id="LifeMember" /></td>
    <td align="right">Gender:</td>
    <td><select name="Gender">
      <option value="Male" selected="selected">Male</option>
      <option value="Female">Female</option>
      <option value="NonBinary">Non Binary</option>
      <option value="NoGender">No Gender</option>
    </select></td>
    <td align="right">Community Member</td>
    <td><input type="checkbox" name="community" id="community"  <?php if (!(strcmp(htmlentities($row_Details['community'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
  </tr>
  <tr>
    <td align="right" nowrap="nowrap">CCC Player</td>
    <td><input type="checkbox" name="ccc_player" id="ccc_player" /></td>
    <td align="right">Deceased:</td>
    <td align="left"><input type="checkbox" name="Deceased" id="Deceased" /></td>
    <td align="right">Honorary Member</td>
    <td><input type="checkbox" name="hon_memb" id="hon_memb"  <?php if (!(strcmp(htmlentities($row_Details['hon_memb'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
  </tr>
  <tr>
    <td align="right" nowrap="nowrap">Referee:</td>
    <td><input type="checkbox" name="Referee" id="Referee" /></td>
    <td align="right">Ref Class:</td>
    <td align="left"><input type="text" name="Ref_Class" value="" size="20" /></td>
    <td align="right">Prospective Referee?</td>
    <td colspan="2" align="left"><input type="checkbox" name="prospective_ref" id="prospective_ref" /></td>
  </tr>
  <tr>
    <td align="right">Affiliate</td>
    <td align="left"><input name="affiliate_player" id="affiliate_player" type="checkbox"/></td>
    <td align="center">Affiliate 1&nbsp;<select name="affiliate_1">
      <option value="">&nbsp;</option>
      <option value="DVSA">DVSA</option>
      <option value="MSBA">MSBA</option>
      <option value="LVABA">LVABA</option>
    </select></td>
    <td align="center">Affiliate 2&nbsp;<select name="affiliate_2">
      <option value="">&nbsp;</option>
      <option value="DVSA">DVSA</option>
      <option value="MSBA">MSBA</option>
      <option value="LVABA">LVABA</option>
    </select></td>
    <td align="center">Affiliate 3&nbsp;<select name="affiliate_3">
      <option value="">&nbsp;</option>
      <option value="DVSA">DVSA</option>
      <option value="MSBA">MSBA</option>
      <option value="LVABA">LVABA</option>
    </select></td>
    <td align="center">Affiliate 4&nbsp;<select name="affiliate_4">
      <option value="">&nbsp;</option>
      <option value="DVSA">DVSA</option>
      <option value="MSBA">MSBA</option>
      <option value="LVABA">LVABA</option>
    </select></td>
  </tr>
  <tr>
    <td align="right" nowrap="nowrap">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" nowrap="nowrap" bgcolor="#CDCDCD">Contact only:</td>
    <td colspan="6" bgcolor="#CDCDCD"><input type="checkbox" name="contact_only" id="contact_only" />
    <span class="red_text">* Use this checkbox if this person is a contact for a club only. The VBSA do not record address</span></td>
  </tr>
  <tr>
    <td colspan="6">&nbsp;</td>
  </tr>
  <tr>
    <td align="right">Paid $</td>
    <td align="left"><input type="text" name="paid_memb" value="" size="10" /></td>
    <td align="right">How Paid:</td>
    <td><select name="paid_how">
        <option value="" >No Entry</option>
        <option value="PP">PP</option>
        <option value="Cash">Cash</option>
        <option value="BT">BT</option>
        <option value="CHQ">CHQ</option>
        <option value="Other">Other</option>
      </select>
    </td>
    <td align="right">Date Paid:&nbsp;</td>
    <td align="left"><input type="text" name="paid_date" value="" size="15" /> <input type="button" value="Select Date Paid" onclick="displayDatePicker('paid_date', false, 'ymd', '.');" /></td>
  </tr>
  <tr>
    <td align='center' colspan="6">(Remove ALL fields if removing as paid)</td>
  </tr>
  <tr>
    <td align="right" nowrap="nowrap">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><input type="submit" value="Insert person" /></td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="MM_insert" value="form2" />
<input type="hidden" name="MemberID" value="<?php echo $row_nextmember['Max(MemberID+1)']; ?>" />
<input type="hidden" name="entered_on" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d"); ?> " />
<input type="hidden" name="ReceiveSMS" value="1" />
<input type="hidden" name="ReceiveEmail" value="1" />
</form>
</div>
</body>
</html>
<?php
mysql_free_result($nextmember);
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
