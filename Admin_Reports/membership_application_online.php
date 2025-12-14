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
    if($member_name != "!S!WCRTESTINPUT000001!E! !S!WCRTESTINPUT000000!E!")
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
    $response = $_POST['g-recaptcha-response'];
    $payload = file_get_contents($captcha_url . '?secret=' . $captcha_secret_key . '&response=' . $response);
    $result = json_decode($payload, TRUE);
    if($result['success'] == 1)
    {
      echo("You are a bot");
      //return false;
    }
    else
    {
      echo("Thank You");
      //return true;
    }

    $ref_class = '';
    
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

    $gender = 'Unknown';

    if(isset($_POST['gender']) && ($_POST['gender'] == 'male'))
    {
      $gender = 'Male';
    }
    /*else
    {
      $gender = 'Unknown';
    }*/
    if(isset($_POST['gender']) && ($_POST['gender'] == 'female'))
    {
      $gender = 'Female';
    }
    /*else
    {
      $gender = 'Unknown';
    }*/
    if(isset($_POST['gender']) && ($_POST['gender'] == 'nonbinary'))
    {
      $gender = 'NonBinary';
    }
    /*else
    {
      $gender = 'Unknown';
    }*/
    if(isset($_POST['gender']) && ($_POST['gender'] == 'notsay'))
    {
      $gender = 'NoGender';
    }
    /*else
    {
      $gender = 'Unknown';
    }*/
    if($_POST['country'] != 'Australia')
    {
      $overseas = 1;
    }
    else
    {
      $overseas = 0;
    }

    /*
    Australian Capital Territory (ACT)  2600 to 2618 and 29##
    New South Wales (NSW)               2###
    Northern Territory (NT)             08## and 09##
    Queensland (QLD)                    4###
    South Australia (SA)                5###
    Tasmania (TAS)                      7###
    Victoria (VIC)                      3###
    Western Australia (WA)              6###
    */

    if((($_POST['postcode'] >= 2600 ) && ($_POST['postcode'] <= 2618)) || ((substr($_POST['postcode'], 0,2) >= 29) && (substr($_POST['postcode'], 0,2) < 30)))
    {
      $state = 'ACT';
    }
    else
    {
      $state_code = substr($_POST['postcode'], 0,1);
      switch($state_code)
      {
        case 0:
            $state = 'NT';
            break;
        case 2:
            $state = 'NSW';
            break;
        case 3:
            $state = 'Vic';
            break;
        case 4:
            $state = 'Qld';
            break;
        case 5:
            $state = 'SA';
            break;
        case 6:
            $state = 'WA';
            break;
        case 7:
            $state = 'Tas';
            break;
        default:
          $state = 'Not Available';
      }
    }
    //echo($state . "<br>");

/*
    $datevalue = strtotime($_POST['dob']);
    $dob_year = date('Y', $datevalue);
    $dob_mnth = date('m', $datevalue);
    $dob_day = date('d', $datevalue);
*/

    $dob_year = $_POST['dob_year'];
    $dob_mnth = $_POST['dob_month'];
    $dob_day = $_POST['dob_day'];

    $todaysdate = date('Y-m-d H:i:s');
    //echo($todaysdate . "<br>");
    $other_person = "'" . $_POST['parent_name'] . ", " . $_POST['parent_phone'] . ", " . $_POST['relationship'] . "'";

    $insertSQL = sprintf("Insert INTO tbl_membership_online (LastName, FirstName, HomeAddress, HomeSuburb, HomeState, HomePostcode, Country, HomePhone, MobilePhone, ReceiveSMS, Email, ReceiveEmail, promo_optin_out, promo_pref, memb_occupation, entered_on, cue, Gender, Overseas, Referee, Ref_Class, dob_day, dob_mnth, dob_year, new_renewal, reason, other_person) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                    GetSQLValueString($_POST['surname'], "text"),
                    GetSQLValueString($_POST['firstname'], "text"),
                    $address,
                    GetSQLValueString($_POST['locality'], "text"),
                    GetSQLValueString($state, "text"),
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
                    GetSQLValueString($gender, "text"),
                    $overseas,
                    GetSQLValueString($_POST['referee'], "integer"),
                    GetSQLValueString($ref_class, "text"),
                    $dob_day,
                    $dob_mnth,
                    $dob_year,
                    GetSQLValueString($_POST['member'], "text"),
                    GetSQLValueString($_POST['reason_membership'], "text"),
                    GetSQLValueString($other_person, "text"));
    mysql_select_db($database_connvbsa, $connvbsa);
    //echo($insertSQL . "<br>");
    $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());
    //$insertGoTo = "http://vbsa.cpc-world.com";
    $insertGoTo = "https://vbsa.org.au";

    echo("<script>");
    echo("alert('Your application has been received, and we have sent you an email confirming this, if you have any questions please email scores@vbsa.org.au');");
    ScoreRegistrarEmail("scores@vbsa.org.au", $_POST['firstname'] . " " . $_POST['surname']);
    if($receive_email == 1)
    {
      NewMemberEmail($_POST['email'],  $_POST['firstname'] . " " . $_POST['surname']);
    }
    echo("window.location.href = '" . $insertGoTo . "';");
    echo("</script>");
}
  //else
  //{
  //    echo("The captcha was unsuccessful, are you are a bot");
  //}
//}
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
$(document).ready(function()
{
  $('#submit_button_clicked').on('click', function()
  {
    /*
    var response = grecaptcha.getResponse();
    if(response.length == 0)
    {
      alert('Please verify that you are not a robot');
      return false;
    }
    */
  });

  $.fn.display = function () 
  {
      $('#submit_button_clicked').prop("disabled", true);
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
  else if(document.getElementById('accept_vbsa').checked == false)
  {
    document.getElementById('submit_button_clicked').disabled = true;
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
  //address1Field.focus();
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
<form action="<?php echo $editFormAction; ?>" method="post" name="address-form" id="address-form" autocomplete="off" class='recaptchaForm' onsubmit="$.fn.display()" >
  
  <input type='hidden' id="ship-address" name="ship-address"/>
  <input type='hidden' id="address2" name="address2"/>
  <input type='hidden' id="state" name="state"/>
  <input type='hidden' id="locality" name="locality"/>
  
  <table width="400" border="0" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td>
      <table class='table dt-responsive display' border=1>
         <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="left">If you <font color="red">ARE NOT</font> any of the following, you must pay a $20 annual membership fee via the  <a href='https://www.vbsa.org.au/vbsa_shop/shop_cart.php#!/VBSA-membership/p/98811788/category=0' target='new' >VBSA Shop</a></td>
        </tr>
        <tr>
          <td align="left">·&nbsp;&nbsp;Life Member</td>
        </tr>
        </tr>
          <td align="left">·&nbsp;&nbsp;Active referee</td>
        </tr>
        <tr>
          <td align="left">·&nbsp;&nbsp;Active coach</td>
        </tr>
        <tr>
          <td align="left">·&nbsp;&nbsp;Active City Club Circuit member during the current competition year</td>
        </tr>
        <tr>
          <td align="left">·&nbsp;&nbsp;Junior (age 18 or under)</td>
        </tr>
        <tr>
          <td align="left">·&nbsp;&nbsp;Identify as a female, Non-Binary or Not specified (prefer not to say)</td>
        </tr>
        <tr>
          <td align="left">·&nbsp;&nbsp;Played at least 1 VBSA pennant match in the calendar year preceding the date of membership OR will be playing in the current season.</td>
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
          <td nowrap="nowrap" align="left">Reason for membership application:</td>
        </tr>
        <td align="left">
            <input type="radio" id="pennant" name="reason_membership" value="Pennant" checked="checked">
            <label for="pennant">Pennant</label><br>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">·&nbsp;&nbsp;Pennant. No membership fee required</td>
        </tr>
        <tr>
          <td align="left">
            <input type="radio" id="tournament" name="reason_membership" value="Tournament">
            <label for="tournament">Tournament & Other</label><br>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">·&nbsp;&nbsp;Tournament or Other.  Membership fee may be required.  See above.</td>
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
        <!--<tr>
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
          <td nowrap="nowrap" align="left">Suburb:</td>
          <td nowrap="nowrap" align="left">Suburb: <font color="red">*</font></td>
        </tr>
        <tr>
          <td align="center"><input id="locality" name="locality" size="24" placeholder='Suburb'></td>
        </tr>-->
        <tr>
          <td>&nbsp;</td>
        </tr>
        <!--<tr>
          <td nowrap="nowrap" align="left">State: <font color="red">*</font></td>
        </tr>
        <tr>
          <td align="center"><input id="state" name="state" size="24" placeholder='State'></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>-->
        <tr>
          <td nowrap="nowrap" align="left">Post Code: <font color="red">*</font>&nbsp;&nbsp;&nbsp;(Use 9999 if International)</td>
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
          <td align='center'>
            <select name="dob_day" id="dob_day" required>
              <?php
              echo("<option value=''>Day</option>");
              for($i = 1; $i <= 31; $i++)
              {
                echo("<option value=" . $i . ">" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>");
              }
              ?>
            </select>
            <select name="dob_month" id="dob_month" required>
              <?php
              echo("<option value=''>Month</option>");
              echo("<option value=1>Jan</option>");
              echo("<option value=2>Feb</option>");
              echo("<option value=3>Mar</option>");
              echo("<option value=4>Apr</option>");
              echo("<option value=5>May</option>");
              echo("<option value=6>Jun</option>");
              echo("<option value=7>July</option>");
              echo("<option value=8>Aug</option>");
              echo("<option value=9>Sept</option>");
              echo("<option value=10>Oct</option>");
              echo("<option value=11>Nov</option>");
              echo("<option value=12>Dec</option>");
              ?>
            </select>
            <select name="dob_year" id="dob_year" required>
              <?php
              echo("<option value=''>Year</option>");
              for($i = 1940; ($i < date('Y')); $i++)
              {
                echo("<option value=" . $i . ">" . $i . "</option>");
              }
              ?>
            </select>
          </td>
        </tr>
<!--
        <tr>
          <td align="center"><input type="text" name="dob" id="dob" value="" size="24" placeholder='Date of Birth (yyyy-mm-dd)' required /></td>
        </tr>
-->
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
        <tr>
          <td>Please do not refresh page until application process has completed.</td>
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
