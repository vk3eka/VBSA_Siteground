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
      $message .= "<p>Membership Registrar</p>";
      $message .= "<p>An application from the VBSA Online Membership Form has been received.</p>";
      $message .= "<p>New Member Name " . $member_name . "</p>";
      $message .= "<p>&nbsp;</p>";
      $message .= "<p>Please access the Admin Portal and check for new members.</p>";
      $message .= "<p>" . date('d/m/Y') . "</p>";
      $message .= "</body></html>";
      //echo($message . "<br>");
      SendemailWithResponse_Members($subject, $message, $email);    
    }
}

function NewMemberEmail($email, $firstname)
{
    $subject = 'VBSA New Online Membership Application'; 
    $message = '<html><body>';
    //$message .= "<p>" . $firstname . " " . $lastname . "</p>";
    $message .= "<p>Dear " . $firstname . "</p>";
    $message .= "<p>Your application from the VBSA Online Membership Form has been received and will be processed shortly.</p>";
    $message .= "<p>As mentioned in the form, if you are<font color='red'> NOT</font><br>";
    $message .= "·         a VBSA Life Member<br>";
    $message .= "·         an Active VBSA Referee<br>";
    $message .= "·         an Active VBSA Coach<br>";
    $message .= "·         an Active City Club Circuit member<br>";
    $message .= "·         a Junior under 18 years<br>";
    $message .= "·         self-identified as a female, Non-Binary or Not specified (prefer not to say)<br>";
    $message .= "·         and will NOT be participating in the current or pending VBSA pennant season<br>";
    $message .= "a $20 Annual Membership Fee is payable.  Go to the <a href='https://www.vbsa.org.au/vbsa_shop/shop_cart.php#!/VBSA-membership/p/98811788/category=0'>VBSA Shop</a> to pay the fee.</p>";
    $message .= "<p>If you have any questions please reply to this email or email <a href='mailto:members@vbsa.org.au'>members@vbsa.org.au.</a></p>"; 
    $message .= "<p>Thank you for your interest in the VBSA.</p>";
    $message .= "<p>Membership Co-Ordinator.</p>";
    $message .= "<p>" . date('d/m/Y') . "</p>";
    $message .= "</body></html>";
    //echo($message . "<br>");
    SendemailWithResponse_Members($subject, $message, $email);
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
    //echo("bot " . $result . "<br>");
    if($result['success'] == 1)
    {
      echo("You are a bot<br>");
      //return false;
    }
    else
    {
      echo("Thank You<br>");
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
    
    /*
    if($_POST['country'] != 'Australia')
    {
      $overseas = 1;
    }
    else
    {
      $overseas = 0;
    }
    */

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
/*
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
*/
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
    $other_person = $_POST['parent_name'] . ", " . $_POST['parent_phone'] . ", " . $_POST['relationship'];

    if($_POST['how_hear'] == 'Other')
    {
      $other_txt = $_POST['other_txt'];
    }
    else
    {
      $other_txt = $_POST['how_hear'];
    }

    if(isset($_POST['pennant']))
    {
      //echo("Pennant is checked<br>");
      $pennant = 1;
    }
    else
    {
      $pennant = 0;
    }
    if(isset($_POST['cc_pennant']))
    {
      //echo("CC Pennant is checked<br>");
      $cc_pennant = 1;
    }
    else
    {
      $cc_pennant = 0;
    }
    if(isset($_POST['tournament']))
    {
      //echo("Tournament is checked<br>");
      $tournament = 1;
    }
    else
    {
      $tournament = 0;
    }
    
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
    $insertSQL = sprintf("Insert INTO tbl_membership_online (LastName, FirstName, HomeAddress, HomeSuburb, HomeState, HomePostcode, Country, HomePhone, MobilePhone, ReceiveSMS, Email, ReceiveEmail, promo_optin_out, promo_pref, memb_occupation, entered_on, cue, Gender, Overseas, Referee, Ref_Class, dob_day, dob_mnth, dob_year, new_renewal, reason, pennant, cc_pennant, tournament, other_person, how_hear, Junior) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
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
                    GetSQLValueString($gender, "text"),
                    GetSQLValueString($_POST['country'], "text"),
                    GetSQLValueString($_POST['referee'], "integer"),
                    GetSQLValueString($ref_class, "text"),
                    $dob_day,
                    $dob_mnth,
                    $dob_year,
                    GetSQLValueString($_POST['member'], "text"),
                    GetSQLValueString($_POST['reason_membership'], "text"),
                    GetSQLValueString($pennant, "integer"),
                    GetSQLValueString($cc_pennant, "integer"),
                    GetSQLValueString($tournament, "integer"),
                    GetSQLValueString($other_person, "text"),
                    GetSQLValueString($other_txt, "text"),
                    GetSQLValueString($junior, "text"));
    mysql_select_db($database_connvbsa, $connvbsa);
    //echo($insertSQL . "<br>");
    $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());
    $insertGoTo = "https://vbsa.org.au";
    //$insertGoTo = "http://172.16.10.32/VBSA_Siteground";

    echo("<script>");
    
    ScoreRegistrarEmail("members@vbsa.org.au", ($_POST['firstname'] . " " . $_POST['surname']));
    
    if($receive_email == 1)
    {
      echo("alert('Your application has been received, and we have sent you an email confirming this, if you have any questions please email members@vbsa.org.au');");
      NewMemberEmail($_POST['email'],  $_POST['firstname']);
    }
    else
    {
      echo("alert('Your application has been received, if you have any questions please email members@vbsa.org.au');");
    }
    
    //echo("window.location.href = '" . $insertGoTo . "';");
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

/*  
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
*/
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
          <td align="left">·&nbsp;&nbsp;A current board member or assistant to the Board of the VBSA.</td>
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
          <td>&nbsp;</td>
        </tr>
         </tr>
          <td align="left"><i>Please address all correspondence via email to <a href = "mailto:members@vbsa.org.au">members@vbsa.org.au</a></i></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
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
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="left">How did you come to hear about the VBSA?</td>
        </tr>
        <tr>
          <td>
            <input type="radio" id="local" name="how_hear" value="Local Club">
            <label for="local">Local Club</label><br>
            <input type="radio" id="social" name="how_hear" value="Social Media">
            <label for="social">Social Media</label><br>
            <input type="radio" id="internet" name="how_hear" value="Internet Search">
            <label for="internet">Internet Search</label><br>
            <input type="radio" id="freinds" name="how_hear" value="Friends Relatives">
            <label for="freinds">Friends/Relatives</label><br>
            <input type="radio" id="word" name="how_hear" value="Word of Mouth">
            <label for="word">Word of Mouth</label><br>
            <input type="radio" id="other" name="how_hear" value="Other">
            <label for="other">Other, please specify&nbsp;</label>
            <input type="text" id="other_txt" name="other_txt" value="">
          </td>
        </tr>
         <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">Reason for membership application:</td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">Select all that apply</td>
        </tr>
        <td align="left">
            <input type="checkbox" id="pennant" name="pennant" value="pennant">
            <!--<input type="radio" id="pennant" name="reason_membership" value="Pennant" checked="checked">-->
            <label for="pennant">VBSA Pennant</label><br>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">·&nbsp;&nbsp;No membership fee required</td>
        </tr>
        <tr>
          <td align="left">
            <input type="checkbox" id="cc_pennant" name="cc_pennant" value="cc_pennant">
            <!--<input type="radio" id="cc_pennant" name="reason_membership" value="CityClub">-->
            <label for="cc_pennant">City Club Curcuit Pennant</label><br>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">·&nbsp;&nbsp;No membership fee required</td>
        </tr>
        <tr>
          <td align="left">
            <input type="checkbox" id="tournament" name="tournament" value="tournament">
            <!--<input type="radio" id="tournament" name="reason_membership" value="Tournament">-->
            <label for="tournament">Tournament & Other</label><br>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="left">·&nbsp;&nbsp;Membership fee may be required.  See above.</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
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
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>-->
        <tr>
          <td nowrap="nowrap" align="left">State: <font color="red">*</font></td>
        </tr>
        <tr>
          <td align="center">
            <select d="state" name="state" style="width: 185px; height: 25px;">
              <option value="">Select State</option>
              <option value="Vic">Victoria</option>
              <option value="NSW">New South Wales</option>
              <option value="Qld">Queensland</option>
              <option value="SA">South Australia</option>
              <option value="Tas">Tasmania</option>
              <option value="NT">Northern Territory</option>
              <option value="ACT">Australian Capital Territiory</option>
            </select>
            <!--<input id="state" name="state" size="24" placeholder='State'>-->
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
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
          <td align='center'>
            <select id="country" name="country" style="width: 185px; height: 25px;">
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
        </tr>
        <!--<tr>
          <td align="center"><input id="country" name="country" size="24" placeholder='Country'></td>
        </tr>-->
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
            <label for="css">Female. Free membership.</label><br>
            <input type="radio" id="nonbinary" name="gender" value="nonbinary">
            <label for="javascript">Non-binary. Free membership.</label></br>
            <input type="radio" id="notsay" name="gender" value="notsay">
            <label for="javascript">Prefer not to say. Free membership.</label><br>
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
//define('API_KEY', 'AIzaSyCLFpqIR36lTs2BcWZQptV6amVVWhOjIjM');
//define('SITE_KEY', '6LciM6kZAAAAAIYwFjGF1P3p_TAn__u5Wh2yE1QH');
?>
<script>
  /*
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLFpqIR36lTs2BcWZQptV6amVVWhOjIjM&loading=async&callback=initAutocomplete&libraries=places&v=weekly"
  defer
  */
</script>
</body>
</html>
