<html>
  <head>
    <title>reCAPTCHA demo: Explicit render for multiple widgets</title>
    <script type="text/javascript">
      var verifyCallback = function(response) {
        if(response != '')
        {
          alert(response);
        }
        
      };
      var widgetId1;
      var onloadCallback = function() {
        // Renders the HTML element with id 'example1' as a reCAPTCHA widget.
        // The id of the reCAPTCHA widget is assigned to 'widgetId1'.
        widgetId1 = grecaptcha.render('example1', {
          'sitekey' : '6LcwbLIfAAAAADdiXBDCs2IGxxEq3bU8TmRVd0Nm',
          'theme' : 'light'
        });
      };
    </script>
  </head>
  <body>

<?php

$captcha_site_key = "6LcwbLIfAAAAADdiXBDCs2IGxxEq3bU8TmRVd0Nm";
$captcha_secret_key = "6LcwbLIfAAAAAFyE15OfFXSOy5i44rXMcwwuhUQ5";
$captcha_url = "https://www.google.com/recaptcha/api/siteverify";

function Captcha_Submit()
{
  $response = $_POST['g-recaptcha-response'];
  $payload = file_get_contents($captcha_url . '?secret=' . $captcha_secret_key . '&response=' . $response);
  $result = json_decode($payload, TRUE);
  if($result['success'] != 1)
  {
    //echo("You are a bot");
    return false;
  }
  else
  {
    //echo("Thank You");
    return true;
  }
}

echo("Captcha - " . Captcha_Submit() . "<br>");

?>


    <!-- The g-recaptcha-response string displays in an alert message upon submit. -->
    <form action="javascript:alert(grecaptcha.getResponse(widgetId1));">
      <div id="example1"></div>
      <br>
      <input type="submit" value="getResponse">
    </form>
    <br>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script>
  </body>
</html>