<?php require_once('../Connections/connvbsa.php'); 
include('../vbsa_online_scores/php_functions.php'); 
date_default_timezone_set('Australia/Melbourne');

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

function ScoreRegistrarEmail($email, $member_name)
{
    $subject = 'VBSA New Online Membership Application'; 
    $message = '<html><body>';
    $message .= "<p>Scores Registrar</p>";
    $message .= "<p>An application from the VBSA Online Membership Form has been received.</p>";
    $message .= "<p>New Member Name " . $member_name . "</p>";
    $message .= "<p>&nbsp;</p>";
    $message .= "<p>Please access the Admin Portal and check for new members.</p>";
    $message .= "</body></html>";
    SendemailWithResponse($subject, $message, $email);
}

function NewMemberEmail($email, $name)
{
    $subject = 'VBSA New Online Membership Application'; 
    $message = '<html><body>';
    $message .= "<p>" . $name . "</p>";
    $message .= "<p>Your application from the VBSA Online Membership Form has been received and will be processed shortly.</p>";
    $message .= "<p>As mentioned in the Form, if you are <font color='red'>NOT</font> a Life Member, Active Referee, Active Coach, Active City Club Circuit member or will <font color='red'>NOT</font> be participating in the current VBSA pennant season, a $20 Annual Membership Fee is payable.  Go to the <a href='https://www.vbsa.org.au/vbsa_shop/shop_cart.php#!/VBSA-membership/p/98811788/category=0'>VBSA Shop</a> to pay the fee.</p>";
    $message .= "<p>If you have any questions please reply to this email or email <a href='mailto:scores@vbsa.org.au'>scores@vbsa.org.au.</a></p>"; 
    $message .= "<p>Thank you for your interest in the VBSA.</p>";
    $message .= "<p>VBSA President.</p>";
    $message .= "</body></html>";
    SendemailWithResponse($subject, $message, $email);
}

$editFormAction = $_SERVER['PHP_SELF'];
/*if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}*/

$captcha_site_key = "6LcwbLIfAAAAADdiXBDCs2IGxxEq3bU8TmRVd0Nm";
$captcha_secret_key = "6LcwbLIfAAAAAFyE15OfFXSOy5i44rXMcwwuhUQ5";
$captcha_url = "https://www.google.com/recaptcha/api/siteverify";

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "address-form")) 
{
    if($_POST['ref_c1_e'] == true)
    {
      $ref_c1_e = "Class 1 Examiner";
    }
    if($_POST['ref_c1'] == true)
    {
      $ref_c1 = "Class 1";
    }

    if($_POST['ref_c2'] == true)
    {
      $ref_c2 = "Class 2";
    }
    if($_POST['ref_c2b'] == true)
    {
      $ref_c2b = "Class 2 Billiards";
    }
    if($_POST['ref_c2s'] == true)
    {
      $ref_c2s = "Class 2 Snooker";
    }

    if($_POST['ref_c3'] == true)
    {
      $ref_c3 = "Class 3";
    }
    if($_POST['ref_c3b'] == true)
    {
      $ref_c3b = "Class 3 Billiards";
    }
    if($_POST['ref_c3s'] == true)
    {
      $ref_c3s = "Class 3 Snooker";
    }
    $ref_class = $ref_c1_e . ", "  . $ref_c1 . ", "  . $ref_c2 . ", "  . $ref_c2b . ", " . $ref_c2s . ", "  . $ref_c3 . ", " . $ref_c3b . ", " . $ref_c3s;
    $ref_class = str_replace(',', '', $ref_class);
    
    if($_POST['address2'] == '')
    {
      $address = "'" . $_POST['ship-address'] . "'";
    }
    else
    {
      $address = "'" . $_POST['ship-address'] . " ". $_POST['address2'] . "'";
    }
    if(isset($_POST['member_comms']) && (($_POST['member_comms'] == 'Email') || ($_POST['member_comms'] == 'Both')))
    {
      $receive_email = 1;
    }
    else
    {
      $receive_email = 0;
    }
    if(isset($_POST['member_comms']) && (($_POST['member_comms'] == 'SMS') || ($_POST['member_comms'] == 'Both')))
    {
      $receive_sms = 1;
    }
    else
    {
      $receive_sms = 0;
    }
    if(isset($_POST['gender']) && ($_POST['gender'] == 'female'))
    {
      $female = 1;
    }
    else
    {
      $female = 0;
    }
    if($_POST['country'] != 'Australia')
    {
      $overseas = 1;
    }
    else
    {
      $overseas = 0;
    }
    $datevalue = strtotime($_POST['dob']);
    $dob_year = date('Y', $datevalue);
    $dob_mnth = date('m', $datevalue);
    $dob_day = date('d', $datevalue);
    $todaysdate = date('Y-m-d');
    $other_person = "'" . $_POST['parent_name'] . ", " . $_POST['parent_phone'] . ", " . $_POST['relationship'] . "'";

    $insertSQL = sprintf("Insert INTO tbl_membership_online (LastName, FirstName, HomeAddress, HomeSuburb, HomeState, HomePostcode, Country, HomePhone, MobilePhone, ReceiveSMS, Email, ReceiveEmail, promo_optin_out, promo_pref, memb_occupation, entered_on, cue, Female, Overseas, Referee, Ref_Class, dob_day, dob_mnth, dob_year, new_renewal, other_person) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                    GetSQLValueString($_POST['surname'], "text"),
                    GetSQLValueString($_POST['firstname'], "text"),
                    $address,
                    GetSQLValueString($_POST['locality'], "text"),
                    GetSQLValueString($_POST['state'], "text"),
                    GetSQLValueString($_POST['postcode'], "text"),
                    GetSQLValueString($_POST['country'], "text"),
                    GetSQLValueString($_POST['homephone'], "text"),
                    GetSQLValueString($_POST['mobilephone'], "text"),
                    $receive_sms,
                    GetSQLValueString($_POST['email'], "text"),
                    $receive_email,
                    GetSQLValueString($_POST['consent'], "integer"),
                    GetSQLValueString($_POST['member_comms'], "text"),
                    GetSQLValueString($_POST['occupation'], "text"),
                    GetSQLValueString($todaysdate, "date"),
                    GetSQLValueString($_POST['cue'], "text"),
                    $female,
                    $overseas,
                    GetSQLValueString($_POST['referee'], "integer"),
                    GetSQLValueString($ref_class, "text"),
                    $dob_day,
                    $dob_mnth,
                    $dob_year,
                    GetSQLValueString($_POST['member'], "text"),
                    GetSQLValueString($other_person, "text"));
    mysql_select_db($database_connvbsa, $connvbsa);
    //echo($insertSQL . "<br>");
    $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());
    //$insertGoTo = "http://vbsa.cpc-world.com";
    $insertGoTo = "https://vbsa.org.au";

    echo("<script>");
    echo("alert('Your application has been received, and we have sent you an email confirming this, if you have any questions please email scores@vbsa.org.au');");
    ScoreRegistrarEmail("scores@vbsa.org.au", $_POST['firstname'] . " " . $_POST['surname']);
    NewMemberEmail($_POST['email'],  $_POST['firstname'] . " " . $_POST['surname']);
    echo("window.location.href = '" . $insertGoTo . "';");
    echo("</script>");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Membership Application</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" href="../Admin_Calendar/calendar/codebase/dhtmlxcalendar.css">
  <link rel="stylesheet" type="text/css" href="../Admin_Calendar/calendar/codebase/skins/dhtmlxcalendar_dhx_skyblue.css">

  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  
  <script src="../Admin_Calendar/calendar/codebase/dhtmlxcommon.js"></script>
  <script src="../Admin_Calendar/calendar/codebase/dhtmlxcalendar.js"></script>

</head>
<script>

$(document).ready(function()
{
  $('#email').on('change', function()
  {
    if(!IsEmail($('#email').val()))
    {
      alert('Email Address is Invalid');
    }
  });

  function IsEmail(email) {
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if(!regex.test(email)) {
       return false;
    }else{
       return true;
    }
  }
});
</script>

<script>
$(document).on('click', '#submit_button_clicked', function()
{
  var response = grecaptcha.getResponse();
  if(response == 0)
  {
    alert('Please verify that you are not a robot');
    return false;
  }
});
</script>
<script type='text/javascript'>

window.onload = function() {
  doOnLoad();
}

function doOnLoad() {
  var mybirthdate;  
  mybirthdate = new dhtmlXCalendarObject("dob");
  mybirthdate.setSkin('dhx_skyblue');
  mybirthdate.hideTime();
  mybirthdate.hideWeekNumbers();
  mybirthdate.setDateFormat("%Y-%m-%d");

  var todaysdate;  
  todaysdate = new dhtmlXCalendarObject("todaysdate");
  todaysdate.setSkin('dhx_skyblue');
  todaysdate.hideTime();
  todaysdate.hideWeekNumbers();
  todaysdate.setDateFormat("%Y-%m-%d");
}

function formatMobile()
{ 
  if((document.getElementById('country').value == 'Australia') || (document.getElementById('country').value == ''))
  {
    if(document.getElementById('mobilephone').value != '')
    {
      var from = document.getElementById('mobilephone').value;
    document.getElementById('mobilephone').value = (from.substr(0, 4) + " " + from.substr(4, 3) + " " + from.substr(7));
    }
  }
  
}

function formatHome()
{ 
  if((document.getElementById('country').value == 'Australia') || (document.getElementById('country').value == ''))
  {
    if(document.getElementById('homephone').value != '')
    {
      var from = document.getElementById('homephone').value;
    document.getElementById('homephone').value = (from.substr(0, 2) + " " + from.substr(2, 4) + " " + from.substr(6));
    }
    
  }
}

// enable submit button when both accept and agree checkboxes checked
function AgreeButtons()
{
  if(document.getElementById('accept_vbsa').checked == true)
  {
    document.getElementById('submit_button_clicked').disabled = false;
  }
}

function isANumber(str){
  return !/\D/.test(str);
}

function CheckInt(input)
{
  if(!isANumber(document.getElementById(input).value))
  {
    alert(input + " needs to be a number");
    document.getElementById(input).value = '';
    if(input == "homephone")
    {
      document.getElementById(input).placeholder = '0300000000 format';
    }
    else if(input == "parent_phone")
    {
      document.getElementById(input).placeholder = 'Phone Number';
    }
    else if(input == "mobilephone")
    {
      document.getElementById(input).placeholder = '0400000000 format';
    }
    else if(input == "postcode")
    {
      document.getElementById(input).placeholder = 'Post Code';
    }
    return;
  }
}

</script>
<script>
$(document).ready(function()
{
  
  $.fn.RefereeDisplay = function (clicked) {
    if(clicked == 'clicked')
    {
      document.getElementById('class1_examiner').disabled = false;
      document.getElementById('class1_examiner_label').style.color='black';
      document.getElementById('class1').disabled = false;
      document.getElementById('class1_label').style.color='black';
      document.getElementById('class2').disabled = false;
      document.getElementById('class2_label').style.color='black';
      document.getElementById('class2_billiards').disabled = false;
      document.getElementById('class2_billiards_label').style.color='black';
      document.getElementById('class2_snooker').disabled = false;
      document.getElementById('class2_snooker_label').style.color='black';
      document.getElementById('class3').disabled = false;
      document.getElementById('class3_label').style.color='black';
      document.getElementById('class3_billiards').disabled = false;
      document.getElementById('class3_billiards_label').style.color='black';
      document.getElementById('class3_snooker').disabled = false;
      document.getElementById('class3_snooker_label').style.color='black';
    }
    else if(clicked == 'not_clicked')
    {
      document.getElementById('class1_examiner').disabled = true;
      document.getElementById('class1_examiner_label').style.color='grey';
      document.getElementById('class1').disabled = true;
      document.getElementById('class1_label').style.color='grey';
      document.getElementById('class2').disabled = true;
      document.getElementById('class2_label').style.color='grey';
      document.getElementById('class2_billiards').disabled = true;
      document.getElementById('class2_billiards_label').style.color='grey';
      document.getElementById('class2_snooker').disabled = true;
      document.getElementById('class2_snooker_label').style.color='grey';
      document.getElementById('class3').disabled = true;
      document.getElementById('class3_label').style.color='grey';
      document.getElementById('class3_billiards').disabled = true;
      document.getElementById('class3_billiards_label').style.color='grey';
      document.getElementById('class3_snooker').disabled = true;
      document.getElementById('class3_snooker_label').style.color='grey';
    }
  }

});
</script>

<script>
/**
 * @license
 * Copyright 2019 Google LLC. All Rights Reserved.
 * SPDX-License-Identifier: Apache-2.0
 */
// This sample uses the Places Autocomplete widget to:
// 1. Help the user select a place
// 2. Retrieve the address components associated with that place
// 3. Populate the form fields with those address components.
// This sample requires the Places library, Maps JavaScript API.
// Include the libraries=places parameter when you first load the API.
// For example: <script
// src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
let autocomplete;
let address1Field;
let address2Field;
let postalField;

function initAutocomplete() {
  address1Field = document.querySelector("#ship-address");
  address2Field = document.querySelector("#address2");
  postalField = document.querySelector("#postcode");
  // Create the autocomplete object, restricting the search predictions to
  // addresses in the US and Canada.
  autocomplete = new google.maps.places.Autocomplete(address1Field, {
    componentRestrictions: { country: ["au"] },
    fields: ["address_components", "geometry"],
    types: ["address"],
  });
  address1Field.focus();
  // When the user selects an address from the drop-down, populate the
  // address fields in the form.
  autocomplete.addListener("place_changed", fillInAddress);
}

function fillInAddress() {
  // Get the place details from the autocomplete object.
  const place = autocomplete.getPlace();
  let address1 = "";
  let postcode = "";

  // Get each component of the address from the place details,
  // and then fill-in the corresponding field on the form.
  // place.address_components are google.maps.GeocoderAddressComponent objects
  // which are documented at http://goo.gle/3l5i5Mr
  for (const component of place.address_components) {
    // @ts-ignore remove once typings fixed
    const componentType = component.types[0];

    switch (componentType) {
      case "street_number": {
        address1 = `${component.long_name} ${address1}`;
        break;
      }

      case "route": {
        address1 += component.short_name;
        break;
      }

      case "postal_code": {
        postcode = `${component.long_name}${postcode}`;
        break;
      }

      case "postal_code_suffix": {
        postcode = `${postcode}-${component.long_name}`;
        break;
      }
      case "locality":
        document.querySelector("#locality").value = component.long_name;
        break;
      case "administrative_area_level_1": {
        document.querySelector("#state").value = component.short_name;
        break;
      }
      case "country":
        document.querySelector("#country").value = component.long_name;
        break;
    }
  }

  address1Field.value = address1;
  postalField.value = postcode;
  // After filling the form with address components from the Autocomplete
  // prediction, set cursor focus on the second address line to encourage
  // entry of subpremise information such as apartment, unit, or floor number.
  address2Field.focus();
}

window.initAutocomplete = initAutocomplete;

</script>
<body id="home">
<div class="container"> 
<?php include '../includes/header.php';?>
<?php include '../includes/nav_vbsa.php';?>
</div>

<!--End Bootstrap Container--> 
<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 
<!--Content--> 
<div class="row"> 
  <div class="Page_heading_container">
    <div class="page_title">VBSA Online Membership Application Form</div>
  </div>    
  <div style="clear:both"><hr style="width: 60%; color: #999; height: 1px; background-color: #999; " /> 
  </div> 
</div>
<div id="map"></div>
<div style="margin-left:10px; margin-right:10px">
<form action="<?php echo $editFormAction; ?>" method="post" name="address-form" id="address-form" autocomplete="off" class='recaptchaForm'>
  <table width="400" border="0" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td>
      <table class='table dt-responsive display'>
         <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="left">You must pay a $20 annual membership fee via the  <a href='https://www.vbsa.org.au/vbsa_shop/shop_cart.php#!/VBSA-membership/p/98811788/category=0' target='new' >VBSA Shop</a> if you are not any of the following:</td>
        </tr>
        <tr>
          <td align="left">·&nbsp;&nbsp;Life Member</td>
        </tr>
        </tr>
          <td align="left">·&nbsp;&nbsp;Active referee.</td>
        </tr>
        <tr>
          <td align="left">·&nbsp;&nbsp;Active coach.</td>
        </tr>
        <tr>
          <td align="left">·&nbsp;&nbsp;Active City Club Circuit member during the current competition year</td>
        </tr>
        <tr>
          <td align="left">·&nbsp;&nbsp;Played at least 1 VBSA pennant match in the calendar year preceding the date of membership.</td>
        </tr>
        <tr>
          <td >&nbsp;</td>
        </tr>
        </tr>
          <td align="left"><i>Please address all correspondence via email to <a href = "mailto:scores@vbsa.org.au">scores@vbsa.org.au</a></i></td>
        </tr>
        <tr>
          <td >&nbsp;</td>
        </tr>
        <tr>
          <td align="left">
            <input type="radio" id="register" name="member" value="New Application" checked="checked">
            <label for="register">I wish to become a member</label><br>
            <input type="radio" id="renew" name="member" value="Renewal">
            <label for="renew">I wish to renew my membership</label><br>
          </td>
        </tr>
        <tr>
          <td >&nbsp;</td>
        </tr>
        <tr>
          <td align="left"><font color="red">* required</font></td>
        </tr>
        <tr>
          <td nowrap="nowrap" align='left'>Surname/Family Name: <font color="red">*</font></td>
        </tr>
        <tr>
          <td align="center"><input type="text" name="surname" value="" size="24" placeholder='Surname/Family Name' required /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">Firstname: <font color="red">*</font></td>
        </tr>
        <tr>
          <td align="center"><input type="text" name="firstname" value="" size="24" placeholder='First Name/Given Name' required /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">Address: <font color="red">*</font></td>
        </tr>
        <tr>
          <td align="center"><input id="ship-address" name="ship-address" size="24" placeholder='Address' required/></td>
        </tr>
        </tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">Address 2:</td>
        </tr>
        <tr>
          <td align="center"><input id="address2" name="address2" size="24" placeholder='Unit No.' /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">Suburb: <font color="red">*</font></td>
        </tr>
        <tr>
          <td align="center"><input id="locality" name="locality" size="24" placeholder='Suburb'></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">State: <font color="red">*</font></td>
        </tr>
        <tr>
          <td align="center"><input id="state" name="state" size="24" placeholder='State'></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">Post Code: <font color="red">*</font></td>
        </tr>
        <tr>
          <td align="center"><input id="postcode" name="postcode" size="24" placeholder='Post Code' OnChange='CheckInt("postcode");'></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">Country</td>
        </tr>
        <tr>
          <td align="center"><input id="country" name="country" size="24" placeholder='Country'></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">Home Phone:</td>
        </tr>
        <tr>
          <td align="center"><input type="text" name="homephone" id="homephone" value="" size="24" onChange='CheckInt("homephone")' maxlength="10" placeholder='0300000000 format' onfocusout="formatHome()"/></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">Mobile Phone: <font color="red">*</font></td>
        </tr>
        <tr>
          <td align="center"><input type="text" name="mobilephone" id="mobilephone" value="" size="24" maxlength="10" placeholder='0400000000 format' required onfocusout="formatMobile()" onChange='CheckInt("mobilephone")'></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">Email Address: <font color="red">*</font></td>
        </tr>
        <tr>
          <td align="center"><input type="text" name="email" id="email" value="" size="24" placeholder='Email Address' required></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left" style="width : 80px;">Date of Birth: <font color="red">*</font></td>
        </tr>
        <tr>
          <td align="center"><input type="text" name="dob" id="dob" value="" size="24" placeholder='Date of Birth (yyyy-mm-dd)' required /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">Gender:</td>
        </tr>
        <tr>
          <td align="left">
            <input type="radio" id="male" name="gender" value="male" checked="checked">
            <label for="html">Male</label><br>
            <input type="radio" id="female" name="gender" value="female">
            <label for="css">Female</label><br>
            <input type="radio" id="nonbinary" name="gender" value="nonbinary">
            <label for="javascript">Non-binary</label></br>
            <input type="radio" id="notsay" name="gender" value="notsay">
            <label for="javascript">Prefer not to say</label><br>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">Occupation:</td>
        </tr>
        <tr>
          <td align="center"><input type="text" name="occupation" value="" size="24" placeholder='Occupation' /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">Cue Sport Preference:</td>
        </tr>
        <tr>
          <td align="left">
            <input type="radio" id="snooker" name="cue" value="Snooker">
            <label for="snooker">Snooker</label><br>
            <input type="radio" id="billiards" name="cue" value="Billiards">
            <label for="css">Billiards</label><br>
            <input type="radio" id="both" name="cue" value="Both" checked="checked">
            <label for="both">Both</label><br>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">Would you like to be a VBSA Referee?</td>
        </tr>
        <tr>
          <td align="left">
            <input type="radio" id="yes" name="referee" value="1">
            <label for="yes">Yes</label><br>
            <input type="radio" id="no" name="referee" value="0" checked="checked">
            <label for="no">No</label><br>
          </td>
        </tr>
        <!--<tr>
          <td align="left">
            <input type="radio" id="yes" name="referee" value="1" onclick="$.fn.RefereeDisplay('clicked')">
            <label for="yes">Yes</label><br>
            <input type="radio" id="no" name="referee" value="0" checked="checked" onclick="$.fn.RefereeDisplay('not_clicked')">
            <label for="no">No</label><br>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left" style='display:none'>If yes, what class/'s do you hold? Select all that apply.</td>
        </tr>
        <tr>
          <td align="left">
            <input type="checkbox" id="class1_examiner" name="ref_c1_e" value="class1_examiner" disabled>
            <label for="ref_c1_e"  id="class1_examiner_label" style='color:grey'>Class 1 Examiner</label><br>
            <input type="checkbox" id="class1" name="ref_c1" value="class1" disabled>
            <label for="ref_c1"  id="class1_label" style='color:grey'>Class 1</label><br>
            <input type="checkbox" id="class2" name="ref_c1" value="class2" disabled>
            <label for="ref_c2"  id="class2_label" style='color:grey'>Class 2</label><br>
            <input type="checkbox" id="class2_billiards" name="ref_c2b" value="class2_billiards" disabled>
            <label for="ref_c2b" id="class2_billiards_label" style='color:grey'>Class 2 Billiards</label><br>
            <input type="checkbox" id="class2_snooker" name="ref_c2s" value="class2_snooker" disabled>
            <label for="ref_c2s" id="class2_snooker_label" style='color:grey'>Class 2 Snooker</label><br>
            <input type="checkbox" id="class3" name="ref_c3" value="class3" disabled>
            <label for="ref_c3"  id="class3_label" style='color:grey'>Class 3</label><br>
            <input type="checkbox" id="class3_billiards" name="ref_c3bs" value="class3_billiards"  disabled>
            <label for="ref_c3b" id="class3_billiards_label" style='color:grey'>Class 3 Billiards</label><br>
            <input type="checkbox" id="class3_snooker" name="ref_c3s" value="class3_snooker" disabled>
            <label for="ref_c3s" id="class3_snooker_label"  style='color:grey'>Class 3 Snooker</label>
          </td>
        </tr>-->
        <tr>
          <td align="left">I prefer to receive VBSA communications.</td>
        </tr>
        <tr>
          <td align="left">
            <input type="radio" id="email" name="member_comms" value="Email">
            <label for="email">Email Only</label><br>
            <input type="radio" id="text" name="member_comms" value="SMS">
            <label for="text">Text Message Only</label><br>
            <input type="radio" id="email_text" name="member_comms" value="Both" checked="checked">
            <label for="both">Both</label><br>
          </td>
        </tr>
        <tr>
          <td align="left">
            <input type="radio" id="consent" name="consent" value="1" checked="checked">
            <label for="email">I consent</label><br>
            <input type="radio" id="notconsent" name="consent" value="0">
            <label for="text">I do not consent</label><br>
          </td>
        </tr>
        <tr>
          <td align="justify">to my name, email and mobile telephone number being provided to third parties and for those third parties to send me promotional and marketing information, using my preferred communications method. Consent can be withdrawn at any time.</td>
        </tr>
        <tr>
          <td align="left"><b>Parent or Guardian</b></td>
        </tr>
        <tr>
          <td align="justify">If this form is being completed by a person other than the applicant e.g. parent on behalf of junior, please provide your name, contact phone number and relationship to the applicant.</td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">Name:</td>
        </tr>
        <tr>
          <td align="center"><input type="text" name="parent_name" value="" size="24" placeholder='Name' /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">Phone Number:</td>
        </tr>
        <tr>
          <td align="center"><input id="parent_phone" name="parent_phone" size="24" placeholder='Phone Number' onChange='CheckInt("parent_phone")' /></td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">Relationship to applicant e.g., Parent:</td>
        </tr>
        <tr>
          <td align="center"><input id="relationship" name="relationship" size="24" placeholder='Relationship' /></td>
        </tr>
        <tr>
          <td align="justify">I agree to abide by the <a href="http://tinyurl.com/deevf4v7" >VBSA Rules, By-Laws and Policies.</a> 
      ·         I also agree to abide by all ABSC Documents and Policies that have been adopted and are notified under “GOVERNANCE” on the <a href="https://absc.com.au/governance">ABSC Website.</a> and I accept the personal responsibility of knowing and understanding these VBSA and ABSC requirements as published from time to time.</td>
        </tr>
        <tr>
          <td align="justify">
      I have read and understood and agree to abide by the Australian National Anti-Doping Policy, effective from 1 January 2021, and found on the <a href="http://tinyurl.com/52tv5dya/">Sport Integrity Australia website</a> being the anti-doping policy adopted by The Australian Billiards & Snooker Council and applicable to all members, participants and non-participants</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="center">
            <input type="checkbox" id="accept_vbsa" name="accept_vbsa" value="agree" required onclick="AgreeButtons()">
            <label for="accept_absc">accept and agree <font color="red">*</font></label><br>
          </td>
        </tr>
        <tr style='border-bottom: 1px solid #000;'>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left"><b>Today's Date</b>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= date("Y-m-d") ?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align='center' class="g-recaptcha" data-sitekey="<?= $captcha_site_key ?>"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="center"><input type="submit" name='submit_button_clicked' id='submit_button_clicked' value="Submit Application" disabled/></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<input type="hidden" name="MM_insert" value="address-form" />
</form>
</div>
<?php
define('API_KEY', 'AIzaSyCLFpqIR36lTs2BcWZQptV6amVVWhOjIjM');
define('SITE_KEY', '6LciM6kZAAAAAIYwFjGF1P3p_TAn__u5Wh2yE1QH');
?>
<script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLFpqIR36lTs2BcWZQptV6amVVWhOjIjM&loading=async&callback=initAutocomplete&libraries=places&v=weekly"
  defer
></script>
</body>
</html>
