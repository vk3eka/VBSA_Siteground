<?php
include("vbsa_online_scores/connection.inc");
include("vbsa_online_scores/header.php");
//include("vbsa_online_scores/php_functions.php");

/** Include PHPMailer */

require_once(__DIR__ . '/vbsa_online_scores/vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vbsa_online_scores/vendor/PHPMailer/src/Exception.php';
require __DIR__ . '/vbsa_online_scores/vendor/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/vbsa_online_scores/vendor/PHPMailer/src/SMTP.php';

$email = 'vk3eka@bigpond.net.au';

function Sendemail($subject, $message, $email)
{
    $mail = new PHPMailer();
    $today = getdate();
    $month = $today['mon'];
    $mday = $today['mday'];
    $year = $today['year'];
    $hours = $today['hours'];
    $mins = $today['minutes'];
    $secs = $today['seconds'];

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    $mail->Username = 'ddplorg@gmail.com'; 
    $mail->Password = 'nxfewoofdnstumzk';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->SMTPDebug = 2;
    $mail->setFrom('ddplorg@gmail.com', 'Database Admin');
    $mail->addReplyTo('noreply@gmail.com', 'No Reply');

    $email = explode(',', $email);
    foreach($email as $address)
    {
        $mail->addAddress($address);
    }
    $mail->addAddress('vk3eka@yahoo.com'); 
    $mail->Subject = $subject;
    $mail->isHTML(true);
    $mailContent = $message;
    $mail->Body = $mailContent;
   
    if($mail->send()){
        echo "<script type='text/javascript'>";
        echo "alert('Mail Sent!')";
        echo "</script>";
    }
    else
    {
        echo "<script type='text/javascript'>";
        echo "alert('Mail not sent, Error: " . $mail->ErrorInfo . "')";
        echo "</script>";
    }
}

function AuthoriseEmail($access, $firstname, $rego_id, $password, $email)
{
    $subject = 'VBSA Portal Access';  
    $message = '<html><body>';
    $message .= "<p>" . $firstname . "</p>";
    $message .= "<p>Here are your log-in details. You have been granted " . $access . " access to the database.</p>";
    $message .= "<p>Username " . $rego_id . "</p>";
    $message .= "<p>Password " . $password . "</p>";
    $message .= "<p>Please access the database and change your password.</p>";
    $message .= "<p><a href='https://www.vbsa.org.au/'>Click here to access the database portal.</a></p>";
    $message .= "<p>Thanks.</p>";
    $message .= "<p>VBSA Secretary.</p>";
    $message .= "<p>&nbsp;</p>";
    $message .= "<p><i>NOTE: Please do not reply to this email as this email address is not monitored.</i></p>";
    $message .= "<p><i>Direct all emails to <a href='mailto:secretary@vbsa.org.au'>secretary@vbsa.org.au</a></i></p>";
    $message .= "</body></html>";
    Sendemail($subject, $message, $email);
}

if ($_POST['ButtonName'] == "Send")
{
    AuthoriseEmail('Club Administrator', 'Peter', 4, 'test', 'vk3eka@yahoo.com');
    /*
    $subject = "Test Player Notification - phpMailer";
    $message = '<html><body>';
	$message = "<p>The following player has been listed as a Test Player:</p>";
	$message .= "<p>Player Name: Peter Johnson</p>";
	$message .= "<p>New Rego ID:            2009</p>";
	$message .= "<p>Club:   Wonthaggi</p>";
	$message .= "<p>Division:   Premier</p>";
	$message .= "<p>Round: 10</p>";
    $message .= "</body></html>";
	Sendemail($subject, $message, $email);
    */
}

?>
<script type="text/javascript">

function SendButton() {
document.players.ButtonName.value = "Send"; 
document.players.submit();
}

</script>
<center>
<form name="players" method="post" action="TestMailer.php">
<input type="hidden" name="ButtonName" />
<table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
  	<tr>
    	<td colspan=2 align=center><b>Test Player Mail</b></td>
	</tr>
	<tr>
        <td colspan=2 align=center>&nbsp;</td>
        </tr>
</table>

<div class="horizontal-centering">
<div>
<div>
<ul id="nav" class="dropdown dropdown-horizontal">
<li><a class='btn btn-primary btn-xs' href="javascript:;" onclick="SendButton();">Send Test Mail</a></li>
</ul>
</div>
</div>
</div>
</form>
</center>
<?php
include("footer.php"); 
?>
